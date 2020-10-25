<?php

use Core\UseCases\Agendamento\{
    CadastroPaciente,
    CadastroPacienteDTO,
};

use Core\Exceptions\{
    AppException,
    ValidationException,
};

use Core\Models\{
    Paciente,
};

use Core\Contracts\Repositories\{
    IPacientesRepository,
};

class CadastroPacienteTest extends \PHPUnit\Framework\TestCase
{
    private $doublePacientesRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpDoubles();
    }

    private function setUpDoubles()
    {
        $this->doublePacientesRepository = $this->createMock(
            IPacientesRepository::class
        );
    }

    private function newSut()
    {
        return new CadastroPaciente(
            $this->doublePacientesRepository,
        );
    }
    
    private function newDTO($id, $nome)
    {
        $dto = new CadastroPacienteDTO();

        $dto->setId($id)
            ->setNome($nome);

        return $dto;
    }

    private function newPaciente($id, $nome)
    {
        $paciente = new Paciente();
        $paciente->setId($id)
                 ->setNome($nome);

        return $paciente;
    }

    public function testDeveCadastrarPaciente()
    {
        $paciente = $this->newPaciente(
            null,
            "Saulo",
        );

        $pacienteAposSave = $this->newPaciente(
            123,
            "Saulo",
        );

        $this->doublePacientesRepository
             ->expects($this->once())
             ->method('save')
             ->with($paciente)
             ->willReturn($pacienteAposSave);

        $sut = $this->newSut();

        $dto = $this->newDTO(
            null,
            $paciente->getNome(),
        );

        $sut->execute($dto);
    }

    public function testDeveAvisarAoNaoSalvarPaciente()
    {
        $this->doublePacientesRepository
             ->expects($this->once())
             ->method('save')
             ->willReturn(null);

        $sut = $this->newSut();

        $dto = $this->newDTO(
            null,
            "Jefferson",
        );

        $this->expectException(AppException::class);
        $this->expectExceptionMessage("Não foi possível cadastrar o paciente");

        $sut->execute($dto);
    }

    public function testDeveAtualizarPaciente()
    {
        $pacienteAtualizado = $this->newPaciente(
            123456,
            "Eduardo Silva",
        );

        $this->doublePacientesRepository
             ->expects($this->once())
             ->method('save')
             ->with($pacienteAtualizado)
             ->willReturn($pacienteAtualizado);

        $sut = $this->newSut();

        $dto = $this->newDTO(
            $pacienteAtualizado->getId(),
            $pacienteAtualizado->getNome()
        );

        $sut->execute($dto);
    }
}
