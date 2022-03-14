/*Chart dashboard team*/
var ctx = document.getElementById('id-chart-studies').getContext('2d');
const gradient = ctx.createLinearGradient(-15, 100, 15, 100);
gradient.addColorStop(0, 'rgba(0,204,204,1)');
gradient.addColorStop(1, 'rgba(0,90,255,1)');
var chart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: ['2000', '2001', '2002', '2003', '2004', '2005', '2006', '2007', '2008'],
        datasets: [{
            label: 'graduate name School',
            fill: false,
            pointRadius: 7,
            pointHoverRadius: 7,
            pointHoverBackgroundColor: gradient,
            pointBorderColor: 'rgba(255, 255, 255, 0)',
            pointBackgroundColor: gradient,
            borderColor: '#ffd200',
            data: [0, 50, 100, 100, 150, 200, 250, 275, 275, 300]
        }]
    },
    options: {
        responsive: true,
        tooltips: {
            backgroundColor: 'rgba(63, 63, 63, 1)'
        },
        legend: {
            display: false
        },
        scales: {
            xAxes: [{
                gridLines: {
                    borderDash: [2,2]
                },
                display: true,
                scaleLabel: {
                    display: true
                }
            }],
            yAxes: [{
                gridLines: {
                    borderDash: [4,4]
                },
                display: true,
                scaleLabel: {
                    display: false,
                    labelString: 'Value'
                },
                ticks: {
                    suggestedMin: -10,
                    suggestedMax: 300
                }
            }]
        }
    }
});

