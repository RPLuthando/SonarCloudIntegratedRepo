<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reviews extends Model
{
    protected $table = "to_be_reviewed";
    protected $fillable = ['revision_number','entity_id','survey_id','sjs_name','sjs_title','sjs_value','sjs_framework','sjs_score','sjs_comment','sjs_standard','sjs_updated_at'];
    

}
