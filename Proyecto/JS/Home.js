// Home.js
document.addEventListener('DOMContentLoaded', () => {
    // Hamburguesa
    const navToggle = document.querySelector('.nav__toggle'),
          mobileMenu = document.querySelector('.nav__mobile-menu');
    navToggle?.addEventListener('click',()=> mobileMenu.classList.toggle('active'));
    document.addEventListener('click', e=>{
      if (!mobileMenu.contains(e.target) && !navToggle.contains(e.target))
        mobileMenu.classList.remove('active');
    });
  
    // User dropdown
    const userBtn = document.querySelector('.nav__user > .btn--icon'),
          userDrop= document.querySelector('.nav__user .nav__dropdown');
    userBtn?.addEventListener('click', e=>{
      e.stopPropagation();
      userDrop.classList.toggle('visible');
    });
    document.addEventListener('click',()=>userDrop?.classList.remove('visible'));
  
    // Cart dropdown
    const cartBtn = document.getElementById('cart-btn'),
          cartDrop= document.getElementById('cart-dropdown');
    cartBtn?.addEventListener('click', e=>{
      e.stopPropagation();
      cartDrop.classList.toggle('visible');
    });
    document.addEventListener('click',()=>cartDrop?.classList.remove('visible'));
  
    // Filtrar productos
    const searchBox = document.getElementById('search-box');
    searchBox?.addEventListener('keyup', ()=>{
      const q=searchBox.value.trim().toLowerCase();
      document.querySelectorAll('.producto-slide').forEach(el=>{
        const txt=el.querySelector('h4').textContent.toLowerCase();
        el.style.display = txt.includes(q)||q===''?'':'none';
      });
    });
  
    // Inicializar carruseles (ejemplo con flickity)
    // document.querySelectorAll('.carousel').forEach(el=>{
    //   new Flickity(el,{cellAlign:'left',contain:true,wrapAround:true});
    // });
  });
  