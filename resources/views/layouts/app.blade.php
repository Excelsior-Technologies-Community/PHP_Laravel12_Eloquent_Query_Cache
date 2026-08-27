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
                    #f8fafc,
                    #eef2f7,
                    #f1f5f9);

            min-height: 100vh;

            color: #0f172a;
        }

        /* Scrollbar */

        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-thumb {

            background: #cbd5e1;

            border-radius: 20px;
        }

        /* Main Wrapper */

        .main-wrapper {

            background: #ffffff;

            border:
                1px solid #e2e8f0;

            border-radius: 28px;

            padding: 35px;

            box-shadow:
                0 10px 40px rgba(15, 23, 42, 0.08);
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

            color: #fff;

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

            color: #0f172a;
        }

        .logo-box p {

            margin: 0;

            color: #64748b;
        }

        /* Generic cards */

        .card {
            color: #0f172a;
        }

        .dashboard-card {

            background: #f8fafc;

            border:
                1px solid #e2e8f0;

            border-radius: 24px;

            padding: 25px;

            transition: 0.3s;
        }

        .dashboard-card:hover {

            transform: translateY(-5px);

            box-shadow:
                0 12px 30px rgba(15, 23, 42, 0.10);
        }

        .dashboard-card h5 {

            color: #64748b;
        }

        .dashboard-card h2 {

            font-size: 35px;

            font-weight: 700;

            margin-top: 10px;

            color: #0f172a;
        }

        /* Search / filter box */

        .search-box {

            background: #f8fafc;

            border:
                1px solid #e2e8f0;

            border-radius: 18px;

            padding: 8px;
        }

        .form-control {
            background: #fff;
            border: 1px solid #cbd5e1;
            color: #0f172a;
            height: 52px;
            border-radius: 12px;
            padding: 10px 14px;
        }

        .form-control:focus {
            background: #fff;
            border: 1px solid #3b82f6;
            color: #0f172a;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.20);
        }

        label {
            color: #334155;
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

            color: #fff;
        }

        .btn-info {
            background:
                linear-gradient(135deg,
                    #06b6d4,
                    #0891b2);

            color: #fff;
        }

        /* Table (light) */

        .table {

            color: #1e293b;
        }

        .table thead th {
            background: #f1f5f9;
            color: #475569;
            border-bottom: 2px solid #e2e8f0;
            font-weight: 600;
        }

        .table td,
        .table th {
            border-color: #eef2f7;
            vertical-align: middle;
        }

        .table-hover tbody tr:hover {
            background: #f8fafc;
        }

        /* Pagination */

        .pagination .page-link {

            background: #fff;

            border: 1px solid #e2e8f0;

            color: #334155;

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

            border-color: transparent;

            color: #fff;
        }

        .pagination .page-link:hover {

            background: #eff6ff;

            color: #1d4ed8;
        }

        /* Alert */

        .alert-success {

            background:
                rgba(16, 185, 129, 0.12);

            border:
                1px solid rgba(16, 185, 129, 0.30);

            color: #047857;

            padding: 16px 20px;

            border-radius: 16px;
        }

        .alert-info {

            background: rgba(14, 165, 233, 0.10);

            border: 1px solid rgba(14, 165, 233, 0.25);

            color: #075985;
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

        /* Toast notifications */

        #toast-container {

            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .toast-alert {

            min-width: 260px;
            padding: 14px 18px;
            border-radius: 14px;
            color: #fff;
            font-weight: 500;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.20);
            opacity: 0;
            transform: translateX(40px);
            transition: 0.4s;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .toast-alert.show {
            opacity: 1;
            transform: translateX(0);
        }

        .toast-alert.success {
            background: linear-gradient(135deg, #16a34a, #15803d);
        }

        .toast-alert.error {
            background: linear-gradient(135deg, #ef4444, #dc2626);
        }

        .toast-alert.info {
            background: linear-gradient(135deg, #0ea5e9, #0284c7);
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

    <div id="toast-container"></div>

    <script>
        // ---------- Toast notifications ----------
        function showToast(message, type = 'success') {
            const container = document.getElementById('toast-container');
            const el = document.createElement('div');
            el.className = `toast-alert ${type}`;
            el.innerHTML = `<i class="bi bi-check-circle"></i><span>${message}</span>`;
            container.appendChild(el);

            requestAnimationFrame(() => el.classList.add('show'));

            setTimeout(() => {
                el.classList.remove('show');
                setTimeout(() => el.remove(), 400);
            }, 3500);
        }

        @if(session('success'))
            showToast(@json(session('success')), 'success');
        @endif

        @if(session('error'))
            showToast(@json(session('error')), 'error');
        @endif
    </script>

    @stack('scripts')

</body>

</html>
