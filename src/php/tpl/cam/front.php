<?php
require_once(DOLORES_PATH . '/dlib/assets.php');
require_once(DOLORES_PATH . '/dlib/calendar.php');

$logo_src = DoloresAssets::get_image_uri('cam/logo-hero.png');

get_header();
?>
<section class="site-presentation explanation">
  <div class="wrap explanation-wrap">
    <p style="font-size: 36px; text-align: center; padding: 10px;">
      Aqui vai o vídeo de apresentação
      <!-- TODO -->
    </p>
  </div>
</section>

<section class="site-hero">
  <div class="hero">
    <div class="hero-credit">
      Foto: Ivo Gonçalves/PMPA
    </div>
    <div class="hero-logo-container">
      <a href="<?php echo site_url(); ?>" title="Página inicial">
        <img class="hero-logo-image" src="<?php echo $logo_src; ?>" />
      </a>
    </div>
    <button class="hero-button toggle-explanation">
      <span>Apresentação</span>
    </button>
  </div>
  <?php
  require(DOLORES_TEMPLATE_PATH . '/home-destaques.php');
  ?>
</section>

<section class="home-temas">
  <div class="wrap home-temas-wrap">
    <div class="home-temas-element">
      <h2 class="home-temas-title">Que mudanças você quer?</h2>

      <?php
      require(DOLORES_TEMPLATE_PATH . '/temas-grid.php');
      ?>

      <div class="home-temas-button-container">
        <a class="home-temas-button" href="/temas/">
          Veja todos os temas
        </a>
      </div>
    </div>
  </div>
</section>

<section class="home-bairros">
  <div class="wrap">
    <?php require(DOLORES_TEMPLATE_PATH . '/bairros-map.php'); ?>
  </div>
</section>

<section class="home-banners">
  <div class="wrap">
    <a class="home-banner home-voluntario" href="/seja-um-voluntario/"
        title="Seja um voluntário">
      <span>Seja um voluntário</span>
    </a>
    <a class="home-banner home-reunioes" href="/reunioes-nos-bairros/"
        title="Organize reuniões no seu bairro">
      <span>Organize reuniões no seu bairro</span>
    </a>
    <a class="home-banner home-projetos" href="/c/projetos-na-camara/"
        title="Projetos na câmara">
      <span>Projetos na câmara</span>
    </a>
    <a class="home-banner home-contribuicoes" href="/c/contribuicoes/"
        title="Contribuições para o debate">
      <span>Contribuições para o debate</span>
    </a>
  </div>
</section>
<?php
get_footer();