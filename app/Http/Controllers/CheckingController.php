<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request; 
use App\Results;
use App\Survey;
use App\Entity;
use App\Reviews;
use App\SurveyDiffAnswer;
class CheckingController extends Controller
{

  /**  
  *  Get the checked checkbox value and update the result table
  * 
  *  @param  $request, get the id of record those save in result table  *
  * 
  *  @return: sucess message
  */ 
  public function checkboxvalue(Request $request){
    
    try{
         
        if(!empty($request->columnId)){ 
        
          $surveyData =  $request->columnId;         
          $getStatus = ' '; 
          foreach($surveyData as $key => $value){
           
           $updateCurrentScore = Results::select('inital_score')->where('id',$value)->get()->toArray();
            
           $current_score_without_sub = $updateCurrentScore[0]['inital_score'];
            //,'review_json' => null, 'review_custom_json' => null  
           Results::where([['status', 'Complete_initial'], ['id', $value]])
           ->update(['status'=>'Review_completed','current_score' =>$current_score_without_sub]);
          }
       
           
        }else{
            return redirect()->back()->with('flash_message', 'Please select at least one survey to complete');
            
        }
        return back(); 
    }catch(Exception $exception){
        return $exception->getMessage();  
      }     
  }

  /**
   * Get name from entity table using query
   * purpose.
   *
   * @param int $entityId Value to run query
   *
   * @return object
   */
 
  public function getEntityDetails($entityId){
          
    try{
        $entityName = Entity::select('name')->where('id',$entityId)->first();
    }catch(Exception $exception){
      return $exception->getMessage();  
    }  
      return $entityName;
  }

  /**
   * Get json, name and deadline from survey table using query
   * purpose.
   *
   * @param int $id Value to run query
   *
   * @return  collaction of objects
   */
  
  public function getSurveyDetails($id){
          
    try{ 
        $surveyDetailJson = Survey::select('json', 'name', 'deadline')->where('id',$id)->first();
      }catch(Exception $exception){
        return $exception->getMessage();  
      }  
    return $surveyDetailJson;
  }

  /**
   * Get custom_json, total_score, inital_score, json, review_json, manage_score, review_custom_json and partial_data from results table with use of where clause with respective survey_id and entity_id  query
   * purpose.
   *
   * @param int $id Value to run query
   * @param int $entityId Value to run query
   * @return  collaction of objects
   */
  
  public function getResultDetails($id, $entityId){
          
    try{ 
        $surveyResultDetails = Results::select('custom_json', 'total_score','inital_score','json','review_json','manage_score','review_custom_json','partial_data','revision_number')->where(['survey_id'=>$id, 'entity_id'=>$entityId])->first();
      }catch(Exception $exception){
        return $exception->getMessage();  
      }  
    return $surveyResultDetails;
  }

  /**
   * Get json with use fo getSurveyDetails method send the respective survey id as parmeter.
   * Convert json in array format and create a new array to manage TEXT type and Radiogroup type.
   * 
   * @param int $id as survey id.
   * 
   * @return  Array
   */
 
  public function getSurveyDetailsInArray($id){
        
    try{
            $surveyDetailJson = $this->getSurveyDetails($id);
            //dd($surveyDetailJson);
           if(isset($surveyDetailJson)){
            $groupJson['json']=$surveyDetailJson->json;           
            
            $groupJson = json_decode($groupJson['json'], true);    
            //dd($groupJson);       
            $top_title = null;
            if(isset($groupJson['title'])){
              $top_title = $groupJson['title'];
            }
            $group = $groupJson['pages'];  
            //dd($group);       
            $page_html = 'pagehtml';
            $page_no = 0;
            $page_html_array = [];
            $page_array = [];
            $question_name = null;
            $page_no = 0;
            foreach($group as $key => $value) {

              $page_title = null;
              $page_name = null;
             
              if(isset($value['title'])){
                $page_title = $value['title'];
              }
              if(isset($value['name'])){
                $page_name = $value['name'];
              }
            
              
              foreach($value['elements'] as $k) {
                $inner_array = [];
             
              
                if(isset($k['type']) && $k['type'] == 'radiogroup' || $k['type'] == 'text'){ 
                 
  
                  if($page_title != null){
                    $inner_array['page_title'] = $page_title; 
                  }
                  $get_html_value = $page_html.$page_no;
                    
                    if($k['type'] == 'radiogroup' && (!isset($k['visible']))){
                      $inner_array['radiogroup'] = $k;
                    }
                    if($k['type'] == 'file'){
                      $inner_array['file'] = $k;
                    }
                  
                    if($k['type'] == 'text' && (!isset($k['visible'])) && (!isset($value['visible']))){
                      $inner_array['text'] = $k;
                    } 
  
                    if((!isset($k['visible'])) && (!isset($value['visible']))){
                      $question_name = $k['name'];
                    }
                  
                    if($question_name != null && (!isset($value['visible']))){
                      $page_array[$question_name] = $inner_array;
                    }
  
                 
                 
                }
                
                if(isset($k['type']) && $k['type'] == 'comment'){
                  $page_array[$question_name]['comment'] = $k;
                 
                } 
                if(isset($k['type']) && $k['type'] == 'file'){
                  $page_array[$question_name]['file'] = $k;
                 
                } 
  
               
              }  
              $page_no++;  
                     
            }
           }else{
            return Redirect::back();
           }
            
    }catch(Exception $exception){
      return $exception->getMessage();  
    }  
    //dd($page_array);
    return $page_array;
  }

  /**
   * Get $page_array and compare with results data.
   * Make new array after comparing the data with use of foreach
   * 
   * 
   * @param int $id as survey id.
   * @param int $entityId as entity id.
   * 
   * @return  view with array
  */

    public function surveyNewType ($id, $entityId){
          
      try{ 
          $dataElements = [];
          $i = 0;
          $user_id = auth()->user()->id;
          //Getting entity name for calling getEntityDetails method
          $entityName = $this->getEntityDetails($entityId);
          //Getting json from survey table calling getSurveyDetails method
          $surveyDetailJson = $this->getSurveyDetails($id);
          //Getting results table calling getResultDetails method
          $data = $this->getResultDetails($id, $entityId);
          $deadline = date('d M Y',strtotime($surveyDetailJson->deadline));
          //Getting array from getSurveyDetailsInArray with respective id
          $page_array = $this->getSurveyDetailsInArray($id);
          //
          //dd($data);
          $dataCount = count($page_array);
          //$results_from_dashboard = dashboard($id, $entityId);
          //dd($results_from_dashboard);
         
          if(isset($data)){
            $total_score = $data->total_score;
            $sum = $data->inital_score;
            $dataCustom = $data->custom_json;
            $dataCustomStriplash = stripslashes($dataCustom); 
            $dataResults = json_decode($dataCustomStriplash, true);                   
          
          }
        
          //dd($dataResults);
          foreach($page_array as $scoreValue){
            $radioGroupData = $scoreValue['radiogroup']['choices'];
            $a = 0;
            foreach($radioGroupData as $choicesValue){
              //echo $choicesValue['Score'] . '<br>';
              if($a < $choicesValue['Score']){
                $a = $choicesValue['Score'];
              }
            }
            
            $arr1[] = $a;
            
          }
          //161
          //die();
          // Making array with compare of result data and survey data
       
           $dataCreatedJson =[];
           $array_details = [];
           //dd($page_array);
           foreach($page_array as $nameKey=>$allValues)
           {
             //dd($page_array[$nameKey]['radiogroup']['choices']);
                if(isset($page_array[$nameKey]['radiogroup']['type'])){
                  $name=$page_array[$nameKey]['radiogroup']['name'];
                  $type = $page_array[$nameKey]['radiogroup']['type'];
                  $question=$page_array[$nameKey]['radiogroup']['title'];
                  $choices=$page_array[$nameKey]['radiogroup']['choices'];
                  //foreach($choices as $choices_value) {
                    //if($choices_value['Score'] == 1) {

                    //}
                  //}
                  $section=$allValues['page_title'];
                  $comment=$page_array[$nameKey]['comment'];
                }elseif(isset($page_array[$nameKey]['text']['type'])){
                  $name=$page_array[$nameKey]['text']['name'];
                  $type = $page_array[$nameKey]['text']['type'];
                  $question=$page_array[$nameKey]['text']['title'];
                  $section=$allValues['page_title'];
                  $comment=$page_array[$nameKey]['comment'];
                }elseif(isset($page_array[$nameKey]['file']['type'])){
                  $name=$page_array[$nameKey]['file']['name'];
                  $type = $page_array[$nameKey]['file']['type'];
                  $question=$page_array[$nameKey]['file']['title'];
                  $section=$allValues['page_title'];
                  $comment=$page_array[$nameKey]['comment'];
                }
                //dd($choices[0]['Standard']);
                $array_details['type'] = $type;
                $array_details['name'] = $name;
                $array_details['question'] = $question;
                $array_details['section'] = $section;
                $array_details['comment'] = '';
                $array_details['score'] = '';
                $array_details['standard'] = '';
				        $array_details['answered'] = '';
                $array_details['survey_id'] = $id;
                $array_details['max'] = $arr1[$i];
               //dd($dataResults);
            
                foreach($dataResults as $resultValues)
                {
                  
                  if ($resultValues['name']==$name && $resultValues['title']==$question)
                  {
                    if(isset($page_array[$nameKey]['radiogroup']['name']) || isset($page_array[$nameKey]['text']['name']) || isset($page_array[$nameKey]['file']['name'])){
                      $array_details['name'] = $resultValues['name'];
                      $array_details['question'] = $resultValues['title'];
					            $array_details['answered'] = $resultValues['value'];
                    }
                      $array_details['score'] = $resultValues['Score'];
                      $array_details['standard'] = $resultValues['Standard'];
                  } //else {
                      //$array_details['score'] = 'BOMScore';
                      //$array_details['standard'] = 'BomStd';
                  //}
              
                    if($resultValues['name'] == $nameKey.c || $resultValues['name'] == $nameKey.'.c'){
                      //echo 'Name: ' . $resultValues['name'] . 'Values: ' . $resultValues['value'] . '<br>';
                      $array_details['comment']=$resultValues['value'];
                    }
                    if($resultValues['name'] == $nameKey.f){
                      
                      $array_details['file']=$resultValues['value'];
                    }
                } 
            
                $i++;
                array_push($dataCreatedJson,$array_details);
                
                $array_details = [];
            }

          //dd($dataCreatedJson);
          $getNoComplience = [];
          $getIdsForNoideal = [];
          
           
          foreach($dataCreatedJson as $keyData=>$valueData){
            if($valueData['standard'] == 'Non-Standard' || $valueData['standard'] == '' && $valueData['type'] != 'text'){
                $getNoComplience[$keyData] = $valueData['name'];
            }
            if(($valueData['standard'] == 'Acceptable') || ($valueData['standard'] == 'Non-Standard') && $valueData['type'] != 'text' ){
                $getIdsForNoideal[$keyData] = $valueData['name'];
            
            }
            
          }
          //BOMBOMBOMBOM
          //Handle score from to_be_reviewd table
          //dd($data->revision_number);
          $updated_score = \DB::table('to_be_reviewed')
          ->select('id','revision_number', 'entity_id','survey_id','sjs_score','sjs_name','sjs_value','sjs_comment','sjs_title')
          ->where(['entity_id' => $entityId, 'survey_id' => $id, 'revision_number' => $data->revision_number ])
          ->get();
          //dd($updated_score);
          $total_updated_score = 0;
          $new_output_array['survey_id'] = $id;
          $surveyDetailJson = $this->getSurveyDetails($id);
          $groupJson['json']=$surveyDetailJson->json;           
          $groupJson = json_decode($groupJson['json'], true);
          //dd($groupJson['pages']);
          foreach ($groupJson['pages'] as $max_score_key => $max_score_value) {
              $new_output_array['section'] = $max_score_value['title'];
              foreach ($max_score_value['elements'] as $elements_value) {
                $new_output_array['title'] = $elements_value['title'];
                
                if ($elements_value['type'] === 'radiogroup') {
                  $score_array = [];
                  $new_output_array['survey_name'] = $elements_value['name'];
                  foreach ($updated_score as $updated_score_value) {
                    if ($updated_score_value->sjs_score == 999.99 && $elements_value['name'].c == $updated_score_value->sjs_name) {
                      $new_output_array['comment'] = $updated_score_value->sjs_value;
                    }
                  }
                    
                  foreach ($elements_value['choices'] as $choices_value) {
                    foreach ($updated_score as $updated_score_value) {
                        if ($updated_score_value->sjs_name == $elements_value['name']) {
                          $new_output_array['newest_score'] = $updated_score_value->sjs_score;
                          $new_output_array['answer'] = $updated_score_value->sjs_value;
                         
                          if ($choices_value['value'] == $updated_score_value->sjs_value) {
                            $new_output_array['standard'] = $choices_value['Standard'];
                             //echo $elements_value['name'] . ' << '. $elements_value['title'] . '>>>' . number_format($choices_value['Score'],2) . '-' . $updated_score_value->sjs_score . '<----->' .  $choices_value['value'] . '$$' . $updated_score_value->sjs_value . ' --- '. $choices_value['Standard'] . '<br>';
                          } 
                        }
                    }
                    $score_array[] = $choices_value['Score']; 
                  }
                  $max_score = max($score_array);
                  $new_output_array['max_score'] = $max_score;
                  $array_for_new_scores[] = $new_output_array; 
                } elseif ($elements_value['type'] === 'text') {
                  foreach ($updated_score as $updated_score_value) {
                    if ($updated_score_value->sjs_score == 999.99 && $elements_value['name'].c == $updated_score_value->sjs_name) {
                      $new_output_array_text['comment'] = $updated_score_value->sjs_value;
                    }
                    if ($updated_score_value->sjs_name == $elements_value['name']) {
                      $new_output_array_text['answer'] = $updated_score_value->sjs_value;
                    }
                  }
                  $new_output_array_text['survey_id'] = $id;
                  $new_output_array_text['section'] = $max_score_value['title'];
                  $new_output_array_text['title'] = $elements_value['title'];
                  $new_output_array_text['survey_name'] = $elements_value['name'];
                  //$new_output_array_text['standard'] = $choices_value['Standard'];
                  
                  $array_for_new_scores[] = $new_output_array_text;
                }
              }  
          }
         
         foreach ($updated_score as $max_updated_score_value) {
            if ($max_updated_score_value->sjs_score != '999.99') {
              if($max_updated_score_value->sjs_name !== 'VM.1.6.3' && $max_updated_score_value->sjs_name !== 'VM.1.6.4') {
                $total_updated_score += $max_updated_score_value->sjs_score;
                //echo $max_updated_score_value->sjs_name . ' - ' . $max_updated_score_value->sjs_score . '<br>';
              }  
            }
          }
          //dd($total_updated_score);
           $nonStandardCount = count($getNoComplience);
           $nonIdealCount = count($getIdsForNoideal);
         
          $viewShareVars = array_keys(get_defined_vars());
          //dd($array_for_new_scores);
          return view('admin.pages.dashboard.review.review_survey_intro',compact($viewShareVars));
        }catch(Exception $exception){
          return $exception->getMessage();  
        }         
    }

  /*===============================================================================================  */


  /**
   * Get Json from survey table convert in array format.
   * Get checked box value like question id and get details from survey array and create a another array after that convert array to json with the use of json_encode php function and save in survey_diff_answer table.
   * 
   * 
   * 
   * @param $request. 
   * 
   * @return  entity_id and survey_id to view to manage survey
  */
 

  public function allsurveychecked(Request $request){
    
      try{
        //dd($request);
          
            $manageScore = [];
            $dataElements = [];
            $standardJson = []; 
            $dataElementHtml = [];
            $scoreData =[];
            $user_id = auth()->user()->id;
           //echo $request->custom;
            $customResultget = $request->custom;
             //Getting results table calling getResultDetails method
            $getCustomJson = $this->getResultDetails($request->survey_id, $request->entity_id);
           
       
            
            if(isset($getCustomJson)){
              $i=0;
              $getStringScore = stripslashes($getCustomJson->custom_json);
              $customJson = json_decode($getStringScore, true);
              
              if(!empty($customJson)){
                foreach($customJson as $custKey => $custValue){
                  if(in_array($custValue['name'], $customResultget)){
                    $manageScore['old'][$custValue['name']] = $custValue['Score'];
                  }
                    
                }
              }
            }
          
             if(!empty($manageScore)){
              $serialized_array = serialize($manageScore);
              $query = Results::where(['entity_id' => $request->entity_id, 'survey_id' => $request->survey_id])->update(['manage_score'=> $serialized_array ]);
              
          }   
           //Getting json from survey table calling getSurveyDetails method
            $surveyDetailJson =  $this->getSurveyDetails($request->survey_id);
            $groupJson['json']=$surveyDetailJson->json;           
            
            $groupJson = json_decode($groupJson['json'], true);           
            $top_title = null;
            if(isset($groupJson['title'])){
              $top_title = $groupJson['title'];
            }
            $group = $groupJson['pages'];         
          
            $page_html = 'pagehtml';
            $page_no = 0;
            $page_html_array = [];

            foreach($group as $key => $value) {

              foreach($value['elements'] as $k) {
                if(isset($k['type']) && $k['type'] == 'html'){ 

                  $key_value =  $page_html.$page_no;
                  $page_html_array[$key_value][] = $k;
                 
                } 
               
              } 
              $page_no++;  
                     
            } 

           
            $page_array = [];
            $question_name = null;
            $page_no = 0;
            foreach($group as $key => $value) {

              $page_title = null;
              $page_name = null;

              if(isset($value['title'])){
                $page_title = $value['title'];
              }
              if(isset($value['name'])){
                $page_name = $value['name'];
              }

            
              foreach($value['elements'] as $k) {
                $inner_array = [];
              
              
                if(isset($k['type']) && $k['type'] == 'radiogroup' || $k['type'] == 'text'){ 
                  
                 
                  if($top_title != null){
                    $inner_array['toptitle'] = $top_title; 
                  }
  
                  if($page_title != null){
                    $inner_array['page_title'] = $page_title; 
                  }
  
                  if($page_name != null){
                    $inner_array['page_name'] = $page_name; 
                  }
                 
                  $get_html_value = $page_html.$page_no;
                    if(!empty($page_html_array[$get_html_value])){
                      $inner_array['html'] = $page_html_array[$get_html_value];
                    }
                    if($k['type'] == 'radiogroup' && (!isset($k['visible']))){
                      $inner_array['radiogroup'] = $k;
                    }

                    if($k['type'] == 'text' && (!isset($k['visible'])) && (!isset($value['visible']))){
                      $inner_array['text'] = $k;
                    } 

                    if((!isset($k['visible'])) && (!isset($value['visible']))){
                      $question_name = $k['name'];
                    }
                  
                    if($question_name != null && (!isset($value['visible']))){
                      $page_array[$question_name] = $inner_array;
                    }

                 
                 
                }
                
                if(isset($k['type']) && $k['type'] == 'comment'){
                  $page_array[$question_name]['comment'] = $k;
                 
                } 
                

               
              } 
              $page_no++;  
                     
            }
            
         
          
         
            $countHtml = 0;
           
            foreach($page_array as $dataKey=>$dataValue){
              $element = [];
              if(in_array($dataKey, $customResultget)){
                if(isset($dataValue['toptitle'])){
                  $standardJson['title'] = $dataValue['toptitle'];
                }
                if(isset($dataValue['page_name'])){
                  $element['name'] = $dataValue['page_name'];
                }
                if(isset($dataValue['page_title'])){
                  $element['title'] = $dataValue['page_title'];
                }
                if(isset($dataValue['html'])){
                   $countHtml = count($dataValue['html']);
                  if($countHtml > 0){
                    foreach($dataValue['html'] as $htmlKey => $htmlValue ){
                      $element['elements'][] = $htmlValue;
                    }
                  }
                }
                if(isset($dataValue['radiogroup'])){
                  $element['elements'][] = $dataValue['radiogroup'];
                }
                if(isset($dataValue['comment'])){
                  $element['elements'][] = $dataValue['comment'];
                }
                if(isset($dataValue['text'])){
                  $element['elements'][] = $dataValue['text'];
                }
                $standardJson['pages'][] = $element;
               
              }
              
            }
            
          
           
            $noComplienceJson =  json_encode($standardJson, JSON_PRETTY_PRINT);
              
           
            $noComplienceJson =  str_replace("\\n", "", $noComplienceJson);
                             
                
              $getSurveyData = SurveyDiffAnswer::where([ 'entity_id'=>$request->entity_id, 'survey_id'=>$request->survey_id])->get()->toArray();     
              if(empty($getSurveyData) && count($getSurveyData) === 0 ){        
                
                  $resultsManage = new SurveyDiffAnswer;
                  $resultsManage->survey_id = $request->survey_id;
                  $resultsManage->entity_id = $request->entity_id;
                  $resultsManage->user_id = $user_id;
                  $resultsManage->no_complience = $noComplienceJson;                 
                  $resultsManage->save();
                 
            } 
            else {
                $resultsManage2 = SurveyDiffAnswer::where(['entity_id'=>$request->entity_id, 'survey_id'=>$request->survey_id])->update(['entity_id'=> $request->entity_id, 'survey_id'=>$request->survey_id,'no_complience'=>$noComplienceJson]); 
            }    
                     
            $resultDataAll = ['entity_id'=>$request->entity_id, 'survey_id'=>$request->survey_id];
            //print_r($resultDataAll);
              return  $resultDataAll;
           
          }catch(Exception $exception){
            return $exception->getMessage();  
        }   
     
    }

  /**
   * Get the Json(no_complience) form survey_diff_answers table with the use of where clause 
   * Get data in array format and manage it for the count of click start button
   * 
   * 
   * 
   * @param $request. 
   * 
   * @return  view with the json.
  */  
 
  public function getdatatorun(Request $request){ 
    
    try{
        //dd($request->en);
        $user_id = auth()->user()->id;
        //dd($user_id);
        
        $data = Results::where(['survey_id'=>$request->id,  'entity_id'=>$request->en])->get()->toArray();
        $getCount = $data[0]['change_count']+1;
        $surveyReviewData = SurveyDiffAnswer::select('no_complience')
        ->where(['survey_id'=>$request->id, 'user_id'=>$user_id, 'entity_id'=>$request->en ])->first();
        $surveyCustomStriplash = stripslashes($surveyReviewData); 
        $surveyResults = json_decode($surveyCustomStriplash); 

        if(!empty($surveyResults)){
          Results::where(['survey_id'=>$request->id,  'entity_id'=>$request->en])->update([ 'change_count'=>$getCount]); 
        }
       //dd($surveyResults);
        return view('admin.pages.dashboard.responsibleuser_surveydata.survey_review_run_new', compact($request->id,'surveyResults'));
    }catch(Exception $exception){
      return $exception->getMessage();   
    }   
  }

  /**
   * Get data from results table to check its empty or not with the use of if condition. 
   * Get data form survey_diff_answers table and convert in array format 
   * 
   * 
   * 
   * @param $request
   * @param int $id(survey id) 
   * @param int $en(entity id). 
   * 
   * @return  Array.
  */ 
  public function surveyReviewNew(Request $request, $id,$en)  
  { 
    //dd($request);
     
    try{
          $user_id = auth()->user()->id;         
          $review_data = []; 
          $getPartialSurvey = Results::select('review_json')->where(['survey_id'=>$id,  'entity_id'=> $en])->get()->toArray();   
          $getPartialSurvey = array_column($getPartialSurvey, 'review_json');
       
          if(!empty($getPartialSurvey) && count($getPartialSurvey) > 0 ){
            $review_data['partial_json'] = $getPartialSurvey[0];
          }else{
            $review_data['partial_json'] = 'no data';
          }  
                 
          $data = SurveyDiffAnswer::select('no_complience')->where(['survey_id'=>$id,  'entity_id'=>$en ])->get()->toArray();
        
          $dataAll = array_column($data, 'no_complience');          
          $review_data['non_comp_json'] = $dataAll[0];
         
          return $review_data;
            
      }catch(Exception $exception){
          return $exception->getMessage();
      }         
  
  }

  /**
   * Get data from results table with the status Review_in_progress and Complete_initial.
   * Update data in results table review_json column and change the status Review_in_progress   
   * 
   * 
   * @param $request
   * 
   * @param int $en(entity id). 
   * 
   * @return  insert data in results table.
  */ 
  
  public function partialReviewSurveyNew(Request $request,$en)  
  { 
       
   
    try{
      $user_id = auth()->user()->id; 
      $resultsJson =  str_replace("'","\'",$request->resultsJson);
      $data = Results::where(['survey_id'=>$request->postId, 'status'=>'Review_in_progress',  'entity_id'=>$en])
      ->get()->toArray();
    
      if(!empty($data) && count($data) > 0){
          Results::where(['survey_id'=>$request->postId, 'status'=>'Review_in_progress',  'entity_id'=>$en])
          ->update([ 'review_json'=>$resultsJson]);
      }else{
        $data = Results::where(['survey_id'=>$request->postId, 'status'=>'Complete_initial',  'entity_id'=>$en])->get();
          if(!empty($data) && count($data) > 0){
               Results::where(['survey_id'=>$request->postId, 'status'=>'Complete_initial',  'entity_id'=>$en])
               ->update(['review_json'=>$resultsJson,'status' =>'Review_in_progress']);
             }else {
                  $result = new Results;
                  $result->survey_id = $request->postId;                    
                  $result->review_json = $resultsJson;
                  $result->entity_id = $en;
                  $result->status = 'Review_in_progress';                   
                  $result->save();
             }         
      }
       
      }catch(Exception $exception){
          return $exception->getMessage();
      } 
  }

  /**
   * Get data from results table with the status Review_in_progress and Complete_initial.
   * Update data in results table review_json column and change the status Complete_initial   
   * 
   * 
   * @param $request
   * 
   * @param int $en(entity id). 
   * 
   * @return  insert data in results table and status Complete_initial.
  */ 
  
  public function completeReviewSurvey(Request $request,$en)
  { 
   
    
    try{
        //Getting data from results table calling getResultDetails method
        $dataGetResult =   $this->getResultDetails($request->postId, $en);
      
        $resultsJson =  str_replace("'","\'",$request->resultsJson);
        $data = Results::where(['survey_id'=>$request->postId, /*'status'=>'Review_in_progress',*/ 'entity_id'=>$en])->get();
        if(!empty($data) && count($data) > 0 || !empty($dataGetResult)){
         Results::where(['survey_id'=>$request->postId, /*'status'=>'Review_in_progress',*/ 'entity_id'=>$en])
         ->update(['review_json'=>$resultsJson, 'status' =>'Review_completed']);
      
        }
        else
        {
          $data = Results::where(['survey_id'=>$request->postId ,'status'=>'Complete_initial', 'entity_id'=>$en])->get();
          if(!empty($data) && count($data) > 0 || $dataGetResult){
            Results::where(['survey_id'=>$request->postId, 'status'=>'Complete_initial',  'entity_id'=> $en])->update(['review_json'=>$resultsJson, 'status' =>'Review_completed']);
          }
              
        }
      
      }catch(Exception $exception){
          return $exception->getMessage();
      }
  }

  /**
   * Get data from results table with the status Review_in_progress and Complete_initial.
   * Update data in results table review_json column and change the status Review_in_progress   
   * 
   * 
   * @param $request
   * 
   * @param int $en(entity id). 
   * 
   * @return  insert data in results table.
  */ 

  public function surveyReviewParameters(Request $request,$en) 
  {       
   
    try{
      
      $manageScore = [];
      $customReviewJson =  str_replace("'","\'",$request->customReviewJson);
      Results::where([['survey_id',$request->postId],  ['entity_id', $en]])->update(['partial_data'=>$customReviewJson]);
      
      }catch(Exception $exception){
          return $exception->getMessage(); 
      }
  }

  /**  
  *  Click on submit no changes button on review page status will change as Review_completed and
  *  review_json and review_custom_json column will NULL
  * 
  *  @param  int $survey_id 
  *  @param  int $entity_id
  * 
  *  @return: sucess message
  */ 

    public function getnochange($survey_id, $entity_id){
      try{
        //'status' => 'Complete_initial',
        $getScore = Results::select('inital_score')
        ->where([  'entity_id' => $entity_id, 'survey_id' => $survey_id])->first();

        //'status' => 'Complete_initial',   'review_json' => null, 'review_custom_json' => null, 
        Results::where(['entity_id' => $entity_id, 'survey_id' => $survey_id])
        ->update(['status'=>'Review_completed','current_score' =>$getScore->inital_score ]);


        return redirect('')->with('flash_message', 'Survey completed successfully');
      }catch(Exception $exception){
        return $exception->getMessage();  
      } 
    }


   /**
   * Update current_score column in results table 
   * Preparing array with the use of $page_array(using survey json), $dataResults(getting custom json) and $partialData (getting parital_data from results table) and compare all for make new array to manage on view submission page    
   * 
   * 
   * @param $request
   *   
   * 
   * @return  view.
  */
  
  public function viewSubmission(Request $request)
  {
    try{

          //Getting data from results table calling getResultDetails method
          $data =   $this->getResultDetails($request->id, $request->en);
          //Getting data from entities table calling getEntityDetails method
          $entityName = $this->getEntityDetails($request->en);
          //Getting data from surveys table calling getSurveyDetails method
          $surveyDetailJson = $this->getSurveyDetails($request->id);
          //Getting array data from surveys table calling getSurveyDetailsInArray method
          $page_array = $this->getSurveyDetailsInArray($request->id);
          //dd($page_array);
          //dd($surveyDetailJson);
          
          if(isset($data)){

            if($data->partial_data == NULL && $data->review_json == NULL && $data->review_custom_json == NULL ){
              if($data->current_score == NULL){
                $daata = Results::where(['id' => $row_id, 'status'=>'Review_completed'])
                ->update(['current_score' => $data->inital_score]);
              }

          }

          if($data->partial_data != NULL && $data->manage_score != NULL && $data->review_json != NULL && $data->review_custom_json != NULL){

            if($data->current_score == NULL){
              if(!empty($data->inital_score)){
                 $inital_score = $data->inital_score;
              }else{
                $inital_score = 0;
              }
              $manage_score = unserialize($data->manage_score); 
         
              $manage_old = $manage_score['old'];
               $manage_new = $manage_score['new'];

              if(!empty($manage_old) ){
                $old_score = array_sum($manage_old);
              }else{
                $old_score = 0;
              }
              if(!empty($manage_new)){ 
                $newScoreTable = array_sum($manage_new);
              }else{
                $newScoreTable = 0;
              }
             
              
              $current_score = ($newScoreTable + $inital_score) - $old_score;

              Results::where(['survey_id'=>$request->id,  'entity_id'=>$request->en])
              ->update([ 'current_score'=>$current_score]);
            }
            
          }
          

            $dataCustom = $data->custom_json;
            $dataCustomStriplash = stripslashes($dataCustom); 
            $dataResults = json_decode($dataCustomStriplash, true); 
           // dd($dataResults);
            
            if(!empty($data->partial_data)){
              $getPartial = stripslashes($data->partial_data);
              $partialData = json_decode($getPartial, true);
            }
            //dd($partialData);
          }
         
          if(isset($page_array)){
                foreach($page_array as $scoreValue){
                  $radioGroupData = $scoreValue['radiogroup']['choices'];
                  $a = 0;
              foreach($radioGroupData as $choicesValue){
                if($a < $choicesValue['Score']){
                  $a = $choicesValue['Score'];
                }
              }
              $arr1[] = $a;              
            }
          
           
                $sum = 0;               
                $i=0;
               
                $dataCreatedJson = [];
                $array_details = [];
                foreach($page_array as $nameKey=>$allValues)
                {
                    if(isset($page_array[$nameKey]['radiogroup']['name']))
                    {
                      $name=$page_array[$nameKey]['radiogroup']['name'];
                      $type = $page_array[$nameKey]['radiogroup']['type'];
                      $question=$page_array[$nameKey]['radiogroup']['title'];
                      $choices=$page_array[$nameKey]['radiogroup']['choices'];
                      $section=$allValues['page_title'];
                      $comment=$page_array[$nameKey]['comment'];
                    // echo 'Comment: ' . $comment['value'] . '<br>';
                      
                    } elseif(isset($page_array[$nameKey]['text']['name']))
                    {
                      $name=$page_array[$nameKey]['text']['name'];
                      $type = $page_array[$nameKey]['text']['type'];
                      $question=$page_array[$nameKey]['text']['title'];                            
                      $section=$allValues['page_title'];
                      $comment=$page_array[$nameKey]['comment'];
                      
                    }
                    
                      $array_details['type'] = $type;
                      $array_details['name'] = $name;
                      $array_details['question'] = $question;
                      $array_details['section'] = $section;
                      $array_details['comment'] = '';
                      $array_details['commentname'] = '';
                      $array_details['score'] = '';
                      $array_details['standard'] = '';
                      $array_details['change'] = '';
                      $array_details['stand'] = '';
                      $array_details['reviewCommentvalue'] = '';
                      $array_details['answered'] = '';
                      $array_details['max'] = $arr1[$i];
                     
                      //dd($dataResults);
                    foreach($dataResults as $resultValues)
                    {
                      if ($resultValues['name']==$name /*&& $resultValues['title']==$question*/)
                      {
                      
                        $array_details['name'] = $resultValues['name'];
                        $array_details['question'] = $resultValues['title'];
                        $array_details['score'] = $resultValues['Score'];
                        $array_details['standard'] = $resultValues['Standard'];//
                        $array_details['answered'] = $resultValues['value'];
                      }
                    
                        if($resultValues['name'] == $nameKey.c || $resultValues['name'] == $nameKey.'.c'){

                          $array_details['comment']=$resultValues['value'];
                          $array_details['commentname']=$resultValues['name'];
                        }
                      
                      
                    } 

                    foreach($partialData as $partKey=>$partValue)
                    {
                      if($name == $partValue['name'])
                      {
                          
                          $array_details['stand'] = $partValue['Standard'];
                          $array_details['answered'] = $partValue['value'];
                          if(is_float($partValue['Score'])) {
                            $array_details['change'] = $partValue['Score'];
                          } elseif(!is_float($partValue['Score'])) {
                            $array_details['change'] = $partValue['Score'].'.00';
                          }
                          
                          
                      }
                      if($nameKey.c == $partValue['name'])
                      {
                          $array_details['reviewCommentvalue'] = $partValue['value'];
                      }
                    }
                    $i++;
                    array_push($dataCreatedJson,$array_details);
                    $array_details = [];

                }
          }   
          //dd($dataCreatedJson);
            $viewShareVars = array_keys(get_defined_vars());         
            return view('admin.pages.dashboard.review.view_submission',compact($viewShareVars));
      }catch(Exception $exception){
          return $exception->getMessage();  
        } 
  }
}
