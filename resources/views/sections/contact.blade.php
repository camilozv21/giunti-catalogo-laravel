<section id="contatti" class="contact on-dark" aria-labelledby="contact-title">
    <div class="contact__backdrop" aria-hidden="true"></div>
    <div class="container contact__inner">
        <x-section-heading id="contact-title" tone="light" class="contact__heading" :title="__('ui.contact.title')" :lead="__('ui.contact.lead')" />

        <div class="contact__card glow-border">
            {{-- Native constraints are the no-JavaScript fallback; the script swaps them for its own messages. --}}
            <form class="contact__form" method="post" action="/contatto" aria-describedby="contact-required" data-contact-form>
                <p id="contact-required" class="contact__required">{{ __('ui.contact.required') }}</p>
                <x-field field="name" />
                <x-field field="email" />
                <x-field field="message" />
                <p role="status" class="sr-only" data-contact-summary></p>
                <button type="submit" class="btn btn--primary btn--lg contact__submit" data-contact-submit>
                    <x-icon name="send" />
                    <span data-submit-label>{{ __('ui.contact.submit') }}</span>
                </button>
            </form>

            <div class="contact__success" role="region" tabindex="-1" aria-labelledby="contact-success-title" hidden data-contact-success>
                <span class="contact__success-icon"><x-icon name="circle-check" class="icon--xlarge" /></span>
                <h3 id="contact-success-title" class="contact__success-title">{{ __('ui.contact.success.title') }}</h3>
                <p class="contact__success-text" data-success-text></p>
                <p class="contact__success-demo">{{ __('ui.contact.success.demo') }}</p>
                <button type="button" class="btn btn--secondary btn--md contact__again" data-contact-again>{{ __('ui.contact.success.again') }}</button>
            </div>
        </div>
    </div>
</section>
