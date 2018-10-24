# Upload GeoJSON

## Install

```shell
composer create-project geo6/upload-geojson
```

## Authentication

To enable authentication, add the following in your configuration file (usually `config/autoload/local.php`) :

```php
'authentication' => [
    'pdo' => [
        'dsn' => 'pgsql:host=localhost;port=5432;dbname=...',
        'username' => '...',
        'password' => '...',
        'table' => '...',
        'field' => [
            'identity' => '...',
            'password' => '...',
        ],
        'sql_get_roles' => '...',
        'sql_get_details' => '...',
    ],
],
```

See <https://docs.zendframework.com/zend-expressive-authentication/v1/user-repository/#pdo-configuration> for more information !
