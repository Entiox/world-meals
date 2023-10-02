<?php

namespace App\Http\Requests;

use App\Rules\CategoryRule;
use Illuminate\Foundation\Http\FormRequest;

class MealRequest extends FormRequest
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
            "per_page" => ["bail", "nullable", "integer", "min:0"],
            "page" => ["bail", "nullable", "integer", "min:1"],
            "category" => ["nullable", new CategoryRule()],
            "tags" => ["nullable", "array"],
            "tags.*" => ["bail", "integer", "min:1", "distinct"],
            "with" => ["nullable", "array"],
            "with.*" => ["bail", "in:ingredients,category,tags", "distinct"],
            "lang" => ["bail", "required", "in:en,hr,de"],
            "diff_time" => ["nullable", "integer"],
        ];
    }

    protected function prepareForValidation()
    {
        return $this->merge([
            "per_page" => $this->per_page ? $this->per_page : NULL,
            "page" => $this->page ? $this->page : NULL,
            "category" => $this->category ? $this->category : NULL,
            "tags" => $this->tags ? explode(",", $this->tags) : NULL,
            "with" => $this->with ? explode(",", $this->with) : NULL,
            "diff_time" => $this->diff_time ? $this->diff_time : NULL,
        ]);
    }
}
