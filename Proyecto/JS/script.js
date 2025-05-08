// script.js
document.addEventListener('DOMContentLoaded', () => {
    const toggle = document.querySelector('.nav__toggle');
    const mobileMenu = document.querySelector('.nav__mobile-menu');
    if (toggle && mobileMenu) {
      toggle.addEventListener('click', () => {
        mobileMenu.classList.toggle('active');
      });
    }
  });
  function buscarProducto() {
    const box = document.getElementById('search-box');
    if (!box) return;
    const term = box.value.trim();
    if (term) window.location.href = `/UrbanJ/productos.php?search=${encodeURIComponent(term)}`;
  }
  