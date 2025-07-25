<?php
// database/migrations/2025_01_01_000008_create_contracts_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->constrained();
            $table->string('contract_number');
            $table->string('service_description');
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('monthly_cost', 10, 2)->nullable();
            $table->decimal('total_cost', 10, 2)->nullable();
            $table->enum('status', ['active', 'expired', 'cancelled']);
            $table->integer('alert_days_before')->default(30);
            $table->text('notes')->nullable();
            $table->string('contract_file')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('contracts');
    }
};