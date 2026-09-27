<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - BiblioControl</title>
    <!-- Íconos de Lucide o FontAwesome para el ojo -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="/css/login.css">
</head>
<body>

    <div class="login-container">
        <div class="brand-header">
            <div class="logo-icon"><i class="fa-solid fa-book-bookmark"></i></div>
            <h2>BiblioControl</h2>
            <p class="subtitle">Ingresa a tu cuenta para continuar</p>
        </div>

        <form id="formularioLogin">
            <div class="input-group">
                <label for="usuario">Usuario</label>
                <div class="input-wrapper">
                    <i class="fa-regular fa-envelope input-icon"></i>
                    <input type="text" id="usuario" name="usuario" placeholder="Alors" required>
                </div>
                <span class="error-msg hide" id="user-info">Usuario Incorrecto</span>
            </div>

            <div class="input-group">
                <label for="password">Contraseña</label>
                <div class="input-wrapper">
                    <i class="fa-solid fa-lock input-icon"></i>
                    <input type="password" id="password" name="password" placeholder="••••••••" required>
                    <button type="button" id="togglePassword" class="btn-toggle-pass" aria-label="Mostrar u ocultar contraseña">
                        <i class="fa-regular fa-eye" id="eyeIcon"></i>
                    </button>
                </div>
                <span class="error-msg hide" id="pass-info">Contraseña Incorrecta</span>
            </div>

            <button type="submit" id="btnEnviar">
                <span>Ingresar</span>
                <i class="fa-solid fa-arrow-right-to-bracket"></i>
            </button>
        </form>
    </div>

    <script src="js/views/login.js"></script>
</body>
</html>