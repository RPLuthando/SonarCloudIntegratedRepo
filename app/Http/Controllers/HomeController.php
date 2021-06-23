<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SurveyService;
use App\QuestionCat;
use App\SurveyRemediationResponse;
use App\SurveyResponse;
use App\SurveyManagementStages;
use App\Survey;
use App\Results;
use Exception;
use Auth;
use App\Reviews;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }
    /**
   * Show the categories list with relations fetched using surveymanagement stages.
   * Manage multiple dashboard 
   * For responsible user dashboard sent data for current survey, to be reviewed and completed section. 
   * 
   * 
   * @return Dashboard view in Array format
  */
   
  // BOM START DATA COLLECTION FOR DASHBOARD
    public function dashboard(){
        try{

            $survey_data_category = QuestionCat::select('id','question_cat_name','survey_type','issued_date','target_date','survey_owner_id')
            ->with('surveyremediation')
            ->get();
            $surveyTiles = QuestionCat::select('id','question_cat_name', 'survey_type', 'issued_date', 'target_date', 'survey_owner_id')
                            ->with('users')
                            ->with('stagesDetails')
                            ->get()
                            ->toArray();

            foreach($survey_data_category as $list){

                $getData[$list['id']] = SurveyResponse::where('survey_id',$list['id'])
                ->join('users', 'survey_response.user_id', 'users.id')
                ->distinct('user_id')
                ->whereNull('users.deleted_at')
                ->count('user_id');

                $countRemediation[$list['id']] = SurveyRemediationResponse::where('survey_id',$list['id'])
                ->join('users', 'survey_remediation_responses.user_id', 'users.id')
                ->distinct('user_id')
                ->whereNull('users.deleted_at')
                ->count('user_id');
            }

            $currentDate = date('Y-m-d H:i:s');
            $presentDate = date("d/m/Y", strtotime($currentDate));

           ///new code 27
          
          
           $entities = [];                      
           $entity_data = \DB::table('entities')
           ->join('assocentities', 'assocentities.entity_id', '=' ,'entities.id')
           ->where([['assocentities.user_id', '=' , Auth::user()->id], ['entities.is_deleted', '=' ,0]])
           ->get(['entities.id','name','colors','group'])
           ->toArray();
           //print_r($entity_data);
           $SurveyData = \DB::table('users as usr')->join('surveys as srv','srv.created_by','=','usr.id')
           ->where(['srv.is_deleted'=>'0'])
           ->select('usr.name as username','srv.created_by','srv.name','srv.id','srv.created_at','srv.deadline','srv.json','srv.group')
           ->get();
           $j = 0; 
           $resultArr = $SurveyData->toArray();
           $b=0;

            if((isset($entity_data) && isset($SurveyData))){
                foreach ($entity_data as $key => $value) {
                    $data = [];  
                    $id = 0;
                    $changesum = 0; 
                    $entities[$j]['id'] = $value->id;
                    $entities[$j]['name'] = $value->name;
                    $entities[$j]['color'] = $value->colors;
                    $entities[$j]['group'] = $value->group;
                    
                    foreach($SurveyData as $key => $srvalue){
                        $data[$id]['username'] = $srvalue->username;
                        $data[$id]['survey_id'] = $srvalue->id;
                        $data[$id]['survey_name'] = $srvalue->name;
                        $data[$id]['created_at'] = $srvalue->created_at;
                        $data[$id]['group'] = $srvalue->group;
                        $data[$id]['deadline'] = date('d M Y',strtotime($srvalue->deadline)); 

                    // Get details from results table with respective entity id and survey id
                        $rest = \DB::table('results')
                        ->select('id','status', 'custom_json','partial_data','updated_at','total_score', 'inital_score','review_score','manage_score','revision_number')
                        ->where(['entity_id' =>$value->id, 'survey_id' =>$srvalue->id ])
                        ->first();              
                        
                       //BOM

                    // Manage the total score with use of results table records    
                        if(isset($rest) && !empty($rest)){
							$data[$id]['result_id'] = $rest->id;
                            $data[$id]['revision_number'] = $rest->revision_number;
                            $revision_nr = $rest->revision_number;
                            $updateTotal = Null;
                            if(($rest->total_score) == 0.00){

                                $dataElements = [];
                                $element = [];
                                
                                if(!empty($srvalue->json)){
                                    $decodedgroupJson = json_decode($srvalue->json, true);
                                    $group = $decodedgroupJson['pages'];
                                    
                                    for($i=0; $i< count($group); $i++){
                                        $arr = $group[$i];                            
                                        $element = $arr['elements'];
                                        foreach($element as $keyy=>$valuees){
                                            $dataElements[$b]['dummy'] = $valuees['choices'];
                                            $b++;
                                        }
                                        
                                    }

                                    $chk = array_column($dataElements, 'dummy');                        
                                    $total = 0;
                                    
                                    for($i=0; $i<(count($chk)); $i++){
                                        $arrTotal = $chk[$i];
                                        $max  = [];
                                        foreach($arrTotal as $keyss=>$valu){
                                        $max [] = $valu['Score'];
                                        }
                                        
                                        $total +=max($max);

                                       
                                    }
                                    $data[$id]['totalScore'] = $total;
                                    $updateTotal = true;
                                }
                            }else{
                                $data[$id]['totalScore']  = $rest->total_score;
                                
                            }

                            $updateIntial = Null;
                            if(($rest->inital_score) == 0.00){
                                $getStringScore = stripslashes($rest->custom_json);
                                $decodedScore = json_decode($getStringScore, true);
                                
                                $sum = 0;                  
                                if(!empty($decodedScore)){
                                    foreach($decodedScore as $keyscore=>$scorevalue){
                                        
                                        if(isset($scorevalue['Score']) || isset($scorevalue['name'])){                                            
                                            $sum +=$scorevalue['Score'];                                    
                                        }
                                    }
                                    $data[$id]['survey_Score'] = $sum;
                                    $updateIntial = true;
                                   
                                }
                            }else{
                                $data[$id]['survey_Score'] = $rest->inital_score;
                            }

                            $updateReview = Null;
                            if(!empty($rest->partial_data)){
                                $scoreCalc = 0;
                                $manage_score = unserialize($rest->manage_score);
                                foreach($manage_score['old'] as $scoreKey=>$scoreValue){
                                   $scoreCalc += $scoreValue;
                                }
                                $data[$id]['old_score'] = $scoreCalc; 
                                if(($rest->review_score) == 0.00){
                                    
                                    $getString = stripslashes($rest->partial_data);                  
                                    $decodedchangeScore = json_decode($getString, true);                                    
                                    $changesum = 0;
                                    if(!empty($decodedchangeScore)){
                                        for($k=0; $k < count($decodedchangeScore); $k++){
                                                if(isset($decodedchangeScore[$k]['Score'])){  
                                                    $changesum +=$decodedchangeScore[$k]['Score'];
                                                    $manage_score['new'][$decodedchangeScore[$k]['name']] = $decodedchangeScore[$k]['Score'];
                                                }
                                        }
                                        $data[$id]['change_score'] = $changesum;

                                        $serialized_manage_score = serialize($manage_score);
                                       
                                        $updateReview = true;  
                                    }
                                }else{
                                    $data[$id]['change_score'] = $rest->review_score;  
                                   
                                }
                            }
                        }
                        
                      
                            
                   
                    
                   
                   //Update results table total score column and initial score

                    if($updateTotal || $updateIntial){
                    
                        $query = Results::where(['entity_id' => $value->id, 'survey_id' => $srvalue->id])
                        ->update(['total_score'=>$data[$id]['totalScore'], 'inital_score'=>$data[$id]['survey_Score'] ]);
                        
                    }

                    if($updateReview){
                       
                        $query2 = Results::where(['entity_id' => $value->id, 'survey_id' => $srvalue->id])
                        ->update(['review_score'=> $data[$id]['change_score'], 'manage_score'=>$serialized_manage_score ]);

                        // calculating current score
                        if($query2){
                            $inital_score = $rest->inital_score;
                            $manage_score = unserialize($rest->manage_score);
                             
                            $manage_old = $manage_score['old'];
                            $manage_new = $data[$id]['change_score'];
                            $old_score = array_sum($manage_old);
                             
                            
                          
                        $current_score = ($manage_new + $inital_score) - $old_score; 

                        // update results table current score
                        if(!empty($current_score)){
                            Results::where(['entity_id' => $value->id, 'survey_id' => $srvalue->id])
                            ->update(['current_score'=>$current_score]);  
                        }
                        
                    
                        }
                        
                    }

                    $completeUpdate =  $rest->updated_at;
                    $data[$id]['update_complete'] = date('d M Y',strtotime($completeUpdate));
                    $res = $rest->status;                  
                    if(!empty($rest) > 0){
                            $data[$id]['survey_status'] = $res;
                        }else{
                            $data[$id]['survey_status']= 'Not Started';
                        }


                        if (isset($revision_nr) && !empty($revision_nr)) {
                            $total_score = 0;
                            $data[$id]['revision_nr'] = $revision_nr;
                            $scores_from_review = \DB::table('to_be_reviewed')
                            ->select('id','revision_number', 'entity_id','survey_id','sjs_score','sjs_name')
                            ->where(['entity_id' => $value->id, 'survey_id' => $srvalue->id, 'revision_number' => $revision_nr ])
                            ->get();
                            foreach ($scores_from_review as $scores_from_review_key => $scores_from_review_value) {
                                if ($scores_from_review_value->entity_id === $value->id && $scores_from_review_value->survey_id === $srvalue->id && $scores_from_review_value->revision_number === $revision_nr && $scores_from_review_value->sjs_score !== '999.99') {
                                    $total_score +=  $scores_from_review_value->sjs_score;
                                    //echo  $scores_from_review_value->sjs_name . ' - ' . $scores_from_review_value->sjs_score . '<br>';
                                }
                            }
                            $data[$id]['sjs_score_total_score'] = number_format($total_score);
                            
                            //dd($scores_from_review);
                            //echo 'Entity: ' . $value->id . ' Survey: ' . $srvalue->id  . ' Revision Number: ' . $revision_nr . '<br>';
                        }

                    $id++;
                   
                    $revision_nr = 0;
                    
                    } //Foreach end
                   
                    
                    $entities[$j]['survey_details'] = $data;

                    $j++;    
                
                }
            }
            
             
             $entities_data = (object) $entities;
            //dd($entities_data);
            //dd($scores_from_review); 
           
        //End 27oct
            $entity_data = \DB::table('entities')->join('assocentities', 'assocentities.entity_id', '=' ,'entities.id')
            ->where([['assocentities.user_id', '=' ,Auth::user()->id]])
            ->get(['name']);
            $getSurveyData = \DB::table('users as usr')->join('surveys as srv','srv.created_by','=','usr.id')
            ->where(['srv.is_deleted'=>'0'])
            ->select('usr.name as username','srv.*')
            ->get();
            
            return view('admin.dashboard', compact('survey_data_category','getData','presentDate','countRemediation', 'surveyTiles','getSurveyData','entities_data','sum'));
        
        }catch(Exception $exception){
                return $exception->getMessage();
            }
        // BOM END DATA COLLECTION FOR DASHBOARD
 
    }

    /**
     * Show the superuser Login and show the survey for assigned surper user.
     *
     * @return App\SurveyQuestion;
     */



    public function superuserSurveydata(){

        try{
            $survey_data_category = \App\SurveyQuestion::with('questionTitles')->where('question_cat_id',1)->select('question_name','id')->get();

            return view('admin.pages.dashboard.superuser_surveydata.survey_data', compact('survey_data_category'));
        }catch(Exception $exception){
            return $exception->getMessage();
        }
    }

    /**
     * Get id and name form survey tables.
     *
     * @return App\Survey;
     */
    public function superuserSurveyadmin(){  
        try{
            $getSurveys = Survey::get(['id','name'])->toArray();           
            return view('admin.pages.dashboard.superuser_surveydata.survey_admin',compact('getSurveys'));
        }catch(Exception $exception){
            return $exception->getMessage();
        }
    }


    /**
     * Show the categories list with relations fetched using surveymanagement stages.
     *
     * @return App\QuestionCat;
     */
    public function reviewsurveynew(){
        $survey_data_category = QuestionCat::select('id','question_cat_name','survey_type','issued_date','target_date','survey_owner_id')
        ->with('surveyremediation')
        ->get();

       
        $surveyTiles = QuestionCat::select('id','question_cat_name', 'survey_type', 'issued_date', 'target_date', 'survey_owner_id')
                        ->with('users')
                        ->with('stagesDetails')
                        ->get()
                        ->toArray();
        
        foreach($survey_data_category as $list){
         
            $getData[$list['id']] = SurveyResponse::where('survey_id',$list['id']) 
            ->join('users', 'survey_response.user_id', 'users.id')
            ->distinct('user_id')
            ->whereNull('users.deleted_at')
            ->count('user_id');
           
            $countRemediation[$list['id']] = SurveyRemediationResponse::where('survey_id',$list['id'])
            ->join('users', 'survey_remediation_responses.user_id', 'users.id')
            ->distinct('user_id')
            ->whereNull('users.deleted_at')
            ->count('user_id');
        }

        $currentDate = date('Y-m-d H:i:s');
        $presentDate = date("d/m/Y", strtotime($currentDate)); 

        return view('admin.dashboardback', compact('survey_data_category','getData','presentDate','countRemediation', 'surveyTiles'));
        
    }


  

   
}
	 