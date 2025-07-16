<?php
$conn = new mysqli("localhost", "root", "", "travel_data");

// Fetch Domestic Visitors
$result1 = $conn->query("SELECT * FROM domestic_visitors_2011");
$labels1 = $data2010_1 = $data2011_1 = [];
while ($row = $result1->fetch_assoc()) {
    $labels1[] = $row['component'];
    $data2010_1[] = $row['expenditure_2010'];
    $data2011_1[] = $row['expenditure_2011'];
}

// Fetch Domestic Tourists
$result2 = $conn->query("SELECT * FROM domestic_tourists_2011");
$labels2 = $data2010_2 = $data2011_2 = [];
while ($row = $result2->fetch_assoc()) {
    $labels2[] = $row['component'];
    $data2010_2[] = $row['expenditure_2010'];
    $data2011_2[] = $row['expenditure_2011'];
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Travel Expenditure Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Travel Expenditure Dashboard</h1>
            <p>Comparative analysis of domestic visitors and tourists expenditure (2010 vs 2011)</p>
        </header>
        
        <section>
            <h2>Domestic Visitors Expenditure</h2>
            <div class="chart-container">
                <div class="chart-wrapper">
                    <h3 class="chart-title">Bar Chart Comparison (2010 vs 2011)</h3>
                    <canvas id="visitorsBarChart"></canvas>
                </div>
                <div class="chart-wrapper">
                    <h3 class="chart-title">2011 Expenditure Distribution</h3>
                    <canvas id="visitorsPieChart"></canvas>
                </div>
            </div>
        </section>
        
        <section>
            <h2>Domestic Tourists Expenditure</h2>
            <div class="chart-container">
                <div class="chart-wrapper">
                    <h3 class="chart-title">Bar Chart Comparison (2010 vs 2011)</h3>
                    <canvas id="touristsBarChart"></canvas>
                </div>
                <div class="chart-wrapper">
                    <h3 class="chart-title">2011 Expenditure Distribution</h3>
                    <canvas id="touristsPieChart"></canvas>
                </div>
            </div>
        </section>
        
        <footer>
            <p>© 2023 Travel Data Analytics | Data Source: National Tourism Department</p>
        </footer>
    </div>

    <script>
        const labels1 = <?php echo json_encode($labels1); ?>;
        const data2010_1 = <?php echo json_encode($data2010_1); ?>;
        const data2011_1 = <?php echo json_encode($data2011_1); ?>;

        const labels2 = <?php echo json_encode($labels2); ?>;
        const data2010_2 = <?php echo json_encode($data2010_2); ?>;
        const data2011_2 = <?php echo json_encode($data2011_2); ?>;

        // Chart configuration
        const chartOptions = {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return `${context.dataset.label || ''}: $${context.raw.toLocaleString()}`;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '$' + value.toLocaleString();
                        }
                    }
                }
            }
        };

        // Visitors - Bar Chart
        new Chart(document.getElementById("visitorsBarChart"), {
            type: 'bar',
            data: {
                labels: labels1,
                datasets: [
                    { 
                        label: '2010', 
                        data: data2010_1, 
                        backgroundColor: 'rgba(67, 97, 238, 0.7)',
                        borderColor: 'rgba(67, 97, 238, 1)',
                        borderWidth: 1
                    },
                    { 
                        label: '2011', 
                        data: data2011_1, 
                        backgroundColor: 'rgba(72, 149, 239, 0.7)',
                        borderColor: 'rgba(72, 149, 239, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                ...chartOptions,
                plugins: {
                    ...chartOptions.plugins,
                    title: { 
                        display: true, 
                        text: 'Domestic Visitors Expenditure Comparison',
                        font: {
                            size: 16
                        }
                    }
                }
            }
        });

        // Visitors - Pie Chart
        new Chart(document.getElementById("visitorsPieChart"), {
            type: 'pie',
            data: {
                labels: labels1,
                datasets: [{
                    data: data2011_1,
                    backgroundColor: [
                        '#4361ee', '#3f37c9', '#4895ef', 
                        '#4cc9f0', '#f72585', '#7209b7'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                ...chartOptions,
                plugins: {
                    ...chartOptions.plugins,
                    title: { 
                        display: true, 
                        text: '2011 Expenditure Breakdown',
                        font: {
                            size: 16
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = Math.round((value / total) * 100);
                                return `${label}: $${value.toLocaleString()} (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });

        // Tourists - Bar Chart
        new Chart(document.getElementById("touristsBarChart"), {
            type: 'bar',
            data: {
                labels: labels2,
                datasets: [
                    { 
                        label: '2010', 
                        data: data2010_2, 
                        backgroundColor: 'rgba(247, 37, 133, 0.7)',
                        borderColor: 'rgba(247, 37, 133, 1)',
                        borderWidth: 1
                    },
                    { 
                        label: '2011', 
                        data: data2011_2, 
                        backgroundColor: 'rgba(114, 9, 183, 0.7)',
                        borderColor: 'rgba(114, 9, 183, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                ...chartOptions,
                plugins: {
                    ...chartOptions.plugins,
                    title: { 
                        display: true, 
                        text: 'Domestic Tourists Expenditure Comparison',
                        font: {
                            size: 16
                        }
                    }
                }
            }
        });

        // Tourists - Pie Chart
        new Chart(document.getElementById("touristsPieChart"), {
            type: 'pie',
            data: {
                labels: labels2,
                datasets: [{
                    data: data2011_2,
                    backgroundColor: [
                        '#f72585', '#7209b7', '#3a0ca3', 
                        '#4361ee', '#4895ef', '#4cc9f0'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                ...chartOptions,
                plugins: {
                    ...chartOptions.plugins,
                    title: { 
                        display: true, 
                        text: '2011 Expenditure Breakdown',
                        font: {
                            size: 16
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = Math.round((value / total) * 100);
                                return `${label}: $${value.toLocaleString()} (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>