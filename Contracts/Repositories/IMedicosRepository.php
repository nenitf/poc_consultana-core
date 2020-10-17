<?php

namespace Core\Contracts\Repositories;

use Core\Models\Medico;

interface IMedicosRepository {
    function findById(int $id):?Medico;
}

