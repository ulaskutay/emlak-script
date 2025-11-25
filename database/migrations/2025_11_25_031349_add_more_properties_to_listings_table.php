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
            // Eğer alanlar zaten varsa hata vermemesi için kontrol ediyoruz
            if (!Schema::hasColumn('listings', 'balconies')) {
                $table->integer('balconies')->nullable()->after('total_rooms'); // Balkon sayısı
            }
            if (!Schema::hasColumn('listings', 'total_floors')) {
                $table->integer('total_floors')->nullable()->after('building_age'); // Toplam kat sayısı
            }
            if (!Schema::hasColumn('listings', 'security')) {
                $table->boolean('security')->default(false)->after('elevator'); // Güvenlik var mı
            }
            if (!Schema::hasColumn('listings', 'terrace')) {
                $table->boolean('terrace')->default(false)->after('security'); // Teras var mı
            }
            if (!Schema::hasColumn('listings', 'inside_site')) {
                $table->boolean('inside_site')->default(false)->after('terrace'); // Site içinde mi
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('listings', function (Blueprint $table) {
            if (Schema::hasColumn('listings', 'balconies')) {
                $table->dropColumn('balconies');
            }
            if (Schema::hasColumn('listings', 'total_floors')) {
                $table->dropColumn('total_floors');
            }
            if (Schema::hasColumn('listings', 'security')) {
                $table->dropColumn('security');
            }
            if (Schema::hasColumn('listings', 'terrace')) {
                $table->dropColumn('terrace');
            }
            if (Schema::hasColumn('listings', 'inside_site')) {
                $table->dropColumn('inside_site');
            }
        });
    }
};
