<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\RemidiationSurvey;
use App\SurveyRemediationResponse;
use App\QuestionCat;
use App\SurveyQuestion;
use App\SurveyResponse;
use App\Services\SurveysService;
use Exception;
use DB;
class RemediationReportsController extends Controller
{
	 /*
    	Completed survey tab for remediation survey list surveys
    */
    public function reportRemediation($id, $value){
    	
    			$survey_data_list = RemidiationSurvey::select('survey_category_name','survey_type','issued_date','target_date')->where('id',$id)->get();
    			$surveyData = app(SurveysService::class)->surveyRemediationReport($id);
    			$userList = app(SurveysService::class)->surveyStandardUser($id);

	     		return view('admin.pages.dashboard.reports.all_remediation_responses', compact('id','survey_data_list','value','surveyData','userList'));
     	
    }
    /*
    	Completed survey tab for remediation survey for individual standard check surveys
    */
    public function reportIndividual($id, $user_id){

    	$survey_data_list = QuestionCat::where('id',$id)
        ->select('question_cat_name','survey_type','target_date')
        ->get();

        $questionFixture = app(SurveysService::class)->surveyResponseView($id, $user_id);
       
    	$total = $questionFixture->count();

    	$sum =0;
  		foreach($questionFixture as $fixture){
  			$sum += $fixture->score_current;
  		}

    	return view('admin.pages.dashboard.reports.report_individual', compact('id','user_id','survey_data_list','questionFixture','total','sum'));
    }
}
