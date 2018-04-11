<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () { return view('welcome'); });

//tests...
Route::resource('tests', 'TestsController', ['except' => ['create', 'store', 'update', 'destroy']] );

// Static Routes
Route::get('/', array('as' => 'home', 'uses' => 'HomeController@home'));
Route::get('/software', array('as' => 'software', 'uses' => 'HomeController@software'));
Route::get('/media', array('as' => 'media', 'uses' => 'HomeController@media'));
Route::get('/events', array('as' => 'events', 'uses' => 'HomeController@events'));
Route::get('/hub', array('as' => 'hub', 'uses' => 'HomeController@hub'));
Route::get('/academy', array('as' => 'academy', 'uses' => 'HomeController@academy'));
Route::get('/community', array('as' => 'community', 'uses' => 'HomeController@community'));
Route::get('/about', array('as' => 'about', 'uses' => 'HomeController@about'));
Route::get('/partnerships', array('as' => 'partnerships', 'uses' => 'HomeController@partnerships'));
Route::get('/contact', array('as' => 'contact', 'uses' => 'HomeController@home'));

// submit the "contact us" form
Route::post('contact', ['as' => 'contact', 'uses' => 'HomeController@postContact']);


// Auth::routes();

// Authentication Routes...
$this->get('login', 'Auth\LoginController@showLoginForm')->name('login');
$this->post('login', 'Auth\LoginController@login');
$this->post('logout', 'Auth\LoginController@logout')->name('logout');
$this->get('logout', 'Auth\LoginController@logout')->name('logout');

// Registration Routes...
$this->get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
$this->post('register', 'Auth\RegisterController@register');
$this->get('join', 'HomeController@join')->name('join');
$this->get('verify-email/{token}', 'Auth\RegisterController@verifyEmail'); 

// Password Reset Routes...
$this->get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
$this->post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
$this->get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
$this->post('password/reset', 'Auth\ResetPasswordController@reset');
$this->post('password/change', 'Auth\ChangePasswordController@change');

$this->get('/', 'HomeController@index')->name('home');



# Blog Management
Route::group(array('prefix' => 'blog'), function(){

	Route::get('/',  ['as'=>'blog', 'uses' => 'ArticlesController@index']);

	Route::get('api', function(){ return Article::paginate(10); });
	Route::get('create',  ['as'=>'blog.create', 'uses' => 'ArticlesController@create']);
	Route::get('search', ['as'=>'blog.search.empty', 'uses'=>'ArticlesController@emptySearch']);
	Route::post('search', ['as'=>'blog.search', 'uses'=>'ArticlesController@search']);
	Route::get('search/{query}', ['as'=>'blog.search.get', 'uses'=>'ArticlesController@getSearch']);
	Route::get('search/api', ['as'=>'blog.search.api', 'uses'=> function(){ return All::ajaxByLetters(); }]);

	Route::get('{id}/highlight', array('as' => 'blog.highlight', 'uses' => 'ArticlesController@highlight'));
	Route::get('{id}/top', array('as' => 'blog.top', 'uses' => 'ArticlesController@top'));
	Route::get('{id}/api', ['as'=>'article.show.api', 'uses'=>'ArticlesController@show']);


	Route::get('{slug}', array('as' => 'blog.show', 'uses' => 'ArticlesController@show'));
	Route::get('{slug}/edit', array('as' => 'blog.edit', 'uses' => 'ArticlesController@edit'));
	Route::get('{slug}/star', array('as' => 'blog.star', 'uses' => 'ArticlesController@star'));
	Route::get('{slug}/unstar', array('as' => 'blog.unstar', 'uses' => 'ArticlesController@unstar'));
	Route::post('{slug}', array('as' => 'blog.update', 'uses' => 'ArticlesController@update'));

});



Route::resource('blog', 'ArticlesController');


Route::get('highlights/api', ['as'=>'article.highlights.api', 'uses'=>'ArticlesController@highlights']);


# Tags Management

Route::get('tags/api', function(){
	// return Tag::usage();
	return Tag::paginate(30);
});
Route::get('tags/{id}/api', function($id){
	// return Tag::find($id);
	return Tag::find($id)->articles;
});
Route::get('tagged/{name}/api', function($name){
	return Tag::where('name', $name)->first()->articles;
});

Route::resource('tags', 'TagsController');
Route::get('tagged/{slug}', ['as'=>'tags.show', 'uses'=>'TagsController@show']); // overriding default url structure


