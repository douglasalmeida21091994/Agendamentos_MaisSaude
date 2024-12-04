<?php
$host = 'localhost';
$dbname = 'sos_agendamentos';
$username = 'root';
$password = '';

try {
    // Estabelecendo a conexão com o banco de dados usando PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Definindo o modo de erro para exceção
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Caso ocorra um erro na conexão, a mensagem será exibida
    echo 'Erro ao conectar ao banco de dados: ' . $e->getMessage();
    exit;
}
