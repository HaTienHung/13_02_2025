<?php

namespace App\Http\Requests\CMS;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class InventoryRequest extends FormRequest
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
    $rule = [
      'product_id' => 'required|integer|exists:products,id',
      'quantity' => 'required|integer|min:1'
    ];

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
        'product_id.required' => 'Sản phẩm là bắt buộc.',
        'product_id.integer' => 'ID sản phẩm phải là số.',
        'product_id.exists' => 'Sản phẩm không tồn tại trong hệ thống.',
        'quantity.required' => 'Số lượng là bắt buộc.',
        'quantity.integer' => 'Số lượng phải là số.',
        'quantity.min' => 'Số lượng phải lớn hơn hoặc bằng 1.',
      ];
    } else {
      return [
        'product_id.required' => 'Product is required.',
        'product_id.integer' => 'Product ID must be an integer.',
        'product_id.exists' => 'The selected product does not exist.',
        'quantity.required' => 'Quantity is required.',
        'quantity.integer' => 'Quantity must be an integer.',
        'quantity.min' => 'Quantity must be at least 1.',
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
