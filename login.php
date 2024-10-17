<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['nome_usuario'])) {
    header('Location: index.php');
    exit;
}

$host = "127.0.0.1";
$usuario = "root";
$senha= "";
$db="p1_dev_web";

$conn = new mysqli($host, $usuario, $senha, $db);

if ($conn->connect_error) 
{
    die("Falha na conexão: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') 
{
    $email = $conn->real_escape_string($_POST['email']);
    $senha = $_POST['senha'];

    $sql = "SELECT * FROM usuarios WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) 
    {
        $user = $result->fetch_assoc();

        if (password_verify($senha, $user['senha'])) 
        {
            // Armazena o nome do usuário na sessão
            $_SESSION['nome_usuario'] = $user['nome'];
            header('Location: index.php');
            exit;
        } 
        else 
        {
            echo "Senha incorreta!";
        }
    } 
    else 
    {
        echo "Usuário não encontrado!";
    }
}
?>

<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
    <link rel="stylesheet" href="stylelogin.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1>Login</h1>
        <?php if (isset($erro)): ?>
            <div class="erro"><?php echo $erro; ?></div>
        <?php endif; ?>
        <form method="POST" action="" class="form">
            <label for="email">Email:</label>
            <input style= 'padding: 10px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 4px;' class="input-email" type="email" id="email" name="email" required>

            <label for="senha">Senha:</label>
            <input type="password" id="senha" name="senha" required>

            <input class="btn-submit" type="submit" value="Entrar">
        </form>
        <p>Ainda não tem uma conta? <a href="cadastro_forms.php">Cadastre-se</a></p>
    </div>
    
</body>
</html>
