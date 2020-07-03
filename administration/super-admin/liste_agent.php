<?php
// Initialize the session
session_start();
$iduser =$_SESSION['id'];

// Vérifions si l'utilisateur est connecté, sinon redirigeons-le vers la page de connexion
if(!isset($_SESSION["connecter"]) || $_SESSION["connecter"] !== true){
    header("location: ../index.php");
    exit;
}
require_once "./data/db.php";
$getid = $_SESSION['id'];
 $requser = $db->prepare('SELECT image FROM utilisateurs WHERE id = ?');
 $requser ->execute(array($getid));
 $userphoto = $requser->fetch();
?>
    <!DOCTYPE html>
    <html lang="fr">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>Danger View - Admin | Ajouter</title>
        <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.18/css/dataTables.bootstrap4.min.css">
        <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <script src="../include/jquery-3.5.1.min.js"></script>
        <script src='../include/bootbox.min.js' type='text/javascript'></script>
        <script src='../include/delete-jquery.js' type='text/javascript'></script>
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
                    <a class="nav-link" href="ajouter_agent.php">
                        <i class="fa fa-user-plus" aria-hidden="true"></i>
                        <span>Ajouter un Agent</span>
                    </a>
                </li>

                <li class="nav-item active">
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
                        <div class="card mb-4">
                            <div class="h4 card-header font-weight-normal" style="background: #a19e9e !important">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <h4 class="mt-2 text-white">Tous les agents</h4>
                                    </div>
                                    <div class="col-lg-6">
                                        <a href="ajouter_agent.php">
                                        <button type="button" class="btn btn-warning m-1 float-right" style="background: #ffc500!important; color:#fff;">
                                            <i class="fa fa-user-plus fa-lg"></i>
                                            &nbsp;&nbsp; Ajouter
                                        </button>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            
                            <?php
                                // On détermine sur quelle page on se trouve
                                if(isset($_GET['page']) && !empty($_GET['page'])){
                                    $currentPage = (int) strip_tags($_GET['page']);
                                }else{
                                    $currentPage = 1;
                                }
                                // On se connecte à là base de données
                                require_once './data/db.php';

                                // On détermine le nombre total d'operateurs
                                $sql = "SELECT COUNT(*) AS nb_utilisateurs FROM utilisateurs WHERE idRole =2";
                                // On prépare la requête
                                $query = $db->prepare($sql);
                                // On exécute
                                $query->execute();
                                // On récupère le nombre d'operateurs
                                $result = $query->fetch();
                                $nbDangers = (int) $result['nb_utilisateurs'];
                                // On détermine le nombre d'operateurs par page
                                $parPage = 6;
                                // On calcule le nombre de pages total
                                $pages = ceil($nbDangers / $parPage);
                                // Calcul du premier operateur de la page
                                $premier = ($currentPage * $parPage) - $parPage;

                               
                                $sql = "SELECT utilisateurs.id,utilisateurs.nom,utilisateurs.prenom,utilisateurs.contact,utilisateurs.email,utilisateurs.adresse,utilisateurs.idRole FROM utilisateurs LEFT JOIN roles ON roles.id=utilisateurs.idRole WHERE idparent =$iduser ORDER BY dateInscription DESC LIMIT :premier, :parpage";
                                
                                // On prépare la requête
                                $query = $db->prepare($sql);

                                $query->bindValue(':premier', $premier, PDO::PARAM_INT);
                                $query->bindValue(':parpage', $parPage, PDO::PARAM_INT);

                                // On exécute
                                $query->execute();
                                // On récupère les valeurs dans un tableau associatif
                                $operateurs = $query->fetchAll(PDO::FETCH_ASSOC);
                                
                                $o_nb = count($operateurs);
                                
                                //on suppose qu'il n'y a pas suffisamment de donnée pour afficher une pagination
                                $afficherPagination = FALSE;

                                //si le nombre de page est superieur à 0 alors on affiche la pagination
                                //si non, pas de pagination
                                if($pages > 0){
                                    $afficherPagination = TRUE;
                                }else{
                                    $afficherPagination = FALSE;
                                }

                                //var_dump($d_nb);exit();
                            ?>
                                <div class="card-body">
                                    <?php if($o_nb === 0): ?>
                                    <center>
                                        <strong class="mt-4 mb-4">
                                            Pas d'informations disponible
                                        </strong>
                                    </center>
                                    <?php else: ?>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="table-responsive">
                                                <table id="user_data" class="table table-striped table-sm table-bordered" style="color: #fff;">
                                                    <thead>
                                                        <tr class="text-center">
                                                            <th>Id</th>
                                                            <th>Nom</th>
                                                            <th>Prénom</th>
                                                            <th>Email</th>
                                                            <th>Téléphone</th>
                                                            <th>Role</th>
                                                            <th>Actions</th>
                                                           
                                                        </tr>
                                                    </thead>
                                                    <tbody>

                                                        <tbody class="text-center text-secondary">
                                                            <?php  foreach($operateurs as $operateur): ?>
                                                            <tr>
                                                                <td>
                                                                    <?= $operateur['id']; ?>
                                                                </td>
                                                                <td>
                                                                    <?= $operateur["nom"]; ?>
                                                                </td>
                                                                <td>
                                                                    <?=  $operateur["prenom"]; ?>
                                                                </td>
                                                                <td>
                                                                    <?=  $operateur["email"]; ?>
                                                                </td>
                                                                <td>
                                                                    <?=  $operateur["contact"]; ?>
                                                                </td>
                                                                <td>
                                                                
                                                                <?php
                                                             
                                                                $req = $db->prepare('SELECT description FROM roles WHERE id = ?');
                                                                $req ->execute(array($operateur["idRole"]));
                                                                $roles_user = $req->fetch();
                                                                ?>
                                                                   <?= $roles_user["description"]; ?>
                                                                </td>
                                                                <td>
                                                                    <a href="mettre-agent-jour.php?id=<?php echo htmlentities($operateur["id"]); ?>" type="button" class="text-primary">
                                                                        <i class="fa fa-edit fa-lg"></i>
                                                                    </a>&nbsp;&nbsp;
                                                                    <a  class="delete text-danger" id='del_<?= $operateur["id"] ?>' data-id='<?= $operateur["id"] ?>'>
                                                                        <i class="fa fa-trash fa-lg"></i>
                                                                    </a>&nbsp;&nbsp;
                                                                </td>
                                                            </tr>
                                                            <?php endforeach; ?>
                                                        </tbody>
                                                </table>

                                                <?php
                                                    // s'il y a pagination, alors on affichera notre nav
                                                    if($afficherPagination = TRUE):
                                                ?>
                                                <nav aria-label="pagination" >
                                                    
                                                    <ul class="pagination justify-content-center">

                                                        <li class="page-item <?= ($currentPage == 1) ? "disabled" : "text-danger" ?>">
                                                            <a class="page-link" href="?page=<?= $currentPage - 1 ?>" aria-label="Previous">
                                                            <span aria-hidden="true">&laquo;</span>
                                                            <span class="sr-only">Previous</span>
                                                            </a>
                                                        </li>

                                                        <?php for($page = 1; $page <= $pages; $page++): ?>
                                                            <li class="page-item <?= ($currentPage == $page) ? "active text-danger" : "" ?>">
                                                                <a class="page-link" href="?page=<?= $page ?>">
                                                                    <?= $page ?>
                                                                </a>
                                                            </li>
                                                        <?php endfor; ?>

                                                        <li class="page-item <?= ($currentPage == $pages) ? "disabled" : "text-danger" ?>">
                                                            <a class="page-link" href="?page=<?= $currentPage + 1 ?>" aria-label="Next">
                                                            <span aria-hidden="true">&raquo;</span>
                                                            <span class="sr-only">Next</span>
                                                            </a>
                                                        </li>

                                                    </ul>
                                                </nav>
                                                <?php endif ?>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endif ?>
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
                            <span>Copyright &copy; 2020, design by Simplon</span>
                        </div>
                    </div>
                </footer>
                <!-- End of Footer -->

            </div>
            <!-- End of Content Wrapper -->

        </div>
        <!-- ./ Wrapper -->

        
        <!-- jQuery first, then Popper.js, then Bootstrap JS -->
      
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js " integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo " crossorigin="anonymous "></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js " integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6 " crossorigin="anonymous "></script>
                                                        
        <script type="text/javascript ">
            ! function(t) {
                "use strict ";
                t("#sidebarToggle, #sidebarToggleTop ").on("click ", function(o) {
                        t("body ").toggleClass("sidebar-toggled "),
                            t(".sidebar ").toggleClass("toggled "),
                            t(".sidebar ").hasClass("toggled ") &&
                            t(".sidebar .collapse ").collapse("hide ")
                    }),
                    t(window).resize(function() {
                        t(window).width() < 768 &&
                            t(".sidebar .collapse ").collapse("hide ")
                    }),
                    t("body.fixed-nav .sidebar ").on("mousewheel DOMMouseScroll wheel ", function(o) {
                        if (768 < t(window).width()) {
                            var e = o.originalEvent,
                                l = e.wheelDelta || -e.detail;
                            this.scrollTop += 30 * (l < 0 ? 1 : -1), o.preventDefault()
                        }
                    })
            }(jQuery);
        </script>
    </body>

    </html>