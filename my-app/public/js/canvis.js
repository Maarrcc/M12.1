document.addEventListener('DOMContentLoaded', function() {
    const diaSelect = document.getElementById('dia');
    const horariSelector = document.getElementById('horari_selector');
    const idHorariSelect = document.getElementById('id_horari');
    const idCursInput = document.getElementById('id_curs');
    const tipusCanviSelect = document.getElementById('tipus_canvi');
    const professorGroup = document.getElementById('professor_group');
    const aulaGroup = document.getElementById('aula_group');

    diaSelect.addEventListener('change', function() {
        const dia = this.value;
        
        if (!dia) {
            horariSelector.style.display = 'none';
            return;
        }

        fetch(`/M12.1/my-app/public/index.php?controller=canvi&action=getHorarisByDia&dia=${dia}`)
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert(data.error);
                    return;
                }

                idHorariSelect.innerHTML = '<option value="">Selecciona un horari</option>';
                data.horaris.forEach(horari => {
                    const option = document.createElement('option');
                    option.value = horari.id_horari;
                    option.dataset.idCurs = horari.id_curs;
                    option.textContent = `${horari.hora_inici} - ${horari.hora_fi} | ${horari.assignatura} - ${horari.professor} - ${horari.nom_aula} (${horari.nom_cicle} ${horari.any_academic})`;
                    idHorariSelect.appendChild(option);
                });

                horariSelector.style.display = 'block';
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al cargar los horarios');
            });
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