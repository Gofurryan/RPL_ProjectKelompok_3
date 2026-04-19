<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'description', 'subject_type', 'subject_id', 'ip_address'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function record($description, $subject = null)
    {
        return self::create([
            'user_id' => auth()->id(),
            'description' => $description,
            'subject_type' => $subject ? get_class($subject) : null,
            'subject_id' => $subject ? $subject->id : null,
            'ip_address' => request()->ip(),
        ]);
    }
}
