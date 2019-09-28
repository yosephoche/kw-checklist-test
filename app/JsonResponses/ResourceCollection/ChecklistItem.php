<?php

namespace App\JsonResponses\ResourceCollection;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ChecklistItem extends ResourceCollection
{
    public $collects = \App\JsonResponses\Resource\Item::class;

    public function toArray($request)
    {
        $item = $this->collection->first();

        if ($this->collection->isEmpty()) {
            return [
                'type' => 'checklists',
                'id' => null,
                'attributes' => [
                    'items' => [],
                ],
                'links' => [
                    'self' => null,
                ],
            ];
        }

        $checklist = $item->checklist;

        return [
            'type' => 'checklists',
            'id' => $checklist->id,
            'attributes' => [
                'object_domain' => $checklist->object_domain,
                'object_id' => $checklist->object_id,
                'description' => $checklist->description,
                'is_completed' => $checklist->is_completed,
                'due' => $checklist->due,
                'urgency' => $checklist->urgency,
                'completed_at' => $checklist->completed_at,
                'last_updated_by' => $checklist->updated_by,
                'updated_at' => $checklist->updated_at,
                'created_at' => $checklist->created_at,
                'items' => $this->collection,
            ],
            'links' => [
                'self' => route('checklists.show', ['checklistId' => $checklist->id]),
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

    public function with($request)
    {
        return [
            'meta' => [
                'count' => $this->count(),
            ],
        ];
    }
}