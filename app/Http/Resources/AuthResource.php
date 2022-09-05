<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AuthResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "access_token" => $this->createToken("access_token")->plainTextToken,
            "u_email" => $this->email,
            "u_name" => $this->name,
            "join_date" => $this->created_at
        ];
    }
}
