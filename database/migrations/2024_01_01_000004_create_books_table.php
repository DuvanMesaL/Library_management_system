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
            $table->string('author');
            $table->string('isbn')->unique()->nullable();
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('set null');
            $table->text('description')->nullable();
            $table->integer('publication_year')->nullable();
            $table->string('publisher')->nullable();
            $table->integer('pages')->nullable();
            $table->string('language')->default('Español');
            $table->integer('copies_total')->default(1);
            $table->integer('copies_available')->default(1);
            $table->string('cover_image')->nullable();
            $table->string('location')->nullable();
            $table->boolean('is_active')->default(true);
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
