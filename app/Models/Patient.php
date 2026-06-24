<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Schema;

class Patient extends Model {
    use SoftDeletes;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function clerkings() {
        return $this->hasMany(Clerking::class);
    }

    public static function fields() {
        $columns = Schema::getColumnListing('patients');

        unset(
            $columns['id'],
            $columns['user_id'],
            $columns['created_at'],
            $columns['updated_at'],
            $columns['deleted_at'],
        );

        return $columns;
    }
}
