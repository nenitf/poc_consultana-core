<?php

namespace Core\UseCases\Agendamento;

use Core\Exceptions\{
    AppException,
    ValidationException,
};

use Core\UseCases\Agendamento\CadastroPacienteDTO;

use Core\Models\{
    Paciente,
};

use Core\Contracts\Repositories\{
    IPacientesRepository,
};

class CadastroPaciente
{
    private $pacientesRepository;

    public function __construct(
        IPacientesRepository $pacientesRepository
    ) {
        $this->pacientesRepository = $pacientesRepository;
    }

    public function execute(CadastroPacienteDTO $dto)
    {
        try {
            $dto->valida();
        } catch (ValidationException $e) {
            throw new AppException($e->getMessage());
        }

        $paciente = new Paciente();
        $paciente->setId($dto->getId())
                 ->setNome($dto->getNome());

        try {
            $pacienteSalvo = $this->pacientesRepository->save($paciente);
            if(is_null($pacienteSalvo)){
                throw new AppException();
            }
        } catch (\Throwable $th){
            throw new AppException("Não foi possível cadastrar o paciente");
        }
    }
}

