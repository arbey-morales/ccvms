@if(Auth::user()->activo==1)
<div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
    <div class="menu_section">
        <h3> {{Auth::user()->jurisdiccion->nombre}}</h3>
        <ul class="nav side-menu">
            <li>
                <a href="{{ url('/') }}"><i class="fa fa-home"></i> Inicio </a>
            </li>
            @permission('show.personas')
            <li>
                <a href="{{ route('persona.index') }}"><i class="fa fa-group"></i> Censo Nominal</a>
            </li>
            @endpermission
            @permission('show.catalogos')
            <li><a><i class="fa fa-archive"></i> Catálogos <span class="fa fa-chevron-down"></span></a>
                <ul class="nav child_menu">
                    <li><a href="{{ route('catalogo.ageb.index') }}">Ageb</a></li>
                    <li><a href="{{ route('catalogo.clue.index') }}">Clues</a></li>
                   <!-- <li><a href="{{ route('usuario.index') }}">Nacionalidads</a></li>
                    <li><a href="{{ route('usuario.index') }}">Vacunas</a></li>
                    <li><a href="{{ route('usuario.index') }}">Enfermedades</a></li>-->
                    <li><a href="{{ route('catalogo.esquema.index') }}">Esquemas</a></li>
                    <li><a href="{{ route('catalogo.municipio.index') }}">Municipios</a></li>
                    <li><a href="{{ route('catalogo.localidad.index') }}">Localidades</a></li>
                   <!-- <li><a href="{{ route('usuario.index') }}">Ciudades</a></li>
                    <li><a href="{{ route('usuario.index') }}">Colonias</a></li>-->
                </ul>
            </li>
            @endpermission
        </ul>
    </div>
    @role('admin|root')    
        <div class="menu_section">
            <h3>SISTEMA</h3>
            <ul class="nav side-menu">
                <li><a><i class="fa fa-universal-access"></i> Configuración <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                        @permission('show.usuarios')<li><a href="{{ route('usuario.index') }}">Usuarios</a></li>@endpermission
                        @role('root') 
                            @permission('show.roles')<li><a href="#">Roles</a></li>@endpermission
                            @permission('show.permissions')<li><a href="#">Permisos</a></li>@endpermission
                        @endrole 
                    </ul>
                </li>
            </ul>
        </div>
    @endrole
</div>
@endif