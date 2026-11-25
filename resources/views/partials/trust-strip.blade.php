<ul class="trust" aria-label="{{ __('ui.trust.label') }}">
    @foreach ($trust->figures() as $figure)
        <li class="trust__item">
            <span class="trust__value">@if ($figure->count !== null)<span aria-hidden="true" class="count count--view" style="--to: {{ $figure->count }}"></span><span class="sr-only">{{ $figure->count }}</span>{{ $figure->value }}@else{{ $figure->value }}@endif</span>
            <span class="trust__text">{{ $figure->text }}</span>
        </li>
    @endforeach
</ul>
