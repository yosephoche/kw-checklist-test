<?php

namespace App\JsonResponses\ResourceCollection;

use Illuminate\Http\Resources\Json\ResourceCollection;

class Checklist extends ResourceCollection
{
    public $collects = \App\JsonResponses\Resource\Checklist::class;

    public function toArray($request)
    {
        // dd($this->collection[0]->id);
        // return [
        //     'data' => $this->collection,
        // ];

        return [
            'data' => [
                'type' => 'checklist',
                'id' => $this->collection[0]->id,
                'attributes' => [
                    'object_domain' => $this->collection[0]->object_domain,
                    'object_id' => $this->collection[0]->object_id,
                    'task_id' => $this->collection[0]->task_id,
                    'description' => $this->collection[0]->description,
                    'is_completed' => $this->collection[0]->is_completed,
                    'due' => $this->collection[0]->due,
                    'urgency' => $this->collection[0]->urgency,
                    'completed_at' => $this->collection[0]->completed_at,
                    'updated_by' => $this->collection[0]->updated_by,
                    'created_by' => $this->collection[0]->created_by,
                    'created_at' => $this->collection[0]->created_at,
                    'updated_at' => $this->collection[0]->updated_at,
                    // 'items' => $this->collection[0]->when(
                    //     $request->has('include') && $request->include == 'items',
                    //     ChecklistItem::collection($this->collection[0]->items)
                    // )
                ],
                'links' => [
                    'self' => url('api/v1/checklists/' . $this->collection[0]->id)
                ]
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
