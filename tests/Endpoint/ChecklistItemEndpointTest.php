<?php

use Carbon\Carbon;
use Carbon\CarbonImmutable;

use Laravel\Lumen\Testing\DatabaseTransactions;

class ChecklistItemEndpointTest extends TestCase
{
    use DatabaseTransactions;

    protected $checklist;

    public function createChecklist()
    {
        $this->checklist = factory('App\Checklist')->create();
    }

    public function testIndexItem()
    {
        $this->createChecklist();
        $this->actingAs($this->user);
        $checklistId = $this->checklist->id;
        $new = factory('App\Item', 20)->create([
            'checklist_id' => $checklistId,
            'user_id' => $this->user->id,
        ]);

        $this->json('GET', $this->baseUrl . "/{$checklistId}/items", [], $this->defaultHeaders);

        $this->seeStatusCode(200)
            ->seeJsonStructure(['data' => ['type', 'id', 'attributes' => [
                'object_domain', 'object_id', 'description', 'is_completed', 'due', 'urgency',
                'completed_at', 'last_updated_by', 'updated_at', 'created_at', 'items'
            ]], 'links', 'meta']);
    }

    public function testStoreItem()
    {
        $this->createChecklist();
        $checklistId = $this->checklist->id;
        $data = [
            'description' => 'Deals Item',
            'due' => Carbon::parse("2019-01-25T07:50:14+00:00"),
            'urgency' => 0,
            'assignee_id' => 1234,
        ];

        $this->json('POST', $this->baseUrl . "/{$checklistId}/items", [
            'data' => [
                'attributes' => $data
            ]
        ], $this->defaultHeaders);

        $this->seeStatusCode(201);
        $this->seeJsonStructure(['data' => ['type', 'id', 'attributes' => [
            'description', 'is_completed', 'completed_at', 'due', 'urgency',
            'updated_by', 'updated_at', 'created_at', 'assignee_id', 'task_id'
        ], 'links' => ['self']]]);
    }

    public function testShowItem()
    {
        $this->createChecklist();
        $checklistId = $this->checklist->id;
        $existing = factory('App\Item')->create([
            'checklist_id' => $checklistId,
            'user_id' => $this->user->id,
        ]);

        $this->json('GET', $this->baseUrl . "/{$checklistId}/items/" . $existing->id, [], $this->defaultHeaders);

        $this->seeJsonStructure(['data' => ['type', 'id', 'attributes' => [
            'description', 'is_completed', 'completed_at', 'due', 'urgency', 'updated_by', 'created_by',
            'checklist_id', 'assignee_id', 'task_id', 'deleted_at', 'created_at', 'updated_at'
        ], 'links' => ['self']]]);
    }

    public function testUpdateItem()
    {
        $this->createChecklist();
        $checklistId = $this->checklist->id;
        $existing = factory('App\Item')->create([
            'checklist_id' => $checklistId,
            'user_id' => $this->user->id,
        ]);

        $data = [
            'description' => 'Deals Item Urgent',
            'due' => Carbon::parse("2019-01-25T07:50:14+00:00"),
            'urgency' => 10,
            'assignee_id' => 1234,
        ];

        $this->json('PATCH', $this->baseUrl . "/{$checklistId}/items/" . $existing->id, [
            'data' => [
                'attributes' => $data
            ]
        ], $this->defaultHeaders);

        $data['id'] = $existing->id;

        $this->seeJsonStructure(['data' => ['type', 'id', 'attributes' => [
            'description', 'is_completed', 'completed_at', 'due', 'urgency',
            'updated_by', 'updated_at', 'created_at', 'assignee_id', 'task_id'
        ], 'links' => ['self']]]);
    }

    public function testDestroyItem()
    {
        $this->createChecklist();
        $checklistId = $this->checklist->id;
        $existing = factory('App\Item', 2)->create([
            'checklist_id' => $checklistId,
            'user_id' => $this->user->id,
        ]);

        $randId = $existing->first()->value('id');

        $this->json('DELETE', $this->baseUrl . "/{$checklistId}/items/" . $randId, [], $this->defaultHeaders);

        $this->seeStatusCode(204);
    }

    public function testCompleteItem()
    {
        $this->createChecklist();
        $checklistId = $this->checklist->id;
        $items = factory('App\Item', 3)->create([
            'checklist_id' => $checklistId,
            'user_id' => $this->user->id,
            'is_completed' => true,
        ]);

        $itemIds = [
            'data' => $items->map(function ($item) {
                return [
                    'item_id' => $item->id,
                ];
            })->toArray()
        ];

        $this->json('POST', $this->baseUrl . "/complete", $itemIds, $this->defaultHeaders);
        $this->seeStatusCode(200);
        $this->seeJsonStructure([
            'data' => [
                '*' => [
                    'id', 'item_id', 'is_completed', 'checklist_id'
                ]
            ]
        ]);
    }

    public function testIncompleteItem()
    {
        $this->createChecklist();
        $checklistId = $this->checklist->id;
        $items = factory('App\Item', 3)->create([
            'checklist_id' => $checklistId,
            'user_id' => $this->user->id,
            'is_completed' => false,
        ]);

        $itemIds = [
            'data' => $items->map(function ($item) {
                return [
                    'item_id' => $item->id,
                ];
            })->toArray()
        ];

        $this->json('POST', $this->baseUrl . "/incomplete", $itemIds, $this->defaultHeaders);
        $this->seeStatusCode(200);
        $this->seeJsonStructure([
            'data' => [
                '*' => [
                    'id', 'item_id', 'is_completed', 'checklist_id'
                ]
            ]
        ]);
    }

    // public function testSummariesItem()
    // {

    // }

    // public function testUpdateBulkItem()
    // {

    // }
}
