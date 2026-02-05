@extends('layouts.guest')

@section('title', 'Style Guide | Laravel Moris')

@section('body')
    <x-site.header />

    <main>
        <x-section class="pt-10">
            <x-section-header title="UI" accent="Style Guide" subtitle="Components, tokens, and usage examples">
                <p class="max-w-2xl text-muted leading-relaxed">
                    This page showcases the existing UI primitives and Blade components used across the site.
                </p>
            </x-section-header>

            <div class="grid gap-7">
                <x-card title="Table of Contents">
                    <nav class="grid gap-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
                        <a href="#buttons" class="text-sm text-muted hover:text-foreground transition-colors">Buttons</a>
                        <a href="#inputs" class="text-sm text-muted hover:text-foreground transition-colors">Form Inputs</a>
                        <a href="#textareas"
                            class="text-sm text-muted hover:text-foreground transition-colors">Textareas</a>
                        <a href="#dropdowns"
                            class="text-sm text-muted hover:text-foreground transition-colors">Dropdowns</a>
                        <a href="#cards" class="text-sm text-muted hover:text-foreground transition-colors">Cards</a>
                        <a href="#avatars" class="text-sm text-muted hover:text-foreground transition-colors">Avatars</a>
                        <a href="#badges" class="text-sm text-muted hover:text-foreground transition-colors">Badges</a>
                        <a href="#alerts" class="text-sm text-muted hover:text-foreground transition-colors">Alerts</a>
                        <a href="#modals" class="text-sm text-muted hover:text-foreground transition-colors">Modals</a>
                        <a href="#nav-links" class="text-sm text-muted hover:text-foreground transition-colors">Navigation
                            Links</a>
                        <a href="#tables" class="text-sm text-muted hover:text-foreground transition-colors">Tables</a>
                        <a href="#palette" class="text-sm text-muted hover:text-foreground transition-colors">Color
                            Palette</a>
                        <a href="#typography"
                            class="text-sm text-muted hover:text-foreground transition-colors">Typography</a>
                    </nav>
                </x-card>

                <x-card id="buttons" title="Buttons">
                    <div class="grid gap-8">
                        <div class="grid gap-3">
                            <x-label>Variants</x-label>
                            <div class="flex flex-wrap gap-3">
                                <x-button variant="primary">Primary</x-button>
                                <x-button variant="secondary">Secondary</x-button>
                                <x-button variant="outline">Outline</x-button>
                                <x-button variant="ghost">Ghost</x-button>
                                <x-button variant="danger">Danger</x-button>
                            </div>
                        </div>

                        <div class="grid gap-3">
                            <x-label>Sizes</x-label>
                            <div class="flex flex-wrap items-center gap-3">
                                <x-button size="sm">Small</x-button>
                                <x-button size="md">Medium</x-button>
                                <x-button size="lg">Large</x-button>
                            </div>
                        </div>

                        <div class="grid gap-3">
                            <x-label>Disabled</x-label>
                            <div class="flex flex-wrap gap-3">
                                <x-button disabled>Primary</x-button>
                                <x-button variant="secondary" disabled>Secondary</x-button>
                                <x-button variant="outline" disabled>Outline</x-button>
                                <x-button variant="ghost" disabled>Ghost</x-button>
                                <x-button variant="danger" disabled>Danger</x-button>
                            </div>
                        </div>
                    </div>
                </x-card>

                <x-card id="inputs" title="Form Inputs">
                    <div class="grid gap-8 md:grid-cols-2">
                        <x-input name="email" label="Email" type="email" placeholder="you@company.com"
                            help="We will only use this for account-related emails." />

                        <x-input name="password" label="Password" type="password" placeholder="••••••••"
                            error="Your password must be at least 12 characters." />

                        <x-input label="Disabled" value="Read-only example" disabled />
                    </div>
                </x-card>

                <x-card id="dropdowns" title="Dropdowns">
                    <div class="grid gap-8 md:grid-cols-2">
                        <x-select name="role" label="Role" placeholder="Select a role" :options="[
                            'member' => 'Member',
                            'speaker' => 'Speaker',
                            'organizer' => 'Organizer',
                        ]"
                            help="Used for access and visibility settings." />

                        <x-select label="With error" error="Please select an option." placeholder="Choose one"
                            :options="[
                                'one' => 'Option One',
                                'two' => 'Option Two',
                            ]" />
                    </div>
                </x-card>

                <x-card id="textareas" title="Textareas">
                    <div class="grid gap-8 md:grid-cols-2">
                        <x-textarea name="bio" label="Bio" rows="3" placeholder="Tell us about yourself..."
                            help="Max 500 characters." />

                        <x-textarea name="feedback" label="Feedback" rows="3"
                            error="Feedback must be at least 10 characters." />
                    </div>
                </x-card>

                <x-card id="cards" title="Cards">
                    <div class="grid gap-7 md:grid-cols-2">
                        <x-card title="Card with title">
                            <p class="text-muted leading-relaxed">Use <x-code>&lt;x-card&gt;</x-code> for the base
                                container
                                and add padding at the component level when needed.</p>
                            <div class="mt-5 flex gap-3">
                                <x-button size="sm">Action</x-button>
                                <x-button size="sm" variant="secondary">Secondary</x-button>
                            </div>
                        </x-card>

                        <x-card :padded="false">
                            <x-slot:header>
                                <div class="flex items-start justify-between gap-4">
                                    <div>
                                        <div class="text-md font-bold tracking-snug">Custom header slot</div>
                                        <div class="mt-1 text-sm text-muted">Optional header content with actions.</div>
                                    </div>
                                    <x-button size="sm" variant="outline">Manage</x-button>
                                </div>
                            </x-slot:header>

                            <div class="p-6">
                                <p class="text-muted leading-relaxed">Set <x-code>:padded="false"</x-code> when you
                                    want full control over internal spacing.</p>
                            </div>
                        </x-card>
                    </div>
                </x-card>

                <x-card id="avatars" title="Avatars">
                    @php
                        $avatarSources = [
                            'data:image/svg+xml,' .
                            rawurlencode(
                                '<svg xmlns="http://www.w3.org/2000/svg" width="120" height="120"><rect width="120" height="120" rx="60" fill="#48dbfb"/><text x="50%" y="54%" text-anchor="middle" dominant-baseline="middle" font-family="ui-sans-serif,system-ui" font-size="44" font-weight="700" fill="#0a0908">A</text></svg>',
                            ),
                            'data:image/svg+xml,' .
                            rawurlencode(
                                '<svg xmlns="http://www.w3.org/2000/svg" width="120" height="120"><rect width="120" height="120" rx="60" fill="#fdcb6e"/><text x="50%" y="54%" text-anchor="middle" dominant-baseline="middle" font-family="ui-sans-serif,system-ui" font-size="44" font-weight="700" fill="#0a0908">B</text></svg>',
                            ),
                            'data:image/svg+xml,' .
                            rawurlencode(
                                '<svg xmlns="http://www.w3.org/2000/svg" width="120" height="120"><rect width="120" height="120" rx="60" fill="#ff6b6b"/><text x="50%" y="54%" text-anchor="middle" dominant-baseline="middle" font-family="ui-sans-serif,system-ui" font-size="44" font-weight="700" fill="#0a0908">C</text></svg>',
                            ),
                        ];

                        $avatarStack = [
                            ['src' => $avatarSources[0], 'alt' => 'Avatar A'],
                            ['src' => $avatarSources[1], 'alt' => 'Avatar B'],
                            ['src' => $avatarSources[2], 'alt' => 'Avatar C'],
                        ];
                    @endphp

                    <div class="grid gap-7">
                        <div class="grid gap-3">
                            <x-label>Sizes</x-label>
                            <div class="flex flex-wrap items-center gap-4">
                                <x-avatar :src="$avatarSources[0]" alt="Avatar" size="xs" border="subtle" />
                                <x-avatar :src="$avatarSources[1]" alt="Avatar" size="sm" border="subtle" />
                                <x-avatar :src="$avatarSources[2]" alt="Avatar" size="md" border="subtle" />
                            </div>
                        </div>

                        <div class="grid gap-3">
                            <x-label>Stack</x-label>
                            <x-avatar-stack :items="$avatarStack" />
                        </div>
                    </div>
                </x-card>

                <x-card id="badges" title="Badges">
                    <div class="grid gap-7">
                        <div class="grid gap-3">
                            <x-label>Soft</x-label>
                            <div class="flex flex-wrap items-center gap-3">
                                <x-badge color="green">Confirmed</x-badge>
                                <x-badge color="gold">Pending</x-badge>
                                <x-badge color="coral">Invited</x-badge>
                                <x-badge color="muted">Disabled</x-badge>
                            </div>
                        </div>

                        <div class="grid gap-3">
                            <x-label>Solid</x-label>
                            <div class="flex flex-wrap items-center gap-3">
                                <x-badge size="xs" :caps="true" color="coral"
                                    variant="solid">Upcoming</x-badge>
                                <x-badge size="xs" :caps="true" color="green" variant="solid">Active</x-badge>
                                <x-badge size="xs" :caps="true" color="muted" variant="solid">Past</x-badge>
                            </div>
                        </div>
                    </div>
                </x-card>

                <x-card id="alerts" title="Alerts">
                    <div class="grid gap-4">
                        <x-alert variant="success" title="Success">
                            Your profile has been updated.
                        </x-alert>

                        <x-alert variant="info" title="Info" dismissible>
                            A new meetup is announced. Check the schedule for details.
                        </x-alert>

                        <x-alert variant="warning" title="Warning" dismissible>
                            This action cannot be undone.
                        </x-alert>

                        <x-alert variant="danger" title="Danger">
                            Something went wrong while saving.
                        </x-alert>
                    </div>
                </x-card>

                <x-card id="modals" title="Modals">
                    <div class="grid gap-7">
                        <div class="grid gap-3">
                            <x-label>Interactive Demo</x-label>
                            <x-text variant="muted">Click the buttons to open different modal styles.</x-text>

                            <div class="flex flex-wrap gap-3 mt-2">
                                <div x-data="{ showModal: false }">
                                    <x-button size="sm" variant="outline" @click="showModal = true">Info
                                        Modal</x-button>
                                    <x-modal id="demo-info-modal">
                                        <x-slot:icon>
                                            <div
                                                class="w-16 h-16 rounded-full bg-teal/10 flex items-center justify-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"
                                                    class="w-8 h-8 text-teal">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            </div>
                                        </x-slot:icon>
                                        <x-slot:header>
                                            <x-heading level="3">Information</x-heading>
                                        </x-slot:header>
                                        <x-slot:message>
                                            <x-text variant="muted" class="text-center">This is an informational modal.
                                                Use it for helpful tips or confirmations.</x-text>
                                        </x-slot:message>
                                        <x-slot:footer>
                                            <div class="flex justify-center">
                                                <x-button variant="primary" size="sm" @click="showModal = false">Got
                                                    it</x-button>
                                            </div>
                                        </x-slot:footer>
                                    </x-modal>
                                </div>

                                <div x-data="{ showModal: false }">
                                    <x-button size="sm" variant="danger" @click="showModal = true">Danger
                                        Modal</x-button>
                                    <x-modal id="demo-danger-modal">
                                        <x-slot:icon>
                                            <div
                                                class="w-16 h-16 rounded-full bg-coral/10 flex items-center justify-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"
                                                    class="w-8 h-8 text-coral">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                                </svg>
                                            </div>
                                        </x-slot:icon>
                                        <x-slot:header>
                                            <x-heading level="3">Delete Item?</x-heading>
                                        </x-slot:header>
                                        <x-slot:message>
                                            <x-text variant="muted" class="text-center">This action cannot be undone.
                                                Are you sure you want to proceed?</x-text>
                                        </x-slot:message>
                                        <x-slot:footer>
                                            <div class="flex justify-center gap-3">
                                                <x-button variant="outline" size="sm"
                                                    @click="showModal = false">Cancel</x-button>
                                                <x-button variant="danger" size="sm"
                                                    @click="showModal = false">Delete</x-button>
                                            </div>
                                        </x-slot:footer>
                                    </x-modal>
                                </div>
                            </div>
                        </div>

                        <div class="grid gap-3">
                            <x-label>Available Slots</x-label>
                            <ul class="space-y-2 text-sm text-muted">
                                <li><x-code>id</x-code> — Unique identifier for the modal</li>
                                <li><x-code>:header</x-code> — Slot for heading (use &lt;x-heading&gt;)</li>
                                <li><x-code>:icon</x-code> — Slot for icon (warning/info)</li>
                                <li><x-code>:message</x-code> — Slot for modal content (or default slot)</li>
                                <li><x-code>:footer</x-code> — Slot for action buttons</li>
                            </ul>
                        </div>
                    </div>
                </x-card>

                <x-card id="nav-links" title="Navigation Links">
                    <div class="flex flex-wrap gap-3">
                        <x-nav-link href="#" :active="true">Active</x-nav-link>
                        <x-nav-link href="#">Default</x-nav-link>
                        <x-nav-link href="#">Another link</x-nav-link>
                    </div>
                </x-card>

                <x-card id="tables" title="Tables">
                    <x-table>
                        <thead>
                            <tr>
                                <th scope="col">Name</th>
                                <th scope="col">Role</th>
                                <th scope="col">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Jane Doe</td>
                                <td>Speaker</td>
                                <td><x-badge color="green">Confirmed</x-badge></td>
                            </tr>
                            <tr>
                                <td>John Smith</td>
                                <td>Organizer</td>
                                <td><x-badge color="gold">Pending</x-badge></td>
                            </tr>
                            <tr>
                                <td>Aisha Patel</td>
                                <td>Member</td>
                                <td><x-badge color="coral">Invited</x-badge></td>
                            </tr>
                        </tbody>
                    </x-table>
                </x-card>

                <x-card id="palette" title="Color Palette">
                    @php
                        $swatches = [
                            ['label' => 'bg-background', 'bg' => 'bg-background', 'text' => 'text-foreground'],
                            ['label' => 'bg-surface', 'bg' => 'bg-surface', 'text' => 'text-foreground'],
                            ['label' => 'bg-surface-2', 'bg' => 'bg-surface-2', 'text' => 'text-foreground'],
                            ['label' => 'text-foreground', 'bg' => 'bg-surface', 'text' => 'text-foreground'],
                            ['label' => 'text-muted', 'bg' => 'bg-surface', 'text' => 'text-muted'],
                            ['label' => 'bg-primary', 'bg' => 'bg-primary', 'text' => 'text-white'],
                            ['label' => 'bg-primary-hover', 'bg' => 'bg-primary-hover', 'text' => 'text-white'],
                        ];
                    @endphp

                    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach ($swatches as $swatch)
                            <div class="relative overflow-hidden rounded-3xl bg-surface border border-border/70">
                                <div class="h-16 {{ $swatch['bg'] }}"></div>
                                <div class="p-5">
                                    <div class="font-mono text-sm {{ $swatch['text'] }}">{{ $swatch['label'] }}</div>
                                    <x-text variant="muted" class="mt-1 text-xs">Token / utility</x-text>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </x-card>

                <x-card id="typography" title="Typography">
                    <div class="grid gap-8">
                        <div class="grid gap-3">
                            <x-label>Headings</x-label>
                            <div class="grid gap-4">
                                <x-heading level="1">Heading 1</x-heading>
                                <x-heading level="2">Heading 2</x-heading>
                                <x-heading level="3">Heading 3</x-heading>
                                <x-heading level="4">Heading 4</x-heading>
                                <x-heading level="5">Heading 5</x-heading>
                                <x-heading level="6">Heading 6</x-heading>
                            </div>
                        </div>

                        <div class="grid gap-3">
                            <x-label>Text Variants</x-label>
                            <div class="grid gap-3">
                                <x-text size="md">
                                    Body text is readable and token-driven. Use <span class="text-muted">muted</span> for
                                    supporting information.
                                </x-text>
                                <x-text variant="muted">Small / muted text for captions, help text, and meta.</x-text>
                                <x-text variant="strong">Strong / emphasized text for important information.</x-text>
                                <x-text variant="subtitle">Subtitle for section labels</x-text>
                            </div>
                        </div>

                        <div class="grid gap-3">
                            <x-label>Links</x-label>
                            <div class="flex flex-wrap items-center gap-6">
                                <x-link href="#">Default link</x-link>
                                <x-link href="#" variant="muted">Muted link</x-link>
                                <x-link href="#" variant="subtle">Subtle link</x-link>
                                <x-link href="#" :external="true">External link</x-link>
                            </div>
                        </div>

                        <div class="grid gap-3">
                            <x-label>Inline Code</x-label>
                            <x-text>
                                Use <x-code>x-button</x-code> for actions and <x-code>x-link</x-code> for navigation.
                            </x-text>
                        </div>
                    </div>
                </x-card>
            </div>
        </x-section>
    </main>
@endsection
