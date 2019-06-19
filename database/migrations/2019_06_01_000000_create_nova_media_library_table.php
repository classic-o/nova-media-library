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
	        $table->string('description')->index()->nullable();
	        $table->string('path');
	        $table->string('mime', 50);
	        $table->string('size', 50);
	        $table->string('type')->index()->collation('utf8_bin');
	        $table->timestamp('created')->index()->useCurrent();
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
