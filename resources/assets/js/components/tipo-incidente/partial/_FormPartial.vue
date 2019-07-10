<template>
    <div class="form-group">
        <label>Descripcion</label>
        <input type="text" class="form-control" placeholder="Ingrese una descripción" name="descripcion" v-model="tipoIncidente.descripcion">
        <small id="passwordHelpBlock" class="form-text text-danger" v-if="errors.descripcion" v-for="error in errors.descripcion" >
            {{error}}
        </small>
    </div>
</template>

<script>
    export default {
        props: ['tipoIncidente', 'errors']
    }
</script>