<?php
session_start();
$message = "";
$host = "localhost";
$database_userID = "root";
$password_serveur = "rootpassy3";
$database_name = "amgConfort";
// on commence par se connecter a la database pour conserver le nom des sessions
$link = mysqli_connect($host, $database_userID, $password_serveur)
  or die("Impossible de se connecter");

if (isset($_GET['id']) and $_GET['id'] > 0) {
  $getid = intval($_GET['id']);
  mysqli_select_db($link, $database_name)
    or die("impossible de selectionner cette base de données");
  $sql = "SELECT * FROM utilisateurs where id='$getid'";
  $result = mysqli_query($link, $sql);
  $line = mysqli_fetch_assoc($result);
  $_SESSION['id'] = $line['id'];
  $_SESSION['name'] = $line['name_user'];
}
/*
   cette function sert a ignorer les points dans un tableau
    parametre: unDossier
    @return: array
  */

function ignore_point(array $unDossier)
{
  $new_dossier = array_diff($unDossier, ['.', '..', '.DS_Store']);
  return $new_dossier;
}
//permet d'afficher un message a l'utlisateur.
function displayMessage($message, $alertColor)
{
  header("Refresh: 2");
  echo '<div class="alert alert-' . $alertColor . ' alert-dismissible fade show" role="alert" id="custom_alert">
            ' . $message . '
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>';
}

function modal_supression($key, $fich){
  echo  '<div class="modal fade" id="exampleModal_'.$key.'" tabindex="'.$key.'" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">suppression</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          voulez-vous vraiment supprimez le fichier "<strong><i>'.$fich.'</i></strong>" ?

        </div>
        <div class="modal-footer">

          <button type="button" class="btn btn-secondary" data-dismiss="modal">annuler</button>
          <button class="btn btn-danger" name="submit_delete" type="submit"><i style="color:red;" class="far fa-trash-alt fa-1x"></i> confirmer la suppression</button>
        </div>
      </div>
    </div>
  </div>';
}
function directoryCheck($directory_name)
{

  //verifie si le dossier existe
  if (file_exists($directory_name)) {
    if (is_dir($directory_name)) {
      if (isset($directory_name)) {
        $dir = htmlspecialchars($directory_name);
        $dir_ = trim($dir);
        $dir_1 = scandir($dir_);
        $final_dir = ignore_point($dir_1);
      }
    }
  } else {
  }

  return $final_dir;
}
//creer un dossier
$chemin_cloud =  dirname(__FILE__);
$dossier = $chemin_cloud;
$dossier = str_replace("\\", "/", $dossier);
$fichierInDocs = scandir($dossier);
$directory = array_diff($fichierInDocs, ['.', '..', 'styles', 'node_modules']);
if (isset($_POST['submit_new_dossier'])) {
  if (isset($_POST['name_dossier'])) {
    if (!empty($_POST['name_dossier'])) {
      $name_dossier = $_POST['name_dossier'];
      $name_dossier = htmlspecialchars($name_dossier);
      $name_dossier = trim($name_dossier);
      $name_dossier = strtolower($name_dossier);
      $name_dossier = ucfirst($name_dossier);
      if (!file_exists($name_dossier)) :
        mkdir($name_dossier, 0755, true);
        chmod($name_dossier, 0755);

        displayMessage("dossier crée avec succès", "success");
      else :
        displayMessage("ce dossier existe deja", "warning");
      endif;
    } else {
      displayMessage("entrez un nom de dossier valide", "warning");
    }
  }
}

function directories_of_cloud(){
  $chemin_cloud =  dirname(__FILE__);
  $dossier = $chemin_cloud;
  $dossier = str_replace("\\", "/", $dossier);
  $fichierInDocs = scandir($dossier);
  $directory = array_diff($fichierInDocs, ['.', '..', 'styles', ".DS_Store"]);
  return $directory;
}
function extension_name($nom)
{
  $nom_fichier = pathinfo($nom);
  $extension = $nom_fichier['extension'];
  return $extension;
}
function name_fichier($nom)
{
  $nom_fichier = pathinfo($nom);
  $name = $nom_fichier['filename'];
  return $name;
}
//l'upload de fichier
if (isset($_POST['upload_fichier'])) {
  if (!empty($_FILES['the_file']) && $_FILES['the_file']['error'] == UPLOAD_ERR_OK &&  is_uploaded_file($_FILES['the_file']['tmp_name'])) {
    if (isset($_GET['page'])) {
      $destination = $_GET['page'];
      if (file_exists($destination)) {
        if ($_FILES['the_file']['error'] > 0) {
          if ($_FILES['the_file']['error'] == 1) :

            displayMessage("une erreur est survenue ...La taille du fichier doit etre inferieur a 2Mo", "warning");
          elseif ($_FILES['the_file']['error'] == 2) :
            displayMessage("La taille du fichier téléchargé excède la valeur de MAX", "warning");
          endif;
        } elseif (($_FILES['the_file']['size'] / 1024) < 30000) {
          echo $_FILES['the_file']['extension'];
          $nom_fichier = $_FILES['the_file']['name'];
          $tmp_name = $_FILES["the_file"]["tmp_name"];
          $name = basename($_FILES["the_file"]["name"]);
          $isItGood = move_uploaded_file($tmp_name, "$destination/$name");
          displayMessage("fichier ajouté", "success");
        } else {
          displayMessage("fichier trop lourd vous la taille limite est de " . (20000 / 1024) . " octects", "warning");
        }
      } else {
        displayMessage("le dossier n'existe pas...", "danger");
      }
    } else {
      displayMessage("vous devez choisir un dossier avant d'ajouter un fichier", "warning");
    }
  }
}

//le telechargement de fichier vers notre ordinateur
if (isset($_POST['submit_rapatrier'])) {
  //on commence par regarder ci le fichier en dans le dossier dit
  $file_name = basename($_GET['f']);
  $main_directory = $_GET['page'];
  if (file_exists("$main_directory/$file_name")) {
    $full_path = "$chemin_cloud/$main_directory/$file_name";

    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . basename($file_name) . '"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($full_path));
    readfile($full_path);
    displayMessage("fichier téléchargé avec succès", "success");
    exit;
  }
}


if (isset($_POST['submit_delete'])) {
  $file_name = basename($_GET['f']);
  $main_directory = $_GET['page'];
  if (file_exists("$main_directory/$file_name")) {
    $the_file_to_delete = "$main_directory/$file_name";
    unlink($the_file_to_delete);
    displayMessage("fichier supprimé", "danger");
  }
}
if ($_SESSION['name'] == "") {
  Header('location: index.php');
}
//tri des elements pour les mettres en minuscule
// pour une meilleures comparaison par ordre alphabetique
function transformInLowercase($value)
{
  if (is_array($value)) {
    return array_map('transformInLowercase', $value);
  }
  return strtolower($value);
}
//triez par extension.
function tri_par_extention($tableaux, $extension)
{
  $new_tab = [];
  foreach ($tableaux as $key => $fichier) :
    $extention_fichier = extension_name($fichier);
    if ($extension === $extention_fichier) {
      $new_tab[$key] = $fichier;
    }
  endforeach;
  return $new_tab;
}
//triez par date par ordre chronologique plus acienne au plus recent.

function tri_by_date($tabs, $dossierSource)
{  //tabs = dir
  $files = array();
  foreach ($tabs as $file) {
    $filemtime = filemtime($dossierSource . '/' . $file);
    $files[$file] = $filemtime;
  }
  arsort($files);
  $files = array_keys($files);

  return ($files) ? $files : false;
}
//trié par date par ordre chronologique plus recent au plus ancien.

function tri_by_date_reverse($tabs, $dossierSource)
{  //tabs = dir
  $files = array();
  foreach ($tabs as $file) {
    $filemtime = filemtime($dossierSource . '/' . $file);
    $files[$file] = $filemtime;
  }
  asort($files);
  $files = array_keys($files);

  return ($files) ? $files : false;
}
// recuperer les dossiers
$i = 0;
function allDocuments()
{
  $chemin_cloud =  dirname(__FILE__);
  $dossier = $chemin_cloud;
  $dossier = str_replace("\\", "/", $dossier);

  $fichierInDocs = scandir($dossier);
  $directory = array_diff($fichierInDocs, ['.', '..', 'styles', ".DS_Store", 'index.php', "index_cloud.php", "package-lock.json"]);
  $the_docs = [];
  foreach ($directory as $un_dossier) {
    // var_dump($un_dossier);
    if (is_dir("$chemin_cloud/$un_dossier")) {
      $docs_asArray = scandir($un_dossier);
      foreach ($docs_asArray as $leFichier) {
        $docs_asArray = ignore_point($docs_asArray);
      }
    }
  }
  $the_docs = ignore_point($the_docs);
}
if (isset($_POST['deplacement'])) {
  $directory = directories_of_cloud();
  $fichier_a_dep = $_POST["the_file_todeplace"];
  $new_place = $_POST["to_go"];
  if(isset($new_place)){
    foreach ($directory as $key => $dossier) {
      if (is_dir("$chemin_cloud/$dossier")) {
        $new_docs = scandir($dossier);
        foreach ($new_docs as $key => $the_fichier) {
          if ($fichier_a_dep === $the_fichier) {
            if(file_exists("$new_place/$the_fichier")){
              displayMessage("ce fichier existe deja dans ce dossier", "warning");
            }else {
              $yes = copy("$dossier/$fichier_a_dep", "$new_place/$the_fichier");
              if ($yes) {
                unlink("$dossier/$fichier_a_dep");
                displayMessage("fichier déplacé avec success", "success");
              }
            }
          }
        }
      }
    }
  }else {
    displayMessage("aucun dossier selectionné", "warning");
  }
}
// editer 
if(isset($_POST['rename'])){
  echo $_POST['fichier_nom_edit'];
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
  <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />

</head>

<body>

  <div class="d-flex" id="wrapper" class="toggled">

    <!-- Sidebar -->
    <div class="bg-light border-right" id="sidebar-wrapper">
      <div class="sidebar-heading"><a href="index_cloud.php">Cloud <i class="fas fa-cloud fas-2x"></i></a></div>
      <div class="list-group list-group-flush " id="myDiv_side">
        <?php foreach ($directory as $fichier) :
          if (is_dir($dossier . "/" . $fichier)) : ?>
            <a href="index_cloud.php?page=<?= $fichier ?>" id="<?= $fichier ?>" name="list_<?= $fichier ?>" onclick="getDossier(this)" class="list-group-item list-group-item-action allDOCS"><i class="fas fa-folder-open"></i> <?= $fichier ?></a>

        <?php endif;
        endforeach
        ?>
      </div>
    </div>
    <!-- /#sidebar-toogle -->

    <!-- Page Content -->
    <div id="page-content-wrapper">

      <div class="panneau">
        <h2>Panneau CLOUD</h2>
      </div>
      <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
        <button class="btn btn-primary liste_btn" data-toggle="collapse" id="menu-toggle">listes</button>

        <div class="row all_buttons">
          <div class="col-xs-6">
            <button class="btn btn-primary btn-sm" id="add_fichier" disabled name="add_fichier" onclick="getId(this)">ajouter un fichier</button>

            <button class="btn btn-primary btn-sm" id="up_fichier" name="up_fichier" onclick="getId(this)">upload fichier</button>
            <form action="" id="rapatrier" method="post" style="margin-top: 10px;">
              <button class=" btn btn-primary btn-sm" href="deconnexion.php"><a href="deconnexion.php">deconnexion <i class="fas fa-sign-out-alt"></i></a></button>

            </form>
          </div>
        </div>
      </nav>

      <div class="container-fluid">
        <!-- quand on clique sur créer un repertoire on affiche ça -->
        <section id="name_directory" class="row show_input_create">
          <form action="" method="post">
            <input type="text" placeholder="nom du dossier" name="name_dossier" id="">
            <button name="submit_new_dossier">valider</button>
          </form>

        </section>
        <!-- fin d'affichage de créer un repertoire -->
        <div class="row show_input_create" id="upload_">
          <form action="" method="post" enctype="multipart/form-data">
            <div class="form-group">
              <label for="exampleFormControlFile1">ajouter un fichier a ce dossier</label>
              <input type="file" name="the_file" class="form-control-file" id="exampleFormControlFile1">
              <button class="btn btn-sm btn-primary" name="upload_fichier" type="submit">ajouter</button>
            </div>
          </form>
        </div>
      </div>
      <!-- afficher les fichiers d'un repertoire -->
      <?php if (file_exists($_GET['page'])) : ?>
        <div class="table-responsive-sm " id="show_table">
          <button class="btn btn-secondary btn-sm" id="deplace" name="deplacement" type="submit">déplacez</button>
          <div style="margin-left:10px">
            <form action="" method="post">
              trié par:
              <select class="" name="extensionSelect" id="">
                <option value="png">png</option>
                <option value="pdf">pdf</option>
                <option value="txt">txt</option>
                <option value="odt">odt</option>
                <option value="word">word</option>
              </select>

              <button type="submit" name='sendExtensions' class="btn btn-default"><i class="fas fa-check"></i></button>
              <input type="submit" class="btn btn-primary btn-sm" name="tri_alpha" id="tri-alpha" value="Tri alphabétique">
              <input type="submit" class="btn btn-primary btn-sm" name="all_documents" id="all_documents" value="tous les documents">
              <input title=" du plus recent au plus ancien" type="submit" class="btn btn-primary btn-sm" name="tri_chronologique" id="tri_chronologique" value="Tri chronologique">
              <button title="trié du plus ancien au plus recent" name="tri_chronologique2" class="btn btn-default" type="submit"><i class="fas fa-history fa-2x"></i></button>
              <input type="text" class="form-control" name="" id="recherche_fichier" placeholder="recherchez un fichier" style="display:inline; width: 30%;">
            </form>
          </div>
          <div id= "this_table" class="table table-hover table-wrapper-scroll-y my-scrollbar">
            <table id="tableDonees" class="table table-bordered table-striped mb-0">
              <thead>
                <tr>
                  <th scope="col">fichier </th>
                  <th scope="col">extension</th>
                  <?php if (isset($_POST['all_documents'])) : ?>
                    <th scope="col">dossiers source</th>
                  <?php endif ?>
                  <th scope="col">rapatrié</th>
                  <th scope="col">supprimez / déplacez</th>
                  <th scope="col">ajouter par</th>
                  <th scope="col">date ajout fichier</th>
                  <th scope="col">date création fichier</th>
                </tr>
              </thead>
              <tbody id="tableCorps">
                <?php
                if (isset($_GET['page'])) {
                  $page = $_GET['page'];
                  $docs = directoryCheck($page);
                  if (isset($_POST['tri_alpha'])) {
                    $docs = transformInLowercase($docs);
                    asort($docs);
                  } else {
                    $docs;
                  }
                  if (isset($_POST['sendExtensions'])) {
                    $requireExtension = $_POST['extensionSelect'];
                    $docs = tri_par_extention($docs, $requireExtension);
                  }
                  if (isset($_POST['tri_chronologique'])) {
                    $docs = tri_by_date($docs, $page);
                  }
                  if (isset($_POST['tri_chronologique2'])) {
                    $docs = tri_by_date_reverse($docs, $page);
                  }
                  if (isset($_POST['all_documents'])) {
                    $chemin_cloud =  dirname(__FILE__);
                    $dossier = $chemin_cloud;
                    $dossier = str_replace("\\", "/", $dossier);
                    $fichierInDocs = scandir($dossier);
                    $directory = array_diff($fichierInDocs, ['.', '..', 'styles', ".DS_Store"]);
                    $the_docs = [];
                    $id = 0;
                    foreach ($directory as $un_dossier) {
                      
                      if (is_dir("$chemin_cloud/$un_dossier")) :
                        $docs_asArray = scandir($un_dossier);
                        $docs_asArray = ignore_point($docs_asArray);
                        
                        foreach ($docs_asArray as $leFichier) :  ?>
                          <form action="index_cloud.php?page=<?= $page ?>&f=<?= $leFichier ?>" method="post">
                            <tr class="<?= name_fichier($leFichier) . "_checkbox" ?>">
                              <td contenteditable="false" onclick="getFichier(this)" id='<?= name_fichier($leFichier) . "_title" ?>' scope="row"><?= $leFichier ?></td>
                              <td> <?= extension_name($leFichier) ?></td>
                              <td> <?= $un_dossier ?></td>
                              <td><button class="btn btn-default" disabled name="submit_rapatrier" type="submit"><i style="color:burlywood;" class="fas fa-file-download fa-2x"></i></button> </td>
                              <td>
                                <button type="button" disabled title="impossible de supprimer le fichier hors du dossier source" class="btn btn-default" data-toggle="modal" data-target="#exampleModal_<?=$id?>">
                                  <i style="color:red;" class="far fa-trash-alt fa-1x"></i>
                                </button>
                              </td>
                              <td> <strong><?= $_SESSION['name'] ?></strong> </td>
                              <td><?= date("d F Y", filemtime("$un_dossier/$leFichier")) ?></td>
                              <td><?= date("d F Y", filectime("$un_dossier/$leFichier")) ?></td>
                              <!-- Modal -->
                            </tr>
                          </form>
                      <?php endforeach;
                      endif;
                    }
                  } else {
                    foreach ($docs as  $key => $fich) : ?>
                      <form action="index_cloud.php?page=<?= $page ?>&f=<?= $fich ?>" id="formTable" method="post">
                        <tr class="<?= name_fichier($fich) . "_checkbox" ?>">
                          <td  class="pt-3-half" contenteditable="true" onclick="getFichier(this)" id='<?= name_fichier($fich) . "_title" ?>' scope="row"><?= $fich ?></td>
                          <td> <?= extension_name($fich) ?></td>
                         
                          <td><button class="btn btn-default" name="submit_rapatrier" type="submit"><i style="color:burlywood;" class="fas fa-file-download fa-2x"></i></button> </td>
                          <td>
                            <button type="button" name="" value="ici la" class="btn btn-default" data-toggle="modal" data-target="#exampleModal_<?= $key ?>">
                              <i style="color:red;" class="far fa-trash-alt fa-1x"></i>
                            </button>
                            <!-- button deplacer -->
                            <button type="button" class="btn btn-default" data-toggle="modal" data-target="#modalDeplacement_<?= $key ?>">
                              <i class="fas fa-file-export" style="color: green;"></i>
                            </button>
                          </td>
                          <td> <strong><?= $_SESSION['name'] ?></strong> </td>
                          <td><?= date("d F Y H:i", filemtime("$page/$fich")) ?></td>
                          <td><?= date("d F Y H:i", filectime("$page/$fich")) ?></td>
                          <!-- modal de deplacement -->
                          <div class="modal fade" id="modalDeplacement_<?= $key ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h5 class="modal-title" id="exampleModalLongTitle">deplacez le fichier: "<i><small><?= $fich ?></small></i>" dans :</h5>
                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                  </button>
                                </div>
                                <div class="modal-body">
                                  <?php
                                  $directory = directories_of_cloud();
                                  for ($i = 0; $i < count($directory); $i++) {
                                    if (is_dir($directory[$i])) : ?>
                                      <label class="btn btn-outline-info">
                                        <input type="radio" name="to_go" id="option_<?= $i ?>" value="<?= $directory[$i] ?>" autocomplete="off"> <?= $directory[$i] ?>
                                      </label>

                                  <?php endif;
                                  }

                                  ?>
                                  <input type="text" style="visibility: hidden;" name="the_file_todeplace" value="<?= $fich ?>">
                                </div>
                                <div class="modal-footer">
                                  <button type="button" class="btn btn-secondary" data-dismiss="modal">fermer</button>
                                  <button type="submit" name="deplacement" class="btn btn-info">deplacer</button>
                                </div>
                              </div>
                            </div>
                          </div>
                          <!-- fin modal de deplacement -->
                          <!-- Modal suppression -->
                          <?= modal_supression($key,$fich) ?>

                        </tr>
                      </form>
                <?php endforeach;
                  }
                } ?>
              </tbody>

            </table>
          </div>

        </div>
        <!-- modal -->
      <?php elseif (isset($_GET['page'])) : ?>
        <h2 style="text-align: center;">ce dossier n'existe pas...</h2>
      <?php endif; ?>
    </div>
  </div>
  </div>
  <!-- /#wrapper -->

  <!-- Bootstrap core JavaScript -->
  <script src="styles/vendor/jquery/jquery.min.js"></script>
  <script src="styles/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="styles/vendor/script.js"></script>

  <!-- Menu Toggle Script -->
  <script>
    $("#menu-toggle").click(function(e) {
      e.preventDefault();
      $("#wrapper").toggleClass("toggled");
    });
  </script>
  <footer>
    <button class="btn btn-danger btn-sm" id="sup_fichier" disabled name="delete_fichier">supprimer un fichier</button>
    <button class="btn btn-primary btn-sm" id="ajout" name="add_repertoire" onclick="getId(this)">ajouter un repertoire</button>
  </footer>
</body>

</html>