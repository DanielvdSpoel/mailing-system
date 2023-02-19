<?php

use App\Models\Email;
use App\Models\EmailAddress;
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
        Schema::create('email_email_address', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Email::class);
            $table->foreignIdFor(EmailAddress::class);
            $table->string('type');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('email_email_address');
    }
};
