<?php

namespace INHack20\InventarioBundle\Extras\ReportesPDF\Activo;
use INHack20\InventarioBundle\Entity\Ubicacion;
use INHack20\InventarioBundle\Entity\Activo;
/**
 * Description of Inventario
 *
 * @author INHACK20
 */
/**
 *Clase para generar pdf 
 */
class ReporteInventario{
   
    public function __construct($tipoActivo,Ubicacion $ubicacion=NULL,$activos, $container, $usuario, $estatus=NULL){
        // Spanish; Castilian

        global $l;
        $l = Array();

        // PAGE META DESCRIPTORS --------------------------------------

        $l['a_meta_charset'] = 'UTF-8';
        $l['a_meta_dir'] = 'ltr';
        $l['a_meta_language'] = 'es';

        // TRANSLATIONS --------------------------------------
        $l['w_page'] = 'página';

        // create new PDF document
        $pdf = new Inventario(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // set datos de cabecera
        if($ubicacion != NULL){
            $pdf->setDenominacion($ubicacion->getDependencia());
        }
        else{
            if($estatus != '' && $estatus != NULL)
            {
                if($estatus == $container->getParameter('STOCK_ALMACEN'))
                    $pdf->setDenominacion('STOCK ALMACEN');
                if($estatus == $container->getParameter('DESINCORPORADO'))
                    $pdf->setDenominacion('DEPOSITO');
            }
        }
        $pdf->setUnidad_administrativa($usuario->getUnidadAdministrativa());
        $pdf->setGeografica("ESTADO ".strtoupper($usuario->getEstado()->getDescripcion()));
        $pdf->setTipoActivo($tipoActivo);
        $pdf->setContainer($container);
        
        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Ing. Carlos Mendoza');
        $pdf->SetTitle('Reporte');
        $pdf->SetSubject('Reporte SACAF');
        $pdf->SetKeywords('INHack20, PDF');

        // set default header data
        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

        // set header and footer fonts
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        //set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, 36, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(20);

        //set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, 32);

        //set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        //set some language-dependent strings
        $pdf->setLanguageArray($l);

        // ---------------------------------------------------------

        // set font
        $pdf->SetFont('times', 'BI', 12);

        // add a page
        $pdf->AddPage();

        // set some text to print
        
        $pdf->SetFont('times', 'B', 5);

        // -----------------------------------------------------------------------------    

        $html= '
        <table border="1">
        <tr>
            <td colspan="3" align="center" width="15%" bgcolor="#CCCCCC">CLASIFICACI&Oacute;N</td>
            <td rowspan="2" align="center" width="5%" bgcolor="#CCCCCC">N&deg; ID</td>
            <td rowspan="2" align="center" width="72%" bgcolor="#CCCCCC">DESCRIPCI&Oacute;N DE LOS BIENES</td>
            <td rowspan="2" align="center" width="8%" bgcolor="#CCCCCC">VALOR UNITARIO (BSF)</td>
        </tr>
        <tr>
            <td align="center" width="5%" bgcolor="#CCCCCC">GRUPO</td>
            <td align="center" width="5%" bgcolor="#CCCCCC">SUB GRUPO</td>
            <td align="center" width="5%" bgcolor="#CCCCCC">SECCION</td>
        </tr>
        ';
        
        $html= '<table border="1">';
            if($tipoActivo==$container->getParameter('ACTIVO_MOBILIARIO'))
            {

                $m="";

                $cant=count($activos);
                $v=array();
                $total=0.00;
                    foreach($activos as $activo)
                    {
                        $v[0]=$activo->getNroBienNacional();
                        $v[1]=$activo->getMobiliario()->getDescripcion();
                        $v[2]=$activo->getMobiliario()->getValor();
                        $total+=$v[2];
                        $m.='
                        <tr>
                            <td align="center" width="5%">2</td>
                            <td align="center" width="5%">01</td>
                            <td align="center" width="5%">0</td>
                            <td align="center" width="5%">'.$v[0].'</td>
                            <td align="left" width="72%">&nbsp;&nbsp;'.$v[1].'</td>
                            <td align="center" width="8%">'.$v[2].'</td>
                        </tr>
                        ';
                    }

                $html.=$m;
                if(count($activos)>0){
                    $html.=
                    '
                    <tr>
                        <td align="center">&nbsp;</td>
                        <td align="center">&nbsp;</td>
                        <td align="center">&nbsp;</td>
                        <td align="center">&nbsp;</td>
                        <td align="center"><strong>'.$cant.' MOVIMIENTO DE BIENES NACIONALES</strong></td>
                        <td align="center"><strong>'.  number_format($total,2,',','.').' BsF</strong></td>
                    </tr>

                    ';
                }
                else{
                    $html.=
                    '
                    <tr>
                        <td align="center" height="40" colspan="6">&nbsp;<br/><br/><br/><br/><br/><br/>NO HAY MOBILIARIOS<br/><br/><br/><br/><br/><br/></td>
                    </tr>
                    ';
                }

            }
            elseif($tipoActivo==$container->getParameter('ACTIVO_EQUIPO')){
                $cant = count($activos );
                foreach ($activos as $activo)
                {
                    $html.='
                        <tr align="left">
                            <td>&nbsp;&nbsp;&nbsp;'.$activo->getNroBienNacional().'</td>
                            <td>&nbsp;&nbsp;&nbsp;'.$activo->getEquipo()->getMarca().'</td>
                            <td>&nbsp;&nbsp;&nbsp;'.$activo->getEquipo()->getModelo().'</td>
                            <td>&nbsp;&nbsp;&nbsp;'.$activo->getEquipo()->getSerial().'</td>
                            <td>&nbsp;&nbsp;&nbsp;'.$activo->getEquipo()->getTipoEquipo()->getDescripcion().'</td>
                        </tr>    
                    ';

                }
                if(count($activos)==0)
                {
                    $html.='
                        <tr>
                            <td height="80" colspan="5" align="center"><br/><br/><br/><br/><br/><br/><strong>NO HAY EQUIPOS ASOCIADOS</strong></td>
                        </tr>
                        ';
                }
                else
                {
                    $html.='
                        <tr align="center">
                            <td colspan="5" align="center">'.$cant.' MOVIMIENTO(S) DE BIENES NACIONALES</td>
                        </tr>
                        ';
                }
            }
        
        $html.='</table>';
        
        $firmas = NULL;
            if($ubicacion != NULL){
                $firmas= $ubicacion->getFirmas();
            }
        
        
        $num_headers=1+4;
        $header=array("","ELABORADO","REVISADO","APROBADO","CONFORME");
            if(count($firmas)==0)
            {
                $firmas[0]=new \INHack20\InventarioBundle\Entity\Firma();
                $firmas[0]->setNombre("SIN FIRMA");
                $firmas[0]->setCargo("SIN FIRMA");
            }
                $j=5;
            if(count($firmas)>=2)
            {
                $num_headers=1+5;
                $header=array("","ELABORADO","REVISADO","APROBADO","CONFORME","CONFORME");
                $j=6;
            }
        
        $nombres=array(0=>"NOMBRE Y APELLIDO",
                            1 => $usuario->getNombreCompleto(), 
                            2 => $usuario->getFirmaDivision()->getNombreCompleto(),
                            3 => $usuario->getFirmaDirector()->getNombreCompleto(),
                            4 => "4",
                            5 => "5",
                        );
        $cargos=array(0=>"CARGO",
                            1 => strtoupper($usuario->getCargo()),
                            2=>  strtoupper($usuario->getFirmaDivision()->getCargo()),
                            3 => strtoupper($usuario->getFirmaDirector()->getCargo()),
                            4 => "4",
                            5 => "5",
                    );
        for($i=4;$i<$j;$i++)
            $nombres[$i]=$firmas[$i-4]->getNombreCompleto();
        for($i=4;$i<$j;$i++)
            $cargos[$i]=$firmas[$i-4]->getCargo();

        $pdf->setNum_headers($num_headers);
        $pdf->setHeaders($header);
        $pdf->setNombres($nombres);
        $pdf->setCargos($cargos);

        //
        $pdf->writeHTML($html, true, false, true, false, 'L');

        //Close and output PDF document
        return $pdf->Output('inventario.pdf', 'I');
    }
}

class Inventario extends \Tcpdf_Tcpdf {
    private $denominacion="";
    private $unidad_administrativa="";
    private $geografica="";
    private $num_headers=5;
    private $headers=array("","ELABORADO","REVISADO","APROBADO","CONFORME","CONFORME");
    private $nombres=array(0=>"NOMBRE Y APELLIDO",
                       1 => "LIC. ELI PARRA", 
                       2 => "LIC. HILDA LUNA",
                       3 => "LIC.AGUSTIN MONTILLA",
                       4 => "4",
                       5=> "5",
                );
      private  $cargos=array(0=>"CARGO",
                      1 => "TECNICO III",
                      2=> "JEFE DE DIVISION DE SERVICIOS ADMINISTRATIVOS Y FINANCIEROS",
                      3 => "DIRECTOR ADMINISTRATIVO REGIONAL",
                      4 => "4",
                      5 => "5",
            );
     private $tipoActivo = NULL; 
     private $container = NULL; 
	//Page header
	public function Header() {
		// Logo
                $image_file = 'bundles/inhack20inventario/images/logo_tsj.jpg';
		$this->SetFont('times', 'B', 5);
		// -----------------------------------------------------------------------------    

                $html= '
                    <table width="100%" border="1" rules="all" >
                    <tr>
                        <td width="11%" rowspan="2" align="center"><img width="40" height="40" src="'.$image_file.'" /></td>
                        <td width="58%" rowspan="2" align="center">DIRECCI&Oacute;N EJECUTIVA DE LA MAGISTRATURA<br/>
                            DIRECCI&Oacute;N ADMINISTRATIVA REGIONAL<br/>
                            DIVISI&Oacute;N DE SERVICIOS ADMINISTRATIVOS Y FINANCIEROS
                        <br/>DEPARTAMENTO DE BIENES NACIONALES</td>
                        <td width="24%" rowspan="2" align="center"><br/><br/>INVENTARIO DE BIENES NACIONALES<br/>
                        - MUEBLES A&Ntilde;O '.date("Y").'</td>
                        <td width="7%" height="23" align="center"><br/><br/>FECHA</td>
                    </tr>
                    <tr>
                        <td align="center">'.date("d/m/Y").'</td>
                    </tr>
                    <tr>
                        <td colspan="4" align="center" bgcolor="#CCCCCC">DENOMINACI&Oacute;N</td>
                    </tr>
                    <tr>
                        <td colspan="4" align="center">'.$this->denominacion.'</td>
                    </tr>
                    <tr>
                        <td colspan="4" align="center" bgcolor="#CCCCCC">UBICACI&Oacute;N</td>
                    </tr>
                    <tr>
                        <td colspan="2">UNIDAD ADMINISTRATIVA</td>
                        <td colspan="2">GEOGRAFICA</td>
                    </tr>
                    <tr>
                        <td colspan="2">'.$this->unidad_administrativa.'</td>
                        <td colspan="2">'.$this->geografica.'</td>
                    </tr>
                    </table>';
                
                $this->writeHTML($html, true, false, true, false, '');
                //CABECERA DEL TIPO DEL COMPROBANTE
              if($this->tipoActivo==$this->container->getParameter('ACTIVO_MOBILIARIO')){         
                    $html='
                    <table border="1">
                    <tr>
                        <td colspan="3" align="center" width="15%" bgcolor="#CCCCCC">CLASIFICACI&Oacute;N</td>
                        <td rowspan="2" align="center" width="5%" bgcolor="#CCCCCC"><br/><br/>N&deg; ID</td>
                        <td rowspan="2" align="center" width="72%" bgcolor="#CCCCCC"><br/><br/>DESCRIPCI&Oacute;N DE LOS BIENES</td>
                        <td rowspan="2" align="center" width="8%" bgcolor="#CCCCCC">VALOR UNITARIO (BSF)</td>
                    </tr>
                    <tr>
                        <td align="center" width="5%" bgcolor="#CCCCCC">GRUPO</td>
                        <td align="center" width="5%" bgcolor="#CCCCCC">SUB GRUPO</td>
                        <td align="center" width="5%" bgcolor="#CCCCCC">SECCION</td>
                    </tr>
                    </table>
                    ';
              }
              elseif($this->tipoActivo == $this->container->getParameter('ACTIVO_EQUIPO')){
                  $html='
                                <table width="100%" border="1" cellspacing="0" cellpadding="0" frame="void" rules="all">
                                        <tr align="center">
                                            <td height="17" bgcolor="#CCCCCC">Numero del Inventario</td>
                                                <td bgcolor="#CCCCCC">Marca</td>
                                                <td bgcolor="#CCCCCC">Modelo</td>
                                                <td bgcolor="#CCCCCC">Serial</td>
                                                <td bgcolor="#CCCCCC">Tipo</td>
                                        </tr>             
                                </table>   
                            ';
              }
                // -----------------------------------------------------------------------------                
                $this->writeHTML($html, true, false, true, false, '');
                         
                
	}

	// Page footer
	public function Footer() {
		// Position at 15 mm from bottom
		$this->SetY(-32);
		// Set font
		$this->SetFont('times', 'B', 5);
		// Page number
		//$this->Cell(0, 10, ''.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
                
       
        $data= array(
            0=>$this->nombres,
            1=>$this->cargos,
            );
        $w = array(20, 38, 45, 38,39);
        if($this->num_headers==5+1)
            $w = array(20, 30, 45, 34,26,25);
        // Colors, line width and bold font
        $this->SetFillColor(255, 255,255);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(0.3);
        $this->SetFont('', 'B', 5);
        // Header
        //$w = array(20, 30, 45, 35,30);
        //$num_headers = count($header);
        for($i = 0; $i < $this->num_headers; ++$i) {
            $this->Cell($w[$i], 3, $this->headers[$i], 1, 0, $align='C', $fill=1,$link=false, $stretch=0);
        }
        //$this->Cell($w, $h, $txt, $border, $ln, $align, $fill, $link, $stretch, $ignore_min_height, $calign, $valign)
        $this->Ln();
        // Color and font restoration
        $this->SetFillColor(224, 235, 255);
        $this->SetTextColor(0);
        $this->SetFont('');
        // Data
        $fill = 1;
        foreach($data as $row) {
            $this->Cell($w[0], 3, $row[0], 'LR', 0, 'C', $fill,$link=false, $stretch=1);
            $this->Cell($w[1], 3, $row[1], 'LR', 0, 'C', $fill,$link=false, $stretch=1);
            $this->Cell($w[2], 3, $row[2], 'LR', 0, 'C', $fill,$link=false, $stretch=1);
            $this->Cell($w[3], 3, $row[3], 'LR', 0, 'C', $fill,$link=false, $stretch=1);
            $this->Cell($w[4], 3, $row[4], 'LR', 0, 'C', $fill,$link=false, $stretch=1);
            if($this->num_headers==5+1)
                $this->Cell($w[5], 3, $row[5], 'LR', 0, 'C', $fill,$link=false, $stretch=1);
            $this->Ln();
            $fill=!$fill;
        }
        $data= array(
           0=>array(0=>"FIRMA Y SELLO",1 => "", 2=> "",3 => "", 4 => "", 5 => ""),
            );
        foreach($data as $row) {
            $this->Cell($w[0], 9, $row[0], 'LR', 0, 'C', $fill,$link=false, $stretch=1);
            $this->Cell($w[1], 9, $row[1], 'LR', 0, 'C', $fill,$link=false, $stretch=1);
            $this->Cell($w[2], 9, $row[2], 'LR', 0, 'C', $fill,$link=false, $stretch=1);
            $this->Cell($w[3], 9, $row[3], 'LR', 0, 'C', $fill,$link=false, $stretch=1);
            $this->Cell($w[4], 9, $row[4], 'LR', 0, 'C', $fill,$link=false, $stretch=1);
            if($this->num_headers==5+1)
                $this->Cell($w[5], 9, $row[5], 'LR', 0, 'C', $fill,$link=false, $stretch=1);
            $this->Ln();
            $fill=!$fill;
        }
        
            $this->Cell(array_sum($w), 0, '', 'T');
             // Position at 15 mm from bottom
        $this->SetY(-14);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        // Page number
        $this->Cell(0, 10,$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M');    
	}
        
        public function setDenominacion($denominacion) {
            $this->denominacion = $denominacion;
        }

        public function setUnidad_administrativa($unidad_administrativa) {
            $this->unidad_administrativa = $unidad_administrativa;
        }

        public function setGeografica($geografica) {
            $this->geografica = $geografica;
        }
        public function setNum_headers($num_headers) {
            $this->num_headers = $num_headers;
        }

        public function setNombres($nombres) {
            $this->nombres = $nombres;
        }

        public function setCargos($cargos) {
            $this->cargos = $cargos;
        }
        public function setHeaders($headers) {
            $this->headers = $headers;
        }
        public function setTipoActivo($tipoActivo) {
            $this->tipoActivo = $tipoActivo;
        }
        public function setContainer($container) {
            $this->container = $container;
        }


}//fin clase