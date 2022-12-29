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
        Schema::create('inboxes', function (Blueprint $table) {
            $table->id();
            $table->string('label');
            $table->text('imap_host');
            $table->text('imap_port');
            $table->string('imap_encryption');
            $table->text('imap_username');
            $table->text('imap_password');
            $table->boolean('same_credentials')->default(false);
            $table->text('smtp_host');
            $table->text('smtp_port');
            $table->string('smtp_encryption');
            $table->text('smtp_username');
            $table->text('smtp_password');
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
        Schema::dropIfExists('inboxes');
    }
};
