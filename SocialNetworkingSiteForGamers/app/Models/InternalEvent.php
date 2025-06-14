<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Testing\Fluent\Concerns\Has;

class InternalEvent extends Model
{
    use HasFactory;
    const CREATED_AT = 'CreationDateTime';
    const UPDATED_AT = 'EditDateTime'; // <- poprawione
    protected $table = 'internalevents';
    protected $primaryKey = 'Id';
    public $timestamps = true;
    protected $guarded = [];



public function attachments()
{
    return $this->belongsToMany(
        Attachment::class,
        'internaleventsattachments',
        'InternalEventId',
        'AttachmentId'
    );
}
}