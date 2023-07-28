<?php
require_once __DIR__ . '/../Models/PlanosModel.php';
require_once __DIR__ . '/../Models/PrecosModel.php';

function retornaPlanos(){
    $planos = new PlanosModel(__DIR__.'/../db/plans.json');
    $precos = new PrecosModel(__DIR__.'/../db/prices.json');
    $newPlanos = $planos->getAllPlanos();
    $planosEncontrados = array();

    foreach ($newPlanos as $plano) {
        $codigo = $plano['codigo'];
        $precosDoPlano = $precos->getAllPrecos($codigo);
        $planosEncontrados[$plano['nome']] = $precosDoPlano;
    }

    return json_encode($planosEncontrados, JSON_PRETTY_PRINT);
}
?>
