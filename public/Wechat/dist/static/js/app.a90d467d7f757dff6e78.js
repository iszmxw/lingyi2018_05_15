webpackJsonp([1],{"3eUn":function(t,s){},NHnr:function(t,s,i){"use strict";Object.defineProperty(s,"__esModule",{value:!0});var a=i("7+uW"),o={render:function(){var t=this.$createElement,s=this._self._c||t;return s("div",{attrs:{id:"app"}},[s("div",{staticClass:"page"},[s("router-view")],1)])},staticRenderFns:[]};var e=i("VU/8")({name:"App"},o,!1,function(t){i("oSKr")},null,null).exports,c=i("/ocq"),n={data:function(){return{}},props:{isShow:{type:Boolean,default:!1}},methods:{hiddendialog:function(){this.$emit("on-close")}}},r={render:function(){var t=this.$createElement,s=this._self._c||t;return s("div",[this.isShow?s("div",{staticClass:"popup_alert",on:{click:this.hiddendialog,touchmove:function(t){t.preventDefault()}}},[this._t("default")],2):this._e()])},staticRenderFns:[]};var l={render:function(){var t=this,s=t.$createElement,i=t._self._c||s;return i("div",[i("div",{staticClass:"quhuo alert_width popup_alert_hook",on:{click:function(t){t.stopPropagation()}}},[i("p",{staticClass:"quhuoinfo"},[t._v("选择配送方式")]),t._v(" "),i("div",{staticClass:"max_height_box"},[i("a",{class:{action:1==t.distributionid},attrs:{href:"javascript:;"},on:{click:function(s){t.select(1)}}},[t._v("快递配送")]),t._v(" "),i("a",{class:{action:2==t.distributionid},attrs:{href:"javascript:;"},on:{click:function(s){t.select(2)}}},[t._v("到店自提")])]),t._v(" "),i("div",{staticClass:"alert_btn_wz"},[i("a",{staticClass:"btn_alert my_text_align btn_alert_bg",attrs:{href:"javascript:;"},on:{click:t.hiddendialog}},[t._v("确认")])])])])},staticRenderFns:[]};var d={components:{myDialog:i("VU/8")(n,r,!1,function(t){i("WLWW")},null,null).exports,selectDistribution:i("VU/8")({data:function(){return{distributionid:1}},props:{},methods:{hiddendialog:function(){this.$emit("on-close")},select:function(t){this.distributionid=t}}},l,!1,function(t){i("3eUn")},"data-v-7a2c879d",null).exports},data:function(){return{isShowdialog:!1}},created:function(){this.$ajax.post("http://develop.01nnt.com/api/wechatApi/store_list").then(function(t){console.log(t)},function(t){console.log(t)})},methods:{showdialog:function(t){this[t]=!0},hiddendialog:function(t){this[t]=!1}}},v={render:function(){var t=this,s=t.$createElement,i=t._self._c||s;return i("div",[i("div",{staticClass:"g-flexview"},[t._m(0),t._v(" "),t._m(1),t._v(" "),i("div",{staticClass:"order"},[i("div",{staticClass:"order_address"},[i("div",{staticClass:"item border_top_4",on:{click:function(s){t.showdialog("isShowdialog")}}},[t._m(2)]),t._v(" "),t._m(3)])]),t._v(" "),t._m(4),t._v(" "),t._m(5)]),t._v(" "),i("my-dialog",{attrs:{"is-show":t.isShowdialog},on:{"on-close":function(s){t.hiddendialog("isShowdialog")}}},[i("select-distribution",{on:{"on-close":function(s){t.hiddendialog("isShowdialog")}}})],1)],1)},staticRenderFns:[function(){var t=this.$createElement,s=this._self._c||t;return s("div",{staticClass:"top"},[s("div",{staticClass:"top_box"},[s("a",{staticClass:"return_btn",attrs:{href:"javascript:;"}}),this._v(" "),s("a",{staticClass:"personal_btn",attrs:{href:"javascript:;"}})])])},function(){var t=this.$createElement,s=this._self._c||t;return s("div",{staticClass:"order order_m"},[s("div",{staticClass:"order_box"},[s("h3",{staticClass:"order_state"},[this._v("待支付")]),this._v(" "),s("a",{staticClass:"address_btn",attrs:{href:"javascript:;",id:"address",onclick:"show('alert')"}},[s("i"),this._v("添加收货地址")]),this._v(" "),s("a",{staticClass:"address_btn",attrs:{href:"javascript:;",id:"zitiinfo",onclick:"show('alert')"}},[s("i"),this._v("添加自提信息")])]),this._v(" "),s("div",{staticClass:"emotion_icon"})])},function(){var t=this.$createElement,s=this._self._c||t;return s("div",{staticClass:"row"},[s("div",{staticClass:"col-20 item_info"},[s("span",[this._v("配送方式")])]),this._v(" "),s("div",{staticClass:"col-80 item_icon"},[s("p",{attrs:{id:"distribution"}},[this._v("快递配送")])])])},function(){var t=this.$createElement,s=this._self._c||t;return s("div",{staticClass:"order_btn"},[s("div",{staticClass:"row"},[s("div",{staticClass:"col-20"},[s("span",{staticClass:"dizhi"},[this._v("备注")])]),this._v(" "),s("div",{staticClass:"col-80"},[s("input",{staticClass:"address_input",attrs:{type:"text",name:"",placeholder:"你有什么需要交代的呢"}})])])])},function(){var t=this,s=t.$createElement,i=t._self._c||s;return i("div",{staticClass:"order order_m1"},[i("div",{staticClass:"order_address1"},[i("div",{staticClass:"row order_address_m"},[i("div",{staticClass:"col-50 dindanmx"},[i("i"),t._v("订单明细"),i("em",[t._v("  (6份商品)")])]),t._v(" "),i("div",{staticClass:"col-50 riqi"},[t._v("2018-04-19 05:45")])]),t._v(" "),i("div",{staticClass:"order_list"},[i("div",{staticClass:"row order_list_box"},[i("div",{staticClass:"col-60"},[t._v("2018春装新款格子修")]),t._v(" "),i("div",{staticClass:"col-15"},[t._v("×1")]),t._v(" "),i("div",{staticClass:"col-25"},[t._v("¥88")])]),t._v(" "),i("div",{staticClass:"row order_list_box"},[i("div",{staticClass:"col-60"},[t._v("2018春装新款格子修")]),t._v(" "),i("div",{staticClass:"col-15"},[t._v("×1")]),t._v(" "),i("div",{staticClass:"col-25"},[t._v("¥88")])]),t._v(" "),i("div",{staticClass:"row order_list_box"},[i("div",{staticClass:"col-60"},[t._v("2018春装新款格子修")]),t._v(" "),i("div",{staticClass:"col-15"},[t._v("×1")]),t._v(" "),i("div",{staticClass:"col-25"},[t._v("¥88")])]),t._v(" "),i("div",{staticClass:"row order_list_box"},[i("div",{staticClass:"col-60"},[t._v("2018春装新款格子修")]),t._v(" "),i("div",{staticClass:"col-15"},[t._v("×1")]),t._v(" "),i("div",{staticClass:"col-25"},[t._v("¥88")])]),t._v(" "),i("div",{staticClass:"row order_list_box"},[i("div",{staticClass:"col-60"},[t._v("2018春装新款格子修")]),t._v(" "),i("div",{staticClass:"col-15"},[t._v("×1")]),t._v(" "),i("div",{staticClass:"col-25"},[t._v("¥88")])]),t._v(" "),i("div",{staticClass:"row order_list_box"},[i("div",{staticClass:"col-60"},[t._v("2018春装新款格子修")]),t._v(" "),i("div",{staticClass:"col-15"},[t._v("×1")]),t._v(" "),i("div",{staticClass:"col-25"},[t._v("¥88")])]),t._v(" "),i("div",{staticClass:"row order_list_box"},[i("div",{staticClass:"col-60"},[t._v("2018春装新款格子修")]),t._v(" "),i("div",{staticClass:"col-15"},[t._v("×1")]),t._v(" "),i("div",{staticClass:"col-25"},[t._v("¥88")])]),t._v(" "),i("div",{staticClass:"order_btn order_zongji"},[i("p",[i("span",{staticClass:"yunfei"},[t._v("运费")]),i("em",[t._v("请先添加收货地址")])])]),t._v(" "),i("div",{staticClass:"order_btn order_zongji"},[i("p",[i("em",[t._v("总计")]),t._v("  ¥789")])])])])])},function(){var t=this.$createElement,s=this._self._c||t;return s("div",{staticClass:"tijiao"},[s("a",{staticClass:"tijao_btn",attrs:{href:"javascript:;"}},[this._v("提交订单"),s("em",[this._v("¥568")])])])}]};var _=i("VU/8")(d,v,!1,function(t){i("ghQB")},"data-v-4b994e75",null).exports;a.a.use(c.a);var h=new c.a({routes:[{path:"/",name:"HelloWorld",component:_}]}),u=i("mtWM"),p=i.n(u);a.a.config.productionTip=!1,a.a.prototype.$ajax=p.a,new a.a({el:"#app",router:h,components:{App:e},template:"<App/>"})},WLWW:function(t,s){},ghQB:function(t,s){},oSKr:function(t,s){}},["NHnr"]);
//# sourceMappingURL=app.a90d467d7f757dff6e78.js.map