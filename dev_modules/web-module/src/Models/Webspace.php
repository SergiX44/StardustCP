<?php

namespace Modules\Web\Models;

use Illuminate\Database\Eloquent\Model;

class Webspace extends Model
{
    protected $table = 'webspace';

    protected $fillable = [
        'domain_id',
        'system_user_id',
        'ipv4_id',
        'ipv6_id',
        'web_root',
        'document_root',
        'disk_quota',
        'traffic_quota',
        'ssl_enabled',
        'le_enabled',
        'php_open_basedir',
        'php_directives'
    ];
}
