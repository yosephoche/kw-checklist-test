<?php

namespace App\JsonResponses\Resource;

use Illuminate\Http\Resources\Json\JsonResource;

class ItemStatus extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'item_id' => $this->id,
            'is_completed' => $this->is_completed,
            'checklist_id' => $this->checklist_id,
        ];
    }

    public function withResponse($request, $response)
    {
        $response->withHeaders([
            'Accept' => 'application/vnd.api+json',
            'Content-Type' => 'application/vnd.api+json',
        ]);
    }
}