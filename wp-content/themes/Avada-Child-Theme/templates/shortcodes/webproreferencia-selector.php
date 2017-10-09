<div class="ref-selector">
  <div class="wrapper">
    <div class="page-width">
      <div class="referenciak">
      <?php while ( $ref->have_posts() ) { $ref->the_post(); ?>
        <?php
          $post = get_post(get_the_ID());
          $img = get_the_post_thumbnail_url($post);

          if (empty($img)) {
            $img = IMG . '/ref_logo_noimg.jpg';
          }
        ?>
        <div class="referencia">
          <div class="img">
            <a href="#<?php echo $post->post_name; ?>"><img src="<?php echo $img; ?>" alt="<?php echo the_title(); ?>"></a>
          </div>
          <div class="title">
            <a href="#<?php echo $post->post_name; ?>"><?php echo the_title(); ?></a>
          </div>
        </div>
      <?php } wp_reset_postdata(); ?>
      </div>
    </div>
  </div>
</div>
