<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\NotificacionMailable;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    public function send()
    {
        $correo_destino = 'pierog@overskull.pe';
        $mensaje = 'Este es un correo de prueba enviado desde Laravel 5.8';

        Mail::to($correo_destino)->send(new NotificacionMailable($mensaje));

        return 'Correo enviado correctamente';
    }
}