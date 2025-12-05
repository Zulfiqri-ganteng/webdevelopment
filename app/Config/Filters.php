<?php

namespace Config;

use CodeIgniter\Config\Filters as BaseFilters;
use CodeIgniter\Filters\CSRF;
use CodeIgniter\Filters\DebugToolbar;
use CodeIgniter\Filters\Honeypot;
use CodeIgniter\Filters\InvalidChars;
use CodeIgniter\Filters\SecureHeaders;
use CodeIgniter\Filters\Cors;
use CodeIgniter\Filters\PageCache;
use CodeIgniter\Filters\PerformanceMetrics;

// Custom Filters
use App\Filters\AuthFilter;
use App\Filters\RoleFilter;
use App\Filters\RemoveIndex;
use App\Filters\ActivityLogger;
use App\Filters\ErrorLogger;
use App\Filters\AbsensiRoleFilter;

class Filters extends BaseFilters
{
    /**
     * Alias untuk memudahkan pemanggilan filter
     */
    public array $aliases = [
        'csrf'          => CSRF::class,
        'toolbar'       => DebugToolbar::class,
        'honeypot'      => Honeypot::class,
        'invalidchars'  => InvalidChars::class,
        'secureheaders' => SecureHeaders::class,
        'cors'          => Cors::class,
        'pagecache'     => PageCache::class,
        'performance'   => PerformanceMetrics::class,

        // Custom filters
        'auth'          => AuthFilter::class,
        'role'          => RoleFilter::class,
        'removeindex'   => RemoveIndex::class,
        'admin'         => RoleFilter::class,
        'absensiRole'   => AbsensiRoleFilter::class,
        'activityLogger' => ActivityLogger::class,
        'errorLogger'   => ErrorLogger::class,
    ];

    /**
     * Filter global (otomatis dijalankan setiap request)
     */
    public array $globals = [
        'before' => [
            // 'removeindex',

            // Auth filter global, tapi halaman login dikecualikan
            'auth' => [
                'except' => [
                    '/',
                    'login',
                    'login/*',
                    'auth/*',
                    'logout',
                    'register-siswa',
                    'register-siswa/*',
                    'forgot-password',
                    'forgot-password/*',
                ],
            ],

            // Aktifkan jika ingin CSRF global
            // 'csrf',
        ],

        'after' => [
            'errorLogger',
            // 'toolbar', // aktifkan jika debugging
        ]
    ];

    /**
     * Filter berdasarkan HTTP Method
     */
    public array $methods = [];

    /**
     * Filter berdasarkan URI
     */
    public array $filters = [

        'auth' => [
            'before' => [
                'dashboard',
                'dashboard/*',
                'siswa',
                'siswa/*',
                'kelas',
                'kelas/*',
                'guru',
                'guru/*',
                'jurusan',
                'jurusan/*',
                'tabungan',
                'tabungan/*',
                'laporan',
                'laporan/*',
                'mapel',
                'mapel/*',
            ],
        ],

        // Role filter (admin/guru/siswa)
        'role' => [
            'before' => [
                'guru',
                'guru/*',
                'siswa',
                'siswa/*',
            ],
        ],
    ];

    /**
     * Required filters
     */
    public array $required = [
        'before' => [],
        'after'  => [],
    ];
}
