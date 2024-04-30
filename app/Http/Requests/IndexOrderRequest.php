<?php

namespace App\Http\Requests;

use App\Enums\Order\OrderStatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IndexOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'national_code' => ['nullable', 'numeric', 'max_digits:10'],
            'mobile' => ['nullable'],
            'min' => ['nullable', 'integer', 'min:0'],
            'max' => ['nullable', 'integer', 'min:0'],
            'status' => ['nullable', 'string', Rule::in([OrderStatusEnum::ACTIVE, OrderStatusEnum::DEACTIVE,])],
        ];
    }
}
