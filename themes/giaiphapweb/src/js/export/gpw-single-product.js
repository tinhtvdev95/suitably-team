import SingleProductGallery from "../components/single-product-gallery";
import SliderProductOptionFields from "../components/slider-product-option-fields";
import CustomizePopupController from "../components/single-product-customize-popup";
document.addEventListener('DOMContentLoaded', function () {
  // * Galleries swiper
  const singleProductGallery = new SingleProductGallery();
  const sliderOptions = new SliderProductOptionFields();
  const customizePopup = new CustomizePopupController();
});
