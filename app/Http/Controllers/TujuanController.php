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
	                "id"          	  	=> $p->id,
                  "name"              => $p->name,
                  "keterangan"        => $p->keterangan,
                  "no_hp  "           => $p->no_hp,
                  "tujuan"            => $p->tujuan,
                  "tanggal_berangkat" => date('Y-m-d', strtotime($p->tanggal_berangkat)),
                  "tanggal_pulang"    => date('Y-m-d', strtotime($p->tanggal_pulang)),
                  "titik_kumpul"      => $p->titik_kumpul,
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
                "id"          	  	=> $p->id,
                "name"              => $p->name,
                "keterangan"        => $p->keterangan,
                "no_hp  "           => $p->no_hp,
                "tujuan"            => $p->tujuan,
                "tanggal_berangkat" => date('Y-m-d', strtotime($p->tanggal_berangkat)),
                "tanggal_pulang"    => date('Y-m-d', strtotime($p->tanggal_pulang)),
                "titik_kumpul"      => $p->titik_kumpul,
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
                "keterangan"        => 'required|string|max:225',
                "no_hp"             => 'required|numeric',
                "tujuan"            => 'required|string|max:255',
                "tanggal_berangkat" => 'required|date_format:Y-m-d',
                "tanggal_pulang"    => 'required|date_format:Y-m-d',
                "titik_kumpul"      => 'required|string|max:255',
    		]);

    		if($validator->fails()){
    			return response()->json([
    				'status'	=> 0,
    				'message'	=> $validator->errors()
    			]);
    		}

    		$data = new Tujuan();
	        $data->name = $request->input('name');
          $data->keterangan = $request->input('keterangan');
          $data->no_hp = $request->input('no_hp');
          $data->tujuan = $request->input('tujuan');
          $data->tanggal_berangkat = $request->input('tanggal_berangkat');
          $data->tanggal_pulang = $request->input('tanggal_pulang');
          $data->titik_kumpul = $request->input('titik_kumpul');
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
          "keterangan"        => 'required|string|max:225',
          "no_hp"             => 'required|numeric',
          "tujuan"            => 'required|string|max:255',
          "tanggal_berangkat" => 'required|date_format:Y-m-d',
          "tanggal_pulang"    => 'required|date_format:Y-m-d',
          "titik_kumpul"      => 'required|string|max:255',
		]);

      	if($validator->fails()){
      		return response()->json([
      			'status'	=> '0',
      			'message'	=> $validator->errors()
      		]);
      	}

      	//proses update data
      	$data->name = $request->input('name');
        $data->keterangan = $request->input('keterangan');
        $data->no_hp = $request->input('no_hp');
        $data->tujuan = $request->input('tujuan');
        $data->tanggal_berangkat = $request->input('tanggal_berangkat');
        $data->tanggal_pulang = $request->input('tanggal_pulang');
        $data->titik_kumpul = $request->input('titik_kumpul');
	      $data->save();

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

    public function find(Request $request, $limit = 10, $offset = 0)
    {
        $find = $request->find;
        $teman = Tujuan::where("id","like","%$find%")
        ->orWhere("tujuan","like","%$find%")
        ->orWhere("titik_kumpul","like","%$find%");
        $data["count"] = $teman->count();
        $temans = array();
        foreach ($teman->skip($offset)->take($limit)->get() as $p) {
          $item = [
            "id"                  => $p->id,
	          "name"                => $p->nama,
	          "keterangan"          => $p->keterangan,
        	  "no_hp"    	          => $p->no_hp,
            "tujuan"    	        => $p->tujuan,
            "tanggal_berangkat"   => $p->tanggal_berangkat,
            "tanggal_pulang"      => $p->tanggal_pulang,
            "titik_kumpul"        => $p->titik_kumpul,
            "created_at"          => $p->created_at,
            "updated_at"          => $p->updated_at
          ];
          array_push($temans,$item);
        }
        $data["teman"] = $temans;
        $data["status"] = 1;
        return response($data);
    }
}
