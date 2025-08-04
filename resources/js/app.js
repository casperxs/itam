import './bootstrap';

// Dark Mode Toggle
document.addEventListener('DOMContentLoaded', function() {
    const toggleButton = document.getElementById('darkModeToggle');
    const darkModeIcon = document.getElementById('darkModeIcon');
    const lightModeIcon = document.getElementById('lightModeIcon');
    
    if (toggleButton) {
        toggleButton.addEventListener('click', function() {
            fetch('/dark-mode/toggle', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Toggle html class
                    const html = document.documentElement;
                    if (data.dark_mode) {
                        html.classList.add('dark');
                        darkModeIcon.classList.add('hidden');
                        lightModeIcon.classList.remove('hidden');
                    } else {
                        html.classList.remove('dark');
                        darkModeIcon.classList.remove('hidden');
                        lightModeIcon.classList.add('hidden');
                    }
                }
            })
            .catch(error => {
                console.error('Error toggling dark mode:', error);
            });
        });
    }
});
