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
        // إضافة أعمدة للمستخدمين
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'active')) {
                $table->boolean('active')->default(true)->after('remember_token');
            }
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone')->nullable()->after('email');
            }
            if (!Schema::hasColumn('users', 'avatar')) {
                $table->string('avatar')->nullable()->after('phone');
            }
            if (!Schema::hasColumn('users', 'last_login_at')) {
                $table->timestamp('last_login_at')->nullable()->after('active');
            }
            if (!Schema::hasColumn('users', 'last_login_ip')) {
                $table->ipAddress('last_login_ip')->nullable()->after('last_login_at');
            }
        });

        // إضافة أعمدة للصلاحيات
        if (Schema::hasTable('permissions')) {
            Schema::table('permissions', function (Blueprint $table) {
                if (!Schema::hasColumn('permissions', 'display_name')) {
                    $table->string('display_name')->nullable()->after('name');
                }
                if (!Schema::hasColumn('permissions', 'group')) {
                    $table->string('group')->default('general')->after('display_name');
                }
                if (!Schema::hasColumn('permissions', 'description')) {
                    $table->text('description')->nullable()->after('group');
                }
            });
        }

        // إضافة أعمدة للأدوار
        if (Schema::hasTable('roles')) {
            Schema::table('roles', function (Blueprint $table) {
                if (!Schema::hasColumn('roles', 'display_name')) {
                    $table->string('display_name')->nullable()->after('name');
                }
                if (!Schema::hasColumn('roles', 'description')) {
                    $table->text('description')->nullable()->after('display_name');
                }
                if (!Schema::hasColumn('roles', 'is_active')) {
                    $table->boolean('is_active')->default(true)->after('description');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // حذف الأعمدة المضافة للمستخدمين
        Schema::table('users', function (Blueprint $table) {
            $columns = ['active', 'phone', 'avatar', 'last_login_at', 'last_login_ip'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $table->dropColumn($column);
                }
            }
        });

        // حذف الأعمدة المضافة للصلاحيات
        if (Schema::hasTable('permissions')) {
            Schema::table('permissions', function (Blueprint $table) {
                $columns = ['display_name', 'group', 'description'];
                foreach ($columns as $column) {
                    if (Schema::hasColumn('permissions', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }

        // حذف الأعمدة المضافة للأدوار
        if (Schema::hasTable('roles')) {
            Schema::table('roles', function (Blueprint $table) {
                $columns = ['display_name', 'description', 'is_active'];
                foreach ($columns as $column) {
                    if (Schema::hasColumn('roles', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }
    }
};
