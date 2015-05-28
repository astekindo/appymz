<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| ADDON CONFIG
|--------------------------------------------------------------------------
*/
$path = '';
$config['app_title']	= 'Yamazaki';
$config['document_root']	= $_SERVER['DOCUMENT_ROOT'] . $path . '/';
$config['min_password_length'] 	= 6;
$config['max_password_length'] 	= 20;
$config['salt_length'] 	= 10;
$config['length_records']	= 20;
$config['logo_print_color'] = $_SERVER['DOCUMENT_ROOT'] . $path . '/appymz/new/assets/img/logo_print.jpg';
$config['header_laporan'] = '<h2>MITRA BANGUNAN SUPERMARKET</h2>';
$config['header_laporan_matrix'] = 'MITRA BANGUNAN SUPERMARKET';
$config['header_laporan_matrix_distribusi'] = 'PT.SURYA KENCANA KERAMINDO';
#$config['logo_print_color'] = 'http://localhost:82/mbs/new/assets/img/logo_print.jpg';

