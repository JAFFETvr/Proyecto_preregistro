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
        /* ============================================
           SISTE — Estilos rediseño visual
           Sistema de Titulación Electrónica
           ============================================ */
          /* --- Botón editar en tabla --- */
        .btn-editar {
            width: 30px;
            height: 30px;
            border-radius: 8px;
            background: #2a5298;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 14px;
        }

        .btn-editar:hover {
            background: #1a3a6b;
        }

        /* --- Botón ver en tabla (NUEVO) --- */
        .btn-ver {
            width: 30px;
            height: 30px;
            border-radius: 8px;
            background: #17a2b8;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 14px;
        }

        .btn-ver:hover {
            background: #138496;
        }
        /* --- Navbar --- */
        .navbar {
            background: #1a3a6b;
            padding: 0 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 56px;
        }

        .navbar-brand img {
            height: 36px;
        }

        .navbar-title {
            color: #fff;
            font-size: 14px;
            font-weight: 500;
        }

        .navbar-user {
            display: flex;
            align-items: center;
            gap: 6px;
            color: #c9d8f0;
            font-size: 13px;
            cursor: pointer;
        }

        .navbar-user #btnLogout {
            color: #c9d8f0;
            cursor: pointer;
        }

        .navbar-user #btnLogout:hover {
            color: #fff;
        }

        /* --- Fondo general --- */
        .main-content {
            padding: 24px;
            background: #f4f6fb;
            min-height: calc(100vh - 56px);
        }

        /* --- Título de página --- */
        .page-title {
            font-size: 16px;
            font-weight: 500;
            color: #1a3a6b;
            margin-bottom: 16px;
        }

        /* --- Tarjetas de métricas --- */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            margin-bottom: 16px;
        }

        .stat-card {
            background: #fff;
            border-radius: 10px;
            border: 0.5px solid rgba(0, 0, 0, 0.08);
            padding: 12px 14px;
        }

        .stat-card .stat-label {
            font-size: 11px;
            color: #6b7280;
            margin-bottom: 4px;
        }

        .stat-card .stat-value {
            font-size: 22px;
            font-weight: 500;
            color: #1a3a6b;
        }

        .stat-card .stat-sub {
            font-size: 11px;
            color: #2e7d4f;
            margin-top: 2px;
        }

        /* --- Tabs de estado --- */
        .status-tabs {
            display: flex;
            gap: 4px;
            margin-bottom: 16px;
            flex-wrap: wrap;
        }

        .status-tabs .tab {
            padding: 7px 14px;
            border-radius: 20px;
            font-size: 12px;
            cursor: pointer;
            border: 0.5px solid transparent;
            display: flex;
            align-items: center;
            gap: 6px;
            color: #6b7280;
            text-decoration: none;
            background: transparent;
        }

        .status-tabs .tab:hover {
            background: #e8edf8;
        }

        .status-tabs .tab.active {
            background: #1a3a6b;
            color: #fff;
        }

        .status-tabs .tab .badge {
            font-size: 10px;
            padding: 1px 6px;
            border-radius: 10px;
            font-weight: 500;
            background: #e0e6f5;
            color: #1a3a6b;
        }

        .status-tabs .tab.active .badge {
            background: rgba(255, 255, 255, 0.2);
            color: #fff;
        }

        /* --- Barra de herramientas (botón + buscador) --- */
        .toolbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 12px;
        }

        .toolbar-left {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .toolbar .count-label {
            font-size: 12px;
            color: #6b7280;
        }

        /* --- Botón Nuevo Registro (SIEMPRE VERDE) --- */
        .btn-nuevo-registro {
            background: #2e7d4f !important;
            color: #fff !important;
            border: none !important;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 13px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-weight: 500;
            text-decoration: none;
        }

        .btn-nuevo-registro:hover {
            background: #245f3d !important;
            color: #fff !important;
        }

        /* --- Buscador --- */
        .buscador {
            display: flex;
            align-items: center;
            gap: 8px;
            background: #fff;
            border: 1.5px solid #dbe5f0;
            border-radius: 8px;
            padding: 7px 12px;
            width: 220px;
        }

        .buscador:focus-within {
            border-color: #2e7d4f;
        }

        .buscador .icono-buscar {
            font-size: 15px;
            color: #1a3a6b;
            flex-shrink: 0;
        }

        .buscador input {
            border: none;
            outline: none;
            font-size: 13px;
            color: #1f2937;
            background: #fff;
            width: 100%;
        }

        .buscador input::placeholder {
            color: #9aa4b2;
        }

        /* --- Tabla de expedientes --- */
        .tabla-expedientes {
            background: #fff;
            border-radius: 12px;
            border: 0.5px solid rgba(0, 0, 0, 0.08);
            overflow: hidden;
            width: 100%;
            border-collapse: collapse;
        }

        .tabla-expedientes thead {
            background: #1a3a6b;
        }

        .tabla-expedientes thead th {
            font-size: 11px;
            font-weight: 500;
            color: #c9d8f0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 10px 16px;
            text-align: left;
        }

        .tabla-expedientes tbody tr {
            border-bottom: 0.5px solid rgba(0, 0, 0, 0.06);
            cursor: pointer;
            transition: background 0.15s;
        }

        .tabla-expedientes tbody tr:last-child {
            border-bottom: none;
        }

        .tabla-expedientes tbody tr:hover {
            background: #f0f4fb;
        }

        .tabla-expedientes tbody td {
            padding: 12px 16px;
            font-size: 13px;
            color: #374151;
        }

        /* --- Avatar con iniciales --- */
        .avatar-iniciales {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: #d6e4f7;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            font-weight: 500;
            color: #0c447c;
            flex-shrink: 0;
            margin-right: 8px;
            vertical-align: middle;
        }

        /* --- Chip de fecha --- */
        .chip-fecha {
            font-size: 12px;
            color: #0c447c;
            background: #e8edf8;
            padding: 3px 10px;
            border-radius: 12px;
            display: inline-block;
        }

        /* --- Botón editar en tabla --- */
        .btn-editar {
            width: 30px;
            height: 30px;
            border-radius: 8px;
            background: #2a5298;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 14px;
        }

        .btn-editar:hover {
            background: #1a3a6b;
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
<body>

<nav class="navbar">
    <div class="navbar-brand">
        <img src="../../assets/images/siste_4.png" alt="SISTE">
    </div>
    <div class="navbar-title">Sistema de Titulación Electrónica</div>
    <?php if (isset($_SESSION['user_name'])): ?>
        <div class="navbar-user">
            <i class="fas fa-user" aria-hidden="true"></i>
            <span id="username"><?php echo $_SESSION['user_name']; ?></span>
            <span>|</span>
            <span id="btnLogout">Cerrar sesión</span>
        </div>
    <?php endif; ?>
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
                <button class="btn-nuevo-registro">NUEVO REGISTRO</button>
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
