<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Pdf {

    public function crear($nombre, $contenido) {
//        Ejemplo de generación del PDF
//        $this->load->library('pdf');
//        $this->pdf->crear("./static/files/orden/orden-58.pdf", "Contenido PDF");

        require_once './static/plugins/MPDF57/mpdf.php';

        $mpdf = new mPDF('utf-8');

        $stylesheet = file_get_contents('./static/css/bootstrap.css');

        $mpdf->WriteHTML($stylesheet, 1);

        // Write some HTML code:
        $mpdf->WriteHTML($contenido);

        //elimina el documento si existe
        @unlink($nombre);
        
        // Output a PDF file directly to the browser
        $mpdf->Output($nombre, 'F');
    }

}
