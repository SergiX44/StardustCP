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

    public function parent_domain()
    {
        return $this->hasOne(Domain::class, 'parent_domain', 'id');
    }
}
