<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Admin; // **Ensure this is the correct path to your Admin model**
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CreateAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Admin user with name, email, and password.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Creating a new Admin user...');
        $this->newLine();

        // 1. Get Name
        $name = $this->ask('Enter Name');

        // 2. Get and Validate Email
        $email = $this->ask('Enter Email');
        $validator = Validator::make(['email' => $email], [
            'email' => ['required', 'email', 'unique:admins,email'], // Assumes your admin table is 'admins'
        ]);

        while ($validator->fails()) {
            $this->error('The email entered is invalid or already in use.');
            $email = $this->ask('Enter Email');
            $validator = Validator::make(['email' => $email], [
                'email' => ['required', 'email', 'unique:admins,email'],
            ]);
        }

        // 3. Get Password (using secret() to hide input)
        $password = $this->secret('Enter Password (min. 8 characters)');
        $validator = Validator::make(['password' => $password], [
            'password' => ['required', 'min:8'],
        ]);

        while ($validator->fails()) {
            $this->error('The password must be at least 8 characters.');
            $password = $this->secret('Enter Password (min. 8 characters)');
            $validator = Validator::make(['password' => $password], [
                'password' => ['required', 'min:8'],
            ]);
        }

        // 4. Create the Admin User
        try {
            Admin::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($password),
                // Add other necessary fields (e.g., 'is_super_admin' => true)
            ]);

            $this->newLine();
            $this->info("✅ Admin user created successfully!");
            $this->table(
                ['Field', 'Value'],
                [
                    ['Name', $name],
                    ['Email', $email],
                ]
            );

            return Command::SUCCESS;

        } catch (\Exception $e) {
            $this->error("❌ An error occurred while creating the user: " . $e->getMessage());
            return Command::FAILURE;
        }
    }
}