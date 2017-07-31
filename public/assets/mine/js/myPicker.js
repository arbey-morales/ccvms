$(document).ready(function() {
    
    init_fecha_aplicacion();

    $("#fecha_nacimiento,#fecha").daterangepicker({
        showDropdowns: true,
        singleDatePicker: true,
        calender_style: "picker_1",
        format: 'DD-MM-YYYY',
        locale: {
            "format": "MM/DD/YYYY",
            "separator": " - ",
            "applyLabel": "Aceptar",
            "cancelLabel": "Cancelar",
            "fromLabel": "Del",
            "toLabel": "Al",
            "customRangeLabel": "Custom",
            "daysOfWeek": [
                "Dom",
                "Lun",
                "Mar",
                "Mié",
                "Jue",
                "Vie",
                "Sab"
            ],
            "monthNames": [
                "Enero",
                "Febrero",
                "Marzo",
                "Abril",
                "Mayo",
                "Junio",
                "Julio",
                "Agosto",
                "Septiembre",
                "Octubre",
                "Noviembre",
                "Diciembre"
            ],
            "firstDay": 1
        }
    }, function(start, end, label) {
        setTimeout(function(){  
            var fn_validar = replaceAll($("#fecha_nacimiento").val(),"-", "/");           
            if(validarFormatoFecha(fn_validar)){ 
                if(existeFecha(fn_validar)){
                    var born_date = fn_validar.split('/');
                    get_esquema(born_date[2], $("#fecha_nacimiento").val());
                }
            }
        }, 500);
    });
});

function init_fecha_aplicacion(){
    $("input[name*='fecha_aplicacion'], input[name*='-fecha-caducidad'], #fecha_nacimiento_tutor").daterangepicker({
        showDropdowns: true,
        singleDatePicker: true,
        calender_style: "picker_1",
        format: 'DD-MM-YYYY',
        locale: {
            "format": "MM/DD/YYYY",
            "separator": " - ",
            "applyLabel": "Aceptar",
            "cancelLabel": "Cancelar",
            "fromLabel": "Del",
            "toLabel": "Al",
            "customRangeLabel": "Custom",
            "daysOfWeek": [
                "Dom",
                "Lun",
                "Mar",
                "Mié",
                "Jue",
                "Vie",
                "Sab"
            ],
            "monthNames": [
                "Enero",
                "Febrero",
                "Marzo",
                "Abril",
                "Mayo",
                "Junio",
                "Julio",
                "Agosto",
                "Septiembre",
                "Octubre",
                "Noviembre",
                "Diciembre"
            ],
            "firstDay": 1
        }
    }, function(start, end, label) {
        
    });
}