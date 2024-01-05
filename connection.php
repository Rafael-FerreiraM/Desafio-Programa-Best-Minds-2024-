<?php
// Conecta ao banco de dados MySQL
$con = mysqli_connect("localhost", "root", "", "nunessports");

// Verifica se houve erro na conexão
if (mysqli_connect_errno()) {
    // Encerra o script e exibe a mensagem de erro
    die("Não foi possível conectar ao banco de dados: " . mysqli_connect_errno());
}

// Define constantes para o diretório de upload e a URL de busca das imagens
define("UPLOAD_SRC", $_SERVER['DOCUMENT_ROOT'] . "/nunessports/uploads/");
define("FETCH_SRC", "http://127.0.0.1:8080/nunessports/uploads/");

// Função para executar consultas SQL
function executeQuery($query)
{
    // Utiliza a conexão global definida anteriormente
    global $con;

    // Executa a consulta e retorna o resultado
    return mysqli_query($con, $query);
}
?>
