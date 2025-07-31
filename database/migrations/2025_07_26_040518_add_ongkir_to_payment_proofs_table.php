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
        Schema::table('payment_proofs', function (Blueprint $table) {
            $table->decimal('ongkir', 12, 2)->nullable()->after('bukti_tf');
        });
    }

    public function down(): void
    {
        Schema::table('payment_proofs', function (Blueprint $table) {
            $table->dropColumn('ongkir');
        });
    }
};
