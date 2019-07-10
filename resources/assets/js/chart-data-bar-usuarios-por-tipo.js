var ctx1 = $("canvas").get(0).getContext("2d");
var gradient1 = ctx1.createLinearGradient(150, 0, 150, 300);
gradient1.addColorStop(0, 'rgba(133, 180, 242, 0.91)');
gradient1.addColorStop(1, 'rgba(255, 119, 119, 0.94)');

export const chartDataUsers = {
    type: 'bar',
    data: {
        labels: [
            "Ciudadano",
            "Policía",
            "Alcalde Vecinal",
            "Comité de Gestión",
            "Personal Plandet"
        ],
        datasets: [
            {
                label: "Data Set 1",
                backgroundColor: [
                    gradient1,
                    gradient1,
                    gradient1,
                    gradient1,
                    gradient1,
                    gradient1,
                    gradient1
                ],
                hoverBackgroundColor: [
                    gradient1,
                    gradient1,
                    gradient1,
                    gradient1,
                    gradient1,
                    gradient1,
                    gradient1
                ],
                borderColor: [
                    gradient1,
                    gradient1,
                    gradient1,
                    gradient1,
                    gradient1,
                    gradient1,
                    gradient1
                ],
                borderWidth: 1,
                data: [12, 4, 6, 5, 7],
            },
            {
                label: "Data Set 2",
                backgroundColor: [
                    gradient2,
                    gradient2,
                    gradient2,
                    gradient2,
                    gradient2,
                    gradient2,
                    gradient2
                ],
                hoverBackgroundColor: [
                    gradient2,
                    gradient2,
                    gradient2,
                    gradient2,
                    gradient2,
                    gradient2,
                    gradient2
                ],
                borderColor: [
                    gradient2,
                    gradient2,
                    gradient2,
                    gradient2,
                    gradient2,
                    gradient2,
                    gradient2
                ],
                borderWidth: 1,
                data: [35, 40, 60, 47, 88, 27, 30],
            }
        ]
    },
    options: {
        scales: {
            xAxes: [{
                display: true,
                gridLines: {
                    color: '#eee'
                }
            }],
            yAxes: [{
                display: true,
                gridLines: {
                    color: '#eee'
                }
            }]
        },
    }
  }
  
  export default chartDataUsers;