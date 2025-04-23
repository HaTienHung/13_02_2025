<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'name' => $this->name,
            'phone_number' => $this->phone_number,
            'address' => $this->address,
            'email'=>$this->email,
        ];
    }
}
