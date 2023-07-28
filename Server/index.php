<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: Content-Type");

// Função para carregar automaticamente as classes necessárias
spl_autoload_register(function ($className) {
    $filePath = './Controllers/' . $className . '.php';
    if (file_exists($filePath)) {
        require_once $filePath;
    }
});

$method = $_SERVER['REQUEST_METHOD'];

$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$requestUri = ltrim($requestUri, '/');

$urlParts = explode('/', $requestUri);

$urlParts = array_reverse($urlParts);

if ($method === 'POST') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $jsonData = file_get_contents('php://input');
        $data = json_decode($jsonData, true); 
    
        if (isset($data['beneficiarios']) && isset($data['planId'])) {
            $beneficiaries = $data['beneficiarios'];
            $planId = $data['planId'];
    
           
            require_once './Controllers/PropostaController.php';
            
            $propostaController = new PropostaController();
            $proposta = $propostaController->calcularProposta($beneficiaries, $planId);
    
            echo json_encode($proposta);
        } else {
            echo json_encode(['error' => 'Dados de beneficiários ou planId não fornecidos corretamente.']);
        }
    } 
} elseif ($method === 'GET' && isset($urlParts[0]) && $urlParts[0] === 'propostas') {
   
    require_once __DIR__.'/Views/PropostasView.php';
    echo retornaPropostas();

} elseif ($method === 'GET' && isset($urlParts[0]) && $urlParts[0] === 'planos'){
    require_once __DIR__.'/Views/PlansView.php';
    echo retornaPlanos();

}
 elseif ($method === 'GET'){
    echo 'Rota Inválida';

}else {
    echo json_encode(['error' => 'Método de requisição não permitido.']);
}
?>
