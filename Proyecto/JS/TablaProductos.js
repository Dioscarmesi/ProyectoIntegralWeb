// js/TablaProductos.js
console.log('TablaProductos.js cargado');

document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.btn-view').forEach(btn => {
    btn.addEventListener('click', () => {
      // Rellenar textos
      document.getElementById('modal-nombre').textContent      = btn.dataset.nombre;
      document.getElementById('modal-descripcion').textContent = btn.dataset.descripcion;
      document.getElementById('modal-precio').textContent      = btn.dataset.precio;
      document.getElementById('modal-categoria').textContent   = btn.dataset.categoria;
      document.getElementById('modal-stock').textContent       = btn.dataset.stock;
      document.getElementById('modal-creado').textContent      = btn.dataset.creado;
      document.getElementById('modal-actualizado').textContent = btn.dataset.actualizado;

      // Carrusel de miniaturas
      const imgs = JSON.parse(btn.dataset.imagenes || '[]');
      const container = document.getElementById('modal-images');
      container.innerHTML = '';

      imgs.forEach((src, idx) => {
        const thumb = document.createElement('img');
        thumb.src = src;
        thumb.alt = btn.dataset.nombre;
        thumb.classList.toggle('active', idx === 0);
        // Al pulsar miniatura, marcarla como activa
        thumb.addEventListener('click', () => {
          container.querySelectorAll('img').forEach(i => i.classList.remove('active'));
          thumb.classList.add('active');
        });
        container.appendChild(thumb);
      });

      // Mostrar modal
      document.querySelector('.modal-overlay').style.display = 'flex';
    });
  });

  // Cerrar modal
  document.getElementById('modal-close').addEventListener('click', () => {
    document.querySelector('.modal-overlay').style.display = 'none';
  });
});
