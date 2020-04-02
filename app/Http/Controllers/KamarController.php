<?php

namespace App\Http\Controllers;

use App\Models\Kamar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class KamarController extends Controller
{
  public function index(Request $request)
  {
    $kamar = Kamar::OrderBy("id", "DESC")->paginate(2)->toArray();
    $response = [
      "total_count" => $kamar["total"],
      "limit" => $kamar["per_page"],
      "pagination" => [
      "next_page" => $kamar["next_page_url"],
      "current_page" => $kamar["current_page"]
    ],
      "data" => $kamar["data"],
    ];
      return response()->json($response,200);
  }

 	/**
   * Store a newly created resource in storage.
   *
   * @param \Illuminate\Http\Request $request
   * @return \Illuminate\Http\Respons
   */

  public function store(Request $request)
  {
    if (Gate::denies('create-kamar')) {
      return response()->json([
        'success' => false,
        'status' => 403,
        'message' => 'You Are unauthorized'
      ], 403);
    }

    $input = $request->all();
    $validationRules = [
      'jenis' => 'required|min:5',
      'info_kamar' => 'required|min:5',
      'harga' => 'required|numeric'
    ];

    $validator = \Validator::make($input, $validationRules);

    if ($validator->fails()) {
      return response()->json($validator->errors(), 400);
    }

    $kamar = kamar::create($input);

    return response()->json($kamar, 200);
  }

	/**
	 * Display the specified resource
	 *
	 * @param int $id
	 * @return \Illuminate\Http\Response
	 */

  public function show($id)
	{
	  $kamar = Kamar::find($id);

    if(!$kamar) {
	    abort(404);
	  }
	    return response()->json($kamar,200);
	}

	/**
	 * Update the specified resource in storage
	 *
	 * @param \Illuminate\Http\Request $request
	 * @param int $id
	 * @return  \Illuminate\Http\Response
	 */

	public function update(Request $request, $id)
  {
    if (Gate::denies('update-kamar')) {
      return response()->json([
        'success' => false,
        'status' => 403,
        'message' => 'You Are unauthorized'
      ], 403);
    }

    $input = $request->all();
	  $kamar = Kamar::find($id);

	  if(!$kamar) {
	    abort(404);
	  }

    $validationRules = [
      'jenis' => 'required|min:5',
      'info_kamar' => 'required|min:5',
      'harga' => 'required|numeric'
    ];

    $validator = \Validator::make($input, $validationRules);

    if($validator->fails()) {
      return response()->json($validator->errors(), 400);
    }

    $kamar->fill($input);
	  $kamar->save();

	  return response()->json($kamar,200);
	}

	/**
	* Remove the specified resource from storage
	*
	* @param int $id
	* @return  \Illuminate\Http\Response
	*/

	public function delete($id)
	{
	  $kamar= Kamar::find($id);

	  if(!$kamar) {
	    abort(404);
	  }

    if (Gate::denies('delete-kamar', $kamar)) {
      return response()->json([
        'success' => false,
        'status' => 403,
        'message' => 'You Are unauthorized'
      ], 403);
    }

    $kamar->delete();
	  $message =['message' => 'deleted succesfully', 'kamar_id' => $id];

	  return response()->json($message,200);
	}
}
