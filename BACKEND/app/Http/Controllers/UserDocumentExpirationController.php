<?php

namespace App\Http\Controllers;

use App\UsersDocumentExpiration;
use App\UsersDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use Validator;

class UserDocumentExpirationController extends Controller
{

    public function getAllActiveDocumentExpiration()
    {
        return response(['data' => UsersDocumentExpiration::where('status' , 'ACTIVE')->get(), 'message' => 'all ACTIVE document type', 'status' => true, 'statusCode' => env('HTTP_SERVER_CODE_OK')]);
    }

    public function getDocumentExpirationByName($name)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);

        $input = $request->all();
        $name = $input['name'];

        if ($validator->fails()) {
            return response(['message' => 'Validation errors', 'errors' => $validator->errors(), 'status' => false], 422);
        }

        return response(['data' => UsersDocumentExpiration::where('name' , $name)->first(), 'message' => 'all Users Document Expiration by name', 'status' => true, 'statusCode' => env('HTTP_SERVER_CODE_OK')]);
    }

    public function getDocumentExpirationByID($id)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            return response(['message' => 'Validation errors', 'errors' => $validator->errors(), 'status' => false], 422);
        }

        return response(['data' => UsersDocumentExpiration::find($id), 'message' => 'get UsersDocumentExpiration by id!', 'status' => true, 'statusCode' => env('HTTP_SERVER_CODE_OK')]);
    }

}
