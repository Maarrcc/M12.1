document.addEventListener('DOMContentLoaded', function() {
    const cursSelect = document.getElementById('selector-curs');
    const diaSelect = document.getElementById('dia');
    const idHorariSelect = document.getElementById('id_horari');
    const horariSelector = document.getElementById('horari_selector');
    const idCursInput = document.getElementById('id_curs');
    const tipusCanviSelect = document.getElementById('tipus_canvi');
    const professorGroup = document.getElementById('professor_group');
    const aulaGroup = document.getElementById('aula_group');

    // Deshabilitar campos inicialmente
    const formElements = [
        diaSelect,
        idHorariSelect,
        tipusCanviSelect,
        document.getElementById('data_canvi'),
        document.getElementById('data_fi'),
        document.getElementById('id_professor_substitut'),
        document.getElementById('id_aula_substituta'),
        document.getElementById('descripcio_canvi')
    ];

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
        const dia = this.value;
        const cursId = cursSelect.value;

        if (!cursId) {
            alert('Si us plau, selecciona primer un curs');
            this.value = '';
            return;
        }

        if (dia) {
            fetch(`/M12.1/my-app/public/index.php?controller=canvis&action=getHorarisByCurs&curs=${cursId}&dia=${dia}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Error en la respuesta del servidor');
                    }
                    return response.json();
                })
                .then(horaris => {
                    idHorariSelect.innerHTML = '<option value="">Selecciona un horari</option>';
                    horaris.forEach(horari => {
                        const option = document.createElement('option');
                        option.value = horari.id_horari;
                        option.dataset.idCurs = horari.id_curs;
                        option.textContent = `${horari.hora_inici} - ${horari.hora_fi} | ${horari.assignatura} - ${horari.professor} - ${horari.nom_aula}`;
                        idHorariSelect.appendChild(option);
                    });
                    horariSelector.style.display = 'block';
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error al cargar los horarios');
                });
        }
    });

    // Gestionar campos según tipo de cambio
    tipusCanviSelect.addEventListener('change', function() {
        const tipusCanvi = this.value;
        
        // Mostrar todos los campos primero
        professorGroup.style.display = 'block';
        aulaGroup.style.display = 'block';
        
        // Ocultar según el tipo
        switch(tipusCanvi) {
            case 'Absència professor':
            case 'Canvi professor':
                aulaGroup.style.display = 'none';
                break;
            case 'Canvi aula':
                professorGroup.style.display = 'none';
                break;
            case 'Classe cancelada':
                professorGroup.style.display = 'none';
                aulaGroup.style.display = 'none';
                break;
        }
    });
});