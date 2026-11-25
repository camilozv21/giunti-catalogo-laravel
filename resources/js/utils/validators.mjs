const NAME_MIN = 2;
const MESSAGE_MIN = 10;
const EMAIL_PATTERN = /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/;

export function validateContact(values) {
    const errors = {};
    const name = String(values.name).trim();
    const email = String(values.email).trim();
    const message = String(values.message).trim();

    if (name === '') errors.name = { key: 'nameRequired' };
    else if (name.length < NAME_MIN) errors.name = { key: 'nameShort' };

    if (email === '') errors.email = { key: 'emailRequired' };
    else if (!EMAIL_PATTERN.test(email)) errors.email = { key: 'emailInvalid' };

    if (message === '') errors.message = { key: 'messageRequired' };
    else if (message.length < MESSAGE_MIN) errors.message = { key: 'messageShort', missing: MESSAGE_MIN - message.length };

    return errors;
}
