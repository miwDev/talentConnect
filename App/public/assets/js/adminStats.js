document.addEventListener('DOMContentLoaded', function() {
    
    const userToken = sessionStorage.getItem("token");
    
    // --- COLORES ---
    const COLORS = {
        BLUE: '#3b82f6',
        BLUE_BG: '#dbeafe',
        GREEN: '#10b981',
        GREEN_BG: '#d1fae5',
        PURPLE: '#8b5cf6',
        PURPLE_BG: '#ede9fe',
        ORANGE: '#f97316',
        ORANGE_BG: '#ffedd5',
        // Para el gráfico circular
        PIE_COLORS: ['#3b82f6', '#10b981'], // Azul para Alumnos, Verde para Empresas
        PIE_HOVER: ['#2563eb', '#059669']
    };

    const API_URL = '/API/adminstats.php?statType='; 

    // --- FETCH GENÉRICO ---
    async function fetchData(statType) {
        if (!userToken) return null;
        try {
            const response = await fetch(API_URL + statType, {
                headers: { 'Authorization': 'Bearer ' + userToken }
            });
            const json = await response.json();
            return json.success ? json.data : 0;
        } catch (error) {
            console.error(error);
            return 0;
        }
    }

    // --- MINI GRÁFICOS (DONUT) ---
    function createMiniDoughnut(canvasId, value, label, colorMain, colorBg) {
        const ctx = document.getElementById(canvasId);
        if(!ctx) return;
        
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: [label, 'Otros'], 
                datasets: [{
                    data: [value, 0], 
                    backgroundColor: [colorMain, colorBg],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '75%', 
                plugins: {
                    legend: { display: false },
                    tooltip: { enabled: false } 
                }
            }
        });
    }

    // --- 1. CARGAR KPIS ---
    async function loadKPIs() {
        const [candidates, companies, offers, applications] = await Promise.all([
            fetchData('totalCandidates'),
            fetchData('totalCompanies'),
            fetchData('activeOffers'),
            fetchData('totalApplications')
        ]);

        document.getElementById('valTotalCandidates').textContent = candidates || 0;
        document.getElementById('valTotalCompanies').textContent = companies || 0;
        document.getElementById('valActiveOffers').textContent = offers || 0;
        document.getElementById('valTotalApplications').textContent = applications || 0;

        createMiniDoughnut('chartMiniCandidates', candidates, 'Alumnos', COLORS.BLUE, COLORS.BLUE_BG);
        createMiniDoughnut('chartMiniCompanies', companies, 'Empresas', COLORS.GREEN, COLORS.GREEN_BG);
        createMiniDoughnut('chartMiniOffers', offers, 'Ofertas', COLORS.PURPLE, COLORS.PURPLE_BG);
        createMiniDoughnut('chartMiniApplications', applications, 'Solicitudes', COLORS.ORANGE, COLORS.ORANGE_BG);
    }

    // --- 2. GRÁFICO DE QUESO (DISTRIBUCIÓN) ---
    async function drawDistributionChart() {
        // Llamada al nuevo endpoint
        const data = await fetchData('userDistribution');
        
        // Datos (Fallback si la API no devuelve nada aún)
        const labels = data?.labels || ['Alumnos', 'Empresas'];
        const counts = data?.data || [0, 0];

        const ctx = document.getElementById('chartUserDistribution').getContext('2d');
        
        new Chart(ctx, {
            type: 'pie', // Tipo Queso
            data: {
                labels: labels,
                datasets: [{
                    data: counts,
                    backgroundColor: COLORS.PIE_COLORS,
                    hoverBackgroundColor: COLORS.PIE_HOVER,
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false, // Importante para que respete el CSS
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            font: { size: 14 }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                let value = context.raw || 0;
                                let total = context.chart._metasets[context.datasetIndex].total;
                                let percentage = Math.round((value / total) * 100) + '%';
                                return `${label}: ${value} (${percentage})`;
                            }
                        }
                    }
                }
            }
        });
    }

    loadKPIs();
    drawDistributionChart();
});