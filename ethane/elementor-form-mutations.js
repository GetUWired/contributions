/**
 * The typical Elementor button is constructed like this:
 * 
<button id="{ID_set_in_the_builder}">
  <div>
    <span class="icon">
    <span>Some text</span>
  </div>
</button>
 */

let value;

const checkForElement = () => {
  const targetElement = document.querySelector(
    '.elementor-message.elementor-message-success'
  );
  if (
    targetElement &&
    targetElement.textContent.includes(
      'Your submission was successful.'
    )
  ) {
    try {
      // Any number of select values with
      // associated URLs can be added here.
      if (value === '{select_value_1}') {
        window.location.replace(
          '{url_for_select_value_1}'
        );
      } else {
        window.location.replace(
          '{url_for_other_selects}'
        );
      }
    } catch (error) {
      console.error('Error during redirection:', error);
    }

    observer.disconnect();
  }
};
const observerConfig = { childList: true, subtree: true };

const observer = new MutationObserver(checkForElement);

observer.observe(document.body, observerConfig);
/**
 * Please note the e.target.parentNode.parentNode.id inside 
 * the event listener. Elementor buttons are weird and a lot 
 * of times it is necessary to add the <span> it likes to 
 * throw in into the event delegation. 
 */
document.addEventListener('click', (e) => {
  if (
    e.target.id === '{btn_id_set_in_builder}' ||
    e.target.parentNode.parentNode.id ===
      'btn_id_set_in_builder'
  ) {
    const form = document.querySelector(
      '{unique_form_selector}'
    );
    const select = form.querySelector('select');
    value = select.value;
  }
});
