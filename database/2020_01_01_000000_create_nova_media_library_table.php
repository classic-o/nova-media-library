<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNovaMediaLibraryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nova_media_library', function (Blueprint $table) {
            $table->increments('id');
	        $table->string('title')->index()->nullable();
	        $table->timestamp('created')->index()->useCurrent();
	        $table->string('type')->index();
	        $table->string('folder')->index();
	        $table->string('name');
	        $table->boolean('private')->default(0);
	        $table->boolean('lp')->default(0);
	        $table->text('options')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nova_media_library');
    }
}
