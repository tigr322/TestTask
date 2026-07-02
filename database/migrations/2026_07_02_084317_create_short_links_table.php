<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('short_links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->text('original_url');
            $table->string('short_code', 10)->unique();
            $table->timestamps();

            $table->index('short_code');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('short_links');
    }
};
