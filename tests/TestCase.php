<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Tymon\JWTAuth\Facades\JWTAuth;

abstract class TestCase extends Laravel\Lumen\Testing\TestCase
{
    // use DatabaseMigrations;

    protected $baseUrl = "http://localhost:8091/api/v1/checklists";
    protected $user;
    protected $token;

    protected $defaultHeaders = [
        'Accept'        => 'application/vnd.api+json',
        'Content-Type'  => 'application/json',
        'Authorization' => '',
    ];

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = factory('App\User')->create();

        // $this->runDatabaseMigrations();
        $this->userLoggedIn();
    }

    public function userLoggedIn()
    {
        $this->actingAs($this->user);
        $this->token = 'Bearer '.JWTAuth::fromUser($this->user);
        $this->defaultHeaders['Authorization'] = 'Bearer ' . JWTAuth::fromUser($this->user);
    }


    /**
     * Creates the application.
     *
     * @return \Laravel\Lumen\Application
     */
    public function createApplication()
    {
        return require __DIR__.'/../bootstrap/app.php';
    }

    // protected function seeIsNotSoftDeletedInDatabase($table, array $data, $onConnection = null)
    // {
    //     $database = $this->app->make('db')->connection($onConnection);
    //     $count = $database->table($table)->where($data)->whereNull('deleted_at')->count();

    //     $this->assertGreaterThan(0, $count, sprintf(
    //         'Found unexpected records in database table [%s] that matched attributes [%s].', $table, json_encode($data)
    //     ));

    //     return $this;
    // }

    // protected function seeIsSoftDeletedInDatabase($table, array $data, $onConnection = null)
    // {
    //     $database = $this->app->make('db')->connection($onConnection);
    //     $count = $database->table($table)->where($data)->whereNotNull('deleted_at')->count();

    //     $this->assertGreaterThan(0, $count, sprintf(
    //         'Unable to find row in database table [%s] that matched attributes [%s].', $table, json_encode($data)
    //     ));

    //     return $this;
    // }
}
