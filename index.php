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

        .container {
            width: 450px;
            height: 525px;
            background-image: url("img/triangulo.png");
            background-repeat: no-repeat;
            background-position: center;
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .forma {
            background: red;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="forma"></div>
        <div class="forma"></div>
        <div class="forma"></div>
    </div>
</body>

</html>