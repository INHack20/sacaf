<?php

namespace INHack20\InventarioBundle\Extras\ReportesPDF\Comprobante;
use INHack20\InventarioBundle\Entity\Comprobante;

/**
 * Description of Reporte
 *
 * @author INHACK20
 */
class ReporteComprobante {
    public function __construct(Comprobante $entity, $container){
        
        $l = Array();

        // PAGE META DESCRIPTORS --------------------------------------

        $l['a_meta_charset'] = 'UTF-8';
        $l['a_meta_dir'] = 'ltr';
        $l['a_meta_language'] = 'es';

        // TRANSLATIONS --------------------------------------
        $l['w_page'] = 'página';

        //============================================================+
        // END OF FILE
        //============================================================+
        
        // create new PDF document
        $pdf = new Reporte(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->setComprobante($entity);
        $pdf->setContainer($container);
        
        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Ing. Carlos Mendoza');
        $pdf->SetTitle('Comprobante');
        $pdf->SetSubject('Comprobante');
        $pdf->SetKeywords('INHack20');

        // set default header data
        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

        // set header and footer fonts
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        //set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, 95, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        //set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, 32);

        //set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        //set some language-dependent strings
        $pdf->setLanguageArray($l);

        // ---------------------------------------------------------

        // set font
        $pdf->SetFont('times', 'B', 5);

        // add a page
        $pdf->AddPage();

        $html='
            <table width="100%" border="1" cellspacing="0" cellpadding="0">';
        
        
        $cant=0;
        $total_suma=0;
        $activos = $entity->getActivos();
        
        if($entity->getTipoActivo() == $container->getParameter('ACTIVO_MOBILIARIO')){
            foreach ($activos as $activo)
            {
                $cant++;
                $html.='
                    <tr align="center">
                        <td width="11%" >'.$activo->getMobiliario()->getCodigoCatalogo().'</td>
                        <td width="7%" >'.$activo->getNroBienNacional().'</td>
                        <td width="4%" >'.$activo->getMobiliario()->getUnidadTributaria().'</td>
                        <td width="66%" align="left">&nbsp;&nbsp;'.$activo->getMobiliario()->getDescripcion().'</td>
                        <td width="12%" >'.number_format($activo->getMobiliario()->getValor(),2,',','.').'</td>
                    </tr>    
                ';
                $total_suma+=$activo->getMobiliario()->getValor();
            }
            if(count($activos)==0)
            {
                $html.='
                    <tr>
                        <td height="80" colspan="5" align="center"><br/><br/><br/><br/><br/><br/><strong>NO HAY MOBILIARIOS ASOCIADOS</strong></td>
                    </tr>
                    ';
            }
            else
            {
                $html.='
                    <tr align="center">
                        <td width="11%" ></td>
                        <td width="7%" ></td>
                        <td width="4%" ></td>
                        <td width="66%" ><strong>'.$cant .' MOVIMIENTO(S) DE BIENES NACIONALES</strong></td>
                        <td width="12%" ><strong>'.number_format($total_suma,2,',','.') .' BsF</strong></td>
                    </tr>
                    ';
            }
        }
        elseif($entity->getTipoActivo() == $container->getParameter('ACTIVO_EQUIPO')){
            foreach ($activos as $activo)
            {
                $cant++;
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
        
        if(count($activos)>0){
            if($entity->getNota()!=NULL && $entity->getNota()!="")
                {
                    $html.='
                        <tr>
                            <td colspan="5" align="left"><br/><br/>&nbsp;&nbsp;Nota: '.$entity->getNota().'<br/></td>
                        </tr>
                        ';
                }
        }
        
        $html.='</table>';

        $pdf->writeHTML($html, true, false, true, false, '');

        return $pdf->Output('comprobante.pdf', 'I');
    }
}


class Reporte extends \Tcpdf_Tcpdf {
        private $comprobante;
        private $container;
        
	//Page header
	public function Header() {
		// Logo
                $image_file = 'bundles/inhack20inventario/images/emblema1.jpg';
		//$this->Image($image_file, 10, 10, 15, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
		// Set font
		$this->SetFont('times', 'B', 5);
		// Title
		//$this->Cell(0, 0, '<< TCPDF Example 003 >>SSS', 1, 0, 'L', 0, '', 0, false, 'M', 'M');
        
        
        //CABECERA
        $html= '
                <table width="100%" border="0" align="center" cellpadding="0">
                    <tr>
                        <td  align="center"><img src="'.$image_file.'" width="142" height="84"></td>
                    </tr>
                    <tr>
                        <td>
                            <table cellspacing="0" cellpadding="0">
                                <tr>
                                    <td colspan="6" align="center">
                                        <strong>
                                            DIRECCION    EJECUTIVA DE LA MAGISTRATURA<br/>
                                            DIRECCION ADMINISTRATIVA REGIONAL DEL ESTADO APURE<br/>
                                            DIVISION DE SERVICIOS ADMINISTRATIVOS Y FINANCIEROS<br/>
                                            AREA DE BIENES
                                        </strong>
                                    </td>
                                </tr>
                            </table>        
                        </td>
                    </tr>
                </table>';
        
        $this->writeHTML($html, true, false, true, false, '');
         $html= '
               <table cellpadding="1" cellspacing="1" border="0"><tr>
               <td width="77%"></td>
                <td width="10%">
                   <table border="1" cellspacing="0" align="center">
                        <tr>
                            <td width="70">N&deg;</td>
                            <td width="70" align="center">'.$this->comprobante->getId().'</td>
                        </tr>
                        <tr>
                            <td>FECHA</td>
                            <td align="center">'.date('Y/m/d').'</td>
                        </tr>
                    </table>
                </td></tr></table>
            ';

              $this->writeHTML($html, true, false, true, false, '');
              $tipo_comprobante="";
              
              if($this->comprobante->getTipo()==$this->container->getParameter('COMPROBANTE_ENTREGA'))
                  $tipo_comprobante="COMPROBANTE DE ENTREGA";
              elseif($this->comprobante->getTipo()==$this->container->getParameter('COMPROBANTE_REASIGNACION'))
                  $tipo_comprobante="COMPROBANTE DE REASIGNACI&Oacute;N";
              elseif($this->comprobante->getTipo()==$this->container->getParameter('COMPROBANTE_DESINCORPORACION'))
                  $tipo_comprobante="COMPROBANTE DE DESINCORPORACI&Oacute;N";
              
              $html='<table width="100%" border="0" align="center" cellpadding="0" rules="all">
                  <tr>
                    <td align="center"><h2><strong>
                           '.$tipo_comprobante.'
                            </strong></h2></td>
                  </tr>
                </table>';
              
              $this->writeHTML($html, true, false, true, false, '');
              
              $html='<br/><br/><br/>';
              $this->writeHTML($html, true, false, true, false, '');
              
              $html='
              <table width="100%" border="1" align="center" cellpadding="0" cellspacing="0" rules="all">
              <tr>
                <td colspan="4" align="center" bgcolor="#CCCCCC">ORGANISMO</td>
              </tr>
              <tr>
                <td width="10%">C&oacute;digo</td>
                <td width="90%" colspan="3">Denominaci&oacute;n</td>
              </tr>
              <tr>
                  <td align="center">05</td>
                  <td colspan="3">DIRECCION EJECUTIVA DE LA MAGISTRATURA</td>
              </tr>
              <tr>
                <td colspan="4" align="center" bgcolor="#CCCCCC">UNIDAD ADMINISTRADORA</td>
              </tr>
              <tr>
                <td>C&oacute;digo</td>
                <td colspan="3">Denominaci&oacute;n</td>
              </tr>
              <tr>
                  <td align="center">39</td>
                  <td colspan="3">DIRECCION ADMINISTRATIVA REGIONAL APURE</td>
              </tr>
              <tr>
                <td colspan="4" align="center" bgcolor="#CCCCCC">DEPENDENCIA USUARIA(Unidad Solicitante)</td>
              </tr>
              <tr>
                <td>C&oacute;digo</td>
                <td colspan="3">Denominaci&oacute;n</td>
              </tr>
              <tr>
                  <td align="center">'.$this->comprobante->getUbicacion()->getCodigo().'</td>
                  <td colspan="3">'.$this->comprobante->getUbicacion()->getDependencia().'</td>
              </tr>
              
              <tr>
                <td colspan="4" align="center" bgcolor="#CCCCCC">ALMACEN</td>
              </tr>
              <tr>
                <td>C&oacute;digo</td>
                <td width="60%">Denominaci&oacute;n</td>
                <td width="30%" colspan="2" align="center">Responsable</td>
              </tr>
              <tr>
                <td rowspan="2" align="center">'.$this->comprobante->getAlmacencodigo().'</td>
                <td rowspan="2">'.$this->comprobante->getalmacendenominacion().'</td>
                <td width="10%" align="center">Cedula de Identidad</td>
                <td width="20%" align="center">Apellidos y nombres</td>
              </tr>
              <tr>
                  <td>'.$this->comprobante->getalmacenrespci().'&nbsp;</td>
                  <td>'.$this->comprobante->getalmacenrespapellnom().'&nbsp;</td>
              </tr>
           
            </table>
            ';
              
              $this->writeHTML($html, false, false, true, false, '');
              
              
              //CABECERA DEL TIPO DEL COMPROBANTE
              if($this->comprobante->getTipoActivo()==$this->container->getParameter('ACTIVO_MOBILIARIO')){
                    $html='
                        <table width="100%" border="1" cellspacing="0" cellpadding="0" frame="void" rules="all">
                                <tr align="center">
                                    <td height="17" width="11%" bgcolor="#CCCCCC">C&oacute;digo del Catalogo</td>
                                    <td width="7%" bgcolor="#CCCCCC">N&uacute;mero del Inventario</td>
                                    <td width="4%" bgcolor="#CCCCCC">U.T.</td>
                                    <td width="66%" bgcolor="#CCCCCC">Descripci&oacute;n</td>
                                    <td width="12%" bgcolor="#CCCCCC">Valor</td>
                                </tr>             
                        </table>   
                    ';
              }
              elseif($this->comprobante->getTipoActivo()==$this->container->getParameter('ACTIVO_EQUIPO')){
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
              else{
                  $html='ERROR CON TIPO DE ACTIVO DE COMPROBANTE ' . $this->comprobante->getTipoActivo();
              }
              
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
        
       
        if($this->comprobante->getTipo()==$this->container->getParameter('COMPROBANTE_ENTREGA'))
        {
            $firmas=$this->comprobante->getUbicacion()->getFirmas();
            $firma_nombre="NO ASIGNADO";
            $firma_cedula="NO ASIGNADO";
            $firma_cargo="NO ASIGNADO";

                if(count($firmas)>0)
                {
                    $firma_nombre=$firmas[0]->getNombreCompleto();
                    $firma_cedula=$firmas[0]->getCedula();
                    $firma_cargo=$firmas[0]->getCargo();
                }
        
            $html= '
            <table width="100%" border="1" cellspacing="0" cellpadding="0" frame="void" rules="all">
                        <tr>
                            <td width="48%" align="center" bgcolor="#CCCCCC"><strong>DESPACHADOR</strong></td>
                            <td width="48%" align="center" bgcolor="#CCCCCC"><strong>RECEPTOR</strong></td>
                        </tr>
                        <tr align="center">
                            <td>APELLIDOS Y NOMBRES</td>
                            <td>APELLIDOS Y NOMBRES</td>
                        </tr>
                        <tr align="center">
                            <td><strong>'.$this->comprobante->getUsuario()->getNombreCompleto().'&nbsp;</strong></td>
                            <td><strong>'.$firma_nombre.'&nbsp;</strong></td>
                        </tr>
                        <tr align="center">
                            <td>CEDULA DE IDENTIDAD</td>
                            <td>CEDULA DE IDENTIDAD</td>
                        </tr>
                        <tr align="center">
                            <td>'.$this->comprobante->getUsuario()->getCedula().'&nbsp;</td>
                            <td>'.$firma_cedula.'&nbsp;</td>
                        </tr>
                        <tr align="center">
                            <td>CARGO</td>
                            <td>CARGO</td>
                        </tr>
                        <tr align="center">
                            <td><strong>'.$this->comprobante->getUsuario()->getCargo().'&nbsp;</strong></td>
                            <td><strong>'.$firma_cargo.'&nbsp;</strong></td>
                        </tr>
                        <tr align="center">
                            <td height="47" valign="bottom"><strong>FIRMA Y SELLO</strong></td>
                            <td valign="bottom"><strong>FIRMA Y SELLO</strong></td>
                        </tr>
                </table>
            ';
        }
        elseif($this->comprobante->getTipo()==$this->container->getParameter('COMPROBANTE_REASIGNACION') 
                || $this->comprobante->getTipo()==$this->container->getParameter('COMPROBANTE_DESINCORPORACION'))
        {          
            $html= '
            <table width="100%" border="1" cellspacing="0" cellpadding="0" frame="void" rules="all">
                <tr>
                    <td colspan="3" bgcolor="#CCCCCC">&nbsp;&nbsp;Responsable Patrimonial Primario</td>
                </tr>
                <tr>
                    <td width="26%" align="center"><strong>CEDULA DE IDENTIDAD</strong></td>
                    <td width="44%" align="center"><strong>APELLIDOS Y NOMBRES</strong></td>
                    <td width="30%" align="center"><strong>CARGO</strong></td>
                </tr>
                <tr>
                    <td align="center">'.$this->comprobante->getUsuario()->getCedula().'</td>
                    <td align="center">'.$this->comprobante->getUsuario()->getNombreCompleto().'</td>
                    <td align="center">'.$this->comprobante->getUsuario()->getCargo().'</td>   
                </tr>   
            </table>               
            ';
        }
            $this->SetY(-40);
            $this->writeHTML($html, false, false, true, false, '');

                // Position at 15 mm from bottom
            $this->SetY(-14);
            // Set font
            $this->SetFont('helvetica', 'I', 8);
            // Page number
            $this->Cell(0, 10,$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M');    
	}
        
        public function setComprobante(Comprobante $comprobante) {
            $this->comprobante = $comprobante;
        }
        public function setContainer($container) {
            $this->container = $container;
        }



}