<?php

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <form action="api/verifica_login.php" method="post">
        <label for="nome">Nome:</label>
        <input type="text" name="nome_usuario" id="">

        <label for="senha">Senha:</label>
        <input type="password" name="senha_usuario" id="">

        <div>
            <button type="submit">Salvar</button>
            <a href="criar_conta.php">Criar Conta</a>
        </div>

        <a href="esqueci_senha.php">Esqueci a Senha</a>
    </form>
</body>

</html>