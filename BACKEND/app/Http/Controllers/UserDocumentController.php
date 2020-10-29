<?php

namespace App\Http\Controllers;

use App\User;
use App\UsersDocument;
use App\UsersDocumentType;
use App\UsersDocumentSendHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Storage;
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
                $userDocumentType = UsersDocumentType::findOrFail($input['documentTypeID']);
            }
            catch(ModelNotFoundException $e)
            {
                return response(['message' => 'Validation errors', 'errors' => "documentTypeID is invalid.", 'status' => false], 200);
            }

            if($user->email != $input['uploadedBy']){
                return response(['message' => 'Validation errors', 'errors' => "uploadedBy is invalid.", 'status' => false], 200);
            }

            $validator = Validator::make($request->all(), [
                'description' => 'required',
                'documentTypeID' => 'required',
                'expiringDate' => 'required',
                'expirationInterval' => 'required'
            ]);

            if ($validator->fails()) {
                return response(['message' => 'Validation errors', 'errors' => $validator->errors(), 'status' => false], 422);
            }

            $user = User::findOrFail($input['userID']);
            $uploadingUser = $user->email;
            $documentName = $request->file('file')->getClientOriginalName();
            $uniqueDocumentName = md5_file($request->file('file')->getRealPath()) . time();
            $ext = pathinfo(storage_path().$documentName, PATHINFO_EXTENSION);
            $destinationPath = "uploads/documents/";
            $request->file('file')->move($destinationPath, $uniqueDocumentName . "." . $ext);

            $documentKey = Hash::make(time() . $user->id . $destinationPath . $uniqueDocumentName . '.' . $ext);

            $returnData = [
                "documentName" => $uniqueDocumentName . '.' . $ext,
                "path" => $destinationPath . $uniqueDocumentName . '.' . $ext,
                "uploadedBy" => $uploadingUser,
                'documentKey'=> $documentKey,
                'document_type' => $userDocumentType->name,
                'expiring_date' => $input['expiringDate'],
                "description" => $input['description'],
                'ext' => $ext
            ];

            $userDocument = UsersDocument::create([
                'user_id' => $user->id,
                'path' => $destinationPath . $uniqueDocumentName . '.' . $ext,
                'name' => $uniqueDocumentName . '.' . $ext,
                'description' => $input['description'],
                'document_key'=> $documentKey,
                'document_type' => $userDocumentType->name,
                'expiring_date' => $input['expiringDate'],
                'expiration_interval' => $input['expirationInterval'],
                'uploaded_by' => $uploadingUser,
                'status' => 'ACTIVE'
            ]);
            $saved = $userDocument->save();
            if($saved){
                return response(['data' => $returnData, 'message' => 'uploaded file!', 'status' => true, 'statusCode' => env('HTTP_SERVER_CODE_OK')]);
            }
        }else{
            return response(['message' => 'Validation errors', 'errors' => "no file sent", 'status' => false], 200);
        }
    }

    public function fetchUserDocument($documentKey){
        $encodedKey = urlencode('$2y$10$H2MXCxzy0s74mwfQu41hCeTd9C9isG6g8WWT4F5bU/PRXkPme1Ixq');
        $decodedKey = urldecode($documentKey);

        // echo($encodedKey);
        $documentData = UsersDocument::where('document_key' , $decodedKey)->first();
        $filePath = url('/') . "/" . $documentData->path;

        $file_path = app()->path() . '/../public/' . $documentData->path;

        $headers = array(
            'Content-Type: application/pdf',
        );

        return response()->download($file_path);
        // return Storage::download($file_path,$documentData->name,$headers);



        // return response(['data' => $filePath,'message' => 'document path', 'status' => true, 'statusCode' => env('HTTP_SERVER_CODE_OK')]);

        // return response()->download($file);

        // return Storage::response($file_path);

        // // Use a custom filename
        // return Storage::response($document->path, 'sweet.pdf');

        // // Force the file to download
        // return Storage::download($filePath);
    }

    public function getUserDocuments($user_id)
    {
        $userID = $user_id;

        $returnData = UsersDocument::where('user_id' , [$userID])->get();

        return response(['data' => $returnData,'message' => 'get all user documents', 'status' => true, 'statusCode' => env('HTTP_SERVER_CODE_OK')]);
    }

    public function updateUserDocument(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'status' => 'required',
            'description' => 'required'
        ]);

        if ($validator->fails()) {
            return response(['message' => 'Validation errors', 'errors' => $validator->errors(), 'status' => false], 422);
        }

        try
        {
            $userDocument = UsersDocument::findOrFail($input['id']);
        }
        catch(ModelNotFoundException $e)
        {
            return response(['message' => 'Validation errors', 'errors' => "id is invalid.", 'status' => false], 200);
        }

        $timestamp = date("Y-m-d H:i:s");
        $userDocument->status = $input['status'];
        $userDocument->description = $input['description'];
        $userDocument->updated_at = $timestamp;
        $saved = $userDocument->save();

        if($saved){
            return response(['data' => $userDocument, 'message' => 'userDocument updated', 'status' => true, 'statusCode' => env('HTTP_SERVER_CODE_OK')]);
        }

    }

    public function sendDocumentViaEmail(Request $request){

        $input = $request->all();

        $validator = Validator::make($request->all(), [
            'email_list' => 'required',
            'document_key' => 'required',
            'document_owner' => 'required'
        ]);

        $emailsArray = explode(",",$input['email_list']);

        foreach($emailsArray as $email){
            $userDocumentSendHistory = UsersDocumentSendHistory::create([
                'status' => "NOT SENT",
                'email' => $email,
                'document_key' => $input['document_key'],
                'document_owner' => $input['document_owner'],
            ]);
            $saved = $userDocumentSendHistory->save();
            if($saved){
                //send mail here
                $user = User::find($input['document_owner']);
                $userDocument = UsersDocument::where('document_key' , $input['document_key'])->first();

                // cliquedom/greendublin007@gmail.com/2e8f21de8f53d7c59a1eafc7ba22d1531596368733.pdf
                $sendEmailUrl = 'https://mail.corporate-setup.com/index.php/Sendingemail_Controller/send_document_link/'. urlencode($user->name) .'/' . urlencode($email) . '/'. urlencode($userDocument->name);

                // echo($sendEmailUrl);
                // exit();
                // create curl resource
                $ch = curl_init();

                // set url
                curl_setopt($ch, CURLOPT_URL, $sendEmailUrl);

                //return the transfer as a string
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

                // $output contains the output string
                $output  = "sent_document";
                $output = curl_exec($ch);

                // close curl resource to free up system resources
                curl_close($ch);
            }
        }


        if ($validator->fails()) {
            return response(['message' => 'Validation errors', 'errors' => $validator->errors(), 'status' => false], 422);
        }

    }

}
