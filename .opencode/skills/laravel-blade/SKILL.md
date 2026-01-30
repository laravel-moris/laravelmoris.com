---

name: laravel-blade
description: Organize Laravel Blade pages, layouts, partials, and anonymous components using @extends/@section/@yield, slots, attribute bags, @class, and stacks (@push/@stack) without class-based components.
---

# Laravel Blade organization

## Scope (hard rules)

* Use **anonymous Blade components** (files under `resources/views/components`) and **do not use class-based components**. ([Laravel][1])
* Use Blade’s built-in composition tools: `@extends`, `@section`, `@yield`, `$slot`, `x-slot`, `$attributes`, `@class`, and stacks (`@push`, `@stack`). ([Laravel][1])

## Recommended view structure

Blade views live in `resources/views`, and anonymous components are discovered from `resources/views/components`. ([Laravel][1])

Suggested folders (adapt names to taste):

* `resources/views/layouts/` — app shells (template inheritance)
* `resources/views/pages/` — page “wiring” (compose components + partials)
* `resources/views/partials/` — small includes (`@include`)
* `resources/views/components/` — reusable UI blocks primitives

Community reference for splitting “partials/includes/components” to keep views clean: ([Prateeksha Web Design][2])

## Layouts (template inheritance)

Use a layout for the outer HTML skeleton and `@yield` for page sections. ([Laravel][1])

Example pattern:

* `layouts/app.blade.php`: `@yield('content')` plus `@stack('scripts')`
* `pages/home.blade.php`: `@extends('layouts.app')`, `@section('content')`

## Pages should be composition-heavy, markup-light

Pages should mostly **compose components** (especially for animation-heavy pages). Blade supports rendering components and passing content via slots. ([Laravel][1])

## Components: slots first

* Default slot content is `{{ $slot }}`. ([Laravel][1])
* Named slots use `<x-slot:...>` (or `<x-slot:title>`). ([Laravel][1])
* Slots can also carry their own attributes and class merging. ([Laravel][1])

## Props vs attribute bag (the clean split)

In **anonymous components**, define props with `@props([...])`. Anything not declared becomes part of the **attribute bag** (`$attributes`). ([Laravel][1])

* Render all attributes: `<div {{ $attributes }}>` ([Laravel][1])
* Default classes: `$attributes->merge(['class' => '...'])` ([Laravel][1])
* Conditional classes in markup: `@class([...])` ([Laravel][1])
* Conditional class merging from the attribute bag: `$attributes->class([...])` ([Laravel][1])

## Boolean props: recommended patterns

### Treat it as an attribute-flag

If you want `<x-card dismissable>` with no prop value, the **reliable, documented** check is presence:

```blade

@props(['dismissable'] => false)
```

The `has(...)` method is documented for component attribute bags. ([Laravel][1])

**I can’t confirm that** Laravel guarantees a bare attribute like `dismissable` will always arrive as a literal `true` boolean when extracted via `@props`, because the official Blade docs describe prop extraction and attribute bags but don’t explicitly document the exact runtime value assigned for “valueless” attributes. The presence-check (`$attributes->has`) is the safe, spec-like approach. ([Laravel][1])

## Nested / grouped components (subdirectories)

Laravel supports a “root component in its own directory” convention (so you can build component families). Example shown in docs for an `accordion/accordion.blade.php` root plus `accordion/item.blade.php`. ([Laravel][1])

## Stacks for page-specific JS/CSS (animations!)

Use stacks to keep per-page scripts/styles out of shared layouts/components:

* Push: `@push('scripts') ... @endpush`
* Render: `@stack('scripts')`
* Conditional push: `@pushIf(...)` ([Laravel][1])

## Blade directives you’ll use constantly

* `@class([...])` for conditional classes in-line. ([Laravel][1])
* Attribute directives like `@checked`, `@selected`, `@disabled` for clean boolean HTML attributes. ([Laravel][1])

---

[1]: https://laravel.com/docs/12.x/blade "Blade Templates - Laravel 12.x - The PHP Framework For Web Artisans"
[2]: https://prateeksha.com/blog/laravel-partials-clean-blade-views-includes-components? "Laravel Partials 101: Clean Up Messy Blade 
