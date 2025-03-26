<?php

return [
    'success' => [
        'success'=>'Success!',
        'product' => [
            'create' =>'Create product success!',
            'update'=>'Update product success!',
            'delete'=>'Delete product success!'
        ],
        'category' => [
            'create' =>'Create category success!',
            'update'=>'Update category success!',
            'delete'=>'Delete category success!'
        ],
        'cart' => [
            'create' => 'Product successfully added to your shopping cart!',
            'update'=>'Update cart success!',
            'delete'=>'Delete cart success!',
            'clear' =>'Clear cart success!',
            'checkout' => 'Order placed successfully.'
        ],
        'order' => [
            'create'=>'Create order success!',
            'update'=>'Update order success!',
            'delete'=>'Delete order success!',
        ],
        'inventory'=>[
            'create' => 'Add product to inventory success!',
        ],
        'user'=>[
            'create' => 'Create user success!',
            'update' => 'Update user success!',
            'delete' => 'Delete user success!',
            'login_success' => 'Login success!',
            'logout_success' => 'Logout success!',
        ],
    ],
    'errors' => [
        'errors' => 'Failed!',
        'not_found' => 'Record does not exist!',
        'auth' => [
            'unauthorized' => 'You are not authorized to access this resource.!',
            'unauthenticated' => 'Invalid Credentials',
            'forbidden' => 'You do not have permission to perform this action.!',
        ],
        'category' => [
            'exists' => 'The category name already exists.!',
            'delete' => 'Delete category failed.! ',
            'cannot_delete' => 'Cannot delete the category because it still contains products.!',
        ] ,
        'validation' => [
            'invalid_data' => 'The provided data is invalid.',
        ],
        'user' => [
            'email_not_found' => 'Email not found!',
        ]
    ]
];
