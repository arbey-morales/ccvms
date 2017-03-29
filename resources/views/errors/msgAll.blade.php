@if($errors->any())
    <div class="row">
        <div class="col-md-12">
            <div id="msgError" class="alert alert-warning alert-dismissible fade in" role="alert">
                <div class="row">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <div class="col-md-1">
                        <img src="{{ url('images/error/danger.png') }}" alt="" class="img-circle" width="55px">
                    </div>
                    <div class="col-md-11">
                        <h4 class="text-info">Debes localizar y corregir estos errores:</h4>
                        @foreach($errors->all() as $error)
                            <span style="color:#000 !important;"> {{ $error }}</span><br>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
@if(Session::has('flash_message_error'))
    <div class="row">
        <div class="col-md-12">
            <div id="msgDanger" class="alert alert-warning alert-dismissible fade in" role="alert">
                <div class="row">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <div class="col-md-1">
                        <img src="{{ url('images/error/danger.png') }}" alt="" class="img-circle" width="55px">
                    </div>
                    <div class="col-md-11">
                        <h4> {{ Session::get('flash_message_error') }}</h4>
                    </div>                    
                </div>
            </div>
        </div>
    </div>
@endif
@if(Session::has('flash_message_ok'))
    <div class="row">
        <div class="col-md-12">
            <div id="msgSuccess" class="alert alert-success alert-dismissible fade in" role="alert">
                <div class="row">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <div class="col-md-1">
                        <img src="{{ url('images/error/success.png') }}" alt="" class="img-circle" width="55px">
                    </div>
                    <div class="col-md-11">
                        <h4> {{ Session::get('flash_message_ok') }}</h4>
                    </div>                    
                </div>
            </div>
        </div>
    </div>
@endif
@if(Session::has('flash_message_info'))
    <div class="row">
        <div class="col-md-12">
            <div id="msgInfo" class="alert alert-info alert-dismissible fade in" role="alert">
                <div class="row">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <div class="col-md-1">
                        <img src="{{ url('images/error/info.png') }}" alt="" class="img-circle" width="55px">
                    </div>
                    <div class="col-md-11">
                        <h4> {{ Session::get('flash_message_info') }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif