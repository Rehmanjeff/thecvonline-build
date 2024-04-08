<?php

namespace App\Models;

use App\Models\Traits\Relations\EmailRelation;
use App\Models\Traits\Scopes\EmailScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    use HasFactory, EmailScope, EmailRelation;

    protected $fillable = [
        'email',
        'requirements',
        'city',
    ];
}
