/**
 * Form module
 *
 * Adds live validation, file preview and checkbox-wrapper click handling
 * to the GDYMC Form module. Submission is delegated to the mailer plugin
 * (via the `e-click="sendForm"` attribute on the submit button).
 *
 * Validation relies on the HTML5 Constraint Validation API, so any
 * `required`, `type="email"`, `pattern`, `minlength`, `maxlength`, ...
 * attribute placed on a field is honoured automatically.
 */
class Form {
  constructor (form) {
    this.form = form;
    this.fileInput = form.querySelector('input[type="file"]');
    this.filePreview = form.querySelector('.filePreview');
    this.submit = form.querySelector('input[type="submit"], button[type="submit"]');
    this.fields = form.querySelectorAll('input, select, textarea');

    this.init();
  }

  init () {
    // Disable native bubbles, we render our own `.invalid` state.
    this.form.setAttribute('novalidate', 'novalidate');

    this.fields.forEach(field => {
      if (this.isIgnored(field)) {
        return;
      }

      field.addEventListener('focusout', () => this.validateField(field));

      // Checkboxes/radios/selects/files only fire `change`, text fields fire
      // both `change` and `input`. Listening to both keeps the `.invalid`
      // state in sync regardless of the field type.
      field.addEventListener('change', () => this.validateField(field));
      field.addEventListener('input', () => {
        if (field.classList.contains('invalid')) {
          this.validateField(field);
        }
      });
    });

    if (this.fileInput && this.filePreview) {
      this.fileInput.addEventListener('change', () => this.renderFilePreview());
    }

    // Allow clicking anywhere in the privacy/checkbox row to toggle the box.
    this.form.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
      const wrapper = checkbox.parentElement;

      if (!wrapper) {
        return;
      }

      wrapper.addEventListener('click', event => this.toggleChildCheckbox(event, wrapper, checkbox));
    });

    // Block submission as long as the form has invalid fields. The submit
    // button carries `e-click="sendForm"`, which the mailer plugin binds to
    // a click handler — using the capture phase + `stopImmediatePropagation`
    // guarantees that handler never fires for an invalid form.
    if (this.submit) {
      this.submit.addEventListener('click', event => this.handleSubmit(event), true);
    }
  }

  isIgnored (field) {
    return field.type === 'submit' || field.type === 'button' || field.type === 'hidden';
  }

  validateField (field) {
    const valid = field.checkValidity();

    field.classList.toggle('invalid', !valid);

    return valid;
  }

  validateAll () {
    let firstInvalid = null;

    this.fields.forEach(field => {
      if (this.isIgnored(field)) {
        return;
      }

      if (!this.validateField(field) && !firstInvalid) {
        firstInvalid = field;
      }
    });

    return firstInvalid;
  }

  handleSubmit (event) {
    const firstInvalid = this.validateAll();

    if (!firstInvalid) {
      return;
    }

    event.preventDefault();
    event.stopImmediatePropagation();

    if (typeof firstInvalid.focus === 'function') {
      firstInvalid.focus();
    }
  }

  renderFilePreview () {
    const files = this.fileInput.files;

    this.filePreview.innerHTML = '';
    this.filePreview.classList.toggle('populated', files.length > 0);

    Array.from(files).forEach(file => {
      const item = document.createElement('div');
      item.className = 'filePreviewItem';

      const name = document.createElement('span');
      // Use textContent to avoid any markup in user-supplied file names.
      name.textContent = file.name;

      item.appendChild(name);
      this.filePreview.appendChild(item);
    });
  }

  toggleChildCheckbox (event, wrapper, checkbox) {
    // Native handling already covers clicks on the checkbox itself, on the
    // associated <label> and on links inside the label.
    if (event.target === checkbox || event.target.closest('a, label, input')) {
      return;
    }

    checkbox.checked = !checkbox.checked;
    checkbox.dispatchEvent(new Event('change', { bubbles: true }));
  }
}

export default Form;
