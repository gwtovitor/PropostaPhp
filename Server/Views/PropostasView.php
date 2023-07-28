<?php

function retornaPropostas()
{
    $propostas = file_get_contents(__DIR__.'/../db/todasPropostas.json');
    $data = json_decode($propostas, true);

    foreach ($data as &$proposta) {
        foreach ($proposta['beneficiarios'] as &$benef) {
            $benef['nome'] = base64_decode($benef['nome']);
            $benef['idade'] = base64_decode($benef['idade']);
            $benef['preco'] = base64_decode($benef['preco']);
        }
        $proposta['preco_total'] = base64_decode($proposta['preco_total']);
        $proposta['plano_nome'] = base64_decode($proposta['plano_nome']);
    
    }
    return json_encode($data, JSON_PRETTY_PRINT);
}




?>