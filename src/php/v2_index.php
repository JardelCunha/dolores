<?php
require_once(__DIR__ . '/dlib/assets.php');
require_once(__DIR__ . '/dlib/wp_util/add_paged_class.php');
require_once(__DIR__ . '/dlib/wp_admin/settings/streaming.php');

get_header();

$hero_src = DoloresAssets::get_image_uri('v2-hero-image.jpg');
$logo_src = DoloresAssets::get_image_uri('v2-logo.png');

$video_mp4 = DoloresAssets::get_static_uri('videos/mare.mp4');
$video_webm = DoloresAssets::get_static_uri('videos/mare.webm');

if (!$paged || $paged == 1) {
  ?>
  <section class="site-presentation explanation">
    <div class="wrap explanation-wrap">
      <p>E se a cidade fosse nossa? Essa é a pergunta que nos une em movimento. Sonhamos com um Rio de Janeiro mais livre, solidário e feliz. Uma cidade que ouça as vozes das ruas para construir um novo modelo de governo com e para as pessoas.</p>
      <p>Queremos um Rio de Janeiro de direitos, onde as pessoas possam fazer parte das decisões que transformam as suas vidas.</p>
    </div>
  </section>

  <section class="site-hero"
      style="background-image: url('<?php echo $hero_src; ?>');">
    <video class="hero-video" autoplay="autoplay" loop="loop"
        poster="<?php echo $hero_src; ?>">
      <source src="<?php echo $video_mp4; ?>" type="video/mp4" />
      <source src="<?php echo $video_webm; ?>" type="video/webm" />
    </video>
    <div class="hero-logo-container">
      <a href="<?php echo site_url(); ?>" title="Página inicial">
        <img class="hero-logo-image" src="<?php echo $logo_src; ?>" />
      </a>
    </div>
    <button class="hero-button toggle-explanation">
      Entenda
    </button>
  </section>

  <?php
  if (DoloresStreaming::get_active()) {
    $title = esc_html(DoloresStreaming::get_title());
    $youtube_id = DoloresStreaming::get_youtube_id();
    $params = "rel=0&amp;controls=0&amp;showinfo=0&amp;autoplay=1";
    $url = "//youtube.com/embed/${youtube_id}?${params}";
    ?>
    <section class="site-streaming">
      <div class="wrap">
        <h2 class="streaming-title"><?php echo $title; ?></h2>
        <iframe
          class="streaming-box"
          src="<?php echo $url; ?>"
          frameborder="0"
          allowfullscreen>
        </iframe>
      </div>
    </section>
    <?php
  }

  ?>

  <section class="home-default-section">
    <div class="wrap">
      <ul class="home-main-grid">
        <li class="home-main-item home-bg-map">
          <a href="#" class="home-main-item-link home-main-item-link-no-alpha">
            <div class="home-main-item-border"></div>
            <div class="home-main-item-wrap">
              <h3 class="home-main-item-title">
                Que mudanças você quer para o seu bairro?
              </h3>
              <button class="home-main-item-action">Participe</button>
            </div>
          </a>
        </li>

        <?php
        $image = "http://imguol.com/c/noticias/2013/10/23/7-lugar-houston-eua---houston-no-estado-do-texas-ficou-em-7-lugar-esta-e-a-quarta-maior-cidade-dos-estados-unidos-quando-se-trata-de-populacao-mas-a-atracao-com-certeza-nao-e-paisagem-1382537404960_956x500.jpg";
        ?>
        <li
            class="home-main-item"
            style="background-image: url('<?php echo $image; ?>');"
            >
          <a href="#" class="home-main-item-link">
            <div class="home-main-item-wrap">
              <h3 class="home-main-item-title">
                Viver na cidade
              </h3>
              <p class="home-main-item-explanation">
                E se as decisões fossem nossas?
              </p>
              <button class="home-main-item-action">Participe</button>
            </div>
          </a>
        </li>
      </ul>
    </div>
  </section>

  <section class="home-ideas">
    <div class="wrap">
      <h2 class="home-title">Ideias mais comentadas</h2>

      <!-- TODO: grid -->

      <div class="home-button-container">
        <a class="home-button" href="/temas">Veja todos os temas</a>
      </div>
    </div>
  </section>

  <?php
}

include(__DIR__ . '/grid.php');
?>

<?php
get_footer();
?>
