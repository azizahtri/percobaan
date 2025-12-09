<?php
namespace Config;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Filters\CSRF;
use CodeIgniter\Filters\DebugToolbar;
use CodeIgniter\Filters\Honeypot;
use CodeIgniter\Filters\InvalidChars;
use CodeIgniter\Filters\SecureHeaders;

class Filters extends BaseConfig
{
    public $aliases = [
    'csrf'     => \CodeIgniter\Filters\CSRF::class,
    'toolbar'  => \CodeIgniter\Filters\DebugToolbar::class,
    'honeypot' => \CodeIgniter\Filters\Honeypot::class,
    'auth'     => \App\Filters\AuthFilter::class,
];

    public $globals = [
        'before' => [
            // 'auth',  <-- JANGAN ADA INI DISINI! HARUS KOSONG ATAU KOMENTAR
            // 'csrf',
        ],
        'after' => [
            'toolbar',
        ],
    ];

    public $filters = [];
    
}
