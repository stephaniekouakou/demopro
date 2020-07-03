<?php
  $pageName = "Messages";
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
                    <div class="col-md-12 col-lg-12">
                            <div class="card mb-4 chat-room">
                                <h6 class="h4 card-header font-weight-normal" style="background: #a19e9e !important">
                                    Messages des visiteurs
                                    <button type="submit" class="btn btn-danger btn-sm float-right">12</button>
                                </h6>
                                
                                <div class="card-body">
                                   <div class="row">
                                        <div class="col-md-4 mb-4" style="background: # !important">
                                            <div class="white z-depth-1 members-panel-1 scrollbar-light-blue">
                                                <ul class="list-unstyled">
                                                    <?php for($i=1; $i<12; $i++){ ?>
                                                    <li class="active grey p-3">
                                                        <a href="#" class="d-flex justify-content-between">
                                                            <img src="../upload/Bad-event_logo.png" alt="avatar" class="avatar rounded-circle d-flex align-self-center mr-2 z-depth-1">
                                                            <div class="text-small">
                                                            <strong style="color: #ffc500">Nom du visiteur</strong>
                                                            <p class="last-message text-muted" style="color: #fff !important">Hello, Are you there?</p>
                                                            <p class="text-smaller text-muted mb-0" style="color: #fff !important">27/06/2020</p>
                                                            </div>
                                                        </a>
                                                    </li>
                                                    <hr class="w-100">
                                                    <?php } ?>
                                                </ul>
                                            </div>
                                        </div>
                                      
                                        <div class="col-md-8">
                                            <div class="chat-message">
                                                <ul class="list-unstyled chat">
                                                    <li class="d-flex justify-content-between mb-4 pb-3">
                                                    <img src="../upload/Bad-event_logo.png" alt="avatar" class="avatar rounded-circle z-depth-1">
                                                    <div class="chat-body white p-3 ml-2 z-depth-1">
                                                        <div class="header">
                                                        <strong class="primary-font">Nom du visiteur</strong>
                                                        <small class="pull-right text-muted"><i class="far fa-clock"></i> 12 mins ago</small>
                                                        </div>
                                                        <hr class="w-100">
                                                        <p class="mb-0">
                                                        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut
                                                        labore et dolore magna aliqua.
                                                        </p>
                                                    </div>
                                                    </li>
                                                    <li class="d-flex justify-content-between mb-4">
                                                        <div class="chat-body white p-3 z-depth-1">
                                                            <div class="header">
                                                            <strong class="primary-font">Nom du superviseur</strong>
                                                            <small class="pull-right text-muted"><i class="far fa-clock"></i> 13 mins ago</small>
                                                            </div>
                                                            <hr class="w-100">
                                                            <p class="mb-0">
                                                            Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque
                                                            laudantium.
                                                            </p>
                                                        </div>
                                                        <img src="https://mdbootstrap.com/img/Photos/Avatars/avatar-5.jpg" alt="avatar" class="avatar rounded-circle mr-0 ml-3 z-depth-1">
                                                    </li>
                                                    <li class="white">
                                                        <div class="form-group basic-textarea">
                                                            <textarea class="form-control pl-2 my-0" id="exampleFormControlTextarea2" rows="3" style="resize: none"  placeholder="Saissisez votre message ici..."></textarea>
                                                        </div>
                                                    </li>
                                                    <button type="submit" class="btn btn-danger btn-sm waves-effect waves-light float-right">Envoyer</button>
                                                </ul>
                                            </div>
                                        </div>
                                   </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- / fil d'actualité -->
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