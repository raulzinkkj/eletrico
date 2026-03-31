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
        }
        body{
            background: rgb(255, 255, 216);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            width: 450px;
            height: 525px;
            background-image: url("img/potencia.png");
            background-repeat: no-repeat;
            background-position: center;
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
            padding-bottom: 40px;
        }
        .forma {
            cursor: pointer;
        }
        .span {
            grid-column: span 2;
            width: 200px;
            justify-self: center;
            margin-top: 150px;
        } 
    </style>
</head>

<body>
    <div class="container">
        <div class="forma span" onclick="potencia()"></div>
        <div class="forma" onclick="corrente()"></div>
        <div class="forma" onclick="tensao()"></div>
    </div>
    <script>
        function tensao(){
            window.location.href = "calcular_tensao.php";
        }
        function corrente(){
            window.location.href = "calcular_corrente.php";
        }
        function potencia(){
            window.location.href = "calcular_potencia.php";
        }
    </script>
</body>


</html>