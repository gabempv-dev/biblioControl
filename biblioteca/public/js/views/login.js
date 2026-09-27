document.addEventListener('DOMContentLoaded', () => {
    const userInput = document.getElementById('usuario');
    const passInput = document.getElementById('password');
    const userInfo = document.getElementById('user-info');
    const passInfo = document.getElementById('pass-info');
    const togglePasswordBtn = document.getElementById('togglePassword');
    const eyeIcon = document.getElementById('eyeIcon');

    // 1. Mostrar / Ocultar Contraseña
    togglePasswordBtn.addEventListener('click', () => {
        const isPassword = passInput.getAttribute('type') === 'password';
        passInput.setAttribute('type', isPassword ? 'text' : 'password');
        
        eyeIcon.classList.toggle('fa-eye', !isPassword);
        eyeIcon.classList.toggle('fa-eye-slash', isPassword);
    });

    // 2. Limpiar errores al escribir de nuevo
    userInput.addEventListener('input', () => userInfo.classList.add('hide'));
    passInput.addEventListener('input', () => passInfo.classList.add('hide'));

    // 3. Envío del formulario
    document.getElementById('formularioLogin').addEventListener('submit', (e) => {
        e.preventDefault();

        userInfo.classList.add('hide');
        passInfo.classList.add('hide');

        const fd = new FormData(e.target);

        fetch('/login/verificar', {
            method: 'POST',
            body: fd
        })
        .then(res => res.json())
        .then(data => {
            if (data.res) {
                window.location = '/dashboard';
                return;
            }

            if (data.message === 'Usuario incorrecto') {
                userInfo.textContent = data.message;
                userInfo.classList.remove('hide');
            } else {
                passInfo.textContent = data.message;
                passInfo.classList.remove('hide');
            }
        })
        .catch(err => console.error('Error:', err));
    });
});