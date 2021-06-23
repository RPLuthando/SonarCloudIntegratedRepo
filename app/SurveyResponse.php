<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SurveyResponse extends Model
{
    protected $table = "survey_response";
    /**
     * The database primary key value.
     *
     * @var string
     */
    protected $primaryKey = 'id';
    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'survey_id', 'question_id', 'option_id', 'owner_id'];

    //surveys service use
    public function optionDetails()
    {
        return $this->belongsTo('\App\Questionoptions', 'option_id')->select('id','question_id','question_options','score_current','ideal_standard','acceptable_standard','option_type');
    }

    //surveysservice  use
    public function questionsView()
    {
        return $this->belongsTo('\App\SurveyQuestion', 'question_id')->select('id','question_cat_id','question_name');
    }

    //survey category use
    public function survey_category()
    {
        return $this->belongsTo('\App\QuestionCat', 'survey_id', 'id')->select('id','question_cat_name', 'survey_type', 'issued_date', 'target_date', 'survey_owner_id');
    }
    ///use in user list fetching in active surveys InitialReportsController
    public function userList()
    {
        return $this->belongsTo('\App\User', 'user_id');
    }
    // SurveyQuestionController use
    public function surveyName()
    {
        return $this->belongsTo('\App\QuestionCat', 'survey_id', 'id')->select('id', 'question_cat_name');
    }

}
