<?php
// app/Models/Attachment.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    use HasFactory;

    
    const CREATED_AT = 'CreationDateTime';
    const UPDATED_AT = 'EditDateTime';

    protected $table = 'attachments';
    protected $primaryKey = 'Id';
    public $timestamps = true;       

  
    protected $guarded = [];


    public function internalEvents()
{
    return $this->belongsToMany(
        InternalEvent::class,
        'internaleventsattachments',
        'AttachmentId',
        'InternalEventId'
    );
}
}
