<?php

namespace Core\UseCases\Agendamento;

use Core\UseCases\Usuarios\AgendamentoDeConsultaDTO;

use Core\Exceptions\AppException;

use Core\Models\Agendamento;

use Core\Contracts\Repositories\{
    IAgendamentosRepository,
    IHorariosDisponiveisRepository,
};

class CriacaoAgendamentoUseCase {
    private $agendamentosRepository;
    private $horariosDisponiveisRepository;

    public function __construct(
        IAgendamentosRepository $agendamentosRepository,
        IHorariosDisponiveisRepository $horariosDisponiveisRepository
    ) {
        $this->agendamentosRepository = $agendamentosRepository;
        $this->horariosDisponiveisRepository = $horariosDisponiveisRepository;
    }

    public function execute(CriacaoAgendamentoDTO $dto)
    {
        if(!$dto->validado()){
            throw new AppException("Faltam parâmetros para criar agendamento");
        }

        $horariosUteis = $this->horariosDisponiveisRepository->findByMedico(
            $dto->getIdMedico()
        );

        $horarioTrabalhavel = false;
        $diaSemana = intval($dto->getDia()->format('w'));
        foreach($horariosUteis as $horario){
            $horaInicio = new \DateTime($dto->getDia()->format('Y-m-d')." ".$horario->getInicio());
            $horaFim = new \DateTime($dto->getDia()->format('Y-m-d')." ".$horario->getFim());
            if($horario->getDiaSemana() === $diaSemana
                && $horaInicio <= $dto->getDia()
                && $horaFim >= $dto->getDia()){
                $horarioTrabalhavel = true;
                break;
            }
        }
        if(!$horarioTrabalhavel){
            throw new AppException("Médico não consulta nesse horário");
        }

        $horaInicio = $dto->getDia();
        $horaFim = clone $dto->getDia(); // não passa referência da variável
        $horaFim->add(new \DateInterval("PT".$dto->getDuracao()."M")); //PT40M -> +40 minutos
        $horariosAgendados = $this->agendamentosRepository->findByIntervalo(
            $horaInicio, $horaFim
        );

        if(!is_null($horariosAgendados)){
            throw new AppException("Horário ocupado por outra consulta");
        }

        $agendamento = new Agendamento();
        $agendamento->setIdMedico($dto->getIdMedico())
                    ->setIdPaciente($dto->getIdPaciente())
                    ->setHorarioInicio($horaInicio)
                    ->setHorarioFim($horaFim);
        try {
            $this->agendamentosRepository->save($agendamento);
        } catch(\Exception $e) {
            throw new AppException("Horário não foi salvo");
        }
    }
}
