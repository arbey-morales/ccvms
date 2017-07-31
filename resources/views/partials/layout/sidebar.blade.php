@if(Auth::user()->activo==1)
<div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
    <div class="menu_section">
        <h3> @role('admin|root') CENTRAL @endrole @role('captura'){{Auth::user()->jurisdiccion->nombre}}@endrole</h3>
        <ul class="nav side-menu">
            <li>
                <a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> Tablero </a>
            </li>
            @permission('show.personas')
            <li>
                <a href="{{ route('persona.index') }}"><i class="fa fa-group"></i> Censo Nominal</a>
            </li>
            @endpermission
            <!--@permission('show.cuadro_distribucion_jurisdiccional')
            <li>
                <a href="{{ route('cuadro-dist-juris.index') }}"><i class="fa fa-share-alt-square"></i> Cuadro distribución</a>
            </li>
            @endpermission-->
            @permission('show.personas')
            <li><a><i class="fa fa-book"></i> Reportes <span class="fa fa-chevron-down"></span></a>
                <ul class="nav child_menu">
                    <li><a href="{{ url('persona/reporte') }}">Censo Nominal</a></li>
                    <!--<li><a href="{{ url('cobertura/reporte') }}">Coberturas</a></li>-->
                </ul>
            </li>
            @endpermission
            @permission('show.catalogos')
            <li><a><i class="fa fa-archive"></i> Catálogos <span class="fa fa-chevron-down"></span></a>
                <ul class="nav child_menu">
                    <li><a href="{{ route('catalogo.ageb.index') }}">Ageb</a></li>
                    <li><a href="{{ route('catalogo.clue.index') }}">Clues</a></li>
                    <li><a href="{{ route('catalogo.esquema.index') }}">Esquemas</a></li>
                    <li><a href="{{ route('catalogo.municipio.index') }}">Municipios</a></li>
                    <li><a href="{{ route('catalogo.localidad.index') }}">Localidades</a></li>
                </ul>
            </li>
            @endpermission
        </ul>
    </div>
    @role('admin|root')
        <!-- SECCIÓN SÓLO OPERADOR ESTATAL -->
        <div class="menu_section">
            <h3>OTROS AJUSTES</h3>
            <ul class="nav side-menu">
                <li><a><i class="fa fa-tasks"></i> Info. CCVMS  <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                        @permission('show.catalogos')
                            <li><a href="{{ route('usuario.index') }}">Coberturas RENAPO</a></li>
                            <li><a href="{{ route('usuario.index') }}">Esquema de vacunación</a></li>
                        @endpermission
                    </ul>
                </li>
                <li><a><i class="fa fa-universal-access"></i> Seguridad <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                        @permission('show.usuarios')<li><a href="{{ route('usuario.index') }}">Usuarios</a></li>@endpermission
                        @permission('show.usuarios')<li><a href="{{ route('monitoreo.index') }}">Monitoreo</a></li>@endpermission
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