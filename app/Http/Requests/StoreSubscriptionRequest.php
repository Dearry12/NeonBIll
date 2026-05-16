<?php

namespace App\Http\Requests;

use App\Models\Subscription;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSubscriptionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', Rule::in(Subscription::CATEGORIES)],
            'price' => ['required', 'integer', 'min:0'],
            'currency' => ['required', Rule::in(Subscription::CURRENCIES)],
            'billing_cycle' => ['required', Rule::in(Subscription::BILLING_CYCLES)],
            'next_due_date' => ['required', 'date'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Service name is required.',
            'price.required' => 'Price is required.',
            'price.min' => 'Price cannot be negative.',
            'billing_cycle.in' => 'Billing cycle must be Monthly or Yearly.',
            'next_due_date.required' => 'Next due date is required.',
        ];
    }
}
