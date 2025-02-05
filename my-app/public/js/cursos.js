function canviarCurs() {
    const cicle = document.getElementById("selector-cicle").value;
    const any = document.getElementById("selector-any").value;
    const cursComplet = `${cicle}-${any}`;
    const dates = obtenirDatesSetmana(dataActual);
    
    // Guardar la selección actual en localStorage
    localStorage.setItem('cicleSeleccionat', cicle);
    localStorage.setItem('anySeleccionat', any);
    
    // Actualizar el horario con los nuevos parámetros
    actualitzaHorari(dates, cursComplet);
}

// Cargar la última selección al iniciar
document.addEventListener("DOMContentLoaded", function () {
    // Recuperar valores guardados o usar valores por defecto
    const cicleGuardat = localStorage.getItem('cicleSeleccionat') || 'DAW';
    const anyGuardat = localStorage.getItem('anySeleccionat') || 'Primer';
    
    // Establecer los valores en los selectores
    document.getElementById("selector-cicle").value = cicleGuardat;
    document.getElementById("selector-any").value = anyGuardat;
    
    // Añadir event listeners
    document.getElementById("selector-cicle").addEventListener("change", canviarCurs);
    document.getElementById("selector-any").addEventListener("change", canviarCurs);
    
    // Cargar horario inicial
    canviarCurs();
});
