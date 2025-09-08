<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CandidateDocument extends Model
{
    use HasFactory;
    protected $fillable = [
        'candidate_id',
        'document_type_id',
        'document_path',
    ];

    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }

    public function documentType()
    {
        return $this->belongsTo(DocumentType::class);
    }
}
