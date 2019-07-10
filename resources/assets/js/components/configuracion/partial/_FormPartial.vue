<template>
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                <label for="nombre">
                    Nombre
                </label>
                <input type="text" class="form-control" name="nombre" placeholder="Ingrese nombre" id="nombre" v-model="configuracion.nombre">
                <small class="form-text text-danger" v-if="errors.nombre"
                       v-for="error in errors.nombre">
                    {{error}}
                </small>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label for="valor">
                    Valor
                </label>
                <input type="text" class="form-control" name="valor" placeholder="Ingrese valor" id="valor" v-model="configuracion.valor">
                <small class="form-text text-danger" v-if="errors.valor"
                       v-for="error in errors.valor">
                    {{error}}
                </small>
            </div>
        </div>
        <div class="col-sm-7">
            <div class="form-group">
                <label for="descripcion">
                    Descripción
                </label>
                <input type="text" class="form-control" name="descripcion" placeholder="Ingrese dirección" id="descripcion" v-model="configuracion.descripcion">
                <small class="form-text text-danger" v-if="errors.descripcion"
                       v-for="error in errors.descripcion">
                    {{error}}
                </small>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['configuracion', 'errors']
    }
</script>