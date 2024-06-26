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
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->string('logo');
            $table->string('slogan');
            $table->string('email');
            $table->string('phone_number');
            $table->string('address');
            $table->string('url_facebook');
            $table->string('url_twitter');
            $table->string('url_instagram');
            $table->string('url_linkedin');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
