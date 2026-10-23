<?php

namespace Database\Seeders;

use App\Models\BairroModel;
use Framework\Database\Seeder;

/**
 * BairroSeeder — inicializa dados associados ao Model Bairro.
 *
 * Antes de executar, confirme que o Model existe, aponta para a tabela
 * `bairros` e permite os campos usados por meio de $fillable:
 *   ['nome', 'slug', 'cidade', 'valor_entrega', 'ativo']
 */
class BairroSeeder extends Seeder
{
    public function run(): void
    {
        $cidade = 'João Pessoa';

        $bairros = [
            // Zona Sul
            ['nome' => 'Agua Fria',                   'slug' => 'agua-fria'],
            ['nome' => 'Anatolia',                    'slug' => 'anatolia'],
            ['nome' => 'Bairro das Industrias',       'slug' => 'bairro-das-industrias'],
            ['nome' => 'Bancarios',                   'slug' => 'bancarios'],
            ['nome' => 'Barra de Gramame',            'slug' => 'barra-de-gramame'],
            ['nome' => 'Castelo Branco',              'slug' => 'castelo-branco'],
            ['nome' => 'Cidade dos Colibris',         'slug' => 'cidade-dos-colibris'],
            ['nome' => 'Costa do Sol',                'slug' => 'costa-do-sol'],
            ['nome' => 'Costa e Silva',               'slug' => 'costa-e-silva'],
            ['nome' => 'Cuia',                        'slug' => 'cuia'],
            ['nome' => 'Distrito Industrial',         'slug' => 'distrito-industrial'],
            ['nome' => 'Ernani Satyro',               'slug' => 'ernani-satyro'],
            ['nome' => 'Ernesto Geisel',              'slug' => 'ernesto-geisel'],
            ['nome' => 'Funcionarios',                'slug' => 'funcionarios'],
            ['nome' => 'Grotao',                      'slug' => 'grotao'],
            ['nome' => 'Jardim Cidade Universitaria', 'slug' => 'jardim-cidade-universitaria'],
            ['nome' => 'Jardim Sao Paulo',            'slug' => 'jardim-sao-paulo'],
            ['nome' => 'Joao Paulo II',               'slug' => 'joao-paulo-ii'],
            ['nome' => 'Jose Americo',                'slug' => 'jose-americo'],
            ['nome' => 'Mangabeira',                  'slug' => 'mangabeira'],
            ['nome' => 'Mucumagro',                   'slug' => 'mucumagro'],
            ['nome' => 'Mumbaba',                     'slug' => 'mumbaba'],
            ['nome' => 'Mussure',                     'slug' => 'mussure'],
            ['nome' => 'Paratibe',                    'slug' => 'paratibe'],
            ['nome' => 'Penha',                       'slug' => 'penha'],
            ['nome' => 'Planalto da Boa Esperanca',   'slug' => 'planalto-da-boa-esperanca'],
            ['nome' => 'Portal do Sol',               'slug' => 'portal-do-sol'],
            ['nome' => 'Valentina',                   'slug' => 'valentina'],

            // Zona Leste
            ['nome' => 'Aeroclube',                   'slug' => 'aeroclube'],
            ['nome' => 'Altiplano Cabo Branco',       'slug' => 'altiplano-cabo-branco'],
            ['nome' => 'Bessa',                       'slug' => 'bessa'],
            ['nome' => 'Brisamar',                    'slug' => 'brisamar'],
            ['nome' => 'Cabo Branco',                 'slug' => 'cabo-branco'],
            ['nome' => 'Expedicionarios',             'slug' => 'expedicionarios'],
            ['nome' => 'Jardim Oceania',              'slug' => 'jardim-oceania'],
            ['nome' => 'Joao Agripino',               'slug' => 'joao-agripino'],
            ['nome' => 'Manaira',                     'slug' => 'manaira'],
            ['nome' => 'Miramar',                     'slug' => 'miramar'],
            ['nome' => 'Ponta do Seixas',             'slug' => 'ponta-do-seixas'],
            ['nome' => 'Quadramares',                 'slug' => 'quadramares'],
            ['nome' => 'Sao Jose',                    'slug' => 'sao-jose'],
            ['nome' => 'Tambau',                      'slug' => 'tambau'],
            ['nome' => 'Tambauzinho',                 'slug' => 'tambauzinho'],

            // Zona Norte
            ['nome' => 'Alto do Ceu',                 'slug' => 'alto-do-ceu'],
            ['nome' => 'Bairro dos Estados',          'slug' => 'bairro-dos-estados'],
            ['nome' => 'Bairro dos Ipes',             'slug' => 'bairro-dos-ipes'],
            ['nome' => 'Centro',                      'slug' => 'centro'],
            ['nome' => 'Jardim 13 de Maio',           'slug' => 'jardim-13-de-maio'],
            ['nome' => 'Mandacaru',                   'slug' => 'mandacaru'],
            ['nome' => 'Padre Ze',                    'slug' => 'padre-ze'],
            ['nome' => 'Pedro Gondim',                'slug' => 'pedro-gondim'],
            ['nome' => 'Roger',                       'slug' => 'roger'],
            ['nome' => 'Tambia',                      'slug' => 'tambia'],
            ['nome' => 'Torre',                       'slug' => 'torre'],
            ['nome' => 'Varadouro',                   'slug' => 'varadouro'],

            // Zona Oeste
            ['nome' => 'Alto do Mateus',              'slug' => 'alto-do-mateus'],
            ['nome' => 'Cristo Redentor',             'slug' => 'cristo-redentor'],
            ['nome' => 'Cruz das Armas',              'slug' => 'cruz-das-armas'],
            ['nome' => 'Ilha do Bispo',               'slug' => 'ilha-do-bispo'],
            ['nome' => 'Jaguaribe',                   'slug' => 'jaguaribe'],
            ['nome' => 'Jardim Veneza',               'slug' => 'jardim-veneza'],
            ['nome' => 'Oitizeiro',                   'slug' => 'oitizeiro'],
            ['nome' => 'Rangel',                      'slug' => 'rangel'],
            ['nome' => 'Trincheiras',                 'slug' => 'trincheiras'],
            ['nome' => 'Varjao',                      'slug' => 'varjao'],
        ];

        $model = new BairroModel();

        foreach ($bairros as $bairro) {
            $model->firstOrCreate(
                ['cidade' => $cidade, 'slug' => $bairro['slug']],
                [
                    'nome'          => $bairro['nome'],
                    'valor_entrega' => 7.50,
                    'ativo'         => 1,
                ]
            );
        }
    }
}
