<?php

use App\Http\Controllers\API\MovieController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\GenreController;
use App\Http\Controllers\Api\ActorController;
use App\Http\Controllers\Api\ActressController;
use App\Http\Controllers\Api\QualityController;
use App\Http\Controllers\Api\SouthActorController;
use App\Http\Controllers\Api\TagController;
use App\Http\Controllers\API\FileController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('file')->group(function () {
    Route::get('get', 'App\Http\Controllers\API\FileController@getFile');
    Route::post('save', 'App\Http\Controllers\API\FileController@saveFile');
    Route::put('update/{id}', 'App\Http\Controllers\API\FileController@updateFile');
    Route::delete('delete/{id}', 'App\Http\Controllers\API\FileController@deleteFile');
});


// Movie Routes
    Route::get('movies', 'App\Http\Controllers\API\MovieController@index');
    Route::get('imdb-movies', 'App\Http\Controllers\API\MovieController@imdb');
    Route::get('all-movies', 'App\Http\Controllers\API\MovieController@all_movies');
    Route::post('add-movie', 'App\Http\Controllers\API\MovieController@store');
    Route::post('add-movie-csv', 'App\Http\Controllers\API\MovieController@addMovieCSV');
    Route::get('movie/{id}', 'App\Http\Controllers\API\MovieController@show');
    Route::get('status-update/{id}', 'App\Http\Controllers\API\MovieController@statusUpdate');
    Route::post('update-movie/{id}', 'App\Http\Controllers\API\MovieController@update');
    Route::post('update-imdb/{id}', 'App\Http\Controllers\API\MovieController@updateImdb');
    Route::post('bulkUpdate', 'App\Http\Controllers\API\MovieController@bulkUpdate');
    Route::delete('movie/{id}', 'App\Http\Controllers\API\MovieController@destroy');
    Route::get('mostViewedThisWeek', 'App\Http\Controllers\API\MovieController@mostViewedThisWeek');
    Route::get('mostViewedLast24Hours', 'App\Http\Controllers\API\MovieController@mostViewedLast24Hours');
    Route::get('allTimeHighViews', 'App\Http\Controllers\API\MovieController@allTimeHighViews');
    Route::get('latestMovies', 'App\Http\Controllers\API\MovieController@latestMovies');
    Route::get('search', 'App\Http\Controllers\API\MovieController@search');
    Route::get('latestCartoonMovies', 'App\Http\Controllers\API\MovieController@latestCartoonMovies');
    Route::get('latestSongMovies', 'App\Http\Controllers\API\MovieController@latestSongMovies');
    Route::get('latestDramaMovies', 'App\Http\Controllers\API\MovieController@latestDramaMovies');
    Route::get('year/{id}', 'App\Http\Controllers\API\MovieController@getMoviesByYear');
    Route::post('/save-thumbnail/{videoId}/{movieId}', 'App\Http\Controllers\API\MovieController@saveThumbnail');
    Route::post('/saveThumbnailFromUrl', 'App\Http\Controllers\API\MovieController@saveThumbnailFromUrl');
    Route::get('suggestions/{title}', 'App\Http\Controllers\API\MovieController@suggestions');
    Route::get('update-thumbnails', 'App\Http\Controllers\API\MovieController@updateThumbnails');
    Route::get('getFeatured', 'App\Http\Controllers\API\MovieController@getFeatured');
    
    //users Routes
    Route::get('/users', 'App\Http\Controllers\API\UserController@index');
    Route::post('/users', 'App\Http\Controllers\API\UserController@store');
    Route::get('/users/{id}', 'App\Http\Controllers\API\UserController@show');
    Route::put('/users/{id}', 'App\Http\Controllers\API\UserController@update');
    Route::delete('/users/{id}', 'App\Http\Controllers\API\UserController@destroy');
    Route::post('/login', 'App\Http\Controllers\API\UserController@login');

    
    // Categories Routes 
    Route::get('categories', 'App\Http\Controllers\API\CategoryController@index');
    Route::post('categories', 'App\Http\Controllers\API\CategoryController@store');
    Route::put('categories/{id}', 'App\Http\Controllers\API\CategoryController@update');
    Route::delete('categories/{id}', 'App\Http\Controllers\API\CategoryController@destroy');
    Route::get('categories/{id}', 'App\Http\Controllers\API\CategoryController@getMoviesByCategory');
    
    // Genre Routes 
    Route::get('genres', 'App\Http\Controllers\API\GenreController@index');
    Route::post('genres', 'App\Http\Controllers\API\GenreController@store');
    Route::put('genres/{id}', 'App\Http\Controllers\API\GenreController@update');
    Route::delete('genres/{id}', 'App\Http\Controllers\API\GenreController@destroy');
    Route::get('genres/{id}', 'App\Http\Controllers\API\GenreController@getMoviesByCategory');
    
    // Actors Routes 
    Route::get('actors', 'App\Http\Controllers\API\ActorController@index');
    Route::post('actors', 'App\Http\Controllers\API\ActorController@store');
    Route::put('actors/{id}', 'App\Http\Controllers\API\ActorController@update');
    Route::delete('actors/{id}', 'App\Http\Controllers\API\ActorController@destroy');
    Route::get('/actors/{id}', 'App\Http\Controllers\API\ActorController@getMoviesByActors');
    
    // Actress Routes 
    Route::get('actress', 'App\Http\Controllers\API\ActressController@index');
    Route::post('actress', 'App\Http\Controllers\API\ActressController@store');
    Route::put('actress/{id}', 'App\Http\Controllers\API\ActressController@update');
    Route::delete('actress/{id}', 'App\Http\Controllers\API\ActressController@destroy');
    Route::get('/actress/{id}', 'App\Http\Controllers\API\ActressController@getMoviesByActress');
    
    
    // Quality Routes 
    Route::get('/quality', 'App\Http\Controllers\API\QualityController@index');
    Route::post('/quality', 'App\Http\Controllers\API\QualityController@store');
    Route::put('/quality/{id}', 'App\Http\Controllers\API\QualityController@update');
    Route::delete('/quality/{id}', 'App\Http\Controllers\API\QualityController@destroy');
    
    
    // Quality Routes 
    Route::get('southactor', 'App\Http\Controllers\API\SouthActorController@index');
    Route::post('southactor', 'App\Http\Controllers\API\SouthActorController@store');
    Route::put('southactor/{id}', 'App\Http\Controllers\API\SouthActorController@update');
    Route::delete('southactor/{id}', 'App\Http\Controllers\API\SouthActorController@destroy');
    Route::get('/southactor/{id}', 'App\Http\Controllers\API\SouthActorController@getMoviesBySouthActor');
    
    
    // Quality Routes 
    
    Route::get('/tag', 'App\Http\Controllers\API\TagController@index');
    Route::post('/tag', 'App\Http\Controllers\API\TagController@store');
    Route::put('/tag/{id}', 'App\Http\Controllers\API\TagController@update');
    Route::delete('/tag/{id}', 'App\Http\Controllers\API\TagController@destroy');
    
    // Season Routes 
    Route::get('/seasons', 'App\Http\Controllers\API\SeasonController@index');
    Route::post('/seasons', 'App\Http\Controllers\API\SeasonController@store');
    Route::put('/seasons/{id}', 'App\Http\Controllers\API\SeasonController@update');
    Route::delete('/seasons/{id}', 'App\Http\Controllers\API\SeasonController@destroy');
    
    
    // Complaint routes
    Route::get('/complaints', 'App\Http\Controllers\API\ComplaintController@index');
    Route::post('/complaints', 'App\Http\Controllers\API\ComplaintController@store');
    Route::get('/complaints/{id}', 'App\Http\Controllers\API\ComplaintController@show');
    Route::put('/complaints/{id}', 'App\Http\Controllers\API\ComplaintController@update');
    Route::delete('/complaints/{id}', 'App\Http\Controllers\API\ComplaintController@destroy');
    
    // Movie Request routes
    Route::get('/movie-requests', 'App\Http\Controllers\API\MovieRequestController@index');
    Route::post('/movie-requests', 'App\Http\Controllers\API\MovieRequestController@store');
    Route::get('/movie-requests/{id}', 'App\Http\Controllers\API\MovieRequestController@show');
    Route::put('/movie-requests/{id}', 'App\Http\Controllers\API\MovieRequestController@update');
    Route::delete('/movie-requests/{id}', 'App\Http\Controllers\API\MovieRequestController@destroy');
  


