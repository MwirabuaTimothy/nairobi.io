<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Testimonial;

class TestimonialsController extends Controller {

	/**
	 * Testimonial Repository
	 *
	 * @var Testimonial
	 */
	protected $testimonial;

	public function __construct(Testimonial $testimonial)
	{
		$this->testimonial = $testimonial;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$user = Sentry::getUser();
		$testimonials = $this->testimonial->paginate(20);

		return View::make('testimonials.index', compact('testimonials', 'user'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$user = Sentry::getUser();
		return View::make('testimonials.create', compact('user'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$input = Input::all();
		$validation = Validator::make($input, Testimonial::$rules);
		$user = Sentry::getUser();

		if ($validation->passes())
		{
			if (Input::hasFile('image')) {
				$file            = Input::file('image');
				$destinationPath = public_path().'/uploads/testimonials/image/';
				$filename        = str_random(6) . '_' . $file->getClientOriginalName();
				$uploadSuccess   = $file->move($destinationPath, $filename);
				$input['image'] =  $filename;
			}
			$input['image'] = $input['image'] ? $input['image'] : 'no_image.png'; //removing erroneous nullity

			$this->testimonial->create($input);

			return Redirect::route('testimonials.index', compact('user'));
		}

		return Redirect::route('testimonials.create', compact('user'))
			->withInput()
			->withErrors($validation)
			->with('message', 'There were validation errors.');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$testimonial = $this->testimonial->findOrFail($id);

		return View::make('testimonials.show', compact('testimonial'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$user = Sentry::getUser();
		$testimonial = $this->testimonial->find($id);

		if (is_null($testimonial))
		{
			return Redirect::route('testimonials.index');
		}

		return View::make('testimonials.edit', compact('testimonial', 'user'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$input = array_except(Input::all(), '_method');
		$rules = array('name' => 'required' );
		$validation = Validator::make($input, $rules);

		if ($validation->passes())
		{
			if (Input::hasFile('image')) {
				$file            = Input::file('image');
				$destinationPath = public_path().'/uploads/testimonials/image/';
				$filename        = str_random(6) . '_' . $file->getClientOriginalName();
				$uploadSuccess   = $file->move($destinationPath, $filename);
				$input['image'] =  $filename;
			}
			else{
				unset($input['image']);
			}

			$testimonial = $this->testimonial->find($id);
			$testimonial->update($input);

			return Redirect::route('testimonials.show', $id);
		}

		return Redirect::route('testimonials.edit', $id)
			->withInput()
			->withErrors($validation)
			->with('message', 'There were validation errors.');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$this->testimonial->find($id)->delete();

		return Redirect::route('testimonials.index');
	}

}
