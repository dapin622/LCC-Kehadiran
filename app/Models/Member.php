<?php

namespace App\Models;

use App\Models\School;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Member extends Model
{
    use HasFactory;

     protected $fillable = [
        'name',
        'nisn',
        'gender',
        'school_id',   
        'team_name',
        'class_name',
        'photo',
        'qr_code',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

}
