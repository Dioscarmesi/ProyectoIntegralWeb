// /UrbanJ/js/Home.js
document.addEventListener('DOMContentLoaded', () => {
  // Permitir scroll horizontal suave con flechas si quieres…
  document.querySelectorAll('.carousel, .productos-carousel').forEach(carousel => {
    let isDown = false, startX, scrollLeft;
    carousel.addEventListener('mousedown', e => {
      isDown = true;
      carousel.classList.add('active');
      startX = e.pageX - carousel.offsetLeft;
      scrollLeft = carousel.scrollLeft;
    });
    carousel.addEventListener('mouseleave', () => {
      isDown = false;
      carousel.classList.remove('active');
    });
    carousel.addEventListener('mouseup', () => {
      isDown = false;
      carousel.classList.remove('active');
    });
    carousel.addEventListener('mousemove', e => {
      if (!isDown) return;
      e.preventDefault();
      const x = e.pageX - carousel.offsetLeft;
      const walk = (x - startX) * 1; // velocidad
      carousel.scrollLeft = scrollLeft - walk;
    });
  });
});
