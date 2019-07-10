<template>
    <div>
        <div class="col-md-12 mb-5">
            <div class="col-md-6 offset-md-3">
                <h4 class="card-header">Métrica general</h4>
                <canvas id="metricas-chart-bar"></canvas>
            </div>
        </div>

        <div class="col-md-12 mb-5">
            <div class="d-flex">
                <div class="col-md-6">
                    <h4 class="card-header">Usuarios - Gráfico de barras</h4>
                    <canvas id="users-chart-bar"></canvas>
                </div>
                <div class="col-md-6">
                    <h4 class="card-header">Incidentes - Gráfico de barras</h4>
                    <canvas id="incidentes-chart-bar"></canvas>
                </div>
            </div>
        </div>   

        <div class="col-md-12 mb-5">
            <div class="d-flex">
                <div class="col-md-6">
                    <h4 class="card-header">Usuarios - Gráfico circular</h4>
                    <canvas id="users-chart-pie"></canvas>
                </div>
                <div class="col-md-6">
                    <h4 class="card-header">Incidentes - Gráfico circular</h4>
                    <canvas id="incidentes-chart-pie"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-12 mb-5">
            <div class="d-flex">
                <div class="col-md-6">
                    <h4 class="card-header">Usuarios - Gráfico circular</h4>
                    <canvas id="users-chart-doughnut"></canvas>
                </div>
                <div class="col-md-6">
                    <h4 class="card-header">Incidentes - Gráfico circular</h4>
                    <canvas id="incidentes-chart-doughnut"></canvas>
                </div>
            </div>
        </div>

    </div>
    
</template>

<script>
    import Chart from 'chart.js';

    export default {
        data() {
            return {
                // 
            }
        },
        mounted() {
            axios.get(route("reportes.metricas")).then((response) => {
                console.log("response métricas ", response);
                var labelMetricas = response.data.labels;
                var dataMetricas = response.data.data;
                var loading_bar = this.createDataChart(labelMetricas, dataMetricas, "Métricas", "pie", true);
                this.createChart('metricas-chart-bar', loading_bar);
            });

            axios.get(route("reportes.total-usuarios-por-tipo")).then((response) => {
                console.log("response total usuarios por tipo ", response);
                var labelUsuarios = response.data.labels;
                var dataUsuarios = response.data.data;
                
                var loading_bar = this.createDataChart(labelUsuarios, dataUsuarios, "Usuarios", "bar", true);
                this.createChart('users-chart-bar', loading_bar);

                var loading_pie = this.createDataChart(labelUsuarios, dataUsuarios, "Usuarios", "pie", true);
                this.createChart('users-chart-pie', loading_pie);

                var loading_doughnut = this.createDataChart(labelUsuarios, dataUsuarios, "Usuarios", "doughnut", true);
                this.createChart('users-chart-doughnut', loading_doughnut);
            });
            
            axios.get(route("reportes.total-incidentes-por-estado-incidente")).then((response) => {
                var labelIncidentes = response.data.labels;
                var dataIncidentes = response.data.data;

                var loading_bar = this.createDataChart(labelIncidentes, dataIncidentes, "Incidentes", "bar", true);
                this.createChart('incidentes-chart-bar', loading_bar);

                var loading_pie = this.createDataChart(labelIncidentes, dataIncidentes, "Incidentes", "pie", true);
                this.createChart('incidentes-chart-pie', loading_pie);

                var loading_doughnut = this.createDataChart(labelIncidentes, dataIncidentes, "Incidentes", "doughnut", true);
                this.createChart('incidentes-chart-doughnut', loading_doughnut);
            });

            $('#mute').removeClass('on');
        },
        methods: {
            createDataChart(paramLabels, paramData, labelTitle, type, legend) {
                let colors = [];

                paramLabels.map(row =>{
                   colors.push(this.getRandomColor());
                });

                var chartData = {
                    type: type,
                    data: {
                        labels: paramLabels,
                        datasets: [
                            {
                                label: labelTitle,
                                data: paramData,
                                backgroundColor: colors
                            }
                        ]
                    },
                    options: {
                        legend: {
                            display: legend,
                            labels: {
                                fontColor: 'black'
                            }
                        },
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero:true
                                }
                            }]
                        }
                    }
                }
                return chartData;
            },
            createChart(chartId, chartData) {
                const ctx = document.getElementById(chartId);
                const myChart = new Chart(ctx, {
                    type: chartData.type,
                    data: chartData.data,
                    options: chartData.options
                });
            },
            getRandomColor() {
                var letters = '0123456789ABCDEF';
                var color = '#';
                for (var i = 0; i < 6; i++) {
                    color += letters[Math.floor(Math.random() * 16)];
                }
                return color;
            }
        }
    }
</script>