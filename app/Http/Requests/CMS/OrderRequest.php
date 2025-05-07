<?php

namespace App\Http\Requests\CMS;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class OrderRequest extends FormRequest
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
        $rule = [];
        break;
      case "update":
        $rule = [
          'status' => 'required|in:completed,cancelled',
        ];
        break;
      default:
        $rule = [];
    endswitch;

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
        'status.required' => 'Trường trạng thái là bắt buộc.',
        'status.in' => 'Trường trạng thái chỉ được nhận các giá trị: completed, cancelled.',
      ];
    } else {
      return [
        'status.required' => 'The status field is required.',
        'status.in' => 'The status must be one of the following: completed, cancelled.',
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
