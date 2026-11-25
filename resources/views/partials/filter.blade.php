<fieldset class="filter">
    <legend class="sr-only">{{ __('ui.catalog.filterLegend') }}</legend>
    <div class="filter__row">
        @foreach ($filter->options() as $option)
            <label class="filter__pill">
                <input class="filter__input" type="radio" name="categoria" value="{{ $option->value }}" @checked($option->isDefault) data-category-radio>
                <span>{{ $option->label }}</span>
                <span class="filter__count">{{ $option->count }}</span>
            </label>
        @endforeach
    </div>
</fieldset>
