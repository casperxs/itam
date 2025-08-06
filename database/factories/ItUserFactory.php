<?php // database/factories/ItUserFactory.php
namespace Database\Factories;

use App\Models\ItUser;
use Illuminate\Database\Eloquent\Factories\Factory;

class ItUserFactory extends Factory
{
    protected $model = ItUser::class;

    public function definition(): array
    {
        $departments = ['Liderazgo', 'TI', 'Administración', 'Recursos Humanos', 'Training', 'Directos (FullTruck)', 'Bodegas', 'Milk Run', 'Virtuales', 'Áereos', 'Material Vehículos', 'Compliance', 'Calidad', 'Tramitadores', 'Servicio al Cliente'];
        $positions = ['Analista', 'Coordinador', 'Supervisor', 'Gerente', 'Director', 'Ejecutivo', 'Especialista'];

        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'employee_id' => 'EMP' . $this->faker->unique()->numberBetween(1000, 9999),
            'department' => $this->faker->randomElement($departments),
            'position' => $this->faker->randomElement($positions),
            'status' => $this->faker->randomElement(['active', 'inactive']),
            'notes' => $this->faker->optional()->text(100),
        ];
    }
}
