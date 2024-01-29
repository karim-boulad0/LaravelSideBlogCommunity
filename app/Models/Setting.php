<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use phpDocumentor\Reflection\Types\Self_;

class Setting extends Model
{

    use HasFactory;
    protected $fillable = ['title', 'site_name', 'icon', 'phone_number', 'email', 'logo', 'twitter', 'facebook', 'instagram', 'linkedin'];
}
