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
        Schema::create('demo_events', function (Blueprint $table) {
            $table->id();
            $table->string('event_class');
            $table->string('user_name')->nullable();
            $table->string('category')->nullable();
            $table->string('action')->nullable();
            $table->string('via')->nullable();
            $table->string('frequency')->nullable();
            $table->integer('item_count')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('demo_events');
    }
};
