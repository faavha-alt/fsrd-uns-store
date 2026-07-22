<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('marketplaces', function (Blueprint $table) {
            $table->string('name')->after('id');
            $table->string('url')->after('name');
            $table->string('icon')->nullable()->after('url');
            $table->boolean('is_active')->default(true)->after('icon');
            $table->integer('sort_order')->default(0)->after('is_active');
        });
    }

    public function down(): void
    {
        Schema::table('marketplaces', function (Blueprint $table) {
            $table->dropColumn(['name', 'url', 'icon', 'is_active', 'sort_order']);
        });
    }
};
