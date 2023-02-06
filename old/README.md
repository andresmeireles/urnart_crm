SYSADMIN - FERRAMENTA DE GERENCIAMENTO URNART
=============================================

Projeto de software de administração completa da empresa Urnas Mart Ltda `http://urnart.com.br`.

Requisitos
==========

1. `php 7.4^`
2. `Ter composer instalado https://getcomposer.org/`
3. `Ter mariaDB instalado`
3. `NodeJS`
4. `yarn ou npm`
5. `mongodb`

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

Para criar, atualizar e monitorar assets em tempo real executar comando `yarn run encore dev --watch` ou `npm run encore dev --watch`.

Para compilar os assets para produção use o comando `npm run build` ou `yarn run build`. 

Para mais informações sebre assets ver `https://symfony.com/doc/current/frontend/` .
