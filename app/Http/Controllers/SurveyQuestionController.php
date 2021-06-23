<?php

namespace App\Http\Controllers;

use App\QuestionCat;
use App\Questionoptions;
use App\SurveyManagementStages;
use App\SurveyQuestion;
use App\SurveyResponse;
use App\Services\SurveysService;
use App\Survey;
use DB;
use Exception;
use Illuminate\Http\Request;

class SurveyQuestionController extends Controller
{

    /*  @param
    @description: get session data and survey response table
    @return: back page redirection
     */
    public function get_show_response()
    {

        try {
            session_start();
            DB::table('survey_response')->where('user_id', $_SESSION["user_id"])->where('survey_id', $_SESSION["survey_id"])->delete();
            return redirect()->back();
        } catch (Exception $exception) {
            
            return $exception->getMessage();
        }

    }

    /*  @param: $survey_id: survey id of survey list
    @description: get session data and survey response table  and get data from question options table
    @return: back page redirection
     */
    public function final_submit_survey($survey_id)
    {

        try {
            $responses    = SurveyResponse::where('user_id', auth()->user()->id)->where('survey_id', $survey_id)->with('surveyName')->get();
            $surveyStages = new SurveyManagementStages;
            $user         = auth()->user();
            $ownerId      = [];

            foreach ($responses as $res) {

                $res->saved = 1;
                $res->update();
            }

            foreach ($responses as $view) {

                $surveyStages->user_id   = auth()->user()->id;
                $surveyStages->survey_id = $survey_id;
                array_push($ownerId, $view->owner_id);
                $surveyStages->stage_first = '1';
                $surveyStages->save();

            }

            $iD = $ownerId['0'];
            return view('admin.pages.dashboard.survey.survey_thanks', compact('iD'));

        } catch (Exception $exception) {
            
            return $exception->getMessage();
        }

    }

    /*  @param $request: request form data

    @desription update edit response of initial survey

    @return redirect to revie wpage after update 
     */
    public function update_response(Request $request)
    {

        try {
            $surveyResponse            = SurveyResponse::find($request->response_id);
            $surveyResponse->option_id = $request->option_val;
            $other_o                   = "other";
            if (!empty($request->$other_o)) {
                $surveyResponse->other = $request->$other_o;
            }
            $surveyResponse->update();
            return redirect('/goto-response/' . $surveyResponse->survey_id . '')->with('flash_message', 'Value Updated');
        } catch (Exception $exception) {
            
            return $exception->getMessage();
        }

    }

    /*  @param $question_id: question id of survey
    @param $survey_id: survey id of survey

    @description: edit initial survey using survey id

    @return options: question options table
    @return response: response from survey response table
     */

    public function edit_response($question_id, $survey_id)
    {
        try {
            $options  = Questionoptions::where('question_id', $question_id)->get();
            $response = SurveyResponse::where('question_id', $question_id)
                ->where('survey_id', $survey_id)
                ->where('user_id', auth()->user()->id)
                ->first();

            return view('admin.pages.dashboard.survey.edit_survey', compact('options', 'response'));
        } catch (Exception $exception) {
            
            return $exception->getMessage();
        }
    }

    /*
    @param $id: survey id
    @description : response page redirection manipulation using  category id
    @return:  survey view page with survey_id value
     */
    public function goto_response($id)
    {
        try {
            $survey_id     = $id;
            $surveyOptions = app(SurveysService::class)->fetchSurveyOptions($survey_id);
            return view('admin.pages.dashboard.survey.survey_review', compact('survey_id'))->with(['surveyOptions' => $surveyOptions]);
        } catch (Exception $exception) {
            
            return $exception->getMessage();
        }

    }

    /*
    @param $request: get data from form
    @description after click response view get initial survey response get before submit
    @return survey_review page with survey_id variable
     */
    public function show_response(Request $request)
    {

        try {
            $i = 0;
            session_start();
            // Storing session data
            $_SESSION["owner_id"]  = $request->owner_id;
            $_SESSION["survey_id"] = $request->survey_id;
            $_SESSION["user_id"]   = $request->user_id;
            $optionData            = SurveyQuestion::where('question_cat_id', $request->survey_id)->get()->count();

            if ($optionData == count($request->option_id)) {

                foreach ($request->question_id as $arr) {
                    $response              = new SurveyResponse;
                    $response->question_id = $arr;
                    $response->survey_id   = $request->survey_id;
                    $response->option_id   = $request->option_id[$i];
                    $response->user_id     = auth()->user()->id;
                    $response->owner_id    = $request->owner_id;
                    $other_o               = "other" . $arr . '';

                    if (!empty($request->$other_o)) {

                        $response->other = $request->$other_o;
                    }
                    $response->save();                   

                    $i++;

                }
                $survey_id = $request->survey_id;
                return view('admin.pages.dashboard.survey.survey_review', compact('survey_id'));

            } else {

                return redirect()->back();
            }
        } catch (Exception $exception) {
            
            return $exception->getMessage();
        }
    }
    /*
    @param $id: survey id of survey
    @description: initial survey form multistep
    @return: survey_start page with question and categoryname variable
     */
    public function startsurvey($id)
    {

        try {

            $question     = SurveyQuestion::where('question_cat_id', $id)->get();
            $categoryname = QuestionCat::where('id', $id)->get();
            return view('admin.pages.dashboard.survey.survey_start', compact('question', 'categoryname'));

        } catch (Exception $exception) {
            
            return $exception->getMessage();  
        }

    } 
    /*
    @param $id: surveyid of survey
    @desc before start initial survey description page
    @return surveydesc page with variable quest_cat: id of category
     */
    public function surveydesc($id, $entityId)
    {
        try {

           
            $ques_cat = DB::table('users as usr')->join('surveys as srv','srv.created_by','=','usr.id')->where(['srv.id'=>$id])->select('usr.name as username','srv.*')->first();
            $entityDetails = DB::table('entities')->where(['id'=>$entityId])->first();           
            return view('admin.pages.dashboard.survey.surveydesc', compact('ques_cat','entityDetails'));
        } catch (Exception $exception) {
            
            return $exception->getMessage();
        }
    }

    /*
    @param $request: form data get
    @desc thankyou page initial survey but not in use right now for making use it in our app
    @return survey_thanks
     */
    public function survey_thanks(Request $request)
    {
        try {

            $i = 0;
            foreach ($request->question_id as $arr) {
                $response              = new SurveyResponse;
                $response->question_id = $arr;
                $response->option_id   = $request->option_id[$i];
                $response->user_id     = $request->responsible_id;
                $response->owner_id    = $request->owner_id;
                $response->category_id = $request->category_id;
                $response->save();
                $i++;
            }

            return view('admin.pages.dashboard.survey.survey_thanks');

        } catch (Exception $exception) {
            
            return $exception->getMessage();
        }

    }
    

}
