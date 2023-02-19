<?php

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
        Schema::create('inbox_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('imap_host');
            $table->text('imap_port');
            $table->string('imap_encryption');
            $table->text('smtp_host');
            $table->text('smtp_port');
            $table->string('smtp_encryption');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inbox_templates');
    }
};
