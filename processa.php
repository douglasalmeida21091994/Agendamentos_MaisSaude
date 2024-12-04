<?php
session_start();

// Incluindo a conexão com o banco de dados
include('conexao.php');

// Exclusão do agendamento específico
if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];

    // Consulta SQL para excluir o agendamento com o ID especificado
    $sql = "DELETE FROM agendamentos WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    if ($stmt->execute([':id' => $id])) {
        $_SESSION['mensagem'] = "Agendamento excluído com sucesso!";
    } else {
        $_SESSION['mensagem'] = "Erro ao excluir o agendamento.";
    }
    // Redireciona de volta para a página index.php
    header('Location: index.php');
    exit;
}

// Exclusão de todos os agendamentos
if (isset($_POST['excluir_todos'])) {
    $sql = "DELETE FROM agendamentos";
    $stmt = $pdo->prepare($sql);
    if ($stmt->execute()) {
        $_SESSION['mensagem'] = "Todos os agendamentos foram excluídos!";
    } else {
        $_SESSION['mensagem'] = "Erro ao excluir os agendamentos.";
    }
    header('Location: index.php');
    exit;
}

// Verificando se foi enviado um arquivo
if (isset($_FILES['arquivo']) && $_FILES['arquivo']['error'] === 0) {
    $arquivo_tmp = $_FILES['arquivo']['tmp_name'];
    $nome_arquivo = $_FILES['arquivo']['name'];
    $caminho_arquivo = 'uploads/' . $nome_arquivo;

    // Movendo o arquivo para o diretório de uploads
    if (move_uploaded_file($arquivo_tmp, $caminho_arquivo)) {
        // Processar o arquivo (exemplo: CSV)
        if (($handle = fopen($caminho_arquivo, 'r')) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {

                // Inserção dos dados no banco
                $sql = "INSERT INTO agendamentos (carteirinha, nome, email, telefone, data_agendamento, hora, unidade) 
                        VALUES (:carteirinha, :nome, :email, :telefone, :data_agendamento, :hora, :unidade)";

                $stmt = $pdo->prepare($sql);

                $stmt->execute([
                    ':carteirinha' => $data[0],
                    ':nome' => $data[1],
                    ':email' => $data[2],
                    ':telefone' => $data[3],
                    ':data_agendamento' => $data[4],
                    ':hora' => $data[5],
                    ':unidade' => $data[6],
                ]);
            }
            fclose($handle);
            $_SESSION['mensagem'] = "Dados importados com sucesso!";
        } else {
            $_SESSION['mensagem'] = "Erro ao processar o arquivo.";
        }
    } else {
        $_SESSION['mensagem'] = "Erro ao enviar o arquivo.";
    }
    header('Location: index.php');
    exit;
}
?>
