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

> Certifique-se de que o MySQL está em execução (via WAMP, XAMPP, ou outro servidor local). A API depende da conexão com o banco configurado no arquivo `.env`.

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


## Testar as Rotas com Swagger (OpenAPI)

Este projeto inclui um arquivo `openapi.yaml`, que contém a documentação da API no padrão **OpenAPI 3.0**.

### Como usar:

1. Acesse: [https://editor.swagger.io](https://editor.swagger.io)
2. Clique em **File > Import File** e selecione o arquivo `openapi.yaml` deste projeto.
3. O Swagger Editor irá carregar automaticamente a documentação completa da API.
4. Para testar as rotas protegidas, clique no botão **Authorize** no topo da interface.
5. Insira o token JWT no campo de autorização usando o formato `Bearer`.
6. Agora você pode testar todas as rotas diretamente pela interface do Swagger.

Isso facilita a visualização, documentação e testes das rotas disponíveis na API.


## Considerações

- O token JWT tem validade de 1 hora.
- Se o token estiver expirado ou ausente, a API retorna erro `401`.

