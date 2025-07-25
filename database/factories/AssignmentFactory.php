<?php // database/factories/AssignmentFactory.php
namespace Database\Factories;

use App\Models\Assignment;
use App\Models\Equipment;
use App\Models\ItUser;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AssignmentFactory extends Factory
{
    protected $model = Assignment::class;

    public function definition(): array
    {
        $assignedAt = $this->faker->dateTimeBetween('-1 year', 'now');
        $returned = $this->faker->boolean(30); // 30% chance of being returned

        return [
            'equipment_id' => Equipment::factory(),
            'it_user_id' => ItUser::factory(),
            'assigned_by' => User::factory(),
            'assigned_at' => $assignedAt,
            'returned_at' => $returned ? $this->faker->dateTimeBetween($assignedAt, 'now') : null,
            'assignment_notes' => $this->faker->optional()->text(100),
            'return_notes' => $returned ? $this->faker->optional()->text(100) : null,
            'document_signed' => $this->faker->boolean(80),
        ];
    }
}