<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotificacionMailable extends Mailable
{
    use Queueable, SerializesModels;

    public $nombre;
    public $estado;
    public $id_envio;
    public $mensaje_extra;
    public $link_encuesta;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($nombre, $estado, $id_envio, $mensaje_extra = null, $link_encuesta = null)
    {
        $this->nombre = $nombre;
        $this->estado = $estado;
        $this->id_envio = $id_envio;
        $this->mensaje_extra = $mensaje_extra;
        $this->link_encuesta = $link_encuesta;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Actualización de envío')
            ->view('emails.notificacion');
    }
}
