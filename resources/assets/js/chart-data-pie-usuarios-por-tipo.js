export const chartDataUsers = {
    type: 'pie',
    data: {
        labels: [
            "Ciudadano",
            "Policía",
            "Alcalde Vecinal",
            "Comité de Gestión",
            "Personal Plandet"
        ],
        datasets: [
        { // one line graph
          label: 'Number of Moons',
          data: [12, 4, 6, 5, 7],
          backgroundColor: [
            '#00b3e7',
            "#d2e5a7",
            "#f9f9f9",
            "#2d2a36",
            "#31a34a",
          ],
          borderColor: [
            '#00b3e7',
            "#d2e5a7",
            "#f9f9f9",
            "#2d2a36",
            "#31a34a",
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