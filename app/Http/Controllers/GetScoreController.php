<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Results;

class GetScoreController extends Controller
{
    public function index(){

    	
	    try{ 
	    	
	    	
	      $surveyResultDetails = Results::select('custom_json', 'total_score','inital_score','json','partial_data','manage_score','survey_id','entity_id','review_json','review_custom_json','id')->where('status', 'Review_completed')->get()->toArray();
	    
	      foreach($surveyResultDetails as $value){
	      	ini_set('max_execution_time', '300');
	      	$row_id =  $value['id'];
	      	

	      	if($value['partial_data'] == NULL && $value['review_json'] == NULL && $value['review_custom_json'] == NULL ){

	      		$daata = Results::where(['id' => $row_id, 'status'=>'Review_completed'])->update(['current_score' => $value['inital_score']]);

	      	}elseif($value['partial_data'] != NULL && $value['manage_score'] != NULL && $value['review_json'] != NULL && $value['review_custom_json'] != NULL){

	      		$manage_score = unserialize($value['manage_score']);
         
		        $manage_old = $manage_score['old'];
		        $manage_new = $manage_score['new'];
		        $old_score = array_sum($manage_old);
		        $inital_score=$value['inital_score'];
		        $newScoreTable = array_sum($manage_new);
	          
          		$current_score = ($newScoreTable + $inital_score) - $old_score;


	      		$daataaa = Results::where(['id' => $row_id, 'status' =>'Review_completed'])->update(['current_score' => $current_score]);
	      		
	      	}
	      	
	      	
	      }
	      if($daataaa){
	      	echo "Record Succesfully updated";
	      	redirect()->back();
	      }

	     
	    }catch(Exception $exception){
	      return $exception->getMessage();  
	    }  
    }
}
