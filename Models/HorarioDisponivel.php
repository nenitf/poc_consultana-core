<?php

namespace Core\Models;

class HorarioDisponivel
{
    private $diaSemana;
    private $inicio;
    private $fim;

    public function getDiaSemana() {
        return $this->diaSemana;
    }

    public function setDiaSemana(int $diaSemana) {
        $this->diaSemana = $diaSemana;
        return $this;
    }

    public function getInicio() {
        return $this->inicio;
    }

    public function setInicio(string $inicio) {
        $this->inicio = $inicio;
        return $this;
    }

    public function getFim() {
        return $this->fim;
    }

    public function setFim(string $fim) {
        $this->fim = $fim;
        return $this;
    }
}
