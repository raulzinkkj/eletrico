<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            /* melhor centralização */
            font-family: sans-serif;
            flex-direction: column;
        }

        .wave-group {
            position: relative;
            margin-top: 30px;
        }

        .wave-group .input {
            font-size: 16px;
            padding: 10px 10px 10px 5px;
            display: block;
            width: 220px;
            border: none;
            border-bottom: 1px solid #515151;
        }

        .wave-group .input:focus {
            outline: none;
        }

        .wave-group .label {
            color: #999;
            font-size: 18px;
            position: absolute;
            pointer-events: none;
            left: 5px;
            top: 10px;
            display: flex;
        }

        .wave-group .label-char {
            transition: 0.2s ease all;
            transition-delay: calc(var(--index) * 0.05s);
        }

        .wave-group .input:focus~label .label-char,
        .wave-group .input:valid~label .label-char {
            transform: translateY(-20px);
            font-size: 14px;
            color: #2c3e50;
        }

        .wave-group .bar {
            position: relative;
            display: block;
            width: 220px;
        }

        .wave-group .bar:before,
        .wave-group .bar:after {
            content: "";
            height: 2px;
            width: 0;
            bottom: 1px;
            position: absolute;
            background-color: #2c3e50;
            transition: 0.2s ease all;
        }

        .wave-group .bar:before {
            left: 50%;
        }

        .wave-group .bar:after {
            right: 50%;
        }

        .wave-group .input:focus~.bar:before,
        .wave-group .input:focus~.bar:after {
            width: 50%;
        }

        button {
            padding: 10px 20px;
            border: none;
            background-color: #2c3e50;
            color: white;
            cursor: pointer;
            border-radius: 5px;
        }

        button:hover {
            background-color: #2c3e50;
        }

        .frufru {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .frufru2 {
            display: flex;
            justify-content: center;
        }

        a {
            text-decoration: none;
            color: #2c3e50;
            font-weight: bold;
        }
        h2 {
            color: #2c3e50;
            font-weight: 900;
            font-size: 2.6rem;
            letter-spacing: 19px;
        }
    </style>
</head>

<body>

    <form action="api/verifica_login.php" method="post">
        <h2>Login</h2>

        <div class="wave-group">
            <input type="text" required class="input" id="nome" name="nome_usuario">
            <span class="bar"></span>
            <label class="label" for="nome">
                <span class="label-char" style="--index:0">N</span>
                <span class="label-char" style="--index:1">o</span>
                <span class="label-char" style="--index:2">m</span>
                <span class="label-char" style="--index:3">e</span>
            </label>
        </div>

        <div class="wave-group">
            <input type="password" required class="input" id="senha" name="senha_usuario">
            <span class="bar"></span>
            <label class="label" for="senha">
                <span class="label-char" style="--index:0">S</span>
                <span class="label-char" style="--index:1">e</span>
                <span class="label-char" style="--index:2">n</span>
                <span class="label-char" style="--index:3">h</span>
                <span class="label-char" style="--index:4">a</span>
            </label>
        </div>

        <div class="wave-group frufru">
            <button type="submit">Entrar</button>
            <a href="criar_conta.php">Criar Conta</a>
        </div>
        <div class="frufru2" >

            <a href="esqueci_senha.php">Esqueci a Senha</a>
        </div>

    </form>

</body>

</html>