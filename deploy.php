<?php

namespace Deployer;

require 'recipe/zend_framework.php';

// Project name
set('application', 'upload-geojson');

// Project repository
set('repository', 'https://github.com/geo6/upload-geojson.git');
set('branch', 'master');

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', true);

// Shared files/dirs between deploys
add('shared_files', [
    'config/autoload/local.php',
]);
add('shared_dirs', []);

// Writable dirs by web server
add('writable_dirs', [
    'data/cache',
    'data/temp',
    'data/upload',
]);
set('writable_mode', 'chown');
set('writable_use_sudo', true);

set('allow_anonymous_stats', false);
set('cleanup_use_sudo', true);

// Files/dirs to be deleted
set('clear_paths', [
    'node_modules',
    'resources',
    'deploy.php',
    'Procfile',
]);
after('deploy:update_code', 'deploy:clear_paths');

// Hosts
inventory('hosts.yml');

// Tasks
task('debug:enable', 'composer run development-enable');
task('debug:disable', 'composer run development-disable');

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');
