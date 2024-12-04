<?php
session_start();

// Incluindo a conexão com o banco de dados
include('conexao.php');

// Filtro de busca
$filtro_geral = isset($_POST['filtro_geral']) ? $_POST['filtro_geral'] : '';

// Consulta SQL com filtro
$sql = "SELECT * FROM agendamentos 
        WHERE 
        (carteirinha LIKE :filtro OR 
        nome LIKE :filtro OR 
        email LIKE :filtro OR 
        telefone LIKE :filtro OR 
        data_agendamento LIKE :filtro OR 
        hora LIKE :filtro OR 
        unidade LIKE :filtro) 
        AND situacao = 1 
        ORDER BY data_agendamento, hora, nome";

$stmt = $pdo->prepare($sql);
$stmt->execute([':filtro' => '%' . $filtro_geral . '%']);
$dados = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agendamentos Mais Saúde</title>
    <link rel="stylesheet" href="style.css">
    <link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css" integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
</head>

<body>
    <div class="header">
        <header class="title">
        <img src="img/nova-logo-branca 1.png" alt="Logo Smile Saúde" class="title-image">
            <h2>Agendamentos Mais Saúde</h2>
        </header>
    </div>

    <div class="main">
        <form action="processa.php" method="post" enctype="multipart/form-data">
            <label for="arquivo">Arquivo</label>
            <input type="file" name="arquivo" id="arquivo" required>
            <input type="submit" value="Enviar">
        </form>

        <?php if (isset($_SESSION['mensagem'])): ?>
            <p><?php echo $_SESSION['mensagem']; ?></p>
            <?php unset($_SESSION['mensagem']); ?>
        <?php endif; ?>

        <form method="POST" action="">
            <label for="filtro_geral">Filtro:</label>
            <input type="text" name="filtro_geral" id="filtro_geral" placeholder="Faça sua busca aqui" value="<?php echo htmlspecialchars($filtro_geral); ?>">
            <input type="submit" value="Filtrar">
        </form>

        <?php if (count($dados) > 0): ?>
            <table border="1">
                <thead>
                    <tr>
                        <th>Carteirinha</th>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Telefone</th>
                        <th>Data Agendamento</th>
                        <th>Hora</th>
                        <th>Unidade</th>
                        <th>Ações</th> <!-- Nova coluna -->
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($dados as $item): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['carteirinha']); ?></td>
                            <td><?php echo htmlspecialchars($item['nome']); ?></td>
                            <td><?php echo htmlspecialchars($item['email']); ?></td>
                            <td><?php echo htmlspecialchars($item['telefone']); ?></td>
                            <td><?php echo htmlspecialchars($item['data_agendamento']); ?></td>
                            <td><?php echo htmlspecialchars($item['hora']); ?></td>
                            <td><?php echo htmlspecialchars($item['unidade']); ?></td>
                            <td>
                                <!-- Ícone de excluir (imagem ou ícone font-awesome) -->
                                <a href="processa.php?id=<?php echo $item['id']; ?>" onclick="return confirm('Tem certeza que deseja excluir este agendamento?');">
                                    <img src="delete-icon.png" alt="Excluir" style="width:20px;">
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Nenhum dado encontrado.</p>
        <?php endif; ?>

        <!-- Formulário para excluir todos os dados -->
        <form method="POST" action="processa.php">
            <input type="submit" name="excluir_todos" value="Excluir Todos os Agendamentos">
        </form>
    </div>
</body>

</html>