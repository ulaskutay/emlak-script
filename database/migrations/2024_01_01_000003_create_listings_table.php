<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('listings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agent_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->enum('type', ['sale', 'rent'])->default('sale');
            $table->enum('status', ['active', 'pending', 'sold', 'rented', 'passive'])->default('pending');
            $table->decimal('price', 15, 2);
            $table->enum('price_period', ['ay', 'yil', 'tam'])->nullable();
            $table->enum('currency', ['TRY', 'USD', 'EUR'])->default('TRY');
            $table->string('city');
            $table->string('district')->nullable();
            $table->text('address');
            $table->integer('area_m2')->nullable();
            $table->integer('bedrooms')->nullable();
            $table->integer('bathrooms')->nullable();
            $table->string('floor')->nullable();
            $table->string('heating_type')->nullable();
            $table->boolean('furnished')->default(false);
            $table->json('tags')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            
            $table->index('agent_id');
            $table->index('status');
            $table->index('type');
            $table->index('city');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('listings');
    }
};

