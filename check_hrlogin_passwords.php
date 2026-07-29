<?php

declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Employee;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

$username = 'hrlogin';
echo "Checking credentials for username: {$username}\n";

$employee = Employee::where('username', $username)->first();
if ($employee) {
    echo "\n--- EMPLOYEE RECORD ---\n";
    echo "ID: {$employee->id}\n";
    echo "User ID: {$employee->user_id}\n";
    echo "Password Hash: {$employee->password}\n";
    
    $checkEmp = Hash::check('254032m', $employee->password);
    echo "Does '254032m' match employee password? " . ($checkEmp ? 'YES' : 'NO') . "\n";
    
    $user = User::find($employee->user_id);
    if ($user) {
        echo "\n--- LINKED USER RECORD ---\n";
        echo "ID: {$user->id}\n";
        echo "Username: {$user->username}\n";
        echo "Email: {$user->email}\n";
        echo "Password Hash: {$user->password}\n";
        
        $checkUser = Hash::check('254032m', $user->password);
        echo "Does '254032m' match user password? " . ($checkUser ? 'YES' : 'NO') . "\n";
    } else {
        echo "\nLinked User (ID: {$employee->user_id}) NOT FOUND in users table!\n";
    }
} else {
    echo "Employee with username '{$username}' NOT FOUND!\n";
}
