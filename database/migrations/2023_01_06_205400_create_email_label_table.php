<?php

use App\Models\Email;
use App\Models\Label;
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
        Schema::create('email_label', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Email::class)->constrained()->onDelete('cascade');
            $table->foreignIdFor(Label::class)->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_label');
    }
};
