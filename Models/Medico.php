<?php

namespace Core\Models;

class Medico
{
    private $id;
    private $horariosDisponiveis;

    public function getId() {
        return $this->id;
    }

    public function setId(int $id) {
        $this->id = $id;
        return $this;
    }

    public function getHorariosDisponiveis() {
        return $this->horariosDisponiveis;
    }

    public function setHorariosDisponiveis(?array $horariosDisponiveis) {
        $this->horariosDisponiveis = $horariosDisponiveis;
        return $this;
    }
}

