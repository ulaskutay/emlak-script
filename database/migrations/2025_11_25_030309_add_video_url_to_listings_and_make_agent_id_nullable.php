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
        Schema::table('listings', function (Blueprint $table) {
            // Make agent_id nullable
            $table->foreignId('agent_id')->nullable()->change();
            
            // Add video_url field
            $table->string('video_url')->nullable()->after('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('listings', function (Blueprint $table) {
            // Remove video_url
            $table->dropColumn('video_url');
            
            // Revert agent_id to not nullable
            // Note: This may fail if there are null values
            $table->foreignId('agent_id')->nullable(false)->change();
        });
    }
};
