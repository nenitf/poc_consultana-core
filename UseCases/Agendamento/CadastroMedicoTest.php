<?php

use Core\UseCases\Agendamento\{
    CadastroMedico,
    CadastroMedicoDTO,
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

class CadastroMedicoTest extends \PHPUnit\Framework\TestCase
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
        return new CadastroMedico(
            $this->doubleMedicosRepository,
        );
    }
    
    private function newDTO($id, $nome, $desativado, $horariosDisponiveis)
    {
        $dto = new CadastroMedicoDTO();

        $dto->setId($id)
            ->setNome($nome)
            ->setDesativado($desativado);

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

    private function newMedico($id, $nome, $desativado, $horariosDisponiveis)
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

    public function testDeveCadastrarMedico()
    {
        $medico = $this->newMedico(
            null,
            "Saulo",
            false,
            [
                $this->newHorarioDisponivel(2, "8:00", "18:00"),
                $this->newHorarioDisponivel(2, "09:00", "14:00")
            ]
        );

        $medicoAposSave = $this->newMedico(
            123,
            "Saulo",
            false,
            [
                $this->newHorarioDisponivel(2, "8:00", "18:00"),
                $this->newHorarioDisponivel(2, "09:00", "14:00")
            ]
        );

        $this->doubleMedicosRepository
             ->expects($this->once())
             ->method('save')
             ->with($medico)
             ->willReturn($medicoAposSave);

        $sut = $this->newSut();

        $dto = $this->newDTO(
            null,
            $medico->getNome(),
            false,
            $medico->getHorariosDisponiveis(),
        );

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
            null,
            "Jefferson",
            false,
            [
                $this->newHorarioDisponivel(2, "8:00", "18:00"),
                $this->newHorarioDisponivel(2, "09:00", "14:00")
            ]
        );

        $this->expectException(AppException::class);
        $this->expectExceptionMessage("Não foi possível cadastrar o médico");

        $sut->execute($dto);
    }

    public function testDeveAtualizarEDesativarMedico()
    {
        $medicoAtualizado = $this->newMedico(
            123456,
            "Eduardo Silva",
            true,
            [
                $this->newHorarioDisponivel(2, "8:00", "18:00"),
                $this->newHorarioDisponivel(2, "09:00", "14:00"),
                $this->newHorarioDisponivel(3, "18:00", "19:00")
            ]
        );

        $this->doubleMedicosRepository
             ->expects($this->once())
             ->method('save')
             ->with($medicoAtualizado)
             ->willReturn($medicoAtualizado);

        $sut = $this->newSut();

        $dto = $this->newDTO(
            $medicoAtualizado->getId(),
            $medicoAtualizado->getNome(),
            $medicoAtualizado->getDesativado(),
            $medicoAtualizado->getHorariosDisponiveis(),
        );

        $sut->execute($dto);
    }

}
