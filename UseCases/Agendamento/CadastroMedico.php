<?php

namespace Core\UseCases\Agendamento;

use Core\Exceptions\{
    AppException,
    ValidationException,
};

use Core\UseCases\Agendamento\CadastroMedicoDTO;

use Core\Models\{
    Medico,
    HorarioDisponivel,
};

use Core\Contracts\Repositories\{
    IMedicosRepository,
};

class CadastroMedico
{
    private $medicosRepository;

    public function __construct(
        IMedicosRepository $medicosRepository
    ) {
        $this->medicosRepository = $medicosRepository;
    }

    public function execute(CadastroMedicoDTO $dto)
    {
        try {
            $dto->valida();
        } catch (ValidationException $e) {
            throw new AppException($e->getMessage());
        }

        $medico = new Medico();
        $medico->setNome($dto->getNome());

        if(is_array($dto->getHorariosDisponiveis())){
            $medico->setHorariosDisponiveis(...$dto->getHorariosDisponiveis());
        }

        try {
            $medicoSalvo = $this->medicosRepository->save($medico);
            if(is_null($medicoSalvo)){
                throw new AppException();
            }
        } catch (\Throwable $th){
            throw new AppException("Não foi possível cadastrar o médico");
        }
    }
}

