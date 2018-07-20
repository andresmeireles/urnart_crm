SYSADMIN - FERRAMENTA DE GERENCIAMENTO URNART
=============================================

Projeto de software de administração completa.

Requisitos
==========

1. `php 7.1^`
2. `Ter composer instalado https://getcomposer.org/`
3. `Ter mariaDB instalado`
3. `NodeJS e Yarn`

====================================================

1 - Primeiros passos:
=====================

Fazer tudo na pasta raiz do projeto.

Dependencias do projeto
-----------------------

Backend:

1 - `composer update`


Font-End:

2 - `yarn`

Banco de dados
--------------

É bom conferir a string de conexão com o banco de dados dentro do arquivo `.env` e verificar se tudo esta correto. 

Caso tenha o banco de dados criado, pular primeira etapa.

1. `php bin\console doctrine:database:create`
2. `php bin\console doctrine:migrations:migrate`

2 - Criando Assets
==================

Para criar, atualizar e monitorar assets em tempo real executar comando `yarn run encore dev --watch`. 

Para mais informações sebre assets ver `https://symfony.com/doc/current/frontend/` .