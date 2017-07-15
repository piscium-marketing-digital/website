<!doctype html>
<html <?php language_attributes(); ?>>
    <head>
        <meta charset="<?php bloginfo('charset'); ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <?php wp_head(); ?>
    </head>

    <body <?php body_class(); ?>>
        <header id="mainHeader">
            <div class="container-fluid">
                <div class="col-xs-2">
                    <img src="<?= IMG_DIR_UPLOAD ?>/logo-piscium-branca.png"/>
                </div>
                <div class="col-xs-9">
                    <nav>
                        <?php
                        wp_nav_menu('header-menu');
                        ?>
                    </nav>
                </div>
                <div class="col-xs-1">
                    <i class="fa fa-search"></i>
                    <i class="fa fa-bars"></i>
                </div>
            </div>
            <hr/>
            <div  class="container">
                <div id="banner">
                    <div  class="col-xs-12">
                        <h1>Especialistas em criar<br/>projetos de sucesso!</h1>
                        <p>Todo o esforço dedicado em performânce e qualidade em nossos<br/>produtos aplicados à necessidade do seu negócio.</p>
                    </div>
                    <div class="col-xs-12">
                        <a href="#">Quem Somos?</a>
                        <a href="#">Soluções</a>
                    </div>
                </div>
            </div>
        </header>
        <main>
            <div class="container">