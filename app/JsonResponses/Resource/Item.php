<?php

namespace App\JsonResponses\Resource;

use Illuminate\Http\Resources\Json\JsonResource;

class Item extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->description,
            'user_id' => $this->user_id,
            'is_completed' => $this->is_completed,
            'due' => $this->due,
            'urgency' => $this->urgency,
            'assignee_id' => $this->assignee_id,
            'task_id' => $this->task_id,
            'completed_at' => $this->completed_at,
            'last_updated_by' => $this->updated_by,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
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