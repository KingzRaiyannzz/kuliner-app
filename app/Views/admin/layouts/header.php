<!DOCTYPE html>
<html>

<head>
    <title><?= $title ?? 'Admin Panel' ?></title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f5f6fa;
        }

        .container {
            display: flex;
        }

        .sidebar {
            width: 250px;
            min-height: 100vh;
            background: #2f3542;
            color: white;
            padding: 20px;
        }

        .sidebar h2 {
            margin-bottom: 20px;
        }

        .sidebar a {
            display: block;
            color: white;
            text-decoration: none;
            padding: 10px;
            margin-bottom: 5px;
            border-radius: 5px;
        }

        .sidebar a:hover {
            background: #57606f;
        }

        .content {
            flex: 1;
            padding: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        th,
        td {
            padding: 12px;
            border: 1px solid #ddd;
        }

        th {
            background: #1e90ff;
            color: white;
        }

        .btn {
            padding: 6px 10px;
            text-decoration: none;
            border-radius: 4px;
        }

        .btn-success {
            background: green;
            color: white;
        }

        .btn-danger {
            background: red;
            color: white;
        }
    </style>
</head>

<body>

    <div class="container">