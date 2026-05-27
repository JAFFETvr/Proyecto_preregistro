$(document).ready(function () {

    $('#login-form').on('submit', function (e) {
        e.preventDefault();

        var username = $('#username').val().trim();
        var password = $('#password').val();

        if (username === '' || password === '') {
            bootbox.alert({
                title: 'Campos requeridos',
                message: 'Por favor ingresa tu usuario y contraseña.'
            });
            return;
        }

        var $btn = $(this).find('button[type="submit"]');
        $btn.prop('disabled', true).text('Verificando...');

        $.post('controllers/signin/controller_login.php', {
            action:   1,
            username: username,
            password: password
        }, function (resp) {

            if (resp.ok) {
                // Aquí estaba el error, ahora detecta tanto 'secretaria' como 'admin'
                if (resp.rol === 'secretaria' || resp.rol === 'admin') {
                    location.href = 'v/secretaria/secretaria.php';
                } else {
                    location.href = 'v/deegreDocument/document.php';
                }
            } else {
                bootbox.alert({
                    title: 'Error de acceso',
                    message: resp.msg || 'No se pudo iniciar sesión.'
                });
                $btn.prop('disabled', false).text('ENTRAR');
            }

        }, 'json').fail(function () {
            bootbox.alert({
                title: 'Error',
                message: 'No se pudo conectar con el servidor. Intenta de nuevo.'
            });
            $btn.prop('disabled', false).text('ENTRAR');
        });
    });

});