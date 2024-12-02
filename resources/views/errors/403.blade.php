<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Error 403 - Access Denied</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fc;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .error-container {
            text-align: center;
            max-width: 500px;
            background-color: #fff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
        }

        .error-container h1 {
            font-size: 100px;
            font-weight: 700;
            margin: 0;
            color: #e74a3b;
        }

        .error-container p {
            font-size: 18px;
            color: #858796;
        }

        .btn-logout {
            background-color: #e74a3b;
            border: none;
            color: #fff;
        }

        .btn-logout:hover {
            background-color: #d64533;
        }

        .btn-back {
            color: #858796;
        }

        .btn-back:hover {
            color: #333;
        }

        .icon {
            font-size: 120px;
            color: #f6c23e;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="icon">🚫</div>
        <h1>403</h1>
        <p>Sorry, you don't have permission to access this page.</p>
        <div class="mt-4">
            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-logout">Logout</button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
