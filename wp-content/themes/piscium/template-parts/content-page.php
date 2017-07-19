</div>
</div>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <header class="entry-header pageHeader">
        <?php
        if (!is_front_page()) {
            ?>
            <div class="content-fluid text-center">
                <?php
                the_title('<h1 class="entry-title">', '</h1>');
                echo '<p>';
                echo get_post_meta(get_the_ID(), 'Subtitulo', true);
                echo '</p>';
                ?>
            </div>
            <?php
        }
        ?>
    </header><!-- .entry-header -->
    <div class="container">

        <div class="entry-content content-page">
            <?php
            the_content();

            wp_link_pages(array(
                'before' => '<div class="page-links">' . esc_html__('Pages:', 'piscium'),
                'after' => '</div>',
            ));
            ?>
        </div><!-- .entry-content -->

        <?php if (get_edit_post_link()) : ?>
            <div class="entry-footer">
                <?php
                edit_post_link(
                        sprintf(
                                wp_kses(
                                        /* translators: %s: Name of current post. Only visible to screen readers */
                                        __('Edit <span class="screen-reader-text">%s</span>', 'piscium'), array(
                    'span' => array(
                        'class' => array(),
                    ),
                                        )
                                ), get_the_title()
                        ), '<span class="edit-link">', '</span>'
                );
                ?>
            </div><!-- .entry-footer -->
        <?php endif; ?>
</article><!-- #post-<?php the_ID(); ?> -->
