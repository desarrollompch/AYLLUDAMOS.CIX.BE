export const chartDataUsers = {
    type: 'pie',
    data: {
        labels: [
            "Sin Confirmar",
            "Confirmado",
            "Falso positivo",
            "Atendido"
        ],
        datasets: [
        { // one line graph
          label: 'Estado Incidente',
          data: [12, 4, 5, 24],
          backgroundColor: [
            '#8366ac',
            '#ca5959',
            "#e4be6c",
            "#5960ae"
          ],
          borderColor: [
            '#8366ac',
            '#ca5959',
            "#e4be6c",
            "#5960ae"
          ],
          borderWidth: 3
        },
        { // another line graph
          label: 'Planet Mass (x1,000 km)',
          data: [4.8, 12.1, 12.7, 6.7, 139.8, 116.4, 50.7, 49.2],
          backgroundColor: [
            'rgba(71, 183,132,.5)', // Green
          ],
          borderColor: [
            '#47b784',
          ],
          borderWidth: 3
        }
      ]
    }
  }
  
  export default chartDataUsers;