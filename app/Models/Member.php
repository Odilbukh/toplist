<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;

    protected $table = 'members';

    protected $fillable = [
        'email'
    ];

    protected $casts = [
        'hidden_email'
    ];

    public function results()
    {
        return $this->hasMany(Result::class);
    }

    public function getHiddenEmailAttribute(): ?string
    {
        if ($this->email) {
            $parts = explode('@', $this->email);
            $username = $parts[0];
            $domain = $parts[1];

            $obscuredUsername = substr($username, 0, 2) . str_repeat('*', strlen($username) - 2);

            return $obscuredUsername . '@' . $domain;
        }

        return null;
    }
}
