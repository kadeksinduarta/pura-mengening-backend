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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->date('tanggal');
            $table->time('jam');
            $table->text('alamat');
            $table->enum('tipe', ['Perorangan', 'Instansi'])->default('Perorangan');
            $table->integer('price')->default(600000);
            $table->string('status')->default('pending');
            $table->string('order_id')->nullable();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};

