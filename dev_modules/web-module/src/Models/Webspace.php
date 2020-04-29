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
        'php_directives',
        'php_enabled',
        'active',
    ];

    protected $casts = [
        'ssl_enabled' => 'boolean',
        'le_enabled' => 'boolean',
        'php_enabled' => 'boolean',
        'active' => 'boolean',
    ];

    public function setSslEnabledAttribute($value)
    {
        $this->attributes['ssl_enabled'] = $value === 'on';
    }

    public function setLeEnabledAttribute($value)
    {
        $this->attributes['le_enabled'] = $value === 'on';
    }

    public function setPhpEnabledAttribute($value)
    {
        $this->attributes['php_enabled'] = $value === 'on';
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function domain()
    {
        return $this->belongsTo(Domain::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function systemUser()
    {
        return $this->belongsTo(SystemUser::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ipv4()
    {
        return $this->belongsTo(IP::class, 'ipv4_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ipv6()
    {
        return $this->belongsTo(IP::class, 'ipv6_id');
    }
}
