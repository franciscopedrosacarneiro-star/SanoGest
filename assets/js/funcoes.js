document.addEventListener("DOMContentLoaded", function() {
    
    // 1. Gráfico de Barras: Equipamentos por Serviço
    const ctxServicos = document.getElementById('graficoServicos');
    if (ctxServicos) {
        new Chart(ctxServicos.getContext('2d'), {
            type: 'bar',
            data: {
                labels: ['Urgências', 'Bloco Operatório', 'Pediatria', 'C. Intensivos', 'Radiologia'],
                datasets: [{
                    label: 'Nº de Equipamentos',
                    data: [35, 42, 15, 28, 22],
                    backgroundColor: '#0d6efd',
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });
    }

    // 2. Gráfico Doughnut (Donut): Distribuição por Categoria (SEM GRELHA)
    const ctxCategorias = document.getElementById('graficoCategorias');
    if (ctxCategorias) {
        new Chart(ctxCategorias.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: ['Imagem Médica', 'Suporte Vida', 'Monitorização', 'Laboratório', 'Gerais'],
                datasets: [{
                    data: [25, 20, 45, 15, 37],
                    backgroundColor: ['#6f42c1', '#dc3545', '#198754', '#ffc107', '#0dcaf0'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' }
                },
                // Força o Chart.js a Ocultar os eixos e remover a "tabela" de fundo
                scales: {
                    x: { display: false },
                    y: { display: false }
                }
            }
        });
    }

    // 3. Gráfico de Barras Horizontais: Suporte de Vida por Serviço
    const ctxSuporte = document.getElementById('graficoSuporteVida');
    if (ctxSuporte) {
        new Chart(ctxSuporte.getContext('2d'), {
            type: 'bar',
            data: {
                labels: ['Urgências', 'Bloco Operatório', 'C. Intensivos', 'Cardiologia'],
                datasets: [{
                    label: 'Qtd. Equipamentos de Suporte de Vida',
                    data: [8, 12, 15, 5],
                    backgroundColor: '#dc3545',
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                indexAxis: 'y', // Inverte para horizontal
                plugins: {
                    legend: { display: false }
                }
            }
        });
    }
});