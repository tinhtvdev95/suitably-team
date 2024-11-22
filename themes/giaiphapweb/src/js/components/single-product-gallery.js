export default class SingleProductGallery {
  constructor() {
    this._mainGalleryEl = document.querySelector(`.gallery__main .swiper`);
    this._thumbGalleryEl = document.querySelector(`.gallery__thumb .swiper`);

    if (!this._mainGalleryEl || !this._thumbGalleryEl) return;
    this.initializeSwiper();
    this.imageZoomOnHover();
  }

  /**
   * Initializes the Swiper galleries for the product page.
   * 
   * This method sets up two Swiper instances:
   * 1. `_thumbGallery`: A thumbnail gallery with specific configurations.
   * 2. `_mainGallery`: The main gallery that uses the thumbnail gallery for navigation.
   * 
   * The `_thumbGallery`:
   * The `_mainGallery` is configured with:
   * - `thumbs`: Uses the `_thumbGallery` instance for thumbnail navigation.
   */
  initializeSwiper() {
    const thumbSwiper = new Swiper(this._thumbGalleryEl, {
      spaceBetween: 0,
      slidesPerView: 3,
      watchSlidesVisibility: true,
      watchSlidesProgress: true,
      breakpoints: {
        550: {
          slidesPerView: 4,
        }
      },
    });
    const mainSwiper = new Swiper(this._mainGalleryEl, {
      spaceBetween: 10,
      thumbs: {
        swiper: thumbSwiper,
      },
    });
  }
  imageZoomOnHover() {
    const imageContainers = this._mainGalleryEl.querySelectorAll('.swiper-slide');
    imageContainers.forEach(imageContainer => {
      const img = imageContainer.querySelector('img');
      imageContainer.addEventListener('mouseenter', onImageMouseEnter);
    });
    const onImageMouseEnter = e => {
      const _self = e.currentTarget;
      const img = _self.querySelector('img');
      const x = e.clientX - _self.offsetLeft;
      const y = e.clientY - _self.offsetTop;
      img.style.setProperty('--x', `${x}px`);
      img.style.setProperty('--y', `${y}px`);
      img.style.setProperty('--scale', '2');
    };
    const onImageMOuseLeave = e => {

    }
  }
}