// /UrbanJ/js/TablaProductos.js

document.addEventListener('DOMContentLoaded', () => {
  console.log("Script cargado correctamente ✔️");

  const modal = document.querySelector('.modal-overlay');
  const closeModal = document.getElementById('modal-close');

  if (!modal || !closeModal) {
    console.error("Modal o botón de cierre no encontrado");
    return;
  }

  document.querySelectorAll('.btn-view').forEach(button => {
    button.addEventListener('click', () => {
      // Rellenar los datos del modal
      document.getElementById('modal-nombre').textContent = button.dataset.nombre;
      document.getElementById('modal-descripcion').textContent = button.dataset.descripcion;
      document.getElementById('modal-precio').textContent = button.dataset.precio;
      document.getElementById('modal-categoria').textContent = button.dataset.categoria;
      document.getElementById('modal-stock').textContent = button.dataset.stock;
      document.getElementById('modal-creado').textContent = button.dataset.creado;
      document.getElementById('modal-actualizado').textContent = button.dataset.actualizado;

      // Limpiar y agregar imágenes
      const images = JSON.parse(button.dataset.imagenes || '[]');
      const imageContainer = document.getElementById('modal-images');
      imageContainer.innerHTML = '';

      images.forEach(img => {
        const image = document.createElement('img');
        image.src = '/UrbanJ/' + img;
        image.alt = 'Producto';
        image.style.width = '80px';
        image.style.margin = '0.25rem';
        image.style.borderRadius = '6px';
        imageContainer.appendChild(image);
      });

      // Mostrar el modal
      modal.classList.add('active');
    });
  });

  // Cerrar modal con la X
  closeModal.addEventListener('click', () => {
    modal.classList.remove('active');
  });

  // Cerrar modal al hacer clic fuera
  modal.addEventListener('click', (e) => {
    if (e.target === modal) {
      modal.classList.remove('active');
    }
  });
});
