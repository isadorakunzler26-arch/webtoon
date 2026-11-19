# Webtoonsz

Projeto PHP 8.2 + MySQL 8 executado com Docker Compose.

## Requisitos

- Docker Desktop com Docker Compose
- Navegador atualizado

## Configuração

1. Copie `.env.example` para `.env`.
2. Mantenha as credenciais iguais às configuradas no MySQL. Os valores iniciais são usuário `webtoonsz` e senha `1234`.
3. Na pasta do projeto, execute `docker compose up -d --build`.
4. Acesse `http://localhost:8080`.
5. O phpMyAdmin fica em `http://localhost:8081`.

## Banco de dados

O arquivo `bc/banco.sql` cria as tabelas e o gatilho de notificações quando o volume do MySQL é inicializado pela primeira vez.

Se o volume `dbdata` já existia antes da atualização do SQL, o MySQL não executará o script de inicialização novamente. Nesse caso, faça backup antes de qualquer alteração e aplique as mudanças pendentes manualmente. Não use `docker compose down -v` se precisar preservar os dados: esse comando remove os volumes do projeto.

## Funcionalidades incluídas

- Cadastro e autenticação de usuários
- Perfis de leitores e artistas
- Conversão de conta de leitor para artista
- Cadastro, edição e exclusão de obras
- Upload de capas
- Favoritar obras e seguir artistas
- Publicação e gerenciamento de avisos e atualizações
- Painel e estatísticas do artista
- Notificações geradas para seguidores e leitores que favoritaram a obra

## Observações

- O fluxo visual de recuperação de senha existente na tela de login ainda não está integrado a um serviço de envio de e-mail; não use essa opção como recuperação funcional.
- O projeto não contém sistema de capítulos/leitor de páginas, comentários ou avaliações. Esses recursos não fazem parte do esquema SQL atualmente entregue.
- Para produção, altere as senhas padrão, configure HTTPS e configure um serviço de e-mail para recuperação de senha.

### Atualizar um banco já existente

Se o banco `webtoonsz` já está em uso, exporte um backup pelo phpMyAdmin e execute apenas `bc/migracao_notificacoes.sql` para adicionar o recurso de notificações. Não execute novamente `bc/banco.sql` sobre um banco com dados que deseja manter.
