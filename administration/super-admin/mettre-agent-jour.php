<?php
// Initialize the session
session_start();



require_once "./data/db.php";

$getid = $_SESSION['id'];
 $requser = $db->prepare('SELECT image FROM utilisateurs WHERE id = ?');
 $requser ->execute(array($getid));
 $userphoto = $requser->fetch();


 $nom = $prenom = $genre = $adresse = $contact = "";
 $nom_err = $prenom_err = $genre_err = $adresse_err = $contact_err = ""; 
 $errorMsg = "";
 

if(isset($_POST['update']))
{

    $userid= isset($_POST["id"]) ? $_POST["id"] : '';

    //var_dump($userid);exit();
    $nom = trim($_POST["nom"]);
    $prenom = trim($_POST["prenom"]);
    $genre = trim($_POST["genre"]);
    $adresse = trim($_POST["adresse"]);
    $contact = trim($_POST["contact"]);

    $sql = "UPDATE utilisateurs SET nom=:nom,prenom=:prenom,genre=:genre,adresse=:adresse,contact=:contact WHERE id=:uid";
        
    $stmt = $db->prepare($sql);
    // Bind variables to the prepared statement as parameters
    $stmt->bindParam(":nom", $nom, PDO::PARAM_STR);
    $stmt->bindParam(":prenom", $prenom, PDO::PARAM_STR);
    $stmt->bindParam(":genre", $genre, PDO::PARAM_STR);
    $stmt->bindParam(":adresse", $adresse, PDO::PARAM_STR);
    $stmt->bindParam(":contact", $contact, PDO::PARAM_STR);
    $stmt->bindParam(":uid", $userid, PDO::PARAM_STR);

    if($stmt->execute()){
        $errorMsg = "success";
        $newActivite = [
            ':activite'     => 'modification des infos dun utilisateur',
            ':dateactivite' => date("Y-m-d H:i:s"),
            ':iduser'       => $_SESSION['id']
        ];
        //ajout d'activité
     
        $activite = "INSERT  INTO activites (intituleActivite, periode, idUtilisateur) VALUES ( :activite, :dateactivite, :iduser)";
      
        $rActivite = $db->prepare($activite)->execute($newActivite);
        header("location:liste_agent.php");
    }else{
        $errorMsg = "error";
    }

    
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Danger View - Admin | Mise à jour</title>
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <script src="../include/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
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
                   &nbsp;&nbsp;&nbsp; Danger <b style="color: #ff1300;">view</b>
                </div>
              </span>
            <hr class="sidebar-divider my-0">

            <li class="nav-item">
                <a class="nav-link" href="home.php">
                    <i class="fa fa-home" aria-hidden="true"></i>
                    <span>Accueil</span></a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="liste_agent.php">
                    <i class="fa fa-user-plus" aria-hidden="true"></i>
                    <span>Ajouter un agent </span>
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
                                <a class="dropdown-item" href="../php/logout.php">
                                    <i class="fa fa-sign-out fa-sm fa-fw mr-2"></i> Déconnexion
                                </a>
                            </div>
                        </li>
                    </ul>
                </nav>
                <!-- / Topbar -->

                <!-- Contenue de la page -->
                <div class="container-fluid">
                    <?php if($errorMsg != ""): ?>
                        <div class="alert alert-success" role="alert">
                            <?php echo $errorMsg; ?>
                        </div>
                    <?php endif ?>
                    <div class="card mb-4">
                        <h5 class=" h4 card-header" style="background: #a19e9e !important">
                            <center>Mettre à jour les informations d'un opérateur</center>
                        </h5>
                        <div class="card-body">
                        <div class="row">
          <div class="col-lg-12">
            <div class="p-1">
            <?php 
             $userid= intval($_GET["id"]);
            
             $query = "SELECT nom,prenom,genre,adresse,contact,id FROM utilisateurs WHERE id=:uid";
             $traitement = $db->prepare($query);
             $traitement->bindParam(':uid', $userid, PDO::PARAM_STR);
             $traitement->execute();
             $data = $traitement->fetchAll();
            ?>
             <?php if($traitement->rowCount() > 0): ?>
                <?php foreach($data as $result): ?>
              <form method="post" class="user" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class="">
                    <h5 style="color: #ffc500">Informations personnelle</h5>
                </div>
                <hr>
                <div class="form-group row <?php echo (!empty($nom_err) && !empty($prenom_err)) ? 'has-error' : ''; ?>">
                  <div class="col-sm-6 mb-3 mb-sm-0">
                    <input type="text" class="form-control form-control-user" id="nom" name="nom" placeholder="Nom" value="<?= $result['nom'] ?>">
                    <small style="color: #ff1300 !important">
                        <span class="align-items-center text-center">
                            <center>
                            <i><?php echo $nom_err; ?></i>
                            </center>
                        </span>
                    </small>
                  </div>
                  <div class="col-sm-6">
                    <input type="text" class="form-control form-control-user" id="prenom" name="prenom" placeholder="Prénom" value="<?= $result['prenom'] ?>">
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
                  <div class="col-sm-3 mb-3 mb-sm-0">
                    <input type="text" class="form-control form-control-user" id="genre" name="genre" placeholder="Genre" value="<?= $result['genre'] ?>">
                    <small style="color: #ff1300 !important">
                        <span class="align-items-center text-center">
                            <center>
                            <i><?php echo $genre_err; ?></i>
                            </center>
                        </span>
                    </small>
                  </div>
                  <div class="col-sm-4 mb-3 mb-sm-0">
                    <input type="text" class="form-control form-control-user" id="adresse" name="adresse" placeholder="Lieu de résidence" value="<?= $result['adresse'] ?>">
                    <small style="color: #ff1300 !important">
                        <span class="align-items-center text-center">
                            <center>
                            <i><?php echo $adresse_err; ?></i>
                            </center>
                        </span>
                    </small> 
                 </div>
                  <div class="col-sm-5">
                    <input type="tel" class="form-control form-control-user" id="contact" name="contact" placeholder="Numéro Téléphone" value="<?= $result['contact'] ?>">
                    <small style="color: #ff1300 !important">
                        <span class="align-items-center text-center">
                            <center>
                            <i><?php echo $contact_err; ?></i>
                            </center>
                        </span>
                    </small>
                  </div>
                </div>
                <div>
                    <div class="row">
                        <div class="col-md-3">
                        <input type="hidden" name="id" value="<?= $result['id'] ?>">
                            <input type="submit" class="btn btn-primary btn-user btn-block" style="background: #ffc500!important; color:#fff;" name="update" value="Mettre à jour">
                        </div>
                        <div class="col-md-6">
                        </div>
                        <div class="col-md-3"></div>
                    </div>
                </div>
              </form>
                <?php endforeach ?>
            <?php endif ?>
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
                        <span>Copyright &copy; 2020, design by Sheila Melissa</span>
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