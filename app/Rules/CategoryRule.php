<?php

declare(strict_types=1);

namespace App\Rules;

use App\Models\Category;
use Illuminate\Contracts\Validation\Rule;

class CategoryRule implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     */
    public function passes($attribute, $value): bool
    {
        return Category::where('name', $value)->exists();
    }

    /**
     * Get the validation error message.
     *
     */
    public function message(): string
    {
        return 'The category field is invalid.';
    }
}
