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
        Schema::table('articles', function (Blueprint $table) {
            $table->string('url', 2083)->change();
            $table->string('url_to_image', 2083)->change();
        });

        Schema::table('article_sources', function (Blueprint $table) {
            $table->string('url', 2083)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->string('url', 255)->change();
            $table->string('url_to_image', 255)->change();
        });

        Schema::table('article_sources', function (Blueprint $table) {
            $table->string('url', 255)->change();
        });
    }
};
