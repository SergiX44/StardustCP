<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDomainsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('domains', function (Blueprint $table) {
	        $table->bigIncrements('id');
	        $table->unsignedBigInteger('user_id')->nullable();
	        $table->string('name');
	        $table->string('extension')->nullable();
	        $table->boolean('is_sld')->default(false);
	        $table->unsignedBigInteger('parent_domain')->nullable();
	        $table->unsignedInteger('used_count')->default(0);
	        $table->timestamps();

	        $table->foreign('parent_domain')->references('id')->on('domains')->onUpdate('cascade')->onDelete('cascade');
	        $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('domains');
    }
}
