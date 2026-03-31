<?php
include 'conexao/conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tensao = $_POST['tensao'];
    $resistencia = $_POST['resistencia'];
    $tipo = "C = ";
    $corrente = $tensao / $resistencia;

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
    <style>
        * {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: rgb(255, 255, 216);
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            padding: 20px;
        }

        form {
            width: 375px;
            background: #ffffff;
            height: 450px;
            padding: 20px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            gap: 12px;
            border-radius: 10px;
            border: 3px solid #d1c783;
            font-size: 1.2rem;
        }

        input {
            width: 375px;
            height: 35px;
            padding: 5px 10px;
            border-radius: 5px;
            border: 1px solid #d1c783;
        }

        button {
            width: 375px;
            height: 50px;
            background: #d1c783;
            border-radius: 8px;
            border: none;
            margin-top: 10px;
            font-size: 1.2rem;
        }

        h2 {
            font-size: 2rem;
            color: #d1c783;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
    <div class="container">
        <form action="" method="post">
            <h2>Calcule a Corrente</h2>
            <label for="tensao">Tensão:</label>
            <input type="number" name="tensao" id="">

            <label for="resistencia">Resistência:</label>
            <input type="number" name="resistencia" id="">

            <label for="corrente">Corrente:</label>
            <input type="text" name="corrente" value=" <?php echo $corrente ?>">

            <button type="submit">Calcular</button>
            <button type="button" onclick="voltar()">Voltar</button>

        </form>
    </div>
    <script>
        function voltar() {
            window.location.href = "index.php";
        }
    </script>
</body>

</html>