<?php

use Core\Exceptions\AppException;

use Core\UseCases\Agendamento\{
    CriacaoAgendamento,
    CriacaoAgendamentoDTO,
};

use Core\Models\{
    Medico,
    HorarioDisponivel,
};

use Core\Contracts\Repositories\{
    IAgendamentosRepository,
    IMedicosRepository,
};

class CriacaoAgendamentoTest extends \PHPUnit\Framework\TestCase
{
    private $doubleAgendamentosRepository;
    private $doubleMedicosRepository;

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
        $this->doubleMedicosRepository = $this->createMock(
            IMedicosRepository::class
        );
    }

    public function newSut()
    {
        return new CriacaoAgendamento(
            $this->doubleAgendamentosRepository,
            $this->doubleMedicosRepository,
        );
    }

    private function newHorarioDisponivel($inicio, $fim)
    {
        $horario1 = new HorarioDisponivel();
        return $horario1->setDiaSemana(2)
                        ->setInicio($inicio)
                        ->setFim($fim);
    }

    private function newMedico($id, $nome, $horariosDisponiveis, $desativado)
    {
        $medico = new Medico();
        $medico->setId($id)
               ->setNome($nome)
               ->setDesativado($desativado);

        if(is_array($horariosDisponiveis)){
            $medico->setHorariosDisponiveis(...$horariosDisponiveis);
        }
        return $medico;
    }

    private function newDTO($idMedico, $idPaciente, $dia, $duracao)
    {
        $dto = new CriacaoAgendamentoDTO();
        return $dto->setIdMedico($idMedico)
                   ->setIdPaciente($idPaciente)
                   ->setDia($dia)
                   ->setDuracao($duracao);
    }

    private function given($contexto, ...$params)
    {
        switch($contexto) {
        case "médico encontrado":
            $this->doubleMedicosRepository
                 ->expects($this->once())
                 ->method('findById')
                 ->willReturn($params[0]);
            break;
        case "horário disponível":
        $this->doubleAgendamentosRepository
             ->expects($this->once())
             ->method('findByIntervalo')
             ->with(
                 $this->callback(function($h) use ($params){
                     return $h->format('Y-m-d H:i') === $params[0];
                 }),
                 $this->callback(function($h) use ($params){
                     return $h->format('Y-m-d H:i') === $params[1];
                 })
             )
             ->willReturn(null);
            break;
        }
        return $this;
    }

    public function testDeveAgendarConsulta()
    {
        $medico = $this->newMedico(
            123456,
            "Robson",
            [
                $this->newHorarioDisponivel("8:00", "18:00"),
                $this->newHorarioDisponivel("09:00", "14:00")
            ],
            false
        );

        $this->given("médico encontrado", $medico)
             ->given("horário disponível", '2020-10-06 09:30', '2020-10-06 10:00');

        $sut = $this->newSut();

        $dto = $this->newDTO(
            $medico->getId(), 54, new \DateTime('2020-10-06T09:30'), 30
        );

        $sut->execute($dto);
    }

    public function testNaoDeveAgendarMedicoDesativado()
    {
        $medico = $this->newMedico(
            123456,
            "Robson",
            [
                $this->newHorarioDisponivel("8:00", "18:00"),
                $this->newHorarioDisponivel("09:00", "14:00")
            ],
            true
        );

        $this->given("médico encontrado", $medico);

        $sut = $this->newSut();

        $dto = $this->newDTO(
            $medico->getId(), 54, new \DateTime('2020-10-06T09:30'), 30
        );

        $this->expectException(AppException::class);
        $this->expectExceptionMessage("Não é possível consultar com médico desativado");

        $sut->execute($dto);
    }
}
