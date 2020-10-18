<?php

namespace Core\Models;

use Core\Models\HorarioDisponivel;

class Medico
{
    private $id;
    private $nome;
    private $horariosDisponiveis;

    public function getId() {
        return $this->id;
    }

    public function setId(?int $id) {
        $this->id = $id;
        return $this;
    }

    public function getHorariosDisponiveis() {
        return $this->horariosDisponiveis;
    }

    public function setHorariosDisponiveis(
        HorarioDisponivel ...$horariosDisponiveis
    ){
        $this->horariosDisponiveis = $horariosDisponiveis;
        return $this;
    }

    public function getNome() {
        return $this->nome;
    }

    public function setNome($nome) {
        $this->nome = $nome;
        return $this;
    }
}

