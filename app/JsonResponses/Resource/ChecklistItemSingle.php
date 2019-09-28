<?php

namespace App\JsonResponses\Resource;

use Illuminate\Http\Resources\Json\JsonResource;

class ChecklistItemSingle extends JsonResource
{
    public function toArray($request)
    {
        return [
            'type' => 'items',
            'id' => $this->id,
            'attributes' => [
                'description' => $this->description,
                'is_completed' => $this->is_completed,
                'completed_at' => $this->completed_at,
                'due' => $this->due,
                'urgency' => $this->urgency,
                'updated_by' => $this->updated_by,
                'created_by' => $this->created_by,
                'checklist_id' => $this->checklist_id,
                'assignee_id' => $this->assignee_id,
                'task_id' => $this->task_id,
                'deleted_at' => $this->deleted_at,
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,
            ],
            'links' => [
                'self' => route('items.show', ['checklistId' => $this->checklist_id, 'itemId' => $this->id]),
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