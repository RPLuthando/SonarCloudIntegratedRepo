<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request; 
use App\Survey;
use App\Results;
use App\Entity;
use App\survey_diff_answer; 

class SurveyNewController extends Controller
{
  /**
   * Get json for results table and send to surveyrun_new.js
   * 
   * 
   * 
   * @param int $id as survey id.
   * @param int $en as entity id.
   * 
   * @return  json
  */
    
  
    public function surveyView(Request $request,$id,$en) 
    { 
     
      try{
          $user_id = auth()->user()->id;
          $getPartialSurvey = Results::select('json')->where(['survey_id' => $id, 'entity_id' => $en])->get()->toArray();   
          $getPartialSurvey = array_column($getPartialSurvey, 'json');   
          $survey_data = [];
          if(!empty($getPartialSurvey) && count($getPartialSurvey) > 0 ){
            $survey_data['partial_json'] = $getPartialSurvey[0];
          }else{
            $survey_data['partial_json'] = 'no data';
          }
          $rand = rand();
          $storage_id = 'storage_id'.$rand;
          $data = Survey::select('json')->where('id',$id)->get()->toArray();
          $data = array_column($data, 'json');
          $survey_data['storage_id'] = $storage_id;
          $survey_data['survey_json'] = $data[0];        
          
          return $survey_data;

        }catch(Exception $exception){
            return $exception->getMessage();
        }
         
    
    }

   /**
   * Get json for results table and send to surveyrun_new.js update status, json and review json
   * For partially save initial survey
   * 
   * 
   * 
   * @param int $en as entity id.
   * 
   * @return  App\Results;
  */
   
    public function partialSurvey(Request $request,$en) 
    { 
        
        
      try{
        
        $resultsJson =  str_replace("'","\'",$request->resultsJson);
        $data = Results::where(['survey_id' => $request->postId, 'status' =>'in_progress', 'entity_id' => $en])->get();
        if(!empty($data) && count($data) > 0){
            Results::where(['survey_id' => $request->postId , 'status' => 'in_progress', 'entity_id' => $en])->update(['json'=>$resultsJson, 'review_json'=>$resultsJson]);
        }else{
          $data = Results::where(['survey_id' => $request->postId, 'status' => 'Complete_initial', 'entity_id' => $en])->get();
            if(!empty($data) && count($data) > 0){
                Results::where(['survey_id' => $request->postId, 'status' => 'Complete_initial',  'entity_id' => $en])->update(['json'=>$resultsJson,'review_json'=>$resultsJson,'status' =>'in_progress']);
               }else {
                    $result = new Results;
                    $result->survey_id = $request->postId;
                    $result->json = $resultsJson;
                    $result->review_json = $resultsJson;                   
                    $result->entity_id = $en;
                    $result->status = 'in_progress';
                    $result->save();
               }         
        }
         
        }catch(Exception $exception){
            return $exception->getMessage(); 
        }
    }

    /**
   * Get json for results table and send to surveyrun_new.js update status, json and review json
   * For complete survey save initial survey Complete_initial
   * 
   * 
   * 
   * @param int $en as entity id.
   * 
   * @return  App\Results;
  */
    public function completeSurvey(Request $request,$en)
    { 
     
      
       try{
        $user_id = auth()->user()->id;
        $resultsJson =  str_replace("","\'",$request->resultsJson);
       
      
        $data = Results::where(['survey_id' => $request->postId, 'status' => 'in_progress', 'entity_id' => $en])->get();
        if(!empty($data) && count($data) > 0){
          $daaa =  Results::where(['survey_id' => $request->postId, 'status' => 'in_progress', 'entity_id' => $en])->update(['json'=>$resultsJson,'review_json'=>$resultsJson, 'status' =>'Complete_initial']);
          
      
        }
        else
        {
          $data = Results::where(['survey_id' => $request->postId , 'status' => 'Complete_initial', 'entity_id' => $en])->get();
          if(!empty($data) && count($data) > 0){
            Results::where(['survey_id' =>$request->postId, 'status' => 'Complete_initial', 'entity_id', $en])->update(['json'=>$resultsJson,'review_json'=>$resultsJson, 'status' =>'Complete_initial']);
            
            
          }else {
                $result = new Results;
                $result->survey_id = $request->postId;
                $result->json = $resultsJson;
                $result->review_json= $resultsJson;
                $result->entity_id = $en;
                $result->status = 'Complete_initial';
                $result->save();                
               }
              
        }
        
        }catch(Exception $exception){
            return $exception->getMessage();
        }
    }

 /**
   * Get json for results table and send to surveyrun_new.js update status, json and review json
   * For complete survey save initial survey Complete_initial and update custom json and review custom json with all parameters 
   * 
   * 
   * 
   * @param int $en as entity id.
   * 
   * @return  App\Results;
  */
    public function surveyParameters(Request $request, $en)
    {    
     
     
       try{
          $user_id = auth()->user()->id;
          $customJson =  str_replace("'","\'",$request->customJson);
          
          Results::where(['survey_id' =>$request->postId,  'entity_id' => $en])->update(['custom_json'=>$customJson, 'review_custom_json'=>$customJson]);
        
        }catch(Exception $exception){
            return $exception->getMessage(); 
        }
    }
}
