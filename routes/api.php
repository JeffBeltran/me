<?php

Route::get('jobs', 'JobController@index');
Route::get('jobs/{job}', 'JobController@show');
Route::get('companies', 'CompanyController@index');
Route::get('companies/{company}', 'CompanyController@show');
Route::get('achievements', 'AchievementController@index');
Route::get('achievements/{achievement}', 'AchievementController@show');
