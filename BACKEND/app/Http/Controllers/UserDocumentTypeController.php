<?php

namespace App\Http\Controllers;

use App\UsersDocumentType;
use App\UsersDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use Validator;

class UserDocumentTypeController extends Controller
{

    public function getAllActiveDocumentType()
    {
        return response(['data' => UsersDocumentType::where('status' , 'ACTIVE')->get(), 'message' => 'all ACTIVE document type', 'status' => true, 'statusCode' => env('HTTP_SERVER_CODE_OK')]);
    }

    public function getDocumentByName($name)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);

        $input = $request->all();
        $name = $input['name'];

        if ($validator->fails()) {
            return response(['message' => 'Validation errors', 'errors' => $validator->errors(), 'status' => false], 422);
        }

        return response(['data' => UsersDocumentType::where('name' , $name)->first(), 'message' => 'all UserDocumentType by name', 'status' => true, 'statusCode' => env('HTTP_SERVER_CODE_OK')]);
    }

    public function getDocumentByID($id)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            return response(['message' => 'Validation errors', 'errors' => $validator->errors(), 'status' => false], 422);
        }

        return response(['data' => UsersDocumentType::find($id), 'message' => 'get UserDocumentType by id!', 'status' => true, 'statusCode' => env('HTTP_SERVER_CODE_OK')]);
    }

}
