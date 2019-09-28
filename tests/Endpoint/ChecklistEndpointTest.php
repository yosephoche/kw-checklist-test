<?php

use Laravel\Lumen\Testing\DatabaseTransactions;
use Tymon\JWTAuth\Facades\JWTAuth;

use App\User;

class ChecklistEndpointTest extends TestCase
{
    use DatabaseTransactions;

    public function testIndexChecklist()
    {
        $new = factory('App\Checklist', 5)->create();
        $this->json('GET', $this->baseUrl, [], $this->defaultHeaders);

        $this->seeStatusCode(200);
        $this->seeJsonStructure([
            "data" => [
                "type",
                "id",
                "attributes" => [
                    "object_domain",
                    "object_id",
                    "due",
                    "urgency",
                    "description",
                    "task_id"
                ],
                "links" => [
                    "self"
                ]
            ]
        ]);
    }

    public function testShowChecklist()
    {
        $existing = factory('App\Checklist')->create();

        $this->json('GET', $this->baseUrl . "/" . $existing->id, [], $this->defaultHeaders);

        $this->seeJsonStructure(['data' => ['type', 'id', 'attributes' => [
            'object_domain', 'object_id', 'description', 'is_completed',
            'due', 'urgency', 'completed_at', 'last_updated_by', 'updated_at', 'created_at'
        ], 'links' => ['self']]]);
    }

    public function testStoreChecklist()
    {
        // $this->refreshApplication();

        $parameters = [
            'data' => [
                'attributes' => [
                    'object_domain' => 'test',
                    'object_id' => '1000',
                    'due' => "2019-01-25T07:50:14+00:00",
                    'urgency' => 3,
                    'description' => "Need to verify this guy house. next level play",
                    'items' => [
                        "Visit his house",
                        "Capture a photo",
                        "Meet him on the house"
                    ],
                    'task_id' => '345'
                ]
            ]
        ];

        $this->json('POST', $this->baseUrl, $parameters, $this->defaultHeaders);

        $this->seeStatusCode(201);
        $this->seeJsonStructure([
            "data" => [
                "type",
                "id",
                "attributes" => [
                    "object_domain",
                    "object_id",
                    "due",
                    "urgency",
                    "description",
                    "task_id"
                ],
                "links" => [
                    "self"
                ]
            ]
        ]);
    }

    public function testUpdateChecklist()
    {
        $existing = factory('App\Checklist')->create();

        $parameters = [
            'data' => [
                "type" => "checklists",
                "id" => $existing->id,
                'attributes' => [
                    "object_domain" => "contact",
                    "object_id" => "1",
                    "description" => "updated data again",
                    "is_completed" => false,
                    "completed_at" => null,
                    "created_at" => "2018-01-25T07:50:14+00:00"
                ]
            ]
        ];
        // dd($this->baseUrl, $existing->id, $parameters);
        $this->json('PATCH', $this->baseUrl . "/" . $existing->id, $parameters, $this->defaultHeaders);

        $this->seeStatusCode(200);
        $this->seeJsonStructure([
            "data" => [
                "type",
                "id",
                "attributes" => [
                    "object_domain",
                    "object_id",
                    "due",
                    "urgency",
                    "description",
                ],
                "links" => [
                    "self"
                ]
            ]
        ]);
    }

    public function testDestroyChecklist()
    {
        $existing = factory('App\Checklist', 2)->create();

        $randId = $existing->first()->value('id');

        $this->json('DELETE', $this->baseUrl . "/" . $randId, [], $this->defaultHeaders);
        $this->seeStatusCode(204);
        $this->missingFromDatabase('checklists', ['id' => $randId], 'mysql');
    }
}
