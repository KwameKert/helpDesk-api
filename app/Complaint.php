<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    //
    protected $fillable = ['category','respondent_id','description','index_no', 'status', 'response'];
}
