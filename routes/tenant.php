<?php
Route::group(['domain' => '{username}.'.config('app.domain')], function () {
Route::group(['prefix' => '/dashboard'], function () {
      Route::get('/', 'DashboardController@index')->name('dashboard');
      Route::get('/employees', 'User\ShowEmployee')->name('tenant.users.index');
      // Route::get('/projects/create', 'Project\CreateProject')->name('employee.projects.create');
      Route::get('/projects/{projectID}', 'Project\ShowProject')->name('tenant.projects.view');
      Route::get('/projects/{projectID}/progress', 'Project\CampaignsProgress')->name('tenant.projects.progress');
      Route::get('/tasks/{task}', 'Task\ShowTask')->name('tenant.tasks.view');
  });
});