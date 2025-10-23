<?php

namespace App\View\Components;

use Illuminate\View\Component;

class FilaSocioEventos extends Component
{
    public $dni;
    public $nombre;
    public $mesa;
    public $asiento;
    public $checked1;
    public $checked2;

    public function __construct($dni, $nombre, $mesa, $asiento, $checked1 = false, $checked2 = false)
    {
        $this->dni = $dni;
        $this->nombre = $nombre;
        $this->mesa = $mesa;
        $this->asiento = $asiento;
        $this->checked1 = $checked1;
        $this->checked2 = $checked2;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        return view('components.fila-socio-eventos');
    }
}
