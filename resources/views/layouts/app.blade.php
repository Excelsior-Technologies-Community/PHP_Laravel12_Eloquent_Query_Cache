<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Laravel 12 – Eloquent Query Cache</title>

    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            background: #f4f6f8;
        }

        .container {
            width: 900px;
            margin: 40px auto;
            background: #fff;
            padding: 25px;
            border-radius: 6px;
            box-shadow: 0 0 10px rgba(0,0,0,.08);
        }

        h1 {
            margin-bottom: 20px;
        }

        .btn {
            padding: 8px 14px;
            border-radius: 4px;
            text-decoration: none;
            font-size: 14px;
            display: inline-block;
        }

        .btn-primary {
            background: #0d6efd;
            color: #fff;
        }

        .btn-secondary {
            background: #6c757d;
            color: #fff;
        }

        .btn:hover {
            opacity: .9;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        table th {
            background: #f1f1f1;
            text-align: left;
        }

        table th, table td {
            padding: 10px;
            border: 1px solid #ddd;
        }

        .alert-success {
            background: #d1e7dd;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 4px;
            color: #0f5132;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .form-group input {
            width: 100%;
            padding: 8px;
            border-radius: 4px;
            border: 1px solid #ccc;
        }

        .error {
            color: red;
            font-size: 13px;
            margin-top: 4px;
        }

        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
    </style>
</head>
<body>

<div class="container">
    @yield('content')
</div>

</body>
</html>