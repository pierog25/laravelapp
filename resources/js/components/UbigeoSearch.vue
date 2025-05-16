<template>
  <div class="form-row" v-if="validate">
    <div class="col-md-4">
      <div class="form-group">
        <validation-provider name="DEPARTAMENTO" rules="required" v-slot="{ errors }">
          <label class="form-control-label">Departamento</label>
          <multiselect
              v-model="department"
              :options="departments"
              @select="getProvinces"
              track-by="department"
              label="department"
              placeholder="Seleccionar"
              deselect-label="Deseleccionar">
            <template slot="singleLabel" slot-scope="{ option }"><span
                class="badge badge-pill badge-success">{{ option.department }}</span></template>
          </multiselect>
          <span class="is-invalid">{{ errors[0] }}</span>
        </validation-provider>
      </div>
    </div>
    <div class="col-md-4">
      <div class="form-group">
        <validation-provider name="PROVINCIA" rules="required" v-slot="{ errors }">
          <label class="form-control-label">Provincia</label>
          <multiselect
              v-model="province"
              :options="provinces"
              @select="getDistricts"
              track-by="province"
              label="province"
              placeholder="Seleccionar"
              deselect-label="Deseleccionar">
            <template slot="singleLabel" slot-scope="{ option }"><span
                class="badge badge-pill badge-success">{{ option.province }}</span></template>
          </multiselect>
          <span class="is-invalid">{{ errors[0] }}</span>
        </validation-provider>
      </div>
    </div>
    <div class="col-md-4">
      <div class="form-group">
        <validation-provider name="DISTRITO" rules="required" v-slot="{ errors }">
          <label class="form-control-label">Distrito</label>
          <multiselect
              v-model="district"
              :options="districts"
              @select="selectedDistrict"
              track-by="district"
              label="district"
              placeholder="Seleccionar"
              deselect-label="Deseleccionar">
            <template slot="singleLabel" slot-scope="{ option }"><span
                class="badge badge-pill badge-success">{{ option.district }}</span></template>
          </multiselect>
          <span class="is-invalid">{{ errors[0] }}</span>
        </validation-provider>
      </div>
    </div>
  </div>
  <div class="form-row" v-else>
    <div class="col-md-4">
      <div class="form-group">
        <label class="form-control-label">Departamento</label>
        <multiselect
            v-model="department"
            :options="departments"
            @select="getProvinces"
            track-by="department"
            label="department"
            placeholder="Seleccionar"
            deselect-label="Deseleccionar">
          <template slot="singleLabel" slot-scope="{ option }"><span
              class="badge badge-pill badge-success">{{ option.department }}</span></template>
        </multiselect>
      </div>
    </div>
    <div class="col-md-4">
      <div class="form-group">
        <label class="form-control-label">Provincia</label>
        <multiselect
            v-model="province"
            :options="provinces"
            @select="getDistricts"
            track-by="province"
            label="province"
            placeholder="Seleccionar"
            deselect-label="Deseleccionar">
          <template slot="singleLabel" slot-scope="{ option }"><span
              class="badge badge-pill badge-success">{{ option.province }}</span></template>
        </multiselect>
      </div>
    </div>
    <div class="col-md-4">
      <div class="form-group">
        <label class="form-control-label">Distrito</label>
        <multiselect
            v-model="district"
            :options="districts"
            @select="selectedDistrict"
            track-by="district"
            label="district"
            placeholder="Seleccionar"
            deselect-label="Deseleccionar">
          <template slot="singleLabel" slot-scope="{ option }"><span
              class="badge badge-pill badge-success">{{ option.district }}</span></template>
        </multiselect>
      </div>
    </div>
  </div>
</template>
<script>
import {Multiselect} from 'vue-multiselect';

export default {
  components: {
    multiselect: Multiselect
  },
  data() {
    return {
      departments: [],
      department: "",
      provinces: [],
      province: "",
      districts: [],
      district: "",
      is_set_ubigeo: false
    }
  },
  props: {
    validate: {
      type: Boolean,
      default: false
    }
  },
  methods: {
    resetUbigeo() {
      this.department = ""
      this.provinces = []
      this.province = ""
      this.districts = []
      this.district = ""
      this.is_set_ubigeo = false
    },
    async setUbigeo(ubigeo) {
      this.department = {department: ubigeo.department}
      this.province = {province: ubigeo.province}
      this.is_set_ubigeo = true
      await this.getProvinces(this.department)
      await this.getDistricts(this.province)
      this.district = {district: ubigeo.district}
    },
    selectedDistrict(district) {
      this.$emit('ubigeo', district.code_ubigeo)
      this.$emit('selectedUbigeo', {
        department: this.department.department,
        province: this.province.province,
        district: this.district.district,
        code: district.code_ubigeo
      })
    },
    selectedUbigeo(){
      let ubigeo = {}
      ubigeo["department"] = this.department.department
      ubigeo["province"] = this.province.province
      ubigeo["district"] = this.district.district
      ubigeo["code_ubigeo"] = this.district.code_ubigeo
      return ubigeo
    },
    async getDepartments() {
      try {
        let result = await axios.get('/get-department')

        if (result.data.code === "Success") {
          this.departments = result.data.data
        }

      } catch (e) {

      }
    },
    async getProvinces(department) {
      try {
        let result = await axios.get(`/get-province/${department.department}`)

        if (result.data.code === "Success") {
          if (!this.is_set_ubigeo) {
            this.province = ""
            this.district = ""
          }
          this.districts = []
          this.provinces = result.data.data
        }

      } catch (e) {

      }
    },
    async getDistricts(province) {
      try {
        let result = await axios.get(`/get-district/${province.province}`)

        if (result.data.code === "Success") {
          if (!this.is_set_ubigeo) {
            this.district = ""
          }
          this.districts = result.data.data
          this.is_set_ubigeo = false
        }

      } catch (e) {

      }
    },
  },
  async mounted() {
    await this.getDepartments()
  }
}
</script>
<style scoped>

</style>