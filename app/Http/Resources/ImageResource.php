<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ImageResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'         => $this->id,
            'user_id'    => $this->user_id,
            'vendor_id'  => $this->vendor_id,
            // 'user'       => new UserResource($this->user),
            // 'images'     => ImageDetailResource::collection($this->imageDetails)
        ];
    }
}
