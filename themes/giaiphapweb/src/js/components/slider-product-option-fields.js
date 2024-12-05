export default class SliderProductOptionFields {
    constructor() {
        this.sliderSelector = '.product-details__fit-customization .swiper-option-fields';
        this.navigationNextSelector = '.navigation .navigation__next';
        this.navigationPrevSelector = '.navigation .navigation__prev';
        this.labelSelector = "label.fit-options__label-image";
        this.containerSelector = ".fit-customization__container";
        this.fitOptionFieldsSelector = ".product-details__fit-option-fields";
        this.dataInputSelector = ".fit-options__input-data";
        this.optionFieldMainSelector = ".fit-option-fields__main";
        this.optionFieldTitleSelector = ".fit-option-fields-top__title";
        this.goBackButtonSelector = ".fit-option-fields-top__go-back";
        this.titleCustomize = ".fit-customization__title";
        this.hiddenClass = "hidden";    
        this.animationDuration = 500;

        this.navigationNextElement = document.querySelector(this.navigationNextSelector);

        this.formData = {};


        this.initSlider();
        this.initLabelListeners();
    }

    initSlider() {
        this.sliderProductOption = new Swiper(this.sliderSelector, {
            loop: false,
            navigation: {
                nextEl: this.navigationNextSelector,
                prevEl: this.navigationPrevSelector,
            },
            slidesPerView: 1,
            on: {
                init: function (swiper) {
                    // swiper.navigation.nextEl[0]?.classList.add('swiper-button-lock');
                    // swiper.navigation.prevEl[0]?.classList.add('swiper-button-lock');

                },
                navigationNext: function (swiper) {
                    document.querySelector('.fit-customization__title.active')?.classList.remove('active');
                    document.querySelector(`.fit-customization__title[data-index="${swiper.activeIndex}"]`)?.classList.add('active');
                },
                navigationPrev: function (swiper) {

                    document.querySelector('.fit-customization__title.active')?.classList.remove('active');
                    document.querySelector(`.fit-customization__title[data-index="${swiper.activeIndex}"]`)?.classList.add('active');
                }

            }
        });

    }

    initNavigationNextListener() {
        const navigationNextElement = document.querySelector(this.navigationNextSelector);
        if (navigationNextElement) {
            navigationNextElement.addEventListener('click', (event) => {
                const fitOptionFields = document.querySelector(this.fitOptionFieldsSelector);
                const container = document.querySelector(this.containerSelector);
                if (fitOptionFields && container) {
                    this.animateHide(fitOptionFields);
                    this.animateShow(container);
                }
            });
        }
    }

    initLabelListeners() {
        const labels = document.querySelectorAll(this.labelSelector);
        const container = document.querySelector(this.containerSelector);
        const fitOptionFields = document.querySelector(this.fitOptionFieldsSelector);
        const rawData = document.querySelector(this.dataInputSelector)?.value;
        const fitOptionsData = rawData ? JSON.parse(rawData) : [];

        const labelOption = document.querySelectorAll(this.labelOptionSelector);

        if (fitOptionFields) {
            fitOptionFields.classList.add(this.hiddenClass);
        }

        if (labels.length && container && fitOptionFields) {
            labels.forEach((label) => {
                label.addEventListener("click", (event) => {
                    this.handleLabelClick(event, label, fitOptionFields, fitOptionsData, container);
                });
            });
        }

        this.initGoBackListener(container, fitOptionFields);

        this.initNavigationNextListener(container, fitOptionFields);

    }

    handleLabelClick(event, label, fitOptionFields, fitOptionsData, container) {
        if (event.target.tagName === "INPUT") {
            event.stopPropagation();
            return;
        }

        const selectedInput = label.querySelector(".fit-options__input");
        const selectedOptionTitle = selectedInput?.value || "";

        this.updateTitle(selectedOptionTitle);

        const selectedOption = fitOptionsData.find(option => option.title === selectedOptionTitle);
        if (!selectedOption) return;

        this.updateOptions(selectedOptionTitle, fitOptionsData);

        this.animateHide(container);
        this.animateShow(fitOptionFields);
    }

    updateTitle(title) {
        const titleElement = document.querySelector(this.optionFieldTitleSelector);
        if (titleElement) {
            titleElement.textContent = title;
        }
    }

    updateOptions(selectedTitle, optionsData) {
        const container = document.querySelector(this.optionFieldMainSelector);
        if (!container) return;

        // container.innerHTML = "";

        const selectedOption = optionsData.find(option => option.title === selectedTitle);
        if (!selectedOption) return;

        if (container.querySelector('.fit-option-fields__main-label')) return;

        selectedOption.option.forEach((subOption) => {
            const template = this.generateOptionTemplate(subOption);
            container.append(template);
        });
    }

    generateOptionTemplate(subOption) {
        let { name = "", price = "", url_image = "" } = subOption;
        price = price || 'Free';
        const labelEl = document.createElement('label');

        labelEl.classList.add('fit-option-fields__main-label');
        const textHtml = `<p class="fit-option-fields__main-option-name">${name}</p>
                <input class="fit-option-fields__main-input" type="radio" name="fit_option" value="${name}" data-price="${price}">
                ${url_image}
                <div class="fit-option-fields__main-bottom">
                <p class="fit-option-fields__main-option-price">${price}</p>
                <img class="img-tick" src="/wp-content/uploads/2024/11/checkmark_white.png" />
                </div>`;


        labelEl.innerHTML = textHtml;


        labelEl.addEventListener('click', (e) => {
            if (e.target.tagName === "INPUT") {
                e.stopPropagation();
                return;
            }

            const inputElement = labelEl.querySelector('input[type="radio"]');
            const price = inputElement ? inputElement.getAttribute('data-price') : null;
            const value = inputElement ? inputElement.value : '';

            this.formData.fitOption = {
                price,
                value
            };
            console.log(this.formData);

            document.querySelector('.img-tick.active')?.classList.remove('active');

            const imgTick = labelEl.querySelector('.img-tick');
            imgTick.classList.add('active');

            // const nextButton = document.querySelector(this.navigationNextSelector);
            // nextButton?.classList.remove('swiper-button-lock');

        });

        return labelEl;

    }

    animateHide(element) {
        if (!element || element.classList.contains(this.hiddenClass)) return;

        const height = element.scrollHeight;
        element.style.height = `${height}px`;
        element.offsetHeight;
        element.style.height = "0";
        element.style.opacity = "0";

        setTimeout(() => {
            element.classList.add(this.hiddenClass);
            element.style.height = null;
            element.style.opacity = null;
        }, this.animationDuration);
    }

    animateShow(element) {
        if (!element || !element.classList.contains(this.hiddenClass)) return;

        element.classList.remove(this.hiddenClass);
        element.style.height = "0";
        element.style.opacity = "0";

        const height = element.scrollHeight;
        element.style.height = `${height}px`;
        element.style.opacity = "1";

        setTimeout(() => {
            element.style.height = "auto";
        }, this.animationDuration);
    }

    initGoBackListener(container, fitOptionFields) {
        const goBackButton = document.querySelector(this.goBackButtonSelector);
        if (goBackButton) {
            goBackButton.addEventListener("click", () => {
                this.animateHide(fitOptionFields);
                this.animateShow(container);
            });
        }
    }
}