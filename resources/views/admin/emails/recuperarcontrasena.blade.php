<div class="card">
    <div class="card-header">
        <h4 class="d-inline-flex">Recuperación de Contraseña</h4>
    </div>
    <div class="card-body">
        <table class="table table-striped table-hover">
            
            <tr>
                <td colspan="2">Estimado {{ $usuario }}</td>                
            </tr>

            <tr>
                <td colspan="2">Estás recibiendo este correo porque hiciste una solicitud de recuperación de contraseña para tu cuenta.</td>
            </tr>
           
            <tr>
                <td colspan="2">Su nueva contraseña es: {{ $clave }}</td>                
            </tr>
            
        </table>
    </div>
</div>