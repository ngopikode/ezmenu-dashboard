<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Buat kolomnya dulu (nullable agar data lama tidak error)
        Schema::table('orders', function (Blueprint $table) {
            $table->string('order_code', 10)->nullable()->after('id')->index();
        });

        // 2. Isi data lama dengan format Hash yang kamu mau
        DB::table('orders')->orderBy('id')->chunk(100, function ($orders) {
            foreach ($orders as $order) {
                // Format: Ambil 6 karakter pertama dari MD5 (ID + Waktu)
                $hash = strtoupper(substr(md5($order->id . $order->created_at), 0, 6));

                DB::table('orders')
                    ->where('id', $order->id)
                    ->update(['order_code' => $hash]);
            }
        });

        // 3. Ubah jadi Unique dan Not Null setelah semua terisi
        Schema::table('orders', function (Blueprint $table) {
            $table->string('order_code', 10)->nullable(false)->unique()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Hapus aturan unik dulu, baru hapus kolomnya (urutan ini penting agar tidak error)
            $table->dropUnique(['order_code']);
            $table->dropColumn('order_code');
        });
    }
};
