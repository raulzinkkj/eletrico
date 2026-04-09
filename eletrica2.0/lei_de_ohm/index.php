<?php
session_start();

if (!isset($_SESSION['id'])) {
    header("Location:index.php");
    exit;
}

include '../conexao/conexao.php';


$v = $i = $r = "";
$resultado = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $questao = $_POST['questao'];
    $tipo = "";
    $v = $_POST['tensao'];
    $i = $_POST['corrente'];
    $r = $_POST['resistencia'];
    $id_usuario = $_SESSION['id'];

    if ($v == "" && $i != "" && $r != "") {
        $v = $i * $r;
        $resultado = "Tensão (V) = $v V";
        $tipo = "V = ";
    } elseif ($i == "" && $v != "" && $r != "") {
        if ($r == 0) {
            $resultado = "Erro: divisão por zero";
        } else {
            $i = $v / $r;
            $resultado = "Corrente (I) = $i A";
            $tipo = "A = ";
        }
    } elseif ($r == "" && $v != "" && $i != "") {
        if ($i == 0) {
            $resultado = "Erro: divisão por zero";            
        } else {
            $r = $v / $i;
            $resultado = "Resistência (R) = $r Ω";
            $tipo = "R = ";
        }
    } else {
        $resultado = "Preencha exatamente 2 campos!";
    }
    $sql = "INSERT INTO ohm (resistencia, corrente, tensao, tipo, questao, id_usuario) VALUES (:resistencia, :corrente, :tensao, :tipo, :questao, :id_usuario)";

    $stmt = $conexao->prepare($sql);
    $stmt->bindParam(':resistencia', $r);
    $stmt->bindParam(':corrente', $i);
    $stmt->bindParam(':tensao', $v);
    $stmt->bindParam(':tipo', $tipo);
    $stmt->bindParam(':questao', $questao);
    $stmt->bindParam(':id_usuario', $id_usuario);
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
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background-color: #f0f2f5;
            margin: 0;
        }

        h1 {
            color: #333;
            margin-bottom: 20px;
        }

        .container {
            position: relative;
            width: 400px;
            height: 350px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .triangle-container {
            position: relative;
            width: 0;
            height: 0;
            border-left: 200px solid transparent;
            border-right: 200px solid transparent;
            border-bottom: 346px solid #2c3e50;
            filter: drop-shadow(0 10px 15px rgba(0, 0, 0, 0.2));
        }

        .content-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            pointer-events: none;
        }

        .section {
            pointer-events: auto;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: white;
            transition: transform 0.2s;
            cursor: pointer;
        }

        .section:hover {
            transform: scale(1.1);
        }

        .v-section {
            margin-top: 140px;
            z-index: 2;
        }

        .horizontal-line {
            width: 240px;
            height: 4px;
            background-color: #ffff;
            margin: 10px 0;
            border-radius: 2px;
        }

        .bottom-row {
            display: flex;
            justify-content: center;
            gap: 40px;
            width: 100%;
        }

        .vertical-line {
            width: 4px;
            height: 80px;
            background-color: white;
            border-radius: 2px;
        }

        .label {
            font-size: 48px;
            font-weight: bold;
        }

        .sub-label {
            font-size: 14px;
            opacity: 0.8;
        }

        .calc-panel {
            margin-top: 40px;
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 350px;
        }

        .input-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #555;
        }

        input {
            width: 100%;
            padding: 10px;
            border: 1px solid #e8f5e9;
            border-radius: 6px;
            box-sizing: border-box;
            font-size: 16px;
        }

        .resultado {
            margin-top: 20px;
            padding: 15px;
            background-color: #e8f5e9;
            border-left: 5px solid #4caf50;
            font-weight: bold;
            color: #2e7d32;
            margin-bottom: 15px;
            border-radius: 5px;
        }

        .instructions {
            font-size: 0.9em;
            color: #666;
            margin-top: 10px;
            text-align: center;
        }

        button {
            width: 100%;
            padding: 15px 10px;
            filter: drop-shadow(0 10px 15px rgba(0, 0, 0, 0.2));
            background: #2c3e50;
            color: white;
            border-radius: 9px;
            border: none;
            font-weight: bold;
            font-size: 16px;
        }
    </style>
</head>

<body>
    <h1>Triângulo Da Lei de Ohm</h1>

    <div class="container">

        <div class="triangle-container"></div>

        <div class="content-overlay">
            <div class="section v-section" onclick="focusInput('v')">
                <div class="label">E</div>
                <div class="sub-label">Tensao (Volts)</div>
            </div>

            <div class="horizontal-line"></div>

            <div class="bottom-row">
                <div class="section" onclick="focusInput ('i')">
                    <div class="label">I</div>
                    <div class="sub-label">Corrente (Amps)</div>
                </div>


                <div class="vertical-line"></div>

                <div class="section" onclick="focusInput ('r')">
                    <div class="label">R</div>
                    <div class="sub-label">Resistência (Ω)</div>
                </div>

            </div>
        </div>
    </div>

    <div class="calc-panel">
        <p class="instructions">Preencha dois campos para calcular o terceiro:</p>
        <form action="" method="post">
            <div class="input-group">
                <label for="questao">Questão :</label>
                <input type="text" name="questao" id="questao" placeholder="Questão">
            </div>

            <div class="input-group">
                <label for="v">Tensão (E):</label>
                <input type="number" name="tensao" id="v" placeholder="Volts">
            </div>

            <div class="input-group">
                <label for="i">Corrente (I):</label>
                <input type="number" name="corrente" id="i" placeholder="Ampéres">
            </div>

            <div class="input-group">
                <label for="r">Resistência (R):</label>
                <input type="number" name="resistencia" id="r" placeholder="Ohms (Ω)">
            </div>

            <div class="resultado" style="display: <?php echo ($resultado != "") ? 'block' : 'none'; ?>">
                <?php echo $resultado; ?>
            </div>
            <button type="submit">Salvar</button>
        </form>
    </div>

    <script>
        function focusInput(id) {
            document.getElementById(id).focus();
        }
    </script>
</body>

</html>