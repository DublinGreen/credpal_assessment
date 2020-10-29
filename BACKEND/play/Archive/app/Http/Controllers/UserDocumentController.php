<?php

namespace App\Http\Controllers;

use App\User;
use App\UsersDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use Validator;

class UserDocumentController extends Controller
{

    public function uploadDocument(Request $request){
        if ($request->hasFile('file')) {
            $input = $request->all();

            try
            {
                $user = User::findOrFail($input['userID']);
            }
            catch(ModelNotFoundException $e)
            {
                return response(['message' => 'Validation errors', 'errors' => "userID is invalid.", 'status' => false], 200);
            }

            try
            {
                $user = User::findOrFail($input['uploadedBy']);
            }
            catch(ModelNotFoundException $e)
            {
                return response(['message' => 'Validation errors', 'errors' => "uploadedBy is invalid.", 'status' => false], 200);
            }

            $validator = Validator::make($request->all(), [
                'description' => 'required',
            ]);

            if ($validator->fails()) {
                return response(['message' => 'Validation errors', 'errors' => $validator->errors(), 'status' => false], 422);
            }

            $user = User::findOrFail($input['userID']);
            $uploadingUser = User::findOrFail($input['uploadedBy']);
            $documentName = $request->file('file')->getClientOriginalName();
            $uniqueDocumentName = md5_file($request->file('file')->getRealPath()) . time();
            $ext = pathinfo(storage_path().$documentName, PATHINFO_EXTENSION);
            $destinationPath = "uploads/documents/";
            $request->file('file')->move($destinationPath, $uniqueDocumentName . "." . $ext);
            $returnData = [
                "documentName" => $uniqueDocumentName . '.' . $ext,
                "path" => $destinationPath . $uniqueDocumentName . '.' . $ext,
                "uploadedToUser" => $user->id,
                "uploadedBy" => $uploadingUser->id,
                "description" => $input['description'],
                'ext' => $ext
            ];

            $userDocument = UsersDocument::create([
                'user_id' => $user->id,
                'path' => $destinationPath . $uniqueDocumentName . '.' . $ext,
                'name' => $uniqueDocumentName . '.' . $ext,
                'description' => $input['description'],
                'uploaded_by' => $uploadingUser->id,
                'status' => 'ACTIVE'
            ]);
            $saved = $userDocument->save();
            if($saved){
                return response(['data' => $returnData, 'message' => 'uploaded file!', 'status' => true, 'statusCode' => env('HTTP_SERVER_CODE_OK')]);
            }
        }
    }

    public function viewUserDocument(){

    }
}
