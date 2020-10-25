<?php

namespace Core\Contracts\Repositories;

use Core\Models\Paciente;

interface IPacientesRepository {
    function save(Paciente $paciente):?Paciente;
    function findById(int $id):?Paciente;
}

