const ctx = document.getElementById('salesChart').getContext('2d');
const salesChart = new Chart(ctx, {
  type: 'bar', // can be 'bar', 'line', 'pie', 'doughnut'
  data: {
    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May'],
    datasets: [{
      label: 'Sales',
      data: [120, 190, 300, 250, 220],
      backgroundColor: 'rgba(233, 30, 99, 0.6)', // matches your Blush-D theme
      borderColor: '#E91E63',
      borderWidth: 1
    }]
  },
  options: {
    responsive: true,
    plugins: {
      legend: { position: 'top' },
      title: { display: true, text: 'Monthly Sales Report' }
    }
  }
});

