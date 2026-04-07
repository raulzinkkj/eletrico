<?php
/**
 * Script para salvar cálculos de resistores no banco de dados.
 * Recebe dados via JSON POST.
 */
require "conexao.php";

// Lê o corpo da requisição JSON
$json = file_get_contents("php://input");
$data = json_decode($json, true);

// Validação básica dos dados recebidos
if (!$data || !isset($data['tipo']) || !isset($data['valores']) || !isset($data['resultado'])) {
    http_response_code(400);
    echo json_encode(["erro" => "Dados inválidos ou incompletos"]);
    exit;
}

try {
    // Prepara a query SQL (usando nomes de colunas do seu banco)
    $sql = "INSERT INTO resistores 
            (tipo_resistores, valores_resistores, resultado_resistores, resolvido_resistores)
            VALUES (:tipo, :valores, :resultado, 0)";

    $stmt = $conexao->prepare($sql);

    // No novo index, 'valores' é um objeto {serie: [], paralelo: []}
    // Vamos converter para JSON para salvar no banco
    $stmt->bindValue(":tipo", $data['tipo']);
    $stmt->bindValue(":valores", json_encode($data['valores']));
    $stmt->bindValue(":resultado", (float)$data['resultado']);

    if ($stmt->execute()) {
        echo json_encode(["status" => "OK", "id" => $conexao->lastInsertId()]);
    } else {
        throw new Exception("Falha ao executar a inserção.");
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["erro" => $e->getMessage()]);
}
