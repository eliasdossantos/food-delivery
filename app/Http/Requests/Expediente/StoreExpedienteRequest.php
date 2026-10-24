<?php

namespace App\Http\Requests\Expediente;

use App\Http\Requests\FormRequest;
use Framework\Auth\Auth;
use Framework\Http\Request;

/**
 * StoreExpedienteRequest
 * ─────────────────────────────────────────────────────────────────────────────
 * Centraliza validação, sanitização e autorização do formulário.
 *
 * Regras de negócio:
 *   - aberto = 1 → abertura e fechamento são obrigatórios
 *   - aberto = 0 → todos os horários são descartados (gravados como null)
 *   - pausa: ou preenche início e fim, ou deixa os dois em branco
 *   - fechamento pode ser menor que a abertura (ex.: abre 18:00, fecha 02:00)
 */
class StoreExpedienteRequest extends FormRequest
{
    private const DIAS = [
        0 => 'Domingo',
        1 => 'Segunda-feira',
        2 => 'Terça-feira',
        3 => 'Quarta-feira',
        4 => 'Quinta-feira',
        5 => 'Sexta-feira',
        6 => 'Sábado',
    ];

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'dia_semana'    => 'required|in:0,1,2,3,4,5,6|unique:expediente,dia_semana',
            'dia_descricao' => 'nullable|max:200',
            'aberto'        => 'required|in:0,1',
            'abertura'      => 'nullable|required_if:aberto,1|time',
            'fechamento'    => 'nullable|required_if:aberto,1|time',
            'pausa_inicio'  => 'nullable|required_with:pausa_fim|time',
            'pausa_fim'     => 'nullable|required_with:pausa_inicio|time',
            'ativo'         => 'nullable|in:0,1',
        ];
    }

    public function messages(): array
    {
        return [
            'dia_semana.required'      => 'O dia da semana é obrigatório.',
            'dia_semana.in'            => 'O dia da semana deve ser um valor de 0 (domingo) a 6 (sábado).',
            'dia_semana.unique'        => 'Já existe um expediente cadastrado para esse dia da semana.',
            'dia_descricao.max'        => 'A descrição do dia deve ter no máximo 200 caracteres.',
            'aberto.required'          => 'Informe se o estabelecimento abre nesse dia.',
            'aberto.in'                => 'O funcionamento deve ser "Aberto" ou "Fechado".',
            'abertura.required_if'     => 'Informe o horário de abertura.',
            'abertura.time'            => 'O horário de abertura deve estar no formato HH:MM.',
            'fechamento.required_if'   => 'Informe o horário de fechamento.',
            'fechamento.time'          => 'O horário de fechamento deve estar no formato HH:MM.',
            'pausa_inicio.required_with' => 'Informe o início da pausa.',
            'pausa_inicio.time'        => 'O início da pausa deve estar no formato HH:MM.',
            'pausa_fim.required_with'  => 'Informe o fim da pausa.',
            'pausa_fim.time'           => 'O fim da pausa deve estar no formato HH:MM.',
            'ativo.in'                 => 'O status do expediente deve ser "Ativo" ou "Inativo".',
        ];
    }

    public function sanitize(): array
    {
        $diaSemana = trim((string) ($this->input['dia_semana'] ?? ''));
        $aberto    = trim((string) ($this->input['aberto'] ?? '1'));

        // Sem descrição digitada, usa o nome do dia da semana
        $descricao = trim(Request::sanitizeValue($this->input['dia_descricao'] ?? ''));
        if ($descricao === '' && ctype_digit($diaSemana)) {
            $descricao = self::DIAS[(int) $diaSemana] ?? '';
        }

        // Dia fechado: descarta qualquer horário enviado
        $fechado = $aberto === '0';

        return [
            'dia_semana'    => $diaSemana,
            'dia_descricao' => $descricao,
            'aberto'        => $aberto,
            'abertura'      => $fechado ? null : $this->hora('abertura'),
            'fechamento'    => $fechado ? null : $this->hora('fechamento'),
            'pausa_inicio'  => $fechado ? null : $this->hora('pausa_inicio'),
            'pausa_fim'     => $fechado ? null : $this->hora('pausa_fim'),
            'ativo' => trim(
                $this->input['ativo'] ?? '1'
            ),
        ];
    }

    /** Horário enviado ou null quando em branco. */
    private function hora(string $campo): ?string
    {
        $valor = trim((string) ($this->input[$campo] ?? ''));

        return $valor === '' ? null : $valor;
    }
}
