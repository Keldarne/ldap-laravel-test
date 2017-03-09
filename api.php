<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

header('Access-Control-Allow-Origin:  *');
header('Access-Control-Allow-Methods:  POST, GET, OPTIONS, PUT, DELETE');
header('Access-Control-Allow-Headers:  Content-Type, Authorization');

Route::group(['prefix' => 'v1'], function () {

    /* Login */
    Route::any('login', 'API\LoginController@logIn')
        ->name('logIn');

    Route::get('logout', 'API\LoginController@logOut')
        ->name('logOut');

    Route::get('isLogged', 'API\LoginController@isLogged')
        ->name('isLogged');

    /* Teams and Users */
    Route::group(['prefix' => 'teams'], function () {
        Route::get('', 'API\TeamController@getTeams')
            ->name('getTeams');

        Route::get('{teamID}/users', 'API\UserController@getUsers')
            ->name('getUsers')
            ->where('teamID', '[0-9]+');
    });

    Route::get('planItem/{username}/{startDate}/{endDate}', 'API\IssueController@getPlanItems')
        ->name('getPlanItems')
        ->where('startDate', '((?:(?:[1]{1}\\d{1}\\d{1}\\d{1})|(?:[2]{1}\\d{3}))[-:\\/.](?:[0]?[1-9]|[1][012])[-:\\/.](?:(?:[0-2]?\\d{1})|(?:[3][01]{1})))(?![\\d])')
        ->where('endDate', '((?:(?:[1]{1}\\d{1}\\d{1}\\d{1})|(?:[2]{1}\\d{3}))[-:\\/.](?:[0]?[1-9]|[1][012])[-:\\/.](?:(?:[0-2]?\\d{1})|(?:[3][01]{1})))(?![\\d])');


    Route::get('sprintUserMaxTime/{username}/{startDate}/{endDate}', 'API\IssueController@getSprintUserMaxTime')
        ->name('sprintUserMaxTime')
        ->where('startDate', '((?:(?:[1]{1}\\d{1}\\d{1}\\d{1})|(?:[2]{1}\\d{3}))[-:\\/.](?:[0]?[1-9]|[1][012])[-:\\/.](?:(?:[0-2]?\\d{1})|(?:[3][01]{1})))(?![\\d])')
        ->where('endDate', '((?:(?:[1]{1}\\d{1}\\d{1}\\d{1})|(?:[2]{1}\\d{3}))[-:\\/.](?:[0]?[1-9]|[1][012])[-:\\/.](?:(?:[0-2]?\\d{1})|(?:[3][01]{1})))(?![\\d])');

    /* Sprints and Issues */
    Route::group(['prefix' => 'sprints'], function () {

        Route::get('', 'API\SprintController@getSprints')
            ->name('getSprints');

        Route::get('time/{teamID}', 'API\SprintController@getSprintTime')
            ->name('getSprintTime')
            ->where('teamID', '[0-9]+');;

        Route::group(['prefix' => '{sprintID}'], function () {


            Route::get('issues', 'API\IssueController@getIssues')
                ->name('getIssues');


        });
    });
    Route::group(['prefix' => 'project'], function () {
        Route::get('', 'API\ProjectController@getProjects')
            ->name('getProjects');
    });

    Route::group(['prefix' => 'import'], function () {
        Route::get('projects', 'API\ProjectController@importProjects')
            ->name('importAllProjects');
    });

    //JIRA EVENTS webhooks
    Route::group(['prefix' => 'webhooks'], function () {
        Route::get('', 'API\ProjectController@importProject')
            ->name('importProject');
    });
});
