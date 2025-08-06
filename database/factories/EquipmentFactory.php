<?php // database/factories/EquipmentFactory.php
namespace Database\Factories;

use App\Models\Equipment;
use App\Models\EquipmentType;
use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class EquipmentFactory extends Factory
{
    protected $model = Equipment::class;

    public function definition(): array
    {
        $brands = ['Dell', 'HP', 'Lenovo', 'Apple', 'Samsung', 'Canon', 'Epson'];
        $statuses = ['available', 'assigned', 'maintenance', 'retired'];
        $valoraciones = ['100%', '90%', '80%', '70%', '60%'];

        return [
            'equipment_type_id' => EquipmentType::factory(),
            'supplier_id' => Supplier::factory(),
            'serial_number' => strtoupper($this->faker->bothify('??-####-???')),
            'asset_tag' => 'AST-' . $this->faker->unique()->numberBetween(1000, 9999),
            'brand' => $this->faker->randomElement($brands),
            'model' => $this->faker->bothify('Model-??##'),
            'specifications' => $this->faker->text(200),
            'status' => $this->faker->randomElement($statuses),
            'valoracion' => $this->faker->optional(0.8)->randomElement($valoraciones),
            'purchase_price' => $this->faker->randomFloat(2, 5000, 50000),
            'purchase_date' => $this->faker->dateTimeBetween('-2 years', 'now'),
            'warranty_end_date' => $this->faker->dateTimeBetween('now', '+3 years'),
            'invoice_number' => 'FAC-' . $this->faker->bothify('####-???'),
            'observations' => $this->faker->optional()->text(100),
        ];
    }
}
