<?php
// Initialize the session
session_start();
$id_user = $_SESSION['id']; 
    $pageName = "Page admin";
 
// Vérifions si l'utilisateur est connecté, sinon redirigeons-le vers la page de connexion
if(!isset($_SESSION["connecter"]) || $_SESSION["connecter"] !== true){
    header("location: ../index.php");
    exit;

}
require_once "./data/db.php";
//requete pour selectionner les info de lutilisateur
$req_info=$db->prepare('SELECT * FROM utilisateurs WHERE id = :iduser');
$req_info->bindValue(':iduser',$id_user ,PDO::PARAM_INT);
//execution de la requete
$executeIsOk= $req_info->execute();
//on recupere les info 
$info=$req_info->fetch();

$date_now = new DateTime('now');
$email_err =$errorMsg =$pass_err ='';
    
if(isset($_POST['update'])){
     
    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $email = trim($_POST['email']);
    $genre = trim($_POST['genre']);
    $adresse = trim($_POST['adresse']);
    $contact = trim($_POST['contact']);
 
        if  (!empty($nom) and !empty($prenom) and !empty($email) and !empty($adresse) and !empty($contact) and !empty($genre) ){
                
        
             //validation email
                if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                    $errorMsg= "$email, n'est pas une adresse email valide";
                }else{
                    $sql = "SELECT id FROM utilisateurs WHERE email = :email and id != :iduser";
                
                    if($stmt = $db->prepare($sql)){
                        $stmt->bindParam(":email", $param_email, PDO::PARAM_STR);
                        $stmt->bindParam(":iduser", $param_id, PDO::PARAM_INT);
                        $param_email = trim($_POST["email"]);
                        $$param_id = $id_user;
                        if($stmt->execute()){
                            if($stmt->rowCount() == 1){
                                $errorMsg = "Cet e-mail est déjà associé à un compte.";
                            } else{
                                $email = trim($_POST["email"]);


                            }
                        } else{
                            $errorMsg= "Oops! Quelque chose a mal tourné. Veuillez réessayer plus tard.";
                        }
                        unset($stmt);
                    }
                }

                //requete de modification
            
             
             $edituser = [ 
                'nom'           => $nom, 
                'prenom'        => $prenom, 
                'genre'         => $genre,
                'contact'       => $contact, 
                'adresse'       => $adresse, 
                'email'         => $email, 
                'iduser'        => $id_user, 
                'datemodif'     => date("Y-m-d H:i:s")
               
               
               ];
             
               $update_user = ("UPDATE utilisateurs  SET nom=:nom, prenom=:prenom, genre=:genre, contact=:contact, adresse=:adresse, email=:email ,dateModification=:datemodif WHERE id=:iduser") ;
               $executeIsOk= $resultat = $db->prepare($update_user)->execute($edituser);
            
             if($executeIsOk){

                $newActivite = [
                    ':activite'     => 'Modification dinfo personnel',
                    ':dateactivite' => date("Y-m-d H:i:s"),
                    ':iduser'       => $_SESSION['id']
                ];
                //ajout d'activité
                $activite = "INSERT  INTO activites (intituleActivite, periode, idUtilisateur) VALUES ( :activite, :dateactivite, :iduser)";
                $rActivite = $db->prepare($activite)->execute($newActivite);
              


                $errorMsg= "validé";
                $_SESSION['nom'] =$nom; 
                $_SESSION['genre'] =$genre;
                $_SESSION['contact'] =$contact;
                $_SESSION['adresse'] =$adresse;
                $_SESSION['email'] =$email;
                $_SESSION['prenom'] =$prenom;
             } else{
                $errorMsg= "ERROR";
             }
       // Bind variables to the prepared statement as parameters

 
         } else{$errorMsg = "Remplissez tous les champs svp";}
  
       
       

}

if(isset($_POST['update_pass'])){
    
    $reqmdp= $db->prepare("SELECT motDePasse FROM utilisateurs  WHERE id = ?");
    $reqmdp->execute(array($_SESSION['id']));
    $motpass= $reqmdp->fetch();

    if(!empty($_POST['lastPassword'] ) and !empty($_POST['password']) and !empty($_POST['confPassword']) ){
        
        $lastpassword = trim($_POST['lastPassword']);
        $password= trim($_POST['password']);
        $confpassword= trim($_POST['confPassword']);
        
        if(strlen($lastpassword) < 6 ){
            $pass_err = "Le mot de passe doit contenir au moins 6 caractères.";
        }
           
          
            if(password_verify(trim($lastpassword), $motpass['motDePasse'])){

                if(strlen($password) < 6  ){
                    $pass_err = "Le mot de passe doit contenir au moins 6 caractères.";
                }
                if($password==$confpassword){

                    $confpassword=password_hash( $confpassword, PASSWORD_DEFAULT);

                    $req=$db->prepare("UPDATE utilisateurs SET motDePasse =:pass WHERE id =:iduser");
                    $req->bindValue(":pass", $confpassword, PDO::PARAM_STR);
                    $req->bindValue(":iduser",$id_user, PDO::PARAM_INT);
                    $executeIsOk =$req->execute();
                    if($executeIsOk){

                        $newActivite = [
                            ':activite'     => 'ModifiCATION MOT DE PASSE',
                            ':dateactivite' => date("Y-m-d H:i:s"),
                            ':iduser'       => $_SESSION['id']
                        ];
                        //ajout d'activité
                        $activite = "INSERT  INTO activites (intituleActivite, periode, idUtilisateur) VALUES ( :activite, :dateactivite, :iduser)";
                        $rActivite = $db->prepare($activite)->execute($newActivite);
                   
                        $pass_err= "validé";
                    } else{
                        $pass_err= "ERROR";
                    }

                }else{
                    $pass_err = "vos nouveau mot de passe sont differents!!";
                }

                

            }else{$pass_err = "mot de passe incorrect!!". $motpass['motDePasse']; }


        

       
    }else{
        $pass_err = "remplissez les champs!!"; }
    

}

  
// traitement de la photo de profil
if(isset($_POST['valider_avatar'])){

if (isset($_FILES['avatar']) AND !empty($_FILES['avatar']['name'] ) ) {
    $taillemax = 2097152;
        $extensionsvalides = array('jpg', 'jpeg', 'gif','png');
  
        if ($_FILES['avatar']['size']<= $taillemax) {
  
          $extensionupload= strtolower(substr(strrchr($_FILES['avatar']['name'], '.'), 1));
          if (in_array($extensionupload , $extensionsvalides)) {
              $chemin="avatar/".$_SESSION['id'].".".$extensionupload;
              $resultat = move_uploaded_file($_FILES['avatar']['tmp_name'], $chemin);
  
              if ($resultat) {
                $updateavatar = $db->prepare('UPDATE utilisateurs SET image = :avatar WHERE id = :id');
                  $updateavatar->execute( array('avatar'=> $_SESSION['id'].".".$extensionupload,
                  'id' => $_SESSION['id']));

                  $newActivite = [
                    ':activite'     => 'ModifiCATION DE LA PHOTO DE PROFIL',
                    ':dateactivite' => date("Y-m-d H:i:s"),
                    ':iduser'       => $_SESSION['id']
                ];
                //ajout d'activité
                $activite = "INSERT  INTO activites (intituleActivite, periode, idUtilisateur) VALUES ( :activite, :dateactivite, :iduser)";
                $rActivite = $db->prepare($activite)->execute($newActivite);
              
  
              }
        }
  
    }
  }

}

$getid = $_SESSION['id'];
 $requser = $db->prepare('SELECT image FROM utilisateurs WHERE id = ?');
 $requser ->execute(array($getid));
 $userphoto = $requser->fetch();



?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <?php require_once "../include/head.php" ?>
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
                   &nbsp;&nbsp;&nbsp; Bad    <b style="color: #ff1300;">  Event</b>
                </div>
              </span>
            <hr class="sidebar-divider my-0">

            <li class="nav-item active">
                <a class="nav-link" href="home.php">
                    <i class="fa fa-home" aria-hidden="true"></i>
                    <span>Accueil</span></a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="ajouter_agent.php">
                    <i class="fa fa-user-plus" aria-hidden="true"></i>
                    <span>Ajouter un agent</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="liste_agent.php">
                    <i class="fa fa-users" aria-hidden="true"></i>
                    <span>Liste des agents</span></a>
            </li>
          
        </ul>
        <!-- / Sidebar -->

        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Main Content -->
            <div id="content">
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow" style="background: #ff1300 !important;font-weight: 700;">
                    <strong id="sidebarToggleTop" class="d-md-none" style="color: #fff !important;font-weight: 900;">
                      Danger <span style="color:#000">View</span>
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
                        <!-- profil-->

                        <div class="container-fluid">
                    <div class="row">
                        <!-- fil d'actualité -->
                        <div class="col-md-4 col-lg-4 mb-4 mt-5"> 
                            <!-- Card -->
                            <div class="card profile-card">

                                <!-- Avatar -->
                                <div class="mb-4" id="operat">
                                    <center>
                                         <?php if (!empty($userphoto['image'])){?>
                                            <img src="avatar/<?php echo $userphoto['image'] ;?> " class="rounded-circle" width="100%" height="100%"/>
                                            <?php }else{?>
                                                <img src="avatar/user.png" class="rounded-circle" alt="First sample avatar image">
                                                <?php } ?>
                                     
                              
                                        
                                    </center>
                                </div>

                                <div class="card-body pt-0 mt-0 text-center">
                                   
                                    <!-- Name -->
                                    <h3 class="mb-3 font-weight-bold">
                                        <strong> <?=$_SESSION["nom"]; ?>  <?=$_SESSION["prenom"]; ?></strong>
                                    </h3>
                                    <h6 class="font-weight-normal cyan-text">
                                    <?= ucfirst(strtolower(str_replace("_", " ",$_SESSION["role"]))); ?>
                                    </h6>
                                    <h6 class="font-weight-normal cyan-text">
                                        <i class="fa fa-map-marker" aria-hidden="true"></i>
                                        <?= $_SESSION["adresse"]; ?>
                                    </h6>
                                    <h6 class="font-weight-normal cyan-text">
                                        <i class="fa fa-phone" aria-hidden="true"></i>
                                        <?= $_SESSION["contact"]; ?>
                                    </h6>
                                    <h6 class="font-weight-normal cyan-text mb-4">
                                        <i class="fa fa-envelope" aria-hidden="true"></i>
                                        <?= $_SESSION["email"]; ?>
                                        
                                    </h6>
                                </div>

                               
                                
                            </div>
                            <form action="" enctype="multipart/form-data" method="post">
                            <div class="container mt-2 card profile-card  ">
                           
                              <label for="" class='text-center'>MODIFIER LA PHOTO</label>
                                <div class="custom-file  mt-1">
                                            
                                            <input type="file" class=" custom-file-input"  name="avatar" value="avatar">
                                            <label class="custom-file-label " for="image">Choisir fichier</label>
                                            <div class="invalid-feedback"> </div>
                                </div>
                               <input type="submit" class="col-md-4 ml-auto mr-auto btn btn-warning p-0 mt-2 "  name = 'valider_avatar' value='valider'>
                              
                            </div>
                            </form>
                            <!-- Card -->
                        </div>
                      
                        <div class="row container-fluid col-md-8 col-lg-8 mb-4" >
                            <!-- Card -->
                            <div class="card card-cascade cascading-admin-card user-card">
                                <h6 class="h4 card-header font-weight-normal" style="background: #a19e9e !important">
                                    <i class="fa fa-pencil-square-o" style="color: #ffc500 !important" aria-hidden="true"></i>
                                    Editer vos informations
                                </h6>                          
                                <!-- Card content -->
                                <div class="card-body card-body-cascade">
                                    <form method="post" class="user" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                                        <div class="form-group row">
                                            <div class="col-sm-6 mb-3 mb-sm-0">
                                                <input type="text" class="form-control form-control-user" id="nom" name="nom" placeholder="Nom" value="">
                                                <small style="color: #ff1300 !important">
                                                    <span class="align-items-center text-center"> 
                                                        <center>
                                                        <i></i>
                                                        </center>
                                                    </span>
                                                </small>
                                            </div>
                                            <div class="col-sm-6">
                                                <input type="text" class="form-control form-control-user" id="prenom" name="prenom" placeholder="Prénom" value="">
                                                <small style="color: #ff1300 !important">
                                                    <span class="align-items-center text-center">
                                                        <center>
                                                        <i></i>
                                                        </center>
                                                    </span>
                                                </small>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-sm-4 mb-3 mb-sm-0">
                                                <input type="text" class="form-control form-control-user" id="genre" name="genre" placeholder="Genre" value="">
                                                <small style="color: #ff1300 !important">
                                                    <span class="align-items-center text-center">
                                                        <center>
                                                        <i></i>
                                                        </center>
                                                    </span>
                                                </small>
                                            </div>
                                            <div class="col-sm-4 mb-3 mb-sm-0">
                                                <input type="text" class="form-control form-control-user" id="adresse" name="adresse" placeholder="Lieu de résidence" value="">
                                                <small style="color: #ff1300 !important">
                                                    <span class="align-items-center text-center">
                                                        <center>
                                                        <i></i>
                                                        </center>
                                                    </span>
                                                </small> 
                                            </div>
                                            <div class="col-sm-4">
                                                <input type="tel" class="form-control form-control-user" id="contact" name="contact" placeholder="Numéro Téléphone" value="">
                                                <small style="color: #ff1300 !important">
                                                    <span class="align-items-center text-center">
                                                        <center>
                                                        <i></i>
                                                        </center>
                                                    </span>
                                                </small>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 mb-5 mt-2 mb-sm-0 conatainer-fluid">
                                            <input type="email" class="form-control form-control-user" id="email" name="email" placeholder="Adresse Email" value="">
                                            <small style="color: #ff1300 !important">
                                                <span class="align-items-center text-center">
                                                    <center>
                                                    <i></i>
                                                    </center>
                                                </span>
                                            </small>
                                        </div>
                                        <div class="row mt-3 ">
                                                <div class="col-md-3 ">
                                                <input type="hidden" name="id" value="">
                                                 <input type="submit" class="btn btn-primary btn-user btn-block p-1 " style="margin-left:8px; background: #ffc500!important; color:#fff;" name="update" value="Mettre à jour">
                                                </div>
                                                <div class="col-md-3 "></div>
                                                <div class="col-md-6 ">
                                                <small style="color: #ff1300 !important">
                                                <span class="align-items-center text-center">
                                                    <center>
                                                    <i><?php echo $errorMsg; ?></i>
                                                    </center>
                                                </span>
                                            </small>
                                            </div>
                                             
                                            </div>
                                        
                                    </form>   

                                    <form method="post" class="user" action="">
                                    
                                    <div class="mt-3">
                                                 <h6 style="color: #ffc500">changer son mot de passe</h6>
                                            </div>
                                            <hr>
                                           
                                        <div class="form-group mt-4 row">
                                            <div class="col-sm-4 mb-3 mb-sm-0">
                                                <input type="password" class="form-control form-control-user" id="lastPassword" name="lastPassword" placeholder="Ancien mot de passe">
                                                <small style="color: #ff1300 !important">
                                                    <span class="align-items-center text-center">
                                                        <center>
                                                        <i></i>
                                                        </center>
                                                    </span>
                                                </small>
                                            </div>
                                            <div class="col-sm-4 mb-3 mb-sm-0">
                                                <input type="password" class="form-control form-control-user" id="password" name="password" placeholder="Nouveau mot de passe">
                                                <small style="color: #ff1300 !important">
                                                    <span class="align-items-center text-center">
                                                        <center>
                                                        <i></i>
                                                        </center>
                                                    </span>
                                                </small> 
                                            </div>
                                            
                                            <div class="col-sm-4">
                                                <input type="password" class="form-control form-control-user" id="confPassword" name="confPassword" placeholder="Confirmez le mot de passe">
                                                <small style="color: #ff1300 !important">
                                                    <span class="align-items-center text-center">
                                                        <center>
                                                        <i></i>
                                                        </center>
                                                    </span>
                                                </small>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="row">
                                                <div class="col-md-3">
                                                <input type="hidden" name="id" value="">
                                                    <input type="submit" class="btn btn-primary btn-user btn-block  p-1" style="margin-left:5px; background: #ffc500!important; color:#fff;" name="update_pass" value="Modifier">
                                                </div>
                                                <div class="col-md-3">
                                                </div>
                                                <div class="col-md-6">
                                                <small style="color: #ff1300 !important">
                                                    <span class="align-items-center text-center">
                                                        <center>
                                                        <i><?php echo $pass_err; ?></i>
                                                        </center>
                                                    </span>
                                                </small> 
                                                </div>
                                            </div>
                                        </div>
                                    
                                    </form>
                                </div>
                                <!-- Card content -->

                            </div>
                            <!-- Card -->
                        </div>
                        <!-- / Activités -->
                    </div>
                </div>
                          
                        <!-- fin profil -->
                </div>
                <!-- /Contenue de la page -->

            </div>
            <!-- End of Main Content -->
            <!-- Footer -->
            <footer class="sticky-footer bg-white mb-0">
                <div class="container">
                    <div class="copyright text-center">
                        <span>Copyright &copy; 2020, design by Simplon</span>
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