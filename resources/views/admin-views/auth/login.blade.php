<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Clinical Management By Keradion</title>

    <link rel="icon" href="favicon.ico">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            background: url("{{ asset(config('app.asset_path') . '/admin/img/background.jpg') }}") no-repeat center center fixed;
            background-size: cover;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #fff;
        }

        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(4px);
            z-index: -1;
        }

        .login-container {
            background: rgba(255, 255, 255, 0.6);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            border-radius: 25px;
            max-width: 420px;
            width: 100%;
            padding: 45px;
            text-align: center;
            backdrop-filter: blur(10px);
        }

        .login-container img.logo {
            height: 80px;
            margin-bottom: 30px;
        }

        .form-control {
            border-radius: 25px;
            padding: 12px 20px;
            margin-bottom: 20px;
            border: none;
            background: rgba(255, 255, 255, 0.8);
        }

        .btn-primary {
            background-color: #6C63FF;
            border: none;
            border-radius: 25px;
            padding: 12px;
            font-weight: 600;
            color: white;
            transition: background-color 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #574bdb;
        }

        .remember-me {
            font-size: 0.9rem;
            text-align: left;
            color: #333;
        }

        h3.mb-4 {
            color: #333;
            font-weight: 600;
        }
    </style>
</head>

<body>
    <div class="overlay"></div>
    <div class="login-container">
        <img class="logo" src="{{ asset(config('app.asset_path') . '/admin/img/160x160/img2.jpg') }}" alt="Logo">
        <h3 class="mb-4">{{ translate('Admin Login') }}</h3>
        <form action="{{ route('admin.auth.login') }}" method="post">
            @csrf

            <input type="email" class="form-control" name="email" placeholder="Email address" required>

            <input type="password" class="form-control" name="password" placeholder="Password" required>

            <div class="form-check remember-me mb-4">
                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                <label class="form-check-label" for="remember">
                    {{ translate('Remember Me') }}
                </label>
            </div>

            <button type="submit" class="btn btn-primary w-100">{{ translate('Sign In') }}</button>
        </form>

        @if (env('APP_MODE') == 'demo')
            <div class="mt-4">
                <small class="text-dark">Demo Login:</small><br>
                <small class="text-dark">Email: admin@admin.com</small><br>
                <small class="text-dark">Password: 12345678</small>
            </div>
        @endif

    </div>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset(config('app.asset_path') . '/admin') }}/js/toastr.js"></script>

    {!! Toastr::message() !!}

    @if ($errors->any())
        <div
            style="position: fixed; bottom: 20px; left: 20px; background-color: rgba(220, 53, 69, 0.9); padding: 15px 20px; border-radius: 10px; color: #fff; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3); max-width: 300px;">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


</body>

</html>
