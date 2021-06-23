<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
// use Spatie\Activitylog\Traits\LogsActivity;
// use Illuminate\Database\Eloquent\SoftDeletes;
use App\SurveyQuestion;
use App\Questionoptions;
class SurveyRemediationResponse extends Model
{
     // use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'survey_remediation_responses';
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
    protected $fillable = ['user_id','survey_id','owner_id','option_id','question_id','period_plan','purchase','install','running','startdate','enddate'];

    /**
     * Change activity log event description
     *
     * @param string $eventName
     *
     * @return string
     */
    public function getDescriptionForEvent($eventName)
    {
        return __CLASS__ . " model has been {$eventName}";
    }
     public function optionDetails(){

        return $this->belongsTo('\App\Questionoptions', 'option_id');
    }
    public function questionsView(){
        return $this->belongsTo('\App\SurveyQuestion', 'question_id' );
    }
    public function survey_category(){
        return $this->belongsTo('\App\RemidiationSurvey','survey_id','id');
    } 
}
