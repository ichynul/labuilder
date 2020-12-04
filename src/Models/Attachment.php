<?php

namespace Ichynul\Labuilder\Models;

use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    protected $fillable = ['name', 'admin_id', 'user_id', 'mime', 'suffix', 'size', 'sha1', 'storage', 'url'];
}
