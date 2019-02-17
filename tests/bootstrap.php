<?php
// tests/bootstrap.php
use Symfony\Component\Dotenv\Dotenv;

if (isset($_ENV['BOOTSTRAP_CLEAR_CACHE_ENV'])) {
// executes the "php bin/console cache:clear" command
    passthru(
        sprintf(
            'APP_ENV=%s php "%s/../bin/console" cache:clear --no-warmup',
            $_ENV['BOOTSTRAP_CLEAR_CACHE_ENV'],
            __DIR__
        )
    );
}
$str = '';
(new Dotenv())->loadEnv(dirname(__DIR__) . '/.env.test');
passthru(
    'php bin/console doctrine:migrations:migrate -n &
php bin/console doctrine:fixtures:load -n', $str
);
echo $str;

require __DIR__ . '/../vendor/autoload.php';