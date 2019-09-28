<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChecklistTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('checklists', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('object_domain');
            $table->bigInteger('object_id');
            $table->string('description');
            $table->boolean('is_completed')->default(false);
            $table->timestampTz('completed_at')->nullable();
            $table->bigInteger('created_by')->nullable();
            $table->timestampTz('created_at')->nullable();
            $table->bigInteger('updated_by')->nullable();
            $table->timestampTz('updated_at')->nullable();
            $table->dateTime('due')->nullable();
            $table->integer('urgency')->default(0);
            $table->bigInteger('task_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('checklists');
    }
}
