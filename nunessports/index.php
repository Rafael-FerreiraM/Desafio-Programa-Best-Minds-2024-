<?php
// Inclui o arquivo de conexão com o banco de dados
require("connection.php");

// Define a consulta SQL para selecionar todos os produtos
$query = "SELECT * FROM produtos";

// Executa a consulta e armazena o resultado
$result = executeQuery($query);

// Verifica se o formulário para editar um produto foi submetido
if (isset($_POST['editarproduto'])) {
    // Chama a função editProduct passando a conexão, os dados do formulário e os arquivos
    editProduct($con, $_POST, $_FILES);
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nunes Sports - Produtos Inovadores é aqui!</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .table th {
            background-color: #343a40; 
            color: white; 
        }
    </style>
</head>
<body class="bg-light">
    <div class="container bg-dark text-light p-3 rounded my-4">
        <div class="d-flex align-items-center justify-content-between px-3">
            <h2> 
                <a href="index.php" class="text-white text-decoration-none">
                    <i class="bi bi-cart-fill"></i>Nunes Sports Loja de Produtos 
                </a>
            </h2>
            <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#adicionarProdutoModal">
                <i class="bi bi-plus-lg"></i> Adicionar Produto
            </button>
        </div>
    </div>

    <div class="container mt-4 p-0">
        <table class="table table-hover text-center">
            <thead class="bg-dark text-white">
                <tr>
                    <th width="10%" scope="col" class="rounded-start">Código</th>
                    <th width="15%" scope="col">Foto</th>
                    <th width="10%" scope="col">Nome</th>
                    <th width="10%" scope="col">Preço</th>
                    <th width="35%" scope="col">Descrição</th>
                    <th width="20%" scope="col" class="rounded-end">Ação</th>
                </tr>
            </thead>
            <tbody class="bg-white">
        <?php
            // Obtém a URL base para o carregamento das imagens
           $fetch_src = FETCH_SRC;

            // Itera sobre os resultados da consulta e exibe cada produto em uma linha da tabela
            while ($fetch = mysqli_fetch_assoc($result)) {
                echo "<tr class='align-middle'>";
                echo "<td>{$fetch['codigo']}</td>";  // Coluna de código do produto
                echo "<td><img src='{$fetch_src}{$fetch['imagem']}' width='150px'></td>";  // Coluna de imagem do produto
                echo "<td>{$fetch['nome']}</td>";  // Coluna de nome do produto
                echo "<td>R$ {$fetch['preco']}</td>";  // Coluna de preço do produto
                echo "<td>{$fetch['descricao']}</td>";  // Coluna de descrição do produto
                echo "<td>";
                
                // Botão de editar que abre o modal de edição com o código do produto como parâmetro na URL
                echo "<a href='?editarProdutoModal=$fetch[codigo]' class='btn btn-success me-2' data-bs-toggle='modal' data-bs-target='#editarProdutoModal'><i class='bi bi-pencil'></i> Editar</a>";
                
                // Botão de deletar que chama a função JavaScript confirm_rem com o código do produto como argumento
                echo "<button onclick='confirm_rem($fetch[codigo])' class='btn btn-danger'><i class='bi bi-trash'></i> Deletar</button>"; 
                
                echo "</td>";
                echo "</tr>";
            }
        ?>

            </tbody>
        </table>
    </div>


    <div class="modal" id="editarProdutoModal" tabindex="-1" aria-labelledby="editarProdutoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editarProdutoModalLabel">Editar Produto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                
                    <form action="crud.php" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="nome" class="form-label">Nome</label>
                            <?php
                            $query = "SELECT * FROM produtos";
                            $result = executeQuery($query);
                            $row = $result -> fetch_assoc();
                            echo"<input type='text' class='form-control' id='editarnome' name='editarnome' value='$row[nome]' required>";
                            ?>
                        </div>
                        <div class="mb-3">
                            <label for="preco" class="form-label">Preço</label>
                            <?php
                            $query = "SELECT * FROM produtos";
                            $result = executeQuery($query);
                            $row = $result -> fetch_assoc();
                            echo"<input type='number' class='form-control' id='editarpreco' name='editarpreco' value='$row[preco]' required>";
                            ?>
                        </div>
                        <div class="mb-3">
                            <label for="codigo" class="form-label">Código(Não é possível editar)</label>
                            <?php
                            $query = "SELECT * FROM produtos";
                            $result = executeQuery($query);
                            $row = $result -> fetch_assoc();
                            echo "<input type='number' class='form-control' id='editarcodigo' name='editarcodigo' value='$row[codigo]' readonly>";

                            ?>
                        </div>
                        <div class="mb-3">
                            <label for="descricao" class="form-label">Descrição</label>
    
            
                           <textarea class="form-control" id="editardescricao" name= "editardescricao" required></textarea>
                            
                        </div>
                        <div class="input-group mb-3">
                            <label class="input-group-text">Foto do Produto</label>
                            <input type="file" class="form-control" id= "editarimagem" name="editarimagem" accept=".jpg,.png,.svg">
                        </div>


                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-success" name="editarProdutoModal">Editar</button>


                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="adicionarProdutoModal" tabindex="-1" aria-labelledby="adicionarProdutoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="adicionarProdutoModalLabel">Adicionar Produto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    <form action="crud.php" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="nome" class="form-label">Nome</label>
                            <input type="text" class="form-control" id="nome" name="nome" required>
                        </div>
                        <div class="mb-3">
                            <label for="preco" class="form-label">Preço</label>
                            <input type="number" class="form-control" id="preco" name="preco" required>
                        </div>
                        <div class="mb-3">
                            <label for="codigo" class="form-label">Código</label>
                            <input type="number" class="form-control" id="codigo" name="codigo" required>
                        </div>
                        <div class="mb-3">
                            <label for="descricao" class="form-label">Descrição</label>
                            <textarea class="form-control" id="descricao" name="descricao" required></textarea>
                        </div>
                        <div class="input-group mb-3">
                            <label class="input-group-text">Foto do Produto</label>
                            <input type="file" class="form-control" id="editarimagem" name="editarimagem" accept=".jpg,.png,.svg">
                        </div>
                        
                        

                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-success" name="adicionarProdutoModal">Adicionar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php
// Verifica se há um parâmetro 'editarProdutoModal' na URL e se é um número positivo
if (isset($_GET['editarProdutoModal']) && $_GET['editarProdutoModal'] > 0) {
    // Constrói a consulta SQL para selecionar os dados do produto com o código fornecido
    $query = "SELECT * FROM `produtos` WHERE `codigo` = '$_GET[editarProdutoModal]'";
    
    // Executa a consulta
    $result = mysqli_query($con, $query);

    // Obtém os dados do produto
    $fetch = mysqli_fetch_assoc($result);

    // Gera um script JavaScript para mostrar o modal de edição e preencher os campos com os dados do produto
    echo "
    <script>
        $(document).ready(function(){
            $('#editarProdutoModal').modal('show');
        });

        $('#editarProdutoModal').on('shown.bs.modal', function () {
            // Preenche os campos do modal com os dados do produto
            $('#editarcodigo').val('$fetch[codigo]');
            $('#editarnome').val('$fetch[nome]');
            $('#editarpreco').val('$fetch[preco]');
            $('#editardescricao').val('$fetch[descricao]');
        });
    </script>";
}
?>

<!-- Script para confirmar a remoção de um produto -->
<script>
    function confirm_rem(codigo) {
        // Exibe um diálogo de confirmação ao usuário
        if (confirm("Você tem certeza que deseja deletar esse produto?")) {
            // Redireciona para o script de remoção com o código do produto
            window.location.href = "crud.php?deleteCodigo=" + codigo;
        }
    }
</script>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-xNzN2a4ltkB44Mc/Jz3pT4iU1cmeR0FkXs4pru/JxaQ=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-iUqDrlCBED2b2PXW6AOo8g5EIMFSQQFiFIf4J2CsFUY3oq1iUId5BhjslXp0MZDI" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>