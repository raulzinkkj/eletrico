<?php
/**
 * Script para alternar o status de 'resolvido' de um cálculo.
 * Recebe o ID via POST (formulário ou switch).
 */
require "conexao.php";

// Verifica se o ID foi fornecido via POST
if (!isset($_POST['id']) || empty($_POST['id'])) {
    header("Location: index.php?erro=id_ausente");
    exit;
}

$id = (int)$_POST['id'];

try {
    // Busca o status atual para inverter ou usa o operador NOT diretamente no SQL
    $stmt = $conexao->prepare("
        UPDATE resistores 
        SET resolvido_resistores = NOT resolvido_resistores 
        WHERE id_resistores = :id
    ");

    $stmt->execute([":id" => $id]);

    // Redireciona de volta para a página principal após o sucesso
    header("Location: index.php?sucesso=atualizado");

} catch (PDOException $e) {
    // Em caso de erro, podemos redirecionar com uma mensagem de erro
    header("Location: index.php?erro=" . urlencode($e->getMessage()));
}
exit;
