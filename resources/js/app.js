import './bootstrap';

// Dark Mode Toggle - Version híbrida con respaldo
function initDarkModeToggle() {
    const toggleForm = document.getElementById('darkModeForm');
    const toggleButton = document.getElementById('darkModeToggle');
    const darkModeIcon = document.getElementById('darkModeIcon');
    const lightModeIcon = document.getElementById('lightModeIcon');
    
    console.log('Dark mode toggle init:', {
        toggleForm: !!toggleForm,
        toggleButton: !!toggleButton,
        darkModeIcon: !!darkModeIcon,
        lightModeIcon: !!lightModeIcon
    });
    
    if (toggleForm && toggleButton && darkModeIcon && lightModeIcon) {
        toggleForm.addEventListener('submit', function(e) {
            e.preventDefault();
            console.log('Dark mode form submitted');
            
            // Obtener token CSRF del formulario
            const formData = new FormData(toggleForm);
            const csrfToken = formData.get('_token');
            
            if (!csrfToken) {
                console.error('CSRF token not found in form');
                // Permitir que el formulario se envíe normalmente
                toggleForm.submit();
                return;
            }
            
            fetch('/dark-mode/toggle', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin'
            })
            .then(response => {
                console.log('Response status:', response.status);
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
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
                console.error('AJAX failed, falling back to form submission:', error);
                // Si AJAX falla, enviar el formulario normalmente
                toggleForm.submit();
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
