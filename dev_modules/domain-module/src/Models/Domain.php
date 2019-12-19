<?php

namespace Modules\Domain\Models;

use Illuminate\Database\Eloquent\Model;

class Domain extends Model
{
    protected $table = 'domains';

    protected $fillable = [
        'user_id',
        'name',
        'extension',
        'is_sld',
        'parent_domain',
    ];
}
