(function() {

  var burger = document.querySelector('.header-burger__icon')
  if(burger) {
    burger.addEventListener('click', function(event) {
      var headerNav = document.querySelector('.header-nav');

      if(!this.classList.contains('header-burger--active')) {
        this.classList.add('header-burger--active');
        headerNav.classList.add('header-nav--active');
      } else {
        this.classList.remove('header-burger--active');
        headerNav.classList.remove('header-nav--active');
      }
    });
  }

  var accords = document.querySelectorAll('.accordion-item'), i;
  var hash = window.location.hash.substring(1);

  for (i = 0; i < accords.length; ++i) {
    let item = accords[i];
    let accordTitle = item.querySelector('.accordion-title');
    if (window.location.hash) {
      if (hash === accordTitle.getAttribute('name')) {
        item.classList.add('is-active');
      }
    }
    accordTitle.addEventListener('click', function(event) {
      if(!item.classList.contains('is-active')) {
        item.classList.add('is-active');
      } else {
        item.classList.remove('is-active');
      }
    })
  }

  function init() {
    var vidDefer = document.getElementsByTagName('iframe');
    for (var i=0; i<vidDefer.length; i++) {
      if(vidDefer[i].getAttribute('data-src')) {
        vidDefer[i].setAttribute('src',vidDefer[i].getAttribute('data-src'));
      }
    }
  }

  window.onload = init;

})();

