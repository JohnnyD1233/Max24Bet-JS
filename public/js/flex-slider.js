$(document).ready(function() {
  $('.flex-prev').click(function(event) {
    event.preventDefault();
    $('.flexslider').flexslider('prev');
  });

  $('.flex-next').click(function(event) {
    event.preventDefault();
    $('.flexslider').flexslider('next');
  });

  $('.flexslider').flexslider({
    animation: 'slide',
    slideshow: true,
    slideshowSpeed: 5000,
    controlNav: false,
    directionNav: true,
    prevText: 'Previous',
    nextText: 'Next'
  });

  // Auto-slide functionality
  setInterval(function() {
    $('.flexslider').flexslider('next');
  }, 5000); // 5 seconds interval
});
