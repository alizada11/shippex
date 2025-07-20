$(document).ready(function () {
  // Initialize carousel with proper settings
  var owl = $('.delivered-carousel').owlCarousel({
    loop: true,
    margin: 20,
    nav: false,
    dots: false,
    responsive: {
      0: {
        items: 1,
        margin: 10,
      },
      576: {
        items: 2,
      },
      992: {
        items: 3,
      },
    },
  })

  // Custom navigation
  $('.carousel-prev').click(function () {
    owl.trigger('prev.owl.carousel')
  })

  $('.carousel-next').click(function () {
    owl.trigger('next.owl.carousel')
  })
})
