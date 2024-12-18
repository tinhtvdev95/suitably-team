export default class StepsAndOptionSelection {
  constructor(customizeFormData, apiObj) {
    this.apiObj = apiObj;

    this.stepsNavItemsSelector = 'customize-popup__nav-item';
    this.navItemActiveClass = `${this.stepsNavItemsSelector}--active`;
    this.navItemDisabledClass = `${this.stepsNavItemsSelector}--disabled`;

    this.totalPriceInputSelector = 'input[name="total_price"]';
    this.totalPriceDisplaySelector = 'customize-popup__product-price';

    this.stepsContentSelector = 'customize-popup__step';
    this.stepContentActiveClass = `${this.stepsContentSelector}--active`;

    this.stepTitleSelector = 'customize-popup__step-title';

    this.stepOptionsSelector = 'step-option';
    this.stepContentContinueBtnSelector = 'customize-popup__step-continue-btn';

    this.productDetailsCustomOptionSelector = 'product-details-custom-option__container';
    this.productDetailsCustomOptionActiveClass = `${this.productDetailsCustomOptionSelector}--active`;
    this.productDetailsCustomOptionShowChildrenClass = `${this.productDetailsCustomOptionSelector}--show-children`;

    this.productDetailsCustomOptionTitleSelector = 'product-details-custom-option__title';

    this.customizeFormData = new Proxy(customizeFormData, {
      set: (target, key, value) => {
        const isKeyExist = Object.prototype.hasOwnProperty.call(target, key);
        if (key === 'choose-options' && isKeyExist) {
          // Hide all product details custom options if user choose another option
          document.querySelectorAll(`.${this.productDetailsCustomOptionActiveClass}`).forEach(container => {
            container.classList.remove(this.productDetailsCustomOptionActiveClass);
          });
          // Clear all data in 'choose-details' if 'choose-options' is changed and 'choose-details' is exist
          if (Object.prototype.hasOwnProperty.call(target, 'choose-details')) {
            target['choose-details'] = {};
          }
        }
        target[key] = value;
        return true;
      },
    });

    this.init();
    this.addEventListeners();
  }

  init() {
    this.stepsNavItems = document.querySelectorAll(`.${this.stepsNavItemsSelector}`);
    this.stepsContent = document.querySelectorAll(`.${this.stepsContentSelector}`);
    this.stepOptions = document.querySelectorAll(`.${this.stepOptionsSelector}`);
    this.productDetailsCustomOptions = document.querySelectorAll(`.${this.productDetailsCustomOptionSelector}`);
    this.productDetailsCustomTitles = document.querySelectorAll(`.${this.productDetailsCustomOptionTitleSelector}`);
    this.form = document.querySelector('.customize-popup__fit-customization');
    this.totalPriceInput = this.form.querySelector(this.totalPriceInputSelector);
    this.totalPriceDisplayEl = this.form.querySelector(`.${this.totalPriceDisplaySelector} .price`);
    this.totalPrice = new Proxy({
      base: parseFloat(this.totalPriceInput.value),
      additional: {},
      total: parseFloat(this.totalPriceInput.value),
    }, {
      set: (target, key, value) => {
        if( key === 'base' || key === 'total') {
          console.error('You can not set base price');
          return false;
        }
        if (key === 'additional' || typeof value === 'object') {
          target.total = target.base;
          // check if value.name exist in target[key] if it does, replace price with new price, if not, add new property to target[key]
          if (target.additional[value.name]) {
            target.additional[value.name] = value.price;
          } else {
            target.additional[value.name] = value.price;
          }
          // calculate total price
          Object.values(target.additional).forEach(price => {
            target.total += price;
          });
        } else {
          console.error('Data type is not correct');
          return false;
        }
        this.totalPriceDisplayEl.textContent = target.total.toFixed(2);
        this.totalPriceInput.value = target.total;
        return true;
      },
    });
  }

  addEventListeners() {
    this.stepsNavItems.forEach(stepNavItem => {
      const navButton = stepNavItem.querySelector('a');
      navButton.addEventListener('click', event => {
        this.handleSwitchStepWithNavItems(event, stepNavItem, navButton);
      });
    });

    this.stepsContent.forEach(stepContent => {
      const continueBtn = stepContent.querySelector(`.${this.stepContentContinueBtnSelector}`);
      continueBtn?.addEventListener('click', event => {
        this.handleContinueStep(event, stepContent);
      });
    });

    this.stepOptions.forEach(stepOption => {
      if (stepOption.hasAttribute('is-fabric')) {
        stepOption.addEventListener('click', event => {
          this.handleFabricTypeSelection(event, stepOption);
        });
      }
      stepOption.addEventListener('click', event => {
        this.handleSelectingOption(event, stepOption);
      });
    });

    this.productDetailsCustomTitles.forEach(title => {
      title.addEventListener('click', event => {
        this.handleProductDetailsCustomOptionTitleClick(event, title);
      });
    });

    this.form.addEventListener('submit', this.handleSubmitForm.bind(this));
  }

  async handleSubmitForm(event) {
    event.preventDefault();
    const formData = new FormData(this.form);
    formData.append('total_price', JSON.stringify(this.totalPrice));
    const selectedInputs = this.form.querySelectorAll('input:checked');
    const response = await fetch(this.apiObj.url, {
      method: 'POST',
      body: formData,
    });
    const data = await response.json();
  }

  handleSelectingOption(event, stepOption) {
    if (event.target.tagName === 'INPUT') {
      event.stopPropagation();
      return;
    }
    const input = stepOption.querySelector('input');
    const inputName = input.getAttribute('name');
    const inputPrice = input.dataset.price;
    this.calculateAdjustedPrice(inputName, inputPrice);
    this.stepOptions.forEach(option => {
      const optionInput = option.querySelector('input');
      if (optionInput.name === inputName && optionInput.hasAttribute('checked')) {
        optionInput.removeAttribute('checked');
      }
    });
    input.setAttribute('checked', '1');
  }

  calculateAdjustedPrice(optionName, additionalPrice) {
    const isPercentage = additionalPrice.endsWith('%');
    let price = additionalPrice.includes('%') || additionalPrice.includes('$') ? parseFloat(additionalPrice.slice(0, -1)) : parseFloat(additionalPrice);
    if(isPercentage) {
      price = this.totalPrice.base * (price / 100);
    } 
    this.totalPrice.additional = {name: optionName, price};
    console.log(this.totalPrice);
  }

  handleFabricTypeSelection(event, stepOption) {
    if (event.target.tagName === 'INPUT') {
      event.stopPropagation();
      return;
    }
    const input = stepOption.querySelector('input');
    const target = input.dataset.slug;
    document.querySelector('.fabric-options--active')?.classList.remove('fabric-options--active');
    document.querySelector(`#${target}`).classList.add('fabric-options--active');
  }

  handleContinueStep(event, stepContent) {
    event.preventDefault();
    const NEXT_STEP_INDEX = 1;

    const stepTitle = stepContent.querySelector(`.${this.stepTitleSelector}`).textContent;
    const currentStepName = stepContent.getAttribute('id');
    let stepOptions =
      currentStepName !== 'choose-details'
        ? stepContent.querySelectorAll(`.${this.stepOptionsSelector} input`)
        : stepContent.querySelectorAll(`.${this.productDetailsCustomOptionActiveClass} .${this.stepOptionsSelector} input`);

    if (currentStepName != 'take-measurements' && !this.validateStepOptions(stepOptions)) return;

    stepOptions.forEach(option => {
      if (option.hasAttribute('checked')) {
        const optionName = option.getAttribute('name');
        const optionPrice = option.dataset.price;
        const optionImgSrc = option.nextElementSibling.nextElementSibling.getAttribute('src');
        if (currentStepName === 'choose-details') {
          // Create an object for 'choose-details' if it's not exist in customizeFormData
          !this.customizeFormData.hasOwnProperty('choose-details') && (this.customizeFormData['choose-details'] = {});
          // get title of choose details children block
          const fitOptionFieldsMain = option.closest('.fit-option-fields__main') ?? option.closest('.fit-option-fields__main-merge');
          let detailTitle = '';
          if (fitOptionFieldsMain.classList.contains('fit-option-fields__main-merge')) {
            detailTitle = fitOptionFieldsMain.previousElementSibling.textContent.trim();
          } else {
            detailTitle = fitOptionFieldsMain.previousElementSibling.querySelector('.fit-option-fields-top__title').textContent.trim();
          }
          this.addDataToFormData(optionName, detailTitle, option.value, option.dataset.slug, optionPrice, optionImgSrc, 'choose-details', stepTitle);
        } else if (currentStepName === 'choose-fabric' && optionName === 'color-and-style') {
          this.addDataToFormData(optionName, stepTitle, option.value, option.dataset.slug, optionPrice, optionImgSrc, 'choose-fabric', stepTitle);
        } else {
          this.addDataToFormData(optionName, stepTitle, option.value, option.dataset.slug, optionPrice, optionImgSrc);
        }
      }
    });

    const nextStepIndex = Array.from(this.stepsContent).indexOf(stepContent) + NEXT_STEP_INDEX;
    const nextStepNavItem = this.stepsNavItems[nextStepIndex];
    const nextStepNavItemButton = nextStepNavItem.querySelector('a');
    const nextStepContent = this.stepsContent[nextStepIndex];
    const nextStepName = nextStepContent.getAttribute('id');

    if (nextStepName === 'choose-details') {
      switch (this.customizeFormData['choose-options'].slug) {
        case 'custom-suits':
          this.setActiveForProductDetailsCustomOption(['custom-jacket', 'pants', 'waistcoat']);
          break;
        case 'custom-jackets':
          this.setActiveForProductDetailsCustomOption(['custom-jacket']);
          break;
        case 'custom-waistcoats':
          this.setActiveForProductDetailsCustomOption(['waistcoat']);
          break;
        case 'custom-pants':
          this.setActiveForProductDetailsCustomOption(['pants']);
          break;
        case 'custom-coats':
          this.setActiveForProductDetailsCustomOption(['coat']);
          break;
        case 'custom-shirts':
          this.setActiveForProductDetailsCustomOption(['shirt']);
          break;
        case 'tuxedo-collection':
          this.setActiveForProductDetailsCustomOption(['custom-jacket', 'pants', 'waistcoat']);
          break;
      }
    } else if (nextStepName === 'confirm') {
      // for loop an object
      this.renderReviewDataBeforeSubmit();
    }

    console.log(this.customizeFormData);
    this.switchActiveStep(nextStepNavItem, nextStepNavItemButton, true);
  }

  setActiveForProductDetailsCustomOption(keys) {
    this.productDetailsCustomOptions.forEach(container => {
      for (const key of keys) {
        if (container.getAttribute('id').includes(key)) {
          container.classList.add(this.productDetailsCustomOptionActiveClass);
        }
      }
    });
  }

  validateStepOptions(stepOptions) {
    let isValid = false;
    let lastInputName = '';
    for (const option of stepOptions) {
      const currentInputName = option.getAttribute('name');
      if (lastInputName != '' && lastInputName !== option.getAttribute('name')) {
        if (!isValid) {
          break;
        }
        lastInputName = option.getAttribute('name');
      } else if (lastInputName === '') {
        lastInputName = option.getAttribute('name');
      }
      if (currentInputName === lastInputName && option.hasAttribute('checked')) {
        isValid = true;
      }
    }
    return isValid;
  }

  addDataToFormData(key, name, selectedValue, slug, price, imgSrc, parentKey = false, parentName = false) {
    if (parentKey) {
      this.customizeFormData[parentKey] = {
        ...this.customizeFormData[parentKey],
        name: parentName,
        [key]: {
          name,
          selectedValue,
          slug,
          imgSrc,
          price,
        },
      };
    } else {
      this.customizeFormData[key] = {
        name,
        selectedValue,
        slug,
        imgSrc,
        price,
      };
    }
  }

  handleSwitchStepWithNavItems(event, parent, _self) {
    const isNavItemActive = parent.classList.contains(this.navItemActiveClass);
    const isNavItemDisabled = parent.classList.contains(this.navItemDisabledClass);

    if (isNavItemActive || isNavItemDisabled) return;

    this.switchActiveStep(parent, _self);
  }

  switchActiveStep(navItem, navItemButton, isContinueClick = false) {
    const oldActiveNavItem = document.querySelector(`.${this.navItemActiveClass}`);
    oldActiveNavItem.classList.remove(this.navItemActiveClass);

    if (isContinueClick) {
      navItem.classList.remove(this.navItemDisabledClass);
      oldActiveNavItem.classList.add(`${this.stepsNavItemsSelector}--completed`);
    }

    navItem.classList.add(this.navItemActiveClass);

    const targetID = navItemButton.dataset.target;
    const targetStep = document.querySelector(`#${targetID}`);

    document.querySelector(`.${this.stepContentActiveClass}`).classList.remove(this.stepContentActiveClass);
    targetStep.classList.add(this.stepContentActiveClass);
  }

  handleProductDetailsCustomOptionTitleClick(event, _self) {
    const parent = _self.parentElement;
    parent.classList.toggle(this.productDetailsCustomOptionShowChildrenClass);
  }

  renderReviewDataBeforeSubmit() {
    const reviewDataContainer = document.querySelector('.customize-popup__review-selection');
    reviewDataContainer.innerHTML = '';
    const createElementWithClass = (elName, className = '') => {
      const el = document.createElement(elName);
      if (className) {
        if (Array.isArray(className)) {
          el.classList.add(...className);
        } else {
          el.classList.add(className);
        }
      }
      return el;
    };
    const appendElsToParent = (parent, ...children) => {
      children.forEach(child => {
        if(child instanceof HTMLImageElement && child.src === '') {
          return;
        }
        parent.appendChild(child);
      });
    };

    for (const [stepKey, stepSelection] of Object.entries(this.customizeFormData)) {
      const reviewDataItemEl = createElementWithClass('div', 'review-selection__step');
      reviewDataItemEl.setAttribute('aria-label', stepSelection.name);
      const itemTitleEl = createElementWithClass('span', 'review-selection__step-title');

      const itemSelectedOptionEl = createElementWithClass('div', ['review-selection__step-selected-option', 'step-option']);
      const selectedOptionImgEl = createElementWithClass('img', ['review-selection__step-img', 'step-option__feature-img']);
      const selectedOptionLabelEl = createElementWithClass('span', ['review-selection__step-label', 'step-option__name']);

      if (stepKey === 'choose-details' || stepKey === 'choose-fabric') {
        itemTitleEl.textContent = stepSelection.name;
        if (stepKey === 'choose-fabric' && selectedOptionLabelEl) {
          selectedOptionLabelEl.textContent = stepSelection.selectedValue;
          selectedOptionImgEl.src = stepSelection.imgSrc;
        }
        const itemOptionsWrapperEl = createElementWithClass('div', ['review-selection__step-options']);

        for (const [key, detail] of Object.entries(stepSelection)) {
          const notObjectKeys = ['name', 'selectedValue', 'slug', 'imgSrc', 'price'];
          if (!notObjectKeys.includes(key)) {
            const detailEl = createElementWithClass('div', ['review-selection__step-option', 'step-option']);
            detailEl.setAttribute('aria-label', detail.name);
            const detailLabelEl = createElementWithClass('span', ['review-selection__step-option-label', 'step-option__name']);
            const detailImgEl = createElementWithClass('img', ['review-selection__step-option-img', 'step-option__feature-img']);
            detailLabelEl.textContent = detail.selectedValue;
            detailImgEl.src = detail.imgSrc;

            appendElsToParent(detailEl, detailLabelEl, detailImgEl);
            itemOptionsWrapperEl.appendChild(detailEl);

            }
          }
          if(stepKey === 'choose-details') {
            appendElsToParent(reviewDataItemEl, itemTitleEl, itemOptionsWrapperEl);
          } else {
            reviewDataItemEl.appendChild(itemTitleEl);
            appendElsToParent(itemSelectedOptionEl, selectedOptionLabelEl, selectedOptionImgEl);
            appendElsToParent(reviewDataItemEl, itemSelectedOptionEl, itemOptionsWrapperEl);
          }
          } else {
          itemTitleEl.textContent = stepSelection.name;
          selectedOptionLabelEl.textContent = stepSelection.selectedValue;
          if (stepSelection.imgSrc) {
            selectedOptionImgEl.src = stepSelection.imgSrc;
          }

          reviewDataItemEl.appendChild(itemTitleEl);
          appendElsToParent(itemSelectedOptionEl, selectedOptionLabelEl, selectedOptionImgEl);
          reviewDataItemEl.appendChild(itemSelectedOptionEl);
          }

          reviewDataContainer.appendChild(reviewDataItemEl);
    }
  }
}
