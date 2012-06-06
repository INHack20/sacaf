/* 
    INHACK20
 */
function cargarContenido(pagina1)
{
  $(document).ready(function(){
        cargando();
        $("#contenido").load(pagina1,function(){
            listoCargado();
        });
    });
}
function cargarContenidoParamCapa(pagina,parametros,capa)
{
  $(document).ready(function(){
        var v=pagina+".php?"+parametros;
        cargando(false);
        $("#"+capa).load(pagina+".php?"+parametros,function()
    {
        listoCargado();
        
    });
    });
}
function cargarContenidoCapa(pagina,capa)
{
  $(document).ready(function(){
        cargando();
        $("#"+capa).load(pagina,function(){
            listoCargado();
        });
    });
}

//funcion para logo cargando
function cargando()
{
    $("#cargando").css("display", "block");
}
function listoCargado()
{
    $("#cargando").css("display", "none");
    
}
function cargarNuevaPestana(pagina,parametros)
{
    var url=pagina+".php?"+parametros;
    url=url.replace(/ /g,"+");
    window.open(url);
}
$(document).ready(function(){
    //evento para reporte pop
    $('a.new-tab-reporte_2').click(function(event){
   
                             event.preventDefault();
                             
                            window.open($(this).attr('href')+"?txt_reporte="+txt_reporte+"&accion="+accion+"&bus=");
     });
     
});

//funcion para remover un item seleccionado de un select
function removerItemSelect(idSelect)
{
    
    $(document).ready(function(){
        var valor= $("#"+idSelect).val();
        if(valor!=null)
            {
               
                $("#"+idSelect).find("option[value='"+valor+"']").remove();
               
            }
    });
}
//funcion que se encarga se seleccionar todos los valores de mis selects
function eviarSelectsCompletos()
{  
    
      $("#lista_sub_comprobante option").each(function(){
            $(this).attr("selected","true");
            
        });
        
}
//funcion para calcular el total de los activos en los bienes nacionales
function sumarTotalActivos()
{
    var v=0;
    var total=0;
    $("#lista_sub_comprobante option").each(function(){
        v=$(this).val();
        v=v.split("|");
        total+=parseFloat(v[8]);
        
    });
    $("#capa_total").html(total.toFixed(2) +" Bs.");
}