<?php  // Moodle configuration file

unset($CFG);
global $CFG;
$CFG = new stdClass();

/*putenv('MOODLE_DB_TYPE=' . getenv('MOODLE_DB_TYPE'));
putenv('MOODLE_DB_LIBRARY=' . getenv('MOODLE_DB_LIBRARY'));
putenv('MOODLE_DB_HOST=' . getenv('MOODLE_DB_HOST'));
putenv('MOODLE_DB_NAME=' . getenv('MOODLE_DB_NAME'));
putenv('MOODLE_DB_USER=' . getenv('MOODLE_DB_USER'));
putenv('MOODLE_DB_PASS=' . getenv('MOODLE_DB_PASS'));
putenv('MOODLE_DB_PREFIX=' . getenv('MOODLE_DB_PREFIX'));

$CFG->dbtype    = getenv('MOODLE_DB_TYPE');
$CFG->dblibrary = getenv('MOODLE_DB_LIBRARY');
$CFG->dbhost    = getenv('MOODLE_DB_HOST');
$CFG->dbname    = getenv('MOODLE_DB_NAME');
$CFG->dbuser    = getenv('MOODLE_DB_USER');
$CFG->dbpass    = getenv('MOODLE_DB_PASS');
$CFG->prefix    = getenv('MOODLE_DB_PREFIX');*/

$CFG->dbtype    = 'mariadb';
$CFG->dblibrary = 'native';
$CFG->dbhost    = 'localhost';
$CFG->dbname    = 'usuipem_db';
$CFG->dbuser    = 'usuipem_user';
$CFG->dbpass    = 'P9m$JUSvL{;d+R59';
$CFG->prefix    = 'mdl_';


$CFG->dboptions = array (
  'dbpersist' => 0,
  'dbport' => 3306,
  'dbsocket' => '',
  'dbcollation' => 'utf8mb4_unicode_ci',
);

$CFG->wwwroot   = 'https://ipem.fusion-ti.com/ipem';
$CFG->dataroot  = '/home1/usuipem/moodledata';
$CFG->admin     = 'admin';

$CFG->webservice_url = "http://ceromultas.edomex.gob.mx/ceroPapel/webService?wsdl";
$CFG->webservice_username = "FirSeMujeres";
$CFG->webservice_password = "Rc24Mr1$64sU";
$CFG->firmante = "CADM820827MDFHRN04";
$CFG->firmante_pw = "Monica_mujeres25@";

//$CFG->reverseproxy = true;
$CFG->sslproxy = true;

$CFG->directorypermissions = 0777;

//@error_reporting(E_ALL | E_STRICT);
//@ini_set('display_errors', '1');
//$CFG->debug = (E_ALL | E_STRICT);
//$CFG->debugdisplay = 1;

//$CFG->debug_developer_use_pretty_exceptions = false;
require_once(__DIR__ . '/lib/setup.php');

// There is no php closing tag in this file,
// it is intentional because it prevents trailing whitespace problems!