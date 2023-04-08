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
        Schema::table('article_sources', function (Blueprint $table) {
            $table->string('identifier_source')->nullable()->after('id');
            $table->text('description')->nullable()->after('name');
            $table->string('language')->nullable()->after('url');
            $table->string('country')->nullable()->after('language');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('article_sources', function (Blueprint $table) {
            $table->dropColumn(['identifier_source', 'description', 'language', 'country']);
        });
    }
};
