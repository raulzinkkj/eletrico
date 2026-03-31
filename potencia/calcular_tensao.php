<?php
include 'conexao/conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $potencia = $_POST['potencia'];
    $corrente = $_POST['corrente'];
    $questao = $_POST['questao'];
    $tensao = $potencia / $corrente;

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


        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            padding: 20px;
        }

        form {
            width: 375px;
            background: rgb(247, 247, 224);
            height: 450px;
            padding: 20px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            gap: 12px;
            border-radius: 10px;
            border: 3px solid rgb(230, 230, 39);
            font-size: 1.2rem;
        }

        input {
            width: 375px;
            height: 35px;
            padding: 5px 10px;
            border-radius: 5px;
            border: 1px solid rgb(230, 230, 39);
        }

        button {
            width: 375px;
            height: 50px;
            background: rgb(230, 230, 39);
            border-radius: 5px;
            border: none;
            margin-top: 5px;
            font-size: 1.2rem;
        }

        h2 {
            font-size: 2rem;
            color: rgb(230, 230, 39);
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
    <div class="container">
        <form action="" method="post">
            <h2>Calcule a Tensão</h2>

            <label for="questao">Questão:</label>
            <input type="number" name="questao" id="">

            <label for="potencia">Potência:</label>
            <input type="number" name="potencia" id="">

            <label for="corrente">Corrente:</label>
            <input type="number" name="corrente" id="">

            <label for="resultado">Resultado:</label>
            <input type="text" name="resultado" value="<?php echo $tensao ?>">

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