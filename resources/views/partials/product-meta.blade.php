@props(['product'])
<dl class="meta">
    @foreach ($product->facts() as $fact)
        <div class="meta__fact">
            <dt class="sr-only">{{ $fact->label }}</dt>
            <dd>{{ $fact->value }}</dd>
        </div>
    @endforeach
</dl>
