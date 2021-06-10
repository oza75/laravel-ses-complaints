<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSesNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ses_notifications', function (Blueprint $table) {
            $table->id();
            $table->text('topic_arn');
            $table->string('source_email');
            $table->string('destination_email');
            $table->string('subject');
            $table->text("message_id")->nullable();
            $table->text("ses_message_id");
            $table->string('type');
            $table->text("options")->nullable();
            $table->timestamp('sent_at');
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
        Schema::dropIfExists('ses_notifications');
    }
}
