<section id="risorse" class="resources" aria-labelledby="resources-title">
    <div class="container resources__inner">
        <x-section-heading id="resources-title" :title="__('ui.resources.title')" :lead="__('ui.resources.lead')" />
        <ul class="resources__levels">
            @foreach (__('ui.resources.qualifications') as $item)
                @php($tests = $facts->testsFor(\App\Catalog\Qualification::from($item['code'])))
                <li class="qualification">
                    <span class="qualification__code" aria-hidden="true">{{ $item['code'] }}</span>
                    <p class="qualification__text"><span class="sr-only">{{ __('ui.card.qualifications.'.$item['code']) }}: </span>{{ $item['text'] }}</p>
                    <div class="qualification__reach">
                        <p class="qualification__share">{{ trans_choice('ui.resources.share', count($tests), ['count' => count($tests), 'total' => $facts->testCount]) }}</p>
                        <span class="reach-track" aria-hidden="true"><span class="reach-bar" style="--level: {{ count($tests) / $facts->testCount }}"></span></span>
                        <p class="qualification__tests"><span class="sr-only">{{ __('ui.resources.opens') }} </span>@if (count($tests) === $facts->testCount){{ __('ui.resources.all') }}@else<span translate="no">{{ implode(' · ', $tests) }}</span>@endif</p>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
</section>
