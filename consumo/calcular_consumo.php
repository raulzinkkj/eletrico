<?php
include 'conexao/conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $potencia = $_POST['potencia'];
    $tempo = $_POST['tempo'];
    $consumo = $potencia * $tempo;

    $sql = "INSERT INTO consumo(potencia, tempo, consumo)
        VALUES(:potencia, :tempo, :consumo)";

    $stmt = $conexao->prepare($sql);
    $stmt->bindParam(':potencia', $potencia);
    $stmt->bindParam(':tempo', $tempo);
    $stmt->bindParam(':consumo', $consumo);
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
            <label for="potencia">Potência:</label>
            <input type="number" name="potencia" id="">

            <label for="tempo">Tempo:</label>
            <input type="number" name="tempo" id="">

            <label for="consumo">Consumo:</label>
            <input type="text" name="consumo" value="<?php echo $consumo ?>">

            <button type="submit">Calcular</button>
        </form>
    </div>
</body>

</html>