<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobPost extends Model
{
    use HasFactory;

    // Define the table name if it's not the plural form of the model name
    protected $table = 'job_postings';

    // Define the primary key if it's not 'id'
    protected $primaryKey = 'id';

    // If you want Eloquent to manage timestamps
    public $timestamps = true;

    // Define the attributes that are mass assignable
    protected $fillable = [
        'job_title', 
        'company_name', 
        'job_description', 
        'job_type', 
        'job_location', 
        'job_deadline', 
        'image_path', 
        'user_id', 
        'uploader_name'
    ];

    // Define the relationship between JobPost and User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
