<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'name',
        'subject',
        'body',
        'variables',
        'is_active',
    ];

    protected $casts = [
        'variables' => 'array',
        'is_active' => 'boolean',
    ];

    public function renderBody(array $data): string
    {
        $parsed = $this->body;
        foreach ($data as $key => $val) {
            $parsed = str_replace("{{\${$key}}}", $val, $parsed);
            $parsed = str_replace("{{{$key}}}", $val, $parsed);
        }
        return $parsed;
    }
}
