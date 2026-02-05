@isset($happeningNow)
    @if ($happeningNow)
        <x-section class="pt-0 pb-10 md:pb-12">
            <x-happening-now :event="$happeningNow" />
        </x-section>
    @endif
@endisset
