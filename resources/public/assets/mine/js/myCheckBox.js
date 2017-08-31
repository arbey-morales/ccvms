
    $(document).ready(function() {
        $('#activo').change(function(e){
            $('.switch-yes').removeClass('hidden show');
            $('.switch-not').removeClass('hidden show');
            if( $(this).prop('checked') ) {
                $('.switch-yes').addClass('show');
                $('.switch-not').addClass('hidden');
            }
            else {
                $('.switch-yes').addClass('hidden');
                $('.switch-not').addClass('show');
            }
        });

        $('#parrilla').change(function(e){
            $('.parrilla-yes').removeClass('hidden show');
            $('.parrilla-not').removeClass('hidden show');
            if( $(this).prop('checked') ) {
                $('.parrilla-yes').addClass('show');
                $('.parrilla-not').addClass('hidden');
            }
            else {
                $('.parrilla-yes').addClass('hidden');
                $('.parrilla-not').addClass('show');
            }
        });

        $('#aireAcondicionado').change(function(e){
            $('.ac-yes').removeClass('hidden show');
            $('.ac-not').removeClass('hidden show');
            if( $(this).prop('checked') ) {
                $('.ac-yes').addClass('show');
                $('.ac-not').addClass('hidden');
            }
            else {
                $('.ac-yes').addClass('hidden');
                $('.ac-not').addClass('show');
            }
        });

        $('#docPendientes').change(function(e){
            $('.doc-yes').removeClass('hidden show');
            $('.doc-not').removeClass('hidden show');
            if( $(this).prop('checked') ) {
                $('.doc-yes').addClass('show');
                $('.doc-not').addClass('hidden');
            }
            else {
                $('.doc-yes').addClass('hidden');
                $('.doc-not').addClass('show');
            }
        });
    });