<?php
/**
 * @author Endrődi Kálmán
 */

session_start();
include './vendor/autoload.php';

use App\Html\Requests;

Requests::handle();