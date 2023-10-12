# Spinner Web Components

## Setup
You must create a script that registers the components with the browser. This is done by calling the `define` function on the `customElements` object. The first argument is the name of the component, the second is the class that extends `HTMLElement` that defines the component.

The script must be included as a module in the HTML file before the component is used.

```html
<script type="module" src="spinner-input.js"></script>
<script type="module" src="number-spinner.js"></script>
<script>
customElements.define('spinner-input', SpinnerInput);
customElements.define('number-spinner', NumberSpinner);
</script>
```
## Usage

```html
<spinner-input datalist="data"></spinner-input>
<datalist id="data">
    <option value="a"></option>
    <option value="b"></option>
    <option value="c"></option>
    <option value="d"></option>
    <option value="e"></option>
</datalist>

<number-spinner min="0" max="100" step="5"></number-spinner>
```