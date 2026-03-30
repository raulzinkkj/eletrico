<?php
include 'conexao/conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $resistencia = $_POST['resistencia'];
    $corrente = $_POST['corrente'];
    $tipo = "T = ";
    $tensao = $resistencia * $corrente;

    $sql = "INSERT INTO ohm (resistencia, corrente, tensao, tipo) VALUES (:resistencia, :corrente, :tensao, :tipo)";

    $stmt = $conexao->prepare($sql);
    $stmt->bindParam(':resistencia', $resistencia);
    $stmt->bindParam(':corrente', $corrente);
    $stmt->bindParam(':tensao', $tensao);
    $stmt->bindParam(':tipo', $tipo);
    $stmt->execute();
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
    <div class="container">
        <form action="" method="post">
            <label for="resistencia">Resistência:</label>
            <input type="number" name="resistencia" id="">

            <label for="corrente">Corrente:</label>
            <input type="number" name="corrente" id="">

            <label for="tensao">Tensão:</label>
            <input type="text" name="tensao" value=" <?php echo $tensao ?>">

            <button type="submit">Calcular</button>

        </form>
    </div>
</body>

</html>