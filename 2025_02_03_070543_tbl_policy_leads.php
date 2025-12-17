<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        if(!Schema::hasTable('policy_leads')){
            Schema::create('policy_leads', function (Blueprint $table) {
                $table->id();
                $table->string('handbook_received',10)->nullable();
                $table->string('handbook_purpose',50)->nullable();
                $table->string('policy_clarity',50)->nullable();
                $table->string('harassment_policy',100)->nullable();
                $table->text('violation_steps')->nullable();
                $table->string('leave_policy',10)->nullable();
                $table->string('formal_day',50)->nullable();
                $table->string('casual_leaves',10)->nullable();
                $table->string('policies_fair',50)->nullable();
                $table->text('policy_update')->nullable();
                $table->string('handbook_help',10)->nullable();
                $table->text('handbook_help_details')->nullable();
                $table->text('accessibility_suggestions')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('policy_leads');
    }
};
