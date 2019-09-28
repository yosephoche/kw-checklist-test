<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->group(['prefix' => 'api/v1'], function () use($router) {
    $router->post('register', 'AuthController@register');
    $router->post('login', 'AuthController@login');

    $router->get('profile', 'UserController@profile');
    $router->get('user/{id}', 'UserController@singleUser');
    $router->get('users', 'UserController@allUsers');

    $router->group(['prefix' => 'checklists', 'middleware' => 'jwt.auth'], function () use($router) {

        // Templates
        $router->get('templates', ['uses' => 'TemplateController@index','as' => 'templates.index',]);
        $router->post('templates', ['uses' => 'TemplateController@store','as' => 'templates.store',]);
        $router->get('templates/{templateId}', ['uses' => 'TemplateController@show','as' => 'templates.show',]);
        $router->patch('templates/{templateId}', ['uses' => 'TemplateController@update','as' => 'templates.update',]);
        $router->delete('templates/{templateId}', ['uses' => 'TemplateController@destroy','as' => 'templates.destroy',]);
        $router->post('templates/{templateId}/assigns', ['uses' => 'TemplateController@assigns','as' => 'templates.assigns',]);

        // Histories
        $router->get('/histories', ['uses' => 'HistoryController@index','as' => 'histories.index',]);
        $router->get('/histories/{historyId}', ['uses' => 'HistoryController@show','as' => 'histories.show',]);

        // Items
        $router->post('/complete', ['uses' => 'ItemController@complete','as' => 'items.complete',]);
        $router->post('/incomplete', ['uses' => 'ItemController@incomplete','as' => 'items.incomplete',]);
        $router->get('/{checklistId}/items', ['uses' => 'ItemController@index','as' => 'items.index',]);
        $router->post('/{checklistId}/items', ['uses' => 'ItemController@store','as' => 'items.store',]);
        $router->get('/{checklistId}/items/{itemId}', ['uses' => 'ItemController@show','as' => 'items.show',]);
        $router->get('/{checklistId}/items/summaries', ['uses' => 'ItemController@summaries','as' => 'items.summaries',]);
        $router->post('/{checklistId}/items/_bulk', ['uses' => 'ItemController@updateBulk','as' => 'items.updateBulk',]);
        $router->patch('/{checklistId}/items/{itemId}', ['uses' => 'ItemController@update','as' => 'items.update',]);
        $router->delete('/{checklistId}/items/{itemId}', ['uses' => 'ItemController@destroy','as' => 'items.destroy',]);

        // Checklists
        $router->get('/', ['uses' => 'ChecklistController@index','as' => 'checklists.index',]);
        $router->post('/', ['uses' => 'ChecklistController@store','as' => 'checklists.store',]);
        $router->get('/{checklistId}', ['uses' => 'ChecklistController@show','as' => 'checklists.show',]);
        $router->patch('/{checklistId}', ['uses' => 'ChecklistController@update','as' => 'checklists.update',]);
        $router->delete('/{checklistId}', ['uses' => 'ChecklistController@destroy','as' => 'checklists.destroy',]);

    });


});
