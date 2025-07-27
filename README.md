# API REST em PHP para Gerenciamento de Pedidos

Este projeto é uma API REST em PHP que utiliza autenticação via JWT para proteger as rotas. Ele permite realizar operações de CRUD em pedidos, além de incluir uma rota auxiliar para criação da tabela `pedidos` com as colunas `cliente_nome`, `descricao`, `status`, `data_criacao` e `data_atualizacao`.


## Como Rodar o Projeto Localmente

1. Clone o repositório:
   ```sh
   git clone https://github.com/GiselleBalieiro/teste-pratico-php
   cd teste-pratico-php
   ```

2. Instale as dependências:
   ```sh
   composer install
   ```

3. Crie o banco de dados e configure seu `.env`.
   
4. Você pode gerar o valor para `JWT_SECRET` do `.env` com o comando:

   ```sh
   php -r "echo bin2hex(random_bytes(32));"
   ```

5. Rode o servidor local com:
   ```sh
   php -S localhost:8000
   ```

6. Execute a rota para criação da tabela e colunas:
   Envie uma requisição `POST` para:
   `http://localhost:8000/workplace`

7. Para gerar o token JWT, envie uma requisição `POST` para:
   `http://localhost:8000/login` com:

  **Body (JSON):**
  ```json
  {
    "usuario": "admin",
    "senha": "123456"
  }
  ```
  
  Se os dados estiverem corretos, você receberá um token JWT de resposta:
  
  ```json
  {
    "success": true,
    "token": "SEU_TOKEN_AQUI"
  }
  ```


### Usar o Token nas Requisições

Para acessar rotas protegidas, adicione o token no `header` da requisição:

`Authorization: Bearer SEU_TOKEN_AQUI`

Rotas como `/pedidos`, `/pedidos/{id}` (GET, POST, PUT) exigem esse token.

## Testes via Postman

1. Faça login usando `/login` e copie o token retornado.
2. Use o token como `Bearer Token` nas demais rotas.

**Exemplo de rota protegida:**

`GET http://localhost:8000/pedidos`

**Header:**

`Authorization: Bearer SEU_TOKEN_AQUI`

## Considerações

- O token JWT tem validade de 1 hora.
- Se o token estiver expirado ou ausente, a API retorna erro `401`.

