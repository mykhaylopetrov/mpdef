<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">

    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    <?php wp_body_open(); ?>
    <div class="wrapper">
        <header class="header">
            <div class="header__container">
                <div class="header__logo">
                    <?php if ( has_custom_logo() ) { ?>
                        <?php the_custom_logo(); ?>
                    <?php } else { ?>
                        <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><img src="<?php echo esc_url( MPDEF_URL . '/assets/images/header/logo-white-50x50.png' ); ?>" alt="<?php bloginfo( 'name' ); ?>"></a>
                    <?php } ?>
                </div>

                <?php mpdef_render_menu(); ?>

                <div class="header-right">
                    <div class="header__socials socials">
                        <ul class="socials__list">
                            <li class="socials__item icon-btn">
                                <a href="#"><img src="<?php echo esc_url( MPDEF_URL . '/assets/images/header/search-icon-white-64x64.png' ); ?>" alt="Search icon"></a>
                            </li>
                            <li class="socials__item icon-btn">
                                <a href="tel:+380675552211"><img src="<?php echo esc_url( MPDEF_URL . '/assets/images/header/telephone-icon-white-64x64.png' ); ?>" alt="Telephone icon"></a>
                            </li>
                            <li class="socials__item icon-btn">
                                <a href="mailto:admin@site.com"><img src="<?php echo esc_url( MPDEF_URL . '/assets/images/header/email-icon-white-64x64.png' ); ?>" alt="Email icon"></a>
                            </li>
                            <li class="socials__item icon-btn">
                                <button class="btn btn--contact-us" type="button">Contact us</button>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="burger-menu">
                    <button type="button" class="burger-menu-btn" aria-label="Open menu">
                        <span class="burger-menu-btn__line"></span>
                    </button>
                </div>
            </div>
        </header>

        <main class="main site-main">
