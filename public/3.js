(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[3],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/views/ClientOrderSearch.vue?vue&type=script&lang=js&":
/*!***********************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/views/ClientOrderSearch.vue?vue&type=script&lang=js& ***!
  \***********************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony default export */ __webpack_exports__["default"] = ({
  props: ['order'],
  computed: {
    steps: function steps() {
      return [{
        key: 'Cotizando',
        label: 'Pedido por cotizar',
        icon: '📝'
      }, {
        key: 'Cotizado',
        label: 'Cotización enviada',
        icon: '📧'
      }, {
        key: 'Producción',
        label: 'En producción',
        icon: '🏭'
      }, {
        key: 'En Marcha',
        label: 'Pedido en marcha',
        icon: '🚚'
      }, {
        key: 'Entregado',
        label: 'Pedido entregado',
        icon: '✅'
      }];
    }
  },
  methods: {
    goToForm: function goToForm() {
      window.location.href = '/client/feedback/' + this.order.code;
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/views/ClientOrderSearch.vue?vue&type=template&id=81242d88&scoped=true&":
/*!*********************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib/loaders/templateLoader.js??ref--6!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/views/ClientOrderSearch.vue?vue&type=template&id=81242d88&scoped=true& ***!
  \*********************************************************************************************************************************************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "render", function() { return render; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return staticRenderFns; });
var render = function render() {
  var _vm = this,
      _c = _vm._self._c;

  return _c("div", {
    staticClass: "order-status-bg"
  }, [_c("h2", [_vm._v("Barra de seguimiento")]), _vm._v(" "), _c("div", {
    staticClass: "status-bar"
  }, _vm._l(_vm.steps, function (step, idx) {
    return _c("div", {
      key: idx,
      "class": {
        active: _vm.order.status === step.key
      }
    }, [_c("span", [_vm._v(_vm._s(step.icon))]), _vm._v(" "), _c("div", [_vm._v(_vm._s(step.label))])]);
  }), 0), _vm._v(" "), _c("div", {
    staticClass: "order-details"
  }, [_c("p", [_c("strong", [_vm._v("Cliente:")]), _vm._v(" " + _vm._s(_vm.order.client_name))]), _vm._v(" "), _c("p", [_c("strong", [_vm._v("Dirección:")]), _vm._v(" " + _vm._s(_vm.order.address))]), _vm._v(" "), _vm._m(0), _vm._v(" "), _c("ul", _vm._l(_vm.order.products, function (prod) {
    return _c("li", {
      key: prod.id
    }, [_vm._v("\r\n          " + _vm._s(prod.producto) + " (" + _vm._s(prod.descripcion) + ") x " + _vm._s(prod.cantidad) + "\r\n        ")]);
  }), 0), _vm._v(" "), _c("p", [_c("strong", [_vm._v("Fecha de entrega:")]), _vm._v(" " + _vm._s(_vm.order.delivery_date))])]), _vm._v(" "), _vm.order.status === "Entregado" ? _c("button", {
    on: {
      click: _vm.goToForm
    }
  }, [_vm._v("COMPLETAR FORMULARIO")]) : _vm._e()]);
};

var staticRenderFns = [function () {
  var _vm = this,
      _c = _vm._self._c;

  return _c("p", [_c("strong", [_vm._v("Productos:")])]);
}];
render._withStripped = true;


/***/ }),

/***/ "./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/views/ClientOrderSearch.vue?vue&type=style&index=0&id=81242d88&scoped=true&lang=css&":
/*!******************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader??ref--6-1!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--6-2!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/views/ClientOrderSearch.vue?vue&type=style&index=0&id=81242d88&scoped=true&lang=css& ***!
  \******************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(/*! ../../../node_modules/css-loader/lib/css-base.js */ "./node_modules/css-loader/lib/css-base.js")(false);
// imports


// module
exports.push([module.i, "\n.order-status-bg[data-v-81242d88] {\r\n  background: rgba(115, 138, 49, 0.8);\r\n  border-radius: 24px;\r\n  margin-top: 32px;\r\n  padding: 20px;\r\n  color: #fff;\n}\n.status-bar[data-v-81242d88] {\r\n  display: flex;\r\n  justify-content: space-between;\r\n  margin-bottom: 24px;\n}\n.status-bar > div[data-v-81242d88] {\r\n  flex: 1;\r\n  text-align: center;\r\n  opacity: 0.5;\n}\n.status-bar > div.active[data-v-81242d88] {\r\n  opacity: 1;\r\n  font-weight: bold;\r\n  color: #ffd700;\n}\n.order-details[data-v-81242d88] {\r\n  background: rgba(0,0,0,0.2);\r\n  border-radius: 10px;\r\n  padding: 14px;\r\n  margin-bottom: 12px;\n}\nbutton[data-v-81242d88] {\r\n  background: #fff;\r\n  color: #63712c;\r\n  padding: 12px 28px;\r\n  border-radius: 20px;\r\n  border: none;\r\n  font-weight: bold;\r\n  font-size: 18px;\r\n  cursor: pointer;\n}\r\n", ""]);

// exports


/***/ }),

/***/ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/views/ClientOrderSearch.vue?vue&type=style&index=0&id=81242d88&scoped=true&lang=css&":
/*!**********************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/style-loader!./node_modules/css-loader??ref--6-1!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--6-2!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/views/ClientOrderSearch.vue?vue&type=style&index=0&id=81242d88&scoped=true&lang=css& ***!
  \**********************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {


var content = __webpack_require__(/*! !../../../node_modules/css-loader??ref--6-1!../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../node_modules/postcss-loader/src??ref--6-2!../../../node_modules/vue-loader/lib??vue-loader-options!./ClientOrderSearch.vue?vue&type=style&index=0&id=81242d88&scoped=true&lang=css& */ "./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/views/ClientOrderSearch.vue?vue&type=style&index=0&id=81242d88&scoped=true&lang=css&");

if(typeof content === 'string') content = [[module.i, content, '']];

var transform;
var insertInto;



var options = {"hmr":true}

options.transform = transform
options.insertInto = undefined;

var update = __webpack_require__(/*! ../../../node_modules/style-loader/lib/addStyles.js */ "./node_modules/style-loader/lib/addStyles.js")(content, options);

if(content.locals) module.exports = content.locals;

if(false) {}

/***/ }),

/***/ "./resources/js/views/ClientOrderSearch.vue":
/*!**************************************************!*\
  !*** ./resources/js/views/ClientOrderSearch.vue ***!
  \**************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _ClientOrderSearch_vue_vue_type_template_id_81242d88_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./ClientOrderSearch.vue?vue&type=template&id=81242d88&scoped=true& */ "./resources/js/views/ClientOrderSearch.vue?vue&type=template&id=81242d88&scoped=true&");
/* harmony import */ var _ClientOrderSearch_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./ClientOrderSearch.vue?vue&type=script&lang=js& */ "./resources/js/views/ClientOrderSearch.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _ClientOrderSearch_vue_vue_type_style_index_0_id_81242d88_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./ClientOrderSearch.vue?vue&type=style&index=0&id=81242d88&scoped=true&lang=css& */ "./resources/js/views/ClientOrderSearch.vue?vue&type=style&index=0&id=81242d88&scoped=true&lang=css&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");






/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__["default"])(
  _ClientOrderSearch_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _ClientOrderSearch_vue_vue_type_template_id_81242d88_scoped_true___WEBPACK_IMPORTED_MODULE_0__["render"],
  _ClientOrderSearch_vue_vue_type_template_id_81242d88_scoped_true___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  "81242d88",
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/views/ClientOrderSearch.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/views/ClientOrderSearch.vue?vue&type=script&lang=js&":
/*!***************************************************************************!*\
  !*** ./resources/js/views/ClientOrderSearch.vue?vue&type=script&lang=js& ***!
  \***************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ClientOrderSearch_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/babel-loader/lib??ref--4-0!../../../node_modules/vue-loader/lib??vue-loader-options!./ClientOrderSearch.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/views/ClientOrderSearch.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ClientOrderSearch_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/views/ClientOrderSearch.vue?vue&type=style&index=0&id=81242d88&scoped=true&lang=css&":
/*!***********************************************************************************************************!*\
  !*** ./resources/js/views/ClientOrderSearch.vue?vue&type=style&index=0&id=81242d88&scoped=true&lang=css& ***!
  \***********************************************************************************************************/
/*! no static exports found */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_ClientOrderSearch_vue_vue_type_style_index_0_id_81242d88_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/style-loader!../../../node_modules/css-loader??ref--6-1!../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../node_modules/postcss-loader/src??ref--6-2!../../../node_modules/vue-loader/lib??vue-loader-options!./ClientOrderSearch.vue?vue&type=style&index=0&id=81242d88&scoped=true&lang=css& */ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/views/ClientOrderSearch.vue?vue&type=style&index=0&id=81242d88&scoped=true&lang=css&");
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_ClientOrderSearch_vue_vue_type_style_index_0_id_81242d88_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_ClientOrderSearch_vue_vue_type_style_index_0_id_81242d88_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__);
/* harmony reexport (unknown) */ for(var __WEBPACK_IMPORT_KEY__ in _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_ClientOrderSearch_vue_vue_type_style_index_0_id_81242d88_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__) if(["default"].indexOf(__WEBPACK_IMPORT_KEY__) < 0) (function(key) { __webpack_require__.d(__webpack_exports__, key, function() { return _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_ClientOrderSearch_vue_vue_type_style_index_0_id_81242d88_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__[key]; }) }(__WEBPACK_IMPORT_KEY__));


/***/ }),

/***/ "./resources/js/views/ClientOrderSearch.vue?vue&type=template&id=81242d88&scoped=true&":
/*!*********************************************************************************************!*\
  !*** ./resources/js/views/ClientOrderSearch.vue?vue&type=template&id=81242d88&scoped=true& ***!
  \*********************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_loaders_templateLoader_js_ref_6_node_modules_vue_loader_lib_index_js_vue_loader_options_ClientOrderSearch_vue_vue_type_template_id_81242d88_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/babel-loader/lib??ref--4-0!../../../node_modules/vue-loader/lib/loaders/templateLoader.js??ref--6!../../../node_modules/vue-loader/lib??vue-loader-options!./ClientOrderSearch.vue?vue&type=template&id=81242d88&scoped=true& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/views/ClientOrderSearch.vue?vue&type=template&id=81242d88&scoped=true&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_loaders_templateLoader_js_ref_6_node_modules_vue_loader_lib_index_js_vue_loader_options_ClientOrderSearch_vue_vue_type_template_id_81242d88_scoped_true___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_loaders_templateLoader_js_ref_6_node_modules_vue_loader_lib_index_js_vue_loader_options_ClientOrderSearch_vue_vue_type_template_id_81242d88_scoped_true___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);