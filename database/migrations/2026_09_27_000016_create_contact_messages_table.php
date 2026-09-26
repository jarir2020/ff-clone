<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('contact_messages')) {
            return;
        }

        Schema::create('contact_messages', function (Blueprint $table): void {
            $table->id();
            $table->string('full_name');
            $table->string('mobile', 20);
            $table->string('email')->nullable();
            $table->string('subject')->nullable();
            $table->text('details');
            $table->unsignedTinyInteger('status')->default(0);
            $table->timestamps();

            $table->index('mobile');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_messages');
    }
};
