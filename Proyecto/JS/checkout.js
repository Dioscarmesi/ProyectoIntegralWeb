// /UrbanJ/js/checkout.js

document.addEventListener('DOMContentLoaded', () => {
    const btnNew = document.getElementById('btn-new-address');
    const list   = document.getElementById('address-list');
    const form   = document.getElementById('address-form');
    const payBtn = document.getElementById('btn-pay');
  
    // Toggle form
    btnNew.addEventListener('click', () => {
      form.style.display = form.style.display === 'none' ? 'block' : 'none';
    });
  
    // Cuando el usuario selecciona o edita / borra direcciones,
    // deberías volver a calcular si ya hay alguna disponible
    const updatePayButton = () => {
      const hasAddress = list.querySelectorAll('li').length > 0;
      if (hasAddress) {
        payBtn.classList.add('enabled');
      } else {
        payBtn.classList.remove('enabled');
      }
    };
  
    // Inicializar
    updatePayButton();
  
    // Podrías también vincular aquí los botones de editar/borrar
    // y al final de esas acciones llamar a updatePayButton().
  });
  