<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {

            $table->id();

            $table->string('note', 500);

            $table->date('do_until')->nullable();

            $table->foreignId('initiator')->constrained('users');

            $table->foreignId('doer')->constrained('users')->nullable();

            $table->string('status', 8)->default('open');

            $table->timestamp('created_at')->nullable();

            $table->timestamp('updated_at')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tickets');
    }
};
