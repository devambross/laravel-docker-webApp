<?php

namespace App\View\Components;

use Illuminate\View\Component;

class FilaSocio extends Component
{
    public $dni;
    public $nombre;
    public $edad;
    public $relacion;
    public $checked;

    public function __construct($dni, $nombre, $edad, $relacion, $checked = false)
    {
        $this->dni = $dni;
        $this->nombre = $nombre;
        $this->edad = $edad;
        $this->relacion = $relacion;
        $this->checked = $checked;
    }

    public function render()
    {
        return view('components.fila-socio');
    }
}
