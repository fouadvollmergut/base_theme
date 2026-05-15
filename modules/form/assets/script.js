/**
 * Form module
 *
 * Adds live validation, file preview, checkbox-wrapper click handling and
 * AJAX submission to the GDYMC Form module. The form is sent to a theme-
 * owned `admin-ajax.php` action (`fvt_form_send`, see
 * `modules/form/functions.php`) which dispatches the message via
 * WordPress' native `wp_mail()` function.
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

    if (this.submit) {
      // Capture phase guarantees we run before any other click handler that
      // might be bound to the submit button.
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
    this.fields.forEach(field => {
      if (this.isIgnored(field)) {
        return;
      }

      this.validateField(field);
    });
  }

  firstMissingRequired () {
    for (const field of this.fields) {
      if (this.isIgnored(field) || !field.required) {
        continue;
      }

      // Only block submission for empty required fields. Other constraint
      // violations (e.g. an invalid email format) still surface via the
      // live `.invalid` styling but do not prevent submission.
      if (field.validity && field.validity.valueMissing) {
        return field;
      }
    }

    return null;
  }

  handleSubmit (event) {
    // Always prevent the native form submit / page navigation — submission
    // happens via fetch to the theme's AJAX endpoint.
    event.preventDefault();
    event.stopImmediatePropagation();

    // Refresh the live `.invalid` state for every field so the user sees
    // all current validation errors, not only the missing required ones.
    this.validateAll();

    const firstMissing = this.firstMissingRequired();

    if (firstMissing) {
      if (typeof firstMissing.focus === 'function') {
        firstMissing.focus();
      }
      return;
    }

    this.send();
  }

  send () {
    const url = this.form.dataset.ajaxUrl;
    const action = this.form.dataset.action;

    if (!url || !action) {
      return;
    }

    const data = new FormData(this.form);
    data.append('action', action);
    data.append('nonce', this.form.dataset.nonce || '');
    // The recipient (and subject) are deliberately not sent from the
    // client — the AJAX handler resolves them server-side from the
    // GDYMC per-module options identified by the module + object IDs.
    data.append('module_id', this.form.dataset.moduleId || '');
    data.append('object_id', this.form.dataset.objectId || '');

    this.form.classList.remove('success', 'failure');
    this.form.classList.add('loading', 'disabled');

    fetch(url, {
      method: 'POST',
      credentials: 'same-origin',
      body: data
    })
      .then(response => response.json().catch(() => ({ success: false })).then(json => ({ ok: response.ok, json })))
      .then(({ ok, json }) => {
        this.form.classList.remove('loading');

        if (ok && json && json.success) {
          this.form.classList.add('success');
        } else {
          this.form.classList.remove('disabled');
          this.form.classList.add('failure');
        }
      })
      .catch(() => {
        this.form.classList.remove('loading', 'disabled');
        this.form.classList.add('failure');
      });
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
