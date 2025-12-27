<header class="header header-transparent default shadow-sm">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <nav class="navbar navbar-static-top navbar-expand-lg header-sticky justify-content-between">
          <a class="navbar-brand" href="/"><img class="lazyload logo" src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" data-src="<?=get_template_directory_uri()?>/assets/images/logo.svg" alt="<?php bloginfo('name'); ?>"></a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarmenu" aria-controls="navbarmenu" aria-expanded="false" aria-label="Toggle navigation">
          <i class="fas fa-align-left"></i>
          </button>
          <div class="navbar-collapse collapse" id="navbarmenu">
              <?php
                    wp_nav_menu(
                            array(
                                'menu' => 'Menu Główne',
                                'container' => false,
                                'items_wrap' => '<ul id="%1$s" class="nav navbar-nav">%3$s</ul>',
                                'link_class' => 'nav-link'
                            )
                    );
                    ?>
            
       
  </div>
  <div class="search">
    <ul class="pl-0">
      <li class="dropdown nav-item header-search">
        <a href="#search"> <i class="fa fa-search"></i></a>
      </li>
    </ul>
  </div>
</nav>
</div>
</div>
</div>
</header>
