<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class PembayaranController extends Controller
{
  public function index()
  {
    if (Gate::denies('read-pembayaran')) {
      return response()->json([
        'success' => false,
        'status' => 403,
        'message' => 'You Are unauthorized'
      ], 403);
    }

    if (Auth::user()->role === 'admin') {
      $pembayaran = Pembayaran::OrderBy("id", "DESC")->paginate(2)->toArray();
    } else {

    $pembayaran = Pembayaran::Where(['id_pelanggan' => Auth::user()->id])->OrderBy("id", "DESC")->paginate(2)->toArray();
    }

    $response = [
      "total_count" => $pembayaran["total"],
      "limit" => $pembayaran["per_page"],
      "pagination" => [
      "next_page" => $pembayaran["next_page_url"],
      "current_page" => $pembayaran["current_page"]
    ],

      "data" => $pembayaran["data"],
    ];
      return response()->json($response,200);
    }

  /**
	 * Store a newly created resource in storage.   	       *
	 * @param \Illuminate\Http\Request $request
	 * @return \Illuminate\Http\Respons
	 */

  public function store(Request $request)
  {
    if (Gate::denies('create-pembayaran')) {
      return response()->json([
        'success' => false,
        'status' => 403,
        'message' => 'You Are unauthorized'
      ], 403);
    }

    $input = $request->all();
    $validationRules = [
      'id_pelanggan' => 'required|min:1',
      'id_kamar' => 'required|min:1',
      'id_pemesanan' => 'required|min:1',
      'tanggal_bayar' => 'required|date_format:Y-m-d H:i:s',
      'lama_inap' => 'required|min:1',
      'total' => 'required|numeric'
    ];

    $validator = \Validator::make($input, $validationRules);

    if ($validator->fails()) {
      return response()->json($validator->errors(), 400);
    }

    $pembayaran = pembayaran::create($input);

    return response()->json($pembayaran, 200);
  }

	/**
	  * Display the specified resource
	  *
	  * @param int $id
	  * @return \Illuminate\Http\Response
	  */

  public function show($id)
	{
	  $pembayaran = Pembayaran::find($id);

    if(!$pembayaran) {
	     abort(404);
	  }

    if (Gate::denies('show-pembayaran', $pembayaran)) {
      return response()->json([
        'success' => false,
        'status' => 403,
        'message' => 'You Are unauthorized'
      ], 403);
    }
	    return response()->json($pembayaran,200);
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
    if (Gate::denies('update-pembayaran')) {
      return response()->json([
        'success' => false,
        'status' => 403,
        'message' => 'You Are unauthorized'
      ], 403);
    }

    $input = $request->all();

	  $pembayaran = Pembayaran::find($id);

    if(!$pembayaran) {
	    abort(404);
	  }

    $validationRules = [
      'id_pelanggan' => 'required|numeric',
      'id_kamar' => 'required|numeric',
      'id_pemesanan' => 'required|numeric',
      'tanggal_bayar' => 'required|date_format:Y-m-d H:i:s',
      'lama_inap' => 'required|numeric',
      'total' => 'required|numeric'
    ];

    $validator = \Validator::make($input, $validationRules);

    if($validator->fails()) {
      return response()->json($validator->errors(), 400);
    }

    $pembayaran->fill($input);
	  $pembayaran->save();

	  return response()->json($pembayaran,200);
	}

	/**
	* Remove the specified resource from storage
	*
	* @param int $id
	* @return  \Illuminate\Http\Response
	*/

	public function delete($id)
	{
	  $pembayaran= Pembayaran::find($id);

	  if(!$pembayaran) {
	    abort(404);
	  }

    if (Gate::denies('delete-pembayaran', $pembayaran)) {
      return response()->json([
        'success' => false,
        'status' => 403,
        'message' => 'You Are unauthorized'
      ], 403);
    }

    $pembayaran->delete();
	  $message =['message' => 'deleted succesfully', 'pembayaran_id' => $id];

	  return response()->json($message,200);
	}
}
