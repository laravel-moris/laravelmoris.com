@isset($happeningNow)
    @if ($happeningNow)
        <x-ui.section class="pt-0 pb-10 md:pb-12">
            <x-home.happening-now
                :event="$happeningNow"
            />
        </x-ui.section>
    @endif
@endisset
