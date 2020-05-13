<template>
            <main class="main">
            <!-- Breadcrumb -->
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Home</li>
                <li class="breadcrumb-item">Administrador</li>
                <li class="breadcrumb-item active">Usuarios</li>
            </ol>
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Usuarios
                        <button type="button" @click="abrirModal('usuario', 'registrar')"  class="btn btn-success btn-nuevo">
                            <i class="icon-plus"></i> <span style="margin-left:2%">Nuevo</span>
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control col-md-3" v-model="criterio">
                                      <option value="username">Nombre Usuario</option>
                                      <option value="codigo_empleado">Codigo Empleado</option>
                                    </select>
                                    <input type="text" v-model="buscar" @keyup.enter="listarUsuario(1, buscar, criterio)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listarUsuario(1, buscar, criterio)" class="btn btn-primary btn-buscar"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th style="text-align: center">ID</th>
                                    <th>Codigo empleado</th>
                                    <th>Nombre de usuario</th>
                                    <th>Rol asignado</th>
                                    <th>Activo</th>
                                    <th>Primer acceso</th>
                                    <th>Ultimo acceso</th>
                                    <th style="text-align: center">Opciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="usuario in arrayUsuario" :key="usuario.id_usuario">
                                    <td v-text="usuario.id_usuario" style="text-align: center"></td>
                                    <td v-text="usuario.codigo_empleado"></td>
                                    <td v-text="usuario.username"></td>
                                    <td v-text="usuario.nombre_rol"></td>
                                    <td v-text="usuario.activo"></td>
                                    <td v-text="usuario.first_session"></td>
                                    <td v-text="usuario.last_session"></td>
                                    <td style="text-align: center;">
                                        <button type="button" @click="abrirModal('usuario', 'actualizar', usuario)" class="btn btn-warning btn-sm btn-circle-text-white">
                                          <i class="icon-pencil"></i>
                                        </button>
                                        <button type="button" @click="abrirModalEliminar(usuario)" class="btn btn-danger btn-sm btn-circle">
                                          <i class="icon-trash"></i>
                                        </button>
                                    </td>
                                </tr>                                
                            </tbody>
                        </table>
                        <nav>
                            <ul class="pagination">
                                <li class="page-item" v-if="pagination.current_page > 1">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1,buscar, criterio)">Ant</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(page,buscar, criterio)" v-text="page"></a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1, buscar, criterio)">Sig</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>
            <!--Inicio del modal agregar/actualizar-->
            <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary" role="document" style="border-radius: 4px;">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" v-text="tituloModal"></h4>
                            <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                              <span aria-hidden="true" style="color:white">×</span>
                            </button>
                        </div>
                        <div class="modal-body" style="padding:4%">
                            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal"> 
                                <div class="form-group">
                                    <label for="username">Nombre de usuario</label>
                                    <input type="text" v-model="username" class="form-control" placeholder="Ingrese el nombre de usuario">
                                </div>
                                <div class="form-group">
                                    <label for="password">Contraseña</label>
                                    <input type="text" v-model="password" class="form-control" placeholder="Ingrese la contraseña">
                                </div>
                                <div class="form-group">
                                    <label for="rol">Rol</label>
                                    <select v-model="id_rol" class="form-control">
                                        <option value="0" selected>-- Seleccione rol --</option>
                                        <option v-for="rol in arrayRol" :key="rol.id_rol" :value="rol.id_rol" v-text="rol.nombre"></option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="empleado">Empleado</label>
                                    <select v-model="codigo_empleado" class="form-control">
                                        <option value="" selected>-- Seleccione empleado --</option>
                                        <option v-for="empleado in arrayEmpleado" :key="empleado.codigo_empleado" :value="empleado.codigo_empleado" v-text="empleado.nombres+' '+empleado.apellidos"></option>
                                    </select>
                                </div>
                                <div style="margin-left:5%" class="form-group form-check">
                                    <input type="checkbox" v-model="activo" class="form-check-input" placeholder="Ingrese estado del usuario">
                                    <label for="activo" class="form-check-label">Activo</label>
                                </div>
                                <div v-show="errorUsuario" class="form-group div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjUsuario" :key="error" v-text="error">

                                        </div>
                                    </div>
                                </div>                               
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary btn-circle-text-white" @click="cerrarModal()">Cerrar</button>
                            <button type="button" v-if="tipoAccion==1" @click="registrarRol()" class="btn btn-primary btn-circle">Guardar</button>
                            <button type="button" v-if="tipoAccion==2" @click="actualizarRol()"  class="btn btn-primary btn-circle">Actualizar</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!-- Fin del modal -->
            <!-- Inicio del modal Eliminar -->
            <div class="modal fade" :class="{'mostrar' : modalEliminar}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-danger" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Eliminar Rol</h4>
                            <button type="button" class="close" @click="cerrarModalEliminar()" aria-label="Close">
                              <span aria-hidden="true" style="color:white">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>Estas seguro de eliminar el Rol: <span v-text="nombre"></span>? </p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary btn-circle-text-white" @click="cerrarModalEliminar()">Cerrar</button>
                            <button type="button" class="btn btn-danger btn-circle" @click="eliminarRol(id_rol)">Eliminar</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!-- Fin del modal Eliminar -->
        </main>
</template>

<script>
    export default {
        data (){
            return {
                id_usuario: 0,
                id_rol: 0,
                nombre_rol: '',
                codigo_empleado: '',
                username: '',
                password: '',
                activo: 0,
                first_session: '',
                last_session: '',
                intentios_fallidos: '',
                arrayUsuario : [],
                arrayRol : [],
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                errorUsuario: 0,
                errorMostrarMsjUsuario: [],
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criterio : 'username',
                buscar : '',
                modalEliminar: 0,
                arrayEmpleado: [],
                nombre: '',
                nombres: '',
                apellidos: ''
            }
        },
        computed:{
            isActived: function(){
                return this.pagination.current_page;
            },
            //Calcula los elementos de la paginación
            pagesNumber: function() {
                if(!this.pagination.to) {
                    return [];
                }
                
                var from = this.pagination.current_page - this.offset; 
                if(from < 1) {
                    from = 1;
                }

                var to = from + (this.offset * 2); 
                if(to >= this.pagination.last_page){
                    to = this.pagination.last_page;
                }  

                var pagesArray = [];
                while(from <= to) {
                    pagesArray.push(from);
                    from++;
                }
                return pagesArray;             

            }
        },
        methods : {
            listarUsuario (page,buscar,criterio){
                let me=this;
                var url= '/usuario?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;
                axios.get(url).then(function (response) {
                    var respuesta  = response.data;
                    me.arrayUsuario = respuesta.usuarios.data;
                    me.pagination= respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            selectRol(){
                let me=this;
                var url= '/rol/selectRoles';
                axios.get(url).then(function (response) {
                    var respuesta  = response.data;
                    me.arrayRol = respuesta.roles;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            selectEmpleado(){
                let me=this;
                var url= '/empleado/selectEmpleado';
                axios.get(url).then(function (response) {
                    var respuesta  = response.data;
                    console.log(respuesta.empleados);
                    me.arrayEmpleado = respuesta.empleados;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            cambiarPagina(page,buscar,criterio){
                let me = this;
                //Actualiza la página actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esa página
                me.listarUsuario(page,buscar,criterio);
            },

            //ELIMINAR ROL
            eliminarRol(id_rol){
                let me=this;
                axios.put('/rol/eliminar', {
                    'id_rol': this.id_rol
                })
                .then(function (response) {
                    me.cerrarModalEliminar();
                    me.listarRol(1, '', 'nombre');
                }).catch(function (error) {
                    console.log(error);
                });
            },
            
            //ABRIR MODAL ELIMINAR
            abrirModalEliminar(data = []){
                this.modalEliminar = 1;
                this.nombre = data['nombre'];
                this.id_rol = data['id_rol'];
            },

            //CERRAR MODAL
            cerrarModalEliminar(){
                this.modalEliminar = 0;
                this.nombre = '';
                this.id_rol = 0;
            },

            //REGISTRAR OBJETO
            registrarRol(){
                if (this.validarRol()){
                    return;
                }

                let me=this;
                axios.post('/rol/registrar', {
                    'nombre': this.nombre,
                })
                .then(function (response) {
                    me.cerrarModal();
                    me.listarRol(1, '', 'nombre');
                }).catch(function (error) {
                    console.log(error);
                });
            },

            //ACTUALIZAR OBJETO
            actualizarRol(){
                if (this.validarRol()){
                    return;
                }

                let me=this;
                axios.put('/rol/actualizar', {
                    'nombre': this.nombre,
                    'id_rol': this.id_rol
                })
                .then(function (response) {
                    me.cerrarModal();
                    me.listarRol(1, '', 'nombre');
                }).catch(function (error) {
                    console.log(error);
                });
            },            

            //VALIDACION DE CAMPOS
            validarRol(){
                this.errorRol = 0;
                this.errorMostrarMsjRol = [];

                if (!this.nombre) this.errorMostrarMsjRol.push("El nombre del rol no puede estar vacío.");

                if (this.errorMostrarMsjRol.length) this.errorRol = 1;

                return this.errorRol;
            },

            //ABRIR MODAL REGISTRAR/ACTUALIZAR
            abrirModal(modelo, accion, data = []){
            switch(modelo)
            {
                case "usuario":
                    {
                        switch(accion){
                            case 'registrar':
                                {
                                    this.modal = 1;
                                    this.nombre = '';
                                    this.tituloModal = 'Registrar Usuario';
                                    this.tipoAccion = 1;
                                    break
                                }
                            case 'actualizar':
                                {
                                    this.modal = 1;
                                    this.nombre = data['nombre'];
                                    this.id_rol = data['id_rol'];
                                    this.tituloModal = 'Actualizar Usuario';
                                    this.tipoAccion = 2;
                                    break
                                }
                        }
                    }
                this.selectRol();   
                this.selectEmpleado(); 
            }
        },

        //CERRAR MODAL
        cerrarModal(){
            this.modal = 0;
            this.nombre = '';
            this.tituloModal = '';
            this.errorMostrarMsjRol = [];
            this.errorRol = 0;
        },

        },
        mounted() {
            this.listarUsuario(1, this.buscar, this.criterio);
        }
    }
</script>
<style>    
    .modal-content{
        width: 100% !important;
        position: absolute !important;
    }
    .mostrar{
        display: list-item !important;
        opacity: 1 !important;
        position: absolute !important;
        background-color: #3c29297a !important;
    }
    .div-error{
        display: flex;
        justify-content: center;
    }
    .text-error{
        color: red !important;
        font-weight: bold;
    }
    .text-error{
        color: red !important;
        font-weight: normal !important;
        font-style:italic;
    }
    .btn-circle{
        border-radius: 4px;
    }
    .btn-circle-text-white{
        border-radius: 4px; color:white;
    }
    .btn-nuevo{
        margin-left:1%;
        margin-top:0.2% !important;
        border-radius: 6px; 
        color:white !important;
    }
    .btn-buscar{
        border-top-right-radius:4px;
        border-bottom-right-radius:4px;
    }
</style>
