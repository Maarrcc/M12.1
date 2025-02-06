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
    <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
  </svg>`,
  "Classe cancelada": `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="#ffc107">
    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm5 13.59L15.59 17 12 13.41 8.41 17 7 15.59 10.59 12 7 8.41 8.41 7 12 10.59 15.59 7 17 8.41 13.41 12 17 15.59z"/>
  </svg>`,
  Altres: `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="#9c27b0">
    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
  </svg>`,
  default: `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="#9c27b0">
    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
  </svg>`,
};

document.addEventListener("DOMContentLoaded", function () {
  const tbody = document.querySelector("#horari tbody");
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

  // Crear estructura de la tabla
  hores.forEach((hora) => {
    const tr = document.createElement("tr");
    const tdHora = document.createElement("td");
    tdHora.textContent = `${hora} - ${horaRanges[hora]}`;
    tr.appendChild(tdHora);

    dies.forEach((dia) => {
      const td = document.createElement("td");
      td.setAttribute("data-dia", dia);
      td.setAttribute("data-hora", hora);
      // Añadir una clase para celdas vacías
      td.classList.add("celda-vacia");
      tr.appendChild(td);
    });

    tbody.appendChild(tr);
  });
});

// Modificar la función actualitzaHorari
function actualitzaHorari(datesSetmana, cursComplet) {
  const tbody = document.querySelector("#horari tbody");

  if (!cursComplet) {
    const cicle = document.getElementById("selector-cicle")?.value || "DAW";
    const any = document.getElementById("selector-any")?.value || "Primer";
    cursComplet = `${cicle}-${any}`;
  }

  if (tbody) tbody.style.opacity = "0.5";

  carregarDades(
    datesSetmana.dilluns.toISOString().split("T")[0],
    datesSetmana.divendres.toISOString().split("T")[0],
    cursComplet
  )
    .then((data) => {
      console.log("Datos recibidos:", data); // Debug

      if (!data || !data.horari || !Array.isArray(data.horari)) {
        throw new Error("Formato de datos inválido");
      }

      // Limpiar tabla
      document.querySelectorAll("td[data-dia]").forEach((cell) => {
        cell.className = "celda-vacia";
        cell.innerHTML = "";
      });

      // Procesar horario base
      data.horari.forEach((item) => {
        const hora = item.hora_inici.slice(0, 5);
        const dia = item.dia.toLowerCase();
        const cell = document.querySelector(
          `td[data-dia='${dia}'][data-hora='${hora}']`
        );

        if (cell) {
          cell.classList.remove("celda-vacia");
          cell.setAttribute("data-horari", item.id_horari); // Añadir id_horari
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
      if (data.canvis && Array.isArray(data.canvis)) {
        console.log("Procesando cambios:", data.canvis); // Debug
        data.canvis.forEach((canvi) => {
          const cell = document.querySelector(
            `td[data-horari='${canvi.id_horari}']`
          );
          console.log("Buscando celda para cambio:", canvi.id_horari, cell); // Debug

          if (cell) {
            cell.classList.add("cambio-horario");
            const tipoCambio = canvi.tipus_canvi;
            const tipoCambioClass = tipoCambio.toLowerCase().replace(" ", "-");

            // Añadir información del cambio
            const canviInfo = document.createElement("div");
            canviInfo.className = `cambio-info cambio-${tipoCambioClass}`;
            canviInfo.innerHTML = `
            <span class="cambio-icon">${changeIcons[tipoCambio] || ""}</span>
            <span class="cambio-text">${canvi.descripcio_canvi}</span>
          `;
            cell.appendChild(canviInfo);
          }
        });
      }

      tbody.style.opacity = "1";
    })
    .catch((error) => {
      console.error("Error:", error);
      tbody.style.opacity = "1";

      const errorDiv = document.createElement("div");
      errorDiv.id = "error-message";
      errorDiv.textContent = "Error al cargar el horario";
      document.querySelector(".container").prepend(errorDiv);
    });
}

// Asegurar que la función se llama cuando se carga la página
document.addEventListener("DOMContentLoaded", () => {
  const today = new Date();
  const dates = obtenirDatesSetmana(today);
  actualitzaHorari(dates); // Pasar el objeto completo
});

// Hacer que la función esté accesible globalmente para el botón
window.actualitzaHorari = actualitzaHorari;

async function carregarDades(start, end, cursComplet) {
  try {
    const response = await fetch(
      `/M12.1/my-app/public/index.php?controller=horari&action=getHorari&start=${start}&end=${end}&curs=${cursComplet}`
    );
    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }
    const data = await response.json();

    if (data.error && data.redirect) {
      window.location.href = data.redirect;
      return;
    }

    return data;
  } catch (error) {
    console.error("Error:", error);
    throw error;
  }
}
