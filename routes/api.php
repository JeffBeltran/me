<?php

Route::get('jobs', 'JobController@index');
Route::get('jobs/{job}', 'JobController@show');
Route::get('companies', 'CompanyController@index');
Route::get('companies/{company}', 'CompanyController@show');
Route::get('achievements', 'AchievementController@index');
Route::get('achievements/{achievement}', 'AchievementController@show');
Route::get('buzzwords', 'BuzzwordController@index');
Route::get('buzzwords/{buzzword}', 'BuzzwordController@show');
Route::get('schools', 'SchoolController@index');
Route::get('schools/{school}', 'SchoolController@show');
