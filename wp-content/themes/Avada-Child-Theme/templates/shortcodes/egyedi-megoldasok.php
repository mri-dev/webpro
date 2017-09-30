<div class="heading">
  <div class="page-width">
    <h2>Egyedi megoldásaink webáruház rendszereinkben</h2>
  </div>
</div>
<div class="items">
  <div class="page-width">
    <div class="flxtbl">
      <?php if($data->have_posts()): while($data->have_posts()): $data->the_post();?>
      <div class="item">
        <div class="wrapper">
          <a href="<?php echo get_permalink(); ?>">
            <div class="title">
              <?php echo get_the_title(); ?>
            </div>
            <div class="image">
              <img src="<?=IMG?>/<?php echo get_post_field( 'post_name', get_the_ID() ); ?>.svg" alt="<?php echo get_the_title(); ?>">
            </div>
          </a>
        </div>
      </div>
    <?php endwhile; wp_reset_postdata(); endif; ?>
    </div>
  </div>
</div>
