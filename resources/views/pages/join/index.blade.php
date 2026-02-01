@extends('layouts.guest')

@section('title', 'Join Our Community | Laravel Moris')

@section('body')
    <x-site.header />

    <main class="px-6 py-12">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-12">
                <x-ui.text.h1>Join Our Community</x-ui.text.h1>
                <x-ui.text.muted class="mt-2 max-w-2xl mx-auto">
                    Connect with fellow Laravel developers in Mauritius. Join our community channels to stay updated and
                    collaborate.
                </x-ui.text.muted>
            </div>

            @if ($communityLinks->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                    @foreach ($communityLinks as $link)
                        @php
                            $isUnavailable = empty($link->url) || $link->url === '#';
                        @endphp
                        <a href="{{ $isUnavailable ? 'javascript:void(0)' : $link->url }}"
                            @unless($isUnavailable) target="_blank" rel="noopener noreferrer" @endunless
                            class="group block"
                            @if($isUnavailable) data-unavailable="true" @endif
                            onclick="return handleLinkClick(event, this)">
                            <x-ui.card class="p-6 transition-all duration-300 hover:border-primary/50 h-full">
                                <div class="flex flex-col items-center text-center">
                                    <div
                                        class="w-16 h-16 rounded-2xl bg-surface-2 flex items-center justify-center mb-4 group-hover:bg-primary/10 transition-colors">
                                        <i class="ci {{ $link->iconKey }} ci-3x"></i>
                                    </div>
                                    <x-ui.text.h3 class="text-lg group-hover:text-primary transition-colors">
                                        {{ $link->name }}
                                    </x-ui.text.h3>
                                    <x-ui.text.muted class="mt-2 text-sm leading-relaxed">
                                        {{ $link->description }}
                                    </x-ui.text.muted>
                                    <div
                                        class="mt-4 flex items-center gap-2 text-primary text-sm font-semibold group-hover:translate-x-1 transition-transform">
                                        <span>Join Now</span>
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor" stroke-width="2" class="w-4 h-4">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                        </svg>
                                    </div>
                                </div>
                            </x-ui.card>
                        </a>
                    @endforeach
                </div>
            @else
                <x-ui.card class="p-12 text-center">
                    <x-ui.text.h3>No Community Links Available</x-ui.text.h3>
                    <x-ui.text.muted class="mt-2">
                        Check back soon for community links!
                    </x-ui.text.muted>
                </x-ui.card>
            @endif
        </div>
    </main>

    <x-site.footer />

    <!-- Link Unavailable Popup -->
    <div id="link-unavailable-popup"
        class="fixed inset-0 z-50 flex items-center justify-center hidden"
        role="dialog"
        aria-modal="true"
        aria-labelledby="popup-title">
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="closePopup()"></div>

        <!-- Popup Content -->
        <div class="relative bg-surface-1 border border-border rounded-2xl p-8 max-w-md mx-4 shadow-2xl transform transition-all">
            <!-- Close Button -->
            <button onclick="closePopup()"
                class="absolute top-4 right-4 text-muted hover:text-foreground transition-colors"
                aria-label="Close popup">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <!-- Icon -->
            <div class="flex justify-center mb-6">
                <div class="w-16 h-16 rounded-full bg-amber-500/10 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="w-8 h-8 text-amber-500">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
            </div>

            <!-- Title -->
            <h3 id="popup-title" class="text-xl font-bold text-center text-foreground mb-2">
                Link Not Available
            </h3>

            <!-- Message -->
            <p class="text-muted text-center mb-6">
                This community link is not available yet. Please check back soon!
            </p>

            <!-- Button -->
            <div class="flex justify-center">
                <button onclick="closePopup()"
                    class="px-6 py-2.5 bg-primary text-primary-foreground font-semibold rounded-lg hover:bg-primary/90 transition-colors">
                    Got it
                </button>
            </div>
        </div>
    </div>

    <script>
        function handleLinkClick(event, element) {
            if (element.hasAttribute('data-unavailable')) {
                event.preventDefault();
                openPopup();
                return false;
            }
            return true;
        }

        function openPopup() {
            const popup = document.getElementById('link-unavailable-popup');
            popup.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }

        function closePopup() {
            const popup = document.getElementById('link-unavailable-popup');
            popup.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }

        // Close popup on Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closePopup();
            }
        });
    </script>
@endsection
