document.addEventListener('DOMContentLoaded', function() {
    const diaSelect = document.getElementById('dia');
    const idHorariSelect = document.getElementById('id_horari');
    const cursSelect = document.getElementById('selector-curs');
    const horariSelector = document.getElementById('horari_selector');
    const idCursInput = document.getElementById('id_curs');
    const tipusCanviSelect = document.getElementById('tipus_canvi');
    const professorGroup = document.getElementById('professor_group');
    const aulaGroup = document.getElementById('aula_group');
    
    // Campos a deshabilitar inicialmente
    const formElements = [
        diaSelect,
        idHorariSelect,
        document.getElementById('tipus_canvi'),
        document.getElementById('data_canvi'),
        document.getElementById('data_fi'),
        document.getElementById('id_professor_substitut'),
        document.getElementById('id_aula_substituta'),
        document.getElementById('descripcio_canvi')
    ];

    // Deshabilitar todos los campos al cargar la página
    formElements.forEach(element => {
        if (element) {
            element.disabled = true;
        }
    });

    // Cuando cambia el curso
    cursSelect.addEventListener('change', function() {
        const cursId = this.value;
        diaSelect.value = ''; // Reset día
        idHorariSelect.innerHTML = '<option value="">Primer selecciona un dia</option>';
        horariSelector.style.display = 'none';
        
        // Habilitar/deshabilitar campos según si hay curso seleccionado
        formElements.forEach(element => {
            if (element) {
                element.disabled = !cursId;
            }
        });
        
        if (cursId) {
            idCursInput.value = cursId;
            diaSelect.disabled = false; // Habilitar selector de día
        }
    });

    // Cuando cambia el día
    diaSelect.addEventListener('change', function() {
        const dia = this.value;
        const cursId = cursSelect.value;

        if (!cursId) {
            alert('Si us plau, selecciona primer un curs');
            this.value = '';
            return;
        }

        if (dia) {
            fetch(`/M12.1/my-app/public/index.php?controller=canvi&action=getHorarisByCurs&curs=${cursId}&dia=${dia}`)
                .then(response => response.json())
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

    idHorariSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        idCursInput.value = selectedOption.dataset.idCurs;
    });

    tipusCanviSelect.addEventListener('change', function() {
        const tipusCanvi = this.value;
        
        professorGroup.classList.remove('disabled');
        aulaGroup.classList.remove('disabled');
        
        switch(tipusCanvi) {
            case 'Absència professor':
            case 'Canvi professor':
                aulaGroup.classList.add('disabled');
                break;
            case 'Canvi aula':
                professorGroup.classList.add('disabled');
                break;
            case 'Classe cancelada':
                professorGroup.classList.add('disabled');
                aulaGroup.classList.add('disabled');
                break;
        }
    });
});