@foreach ($languages as $language)
    @if (session('app_locale') == $language->canonical)
        @continue
    @endif
    <th class="text-center">{{ $language->name }}</th>
@endforeach
