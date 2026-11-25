<section id="interpreta" class="interpreter" aria-labelledby="interpreter-title" data-interpreter>
    <div class="container interpreter__inner">
        <div>
            <x-section-heading id="interpreter-title" :title="__('ui.interpreter.title')" :lead="__('ui.interpreter.lead')" />
            <div class="interpreter__controls">
                <div class="interpreter__slider-head">
                    <label for="interpreter-z" class="interpreter__label">{{ __('ui.interpreter.slider') }}</label>
                    <output for="interpreter-z" class="interpreter__z" data-interpreter-z>{{ $interpreter->score->formattedZ() }}</output>
                </div>
                <input id="interpreter-z" type="range" min="{{ (int) \App\Interpreter\ScoreInterpretation::Z_MIN }}" max="{{ (int) \App\Interpreter\ScoreInterpretation::Z_MAX }}" step="0.1" value="1"
                       aria-valuetext="{{ $interpreter->valueText() }}"
                       class="range interpreter__range" style="{{ $interpreter->score->chartStyle() }}" data-interpreter-input>
                <dl class="interpreter__readouts">
                    @foreach ($interpreter->readouts() as $key => $readout)
                        <div class="interpreter__readout">
                            <dt class="interpreter__readout-label">{{ $readout['label'] }}</dt>
                            <dd class="interpreter__readout-value" data-interpreter-{{ $key }}>{{ $readout['value'] }}</dd>
                        </div>
                    @endforeach
                </dl>
                <p class="interpreter__note">{{ __('ui.interpreter.note') }}</p>
            </div>
        </div>

        <div class="interpreter__card" style="{{ $interpreter->score->chartStyle() }}" data-interpreter-card>
            <div class="interpreter__card-head">
                <span class="interpreter__band" data-interpreter-band>{{ $interpreter->score->band->label() }}</span>
                <span class="interpreter__card-percentile" data-interpreter-card-percentile>{{ $interpreter->percentileLabel() }}</span>
            </div>
            <div class="interpreter__chart" aria-hidden="true">{!! $interpreter->chart() !!}</div>
            <div class="dots interpreter__dots" aria-hidden="true">
                @for ($i = 0; $i < 100; $i++)
                    <span class="dot" style="--i: {{ $i }}"></span>
                @endfor
            </div>
            <p class="interpreter__sentence" data-interpreter-sentence>{{ $interpreter->sentence() }}</p>
        </div>
    </div>
</section>
