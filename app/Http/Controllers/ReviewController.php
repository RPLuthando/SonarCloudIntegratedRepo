<?php

namespace App\Http\Controllers;

use App\QuestionCat;
use App\Questionoptions;
use App\Services\SurveysService;
use App\SurveyManagementStages;
use App\SurveyResponse;
use App\User;
use DB;
use Exception;
use Illuminate\Http\Request;
use Session;
use Carbon\Carbon;
use App\ReviewHistory;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
class ReviewController extends Controller
{
    /*  @param $id
        @description: get updation date of get response regards with specific user logged in way
        @return: values to review survey intro function
     */
    private function questionsCount($id)
    {
        try {

            $select = \DB::raw('COUNT(CASE
					WHEN question_options.ideal_standard = "Yes" THEN question_options.id
					ELSE NULL END) AS perfect, COUNT(CASE
					WHEN question_options.acceptable_standard = "Yes" THEN question_options.id
					ELSE NULL END) AS accept, COUNT(CASE
					WHEN question_options.ideal_standard = "No" AND question_options.acceptable_standard = "No" THEN question_options.id
					ELSE NULL END) AS noncompliant');

            $surveyQuestionsCount = SurveyResponse::select($select)
                ->join('question_options', 'survey_response.option_id', 'question_options.id')
                ->where('survey_response.user_id', auth()->user()->id)
                ->where('survey_response.survey_id', $id)
                ->orderBy('survey_response.option_id', 'DESC')
                ->get()
                ->toArray();

            $totalQuestionCount = array_sum(array_flatten($surveyQuestionsCount));
            $countIndividual    = $surveyQuestionsCount;

            $selects = \DB::raw('(CASE
					WHEN question_options.ideal_standard = "Yes" THEN question_options.id
					ELSE NULL END) AS allresponse, (CASE
					WHEN question_options.acceptable_standard = "Yes" THEN question_options.id
					ELSE NULL END) AS nonideal, (CASE
					WHEN question_options.ideal_standard = "No" AND question_options.acceptable_standard = "No" THEN question_options.id
					ELSE NULL END) AS noncompliant');
            $surveyQuestionsCounts = SurveyResponse::select($selects)
                ->join('question_options', 'survey_response.option_id', 'question_options.id')
                ->where('survey_response.user_id', auth()->user()->id)
                ->where('survey_response.survey_id', $id)
                ->get()
                ->toArray();

            $idealResponses = array_values(array_filter(array_pluck($surveyQuestionsCounts, 'allresponse')));
            $acceptable     = array_values(array_filter(array_pluck($surveyQuestionsCounts, 'nonideal')));
            $nonCompliant   = array_values(array_filter(array_pluck($surveyQuestionsCounts, 'noncompliant')));
            $nonIdeal       = array_merge_recursive($acceptable, $nonCompliant);
            $allResponses   = array_merge_recursive($idealResponses, $nonIdeal);

        } catch (Exception $exception) {

            return $exception->getMessage();
        }
        return compact('totalQuestionCount', 'countIndividual', 'idealResponses', 'nonIdeal', 'nonCompliant', 'allResponses');
    }

    /*  @param $id
        @description: get updation date of get response regards with specific user logged in way
        @return: values to review survey intro function
     */
    private function previousResponses($id)
    {

        try {

            $updateInstance = SurveyManagementStages::select('updated_at')
                ->where('survey_id', $id)
                ->where('user_id', auth()->user()->id)
                ->get()
                ->toArray();
            $arrays = array_pluck($updateInstance, 'updated_at');

        } catch (Exception $exception) {

            return $exception->getMessage();
        }
        return $arrays;
    }

    /*  @param $id
        @description: get values from survey list with regards to specific id  and get user name from user list
        @return: surveyData and variable data
     */
    private function surveyLists($id)
    {
        try {

            $surveyData = QuestionCat::select('issued_date', 'target_date', 'survey_owner_id')->find($id);
            $userName   = User::select('name')->find($surveyData->survey_owner_id);
            $nameUser   = $userName->name;
        } catch (Exception $exception) {

            return $exception->getMessage();
        }
        return compact('surveyData', 'nameUser');

    }

    /*  @param $id
        @description: get surveyLists function invoked
        @return: page redirect to review survey intro and with different values
     */
    public function review_survey_intro()
    {
       // dd('hello');
        // try {

        //     $resultant         = $this->surveyLists($id);
        //     $getResponses      = $this->previousResponses($id);
        //     $getQuestionsCount = $this->questionsCount($id);

        //     $surveyData = $resultant['surveyData'];
        //     $nameUser   = $resultant['nameUser'];

        //     $lastKey   = array_values($getResponses);
        //     $lastValue = end($lastKey);

        //     $totalCount        = $getQuestionsCount['totalQuestionCount'];
        //     $getStandardCounts = $getQuestionsCount['countIndividual']['0'];

        //     $idealResponses = $getQuestionsCount['idealResponses'];
        //     //nonideal all second button response
        //     $nonIdeal       = $getQuestionsCount['nonIdeal'];
        //     //all non-compliant third button response
        //     $nonCompliant   = $getQuestionsCount['nonCompliant'];
        //     //non-ideal responses all button response
        //     $allResponses   = $getQuestionsCount['allResponses'];                     
        // } catch (Exception $exception) {

        //     return $exception->getMessage();
        // }
        // return view('admin.pages.dashboard.review.review_survey_intro', compact('id', 'surveyData', 'nameUser', 'getResponses', 'lastValue', 'getStandardCounts', 'totalCount', 'idealResponses', 'nonIdeal', 'nonCompliant', 'allResponses'));
        return view('admin.pages.dashboard.review.review_survey_intro');
    }

    /*  @param $id, $obj
        @description: get options with question function invoked
        @return: page redirect to review_steps and with different values
     */
    public function review_change($id, $obj)
    {
        try {

             $decodedOptionsValues = Crypt::decryptString($obj);
             
            $decodedOptionsValue = json_decode($decodedOptionsValues);
            $questionOption     = Questionoptions::select('id', 'question_id', 'question_options', 'ideal_standard', 'acceptable_standard')
                ->with('questionsGet')
                ->with('responseDate')
                ->whereIn('id', $decodedOptionsValue);
            $questionOptions =   $questionOption->get()->toArray();
            $questionsId = $questionOption->pluck('question_id');
            Session::put('questionsId', $questionsId); 

        } catch (Exception $exception) {

            return $exception->getMessage();
        }
        return view('admin.pages.dashboard.review.review_steps', compact('id', 'questionOptions','questionsId'));
    }

    /*  @param $request, $obj
        @description: get all value and make edit function as an array
        @return: page redirect to update response review page
     */

    public function update_review_results(Request $request)
    {

        try {
            
            $results     = array();
            $optionNames = $request->optionnames;           
            $input      = collect(request()->other)->filter()->all();
            $totalCount = $request->total_questions;

            foreach ($optionNames as $key => $options) {
                if (!isset($input[$key])) {
                    $results[$key] = '';
                } else {
                    $results[$key] = $input[$key];
                }

                $values = [
                    'option_id' => $options,
                    'other'     => $results[$key],
                ];

                $optionData = SurveyResponse::select('option_id')
                    ->where('survey_id', $request->survey_id)
                    ->where('user_id', auth()->user()->id)
                    ->whereIn('question_id', [$key])
                    ->update($values);
            }

        } catch (Exception $exception) {

            return $exception->getMessage();
        }
        return redirect()->to('/review-check/' . $request->survey_id);

    }

    /*  @param $id
        @description: get all value and make edit function as an array
        @return: page redirect to review list page review
     */
    public function review_check($id)
    {

        try {
            if(Session::has('questionsId')) {
                $viewModelData = Session::get('questionsId');
                
            }
           
            $survey_id     = $id;
            $surveyOptions = app(SurveysService::class)->fetchSurveyReview($survey_id)
            ->whereIn('question_id',$viewModelData)->get();
            $firstKey = $surveyOptions->first();
       
            $ownerId = $firstKey->owner_id;
           
        } catch (Exception $exception) {

            return $exception->getMessage();

        }
        return view('admin.pages.dashboard.review.review_list', compact('survey_id','ownerId'))->with(['surveyOptions' => $surveyOptions]);
    }

        /*  @param $id
            @description: get all value and make edit function as an array with perfect standard
            @return: page redirect to review list page review
        */
    public function review_check_correct($id){
         try {

                $survey_id     = $id;
                $surveyOptions = app(SurveysService::class)->fetchSurveyReview($survey_id)
                ->get();
                $firstKey = $surveyOptions->first();
                $ownerId = $firstKey->owner_id;

            } catch (Exception $exception) {

            return $exception->getMessage();

        }
        return view('admin.pages.dashboard.review.review_list', compact('survey_id','ownerId'))->with(['surveyOptions' => $surveyOptions]);
    }

    /*   @param $question_id, $survey_id
        @description: get all value and make edit function as an array
        @return: page redirect to edit_review_form page review
     */
    public function edit_review_response($question_id, $survey_id)
    {

        try {

            $surveyOptions = app(SurveysService::class)->fetchSurveyReview($survey_id)->where('question_id', $question_id)->first();
            
            $savedValues = Questionoptions::select('id', 'question_id', 'question_options', 'score_current', 'ideal_standard', 'acceptable_standard', 'option_type')->where('question_id', $question_id)->get();

            return view('admin.pages.dashboard.review.edit_review_form', compact('savedValues', 'surveyOptions', 'question_id', 'survey_id'));

        } catch (Exception $exception) { 
            return $exception->getMessage();

        }

    }

    /*  @param $request: request form data

        @desription update edit response of initial survey

        @return to review page before submit
     */
    public function update_response_review(Request $request)
    {

        try {
            $surveyResponse            = SurveyResponse::find($request->response_id);
            $surveyResponse->option_id = $request->option_val;
            $other_o                   = "other";
            if (!empty($request->$other_o)) {
                $surveyResponse->other = $request->$other_o;
            }
            $surveyResponse->update();
            return redirect('/review-check/' . $surveyResponse->survey_id . '')->with('info', 'Value Updated!!');
           

        } catch (Exception $exception) {

            return $exception->getMessage();

        }
    }

    /*  @param $id: owner id
        @desription  value of owner id passed

        @return to thank you page 
    */

    public function thanks_page( $ownerId = NULL, $survey_id = NULL )
    {
        
        try {
            $mytime = Carbon::now();

            $date_updated = $mytime->toDateString();
            
            $user_id = auth()->user()->id;
            $survey_id =$survey_id;
            $optionData = ReviewHistory::create(['user_id' => $user_id, 
                'response_date' => $date_updated,
                'survey_id' => $survey_id

            ]);
            $optionData->save();

            $iD = $ownerId;
            return view('admin.pages.dashboard.survey.survey_thanks', compact('iD'));
        } catch (Exception $exception) {
            return $exception->getMessage();
        }
    }

}
