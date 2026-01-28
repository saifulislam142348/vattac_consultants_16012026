<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaxSubmission extends Model
{
    protected $fillable = [
        'user_id',
        'full_name',
        'phone_number',
        'tin_number',
        'service_type',
        'total_income',
        'status',
        'message',
        'admin_reply',
        'is_phone_verified',
        'service_charge',
        'payment_status',
        'payment_method',
        'vat_month',
        'return_data',
        'calculation_notes',
        'submission_date',
        'nbr_ref_no',
        'missing_documents',
    ];

    protected $casts = [
        'missing_documents' => 'array',
        'is_phone_verified' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class, 'submission_id');
    }
}
