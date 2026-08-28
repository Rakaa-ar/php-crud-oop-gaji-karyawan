const grafik = document.getElementById('grafikGaji');

new Chart(grafik, {
    type: 'bar',

    data: {
        labels: namaKaryawan,

        datasets: [{
            label: 'Gaji Pokok',
            data: gajiKaryawan,

            backgroundColor: '#3765b0',
            borderColor: '#4069a8',
            borderWidth: 1,

            hoverBackgroundColor: '#315baa'
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