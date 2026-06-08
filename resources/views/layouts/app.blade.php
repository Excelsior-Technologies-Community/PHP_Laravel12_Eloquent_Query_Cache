<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Laravel Query Cache</title>

    <!-- Bootstrap -->

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <!-- Bootstrap Icons -->

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
        rel="stylesheet">

    <!-- Google Font -->

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {

            font-family: 'Poppins', sans-serif;

            background:
                linear-gradient(135deg,
                    #0f172a,
                    #111827,
                    #1e293b);

            min-height: 100vh;

            color: white;
        }

        /* Scrollbar */

        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-thumb {

            background: #475569;

            border-radius: 20px;
        }

        /* Main Wrapper */

        .main-wrapper {

            background:
                rgba(255, 255, 255, 0.04);

            border:
                1px solid rgba(255, 255, 255, 0.08);

            border-radius: 28px;

            padding: 35px;

            backdrop-filter: blur(12px);

            box-shadow:
                0 8px 40px rgba(0, 0, 0, 0.35);
        }

        /* Navbar */

        .top-navbar {

            display: flex;

            justify-content: space-between;

            align-items: center;

            margin-bottom: 30px;
        }

        .logo-box {

            display: flex;

            align-items: center;

            gap: 15px;
        }

        .logo-icon {

            width: 60px;
            height: 60px;

            border-radius: 18px;

            display: flex;

            align-items: center;

            justify-content: center;

            font-size: 25px;

            background:
                linear-gradient(135deg,
                    #2563eb,
                    #4f46e5);

            box-shadow:
                0 10px 25px rgba(59, 130, 246, 0.35);
        }

        .logo-box h2 {

            margin: 0;

            font-size: 28px;

            font-weight: 700;
        }

        .logo-box p {

            margin: 0;

            color: #94a3b8;
        }

        /* Dashboard Cards */

        .dashboard-card {

            background:
                rgba(255, 255, 255, 0.05);

            border:
                1px solid rgba(255, 255, 255, 0.08);

            border-radius: 24px;

            padding: 25px;

            transition: 0.3s;
        }

        .dashboard-card:hover {

            transform: translateY(-5px);

            box-shadow:
                0 12px 30px rgba(0, 0, 0, 0.30);
        }

        .dashboard-card h5 {

            color: #94a3b8;
        }

        .dashboard-card h2 {

            font-size: 35px;

            font-weight: 700;

            margin-top: 10px;
        }

        /* Search */

        .search-box {

            background:
                rgba(255, 255, 255, 0.04);

            border:
                1px solid rgba(255, 255, 255, 0.08);

            border-radius: 18px;

            padding: 8px;
        }

        .form-control {
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.15);
            color: white;
            height: 52px;
            border-radius: 12px;
            padding: 10px 14px;
        }

        .form-control:focus {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid #3b82f6;
            color: white;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.25);
        }

        label {
            color: #cbd5e1;
            font-weight: 500;
            margin-bottom: 6px;
        }

        .form-control::placeholder {

            color: #94a3b8;
        }

        /* Buttons */

        .btn {

            border: none;

            border-radius: 14px;

            font-weight: 500;

            padding: 10px 18px;

            transition: 0.3s;
        }

        .btn:hover {

            transform: translateY(-2px);
        }

        .btn-primary {

            background:
                linear-gradient(135deg,
                    #3b82f6,
                    #6366f1);
        }

        .btn-danger {

            background:
                linear-gradient(135deg,
                    #ef4444,
                    #dc2626);
        }

        .btn-warning {

            background:
                linear-gradient(135deg,
                    #f59e0b,
                    #d97706);

            color: white;
        }

        .btn-info {

            background:
                linear-gradient(135deg,
                    #06b6d4,
                    #0891b2);

            color: white;
        }

        /* Table */

        .custom-table {

            width: 100%;

            border-collapse: separate;

            border-spacing: 0 12px;
        }

        .custom-table thead tr {

            background:
                linear-gradient(135deg,
                    #2563eb,
                    #4f46e5);
        }

        .custom-table thead th {

            padding: 18px;

            border: none;

            color: white;

            font-size: 15px;

            font-weight: 600;
        }

        .custom-table thead th:first-child {

            border-top-left-radius: 16px;
            border-bottom-left-radius: 16px;
        }

        .custom-table thead th:last-child {

            border-top-right-radius: 16px;
            border-bottom-right-radius: 16px;
        }

        .custom-table tbody tr {

            background:
                rgba(255, 255, 255, 0.04);

            transition: 0.3s;
        }

        .custom-table tbody tr:hover {

            transform: translateY(-3px);

            background:
                rgba(59, 130, 246, 0.10);

            box-shadow:
                0 10px 25px rgba(0, 0, 0, 0.30);
        }

        .custom-table tbody td {

            padding: 18px;

            border-top:
                1px solid rgba(255, 255, 255, 0.05);

            border-bottom:
                1px solid rgba(255, 255, 255, 0.05);

            color: #e2e8f0;
        }

        .custom-table tbody td:first-child {

            border-left:
                1px solid rgba(255, 255, 255, 0.05);

            border-top-left-radius: 16px;

            border-bottom-left-radius: 16px;
        }

        .custom-table tbody td:last-child {

            border-right:
                1px solid rgba(255, 255, 255, 0.05);

            border-top-right-radius: 16px;

            border-bottom-right-radius: 16px;
        }

        /* Product Icon */

        .product-icon {

            width: 38px;
            height: 38px;

            border-radius: 12px;

            display: flex;

            align-items: center;

            justify-content: center;

            background:
                linear-gradient(135deg,
                    #06b6d4,
                    #3b82f6);

            font-size: 16px;
        }

        /* Price Badge */

        .price-badge {

            background:
                rgba(16, 185, 129, 0.15);

            color: #6ee7b7;

            padding: 8px 14px;

            border-radius: 12px;

            font-size: 14px;

            font-weight: 600;
        }

        /* Pagination */

        .pagination .page-link {

            background:
                rgba(255, 255, 255, 0.05);

            border: none;

            color: white;

            margin: 0 5px;

            border-radius: 12px;

            min-width: 42px;

            text-align: center;
        }

        .pagination .page-item.active .page-link {

            background:
                linear-gradient(135deg,
                    #3b82f6,
                    #6366f1);
        }

        .pagination .page-link:hover {

            background: #2563eb;

            color: white;
        }

        /* Alert */

        .alert-success {

            background:
                rgba(16, 185, 129, 0.15);

            border:
                1px solid rgba(16, 185, 129, 0.30);

            color: #6ee7b7;

            padding: 16px 20px;

            border-radius: 16px;
        }

        /* Responsive */

        @media(max-width:768px) {

            .top-navbar {

                flex-direction: column;

                gap: 20px;

                text-align: center;
            }

            .main-wrapper {

                padding: 20px;
            }

        }
    </style>

</head>

<body>

    <div class="container py-5">

        <div class="main-wrapper">

            <!-- Top Navbar -->

            <div class="top-navbar">

                <div class="logo-box">

                    <div class="logo-icon">

                        <i class="bi bi-database-fill-check"></i>

                    </div>

                    <div>

                        <h2>Laravel Query Cache</h2>

                        <p>
                            Fast • Cached • Optimized Dashboard
                        </p>

                    </div>

                </div>

                <div>

                    <span class="badge bg-primary p-3 fs-6">
                        Laravel 12
                    </span>

                </div>

            </div>

            @yield('content')

        </div>

    </div>

</body>

</html>