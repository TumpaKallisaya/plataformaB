<?php

class Exportar extends Controller{
    function Exportar(){
        parent::Controller();
        
        $this->load->library(array('cezpdf','fpdf','table','validation', 'My_PHPMailer'));
	// load helper
	$this->load->helper(array('form', 'url', 'download'));
        error_reporting(E_ALL);
    }
    function index(){
        $this->load->view('vistaPruebaExcelPdf');
    }
    
    function exportarExcel(){
        $this->load->library('excel');
        
        $this->excel->setActiveSheetIndex(0);
        $this->excel->getActiveSheet()->setTitle('Lista de usuarios');
        $this->load->model('modeloExcelPdf');
        $usuarios = $this->modeloExcelPdf->usuariosExcelPdf();
        
        $this->excel->getActiveSheet()->fromArray($usuarios);
        $filename='UsuariosExcel.xls';
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
        $objWriter->save('php://output');
    }
}