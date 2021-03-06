<?php

namespace Core\Contracts\Repositories;

use Core\Models\Medico;

interface IMedicosRepository {
    function save(Medico $medico):?Medico;
    function findById(int $id):?Medico;
}

