<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3   bg-gradient-dark" id="sidenav-main">

    <div class="sidenav-header">
        <i class="fas fa-times p-3 cursor-pointer text-white opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
        <a class="navbar-brand m-0" href="./home" target="_blank">
            <img src="./assets/img/help_logo.PNG" style="width: 200px; height: 80px;" class="navbar-brand-img h-100" alt="main_logo">
            <span class="ms-1 font-weight-bold text-white"></span>
        </a>
    </div>
    <hr class="horizontal light mt-0 mb-2">
    <div class="collapse navbar-collapse  w-auto " id="sidenav-collapse-main">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link text-white " href="./home.php">
                    
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">dashboard</i>
                    </div>
                    
                    <span class="nav-link-text ms-1">Inicial</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white " href="./documentos.php">
                    
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">table_view</i>
                    </div>
                    
                    <span class="nav-link-text ms-1">Documentos</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white " href="./favoritos.php">
                    
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">receipt_long</i>
                    </div>
                    
                    <span class="nav-link-text ms-1">Favoritos</span>
                </a>
            </li>
            <?php if($_COOKIE["permissao"] == '1') : ?>
            <li class="nav-item">
                <a class="nav-link text-white " href="./usuarios.php">
                    
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">person</i>
                    </div>
                    
                    <span class="nav-link-text ms-1">Usuários</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white " href="./categorias.php">
                    
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">folder</i>
                    </div>
                    
                    <span class="nav-link-text ms-1">Categorias</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white " href="./niveis.php">
                    
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">school</i>
                    </div>
                    
                    <span class="nav-link-text ms-1">Níveis</span>
                </a>
            </li>
            <?php endif; ?>
        </ul>
        <div class="sidenav-footer position-absolute w-100 bottom-0 " style="margin-bottom: 10%;">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link text-white " href="./logoff.php">
                        
                        <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="material-icons opacity-10">login</i>
                        </div>
                        
                        <span class="nav-link-text ms-1">Logout</span>
                    </a>
                </li>
            </ul>
               
        </div>
    </div>
</aside>