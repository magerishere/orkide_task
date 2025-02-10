<?php

namespace Modules\User\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class UserCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return $this->collection->map(fn($user) => [
            'id' => $user->id,
            'mobile' => $user->mobile,
            'email' => $user->email,
            'full_name' => $user->fullName,
            'transactions' => $user->transactions ?? [],
        ])->toArray();
    }
}
