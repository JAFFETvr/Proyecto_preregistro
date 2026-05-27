<?php 
date_default_timezone_set("America/Mexico_City"); 
session_start();

if (!isset($_SESSION['user_name'])) {
    echo '<script>location.href = "../../index.php";</script>';
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>SISTE - Panel Secretaría</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../assets/css/bootstrap-select.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <link rel="stylesheet" href="../../assets/css/estilos.css"> 
    <style>
        :root {
            --gov-blue: #0b3b6f;
            --gov-blue-dark: #07294d;
            --gov-blue-light: #e8eff8;
            --gov-border: #d6deea;
            --gov-bg: #f4f6f8;
            --gov-text: #1f2a37;
            --gov-muted: #5f6b7a;
        }

        .gov-secretaria {
            background: var(--gov-bg);
            color: var(--gov-text);
            font-family: "Segoe UI", "Noto Sans", system-ui, sans-serif;
        }

        .gov-secretaria .gov-navbar {
            background: var(--gov-blue);
            border-bottom: 3px solid var(--gov-blue-dark);
            padding: 10px 0;
        }

        .gov-secretaria .gov-nav {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 24px;
        }

        .gov-secretaria .gov-brand {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .gov-secretaria .gov-brand img {
            height: 50px;
        }

        .gov-secretaria .gov-title {
            color: #ffffff;
            font-weight: 700;
            font-size: 0.95rem;
        }

        .gov-secretaria .gov-subtitle {
            color: #c6d2e3;
            font-size: 0.72rem;
        }

        .gov-secretaria .gov-user {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #e7edf6;
            font-size: 0.85rem;
        }

        .gov-secretaria .gov-user #btnLogout {
            color: #e7edf6;
            cursor: pointer;
        }

        .gov-secretaria .gov-user #btnLogout:hover {
            color: #ffffff;
        }

        .gov-secretaria .main-content {
            padding: 26px 0 32px;
            min-height: calc(100vh - 74px);
        }

        .gov-secretaria .page-title {
            font-size: 0.95rem;
            font-weight: 700;
            color: var(--gov-blue);
            margin-bottom: 18px;
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }

        .gov-secretaria .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 16px;
            margin-bottom: 18px;
        }

        .gov-secretaria .stat-card {
            background: #ffffff;
            border: 1px solid var(--gov-border);
            border-left: 4px solid var(--gov-blue);
            border-radius: 8px;
            padding: 14px 16px;
        }

        .gov-secretaria .stat-card .stat-label {
            font-size: 0.75rem;
            color: var(--gov-muted);
            margin-bottom: 4px;
        }

        .gov-secretaria .stat-card .stat-value {
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--gov-blue);
        }

        .gov-secretaria .stat-card .stat-sub {
            font-size: 0.72rem;
            color: #2f6b3f;
            margin-top: 2px;
        }

        .gov-secretaria .status-tabs {
            display: flex;
            gap: 8px;
            margin-bottom: 18px;
            flex-wrap: wrap;
        }

        .gov-secretaria .status-tabs .tab {
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 0.78rem;
            cursor: pointer;
            border: 1px solid var(--gov-border);
            display: flex;
            align-items: center;
            gap: 6px;
            color: var(--gov-muted);
            text-decoration: none;
            background: #ffffff;
        }

        .gov-secretaria .status-tabs .tab:hover {
            border-color: var(--gov-blue);
            color: var(--gov-blue);
        }

        .gov-secretaria .status-tabs .tab.active {
            background: var(--gov-blue);
            color: #ffffff;
            border-color: var(--gov-blue);
        }

        .gov-secretaria .status-tabs .tab .badge {
            font-size: 0.7rem;
            padding: 2px 7px;
            border-radius: 10px;
            font-weight: 600;
            background: var(--gov-blue-light);
            color: var(--gov-blue);
        }

        .gov-secretaria .status-tabs .tab.active .badge {
            background: rgba(255, 255, 255, 0.2);
            color: #ffffff;
        }

        .gov-secretaria .toolbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 14px;
            flex-wrap: wrap;
            gap: 12px;
        }

        .gov-secretaria .toolbar-left {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .gov-secretaria .toolbar .count-label {
            font-size: 0.78rem;
            color: var(--gov-muted);
        }

        .gov-secretaria .btn-nuevo-registro {
            background: var(--gov-blue) !important;
            color: #fff !important;
            border: none !important;
            padding: 8px 18px;
            border-radius: 6px;
            font-size: 0.82rem;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-weight: 600;
            text-decoration: none;
        }

        .gov-secretaria .btn-nuevo-registro:hover {
            background: var(--gov-blue-dark) !important;
        }

        .gov-secretaria .buscador {
            display: flex;
            align-items: center;
            gap: 8px;
            background: #ffffff;
            border: 1px solid var(--gov-border);
            border-radius: 6px;
            padding: 7px 12px;
            width: 240px;
        }

        .gov-secretaria .buscador:focus-within {
            border-color: var(--gov-blue);
        }

        .gov-secretaria .buscador .icono-buscar {
            font-size: 0.95rem;
            color: var(--gov-blue);
            flex-shrink: 0;
        }

        .gov-secretaria .buscador input {
            border: none;
            outline: none;
            font-size: 0.82rem;
            color: var(--gov-text);
            background: #fff;
            width: 100%;
        }

        .gov-secretaria .buscador input::placeholder {
            color: #9aa4b2;
        }

        .gov-secretaria .tabla-expedientes {
            background: #fff;
            border-radius: 10px;
            border: 1px solid var(--gov-border);
            overflow: hidden;
            width: 100%;
            border-collapse: collapse;
        }

        .gov-secretaria .tabla-expedientes thead {
            background: var(--gov-blue-light);
        }

        .gov-secretaria .tabla-expedientes thead th {
            font-size: 0.7rem;
            font-weight: 700;
            color: var(--gov-blue);
            text-transform: uppercase;
            letter-spacing: 0.08em;
            padding: 12px 16px;
            text-align: left;
        }

        .gov-secretaria .tabla-expedientes tbody tr {
            border-bottom: 1px solid var(--gov-border);
            cursor: pointer;
            transition: background 0.15s;
        }

        .gov-secretaria .tabla-expedientes tbody tr:last-child {
            border-bottom: none;
        }

        .gov-secretaria .tabla-expedientes tbody tr:hover {
            background: #f7f9fc;
        }

        .gov-secretaria .tabla-expedientes tbody tr.row-selected {
            background: #e3ecf9;
        }

        .gov-secretaria .tabla-expedientes tbody td {
            padding: 12px 16px;
            font-size: 0.85rem;
            color: #374151;
        }

        .gov-secretaria .avatar-iniciales {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: var(--gov-blue-light);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.65rem;
            font-weight: 600;
            color: var(--gov-blue);
            flex-shrink: 0;
            margin-right: 8px;
            vertical-align: middle;
        }

        .gov-secretaria .chip-fecha {
            font-size: 0.75rem;
            color: var(--gov-blue);
            background: var(--gov-blue-light);
            padding: 3px 10px;
            border-radius: 12px;
            display: inline-block;
        }

        .gov-secretaria .btn-editar,
        .gov-secretaria .btn-ver {
            width: 30px;
            height: 30px;
            border-radius: 6px;
            background: var(--gov-blue);
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 0.85rem;
        }

        .gov-secretaria .btn-ver {
            background: #1a5da8;
        }

        .gov-secretaria .btn-editar:hover,
        .gov-secretaria .btn-ver:hover {
            background: var(--gov-blue-dark);
        }

        .gov-secretaria .btn.btn-success {
            background: var(--gov-blue);
            border-color: var(--gov-blue);
        }

        .gov-secretaria .btn.btn-success:hover {
            background: var(--gov-blue-dark);
            border-color: var(--gov-blue-dark);
        }

        #loading-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.6);
            z-index: 9999;
            text-align: center;
            padding-top: 20%;
        }
    </style>
</head>
<body class="gov-secretaria">

<nav class="navbar gov-navbar">
    <div class="container gov-nav">
        <div class="gov-brand">
            <img src="../../assets/images/siste_4.png" alt="SISTE">
            <div class="gov-brand-text">
                <div class="gov-title">Sistema de Titulación Electrónica</div>
                <div class="gov-subtitle">Gobierno de México — INAE</div>
            </div>
        </div>
        <?php if (isset($_SESSION['user_name'])): ?>
            <div class="gov-user">
                <i class="fas fa-user" aria-hidden="true"></i>
                <span id="username"><?php echo $_SESSION['user_name']; ?></span>
                <span>|</span>
                <span id="btnLogout">Cerrar sesión</span>
            </div>
        <?php endif; ?>
    </div>
</nav>

<div id="loading-overlay">
    <img src="../../assets/loading.gif" alt="Cargando..." style="width:80px;">
</div>

<main class="main-content">
    <div class="container">
        <h1 class="page-title">EXPEDIENTE ELECTRÓNICO</h1>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-label">Pre-registrados</div>
                <div class="stat-value" id="stat-pre">0</div>
                <div class="stat-sub"><i class="fa-solid fa-arrow-up"></i> Nuevos hoy</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">En proceso</div>
                <div class="stat-value">0</div>
                <div class="stat-sub">Sin cambios</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Emitidos</div>
                <div class="stat-value">0</div>
                <div class="stat-sub">Sin cambios</div>
            </div>
        </div>

        <div class="status-tabs" id="tabsSecretaria">
            <a class="tab nav-link active" data-tab="preregistrados">
                Pre-registrados <span class="badge" id="count-pre">0</span>
            </a>
            <a class="tab nav-link" data-tab="creados">
                Creados <span class="badge" id="count-creados">0</span>
            </a>
            <a class="tab nav-link" data-tab="por-firmar">
                Por firmar <span class="badge">0</span>
            </a>
            <a class="tab nav-link" data-tab="listos-enviar">
                Listos para Enviar <span class="badge">0</span>
            </a>
            <a class="tab nav-link" data-tab="revision">
                En Revisión DGP <span class="badge">0</span>
            </a>
            <a class="tab nav-link" data-tab="rechazados">
                Rechazados <span class="badge">0</span>
            </a>
            <a class="tab nav-link" data-tab="emitidos">
                Emitidos <span class="badge">0</span>
            </a>
        </div>

        <div class="toolbar">
            <div class="toolbar-left">
                <button class="btn-nuevo-registro" id="btn-nuevo-registro">NUEVO REGISTRO</button>
                <span class="count-label" id="count-total">0 registros</span>
            </div>
            <div class="buscador">
                <i class="fa-solid fa-magnifying-glass icono-buscar"></i>
                <input id="search-alumno" type="text" placeholder="Buscar alumno o prc">
            </div>
        </div>

        <div id="tabla-container">
            <table class="tabla-expedientes">
                <thead>
                    <tr>
                        <th>NOMBRE DEL ALUMNO</th>
                        <th>PROGRAMA</th>
                        <th class="text-center">FECHA PRE-REGISTRO</th>
                        <th style="width: 50px;"></th>
                    </tr>
                </thead>
                <tbody id="tbody-registros">
                    <tr>
                        <td colspan="4" class="text-center text-muted py-4">
                            <i class="fa-solid fa-spinner fa-spin"></i> Cargando...
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</main>

<div class="modal fade" id="modalDetalle" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background:#0c315e; color:white;">
                <h5 class="modal-title">
                    <i class="fa-solid fa-user-graduate"></i> Detalle del Registro
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="modal-body-detalle" style="padding: 20px;">
                </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-success" id="btn-promover" style="display:none;">
                    <i class="fa-solid fa-arrow-right"></i> Pasar a Creados
                </button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/5.3.2/bootbox.min.js"></script>
<script src="../../controllers/secretaria/script_secretaria.js"></script>
</body>
</html>
