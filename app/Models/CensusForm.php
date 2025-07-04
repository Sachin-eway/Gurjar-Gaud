<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CensusForm extends Model
{
    use HasFactory;

    protected $table = 'census_forms';

    protected $fillable = [
        'family_uid',
        'head_name',
        'gender',
        'dob',
        'father_or_husband_name',
        'caste',
        'family_deity',
        'contact_number',
        'bank_account',
        'whatsapp',
        'email',
        'identity_proof',
        'current_address',
        'permanent_address',
        'total_members',
    ];

    // Relationship: A form has many members
    public function members()
    {
        return $this->hasMany(CensusMember::class);
    }
}
