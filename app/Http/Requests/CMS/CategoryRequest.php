<?php

namespace App\Http\Requests\CMS;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class CategoryRequest extends FormRequest
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
   * @return array<string, mixed>
   */
  public function rules()
  {
    $action = $this->segments()[3];

    switch ($action):
      case "create":
        $rule = [
          'name' => 'required|string|max:255|min:2',
        ];
        break;
      case "update":
        $rule = [
          'name' => 'required|string|max:255|min:2',
          'slug' => 'required|string',
        ];
        break;
      default:
        $rule = [];
    endswitch;

    return $rule;

    return $rule;
  }

  /**
   * Get the error messages for the defined validation rules.
   *
   * @return array
   */
  public function messages()
  {
    $lang = ($this->hasHeader('X-localization')) ? $this->header('X-localization') : 'vi';

    if ($lang == 'vi') {
      return [
        'name.required' => 'Tên là bắt buộc.',
        'name.string' => 'Tên phải là chuỗi.',
        'name.max' => 'Tên không được vượt quá 255 ký tự.',
        'name.min' => 'Tên không được ít hơn 2 ký tự.',
        'slug.required' => 'Slug là bắt buộc.',
        'slug.string' => 'Slug phải là chuỗi.',
      ];
    } else {
      return [
        'name.required' => 'Name is required.',
        'name.string' => 'Name must be a string.',
        'name.max' => 'Name may not be greater than 255 characters.',
        'name.min' => 'Name may not be less than 2 characters.',
        'slug.required' => 'Slug is required.',
        'slug.string' => 'Slug must be a string.',
      ];
    }
  }

  protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
  {
    $errors = (new ValidationException($validator))->errors();
    throw new HttpResponseException(response()->json(
      [
        'error' => $errors,
        'status_code' => 422,
      ],
      JsonResponse::HTTP_UNPROCESSABLE_ENTITY
    ));
  }
}
