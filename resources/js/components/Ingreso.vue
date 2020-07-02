<template>
        <main class="main">
            <!-- Breadcrumb -->
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Administrador</li>
                <li class="breadcrumb-item">Mantenimiento</li>
                <li class="breadcrumb-item">Permisos de acceso</li>
                <li class="breadcrumb-item active">Ingresos y Descuentos</li>
            </ol>
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Ingresos y descuentos
                        <button @click="abrirModal('privilegio', 'registrar')" type="button" class="btn btn-success btn-nuevo">
                            <i class="icon-plus"></i> <span style="margin-left:2%">Nuevo</span>
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control col-md-3" v-model="criterio">
                                      <option value="nombre">Nombre</option>
                                      <option value="accion">Acción</option>
                                      <option value="entidad">Entidad</option>
                                      <option value="todos">Todos</option>
                                    </select>
                                    <input type="text" v-model="buscar" @keyup.enter="listarPrivilegios(1, buscar, criterio)" class="form-control col-md-5" placeholder="Texto a buscar">
                                    <button type="submit" @click="listarPrivilegios(1, buscar, criterio)" class="btn btn-primary btn-buscar"><i class="fa fa-search"></i> Buscar</button>
                                </div>             
                            </div>
                        </div>
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th style="text-align: center">Codigo</th>
                                    <th>Apellidos</th>
                                    <th>Nombres</th>
                                    <th style="text-align: center">Opciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="empleado in arrayEmpleados" :key="empleado.codigo_empleado">
                                    <td v-text="empleado.codigo_empleado" style="text-align: center"></td>
                                    <td v-text="empleado.primer_nombre+' '+empleado.segundo_nombre"></td>
                                    <td v-text="empleado.primer_apellido+' '+empleado.segundo_apellido"></td>
                                    <td style="text-align: center;">
                                        <button type="button" @click="abrirModalIngresos()" class="btn btn-warning btn-sm btn-circle-text-white">
                                          <i class="icon-pencil"></i>
                                        </button> &nbsp;
                                        <button type="button" @click="abrirModalIngresos()" class="btn btn-warning btn-sm btn-circle-text-white">
                                          <i class="icon-pencil"></i>
                                        </button> &nbsp;
                                        <button type="button" @click="abrirModalEliminar()" class="btn btn-danger btn-sm btn-circle">
                                          <i class="icon-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <nav>
                            <ul class="pagination">
                                <li class="page-item" v-if="pagination.current_page > 1">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1, buscar, criterio)">Ant</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(page, buscar, criterio)" v-text="page"></a>
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
                            <h4 class="modal-title">Ingresos/Descuentos</h4>
                            <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                              <span aria-hidden="true" style="color:white">×</span>
                            </button>
                        </div>
                        <div class="modal-body" style="padding:4%">
                            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                                <div class="form-group">
                                    <label for="transaccion"> Tipo de operaciónn</label><br>
                                    <select @change="seleccionOperacion($event)" name="transaccion" class="form-control" v-model="accionT">
                                        <option value="" selected>-- Seleccione el tipo de operacion --</option>
                                        <option value="ing">Ingreso</option>
                                        <option value="des">Descuento</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="concepto">Concepto</label><br>       
                                    <select name="concepto" class="form-control">
                                        <option value="" selected>-- Seleccione el Concepto --</option>
                                        <option value="t1">Horas Extras - diurnas</option>
                                        <option value="t1">Horas Extras - nocturanas</option>
                                        <option value="t1">Bonificacion</option>
                                        <option value="t1">Comision</option>
                                        <option value="t1">Etc</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="descripcion">Descripcion</label><br>
                                    <textarea style="resize: none;" placeholder="Ingrese descripcion" class="form-control" name="descripcion" cols="60" rows="2"></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="cuantia">Cuantia</label><br>
                                    <input class="form-control" placeholder="Ingrese cantidad" name="cuantia" type="text">
                                </div>  
                                <div class="form-group">
                                    <label for="monto">Monto</label><br>
                                    <input class="form-control" placeholder="Ingrese monto" name="monto" type="text">
                                </div>                            
                            </form>
                            <br>
                            <table>
                                <thead>
                                    <th>Tipo</th>
                                    <th>Concepto</th>
                                    <th>Porcenjae</th>
                                    <th>Monto</th>
                                </thead>
                                <tbody>
                                    <tr>Tipo</tr>
                                    <tr>Concepto</tr>
                                    <tr>Porcenjae</tr>
                                    <tr>Monto</tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary btn-circle-text-white" @click="cerrarModal()">Cerrar</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!--Fin del modal-->
            <!-- Inicio del modal Eliminar -->
            <div class="modal fade" :class="{'mostrar' : modalEliminar}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-danger" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Eliminar Privilegio</h4>
                            <button type="button" class="close" @click="cerrarModalEliminar()" aria-label="Close">
                              <span aria-hidden="true" style="color:white">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>Estas seguro de eliminar el Privilegio: <span></span>? </p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary btn-circle-text-white" @click="cerrarModalEliminar()">Cerrar</button>
                            <button type="button" class="btn btn-danger btn-circle" @click="eliminarProvilegio(id_privilegio)">Eliminar</button>
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
        data(){
            return{
                //NECESARIAS
                modal: 0,
                tituloModal: '',
                tipoAccion: 0,
                pagination: {
                    'total': 0,
                    'current_page': 0,
                    'per_page': 0,
                    'last_page': 0,
                    'from': 0,
                    'to': 0,                    
                },
                offset: 3,
                criterio: 'primer_nombre',
                buscar: '',

                //ENTIDAD
                accion_ingreso: 0,
                accion_descuento: 0,
                arrayEmpleados: [],
                arrayIngresos: [],
                arrayDescuentos: [],
                arrayTiposInDe: [],
                modalEliminar: 0,
                accionT: ''
            }
        },

        //LOGICA DE PAGINACION
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

        //METODOS UTILIZADOS PARA ACCIONAR BOTONES Y VISUALIZAR MODALS
        methods: {

            //LISTAR OBJETOS
            listarEmpleados(page, buscar, criterio){
                if(criterio=='todos'){ criterio='primer_nombre'; buscar='';}

                let me=this;
                var url= '/ingresos?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                axios.get(url)
                .then(function (response) {
                    var respuesta= response.data;
                    me.pagination= respuesta.pagination;
                    me.arrayEmpleados = respuesta.empleados.data;
                    console.log(me.arrayEmpleados);
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                });
            },

            //PAGINACION: CAMBIO DE PAGINA
            cambiarPagina(page, buscar, criterio){
                let me = this;
                //Actualiza la página actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esa página
                me.listarEmpleados(page, buscar, criterio);
            },

/*             //REGISTRAR OBJETO
            registrarPrivilegio(){
                if (this.validarPrivilegio()){
                    return;
                }

                let me=this;
                axios.post('/privilegio/registrar', {
                    'nombre': this.accion+" "+this.entidad,
                    'accion': this.accion,
                    'entidad': this.entidad
                })
                .then(function (response) {
                    me.cerrarModal();
                    me.listarPrivilegios(1, '', 'nombre');
                }).catch(function (error) {
                    console.log(error);
                });
            }, */

/*             //ACTUALIZAR OBJETO
            actualizarPrivilegio(){
                if (this.validarPrivilegio()){
                    return;
                }

                let me=this;
                axios.put('/privilegio/actualizar', {
                    'nombre': this.accion+" "+this.entidad,
                    'accion': this.accion,
                    'entidad': this.entidad,
                    'id_privilegio': this.id_privilegio
                })
                .then(function (response) {
                    me.cerrarModal();
                    me.listarPrivilegios(1, '', 'nombre');
                }).catch(function (error) {
                    console.log(error);
                });
            },

            //VALIDACION DE CAMPOS
            validarPrivilegio(){
                this.errorPrivilegio = 0;
                this.errorMostrarMsjPrivilegio = [];

                if (!this.accion) this.errorMostrarMsjPrivilegio.push("La acción del privilegio no puede estar vacío.");
                if (!this.entidad) this.errorMostrarMsjPrivilegio.push("La entidad de contexto del privilegio no puede estar vacío.");

                if (this.errorMostrarMsjPrivilegio.length) this.errorPrivilegio = 1;

                return this.errorPrivilegio;
            }, */

            //DEFINIR TIPO DE OPERACION: INGRESO, DESCUENTO
            seleccionOperacion(event) {

            var me = this;
            console.log(event.target.value);
            console.log(this.accionT);

            var url= 'ingresos/selectTiposInDe?tipo='+this.accionT;
                axios.get(url)
                .then(function (response) {
                    var respuesta= response.data;
                    me.arrayTiposInDe = respuesta.tipos;
                    console.log("B: "+JSON.stringify(me.arrayTiposInDe));
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                });
        },

            //ABRIR MODAL INGRESOS DESCUENTOS
            abrirModalIngresos(){
                this.modal = 1;
            },

            //CERRAR MODAL
            cerrarModalIngresos(){
                this.modal = 0;
                this.accion_ingreso = 0;
                this.accion_descuento = 0;
            },

/*             //ABRIR MODAL REGISTRAR/ACTUALIZAR
            abrirModal(modelo, accion, data = []){
            switch(modelo)
            {
                case "privilegio":
                    {
                        switch(accion){
                            case 'registrar':
                                {
                                    this.modal = 1;
                                    this.nombre = '';
                                    this.accion = '';
                                    this.entidad = '';
                                    this.tituloModal = 'Registrar Privilegio';
                                    this.tipoAccion = 1;
                                    break
                                }
                            case 'actualizar':
                                {
                                    this.modal = 1;
                                    this.accion = data['accion'];
                                    this.entidad = data['entidad'];
                                    this.id_privilegio = data['id_privilegio'];
                                    this.tituloModal = 'Actualizar Privilegio';
                                    this.tipoAccion = 2;
                                    break
                                }
                        }
                    }
            }
        },

        //ELIMINAR PRIVILEGIO
        eliminarProvilegio(id_privilegio){
            let me=this;
            axios.put('/privilegio/eliminar', {
                'id_privilegio': this.id_privilegio
            })
            .then(function (response) {
                me.cerrarModalEliminar();
                me.listarPrivilegios(1, '', 'nombre');
            }).catch(function (error) {
                console.log(error);
            });
        },
        */
        
        //ABRIR MODAL ELIMINAR
        abrirModalEliminar(data = []){
            this.modalEliminar = 1;
        },


        //CERRAR MODAL
        cerrarModalEliminar(){
            this.modalEliminar = 0;
        },

    },

    //PARTE DEL CICLO DE VIDA DE VUE
    mounted() {
        this.listarEmpleados(1, this.buscar, this.criterio);
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
