<?php

namespace Core\Contracts\Repositories;

use Core\Models\Agendamento;

interface IAgendamentosRepository {
    public function save(Agendamento $agendamento):void;
    public function findByIntervalo(\DateTime $inicio, \DateTime $fim):?array;
}

