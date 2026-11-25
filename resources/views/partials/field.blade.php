@props(['field'])
@php
    $id = "contact-{$field}";
    $isMessage = $field === 'message';
@endphp
<div class="field" data-field="{{ $field }}">
    <label for="{{ $id }}" class="field__label">{{ __("ui.contact.fields.{$field}.label") }}</label>
    @if ($isMessage)
        <textarea id="{{ $id }}" name="{{ $field }}" class="field__control field__control--textarea" rows="5" minlength="10" required
                  placeholder="{{ __("ui.contact.fields.{$field}.placeholder") }}" aria-describedby="{{ $id }}-hint"></textarea>
    @else
        <input id="{{ $id }}" name="{{ $field }}" class="field__control field__control--input"
               type="{{ $field === 'email' ? 'email' : 'text' }}" autocomplete="{{ $field === 'email' ? 'email' : 'name' }}"
               @if ($field === 'name') minlength="2" @endif required
               placeholder="{{ __("ui.contact.fields.{$field}.placeholder") }}" aria-describedby="{{ $id }}-hint">
    @endif
    <p id="{{ $id }}-error" class="field__error" hidden>
        <x-icon name="circle-alert" class="field__error-icon" />
        <span data-error-text></span>
    </p>
    <p id="{{ $id }}-hint" class="field__hint">{{ __("ui.contact.fields.{$field}.hint") }}</p>
</div>
