<?php

namespace App\JsonResponses\Resource;

use Illuminate\Http\Resources\Json\JsonResource;

class ChecklistStore extends JsonResource
{
    public function toArray($request)
    {
        return [
            'type' => 'checklists',
            'id' => $this->id,
            'attributes' => [
                'object_domain' => $this->object_domain,
                'object_id' => $this->object_id,
                'task_id' => $this->task_id,
                'description' => $this->description,
                'is_completed' => $this->is_completed,
                'due' => $this->due,
                'urgency' => $this->urgency,
                'completed_at' => $this->completed_at,
                'updated_by' => $this->updated_by,
                'created_by' => $this->created_by,
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,
            ],
            'links' => [
                'self' => route('checklists.show', ['checklistId' => $this->id]),
            ],
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