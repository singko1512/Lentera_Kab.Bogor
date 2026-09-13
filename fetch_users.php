<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$users = \App\Models\User::select('id', 'name', 'email', 'username', 'role', 'password')->take(15)->get();

foreach($users as $u) {
    echo "- Role: {$u->role} | Name: {$u->name} | Email: {$u->email} | Username: {$u->username}\n";
}
