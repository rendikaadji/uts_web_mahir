<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GuardianStudent extends Model
{
    protected $fillable = [
        'guardian_user_id',
        'student_member_id',
    ];

    public function guardian()
    {
        return $this->belongsTo(User::class, 'guardian_user_id');
    }

    public function student()
    {
        return $this->belongsTo(Member::class, 'student_member_id');
    }
}
