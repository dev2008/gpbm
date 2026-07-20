<?php
/*
function set_standard_pdf_parameters_custom($pdf){
	// e.g. set margin left to 200
	//$pdf->SetMargins(200, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
}
*/


function pdf_custom_code_after_addpage($pdf){
    //$pdf->Image('images/logo.png', 10, 10);
}


// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF {
    

    //Page header
    public function Header() {
        // Logo
        $image_file = 'download.jpg';
        $this->Image($image_file, 10, 10, 15, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        // Set font
        $this->SetFont('helvetica', 'B', 20);
        // Title
        $this->Cell(0, 15, '<< TCPDF Example 003 >>', 0, false, 'C', 0, '', 0, false, 'M', 'M');
    }

    // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        // Page number
        $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
}

?>