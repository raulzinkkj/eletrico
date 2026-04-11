<?php
session_start();

if (!isset($_SESSION['id'])) {
    header("Location:index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Menu</title>

        <style>
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
                font-family: 'Segoe UI', sans-serif;
            }

            body {
                height: 100vh;
                display: flex;
                flex-direction: column;
                color: white;
                background: #f0f2f5;
            }

            header {
                display: flex;
                justify-content: right;
                padding: 20px;
            }

            header a {
                text-decoration: none;
                background: #2c3e50;
                color: white;
                padding: 10px 15px;
                border-radius: 8px;
            }

            section {
                height: 100vh;
                display: flex;
                justify-content: center;
                align-items: center;
            }

            nav {
                background: #2c3e50;
                padding: 40px;
                border-radius: 15px;
                box-shadow: 0 8px 25px rgba(0, 0, 0, 0.86);
            }

            ul {
                list-style: none;
            }

            li {
                margin: 15px 0;
            }

            li a {
                display: block;
                text-decoration: none;
                background: white;
                color: #2c3e50;
                padding: 15px 25px;
                border-radius: 10px;
                font-weight: bold;
            }

            h2 {
                text-align: center;
                margin-bottom: 20px;
                font-size: 2.5rem;
            }

            .pe {
                background: #2c3e50;
                padding: 10px 15px;
                border-radius: 8px;
                border: none;
                color: white;
                font-weight: bold;
            }

            header {
                display: flex;
                justify-content: center;
                align-items: center;
                padding: 20px;
                position: relative;
            }

            header a {
                text-decoration: none;
                background: #2c3e50;
                color: white;
                padding: 10px 15px;
                border-radius: 8px;
                position: absolute;
                right: 20px;
            }
        </style>

    </head>

<body>
    <header>
        <div class="usuario">
            <p class="pe">
                Bem vindo <?php echo $_SESSION['nome_usuario']; ?>
            </p>
        </div>
        
        <a href="api/logout.php">🚪 Sair</a>
    </header>
    <section>
        <nav>
            <h2>Menu Principal</h2>
            <ul>
                <li><a href="eletrica2.0/lei_de_ohm/index.php">Triângulo da Lei de Ohm</a></li>
                <li><a href="eletrica2.0/potencia/index.php">Triângulo da Potência</a></li>
                <li><a href="eletrica2.0/resistores/index.php">Associação de Resistores</a></li>
            </ul>
        </nav>
    </section>
</body>

</html>