<?php
// database/migrations/2025_01_01_000007_create_maintenance_records_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('maintenance_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipment_id')->constrained();
            $table->foreignId('performed_by')->constrained('users');
            $table->enum('type', ['preventive', 'corrective', 'update']);
            $table->datetime('scheduled_date');
            $table->datetime('completed_date')->nullable();
            $table->enum('status', ['scheduled', 'in_progress', 'completed', 'cancelled']);
            $table->text('description');
            $table->text('performed_actions')->nullable();
            $table->decimal('cost', 10, 2)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('maintenance_records');
    }
};