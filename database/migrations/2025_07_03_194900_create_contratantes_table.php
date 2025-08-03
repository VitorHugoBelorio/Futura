<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContratantesTable extends Migration
{
    public function up()
    {
        Schema::create('contratantes', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('cnpj', 18)->unique();
            $table->string('telefone')->nullable();
            $table->string('email')->unique();
            $table->string('banco_dados');      // ← nome do banco tenant
            $table->timestamps();
            $table->unsignedBigInteger('user_id')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('contratantes');
    }
}
