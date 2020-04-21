<?php

namespace Core\Models;

use Illuminate\Database\Eloquent\Model;

class SystemUser extends Model
{

    protected $fillable = [
        'user',
        'group',
        'home_dir',
    ];


    public static function new(string $homeDir)
    {
        $lastId = self::latest()->first()->id ?? 0;
        $lastId++;

        return new self([
            'user' => "wuser$lastId",
            'group' => "wuser$lastId",
            'home_dir' => $homeDir,
        ]);
    }
}
