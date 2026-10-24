<?php

namespace App\Http\Requests\Expediente;

use App\Http\Requests\FormRequest;
use Framework\Auth\Auth;

/**
 * UpdateExpedienteRequest
 * ─────────────────────────────────────────────────────────────────────────────
 * Centraliza validação, sanitização e autorização do formulário de edição.
 *
 * O dia da semana e a descrição NÃO são editáveis (são 7 registros fixos criados
 * pelo seeder), por isso não entram aqui. Só funcionamento e horários.
 *
 * Regras de negócio:
 *   - aberto = 1 → abertura e fechamento são obrigatórios
 *   - aberto = 0 → todos os horários são descartados (gravados como null)
 *   - pausa: ou preenche início e fim, ou deixa os dois em branco
 *   - fechamento pode ser menor que a abertura (ex.: abre 18:00, fecha 02:00)
 */
class UpdateExpedienteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'aberto'       => 'required|in:0,1',
            'abertura'     => 'nullable|required_if:aberto,1|time',
            'fechamento'   => 'nullable|required_if:aberto,1|time',
            'pausa_inicio' => 'nullable|required_with:pausa_fim|time',
            'pausa_fim'    => 'nullable|required_with:pausa_inicio|time',
            'ativo'        => 'nullable|in:0,1',
        ];
    }

    public function messages(): array
    {
        return [
            'aberto.required'            => 'Informe se o estabelecimento abre nesse dia.',
            'aberto.in'                  => 'O funcionamento deve ser "Aberto" ou "Fechado".',
            'abertura.required_if'       => 'Informe o horário de abertura.',
            'abertura.time'              => 'O horário de abertura deve estar no formato HH:MM.',
            'fechamento.required_if'     => 'Informe o horário de fechamento.',
            'fechamento.time'            => 'O horário de fechamento deve estar no formato HH:MM.',
            'pausa_inicio.required_with' => 'Informe o início da pausa.',
            'pausa_inicio.time'          => 'O início da pausa deve estar no formato HH:MM.',
            'pausa_fim.required_with'    => 'Informe o fim da pausa.',
            'pausa_fim.time'             => 'O fim da pausa deve estar no formato HH:MM.',
            'ativo.in'                   => 'O status do expediente deve ser "Ativo" ou "Inativo".',
        ];
    }

    public function sanitize(): array
    {
        return [
            'aberto'       => trim((string) ($this->input['aberto'] ?? '1')),
            'abertura'     => $this->hora('abertura'),
            'fechamento'   => $this->hora('fechamento'),
            'pausa_inicio' => $this->hora('pausa_inicio'),
            'pausa_fim'    => $this->hora('pausa_fim'),
            'ativo'        => trim((string) ($this->input['ativo'] ?? '1')),
        ];
    }

    /** Horário enviado ou null quando em branco. */
    private function hora(string $campo): ?string
    {
        $valor = trim((string) ($this->input[$campo] ?? ''));

        return $valor === '' ? null : $valor;
    }
}
