<?php

namespace Core\UseCases\Agendamento;

use Core\Exceptions\ValidationException;

class CriacaoAgendamentoDTO
{
    private $idMedico;
    private $idPaciente;
    private $dia;
    private $duracao;

    public function getDia() {
        return $this->dia;
    }

    public function setDia(\DateTime $dia) {
        $this->dia = $dia;
        return $this;
    }

    public function getDuracao() {
        return $this->duracao;
    }

    public function setDuracao(int $duracao) {
        $this->duracao = $duracao;
        return $this;
    }

    public function getIdMedico() {
        return $this->idMedico;
    }

    public function setIdMedico(int $idMedico) {
        $this->idMedico = $idMedico;
        return $this;
    }

    public function getIdPaciente() {
        return $this->idPaciente;
    }

    public function setIdPaciente(int $idPaciente) {
        $this->idPaciente = $idPaciente;
        return $this;
    }

    public function valida()
    {
        if(is_null($this->idMedico))
            throw new ValidationException('Médico é obrigatório');

        if(is_null($this->idPaciente))
            throw new ValidationException('Paciente é obrigatório');

        if(is_null($this->dia))
            throw new ValidationException('Dia e horário são obrigatórios');

        if(is_null($this->duracao))
            throw new ValidationException('Duração é obrigatória');
    }
}
