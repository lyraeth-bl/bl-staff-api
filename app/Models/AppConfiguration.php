<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['android_app_link', 'android_app_version', 'force_app_update', 'app_maintenance'])]
class AppConfiguration extends Model
{
    //
}
