<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('contratantes', function (Blueprint $table) {
            $table->string('banco_dados')->after('email');
        });
    }

    public function down()
    {
        Schema::table('contratantes', function (Blueprint $table) {
            $table->dropColumn('banco_dados');
        });
    }
};
