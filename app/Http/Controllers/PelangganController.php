<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class PelangganController extends Controller
{
  public function index(Request $request)
    {
      if (Gate::denies('read-pelanggan')) {
        return response()->json([
          'success' => false,
          'status' => 403,
          'message' => 'You Are unauthorized'
        ], 403);
      }

      if (Auth::user()->role === 'admin') {
        $pelanggan = Pelanggan::OrderBy("id", "DESC")->paginate(2)->toArray();
      } else {
        $pelanggan = Pelanggan::Where(['id_pelanggan' => Auth::user()->id])->OrderBy("id", "DESC")->paginate(2)->toArray();
      }

      $response = [
        "total_count" => $pelanggan["total"],
        "limit" => $pelanggan["per_page"],
        "pagination" => [
        "next_page" => $pelanggan["next_page_url"],
        "current_page" => $pelanggan["current_page"]
      ],
        "data" => $pelanggan["data"],
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
      if (Gate::denies('create-pelanggan')) {
        return response()->json([
          'success' => false,
          'status' => 403,
          'message' => 'You Are unauthorized'
        ], 403);
      }

      if (Auth::user()->role === 'admin') {
        $input = $request->all();
        $validationRules = [
          'nama' => 'required|min:5',
          'nik' => 'required|min:15',
          'no_hp' => 'required|min:11',
          'tanggal_lahir' => 'required|date',
          'jenis_kelamin' => 'required|in:laki-laki,perempuan',
          'alamat' => 'required|min:5',
          'id_pelanggan' => 'required|min:1'
        ];

        $validator = \Validator::make($input, $validationRules);

        if ($validator->fails()) {
          return response()->json($validator->errors(), 400);
        }
          $pelanggan = Pelanggan::create($input);
        } else {
          $input = $request->all();
          $validationRules = [
            'nama' => 'required|min:5',
            'nik' => 'required|min:16',
            'no_hp' => 'required|min:11',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:laki-laki,perempuan',
            'alamat' => 'required|min:5'
          ];

          $validator = \Validator::make($input, $validationRules);

          if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
          }
            $pelanggan = new Pelanggan;
            $pelanggan->id_pelanggan = Auth::user()->id;
            $pelanggan->nama = $request->input('nama');
            $pelanggan->nik = $request->input('nik');
            $pelanggan->no_hp = $request->input('no_hp');
            $pelanggan->tanggal_lahir = $request->input('tanggal_lahir');
            $pelanggan->jenis_kelamin = $request->input('jenis_kelamin');
            $pelanggan->alamat = $request->input('alamat');
            $pelanggan->save();
          }
            return response()->json($pelanggan, 200);
      }

    /**
     *Display the specified resource
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */

    public function show($id)
    {
      $pelanggan = Pelanggan::find($id);

      if(!$pelanggan) {
        abort(404);
      }

      if (Gate::denies('show-pelanggan', $pelanggan)) {
        return response()->json([
          'success' => false,
          'status' => 403,
          'message' => 'You Are unauthorized'
        ], 403);
      }
        return response()->json($pelanggan,200);
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
      $input = $request->all();
      $pelanggan = Pelanggan::find($id);

      if(!$pelanggan) {
        abort(404);
      }

      if (Gate::denies('update-pelanggan', $pelanggan)) {
        return response()->json([
          'success' => false,
          'status' => 403,
          'message' => 'You Are unauthorized'
        ], 403);
      }

      $validationRules = [
        'nama' => 'required|min:5',
        'nik' => 'required|min:16',
        'no_hp' => 'required|min:11',
        'tanggal_lahir' => 'required|date',
        'jenis_kelamin' => 'required|in:laki-laki,perempuan',
        'alamat' => 'required|min:5'
      ];

      $validator = \Validator::make($input, $validationRules);

      if($validator->fails()) {
        return response()->json($validator->errors(), 400);
      }

      $pelanggan->fill($input);
      $pelanggan->save();

      return response()->json($pelanggan,200);
    }

  /**
 	* Remove the specified resource from storage
 	*
 	* @param int $id
 	* @return  \Illuminate\Http\Response
 	*/

 	public function delete($id)
 	{
  	$pelanggan= Pelanggan::find($id);

  	if(!$pelanggan) {
   		abort(404);
  	}

    if (Gate::denies('delete-pelanggan', $pelanggan)) {
      return response()->json([
        'success' => false,
        'status' => 403,
        'message' => 'You Are unauthorized'
      ], 403);
    }

    $pelanggan->delete();
  	$message =['message' => 'deleted succesfully', 'pelanggan_id' => $id];

  	return response()->json($message,200);
 	}
}
