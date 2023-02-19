<?php

use App\Models\Conversation;
use App\Models\Inbox;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('emails', function (Blueprint $table) {
            $table->id();
            $table->text('subject');
            $table->longText('text_body')->nullable();
            $table->longText('html_body')->nullable();
            $table->foreignId('reply_to_address_id')->nullable()->constrained('email_addresses')->nullOnDelete();
            $table->foreignId('sender_address_id')->nullable()->constrained('email_addresses')->nullOnDelete();
            $table->integer('message_uid')->nullable();
            $table->string('message_id')->nullable();
            $table->dateTime('received_at');
            $table->dateTime('archived_at')->nullable();
            $table->dateTime('deleted_at')->nullable();
            $table->dateTime('read_at')->nullable();
            $table->boolean('is_draft')->default(false);
            $table->boolean('email_send_by_us')->default(false);
            $table->boolean('needs_human_verification')->default(false);
            $table->dateTime('auto_filtered_at')->nullable();
            $table->foreignIdFor(Conversation::class)->nullable()->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Inbox::class)->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emails');
    }
};
