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
        Schema::create('reminder_notifications', function (Blueprint $table) {
            $table->id();
			$table->string('title',255);
			$table->text('description')->nullable();
            $table->string('email',255);
			$table->string('type',255);
			$table->string('time',255)->nullable();
			$table->string('daily')->nullable();
			$table->string('monthly')->nullable();
			$table->integer('status')->default(1)->nullable();
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
        Schema::dropIfExists('reminder_notifications');
    }
};