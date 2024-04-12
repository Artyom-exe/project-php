<?php
require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'constantes.php';
require_once dirname(__DIR__, 2) . DS . 'core' . DS . 'authentificationGestion.php';

deconnecter_utilisateur();
header("location: /connection.php");
