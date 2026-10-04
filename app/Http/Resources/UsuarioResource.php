<?php

namespace App\Http\Resources;

/**
 * UsuarioResource — Formato exposto de um User pela API
 * Nunca inclui password, remember_token ou qualquer campo interno.
 */
class UsuarioResource extends ApiResource
{
    public function toArray(): array
    {
        return [
            'id'         => (int) $this->resource->id,
            'nome'       => $this->resource->nome,
            'email'      => $this->resource->email,
            'is_admin'     => $this->resource->is_admin,
            'ativo'      => (bool) $this->resource->ativo,
            'created_at' => $this->resource->created_at ?? null,
        ];
    }
}
