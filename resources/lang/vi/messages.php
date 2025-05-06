<?php

return [
    'success' => [
        'success' => 'Thành công!',
        'product' => [
            'create' => 'Thêm sản phẩm thành công!',
            'update' => 'Cập nhật sản phẩm thành công!',
            'delete' => 'Xoá sản phẩm thành công!'
        ],
        'category' => [
            'create' => 'Thêm danh mục thành công!',
            'update' => 'Cập nhật danh mục thành công!',
            'delete' => 'Xoá danh mục thành công!'
        ],
        'cart' => [
            'create' => 'Thêm sản phẩm vào giỏ hàng thành công!',
            'update' => 'Cập nhật giỏ hàng thành công!',
            'delete' => 'Xoá sản phẩm khỏi giỏ hàng thành công!',
            'clear' => 'Dọn sạch giỏ hàng thành công!',
            'checkout' => 'Đặt hàng thành công'
        ],
        'order' => [
            'create' => 'Thêm đơn hàng thành công!',
            'update' => 'Cập nhật đơn hàng thành công!',
            'delete' => 'Xoá đơn hàng thành công!',
            'checkout' => 'Đặt hàng thành công!',
        ],
        'inventory' => [
            'create' => 'Thêm sản phẩm vào kho thành công!',
        ],
        'user' => [
            'create' => 'Thêm người dùng thành công !',
            'update' => 'Cập nhật người dùng thành công !',
            'delete' => 'Xoá người dùng thành công !',
            'login_success' => 'Đăng nhập thành công!',
            'logout_success' => 'Đăng xuất thành công!',
        ],
    ],
    'errors' => [
        'errors' => 'Thất bại',
        'not_found' => 'Bản ghi không tồn tại!',
        'auth' => [
            'unauthorized' => 'Bạn không có quyền truy cập.',
            'unauthenticated' => 'Thông tin đăng nhập không hợp lệ',
            'forbidden' => 'Bạn không được phép thực hiện hành động này.',
        ],
        'category' => [
            'exists' => 'Tên danh mục đã tồn tại!',
            'delete' => 'Xoá danh mục thất bại!',
            'cannot_delete' => 'Không thể xoá danh mục vì danh mục vẫn còn sản phẩm!',
        ],
        'validation' => [
            'invalid_data' => 'Dự liệu không hợp lệ!',
        ],
        'user' => [
            'email_not_found' => 'Không tìm thấy email!',
        ],
        'rules' => [
            'required'  => ':attribute là trường bắt buộc.',
            'string'    => ':attribute phải là một chuỗi ký tự.',
            'in'        => 'Giá trị của trường :attribute phải là một trong các giá trị sau: :value',
            'not_in'    => ':attribute đã chọn không hợp lệ.',
            'min'       => ':attribute phải có ít nhất :value ký tự.',
            'url'       => ':attribute phải là địa chỉ URL hợp lệ.',
            'max'       => ':attribute không được vượt quá :value ký tự.',
            'integer'   => ':attribute phải là số nguyên.',
            'mimes'     => ':attribute phải có định dạng là: :value',
            'email'     => ':attribute phải có định dạng email.',
            'unique'    => ':attribute đã tồn tại.',
            'json'      => ':attribute phải là một chuỗi JSON hợp lệ.',
            'image'     => ':attribute phải là hình ảnh.',
            'array'     => 'Trường :attribute phải là một dãy giá trị.',
            'boolean'   => 'Trường :attribute phải có thể chuyển đổi thành true hoặc false.',
            'regex'     => ':attribute không đúng định dạng.',
            'exist'     => ':attribute không tồn tại.',
            'same'      => ':attribute và :other phải giống nhau.',
            'uploaded'  => 'Không thể tải tệp lên.',
            'numeric'   => ':attribute phải là số.',
            'after'     => ':attribute phải lớn hơn :value.',
            'date'        => 'Trường :attribute phải là ngày hợp lệ.',
            'date_format' => 'Trường :attribute phải theo định dạng :value.'
        ],
    ]
];
