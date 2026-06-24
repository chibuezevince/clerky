<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class ResponseValue implements CastsAttributes {
    public function get(Model $model, $key, $value, $attributes) {
        $type = $model->sectionQuestion?->input_type ?? $model->complaintTemplateQuestion->input_type;

        if ($type === 'multi_select' || $type === 'key_value')
            return json_decode($value, true) ?? [];

        return $value ?? '';
    }

    public function set(Model $model, $key, $value, $attributes) {
        return is_array($value) ? json_encode($value) : $value;
    }
}
