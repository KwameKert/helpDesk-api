<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    //
    protected $fillable = ['cat_id','respondent_id','description','index_no', 'status', 'response'];
}
