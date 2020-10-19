<?php

use Core\UseCases\Agendamento\{
    AtualizacaoMedico,
    AtualizacaoMedicoDTO,
};

use Core\Exceptions\{
    AppException,
    ValidationException,
};

use Core\Models\{
    Medico,
    HorarioDisponivel,
};

use Core\Contracts\Repositories\{
    IMedicosRepository,
};

class AtualizacaoMedicoTest extends \PHPUnit\Framework\TestCase
{
    private $doubleMedicosRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpDoubles();
    }

    private function setUpDoubles()
    {
        $this->doubleMedicosRepository = $this->createMock(
            IMedicosRepository::class
        );
    }

    private function newSut()
    {
        return new AtualizacaoMedico(
            $this->doubleMedicosRepository,
        );
    }
    
    private function newDTO($id, $nome, $horariosDisponiveis)
    {
        $dto = new AtualizacaoMedicoDTO();

        $dto->setId($id)
            ->setNome($nome);

        if(is_array($horariosDisponiveis)){
            $dto->setHorariosDisponiveis(...$horariosDisponiveis);
        }
        return $dto;
    }

    private function newHorarioDisponivel($diaSemana, $inicio, $fim)
    {
        $horario1 = new HorarioDisponivel();
        return $horario1->setDiaSemana($diaSemana)
                        ->setInicio($inicio)
                        ->setFim($fim);
    }

    private function newMedico($id, $nome, $horariosDisponiveis)
    {
        $medico = new Medico();
        $medico->setId($id)
               ->setNome($nome);

        if(is_array($horariosDisponiveis)){
            $medico->setHorariosDisponiveis(...$horariosDisponiveis);
        }
        return $medico;
    }

    public function testDeveAtualizarMedico()
    {
        $medico = $this->newMedico(
            123,
            "Saulo",
            [
                $this->newHorarioDisponivel(2, "8:00", "18:00"),
                $this->newHorarioDisponivel(2, "09:00", "14:00")
            ]
        );

        $dto = $this->newDTO(
            $medico->getId(),
            "Saulo Jr",
            [
                $this->newHorarioDisponivel(3, "8:00", "18:00"),
                $this->newHorarioDisponivel(3, "09:00", "14:00"),
                $this->newHorarioDisponivel(3, "15:00", "16:00")
            ]
        );

        $medicoAtualizado = $this->newMedico(
            $medico->getId(),
            $dto->getNome(),
            $dto->getHorariosDisponiveis()
        );

        $this->doubleMedicosRepository
             ->expects($this->once())
             ->method('save')
             ->with($medicoAtualizado)
             ->willReturn($medico);

        $sut = $this->newSut();

        $sut->execute($dto);
    }

    public function testDeveAvisarAoNaoSalvarMedico()
    {
        $this->doubleMedicosRepository
             ->expects($this->once())
             ->method('save')
             ->willReturn(null);

        $sut = $this->newSut();

        $dto = $this->newDTO(
            123,
            "Jefferson",
            [
                $this->newHorarioDisponivel(4, "8:00", "18:00"),
                $this->newHorarioDisponivel(4, "09:00", "14:00")
            ]
        );

        $this->expectException(AppException::class);
        $this->expectExceptionMessage("Não foi possível cadastrar o médico");

        $sut->execute($dto);
    }
}
