<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <h1>Login</h1>
    <form id="loginForm">
        <input type="email" id="email" placeholder="Email" required>
        <input type="password" id="password" placeholder="Password" required>
        <button type="submit">Login</button>
    </form>

    <script>


$.ajaxSetup({

headers : {
    'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')
}
});

        $('#loginForm').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: '/api/login',
                method: 'POST',
                data: {
                    email: $('#email').val(),
                    password: $('#password').val()
                },
                success: function(response) {
                    localStorage.setItem('token', response.token);
                    window.location.href = '/users';
                },
                error: function() {
                    alert('Login failed!');
                }
            });
        });
    </script>
</body>
</html>
