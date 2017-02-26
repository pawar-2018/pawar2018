(function() {
  $(document).foundation();

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

})();

