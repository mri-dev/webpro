<div class="ref-list">
  <div class="wrapper">
      <?php while ( $ref->have_posts() ) { $ref->the_post(); ?>
        <?php
          $post = get_post(get_the_ID());
          $img = get_the_post_thumbnail_url($post);

          if (empty($img)) {
            $img = IMG . '/ref_logo_noimg.jpg';
          }

          $website = get_post_meta($post->ID, 'websiteurl', true);
          $colorid = get_post_meta($post->ID, 'colorid', true);
          $coverimg = get_post_meta($post->ID, 'coverimg', true);
          $customshortcodes = get_post_meta($post->ID, 'include_customshortcodes', true);

          if (!empty($customshortcodes)) {
            $xcustomshortcodes = explode(",",$customshortcodes);
          } else {
            $xcustomshortcodes = array();
          }

          $keywords = array();
          if(get_post_meta($post->ID, 'keywords', true) != '') {
            $tempkeywords = explode(",", get_post_meta($post->ID, 'keywords', true));
            foreach ((array)$tempkeywords as $keys) {
              $keywords[] = trim($keys);
            }
          }

        ?>
        <?php if (!empty($colorid)): ?>
        <style media="screen">
          .webpro-referenciak-holder .ref-list .referencia-item.pg<?=$post->ID?> .informations .base-wire .info-wire .info .desc ul li:before{
            color: <?=$colorid?>;
          }
        </style>
        <?php endif; ?>
        <a name="<?php echo $post->post_name; ?>"></a>
        <div class="referencia-item pg<?=$post->ID?>">
          <div class="informations">
            <div class="page-width">
              <div class="flxtbl base-wire">
                <div class="info-block">
                  <div class="flxtbl info-wire">
                    <div class="profil">
                      <img src="<?php echo $img; ?>" alt="<?php echo the_title(); ?>">
                    </div>
                    <div class="info">
                      <h3><?php echo the_title(); ?></h3>
                      <?php if (!empty($website)): ?>
                      <div class="link">
                        <a style="background-color: <?=$colorid?>;" href="<?php echo $website; ?>" target="_blank"><?php echo $website; ?></a>
                      </div>
                      <?php endif; ?>
                      <div class="desc">
                        <div class="h">
                          Mit csináltunk mi?
                        </div>
                        <?php echo the_content(); ?>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="cover-block">
                  <img src="<?php echo $coverimg; ?>" alt="<?php echo the_title(); ?>">
                </div>
              </div>

              <?php if (!empty($keywords)): ?>
              <div class="keywords">
                <?php foreach ($keywords as $key): ?>
                  <div><?php echo $key; ?></div>
                <?php endforeach; ?>
              </div>
              <div class="fusion-clearfix clr"></div>
              <?php endif; ?>
            </div>
          </div>

          <div class="szalag" style="background: <?=$colorid?>;" >
            <div class="page-width">
              EGYEDI MEGOLDÁSOK WEBÁRUHÁZ RENDSZERÜNK SEGÍTSÉGÉVEL
            </div>
          </div>
          <?php if (!empty($xcustomshortcodes)): ?>
          <?php foreach ( (array)$xcustomshortcodes as $sc ): ?>
            <?php echo do_shortcode('[EgyediMegoldasok key="'.$sc.'"]'); ?>
          <?php endforeach; ?>
          <?php endif; ?>
          <?php
            $gallery_id = (int)get_post_meta($post->ID, 'galleryid', true);
          ?>
          <?php if ($gallery_id != 0): ?>
          <div class="galery">
            <div class="page-width">
              <?php echo photo_gallery($gallery_id); ?>
            </div>
          </div>
          <?php endif; ?>
          <div class="szalag contact-ad">
            <div class="page-width">
              <a href="/kapcsolat/#_form">Ön is szeretné, ha egyedi weboldala legyen? Írjon nekünk!</a> <a class="totop" href="#top">lap tetejére <i class="fa fa-angle-up"></i></a>
            </div>
          </div>
        </div>
      <?php } wp_reset_postdata(); ?>
  </div>
</div>
