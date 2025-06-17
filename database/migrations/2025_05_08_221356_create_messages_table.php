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
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conversation_id')
                  ->constrained()
                  ->cascadeOnDelete();
            $table->foreignId('user_id')
                  ->constrained()
                  ->cascadeOnDelete();
            $table->text('body')->nullable();
            $table->foreignId('group_id')->nullable()->constrained('groups');
            // optional voice fields
            $table->string('voice_path')->nullable();
            $table->integer('voice_duration')->nullable();
            $table->timestamps();
        });
        // Schema::create('messages', function (Blueprint $table) {
        //     $table->id();
        //     $table->longText('message')->nullable();
        //     $table->timestamp('read_at')->nullable();
        //     $table->foreignId('sender_id')->constrained('users');
        //     $table->foreignId('receiver_id')->nullable()->constrained('users');
            // $table->foreignId('group_id')->nullable()->constrained('groups');
        //     $table->foreignId('conversation_id')->nullable()->constrained('conversations');
        //     $table->timestamps();
        // });

        Schema::table('groups', function (Blueprint $table) {
            $table->foreignId('last_message_id')->nullable()->constrained('messages');
        });

        Schema::table('conversations', function (Blueprint $table) {
            $table->foreignId('last_message_id')->nullable()->constrained('messages');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
