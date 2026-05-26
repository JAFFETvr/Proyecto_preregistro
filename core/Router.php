<?php

/*
 * 
 * function __autoload($class_name) {
    if (file_exists('./controllers/' . $GLOBALS["path"]["module"] . "/" . $class_name . '.php')) {
        include_once './controllers/' . $GLOBALS["path"]["module"] . "/" . $class_name . '.php';
    } else if (file_exists('./models/' . $GLOBALS["path"]["module"] . "/" . $class_name . '.php')) {
        include_once './models/' . $GLOBALS["path"]["module"] . "/" . $class_name . '.php';
    } else if (file_exists('./core/'. $class_name .'.php')) {
        include_once './core/'. $class_name .'.php';
    }
}
 * 
 */

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$path = array_filter(explode("/", $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']));
$path = buildUrl($path, $config["struct_url"]);



/*
 * if (validateController($url)) {

    include_once __DIR__ . '/Controller.php';
    // include_once $url;
    $c = new $path["controller"];
    if (method_exists($c, $path["method"])) {
        $c->$path["method"]();
    } else {
        include_once __DIR__ . "/../views/actionNotValid.php";
    }
} else {
    include_once __DIR__ . "/../views/notFound.php";
}
 */

function validateController($url) {
    if (file_exists($url)) {
        return true;
    } else {
        return false;
    }
}

//var_dump($path);

function buildDirController($path) {
    return "./views/" . $path["module"] . "/" . $path["page"] . ".php";
}

function buildUrl($path, $struct) {
    $sizeStruct = count($struct);
    $arr = array();
    for ($i = 0, $p = 0; $i < $sizeStruct && !empty($path[$p]); $i++, $p++) {

        switch ($struct[$i]) {
            case "domain":
                if($GLOBALS["config"]["contains_sessions"]){
                    $arr[$struct[$i]] = $path[$p]."/".$path[$p+1]; 
                $p++;
                }else{
                $arr[$struct[$i]] = $path[$p]; 
                }
                break;
            default :
                $arr[$struct[$i]] = $path[$p];
                break;
                
        }
    }
    return $arr;
}

function base_url() {

    // $root = $_SERVER['DOCUMENT_ROOT'];
    // $ip = $_SERVER['REMOTE_ADDR'];
    // $url = $_SERVER['HTTP_REFERER'];
    $server_name = $_SERVER['SERVER_NAME'];
    $server_port = $_SERVER['SERVER_PORT'];
    $url = "http://".$server_name.":".$server_port."/evaluacioninaoe/";

    return $url;
    //return "http://cpulabserver.inaoep.mx:2018/MonitorCiudadanoMX"
    //return 'http://localhost/MonitorCiudadanoMX/assets/plugins/dropzone.css';
}

function all_url($limit = "home") {
    $bool = true;
    $url = "http://";    
    $i = 0;
    $size = count($GLOBALS["path"]);
    while ($bool) { //isset($GLOBALS["config"]["struct_url"][$i])
        $url .= $GLOBALS["path"][$GLOBALS["config"]["struct_url"][$i]]."/";
        if($GLOBALS["config"]["struct_url"][$i]== $limit || $i == $size )
            {
            $bool= false;
        }else{
        $i++;
        }
    }
    return $url;
}

function getURL(){
    $i=0;
    $url ="http://";
    while (isset($GLOBALS["path"][$GLOBALS["config"]["struct_url"][$i]])) { //isset($GLOBALS["config"]["struct_url"][$i])
        $url .= $GLOBALS["path"][$GLOBALS["config"]["struct_url"][$i]]."/";
        $i++;
    }
    return $url;
}