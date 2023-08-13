<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invextry_meta', function (Blueprint $table) {
            $table->id();
            $table->string('meta_key');
            $table->text('meta_value');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invextry_meta');
    }
};
