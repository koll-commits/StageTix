<?php

namespace App\Concerns;

use App\Models\User;
use Illuminate\Validation\Rule;

trait ProfileValidationRules
{
    /**
     * Aturan profil: nama dan email.
     *
     * @return array<string, array<int, mixed>>
     */
    protected function profileRules(?int $userId = null): array
    {
        return [
            'name' => $this->nameRules(),
            'email' => $this->emailRules($userId),
        ];
    }

    /**
     * @return array<int, mixed>
     */
    protected function nameRules(): array
    {
        return ['required', 'string', 'max:255'];
    }

    /**
     * Aturan email; abaikan baris milik user yang sedang diedit.
     *
     * @return array<int, mixed>
     */
    protected function emailRules(?int $userId = null): array
    {
        return [
            'required', 'string', 'email', 'max:255',
            $userId === null
                ? Rule::unique(User::class)
                : Rule::unique(User::class)->ignore($userId),
        ];
    }
}
