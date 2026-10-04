# Food Delivery

> Plataforma web para gerenciamento de restaurantes, cardápios, clientes, pedidos, entregas e pagamentos.

[![PHP](https://img.shields.io/badge/PHP-8.1%2B-777BB4?logo=php&logoColor=white)](https://www.php.net/)
[![Arquitetura](https://img.shields.io/badge/arquitetura-MVC-blue)](#arquitetura)
[![Banco de dados](https://img.shields.io/badge/banco-MySQL%20%7C%20MariaDB-orange)](#requisitos)
[![License](https://img.shields.io/badge/license-GPL--3.0-red)](LICENSE)

---

## Sobre o projeto

O **Food Delivery** é uma plataforma de gestão e operação para negócios de alimentação que desejam centralizar, em um único sistema, o catálogo de produtos, o recebimento de pedidos e o acompanhamento da entrega.

O projeto está sendo desenvolvido sobre o framework [PHP MVC](https://github.com/eliasdossantos/php-mvc), uma base em PHP puro com arquitetura MVC, roteamento, autenticação, validação, acesso a banco de dados, migrations, seeders, upload de arquivos, logs e comandos de linha de comando.

A solução foi pensada para atender diferentes perfis de utilização:

- **Administradores:** configuração da plataforma, usuários, permissões e acompanhamento geral;

- **Restaurantes:** gestão da loja, categorias, produtos, complementos, horários e pedidos;

- **Clientes:** navegação pelo cardápio, montagem do carrinho, checkout e acompanhamento do pedido;

- **Entregadores:** visualização das entregas atribuídas e atualização do status da entrega.

> **Status:** em desenvolvimento. As funcionalidades descritas em “Visão do produto” representam o escopo da plataforma; consulte a implementação atual do código e o histórico de releases para saber o que já está disponível.

---

## Visão do produto

### Gestão de restaurantes

- Cadastro e edição dos dados do restaurante;
- Logo, capa, endereço e informações de contato;
- Horários de funcionamento e dias de atendimento;
- Controle de restaurante aberto, fechado ou em pausa;
- Taxa e região de entrega;
- Configurações operacionais do estabelecimento.

### Cardápios e produtos

- Cadastro de categorias e produtos;
- Nome, descrição, preço, imagem e disponibilidade;
- Produtos em destaque e promoções;
- Complementos, adicionais e grupos de opções;
- Organização e ordenação do cardápio;
- Controle de itens ativos, inativos e esgotados.

### Clientes

- Cadastro e autenticação de clientes;
- Perfil e dados de contato;
- Múltiplos endereços de entrega;
- Histórico de pedidos;
- Preferências e dados necessários para o checkout.

### Pedidos

- Carrinho de compras;
- Seleção de endereço e forma de pagamento;
- Cálculo de subtotal, taxa de entrega, descontos e total;
- Número identificador do pedido;
- Histórico de alterações de status;
- Acompanhamento do pedido pelo cliente e pelo restaurante;
- Cancelamento conforme regras de negócio;
- Observações para o restaurante e para a entrega.

### Entregas

- Organização das entregas por pedido;
- Associação de entregador;
- Status operacional da entrega;
- Registro de retirada e conclusão;
- Acompanhamento de ocorrências;
- Histórico para auditoria e atendimento.

### Pagamentos

- Registro da forma de pagamento escolhida;
- Controle do status da transação;
- Associação do pagamento ao pedido;
- Tratamento de aprovação, recusa, pendência e estorno;
- Camada preparada para integração com provedores externos.

> Integrações de pagamento devem ser configuradas somente quando o respectivo provedor estiver implementado no projeto. Nunca armazene dados sensíveis de cartão diretamente na aplicação sem seguir os requisitos de segurança e conformidade aplicáveis.

---

## Perfis e controle de acesso

A plataforma deve utilizar autorização por perfil e permissões para separar as responsabilidades de cada usuário:

| Perfil                | Responsabilidades principais                                               |
| --------------------- | -------------------------------------------------------------------------- |
| Administrador         | Gerenciar a plataforma, usuários, restaurantes, permissões e configurações |
| Gestor do restaurante | Administrar o restaurante, cardápio, pedidos e operação da loja            |
| Atendente             | Acompanhar e atualizar pedidos conforme as permissões concedidas           |
| Entregador            | Consultar entregas atribuídas e atualizar o andamento da entrega           |
| Cliente               | Consultar cardápios, realizar pedidos e acompanhar seu histórico           |

As permissões devem ser verificadas no servidor, por meio de middleware e regras de autorização. Esconder um botão na interface não substitui a proteção da rota ou da operação.

---

## Fluxo principal do pedido

```
Cliente acessa o restaurante
        ↓
Consulta o cardápio e monta o carrinho
        ↓
Informa endereço e forma de pagamento
        ↓
Pedido é criado e aguarda confirmação
        ↓
Restaurante confirma ou recusa o pedido
        ↓
Pedido entra em preparo
        ↓
Pedido é enviado para entrega
        ↓
Entregador realiza a entrega
        ↓
Pedido é concluído
```

Os nomes exatos dos status devem ser mantidos centralizados nas regras de domínio para evitar transições inválidas. Cada mudança relevante deve ser registrada com data, usuário responsável e observação quando aplicável.

---

## Arquitetura

O projeto utiliza uma arquitetura em camadas baseada em MVC:

- **Controllers:** recebem as requisições e coordenam o fluxo da aplicação;
- **Requests:** validam e normalizam os dados de entrada;
- **Services:** concentram regras de negócio e casos de uso;
- **Repositories:** encapsulam consultas e operações de persistência;
- **Models:** representam entidades e dados da aplicação;
- **Resources:** padronizam as respostas JSON da API;
- **Middlewares:** protegem rotas e aplicam regras transversais;
- **Views:** renderizam as páginas HTML;
- **Migrations:** versionam a estrutura do banco de dados;
- **Seeders:** criam dados iniciais e dados de desenvolvimento.

### Estrutura principal

```
food-delivery/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Api/
│   │   │   └── Web/
│   │   ├── Middlewares/
│   │   ├── Requests/
│   │   └── Resources/
│   ├── Models/
│   ├── Repositories/
│   ├── Services/
│   └── Support/
├── bootstrap/
├── cli/
├── config/
├── database/
│   ├── migrations/
│   └── seeds/
├── docs/
├── framework/
├── public/
├── resources/
│   └── views/
├── routes/
├── storage/
├── tests/
├── .env.example
├── composer.json
└── mvc
```

Controllers HTML ficam em `app/Http/Controllers/Web/` e controllers de API em `app/Http/Controllers/Api/`. As respostas JSON devem ser organizadas em `app/Http/Resources/`, enquanto as páginas HTML ficam em `resources/views/`.

---

## Requisitos

- PHP 8.1 ou superior;
- Composer 2 ou superior;
- MySQL 5.7+ ou MariaDB 10+;
- Extensão PDO;
- Extensão OpenSSL;
- Extensão Mbstring;
- Extensão JSON;
- Servidor web apontando o document root para `public/` em produção.

---

## Instalação

Clone o projeto e instale as dependências:

```bash
git clone <URL-DO-SEU-REPOSITORIO> food-delivery
cd food-delivery
composer install
```

Crie o arquivo de ambiente:

```bash
cp .env.example .env
php mvc key:generate
```

Configure o `.env` com os dados da aplicação e do banco:

```
APP_NAME="Food Delivery"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000
APP_KEY=

DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=food_delivery
DB_USERNAME=root
DB_PASSWORD=
```

Crie o banco de dados e execute as migrations:

```bash
php mvc migrate
```

Se houver seeders cadastrados para o ambiente de desenvolvimento:

```bash
php mvc seed:run
```

> Não utilize `migrate --fresh` em produção: o comando remove e recria a estrutura do banco, podendo causar perda de dados.

---

## Executando localmente

Inicie o servidor de desenvolvimento:

```bash
php mvc serve
```

Para escolher uma porta específica:

```bash
php mvc serve --port=8080
```

Depois, acesse a URL exibida pelo comando no navegador.

---

## Banco de dados

### Migrations

Migrations ficam em `database/migrations/` e definem a estrutura do banco de forma versionada:

```bash
php mvc make:migration create_restaurants_table
php mvc make:migration create_orders_table
php mvc migrate
```

Para desfazer o último lote:

```bash
php mvc migrate:rollback
```

Para recriar o banco durante o desenvolvimento:

```bash
php mvc migrate --fresh
```

Toda migration deve implementar corretamente `up( )` e `down()`. Alterações de schema devem ser revisadas antes de serem aplicadas em ambientes compartilhados ou de produção.

### Seeders

Use seeders para dados iniciais, perfis, permissões, categorias de exemplo e dados de teste:

```bash
php mvc make:seed RestaurantSeeder
php mvc seed:run
```

Para executar um seeder específico:

```bash
php mvc seed:run RestaurantSeeder
```

---

## Comandos úteis

### Geração de código

```bash
php mvc make:controller RestaurantController
php mvc make:model Restaurant
php mvc make:request StoreRestaurantRequest
php mvc make:service OrderService
php mvc make:repository OrderRepository
php mvc make:migration create_orders_table
php mvc make:seed OrderSeeder
php mvc make:view restaurants.index
```

### Informações e ajuda

```bash
php mvc list
php mvc help
```

### Testes

```bash
composer test
```

---

## API

A aplicação pode expor endpoints para clientes web, aplicativos móveis e integrações externas. Os endpoints devem:

- exigir autenticação quando acessarem dados privados;
- validar todos os dados recebidos;
- retornar respostas JSON consistentes;
- aplicar autorização por usuário, restaurante e recurso;
- usar paginação em listagens;
- não expor dados internos ou sensíveis;
- registrar eventos importantes para auditoria.

A documentação técnica disponível no projeto fica em:

```
docs/index.html
```

Consulte esse arquivo para os detalhes da infraestrutura do framework e da API implementada.

---

## Segurança

O projeto utiliza recursos de segurança da base PHP MVC, incluindo:

- proteção contra CSRF;
- prepared statements e prevenção contra SQL Injection;
- escape de saída para reduzir riscos de XSS;
- sessões protegidas;
- headers de segurança;
- rate limiting para operações sensíveis;
- autenticação e autorização por middleware;
- validação de uploads por MIME real;
- logs de erros e eventos.

Boas práticas adicionais para este domínio:

- nunca confiar em valores de preço enviados pelo cliente; recalcule o pedido no servidor;
- validar a disponibilidade e o preço atual dos produtos no momento da criação do pedido;
- impedir que um usuário acesse pedidos, endereços ou dados de outro usuário;
- restringir o acesso de cada restaurante aos seus próprios dados;
- não armazenar senha, token ou segredo em código-fonte;
- manter credenciais e chaves somente no ambiente de execução;
- usar HTTPS em produção;
- configurar origens CORS específicas, nunca `*`, quando a API for exposta;
- revisar logs para que não contenham dados de cartão ou outras informações sensíveis.

Em produção, configure origens exatas em `CORS_ALLOWED_ORIGINS`. Caso a aplicação esteja atrás de um proxy reverso, informe os IPs confiáveis em `TRUSTED_PROXIES`.

---

## Configurações de produção

Antes de publicar a aplicação:

1. Defina `APP_ENV=production`;
1. Desative o modo de debug (`APP_DEBUG=false`);
1. Configure uma `APP_KEY` segura;
1. Aponte o document root do servidor para `public/`;
1. Configure banco, e-mail, armazenamento e integrações por variáveis de ambiente;
1. Execute migrations revisadas e faça backup antes de alterações de schema;
1. Restrinja permissões de escrita a `storage/` e aos diretórios necessários;
1. Habilite HTTPS e cookies seguros;
1. Configure filas, webhooks e tarefas assíncronas caso sejam adotados;
1. Monitore erros, logs, falhas de pagamento e indisponibilidade de restaurantes.

---

## Contribuição

Contribuições são bem-vindas:

1. Faça um fork do projeto;

1. Crie uma branch para sua alteração;

1. Implemente a mudança seguindo a arquitetura existente;

1. Adicione ou atualize os testes necessários;

1. Verifique migrations, permissões e impactos de segurança;

1. Execute `composer test`;

1. Abra um Pull Request descrevendo o que foi alterado.

Para mudanças de banco, informe claramente a migration, possíveis impactos e o procedimento de rollback.

---

## Licenciamento de templates e recursos de terceiros

O código desenvolvido especificamente para esta aplicação e a arquitetura baseada no framework PHP MVC possuem autoria e condições de licenciamento próprias, conforme indicado neste README e no arquivo `LICENSE`.

Entretanto, **templates, temas, componentes visuais, ícones, imagens, fontes, bibliotecas, plugins e outros recursos de terceiros utilizados no projeto podem estar sujeitos a licenças diferentes**. A licença do framework ou do código autoral da aplicação não se estende automaticamente a esses materiais.

Antes de utilizar, modificar, redistribuir ou publicar qualquer template ou recurso incorporado ao sistema:

- consulte a licença original e os termos de uso do recurso;

- verifique se o uso comercial é permitido;

- observe as exigências de atribuição, créditos e distribuição da licença;

- mantenha os avisos de copyright e os arquivos de licença correspondentes;

- confirme se existem restrições para remoção de marca, redistribuição ou sublicenciamento;

- registre, quando possível, a origem, a versão e a licença de cada recurso utilizado.

É responsabilidade de quem distribui ou publica esta aplicação verificar a licença de cada template e dependência adicionada ao projeto. Em caso de conflito entre as condições de um recurso de terceiros e a licença do framework ou da aplicação, prevalecem as condições específicas aplicáveis ao respectivo recurso, dentro do seu escopo.

Consulte sempre a documentação e a página oficial do template utilizado. Não presuma que um template gratuito seja livre de restrições ou que possa ser redistribuído sem cumprir suas condições de licença.

---

## Versões e atualizações

Consulte as [Releases](https://github.com/eliasdossantos/php-mvc/releases) e o `CHANGELOG.md` antes de atualizar a base do framework. Uma atualização pode incluir correções de segurança, mudanças de API ou migrations que precisam ser executadas manualmente.

O projeto segue, quando aplicável, o [Semantic Versioning](https://semver.org/lang/pt-BR/):

- **PATCH:** correções compatíveis;

- **MINOR:** novas funcionalidades compatíveis;

- **MAJOR:** alterações que podem quebrar compatibilidade.

---

## Licença

Este projeto é distribuído sob a licença **GNU General Public License v3.0 (GPL-3.0)**.

Consulte o arquivo [LICENSE](LICENSE) para o texto completo da licença. A base técnica utilizada está disponível no repositório [eliasdossantos/php-mvc](https://github.com/eliasdossantos/php-mvc).

---

## Créditos

- Base do framework: [Elias dos Santos — PHP MVC](https://github.com/eliasdossantos/php-mvc);

- Infraestrutura: PHP, Composer, PDO, MySQL/MariaDB;

- Projeto: Food Delivery.
