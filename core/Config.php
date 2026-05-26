<?php

/*
 * 
 * Base url del sitio
 * 
 * @description:    Dirección URL del dominio donde se encuentra alojado el proyecto 
 * @example:        http://ejemplo.com/  
 * 
 */
//$config["base_url"] = "http://localhost/";
// "domain","home", "module","controller","method","parameters"
$config["struct_url"] = array("domain", "home", "module","page","key","key2");

/*
 * 
 * Controlador por defecto
 * 
 * @description:    Este parametro configura al controlador que será llamado por defecto, se puede interpretar como
 *                  el archivo index del sistema. El nombre del controlador no debe llevar la extensión del archivo, es decir, 
 *                  si el controlador por defecto es index.php solo se debe asignar index ;
 * @example:         index
 * 
 */

$config["default_view"] = "login";

/*
 * 
 * Módulo por defecto
 * 
 * @description:    Este parametro indica la carpeta donde se encuentra alojado el controlador que será llamado por defecto, por ejemplo,
 *                  si la ubicación del controlador es la siguiente src/controllers/website/index.php la carpeta del controlador es "website"
 *                  y solo se debe escribir este titulo.
 * @example:        website
 * 
 */
$config["default_module"] = "website";


$config["contains_sessions"] = true;

$config["rows_per_page"] = 50;
$config["rows_per_page_profile"] = 5;

/*
 * 
 * 
$config["language"] = "es";

$config["page_not_found"] = "";

$config["denied_action"] = "";
 * 
 * 
 */

