<div class="profile">
    <div class="profile_pic">
    <img src="{{ url('storage/user/profile/'.Auth::user()->foto) }}" alt="..." class="img-circle profile_img">
    </div>
    <div class="profile_info">
    <span> 
    <!-- Roles -->
    </span>
    <h2>@role('admin|root') OFICINA @endrole @role('captura') {{Auth::user()->jurisdiccion->clave}} <small>Jurisdicción</small>@endrole
    </h2>
    </div>
</div>