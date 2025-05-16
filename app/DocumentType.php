<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DocumentType extends Model
{
    protected $table = 'document_types';

    // Los campos que se pueden asignar masivamente
    protected $fillable = ['name', 'status'];
    
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    // Relación: Un tipo de documento tiene muchos clientes
    public function clients()
    {
        return $this->hasMany(Client::class, 'id_document_type');
    }
}
