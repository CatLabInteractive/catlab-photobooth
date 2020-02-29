<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subjects', function (Blueprint $table) {
            $table->increments('id');

            $table->string('identifier')
                ->unique();

            $table->string('name')
                ->nullable();

            $table->timestamps();
        });

        Schema::table('assets', function (Blueprint $table) {

            $table->integer('subject_id')
                ->after('user_id')
                ->nullable()
                ->unsigned();

            $table->foreign('subject_id')->references('id')->on('subjects');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('assets', function (Blueprint $table) {

            $table->dropForeign('assets_subject_id_foreign');

            $table->dropColumn('subject_id');

        });

        Schema::dropIfExists('subjects');
    }
}
