<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReceitasTable extends Migration
{
    public function up()
    {
        Schema::connection('tenant_temp')->create('receitas', function (Blueprint $table) {
            $table->id();
            $table->string('descricao');
            $table->decimal('valor', 10, 2);
            $table->date('data_recebimento');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::connection('tenant_temp')->dropIfExists('receitas');
    }
}
