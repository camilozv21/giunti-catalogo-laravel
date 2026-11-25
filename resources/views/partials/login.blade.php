<button type="button" class="btn btn--ghost btn--md login__button" popovertarget="login-note">
    <x-icon name="user-round" class="icon--large" />
    <span class="login__label">{{ __('ui.login.label') }}</span>
</button>
<div id="login-note" popover="auto" class="login__note">
    <p class="login__note-text">
        <x-icon name="info" class="login__note-icon" />
        <span>{{ __('ui.login.note') }}</span>
    </p>
</div>
