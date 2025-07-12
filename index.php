<?php
require_once 'utils/ConexaoBanco.php';

$erro = "";
$bancos = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['conectar'])) {
    $servidor = $_POST['servidor'] ?? '';
    $usuario = $_POST['usuario'] ?? '';
    $senha = $_POST['senha'] ?? '';

    try {
        $pdo = ConexaoBanco::conectar($servidor, $usuario, $senha);
        $bancos = ConexaoBanco::listarBancos($pdo);
    } catch (PDOException $e) {
        $erro = "Erro ao conectar: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Conectar ao Banco</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <form method="post">
        <div class="container">
            <h2>Informações de Conexão</h2>

            <?php if (!empty($erro)): ?>
                <div class="mensagem_erro"><?= $erro ?></div>
            <?php endif; ?>

            <label>Servidor:</label>
            <input type="text" name="servidor" required>

            <label>Usuário:</label>
            <input type="text" name="usuario" required>

            <label>Senha:</label>
            <input type="password" name="senha">

            <button type="submit" name="conectar">Conectar</button>
        </div>
    </form>

    <?php if ($bancos): ?>
        <form method="post" action="creator.php">
            <div class="container">
                <h2>Selecione o banco</h2>
                <select name="banco">
                    <?php foreach ($bancos as $banco): ?>
                        <option value="<?= $banco ?>"><?= $banco ?></option>
                    <?php endforeach; ?>
                </select>
                <input type="hidden" name="servidor" value="<?= $servidor ?>">
                <input type="hidden" name="usuario" value="<?= $usuario ?>">
                <input type="hidden" name="senha" value="<?= $senha ?>">
                <button type="submit">Gerar Estrutura</button>
            </div>
        </form>
    <?php endif; ?>
</body>
</html>
