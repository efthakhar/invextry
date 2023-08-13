<?php

namespace App\Http\Requests\Brand;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class UpdateBrandRequest extends FormRequest
{
    public function authorize(): bool
    {

        return Gate::allows('update_brand');

    }

    public function rules(): array
    {

        $brand_id = $this->route('id');

        return [
            'name' => ['required', 'string', Rule::unique('brands')->ignore($brand_id)],
            'slug' => ['required', 'string', Rule::unique('brands')->ignore($brand_id)],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'slug' => str()->slug($this->slug),
        ]);
    }
}
