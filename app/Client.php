<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $table = 'clients';

    protected $fillable = [
        'id_document_type',
        'first_name',
        'last_name',
        'document_number',
        'phone',
        'email',
        'address',
        'status'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    // Relación: Un cliente pertenece a un tipo de documento
    public function documentType()
    {
        return $this->belongsTo(DocumentType::class, 'id_document_type');
    }
}
