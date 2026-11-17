<?php

namespace Database\Factories;

use App\Models\BeritaKegiatan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<BeritaKegiatan>
 */
class BeritaKegiatanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'judul' => fake()->sentence(),
            'slug' => fake()->unique()->slug(),
            'kategori' => 'Pendidikan',
            'ringkasan' => fake()->sentence(),
            'konten' => '<p>Isi berita pengujian.</p>',
            'tanggal' => today(),
            'status' => 'tayang',
        ];
    }
}
