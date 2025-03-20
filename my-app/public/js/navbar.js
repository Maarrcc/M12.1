document.addEventListener("DOMContentLoaded", () => {
  const navToggle = document.querySelector(".nav-toggle");
  const navLinks = document.querySelector(".nav-links");
  const dropdowns = document.querySelectorAll(".dropdown");

  // Cerrar menú al hacer clic fuera
  document.addEventListener("click", (e) => {
    if (!navLinks.contains(e.target) && !navToggle.contains(e.target)) {
      navToggle.classList.remove("active");
      navLinks.classList.remove("active");
      dropdowns.forEach(dropdown => dropdown.classList.remove("active"));
    }
  });

  // Toggle menú principal
  navToggle.addEventListener("click", (e) => {
    e.stopPropagation();
    navToggle.classList.toggle("active");
    navLinks.classList.toggle("active");
  });

  // Manejar dropdowns
  dropdowns.forEach((dropdown) => {
    const toggle = dropdown.querySelector(".dropdown-toggle");
    toggle.addEventListener("click", (e) => {
      if (window.innerWidth <= 768) {
        e.preventDefault();
        e.stopPropagation();
        
        // Cerrar otros dropdowns
        dropdowns.forEach((other) => {
          if (other !== dropdown) {
            other.classList.remove("active");
          }
        });
        
        dropdown.classList.toggle("active");
      }
    });
  });

  // Cerrar menú al cambiar tamaño de ventana
  window.addEventListener("resize", () => {
    if (window.innerWidth > 768) {
      navToggle.classList.remove("active");
      navLinks.classList.remove("active");
      dropdowns.forEach(dropdown => dropdown.classList.remove("active"));
    }
  });
});
