<?php
     
    require 'database.php';
 
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

         
        // insert data
        // primeiro o cliente, depois ele cadastraria seu incidente. 
        // o certo seria duas paginas, uma para o cliente e uma para o incidente. 

        //id cliente seria o id da sessão que o cliente estaria no site, com autenticação de usuario.
        $id_Cliente = null;
        /*
        Os Id's do aeroportos seria colocados em um drop down, mas como são muitos na vida real, eu pediria para digitar
        e prepararia um sistema de pesquisa e auto completação. Como eu ja teria em banco todos os possiveis combinações de siglas,
        não seria necesario cadastrar uma nova assim que o cliente a inserisse. Dando mais controle e evitando erro humano.  
         */
        $id_Aeroporto_Origem = null;
        $id_Aeroporto_Destino = null;
        if ($valid) {
            $pdo = Database::connect();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            //inserindo usuario
            $sql = "INSERT INTO cliente (nome,telefone,email) values(?, ?, ?)";
            $q = $pdo->prepare($sql);
            $q->execute(array($nome,$telefone,$email));
            //inserio o aeroporto 
            // mas poderia muito bem ser um drop down, ou uma pesquisa rapida, de acordo com a digitação do usuario,
            //quanto mais responsivo melhor. 
            $sql = "INSERT INTO aeroporto (sigla) values(?),(?)";
            $q = $pdo->prepare($sql);
            $q->execute(array($aeroporto_origem,$aeroporto_destino,$email));
            //inserito incidente, numero de voo e é dessa unica tabela que seria feito o select. 
            //dois inner join, um para o cliente e dois, um para cada aeroporto. 
            $sql = "INSERT INTO incidente (id_cliente,aeroporto_origem,aeroporto_destino,numero_voo) values(?,?,?,?)";
            $q = $pdo->prepare($sql);
            $q->execute(array($id_Cliente,$id_Aeroporto_Origem,$id_Aeroporto_Destino,$numero_voo)); // numero_voo sendo a unica informação nova. cliente e aeroportos ja estariam cadastrados ( assumindo que o cliente se cadastrou em uma pagina anterior e está em sessão.)
            Database::disconnect();
            header("Location: index.php");
        }
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
                        <h3>Cadastrando Incidente</h3>
                    </div>
             
                    <form class="form-horizontal" action="create.php" method="post">

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
                          <button type="submit" class="btn btn-success">Create</button>
                          <a class="btn" href="index.php">Back</a>
                        </div>
                    </form>
                </div>
                 
    </div> <!-- /container -->
  </body>
</html>
        