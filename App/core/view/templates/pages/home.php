<?php
$this->layout('layout/layout', [
    'title' => 'Talent Connect - Inicio',
    'navItems' => [
        '<a href="?menu=login" class="menu-enlace">SIGN IN</a>',
        '<a href="?menu=regRedirect" class="menu-enlace">SIGN UP</a>'
    ]
]);
?>

<?php $this->start('pageContent') ?>
<section id="section0">
    <div class="banner-container">
        <img class="banner-image" src="/public/assets/images/banner.png" alt="bannerPicture" height="800">
        <div class="banner-text">
            <h1>TALENT CONNECT</h1>
            <h2>Donde el potencial se encuentra con el éxito</h2>
        </div>
    </div>
</section>
<section id="section1">
    <div class="section-container">
        <h1>
            CONECTAMOS EL MEJOR TALENTO CON LAS EMPRESAS LÍDERES
        </h1>
        <div id="section1-content">
            <p class="h1-content" id="boldSubtitle">
                Creamos oportunidades, simplificamos la contratación.
            </p>
            <img class="section1-img" src="/public/assets/images/section1img.png" alt="s1Picture">
        </div>

    </div>
</section>
<section id="section2">
    <div class="section-container">
        <div>
            <h2>
                Nuestras empresas
            </h2>
        </div>
        <div class="companies-container">
            <img class="placeholder" src="/public/assets/images/placeholder.png" alt="placeholder" width="2000" height="500">
        </div>
    </div>
</section>
<section id="section3">

    <div class="s3-container">
        <div class="s3-header">
            <h3>
                ¿Aún no te conectas a Talent Connect?
            </h3>
            <p class="s3-subtitle">
                Tanto si buscas tu primer empleo como si buscas el talento que impulse tu empresa, ¡Es hora de unirte a la red!
            </p>
        </div>
        <div class="s3-btn-container">
            <div class="btn-style">
                <a href="?menu=regEmpresa" class="a-section3">EMPRESA</a>
            </div>
            <div class="btn-style">
                <a href="?menu=regAlumno" class="a-section3">ALUMNO</a>
            </div>
        </div>
        <div class="s3-register-link">
            <a class="register-link" href="?menu=login">¿Estas ya Registrado? clica aquí!</a>
        </div>
    </div>

</section>
<?php $this->stop() ?>