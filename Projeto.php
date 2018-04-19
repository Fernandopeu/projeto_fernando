<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = (isset($_POST["id"]) && $_POST["id"] != null) ? $_POST["id"] : "";
    $nome = (isset($_POST["nome"]) && $_POST["nome"] != null) ? $_POST["nome"] : "";
    $email = (isset($_POST["email"]) && $_POST["email"] != null) ? $_POST["email"] : "";
    $celular = (isset($_POST["celular"]) && $_POST["celular"] != null) ? $_POST["celular"] : NULL;
} else if (!isset($id)) {
    $id = (isset($_GET["id"]) && $_GET["id"] != null) ? $_GET["id"] : "";
    $nome = NULL;
    $email = NULL;
    $celular = NULL;
}

try {
	$conexao = new PDO("mysql:host=localhost; dbname=cadastro", "root", "");
	$conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$conexao->exec("set names utf8");
} catch (PDOException $erro) {
	echo "Erro na conexão:" . $erro->getMessage();
}
if (isset($_REQUEST["act"]) && $_REQUEST["act"] == "save" && $nome != "") {
    try {
     if ($id != "") {
    $stmt = $conexao->prepare("UPDATE usuarios SET nome=?, email=?, celular=? WHERE id = ?");
    $stmt->bindParam(4, $id);
} else {
    $stmt = $conexao->prepare("INSERT INTO usuarios (nome, email, celular) VALUES (?, ?, ?)");
}
        $stmt->bindParam(1, $nome);
        $stmt->bindParam(2, $email);
        $stmt->bindParam(3, $celular);
         
        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {
                echo "Dados cadastrados com sucesso!";
                $id = null;
                $nome = null;
                $email = null;
                $celular = null;
            } else {
                echo "Erro ao tentar efetivar cadastro";
            }
        } else {
               throw new PDOException("Erro: Não foi possível executar a declaração sql");
        }
    } catch (PDOException $erro) {
        echo "Erro: " . $erro->getMessage();
    }
}
if (isset($_REQUEST["act"]) && $_REQUEST["act"] == "upd" && $id != "") {
    try {
        $stmt = $conexao->prepare("SELECT * FROM usuarios WHERE id = ?");
        $stmt->bindParam(1, $id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            $rs = $stmt->fetch(PDO::FETCH_OBJ);
            $id = $rs->id;
            $nome = $rs->nome;
            $email = $rs->email;
            $celular = $rs->celular;
        } else {
            throw new PDOException("Erro: Não foi possível executar a declaração sql");
        }
    } catch (PDOException $erro) {
        echo "Erro: ".$erro->getMessage();
    }
}
if (isset($_REQUEST["act"]) && $_REQUEST["act"] == "del" && $id != "") {
	try {
		$stmt = $conexao->prepare("DELETE FROM usuarios WHERE id = ?");
		$stmt->bindParam(1, $id, PDO::PARAM_INT);
		if ($stmt->execute()) {
			echo "Registro foi excluido";
			$id = null;
		} else {
			throw new PDOException("Erro: não foi possivel execultar a operação");
		}
	} catch (PDOexception $erro) {
		echo "Erro: ".$erro->getMessage();
}
}
?>
<?php

?>


<html>
<html lang="en">
  <head>
  
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <title>Agenda de Contatos</title>   
    <link href="css/bootstrap.min.css" rel="stylesheet"> 

  </head>
  <body>
  <div class="jumbotron">
  <div class="conteiner">
  <div class="row">
  <div class="col-md-6 col-md-offset-3">
  <h1>Agenda de contato</h1>
  </div>
  </div>
   
  <form class="form-inline" action="?act=save" method="POST" name="form1"></br></br>
  
  <input type="hidden" name="id"
  <?php
            // Preenche o id no campo id com um valor "value"
            if (isset($id) && $id != null || $id != "") {
                echo "value=\"{$id}\"";
            }
            ?> />
  Nome:
   <input type="text" name="nome" class="form-control"
  <?php
            // Preenche o nome no campo nome com um valor "value"
            if (isset($nome) && $nome != null || $nome != ""){
                echo "value=\"{$nome}\"";
            }
            ?> />
  e-mail:
  <input type="text" name="email" class="form-control"
   <input type="text" name="email" <?php
            // Preenche o email no campo email com um valor "value"
            if (isset($email) && $email != null || $email != ""){
                echo "value=\"{$email}\"";
            }
            ?> />
  Celular: 
  <input type="text" name="celular" class="form-control"
  <input type="text" name="celular" <?php
            // Preenche o celular no campo celular com um valor "value"
            if (isset($celular) && $celular != null || $celular != ""){
                echo "value=\"{$celular}\"";
            }
            ?> />
  <div class="btn-group btn-group">
  <button class="btn btn-primary" type="subimit">SALVAR</button>
  <button class="btn btn-primary" type="reset">NOVO</button>
  </div> 
 

    </form>

	<div class="col-md-6 col-md-offset-3">
	<table class="table table-striped">
<thead>
   <tr>
       <th>Nome</th>
        <th>E-mail</br>
        <th>Celular</th>
        <th>Ações</th>
    </tr>
	</thead>
</div>
	</div>
	<?php
try {
 
    $stmt = $conexao->prepare("SELECT * FROM usuarios");
 
        if ($stmt->execute()) {
            while ($rs = $stmt->fetch(PDO::FETCH_OBJ)) {
                echo "<tr>";
                echo "<td>".$rs->nome."</td><td>".$rs->email."</td><td>".$rs->celular
                           ."</td><td><center><a href=\"?act=upd&id=" . $rs->id . "\">[Alterar]</a>"
                           ."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"
                           ."<a href=\"?act=del&id=" . $rs->id . "\">[Excluir]</a></center></td>";
                echo "</tr>";
            }
        } else {
            echo "Erro: Não foi possível recuperar os dados do banco de dados";
        }
} catch (PDOException $erro) {
    echo "Erro: ".$erro->getMessage();
}
?>
</table>
    </body>
</html>