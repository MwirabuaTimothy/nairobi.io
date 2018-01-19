<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Partner;

class PartnersController extends Controller {

	/**
	 * Partner Repository
	 *
	 * @var Partner
	 */
	protected $partner;

	public function __construct(Partner $partner)
	{
		$this->partner = $partner;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$partners = $this->partner->paginate(20);
		$user = Sentry::getUser();

		return View::make('partners.index', compact('partners', 'user'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{	
		if(isAdmin()):
			$user = Sentry::getUser();
			return View::make('partners.create', compact('user'));
		else:
			return Redirect::back()->withErrors('msg', 'You have to be admin to perform this operation');
		endif;
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$input = Input::all();
		$validation = Validator::make($input, Partner::$rules);

		if ($validation->passes())
		{
			if (Input::hasFile('logo')) {
				$file            = Input::file('logo');
				
				$filename        = str_random(3) . '_' . $file->getClientOriginalName();
				$destinationPath = public_path().'/uploads/partners/logo/';
				$uploadSuccess   = $file->move($destinationPath, $filename);
				$input['logo']	 = $filename;
			}
			$input['logo'] = $input['logo'] ? $input['logo'] : 'no_logo.png'; //no erroneous nullity
			// $destinationPath = public_path().'/uploads/partners/logo/';
			// $product = $this->product->create($input);
			$partner = $this->partner->create($input);
			$partner -> save();

			return Redirect::route('partners.index');
		}

		// if ($validation->passes())
		// {
		// 	$this->partner->create($input);

		// 	return Redirect::route('partners.index');
		// }

		return Redirect::route('partners.create')
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
		$user = Sentry::getUser();
		$partner = $this->partner->findOrFail($id);

		return View::make('partners.show', compact('partner', 'user'));
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
		$partner = $this->partner->find($id);

		if (is_null($partner))
		{
			return Redirect::route('partners.index');
		}

		return View::make('partners.edit', compact('partner', 'user'));
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
		$validation = Validator::make($input, Partner::$rules);

		if ($validation->passes())
		{
			$partner = $this->partner->find($id);

			if (Input::hasFile('logo')) {
				$file            = Input::file('logo');
				$destinationPath = public_path().'/uploads/partners/logo/';
				$filename        = str_random(3) . '_' . $file->getClientOriginalName();
				$uploadSuccess   = $file->move($destinationPath, $filename);
				$input['logo']	 = $filename;
			}else{
				unset($input['logo']);
			}
			// $input['logo'] = $input['logo'] ? $input['logo'] : 'no_logo.png'; //no erroneous nullity
			// $product = $this->product->create($input);
			// $partner = $this->partner->create($input);
			$partner = $this->partner->find($id);
			$partner->update($input);

			return Redirect::route('partners.show', $id);
			// $partner -> save();

			// return Redirect::route('partners.index');
		}

		return Redirect::route('partners.edit', $id)
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
		$this->partner->find($id)->delete();

		return Redirect::route('partners.index');
	}

}
