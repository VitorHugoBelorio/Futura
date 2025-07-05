<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFornecedoresTable extends Migration
{
    public function up()
    {
        Schema::connection('tenant_temp')->create('fornecedores', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('cnpj')->nullable();
            $table->string('telefone')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::connection('tenant_temp')->dropIfExists('fornecedores');
    }
}
