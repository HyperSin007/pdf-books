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
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('author');
            $table->string('isbn')->nullable();
            $table->date('published_date')->nullable();
            $table->string('language', 5)->default('en');
            $table->integer('pages')->nullable();
            $table->string('file_path'); // Path to the PDF file
            $table->string('cover_image')->nullable(); // Path to cover image
            $table->integer('file_size')->nullable(); // File size in bytes
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->integer('download_count')->default(0);
            $table->decimal('rating', 3, 2)->default(0.00); // Rating out of 5
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
