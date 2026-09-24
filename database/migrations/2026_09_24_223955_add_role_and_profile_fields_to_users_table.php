<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->unique()->nullable()->after('name');
            $table->string('role')->default('moderator')->index()->after('password');
            $table->string('phone')->nullable()->after('role');
            $table->string('whatsapp_number')->nullable()->after('phone');
            $table->boolean('is_active')->default(true)->index()->after('whatsapp_number');
            $table->timestamp('last_login_at')->nullable()->after('is_active');
            $table->softDeletes()->after('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'deleted_at')) {
                $table->dropSoftDeletes();
            }
            if (Schema::hasColumn('users', 'username')) {
                $table->dropUnique(['username']);
            }
            if (Schema::hasColumn('users', 'role')) {
                $table->dropIndex(['role']);
            }
            if (Schema::hasColumn('users', 'is_active')) {
                $table->dropIndex(['is_active']);
            }
            $columnsToDrop = array_values(array_filter([
                Schema::hasColumn('users', 'username') ? 'username' : null,
                Schema::hasColumn('users', 'role') ? 'role' : null,
                Schema::hasColumn('users', 'phone') ? 'phone' : null,
                Schema::hasColumn('users', 'whatsapp_number') ? 'whatsapp_number' : null,
                Schema::hasColumn('users', 'is_active') ? 'is_active' : null,
                Schema::hasColumn('users', 'last_login_at') ? 'last_login_at' : null,
            ]));

            if (! empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};
