<?php
// Inclui o arquivo de conexão com o banco de dados
require("connection.php");

// Função para fazer upload de uma imagem e retornar o novo nome
function image_upload($img)
{
    // Obtém o local temporário e gera um novo nome
    $tmp_loc = $img["tmp_name"];
    $new_name = mt_rand(11111, 99999) . $img['name'];

    // Define o novo local para a imagem carregada
    $new_loc = UPLOAD_SRC . $new_name;

    // Move a imagem carregada para o novo local
    if (!move_uploaded_file($tmp_loc, $new_loc)) {
        echo "Erro ao fazer upload da imagem.";
        exit;
    } else {
        return $new_name;
    }
}

// Função para remover uma imagem
function image_remove($img)
{
    // Remove a imagem especificada
    if (!unlink(UPLOAD_SRC . $img)) {
        echo "Erro ao remover a imagem.";
        exit;
    }
}

// Verifica se o formulário para adicionar um produto foi enviado
if (isset($_POST['adicionarProdutoModal'])) {
    // Sanitiza os dados de entrada
    foreach ($_POST as $key => $value) {
        $_POST[$key] = mysqli_real_escape_string($con, $value);
    }

    // Faz upload da imagem e obtém o caminho
    $imgpath = image_upload($_FILES['editarimagem']);

    // Insere o novo produto no banco de dados
    $query = "INSERT INTO `produtos` (`codigo`, `nome`, `preco`, `descricao`, `imagem`) 
              VALUES ('$_POST[codigo]', '$_POST[nome]', '$_POST[preco]', '$_POST[descricao]', '$imgpath')";

    // Verifica se a consulta foi bem-sucedida
    if (mysqli_query($con, $query)) {
        header("location: index.php?success=adicionado");
    } else {
        header("location: index.php?alert=erro_ao_adicionar");
    }
} elseif (isset($_POST['editarProdutoModal'])) {
    // Verifica se os dados necessários estão definidos e são numéricos
    if (
        isset($_POST['editarnome'], $_POST['editarpreco'], $_POST['editardescricao'], $_POST['editarcodigo'])
        && is_numeric($_POST['editarpreco'])
        && is_numeric($_POST['editarcodigo'])
    ) {
        // Sanitiza os dados
        $nome = mysqli_real_escape_string($con, $_POST['editarnome']);
        $preco = mysqli_real_escape_string($con, $_POST['editarpreco']);
        $descricao = mysqli_real_escape_string($con, $_POST['editardescricao']);
        $codigo = mysqli_real_escape_string($con, $_POST['editarcodigo']);

        // Constrói a consulta de atualização
        $update = "UPDATE `produtos` SET 
                    `nome` = '$nome', 
                    `preco`= '$preco', 
                    `descricao`='$descricao'";

        // Verifica se uma nova imagem está sendo carregada
        if (isset($_FILES['editarimagem']) && $_FILES['editarimagem']['error'] == 0) {
            $query = "SELECT `imagem` FROM `produtos` WHERE `codigo` = '$codigo'";
            $result = mysqli_query($con, $query);
            $fetch = mysqli_fetch_assoc($result);

            // Remove a imagem antiga
            if ($fetch) {
                image_remove($fetch['imagem']);
            }

            // Faz upload da nova imagem e atualiza a consulta
            $imgpath = image_upload($_FILES['editarimagem']);
            $update .= ", `imagem`= '$imgpath'";
        }

        // Completa a consulta de atualização
        $update .= " WHERE `codigo` = '$codigo'";

        // Registra a consulta de atualização
        error_log("Consulta de Atualização: " . $update);

        // Executa a consulta de atualização
        if (mysqli_query($con, $update)) {
            header("location: index.php?success=atualizado");
            exit;
        } else {
            // Trata falha na atualização
            echo "Erro no MySQL: " . mysqli_error($con);
            error_log("Erro ao atualizar dados: " . mysqli_error($con));
            header("location: index.php?alert=falha_ao_atualizar");
            exit;
        }
    } else {
        // Trata dados de entrada inválidos
        echo "Dados de entrada inválidos.";
        exit;
    }
} elseif (isset($_GET['deleteCodigo'])) {
    // Sanitiza o código a ser excluído
    $rem_code = mysqli_real_escape_string($con, $_GET['deleteCodigo']);

    // Obtém o caminho da imagem do produto a ser excluído
    $query = "SELECT `imagem` FROM `produtos` WHERE `codigo` = '$rem_code'";
    $result = mysqli_query($con, $query);
    $fetch = mysqli_fetch_assoc($result);

    // Remove a imagem do produto a ser excluído
    if ($fetch) {
        image_remove($fetch['imagem']);
    }

    // Exclui o produto do banco de dados
    $query = "DELETE FROM `produtos` WHERE `codigo` = '$rem_code'";

    // Verifica se a exclusão foi bem-sucedida
    if (mysqli_query($con, $query)) {
        header("location: index.php?success=removido");
        exit;
    } else {
        header("location: index.php?alert=falha_em_remover");
        exit;
    }
} else {
    // Redireciona se nenhuma das ações esperadas for acionada
    header("location: index.php?alert=operacao_invalida");
    exit;
}
?>
