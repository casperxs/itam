import './bootstrap';

// Dark Mode Toggle - Version final optimizada
function initDarkModeToggle() {
    const toggleForm = document.getElementById('darkModeForm');
    const toggleButton = document.getElementById('darkModeToggle');
    const darkModeIcon = document.getElementById('darkModeIcon');
    const lightModeIcon = document.getElementById('lightModeIcon');
    
    if (toggleForm && toggleButton && darkModeIcon && lightModeIcon) {
        toggleForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Usar FormData para enviar exactamente como un formulario normal
            const formData = new FormData(toggleForm);
            
            fetch(toggleForm.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin'
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Toggle html class inmediatamente
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
                // Si AJAX falla, usar el formulario normal (con recarga)
                console.log('Using form fallback');
                toggleForm.submit();
            });
        });
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
