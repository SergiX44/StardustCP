<?php

namespace Modules\Web\Models;

use Core\Models\IP;
use Core\Models\SystemUser;
use Illuminate\Database\Eloquent\Model;
use Modules\Domain\Models\Domain;

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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function domain()
    {
        return $this->hasOne(Domain::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function systemUser()
    {
        return $this->hasOne(SystemUser::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function ipv4()
    {
        return $this->hasOne(IP::class, null, 'ipv4_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function ipv6()
    {
        return $this->hasOne(IP::class, null, 'ipv6_id');
    }
}
