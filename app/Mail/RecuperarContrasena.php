<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class RecuperarContrasena extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    protected $usuario;
    protected $clave;

    public function __construct($usuario, $clave)
    {
        $this->usuario = $usuario;
        $this->clave = $clave;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('admin.emails.recuperarcontrasena')
                    ->with([
                            "usuario" => $this->usuario,
                            "clave" => $this->clave
                        ]);
    }
}
