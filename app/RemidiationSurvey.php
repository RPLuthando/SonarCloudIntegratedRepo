<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
class RemidiationSurvey extends Model
{
    protected $table = "remidiation_survey";
    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'id';
    
    public function user_name()
    {
        return $this->belongsTo('\App\User', 'survey_owner_id');      
    }
}
