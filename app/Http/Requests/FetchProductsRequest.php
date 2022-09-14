<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Rules\CategoryRule;
use Illuminate\Foundation\Http\FormRequest;

class FetchProductsRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'category' => ['bail', 'string', 'filled', new CategoryRule],
            'max_price' => ['int', 'filled'],
        ];
    }
}
