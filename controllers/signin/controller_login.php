<?php
date_default_timezone_set("America/Mexico_City");
session_start();

require_once __DIR__ . '/../../m/db_connection.php';

header('Content-Type: application/json');

if (!isset($_POST['action'])) {
    echo json_encode(['ok' => false, 'msg' => 'Acción no definida']);
    exit;
}

switch ((int)$_POST['action']) {

    // ── action=1 : Login ────────────────────────────────────────────
    case 1:
        $username = isset($_POST['username']) ? trim($_POST['username']) : '';
        $password = isset($_POST['password']) ? $_POST['password'] : '';

        if ($username === '' || $password === '') {
            echo json_encode(['ok' => false, 'msg' => 'Usuario y contraseña son obligatorios.']);
            exit;
        }

        $con = new DBconnection();
        $con->openDB();

        $usernameEscaped = pg_escape_string($con->getConn(), $username);
        $res = $con->query(
            "SELECT id, username, display_name, password_hash, rol, status
             FROM users
             WHERE username = '$usernameEscaped'
             LIMIT 1"
        );

        $user = pg_fetch_assoc($res);
        $con->closeDB();

        if (!$user) {
            echo json_encode(['ok' => false, 'msg' => 'Usuario o contraseña incorrectos.']);
            exit;
        }

        if ((int)$user['status'] !== 1) {
            echo json_encode(['ok' => false, 'msg' => 'Tu cuenta está desactivada. Contacta al administrador.']);
            exit;
        }

        if (!password_verify($password, $user['password_hash'])) {
            echo json_encode(['ok' => false, 'msg' => 'Usuario o contraseña incorrectos.']);
            exit;
        }

        // Sesión
        $_SESSION['user_name']    = $user['username'];
        $_SESSION['username']     = $user['display_name'] ?: $user['username'];
        $_SESSION['rol']          = $user['rol'];
        $_SESSION['user_id']      = $user['id'];

        echo json_encode([
            'ok'  => true,
            'rol' => $user['rol']
        ]);
        break;

    // ── action=99 : Logout ──────────────────────────────────────────
    case 99:
        session_unset();
        session_destroy();
        echo json_encode(['ok' => true]);
        break;

    default:
        echo json_encode(['ok' => false, 'msg' => 'Acción no válida']);
        break;
}
