<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sedekah_forms', function (Blueprint $table) {
            $table->id();
            $table->string('jenis_donasi', 100);
            $table->string('nominal', 100);
            $table->string('nama', 100);
            $table->string('email', 100);
            $table->string('phone', 100);
            // $table->string('fotoSedekah')->nullable;
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sedekah_forms');
    }
};
