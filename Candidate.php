<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Candidate extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'status',
        'documents_verified', // Added new column here
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
    public function documents()
    {
        return $this->hasMany(CandidateDocument::class, 'candidate_id');
    }
    public function canConvertToEmployee()
    {
        return $this->documents_verified == 1 && !User::where('candidate_id', $this->id)->exists();
    }
}
