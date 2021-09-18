<?php

//Ini setup.
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set("memory_limit", "512M");
header('Content-type: text/plain; charset=utf-8');
set_time_limit(1000);
date_default_timezone_set("Asia/Hong_Kong");

//Script setup.
$name_folder = "PTR"; //Put the name of the folder to be used throughout the script.
$name_script = "PTR"; //Put the name of the script to be used throughout the script.
$email_error = "gerard.reyes@maximintegrated.com"; //Put whom to send email for error handling.
$email_debug = "gerard.reyes@maximintegrated.comx"; //Put whom to send email for debugging purpose implode by "," for multiple email recipients.
mail($email_debug, $name_script . " SCRIPT", "START");

//Path for logs.
$path = file_exists("/var/www/" . $name_folder) ? "/var/www/" . $name_folder : "C:/xampp/htdocs/" . $name_folder;
$path_root = file_exists("/var/www/" . $name_folder) ? "/var/www/" : "C:/xampp/htdocs/";
$dir_logs = $path . $name_folder . "/LOGS/";
$dir_text = $path . $name_folder . "/TEXT/";

//Logs start.
$var_start_time = time() + microtime();
$str_title = $name_script . "_";
$str_log_file_name = $dir_logs . $str_title . date("Ymd_His", time()) . ".LOG";
//$log_instance = fopen($str_log_file_name, "w");
ECHO_AND_LOG_ME("START : " . date("Y-m-d H:i:s", time()) . "\r\n");
ECHO_AND_LOG_ME("Name of folder: " . $name_folder . "\r\n");
ECHO_AND_LOG_ME("Name of script: " . $name_script . "\r\n");

//Includes.
//require_once($path_root . "COMMON_FUNCTIONS.PHP");
//Links.
//Program Proper.
$link_main = OPEN_LINK_MYSQLI_DIRECT('localhost', 'root', ''); //SCRIPT WILL INSERT IN THIS SERVER!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
ECHO_AND_LOG_ME("\r\nConnected to main link: " . $link_main->host_info . ".\r\n");
PROGRAM_PROPER($link_main);

//Logs end.
$var_end_time = time() + microtime();
ECHO_AND_LOG_ME("\r\nDONE. (" . number_format($var_end_time - $var_start_time, 1) . " s.).\r\n");
ECHO_AND_LOG_ME("END : " . date("Y-m-d H:i:s", time()) . "\r\n");
mail($email_debug, $name_script . " SCRIPT", "END");

###########################################################
###                 FUNCTIONS                           ###
###########################################################

function OPEN_LINK_MYSQLI_DIRECT($hostname, $username, $password) {
    $con = mysqli_connect($hostname, $username, $password);
    if (!$con) {
        ERROR_HANLDER_MYSQLI("CONNECTION ERROR: " . $hostname . " \r\n");
        die("Connection failed: " . mysqli_connect_error());
    }
    return $con;
}

function EXECUTE_QUERY_MYSQLI($query, $link) {
    $result = mysqli_query($link, $query);
    return $result;
}

function ECHO_AND_LOG_ME($string) {
    echo $string;
}

function EMPTY_ARRAY_CHECKER($array_check, $name, $sql = "") {
    global $name_script;
    global $email_debug;
    $boolEmpty = empty($array_check) ? true : false;
    if ($boolEmpty == true) {
        ECHO_AND_LOG_ME("\r\n" . $name . " EMPTY! Query: " . $sql . "\n");
        mail($email_debug, $name_script . " SCRIPT EMPTY CHECKER", $name . " EMPTY! Query: " . $sql . "\n");
    } else {
        //do nothing
    }
}

function GET_ALL_ROW_MYSQLI($query, $link) {
    $array_return = array();
    $result = EXECUTE_QUERY_MYSQLI($query, $link);
    while ($row = mysqli_fetch_assoc($result)) {
        $array_return[] = $row;
    }
    return $array_return;
}

function GET_ALL_ROW_MYSQLI_WITH_KEY($query, $link) {
    $array_return = array();
    $result = EXECUTE_QUERY_MYSQLI($query, $link);
    while ($row = mysqli_fetch_assoc($result)) {
        $array_return[$row['param_key']] = $row;
    }
    return $array_return;
}

function GET_ONE_ROW_MYSQLI($query, $link) {
    $array_return = array();
    $result = EXECUTE_QUERY_MYSQLI($query, $link);
    while ($row = mysqli_fetch_assoc($result)) {
        $array_return = $row;
    }
    return $array_return;
}

function GET_ONE_VALUE_MYSQLI($query, $link) {
    $array_return = '';
    $result = EXECUTE_QUERY_MYSQLI($query, $link);
    while ($row = mysqli_fetch_assoc($result)) {
        $array_return = $row['param_value'];
    }
    return $array_return;
}

function GET_PARAM_VALUE_MYSQLI($query, $link) {
    $array_return = array();
    $result = EXECUTE_QUERY_MYSQLI($query, $link);
    while ($row = mysqli_fetch_assoc($result)) {
        $array_return[] = $row['param_value'];
    }
    return $array_return;
}

function GET_ARRAY_VALUE_MYSQLI_WITH_KEY($query, $link) {
    $array_return = array();
    $result = EXECUTE_QUERY_MYSQLI($query, $link);
    while ($row = mysqli_fetch_assoc($result)) {
        $array_return[$row['param_key']] = $row['param_value'];
    }
    return $array_return;
}

function SEND_SOMETHING_TO_THE_VOID($something) {
    //I created this function because I am annoyed with those warning about a variable not being used but is created nonetheless... please stop doing that >_<;
    $void = $something;
    unset($void);
}

function TRIM_ME_PLEASE($array, $exclude = array()) {
    foreach ($array as $key => $value) {
        if (in_array($key, $exclude)) {
            continue;
        }
        $array[$key] = trim($value);
    }
    return $array;
}

function PROGRAM_PROPER($link) {
    global $path;
    ECHO_AND_LOG_ME("\r\nPROGRAM PROPER: MAIN LINK: " . $link->host_info . "\r\n");
    ECHO_AND_LOG_ME("Good day! Today is: " . date("Y-m-d H:i:s") . "\r\n");

    EXECUTE_QUERY_MYSQLI("TRUNCATE ptr.ptr;", $link);
    $query = "LOAD DATA INFILE '" . $path . "/Process_me.csv' INTO TABLE ptr.ptr FIELDS TERMINATED BY ',' LINES TERMINATED BY '\n' IGNORE 1 ROWS;";
    EXECUTE_QUERY_MYSQLI($query, $link);

    $array_data = GET_ALL_ROW_MYSQLI("SELECT * FROM ptr.ptr;", $link);
//    print_r($array_data);
//    $counter_left_increment = 0;
    $counter_right_increment = 0;
    $counter_pages = 1;

    //Font Size 11
//    $parameter_left = 7;
//    $parameter_right = array(4, 21, 38);
//    $add_me_to_left_for_profession = 2;
//    $add_me_to_right_for_profession = 2;
//    $add_me_to_left_for_tin = 5;
//    $add_me_for_next_page = 39;
    //Font Size 10
//    $parameter_left = 8;
//    $parameter_right = array(4, 21, 38);
//    $add_me_to_left_for_profession = 2;
//    $add_me_to_right_for_profession = 2;
//    $add_me_to_left_for_tin = 6;
//    $add_me_for_next_page = 46;
    //Font Size 10.5
    $parameter_left = 8;
    $parameter_right = array(4, 21, 38);
    $add_me_to_left_for_profession = 1;
    $add_me_to_right_for_profession = 2;
    $add_me_to_left_for_address = 3;
    $add_me_to_right_for_address = 1;
    $add_me_to_left_for_tin = 5;
    $add_me_for_next_page = 41;
//    foreach ($parameter_right as $value) {
//        $array_put_data_here_second_row[] = $value + $add_me_to_left_for_tin;
//    }
//    print_r($array_put_data_here_second_row);

    $exapp = new COM("Excel.application") or die("Error: Cannot connect.");
//    $wkb = $exapp->Workbooks->open($path . "\PTR_test.xlsx");
    $wkb = $exapp->Workbooks->open($path . "\PTR_output.xlsx");
    $exapp->Application->Visible = 1;
    $sheets = $wkb->Worksheets(1);
    $sheets->activate;

    foreach ($array_data as $value) {
        $cell = $sheets->Cells($parameter_left, $parameter_right[$counter_right_increment]);
        $cell->activate;
        $cell->value = str_replace(';', ',', $value['name']);

        $cell = $sheets->Cells($parameter_left + $add_me_to_left_for_profession, $parameter_right[$counter_right_increment] + $add_me_to_right_for_profession);
        $cell->activate;
        $cell->value = 'INSURANCE AGENT';

        $cell = $sheets->Cells($parameter_left + $add_me_to_left_for_address, $parameter_right[$counter_right_increment] + $add_me_to_right_for_address);
        $cell->activate;
        $cell->value = 'La Fuerza Bldg, Chino Roces, Makati';

        $cell = $sheets->Cells($parameter_left + $add_me_to_left_for_tin, $parameter_right[$counter_right_increment]);
        $cell->activate;
        $cell->value = $value['tin'];

        if ($counter_right_increment == 2) {
            $counter_right_increment = 0;
            $parameter_left = $parameter_left + $add_me_for_next_page;
            $counter_pages++;
        } else {
            $counter_right_increment++;
        }
    }
//    $sheets->protect("master");

    ECHO_AND_LOG_ME("\r\nTotal generated entries: " . count($array_data));
    ECHO_AND_LOG_ME("\r\nTotal generated pages: " . $counter_pages . "\r\n");
}
