(function ($) {
    'use strict';
    $(function () {
        var infoColor = "#2b8ae3";
        var primaryColor = "#b76cf8";
        var secondaryColor = "#c3bdbd";
        var successColor = "#5bcfb5";
        var dangerColor = "#ef7a95";
        var warningColor = "#fbd84a"
        if ($("#visit-sale-chart").length) {
            const ctx = document.getElementById('visit-sale-chart');
        
            var graphGradient1 = document.getElementById('visit-sale-chart').getContext("2d");
            var graphGradient2 = document.getElementById('visit-sale-chart').getContext("2d");
            var graphGradient3 = document.getElementById('visit-sale-chart').getContext("2d");
        
            var gradientStrokeViolet = graphGradient1.createLinearGradient(0, 0, 0, 181);
            gradientStrokeViolet.addColorStop(0, 'rgba(218, 140, 255, 1)');
            gradientStrokeViolet.addColorStop(1, 'rgba(154, 85, 255, 1)');
            var gradientLegendViolet = 'linear-gradient(to right, rgba(218, 140, 255, 1), rgba(154, 85, 255, 1))';
            
            var gradientStrokeBlue = graphGradient2.createLinearGradient(0, 0, 0, 360);
            gradientStrokeBlue.addColorStop(0, 'rgba(54, 215, 232, 1)');
            gradientStrokeBlue.addColorStop(1, 'rgba(177, 148, 250, 1)');
            var gradientLegendBlue = 'linear-gradient(to right, rgba(54, 215, 232, 1), rgba(177, 148, 250, 1))';
        
            var gradientStrokeRed = graphGradient3.createLinearGradient(0, 0, 0, 300);
            gradientStrokeRed.addColorStop(0, 'rgba(255, 191, 150, 1)');
            gradientStrokeRed.addColorStop(1, 'rgba(254, 112, 150, 1)');
            var gradientLegendRed = 'linear-gradient(to right, rgba(255, 191, 150, 1), rgba(254, 112, 150, 1))';
            const bgColor1 = ["rgba(218, 140, 255, 1)"];
            const bgColor2 = ["rgba(54, 215, 232, 1"];
            const bgColor3 = ["rgba(255, 191, 150, 1)"];
        
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['JAN', 'FEB', 'MAR', 'APR', 'MAY', 'JUN', 'JUL', 'AUG'],
                    datasets: [{
                            label: "CHN",
                            borderColor: gradientStrokeViolet,
                            backgroundColor: gradientStrokeViolet,
                            fillColor:bgColor1,
                            hoverBackgroundColor: gradientStrokeViolet,
                            pointRadius: 0,
                            fill: false,
                            borderWidth: 1,
                            fill: 'origin',
                            data: [20, 40, 15, 35, 25, 50, 30, 20],
                            barPercentage: 0.5,
                            categoryPercentage: 0.5,
                        },
                        {
                            label: "USA",
                            borderColor: gradientStrokeRed,
                            backgroundColor: gradientStrokeRed,
                            hoverBackgroundColor: gradientStrokeRed,
                            fillColor:bgColor2,
                            pointRadius: 0,
                            fill: false,
                            borderWidth: 1,
                            fill: 'origin',
                            data: [40, 30, 20, 10, 50, 15, 35, 40],
                            barPercentage: 0.5,
                            categoryPercentage: 0.5,
                        },
                        {
                            label: "UK",
                            borderColor: gradientStrokeBlue,
                            backgroundColor: gradientStrokeBlue,
                            hoverBackgroundColor: gradientStrokeBlue,
                            fillColor:bgColor3,
                            pointRadius: 0,
                            fill: false,
                            borderWidth: 1,
                            fill: 'origin',
                            data: [70, 10, 30, 40, 25, 50, 15, 30],
                            barPercentage: 0.5,
                            categoryPercentage: 0.5,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    elements: {
                        line: {
                            tension: 0.4,
                        },
                    },
                    scales: {
                    y: {
                        display:false,
                        grid: {
                        display: true,
                        drawOnChartArea: true,
                        drawTicks: false,
                        },
                    },
                    x: {
                        display:true,
                        grid: {
                        display: false,
                        },
                    }
                    },
                    plugins: {
                        legend: {
                            display: false,
                        }
                    }
                },
                plugins: [{
                    afterDatasetUpdate: function(chart, args, options) {
                        const chartId = chart.canvas.id;
                        var i;
                        const legendId = `${chartId}-legend`;
                        const ul = document.createElement('ul');
                        for (i = 0; i < chart.data.datasets.length; i++) {
                            ul.innerHTML += `
                    <li>
                    <span style="background-color: ${chart.data.datasets[i].fillColor}"></span>
                    ${chart.data.datasets[i].label}
                    </li>
                `;
                        }
                        // alert(chart.data.datasets[0].backgroundColor);
                        return document.getElementById(legendId).appendChild(ul);
                    }
                }]
            });
        }
        
        if ($("#traffic-chart").length) { 
        const ctx = document.getElementById('traffic-chart');
    
        var graphGradient1 = document.getElementById("traffic-chart").getContext('2d');
        var graphGradient2 = document.getElementById("traffic-chart").getContext('2d');
        var graphGradient3 = document.getElementById("traffic-chart").getContext('2d');
    
        var gradientStrokeBlue = graphGradient1.createLinearGradient(0, 0, 0, 181);
        gradientStrokeBlue.addColorStop(0, 'rgba(54, 215, 232, 1)');
        gradientStrokeBlue.addColorStop(1, 'rgba(177, 148, 250, 1)');
        var gradientLegendBlue = 'rgba(54, 215, 232, 1)';
    
        var gradientStrokeRed = graphGradient2.createLinearGradient(0, 0, 0, 50);
        gradientStrokeRed.addColorStop(0, 'rgba(255, 191, 150, 1)');
        gradientStrokeRed.addColorStop(1, 'rgba(254, 112, 150, 1)');
        var gradientLegendRed = 'rgba(255, 191, 150, 1';
    
        var gradientStrokeGreen = graphGradient3.createLinearGradient(0, 0, 0, 300);
        gradientStrokeGreen.addColorStop(0, 'rgba(6, 185, 157, 1)');
        gradientStrokeGreen.addColorStop(1, 'rgba(132, 217, 210, 1)');
        var gradientLegendGreen = 'rgba(6, 185, 157, 1)';
    
        // const bgColor1 = ["rgba(54, 215, 232, 1)"];
        // const bgColor2 = ["rgba(255, 191, 150, 1"];
        // const bgColor3 = ["rgba(6, 185, 157, 1)"];
    
        new Chart(ctx, {
            type: 'doughnut',
            data: {
            labels: ['Search Engines 30%','Direct Click 30%','Bookmarks Click40%'],
            datasets: [{
                data: [30, 30, 40],
                backgroundColor: [gradientStrokeBlue,gradientStrokeGreen,gradientStrokeRed],
                hoverBackgroundColor: [
                gradientStrokeBlue,
                gradientStrokeGreen,
                gradientStrokeRed
                ],
                borderColor: [
                gradientStrokeBlue,
                gradientStrokeGreen,
                gradientStrokeRed
                ],
                legendColor: [
                gradientLegendBlue,
                gradientLegendGreen,
                gradientLegendRed
                ]
            }]
            },
            options: {
            cutout: 50,
            animationEasing: "easeOutBounce",
            animateRotate: true,
            animateScale: false,
            responsive: true,
            maintainAspectRatio: true,
            showScale: true,
            legend: false,
            plugins: {
                legend: {
                    display: false,
                }
            }
            },
            plugins: [{
            afterDatasetUpdate: function (chart, args, options) {
                const chartId = chart.canvas.id;
                var i;
                const legendId = `${chartId}-legend`;
                const ul = document.createElement('ul');
                for(i=0;i<chart.data.datasets[0].data.length; i++) {
                    ul.innerHTML += `
                    <li>
                        <span style="background-color: ${chart.data.datasets[0].legendColor[i]}"></span>
                        ${chart.data.labels[i]}
                    </li>
                    `;
                }
                return document.getElementById(legendId).appendChild(ul);
                }
            }]
        });
        }

        if ($("#statistics-graph-1").length) {
            const ctx = document.getElementById('statistics-graph-1');
            var lineChartCanvas = document.getElementById('statistics-graph-1').getContext("2d");
            var gradientStrokeFill_1 = lineChartCanvas.createLinearGradient(1, 2, 1, 400);
            gradientStrokeFill_1.addColorStop(0, 'rgba(218, 140, 255, 1)');
            gradientStrokeFill_1.addColorStop(1, infoColor);
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ["Day 1", "Day 2", "Day 3", "Day 4", "Day 5", "Day 6", "Day 7"],
                  datasets: [{
                      label: 'Profit',
                      data: [3, 9, 7, 5, 7, 2, 8],
                      borderColor: infoColor,
                      backgroundColor: gradientStrokeFill_1,
                      borderWidth: 2,
                      fill: true,
                      pointRadius:0,
                  }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    elements: {
                        line: {
                            tension: 0,
                        },
                    },
                    scales: {
                    y: {
                        display:false,
                    },
                    x: {
                        display:false,
                    }
                    },
                    plugins: {
                        legend: {
                            display: false,
                        }
                    }
                },
            });
        }

        if ($("#statistics-graph-2").length) {
            const ctx = document.getElementById('statistics-graph-2');
            var lineChartCanvas = document.getElementById('statistics-graph-2').getContext("2d");
            var gradientStrokeFill_1 = lineChartCanvas.createLinearGradient(1, 2, 1, 400);
            gradientStrokeFill_1.addColorStop(0, '#fff');
            gradientStrokeFill_1.addColorStop(1, primaryColor);
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ["Day 1", "Day 2", "Day 3", "Day 4", "Day 5", "Day 6", "Day 7"],
                  datasets: [{
                      label: 'Profit',
                      data: [7, 9, 2, 2, 8, 7, 9],
                      borderColor: primaryColor,
                      backgroundColor: gradientStrokeFill_1,
                      borderWidth: 2,
                      fill: true,
                      pointRadius:0,
                  }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    elements: {
                        line: {
                            tension: 0,
                        },
                    },
                    scales: {
                    y: {
                        display:false,
                    },
                    x: {
                        display:false,
                    }
                    },
                    plugins: {
                        legend: {
                            display: false,
                        }
                    }
                },
            });
        }

        if ($("#statistics-graph-3").length) {
            const ctx = document.getElementById('statistics-graph-3');
            var lineChartCanvas = document.getElementById('statistics-graph-3').getContext("2d");
            var gradientStrokeFill_1 = lineChartCanvas.createLinearGradient(1, 2, 1, 400);
            gradientStrokeFill_1.addColorStop(0, '#fff');
            gradientStrokeFill_1.addColorStop(1, warningColor);
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ["Day 1", "Day 2", "Day 3", "Day 4", "Day 5", "Day 6", "Day 7"],
                    datasets: [{
                        label: 'Profit',
                        data: [5, 4, 7, 2, 9, 2, 8],
                        borderColor: warningColor,
                        backgroundColor: gradientStrokeFill_1,
                        borderWidth: 2,
                        fill: true,
                        pointRadius:0,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    elements: {
                        line: {
                            tension: 0,
                        },
                    },
                    scales: {
                    y: {
                        display:false,
                    },
                    x: {
                        display:false,
                    }
                    },
                    plugins: {
                        legend: {
                            display: false,
                        }
                    }
                },
            });
        }

        if ($("#statistics-graph-4").length) {
            const ctx = document.getElementById('statistics-graph-4');
            var lineChartCanvas = document.getElementById('statistics-graph-4').getContext("2d");
            var gradientStrokeFill_1 = lineChartCanvas.createLinearGradient(1, 2, 1, 400);
            gradientStrokeFill_1.addColorStop(0, '#fff');
            gradientStrokeFill_1.addColorStop(1, dangerColor);
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ["Day 1", "Day 2", "Day 3", "Day 4", "Day 5", "Day 6", "Day 7"],
                    datasets: [{
                        label: 'Profit',
                        data: [5, 2, 5, 2, 4, 4, 1],
                        borderColor: dangerColor,
                        backgroundColor: gradientStrokeFill_1,
                        borderWidth: 2,
                        fill: true,
                        pointRadius:0,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    elements: {
                        line: {
                            tension: 0,
                        },
                    },
                    scales: {
                    y: {
                        display:false,
                    },
                    x: {
                        display:false,
                    }
                    },
                    plugins: {
                        legend: {
                            display: false,
                        }
                    }
                },
            });
        }

        if ($("#UsersDoughnutChart").length) { 
            const ctx = document.getElementById('UsersDoughnutChart');
        
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                labels: ['Search Engines 30%','Direct Click 30%','Bookmarks Click40%'],
                datasets: [{
                    data: [80, 34, 100],
                    backgroundColor: [
                        successColor,
                        infoColor,
                        secondaryColor
                    ],
                    borderColor: [
                        successColor,
                        infoColor,
                        secondaryColor
                    ],
                    labels: [
                        'Request',
                        'Email',
                        'Draft'
                    ]
                }]
                },
                options: {
                cutout: 50,
                animationEasing: "easeOutBounce",
                animateRotate: true,
                animateScale: false,
                responsive: true,
                maintainAspectRatio: true,
                showScale: true,
                legend: false,
                plugins: {
                    legend: {
                        display: false,
                    }
                }
                },
            });
        }
        
        if ($("#conversionBarChart").length) {
            const ctx = document.getElementById('conversionBarChart');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ["Day 1", "Day 2", "Day 3", "Day 4", "Day 5", "Day 6", "Day 7", "Day 8", "Day 9", "Day 10"],
                    datasets: [{
                        label: 'Amount Due',
                        data: [39, 19, 25, 16, 31, 39, 12, 18, 33, 24],
                        backgroundColor: primaryColor,
                        borderColor: primaryColor,
                        borderWidth: 0,
                        barPercentage: 0.4,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    elements: {
                        line: {
                            tension: 0,
                        },
                    },
                    scales: {
                    y: {
                        display:false,
                    },
                    x: {
                        display:false,
                    }
                    },
                    plugins: {
                        legend: {
                            display: false,
                        }
                    }
                },
            });
        }

        if ($("#trafficSourceDoughnutChart").length) { 
            const ctx = document.getElementById('trafficSourceDoughnutChart');
        
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    datasets: [{
                        data: [185, 85, 15],
                        backgroundColor: [
                            secondaryColor,
                            successColor,
                            dangerColor,

                        ],
                        borderColor: [
                            secondaryColor,
                            successColor,
                            dangerColor,

                        ],
                    }],
                    labels: [
                        'Human Resources',
                        'Manger',
                        'Other'
                    ]
                },
                options: {
                    cutout: 50,
                    animationEasing: "easeOutBounce",
                    animateRotate: true,
                    animateScale: false,
                    responsive: true,
                    maintainAspectRatio: true,
                    showScale: true,
                    legend: false,
                    plugins: {
                        legend: {
                            display: false,
                        }
                    }
                },
            });
        }
        if ($('#review-rating-1').length) {
            $('#review-rating-1').barrating({
                theme: 'css-stars',
                showSelectedRating: false,
                initialRating: 4
            });
        }
        if ($('#review-rating-2').length) {
            $('#review-rating-2').barrating({
                theme: 'css-stars',
                showSelectedRating: false,
                initialRating: 5
            });
        }
        if ($('#review-rating-3').length) {
            $('#review-rating-3').barrating({
                theme: 'css-stars',
                showSelectedRating: false,
                initialRating: 3
            });
        }
        if ($('#review-rating-4').length) {
            $('#review-rating-4').barrating({
                theme: 'css-stars',
                showSelectedRating: false,
                initialRating: 4
            });
        }
        if ($('#review-rating-5').length) {
            $('#review-rating-5').barrating({
                theme: 'css-stars',
                showSelectedRating: false,
                initialRating: 2
            });
        }

    });
})(jQuery)