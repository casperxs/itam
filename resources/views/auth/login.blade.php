<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>Login - ITAM BKB - {{ now()->timestamp }}</title>
    @vite(['resources/css/app.css'])
    <style>
        /* GUERRA TOTAL CONTRA EL CACHE Y DARK MODE */
        html, body { 
            color-scheme: light !important; 
            background-color: #f9fafb !important;
        }
        * { 
            color-scheme: light !important; 
        }
        .login-container {
            background-color: #ffffff !important;
            border: 1px solid #e5e7eb !important;
            color: #111827 !important;
        }
        .login-title {
            color: #111827 !important;
        }
        .login-subtitle {
            color: #6b7280 !important;
        }
        .login-label {
            color: #111827 !important;
        }
        .login-input {
            background-color: #ffffff !important;
            border: 1px solid #d1d5db !important;
            color: #111827 !important;
        }
        .login-input:focus {
            border-color: #f97316 !important;
            outline: 2px solid #f97316 !important;
            outline-offset: 2px;
        }
        .login-button {
            background-color: #f97316 !important;
            color: #ffffff !important;
        }
        .login-button:hover {
            background-color: #ea580c !important;
        }
        .login-footer {
            color: #6b7280 !important;
        }
        .login-footer strong {
            color: #374151 !important;
        }
        .login-link {
            color: #f97316 !important;
        }
        .login-link:hover {
            color: #ea580c !important;
        }
    </style>
</head>
<body style="background-color: #f9fafb !important; min-height: 100vh; display: flex; align-items: center; justify-content: center; margin: 0; padding: 20px; box-sizing: border-box;">
    <div class="login-container" style="max-width: 28rem; width: 100%; background-color: #ffffff; border-radius: 0.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border: 1px solid #e5e7eb; padding: 2rem;">
        <!-- Header con título ITAM-BKB -->
        <div style="text-align: center; margin-bottom: 2rem;">
            <h1 class="login-title" style="font-size: 1.5rem; font-weight: bold; color: #111827; margin-bottom: 0.5rem;">ITAM-BKB</h1>
            <p class="login-subtitle" style="color: #6b7280; font-size: 0.875rem;">Sistema de Gestión de Activos TI</p>
            <div style="margin-top: 1rem;">
                <img src="{{ asset('images/ITAM_logo.svg') }}" alt="ITAM BKB Logo" style="margin: 0 auto; height: 4rem; width: auto; opacity: 0.8; display: block;">
            </div>
        </div>

        <form method="POST" action="{{ route('login.store') }}">
            @csrf

            <div style="margin-bottom: 1rem;">
                <label for="email" class="login-label" style="display: block; font-size: 0.875rem; font-weight: 500; color: #111827; margin-bottom: 0.5rem;">
                    Usuario
                </label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    class="login-input"
                    style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem; background-color: #ffffff; color: #111827; box-sizing: border-box;"
                    placeholder="usuario@ejemplo.com"
                    required
                >
                @error('email')
                    <p style="color: #ef4444; font-size: 0.875rem; margin-top: 0.25rem;">{{ $message }}</p>
                @enderror
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label for="password" class="login-label" style="display: block; font-size: 0.875rem; font-weight: 500; color: #111827; margin-bottom: 0.5rem;">
                    Contraseña
                </label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    class="login-input"
                    style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem; background-color: #ffffff; color: #111827; box-sizing: border-box;"
                    placeholder="••••••••"
                    required
                >
                @error('password')
                    <p style="color: #ef4444; font-size: 0.875rem; margin-top: 0.25rem;">{{ $message }}</p>
                @enderror
            </div>

            <button
                type="submit"
                class="login-button"
                style="width: 100%; background-color: #f97316; color: #ffffff; padding: 0.625rem 1rem; border-radius: 0.375rem; border: none; font-weight: 500; cursor: pointer; transition: background-color 0.2s;"
            >
                Iniciar Sesión
            </button>
        </form>

        <div class="login-footer" style="margin-top: 2rem; text-align: center; font-size: 0.875rem; color: #6b7280;">
            <p style="font-weight: 500; color: #374151; margin-bottom: 0.25rem;">ITAM-BKB v1.5</p>
            <p style="margin-bottom: 0.25rem;">Soporte IT & Gestión de Activos</p>
            <p style="font-size: 0.75rem;">
                Designed, Hosted & Supported by 
                <a href="https://garey.mx/" target="_blank" class="login-link" style="color: #f97316; font-weight: 500; text-decoration: none;">
                    GAREY AW
                </a>
            </p>
        </div>
    </div>
</body>
</html>
