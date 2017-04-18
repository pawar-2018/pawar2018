(function() {

  document.querySelector('.header-burger').addEventListener('click', function(event) {
    var headerNav = document.querySelector('.header-nav');

    if(!this.classList.contains('header-burger--active')) {
      this.classList.add('header-burger--active');
      headerNav.classList.add('header-nav--active');
    } else {
      this.classList.remove('header-burger--active');
      headerNav.classList.remove('header-nav--active');
    }
  });

  var accords = document.querySelectorAll('.accordion-item'), i;
  var hash = window.location.hash.substring(1);

  for (i = 0; i < accords.length; ++i) {
    let accordContent = accords[i].querySelector(".accordion-content");
    let accordTitle = accords[i].querySelector(".accordion-title");
    if (window.location.hash) {
      if (hash === accordTitle.getAttribute('name')) {
        accordContent.classList.add('accordion-content--active');
        accordTitle.classList.add('accordion-title--active');
      }
    }
    accordTitle.addEventListener('click', function(event) {
      if(!accordContent.classList.contains('accordion-content--active')) {
        accordContent.classList.add('accordion-content--active');
        accordTitle.classList.add('accordion-title--active');
      } else {
        accordContent.classList.remove('accordion-content--active');
        accordTitle.classList.remove('accordion-title--active');
      }
    })
  }

})();

