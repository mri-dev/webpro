<div class="ref-list">
  <div class="wrapper">
    <div class="page-width">
      <?php while ( $ref->have_posts() ) { $ref->the_post(); ?>
        <?php
          $post = get_post(get_the_ID());
          $img = get_the_post_thumbnail_url($post);

          if (empty($img)) {
            $img = IMG . '/ref_logo_noimg.jpg';
          }
        ?>
        <a name="<?php echo $post->post_name; ?>"></a>
        <div class="referencia-item">
          <h1><?php echo the_title(); ?></h1>
        </div>
      <?php } wp_reset_postdata(); ?>
    </div>
  </div>
</div>
