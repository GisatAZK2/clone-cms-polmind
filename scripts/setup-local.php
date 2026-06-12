<?php

function runCommand(string $command): void
{
    echo "Running: $command\n";
    passthru($command, $status);

    if ($status !== 0) {
        throw new RuntimeException("Command failed: $command");
    }
}

$projectRoot = dirname(__DIR__);
$envPath = $projectRoot . '/.env';
$envExamplePath = $projectRoot . '/.env.example';

if (!file_exists($envPath)) {
    if (!copy($envExamplePath, $envPath)) {
        throw new RuntimeException('Unable to copy .env.example to .env');
    }
    echo "Created .env from .env.example\n";
}

$contents = file_get_contents($envPath);
if ($contents === false) {
    throw new RuntimeException('Unable to read .env file');
}

$replacements = [
    '/^#?\s*DB_CONNECTION=.*$/m' => 'DB_CONNECTION=mysql',
    '/^#?\s*DB_HOST=.*$/m' => 'DB_HOST=127.0.0.1',
    '/^#?\s*DB_PORT=.*$/m' => 'DB_PORT=3306',
    '/^#?\s*DB_DATABASE=.*$/m' => 'DB_DATABASE=cms-polmind',
    '/^#?\s*DB_USERNAME=.*$/m' => 'DB_USERNAME=root',
    '/^#?\s*DB_PASSWORD=.*$/m' => 'DB_PASSWORD=',
];

$contents = preg_replace(array_keys($replacements), array_values($replacements), $contents);
if ($contents === null) {
    throw new RuntimeException('Error updating .env content');
}

file_put_contents($envPath, $contents);
echo "Updated .env database settings for local setup\n";

runCommand('php artisan key:generate');

try {
    runCommand('php artisan migrate:fresh --seed');
} catch (RuntimeException $exception) {
    echo "migrate:fresh --seed failed, falling back to migrate --seed\n";
    runCommand('php artisan migrate --seed');
}

runCommand('php artisan storage:link');

echo "Local setup completed successfully.\n";
