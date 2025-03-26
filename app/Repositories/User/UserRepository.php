<?php

namespace App\Repositories\User;

use App\Enums\Constant;
use App\Models\User;
use App\Repositories\BaseRepository;


class UserRepository extends BaseRepository implements UserInterface
{
    public function __construct(User $user)
    {
        parent::__construct($user);
    }

    // Có thể thêm các phương thức đặc biệt riêng cho Product nếu cần
    public function listUser($perpage = Constant::PER_PAGE)
    {
        $users = $this->model->search(request('searchFields'), request('search'))
            ->filter(request('filter'))
            ->sort(request('sort'))->paginate($perpage);
        return $users;
    }
}
