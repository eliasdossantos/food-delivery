<?php

namespace App\Services\ValidacaoCPF;

use Framework\Core\Service;

class CpfService extends Service
{
    /** Remove tudo que não for dígito */
    public static function normalizar(?string $cpf): string
    {
        return preg_replace('/\D/', '', (string)$cpf);
    }

    /** Valida CPF (com ou sem máscara) pelos dígitos verificadores */
    public static function isValido(?string $cpf): bool
    {
        $cpf = self::normalizar($cpf);

        // 11 dígitos e não pode ser sequência repetida (000..., 111..., etc.)
        if (strlen($cpf) !== 11 || preg_match('/^(\d)\1{10}$/', $cpf)) {
            return false;
        }

        for ($t = 9; $t < 11; $t++) {
            $d = 0;
            for ($c = 0; $c < $t; $c++) {
                $d += (int)$cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;

            if ((int)$cpf[$t] !== $d) {
                return false;
            }
        }

        return true;
    }

    /** 
     * 12345678909 → 123.456.789-09. Se não tiver 11 dígitos, devolve o valor como veio (a validação barra) 
     * */
    public static function formatar(?string $cpf): string
    {
        $original = trim((string)$cpf);
        $digitos  = self::normalizar($original);

        return strlen($digitos) === 11
            ? preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $digitos)
            : $original;
    }
}
