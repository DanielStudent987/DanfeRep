<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rules\Unique;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('danfes', function (Blueprint $table) {
            $table->string('chave', 44)->primary();
            $table->unsignedBigInteger('inserido_por');
            $table->foreign('inserido_por')->references('id')->on('users');
            $table->longText('content_xml');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('danfes');
    }
};
