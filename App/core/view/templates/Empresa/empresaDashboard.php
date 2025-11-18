<?php
$this->layout('layout/layout', [
    'title' => 'Talent Connect - Dashboard Empresa',
    'role' => $this->data['role'], 
]);
?>

<?php $this->start('js') ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script src="/public/assets/js/empresaStats.js"></script>
<?php $this->stop() ?>

<?php $this->start('pageContent') ?>

<div class="list-container dashboard-container">
    
    <div class="header-section">
        <h1>Dashboard de <span class="highlight">Estadísticas</span></h1>
        <p class="dashboard-subtitle">Visualiza el rendimiento de tus ofertas y la actividad de las solicitudes.</p>
    </div>

    <div class="kpi-grid">
        <div class="kpi-card kpi-offers">
            <img class="kpi-icon" src="/public/assets/images/jobOffer.svg" alt="icono-ofertas">
            <p class="kpi-title">Ofertas Publicadas</p>
            <p class="kpi-value" id="totalOffersValue">Cargando...</p>
        </div>
        <div class="kpi-card kpi-solicitudes">
            <img class="kpi-icon" src="/public/assets/images/solicitudesDash.svg" alt="icono-solicitudes">
            <p class="kpi-title">Solicitudes Recibidas</p>
            <p class="kpi-value" id="totalSolicitudesValue">Cargando...</p>
        </div>
    </div>

    <div class="charts-row">
        <div class="chart-card chart-donut">
            <h3 class="chart-title">Estado de las Ofertas</h3>
            <div class="chart-canvas-container">
                <canvas id="chartActiveExpired"></canvas>
            </div>
        </div>

        <div class="chart-card chart-bar-v">
            <h3 class="chart-title">Distribución por Ciclo Formativo</h3>
            <div class="chart-canvas-container">
                <canvas id="chartCycles"></canvas>
            </div>
        </div>
    </div>

    <div class="charts-row">
        <div class="chart-card chart-bar-h">
            <h3 class="chart-title">Top 5 Ofertas Más Solicitadas</h3>
            <div class="chart-canvas-container">
                <canvas id="chartTopOffers"></canvas>
            </div>
        </div>

        <div class="chart-card chart-line">
            <h3 class="chart-title">Tendencia Mensual de Solicitudes</h3>
            <div class="chart-canvas-container">
                <canvas id="chartMonthlyTrend"></canvas>
            </div>
        </div>
    </div>
</div>

<?php $this->stop() ?>