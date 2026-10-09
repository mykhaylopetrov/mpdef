        </main>

        <footer class="footer">
            <div class="footer__container">
                <p>&copy; 2025-<span class="current-year"><?php echo do_shortcode( '[mpdef_current_year_shortcode]' ); ?></span> <?php bloginfo( 'name' ); ?>. All rights reserved.</p>
            </div>
        </footer>

        <?php get_template_part( 'template-parts/footer/scroll-to-top-button' ); ?>

    </div><!-- /.wrapper -->

    <?php wp_footer(); ?>
    
</body>
</html>
