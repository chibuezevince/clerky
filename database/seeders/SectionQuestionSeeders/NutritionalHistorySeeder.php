<?php

namespace Database\Seeders\SectionQuestionSeeders;

use App\Models\SectionQuestion;
use Illuminate\Database\Seeder;

class NutritionalHistorySeeder extends Seeder {
    public function run(): void {
        $sectionQuestion = SectionQuestion::orderBy('id', 'desc')->first();
        $firstId = $sectionQuestion->id + 1;

        session([
            'begin_nutritional_id' => $firstId,
        ]);

        $questions = [
            [
                'clerking_section_id' => 8,
                'question' => 'Is the child currently being breastfed?',
                'field_key' => 'currently_breastfeeding',
                'input_type' => 'select',
                'options' => [
                    'exclusive' => 'Yes — Exclusive breastfeeding',
                    'mixed' => 'Yes — Mixed feeding (breast + other)',
                    'not_breastfeeding' => 'No — Not breastfeeding',
                ],
            ],
            [
                'clerking_section_id' => 8,
                'question' => 'For how long was the child exclusively breastfed?',
                'field_key' => 'exclusive_breastfeeding_duration',
                'input_type' => 'select',
                'options' => [
                    'never' => 'Never exclusively breastfed',
                    '<1m' => 'Less than 1 month',
                    '1_3m' => '1 - 3 months',
                    '4_5m' => '4 - 5 months',
                    '6m' => '6 months (recommended)',
                    '>6m' => 'More than 6 months',
                    'ongoing' => 'Still exclusively breastfeeding',
                ],
            ],
            [
                'clerking_section_id' => 8,
                'question' => 'At what age were complementary foods introduced?',
                'field_key' => 'complementary_feeding_age',
                'input_type' => 'select',
                'options' => [
                    '<4m' => 'Before 4 months',
                    '4m' => '4 months',
                    '5m' => '5 months',
                    '6m' => '6 months (recommended)',
                    '>6m' => 'After 6 months',
                    'not_yet' => 'Not yet introduced',
                ],
            ],
            [
                'clerking_section_id' => 8,
                'question' => 'What type of complementary foods are being given?',
                'field_key' => 'complementary_food_types',
                'input_type' => 'select',
                'options' => [
                    'cereals' => 'Cereals / Porridge',
                    'vegetables' => 'Vegetables / Fruits',
                    'protein' => 'Protein (meat, fish, eggs, legumes)',
                    'mixed' => 'Mixed / Family diet',
                    'not_applicable' => 'Not applicable',
                ],
            ],
            [
                'clerking_section_id' => 8,
                'question' => 'How many meals does the child eat per day?',
                'field_key' => 'meals_per_day',
                'input_type' => 'select',
                'options' => [
                    '1_2' => '1 - 2 meals',
                    '3_4' => '3 - 4 meals',
                    '5_plus' => '5 or more meals',
                    'not_applicable' => 'Not applicable',
                ],
            ],
            [
                'clerking_section_id' => 8,
                'question' => 'Does the child have a good appetite?',
                'field_key' => 'appetite',
                'input_type' => 'select',
                'options' => [
                    'good' => 'Good',
                    'fair' => 'Fair',
                    'poor' => 'Poor',
                    'excessive' => 'Excessive / Always hungry',
                ],
            ],
            [
                'clerking_section_id' => 8,
                'question' => 'Does the child receive any vitamin or mineral supplements?',
                'field_key' => 'vitamin_supplements',
                'input_type' => 'select',
                'options' => [
                    'none' => 'None',
                    'vitamin_a' => 'Vitamin A',
                    'iron' => 'Iron',
                    'multivitamin' => 'Multivitamin',
                    'multiple' => 'Multiple supplements',
                ],
            ],
            [
                'clerking_section_id' => 8,
                'question' => 'Does the child have any food allergies or intolerances?',
                'field_key' => 'food_allergies',
                'input_type' => 'select',
                'options' => [
                    'none' => 'None',
                    'milk' => 'Cow\'s milk protein',
                    'eggs' => 'Eggs',
                    'peanuts' => 'Peanuts / Tree nuts',
                    'seafood' => 'Seafood',
                    'gluten' => 'Gluten / Wheat',
                    'multiple' => 'Multiple allergies',
                    'suspected' => 'Suspected (not confirmed)',
                ],
            ],
            [
                'clerking_section_id' => 8,
                'question' => 'Does the child have any difficulty chewing or swallowing?',
                'field_key' => 'feeding_difficulty',
                'input_type' => 'boolean',
            ],
            [
                'clerking_section_id' => 8,
                'question' => 'Has the child lost weight recently or failed to gain weight appropriately?',
                'field_key' => 'weight_concern',
                'input_type' => 'select',
                'options' => [
                    'gaining_well' => 'Gaining weight well',
                    'poor_gain' => 'Poor weight gain',
                    'weight_loss' => 'Weight loss',
                    'unknown' => 'Unknown',
                ],
            ],
            [
                'clerking_section_id' => 8,
                'question' => 'Does the child have any dietary restrictions or specific diets?',
                'field_key' => 'dietary_restrictions',
                'input_type' => 'select',
                'options' => [
                    'none' => 'None',
                    'vegetarian' => 'Vegetarian / Vegan',
                    'religious' => 'Religious dietary restrictions',
                    'medical' => 'Medical (e.g. PKU, G6PD, diabetes)',
                    'other' => 'Other',
                ],
            ],
            [
                'clerking_section_id' => 8,
                'question' => 'Is there any pica (eating non-food items like sand, chalk, clay)?',
                'field_key' => 'pica',
                'input_type' => 'boolean',
            ],
        ];

        foreach ($questions as $question) {
            SectionQuestion::create($question);
        }

        $latest = SectionQuestion::orderBy('id', 'desc')->first();

        session([
            'end_nutritional_id' => $latest->id,
        ]);
    }
}
