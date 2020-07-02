<template>
        <main class="main">
            <!-- Breadcrumb -->
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Jefe de RRHH</li>
                <li class="breadcrumb-item">Mantenimiento</li>
                <li class="breadcrumb-item">Gestión de Puestos y Salarios</li>
                <li class="breadcrumb-item active">Categorías de Comisión</li>
            </ol>
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Categorías de Comisión
                        <button @click="abrirModal('categoriaComision', 'registrar')" type="button" class="btn btn-success btn-nuevo">
                            <i class="icon-plus"></i> <span style="margin-left:2%">Nuevo</span>
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control col-md-5" v-model="criterio">
                                      <!-- <option value="id_categoria_comision">ID</option> -->
                                      <option value="ventas_minimas">Ventas Mínimas</option>
                                      <option value="ventas_maximas">Ventas Máximas</option>
                                      <!-- <option value="codigo_puesto">Código de Puesto</option> -->
                                      <option value="porcentaje">Porcentaje</option>
                                      <!-- <option value="todos">Todos</option> -->
                                    </select>
                                    <input type="text" v-model="buscar" @keyup.enter="listarCategoriasComision(1, buscar, criterio)" class="form-control col-md-5" placeholder="Texto a buscar">
                                    <button type="submit" @click="listarCategoriasComision(1, buscar, criterio)" class="btn btn-primary btn-buscar"><i class="fa fa-search"></i> Buscar</button>
                                </div>             
                            </div>
                        </div>
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th style="text-align: center">No.</th>
                                    <th>Ventas Mínimas</th>
                                    <th>Ventas Máximas</th>
                                    <!-- <th>Código de Puesto</th> -->
                                    <th>Porcentaje (%)</th>
                                    <th style="text-align: center">Opciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="categoriaComision in arrayCategoriaComision" :key="categoriaComision.id_categoria_comision">
                                    <td v-text="categoriaComision.id_categoria_comision" style="text-align: center"></td>
                                    <td v-text="categoriaComision.ventas_minimas" style="text-align: right"></td>
                                    <td v-text="categoriaComision.ventas_maximas" style="text-align: right"></td>
                                    <!-- <td v-text="categoriaComision.codigo_puesto" style="text-align: center"></td> -->
                                    <td v-text="categoriaComision.porcentaje" style="text-align: center"></td>
                                    <td style="text-align: center;">
                                        <button type="button" @click="abrirModal('categoriaComision', 'actualizar', categoriaComision)" class="btn btn-warning btn-sm btn-circle-text-white">
                                          <i class="icon-pencil"></i>
                                        </button> &nbsp;
                                        <button type="button" @click="abrirModalEliminar(categoriaComision)" class="btn btn-danger btn-sm btn-circle">
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
                <!-- Fin de tabla Listado -->
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
                                    <label for="ventas_minimas">Ventas Mínimas</label>
                                    <input type="number" step="0.01" min="0" v-model="ventas_minimas" class="form-control" placeholder="00.00">
                                </div>
                                <div class="form-group">
                                    <label for="ventas_maximas">Ventas Máximas</label>
                                    <input type="number" step="0.01" min=0 v-model="ventas_maximas" class="form-control" placeholder="00.00">
                                </div>
                                <div class="form-group">
                                    <label for="porcentaje">Porcentaje</label>
                                    <input type="number" step="0.01" min=0 max=100 v-model="porcentaje" class="form-control" placeholder="00.00">
                                </div>                                    
                                <div v-show="errorCategoriaComision" class="form-group div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjCategoriaComision" :key="error" v-text="error">

                                        </div>
                                    </div>
                                </div>                                
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary btn-circle-text-white" @click="cerrarModal()">Cerrar</button>
                            <button type="button" v-if="tipoAccion==1" @click="registrarCategoriaComision()" class="btn btn-primary btn-circle">Guardar</button>
                            <button type="button" v-if="tipoAccion==2" @click="actualizarCategoriaComision()"  class="btn btn-primary btn-circle">Actualizar</button>
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
                            <h4 class="modal-title">Eliminar Categoría de Comisión</h4>
                            <button type="button" class="close" @click="cerrarModalEliminar()" aria-label="Close">
                              <span aria-hidden="true" style="color:white">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>¿Estas seguro de eliminar la categoría de comisión? </p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary btn-circle-text-white" @click="cerrarModalEliminar()">Cerrar</button>
                            <button type="button" class="btn btn-danger btn-circle" @click="eliminarCategoriaComision(id_categoria_comision)">Eliminar</button>
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
                id_categoria_comision:0,
                ventas_minimas:0,
                ventas_maximas:0,
                codigo_puesto:'',
                porcentaje:0,
                arrayCategoriaComision: [],
                modal: 0,
                tituloModal: '',
                tipoAccion: 0,
                errorCategoriaComision: 0,
                errorMostrarMsjCategoriaComision: [],
                pagination: {
                    'total': 0,
                    'current_page': 0,
                    'per_page': 0,
                    'last_page': 0,
                    'from': 0,
                    'to': 0,                    
                },
                offset: 3,
                criterio: 'ventas_minimas',
                buscar: '',
                modalEliminar: 0
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
            listarCategoriasComision(page, buscar, criterio){
                if(criterio=='todos'){ criterio='id_categoria_comision'; buscar='';}//Antes decía 'Nombre'

                let me=this;
                var url= '/categoriaComision?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                axios.get(url)
                .then(function (response) {
                    var respuesta= response.data;
                    me.arrayCategoriaComision = respuesta.categorias_comisiones.data;
                    me.pagination= respuesta.pagination;
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
                me.listarCategoriasComision(page, buscar, criterio);
            },

            //REGISTRAR OBJETO
            registrarCategoriaComision(){
                if (this.validarCategoriaComision()){
                    return;
                }

                let me=this;
                axios.post('/categoriaComision/registrar', {
                    'ventas_minimas': this.ventas_minimas,
                    'ventas_maximas': this.ventas_maximas,
                    'codigo_puesto': this.codigo_puesto,
                    'porcentaje': this.porcentaje
                })
                .then(function (response) {
                    me.cerrarModal();
                    me.listarCategoriasComision(1, '', 'id_categoria_comision');//Antes decía nombre
                }).catch(function (error) {
                    console.log(error);
                });
            },

            //ACTUALIZAR OBJETO
            actualizarCategoriaComision(){
                if (this.validarCategoriaComision()){
                    return;
                }

                let me=this;
                axios.put('/categoriaComision/actualizar', {
                    'ventas_minimas': this.ventas_minimas,
                    'ventas_maximas': this.ventas_maximas,
                    'codigo_puesto': this.codigo_puesto,
                    'porcentaje': this.porcentaje,
                    'id_categoria_comision': this.id_categoria_comision
                })
                .then(function (response) {
                    me.cerrarModal();
                    me.listarCategoriasComision(1, '', 'id_categoria_comision');//Probar cambiar a ventas_minimas
                }).catch(function (error) {
                    console.log(error);
                });
            },

            //VALIDACION DE CAMPOS
            validarCategoriaComision(){
                this.errorCategoriaComision = 0;
                this.errorMostrarMsjCategoriaComision = [];

                if (!this.ventas_minimas) this.errorMostrarMsjCategoriaComision.push("La cantidad de ventas mínimas no puede estar vacía.");
                if (!this.ventas_maximas) this.errorMostrarMsjCategoriaComision.push("La cantidad de ventas máximas no puede estar vacía.");
                if (this.ventas_minimas<0.00 || this.ventas_maximas<0.00) this.errorMostrarMsjCategoriaComision.push("Las ventas deben de ser mayor que 0");
                if (!this.codigo_puesto) this.errorMostrarMsjCategoriaComision.push("Debe seleccionar el puesto?.");
                if (!this.porcentaje || this.porcentaje<0 || this.porcentaje>100) this.errorMostrarMsjCategoriaComision.push("El porcentaje debe ser un número entre 0 y 100");

                if (this.errorMostrarMsjCategoriaComision.length) this.errorCategoriaComision = 1;

                return this.errorCategoriaComision;
            },

            //CERRAR MODAL
            cerrarModal(){
                this.modal = 0;
                this.id_categoria_comision = '';
                this.ventas_minimas = '';
                this.ventas_maximas = '';
                this.codigo_puesto = '';
                this.porcentaje = '';
                this.tituloModal = '';
                this.errorMostrarMsjCategoriaComision = [];
                this.errorCategoriaComision = 0;
            },

            //ABRIR MODAL REGISTRAR/ACTUALIZAR
            abrirModal(modelo, accion, data = []){
            switch(modelo)
            {
                case "categoriaComision":
                    {
                        switch(accion){
                            case 'registrar':
                                {
                                    this.modal = 1;
                                    this.id_categoria_comision = '';
                                    this.ventas_minimas = '';
                                    this.ventas_maximas = '';
                                    this.codigo_puesto = 'VENT01';
                                    this.porcentaje = '';
                                    this.tituloModal = 'Registrar Categoría de Comisión';
                                    this.tipoAccion = 1;
                                    break
                                }
                            case 'actualizar':
                                {
                                    this.modal = 1;
                                    this.ventas_minimas = data['ventas_minimas'];
                                    this.ventas_maximas = data['ventas_maximas'];
                                    this.codigo_puesto = data['codigo_puesto'];
                                    this.porcentaje = data['porcentaje'];
                                    this.id_categoria_comision = data['id_categoria_comision'];
                                    this.tituloModal = 'Actualizar Categoría de Comisión';
                                    this.tipoAccion = 2;
                                    break
                                }
                        }
                    }
            }
        },

        //ELIMINAR Categoría de Comisión
        eliminarCategoriaComision(id_categoria_comision){
            let me=this;
            axios.put('/categoriaComision/eliminar', {
                'id_categoria_comision': this.id_categoria_comision
            })
            .then(function (response) {
                me.cerrarModalEliminar();
                me.listarCategoriasComision(1, '', 'id_categoria_comision');//Antes decía "nombre"
            }).catch(function (error) {
                console.log(error);
            });
        },
        
        //ABRIR MODAL ELIMINAR
        abrirModalEliminar(data = []){
            this.modalEliminar = 1;
            //this.nombre = data['nombre']; //¿Qué debería poner aquí?
            this.id_categoria_comision = data['id_categoria_comision'];
        },

        //CERRAR MODAL
        cerrarModalEliminar(){
            this.modalEliminar = 0;
            this.nombre = '';
            this.id_categoria_comision = 0;
        },

    },

    //PARTE DEL CICLO DE VIDA DE VUE
    mounted() {
        this.listarCategoriasComision(1, this.buscar, this.criterio);
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
