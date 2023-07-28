<?php

require_once __DIR__ . '/../Models/PlanosModel.php';
require_once __DIR__ . '/../Models/PrecosModel.php';


class PropostaController
{
    private $planosModel;
    private $precosModel;

    public function __construct()
    {
        $this->planosModel = new PlanosModel(__DIR__ . '/../db/plans.json');
        $this->precosModel = new PrecosModel(__DIR__ . '/../db/prices.json');
    }

    public function calcularProposta($beneficiarios, $planoId)
    {
        $proposta = [];
        $plano = $this->planosModel->getPlanoById($planoId);
    
        if (!$plano) {
            $proposta['erro'] = 'Plano não encontrado.';
            return $proposta;
        }
    
        // Obter o número de vidas para esse plano
        $numVidas = count($beneficiarios);
    
        $planoNome = $plano['nome']; // Nome do plano escolhido
        
        foreach ($beneficiarios as $beneficiario) {
            $idade = $beneficiario['idade'];
            if ($idade <= 17) {
                $faixa = 'faixa1';
            } elseif ($idade <= 40) {
                $faixa = 'faixa2';
            } else {
                $faixa = 'faixa3';
            }
            $precoPorFaixa = $this->precosModel->getPrecoByPlanoIdAndFaixa($planoId, $faixa, $numVidas);
    
            $nomeCodificado = base64_encode($beneficiario['nome']);
            $idadeCodificada = base64_encode($idade);
            $precoCodificado = base64_encode($precoPorFaixa);
            
            $proposta['beneficiarios'][] = [
                'nome' => $nomeCodificado,
                'idade' => $idadeCodificada,
                'preco' => $precoCodificado
            ];
        }
        
       
    
        $proposta['plano_nome'] = $planoNome;
    
        $propostaJson = json_encode($proposta);
        
        $propostaFilePath = './db/proposta.json';
        file_put_contents($propostaFilePath, $propostaJson);
    
        $todasPropostasFilePath = './db/todasPropostas.json';
        if (file_exists($todasPropostasFilePath)) {
            $todasPropostasJson = file_get_contents($todasPropostasFilePath);
            $todasPropostas = json_decode($todasPropostasJson, true);
        } else {
            $todasPropostas = array();
        }

        $proposta['plano_nome'] = $planoNome;
        $propostaReturn = $proposta;
      
         foreach ($propostaReturn['beneficiarios'] as &$beneficiario) {
            $beneficiario['nome'] = base64_decode($beneficiario['nome']);
            $beneficiario['idade'] = base64_decode($beneficiario['idade']);
            $beneficiario['preco'] = base64_decode($beneficiario['preco']);
        }
        $propostaReturn['preco_total'] = (array_sum(array_column($propostaReturn['beneficiarios'], 'preco')));
        $proposta['preco_total'] = base64_encode($propostaReturn['preco_total']) ;
        $proposta['plano_nome'] = base64_encode($propostaReturn['plano_nome']);
        $todasPropostas[] = $proposta;
        $todasPropostasJson = json_encode($todasPropostas);
        file_put_contents($todasPropostasFilePath, $todasPropostasJson);
        return  $propostaReturn;
    }   
    
}
?>