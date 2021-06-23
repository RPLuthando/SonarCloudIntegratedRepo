<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SurveyResponse;
use App\SurveyQuestion;
use App\Questionoptions;
use App\Services\SurveysService;
use App\SurveyRemediationResponse;
use Illuminate\Support\Facades\Config;
use DB;
use Redirect;
use Exception;

class QuestionCatController extends Controller
{

    /*  @param $id: get data from survey id
    
        @desription remediation survey response check

        @return to standardcheck page with surveyOptions variable

    */
    public function standardcheck($id){

        try
        {
        	$survey_id = $id;
        	$surveyOptions =  app(SurveysService::class)->fetchSurveyOptions($id);        
        	return view('admin.pages.dashboard.remediation.standardcheck',compact('survey_id'))->with(['surveyOptions'=> $surveyOptions ]);
        } catch (Exception $exception) {
            
            return $exception->getMessage();
           
        } catch (Throwable $exception) {
             return $exception->getMessage();
        }
    }

    /*  @param $request: get data from post 
    
        @desription value of form for column parameters standard

        @return to remsurvey page with questionsOptionsArray and optionsName variable

    */
    public function remsurvey(Request $request){

        try
        {
            $optionsName = $request->survey_option_names;
            $fetchQuestionsId = $request->survey_question_id;
        	$questionsOptionsArray = SurveyQuestion::with('questionoption')->whereIn('id', $fetchQuestionsId )->get();
            
        	return view('admin.pages.dashboard.remediation.remsurvey', compact('questionsOptionsArray','optionsName'));
        } catch (Exception $exception) {
            
            return $exception->getMessage();
           
        } catch (Throwable $exception) {
             return $exception->getMessage();
        }
    }

    /*  @param $request: get data from form 
    
        @desription after multistep remediation

        @return to review response page with survey_id variable

    */
    public function remsurveystep(Request $request){
        
        try
        {

            $array = [];
            $jsonarr= [];  
         	for($i=0; $i<count($request->optionsData); $i++){
         		for($j=0; $j<count($request->optionsData[$i]); $j++){
         			$option_id=$request->optionsData[$i][$j];
         			$question_id=$request->questionData[$i][$j];
                    $purchase=$request->purchase[$i][$j];
         			$period_plan=$request->period_plan[$i][$j];
         			$install=$request->install[$i][$j];
         			$running=$request->running[$i][$j];
                    $start_date= date_create(str_replace('/','-',$request->startdate[$i][$j]));
                    $expr_date = date_create(str_replace('/','-',$request->enddate[$i][$j]));
                    $startdate = date_format($start_date,"Y-m-d");
                    $enddate =  date_format($expr_date,"Y-m-d");         			
         			$array["user_id"]=$request->user_id;
         			$array["survey_id"]=$request->survey_id[$i];
         			$array["owner_id"]= 2;
         			$array["option_id"]=$option_id;
         			$array["question_id"]=$question_id;
                    $array["period_plan"]=$period_plan;
         			$array["purchase"]=$purchase;
         			$array["install"]=$install;
         			$array["running"]=$running;
         			$array["startdate"]=$startdate;
         			$array["enddate"]=$enddate;
         		 array_push($jsonarr, $array);
         		}
         	}

            $response = SurveyRemediationResponse::insert($jsonarr);
            $survey_id = $request->survey_id[0];
            return redirect('/review-response/'. $survey_id.'');

        } catch (Exception $exception) {
            
            return $exception->getMessage();
           
        } catch (Throwable $exception) {
             return $exception->getMessage();
        }
    }

     /*  @param $id: survey response id of survey
    
        @desription use service using survey id

        @return to surveyresponse  page with survey_id variable

    */
    public function reviewResponse($id){

        try
        {

            $survey_id = $id;
            $surveyRemediationArray = app(SurveysService::class)->fetchSurveyRemediation($survey_id);

            return view('admin.pages.dashboard.remediation.surveyresponse',compact('survey_id'))->with(['surveyRemediationArray'=> $surveyRemediationArray ]);

        } catch (Exception $exception) {
            
            return $exception->getMessage();
           
        } catch (Throwable $exception) {
             return $exception->getMessage();
        }
    }

    /*  @param $option_id: options id of question wise, $survey_id: survey id specific
    
        @desription survey remediation response query with relations join with  option of questions 

        @return to response review page

    */
    public function edit_rem_response($option_id, $survey_id){

        try
        {
            $fetchOptionId = $option_id;
            $questionsOptionsArray = SurveyRemediationResponse::with('questionsView')->with('optionDetails')->where('option_id',$option_id)->where('user_id',auth()->user()->id)->get();
            return view('admin.pages.dashboard.remediation.edit_rem_survey', compact('questionsOptionsArray'));

        } catch (Exception $exception) {
            
            return $exception->getMessage();
           
        } catch (Throwable $exception) {
             return $exception->getMessage();
        }
    }

    /*  @param $request: request edit form data 
    
        @desription update edit response of remediation survey  

        @return to response review page

    */
    public function remediation_updated( Request $request ){
        try
        {
            $startdate= date_create(str_replace('/','-',$request->startdate));
            $expr_date = date_create(str_replace('/','-',$request->enddate));

            $surveyRemediationUpdate = SurveyRemediationResponse::find($request->response_id);

            if($startdate && $expr_date){
                $surveyRemediationUpdate->startdate = date_format($startdate,"Y-m-d");
                $surveyRemediationUpdate->enddate =  date_format($expr_date,"Y-m-d");
            }    
             
             $surveyRemediationUpdate->period_plan = $request->period_plan;
             $surveyRemediationUpdate->purchase = $request->purchase;
             $surveyRemediationUpdate->install = $request->install;
             $surveyRemediationUpdate->running = $request->running;

             $surveyRemediationUpdate->update();
             return redirect('/review-response/'.$surveyRemediationUpdate->survey_id.'')->with('flash_message','Value Updated');

        } catch (Exception $exception) {
            
            return $exception->getMessage();
           
        } catch (Throwable $exception) {
             return $exception->getMessage();
        }
    }
    /*  @param $survey_id: survey id 
    
        @desription store data into database for remediation survey  

        @return to survey thanks page

    */
    public function remediation_storage($survey_id){

        try{

            $responses = SurveyRemediationResponse::where('user_id',auth()->user()->id)->where('survey_id',$survey_id)->get();
           
            $ownerId = [];
            foreach($responses as $res){

                array_push($ownerId, $res->owner_id);
                $res->saved=1;
                $res->update();
            }
            $ownerValue = $ownerId['0']; 
            
            DB::table('survey_management_stages')
            ->where('user_id', auth()->user()->id)
            ->where('survey_id', $survey_id)
            ->update(['stage_first' => 3]);
             return view('admin.pages.dashboard.remediation.survey_thanks',compact('ownerValue'));

        } catch (Exception $exception) {
            
            return $exception->getMessage();
           
        } catch (Throwable $exception) {
             return $exception->getMessage();
        }
    }
}