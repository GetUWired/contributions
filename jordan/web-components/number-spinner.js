import {SpinnerInput} from './spinner-input.js';
export class NumberSpinner extends SpinnerInput {
    set min(value) {
        if(value === null || value === undefined || value === '') {
            this.setAttribute('min', value);
        } else {
            this.setAttribute('min', 0);
        }
    }

    get min() {
        return this.getAttribute('min');
    }

    set max(value) {
        if(value === null || value === undefined || value === '') {
            this.setAttribute('max', value);
        } else {
            this.setAttribute('max', 100);
        }
    }

    get max() {
        return this.getAttribute('max');
    }

    set step(value) {
        if(value === null || value === undefined || value === '' || value === 0) {
            this.setAttribute('step', value);
        }
        else {
            this.setAttribute('step', 1);
        }
    }

    get step() {
        return this.getAttribute('step');
    }

    set padstart(value) {
        if(value === null || value === undefined || value === '') {
            this.setAttribute('padstart', value);
        } else {
            this.setAttribute('padstart', 1);
        }
    }

    get padstart() {
        return this.getAttribute('padstart');
    }

    set decimals(value) {
        if(value === null || value === undefined || value === '') {
            this.setAttribute('decimals', value);
        } else {
            this.setAttribute('decimals', 0);
        }
    }

    get decimals() {
        return this.getAttribute('decimals');
    }

    constructor(){
        super();
        this.pattern = "[0-9]+"
    }

    updateDatalist(){
        this.options = [];
        this.DatalistOptions = this.internalDatalist.querySelector('.datalist-options');
        this.DatalistOptions.innerHTML = '';
        const step = parseFloat(this.step) || 1;
        const min = parseFloat(this.min) || 0;
        const max = parseFloat(this.max) || 100;
        const padstart = parseInt(this.padstart) || 1;
        const decimals = parseInt(this.decimals) || 0;
        const numberFormat = new Intl.NumberFormat('en-US', {minimumFractionDigits: decimals, maximumFractionDigits: decimals, minimumIntegerDigits: padstart});
        for (let i = min; parseFloat(i.toFixed(decimals)) <= max; i += step) {
            const option = document.createElement('button');
            option.setAttribute('type', 'button');
            option.setAttribute('part', 'option');
            option.innerHTML = numberFormat.format(i).replace(/,/g, '');
            option.dataset.optionValue = numberFormat.format(i).replace(/,/g, '');
            option.classList.add('option');
            this.DatalistOptions.appendChild(option);
            this.options.push(option);
        }

        this.setItemHeight();
    }
}

