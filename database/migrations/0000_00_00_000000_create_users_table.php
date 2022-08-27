<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {

            $table->id();

            $table->string('user_name', 20);

            $table->string('password', 8);

            $table->string('full_name', 200)->nullable();

            $table->string('authority', 5)->default('user');

            $table->timestamp('created_at')->nullable();

            $table->timestamp('updated_at')->nullable();


            $table->unique(['user_name']);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
