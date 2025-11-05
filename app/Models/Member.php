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
        'class_id',
        'photo',
        'qr_code',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function class()
    {
        return $this->belongsTo(ClassRoom::class, 'class_id');
    }

}
