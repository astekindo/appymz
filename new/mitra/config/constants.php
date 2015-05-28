<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
  |--------------------------------------------------------------------------
  | File and Directory Modes
  |--------------------------------------------------------------------------
  |
  | These prefs are used when checking and setting modes when working
  | with the file system.  The defaults are fine on servers with proper
  | security, but you may wish (or even need) to change the values in
  | certain environments (Apache running a separate process for each
  | user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
  | always be used to set the mode correctly.
  |
 */
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
  |--------------------------------------------------------------------------
  | File Stream Modes
  |--------------------------------------------------------------------------
  |
  | These modes are used when working with fopen()/popen()
  |
 */

define('FOPEN_READ', 'rb');
define('FOPEN_READ_WRITE', 'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE', 'ab');
define('FOPEN_READ_WRITE_CREATE', 'a+b');
define('FOPEN_WRITE_CREATE_STRICT', 'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

//Parameter
define('JTH_TEMPO_PO', '6');
define('LIMIT_NOTIFIKASI_PO', '1');
define('LIMIT_NOTIFIKASI_INVOICE', '2');
define('KD_PARAMETER_MARGIN', '1');
define('PIC_PENERIMA_PO', '8');
define('ALAMAT_PENERIMA_PO', '9');
define('REMARK_PO', '10');
define('KD_KATEGORI1_ASSET', '0');
define('PRM_HEADER_NPWP', 'POS2');
define('PRM_HEADER_CETAK_DOC_RIGHT1', 'PRM1');
define('PRM_HEADER_CETAK_DOC_RIGHT2', 'PRM2');
define('PRM_HEADER_CETAK_DOC_RIGHT3', 'PRM3');
define('KD_LOKASI', '00');
define('KD_BLOK', '05');
define('KD_SUB_BLOK', '01');
define('PREFIX_ASSET_REQUEST', 'AR');
define('GET_ASSET_REQUEST', 'PA');
define('GET_RO_REQUEST', 'PO');
define('GET_INVOICE_REQUEST', 'IN');
define('GET_INVOICE_KONSINYASI_REQUEST', 'IK');
define('PRM_HEADER_CETAK_DOC_RIGHT4', 'PRM4');
define('GET_PB_REQUEST', 'PB');
define('BANK_FAKTUR', 'PRM5');
define('NAMA_FAKTUR', 'PRM6');
define('JABATAN_FAKTUR', 'PRM7');
define('NAMA_REKENING_BANK', 'PRM8');
define('NO_NPWP', 'PRM11');
define('TGL_PENGUKUHAN', 'PRM12');


define('PDF_PAGE_ORIENTATION_PORTRAIT', 'P');
define('PDF_PAGE_ORIENTATION_LANDSCAPE', 'L');

/*BIRT */
define('BIRT_BASE_URL', 'http://192.168.2.2:8080/birt');


/* End of file constants.php */
/* Location: ./application/config/constants.php */