<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientProfile extends Model
{
    protected $fillable = [
        'user_id',
        'nid_no',
        'passport_no',
        'bin_no',
        'tin_no',
        'address',
        'business_address',
        'business_name',
        'business_type',
        'vat_circle',
        'vat_zone',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
