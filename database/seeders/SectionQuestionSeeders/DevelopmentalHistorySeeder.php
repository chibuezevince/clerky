<?php

namespace Database\Seeders\SectionQuestionSeeders;

use App\Models\SectionQuestion;
use Illuminate\Database\Seeder;

class DevelopmentalHistorySeeder extends Seeder {
    public function run(): void {
        $sectionQuestion = SectionQuestion::orderBy('id', 'desc')->first();
        $firstId = $sectionQuestion->id + 1;

        session([
            'begin_developmental_id' => $firstId,
        ]);

        $questions = [
            [
                'clerking_section_id' => 9,
                'question' => 'At what age did the baby first lift his/her head?',
                'field_key' => 'milestone_head_control',
                'input_type' => 'select',
                'options' => [
                    '1m' => '1 month',
                    '2m' => '2 months',
                    '3m' => '3 months',
                    '4m_plus' => '4 months or older',
                    'not_yet' => 'Not yet achieved',
                    'unknown' => 'Unknown',
                ],
            ],
            [
                'clerking_section_id' => 9,
                'question' => 'At what age did the baby first roll over?',
                'field_key' => 'milestone_rolling',
                'input_type' => 'select',
                'options' => [
                    '3m' => '3 months',
                    '4m' => '4 months',
                    '5m' => '5 months',
                    '6m_plus' => '6 months or older',
                    'not_yet' => 'Not yet achieved',
                    'unknown' => 'Unknown',
                ],
            ],
            [
                'clerking_section_id' => 9,
                'question' => 'At what age did the baby first sit without support?',
                'field_key' => 'milestone_sitting',
                'input_type' => 'select',
                'options' => [
                    '5m' => '5 months',
                    '6m' => '6 months',
                    '7m' => '7 months',
                    '8m' => '8 months',
                    '9m_plus' => '9 months or older',
                    'not_yet' => 'Not yet achieved',
                    'unknown' => 'Unknown',
                ],
            ],
            [
                'clerking_section_id' => 9,
                'question' => 'At what age did the baby first crawl?',
                'field_key' => 'milestone_crawling',
                'input_type' => 'select',
                'options' => [
                    '6m' => '6 months',
                    '7m' => '7 months',
                    '8m' => '8 months',
                    '9m' => '9 months',
                    '10m_plus' => '10 months or older',
                    'not_yet' => 'Not yet achieved',
                    'never' => 'Never crawled (bottom shuffled / walked straight)',
                    'unknown' => 'Unknown',
                ],
            ],
            [
                'clerking_section_id' => 9,
                'question' => 'At what age did the baby first stand with support?',
                'field_key' => 'milestone_stand_with_support',
                'input_type' => 'select',
                'options' => [
                    '7m' => '7 months',
                    '8m' => '8 months',
                    '9m' => '9 months',
                    '10m' => '10 months',
                    '11m_plus' => '11 months or older',
                    'not_yet' => 'Not yet achieved',
                    'unknown' => 'Unknown',
                ],
            ],
            [
                'clerking_section_id' => 9,
                'question' => 'At what age did the baby first walk without support?',
                'field_key' => 'milestone_walking',
                'input_type' => 'select',
                'options' => [
                    '10m' => '10 months',
                    '11m' => '11 months',
                    '12m' => '12 months',
                    '13m' => '13 months',
                    '14m' => '14 months',
                    '15m_plus' => '15 months or older',
                    'not_yet' => 'Not yet achieved',
                    'unknown' => 'Unknown',
                ],
            ],
            [
                'clerking_section_id' => 9,
                'question' => 'At what age did the baby first run?',
                'field_key' => 'milestone_running',
                'input_type' => 'select',
                'options' => [
                    '15m' => '15 months',
                    '18m' => '18 months',
                    '2y' => '2 years',
                    '2y_plus' => 'Older than 2 years',
                    'not_yet' => 'Not yet achieved',
                    'unknown' => 'Unknown',
                ],
            ],
            [
                'clerking_section_id' => 9,
                'question' => 'At what age did the baby first smile socially?',
                'field_key' => 'milestone_social_smile',
                'input_type' => 'select',
                'options' => [
                    '6w' => '6 weeks',
                    '2m' => '2 months',
                    '3m' => '3 months',
                    '4m_plus' => '4 months or older',
                    'not_yet' => 'Not yet achieved',
                    'unknown' => 'Unknown',
                ],
            ],
            [
                'clerking_section_id' => 9,
                'question' => 'At what age did the baby first babble?',
                'field_key' => 'milestone_babbling',
                'input_type' => 'select',
                'options' => [
                    '4m' => '4 months',
                    '5m' => '5 months',
                    '6m' => '6 months',
                    '7m' => '7 months',
                    '8m_plus' => '8 months or older',
                    'not_yet' => 'Not yet achieved',
                    'unknown' => 'Unknown',
                ],
            ],
            [
                'clerking_section_id' => 9,
                'question' => 'At what age did the baby first say single words with meaning?',
                'field_key' => 'milestone_first_words',
                'input_type' => 'select',
                'options' => [
                    '10m' => '10 months',
                    '11m' => '11 months',
                    '12m' => '12 months',
                    '13m' => '13 months',
                    '14m' => '14 months',
                    '15m_plus' => '15 months or older',
                    'not_yet' => 'Not yet achieved',
                    'unknown' => 'Unknown',
                ],
            ],
            [
                'clerking_section_id' => 9,
                'question' => 'At what age did the baby first say two-word phrases?',
                'field_key' => 'milestone_two_word_phrases',
                'input_type' => 'select',
                'options' => [
                    '18m' => '18 months',
                    '2y' => '2 years',
                    '2_5y' => '2.5 years',
                    '3y_plus' => '3 years or older',
                    'not_yet' => 'Not yet achieved',
                    'unknown' => 'Unknown',
                ],
            ],
            [
                'clerking_section_id' => 9,
                'question' => 'At what age did the baby first pick up small objects (pincer grasp)?',
                'field_key' => 'milestone_pincer_grasp',
                'input_type' => 'select',
                'options' => [
                    '7m' => '7 months',
                    '8m' => '8 months',
                    '9m' => '9 months',
                    '10m' => '10 months',
                    '11m_plus' => '11 months or older',
                    'not_yet' => 'Not yet achieved',
                    'unknown' => 'Unknown',
                ],
            ],
            [
                'clerking_section_id' => 9,
                'question' => 'At what age did the baby start waving goodbye?',
                'field_key' => 'milestone_waving',
                'input_type' => 'select',
                'options' => [
                    '8m' => '8 months',
                    '9m' => '9 months',
                    '10m' => '10 months',
                    '11m_plus' => '11 months or older',
                    'not_yet' => 'Not yet achieved',
                    'unknown' => 'Unknown',
                ],
            ],
            [
                'clerking_section_id' => 9,
                'question' => 'At what age did the baby start pointing at objects of interest?',
                'field_key' => 'milestone_pointing',
                'input_type' => 'select',
                'options' => [
                    '9m' => '9 months',
                    '10m' => '10 months',
                    '11m' => '11 months',
                    '12m' => '12 months',
                    '13m_plus' => '13 months or older',
                    'not_yet' => 'Not yet achieved',
                    'unknown' => 'Unknown',
                ],
            ],
            [
                'clerking_section_id' => 9,
                'question' => 'Is the child able to follow simple commands?',
                'field_key' => 'milestone_follows_commands',
                'input_type' => 'select',
                'options' => [
                    'yes' => 'Yes',
                    'no' => 'No',
                    'sometimes' => 'Sometimes',
                    'too_young' => 'Too young to assess',
                ],
            ],
            [
                'clerking_section_id' => 9,
                'question' => 'Has there been any regression or loss of previously achieved milestones?',
                'field_key' => 'milestone_regression',
                'input_type' => 'boolean',
            ],
            [
                'clerking_section_id' => 9,
                'question' => 'If yes, describe which milestones were lost and at what age:',
                'field_key' => 'milestone_regression_details',
                'input_type' => 'textarea',
                'max_char' => 500,
                'depends_on_section_question_id' => $firstId + 15,
                'depends_on_answer' => 'yes',
            ],
        ];

        foreach ($questions as $question) {
            SectionQuestion::create($question);
        }

        $latest = SectionQuestion::orderBy('id', 'desc')->first();

        session([
            'end_developmental_id' => $latest->id,
        ]);
    }
}
