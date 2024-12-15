import SingleProductGallery from "../components/single-product-gallery";
import SingleProductCustomizeOption from "../components/single-product-customize-option"
import CustomizePopupController from "../components/single-product-customize-popup";
import SingleProductStepOptionsSelection from "../components/single-product-step-options-selection";
document.addEventListener('DOMContentLoaded', function () {
  const customizeFormData = {};

  // * Galleries swiper
  const singleProductGallery = new SingleProductGallery();
  const sliderSingleProductCustomizeOption = new SingleProductCustomizeOption();
  const customizePopup = new CustomizePopupController();
  const stepsAndOptionSelection = new SingleProductStepOptionsSelection(customizeFormData, apiObj);
});
