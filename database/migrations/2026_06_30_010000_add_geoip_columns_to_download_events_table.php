<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('download_events', function (Blueprint $table): void {
            $table->string('country_code', 8)->nullable()->after('referer');
            $table->string('country_name', 120)->nullable()->after('country_code');
            $table->string('city', 120)->nullable()->after('country_name');
        });
    }

    public function down(): void
    {
        Schema::table('download_events', function (Blueprint $table): void {
            $table->dropColumn(['country_code', 'country_name', 'city']);
        });
    }
};
