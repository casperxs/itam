<?php
// database/migrations/2025_01_01_000004_create_equipment_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('equipment', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipment_type_id')->constrained();
            $table->foreignId('supplier_id')->constrained();
            $table->string('serial_number')->unique();
            $table->string('asset_tag')->unique()->nullable();
            $table->string('brand');
            $table->string('model');
            $table->text('specifications')->nullable();
            $table->enum('status', ['available', 'assigned', 'maintenance', 'retired', 'lost']);
            $table->decimal('purchase_price', 10, 2)->nullable();
            $table->date('purchase_date')->nullable();
            $table->date('warranty_end_date')->nullable();
            $table->string('invoice_number')->nullable();
            $table->string('invoice_file')->nullable();
            $table->text('observations')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('equipment');
    }
};
