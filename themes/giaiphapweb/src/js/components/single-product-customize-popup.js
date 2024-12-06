export default class CustomizePopupController {
  constructor(){
    this.popupDialog = document.querySelector('.product-details__customize-popup');
    this.openPopupDialogBtn = document.querySelector('.product-details__open-customize-popup-btn');
    this.closePopupDialogBtn = this.popupDialog.querySelector('.customize-popup__close-btn');

    this.addEvents();
  }
  addEvents() {
    this.openPopupDialogBtn.addEventListener('click', this.openPopupDialog.bind(this));
    this.closePopupDialogBtn.addEventListener('click', this.closePopupDialog.bind(this));
  }
  openPopupDialog() {
    this.popupDialog.showModal();
    document.documentElement.classList.add('customize-popup-opened');
  }
  closePopupDialog() {
    this.popupDialog.close();
    document.documentElement.classList.remove('customize-popup-opened');
  }
}