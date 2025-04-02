<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <h1>Register</h1>
    <form id="registerForm">
        {{-- @csrf --}}
        <input type="text" id="name" placeholder="Name" required>
        <input type="email" id="email" placeholder="Email" required>
        <input type="password" id="password" placeholder="Password" required>
        <button type="submit">Register</button>
    </form>

    <script>

        $.ajaxSetup({

            headers : {
                'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#registerForm').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: '/api/register',
                method: 'POST',
                data: {
                    name: $('#name').val(),
                    email: $('#email').val(),
                    password: $('#password').val()
                },
                success: function(response) {
                    console.log(response);
                    alert('Registration successful!');
                    window.location.href = '/login';
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        $('#errorMessages').html('');
                        $.each(xhr.responseJSON.errors, function(key, messages) {
                            messages.forEach(function(message) {
                                $('#errorMessages').append('<p style="color:red;">' + message + '</p>');
                            });
                        });
                    } else {
                        alert('Registration failed!');
                    }
            }
            });
        });
    </script>
<div id="errorMessages" style="color: red;"></div>

</body>
</html>
