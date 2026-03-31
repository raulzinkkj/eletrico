<?php
include 'conexao/conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tensao = $_POST['tensao'];
    $corrente = $_POST['corrente'];
    $questao = $_POST['questao'];
    $potencia = $tensao * $corrente;

    $sql = "INSERT INTO potencia(potencia, corrente, tensao, questao)
        VALUES(:potencia, :corrente, :tensao, :questao)";

    $stmt = $conexao->prepare($sql);
    $stmt->bindParam(':potencia', $potencia);
    $stmt->bindParam(':corrente', $corrente);
    $stmt->bindParam(':tensao', $tensao);
    $stmt->bindParam(':questao', $questao);
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
            background: rgb(252, 252, 252);
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
            background: rgb(229, 240, 223);
            height: 450px;
            padding: 20px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            gap: 12px;
            border-radius: 10px;
            border: 3px solid rgb(197, 224, 180);
            font-size: 1.2rem;
        }

        input {
            width: 375px;
            height: 35px;
            padding: 5px 10px;
            border-radius: 5px;
            border: 1px solid rgb(197, 224, 180);
        }

        button {
            width: 375px;
            height: 50px;
            background: rgb(197, 224, 180);
            border-radius: 8px;
            border: none;
            margin-top: 10px;
            font-size: 1.2rem;
        }

        h2 {
            font-size: 2rem;
            color: rgb(197, 224, 180);
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
    <div class="container">
        <form action="" method="post">
            <h2>Calcule a Potência</h2>

            <label for="questao">Questão:</label>
            <input type="number" name="questao" id="">

            <label for="tensao">Tensão:</label>
            <input type="number" name="tensao" id="">

            <label for="corrente">Corrente:</label>
            <input type="number" name="corrente" id="">

            <label for="resultado">Resultado:</label>
            <input type="text" name="resultado" value="<?php echo $potencia ?>">

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
</body>

</html>