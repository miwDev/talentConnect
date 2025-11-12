<?php
$this->layout('layout/layout', [
    'title' => 'Talent Connect - Inicio',
    'role' => $this->data['role'] ?? 'ROLE_GUEST', // Propaga el rol para el header
    'username' => $this->data['username'] ?? '' // Propaga el username
]);
?>

<?php $this->start('pageContent') ?>
<div class="main-container">
    <section id="section0">
        <div class="banner-container">
            <video class="banner-video" width="100%" height="800" autoplay muted loop playsinline>
                <source src="/public/assets/images/bannerVideo.mp4" type="video/mp4">
                Tu navegador no soporta el elemento de video.
            </video>

            <div class="banner-text">
                <h1>TALENT CONNECT</h1>
                <h2>Donde el potencial se encuentra con el éxito</h2>
            </div>
        </div>
    </section>

    <section id="section1">
        <h2 class="section1-title">
            Tu futuro <span class="highlight">comienza aquí</span>
        </h2>
        <div class="section-container">
            <div class="feature-card">
                <div class="feature-image-container">
                    <img src="/public/assets/images/section1img.png" alt="Feature 1: Creamos oportunidades">
                </div>
                <div class="feature-text">
                    <h3>Creamos oportunidades</h3>
                    <p>
                        Conectamos profesionales talentosos con las mejores empresas del mercado.
                        Nuestra plataforma facilita el encuentro entre el talento excepcional y
                        las oportunidades que realmente marcan la diferencia en tu carrera.
                    </p>
                </div>
            </div>

            <div class="feature-card feature-card-reverse">
                <div class="feature-image-container">
                    <img src="/public/assets/images/section2img.png" alt="Feature 2: Simplificamos la contratación">
                </div>
                <div class="feature-text">
                    <h3>Simplificamos la contratación</h3>
                    <p>
                        Hacemos que el proceso de reclutamiento sea más eficiente y efectivo.
                        Las empresas encuentran candidatos cualificados rápidamente, mientras
                        los profesionales descubren oportunidades que se ajustan a su perfil.
                    </p>
                </div>
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
                <h1 class="placeholder" style="color: #FFFFE9;">carrusel en proceso</h1>
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
                    Tanto si buscas tu primer empleo como si buscas el talento que impulse tu empresa, <span class="s3-subtitle-alter">¡Es hora de unirte a la red!</span>
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
                <a class="register-link" href="?menu=login">¿Estás ya Registrado? <span class="s3-register-alter">¡Es hora de unirte a la red!</span></a>
            </div>
        </div>
    </section>
</div>
<?php $this->stop() ?>