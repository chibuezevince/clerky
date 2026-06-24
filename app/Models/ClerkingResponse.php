<?php

namespace App\Models;

use App\Casts\ResponseValue;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class ClerkingResponse extends Model {
    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $casts = [
        'answer' => ResponseValue::class,
    ];

    protected function formattedAnswer(): Attribute {
        return Attribute::make(get: function () {
            $type = $this->sectionQuestion?->input_type
                ?? $this->complaintTemplateQuestion?->input_type;

            $value = $this->answer;

            return match ($type) {
                'multi_select' => collect($value)->map(fn($value) => ucfirst($value))->join(', '),
                'boolean' => $value === '1' ? 'Yes' : 'No',
                'select' => ucfirst(convert_text($value)),
                'key_value' => collect($value)->map(fn($value) => ucfirst("{$value['key']}: {$value['value']}"))->implode("\n"),
                default => ucfirst($value),
            };
        });
    }

    public function clerking() {
        return $this->belongsTo(Clerking::class);
    }

    public function clerkingSection() {
        return $this->belongsTo(ClerkingSection::class);
    }

    public function sectionQuestion() {
        return $this->belongsTo(SectionQuestion::class);
    }

    public function complaintTemplateQuestion() {
        return $this->belongsTo(ComplaintTemplateQuestion::class);
    }
}
