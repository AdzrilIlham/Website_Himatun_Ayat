<?php

namespace App\Providers;

use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use App\Http\Controllers\Admin\TwoFactorChallengeController;
use App\Http\Responses\GenericFailedPasswordResetLinkRequestResponse;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Laravel\Fortify\Contracts\FailedPasswordResetLinkRequestResponse as FailedPasswordResetLinkRequestResponseContract;
use Laravel\Fortify\Fortify;
use Laravel\Fortify\Http\Controllers\TwoFactorAuthenticatedSessionController;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Bind generic response for failed password reset link requests to prevent account enumeration
        $this->app->singleton(
            FailedPasswordResetLinkRequestResponseContract::class,
            GenericFailedPasswordResetLinkRequestResponse::class
        );

        // Bind custom TwoFactorAuthenticatedSessionController to support multi-factor login pipeline with Email OTP
        $this->app->bind(
            TwoFactorAuthenticatedSessionController::class,
            TwoFactorChallengeController::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        // Configure default password policy (minimum 8 characters)
        Password::defaults(function () {
            return Password::min(8);
        });

        // Set 7-day Remember Me duration on the session guard (7 * 24 * 60 = 10,080 minutes)
        $this->app->resolving(StatefulGuard::class, function ($guard) {
            if (method_exists($guard, 'setRememberDuration')) {
                $guard->setRememberDuration(7 * 24 * 60);
            }
        });

        // Register custom Fortify view responses matching the application design system
        Fortify::loginView(fn () => view('admin.auth.login'));
        Fortify::twoFactorChallengeView(fn () => view('admin.auth.two-factor-challenge'));
        Fortify::requestPasswordResetLinkView(fn () => view('admin.auth.forgot-password'));
        Fortify::resetPasswordView(fn (Request $request) => view('admin.auth.reset-password', ['request' => $request]));
        Fortify::verifyEmailView(fn () => view('admin.auth.verify-email'));
        Fortify::confirmPasswordView(fn () => view('admin.auth.confirm-password'));

        // Configure login rate limiter: 5 attempts per minute per email + IP
        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())).'|'.$request->ip());

            return Limit::perMinute(5)->by($throttleKey);
        });

        // Configure two-factor rate limiter: 5 attempts per minute
        RateLimiter::for('two-factor', function (Request $request) {
            $key = ($request->session()->get('login.id') ?: 'unknown').'|'.$request->ip();

            return Limit::perMinute(5)->by($key);
        });
    }
}
