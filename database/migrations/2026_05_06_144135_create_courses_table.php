<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instructor_id')->constrained('users')->onDelete('cascade');
            $table->unsignedBigInteger('category_id')->nullable();
            $table->string('title');
            $table->string('short_description', 200)->nullable();
            $table->text('description')->nullable();
            $table->text('what_you_learn')->nullable();
            $table->text('requirements')->nullable();
            $table->text('who_is_this_for')->nullable();
            $table->enum('level', ['beginner', 'intermediate', 'advanced'])->nullable();
            $table->unsignedSmallInteger('duration_hours')->default(0);
            $table->unsignedSmallInteger('duration_minutes')->default(0);
            $table->decimal('price', 8, 2);
            $table->string('image')->nullable();
            $table->boolean('is_published')->default(false);
                    $table->string('language')->default('English');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
