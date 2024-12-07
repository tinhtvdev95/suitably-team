export default class SingleProductCustomizeOption {
    constructor() {
        this.sliderSelector = '.swiper-product-details-custom-option';
        this.navigationPrevSelector = '.navigation .navigation__prev';
        this.navigationNextSelector = '.navigation .navigation__next';
        this.initSlider();
    }

    initSlider() {
        this.sliderProductCustomizeOption = new Swiper(this.sliderSelector, {
            loop: false,
            navigation: {
                nextEl: this.navigationNextSelector,
                prevEl: this.navigationPrevSelector
            },
            slidesPerView: 1,
        });
        console.log(this.navigationNextSelector);
        
    }
}