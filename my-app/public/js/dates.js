let dataActual = new Date();

function obtenirDatesSetmana(data) {
  const dilluns = new Date(data);
  const currentDay = dilluns.getDay(); // 0 = domingo, 1 = lunes, ...

  // Ajustar al lunes de la semana actual
  if (currentDay === 0) {
    // Si es domingo
    dilluns.setDate(dilluns.getDate() + 1);
  } else {
    dilluns.setDate(dilluns.getDate() - currentDay + 1);
  }

  const divendres = new Date(dilluns);
  divendres.setDate(dilluns.getDate() + 4);

  // Asegurar que las fechas sean objetos Date
  return {
    dilluns: new Date(dilluns),
    divendres: new Date(divendres),
    formatat: `${dilluns.toLocaleDateString("ca-ES")} - ${divendres.toLocaleDateString("ca-ES")}`,
  };
}

function actualitzarMostraSetmana() {
  const dates = obtenirDatesSetmana(dataActual);
  const setmanaActualElement = document.getElementById("setmanaActual");
  if (setmanaActualElement) {
    setmanaActualElement.textContent = dates.formatat;
  }
  
  if (typeof window.actualitzaHorari === "function") {
    window.actualitzaHorari(dates); // Pasar el objeto completo
  }
}

document.addEventListener("DOMContentLoaded", function () {
  const setmanaAnterior = document.getElementById("setmanaAnterior");
  const setmanaSeguent = document.getElementById("setmanaSeguent");

  // Solo agregar listeners si los elementos existen
  if (setmanaAnterior) {
    setmanaAnterior.addEventListener("click", () => {
      dataActual.setDate(dataActual.getDate() - 7);
      actualitzarMostraSetmana();
    });
  }

  if (setmanaSeguent) {
    setmanaSeguent.addEventListener("click", () => {
      dataActual.setDate(dataActual.getDate() + 7);
      actualitzarMostraSetmana();
    });
  }

  // Asegurar que horari.js se ha cargado
  setTimeout(() => {
    actualitzarMostraSetmana();
  }, 100);
});
