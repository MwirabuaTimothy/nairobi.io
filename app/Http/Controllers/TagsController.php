<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Tag;

class TagsController extends Controller {

	/**
	 * Display a listing of tags
	 *
	 * @return Response
	 */
	public function index()
	{
		$tags = Tag::all();

		return View::make('tags.index', compact('tags'));
	}

	/**
	 * Show the form for creating a new tag
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make('tags.create');
	}

	/**
	 * Store a newly created tag in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$validator = Validator::make($data = Input::all(), Tag::$rules);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}

		Tag::create($data);

		return Redirect::route('tags.index');
	}

	/**
	 * Display the specified tag.
	 *
	 * @param  string  $slug
	 * @return Response
	 */
	public function show($slug)
	{
		// $tag = Tag::findBySlug($slug);
		$tag = Tag::whereSlug($slug)->firstOrFail();
		$articles = $tag->articles()->paginate(20);
		
		$title = "Tagged: ".$tag->name;
		$tags = Tag::paginate(50);

		return View::make('articles.index', compact('title', 'tag', 'tags', 'articles'));
	}

	/**
	 * Show the form for editing the specified tag.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$tag = Tag::find($id);

		return View::make('tags.edit', compact('tag'));
	}

	/**
	 * Update the specified tag in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$tag = Tag::findOrFail($id);

		$validator = Validator::make($data = Input::all(), Tag::$rules);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}

		$tag->update($data);

		return Redirect::route('tags.index');
	}

	/**
	 * Remove the specified tag from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		Tag::destroy($id);

		return Redirect::route('tags.index');
	}

}
