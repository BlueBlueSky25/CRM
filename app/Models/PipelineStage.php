<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PipelineStage extends Model
{
    protected $fillable = ['name', 'slug', 'description', 'order'];
    protected $table = 'pipeline_stages';

    public function pipelines(): HasMany
    {
        return $this->hasMany(Pipeline::class, 'stage_id');
    }
}