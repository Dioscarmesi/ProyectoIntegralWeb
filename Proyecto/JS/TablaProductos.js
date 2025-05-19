document.addEventListener('DOMContentLoaded', () => {
  console.log("Script cargado correctamente ✔️");

  // Elementos del modal
  const modal = document.querySelector('.modal-overlay');
  const closeModal = document.getElementById('modal-close');
  const modalContent = document.querySelector('.modal');

  if (!modal || !closeModal || !modalContent) {
    console.error("Elementos del modal no encontrados");
    return;
  }

  // Función para mostrar el modal con animación
  function showModal() {
    modal.style.display = 'flex';
    setTimeout(() => {
      modal.classList.add('active');
      modalContent.classList.add('active');
    }, 10);
  }

  // Función para ocultar el modal con animación
  function hideModal() {
    modal.classList.remove('active');
    modalContent.classList.remove('active');
    setTimeout(() => {
      modal.style.display = 'none';
    }, 300);
  }

  // Eventos para los botones Ver
  document.querySelectorAll('.btn-view').forEach(button => {
    button.addEventListener('click', () => {
      // Mostrar datos básicos
      document.getElementById('modal-nombre').textContent = button.dataset.nombre;
      document.getElementById('modal-descripcion').textContent = button.dataset.descripcion;
      document.getElementById('modal-precio').textContent = button.dataset.precio;
      document.getElementById('modal-categoria').textContent = button.dataset.categoria;
      document.getElementById('modal-stock').textContent = button.dataset.stock;
      document.getElementById('modal-creado').textContent = button.dataset.creado;
      document.getElementById('modal-actualizado').textContent = button.dataset.actualizado;

      // Procesar imágenes
      const imagesContainer = document.getElementById('modal-images');
      imagesContainer.innerHTML = '';
      
      try {
        const images = JSON.parse(button.dataset.imagenes || '[]');
        
        if (images.length > 0) {
          images.forEach(img => {
            const imgElement = document.createElement('img');
            imgElement.src = img.startsWith('http') ? img : `/UrbanJ/${img}`;
            imgElement.alt = button.dataset.nombre;
            imgElement.loading = 'lazy';
            imagesContainer.appendChild(imgElement);
          });
        } else {
          imagesContainer.innerHTML = '<p class="no-images">No hay imágenes disponibles</p>';
        }
      } catch (error) {
        console.error("Error al parsear imágenes:", error);
        imagesContainer.innerHTML = '<p class="no-images">Error al cargar imágenes</p>';
      }

      showModal();
    });
  });

  // Eventos para cerrar el modal
  closeModal.addEventListener('click', hideModal);
  modal.addEventListener('click', (e) => e.target === modal && hideModal());
  document.addEventListener('keydown', (e) => e.key === 'Escape' && hideModal());
});