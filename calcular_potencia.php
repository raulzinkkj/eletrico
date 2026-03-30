<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
$tensao = $_POST['tensao'];
$corrente = $_POST['corrente'];
$potencia = $tensao * $corrente;

$sql = "INSERT INTO potencia(resistencia, corrente, tensao)
        VALUES(:resistencia, :corrente, :tensao)";

    $stmt = $conexao->prepare($sql);
    $stmt->bindParam(':resistencia', $resistencia);
    $stmt->bindParam(':corrente', $corrente);
    $stmt->bindParam(':tensao', $tensao);
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
            <label for="tensao">Tensão:</label>
            <input type="number" name="tensao" id="">

            <label for="corrente">Corrente:</label>
            <input type="number" name="corrente" id="">

            <label for="resultado">Resultado:</label>
            <input type="text" name="resultado" value="<?php echo $potencia ?>">

            <button type="submit">Calcular</button>

        </form>
    </div>
</body>
</html>