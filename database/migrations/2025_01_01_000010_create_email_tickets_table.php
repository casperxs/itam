<?php
// database/migrations/2025_01_01_000010_create_email_tickets_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('email_tickets', function (Blueprint $table) {
            $table->id();
            $table->string('message_id')->unique();
            $table->string('subject');
            $table->string('from_email');
            $table->string('from_name')->nullable();
            $table->text('body');
            $table->datetime('received_at');
            $table->enum('status', ['pending', 'in_progress', 'resolved', 'closed']);
            $table->foreignId('assigned_to')->nullable()->constrained('users');
            $table->foreignId('it_user_id')->nullable()->constrained();
            $table->foreignId('equipment_id')->nullable()->constrained();
            $table->text('resolution_notes')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('email_tickets');
    }
};