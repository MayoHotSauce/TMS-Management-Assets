<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class HashPassword extends Command
{
    protected $signature = 'create:hash {password}';
    protected $description = 'Generate a hashed password';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $password = $this->argument('password');
        $hashedPassword = Hash::make($password);
        
        $this->info("Hashed Password: " . $hashedPassword);
    }
}
