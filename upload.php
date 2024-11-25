<?php

include("db.php"); 
 
if (isset($_FILES['arquivo'])) {
    $arquivo = $_FILES['arquivo'];

    if ($arquivo['error']) {
        die("Falha ao enviar arquivo");
    }

    if ($arquivo['size'] > 209715332) {
        die("Arquivo muito grande");
    }

    $pasta = "arquivo/";
    $nomearq = $arquivo['name'];
    $newname = uniqid();
    $extensao = strtolower(pathinfo($nomearq, PATHINFO_EXTENSION));

    if ($extensao != "jpg" && $extensao != 'png') {
        die("Tipo de arquivo não aceito");
    }

    $path = $pasta . $newname . "." . $extensao;

    $deu_certo = move_uploaded_file($arquivo["tmp_name"], $path);
    if ($deu_certo) {
        $mysqli->query("INSERT INTO arquivos (nome, path) VALUES ('$nomearq', '$path')") or die($mysqli->error);
        echo "<p>Arquivo enviado com sucesso. Para acessá-lo, clique: <a target=\"_blank\" href=\"arquivo/$newname.$extensao\">aqui</a></p>";
    } else {
        echo "<p>Falha ao enviar arquivo</p>";
    }
}

$sql_query = $mysqli->query ("SELECT * FROM arquivos") or die ($mysqli->error);
?>

<hmtl>
    <head>

    <title>Upload Sessão </title>
    
    </head>

    <body>
        <form method="POST" enctype="multipart/form-data" action="">
           <p> <label for=""> Selecione o arquivo </label>
            <input name="arquivo" type="file"> </p>
            <button  name="upload" type="submit"> Enviar arquivos </button>
    </form>
    <h1>Lista de arquivos</h1>
    <table border="1" cellpadding="10">
        <thead>   
        <th>Arquivo</th>
        <th>Data de envio</th>
        </thead>
        
        <tbody>
        <?php
    while($arquivo = $sql_query-> fetch_assoc()){

    
    ?>
<tr>

            <td><a  target="_blank" href="<?php echo $arquivo['path']; ?>"><?php echo $arquivo['nome'];?></a></td>
            <td><?php echo date("d/m/Y H:i", strtotime($arquivo['data_upload'])); ?></td>

</tr>
<?php
    }
    ?>
</tbody>
</table>
    </body>
</hmtl>