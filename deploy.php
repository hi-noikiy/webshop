<?php
namespace Deployer;

require 'recipe/laravel.php';

// Project name
set('application', 'WTG Webshop');

// Project repository
set('repository', 'git@github.com:Wiringa-Technische-Groothandel/webshop');

set('http_user', 'wiringa');

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', true); 

// Shared files/dirs between deploys 
set('shared_files', []);
set('shared_dirs', []);

// Writable dirs by web server 
set('writable_dirs', []);
set('allow_anonymous_stats', false);

set('bin/php', 'cd ~/docker/wiringa.nl && docker-compose exec -T php php');
set('bin/composer', 'composer');

host('artemis')
    ->stage('staging')
    ->set('deploy_path', '/httpdocs');

// Tasks

//task('build', function () {
//    run('cd {{release_path}} && build');
//});

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');

// Copy env file before migration

before('artisan:config:cache', 'deploy:environment');
task('deploy:environment', function () {
    run('cp /config/environment/laravel {{release_path}}/.env');
});

// Migrate database before symlink new release.

before('deploy:symlink', 'artisan:migrate');

