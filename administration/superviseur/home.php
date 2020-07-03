<?php
    $pageName = "Accueil";
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
                        <div class="col-md-12 col-lg-12"> 
                            <div class="card mb-4">
                                <h6 class="h4 card-header font-weight-normal" style="background: #a19e9e !important">
                                    <center>Fil d'actualité</center>
                                </h6>
                                <div class="card-body">
                                    <div class="row">
                                        <div class=" card-text col-md-6 text-center">
                                            <p>
                                                Visiteurs <br> 0 <br> en ligne
                                            </p>
                                        </div>
                                        <div class=" card-text col-md-6 text-center">
                                            <p>
                                                Opérateurs <br> 0 <br> connecté
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- fil d'actualité -->
                    </div>
                    <div class="row">
                        <!-- Activités -->
                        <div class="col-md-12 col-lg-12">
                            <div class="card mb-4">
                                <h6 class="h4 card-header font-weight-normal" style="background: #a19e9e !important">
                                    <center>Activités</center>
                                </h6>
                                <div class="card-body">
                                    <div class="card-text">
                                        <p class="card-text">
                                                <!-- <i class="fa fa-times text-danger" aria-hidden="true"></i>&nbsp; -->
                                                <i>Aucune activité</i></p>
                                                <br>
                                                <br>
                                    </div>
                                </div>
                            </div>
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