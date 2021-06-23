<?php
namespace App\Services;

use App\SurveyManagementStages;
use App\SurveyRemediationResponse;
use App\SurveyResponse;
use Exception;

class SurveysService
{
    //initial survey response table fetched
    public function fetchSurveyReview($survey_id)
    {
        try {

            $surveyResponse = SurveyResponse::with('optionDetails')
                ->with('survey_category')
                ->with('questionsView')
                ->where('user_id', auth()->user()->id)
                ->where('survey_id', $survey_id);
        } catch (Exception $exception) {
            return $exception->getMessage();

        }
        return $surveyResponse;
    }
    //initial survey response table fetched management user
    public function fetchSurveyOptions($survey_id)
    {
        try {

            $surveyResponse = SurveyResponse::with('optionDetails')
                ->with('survey_category')
                ->with('questionsView')
                ->where('user_id', auth()->user()->id)
                ->where('survey_id', $survey_id)
                ->get()->sortByDesc(function ($survey_response) {
                return $survey_response->optionDetails->score_current;
            });
        } catch (Exception $exception) {
            return $exception->getMessage();
        }
        return $surveyResponse;
    }

    public function fetchSurveyRemediation($survey_id)
    {
        try {
            return SurveyRemediationResponse::with('optionDetails')->with('survey_category')->with('questionsView')
            ->where('survey_remediation_responses.user_id', auth()->user()->id)
            ->where('survey_id', $survey_id)
            ->get();
        } catch (Exception $exception) {
            return $exception->getMessage();
        }
    }

    public function initialReports($survey_id)
    {
        try {
            return SurveyResponse::where('survey_id', $survey_id)
            ->join('survey_questions', 'survey_response.question_id', 'survey_questions.id')
            ->join('question_options', 'survey_response.option_id', 'question_options.id')
            ->join('users', 'survey_response.user_id', 'users.id')
            ->select('survey_response.question_id', 'survey_questions.question_name', 'survey_response.user_id', 'question_options.question_options', 'question_options.score_current', 'users.name', 'question_options.ideal_standard', 'question_options.acceptable_standard')
            ->whereNull('users.deleted_at')
            ->orderBy('survey_response.user_id')
            ->get();
        } catch (Exception $exception) {
            return $exception->getMessage();
        }
    }

    public function surveyRemediationReport($survey_id)
    {
        try {
            return SurveyRemediationResponse::select('survey_remediation_responses.question_id', 'survey_response.option_id', 'users.name', 'survey_remediation_responses.purchase', 'survey_remediation_responses.install', 'survey_remediation_responses.running', 'survey_remediation_responses.startdate', 'survey_remediation_responses.enddate', 'question_options.question_options', 'question_options.score_current')
            ->join('question_options', 'survey_remediation_responses.option_id', 'question_options.id')
            ->join('users', 'survey_remediation_responses.user_id', 'users.id')
            ->join('survey_response', 'survey_remediation_responses.question_id', 'survey_response.question_id')
            ->where('survey_remediation_responses.survey_id', $survey_id)
            ->whereNull('users.deleted_at')
            ->whereColumn([['survey_remediation_responses.user_id', 'survey_response.user_id']])
            ->get();
        } catch (Exception $exception) {
            return $exception->getMessage();
        }
    }
    
    public function surveyStandardUser($survey_id)
    {
        try {
            $list = SurveyManagementStages::select('users.name', 'users.id')
                ->join('users', 'survey_management_stages.user_id', 'users.id')
                ->where('survey_id', $survey_id)
                ->where('stage_second', '2')
                ->whereNull('users.deleted_at')
                ->get();
            return $list;
        } catch (Exception $exception) {
            return $exception->getMessage();
        }
    }

    public function surveyResponseView($id, $user_id)
    {
        try {
            return SurveyResponse::select('survey_questions.id', 'survey_questions.question_name', 'question_options.question_options', 'question_options.score_current', 'question_options.ideal_standard')
            ->join('question_options', 'survey_response.option_id', 'question_options.id')
            ->join('survey_questions', 'survey_response.question_id', 'survey_questions.id')
            ->join('users', 'survey_response.user_id', 'users.id')
            ->where('question_cat_id', $id)
            ->where('survey_response.user_id', $user_id)
            ->whereNull('users.deleted_at')
            ->get();
        } catch (Exception $exception) {
            return $exception->getMessage();
        }
    }
}
