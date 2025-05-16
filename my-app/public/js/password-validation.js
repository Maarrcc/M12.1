document.addEventListener('DOMContentLoaded', function() {
    const passwordInput = document.getElementById('contrasenya');
    const requirements = {
        length: 8,
        uppercase: /[A-Z]/,
        lowercase: /[a-z]/,
        number: /[0-9]/,
        special: /[^A-Za-z0-9]/
    };

    function validatePassword(password) {
        const checks = {
            length: password.length >= requirements.length,
            uppercase: requirements.uppercase.test(password),
            lowercase: requirements.lowercase.test(password),
            number: requirements.number.test(password),
            special: requirements.special.test(password)
        };

        return checks;
    }

    passwordInput.addEventListener('input', function() {
        const password = this.value;
        const checks = validatePassword(password);
        
        // Actualizar mensaje de validación
        let mensaje = '';
        if (!checks.length) mensaje += '- Mínim 8 caràcters<br>';
        if (!checks.uppercase) mensaje += '- Una lletra majúscula<br>';
        if (!checks.lowercase) mensaje += '- Una lletra minúscula<br>';
        if (!checks.number) mensaje += '- Un número<br>';
        if (!checks.special) mensaje += '- Un caràcter especial<br>';

        const feedbackDiv = document.getElementById('password-feedback');
        if (mensaje) {
            feedbackDiv.innerHTML = '<div class="password-requirements">Requisits pendents:<br>' + mensaje + '</div>';
        } else {
            feedbackDiv.innerHTML = '<div class="password-valid">Contrasenya vàlida</div>';
        }
    });
});