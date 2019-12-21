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
            $table->unsignedBigInteger('domain_id');
            $table->unsignedBigInteger('system_user_id');
            $table->unsignedBigInteger('ipv4_id')->nullable();
            $table->unsignedBigInteger('ipv6_id')->nullable();
            $table->string('web_root');
            $table->string('document_root');
            $table->unsignedInteger('disk_quota')->nullable();
            $table->unsignedInteger('traffic_quota')->nullable();
            $table->boolean('ssl_enabled')->default(false);
            $table->boolean('le_enabled')->default(false);
            $table->text('php_open_basedir')->nullable();
            $table->text('webserver_directives')->nullable();
            $table->text('php_directives')->nullable();
            $table->timestamps();

            $table->foreign('domain_id')->references('id')->on('domains')->onUpdate('cascade');
            $table->foreign('ipv4_id')->references('id')->on('system_ips')->onUpdate('cascade')->onDelete('set null');
            $table->foreign('ipv6_id')->references('id')->on('system_ips')->onUpdate('cascade')->onDelete('set null');
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
