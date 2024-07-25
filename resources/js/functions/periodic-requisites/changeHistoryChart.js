window.changeHistoryChart = function (idSelectorName) {

    if (document.getElementById(idSelectorName) !== null) {
        var periodicRequisitesChangeHistoryChartCanvas = document.getElementById(idSelectorName).getContext('2d')

        var labelsJson = periodicRequisitesChangeHistoryChartCanvas.canvas.getAttribute('data-labels')
        var valuesJson = periodicRequisitesChangeHistoryChartCanvas.canvas.getAttribute('data-values')

        var labelsArray = JSON.parse(labelsJson)
        var valuesArray = JSON.parse(valuesJson)

        var periodicRequisitesChangeHistoryChartData = {
            labels: labelsArray,
            datasets: [
                {
                    borderColor: 'rgba(60,141,188,0.8)',
                    backgroundColor: 'rgba(255,255,255,0)',
                    data: valuesArray
                }
            ]
        }

        var periodicRequisitesChangeHistoryChartOptions = {
            responsive: true,
            legend: {
                display: false
            },
        }

        // This will get the first returned node in the jQuery collection.
        // eslint-disable-next-line no-unused-vars
        var basePaymentValueChangeHistoryChart = new Chart(periodicRequisitesChangeHistoryChartCanvas, { // lgtm[js/unused-local-variable]
            type: 'line',
            data: periodicRequisitesChangeHistoryChartData,
            options: periodicRequisitesChangeHistoryChartOptions
        })
    }
}
