<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Article;

class ArticlesController extends Controller {
	
	protected $article;

	public function __construct(Article $article)
	{
	    parent::__construct();
	    $this->article = $article;
	    $this->beforeFilter('auth', ['only' =>
	    	['listing', 'create', 'store', 'edit', 'update', 'destroy', 'restore', 'star', 'unstar']
	    ]);
	    $this->beforeFilter('admin', ['only' =>['highlight']]);
	}
	/**
	 * Display all of articles on grid
	 *
	 * @return Response
	 */
	public function index()
	{
		$articles = $this->article->where(['public'=>1])->orderBy('created_at', 'desc')->paginate(10);
		$tags = Tag::paginate(50);
		// return $articles;

		$title = 'All Articles';
		// return compact('title', 'articles', 'tags');
		return View::make('articles.home', compact('title', 'articles', 'tags'));
	}

	/**
	 * listing of articles for admin
	 *
	 * @return Response
	 */
	public function listing()
	{
		$articles = $this->article->latest()->withTrashed()->paginate(20);

		$title = 'All Articles';
		return View::make('articles.list', compact('title'), compact('articles'));
	}

	/**
	 * Show the form for creating a new article
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make('articles.create');
	}

	/**
	 * Store a newly created article in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$input = Input::all();
		$input['public'] = !isset($input['public']) ? 1 : $input['public']; //autoshow posts

		$validator = Validator::make($input, Article::$rules);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}

		$input['user_id'] = $this->auth->id;

		if(isset($input['tags'])){ // save tags
			$tags = [];
			foreach ($input['tags'] as $key => $name) {
				if($tag = Tag::where('name', $name)->first()){
					$tags[] = $tag;
				}
				else{
					$tags[] = Tag::create(['name'=>$name, 'creator'=>$this->auth->id]);
				}
			}
			unset($input['tags']);
			$article = Article::create($input); // create article
			$article->tags()->saveMany($tags);
		}
		else{
			$article = $this->article->create($input);
		}

		$article['image'] = $this->auth->image;

		/*
		* send gcm alerts:
		*/
		function gcm(){
			$auth = $this->auth;

			// new algorithm	
			$follower_ids = $auth->followers()->lists('user_id');
			// return $follower_ids;

			$followers = User::whereIn('id', $follower_ids)->get();
			// return $followers;
			
			$reg_ids = [];
			
			foreach ($followers as $usr) {
				$reg_ids[] = $usr->gcm;
			}

			// $reg_ids = User::lists('gcm'); // pushing to all devices
			$reg_ids = array_values(array_unique($reg_ids));
			
			if(count($reg_ids)){
				$res = $article->gcm($reg_ids); //gcm push!
			}
		}


		return Redirect::route('blog.show', $article->slug);

	}

	/**
	 * Display the specified article.
	 *
	 * @param  string $slug
	 * @return Response
	 */
	public function show($slug)
	{
		$article = $this->article->whereSlug($slug)->firstOrFail();

		return $this->display($article);
	}

	/**
	 * Display the specified article.
	 *
	 * @param  model $article
	 * @return Response
	 */
	public function display($article)
	{
		if(in_array('api', Request::segments())){
			return $article;
		}
		return View::make('articles.show', compact('article'));
	}

	/**
	 * Show the form for editing the specified article.
	 *
	 * @param  string $slug
	 * @return Response
	 */
	public function edit($slug)
	{
		$article = $this->article->findBySlug($slug);

		return View::make('articles.edit', compact('article'));

	}

	/**
	 * Update the specified article in storage.
	 *
	 * @param  string $slug
	 * @return Response
	 */
	public function update($slug)
	{
		$input = Input::all();
		// return $input;

		$validator = Validator::make($input, Article::$rules);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}
		
		$article = $this->article->findBySlug($slug);

		$article->tags()->detach();

		if(isset($input['tags'])){
			$tags = [];
			foreach ($input['tags'] as $key => $name) {
				if($tag = Tag::where('name', $name)->first()){
					$tags[] = $tag;
				}
				else{
					$tags[] = Tag::create(['name'=>$name, 'creator'=>$this->auth->id]);
				}
			}
			unset($input['tags']);
			$article->update($input);
			$article->tags()->saveMany($tags);
		}
		else{
			$article->update($input);
		}

		return View::make('articles.show', compact('article'));
	}

	/**
	 * Trash the specified article
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$article = $this->article->withTrashed()->findOrFail($id);
		$article->delete();
		return Redirect::back()->with('success', 'Deleted article '.$id.' successfully');
		// return Redirect::route('admin.articles')->with('success', 'Deleted article '.$id.' successfully');
	}

	/**
	 * Return the specified article to view
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function restore($id)
	{
		$article = $this->article->withTrashed()->findOrFail($id);
		$article->restore();
		return Redirect::back()->with('success', 'Restored article '.$id.' successfully');
		// return Redirect::route('admin.articles')->with('success', 'Restored article '.$id.' successfully');
	}
	
	public function highlight($id){
		if(!isAdmin()):
			return Redirect::back()->with(['error' => 'Admin only feature!']);
			// return ['error' => 'Admin only feature'];
		endif;

		$article = $this->article->findOrFail($id);
		if($article->highlighted):
			$article->highlighted = false;
			$article->save();
			$msg = "removed from";
		else:
			$article->highlighted = true;
			$article->save();
			$msg = "added to";
		endif;
		return Redirect::back()->with(['success' => 'Article has been '.$msg.' this week\'s highlights!']);
		// return ['success' => $article->highlighted];
	}
	public function highlights(){

		$articles = $this->article->where(['highlighted'=>1, 'public'=>1])->orderBy('created_at', 'desc')->paginate(20);
		if(in_array('api', Request::segments())) { //its from api
			return $articles;
		}
		$title = 'This Week\'s Highlights';
		$mapped = 1;

		return View::make('articles.home', compact(['title', 'articles', 'mapped']));
	}
	public function top($id){
		if(!isAdmin()):
			return Redirect::back()->with(['error' => 'Admin only feature!']);
		endif;

		$articles = $this->article->withTrashed()->where(['top_story'=>1])->get();
		foreach ($articles as $article) {
			$article->top_story = false;
			$article->save();
		}
		$article = $this->article->findOrFail($id);
		if($article->top_story):
			$article->top_story = false;
			$article->save();
			$msg = "is not";
		else:
			$article->top_story = true;
			$article->save();
			$msg = "is now";
		endif;
		return Redirect::back()->with(['success' => 'Article '.$msg.' the current Top Story!']);
		// return ['success' => $article->top_story];
	}
	public function topStory(){

		$article = $this->article->where(['top_story'=>1, 'public'=>1])->first();
		if(in_array('api', Request::segments())) { //its from api
			return $article;
		}
		return View::make('articles.show', compact('article'));
	}
	public function star($slug){

		$article = $this->article->whereSlug($slug)->firstOrFail();

		Star::create(['article_id'=> $article->id, 'user_id'=> $this->auth->id]);
		
		return success('You have starred this article!', route('blog.show', $slug), $article);
	}
	public function unstar($slug){

		$article = $this->article->whereSlug($slug)->firstOrFail();

		$article->stars()->where(['user_id' =>$this->auth->id])->delete();
		
		return success('You have unstarred this article!', route('blog.show', $slug), $article);
	}

	public function push(){
		// use Sly\NotificationPusher\PushManager,
		//     Sly\NotificationPusher\Adapter\Gcm as GcmAdapter,
		//     Sly\NotificationPusher\Collection\DeviceCollection,
		//     Sly\NotificationPusher\Model\Device,
		//     Sly\NotificationPusher\Model\Message,
		//     Sly\NotificationPusher\Model\Push
		// ;
		// First, instantiate the manager and declare an adapter.
		$pushManager    = new PushManager();
		$exampleAdapter = new ApnsAdapter();

		// Set the device(s) to push the notification to.
		$devices = new DeviceCollection(array(
		    new Device('Token1'),
		    new Device('Token2'),
		    new Device('Token3'),
		    // ...
		));

		// Then, create the push skel.
		$message = new Message('This is an example.');

		// Finally, create and add the push to the manager, and push it!
		$push = new Push($exampleAdapter, $devices, $message);
		$pushManager->add($push);
		return $pushManager->push();
	}

	public function emptySearch(){
		return Redirect::to('blog'); // default press
	}
	public function search(){

		$query = Input::get('query');
		
		if($query == 'Search Blog...') return Redirect::to('blog'); // default press

		$query = str_replace('%20', '_', $query); // replace space with underscores
		$query = str_replace(' ', '_', rawurldecode($query)); // replace space with underscores

		return Redirect::route('blog.search.get', compact('query'));
		// return $this->getSearch($query);
	}

	public function getSearch($query){

		$query = str_replace('_', ' ', $query);

		$articles = $this->article
			->where('title', 'like', '%'.$query.'%')
			->orWhere('body', 'like', '%'.$query.'%')
			->orderBy('created_at', 'desc')
			->paginate(10);

		$tags = Tag::paginate(50);
		// return $articles;

		$title = 'Searched: '.$query;
		return View::make('articles.index', compact('title', 'articles', 'tags', 'query'));
	}
}
