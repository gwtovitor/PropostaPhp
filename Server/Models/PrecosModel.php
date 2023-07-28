<?php

class PrecosModel {
    private $precos;

    public function __construct($precosJsonFile) {
        $precosJson = file_get_contents($precosJsonFile);
        $this->precos = json_decode($precosJson, true);
    }
    public function getPrecoByPlanoIdAndFaixa($planoId, $faixa, $numVidas) {
        $precosDoPlanoEncontrado = array();
        $maiorMinimoVidas = 0;
        $planoComMaiorMinimoVidas = null;
    
        foreach ($this->precos as $plano) {
            if ($plano['codigo'] == $planoId) {
                $precosDoPlanoEncontrado[] = $plano;
                $minimoVidas = $plano['minimo_vidas'];
                if ($minimoVidas > $maiorMinimoVidas) {
                    $maiorMinimoVidas = $minimoVidas;
                    $planoComMaiorMinimoVidas = $plano;
                }
            }
        }
        if ($numVidas >= $maiorMinimoVidas && $planoComMaiorMinimoVidas) {
            return $planoComMaiorMinimoVidas[$faixa];
        } elseif ($precosDoPlanoEncontrado) {
          
            return $precosDoPlanoEncontrado[0][$faixa];
        } else {
            return 'Nem um plano encontrado'; 
        }
    }
    public function getAllPrecos($planID) {
        $planosEncontrados = array();

        foreach ($this->precos as $precoCodigo) {
            if ($precoCodigo['codigo'] == $planID) {
                $planosEncontrados[] = $precoCodigo;
            }
        }

        return $planosEncontrados;
    }
    
    
}
?>
