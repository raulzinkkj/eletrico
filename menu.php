<?php
session_start();

if (!isset($_SESSION['id'])) {
    header("Location:index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <header>
        <a href="api/logout.php">🚪Sair</a>
    </header>
    <section>
        <nav>
            <ul>
                <li><a href="eletrica2.0/lei_de_ohm/index.php">Triângulo da Lei de Ohm</a></li>
                <li><a href="eletrica2.0/potencia/index.php">Triângulo da Potência</a></li>
                <li><a href="eletrica2.0/resistores/index.php">Associação de Resistores</a></li>
            </ul>
        </nav>
    </section>
</body>
</html>