$(function () {
  // ============================================================
  // VALORES / FINANCEIRO
  // ============================================================

  $(".dinheiro").mask("#.##0,00", {
    reverse: true,
  });

  $(".dinheiro-inteiro").mask("#.##0", {
    reverse: true,
  });

  $(".porcentagem").mask("##0,00", {
    reverse: true,
  });

  $(".decimal").mask("#.##0,00", {
    reverse: true,
  });

  $(".quantidade").mask("#.##0", {
    reverse: true,
  });

  // ============================================================
  // DOCUMENTOS PESSOAIS
  // ============================================================

  // CPF
  $(".cpf").mask("000.000.000-00");

  // CNPJ
  $(".cnpj").mask("00.000.000/0000-00");

  // PIS / PASEP
  $(".pis-pasep").mask("000.00000.00-0");

  // NIS
  $(".nis").mask("000.00000.00-0");

  // CNH
  $(".cnh").mask("00000000000");

  // Título de eleitor
  $(".titulo-eleitor").mask("0000 0000 0000");

  // Certidão de nascimento / casamento
  $(".certidao").mask("000000 00 00 0000 0 00000 000 0000000-00");

  // ============================================================
  // ENDEREÇO
  // ============================================================

  // CEP
  $(".cep").mask("00000-000");

  // UF
  $(".uf").mask("AA");

  // Código IBGE
  $(".codigo-ibge").mask("0000000");

  // Número do endereço
  $(".numero").mask("0#");

  // Bloco
  $(".bloco").mask("0#");

  // Apartamento
  $(".apartamento").mask("0#");

  // Sala
  $(".sala").mask("0#");

  // Andar
  $(".andar").mask("0#");

  // ============================================================
  // TELEFONE / CONTATO
  // ============================================================

  var telefoneMaskBehavior = function (valor) {
    var numeros = valor.replace(/\D/g, "");

    return numeros.length === 11 ? "(00) 00000-0000" : "(00) 0000-00009";
  };

  var telefoneMaskOptions = {
    onKeyPress: function (valor, evento, campo, opcoes) {
      campo.mask(telefoneMaskBehavior.apply({}, arguments), opcoes);
    },
  };

  // Telefone fixo ou celular
  $(".telefone").mask(telefoneMaskBehavior, telefoneMaskOptions);

  // Celular
  $(".celular").mask("(00) 00000-0000");

  // Telefone fixo
  $(".telefone-fixo").mask("(00) 0000-0000");

  // DDD
  $(".ddd").mask("00");

  // Ramal
  $(".ramal").mask("0000");

  // ============================================================
  // DATA / HORA
  // ============================================================

  // Data
  $(".data").mask("00/00/0000");

  // Data e hora
  $(".data-hora").mask("00/00/0000 00:00");

  // Hora
  $(".hora").mask("00:00");

  // Mês
  $(".mes").mask("00");

  // Ano
  $(".ano").mask("0000");

  // Mês / Ano
  $(".mes-ano").mask("00/0000");

  // ============================================================
  // VEÍCULOS
  // ============================================================

  // Placa antiga
  $(".placa-antiga").mask("AAA-0000");

  // Placa Mercosul
  $(".placa").mask("AAA-0A00", {
    translation: {
      A: {
        pattern: /[A-Za-z]/,
      },
    },
  });

  // RENAVAM
  $(".renavam").mask("00000000000");

  // Ano do veículo
  $(".ano-veiculo").mask("0000");

  // Quilometragem
  $(".quilometragem").mask("#.##0", {
    reverse: true,
  });

  // Chassi
  $(".chassi").mask("AAAAAAAAAAAAAAAAA", {
    translation: {
      A: {
        pattern: /[A-Za-z0-9]/,
      },
    },
  });

  // ============================================================
  // DADOS BANCÁRIOS
  // ============================================================

  // Código do banco
  $(".codigo-banco").mask("000");

  // Agência
  $(".agencia").mask("0000-0");

  // Conta
  $(".conta").mask("00000000-0");

  // Operação bancária
  $(".operacao-bancaria").mask("000");

  // Dígito
  $(".digito").mask("0");

  // ============================================================
  // CARTÃO
  // ============================================================

  // Número do cartão
  $(".cartao").mask("0000 0000 0000 0000");

  // Mês de validade
  $(".cartao-mes").mask("00");

  // Ano de validade
  $(".cartao-ano").mask("0000");

  // CVV
  $(".cartao-cvv").mask("0000");

  // ============================================================
  // EMPRESAS
  // ============================================================

  // Inscrição Estadual
  $(".inscricao-estadual").mask("000000000000");

  // Inscrição Municipal
  $(".inscricao-municipal").mask("000000000000");

  // Matrícula
  $(".matricula").mask("0000000000");

  // Código da empresa
  $(".codigo-empresa").mask("0000000000");

  // Código da unidade
  $(".codigo-unidade").mask("00000000000");

  // ============================================================
  // SAÚDE
  // ============================================================

  // Cartão SUS / CNS
  $(".cartao-sus").mask("000 0000 0000 0000");

  $(".cns").mask("000 0000 0000 0000");

  // CRM
  $(".crm").mask("000000");

  // COREN
  $(".coren").mask("000000");

  // CRO
  $(".cro").mask("000000");

  // Registro profissional
  $(".registro-profissional").mask("000000");

  // ============================================================
  // CÓDIGOS
  // ============================================================

  // Código numérico
  $(".codigo").mask("0#");

  // Código de verificação
  $(".codigo-verificacao").mask("000000");

  // Código de segurança
  $(".codigo-seguranca").mask("000000");

  // PIN
  $(".pin").mask("000000");

  // Token numérico
  $(".token").mask("000000");

  // Código de rastreamento dos Correios
  $(".codigo-rastreio").mask("AA000000000AA");

  // ============================================================
  // MEDIDAS
  // ============================================================

  // Peso
  $(".peso").mask("#.##0,00", {
    reverse: true,
  });

  // Altura
  $(".altura").mask("0,00");

  // Comprimento
  $(".comprimento").mask("#.##0,00", {
    reverse: true,
  });

  // Largura
  $(".largura").mask("#.##0,00", {
    reverse: true,
  });

  // Distância
  $(".distancia").mask("#.##0,00", {
    reverse: true,
  });

  // ============================================================
  // REDE / COMPUTADOR
  // ============================================================

  // IPv4
  $(".ip").mask("000.000.000.000");

  // Porta
  $(".porta").mask("00000");

  // MAC Address
  $(".mac-address").mask("AA:AA:AA:AA:AA:AA", {
    translation: {
      A: {
        pattern: /[A-Fa-f0-9]/,
      },
    },
  });

  // ============================================================
  // CAMPOS GENÉRICOS
  // ============================================================

  // Somente números
  $(".somente-numeros").mask("0#");

  // Número inteiro
  $(".numero-inteiro").mask("#.##0", {
    reverse: true,
  });

  // Número decimal
  $(".numero-decimal").mask("#.##0,00", {
    reverse: true,
  });

  // Campo com seleção automática ao receber foco
  $(".selecionar-ao-focar").mask("00000000", {
    selectOnFocus: true,
  });

  // ============================================================
  // LIMITE DE CARACTERES NUMÉRICOS
  // ============================================================

  // 2 dígitos
  $(".dois-digitos").mask("00");

  // 3 dígitos
  $(".tres-digitos").mask("000");

  // 4 dígitos
  $(".quatro-digitos").mask("0000");

  // 5 dígitos
  $(".cinco-digitos").mask("00000");

  // 6 dígitos
  $(".seis-digitos").mask("000000");

  // 8 dígitos
  $(".oito-digitos").mask("00000000");

  // 10 dígitos
  $(".dez-digitos").mask("0000000000");

  // 11 dígitos
  $(".onze-digitos").mask("00000000000");

  // 14 dígitos
  $(".quatorze-digitos").mask("00000000000000");

  // ============================================================
  // MÁSCARAS ALFANUMÉRICAS
  // ============================================================

  // Somente letras
  $(".somente-letras").mask("A#", {
    translation: {
      A: {
        pattern: /[A-Za-zÀ-ÿ]/,
      },
    },
  });

  // Alfanumérico
  $(".alfanumerico").mask("A#", {
    translation: {
      A: {
        pattern: /[A-Za-z0-9À-ÿ]/,
      },
    },
  });
});
