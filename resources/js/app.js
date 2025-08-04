import './bootstrap';

// Dark Mode Toggle - Version más robusta
function initDarkModeToggle() {
    const toggleButton = document.getElementById('darkModeToggle');
    const darkModeIcon = document.getElementById('darkModeIcon');
    const lightModeIcon = document.getElementById('lightModeIcon');
    
    console.log('Dark mode toggle init:', {
        toggleButton: !!toggleButton,
        darkModeIcon: !!darkModeIcon,
        lightModeIcon: !!lightModeIcon
    });
    
    if (toggleButton && darkModeIcon && lightModeIcon) {
        toggleButton.addEventListener('click', function(e) {
            e.preventDefault();
            console.log('Dark mode toggle clicked');
            
            // Obtener token CSRF
            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            if (!csrfToken) {
                console.error('CSRF token not found');
                return;
            }
            
            fetch('/dark-mode/toggle', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken.getAttribute('content')
                },
                credentials: 'same-origin'
            })
            .then(response => {
                console.log('Response status:', response.status);
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);
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
    } else {
        console.warn('Dark mode toggle elements not found');
    }
}

// Ejecutar cuando el DOM esté listo
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initDarkModeToggle);
} else {
    initDarkModeToggle();
}

// También ejecutar cuando la página se cargue completamente
window.addEventListener('load', initDarkModeToggle);
