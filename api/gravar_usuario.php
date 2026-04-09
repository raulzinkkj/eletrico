<?php
include '../eletrica2.0/conexao/conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome_usuario = $_POST['nome_usuario'] ?? null;
    $senha_usuario = $_POST['senha_usuario'] ?? null;

    $sql = "INSERT INTO usuario(nome_usuario, senha_usuario) VALUES(:nome_usuario, :senha_usuario)";

    $stmt = $conexao->prepare($sql);
    $stmt->bindParam(':nome_usuario', $nome_usuario);
    $stmt->bindParam(':senha_usuario', $senha_usuario);
    $stmt->execute();

    header('Location:../index.php');
}
