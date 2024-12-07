export default class StepsAndOptionSelection {
  constructor(customizeFormData) {
    this.stepsNavItemsSelector = 'customize-popup__nav-item';
    this.navItemActiveClass = `${this.stepsNavItemsSelector}--active`;
    this.navItemDisabledClass = `${this.stepsNavItemsSelector}--disabled`;

    this.stepsContentSelector = 'customize-popup__step';
    this.stepContentActiveClass = `${this.stepsContentSelector}--active`;

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
          document.querySelectorAll(`.${this.productDetailsCustomOptionActiveClass}`).forEach(container => {
            container.classList.remove(this.productDetailsCustomOptionActiveClass);
          });
        }
        target[key] = value;
        return true;
        
      }
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
      stepOption.addEventListener('click', event => {
        this.handleSelectingOption(event, stepOption);
      });
    });

    this.productDetailsCustomTitles.forEach(title => {
      title.addEventListener('click', event => {
        this.handleProductDetailsCustomOptionTitleClick(event, title);
      });
    });
  }

  handleSelectingOption(event, stepOption) {
    if (event.target.tagName === 'INPUT') {
      event.stopPropagation();
      return;
    }
    const input = stepOption.querySelector('input');
    const inputName = input.getAttribute('name');
    this.stepOptions.forEach(option => {
      const optionInput = option.querySelector('input');
      if (optionInput.name === inputName && optionInput.hasAttribute('checked')) {
        optionInput.removeAttribute('checked');
      }
    });
    input.setAttribute('checked', '1');
  }

  handleContinueStep(event, stepContent) {
    event.preventDefault();
    const NEXT_STEP_INDEX = 1;

    const stepOptions = stepContent.querySelectorAll(`.${this.stepOptionsSelector} input`);
  
    if (!this.validateStepOptions(stepOptions)) return;

    stepOptions.forEach(option => {
      if (option.hasAttribute('checked')) {
        this.addDataToFormData(option.getAttribute('name'), option.value, option.dataset.slug);
      }
    });

    // console.log(this.customizeFormData);

    const nextStepIndex = Array.from(this.stepsContent).indexOf(stepContent) + NEXT_STEP_INDEX;
    const nextStepNavItem = this.stepsNavItems[nextStepIndex];
    const nextStepNavItemButton = nextStepNavItem.querySelector('a');
    const nextStepContent = this.stepsContent[nextStepIndex];
    const stepName = nextStepContent.getAttribute('id');

    if(stepName === 'choose-details') {
      console.log(this.customizeFormData['choose-options']);
      switch(this.customizeFormData['choose-options'].slug) {
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
    }

    this.switchActiveStep(nextStepNavItem, nextStepNavItemButton, true);
  }

  setActiveForProductDetailsCustomOption(keys){
    this.productDetailsCustomOptions.forEach(container => {
      for(const key of keys) {
        if(container.getAttribute('id').includes(key)) {
          container.classList.add(this.productDetailsCustomOptionActiveClass);
        }
      }
    })
  }

  validateStepOptions(stepOptions) {
    let isValid = false;
    let lastInputName = '';
    for (const option of stepOptions) {
      const currentInputName = option.getAttribute('name');
      if (lastInputName !== option.getAttribute('name')) {
        if (isValid) {
          break;
        }
        lastInputName = option.getAttribute('name');
      }
      if (currentInputName === lastInputName && option.hasAttribute('checked')) {
        isValid = true;
      }
    }
    return isValid;
  }

  addDataToFormData(key, value, slug) {
    this.customizeFormData[key] = {
      value,
      slug,
    };
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
}

