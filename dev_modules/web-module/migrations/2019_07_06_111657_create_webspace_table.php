<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWebspaceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('webspace', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('domain');
            $table->unsignedBigInteger('system_user');
            $table->unsignedBigInteger('ipv4')->nullable();
            $table->unsignedBigInteger('ipv6')->nullable();
            $table->unsignedInteger('disk_quota')->nullable();
            $table->unsignedInteger('traffic_quota')->nullable();
            $table->string('web_root');
            $table->string('document_root');
            $table->boolean('ssl_enabled');
            $table->boolean('le_enabled');
            $table->text('php_open_basedir');
            $table->text('webserver_directives')->nullable();
            $table->text('php_directives')->nullable();
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
        Schema::dropIfExists('webspace');
    }
}
