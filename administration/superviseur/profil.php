<?php
    $pageName = "Profil";
    $id_user = $_SESSION['id'];
    // Initialize the session
session_start();

// Vérifions si l'utilisateur est connecté, sinon redirigeons-le vers la page de connexion
if(!isset($_SESSION["connecter"]) || $_SESSION["connecter"] !== true){
    header("location: ../index.php");
    exit;
}
require_once "/data/db.php";
//requete pour selectionner les info de lutilisateur
$req_info=$db->prepare('SELECT * FROM utilisateurs WHERE id = :iduser  ');
$req_info->bindValue(':iduser', $id_user ,PDO::PARAN_INT);
//execution de la requete
$executeIsOk= $req_info->execute();
//on recupere les info 
$info  =$req_info->fetch();
var_dump($info);




?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <?php require_once "../include/head.php" ?>
</head>

<body id="page-top">
    <div id="wrapper">
        <!-- Sidebar -->
        <?php require_once "./inc/sidebar.php" ?>
        <!-- / Sidebar -->

        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Main Content -->
            <div id="content">
               <!-- Mon navbar -->
               <?php require_once "./inc/navbar.php" ?>
                <!-- Contenue de la page -->
                <div class="container-fluid">
                    <div class="row">
                        <!-- fil d'actualité -->
                        <div class="col-md-4 col-lg-4 mb-4 mt-5"> 
                            <!-- Card -->
                            <div class="card profile-card">

                                <!-- Avatar -->
                                <div class="mb-4" id="operat">
                                    <center>
                                        <img src="https://mdbootstrap.com/img/Photos/Avatars/img%20(10).jpg" class="rounded-circle" alt="First sample avatar image">
                                    </center>
                                </div>

                                <div class="card-body pt-0 mt-0 text-center">

                                    <!-- Name -->
                                    <h3 class="mb-3 font-weight-bold">
                                        <strong> <?= $_SESSION["nom"]; ?>  <?= $_SESSION["prenom"]; ?></strong>
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
                            <!-- Card -->
                        </div>
                      
                        <div class="col-md-8 col-lg-8 mb-4">
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
                                                <input type="text" class="form-control form-control-user" id="genre" name="genre" placeholder="Genre">
                                                <small style="color: #ff1300 !important">
                                                    <span class="align-items-center text-center">
                                                        <center>
                                                        <i></i>
                                                        </center>
                                                    </span>
                                                </small>
                                            </div>
                                            <div class="col-sm-4 mb-3 mb-sm-0">
                                                <input type="text" class="form-control form-control-user" id="adresse" name="adresse" placeholder="Lieu de résidence">
                                                <small style="color: #ff1300 !important">
                                                    <span class="align-items-center text-center">
                                                        <center>
                                                        <i></i>
                                                        </center>
                                                    </span>
                                                </small> 
                                            </div>
                                            <div class="col-sm-4">
                                                <input type="tel" class="form-control form-control-user" id="contact" name="contact" placeholder="Numéro Téléphone">
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
                                                    <input type="submit" class="btn btn-primary btn-user btn-block" style="background: #ffc500!important; color:#fff;" name="update" value="Mettre à jour">
                                                </div>
                                                <div class="col-md-6">
                                                </div>
                                                <div class="col-md-3"></div>
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
                <!-- /Contenue de la page -->
            </div>
            <!-- End of Main Content -->
            <!-- Footer -->
            <footer class="sticky-footer mb-0" style="background: #000 !important">
                <!-- Mon footer -->
                <?php require_once "../include/footer.php" ?>
            </footer>
            <!-- End of Footer -->
        </div>
        <!-- End of Content Wrapper -->
    </div>
    <!-- ./ Wrapper -->
    <?php require_once "../include/js-cdn.php" ?>
</body>
</html>