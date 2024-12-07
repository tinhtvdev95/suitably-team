export default class StepsAndOptionSelection {
  constructor(){
    this.stepsNavItemsSelector = 'customize-popup__nav-item';
    this.navItemActiveClass = `${this.stepsNavItemsSelector}--active`;
    this.navItemDisabledClass = `${this.stepsNavItemsSelector}--disabled`;

    this.stepsContentSelector = 'customize-popup__step';
    this.stepContentActiveClass = `${this.stepsContentSelector}--active`;
    
    this.stepOptionsSelector = 'step-option';
    this.stepContentContinueBtnSelector = 'customize-popup__step-continue-btn';

    this.init();
    this.addEventListeners();
  }

  init() {
    this.stepsNavItems = document.querySelectorAll(`.${this.stepsNavItemsSelector}`);
    this.stepsContent = document.querySelectorAll(`.${this.stepsContentSelector}`);
    this.stepOptions = document.querySelectorAll(`.${this.stepOptionsSelector}`);
  }

  addEventListeners(){
    this.stepsNavItems.forEach(stepNavItem => {
      const navButton = stepNavItem.querySelector('a');
      navButton.addEventListener('click', event => {
        this.handleSwitchStepWithNavItems(event, stepNavItem, navButton);
      });
    });   

    this.stepsContent.forEach(stepContent => {
      const continueBtn = stepContent.querySelector(`.${this.stepContentContinueBtnSelector}`);
      continueBtn.addEventListener('click', event => {
        this.handleContinueStep(event, stepContent);
      });
    });

    this.stepOptions.forEach(stepOption => {
      stepOption.addEventListener('click', event => {
        this.handleSelectingOption(event, stepOption);
      });
    });
  }

  handleSelectingOption(event, stepOption) {
    if(event.target.tagName === "INPUT") {
      event.stopPropagation();
      return;
    }
    const input = stepOption.querySelector('input');
    const inputName = input.getAttribute('name');
    this.stepOptions.forEach(option => {
      const optionInput = option.querySelector('input');
      if(optionInput.name === inputName && optionInput.hasAttribute('checked')) {
        optionInput.removeAttribute('checked');
      }
    });
    input.setAttribute('checked', '1');
  };

  handleContinueStep(event, stepContent) {
    event.preventDefault();

    const nextStepIndex = Array.from(this.stepsContent).indexOf(stepContent) + 1;
    const nextStepNavItem = this.stepsNavItems[nextStepIndex];
    const nextStepNavItemButton = nextStepNavItem.querySelector('a');

    this.switchActiveStep(nextStepNavItem, nextStepNavItemButton, true);
  }

  validateStepOptions(stepContent) {
    const stepOptions = stepContent.querySelectorAll(`.${this.stepOptionsSelector} input`);
    let isValid = true;
    stepOptions.forEach(option => {
      if(!option.hasAttribute('checked')) {
        isValid = false;
      }
    });
    return isValid;
  }

  handleSwitchStepWithNavItems(event, parent, _self) {
    const isNavItemActive = parent.classList.contains(this.navItemActiveClass);
    const isNavItemDisabled = parent.classList.contains(this.navItemDisabledClass);

    if(isNavItemActive || isNavItemDisabled) return;

    this.switchActiveStep(parent, _self);
  }
  
  switchActiveStep(navItem, navItemButton, isContinueClick = false) {
    const oldActiveNavItem = document.querySelector(`.${this.navItemActiveClass}`);
    oldActiveNavItem.classList.remove(this.navItemActiveClass);
    
    if(isContinueClick) {
      navItem.classList.remove(this.navItemDisabledClass);
      oldActiveNavItem.classList.add(`${this.stepsNavItemsSelector}--completed`);
    }

    navItem.classList.add(this.navItemActiveClass);

    const targetID = navItemButton.dataset.target;
    const targetStep = document.querySelector(`#${targetID}`);

    document.querySelector(`.${this.stepContentActiveClass}`).classList.remove(this.stepContentActiveClass);
    targetStep.classList.add(this.stepContentActiveClass);
  }
}