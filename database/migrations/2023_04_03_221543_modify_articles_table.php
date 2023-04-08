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
            $table->text('description')->nullable()->after('title');
            $table->string('url')->nullable()->after('description');
            $table->string('url_to_image')->nullable()->after('url');
            $table->timestamp('published_at')->nullable()->after('url_to_image');
            $table->string('author')->nullable()->after('published_at');
            $table->unsignedBigInteger('category_id')->nullable()->change();

            // Drop the foreign key constraint on the 'category_id' column
            $table->dropForeign(['category_id']);
            // Add a new foreign key constraint on the 'category_id' column referencing the 'categories' table
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->dropColumn(['description', 'url', 'url_to_image', 'published_at', 'author']);
            $table->unsignedBigInteger('category_id')->change();

            $table->dropForeign(['category_id']);
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
        });
    }
};
