window.customerDistributionChart = function (classSelectorName) {

    var customerDistributionChartCanvases = document.getElementsByClassName(classSelectorName)

    var colors = [
        '#8BC1F7',
        '#519DE9',
        '#0066CC',
        '#004B95',
        '#002F5D'
    ]

    for (var i = 0; i < customerDistributionChartCanvases.length; i++) {
        var element = customerDistributionChartCanvases[i];
        var canvas = element.getContext('2d')

        var labelsJson = canvas.canvas.getAttribute('data-labels')
        var valuesJson = canvas.canvas.getAttribute('data-values')

        var labelsArray = JSON.parse(labelsJson)
        var valuesArray = JSON.parse(valuesJson)

        var backgroundColors = colors.slice(0, labelsArray.length)

        var customerDistributionChartData = {
            labels: labelsArray,
            datasets: [
                {
                    backgroundColor: backgroundColors,
                    data: valuesArray
                }
            ]
        }

        var customerDistributionChartOptions = {
            responsive: true,
            legend: {
                display: false
            },
        }

        // This will get the first returned node in the jQuery collection.
        // eslint-disable-next-line no-unused-vars
        var customerDistributionChart = new Chart(canvas, { // lgtm[js/unused-local-variable]
            type: 'bar',
            data: customerDistributionChartData,
            options: customerDistributionChartOptions
        })
    }
}
