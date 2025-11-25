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
            // Oda bilgileri
            $table->integer('living_rooms')->nullable()->after('bedrooms'); // Salon sayısı
            $table->integer('total_rooms')->nullable()->after('living_rooms'); // Toplam oda sayısı
            
            // Bina bilgileri
            $table->integer('building_age')->nullable()->after('floor'); // Bina yaşı
            $table->string('building_type')->nullable()->after('building_age'); // Bina tipi (apartman, villa, vs.)
            
            // Ek özellikler (boolean)
            $table->boolean('balcony')->default(false)->after('furnished'); // Balkon
            $table->boolean('parking')->default(false)->after('balcony'); // Otopark
            $table->boolean('garden')->default(false)->after('parking'); // Bahçe
            $table->boolean('pool')->default(false)->after('garden'); // Havuz
            $table->boolean('elevator')->default(false)->after('pool'); // Asansör
            
            // Eşya durumu
            $table->enum('furnished_type', ['furnished', 'unfurnished', 'semi_furnished'])->nullable()->after('elevator'); // Eşya durumu
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('listings', function (Blueprint $table) {
            $table->dropColumn([
                'living_rooms',
                'total_rooms',
                'building_age',
                'building_type',
                'balcony',
                'parking',
                'garden',
                'pool',
                'elevator',
                'furnished_type',
            ]);
        });
    }
};
