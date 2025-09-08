<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentType extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'required'
    ];

    public function candidateDocuments()
    {
        return $this->hasMany(CandidateDocument::class);
    }
}
