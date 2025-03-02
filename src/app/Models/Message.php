<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'content',
    ];

    /**
     * Get the user that sent this message.
     *
     * @return BelongsTo<User, Message>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
