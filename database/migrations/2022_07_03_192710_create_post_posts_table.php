<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('post_posts', function (Blueprint $table) {
            $table->uuid('id')->index();
            $table->uuid('category_id')->index();
            $table->string('title');
            $table->text('text')->nullable();
            $table->primary('id');
            $table->timestamps();
            $table->foreign('category_id')
                ->references('id')->on('post_categories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('post_posts');
    }
};
