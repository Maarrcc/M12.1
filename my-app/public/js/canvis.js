document.addEventListener('DOMContentLoaded', function() {
    // Verificar si estamos en la página de canvis
    if (!document.getElementById('selector-curs')) {
        return; // Si no estamos en la página correcta, no ejecutar el código
    }

    const cursSelect = document.getElementById('selector-curs');
    const diaSelect = document.getElementById('dia');
    const idHorariSelect = document.getElementById('id_horari');
    const horariSelector = document.getElementById('horari_selector');
    const idCursInput = document.getElementById('id_curs');
    const tipusCanviSelect = document.getElementById('tipus_canvi');
    const professorGroup = document.getElementById('professor_group');
    const aulaGroup = document.getElementById('aula_group');

    // Obtener todos los campos del formulario excepto el selector de curso
    const formElements = [
        diaSelect,
        tipusCanviSelect,
        document.getElementById('data_canvi'),
        document.getElementById('data_fi'),
        document.getElementById('id_professor_substitut'),
        document.getElementById('id_aula_substituta'),
        document.getElementById('descripcio_canvi')
    ];

    // Deshabilitar inicialmente todos los campos excepto el selector de curso
    formElements.forEach(element => {
        if (element) {
            element.disabled = true;
        }
    });

    // Event listener para cambio de curso
    cursSelect.addEventListener('change', function() {
        const cursId = this.value;
        diaSelect.value = '';
        idHorariSelect.innerHTML = '<option value="">Primer selecciona un dia</option>';
        horariSelector.style.display = 'none';
        idHorariSelect.disabled = true;
        
        formElements.forEach(element => {
            if (element) {
                element.disabled = !cursId;
            }
        });
        
        if (cursId) {
            idCursInput.value = cursId;
            diaSelect.disabled = false;
        }
    });

    // Event listener para cambio de día
    diaSelect.addEventListener('change', function() {
        const dia = diaSelect.value;
        const cursId = cursSelect.value;

        if (dia && cursId) {
            fetch(`/M12.1/my-app/public/index.php?controller=canvis&action=getHorarisByCurs&curs=${cursId}&dia=${dia}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error en la respuesta del servidor');
                }
                return response.json();
            })
            .then(responseData => {
                if (!responseData.success) {
                    throw new Error(responseData.message || 'Error desconocido');
                }
                const horaris = responseData.data;
                idHorariSelect.innerHTML = '<option value="">Selecciona un horari</option>';
                horaris.forEach(horari => {
                    const option = document.createElement('option');
                    option.value = horari.id_horari;
                    option.dataset.idCurs = horari.id_curs;
                    option.textContent = `${horari.hora_inici} - ${horari.hora_fi} | ${horari.assignatura} - ${horari.professor} - ${horari.nom_aula}`;
                    idHorariSelect.appendChild(option);
                });
                horariSelector.style.display = 'block';
                idHorariSelect.disabled = false;
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al cargar los horarios');
            });
        } else {
            horariSelector.style.display = 'none';
            idHorariSelect.disabled = true;
        }
    });

    // Gestionar campos según tipo de cambio
    tipusCanviSelect.addEventListener('change', function() {
        const tipusCanvi = this.value;
        const professorSubstitutGroup = professorGroup;
        const aulaSubstitutaGroup = aulaGroup;
        
        // Ocultar todos los campos opcionales
        professorSubstitutGroup.style.display = 'none';
        aulaSubstitutaGroup.style.display = 'none';
        
        // Restablecer required y disabled
        const professorSubstitutSelect = document.getElementById('id_professor_substitut');
        const aulaSubstitutaSelect = document.getElementById('id_aula_substituta');
        
        professorSubstitutSelect.required = false;
        aulaSubstitutaSelect.required = false;
        
        // Mostrar campos según el tipo de cambio
        if (tipusCanvi === 'Canvi professor') {
            professorSubstitutGroup.style.display = 'block';
            professorSubstitutSelect.required = true;
        } else if (tipusCanvi === 'Canvi aula') {
            aulaSubstitutaGroup.style.display = 'block';
            aulaSubstitutaSelect.required = true;
        }
    });
});