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
    Schema::create('envios', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('orden_id');
      $table->string('direccion_envio');
      $table->string('codigo_postal');
      $table->string('ciudad');
      $table->boolean('entregado')->default(false);
      $table->foreign('orden_id')->references('id')->on('ordenes')->onUpdate('cascade')->onDelete('restrict');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('envios');
  }
};
