<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class BasePolicy
{
    /**
     * Kiểm tra quyền xem model
     */
    public function view(User $user, Model $model)
    {
        return $user->isAdmin() || $model->user_id === $user->id;
    }

    /**
     * Kiểm tra quyền cập nhật model
     */
    public function update(User $user, Model $model)
    {
        return $user->isAdmin() || $model->user_id === $user->id;
    }

    /**
     * Kiểm tra quyền xóa model
     */
    public function delete(User $user, Model $model)
    {
        return $user->isAdmin() || $model->user_id === $user->id;
    }

    /**
     * Kiểm tra quyền tạo model
     */
    public function create(User $user)
    {
        return $user->isAdmin();
    }
}
