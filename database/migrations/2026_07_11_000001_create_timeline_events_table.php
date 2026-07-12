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
        Schema::create('timeline_events', function (Blueprint $table): void {
            $table->id();
            $table->string('slug')->unique();
            $table->string('title');
            $table->string('client_or_company');
            $table->string('role');
            $table->string('era');
            $table->string('tagline')->nullable();
            $table->text('description')->nullable();
            $table->json('tech_stack')->nullable();
            $table->json('metadata')->nullable();
            $table->json('skills')->nullable();
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->boolean('featured')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('timeline_events');
    }
};
