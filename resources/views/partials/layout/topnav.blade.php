<div class="top_nav">
    <div class="nav_menu">
    <nav class="" role="navigation">
        <div class="nav toggle">
        <a id="menu_toggle"><i class="fa fa-bars"></i></a>
        </div>
        <ul class="nav navbar-nav navbar-right">
            <li class="">
                <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                <img src="{{ url('storage/user/profile/'.Auth::user()->foto) }}" alt=""> {{ Auth::user()->nombre }}
                <span class=" fa fa-angle-down"></span>
                </a>
                <ul class="dropdown-menu dropdown-usermenu pull-right">
                <li><a href="{{ url('usuario/'.Auth::user()->id.'/edit') }}"> Editar perfil </a></li>
                <li>
                    <a href="javascript:;">
                    <span class="badge bg-red pull-right">50%</span>
                    <span>Configuración</span>
                    </a>
                </li>
                <li><a href="{{ url('storage/manual/ManualCCVMSv1.pdf') }}" download>Ayuda</a></li>
                <li><a href="{{ route('auth/logout') }}"><i class="fa fa-sign-out pull-right"></i> Salir</a></li>
                </ul>
            </li>
        </ul>
    </nav>
    </div>
</div>