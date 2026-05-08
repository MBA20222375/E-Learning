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
             $table->text('about')->nullable()->after('experience');
        $table->string('address')->nullable()->after('about');
        $table->string('twitter')->nullable()->after('address');
        $table->string('facebook')->nullable()->after('twitter');
        $table->string('instagram')->nullable()->after('facebook');
        $table->string('linkedin')->nullable()->after('instagram');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
