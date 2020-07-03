
<?php
// Initialize the session
session_start();
 
// Vérifions si l'utilisateur est connecté, sinon redirigeons-le vers la page de connexion
if(!isset($_SESSION["connecter"]) || $_SESSION["connecter"] !== true){
    header("location: ../index.php");
    exit;
}

require_once "./data/db.php";
$idparent=$_SESSION["id"];

$getid = $_SESSION['id'];
 $requser = $db->prepare('SELECT image FROM utilisateurs WHERE id = ?');
 $requser ->execute(array($getid));
 $userphoto = $requser->fetch();
// selection des roles
$sql_role=$db->prepare('SELECT * FROM roles  ');
$sql_role->execute(array());
 
$nom = $prenom = $genre = $adresse = $contact = $email = $motDePasse = $confMotDePasse = $roles = "";
$nom_err = $prenom_err = $genre_err = $adresse_err = $contact_err = $email_err = $motDePasse_err = $confMotDePasse_err = $role_err= $autorisation_err="";
$errorMsg = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    // validation du nom
    if (empty($_POST["nom"])) {
        $nom_err = "Le nom est obligatoire";
      } else {
        $nom = trim($_POST["nom"]);
      }
    // validation prenom
      if (empty($_POST["prenom"])) {
        $prenom_err = "Le prénom est obligatoire";
      } else {
        $prenom = trim($_POST["prenom"]);
      }
    // validation genre
      if (empty($_POST["genre"])) {
        $genre_err = "Le Genre est obligatoire";
      } else {
        $genre = trim($_POST["genre"]);
      }

      // validation ROLE
      if (empty($_POST["role"])) {
        $role_err = "Le role est obligatoire";
      } else {
        $roles = trim($_POST["role"]);
      }
    
    // validation lieu de residence
      if (empty($_POST["adresse"])) {
        $adresse_err = "Le Lieu de Résidence est obligatoire";
      } else {
        $adresse = trim($_POST["adresse"]);
      }
    // validation Télephone
    if (empty($_POST["contact"])) {
        $contact_err = "Le Numéro de téléphone est oblidatoire";
      } else {
        $contact = trim($_POST["contact"]);
      }

    // validation de email
    if(empty(trim($_POST["email"]))){
        $email_err = "Veuillez saisir un e-mail.";
    } else{
        $email = trim($_POST["email"]);

        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $email_err = "$email, n'est pas une adresse email valide";
        }else{
            $sql = "SELECT id FROM utilisateurs WHERE email = :email";
        
            if($stmt = $db->prepare($sql)){
                $stmt->bindParam(":email", $param_email, PDO::PARAM_STR);
                $param_email = trim($_POST["email"]);
                if($stmt->execute()){
                    if($stmt->rowCount() == 1){
                        $email_err = "Cet e-mail est déjà associé à un compte.";
                    } else{
                        $email = trim($_POST["email"]);
                    }
                } else{
                    $errorMsg = "Oops! Quelque chose a mal tourné. Veuillez réessayer plus tard.";
                }
                unset($stmt);
            }
        }
    }
    
    // Validate mot de passe
    if(empty(trim($_POST["motDePasse"]))){
        $motDePasse_err = "Veuillez entrer un mot de passe.";     
    } elseif(strlen(trim($_POST["motDePasse"])) < 6){
        $motDePasse_err = "Le mot de passe doit contenir au moins 6 caractères.";
    } else{
        $motDePasse = trim($_POST["motDePasse"]);
    }
    
    // Validate confirmation mot de passe
    if(empty(trim($_POST["confMotDePasse"]))){
        $confMotDePasse_err = "Veuillez confirmer le mot de passe.";     
    } else{
        $confMotDePasse = trim($_POST["confMotDePasse"]);
        if(empty($confMotDePasse_err) && ($motDePasse != $confMotDePasse)){
            $confMotDePasse_err = "Les mots de passe ne correspondent pas.";
        }
    }
    // Validate autorisation
   
        if (isset($_POST['Apays'])) {
            $ap = "oui"; 
        }
        else{
            $ap = "non";
        };


        if (isset($_POST['Aville'])) {
            $av = "oui"; 
        }
        else{
            $av = "non";
        };

        if (isset($_POST['ATevent'])) {
            $atevent = "oui"; 
        }
        else{
            $atevent = "non";
        };
        

    // Vérification des erreurs de saisie avant l'insertion dans la base de données
    if(empty($nom_err) && 
       empty($prenom_err) && 
       empty($genre_err) && 
       empty($adresse_err) &&
       empty($contact_err) &&
       empty($email_err) &&
       empty($motDePasse_err) &&
       empty($confMotDePasse_err)){
        
        // Préparons une instruction d'insertion
        $sql = "INSERT INTO utilisateurs (nom, prenom, genre, adresse, contact, email, motDePasse, idRole,autorisationApays,autorisationAtypeevent,autorisationAville,idparent) 
                VALUES (:nom, :prenom, :genre, :adresse, :contact, :email, :motDePasse, :roles, :ap, :atevent,:av, :id)";

        if($stmt = $db->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":nom", $param_nom, PDO::PARAM_STR);
            $stmt->bindParam(":prenom", $param_prenom, PDO::PARAM_STR);
            $stmt->bindParam(":genre", $param_genre, PDO::PARAM_STR);
            $stmt->bindParam(":adresse", $param_adresse, PDO::PARAM_STR);
            $stmt->bindParam(":contact", $param_contact, PDO::PARAM_STR);
            $stmt->bindParam(":email", $param_email, PDO::PARAM_STR);
            $stmt->bindParam(":motDePasse", $param_motDePasse, PDO::PARAM_STR);
            $stmt->bindParam(":roles", $param_roles, PDO::PARAM_STR);
            $stmt->bindParam(":ap", $param_ap, PDO::PARAM_STR);
            $stmt->bindParam(":atevent", $param_event, PDO::PARAM_STR);
            $stmt->bindParam(":av", $param_av, PDO::PARAM_STR);
            $stmt->bindParam(":id", $param_idparent, PDO::PARAM_STR);
            // Set parameters
            $param_nom = $nom;
            $param_prenom = $prenom;
            $param_genre = $genre;
            $param_adresse = $adresse;
            $param_contact = $contact;
            $param_email = $email;
            $param_roles=$roles;
            $param_ap=$ap;
            $param_event=$atevent;
            $param_av=$av;
            $param_idparent=$idparent;

            $param_motDePasse = password_hash($motDePasse, PASSWORD_DEFAULT); // Creates a password hash
          
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                $nomOp = $_POST["nom"];
                $emailOp = $_POST["email"];
                $pass = $_POST["motDePasse"];
                //$errorMsg  = "Opérateur <b>$nomOp</b> ajouté avec succès. Ces identifiants de connexion sont : <br> <b>Email:</b> $emailOp <br> <b>Mot de passe (à changer avant 24h):</b> $pass";
                $errorMsg = "success";
                $newActivite = [
                    ':activite'     => 'Ajout utilisateur',
                    ':dateactivite' => date("Y-m-d H:i:s"),
                    ':iduser'       => $_SESSION['id']
                ];
                //ajout d'activité
             
                $activite = "INSERT  INTO activites (intituleActivite, periode, idUtilisateur) VALUES ( :activite, :dateactivite, :iduser)";
              
                $rActivite = $db->prepare($activite)->execute($newActivite);
               
            } else{
                $errorMsg = "error";
            }

            // Close statement
            unset($stmt);
        }
    }
    
    // Close connection
    unset($db);
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Danger View - Admin | Ajouter un Agent</title>
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <script src="../include/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script> -->
    <?php 
        if($errorMsg === "success"){
            echo '<script type="text/javascript">
                    $(document).ready(function(){
                        const Toast = Swal.mixin({
                            toast: true,
                            position: "top-end",
                            showConfirmButton: false,
                            timer: 5000,
                            timerProgressBar: true,
                            onOpen: (toast) => {
                              toast.addEventListener("mouseenter", Swal.stopTimer)
                              toast.addEventListener("mouseleave", Swal.resumeTimer)
                            }
                          })
                          
                          Toast.fire({
                            icon: "success",
                            title: "Opérateur ajouté avec succès!"
                          })
                    });
                </script>';
        }elseif($errorMsg === "error"){
            echo '<script type="text/javascript">
                    $(document).ready(function(){
                        const Toast = Swal.mixin({
                            toast: true,
                            position: "top-end",
                            showConfirmButton: false,
                            timer: 5000,
                            timerProgressBar: true,
                            onOpen: (toast) => {
                              toast.addEventListener("mouseenter", Swal.stopTimer)
                              toast.addEventListener("mouseleave", Swal.resumeTimer)
                            }
                          })
                          
                          Toast.fire({
                            icon: "error",
                            title: "Une erreur s\'est produite lors de l\'ajout, veillez réessayer svp!"
                          })
                    });
                </script>';
        }
    ?>
    <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="../img/logo1.png" />
</head>

<body id="page-top">
    <div id="wrapper">
        <!-- Sidebar -->
        <ul class="navbar-nav sidebar sidebar-dark accordion" id="accordionSidebar" style="background: #000 !important;">
            <span class="sidebar-brand d-flex align-items-center justify-content-center">
                <div class="sidebar-brand-icon" style="cursor: pointer;">
                    <i class="fa fa-list" aria-hidden="true" id="sidebarToggle"></i>
                  </div>
                <div class="sidebar-brand-text">
                   &nbsp;&nbsp;&nbsp; Bad <b style="color: #ff1300;">Event</b>
                </div>
              </span>
            <hr class="sidebar-divider my-0">

            <li class="nav-item">
                <a class="nav-link" href="home.php">
                    <i class="fa fa-home" aria-hidden="true"></i>
                    <span>Accueil</span></a>
            </li>

            <li class="nav-item active">
                <a class="nav-link" href="ajouter_agent.php">
                    <i class="fa fa-user-plus" aria-hidden="true"></i>
                    <span>Ajouter un Agent</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="liste_agent.php">
                    <i class="fa fa-users" aria-hidden="true"></i>
                    <span>Liste des Agents</span></a>
            </li>
          
        </ul>
        <!-- / Sidebar -->

        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Main Content -->
            <div id="content">
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow" style="background: #ff1300 !important;font-weight: 700;">
                    <strong id="sidebarToggleTop" class="d-md-none" style="color: #fff !important;font-weight: 900;">
                      Bad <span style="color:#000">Event</span>
                    </strong>
                    <ul class="navbar-nav ml-auto">
                      

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <?php if (!empty($userphoto['image'])){?>
                                            <img src="avatar/<?php echo $userphoto['image'] ;?> " class="rounded-circle" width="50px" height="50px"/>
                                            <?php }else{?>
                                                <img src="Avatar/user.png" class="img-profile rounded-circle" alt="First sample avatar image">
                                                <?php } ?>&nbsp;&nbsp;
                                <span class="d-none d-lg-inline text-gray-600 small" style="color: #fff !important;font-weight: 800;">
                                    <?= htmlspecialchars($_SESSION["prenom"]); ?> 
                                </span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="profilA.php">
                                    <i class="fa fa-user fa-sm fa-fw mr-2"></i> Mon Profil
                                </a>
                                <a class="dropdown-item" href="../logout.php">
                                    <i class="fa fa-sign-out fa-sm fa-fw mr-2"></i> Déconnexion
                                </a>
                            </div>
                        </li>
                    </ul>
                </nav>
                <!-- / Topbar -->

                <!-- Contenue de la page -->
                <div class="container-fluid">
                    <div class="card mb-4">
                        <h5 class=" h4 card-header" style="background: #a19e9e !important">
                            <center>Ajouter un Agent</center>
                        </h5>
                        <div class="card-body">
                        <div class="row">
          <div class="col-lg-12">
            <div class="p-1">
              <form method="post" class="user" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class="">
                    <h5 style="color: #ffc500">Informations personnelle</h5>
                </div>
                <hr>
                <div class="form-group row <?php echo (!empty($nom_err) && !empty($prenom_err)) ? 'has-error' : ''; ?>">
                  <div class="col-sm-6 mb-3 mb-sm-0">
                    <input type="text" class="form-control form-control-user" id="nom" name="nom" placeholder="Nom">
                    <small style="color: #ff1300 !important">
                        <span class="align-items-center text-center">
                            <center>
                            <i><?php echo $nom_err; ?></i>
                            </center>
                        </span>
                    </small>
                  </div>
                  <div class="col-sm-6">
                    <input type="text" class="form-control form-control-user" id="prenom" name="prenom" placeholder="Prénom">
                    <small style="color: #ff1300 !important">
                        <span class="align-items-center text-center">
                            <center>
                            <i><?php echo $prenom_err; ?></i>
                            </center>
                        </span>
                    </small>
                  </div>
                </div>
                <div class="form-group row <?php echo (!empty($genre_err) && !empty($adresse_err) && !empty($contact_err)) ? 'has-error' : ''; ?>"">
                  <div class="col-sm-6 mb-3 mb-sm-0">
                    <input type="text" class="form-control form-control-user" id="contact" name="contact" placeholder="Numéro Téléphone">
                    <small style="color: #ff1300 !important">
                        <span class="align-items-center text-center">
                            <center>
                            <i><?php echo $contact_err; ?></i>
                            
                            </center>
                        </span>
                    </small>
                  </div>
                  <div class="col-sm-6 mb-3 mb-sm-0">
                    <input type="text" class="form-control form-control-user" id="adresse" name="adresse" placeholder="Lieu de résidence">
                    <small style="color: #ff1300 !important">
                        <span class="align-items-center text-center">
                            <center>
                            <i><?php echo $adresse_err; ?></i>
                            </center>
                        </span>
                    </small> 
                 </div>

                 <div class="col-sm-6 mb-3 mb-sm-0">
                   <label for="" class="mt-2 ml-3">Role:</label>
                    <select name="role" id="" class=" form-control " >
                        <?php while ($d = $sql_role->fetch() ){?>
                           <option value="<?= $d['id']?>" ><?= $d['description']?></option>
                        <?php }
                         $sql_role->closeCursor();
                    ?>
                    </select>
                    <small style="color: #ff1300 !important">
                        <span class="align-items-center text-center">
                            <center>
                            <i><?php echo $role_err; ?></i>
                            </center>
                        </span>
                    </small> 
                 </div>

                  <div class="col-sm-6 mt-3  mb-sm-0 " >
                      <label for="" class="ml-5">Genre:</label><br>
                  <input type=radio  class="ml-5" id=genre name=genre value=feminin 1> Feminin
                  <input type=radio   id=genre name=genre value=masculin 2> Masculin
                    <small style="color: #ff1300 !important">
                        <span class="align-items-center text-center">
                            <center>
                            <i><?php echo $genre_err; ?></i>
                            </center>
                        </span>
                    </small>
                  </div>

                
                  
                </div>


                <div class="mt-3">
                    <h5 style="color: #ffc500">Identifiants de connexion</h5>
                </div>
                <hr>
                <div class="form-group row <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>"">
                  <div class="col-sm-6 mb-3 mt-2 mb-sm-0">
                    <input type="text" class="form-control form-control-user" id="email" name="email" placeholder="Adresse Email">
                    <small style="color: #ff1300 !important">
                        <span class="align-items-center text-center">
                            <center>
                            <i><?php echo $email_err; ?></i>
                            </center>
                        </span>
                    </small>
                 </div>
                </div>
                <div class="form-group row <?php echo (!empty($motDePasse_err) && !empty($confMotDePasse_err)) ? 'has-error' : ''; ?>"">
                  <div class="col-sm-6 mb-3 mt-2 mb-sm-0">
                    <input type="password" class="form-control form-control-user" id="motDePasse" name="motDePasse" placeholder="Mot de passe">
                    <small style="color: #ff1300 !important">
                        <span class="align-items-center text-center">
                            <center>
                            <i><?php echo $motDePasse_err; ?></i>
                            </center>
                        </span>
                    </small>
                  </div>
                  <div class="col-sm-6 mt-2">
                    <input type="password" class="form-control form-control-user" id="confMotDePasse" name="confMotDePasse" placeholder="confirmez le mot de passe">
                    <small style="color: #ff1300 !important">
                        <span class="align-items-center text-center">
                            <center>
                            <i><?php echo $confMotDePasse_err; ?></i>
                            </center>
                        </span>
                    </small> 
                 </div>

                </div>
                <div class="mt-3">
                    <h5 style="color: #ffc500">Autorisation :</h5>
                </div>
                <hr>

                <div class="row">
                                <div class="form-group col-md">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="choix1" name="Apays" > 
                                        <label class="custom-control-label" for="choix1">Ajouter Pays</label>
                                    </div>
                                </div>
                                <div class="form-group col-md">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="choix2" name="Aville" >
                                        <label class="custom-control-label" for="choix2">Ajouter Ville</label>
                                    </div>
                                </div>
                                <div class="form-group col-md">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="choix3" name="ATevent" >
                                        <label class="custom-control-label" for="choix3">Ajouter type event</label>
                                    </div>
                                </div>
                                <small style="color: #ff1300 !important">
                                    <span class="align-items-center text-center">
                                        <center>
                                        <i><?php echo $autorisation_err; ?></i>
                                        </center>
                                    </span>
                                </small>

                            </div>
                           


                <div>
                    <div class="row">
                        <div class="col-md-3">
                            <input type="submit" class="btn btn-primary btn-user btn-block" style="background: #ffc500!important; color:#fff;" value="Enregister">
                        </div>
                        <div class="col-md-6">
                        </div>
                        <div class="col-md-3"></div>
                    </div>
                </div>
              </form>
            </div>
          </div>
        </div>
                        </div>
                    </div>
                </div>
                <!-- /Contenue de la page -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container">
                    <div class="copyright text-center">
                        <span></span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- ./ Wrapper -->

    <!-- Navigation top-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fa fa-angle-up"></i>
    </a>

    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

    <script type="text/javascript">
        ! function(t) {
            "use strict";
            t("#sidebarToggle, #sidebarToggleTop").on("click", function(o) {
                    t("body").toggleClass("sidebar-toggled"),
                        t(".sidebar").toggleClass("toggled"),
                        t(".sidebar").hasClass("toggled") &&
                        t(".sidebar .collapse").collapse("hide")
                }),
                t(window).resize(function() {
                    t(window).width() < 768 &&
                        t(".sidebar .collapse").collapse("hide")
                }),
                t("body.fixed-nav .sidebar").on("mousewheel DOMMouseScroll wheel", function(o) {
                    if (768 < t(window).width()) {
                        var e = o.originalEvent,
                            l = e.wheelDelta || -e.detail;
                        this.scrollTop += 30 * (l < 0 ? 1 : -1), o.preventDefault()
                    }
                }), t(document).on("scroll", function() {
                    100 < t(this).scrollTop() ? t(".scroll-to-top").fadeIn() :
                        t(".scroll-to-top").fadeOut()
                }),
                t(document).on("click", "a.scroll-to-top", function(o) {
                    var e = t(this);
                    t("html, body").stop().animate({
                            scrollTop: t(e.attr("href")).offset().top
                        }, 1e3, "easeInOutExpo"),
                        o.preventDefault()
                })
        }(jQuery);
    </script>
</body>

</html>