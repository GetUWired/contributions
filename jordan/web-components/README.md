# Spinner Web Components

## Setup
You must create a script that registers the components with the browser. This is done by calling the `define` function on the `customElements` object. The first argument is the name of the component, the second is the class that extends `HTMLElement` that defines the component.

```javascript
customElements.define('spinner-input', SpinnerInput);
customElements.define('number-spinner', NumberSpinner);
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