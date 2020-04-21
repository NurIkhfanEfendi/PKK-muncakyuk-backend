<?php

namespace App\Http\Controllers;

use App\Tujuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class TujuanController extends Controller
{

    public function index()
    {
    	try{
	        $data["count"] = Tujuan::count();
	        $tujuan = array();

	        foreach (Tujuan::all() as $p) {
	            $item = [
	                "id"          		=> $p->id,
                    "name"              => $p->name,
                    "tujuan"            => $p->tujuan,
                    "tanggal_berangkat" => date('Y-m-d', strtotime($p->tanggal_berangkat)),
                    "tanggal_pulang"    => date('Y-m-d', strtotime($p->tanggal_pulang)),
                    "titik_kumpul"      => $p->titik_kumpul,
                    "nomerwa"           => $p->nomerwa
	            ];

	            array_push($tujuan, $item);
	        }
	        $data["tujuan"] = $tujuan;
	        $data["status"] = 1;
	        return response($data);

	    } catch(\Exception $e){
			return response()->json([
			  'status' => '0',
			  'message' => $e->getMessage()
			]);
      	}
    }

    public function getAll($limit = 10, $offset = 0)
    {
    	try{
	        $data["count"] = Tujuan::count();
	        $tujuan = array();

	        foreach (Tujuan::take($limit)->skip($offset)->get() as $p) {
	            $item = [
	                "id"          		=> $p->id,
	                "name"              => $p->name,
                    "tujuan"            => $p->tujuan,
                    "tanggal_berangkat" => $p->tanggal_berangkat,
                    "tanggal_pulang"    => $p->tanggal_pulang,
                    "titik_kumpul"      => $p->titik_kumpul,
                    "nomerwa"           => $p->nomerwa
	            ];

	            array_push($tujuan, $item);
	        }
	        $data["tujuan"] = $tujuan;
	        $data["status"] = 1;
	        return response($data);

	    } catch(\Exception $e){
			return response()->json([
			  'status' => '0',
			  'message' => $e->getMessage()
			]);
      	}
    }

    public function store(Request $request)
    {
      try{
    		$validator = Validator::make($request->all(), [
                "name"              => 'required|string|max:255',
                "tujuan"            => 'required|string|max:255',
                "tanggal_berangkat" => 'required|date_format:Y-m-d',
                "tanggal_pulang"    => 'required|date_format:Y-m-d',
                "titik_kumpul"      => 'required|string|max:255',
                "nomerwa"           => 'required|numeric',
    		]);

    		if($validator->fails()){
    			return response()->json([
    				'status'	=> 0,
    				'message'	=> $validator->errors()
    			]);
    		}

    		$data = new Tujuan();
	        $data->name = $request->input('name');
            $data->tujuan = $request->input('tujuan');
            $data->tanggal_berangkat = $request->input('tanggal_berangkat');
            $data->tanggal_pulang = $request->input('tanggal_pulang');
            $data->titik_kumpul = $request->input('titik_kumpul');
	        $data->nomerwa = $request->input('nomerwa');
	        $data->save();

    		return response()->json([
    			'status'	=> '1',
    			'message'	=> 'Data berhasil ditambahkan!'
    		], 201);

      } catch(\Exception $e){
            return response()->json([
                'status' => '0',
                'message' => $e->getMessage()
            ]);
        }
  	}


    public function update(Request $request, $id)
    {
      try {
      	$validator = Validator::make($request->all(), [
			"name"              => 'required|string|max:255',
            "tujuan"            => 'required|string|max:255',
            "tanggal_berangkat" => 'required|date_format:Y-m-d',
            "tanggal_pulang"    => 'required|date_format:Y-m-d',
            "titik_kumpul"      => 'required|string|max:255',
            "nomerwa"           => 'required|numeric|max:255',
		]);

      	if($validator->fails()){
      		return response()->json([
      			'status'	=> '0',
      			'message'	=> $validator->errors()
      		]);
      	}

      	//proses update data
    	$data = Tujuan::where('id', $id)->first();
        $data->name = $request->input('name');
        $data->tujuan = $request->input('tujuan');
        $data->tanggal_berangkat = $request->input('tanggal_berangkat');
        $data->tanggal_pulang = $request->input('tanggal_pulang');
        $data->titik_kumpul = $request->input('titik_kumpul');
        $data->nomerwa = $request->input('nomerwa');

      	return response()->json([
      		'status'	=> '1',
      		'message'	=> 'Data berhasil diubah'
      	]);
        
      } catch(\Exception $e){
          return response()->json([
              'status' => '0',
              'message' => $e->getMessage()
          ]);
      }
    }

    public function delete($id)
    {
        try{

            $delete = Tujuan::where("id", $id)->delete();

            if($delete){
              return response([
                "status"  => 1,
                  "message"   => "Data berhasil dihapus."
              ]);
            } else {
              return response([
                "status"  => 0,
                  "message"   => "Data gagal dihapus."
              ]);
            }
            
        } catch(\Exception $e){
            return response([
            	"status"	=> 0,
                "message"   => $e->getMessage()
            ]);
        }
    }

}
