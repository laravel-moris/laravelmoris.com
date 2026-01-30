<?php

declare(strict_types=1);

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\In;

final class ProviderRedirectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, string>|In>
     */
    public function rules(): array
    {
        return [
            'provider' => ['required', 'string', Rule::in(['github', 'google'])],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function validationData(): array
    {
        return array_merge(
            $this->all(),
            ['provider' => $this->route('provider')]
        );
    }
}
