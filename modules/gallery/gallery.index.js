document.addEventListener('DOMContentLoaded', function() {
  if (document.querySelector('.gallery')) {
    import(/* webpackChunkName: "swiper-styles" */ 'swiper/css');
    import(/* webpackChunkName: "swiper" */ 'swiper')
      .then(({ default: Swiper }) => {
        import(/* webpackChunkName: "swiper-modules" */ 'swiper/modules')
          .then(({ Navigation, Autoplay }) => {
            document.querySelectorAll('.gallery').forEach(gallery => {
              const swiperContainer = gallery.querySelector('.gdymc_gallery_container');
              const swiperWrapper = gallery.querySelector('.gdymc_gallery');
              const swiperSlides = swiperWrapper.querySelectorAll('.gdymc_gallery_item');
              const swiperSlidesPerView = gallery.dataset.slides || 1;
              const style = getComputedStyle(document.documentElement);

              if (swiperSlides.length < 2) {
                gallery.querySelector('.content--controls').style.display = 'none';
                gallery.classList.add('gallery--single');
                return;
              }

              swiperContainer.classList.add('swiper');
              swiperWrapper.classList.add('swiper-wrapper');
              swiperSlides.forEach(slide => {
                slide.classList.add('swiper-slide');
              });

              new Swiper(swiperContainer, {
                modules: [Navigation, Autoplay],
                navigation: {
                  nextEl: gallery.querySelector('.swiper-button-next'),
                  prevEl: gallery.querySelector('.swiper-button-prev')
                },
                loop: true,
                slidesPerView: swiperSlidesPerView,
                spaceBetween: parseFloat(style.getPropertyValue('--column-gap'), 10) * 16,
                breakpoints: {
                  0: {
                    slidesPerView: 1
                  },
                  1024: {
                    slidesPerView: swiperSlidesPerView > 2 ? 2 : swiperSlidesPerView
                  },
                  1400: {
                    slidesPerView: swiperSlidesPerView
                  },
                }
                // autoplay: {
                //   delay: 2500,
                //   disableOnInteraction: false,
                //   pauseOnMouseEnter: true
                // }
              });
            });
          });
      });
  }
});