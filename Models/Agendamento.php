<?php

namespace Core\Models;

class Agendamento
{
    private $idMedico;
    private $idPaciente;
    private $horarioInicio;
    private $horarioFim;

    public function getIdMedico() {
        return $this->idMedico;
    }

    public function setIdMedico($idMedico) {
        $this->idMedico = $idMedico;
        return $this;
    }

    public function getHorarioInicio() {
        return $this->horarioInicio;
    }

    public function setHorarioInicio($horarioInicio) {
        $this->horarioInicio = $horarioInicio;
        return $this;
    }

    public function getHorarioFim() {
        return $this->horarioFim;
    }

    public function setHorarioFim($horarioFim) {
        $this->horarioFim = $horarioFim;
        return $this;
    }

    public function getIdPaciente() {
        return $this->idPaciente;
    }

    public function setIdPaciente($idPaciente) {
        $this->idPaciente = $idPaciente;
        return $this;
    }
}

