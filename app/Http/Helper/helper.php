<?php
use App\User; 
use App\RoleUser;
use App\QuestionCat;
use App\SurveyQuestion;
use App\SurveyResponse;
use App\Questionoptions;
use App\SurveyManagementStages;
//use DB;


function countValues($user_id){

    $countVlaues = SurveyResponse::select('survey_response.user_id', DB::raw('COUNT(question_options.id) AS questions_count'),DB::raw('SUM(question_options.score_current) AS questions_scores'))
            ->join('question_options', 'survey_response.option_id', 'question_options.id')
            ->where('survey_response.user_id', $user_id)
            ->groupBy('survey_response.user_id')
            ->get();
    return $countVlaues;
}

//dashboard cards phase
function checkManageStages($survey_id){
    return SurveyManagementStages::where('user_id', auth()->user()->id)->where('survey_id',$survey_id)->get();
}

    function check_role(){ 
        if(auth()->check()){
            $roleUser = RoleUser::where('user_id',auth()->user()->id)->first();
            if($roleUser){ 
                return $roleUser->role_id;
            }
        }
     
}
function fetchsurveycat_name($id){
    $surveyDetails = QuestionCat::find($id);
    return $surveyDetails;
}
function fetch_option_detail($id){
   $optionDetails =  Questionoptions::find($id);
   return $optionDetails;
}
function fetch_question_detail($id){
    $questionDetail = SurveyQuestion::find($id);
    return $questionDetail;
}
function fetch_response_via_question_id($id){
    $result = SurveyResponse::where('user_id',auth()->user()->id)->where('question_id',$id)->first();
    return $result;
}
function fetch_questions_via_survey_id($id){
    $surveyQuestion  = SurveyQuestion::where('question_cat_id',$id)->get();
    return $surveyQuestion;
}
function fetchUserViaEmail($email){
    $userEmail = User::where('email',$email)->first();
    return $userEmail;
}


function fetch_users_via_role_id($role_id){
    if($role_id!==0){
        $roleUser = RoleUser::select('user_id')->where('role_id',$role_id)->get();
    }else{
        $roleUser = RoleUser::select('user_id')->get();
    }     
    if($roleUser){
        return $roleUser;
    }else{
        return null;
    }

}
/// for adding new user form user based role /////
function fancy_the_role($role_name){
    if($role_name=="super_user"){
        return "Super User";
    }
    if($role_name=="management_user"){
        return "Management User";
    }
    if($role_name=="responsible_user"){
        return "Responsible User";
    }
}
/// for adding new user form user based role //////
function fetch_user_info($user_id){

    $roleUser = User::find($user_id);
    return $roleUser;
}

function fetchsurveycat(){
    $survey_data_category = QuestionCat::all();
    return $survey_data_category;
}
function surveyoptions($response){
    $optionlist = array_values($response);
    $schedulelist = Questionoptions::whereIn('id',$optionlist)
    ->get();
    return $schedulelist;
}

function fetch_options_of_single_question($res){
    
    $questionOption = Questionoptions::where('question_id',$res->question_id)->get();
    return $questionOption;
}
function fetch_question_via_id($id){
    $surveyQuestion = SurveyQuestion::find($id);
    return $surveyQuestion;
}
function fetchviaownerid($id){
    $questionSurvey = QuestionCat::find($id);
    return $questionSurvey;
}

function getOptionsList($id){

    $optionsList = Questionoptions::select('id','question_options','option_type')->where('question_id',$id)->get()->toArray();
    return  $optionsList;

}