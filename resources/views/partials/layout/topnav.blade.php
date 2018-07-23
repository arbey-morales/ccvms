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

            @role('red-frio|root')
            <li role="presentation" class="dropdown">
                <a href="/reporte-contenedor" class="dropdown-toggle info-number"  aria-expanded="false">
                <i class="fa fa-bell-o"></i>
                <span class="badge bg-green total-notificaciones"> </span>
                </a>
                <ul id="menu1" class="dropdown-menu list-unstyled msg_list notificaciones" role="menu">
                <!-- <li>
                    <a>
                    <span class="image"><img src="images/img.jpg" alt="Profile Image" /></span>
                    <span>
                        <span>John Smith</span>
                        <span class="time">3 mins ago</span>
                    </span>
                    <span class="message">
                        Film festivals used to be do-or-die moments for movie makers. They were where...
                    </span>
                    </a>
                </li>
                <li>
                    <a>
                    <span class="image"><img src="images/img.jpg" alt="Profile Image" /></span>
                    <span>
                        <span>John Smith</span>
                        <span class="time">3 mins ago</span>
                    </span>
                    <span class="message">
                        Film festivals used to be do-or-die moments for movie makers. They were where...
                    </span>
                    </a>
                </li>
                <li>
                    <a>
                    <span class="image"><img src="images/img.jpg" alt="Profile Image" /></span>
                    <span>
                        <span>John Smith</span>
                        <span class="time">3 mins ago</span>
                    </span>
                    <span class="message">
                        Film festivals used to be do-or-die moments for movie makers. They were where...
                    </span>
                    </a>
                </li>
                <li>
                    <a>
                    <span class="image"><img src="images/img.jpg" alt="Profile Image" /></span>
                    <span>
                        <span>John Smith</span>
                        <span class="time">3 mins ago</span>
                    </span>
                    <span class="message">
                        Film festivals used to be do-or-die moments for movie makers. They were where...
                    </span>
                    </a>
                </li>
                <li>
                    <div class="text-center">
                    <a>
                        <strong>See All Alerts</strong>
                        <i class="fa fa-angle-right"></i>
                    </a>
                    </div>
                </li> -->
                </ul>
            </li>
            @endrole


        </ul>
    </nav>
    </div>
</div>