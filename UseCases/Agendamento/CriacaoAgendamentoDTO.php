<?php

namespace Core\UseCases\Agendamento;

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

    public function validado()
    {
        return !is_null($this->idMedico)
            && !is_null($this->idPaciente)
            && !is_null($this->dia)
            && !is_null($this->duracao);
    }
}
