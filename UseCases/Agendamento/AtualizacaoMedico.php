<?php

namespace Core\UseCases\Agendamento;

use Core\Exceptions\{
    AppException,
    ValidationException,
};

use Core\UseCases\Agendamento\AtualizacaoMedicoDTO;

use Core\Models\{
    Medico,
    HorarioDisponivel,
};

use Core\Contracts\Repositories\{
    IMedicosRepository,
};

class AtualizacaoMedico
{
    private $medicosRepository;

    public function __construct(
        IMedicosRepository $medicosRepository
    ) {
        $this->medicosRepository = $medicosRepository;
    }

    public function execute(AtualizacaoMedicoDTO $dto)
    {
        try {
            $dto->valida();
        } catch (ValidationException $e) {
            throw new AppException($e->getMessage());
        }

        $medico = new Medico();
        $medico->setId($dto->getId());
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

