<?php

namespace App\Http\Controllers;

use App\Training;
use App\TrainingAttendees;
use App\TrainingFiles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Validator;

class TrainingController extends Controller
{

    public function getAllActiveTrainingWithFiles(){
        $mixData = [];
        $activeTraining = Training::where('status' , 'ACTIVE')->get();

        if(!empty($activeTraining)){
            foreach ($activeTraining as $training) {
                $trainingFiles = TrainingFiles::where('training_id' , $training->id)->get();
                $data = ['training' => $training,'files' => $trainingFiles];
                array_push($mixData,$data);
            }
        }

        return response(['data' => $mixData, 'message' => 'all ACTIVE training', 'status' => true, 'statusCode' => env('HTTP_SERVER_CODE_OK')]);
    }

    public function getAllTraining(){
        return response(['data' => Training::all(), 'message' => 'all trainings!', 'status' => true, 'statusCode' => env('HTTP_SERVER_CODE_OK')]);
    }

    public function getTrainingByID($id,Request $request){
        $mixData = [];
        $training = Training::find($id);

        if(!empty($training)){
            $trainingFiles = TrainingFiles::where('training_id' , $training->id)->get();
            $data = ['training' => $training,'files' => $trainingFiles];
            array_push($mixData,$data);
        }

        return response(['data' => $mixData, 'message' => 'get training by id!', 'status' => true, 'statusCode' => env('HTTP_SERVER_CODE_OK')]);
    }

    public function getAllActiveTrainingWithFilesAndAttendees(){

    }

}
