// /UrbanJ/js/script.js

/**
 * Filtra las tarjetas de producto según el texto ingresado.
 */
function buscarProducto() {
  const input  = document.getElementById('search-box');
  const filtro = input.value.trim().toLowerCase();
  if (!filtro) return;

  document.querySelectorAll('.card, .producto-slide').forEach(el => {
    // admitimos tanto .card (index/inventario) como .producto-slide (Home)
    const nameEl = el.querySelector('.card__name, h4');
    const nombre = nameEl ? nameEl.textContent.toLowerCase() : '';
    el.style.display = nombre.includes(filtro) ? '' : 'none';
  });
}

document.addEventListener('DOMContentLoaded', () => {
  // Toggle menú hamburguesa
  const navToggle  = document.querySelector('.nav__toggle');
  const mobileMenu = document.querySelector('.nav__mobile-menu');

  if (navToggle && mobileMenu) {
    navToggle.addEventListener('click', e => {
      e.stopPropagation();
      mobileMenu.classList.toggle('active');
    });
    document.addEventListener('click', e => {
      if (!mobileMenu.contains(e.target) && !navToggle.contains(e.target)) {
        mobileMenu.classList.remove('active');
      }
    });
  }
});
