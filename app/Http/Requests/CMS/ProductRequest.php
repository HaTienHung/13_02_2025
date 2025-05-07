<?php

namespace App\Http\Requests\CMS;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class ProductRequest extends FormRequest
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
          'name' => 'required|string|min:3|max:191',
          'description' => 'nullable|string|max:2000',
          'price' => 'required|numeric|min:1000',
          'category_id' => 'required|integer|exists:categories,id',
          'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ];
        break;
      case "update":
        $rule = [
          'name' => 'required|string|min:3|max:191',
          'description' => 'nullable|string|max:2000',
          'price' => 'required|numeric',
          'category_id' => 'required|integer|exists:categories,id',
          'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
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
        'name.required' => 'Tên là trường bắt buộc',
        'name.string' => 'Tên phải là một chuỗi kí tự',
        'name.min' => 'Tên phải có ít nhất :min kí tự',
        'name.max' => 'Tên không được vượt quá :max kí tự',
        'description.string' => 'Mô tả sản phẩm phải là một chuỗi kí tư',
        'description.max' => 'Mô tả sản phẩm không được vượt quá :max kí tự',
        'price.required' => 'Giá là trường bắt buộc',
        'price.numeric' => 'Giá phải là số ',
        'price.min' => 'Giá phải lớn hơn 1000 VNĐ ',
        'category_id.required' => 'Danh mục sản phẩm là trường bắt buộc',
        'category_id.integer' => 'Danh mục sản phẩm là một số nguyên',
        'category_id.required' => 'Danh mục sản phẩm là trường bắt buộc',
        'category_id.exists' => 'Danh mục không tồn tại trong hệ thống.',
        'image.required' => 'Ảnh là trường bắt buộc.',
        'image.image' => 'Trường ảnh phải là một tệp hình ảnh.',
        'image.mimes' => 'Ảnh phải có định dạng: jpeg, png, jpg, gif, svg, webp.',
        'image.max' => 'Ảnh không được vượt quá 2MB.',
      ];
    } else {
      return [
        'name.required' => 'The name is a required field.',
        'name.string' => 'The name must be a string.',
        'name.min' => 'The name must be at least :min characters.',
        'name.max' => 'The name must not exceed :max characters.',
        'description.string' => 'The product description must be a string.',
        'description.max' => 'The product description must not exceed :max characters.',
        'price.required' => 'Price is a required field.',
        'price.numeric' => 'The price must be a number.',
        'price.min' => 'The price must be greater than 1000 VNĐ ',
        'category_id.required' => 'The product category is a required field.',
        'category_id.integer' => 'The product category must be an integer.',
        'category_id.exists' => 'The category does not exist in the system.',
        'image.required' => 'The image is a required field.',
        'image.image' => 'The image must be a valid image file.',
        'image.mimes' => 'The image must be in one of the following formats: jpeg, png, jpg, gif, svg, webp.',
        'image.max' => 'The image must not exceed 2MB.',
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
