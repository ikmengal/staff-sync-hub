<?php

use Illuminate\Support\Str;

return [

    /*
    |--------------------------------------------------------------------------
    | Default Database Connection Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the database connections below you wish
    | to use as your default connection for all database work. Of course
    | you may use many connections at once using the Database library.
    |
    */

    'default' => env('DB_CONNECTION', 'mysql'),

    /*
    |--------------------------------------------------------------------------
    | Database Connections
    |--------------------------------------------------------------------------
    |
    | Here are each of the database connections setup for your application.
    | Of course, examples of configuring each database platform that is
    | supported by Laravel is shown below to make development simple.
    |
    |
    | All database work in Laravel is done through the PHP PDO facilities
    | so make sure you have the driver for your particular database of
    | choice installed on your machine before you begin development.
    |
    */

    'connections' => [

        'sqlite' => [
            'driver' => 'sqlite',
            'url' => env('DATABASE_URL'),
            'database' => env('DB_DATABASE', database_path('database.sqlite')),
            'prefix' => '',
            'foreign_key_constraints' => env('DB_FOREIGN_KEYS', true),
        ],

        'mysql' => [
            'driver' => 'mysql',
            'url' => env('DATABASE_URL'),
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '3306'),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            ]) : [],
        ],

        /* HRM-Portals DB-Connection */
        env("CYBERONIX_DB_DATABASE", "cyberonix_portal") => [
            'driver' => 'mysql',
            'url' => env('CYBERONIX_DATABASE_URL'),
            'host' => env('CYBERONIX_DB_HOST', '127.0.0.1'),
            'port' => env('CYBERONIX_DB_PORT', '3306'),
            'database' => env('CYBERONIX_DB_DATABASE', 'forge'),
            'username' => env('CYBERONIX_DB_USERNAME', 'forge'),
            'password' => env('CYBERONIX_DB_PASSWORD', ''),
            'unix_socket' => env('CYBERONIX_DB_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            ]) : [],
        ],
        env("VERTICAL_DB_DATABASE", "hrmsvertical_hr_portal") => [
            'driver' => 'mysql',
            'url' => env('VERTICAL_DATABASE_URL'),
            'host' => env('VERTICAL_DB_HOST', '127.0.0.1'),
            'port' => env('VERTICAL_DB_PORT', '3306'),
            'database' => env('VERTICAL_DB_DATABASE', 'forge'),
            'username' => env('VERTICAL_DB_USERNAME', 'forge'),
            'password' => env('VERTICAL_DB_PASSWORD', ''),
            'unix_socket' => env('VERTICAL_DB_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            ]) : [],
        ],

        env("BRAINCELL_DB_DATABASE", "braincelltech_hr_portal")  => [
            'driver' => 'mysql',
            'url' => env('BRAINCELL_DATABASE_URL'),
            'host' => env('BRAINCELL_DB_HOST', '127.0.0.1'),
            'port' => env('BRAINCELL_DB_PORT', '3306'),
            'database' => env('BRAINCELL_DB_DATABASE', 'forge'),
            'username' => env('BRAINCELL_DB_USERNAME', 'forge'),
            'password' => env('BRAINCELL_DB_PASSWORD', ''),
            'unix_socket' => env('BRAINCELL_DB_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            ]) : [],
        ],
        env("CLEVEL_DB_DATABASE", "clevelhr_hr_portal")  => [
            'driver' => 'mysql',
            'url' => env('CLEVEL_DATABASE_URL'),
            'host' => env('CLEVEL_DB_HOST', '127.0.0.1'),
            'port' => env('CLEVEL_DB_PORT', '3306'),
            'database' => env('CLEVEL_DB_DATABASE', 'forge'),
            'username' => env('CLEVEL_DB_USERNAME', 'forge'),
            'password' => env('CLEVEL_DB_PASSWORD', ''),
            'unix_socket' => env('CLEVEL_DB_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            ]) : [],
        ],
        env("DELVE12_DB_DATABASE", "delve12_hr_portal")  =>  [
            'driver' => 'mysql',
            'url' => env('DELVE12_DATABASE_URL'),
            'host' => env('DELVE12_DB_HOST', '127.0.0.1'),
            'port' => env('DELVE12_DB_PORT', '3306'),
            'database' => env('DELVE12_DB_DATABASE', 'forge'),
            'username' => env('DELVE12_DB_USERNAME', 'forge'),
            'password' => env('DELVE12_DB_PASSWORD', ''),
            'unix_socket' => env('DELVE12_DB_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            ]) : [],
        ],
        env("HORIZONTAL_DB_DATABASE", "techhorizontal_hr_portal")  => [
            'driver' => 'mysql',
            'url' => env('HORIZONTAL_DATABASE_URL'),
            'host' => env('HORIZONTAL_DB_HOST', '127.0.0.1'),
            'port' => env('HORIZONTAL_DB_PORT', '3306'),
            'database' => env('HORIZONTAL_DB_DATABASE', 'forge'),
            'username' => env('HORIZONTAL_DB_USERNAME', 'forge'),
            'password' => env('HORIZONTAL_DB_PASSWORD', ''),
            'unix_socket' => env('HORIZONTAL_DB_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            ]) : [],
        ],
        env("MERCURY_DB_DATABASE", "mercuryhrms_hr_portal")   => [
            'driver' => 'mysql',
            'url' => env('MERCURY_DATABASE_URL'),
            'host' => env('MERCURY_DB_HOST', '127.0.0.1'),
            'port' => env('MERCURY_DB_PORT', '3306'),
            'database' => env('MERCURY_DB_DATABASE', 'forge'),
            'username' => env('MERCURY_DB_USERNAME', 'forge'),
            'password' => env('MERCURY_DB_PASSWORD', ''),
            'unix_socket' => env('MERCURY_DB_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            ]) : [],
        ],
        env("MOMYOM_DB_DATABASE", "momyom_hrms")   => [
            'driver' => 'mysql',
            'url' => env('MOMYOM_DATABASE_URL'),
            'host' => env('MOMYOM_DB_HOST', '127.0.0.1'),
            'port' => env('MOMYOM_DB_PORT', '3306'),
            'database' => env('MOMYOM_DB_DATABASE', 'forge'),
            'username' => env('MOMYOM_DB_USERNAME', 'forge'),
            'password' => env('MOMYOM_DB_PASSWORD', ''),
            'unix_socket' => env('MOMYOM_DB_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            ]) : [],
        ],
        env("SOFTNOVA_DB_DATABASE", "softnova_hr_portal")  => [
            'driver' => 'mysql',
            'url' => env('SOFTNOVA_DATABASE_URL'),
            'host' => env('SOFTNOVA_DB_HOST', '127.0.0.1'),
            'port' => env('SOFTNOVA_DB_PORT', '3306'),
            'database' => env('SOFTNOVA_DB_DATABASE', 'forge'),
            'username' => env('SOFTNOVA_DB_USERNAME', 'forge'),
            'password' => env('SOFTNOVA_DB_PASSWORD', ''),
            'unix_socket' => env('SOFTNOVA_DB_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            ]) : [],
        ],
        env("SOFTFELLOW_DB_DATABASE", "softfellow_hr_portal")  => [
            'driver' => 'mysql',
            'url' => env('SOFTFELLOW_DATABASE_URL'),
            'host' => env('SOFTFELLOW_DB_HOST', '127.0.0.1'),
            'port' => env('SOFTFELLOW_DB_PORT', '3306'),
            'database' => env('SOFTFELLOW_DB_DATABASE', 'forge'),
            'username' => env('SOFTFELLOW_DB_USERNAME', 'forge'),
            'password' => env('SOFTFELLOW_DB_PASSWORD', ''),
            'unix_socket' => env('SOFTFELLOW_DB_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            ]) : [],
        ],
        env("SWYFTCUBE_DB_DATABASE", "swyftcube_hr_portal")  => [
            'driver' => 'mysql',
            'url' => env('SWYFTCUBE_DATABASE_URL'),
            'host' => env('SWYFTCUBE_DB_HOST', '127.0.0.1'),
            'port' => env('SWYFTCUBE_DB_PORT', '3306'),
            'database' => env('SWYFTCUBE_DB_DATABASE', 'forge'),
            'username' => env('SWYFTCUBE_DB_USERNAME', 'forge'),
            'password' => env('SWYFTCUBE_DB_PASSWORD', ''),
            'unix_socket' => env('SWYFTCUBE_DB_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            ]) : [],
        ],
        env("SWYFTZONE_DB_DATABASE", "swyftzone_hr_portal") => [ // currently not in used
            'driver' => 'mysql',
            'url' => env('SWYFTZONE_DATABASE_URL'),
            'host' => env('SWYFTZONE_DB_HOST', '127.0.0.1'),
            'port' => env('SWYFTZONE_DB_PORT', '3306'),
            'database' => env('SWYFTZONE_DB_DATABASE', 'forge'),
            'username' => env('SWYFTZONE_DB_USERNAME', 'forge'),
            'password' => env('SWYFTZONE_DB_PASSWORD', ''),
            'unix_socket' => env('SWYFTZONE_DB_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            ]) : [],
        ],
        env("TECHCOMRADE_DB_DATABASE", "techcomrade_hr_portal")   => [
            'driver' => 'mysql',
            'url' => env('TECHCOMRADE_DATABASE_URL'),
            'host' => env('TECHCOMRADE_DB_HOST', '127.0.0.1'),
            'port' => env('TECHCOMRADE_DB_PORT', '3306'),
            'database' => env('TECHCOMRADE_DB_DATABASE', 'forge'),
            'username' => env('TECHCOMRADE_DB_USERNAME', 'forge'),
            'password' => env('TECHCOMRADE_DB_PASSWORD', ''),
            'unix_socket' => env('TECHCOMRADE_DB_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            ]) : [],
        ],
        env("ROCKETFLARELABS_DB_DATABASE", "rocketflarelabs_hr_portal")   => [
            'driver' => 'mysql',
            'url' => env('ROCKETFLARELABS_DATABASE_URL'),
            'host' => env('ROCKETFLARELABS_DB_HOST', '127.0.0.1'),
            'port' => env('ROCKETFLARELABS_DB_PORT', '3306'),
            'database' => env('ROCKETFLARELABS_DB_DATABASE', 'forge'),
            'username' => env('ROCKETFLARELABS_DB_USERNAME', 'forge'),
            'password' => env('ROCKETFLARELABS_DB_PASSWORD', ''),
            'unix_socket' => env('ROCKETFLARELABS_DB_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            ]) : [],
        ],
        /* HRM-Portals DB-Connection */

        'pgsql' => [
            'driver' => 'pgsql',
            'url' => env('DATABASE_URL'),
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '5432'),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
            'search_path' => 'public',
            'sslmode' => 'prefer',
        ],

        'sqlsrv' => [
            'driver' => 'sqlsrv',
            'url' => env('DATABASE_URL'),
            'host' => env('DB_HOST', 'localhost'),
            'port' => env('DB_PORT', '1433'),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
            // 'encrypt' => env('DB_ENCRYPT', 'yes'),
            // 'trust_server_certificate' => env('DB_TRUST_SERVER_CERTIFICATE', 'false'),
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Migration Repository Table
    |--------------------------------------------------------------------------
    |
    | This table keeps track of all the migrations that have already run for
    | your application. Using this information, we can determine which of
    | the migrations on disk haven't actually been run in the database.
    |
    */

    'migrations' => 'migrations',

    /*
    |--------------------------------------------------------------------------
    | Redis Databases
    |--------------------------------------------------------------------------
    |
    | Redis is an open source, fast, and advanced key-value store that also
    | provides a richer body of commands than a typical key-value system
    | such as APC or Memcached. Laravel makes it easy to dig right in.
    |
    */

    'redis' => [

        'client' => env('REDIS_CLIENT', 'phpredis'),

        'options' => [
            'cluster' => env('REDIS_CLUSTER', 'redis'),
            'prefix' => env('REDIS_PREFIX', Str::slug(env('APP_NAME', 'laravel'), '_') . '_database_'),
        ],

        'default' => [
            'url' => env('REDIS_URL'),
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'username' => env('REDIS_USERNAME'),
            'password' => env('REDIS_PASSWORD'),
            'port' => env('REDIS_PORT', '6379'),
            'database' => env('REDIS_DB', '0'),
        ],

        'cache' => [
            'url' => env('REDIS_URL'),
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'username' => env('REDIS_USERNAME'),
            'password' => env('REDIS_PASSWORD'),
            'port' => env('REDIS_PORT', '6379'),
            'database' => env('REDIS_CACHE_DB', '1'),
        ],

    ],
];
