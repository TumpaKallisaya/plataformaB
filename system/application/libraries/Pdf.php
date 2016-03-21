<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    // Incluimos el archivo fpdf
    require_once APPPATH."libraries/Table_pdf.php";
 
    //Extendemos la clase Pdf de la clase fpdf para que herede todas sus variables y funciones
    class Pdf extends Table_pdf {
        public function __construct() {
            parent::__construct();
            ob_start();
        }
        // El encabezado del PDF
        public function Header(){
            $this->Image('images/logo1.png',10,8,22);
            $this->SetFont('Arial','B',13);
            $this->Cell(40);
            $this->Cell(120,10,'AUTORIDAD DE REGULACION Y FISCALIZACION DE',0,0,'C');
            $this->Ln('5');
            $this->Cell(40);
            $this->Cell(120,10,'TELECOMUNICACIONES Y TRANSPORTES',0,0,'C');
            $this->Ln('8');
            $this->SetFont('Arial','B',8);
            $this->Cell(38);
            $this->Cell(120,10,'REPORTE DE CHAT',0,0,'C');
            $this->Ln(20);
       }
       // El pie del pdf
       public function Footer(){
           $this->SetY(-15);
           $this->SetFont('Arial','I',8);
           $this->Cell(0,10,'Página '.$this->PageNo().'/{nb}',0,0,'C');
      }
    }