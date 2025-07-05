<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDespesasTable extends Migration
{
    public function up()
    {
        Schema::connection('tenant_temp')->create('despesas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fornecedor_id')->constrained('fornecedores')->cascadeOnDelete();
            $table->string('descricao');
            $table->decimal('valor', 10, 2);
            $table->date('data_pagamento');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::connection('tenant_temp')->dropIfExists('despesas');
    }
}
