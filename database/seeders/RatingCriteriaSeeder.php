<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RatingCriteriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $criteria = [
            [
                'name' => 'equipment_age',
                'label' => 'Edad del Equipo',
                'weight_percentage' => 40,
                'auto_calculated' => true,
                'options' => [
                    ['label' => '6 meses', 'value' => 10],
                    ['label' => '12 meses', 'value' => 9],
                    ['label' => '18 meses', 'value' => 8],
                    ['label' => '24 meses', 'value' => 7],
                    ['label' => '30 meses', 'value' => 6],
                    ['label' => '36 meses', 'value' => 5],
                    ['label' => '42 meses', 'value' => 4],
                    ['label' => '48 meses', 'value' => 3],
                    ['label' => '54 meses', 'value' => 2],
                    ['label' => '60 meses', 'value' => 1],
                ],
                'active' => true,
            ],
            [
                'name' => 'physical_condition',
                'label' => 'Estado Físico',
                'weight_percentage' => 15,
                'auto_calculated' => false,
                'options' => [
                    ['label' => 'Nuevo', 'value' => 10],
                    ['label' => 'Muy Bueno', 'value' => 8],
                    ['label' => 'Aceptable', 'value' => 6],
                    ['label' => 'Deficiente', 'value' => 4],
                    ['label' => 'Muy Malo', 'value' => 2],
                    ['label' => 'Inservible', 'value' => 1],
                ],
                'active' => true,
            ],
            [
                'name' => 'technical_performance',
                'label' => 'Rendimiento Técnico',
                'weight_percentage' => 35,
                'auto_calculated' => false,
                'options' => [
                    ['label' => 'Nuevo', 'value' => 10],
                    ['label' => 'Excelente', 'value' => 9],
                    ['label' => 'Muy Bueno', 'value' => 8],
                    ['label' => 'Bueno', 'value' => 7],
                    ['label' => 'Aceptable', 'value' => 6],
                    ['label' => 'Regular', 'value' => 5],
                    ['label' => 'Deficiente', 'value' => 4],
                    ['label' => 'Malo', 'value' => 3],
                    ['label' => 'Muy Malo', 'value' => 2],
                    ['label' => 'Inservible', 'value' => 1],
                ],
                'active' => true,
            ],
            [
                'name' => 'financial_impact',
                'label' => 'Impacto Financiero',
                'weight_percentage' => 10,
                'auto_calculated' => false,
                'options' => [
                    ['label' => 'De $1 a $599', 'value' => 10],
                    ['label' => 'De $600 a $1000', 'value' => 8],
                    ['label' => 'De $1001 a $2000', 'value' => 6],
                    ['label' => 'De $2001 a $3000', 'value' => 4],
                    ['label' => 'De $3001 a $4000', 'value' => 2],
                    ['label' => 'De más de $4001', 'value' => 0],
                ],
                'active' => true,
            ],
        ];

        foreach ($criteria as $criterion) {
            \App\Models\RatingCriterion::create($criterion);
        }
    }
}
