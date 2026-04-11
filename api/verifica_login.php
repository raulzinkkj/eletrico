<?php
session_start();

require '../eletrica2.0/conexao/conexao.php';

$nome_usuario = $_POST['nome_usuario'];
$senha_usuario = $_POST['senha_usuario'];

$sql = "SELECT * FROM usuario WHERE nome_usuario = :nome_usuario";
$stmt = $conexao->prepare($sql);
$stmt->bindValue(":nome_usuario", $nome_usuario);
$stmt->execute();

$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user) {
    $_SESSION['nome_usuario'] = $user['nome_usuario'];
    $_SESSION['id'] = $user['id_usuario'];

    header("Location:../menu.php");
    exit;
} else {
    echo "Usuario ou senha inválidos!";
}
?>