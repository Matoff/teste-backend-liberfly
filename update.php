<?php
    require 'database.php';
 
    $id = null;
    if ( !empty($_GET['id'])) {
        $id = $_REQUEST['id'];
    }
     
    if ( null==$id ) {
        header("Location: index.php");
    }
     
    if ( !empty($_POST)) {
        // keep track validation errors
        $nomeError = null;
        $telefoneError = null;
        $emailError = null;
        $aeroporto_origemError = null;
        $aeroporto_destinoError = null;
        $numero_vooError = null;
         
        // keep track post values
        $nome = $_POST['nome'];
        $telefone = $_POST['telefone'];
        $email = $_POST['email'];
        $aeroporto_origem = $_POST['aeroporto_origem'];
        $aeroporto_destino = $_POST['aeroporto_destino'];
        $numero_voo = $_POST['numero_voo'];
         
        // validate input
        $valid = true;
        if (empty($nome)) {
            $nomeError = 'Por favor insira seu nome';
            $valid = false;
        }
         
        if (empty($telefone)) {
            $telefoneError = 'Por favor insira seu telefone';
            $valid = false;
        }

        if (empty($email)) {
            $emailError = 'Por favor insira seu E-mail';
            $valid = false;
        } else if ( !filter_var($email,FILTER_VALIDATE_EMAIL) ) {
            $emailError = 'Por favor insira um E-mail Valido';
            $valid = false;
        }

        if (empty($aeroporto_origem)) {
            $aeroporto_origemError = 'Por favor insira origem';
            $valid = false;
        }

        if (empty($aeroporto_destino)) {
            $aeroporto_destinoError = 'Por favor insira  destino';
            $valid = false;
        }

        if (empty($numero_voo)) {
            $numero_vooError = 'Por favor insira numero de voo';
            $valid = false;
        }
         
        // update data
        // para o update, depende muito de como iria ser gerenciado. como é algo in house, não seria extremamente necessario criar uma plataforma para isso
        // mas não adianta pensar em trabalho feito pela metade, então para gerenciar e dar updates, como as tabelas são separadas seria algo dividido. 
        // um update para cliente, um para voo, e um para aeroportos ( endo aeroportos não sendo tão necessario, a não ser por manutenção, visando que 
        // vai estar com todas as possibilidades )
        if ($valid) {
            $pdo = Database::connect();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "UPDATE customers  set name = ?, email = ?, mobile =? WHERE id = ?";
            $q = $pdo->prepare($sql);
            $q->execute(array($name,$email,$mobile,$id));
            Database::disconnect();
            header("Location: index.php");
        }
    } else {
        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
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
        $nome = $data['nome'];
        $telefone = $data['telefone'];
        $email = $data['email'];
        $origem = $data['origem'];
        $destino = $data['destino'];
        $numero_voo = $data['numero_voo'];
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
                        <h3>Update a Customer</h3>
                    </div>
             
                    <div class="control-group <?php echo !empty($nomeError)?'error':'';?>">
                        <label class="control-label">Nome</label>
                        <div class="controls">
                            <input name="nome" type="text"  placeholder="Nome" value="<?php echo !empty($nome)?$nome:'';?>">
                            <?php if (!empty($nomeError)): ?>
                                <span class="help-inline"><?php echo $nomeError;?></span>
                            <?php endif; ?>
                        </div>
                      </div>

                      <div class="control-group <?php echo !empty($telefoneError)?'error':'';?>">
                        <label class="control-label">telefone</label>
                        <div class="controls">
                            <input name="telefone" type="text" placeholder="telefone" value="<?php echo !empty($telefone)?$telefone:'';?>">
                            <?php if (!empty($emailError)): ?>
                                <span class="help-inline"><?php echo $telefoneError;?></span>
                            <?php endif;?>
                        </div>
                      </div>

                      <div class="control-group <?php echo !empty($emailError)?'error':'';?>">
                        <label class="control-label">Email Address</label>
                        <div class="controls">
                            <input name="email" type="text" placeholder="E-mail" value="<?php echo !empty($email)?$email:'';?>">
                            <?php if (!empty($emailError)): ?>
                                <span class="help-inline"><?php echo $emailError;?></span>
                            <?php endif;?>
                        </div>
                      </div>

                      <div class="control-group <?php echo !empty($aeroporto_origemError)?'error':'';?>">
                        <label class="control-label">Origem</label>
                        <div class="controls">
                            <input name="aeroporto_origem" type="text" placeholder="Origem" value="<?php echo !empty($aeroporto_origem)?$aeroporto_origem:'';?>">
                            <?php if (!empty($aeroporto_origemError)): ?>
                                <span class="help-inline"><?php echo $aeroporto_origemError;?></span>
                            <?php endif;?>
                        </div>
                      </div>
 
                      <div class="control-group <?php echo !empty($aeroporto_destinoError)?'error':'';?>">
                        <label class="control-label">Destino</label>
                        <div class="controls">
                            <input name="aeroporto_destino" type="text" placeholder="destino" value="<?php echo !empty($aeroporto_destino)?$aeroporto_destino:'';?>">
                            <?php if (!empty($aeroporto_destinoError)): ?>
                                <span class="help-inline"><?php echo $aeroporto_destinoError;?></span>
                            <?php endif;?>
                        </div>
                      </div>

                      <div class="control-group <?php echo !empty($numero_vooError)?'error':'';?>">
                        <label class="control-label">Voo</label>
                        <div class="controls">
                            <input name="numero_voo" type="text" placeholder="Voo" value="<?php echo !empty($numero_voo)?$numero_voo:'';?>">
                            <?php if (!empty($numero_vooError)): ?>
                                <span class="help-inline"><?php echo $numero_vooError;?></span>
                            <?php endif;?>
                        </div>
                      </div>
                      <div class="form-actions">
                          <button type="submit" class="btn btn-success">Update</button>
                          <a class="btn" href="index.php">Back</a>
                        </div>
                    </form>
                </div>
                 
    </div> <!-- /container -->
  </body>
</html>