<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Notes;

class Notebook extends Model
{
    use HasFactory;


    /**
     * returns a user the notebook belongs to
     *
     * Description.
     */
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }


    /**
     * returns all the notes in the notebook
     *
     * Description.
     */
    public function notes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Notes::class);
    }
}
