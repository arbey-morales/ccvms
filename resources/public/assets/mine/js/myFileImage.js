$(document).ready(function() {
    $('input[type=file]').bootstrapFileInput();
    $('.file-inputs').bootstrapFileInput();

    $('input[type=file]').change(function(e){
        if(this.name=='foto'){
            mostrarImagen(this);
        }
        if(this.name=='licencia'){
            var name = this.name;
            mostrar(this,name);
        }
        if(this.name=='comprobanteDomicilio'){
            var name = this.name;
            mostrar(this,name);
        }
        if(this.name=='ine'){
            var name = this.name;
            mostrar(this,name);
        }
        if(this.name=='contrato'){
            var name = this.name;
            mostrar(this,name);
        }
        if(this.name=='seguro'){
            var name = this.name;
            mostrar(this,name);
        }
        if(this.name=='pagare'){
            var name = this.name;
            mostrar(this,name);
        }
    });
});

var extensiones_permitidas = new Array("jpg", "jpeg");

function mostrarImagen(input) {
    $('#yes-image').removeClass('hidden show');
    $('#no-image').removeClass('hidden show');
    var permitida = false;
    
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) { 
            var ext = input.files[0].name;
            ext = ext.split(".");
            var extension = ext[1];
            for (var i = 0; i < extensiones_permitidas.length; i++) { 
                if (extensiones_permitidas[i] == extension) { 
                    permitida = true; 
                    break; 
                } 
            }
            $('#img_destino,#img_destinop').attr('src', e.target.result);
            if (!permitida) {
                $('#yes-image').addClass('hidden');
                $('#no-image').addClass('show');
            } else {
                $('#no-image').addClass('hidden');
                $('#yes-image').addClass('show');
            }
            
        }
        reader.readAsDataURL(input.files[0]);
    }
}

function mostrar(input,name) {
    $('#yes-'+name).removeClass('hidden show');
    $('#no-'+name).removeClass('hidden show');
    var permitida = false;
    
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) { 
            var ext = input.files[0].name;
            ext = ext.split(".");
            var extension = ext[1];
            for (var i = 0; i < extensiones_permitidas.length; i++) { 
                if (extensiones_permitidas[i] == extension) { 
                    permitida = true; 
                    break; 
                } 
            }
            $('#img_destino_'+name).attr('src', e.target.result);
            if (!permitida) {
                $('#yes-'+name).addClass('hidden');
                $('#no-'+name).addClass('show');
            } else {
                $('#no-'+name).addClass('hidden');
                $('#yes-'+name).addClass('show');
            }
            
        }
        reader.readAsDataURL(input.files[0]);
    }
}
