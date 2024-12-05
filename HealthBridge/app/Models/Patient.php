<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $table = 'Patient';
    protected $primaryKey = 'PatientID';
    public $timestamps = false;

    protected $fillable = [
        'Pt_Name',
        'DOB',
        'Email',
        'Mailing_address',
        'Ins_member_id',
        'Phone_no',
        'CredentialID',
        'InsuranceID'
    ];

    public static function search($search)
    {
        return empty($search) ? static::query()
            : static::where('Pt_Name', 'like', '%'.$search.'%')
                ->orWhere('Email', 'like', '%'.$search.'%')
                ->orWhere('Ins_member_id', 'like', '%'.$search.'%');
    }
}
