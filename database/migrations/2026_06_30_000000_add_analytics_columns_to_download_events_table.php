<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('download_events', function (Blueprint $table): void {
            $table->string('site_locale', 8)->nullable()->after('user_agent');
            $table->string('accept_language', 255)->nullable()->after('site_locale');
            $table->string('browser', 64)->nullable()->after('accept_language');
            $table->string('os', 64)->nullable()->after('browser');
            $table->string('timezone', 64)->nullable()->after('os');
            $table->string('referer', 500)->nullable()->after('timezone');
        });
    }

    public function down(): void
    {
        Schema::table('download_events', function (Blueprint $table): void {
            $table->dropColumn([
                'site_locale',
                'accept_language',
                'browser',
                'os',
                'timezone',
                'referer',
            ]);
        });
    }
};
