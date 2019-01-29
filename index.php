<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title></title>
</head>
<body>
    
    <h1>Tasklist</h1>
    
    <form method="POST" action="index.php">
        <label>Nova tasca:</label>
        <input type="text" name="introducir">
        <button>Añadir tasca</button>
    </form>

    <?php 
    $db = parse_url(getenv("DATABASE_URL"));
    $pdo = new PDO("pgsql:" . sprintf(
        "host=%s;port=%s;user=%s;password=%s;dbname=%s",
        $db["host"],
        $db["port"],
        $db["user"],
        $db["pass"],
        ltrim($db["path"], "/")
    ));
    if(isset($_GET['hecho'])) {
                $value = $_GET['hecho'];
                $cambiar = $pdo->exec("update tasks set pendientes=true where id = '$value'");
            }
            if(isset($_GET['sinHacer'])) {
                $value = $_GET['sinHacer'];
                $cambiar = $pdo->exec("update tasks set pendientes=false where id = '$value'");
            }
            if(isset($_GET['borrar'])) {
                $value = $_GET['borrar'];
                $borrar = $pdo->exec("delete from tasks where id = '$value'");
            }
            if (isset($_POST["introducir"])) {
                $value = $_POST["introducir"];
                $query = $pdo->prepare("insert into taskss (lista_tareas, pendientes) values ('$value',false)");
                $query->execute();
            }
            echo "<br><br>";
            $query = $pdo->prepare("select * FROM tasks");
            $query->execute();
            $query2 = $pdo->prepare("select * FROM tasks");
            $query2->execute();
            echo "<b>Cosas pendientes</b> <br>";
            foreach ($query as $row) {
                if ($row['pendientes'] == 0) {
                    $idprimaria = $row['id'];
                    echo $row['lista_tareas'] ."\t"."<a href='?hecho=$idprimaria'>Hecho</a>"."\t"."<a href='?borrar=$idprimaria'>Borrar</a>". "<br>";
                }
              }
            echo "<br><br>";
            echo "<b>Cosas no pendientes</b> <br>";
            foreach ($query2 as $row) {
                if ($row['pendientes'] == 1) {
                    $idprimaria = $row['id'];
                    echo $row['lista_tareas'] ."\t"."<a href='?sinHacer=$idprimaria'>Sin hacer</a>"."\t"."<a href='?borrar=$idprimaria'>Borrar</a>". "<br>";
                }
            }
    //comprovo errors:
      $e= $query->errorInfo();
      if ($e[0]!='00000') {
        echo "\nPDO::errorInfo():\n";
        die("Error accedint a dades: " . $e[2]);
      }
     ?>

</body>
</html>