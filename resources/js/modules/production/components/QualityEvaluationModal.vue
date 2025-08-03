<template>
  <div class="modal fade" ref="modal" tabindex="-1" aria-labelledby="QualityEvalLabel" aria-hidden="true"
       id="QualityEvalLabel">
    <div class="modal-dialog">
      <div class="modal-content border border-dark" style="border-radius: 16px;">
        <!-- Selector de tipo -->
        <div class="modal-header d-flex align-items-center justify-content-between"
             style="border-bottom:1px solid #222;">
          <div class="d-flex flex-column flex-grow-1">
            <div class="fw-bold mb-2" style="font-size:1.2rem;">SELECCIONAR</div>
            <div class="d-flex">
              <button
                  class="btn btn-outline-dark flex-fill me-1"
                  :class="{ active: evalType === 'MATERIAL' }"
                  @click.prevent="evalType = 'MATERIAL'"
                  style="font-size:1rem;">
                MATERIAL
              </button>
              <button
                  class="btn btn-outline-dark flex-fill"
                  :class="{ active: evalType === 'SERVICIO' }"
                  @click.prevent="evalType = 'SERVICIO'"
                  style="font-size:1rem;">
                SERVICIO
              </button>
            </div>
          </div>
          <button class="btn btn-outline-dark p-1 ms-2" style="border-radius:100%; width:32px; height:32px;"
                  @click.prevent="closeModal">
            <i class="fas fa-caret-down"></i>
          </button>
        </div>

        <div class="modal-body">
          <!-- MATERIAL -->
          <div v-if="evalType === 'MATERIAL'">
            <!-- Pregunta 1 -->
            <div class="mb-4">
              <div class="p-3 border border-dark rounded mb-3 text-center">
                ¿El material pasó las pruebas de calidad de la empresa?
              </div>
              <div class="d-flex justify-content-between">
                <button
                    class="btn btn-outline-dark flex-fill mx-1"
                    :class="{ active: localData.pasoCalidad === 'Deficiente' }"
                    @click.prevent="localData.pasoCalidad = 'Deficiente'">
                  Deficiente
                </button>
                <button
                    class="btn btn-outline-dark flex-fill mx-1"
                    :class="{ active: localData.pasoCalidad === 'Aceptable' }"
                    @click.prevent="localData.pasoCalidad = 'Aceptable'">
                  Aceptable
                </button>
                <button
                    class="btn btn-outline-dark flex-fill mx-1"
                    :class="{ active: localData.pasoCalidad === 'Excelente' }"
                    @click.prevent="localData.pasoCalidad = 'Excelente'">
                  Excelente
                </button>
              </div>
            </div>

            <!-- Pregunta 2 -->
            <div>
              <div class="p-3 border border-dark rounded mb-3 text-center">
                ¿La relación calidad-precio del material entregado es adecuada?
              </div>
              <div class="d-flex justify-content-between">
                <button
                    class="btn btn-outline-dark flex-fill mx-1"
                    :class="{ active: localData.calidadPrecio === 'Deficiente' }"
                    @click.prevent="localData.calidadPrecio = 'Deficiente'">
                  Deficiente
                </button>
                <button
                    class="btn btn-outline-dark flex-fill mx-1"
                    :class="{ active: localData.calidadPrecio === 'Aceptable' }"
                    @click.prevent="localData.calidadPrecio = 'Aceptable'">
                  Aceptable
                </button>
                <button
                    class="btn btn-outline-dark flex-fill mx-1"
                    :class="{ active: localData.calidadPrecio === 'Excelente' }"
                    @click.prevent="localData.calidadPrecio = 'Excelente'">
                  Excelente
                </button>
              </div>
            </div>
          </div>

          <!-- SERVICIO -->
          <div v-else>
            <div class="mb-4">
              <div class="p-3 border border-dark rounded mb-3 text-center">
                Cantidad de Productos Defectuosos
              </div>
              <input type="number" min="0"
                     class="form-control border border-dark mb-3"
                     v-model.number="localData.defectuosos"
                     placeholder="Ingrese cantidad de defectuosos"/>
            </div>
            <div>
              <div class="p-3 border border-dark rounded mb-3 text-center">
                Cantidad de Productos Totales
              </div>
              <input type="number" min="0"
                     class="form-control border border-dark"
                     v-model.number="localData.totales"
                     placeholder="Ingrese cantidad total"/>
            </div>
          </div>
        </div>

        <div class="modal-footer d-flex justify-content-end">
          <button type="button" class="btn btn-secondary" @click.prevent="closeModal">Cerrar</button>
          <button type="button" class="btn btn-primary ms-2" @click.prevent="saveEvaluation">Guardar</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      activeItemIndex: null,
      activeSubIndex: null,// El item seleccionado para evaluación
      evalType: 'MATERIAL', // puede ser 'MATERIAL' o 'SERVICIO'
      localData: {
        // Para MATERIAL:
        pasoCalidad: null,
        calidadPrecio: null,
        // Para SERVICIO:
        defectuosos: null,
        totales: null,
      },
    }
  },
  methods: {
    setQuality(id) {
      var type = null;
      switch (id) {
        case 1:
          type = 'Deficiente';
          break;
        case 2:
          type = 'Aceptable';
          break;
        case 3:
          type = 'Excelente';
          break;
        default:
          type = null;
      }
      return type;
    },
    getQuality(type) {
      var id = null;
      switch (type) {
        case "Deficiente":
          id = 1;
          break;
        case "Aceptable":
          id = 2;
          break;
        case "Excelente":
          id = 3;
          break;
        default:
          id = null;
      }
      return id;
    },
    resetModal() {
      this.evalType = 'MATERIAL'; // puede ser 'MATERIAL' o 'SERVICIO'
      this.localData = {
        pasoCalidad: null,
        calidadPrecio: null,
        defectuosos: null,
        totales: null,
      };
      this.activeItemIndex= null;
      this.activeSubIndex=null;// El item seleccionado para evaluación
    },
    openModal(itemId, idx2, idx) {
      console.log(itemId, idx, "MODAL INFORMACION DE INDICES")
      this.resetModal();
      this.localData.pasoCalidad = this.setQuality(itemId.material_quality);
      this.localData.calidadPrecio = this.setQuality(itemId.material_relation);
      this.localData.defectuosos = itemId.service_qty_defective;
      this.localData.totales = itemId.service_qty_total;
      this.activeItemIndex= idx;
      this.activeSubIndex=idx2;
      $("#QualityEvalLabel").modal("show");
    },
    closeModal() {
      $("#QualityEvalLabel").modal("hide");
      this.resetModal();
    },
    saveEvaluation() {
      // emitir un evento del localData al componente padre
      this.$emit('saveEvaluation', {
        activeItemIndex: this.activeItemIndex,
        activeSubIndex: this.activeSubIndex,
        material_quality: this.getQuality(this.localData.pasoCalidad),
        material_relation: this.getQuality(this.localData.calidadPrecio),
        service_qty_defective: this.localData.defectuosos,
        service_qty_total: this.localData.totales
      });
      this.closeModal();
    }
  }
};
</script>

<style scoped>
.btn-outline-dark.active {
  background: #222;
  color: #fff;
  border-color: #222;
}
</style>