<?php

use App\Models\EmailAddress;
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
        Schema::create('email_address_inbox', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Inbox::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(EmailAddress::class)->constrained()->cascadeOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_address_inbox');
    }
};
