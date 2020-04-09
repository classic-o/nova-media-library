<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterNovaMediaLibraryAddMediaCategory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('nova_media_library', function (Blueprint $table) {
            $table->unsignedInteger('category_id')->after('id')->nullable();
            $table->foreign('category_id')->references('id')->on('nova_media_categories')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('nova_media_library', function (Blueprint $table) {
            $table->dropColumn('category_id');
        });
    }
}
