<template>
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                <label for="nombre">
                    Nombre
                </label>
                <input type="text" class="form-control" name="nombre" placeholder="Ingrese nombre" id="nombre" v-model="directorio.nombre">
                <small class="form-text text-danger" v-if="errors.nombre"
                       v-for="error in errors.nombre">
                    {{error}}
                </small>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label for="telefono">
                    Telefono
                </label>
                <input type="text" class="form-control" name="telefono" placeholder="Ingrese telefono" id="telefono" v-model="directorio.telefono">
                <small class="form-text text-danger" v-if="errors.telefono"
                       v-for="error in errors.telefono">
                    {{error}}
                </small>
            </div>
        </div>
        <div class="col-sm-7">
            <div class="form-group">
                <label for="direccion">
                    Dirección
                </label>
                <input type="text" class="form-control" name="direccion" placeholder="Ingrese dirección" id="direccion" v-model="directorio.direccion">
                <small class="form-text text-danger" v-if="errors.direccion"
                       v-for="error in errors.direccion">
                    {{error}}
                </small>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['directorio', 'errors']
    }
</script>