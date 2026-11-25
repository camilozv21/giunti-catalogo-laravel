@props(['format'])
<span {{ $attributes->class(['badge', 'badge--'.$format->value]) }} data-format="{{ $format->value }}"><x-icon name="{{ $format->icon() }}" class="icon--small" />{{ $format->label() }}</span>
