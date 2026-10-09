<section class="hero" id="hero">
    <div class="hero__container">
        <div class="hero__body">
            <div class="hero__title section-title">
                <?php
                // Якщо це сторінка (але не Головна), виводимо заголовок сторінки, інакше виводимо заголовок "Hero Section" 
                if ( is_page() && ( ! is_front_page() ) ) {
                    the_title( '<h1>', '</h1>' );
                } elseif ( is_single() /* || is_singular( 'CPT_name' ) */ ) {
                    the_title( '<h1>', '</h1>' );
                } else {    
                ?>
                    <h1>Hero Section</h1>
                <?php }?>
            </div>
            <div class="hero__content">
                <p>Hero Content</p>
            </div>
            <div class="hero__btn">
                <button class="btn btn--join-us" type="button">Join us</button>
            </div>
        </div>
    </div>
    <div class="hero__background">
        <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/main/hero/hero-bg.jpg' ); ?>" alt="Hero Section">
    </div>
</section>