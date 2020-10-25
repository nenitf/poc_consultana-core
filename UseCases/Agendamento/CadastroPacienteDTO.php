<?php

namespace Core\UseCases\Agendamento;

use Core\Exceptions\ValidationException;

class CadastroPacienteDTO
{
    private $id;
    private $nome;

    public function getId() {
        return $this->id;
    }

    public function setId(?int $id) {
        $this->id = $id;
        return $this;
    }

    public function getNome() {
        return $this->nome;
    }

    public function setNome(string $nome) {
        $this->nome = $nome;
        return $this;
    }

    public function valida()
    {
        if(is_null($this->nome))
            throw new ValidationException('Nome é obrigatório');
    }
}

