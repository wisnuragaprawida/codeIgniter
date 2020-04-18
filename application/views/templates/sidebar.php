<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-american-sign-language-interpreting"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Smart Aviliate</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider ">

    <!-- query menu join 2 tabel -->

    <?php
    $roleid = $this->session->userdata('role_id');
    $querymenu = "SELECT `user_menu`.`id` , `menu`
                    FROM `user_menu` JOIN `user_access`
                    ON `user_menu`. `id` = `user_access`. `menu_id`
                    WHERE `user_access`. `role_id` = $roleid 
                    ORDER BY `user_access`. `menu_id` ASC
                    ";
    $menu = $this->db->query($querymenu)->result_array();

    ?>
    <!-- looping menu -->
    <?php foreach ($menu as $m) : ?>
        <div class="sidebar-heading">
            <?= $m['menu'] ?>
        </div>

        <!-- query submenu buat menu -->
        <?php
        $menuid = $m['id'];
        $querysubmenu = "SELECT * FROM `user_sub_menu`
        WHERE `menu_id` = $menuid
        AND `is_active` = 1
         ";
        $submenu = $this->db->query($querysubmenu)->result_array();
        ?>

        <?php foreach ($submenu as $sm) : ?>

            <!-- sub menu  -->
            <li class="nav-item">
                <a class="nav-link" href="<?= base_url($sm['url']) ?>">
                    <i class="<?= $sm['icon'] ?>"></i>
                    <span><?= $sm['title'] ?></span></a>
            </li>

        <?php endforeach; ?>
        <hr class="sidebar-divider">

    <?php endforeach; ?>


    <!-- Divider -->

    <li class="nav-item">
        <a class="nav-link" href="<?= base_url('auth/logout/'); ?>" data-toggle="modal" data-target="#logoutModal">
            <i class="fas fa-fw fa-sign-out-alt"></i>
            <span>Logout</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
<!-- End of Sidebar -->