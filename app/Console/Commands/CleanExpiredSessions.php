<?php

namespace App\Console\Commands;

use App\Models\UserDeviceSession;
use Illuminate\Console\Command;
use Laravel\Sanctum\PersonalAccessToken;

class CleanExpiredSessions extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'sessions:clean {--days=30 : Number of days after which sessions are considered expired}';

    /**
     * The console command description.
     */
    protected $description = 'Clean expired device sessions and orphaned tokens';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days = $this->option('days');
        $expiredDate = now()->subDays($days);

        $this->info("Cleaning sessions older than {$days} days...");

        // Clean expired device sessions
        $expiredSessions = UserDeviceSession::where('last_activity_at', '<', $expiredDate)->count();
        UserDeviceSession::where('last_activity_at', '<', $expiredDate)->delete();

        // Clean orphaned tokens (tokens without device sessions)
        $orphanedTokens = PersonalAccessToken::whereNotIn('id', function ($query) {
            $query->select('sanctum_token_id')
                  ->from('user_device_sessions')
                  ->whereNotNull('sanctum_token_id');
        })->where('created_at', '<', $expiredDate)->count();

        PersonalAccessToken::whereNotIn('id', function ($query) {
            $query->select('sanctum_token_id')
                  ->from('user_device_sessions')
                  ->whereNotNull('sanctum_token_id');
        })->where('created_at', '<', $expiredDate)->delete();

        // Clean device sessions without valid tokens
        $orphanedSessions = UserDeviceSession::whereNotIn('sanctum_token_id', function ($query) {
            $query->select('id')->from('personal_access_tokens');
        })->count();

        UserDeviceSession::whereNotIn('sanctum_token_id', function ($query) {
            $query->select('id')->from('personal_access_tokens');
        })->delete();

        $this->info("Cleaned {$expiredSessions} expired sessions");
        $this->info("Cleaned {$orphanedTokens} orphaned tokens");
        $this->info("Cleaned {$orphanedSessions} orphaned sessions");
        
        $this->info('Session cleanup completed successfully!');
    }
}