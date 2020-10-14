<?php
session_start();
//conexion a la base de données
$message= "";
$host = "localhost"; 
$database_userID= "root";
$password_serveur = "rootpassy3";
$database_name = "amgConfort";
 $link = mysqli_connect($host, $database_userID, $password_serveur)
           or die("Impossible de se connecter");

function secureInput($var){
    $trim_var = trim($var);
    $var_ = htmlspecialchars($trim_var);
    return $var_;
}           

if (isset($_POST['submit'])) {
  
    if(!empty($_POST['name_user'])){
      if(!empty($_POST['password_user'])){
          $userName = secureInput($_POST['name_user']);
          $passwordUser = secureInput($_POST['password_user']);
          mysqli_select_db($link,$database_name)
              or die("impossible de selectionner cette base de données");
          $sql = "SELECT * FROM utilisateurs where name_user='$userName' AND password_user='$passwordUser'";
         
          $result = mysqli_query($link, $sql);
          $line = mysqli_fetch_assoc($result);
          $rowcount=mysqli_num_rows($result);
          if($rowcount == 1){
            $_SESSION['name'] = $line['name_user'];
            $_SESSION['id'] = $line['id'];
            sleep(2);
            header("location: index_cloud.php?id=".$_SESSION['id']);
          }else {
            $message = "<p class='alert alert-danger'>le mot de passe ou le nom d'utilisateur ne correspond pas...</p>";
          }
        
          mysqli_free_result($result); // Libération des résultats
          mysqli_close($link); // Fermeture de la connexion
      }else{
        $message = "<p class='alert alert-danger'>vous devez entrer un mot de passe...</p>";
      }
    }else {
      $message = "<p class='alert alert-danger'>vous devez entrer un nom...</p>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  

  <title>cloud</title>

  <!-- Bootstrap CSS -->
  <link href="styles/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="styles/css/simple-sidebar.css" rel="stylesheet">
  <link rel="stylesheet" href="styles/css/style_cloud.css">
  <!-- Custom styles -->
  <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
  <link rel="icon" href='<i class="fas fa-cloud fas-2x"></i>'>
</head>
<body class="container-fluid" id="main_body">
  <h1>connectez-vous au Cloud.</h1>
  <div id="blockInput">
      <form action="" method="post">
        <input type="text" name="name_user" class="form-control" id="the_name" placeholder="entrerz un nom">
        <input type="password" class="form-control" name="password_user" id="the_password" required placeholder="entrez un mot de passe">
        <input type="submit" name="submit" value="valider" class="btn btn-primary btn-sm">
      </form>
      <section style="margin-top: 14px;"><?if(isset($message)) echo $message; ?></section>
  </div>
</body>
</html>