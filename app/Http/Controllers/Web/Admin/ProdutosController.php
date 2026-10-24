<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Web\BaseController;
use App\Http\Requests\Produtos\StoreProdutosRequest;
use App\Http\Requests\Produtos\UpdateProdutosRequest;
use App\Repositories\CategoriaRepository;
use App\Repositories\ExtraRepository;
use App\Repositories\MedidaRepository;
use App\Repositories\ProdutoEspecificacaoRepository;
use App\Repositories\ProdutoExtraRepository;
use App\Repositories\ProdutoRepository;
use App\Services\BuscaNome\BuscaNomeService;
use Framework\Support\Session;

/**
 * ProdutosController
 * ─────────────────────────────────────────────────────────────────────────────
 * Responsável por receber as requisições HTTP, delegar para o Service/Repository
 * e retornar a resposta adequada (View ou JSON).
 *
 * Regra: controllers devem ser finos.
 * Lógica de negócio → Service | Acesso a dados → Repository
 *
 * Upload de arquivo (se o Model tiver algum campo de arquivo):
 *   Os blocos de upload em store()/update() estão comentados por padrão.
 *   Descomente e ajuste o nome do campo (ex: 'imagem', 'avatar', 'anexo').
 *   Ver Framework\Support\Upload e BaseController::saveUploadedFile()/replaceUploadedFile()
 *   para detalhes.
 */
class ProdutosController extends BaseController
{
    /**
     * Nome da entidade usada como subpasta em public/uploads.
     * Exemplo: 'produtos' gera arquivos em public/uploads/produtos/.
     */
    private const UPLOAD_ENTITY = 'produtos';

    /**
     * Tamanho máximo permitido para o upload, em megabytes (MB).
     * Exemplo: 5 permite arquivos de até 5 MB.
     */
    private const UPLOAD_MAX_MB = 5;

    private ProdutoRepository $produtoModel;
    private CategoriaRepository $categoriaModel;
    private ExtraRepository $extraModel;
    private ProdutoExtraRepository $produtoExtraModel;
    private MedidaRepository $medidaModel;
    private ProdutoEspecificacaoRepository $especificacaoModel;

    public function __construct()
    {
        $this->produtoModel = new ProdutoRepository();
        $this->categoriaModel = new CategoriaRepository();
        $this->extraModel = new ExtraRepository();
        $this->produtoExtraModel = new ProdutoExtraRepository();
        $this->medidaModel = new MedidaRepository();
        $this->especificacaoModel = new ProdutoEspecificacaoRepository();
    }

    /**
     * Lista todos os registros.
     * GET /produtos
     */
    public function index(): void
    {
        $data = [
            'titulo' => 'Produtos',
            'subtitulo' => 'Lista de produtos',
            'produtos' => $this->produtoModel->tudoComCategoria(),
            'extrasPorProduto' => $this->produtoExtraModel->getExtrasAgrupadosPorProduto(),
            'especificacoesPorProduto' => $this->especificacaoModel->getAgrupadasPorProduto(),
        ];

        $this->view('admin.produtos.index', $data, 'main');
    }

    /**
     * Exibe um registro específico.
     * GET /produtos/{id}
     */
    public function show(int $id): void
    {
        // findComCategoria traz também categoria_nome
        $produtos = $this->produtoModel->findComCategoria($id);
        $this->abortUnless((bool) $produtos, 404, 'Produto não encontrado.');

        $data = [
            'titulo'         => 'Produtos',
            'subtitulo'      => 'Detalhes do Produto',
            'produtos'       => $produtos,
            'extras'         => $this->produtoExtraModel->getExtrasDoProduto($id),
            'especificacoes' => $this->especificacaoModel->getDoProduto($id),
        ];

        $this->view('admin.produtos.show', $data, 'main');
    }

    /**
     * Exibe o formulário de criação.
     * GET /produtos/create
     */
    public function create(): void
    {
        $data = [
            'titulo'         => 'Novo Produtos',
            'subtitulo'      => 'Criar novo Produtos',
            'categorias'     => $this->categoriasDisponiveis(),
            'extras'         => $this->extraModel->getAtivas(),
            'extraIds'       => [],
            'medidas'        => $this->medidasDisponiveis(),
            'especificacoes' => [],
            'uploadMaxMb'    => self::UPLOAD_MAX_MB,
        ];

        $this->view('admin.produtos.create', $data, 'main');
    }

    /**
     * Processa a criação de um novo registro.
     * POST /produtos
     */
    public function store(StoreProdutosRequest $request): void
    {
        if ($request->fails()) {
            Session::flash('error', $request->firstError());
            Session::flashErrors($request->errors());
            Session::flashInput($request->all());

            $this->back();
            return;
        }

        $input = $request->all();

        // Extras selecionadas (IDs válidos e ativos)
        $extraIds = $this->validarExtraIds($input);

        if ($extraIds === false) {
            Session::flash('error', 'Uma ou mais extras selecionadas são inválidas ou estão inativas.');
            Session::flashInput($input);
            $this->back();
            return;
        }

        // Preços (com ou sem medida) e customizável.
        // No cadastro, é obrigatório informar ao menos um preço.
        $erroEspec = null;
        $especificacoes = $this->validarEspecificacoes($input, [], $erroEspec, true);

        if ($especificacoes === false) {
            Session::flash('error', $erroEspec);
            Session::flashErrors(['especificacoes' => [$erroEspec]]);
            Session::flashInput($input);
            $this->back();
            return;
        }

        $data = $this->normalizarCategoria($request->validated());

        // Esses campos pertencem às tabelas de vínculo, não a `produtos`
        unset($data['extra_ids'], $data['especificacoes']);

        if (!$this->categoriaPermitida($data['categoria_id'])) {
            Session::flash('error', 'A categoria selecionada é inválida ou está inativa.');
            Session::flashErrors(['categoria_id' => ['Categoria inválida.']]);
            Session::flashInput($request->all());

            $this->back();
            return;
        }

        // Slug: o digitado ou, em branco, gerado a partir do nome (sempre único)
        $slug = trim((string) ($data['slug'] ?? ''));
        $data['slug'] = $this->produtoModel->gerarSlugUnico($slug !== '' ? $slug : $data['nome']);

        // Código (SKU): o digitado ou, em branco, gerado a partir do nome
        $codigo = trim((string) ($data['codigo'] ?? ''));
        $data['codigo'] = $codigo !== ''
            ? $codigo
            : $this->produtoModel->gerarCodigoUnico($data['nome']);

        // ── Upload de arquivo ───────────
        $nomeArquivo = null;

        if (!empty($_FILES['imagem']['name'])) {
            $nomeArquivo = $this->saveUploadedFile(
                $_FILES['imagem'],
                self::UPLOAD_ENTITY,
                'image',
                self::UPLOAD_MAX_MB
            );

            if ($nomeArquivo === null) {
                Session::flash('error', $this->uploadErrors[0] ?? 'Falha no upload.');
                Session::flashInput($data);
                $this->back();
                return;
            }

            $data['imagem_url'] = $nomeArquivo;
        }

        $id = $this->produtoModel->create($data);

        if (!$id) {
            // Não deixa arquivo órfão em public/uploads se o registro não foi criado
            if ($nomeArquivo !== null) {
                $this->deleteUploadedFile(self::UPLOAD_ENTITY, $nomeArquivo);
            }

            $this->redirectWith('admin/produto', 'error', 'Não foi possível criar o produto.');
            return;
        }

        if (!$this->produtoExtraModel->sincronizar((int) $id, $extraIds)) {
            $this->redirectWith(
                'admin/produto',
                'error',
                'O produto foi criado, mas não foi possível salvar as extras.'
            );
            return;
        }

        if (!$this->especificacaoModel->sincronizar((int) $id, $especificacoes)) {
            $this->redirectWith(
                'admin/produto',
                'error',
                'O produto foi criado, mas não foi possível salvar os preços e medidas.'
            );
            return;
        }

        $this->redirectWith('admin/produto', 'success', 'Produto criado com sucesso!');
    }

    /**
     * Exibe o formulário de edição.
     * GET /produtos/{id}/edit
     */
    public function edit(int $id): void
    {
        $produtos = $this->produtoModel->findComCategoria($id);
        $this->abortUnless((bool) $produtos, 404, 'Produto não encontrado.');

        $especificacoes = $this->especificacaoModel->getDoProduto($id);

        $data = [
            'titulo'         => 'Produto',
            'subtitulo'      => 'Editar Produto',
            'produtos'       => $produtos,
            'uploadMaxMb'    => self::UPLOAD_MAX_MB,
            'categorias'     => $this->categoriasDisponiveis($produtos->categoria_id ?? null),
            'extras'         => $this->extraModel->getAtivas(),
            'extraIds'       => array_map(
                'intval',
                $this->produtoExtraModel->getIdsDasExtrasDoProduto($id)
            ),
            'medidas'        => $this->medidasDisponiveis(
                $this->idsDasMedidas($especificacoes)
            ),
            'especificacoes' => $especificacoes,
        ];

        $this->view('admin.produtos.edit', $data, 'main');
    }

    /**
     * Processa a atualização de um registro.
     * PUT /produtos/{id}
     */
    public function update(UpdateProdutosRequest $request, int $id): void
    {
        $produtos = $this->produtoModel->findById($id);
        $this->abortUnless((bool) $produtos, 404, 'Produto não encontrado.');

        if ($request->fails()) {
            Session::flash('error', $request->firstError());
            Session::flashErrors($request->errors());
            Session::flashInput($request->all());

            $this->back();
            return;
        }

        $input = $request->all();

        // Extras selecionadas (IDs válidos e ativos)
        $extraIds = $this->validarExtraIds($input);

        if ($extraIds === false) {
            Session::flash('error', 'Uma ou mais extras selecionadas são inválidas ou estão inativas.');
            Session::flashInput($input);
            $this->back();
            return;
        }

        // Preços e medidas (medidas já vinculadas podem continuar mesmo inativas).
        // Linhas sem medida (preço único) não entram na lista de medidas vinculadas.
        $atuais = $this->idsDasMedidas($this->especificacaoModel->getDoProduto($id));

        $erroEspec = null;
        $especificacoes = $this->validarEspecificacoes($input, $atuais, $erroEspec);

        if ($especificacoes === false) {
            Session::flash('error', $erroEspec);
            Session::flashErrors(['especificacoes' => [$erroEspec]]);
            Session::flashInput($input);
            $this->back();
            return;
        }

        $data = $this->normalizarCategoria($request->validated());

        // Esses campos pertencem às tabelas de vínculo, não a `produtos`
        unset($data['extra_ids'], $data['especificacoes']);

        // Mantém a categoria atual mesmo se ela estiver inativa; só valida se mudou
        $atual = isset($produtos->categoria_id) ? (int) $produtos->categoria_id : null;

        if (!$this->categoriaPermitida($data['categoria_id'], $atual)) {
            Session::flash('error', 'A categoria selecionada é inválida ou está inativa.');
            Session::flashErrors(['categoria_id' => ['Categoria inválida.']]);
            Session::flashInput($request->all());

            $this->back();
            return;
        }

        // Slug: só altera quando o nome do produto mudar.
        $nomeAtual = trim((string) $produtos->nome);
        $nomeNovo = trim((string) ($data['nome'] ?? $nomeAtual));

        if ($nomeNovo !== $nomeAtual) {
            $data['slug'] = $this->produtoModel->gerarSlugUnico(
                $nomeNovo,
                $id
            );
        } else {
            $data['slug'] = $produtos->slug;
        }

        // Código (SKU): o digitado; em branco, mantém o atual (ou gera, se o produto não tinha)
        $codigo = trim((string) ($data['codigo'] ?? ''));

        if ($codigo === '') {
            $codigo = ($produtos->codigo ?? '') !== ''
                ? $produtos->codigo
                : $this->produtoModel->gerarCodigoUnico($data['nome'], $id);
        }

        $data['codigo'] = $codigo;

        // ── Upload de arquivo ───────────
        if (!empty($_FILES['imagem']['name'])) {
            $nomeAntigo = $this->nomeArquivoLocal($produtos->imagem_url ?? null);

            $nomeNovo = $this->replaceUploadedFile(
                $_FILES['imagem'],
                self::UPLOAD_ENTITY,
                $nomeAntigo,
                'image',
                self::UPLOAD_MAX_MB
            );

            if ($nomeNovo === null) {
                Session::flash('error', $this->uploadErrors[0] ?? 'Falha no upload.');
                Session::flashInput($data);
                $this->back();
                return;
            }

            $data['imagem_url'] = $nomeNovo;
        }

        $atualizada = $this->produtoModel->update($id, $data);

        if (!$atualizada) {
            $this->redirectWith('admin/produto', 'error', 'Não foi possível atualizar o produto.');
            return;
        }

        if (!$this->produtoExtraModel->sincronizar($id, $extraIds)) {
            $this->redirectWith(
                'admin/produto',
                'error',
                'O produto foi atualizado, mas não foi possível salvar as extras.'
            );
            return;
        }

        if (!$this->especificacaoModel->sincronizar($id, $especificacoes)) {
            $this->redirectWith(
                'admin/produto',
                'error',
                'O produto foi atualizado, mas não foi possível salvar os preços e medidas.'
            );
            return;
        }

        $this->redirectWith('admin/produto', 'success', 'Produto atualizado com sucesso!');
    }

    /**
     * Remove um registro.
     * DELETE /produtos/{id}
     */
    public function destroy(int $id): void
    {
        $produtos = $this->produtoModel->findById($id);
        $this->abortUnless((bool) $produtos, 404, 'Produto não encontrado.');

        if (!$this->produtoModel->delete($id)) {
            $this->redirectWith('admin/produto', 'error', 'Não foi possível remover o produto.');
            return;
        }

        // Apaga a imagem junto (evita lixo em public/uploads)
        $nomeArquivo = $this->nomeArquivoLocal($produtos->imagem_url ?? null);

        if ($nomeArquivo !== null) {
            $this->deleteUploadedFile(self::UPLOAD_ENTITY, $nomeArquivo);
        }

        $this->redirectWith('admin/produto', 'success', 'Produto removido com sucesso.');
    }

    // ── Auxiliares ────────────────────────────────────────────────────────────

    /**
     * Autocomplete por nome.
     * GET /produtos/procurar?term=...
     */
    public function procurar(): never
    {
        $service = new BuscaNomeService($this->produtoModel, ['codigo']);

        $this->json($service->buscar($this->request->get('term', '')));
    }

    /**
     * Devolve o nome do arquivo apenas se ele for um upload local.
     * Registros antigos com URL externa (ex.: Unsplash) retornam null,
     * para nunca tentar apagar algo que não está em public/uploads.
     */
    private function nomeArquivoLocal(?string $imagem): ?string
    {
        if ($imagem === null || $imagem === '' || preg_match('#^https?://#i', $imagem)) {
            return null;
        }

        return $imagem;
    }

    /**
     * IDs das medidas de uma lista de especificações.
     * Linhas sem medida (preço único, medida_id NULL) são ignoradas.
     *
     * @return int[]
     */
    private function idsDasMedidas(array $especificacoes): array
    {
        $ids = [];

        foreach ($especificacoes as $especificacao) {
            if (!empty($especificacao->medida_id)) {
                $ids[] = (int) $especificacao->medida_id;
            }
        }

        return $ids;
    }

    /**
     * "Sem categoria" vira NULL (nunca '' em coluna inteira com FK)
     * e uma categoria escolhida vira inteiro.
     */
    private function normalizarCategoria(array $data): array
    {
        $valor = $data['categoria_id'] ?? null;

        $data['categoria_id'] = ($valor === null || $valor === '')
            ? null
            : (int) $valor;

        return $data;
    }

    /**
     * A categoria existe e está ativa? (ou é a que o produto já tinha)
     */
    private function categoriaPermitida(?int $categoriaId, ?int $atualId = null): bool
    {
        if ($categoriaId === null || $categoriaId === $atualId) {
            return true;
        }

        $categoria = $this->categoriaModel->findById($categoriaId);

        return $categoria && !empty($categoria->ativo);
    }

    /**
     * Categorias que aparecem no select. Se o produto já está numa categoria
     * inativa, ela é incluída para não "sumir" na edição.
     */
    private function categoriasDisponiveis(?int $incluirId = null): array
    {
        $categorias = $this->categoriaModel->getAtivas();

        if ($incluirId === null) {
            return $categorias;
        }

        foreach ($categorias as $categoria) {
            if ((int) $categoria->id === (int) $incluirId) {
                return $categorias;
            }
        }

        $atual = $this->categoriaModel->findById($incluirId);

        if ($atual) {
            $categorias[] = $atual;
        }

        return $categorias;
    }

    /**
     * Lê extra_ids[] do formulário, remove duplicados e valida os IDs.
     *
     * @return int[]|false Retorna false se algum ID for inválido ou inativo.
     */
    private function validarExtraIds(array $input): array|false
    {
        $valores = $input['extra_ids'] ?? [];

        if (!is_array($valores)) {
            return false;
        }

        $ids = [];

        foreach ($valores as $valor) {
            if (!filter_var($valor, FILTER_VALIDATE_INT) || (int) $valor < 1) {
                return false;
            }

            $ids[] = (int) $valor;
        }

        $ids = array_values(array_unique($ids));

        foreach ($ids as $extraId) {
            $extra = $this->extraModel->findById($extraId);

            if (!$extra || empty($extra->ativo)) {
                return false;
            }
        }

        return $ids;
    }

    /**
     * Medidas ativas para o select. Medidas já vinculadas ao produto entram
     * mesmo inativas, para não sumirem na edição.
     */
    private function medidasDisponiveis(array $incluirIds = []): array
    {
        $medidas = $this->medidaModel->getAtivas();
        $existentes = array_map(fn($m) => (int) $m->id, $medidas);

        foreach ($incluirIds as $id) {
            if (!in_array((int) $id, $existentes, true)) {
                $medida = $this->medidaModel->findById((int) $id);

                if ($medida) {
                    $medidas[] = $medida;
                }
            }
        }

        return $medidas;
    }

    /**
     * Lê especificacoes[i][medida_id|preco|customizavel] do formulário.
     *
     * Regras:
     *  - Linhas totalmente vazias (sem medida e sem preço) são ignoradas.
     *  - Em toda linha preenchida, o preço (maior que zero) é obrigatório.
     *  - A medida é OPCIONAL. Linha sem medida = produto de preço único
     *    (medida_id NULL, customizavel 0).
     *  - Só pode existir UMA linha sem medida, e ela não pode conviver
     *    com linhas que tenham medida (o produto é "preço único" ou "por medida").
     *
     * @param int[]       $medidasJaVinculadas medidas que podem continuar mesmo inativas
     * @param string|null $erro                recebe a mensagem de erro, se houver
     * @param bool        $exigirUma           exige ao menos uma linha com preço
     * @return array<int, array{medida_id:int|null, preco:float, customizavel:int}>|false
     */
    private function validarEspecificacoes(
        array $input,
        array $medidasJaVinculadas,
        ?string &$erro = null,
        bool $exigirUma = false
    ): array|false {
        $linhas = $input['especificacoes'] ?? [];

        if (!is_array($linhas)) {
            $erro = 'Preços e medidas inválidos.';
            return false;
        }

        $itens = [];
        $vistas = [];
        $temSemMedida = false;

        foreach ($linhas as $linha) {
            if (!is_array($linha)) {
                $erro = 'Preços e medidas inválidos.';
                return false;
            }

            $medidaId = trim((string) ($linha['medida_id'] ?? ''));
            $precoTxt = trim((string) ($linha['preco'] ?? ''));

            // Linha totalmente vazia: ignora
            if ($medidaId === '' && $precoTxt === '') {
                continue;
            }

            // Preço obrigatório em toda linha preenchida
            if ($precoTxt === '') {
                $erro = 'Informe o preço de todas as linhas.';
                return false;
            }

            // "1.234,56" → "1234.56"
            $normalizado = str_replace(',', '.', str_replace('.', '', $precoTxt));

            if (!is_numeric($normalizado) || (float) $normalizado <= 0) {
                $erro = 'O preço de cada linha deve ser maior que zero.';
                return false;
            }

            $preco = round((float) $normalizado, 2);

            // ── Sem medida: preço único do produto ──
            if ($medidaId === '') {
                if ($temSemMedida) {
                    $erro = 'Só é possível informar um preço sem medida.';
                    return false;
                }

                $temSemMedida = true;

                $itens[] = [
                    'medida_id'    => null,
                    'preco'        => $preco,
                    'customizavel' => 0, // customizável só faz sentido com medida
                ];

                continue;
            }

            // ── Com medida ──
            if (!filter_var($medidaId, FILTER_VALIDATE_INT) || (int) $medidaId < 1) {
                $erro = 'Medida inválida.';
                return false;
            }

            $medidaId = (int) $medidaId;

            if (isset($vistas[$medidaId])) {
                $erro = 'A mesma medida foi informada mais de uma vez.';
                return false;
            }

            $medida = $this->medidaModel->findById($medidaId);

            if (!$medida || (empty($medida->ativo) && !in_array($medidaId, $medidasJaVinculadas, true))) {
                $erro = 'Uma ou mais medidas selecionadas são inválidas ou estão inativas.';
                return false;
            }

            $vistas[$medidaId] = true;

            $itens[] = [
                'medida_id'    => $medidaId,
                'preco'        => $preco,
                'customizavel' => !empty($linha['customizavel']) ? 1 : 0,
            ];
        }

        // Preço único não convive com medidas
        if ($temSemMedida && count($itens) > 1) {
            $erro = 'Produto com preço sem medida deve ter apenas uma linha. '
                . 'Informe a medida em todas as linhas ou deixe só o preço único.';
            return false;
        }

        if ($exigirUma && empty($itens)) {
            $erro = 'Informe ao menos um preço (com ou sem medida).';
            return false;
        }

        return $itens;
    }
}