// Definir assignaturesClasses en el ámbito global
const assignaturesClasses = {
  M6: "modulo-m6",
  M7: "modulo-m7",
  M8: "modulo-m8",
  M9: "modulo-m9",
  M12: "modulo-m12",
};

// Definir los iconos SVG para cada tipo de cambio
const changeIcons = {
  "Absència professor": `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="#f44336">
    <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
  </svg>`,
  "Canvi aula": `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="#4caf50">
    <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/>
  </svg>`,
  "Canvi professor": `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="#2196f3">
    <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4-4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
  </svg>`,
  "Classe cancelada": `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="#ffc107">
    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm5 13.59L15.59 17 12 13.41 8.41 17 7 15.59 10.59 12 7 8.41 8.41 7 12 10.59 15.59 7 17 8.41 13.41 12 17 15.59z"/>
  </svg>`,
  Altres: `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="#9c27b0">
    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-10-10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
  </svg>`,
  default: `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="#9c27b0">
    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-10-10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
  </svg>`,
};

const selectorDia = document.getElementById('selector-dia');
let diaSeleccionado = 'Dilluns';

// Añadir al inicio del archivo, junto con las otras variables globales
let currentController = null;

// Añadir event listener para el selector de día
selectorDia.addEventListener('change', function(e) {
    diaSeleccionado = e.target.value;
    actualizarHorario(); 
});

document.addEventListener("DOMContentLoaded", function () {
  const grid = document.querySelector("#horari");
  const horaRanges = {
    "15:00": "16:00",
    "16:00": "17:00",
    "17:00": "18:00",
    "18:30": "19:30",
    "19:30": "20:30",
    "20:30": "21:30",
  };

  const hores = Object.keys(horaRanges);
  const dies = ["dilluns", "dimarts", "dimecres", "dijous", "divendres"];

  // Generar la cuadrícula
  hores.forEach((hora) => {
    // Celda de hora
    const horaDiv = document.createElement("div");
    horaDiv.classList.add("hora-cell");
    horaDiv.textContent = `${hora} - ${horaRanges[hora]}`;
    grid.appendChild(horaDiv);

    // Celdas de días
    dies.forEach((dia) => {
      const diaDiv = document.createElement("div");
      diaDiv.classList.add("dia-cell", "celda-vacia");
      diaDiv.setAttribute("data-dia", dia);
      diaDiv.setAttribute("data-hora", hora);
      grid.appendChild(diaDiv);
    });
  });

  // Inicializar la vista móvil
  actualizarHorario();

  // Añadir listener para el selector de día
  document.getElementById('selector-dia').addEventListener('change', actualizarHorario);
  
  // Añadir listener para cambios de tamaño de ventana
  window.addEventListener('resize', actualizarHorario);
});

document.addEventListener("DOMContentLoaded", function() {
    // Inicializar la vista móvil
    actualizarHorario();

    // Añadir listener para el selector de día
    const selectorDia = document.getElementById('selector-dia');
    if (selectorDia) {
        selectorDia.addEventListener('change', function() {
            actualizarHorario();
        });
    }

    // Añadir listener para cambios de tamaño de ventana
    window.addEventListener('resize', actualizarHorario);
});

document.addEventListener('DOMContentLoaded', function() {
    const selectorCicle = document.getElementById('selector-cicle');
    const selectorAny = document.getElementById('selector-any');
    
    const today = new Date();
    const dates = obtenirDatesSetmana(today);
    
    // Event listeners para cambios de curso
    selectorCicle.addEventListener('change', function() {
        const cursComplet = `${selectorCicle.value}-${selectorAny.value}`;
        actualitzaHorari(dates, cursComplet);
    });

    selectorAny.addEventListener('change', function() {
        const cursComplet = `${selectorCicle.value}-${selectorAny.value}`;
        actualitzaHorari(dates, cursComplet);
    });

    // Cargar solo el curso inicial
    const cursInicial = `${selectorCicle.value}-${selectorAny.value}`;
    actualitzaHorari(dates, cursInicial);
});

/**
 * Actualiza la taula d'horaris amb les dades del horari base i
 * els canvis corresponents a una setmana i un curs.
 *
 * @param {Object} datesSetmana objecte amb les dates d'inici i fi
 *                              de la setmana en format ISO
 * @param {string} [cursComplet] Identificador de curs (format:
 *                              "DAW-Primer")
 */
function actualitzaHorari(datesSetmana, cursComplet) {
    const grid = document.querySelector("#horari");

    if (!cursComplet) {
        const cicle = document.getElementById("selector-cicle")?.value || "DAW";
        const any = document.getElementById("selector-any")?.value || "Primer";
        cursComplet = `${cicle}-${any}`;
    }

    // Cancelar petición anterior si existe
    if (currentController) {
        currentController.abort();
    }

    if (grid) grid.style.opacity = "0.5";

    // Crear nuevo controlador para esta petición
    currentController = new AbortController();

    // Realizar la petición
    carregarDades(
        datesSetmana.dilluns.toISOString().split("T")[0],
        datesSetmana.divendres.toISOString().split("T")[0],
        cursComplet,
        currentController.signal
    )
    .then((data) => {
        if (data) {
            renderizarHorario(data.horari, data.canvis);
            grid.style.opacity = "1";
        }
    })
    .catch((error) => {
        if (error.name !== 'AbortError') {
            console.error("Error:", error);
            grid.style.opacity = "1";
            const errorDiv = document.createElement("div");
            errorDiv.id = "error-message";
            errorDiv.textContent = "Error al cargar el horario";
            document.querySelector(".horari-container").prepend(errorDiv);
        }
    });
}

// Separar la lógica de renderizado
function renderizarHorario(horariData, canvisData) {
    const grid = document.querySelector("#horari");

    // Limpiar cuadrícula
    document.querySelectorAll(".dia-cell").forEach((cell) => {
        cell.className = "dia-cell celda-vacia";
        cell.innerHTML = "";
        cell.removeAttribute("data-cambio");
    });

    // Procesar horario base
    horariData.forEach((item) => {
        if (!item || !item.hora_inici || !item.dia) {
            console.warn('Datos de horario incompletos:', item);
            return;
        }

        const hora = item.hora_inici.slice(0, 5);
        const dia = item.dia.toLowerCase();
        const cell = document.querySelector(
            `.dia-cell[data-dia='${dia}'][data-hora='${hora}']`
        );

        if (cell) {
            cell.classList.remove("celda-vacia");
            cell.setAttribute("data-horari", item.id_horari);
            const modulo = item.assignatura.match(/M\d+/)?.[0] || "default";
            cell.classList.add(assignaturesClasses[modulo] || "modulo-default");

            cell.innerHTML = `
                <div class="classe-info">
                    <div class="classe-info-basic">
                        <div class="assignatura">${item.assignatura}</div>
                        <div class="professor">${item.professor}</div>
                        <div class="aula">Aula: ${item.aula}</div>
                    </div>
                </div>`;
        }
    });

    // Procesar cambios
    if (canvisData && Array.isArray(canvisData)) {
        canvisData.forEach((canvi) => {
            if (!canvi || !canvi.id_horari || !canvi.tipus_canvi) {
                console.warn('Datos de cambio incompletos:', canvi);
                return;
            }

            const cell = document.querySelector(
                `.dia-cell[data-horari='${canvi.id_horari}']`
            );

            if (cell) {
                cell.classList.add("cambio-horario");
                cell.setAttribute("data-cambio", canvi.tipus_canvi);

                const tipoCambio = canvi.tipus_canvi.toLowerCase().replace(" ", "-");
                const canviInfo = document.createElement("div");
                canviInfo.className = `cambio-info cambio-${tipoCambio}`;

                let descripcionCambio = '';
                switch (canvi.tipus_canvi) {
                    case 'Absència professor':
                        descripcionCambio = `Professor absent`;
                        break;
                    case 'Canvi aula':
                        descripcionCambio = canvi.aula_substituta ? 
                            `Canvi d'aula: ${canvi.aula_original} → ${canvi.aula_substituta}` :
                            `Canvi d'aula`;
                        break;
                    case 'Canvi professor':
                        descripcionCambio = canvi.professor_substitut ?
                            `Canvi de professor: ${canvi.professor_original} → ${canvi.professor_substitut}` :
                            `Canvi de professor`;
                        break;
                    case 'Classe cancelada':
                        descripcionCambio = 'Classe cancel·lada';
                        break;
                    default:
                        descripcionCambio = canvi.descripcio_canvi || 'Altres canvis';
                }

                canviInfo.innerHTML = `
                    <span class="cambio-icon">${changeIcons[canvi.tipus_canvi] || changeIcons["default"]}</span>
                    <span class="cambio-text">${descripcionCambio}</span>
                `;

                cell.appendChild(canviInfo);
            }
        });
    }
}

function netejarCache(completo = false) {
    if (completo) {
        // Limpieza completa del caché
        cache.horari = {};
        cache.canvis = {};
        cache.cursActual = null;
    } else {
        // Solo limpiar los cambios, manteniendo el horario base
        cache.canvis = {};
    }
}

// Asegurar que la función se llama cuando se carga la página
document.addEventListener("DOMContentLoaded", () => {
  const today = new Date();
  const dates = obtenirDatesSetmana(today);
  actualitzaHorari(dates); // Pasar el objeto completo
});

// Hacer que la función esté accesible globalmente para el botón
window.actualitzaHorari = actualitzaHorari;

/**
 * Espera un tiempo determinado
 * @param {number} ms Milisegundos a esperar
 * @returns {Promise} Promise que se resuelve después del tiempo especificado
 */
const delay = ms => new Promise(resolve => setTimeout(resolve, ms));

/**
 * Carrega les dades del horari des de l'API PHP.
 * @param {string} start Data d'inici del període.
 * @param {string} end Data de fi del període.
 * @param {string} cursComplet Nom del curs amb format "DAW-Primer".
 * @param {AbortSignal} signal Señal para cancelar la petición.
 * @return {Promise<Object>} Dades del horari.
 * @throws {Error} Si hi ha algun error en la càrrega.
 */
async function carregarDades(start, end, cursComplet, signal) {
    try {
        const horariResponse = await fetch(`${API_CONFIG.BASE_URL}?curs=${cursComplet}`, {
            headers: {
                'X-API-Key': API_CONFIG.API_KEY
            },
            signal
        });

        if (!horariResponse.ok) {
            throw new Error(`Error HTTP! estat: ${horariResponse.status}`);
        }

        const horariData = await horariResponse.json();

        const canvisResponse = await fetch(`${API_CONFIG.BASE_URL}/canvis?curs=${cursComplet}&start=${start}&end=${end}`, {
            headers: {
                'X-API-Key': API_CONFIG.API_KEY
            },
            signal
        });

        if (!canvisResponse.ok) {
            throw new Error(`Error HTTP! estat: ${canvisResponse.status}`);
        }

        const canvisData = await canvisResponse.json();

        if (!horariData.success || !canvisData.success) {
            throw new Error(horariData.message || canvisData.message || 'Error desconegut');
        }

        return {
            horari: horariData.data,
            canvis: canvisData.data
        };
    } catch (error) {
        // Ignorar errores de cancelación
        if (error.name === 'AbortError') {
            console.log('Petición cancelada');
            return;
        }
        console.error("Error en carregarDades:", error);
        throw error;
    }
}

function actualizarHorario() {
    const horariContainer = document.querySelector('.horari-container');
    const diaSeleccionado = document.getElementById('selector-dia').value.toLowerCase();
    const esMobile = window.innerWidth <= 768;
    const horaCells = document.querySelectorAll('.hora-cell');
    const diaCells = document.querySelectorAll('.dia-cell');

    if (esMobile) {
        // Mostrar el selector de día
        document.getElementById('selector-dia').style.display = 'block';
        
        // Ajustar la visualización del grid
        horariContainer.classList.add('mobile-view');
        
        // Mostrar todas las celdas de hora
        horaCells.forEach(cell => {
            cell.style.display = 'block';
        });

        // Mostrar solo las celdas del día seleccionado
        diaCells.forEach(cell => {
            const cellDia = cell.getAttribute('data-dia');
            if (cellDia === diaSeleccionado) {
                cell.style.display = 'block';
            } else {
                cell.style.display = 'none';
            }
        });
    } else {
        // Restaurar vista de escritorio
        document.getElementById('selector-dia').style.display = 'none';
        horariContainer.classList.remove('mobile-view');
        
        // Mostrar todas las celdas
        [...horaCells, ...diaCells].forEach(cell => {
            cell.style.display = '';
        });
    }
}

// Añadir listener para cambios de tamaño de ventana
window.addEventListener('resize', actualizarHorario);
