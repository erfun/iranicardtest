<?php

namespace App\Http\Requests;

use App\Rules\CheckCategoryIdIsValid;
use Illuminate\Foundation\Http\FormRequest;

class PostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "cat_id" => ["required", "integer", new CheckCategoryIdIsValid()],
            "title" => ["required", "string"],
            "post_content" => ["required", "string"]
        ];
    }
}
