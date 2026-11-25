<section id="consulenza" class="assistant" aria-labelledby="assistant-title" data-assistant>
    <div class="container assistant__inner">
        <div>
            <x-section-heading id="assistant-title" :title="__('ui.assistant.title')" :lead="__('ui.assistant.lead')" />
            <div class="assistant__pick" role="group" aria-labelledby="assistant-pick">
                <p id="assistant-pick" class="eyebrow assistant__pick-label">{{ __('ui.assistant.pick') }}</p>
                <div class="assistant__chips">
                    @foreach ($assistant->scenarios as $scenario)
                        <button type="button" class="assistant__chip" aria-pressed="{{ $loop->first ? 'true' : 'false' }}" data-scenario="{{ $scenario->id }}" data-applied="{{ $scenario->appliedFilters }}">{{ $scenario->label }}</button>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="chat" data-chat>
            <div class="chat__head">
                <span class="chat__live">{{ __('ui.assistant.window') }}</span>
                <span class="chat__status">{{ __('ui.assistant.status') }}</span>
            </div>
            <div class="chat__log" role="log" aria-live="polite" aria-label="{{ __('ui.assistant.window') }}">
                <p class="chat__msg chat__msg--user" data-chat-question>{{ $assistant->firstScenario()->message }}</p>
                <p class="chat__msg chat__msg--bot chat__typing" hidden data-chat-typing><span class="chat__dots" aria-hidden="true"><span></span><span></span><span></span></span>{{ __('ui.assistant.typing') }}</p>
                <div class="chat__msg chat__msg--bot" data-chat-answer>
                    <p data-chat-found>{{ trans_choice('ui.assistant.found', $assistant->answerCount(), ['count' => $assistant->answerCount()]) }}</p>
                    <ul role="list" class="chat__results">
                        @foreach ($assistant->products() as $product)
                            <li class="chat__result" @unless ($assistant->isInAnswer($product)) hidden @endunless style="--i: {{ $assistant->answerPosition($product) }}" data-chat-row="{{ $product->id }}">
                                <span class="chat__result-code" translate="no">{{ $product->code }}</span>
                                <span class="chat__result-name">{{ $product->name }}</span>
                                <span class="chat__result-meta">{{ $product->digest() }}</span>
                            </li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn btn--accent btn--md assistant__cta" data-chat-cta>
                        {{ __('ui.assistant.cta') }}
                        <x-icon name="arrow-down" />
                    </button>
                    <p class="chat__meta" data-chat-applied>{{ $assistant->firstScenario()->appliedFilters }}</p>
                </div>
            </div>
        </div>
    </div>
</section>
