$(document).ready(function () {
    const $canvasP1 = $("#finishGoodP1");
    const p1Url = $canvasP1.data("url");
    const ctx = $canvasP1[0].getContext("2d");

    var gradientStroke1 = ctx.createLinearGradient(0, 230, 0, 50);
    gradientStroke1.addColorStop(1, "rgba(203,12,159,0.2)");
    gradientStroke1.addColorStop(0.2, "rgba(72,72,176,0.0)");
    gradientStroke1.addColorStop(0, "rgba(203,12,159,0)"); //purple colors

    var gradientStroke2 = ctx.createLinearGradient(0, 230, 0, 50);
    gradientStroke2.addColorStop(1, "rgba(20,23,39,0.2)");
    gradientStroke2.addColorStop(0.2, "rgba(72,72,176,0.0)");
    gradientStroke2.addColorStop(0, "rgba(20,23,39,0)"); //purple colors

    $.get(p1Url, function (data) {
        // console.log(data);
        new Chart(ctx, {
            type: "line",
            data: {
                labels: data.labels,
                datasets: [
                    {
                        label: "Lada Line A",
                        tension: 0.4,
                        borderWidth: 3,
                        pointRadius: 0,
                        borderColor: "#cb0c9f",
                        backgroundColor: gradientStroke1,
                        fill: true,
                        data: data.lineA,
                        maxBarThickness: 6,
                    },
                    {
                        label: "Lada Line B",
                        tension: 0.4,
                        borderWidth: 3,
                        pointRadius: 0,
                        borderColor: "#3A416F",
                        backgroundColor: gradientStroke2,
                        fill: true,
                        data: data.lineB,
                        maxBarThickness: 6,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: "right",
                        labels: {
                            usePointStyle: true, // legend jadi bulatan kecil
                            pointStyle: "line", // tipe legend sesuai border line
                            color: "#000", // warna teks legend
                            font: {
                                size: 12,
                                family: "Open Sans",
                            },
                            generateLabels: function (chart) {
                                return chart.data.datasets.map(function (
                                    dataset,
                                    i
                                ) {
                                    return {
                                        text: dataset.label,
                                        fillStyle: dataset.borderColor, // gunakan borderColor untuk warna legend
                                        strokeStyle: dataset.borderColor,
                                        hidden: !chart.isDatasetVisible(i),
                                        datasetIndex: i,
                                    };
                                });
                            },
                        },
                    },
                },
                interaction: {
                    intersect: false,
                    mode: "index",
                },
                scales: {
                    y: {
                        grid: {
                            drawBorder: false,
                            display: true,
                            drawOnChartArea: true,
                            drawTicks: false,
                            borderDash: [5, 5],
                        },
                        ticks: {
                            display: true,
                            padding: 10,
                            color: "#b2b9bf",
                            font: {
                                size: 11,
                                family: "Open Sans",
                                style: "normal",
                                lineHeight: 2,
                            },
                        },
                    },
                    x: {
                        grid: {
                            drawBorder: false,
                            display: false,
                            drawOnChartArea: false,
                            drawTicks: false,
                            borderDash: [5, 5],
                        },
                        ticks: {
                            display: true,
                            color: "#b2b9bf",
                            padding: 20,
                            font: {
                                size: 11,
                                family: "Open Sans",
                                style: "normal",
                                lineHeight: 2,
                            },
                        },
                    },
                },
            },
        });
    });

    const $canvasP2 = $("#finishGoodP2");
    const p2Url = $canvasP2.data("url");
    const ctxP2 = $canvasP2[0].getContext("2d");
    // var ctx = document.getElementById("finishGoodP1").getContext("2d");
    var p2gradientStroke1 = ctxP2.createLinearGradient(0, 230, 0, 50);
    p2gradientStroke1.addColorStop(1, "rgba(235, 207, 118, 0.2)");
    p2gradientStroke1.addColorStop(0.2, "rgba(72,72,176,0.0)");
    p2gradientStroke1.addColorStop(0, "rgba(203,12,159,0)"); //purple colors

    var p2gradientStroke2 = ctxP2.createLinearGradient(0, 230, 0, 50);
    p2gradientStroke2.addColorStop(1, "rgba(183, 247, 141, 0.2)");
    p2gradientStroke2.addColorStop(0.2, "rgba(72,72,176,0.0)");
    p2gradientStroke2.addColorStop(0, "rgba(20,23,39,0)"); //purple colors

    var p2gradientStroke3 = ctxP2.createLinearGradient(0, 230, 0, 50);
    p2gradientStroke3.addColorStop(1, "rgba(233, 78, 197, 0.2)");
    p2gradientStroke3.addColorStop(0.2, "rgba(72,72,176,0.0)");
    p2gradientStroke3.addColorStop(0, "rgba(203,12,159,0)"); //purple colors

    var p2gradientStroke4 = ctxP2.createLinearGradient(0, 230, 0, 50);
    p2gradientStroke4.addColorStop(1, "rgba(245, 243, 133, 0.2)");
    p2gradientStroke4.addColorStop(0.2, "rgba(72,72,176,0.0)");
    p2gradientStroke4.addColorStop(0, "rgba(20,23,39,0)"); //purple colors

    var p2gradientStroke5 = ctxP2.createLinearGradient(0, 230, 0, 50);
    p2gradientStroke5.addColorStop(1, "rgba(240, 128, 128, 0.2)");
    p2gradientStroke5.addColorStop(0.2, "rgba(72,72,176,0.0)");
    p2gradientStroke5.addColorStop(0, "rgba(203,12,159,0)"); //purple colors

    var p2gradientStroke6 = ctx.createLinearGradient(0, 230, 0, 50);
    p2gradientStroke6.addColorStop(1, "rgba(20,23,39,0.2)");
    p2gradientStroke6.addColorStop(0.2, "rgba(72,72,176,0.0)");
    p2gradientStroke6.addColorStop(0, "rgba(20,23,39,0)"); //purple colors

    var p2gradientStroke7 = ctxP2.createLinearGradient(0, 230, 0, 50);
    p2gradientStroke7.addColorStop(1, "rgba(140, 231, 247, 0.2)");
    p2gradientStroke7.addColorStop(0.2, "rgba(72,72,176,0.0)");
    p2gradientStroke7.addColorStop(0, "rgba(20,23,39,0)"); //purple colors

    $.get(p2Url, function (data) {
        // console.log(data);
        new Chart(ctxP2, {
            type: "line",
            data: {
                labels: data.labels,
                datasets: [
                    {
                        label: "Kunyit",
                        tension: 0.4,
                        borderWidth: 3,
                        pointRadius: 0,
                        borderColor: "#ec9f0fff",
                        backgroundColor: p2gradientStroke1,
                        fill: true,
                        data: data.kunyit,
                        maxBarThickness: 6,
                    },
                    {
                        label: "Ketumbar",
                        tension: 0.4,
                        borderWidth: 3,
                        pointRadius: 0,
                        borderColor: "#5ae00dff",
                        backgroundColor: p2gradientStroke2,
                        fill: true,
                        data: data.ketumbar,
                        maxBarThickness: 6,
                    },
                    {
                        label: "Bawang Putih",
                        tension: 0.4,
                        borderWidth: 3,
                        pointRadius: 0,
                        borderColor: "#cb0c9f",
                        backgroundColor: p2gradientStroke3,
                        fill: true,
                        data: data.baput,
                        maxBarThickness: 6,
                    },
                    {
                        label: "Marinasi",
                        tension: 0.4,
                        borderWidth: 3,
                        pointRadius: 0,
                        borderColor: "#fbff00ff",
                        backgroundColor: p2gradientStroke4,
                        fill: true,
                        data: data.marinasi,
                        maxBarThickness: 6,
                    },
                    {
                        label: "Pouch",
                        tension: 0.4,
                        borderWidth: 3,
                        pointRadius: 0,
                        borderColor: "#d83024ff",
                        backgroundColor: p2gradientStroke5,
                        fill: true,
                        data: data.pouch,
                        maxBarThickness: 6,
                    },
                    {
                        label: "Display",
                        tension: 0.4,
                        borderWidth: 3,
                        pointRadius: 0,
                        borderColor: "#3A416F",
                        backgroundColor: p2gradientStroke6,
                        fill: true,
                        data: data.display,
                        maxBarThickness: 6,
                    },
                    {
                        label: "Seasoning",
                        tension: 0.4,
                        borderWidth: 3,
                        pointRadius: 0,
                        borderColor: "#4ed2f3ff",
                        backgroundColor: p2gradientStroke7,
                        fill: true,
                        data: data.seasoning,
                        maxBarThickness: 6,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: "right",
                        labels: {
                            usePointStyle: true, // legend jadi bulatan kecil
                            pointStyle: "line", // tipe legend sesuai border line
                            color: "#000", // warna teks legend
                            font: {
                                size: 12,
                                family: "Open Sans",
                            },
                            generateLabels: function (chart) {
                                return chart.data.datasets.map(function (
                                    dataset,
                                    i
                                ) {
                                    return {
                                        text: dataset.label,
                                        fillStyle: dataset.borderColor, // gunakan borderColor untuk warna legend
                                        strokeStyle: dataset.borderColor,
                                        hidden: !chart.isDatasetVisible(i),
                                        datasetIndex: i,
                                    };
                                });
                            },
                        },
                    },
                },
                interaction: {
                    intersect: false,
                    mode: "index",
                },
                scales: {
                    y: {
                        grid: {
                            drawBorder: false,
                            display: true,
                            drawOnChartArea: true,
                            drawTicks: false,
                            borderDash: [5, 5],
                        },
                        ticks: {
                            display: true,
                            padding: 10,
                            color: "#b2b9bf",
                            font: {
                                size: 11,
                                family: "Open Sans",
                                style: "normal",
                                lineHeight: 2,
                            },
                        },
                    },
                    x: {
                        grid: {
                            drawBorder: false,
                            display: false,
                            drawOnChartArea: false,
                            drawTicks: false,
                            borderDash: [5, 5],
                        },
                        ticks: {
                            display: true,
                            color: "#b2b9bf",
                            padding: 20,
                            font: {
                                size: 11,
                                family: "Open Sans",
                                style: "normal",
                                lineHeight: 2,
                            },
                        },
                    },
                },
            },
        });
    });
});
