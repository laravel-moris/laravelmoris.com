---
name: actions-first-architecture
description: >-
  Enforce an Actions-first Laravel architecture where all business logic lives in `app/Actions/**`, adapters stay thin (Controllers/Jobs/Commands/Listeners/UI handlers), and Action inputs are typed with Spatie Laravel Data. Actions compose via dependency injection and expose a single `execute(...)` method.
---

## When to activate (triggers)
Activate this skill whenever the task involves ANY of the following:
- Creating or editing: Controllers, Form Requests, Jobs, Commands, Listeners/Subscribers, Livewire/Filament handlers, route closures
- Adding “business logic” (domain rules, workflows, side effects, orchestration) anywhere
- Any change that touches request handling or application workflows (e.g. checkout, billing, onboarding, imports)

## Non-negotiable rules
1) **No business logic outside `app/Actions/**`.**
   - Controllers/Jobs/Commands/Listeners/UI handlers are *adapters only*.
   - They may only: authorize, validate, map input → Data, call Actions, return responses / dispatch events / queue jobs.

2) **Actions are mandatory for all use cases.**
   - Every meaningful operation must be an Action: `CreateOrder`, `GenerateSku`, `CapturePayment`, etc.
   - No “fat controller”, no “smart job”, no route closure logic.

3) **Spatie Data is mandatory for inputs (and usually outputs).**
   - Actions MUST NOT accept raw arrays for input.
   - Define `Data` objects in `app/Data/**`.
   - Prefer `DataCollection<TData>` over arrays.

4) **Actions compose via dependency injection.**
   - Never instantiate Actions with `new`.
   - Use constructor injection for Action-to-Action reuse.

5) **Action API convention**
   - Each Action exposes exactly one public method: `execute(...)`.
   - Keep Actions framework-light: accept Data/value objects/models; return a model/Data/value.

## Adapter templates (must follow)
### Controller template
- Resolve Action via DI
- Use Form Request or Data rules
- Build Data
- Call Action
- Return response

### Job/Listener/Command template
- Minimal adapter glue
- Build Data
- Call Action
- No branching workflows (those belong in Actions)

## Required code patterns (examples)

### Action composing another Action (DI)
```php
<?php

declare(strict_types=1);

namespace App\Actions;

use Illuminate\Support\Str;

final class GenerateSku
{
    public function execute(): string
    {
        return 'SKU-'.Str::upper(Str::random(10));
    }
}
````

```php
<?php

declare(strict_types=1);

namespace App\Data;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

final class OrderItemData extends Data
{
    public function __construct(
        public string $sku,
        public int $quantity,
        public float $price,
    ) {}
}

final class CreateOrderData extends Data
{
    /** @param DataCollection<OrderItemData> $items */
    public function __construct(
        public int $userId,
        public DataCollection $items,
    ) {}
}
```

```php
<?php

declare(strict_types=1);

namespace App\Actions;

use App\Data\CreateOrderData;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

final class CreateOrder
{
    public function __construct(
        private GenerateSku $generateSku,
    ) {}

    public function execute(CreateOrderData $data): Order
    {
        return DB::transaction(function () use ($data): Order {
            $order = Order::create([
                'user_id' => $data->userId,
                'sku' => $this->generateSku->execute(),
                'status' => 'pending',
            ]);

            foreach ($data->items as $item) {
                $order->items()->create([
                    'sku' => $item->sku,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                ]);
            }

            return $order->fresh('items');
        });
    }
}
```