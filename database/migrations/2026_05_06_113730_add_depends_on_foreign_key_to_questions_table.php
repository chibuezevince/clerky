<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('complaint_template_questions', function (Blueprint $table) {
            $table->foreign('depends_on_complaint_question_id', 'ctq_depends_complaint_fk')
                ->references('id')
                ->on('complaint_template_questions')
                ->nullOnDelete();
        });

        Schema::table('section_questions', function (Blueprint $table) {
            $table->foreign('depends_on_complaint_question_id', 'sq_depends_complaint_fk')
                ->references('id')
                ->on('complaint_template_questions')
                ->nullOnDelete();
        });
    }
};
