# Guia de Contribuição

Obrigado por considerar contribuir com o **PHP MVC Boilerplate**!

Este projeto tem como objetivo fornecer uma base sólida, organizada e reutilizável para aplicações desenvolvidas em PHP puro utilizando o padrão MVC.

> **Importante:** o código-fonte deste projeto está disponível para uso e estudo, mas **não** é open source. Alterações, forks e Pull Requests dependem de **autorização prévia e por escrito do autor**, conforme o arquivo [LICENSE](LICENSE). Leia a seção [Licença](#licença) antes de começar.

## Código de Conduta

Ao participar deste projeto, esperamos que todos os colaboradores mantenham um ambiente respeitoso, profissional e colaborativo.

## Como Contribuir

### 1. Reportar Bugs

Encontrou um problema?

Abra uma issue em:

https://github.com/eliasdossantos/php-mvc/issues

Inclua as seguintes informações:

- Descrição clara do problema;
- Passos para reproduzir;
- Comportamento esperado;
- Comportamento atual;
- Versão do PHP;
- Sistema operacional;
- Versão do projeto.

Reportar bugs não exige autorização prévia.

### 2. Sugerir Melhorias

Antes de iniciar qualquer alteração, abra uma issue descrevendo sua proposta.

Isso permite discutir a implementação, manter a consistência do projeto e **obter a autorização do autor**, que é necessária para o próximo passo.

### 3. Enviar Pull Requests

Só envie um Pull Request depois que o autor autorizar a alteração na issue correspondente. Com a autorização, você pode criar um fork e modificar o código **exclusivamente** para preparar e enviar essa contribuição a este repositório.

```bash
# Com a autorização do autor, faça um fork do projeto

# Clone seu fork
git clone https://github.com/SEU_USERNAME/php-mvc.git

# Entre no diretório
cd php-mvc

# Instale as dependências
composer install

# Crie uma branch para sua alteração
git checkout -b feat/minha-feature

# Faça suas alterações

# Commit
git commit -m "feat: adiciona nova funcionalidade"

# Envie para seu fork
git push origin feat/minha-feature
```

Após isso, abra um Pull Request para a branch `main`, referenciando a issue em que a alteração foi autorizada.

O fork não pode ser usado, publicado ou distribuído para nenhuma outra finalidade. Pull Requests enviados sem autorização prévia podem ser fechados sem análise.

## Padrões de Código

Este projeto segue as seguintes convenções:

- PSR-12;
- Código limpo e legível;
- Separação adequada de responsabilidades;
- Uso de namespaces;
- DocBlocks em classes e métodos públicos;
- Comentários apenas quando realmente necessários.

## Estrutura do Projeto

Ao contribuir, mantenha a organização existente e os namespaces PSR-4 correspondentes:

- Controllers Web: `app/Http/Controllers/Web/` (`App\Http\Controllers\Web`);
- Controllers de API: `app/Http/Controllers/Api/` (`App\Http\Controllers\Api`);
- Middlewares, requests e recursos da API: `app/Http/Middlewares/`, `app/Http/Requests/` e `app/Http/Resources/`;
- Models, repositories e services: `app/Models/`, `app/Repositories/` e `app/Services/`;
- Helpers da aplicação: `app/Support/Helpers/`;
- Views HTML: `resources/views/`; a chamada `home.index` corresponde a `resources/views/home/index.php`;
- Classes do framework: organize por responsabilidade em `framework/Core/`, `framework/Http/`, `framework/Database/`, `framework/Auth/`, `framework/Support/` e `framework/Interfaces/`;
- Stubs dos geradores: `cli/Stubs/`, dentro da subpasta da categoria correspondente.

Mantenha regras de fluxo nos controllers, acesso a dados nos models/repositories, regras de negócio nos services e apresentação nas views. Não crie arquivos ou diretórios vazios apenas para espelhar uma estrutura de referência: adicione componentes quando fizerem parte de uma funcionalidade implementada.

---

## Testes

Sempre que possível:

- Adicione testes para novas funcionalidades;
- Certifique-se de que funcionalidades existentes continuam funcionando;
- Execute os testes antes de enviar o Pull Request.

```bash
composer test
```

## Commits

Utilize o padrão Conventional Commits:

| Prefixo   | Descrição                             |
| --------- | ------------------------------------- |
| feat:     | Nova funcionalidade                   |
| fix:      | Correção de bug                       |
| docs:     | Alterações na documentação            |
| refactor: | Refatoração sem alterar comportamento |
| test:     | Inclusão ou alteração de testes       |
| chore:    | Manutenção e tarefas internas         |
| perf:     | Melhorias de desempenho               |
| style:    | Ajustes de formatação                 |

Exemplos:

```bash
git commit -m "feat: adiciona sistema de cache"
git commit -m "fix: corrige validação de upload"
git commit -m "docs: atualiza README"
```

## Licença

Este projeto é distribuído sob uma licença própria de uso e estudo (source-available), com todos os direitos reservados. Os termos completos estão no arquivo [LICENSE](LICENSE).

Ao enviar uma contribuição (issue, Pull Request ou qualquer código), você concede ao autor uma licença perpétua, mundial, gratuita e irrevogável para usar, modificar e distribuir essa contribuição como parte do projeto, inclusive sob outras licenças.

Para solicitar autorização de cópia, modificação ou redistribuição, entre em contato com o autor pelo GitHub: [@eliasdossantos](https://github.com/eliasdossantos).
