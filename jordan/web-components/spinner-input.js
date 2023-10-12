
export class SpinnerInput extends HTMLElement {

    static get observedAttributes() {
        return ['value'];
    }

    get value() {
        return this.getAttribute('value');
    }

    set value(val) {
        if(val !== null && val !== undefined){
            let newValue = val;
            if(this.pattern !== null && this.pattern !== undefined && this.pattern !== ''){
                if(new RegExp(this.pattern).test(newValue)){
                    newValue = val.toString().match(new RegExp(this.pattern))[0];
                } else {
                    return;
                }
            }
            this.setAttribute('value', newValue);
        } else {
            this.removeAttribute('value');
        }
        
    }

    get datalist() {
        return this.getAttribute('datalist');
    }

    set datalist(val) {
        this.setAttribute('datalist', val);
        this.updateDatalist();
    }

    get placeholder() {
        return this.getAttribute('placeholder');
    }

    set placeholder(val) {
        if(val){
            this.setAttribute('placeholder', val);
            this.input.placeholder = val;
        } else {
            this.removeAttribute('placeholder');
            this.input.removeAttribute('placeholder');
        }
    }

    get pattern() {
        return this.getAttribute('pattern');
    }

    set pattern(val) {
        if(val){
            this.setAttribute('pattern', val);
            this.input.pattern = val;
        } else {
            this.removeAttribute('pattern');
            this.input.removeAttribute('pattern');
        }
    }

    constructor() {
        super();
        this.attachShadow({ mode: 'open' });
        this.listeningToPosition = false;
        this.shadowRoot.innerHTML = this.template();
        this.internalDatalist = this.shadowRoot.querySelector('.datalist');
        this.focusElement = this.shadowRoot.querySelector('.focus');
        this.input = this.shadowRoot.querySelector('.input');
        this.datalistScroll = this.internalDatalist.querySelector('.datalist-scroll');
        this.datalistOptions = this.internalDatalist.querySelector('.datalist-options');
        this.scrolling = false;
        this.typedValue = '';
        this.autoScrolling = false;
        this.decrementButton = this.shadowRoot.querySelector('.decrement');
        this.incrementButton = this.shadowRoot.querySelector('.increment');
        this.optionHeight = 12;
        this.keyUpDebounce = null;
        this.datalistScroll.addEventListener('scroll', this.handleScroll.bind(this));
        this.input.addEventListener('change', this.handleChange.bind(this));
        this.focusElement.addEventListener('keydown', this.handleKeydown.bind(this));
        this.focusElement.addEventListener('keyup', this.handleKeyup.bind(this));
        this.updateDatalistLocation = this.updateDatalistLocation.bind(this);
        this.focusElement.addEventListener('focusin', () => {
            this.typedValue = '';
            this.setItemHeight();
            this.updateDatalistLocation();
            this.internalDatalist.classList.add('shown');
            this.listeningToPosition = true;
            this.positionListener(); 
        });

        this.focusElement.addEventListener('focusout', () => {
            this.typedValue = '';
            this.internalDatalist.classList.remove('shown');
            this.listeningToPosition = false;
            
        });
        
        this.decrementButton.addEventListener('click', this.handleDecrement.bind(this));
        this.incrementButton.addEventListener('click', this.handleIncrement.bind(this));
        
        this.updateDatalist();
    }

    styles() {
        return `
            <style>
            :host {
                display: inline-block;
                background-color: #fff;
                overflow: visible;

            }

            .spinner-input {
                --item-height: 30px;
                --button-visibility: visible;
                height: 100%;
            }

            .input-presentation {
                display: flex;
                align-items: stretch;
                height: 100%;
            }

             .input-presentation input {
                border: none;
                flex: 1;
                padding: 0;
                outline: none;
             }

            .button-presentation {
                display: flex;
                flex-direction: column;
                max-height: 100%;
                position: absolute;
                right: 0;
                top: 0;
                bottom: 0;
                overflow: hidden;
                position: relative;
            }

            .button-presentation button {
                border: none;
                flex: 1;
                padding: 0;
                line-height: 1;
                font-size: 10px;
                background-color: transparent;
            }

            .datalist {
                --item-height: 30px;
                box-shadow: 0 0 5px 0 rgba(0, 0, 0, 0.1);
                background-color: #fff;
                height: calc(var(--item-height) * 5);
                position: relative;
                visibility: hidden;
                position: fixed;
                pointer-events: none;
                top: var(--vertical-offset, 0);
                left: var(--horizontal-offset, 0);
                z-index: 1000;
                width: var(--width, 100%);
            }

            .datalist.shown {
                visibility: visible;
                pointer-events: auto;
            }

            .datalist-scroll {
                height: calc(var(--item-height) * 5);
                overflow-y: auto;
                -ms-overflow-style: none;
                scrollbar-width: none;
            }

            .datalist-options::-webkit-scrollbar {
                display: none;
            }

            .option {
                display: block;
                padding: 5px;
                cursor: pointer;
                width: 100%;
                border: none;
                background-color: transparent;
                outline: none;
                text-align: center;
            }

            .selection-indicator {
                position: absolute;
                top: 50%;
                left: 0;
                width: 100%;
                height: var(--item-height);
                transform: translateY(-50%);
                border-top: 1px solid #ccc;
                border-bottom: 1px solid #ccc;
                pointer-events: none;
            }

            .gradient-overlay {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                bottom: 0;
                background: linear-gradient(to bottom, rgba(255,255,255,0.75) 0%,rgba(255,255,255,0) 40%,rgba(255,255,255,0) 60%, rgba(255,255,255,0.75) 100%);
                pointer-events: none;
            }

            .option:first-child {
                margin-top:calc(var(--item-height) * 2);
            }

            .option:last-child {
                margin-bottom:calc(var(--item-height) * 2);
            }

            </style>`
    }

    template(){
        return `
            ${this.styles()}
            <div class="spinner-input focus" tabindex="0">
                <div class="input-presentation">
                    <span class="input" part="input" tabindex="0">${this.value || ''}</span>
                    <div class="button-presentation" part="buttons">
                        <button class="decrement" part="decrement">&#9650;</button>
                        <button class="increment" part="increment">&#9660;</button>
                    </div>
                </div>
                <div class="datalist" part="datalist">
                    <div class="datalist-scroll">
                        <div class="datalist-options"></div>
                    </div>
                    <div class="selection-indicator" part="selection-indicator"></div>
                    <div class="gradient-overlay"></div>
                </div>
            </div>
        `;
    }


    updateDatalist(){
        this.datalistElement = document.querySelector(`#${this.datalist}`);
        this.DatalistOptions = this.internalDatalist.querySelector('.datalist-options');
        this.options = [];
        if(this.datalistElement !== null && this.datalistElement !== undefined){
            this.datalistElement.querySelectorAll('option').forEach((item) => {
                const option = document.createElement('button');
                option.setAttribute('type', 'button');
                option.setAttribute('part', 'option');
                option.innerHTML = item.value;
			    option.dataset.optionValue = item.value;
			    option.classList.add('option');
                if (item.selected) {
                    option.classList.add('selected');
                }
                if (item.disabled) {
                    option.classList.add('disabled');
                }
                this.DatalistOptions.appendChild(option);
                this.options.push(option);
            });

            this.setItemHeight();
        }
    }


    setItemHeight(){
        this.optionHeight = Math.max(this.internalDatalist.querySelector('.option:first-child').offsetHeight, 12);
        this.internalDatalist.style.setProperty('--item-height', `${this.optionHeight}px`);
    }

    async handleScroll(e){
        const { scrollTop } = this.datalistScroll;
        const itemHeight = this.options[0].offsetHeight;
        const newIndex = Math.round(scrollTop / itemHeight);
        if(this.options[newIndex].dataset.optionValue !== this.value && !this.autoScrolling){
            this.value = this.options[newIndex].dataset.optionValue;
        } 

        if(!this.scrolling){
            this.waitForScrollEnd().then(() => {
                if(!this.autoScrolling){
                    this.smoothScroll();
                }
            });
        }
        
    }

    updateDatalistLocation(){
        const { top, left } = this.getBoundingClientRect();
        let relativeTop = top;
        const inputBottom = top + this.offsetHeight;
        const height  = this.internalDatalist.offsetHeight;
        const { innerHeight } = window;
        const bottom = top + height;
        const overFlow = bottom - innerHeight + 50;
        const offsetBottom = this.offsetTop + this.offsetHeight;
        let verticalOffset = inputBottom;
        if(overFlow > 0){
            verticalOffset -= overFlow;
        }
        this.internalDatalist.style.setProperty('--vertical-offset', `${verticalOffset}px`);
        this.internalDatalist.style.setProperty('--horizontal-offset', `${left}px`);
        this.internalDatalist.style.setProperty('--width', `${this.offsetWidth}px`);
    }

    positionListener(){
        let lastPosition = this.getBoundingClientRect();
        let lastDocumentScroll = document.documentElement.scrollTop;
        const checkPosition = () => {
            const position = this.getBoundingClientRect();
            if(position.top !== lastPosition.top || position.left !== lastPosition.left){
                this.updateDatalistLocation();
                lastPosition = position;
            }
            if(this.listeningToPosition){
                requestAnimationFrame(checkPosition);
            }
        }
        requestAnimationFrame(checkPosition);
    }

    waitForScrollEnd(){
        this.scrolling = true;
        let lastScrollTop = this.datalistScroll.scrollTop;
        let lastChangeFrame = 0;
        return new Promise((resolve) => {
            const checkScroll = (frames) => {
                if (frames - lastChangeFrame > 20) {
                    this.scrolling = false;
                    resolve();
                } else {
                    if(this.datalistScroll.scrollTop != lastScrollTop){
                        lastChangeFrame = frames;
                        lastScrollTop = this.datalistScroll.scrollTop;
                    }
                    
                    requestAnimationFrame(checkScroll.bind(null, frames + 1));
                }
            }
            checkScroll(0);
        });

    }

    smoothScroll(){
        this.autoScrolling = true;
        let selectedOption = this.options.findIndex((item) => item.dataset.optionValue === this.value);
        if(selectedOption === -1) selectedOption = 0;
        const top = this.options[0].offsetHeight * selectedOption;
        return new Promise((resolve, reject) => {
            this.datalistScroll.scroll({top, behavior: 'smooth'});
            const failed = setTimeout(() => {
                this.autoScrolling = false;
                resolve();
            }, 2000);

            const scrollHandler = (e) => {
                if(this.datalistScroll.scrollTop === top){
                    this.datalistScroll.removeEventListener('scroll', scrollHandler);
                    clearTimeout(failed);
                    this.autoScrolling = false;
                    resolve();
                }
            }
            if(this.datalistScroll.scrollTop === top){
                clearTimeout(failed);
                this.autoScrolling = false;
                resolve();
            } else {
                this.datalistScroll.addEventListener('scroll', scrollHandler);
            }
        });

    }

    handleChange(e){
        this.value = e.target.value;
    }

    handleKeyup(e){
        if(e.key !== 'ArrowDown' && e.key !== 'ArrowUp' && e.key !== 'Backspace' && e.key !== 'Delete'){
            this.typedValue = this.typedValue + e.key;
        } else if(e.key === 'Backspace' || e.key === 'Delete'){
            this.typedValue = this.typedValue.slice(0, -1);
        }
        clearTimeout(this.keyUpDebounce);
        this.keyUpDebounce = setTimeout(() => {
            this.value = this.typedValue;
        }, 200);

    }

    handleKeydown(e){
        if(e.key === 'ArrowDown'){
            this.handleIncrement(e);
            return;
        }
        if(e.key === 'ArrowUp'){
            this.handleDecrement(e);
            return;
        }
    }

    handleDecrement(e){
        e.preventDefault();
        let selectedOption = this.options.findIndex((item) => item.dataset.optionValue === this.value);
        if(selectedOption === -1) selectedOption = 0;
        if(selectedOption > 0){
            this.value = this.options[selectedOption - 1].dataset.optionValue;
        } 
    }

    handleIncrement(e){
        e.preventDefault();
        let selectedOption = this.options.findIndex((item) => item.dataset.optionValue === this.value);
        if(selectedOption < this.options.length - 1){
            this.value = this.options[selectedOption + 1].dataset.optionValue;
        } 
    }

    attributeChangedCallback(name, oldValue, newValue) {
        if(name === 'value'){
            this.input.innerHTML = newValue;
            this.waitForScrollEnd().then(() => {
                this.smoothScroll().then(() => {
                    this.shadowRoot.dispatchEvent(new CustomEvent('change', {detail: {value: newValue},bubbles: true, composed: true }));
                });
            });
        }
    }
}


