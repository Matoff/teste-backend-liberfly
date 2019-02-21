<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <link   href="css/bootstrap.min.css" rel="stylesheet">
    <script src="js/bootstrap.min.js"></script>
</head>
 
<body>
    <div class="container">
            <div class="row">
                <h3>Teste</h3>
            </div>
            <div class="row">
                <p>
                    <a href="create.php" class="btn btn-success">Create</a>
                </p>
                <table class="table table-striped table-bordered">
                  <thead>
                    <tr>
                      <th>id</th>
                      <th>nome</th>
                      <th>telefone</th>
                      <th>email</th>
                      <th>origem</th>
                      <th>destino</th>
                      <th>voo</th>
                    </tr>
                  </thead>
                  <tbody>
                  <?php
                   include 'database.php';
                   $pdo = Database::connect();
                   //SELECT completo dos dados usando join, planejado para ser adaptavel e receber duas vezes o aeroporto 
                   //organizando as tabelas de maneiras mais gerenciavel.
                   $sql = 'SELECT 
                   incidente.id,
                   cliente.nome,
                   cliente.telefone,
                   cliente.email,
                   origem.sigla as origem,
                   destino.sigla as destino,
                   incidente.numero_voo
                   FROM incidente
                   INNER JOIN cliente ON incidente.id_cliente = cliente.id
                   INNER JOIN aeroporto origem ON incidente.aeroporto_origem = origem.id
                   INNER JOIN aeroporto destino ON incidente.aeroporto_destino = destino.id
                   ORDER BY cliente.id';
                   foreach ($pdo->query($sql) as $row) {
                            echo '<tr>';
                            echo '<td>'. $row['id'] . '</td>';
                            echo '<td>'. $row['telefone'] . '</td>';
                            echo '<td>'. $row['email'] . '</td>';
                            echo '<td>'. $row['origem'] . '</td>';
                            echo '<td>'. $row['destino'] . '</td>';
                            echo '<td>'. $row['numero_voo'] . '</td>';
                            echo '<td width=250>';
                            echo '<a class="btn" href="read.php?id='.$row['id'].'">Read</a>';
                            echo ' ';
                            echo '<a class="btn btn-success" href="update.php?id='.$row['id'].'">Update</a>';
                            echo ' ';
                            echo '<a class="btn btn-danger" href="delete.php?id='.$row['id'].'">Delete</a>';
                            echo '</td>';
                   }
                   Database::disconnect();
                  ?>
                  </tbody>
            </table>
        </div>
    </div> <!-- /container -->
  </body>
</html>