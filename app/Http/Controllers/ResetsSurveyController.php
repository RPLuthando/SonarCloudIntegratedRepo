<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Results;
use App\Reviews;
use App\ResultsBackup;

class ResetsSurveyController extends Controller
{
    
    public function update(Request $request){  
       //dd($request);
        try{
            foreach ($request->reset as $survey_key => $survey_value) {
                $survey_id = $survey_value;
            
            $entity_id = $request->entity_id;
            //dd($entity_id);
            $results = \DB::table('results')
            ->select('id','status','custom_json','partial_data','updated_at','total_score', 'inital_score','review_score','manage_score','entity_id','survey_id','revision_number')
            ->where('entity_id',$entity_id)
            ->where('survey_id',$survey_id)
            ->get();
            $results_backup = \DB::table('results')
            ->select('survey_id','json','custom_json','review_json','review_custom_json','partial_data','total_score','inital_score','review_score','manage_score','current_score','change_count','res_uid','status','entity_id','revision_number')
            ->where('entity_id',$entity_id)
            ->where('survey_id',$survey_id)
            ->get();
            //dd($results);
            //Backing up
           foreach ($results_backup as $key => $value) {
                ResultsBackup::create([
                    'survey_id'=>$value->survey_id,
                    'json'=>$value->json,         
                    'custom_json'=>$value->custom_json,
                    'review_json'=>$value->review_json,
                    'review_custom_json'=>$value->review_custom_json,
                    'partial_data'=>$value->partial_data,
                    'total_score'=>$value->total_score,
                    'inital_score'=>$value->inital_score,
                    'review_score'=>$value->review_score,
                    'manage_score'=>$value->manage_score,
                    'current_score'=>$value->current_score,
                    'change_count'=>$value->change_count,
                    'res_uid'=>$value->res_uid,
                    'status'=>$value->status,
                    'entity_id'=>$value->entity_id,
                    'revision_number'=>$value->revision_number
                ]);
            }
            //dd($results_backup);
            foreach ($results as $results_value) {
                if ($results_value->revision_number < 1 || $results_value->revision_number === null) {
                    $revision_number = 1;
                    
                } elseif($results_value->revision_number > 0 || $results_value->revision_number != null) {
                    $revision_number = $results_value->revision_number + 1;
                }
                //dd($revision_number);
                //dd($results_value->revision_number);
                $entity_id = $results_value->entity_id;
                $survey_id = $results_value->survey_id;
                $sjs_updated_at = $results_value->updated_at;
                $stripslashes_custom = stripslashes($results_value->custom_json);
                $custom_json_decoded = json_decode($stripslashes_custom, true);
                //dd($custom_json_decoded);
                $stripslashes_partial = stripslashes($results_value->partial_data);
                $partial_data_decoded = json_decode($stripslashes_partial, true);
                //dd($partial_data_decoded);
                //$new = array_combine($custom_json_decoded,$partial_data_decoded );
                //dd($new);
                foreach ($custom_json_decoded as $custom_json_decoded_key => $custom_json_decoded_value) {
                    if (substr($custom_json_decoded_value['name'], -1) !== 'c') {
                        $sjs_name = $custom_json_decoded_value['name'];
                        $sjs_title = $custom_json_decoded_value['title'];
                        $sjs_framework = $custom_json_decoded_value['Framework'];
                        $sjs_value = $custom_json_decoded_value['value'];
                        $sjs_standard = $custom_json_decoded_value['Standard'];
                        if (isset($custom_json_decoded_value['Score'])) {
                            $sjs_score = $custom_json_decoded_value['Score'];
                        } else {
                            $sjs_score = 0.00;
                        }
                    foreach ($partial_data_decoded as $partial_data_decoded_key => $partial_data_decoded_value) {
                        if ($custom_json_decoded_value['name'] === $partial_data_decoded_value['name']) {
                            $sjs_value = $partial_data_decoded_value['value'];
                            $sjs_standard = $partial_data_decoded_value['Standard'];
                            if (isset($partial_data_decoded_value['Score'])) {
                                $sjs_score = $partial_data_decoded_value['Score'];
                            } else {
                                $sjs_score = 0.00;
                            }
                        }
                    }
                    //echo 'revision_number: ' . $revision_number . '<br>entity_id: ' . $entity_id . '<br>survey_id: ' . $survey_id . '<br>sjs_name: ' . $sjs_name . '<br>sjs_title: ' . $sjs_title . '<br>sjs_value: ' . $sjs_value . '<br>sjs_score: ' . $sjs_score . '<br>sjs_comment: ' . $sjs_comment . '<br>sjs_standard: ' . $sjs_standard . '<br>-------------------<br>';
                    //DB
                    Reviews::create(['revision_number'=>$revision_number,'entity_id'=>$entity_id, 'survey_id'=>$survey_id, 
                        'sjs_name'=>$sjs_name, 'sjs_title'=>$sjs_title, 'sjs_value'=>$sjs_value, 
                        'sjs_framework'=>$sjs_framework, 'sjs_score'=>$sjs_score, 'sjs_comment'=>$sjs_comment, 
                        'sjs_standard'=>$sjs_standard, 'sjs_updated_at'=>$sjs_updated_at]);
                    //echo 'revision_number: ' . $revision_number . '<br>entity_id: ' . $entity_id . '<br>survey_id: ' . $survey_id . '<br>sjs_name: ' . $sjs_name . '<br>sjs_title: ' . $sjs_title . '<br>sjs_value: ' . $sjs_value . '<br>sjs_score: ' . $sjs_score . '<br>sjs_comment: ' . $sjs_comment . '<br>sjs_standard: ' . $sjs_standard . '<br>-------------------<br>';
                    } elseif (substr($custom_json_decoded_value['name'], -1) === 'c') {
                        $sjs_name = $custom_json_decoded_value['name'];
                        $sjs_title = $custom_json_decoded_value['title'];
                        $sjs_value = $custom_json_decoded_value['value'];
                        foreach ($partial_data_decoded as $partial_data_decoded_key => $partial_data_decoded_value) {
                            if (isset($partial_data_decoded_value) && $custom_json_decoded_value['name'] === $partial_data_decoded_value['name']) {
                                $sjs_value = $partial_data_decoded_value['value'];
                            }

                        }
                        Reviews::create(['revision_number'=>$revision_number,'entity_id'=>$entity_id, 'survey_id'=>$survey_id, 
                        'sjs_name'=>$sjs_name, 'sjs_title'=>$sjs_title, 'sjs_value'=>$sjs_value, 'sjs_score'=>999.99, 
                        'sjs_standard'=>'commented', 'sjs_updated_at'=>$sjs_updated_at]);
                    }
                }                  
        }  

/////////////////////////////////////////////////////////////////////////

        //dd();
            $results_to_be_reviewed = \DB::table('to_be_reviewed')
            ->select('sjs_name as name', 'sjs_value as value', 'sjs_title as title', 'sjs_standard as Standard', 'sjs_score as Score')
            ->where('entity_id',$entity_id)
            ->where('survey_id', $survey_id)
            ->where('revision_number', $revision_number)
            ->get();
            
            $result_array = [];
            $encoded_result = '';
            
            for ($i=0; $i < count($results_to_be_reviewed); $i++) {
                $result_array[$i]['name'] = $results_to_be_reviewed[$i]->name;
                $result_array[$i]['value'] = $results_to_be_reviewed[$i]->value;
                $result_array[$i]['title'] = $results_to_be_reviewed[$i]->title;
                if ($results_to_be_reviewed[$i]->Score !== 999.99 && $results_to_be_reviewed[$i]->Standard !== 'commented') {
                    $result_array[$i]['Standard'] = $results_to_be_reviewed[$i]->Standard;
                    $result_array[$i]['Score'] = $results_to_be_reviewed[$i]->Score;
                } 
            }
                
            $encoded_result = json_encode($result_array);
            //dd($encoded_result);
           
            Results::where(['entity_id' => $entity_id, 'survey_id' => $survey_id])
            ->update(['custom_json'=>'','partial_data'=>'','status'=>'','revision_number'=>'', 'review_score' => '']);
            Results::where(['entity_id' => $entity_id, 'survey_id' => $survey_id])
            ->update(['custom_json'=>$encoded_result,'partial_data'=>'','status'=>'Complete_initial','revision_number'=>$revision_number]);
        }   
            //Success message
            return back()->with('status', 'Survey successfully added to review!');
        
        } catch(Exception $exception){
            return $exception->getMessage();
        }
    }
}
