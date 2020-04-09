<?php

namespace App\Http\Controllers;

use App\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AdminController extends Controller
{

    public function getAll($limit = 10, $offset = 0)
    {
    	try{
	        $data["count"] = Admin::count();
	        $admin = array();

	        foreach (Admin::take($limit)->skip($offset)->get() as $p) {
	            $item = [
	                "id"          		=> $p->id,
	                "firstname"         => $p->firstname,
	                "lastname"  		=> $p->lastname,
                    "tempat_tinggal"    => $p->tempat_tinggal,
                    "email"             => $p->email,
                    "created_at"        => $p->created_at,
	                "updated_at"        => $p->updated_at
	            ];

	            array_push($admin, $item);
	        }
	        $data["admin"] = $admin;
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
		$validator = Validator::make($request->all(), [
			'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'tempat_tinggal' => 'required|string|max:255',
			'email' => 'required|string|email|max:255|unique:User',
			'password' => 'required|string|min:6',
			'password_verify' => 'required|string|min:6',
		]);

		if($validator->fails()){
			return response()->json([
				'status'	=> 0,
				'message'	=> $validator->errors()
			]);
		}

		$data = new Admin();
		$data->firstname 	= $request->firstname;
        $data->lastname 	= $request->lastname;
        $data->tempat_tinggal  = $request->tempat_tinggal;
		$data->email 	= $request->email;
		$data->password = Hash::make($request->password);
		$data->password_verify = Hash::make($request->password_verify);
		$data->save();

		$token = JWTAuth::fromdUser($data);

		return response()->json([
			'status'	=> '1',
			'message'	=> 'User berhasil ditambahkan'
			//'user'		=> $user,
		], 201);
    }
      
    public function update(Request $request, $id)
    {
      try {
      	$validator = Validator::make($request->all(), [
			'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'tempat_tinggal' => 'required|string|max:255',
			'email' => 'required|string|email|max:255|unique:User',
			'password' => 'required|string|min:6',
			'password_verify' => 'required|string|min:6',
		]);

      	if($validator->fails()){
      		return response()->json([
      			'status'	=> '0',
      			'message'	=> $validator->errors()
      		]);
      	}

      	//proses update data
      	$data = Admin::where('id', $id)->first();
        $data->firstname = $request->input('firstname');
        $data->lastname = $request->input('lastname');
        $data->tempat_tinggal = $request->input('tempat_tinggal');
        $data->email = $request->input('email');
        $data->password = $request->input('password');
        $data->password_verify = $request->input('password_verify');
        $data->save();

      	return response()->json([
      		'status'	=> '1',
      		'message'	=> 'Data Admin berhasil diubah'
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
        	$delete = Admin::where("id", $id);
        		$delete->delete();

        		if($delete){
	            	return response([
		            	"status"	=> 1,
		                "message"   => "Data berhasil dihapus."
		            ]);
	            } else {
	            	return response([
		            	"status"	=> 0,
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
