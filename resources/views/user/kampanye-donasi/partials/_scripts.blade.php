    <!-- Script Smooth Scroll listener -->
    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('scroll-to-form', () => {
                const target = document.getElementById('formulir-donatur');
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth' });
                }
            });
        });
    </script>