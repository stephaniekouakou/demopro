<!-- Sidebar -->
 
<?php session_start(); ?>
<ul class="navbar-nav sidebar sidebar-dark accordion" id="accordionSidebar" style="background: #ff1300 !important;">
            <span class="sidebar-brand d-flex align-items-center justify-content-center">
                <div class="sidebar-brand-icon" style="cursor: pointer;">
                    <i class="fa fa-list" aria-hidden="true" id="sidebarToggle"></i>
                  </div>
                <div class="sidebar-brand-text">
                   &nbsp;&nbsp;&nbsp; BAD-<b style="color: #000;">EVENT</b>
                </div>
              </span>
            <li class="nav-item active"><hr class="sidebar-divider my-0">
            <?php if($pageName === "Accueil"): ?>
                <?php echo '<li class="nav-item active">'; ?>
            <?php else: ?>
                <?php echo '<li class="nav-item">'; ?>
            <?php endif ?>
                <a class="nav-link" href="./home.php">
                    <i class="fa fa-home" aria-hidden="true"></i>
                    <span>Accueil</span></a>
            </li>
            
            
            <?php if($pageName === "Messages"): ?>
                <?php echo '<li class="nav-item active">'; ?>
            <?php else: ?>
                <?php echo '<li class="nav-item">'; ?>
            <?php endif ?>
                <a class="nav-link" href="./messages.php">
                    <i class="fa fa-comments" aria-hidden="true"></i>
                    <span>Voir les messages</span>
                </a>
            </li>
            
            

        </ul>
        <!-- / Sidebar -->