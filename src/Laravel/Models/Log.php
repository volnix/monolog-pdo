<?php

namespace Logger\Laravel\Models;

use Illuminate\Database\Eloquent\Model;


class Log extends Model {

    protected $fillable = [
        'instance',
        'channel',
        'message',
        'level',
        'context'
    ];

    protected $casts = [
        'context' => 'array',
        'extra'   => 'array'
    ];

    public function __construct(array $attributes = array())
    {
        $this->table      = env('DB_LOG_TABLE', 'logs');
        $this->connection = env('DB_LOG_CONNECTION', env('DB_CONNECTION', ''));

        parent::__construct($attributes);
    }
}
