<?php
    require 'database.php';
    $id = null;
    if ( !empty($_GET['id'])) {
        $id = $_REQUEST['id'];
    }
     
    if ( null==$id ) {
        header("Location: index.php");
    } else {
        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        //SELECT completo dos dados usando join, planejado para ser adaptavel e receber duas vezes o aeroporto 
        //organizando as tabelas de maneiras mais gerenciavel. 
        $sql = "SELECT 
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
        WHERE incidente.id = ?";
        $q = $pdo->prepare($sql);
        $q->execute(array($id));
        $data = $q->fetch(PDO::FETCH_ASSOC);
        Database::disconnect();
    }
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <link   href="css/bootstrap.min.css" rel="stylesheet">
    <script src="js/bootstrap.min.js"></script>
</head>
 
<body>
    <div class="container">
     
                <div class="span10 offset1">
                    <div class="row">
                        <h3>Read a Customer</h3>
                    </div>
                     
                    <div class="form-horizontal" >
                      <div class="control-group">
                        <label class="control-label">id</label>
                        <div class="controls">
                            <label class="checkbox">
                                <?php echo $data['id'];?>
                            </label>
                        </div>
                      </div>

                      <div class="control-group">
                        <label class="control-label">Nome</label>
                        <div class="controls">
                            <label class="checkbox">
                                <?php echo $data['nome'];?>
                            </label>
                        </div>
                      </div>

                      <div class="control-group">
                        <label class="control-label">telefone</label>
                        <div class="controls">
                            <label class="checkbox">
                                <?php echo $data['telefone'];?>
                            </label>
                        
                        </div>
                      </div>

                      <div class="control-group">
                        <label class="control-label">email</label>
                        <div class="controls">
                            <label class="checkbox">
                                <?php echo $data['email'];?>
                            </label>
                        
                        </div>
                      </div>

                      <div class="control-group">
                        <label class="control-label">origem</label>
                        <div class="controls">
                            <label class="checkbox">
                                <?php echo $data['origem'];?>
                            </label>
                        
                        </div>
                      </div>

                      <div class="control-group">
                        <label class="control-label">destino</label>
                        <div class="controls">
                            <label class="checkbox">
                                <?php echo $data['destino'];?>
                            </label>
                        
                        </div>
                      </div>

                      <div class="control-group">
                        <label class="control-label">numero voo</label>
                        <div class="controls">
                            <label class="checkbox">
                                <?php echo $data['numero_voo'];?>
                            </label>
                        
                        </div>
                      </div>
                        <div class="form-actions">
                          <a class="btn" href="index.php">Back</a>
                       </div>
                     
                      
                    </div>
                </div>
                 
    </div> <!-- /container -->
  </body>
</html>