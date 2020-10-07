<?php

namespace Core\Contracts\Repositories;

interface IHorariosDisponiveisRepository
{
    public function findByMedico(int $id): ?array;
}
