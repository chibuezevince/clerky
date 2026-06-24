<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clerking_template_complaint_template', function (Blueprint $table) {
            $table->foreignId('clerking_template_id')
                ->constrained('clerking_templates', indexName: 'ctct_clerking_temp_fk')
                ->cascadeOnDelete();

            $table->foreignId('complaint_template_id')
                ->constrained('complaint_templates', indexName: 'ctct_complaint_temp_fk')
                ->cascadeOnDelete();
            $table->primary(['clerking_template_id', 'complaint_template_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clerking_template_complaint_template');
    }
};
