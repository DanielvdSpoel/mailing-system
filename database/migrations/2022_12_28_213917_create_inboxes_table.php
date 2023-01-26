<?php

use App\Models\InboxTemplate;
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
            $table->string('name');
            $table->string('imap_host');
            $table->integer('imap_port');
            $table->string('imap_encryption');
            $table->text('imap_username');
            $table->text('imap_password');
            $table->boolean('same_credentials')->default(false);
            $table->string('smtp_host');
            $table->integer('smtp_port');
            $table->string('smtp_encryption');
            $table->text('smtp_username');
            $table->text('smtp_password');
            $table->dateTime('last_successful_connection_at')->nullable();
            $table->json('folder_to_flags_mapping')->nullable();
            $table->foreignId('template_id')->nullable()->constrained('inbox_templates')->nullOnDelete();
            $table->softDeletes();
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
