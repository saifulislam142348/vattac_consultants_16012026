<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $fillable = [
        'submission_id',
        'file_name',
        'file_path',
        'file_type',
        'doc_type',
    ];

    public function submission()
    {
        return $this->belongsTo(TaxSubmission::class, 'submission_id');
    }
}
