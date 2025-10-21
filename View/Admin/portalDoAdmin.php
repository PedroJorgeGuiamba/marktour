<?php
session_start();
include '../../Conexao/conector.php';

$conector = new Conector();
$conn = $conector->getConexao();

// Queries for Data with Error Handling
$total_users_query = mysqli_query($conn, "SELECT COUNT(*) FROM utilizador");
$total_users = ($total_users_query) ? mysqli_fetch_row($total_users_query)[0] : 0;

$total_accommodations_query = mysqli_query($conn, "SELECT COUNT(*) FROM alojamento");
$total_accommodations = ($total_accommodations_query) ? mysqli_fetch_row($total_accommodations_query)[0] : 0;

$total_events_query = mysqli_query($conn, "SELECT COUNT(*) FROM eventos");
$total_events = ($total_events_query) ? mysqli_fetch_row($total_events_query)[0] : 0;

$total_activities_query = mysqli_query($conn, "SELECT COUNT(*) FROM actividade");
$total_activities = ($total_activities_query) ? mysqli_fetch_row($total_activities_query)[0] : 0;

$total_reservations_query = mysqli_query($conn, "SELECT COUNT(*) FROM reserva");
$total_reservations = ($total_reservations_query) ? mysqli_fetch_row($total_reservations_query)[0] : 0;

$reservations_by_type_query = mysqli_query($conn, "SELECT tipo_item, COUNT(*) as count FROM reserva_item GROUP BY tipo_item");
$reservations_by_type = [];
if ($reservations_by_type_query) {
    while ($row = mysqli_fetch_assoc($reservations_by_type_query)) {
        $reservations_by_type[$row['tipo_item']] = $row['count'];
    }
}

$months = ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'];
$monthly_reservations_query = mysqli_query($conn, "SELECT MONTH(data_reserva) as month, COUNT(*) as count FROM reserva WHERE YEAR(data_reserva) = YEAR(CURDATE()) GROUP BY MONTH(data_reserva)");
$monthly_reservations = array_fill_keys($months, 0);
if ($monthly_reservations_query) {
    while ($row = mysqli_fetch_assoc($monthly_reservations_query)) {
        $monthly_reservations[$months[$row['month'] - 1]] = $row['count'];
    }
}

// For registrations over time - get unique months
$labels_query = mysqli_query($conn, "SELECT DISTINCT DATE_FORMAT(data, '%Y-%m') as month FROM (SELECT data_registo as data FROM empresa e JOIN alojamento a ON e.id_empresa = a.id_empresa UNION SELECT data_criacao as data FROM eventos UNION SELECT data_hora as data FROM actividade) combined ORDER BY month");
$labels = [];
if ($labels_query) {
    while ($row = mysqli_fetch_assoc($labels_query)) {
        $labels[] = $row['month'];
    }
}

// Alojamentos over time (using empresa.data_registo via join)
$aloj_query = mysqli_query($conn, "SELECT DATE_FORMAT(e.data_registo, '%Y-%m') as month, COUNT(*) as count FROM alojamento a JOIN empresa e ON a.id_empresa = e.id_empresa GROUP BY month ORDER BY month");
$aloj_map = [];
if ($aloj_query) {
    while ($row = mysqli_fetch_assoc($aloj_query)) {
        $aloj_map[$row['month']] = $row['count'];
    }
}
$aloj_data = [];
foreach ($labels as $month) {
    $aloj_data[] = isset($aloj_map[$month]) ? $aloj_map[$month] : 0;
}

// Eventos over time
$eventos_query = mysqli_query($conn, "SELECT DATE_FORMAT(data_criacao, '%Y-%m') as month, COUNT(*) as count FROM eventos GROUP BY month ORDER BY month");
$eventos_map = [];
if ($eventos_query) {
    while ($row = mysqli_fetch_assoc($eventos_query)) {
        $eventos_map[$row['month']] = $row['count'];
    }
}
$eventos_data = [];
foreach ($labels as $month) {
    $eventos_data[] = isset($eventos_map[$month]) ? $eventos_map[$month] : 0;
}

// Atividades over time
$ativ_query = mysqli_query($conn, "SELECT DATE_FORMAT(data_hora, '%Y-%m') as month, COUNT(*) as count FROM actividade GROUP BY month ORDER BY month");
$ativ_map = [];
if ($ativ_query) {
    while ($row = mysqli_fetch_assoc($ativ_query)) {
        $ativ_map[$row['month']] = $row['count'];
    }
}
$ativ_data = [];
foreach ($labels as $month) {
    $ativ_data[] = isset($ativ_map[$month]) ? $ativ_map[$month] : 0;
}

$total_faqs_query = mysqli_query($conn, "SELECT COUNT(*) FROM faq");
$total_faqs = ($total_faqs_query) ? mysqli_fetch_row($total_faqs_query)[0] : 0;
?>

<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Marktour</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">

    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Custom CSS for Innovation and Beauty -->
    <style>
        :root {
            --primary-color: #0d6efd;
            --secondary-color: #6c757d;
            --success-color: #198754;
            --info-color: #0dcaf0;
            --warning-color: #ffc107;
            --danger-color: #dc3545;
            --bg-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --card-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            --border-radius: 15px;
        }

        body {
            background: linear-gradient(to bottom, #f8f9fa, #e9ecef);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .dashboard-header {
            background: var(--bg-gradient);
            color: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
            box-shadow: var(--card-shadow);
        }

        .stat-card {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--card-shadow);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            overflow: hidden;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
            margin-bottom: 1rem;
        }

        .stat-card.users .stat-icon {
            background: var(--info-color);
        }

        .stat-card.accommodations .stat-icon {
            background: var(--success-color);
        }

        .stat-card.events .stat-icon {
            background: var(--warning-color);
        }

        .stat-card.activities .stat-icon {
            background: var(--primary-color);
        }

        .stat-card.reservations .stat-icon {
            background: var(--danger-color);
        }

        .stat-card.faqs .stat-icon {
            background: var(--secondary-color);
        }

        .chart-container {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--card-shadow);
            padding: 2rem;
            margin-bottom: 2rem;
        }

        .chart-title {
            text-align: center;
            margin-bottom: 1.5rem;
            font-weight: bold;
            color: #333;
        }

        footer {
            background: var(--bg-gradient);
            color: white;
            padding: 1rem 0;
            margin-top: 3rem;
        }

        .pulse {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }

            100% {
                transform: scale(1);
            }
        }

        .nav-link {
            color: var(--text-light) !important;
            transition: var(--transition);
        }

        .nav-link:hover {
            color: var(--secondary-color) !important;
        }

        .dropdown-menu {
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-medium);
        }
    </style>
</head>

<body>
    <!-- Header with Navigation -->
    <header>
        <nav class="navbar navbar-expand-lg bg-body-tertiary">
            <div class="container-fluid">
                <img src="http://marktour.co.mz/wp-content/uploads/2022/04/Logo-Marktour-PNG-SEM-FUNDO1.png.webp" alt="Marktour Logo" style="height: 40px;">
                <div class="nav-modal ms-auto">
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarText">
                        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                            <li class="nav-item"><a class="nav-link" href="https://www.instagram.com/marktourreservasonline/"><i class="fab fa-instagram"></i> Instagram</a></li>
                            <li class="nav-item"><a class="nav-link" href="https://web.facebook.com/marktour.ei?_rdc=1&_rdr#"><i class="fab fa-facebook"></i> Facebook</a></li>
                            <li class="nav-item"><a href="../../Controller/Auth/LogoutController.php" class="btn btn-danger">Logout</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>

        <nav class="navbar navbar-expand-lg" style="background: rgba(255,255,255,0.9);">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item"><a class="nav-link active" href="#">Dashboard</a></li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Acomodações</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">Hotéis</a></li>
                        <li><a class="dropdown-item" href="#">Resorts</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Passeios</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">A Pé</a></li>
                    </ul>
                </li>
                <li class="nav-item"><a class="nav-link" href="#">Eventos</a></li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">MarkTour</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="../MarkTour/Sobre.php">Sobre</a></li>
                        <li><a class="dropdown-item" href="AdminFaqs.php">Faqs - Admin</a></li>
                    </ul>
                </li>
            </ul>
        </nav>
    </header>

    <!-- Dashboard Header -->
    <section class="dashboard-header text-center">
        <div class="container">
            <h1 class="display-4 fw-bold"><i class="fas fa-chart-line me-3"></i>Painel Administrativo</h1>
            <p class="lead">Relatórios e Análises em Tempo Real - Dados Fáceis de Interpretar</p>
        </div>
    </section>

    <main class="container-fluid">
        <!-- Statistics Cards Row -->
        <section class="row g-4 mb-5">
            <div class="col-lg-2 col-md-3 col-sm-6">
                <div class="stat-card users h-100 text-center p-4">
                    <div class="stat-icon"><i class="fas fa-users"></i></div>
                    <h3 class="text-primary"><?php echo $total_users; ?></h3>
                    <p class="mb-0">Utilizadores Registrados</p>
                </div>
            </div>
            <div class="col-lg-2 col-md-3 col-sm-6">
                <div class="stat-card accommodations h-100 text-center p-4">
                    <div class="stat-icon"><i class="fas fa-bed"></i></div>
                    <h3 class="text-success"><?php echo $total_accommodations; ?></h3>
                    <p class="mb-0">Alojamentos</p>
                </div>
            </div>
            <div class="col-lg-2 col-md-3 col-sm-6">
                <div class="stat-card events h-100 text-center p-4">
                    <div class="stat-icon"><i class="fas fa-calendar-alt"></i></div>
                    <h3 class="text-warning"><?php echo $total_events; ?></h3>
                    <p class="mb-0">Eventos</p>
                </div>
            </div>
            <div class="col-lg-2 col-md-3 col-sm-6">
                <div class="stat-card activities h-100 text-center p-4">
                    <div class="stat-icon"><i class="fas fa-running"></i></div>
                    <h3 class="text-primary"><?php echo $total_activities; ?></h3>
                    <p class="mb-0">Atividades</p>
                </div>
            </div>
            <div class="col-lg-2 col-md-3 col-sm-6">
                <div class="stat-card reservations h-100 text-center p-4 pulse">
                    <div class="stat-icon"><i class="fas fa-shopping-cart"></i></div>
                    <h3 class="text-danger"><?php echo $total_reservations; ?></h3>
                    <p class="mb-0">Reservas Totais</p>
                </div>
            </div>
            <div class="col-lg-2 col-md-3 col-sm-6">
                <div class="stat-card faqs h-100 text-center p-4">
                    <div class="stat-icon"><i class="fas fa-question-circle"></i></div>
                    <h3 class="text-secondary"><?php echo $total_faqs; ?></h3>
                    <p class="mb-0">FAQs</p>
                </div>
            </div>
        </section>

        <!-- Charts Section -->
        <section class="row g-4">
            <!-- Pie Chart: Reservas por Tipo -->
            <div class="col-lg-6">
                <div class="chart-container">
                    <h4 class="chart-title"><i class="fas fa-chart-pie me-2"></i>Distribuição de Itens Reservados por Tipo</h4>
                    <canvas id="pieChart" height="300"></canvas>
                </div>
            </div>

            <!-- Bar Chart: Reservas Mensais -->
            <div class="col-lg-6">
                <div class="chart-container">
                    <h4 class="chart-title"><i class="fas fa-chart-bar me-2"></i>Reservas por Mês (2025)</h4>
                    <canvas id="barChart" height="300"></canvas>
                </div>
            </div>

            <!-- Line Chart: Registros ao Longo do Tempo -->
            <div class="col-12">
                <div class="chart-container">
                    <h4 class="chart-title"><i class="fas fa-chart-line me-2"></i>Crescimento de Registros (Alojamentos, Eventos e Atividades)</h4>
                    <canvas id="lineChart" height="300"></canvas>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="text-center">
        <div class="container">
            <p>Copyright 2025 © Marktour | Todos Direitos Reservados <span class="fw-bold">MARKTOUR.</span></p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Chart.js Scripts -->
    <script>
        // Pie Chart: Reservas por Tipo
        const ctxPie = document.getElementById('pieChart').getContext('2d');
        new Chart(ctxPie, {
            type: 'pie',
            data: {
                labels: <?php echo json_encode(array_keys($reservations_by_type)); ?>,
                datasets: [{
                    data: <?php echo json_encode(array_values($reservations_by_type)); ?>,
                    backgroundColor: ['#0dcaf0', '#198754', '#ffc107'],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: (ctx) => ctx.label + ': ' + ctx.parsed + ' (' + ((ctx.parsed / <?php echo array_sum($reservations_by_type) ?: 1; ?> * 100).toFixed(1)) + '%)'
                        }
                    }
                },
                animation: {
                    duration: 2000,
                    easing: 'easeInOutQuart'
                }
            }
        });

        // Bar Chart: Reservas Mensais
        const ctxBar = document.getElementById('barChart').getContext('2d');
        new Chart(ctxBar, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode(array_keys($monthly_reservations)); ?>,
                datasets: [{
                    label: 'Número de Reservas',
                    data: <?php echo json_encode(array_values($monthly_reservations)); ?>,
                    backgroundColor: 'rgba(13, 202, 240, 0.6)',
                    borderColor: 'rgba(13, 202, 240, 1)',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 10
                        }
                    }
                },
                animation: {
                    duration: 1500,
                    easing: 'easeOutBounce'
                }
            }
        });

        // Line Chart: Registros ao Longo do Tempo
        const ctxLine = document.getElementById('lineChart').getContext('2d');
        new Chart(ctxLine, {
            type: 'line',
            data: {
                labels: <?php echo json_encode($labels); ?>,
                datasets: [{
                        label: 'Alojamentos',
                        data: <?php echo json_encode($aloj_data); ?>,
                        borderColor: '#198754',
                        backgroundColor: 'rgba(25, 135, 84, 0.1)',
                        tension: 0.4
                    },
                    {
                        label: 'Eventos',
                        data: <?php echo json_encode($eventos_data); ?>,
                        borderColor: '#ffc107',
                        backgroundColor: 'rgba(255, 193, 7, 0.1)',
                        tension: 0.4
                    },
                    {
                        label: 'Atividades',
                        data: <?php echo json_encode($ativ_data); ?>,
                        borderColor: '#0d6efd',
                        backgroundColor: 'rgba(13, 110, 253, 0.1)',
                        tension: 0.4
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    }
                },
                animation: {
                    duration: 2000,
                    easing: 'easeInOutQuart'
                }
            }
        });
    </script>
</body>

</html>