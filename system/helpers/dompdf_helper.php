<?php
/**
 * Created by PhpStorm.
 * User: Azfar
 * Date: 12/11/14
 * Time: 10:37 PM
 */

if (!defined('BASEPATH')) exit('No direct script access allowed');
function pdf_create($html, $filename='', $stream=TRUE)
{
    require_once("dompdf/dompdf_config.inc.php");

    $dompdf = new DOMPDF();
    $dompdf->load_html($html);
    $dompdf->render();

    $output = $dompdf->output();
    $file_to_save = './printitem/'.$filename.'.pdf';
    file_put_contents($file_to_save, $output);

    if ($stream) {
       // $dompdf->stream($filename.".pdf");
        //$dompdf->stream($filename.".pdf",array('Attachment'=>0));
        $output = $dompdf->output();
        file_put_contents($filename.".pdf", $output);



    } else {
        return $dompdf->output();
    }
}
?>