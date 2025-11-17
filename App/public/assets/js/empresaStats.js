document.addEventListener('DOMContentLoaded', function() {
    
    // Asumimos que el userToken se obtiene al inicio
    const userToken = sessionStorage.getItem("token");
    
    // --- PALETA DE COLORES ---
    const COLORS = {
        GREEN_DARK: '#16443D',
        ACCENT: '#EBD3FD',
        ALERT: '#E53E3E',
        DARK: '#191919',
        WHITE_OFF: '#FEFDF8',
        BEIGE_LIGHT: '#FFFFE9',
        // Colores secundarios para gráficos de distribución
        CYCLE_COLORS: ['#EBD3FD', '#D7B4F2', '#AF7AF5', '#7A52A3', '#5E3F7D'] 
    };

    // La URL base de tu API
    const API_URL = '/API/ApiStatsEmpresa.php?statType='; 

    // --------------------------------------------------------------------------
    // --- FUNCIÓN CENTRAL PARA FETCH Y ERROR ---
    // --------------------------------------------------------------------------
    async function fetchData(statType) {
        if (!userToken) {
            console.error("Authorization Error: No token found. Redirecting...");
            // Aquí podrías añadir window.location.href = '/login';
            return null;
        }

        try {
            const response = await fetch(API_URL + statType, {
                method: "GET",
                headers: {
                    // Incluimos el token en el formato 'Bearer TOKEN'
                    'Authorization': 'Bearer ' + userToken
                }
            });
            
            if (!response.ok) {
                // Maneja errores HTTP como 401, 403, 404, etc.
                throw new Error(`HTTP error! status: ${response.status} for ${statType}`);
            }

            const json = await response.json();
            
            if (!json.success) {
                 // Maneja errores lógicos devueltos por la API (success: false)
                 throw new Error(`API error: ${json.message || 'Unknown error'} for ${statType}`);
            }

            // Devuelve la sección 'data' del JSON
            return json.data;

        } catch (error) {
            console.error(`Error fetching ${statType}:`, error);
            return null;
        }
    }

    // --------------------------------------------------------------------------
    // --- 1. Indicadores Clave (Total Ofertas y Solicitudes) ---
    // --------------------------------------------------------------------------
    async function loadIndicators() {
        // Total Ofertas
        const totalOffers = await fetchData('totalOffers');
        if (totalOffers !== null) {
            document.getElementById('totalOffersValue').textContent = totalOffers;
        }

        // Total Solicitudes
        const totalSolicitudes = await fetchData('totalSolicitudes');
        if (totalSolicitudes !== null) {
            document.getElementById('totalSolicitudesValue').textContent = totalSolicitudes;
        }
    }

    // --------------------------------------------------------------------------
    // --- 2. Gráfico: Top 5 Ofertas por Solicitudes (Barras Horizontales) ---
    // --------------------------------------------------------------------------
    async function drawTopOffersChart() {
        const data = await fetchData('topOffers');
        if (!data || data.length === 0) return;

        const titles = data.map(item => item.titulo);
        const counts = data.map(item => item.count);

        new Chart(document.getElementById('chartTopOffers'), {
            type: 'bar',
            data: {
                labels: titles,
                datasets: [{
                    label: 'Solicitudes',
                    data: counts,
                    backgroundColor: COLORS.GREEN_DARK, 
                    borderColor: COLORS.DARK,
                    borderWidth: 1
                }]
            },
            options: {
                indexAxis: 'y', // Horizontales
                responsive: true,
                maintainAspectRatio: false, 
                scales: {
                    x: {
                        grid: { color: 'rgba(25, 25, 25, 0.1)' } 
                    },
                    y: {
                        grid: { display: false }
                    }
                },
                plugins: {
                    legend: { display: false },
                }
            }
        });
    }

    // --------------------------------------------------------------------------
    // --- 3. Gráfico: Distribución de Ciclos Solicitados (Barras Verticales) ---
    // --------------------------------------------------------------------------
    async function drawCyclesChart() {
        const data = await fetchData('cyclesDistribution');
        if (!data || data.length === 0) return;

        const cycles = data.map(item => item.ciclo);
        const counts = data.map(item => item.count);
        
        new Chart(document.getElementById('chartCycles'), {
            type: 'bar',
            data: {
                labels: cycles,
                datasets: [{
                    label: 'Ofertas Publicadas',
                    data: counts,
                    backgroundColor: COLORS.CYCLE_COLORS, 
                    borderColor: COLORS.DARK,
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false, 
                scales: {
                    y: { beginAtZero: true },
                    x: { grid: { display: false } }
                },
                plugins: {
                    legend: { display: false },
                }
            }
        });
    }

    // --------------------------------------------------------------------------
    // --- 4. Gráfico: Estado de Ofertas (Donut) ---
    // --------------------------------------------------------------------------
    async function drawActiveExpiredChart() {
        const data = await fetchData('activeExpiredOffers');
        if (!data || data.length === 0) return;

        const labels = data.map(item => item.status);
        const counts = data.map(item => item.count);
        
        const backgroundColors = labels.map(status => 
            status === 'Activa' ? COLORS.GREEN_DARK : COLORS.ALERT
        );

        new Chart(document.getElementById('chartActiveExpired'), {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    data: counts,
                    backgroundColor: backgroundColors,
                    borderColor: COLORS.DARK,
                    borderWidth: 2,
                    hoverOffset: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false, 
                plugins: {
                    legend: { position: 'bottom', labels: { usePointStyle: true } },
                }
            }
        });
    }
    
    // --------------------------------------------------------------------------
    // --- 5. Gráfico: Tendencia Mensual (Líneas) ---
    // --------------------------------------------------------------------------
    async function drawMonthlyTrendChart() {
        const data = await fetchData('monthlyTrend');
        if (!data || data.length === 0) return;

        const months = data.map(item => item.month);
        const counts = data.map(item => item.count);
        
        new Chart(document.getElementById('chartMonthlyTrend'), {
            type: 'line',
            data: {
                labels: months,
                datasets: [{
                    label: 'Solicitudes',
                    data: counts,
                    backgroundColor: 'rgba(235, 211, 253, 0.6)',
                    borderColor: COLORS.GREEN_DARK,
                    tension: 0.3,
                    fill: true,
                    pointRadius: 6,
                    pointBackgroundColor: COLORS.GREEN_DARK
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false, 
                scales: {
                    y: { beginAtZero: true },
                    x: { grid: { color: 'rgba(25, 25, 25, 0.1)' } }
                },
                plugins: {
                    legend: { display: false },
                }
            }
        });
    }

    // --------------------------------------------------------------------------
    // --- EJECUCIÓN ---
    // --------------------------------------------------------------------------
    loadIndicators();
    drawTopOffersChart();
    drawCyclesChart();
    drawActiveExpiredChart();
    drawMonthlyTrendChart();
});