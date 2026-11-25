<section id="perche-sceglierci" class="bento on-dark" aria-labelledby="bento-title">
    <div class="bento__backdrop" aria-hidden="true"></div>
    <div class="container">
        <x-section-heading id="bento-title" tone="light" :title="__('ui.bento.title')" />
        <p class="bento__statement">@foreach (explode(' ', __('ui.bento.statement')) as $i => $word)@if ($i > 0) @endif<span class="reveal-word">{{ $word }}</span>@endforeach</p>

        <ul role="list" class="bento__grid">
            <li class="bento-tile bento__tile bento__tile--ages" style="--start: 0%">
                <h3 class="bento__title">{{ __('ui.bento.ages.title') }}</h3>
                <p class="bento__text">{{ __('ui.bento.ages.text', ['min' => $bento->facts->minAge, 'max' => $bento->facts->maxAge]) }}</p>
                <ul class="bento__ages" aria-label="{{ __('ui.bento.ages.label') }}">
                    @foreach ($bento->testsByAge() as $test)
                        <li class="bento__age-row">
                            <span class="bento__age-code" aria-hidden="true" translate="no">{{ $test->code }}</span>
                            <span class="sr-only">{{ __('ui.bento.ages.row', ['code' => $test->code, 'min' => $test->ageMin, 'max' => $test->ageMax]) }}</span>
                            <span class="coverage-track" aria-hidden="true"><span class="coverage-bar" style="--from: {{ $test->ageMin }}; --to: {{ $test->ageMax }}; --start: {{ 18 + $loop->index * 3 }}%"></span></span>
                        </li>
                    @endforeach
                </ul>
                <div class="bento__age-axis" aria-hidden="true">
                    <span>{{ __('ui.bento.ages.axis') }}</span>
                    <span class="bento__age-ticks">@foreach ($bento->ageTicks() as $tick)<span>{{ $tick }}</span>@endforeach</span>
                </div>
            </li>

            <li class="bento-tile bento__tile bento__tile--glass bento__tile--time" style="--start: 10%">
                <p class="bento__figure"><span class="bento__figure-value">{{ $bento->facts->minMinutes }}–{{ $bento->facts->maxMinutes }}</span><span class="bento__figure-unit">{{ __('ui.bento.time.unit') }}</span></p>
                <div aria-hidden="true">
                    <div class="span-ruler" style="--from: {{ $bento->facts->minMinutes }}; --to: {{ $bento->facts->maxMinutes }}; --scale: {{ $bento::RULER_SCALE_MINUTES }}">
                        <span class="span-ruler__range" style="--start: 25%"></span>
                    </div>
                    <div class="bento__ruler-labels">
                        @foreach ($bento->rulerMinutes() as $minute)
                            <span>{{ $minute }}′</span>
                        @endforeach
                    </div>
                </div>
                <h3 class="bento__title bento__title--end">{{ __('ui.bento.time.title') }}</h3>
                <p class="bento__text">{{ __('ui.bento.time.text') }}</p>
            </li>

            <li class="bento-tile bento__tile bento__tile--glass bento__tile--formats" style="--start: 10%">
                <div class="bento__formats-text">
                    <h3 class="bento__title">{{ __('ui.bento.formats.title') }}</h3>
                    <p class="bento__text">{{ __('ui.bento.formats.text') }}</p>
                </div>
                <div class="bento__badges" aria-hidden="true">
                    @foreach ($bento->formats() as $format)
                        <span class="slide-in bento__badge" style="--start: {{ 20 + $loop->index * 8 }}%"><x-format-badge :format="$format" /></span>
                    @endforeach
                </div>
            </li>

            <li class="bento-tile bento__tile bento__tile--training" style="--start: 0%">
                <x-photo class="bento__picture" stem="res-2704" image-class="bento__photo card-photo" sizes="(min-width: 80rem) 900px, (min-width: 64rem) 75vw, 100vw" />
                <span class="bento__scrim" aria-hidden="true"></span>
                <div class="bento__training">
                    <div class="bento__training-text">
                        <h3 class="bento__title">{{ __('ui.bento.training.title') }}</h3>
                        <p class="bento__text bento__text--soft">{{ __('ui.bento.training.text') }}</p>
                    </div>
                    <p class="bento__stat">
                        <span class="bento__stat-value"><span aria-hidden="true" class="count count--view" style="--to: {{ $bento->facts->maxEcm }}"></span><span class="sr-only">{{ $bento->facts->maxEcm }}</span></span>
                        <span class="bento__stat-label">{{ __('ui.bento.training.statLabel') }}</span>
                    </p>
                </div>
            </li>

            <li class="bento-tile bento__tile bento__tile--quote" style="--start: 20%">
                <div class="bento__receipt" aria-hidden="true">
                    @foreach ($bento->quoteLines() as $line)
                        <p class="slide-in bento__receipt-line" style="--start: {{ 24 + $loop->index * 8 }}%">
                            <span class="bento__receipt-item">{{ $line->quantity }} × {{ $line->product->code }}</span>
                            <span class="bento__receipt-price">{{ $line->total()->format() }}</span>
                        </p>
                    @endforeach
                    <p class="bento__receipt-total">
                        <span>{{ __('ui.bento.quote.total') }}</span>
                        <span class="bento__receipt-sum">{{ $bento->quoteTotal()->format() }}</span>
                    </p>
                    <span class="bento__receipt-cta">{{ __('ui.bento.quote.request') }}</span>
                </div>
                <h3 class="bento__title bento__title--end">{{ __('ui.bento.quote.title') }}</h3>
                <p class="bento__text bento__text--muted">{{ __('ui.bento.quote.text') }}</p>
            </li>
        </ul>
    </div>
</section>
