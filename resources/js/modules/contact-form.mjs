import $ from 'jquery/slim';
import copy from '../../data/copy.it.json';
import { fill, plural } from '../utils/copy.mjs';
import { validateContact } from '../utils/validators.mjs';

const FIELDS = ['name', 'email', 'message'];
const SIMULATED_SEND_MS = 700;

function errorText(error) {
    if (error.key === 'messageShort') return plural(copy.contact.errors.messageShort, error.missing);
    return copy.contact.errors[error.key];
}

export function initContactForm() {
    const $form = $('[data-contact-form]');
    if (!$form.length) return;

    const $summary = $form.find('[data-contact-summary]');
    const $submit = $form.find('[data-contact-submit]');
    const $submitLabel = $submit.find('[data-submit-label]');
    const $success = $('[data-contact-success]');
    const $successText = $success.find('[data-success-text]');
    const touched = {};

    // The markup keeps the native constraints for when this script does not run.
    $form.attr('novalidate', '');

    const findControl = (field) => $form.find(`[name="${field}"]`);
    const readValues = () => ({
        name: String(findControl('name').val()),
        email: String(findControl('email').val()),
        message: String(findControl('message').val()),
    });

    function showError(field, error) {
        const id = `contact-${field}`;
        const $error = $(`#${id}-error`);
        $error.find('[data-error-text]').text(errorText(error));
        $error.prop('hidden', false);
        findControl(field).attr({ 'aria-invalid': 'true', 'aria-describedby': `${id}-error ${id}-hint` });
    }

    function clearError(field) {
        const id = `contact-${field}`;
        $(`#${id}-error`).prop('hidden', true);
        findControl(field).removeAttr('aria-invalid').attr('aria-describedby', `${id}-hint`);
    }

    function renderError(field, error) {
        if (error) showError(field, error);
        else clearError(field);
    }

    function validateField(field) {
        renderError(field, validateContact(readValues())[field]);
    }

    function showForm() {
        $success.prop('hidden', true);
        $form.prop('hidden', false);
        $submit.prop('disabled', false);
        $submitLabel.text(copy.contact.submit);
    }

    function showSuccess(sender) {
        $form.prop('hidden', true);
        $successText.text(fill(copy.contact.success.text, sender));
        $success.prop('hidden', false).trigger('focus');
    }

    function submit() {
        const errors = validateContact(readValues());
        FIELDS.forEach((field) => {
            touched[field] = true;
            renderError(field, errors[field]);
        });

        const invalidFields = FIELDS.filter((field) => errors[field]);
        if (invalidFields.length > 0) {
            $summary.text(plural(copy.contact.errors.summary, invalidFields.length));
            findControl(invalidFields[0]).trigger('focus');
            return;
        }

        $summary.text('');
        $submit.prop('disabled', true);
        $submitLabel.text(copy.contact.sending);
        const { name, email } = readValues();
        const sender = { name: name.trim(), email: email.trim() };
        window.setTimeout(() => showSuccess(sender), SIMULATED_SEND_MS);
    }

    FIELDS.forEach((field) => {
        findControl(field).on('blur', () => {
            if (!findControl(field).val() && !touched[field]) return; // do not shout at people tabbing through
            touched[field] = true;
            validateField(field);
        });
        findControl(field).on('input', () => {
            if (touched[field]) validateField(field);
        });
    });

    $form.on('submit', (event) => {
        event.preventDefault();
        submit();
    });

    $(document).on('contact:prefill', (event, message) => {
        showForm();
        findControl('message').val(message);
        clearError('message');
    });

    $success.on('click', '[data-contact-again]', () => {
        $form[0].reset();
        FIELDS.forEach((field) => {
            touched[field] = false;
            clearError(field);
        });
        showForm();
        findControl('name').trigger('focus');
    });
}
