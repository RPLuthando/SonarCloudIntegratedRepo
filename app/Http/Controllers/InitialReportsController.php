<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\QuestionCat;
use App\SurveyManagementStages;
use App\SurveyResponse;
use App\Services\SurveysService;
use Exception;
use DB;
class InitialReportsController extends Controller
{
	/* 	specific category categorisation card view with 
		reference to specific id of survey with 	
		receipent value 
	*/
     public function initialReports($id, $value){

     	try{
	     	$survey_data_list = QuestionCat::select('id','question_cat_name','survey_type','issued_date','target_date')->where('id',$id)->get();
            $getUserData =  SurveyResponse::where('survey_response.survey_id',$id)
            ->join('users', 'survey_response.user_id', 'users.id')
            ->whereNull('users.deleted_at')
            ->groupBy('survey_response.user_id')
            ->get();

	     	return view('admin.pages.dashboard.reports.active_reports', compact('id','survey_data_list','value','getUserData'));
            
     	}catch(Exception $exception){
            return $exception->getMessage();
        }
     }

     /* display initial surveys responses grid using three table from survey response table
	    main table and referenced to different table with 
	    according to survey id with receipent value 
     */
    public function initialData($id, $value){

     	try{
     		$survey_data_list = QuestionCat::select('question_cat_name','survey_type','issued_date','target_date')->where('id',$id)->get();
     		$surveyData = app(SurveysService::class)->initialReports($id);	     	
	     	return view('admin.pages.dashboard.reports.initial_results', compact('id','survey_data_list','value','surveyData'));
     	}
     	catch(Exception $exception){
            return $exception->getMessage();
        }
    }
    /*
    	Completed survey tab for initial surveys
    */
    public function reportInitial($id, $value){
        
    	try{
    		$survey_data_list = QuestionCat::select('question_cat_name','survey_type','issued_date','target_date')->where('id',$id)->get();
    		$surveyData = app(SurveysService::class)->initialReports($id);
	     	return view('admin.pages.dashboard.reports.all_initial_responses', compact('id','survey_data_list','value','surveyData','surveyDataCount'));
     	}
     	catch(Exception $exception){
            return $exception->getMessage();
        }
    }
    
}
