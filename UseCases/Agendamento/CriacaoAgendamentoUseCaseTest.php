<?php

use Core\UseCases\Agendamento\CriacaoAgendamentoUseCase;
use Core\UseCases\Agendamento\CriacaoAgendamentoDTO;

use Core\Models\HorarioDisponivel;

use Core\Contracts\Repositories\{
    IAgendamentosRepository,
    IHorariosDisponiveisRepository,
};

class CriacaoAgendamentoUseCaseTest extends \PHPUnit\Framework\TestCase
{
    private $doubleAgendamentosRepository;
    private $doubleHorariosDisponiveisRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpDoubles();
    }

    private function setUpDoubles()
    {
        $this->doubleAgendamentosRepository = $this->createMock(
            IAgendamentosRepository::class
        );
        $this->doubleHorariosDisponiveisRepository = $this->createMock(
            IHorariosDisponiveisRepository::class
        );
    }

    public function newSut()
    {
        return new CriacaoAgendamentoUseCase(
            $this->doubleAgendamentosRepository,
            $this->doubleHorariosDisponiveisRepository,
        );
    }

    public function newHorarioDisponivel($inicio, $fim)
    {
        $horario1 = new HorarioDisponivel();
        return $horario1->setDiaSemana(2)
                        ->setInicio("8:00")
                        ->setFim("18:00");
    }

    private function newDTO($idMedico, $idPaciente, $dia, $duracao)
    {
        $dto = new CriacaoAgendamentoDTO();
        return $dto->setIdMedico($idMedico)
                   ->setIdPaciente($idPaciente)
                   ->setDia($dia)
                   ->setDuracao($duracao);
    }

    public function testDeveAgendarConsulta()
    {
        $this->doubleHorariosDisponiveisRepository
             ->expects($this->once())
             ->method('findByMedico')
             ->willReturn([
                 $this->newHorarioDisponivel("8:00", "18:00"),
                 $this->newHorarioDisponivel("09:00", "14:00")
             ]);

        $this->doubleAgendamentosRepository
             ->expects($this->once())
             ->method('findByIntervalo')
             ->with(
                 $this->callback(function($h){
                     return $h->format('Y-m-d H:i') === '2020-10-06 09:30';
                 }),
                 $this->callback(function($h){
                     return $h->format('Y-m-d H:i') === '2020-10-06 10:00';
                 })
             )
             ->willReturn(null);

        $sut = $this->newSut();

        $dto = $this->newDTO(3, 54, new \DateTime('2020-10-06T09:30'), 30);

        $sut->execute($dto);
    }
}
