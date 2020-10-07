# poc_consultana-core

[![emojicom](https://img.shields.io/badge/emojicom-%F0%9F%90%9B%20%F0%9F%86%95%20%F0%9F%92%AF%20%F0%9F%91%AE%20%F0%9F%86%98%20%F0%9F%92%A4-%23fff)](https://gist.github.com/nenitf/1cf5182bff009974bf436f978eea1996#emojicom)

## Setup

```sh
# download de dependências do php
composer i
```

## Testes

### Configuração

```sh
# XDebug para coverage
sudo pecl install xdebug
#sudo apt-get install php-xdebug
#php -i | grep php.ini # ver onde está config do php
#systemctl restart apache2 # reinicia o apache
```

### Criação

- Se precisar criar um namespace em `composer.json` execute `composer du` logo após
- Convenções de nomenclatura:
    - `ProcedimentoUseCaseTest.php` na pasta `UseCases/**`, próximo de `ProcedimentoUseCase.php`
    - Funções de teste devem conter `testDeve**`, exemplo: `testDeveCadastrarPedido()`.

- Convenções de *anotations*:
    - `@testdox Renomeia classe ou método para output testdox` se necessário
    - `@todo` marca teste como arriscado, sinalizando para outros desenvolvedores que o teste está em desenvolvimento
    - `@ticket 123456` para métodos de teste que estão relacionados a uma issue no Github

### Execução

```sh
composer test
#composer test:dox
#composer test:filter ProcedimentoUseCaseTest
#composer test:dox:filter ProcedimentoUseCaseTest
#composer test:filter testNomeDoMetodo
#composer test:dox:filter testNomeDoMetodo

# cria/atualiza arquivo e exibe coverage dos testes no terminal
# também está disponível em tests/_reports/coverage/index.html
# o arquivo é gerado a cada novo teste, portando basta abrí-lo no browser
# e recarregá-lo sempre que quiser ver seu estado atual
composer test:cover

# trabalhando com testes de maneira repetitiva
# o script abaixo testa em loop os testes e
# ao final gera cover e testdox geral do projeto
php tdd
#php tdd filter ProcedimentoUseCaseTest
#php tdd dox:filter ProcedimentoUseCaseTest
#php tdd filter testNomeDoMetodo
#php tdd dox:filter testNomeDoMetodo
#php tdd group 123456
#php tdd dox:group 123456
```

## Contribuindo

Veja o [CONTRIBUTING.md](CONTRIBUTING.md)
