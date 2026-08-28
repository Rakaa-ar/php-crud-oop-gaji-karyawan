const grafik = document.getElementById('grafikGaji');

new Chart(grafik, {
    type: 'bar',

    data: {
        labels: namaKaryawan,

        datasets: [{
            label: 'Gaji Pokok',
            data: gajiKaryawan,

            backgroundColor: '#5b84c4',
            borderColor: '#4a70aa',
            borderWidth: 1,

            hoverBackgroundColor: '#4a69a1'
        }]
    },

    options: {
    responsive: true,

    scales: {
        y: {
            ticks: {
                callback: function(value) {
                    return 'Rp ' + (value / 1000000) + ' jt';
                }
            }
        }
    },

    plugins: {
        tooltip: {
            callbacks: {
                label: function(context) {
                    return 'Rp ' + context.raw.toLocaleString('id-ID');
                }
            }
        }
    }
}
});