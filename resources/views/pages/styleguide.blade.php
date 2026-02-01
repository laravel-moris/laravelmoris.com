@extends('layouts.guest')

@section('title', 'Style Guide | Laravel Moris')

@section('body')
    <x-site.header :links="[
        ['label' => 'Buttons', 'href' => '#buttons'],
        ['label' => 'Inputs', 'href' => '#inputs'],
        ['label' => 'Dropdowns', 'href' => '#dropdowns'],
        ['label' => 'Cards', 'href' => '#cards'],
        ['label' => 'Avatars', 'href' => '#avatars'],
        ['label' => 'Chips', 'href' => '#chips'],
        ['label' => 'Alerts', 'href' => '#alerts'],
        ['label' => 'Modals', 'href' => '#modals'],
        ['label' => 'Links', 'href' => '#nav-links'],
        ['label' => 'Tables', 'href' => '#tables'],
        ['label' => 'Palette', 'href' => '#palette'],
        ['label' => 'Type', 'href' => '#typography'],
    ]" />

    <main>
        <x-ui.section class="pt-10">
            <x-ui.section-header title="UI" accent="Style Guide" subtitle="Components, tokens, and usage examples">
                <p class="max-w-2xl text-muted leading-relaxed">
                    This page showcases the existing UI primitives and Blade components used across the site.
                </p>
            </x-ui.section-header>

            <div class="grid gap-7">
                <x-ui.card id="buttons" title="Buttons">
                    <div class="grid gap-8">
                        <div class="grid gap-3">
                            <x-ui.text.label>Variants</x-ui.text.label>
                            <div class="flex flex-wrap gap-3">
                                <x-ui.button variant="primary">Primary</x-ui.button>
                                <x-ui.button variant="secondary">Secondary</x-ui.button>
                                <x-ui.button variant="outline">Outline</x-ui.button>
                                <x-ui.button variant="ghost">Ghost</x-ui.button>
                                <x-ui.button variant="danger">Danger</x-ui.button>
                            </div>
                        </div>

                        <div class="grid gap-3">
                            <p class="text-[12px] font-bold uppercase tracking-[0.14em] text-muted">Sizes</p>
                            <div class="flex flex-wrap items-center gap-3">
                                <x-ui.button size="sm">Small</x-ui.button>
                                <x-ui.button size="md">Medium</x-ui.button>
                                <x-ui.button size="lg">Large</x-ui.button>
                            </div>
                        </div>

                        <div class="grid gap-3">
                            <x-ui.text.label>Disabled</x-ui.text.label>
                            <div class="flex flex-wrap gap-3">
                                <x-ui.button disabled>Primary</x-ui.button>
                                <x-ui.button variant="secondary" disabled>Secondary</x-ui.button>
                                <x-ui.button variant="outline" disabled>Outline</x-ui.button>
                                <x-ui.button variant="ghost" disabled>Ghost</x-ui.button>
                                <x-ui.button variant="danger" disabled>Danger</x-ui.button>
                            </div>
                        </div>
                    </div>
                </x-ui.card>

                <x-ui.card id="inputs" title="Form Inputs">
                    <div class="grid gap-8 md:grid-cols-2">
                        <x-ui.input name="email" label="Email" type="email" placeholder="you@company.com"
                            help="We will only use this for account-related emails." />

                        <x-ui.input name="password" label="Password" type="password" placeholder="••••••••"
                            error="Your password must be at least 12 characters." />

                        <x-ui.input label="Disabled" value="Read-only example" disabled />
                    </div>
                </x-ui.card>

                <x-ui.card id="dropdowns" title="Dropdowns">
                    <div class="grid gap-8 md:grid-cols-2">
                        <x-ui.select name="role" label="Role" placeholder="Select a role" :options="[
                            'member' => 'Member',
                            'speaker' => 'Speaker',
                            'organizer' => 'Organizer',
                        ]"
                            help="Used for access and visibility settings." />

                        <x-ui.select label="With error" error="Please select an option." placeholder="Choose one"
                            :options="[
                                'one' => 'Option One',
                                'two' => 'Option Two',
                            ]" />
                    </div>
                </x-ui.card>

                <x-ui.card id="cards" title="Cards">
                    <div class="grid gap-7 md:grid-cols-2">
                        <x-ui.card title="Card with title">
                            <p class="text-muted leading-relaxed">Use <x-ui.code>.lm-card</x-ui.code> for the base container
                                and add padding at the component level when needed.</p>
                            <div class="mt-5 flex gap-3">
                                <x-ui.button size="sm">Action</x-ui.button>
                                <x-ui.button size="sm" variant="secondary">Secondary</x-ui.button>
                            </div>
                        </x-ui.card>

                        <x-ui.card :padded="false">
                            <x-slot:header>
                                <div class="flex items-start justify-between gap-4">
                                    <div>
                                        <div class="text-[16px] font-bold tracking-[-0.01em]">Custom header slot</div>
                                        <div class="mt-1 text-[13px] text-muted">Optional header content with actions.</div>
                                    </div>
                                    <x-ui.button size="sm" variant="outline">Manage</x-ui.button>
                                </div>
                            </x-slot:header>

                            <div class="p-6">
                                <p class="text-muted leading-relaxed">Set <x-ui.code>:padded="false"</x-ui.code> when you
                                    want full control over internal spacing.</p>
                            </div>
                        </x-ui.card>
                    </div>
                </x-ui.card>

                <x-ui.card id="avatars" title="Avatars">
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
                            <x-ui.text.label>Sizes</x-ui.text.label>
                            <div class="flex flex-wrap items-center gap-4">
                                <x-ui.avatar :src="$avatarSources[0]" alt="Avatar" size="xs" border="subtle" />
                                <x-ui.avatar :src="$avatarSources[1]" alt="Avatar" size="sm" border="subtle" />
                                <x-ui.avatar :src="$avatarSources[2]" alt="Avatar" size="md" border="subtle" />
                            </div>
                        </div>

                        <div class="grid gap-3">
                            <x-ui.text.label>Stack</x-ui.text.label>
                            <x-ui.avatar-stack :items="$avatarStack" />
                        </div>
                    </div>
                </x-ui.card>

                <x-ui.card id="chips" title="Chips">
                    <div class="grid gap-7">
                        <div class="grid gap-3">
                            <x-ui.text.label>Soft</x-ui.text.label>
                            <div class="flex flex-wrap items-center gap-3">
                                <x-ui.chip color="green">Confirmed</x-ui.chip>
                                <x-ui.chip color="gold">Pending</x-ui.chip>
                                <x-ui.chip color="coral">Invited</x-ui.chip>
                                <x-ui.chip color="muted">Disabled</x-ui.chip>
                            </div>
                        </div>

                        <div class="grid gap-3">
                            <x-ui.text.label>Status pill</x-ui.text.label>
                            <div class="flex flex-wrap items-center gap-3">
                                <x-ui.chip size="xs" :caps="true" color="coral"
                                    variant="solid">Upcoming</x-ui.chip>
                                <x-ui.chip size="xs" :caps="true" color="coral" variant="soft"
                                    text="coral">Upcoming</x-ui.chip>
                                <x-ui.chip size="xs" :caps="true" color="muted" variant="soft"
                                    text="muted">Past</x-ui.chip>
                            </div>
                        </div>
                    </div>
                </x-ui.card>

                <x-ui.card id="alerts" title="Alerts">
                    <div class="grid gap-4">
                        <x-ui.alert variant="success" title="Success">
                            Your profile has been updated.
                        </x-ui.alert>

                        <x-ui.alert variant="info" title="Info" dismissible>
                            A new meetup is announced. Check the schedule for details.
                        </x-ui.alert>

                        <x-ui.alert variant="warning" title="Warning" dismissible>
                            This action cannot be undone.
                        </x-ui.alert>

                        <x-ui.alert variant="danger" title="Danger">
                            Something went wrong while saving.
                        </x-ui.alert>
                    </div>
                </x-ui.card>

                <x-ui.card id="modals" title="Modals">
                    <div class="grid gap-7 md:grid-cols-2">
                        <div class="grid gap-3">
                            <x-ui.text.label>Basic Modal</x-ui.text.label>
                            <p class="text-muted text-sm">Use the modal component with title, icon slot, message slot, and
                                footer slot for actions.</p>

                            <div class="mt-4 p-4 bg-surface-2 rounded-lg border border-border">
                                <p class="text-sm text-muted mb-3">Preview structure (opens via JS):</p>
                                <div class="space-y-2">
                                    <code class="block text-xs text-foreground">
                                        &lt;x-ui.modal id="confirm-delete" title="Delete Item"&gt;
                                    </code>
                                    <code class="block text-xs text-foreground pl-4">
                                        &lt;x-slot:icon&gt;...&lt;/x-slot:icon&gt;
                                    </code>
                                    <code class="block text-xs text-foreground pl-4">
                                        &lt;x-slot:message&gt;...&lt;/x-slot:message&gt;
                                    </code>
                                    <code class="block text-xs text-foreground pl-4">
                                        &lt;x-slot:footer&gt;...&lt;/x-slot:footer&gt;
                                    </code>
                                    <code class="block text-xs text-foreground">
                                        &lt;/x-ui.modal&gt;
                                    </code>
                                </div>
                            </div>
                        </div>

                        <div class="grid gap-3">
                            <x-ui.text.label>Props & Slots</x-ui.text.label>
                            <ul class="space-y-2 text-sm text-muted">
                                <li><x-ui.code>id</x-ui.code> - Unique identifier for the modal</li>
                                <li><x-ui.code>title</x-ui.code> - Modal header title</li>
                                <li><x-ui.code>:icon</x-ui.code> - Slot for icon (warning/info)</li>
                                <li><x-ui.code>:message</x-ui.code> - Slot for modal content</li>
                                <li><x-ui.code>:footer</x-ui.code> - Slot for action buttons</li>
                            </ul>
                        </div>
                    </div>
                </x-ui.card>

                <x-ui.card id="nav-links" title="Navigation Links">
                    <div class="flex flex-wrap gap-3">
                        <x-ui.nav-link href="#" :active="true">Active</x-ui.nav-link>
                        <x-ui.nav-link href="#">Default</x-ui.nav-link>
                        <x-ui.nav-link href="#">Another link</x-ui.nav-link>
                    </div>
                </x-ui.card>

                <x-ui.card id="tables" title="Tables">
                    <x-ui.table>
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
                                <td><x-ui.chip color="green">Confirmed</x-ui.chip></td>
                            </tr>
                            <tr>
                                <td>John Smith</td>
                                <td>Organizer</td>
                                <td><x-ui.chip color="gold">Pending</x-ui.chip></td>
                            </tr>
                            <tr>
                                <td>Aisha Patel</td>
                                <td>Member</td>
                                <td><x-ui.chip color="coral">Invited</x-ui.chip></td>
                            </tr>
                        </tbody>
                    </x-ui.table>
                </x-ui.card>

                <x-ui.card id="palette" title="Color Palette">
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
                            <div class="lm-card overflow-hidden">
                                <div class="h-16 {{ $swatch['bg'] }}"></div>
                                <div class="p-5">
                                    <div class="font-mono text-[13px] {{ $swatch['text'] }}">{{ $swatch['label'] }}</div>
                                    <div class="mt-1 text-[12px] text-muted">Token / utility</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </x-ui.card>

                <x-ui.card id="typography" title="Typography">
                    <div class="grid gap-8">
                        <div class="grid gap-4">
                            <x-ui.text.h1 class="text-[clamp(32px,5vw,54px)] font-bold tracking-[-0.03em]">Heading
                                1</x-ui.text.h1>
                            <x-ui.text.h2 class="text-[clamp(28px,4vw,44px)] font-bold tracking-[-0.02em]">Heading
                                2</x-ui.text.h2>
                            <x-ui.text.h3 class="text-[28px] font-bold tracking-[-0.02em]">Heading 3</x-ui.text.h3>
                            <x-ui.text.h4 class="text-[22px] font-bold tracking-[-0.01em]">Heading 4</x-ui.text.h4>
                            <x-ui.text.h5 class="text-[18px] font-bold">Heading 5</x-ui.text.h5>
                            <x-ui.text.h6 class="text-[15px] font-bold uppercase tracking-[0.12em] text-muted">Heading
                                6</x-ui.text.h6>
                        </div>

                        <div class="grid gap-3">
                            <x-ui.text.body class="text-[16px] leading-relaxed text-foreground">
                                Body text is readable and token-driven. Use <span class="text-muted">muted</span> for
                                supporting information.
                            </x-ui.text.body>
                            <x-ui.text.muted class="text-[13px]">Small / muted text for captions, help text, and
                                meta.</x-ui.text.muted>
                            <p class="text-[15px]">
                                <a href="#"
                                    class="text-primary hover:text-primary-hover underline underline-offset-4">Link
                                    style</a>
                                for navigation and inline references.
                            </p>
                        </div>
                    </div>
                </x-ui.card>
            </div>
        </x-ui.section>
    </main>

    <x-site.footer />
@endsection
