<?php
class PlanosModel {
    private $planos;

    public function __construct($planosJsonFile) {
        $planosJson = file_get_contents($planosJsonFile);
        $this->planos = json_decode($planosJson, true);
    }

    public function getPlanoById($id) {
        foreach ($this->planos as $plano) {
            if ($plano['codigo'] == $id) {
                return $plano;
            }
        }
        return null;
    }

    public function getAllPlanos(){
        return $this->planos; 
    }
}
?>
