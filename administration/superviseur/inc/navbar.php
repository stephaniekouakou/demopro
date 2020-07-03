<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow" style="background: #000 !important;font-weight: 700;">
                    <strong id="sidebarToggleTop" class="d-md-none" style="color: #fff !important;font-weight: 900;">
                      BAD-<span style="color:#ff1300">EVENT</span>
                    </strong>
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item dropdown no-arrow mx-1">
                            <a class="nav-link dropdown-toggle" href="#" id="messagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-envelope fa-fw" style="color: #fff !important;font-weight: bold;"></i>
                                <span class="d-none d-lg-inline text-gray-600 small" style="color: #fff !important;font-weight: 500;">Notifications</span>
                            </a>
                            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="messagesDropdown">
                                <h6 class="dropdown-header">
                                    Messages
                                </h6>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="dropdown-list-image mr-3">
                                        <img class="rounded-circle" src="../img/logo.png" alt="">
                                    </div>
                                    <div class="font-weight-bold">
                                        <div class="text-truncate">Bonjour</div>
                                        <div class="small text-gray-500">Nom Visiteur · 58m</div>
                                    </div>
                                </a>
                                <a class="dropdown-item text-center small text-gray-500" href="./messages.php">Lire plus de messages</a>
                            </div>
                        </li>

                        <div class="topbar-divider d-none d-sm-block" style="border-right: 2px solid #ff1300 !important"></div>

                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <img class="img-profile rounded-circle" src="https://mdbootstrap.com/img/Photos/Avatars/img%20(10).jpg" alt="photo">&nbsp;&nbsp;
                                <span class="d-none d-lg-inline text-gray-600 small" style="color: #fff !important;font-weight: 800;">
                                    <?= $_SESSION["prenom"]; ?>
                                </span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="./profil.php">
                                    <i class="fa fa-user fa-sm fa-fw mr-2"></i> Mon Profil
                                </a>
                                <a class="dropdown-item" href="../logout.php">
                                    <i class="fa fa-sign-out fa-sm fa-fw mr-2"></i> Déconnexion
                                </a>
                            </div>
                        </li>
                    </ul>
                </nav>