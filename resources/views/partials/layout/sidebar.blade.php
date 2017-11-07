@if(Auth::user()->activo==1)
<div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
    <div class="menu_section">
        <h3> 
            @role('root') Acceso total @endrole 
            @role('admin') CENTRAL @endrole 
            @role('red-frio') Red de frío @endrole 
            @role('captura'){{Auth::user()->jurisdiccion->nombre}}@endrole
        </h3>
        <ul class="nav side-menu">
            <li>
                <a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> Tablero </a>
            </li>
            @role('admin|root|captura')
                @permission('show.personas')
                    <li>
                        <a href="{{ url('persona') }}"><i class="fa fa-group"></i> Censo Nominal</a>
                    </li>
                @endpermission
                @permission('show.cuadro_distribucion_jurisdiccional')
                    <li>
                        <a href="{{ route('cuadro-dist-juris.index') }}"><i class="fa fa-share-alt-square"></i> Cuadro distribución</a>
                    </li>
                @endpermission
            @endrole
            @role('red-frio|root')
                @permission('show.catalogos')
                <li>
                    <a href="{{ url('temperatura') }}"><i class="fa fa-share-alt-square"></i> Registro de temperaturas</a>
                </li>
                <li>
                    <a href="{{ url('mantenimiento') }}"><i class="fa fa-wrench"></i> Mantenimiento de equipos</a>
                </li>
                @endpermission
            @endrole
            
            <li><a><i class="fa fa-archive"></i> Catálogos <span class="fa fa-chevron-down"></span></a>
                <ul class="nav child_menu">
                @permission('show.catalogos')
                    @role('admin|captura|root')                    
                        <li><a href="{{ route('catalogo.ageb.index') }}">Ageb</a></li>
                        <li><a href="{{ route('catalogo.clue.index') }}">Clues</a></li>
                        <li><a href="{{ route('catalogo.esquema.index') }}">Esquemas</a></li>
                        <li><a href="{{ route('catalogo.municipio.index') }}">Municipios</a></li>
                        <li><a href="{{ route('catalogo.localidad.index') }}">Localidades</a></li>
                        <li><a href="{{ route('catalogo.colonia.index') }}">Colonias</a></li>
                    @endrole
                    @role('red-frio|root')        
                        <li><a href="{{ route('catalogo.marca.index') }}">Marca</a></li>
                        <li><a href="{{ route('catalogo.modelo.index') }}">Modelo</a></li>            
                        <li><a href="{{ route('catalogo.contenedor-biologico.index') }}">Contenedores de biológico</a></li>
                        <li><a href="{{ route('catalogo.estatus-contenedor.index') }}">Estatus contenedores</a></li>
                    @endrole 
                @endpermission  
                </ul>                
            </li>
            
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
                            <li><a href="{{ route('catalogo.poblacion-conapo.index') }}">Población CONAPO</a></li>
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