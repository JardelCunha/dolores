<section class="site-grid-ideias">
  <div class="wrap">
    <?php
    if (have_posts()) {
      echo '<ul class="grid-ideias-list">';
      while (have_posts()) {
        the_post();
        ?>
        <li class="grid-ideia">
          <h3 class="grid-ideia-title">
            <a href="<?php the_permalink(); ?>">
              <?php the_title(); ?>
            </a>
          </h3>
          <?php
          $taxonomy = 'tema';
          $terms = get_the_terms($post->ID, $taxonomy);
          $tags = array();
          foreach ($terms as $term) {
            if ($term->parent != 0) {
              $tags[] = array(
                'name' => $term->name,
                'link' => get_term_link($term, $taxonomy)
              );
            }
          }
          if (count($tags)) {
            ?>
            <p class="grid-ideia-tags">
              <?php
              foreach ($tags as $tag) {
                ?>
                <a class="grid-ideia-tag" href="<?php echo $tag['link']; ?>"
                    ><?php echo $tag['name']; ?></a>
                <?php
              }
              ?>
            </p>
            <?php
          }
          ?>
          <p class="grid-ideia-author">
            <?php
            $id = get_the_author_meta('ID');
            require_once(__DIR__ . '/dlib/wp_util/user_meta.php');
            $picture = dolores_get_profile_picture(get_user_by('id', $id));
            $style = ' style="background-image: url(\'' . $picture. '\');"';
            $url = get_author_posts_url(get_the_author_meta('ID'));
            ?>
            <a href="<?php echo $url; ?>">
              <span class="grid-ideia-author-picture" <?php echo $style; ?>>
              </span>
              <?php the_author(); ?>
            </a>
          </p>
          <p class="grid-ideia-interact">
            <?php
            require_once(__DIR__ . '/dlib/interact.php');
            $interact = new DoloresInteract();
            list($up, $down) = $interact->get_post_votes($post->ID);
            $data = "href=\"#\" data-vote=\"post_id|{$post->ID}\"";
            ?>
            <a class="grid-ideia-action ideia-upvote" <?php echo $data; ?>>
              <i class="fa fa-fw fa-thumbs-up"></i>
              <span class="number"><?php echo $up; ?></span>
            </a>
            <a class="grid-ideia-action ideia-downvote" <?php echo $data; ?>>
              <i class="fa fa-fw fa-thumbs-down"></i>
              <span class="number"><?php echo $down; ?></span>
            </a>
            <a class="grid-ideia-action grid-ideia-discussion"
                href="<?php the_permalink(); ?>#comments">
              <i class="fa fa-fw fa-comments"></i>
              <?php echo get_comments_number(); ?>
            </a>
          </p>
          <!-- TODO: share -->
          <a class="grid-ideia-button" href="<?php the_permalink(); ?>">
            Opine &nbsp;
            <i class="fa fa-angle-right"></i>
          </a>
        </li>
        <?php
      }
      echo '</ul>';
    } else {
      echo '<p style="margin: 20px 0; text-align: center;">';
      echo 'Nenhuma ideia cadastrada.';
      echo '</p>';
    }

    if (!$paged) {
      $paged = 1;
    }

    $prev_page = intval($paged) - 1;
    $next_page = intval($paged) + 1;

    ?>
    <div class="grid-pagination">
      <?php

      if ($prev_page > 0) {
        $prev_link = get_previous_posts_page_link();
        ?>
        <a class="btn-prev-page" href="<?php echo $prev_link; ?>"></a>
        <?php
      }

      if ($next_page <= $wp_query->max_num_pages) {
        $next_link = get_next_posts_page_link();
        ?>
        <a class="btn-next-page" href="<?php echo $next_link; ?>"></a>
        <?php
      }
      ?>
    </div>
  </div>
</section>
