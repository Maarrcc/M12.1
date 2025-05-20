document.addEventListener('DOMContentLoaded', function() {
    const inputContrasenya = document.getElementById('contrasenya');
    const requisits = {
        longitud: 8,
        majuscula: /[A-Z]/,
        minuscula: /[a-z]/,
        numero: /[0-9]/,
        especial: /[^A-Za-z0-9]/
    };

    /**
     * Valida una contrasenya segons els requisits
     * 
     * @param {string} contrasenya La contrasenya a validar
     * @returns {object} Un objecte amb les comprovacions realitzades
     */
    function validarContrasenya(contrasenya) {
        const comprovacions = {
            longitud: contrasenya.length >= requisits.longitud,
            majuscula: requisits.majuscula.test(contrasenya),
            minuscula: requisits.minuscula.test(contrasenya),
            numero: requisits.numero.test(contrasenya),
            especial: requisits.especial.test(contrasenya)
        };

        return comprovacions;
    }

    inputContrasenya.addEventListener('input', function() {
        const contrasenya = this.value;
        const comprovacions = validarContrasenya(contrasenya);
        
        // Actualitzar missatge de validació
        let missatge = '';
        if (!comprovacions.longitud) missatge += '- Mínim 8 caràcters<br>';
        if (!comprovacions.majuscula) missatge += '- Una lletra majúscula<br>';
        if (!comprovacions.minuscula) missatge += '- Una lletra minúscula<br>';
        if (!comprovacions.numero) missatge += '- Un número<br>';
        if (!comprovacions.especial) missatge += '- Un caràcter especial<br>';

        const divFeedback = document.getElementById('password-feedback');
        if (missatge) {
            divFeedback.innerHTML = '<div class="requisits-contrasenya">Requisits pendents:<br>' + missatge + '</div>';
        } else {
            divFeedback.innerHTML = '<div class="contrasenya-valida">Contrasenya vàlida</div>';
        }
    });
});

