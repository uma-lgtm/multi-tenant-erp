<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::connection('central')->table('domains', function (Blueprint $table) {
            $table->string('type')->default('subdomain')->after('tenant_id');
            $table->string('verification_status')->default('verified')->after('type');
            $table->timestamp('verified_at')->nullable()->after('verification_status');
        });

        // Backfill existing rows
        DB::connection('central')->table('domains')->update([
            'type' => 'subdomain',
            'verification_status' => 'verified',
            'verified_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::connection('central')->table('domains', function (Blueprint $table) {
            $table->dropColumn(['type', 'verification_status', 'verified_at']);
        });
    }
};
