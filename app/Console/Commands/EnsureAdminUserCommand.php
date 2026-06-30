<?php

namespace App\Console\Commands;

use App\Services\AdminUserManager;
use Illuminate\Console\Command;

final class EnsureAdminUserCommand extends Command
{
    protected $signature = 'admin:ensure-user';

    protected $description = 'Create or update the admin user from ADMIN_EMAIL and ADMIN_PASSWORD.';

    public function handle(AdminUserManager $manager): int
    {
        try {
            $user = $manager->ensureFromConfig();
        } catch (\InvalidArgumentException $exception) {
            $this->error($exception->getMessage());

            return self::FAILURE;
        }

        $this->info('Admin user ready: '.$user->email);

        return self::SUCCESS;
    }
}
