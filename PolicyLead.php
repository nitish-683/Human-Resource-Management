<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class PolicyLead extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'handbook_received',
        'handbook_purpose',
        'candidate_id',
        'policy_clarity',
        'harassment_policy',
        'violation_steps',
        'leave_policy',
        'formal_day',
        'casual_leaves',
        'policies_fair',
        'policy_update',
        'handbook_help',
        'handbook_help_details',
        'accessibility_suggestions'
    ];

    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }
}
