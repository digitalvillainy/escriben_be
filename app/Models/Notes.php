<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notes extends Model
{
    use HasFactory;

    /**
     * Get the user that owns the Notes
     *
     * Description.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo('App\Models\User');
    }


    /**
     * Get the notebook that owns the Notes
     *
     * Description.
     */
    public function notebook(): BelongsTo
    {
        return $this->belongsTo('App\Models\Notebook');
    }
}
