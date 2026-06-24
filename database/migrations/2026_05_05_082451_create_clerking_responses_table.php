<?php

use App\Models\Clerking;
use App\Models\ClerkingSection;
use App\Models\ComplaintTemplateQuestion;
use App\Models\SectionQuestion;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clerking_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Clerking::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(ClerkingSection::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(SectionQuestion::class)->nullable()->constrained()->cascadeOnDelete();
            $table->foreignIdFor(ComplaintTemplateQuestion::class)->nullable()->constrained()->cascadeOnDelete();
            $table->text('answer')->nullable();
            $table->timestamps();

            $table->index(['clerking_id', 'clerking_section_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clerking_responses');
    }
};
