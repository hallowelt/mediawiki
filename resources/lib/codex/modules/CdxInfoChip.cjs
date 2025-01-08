"use strict";const e=require("vue"),i=require("./constants.js"),t=require("./Icon.js"),r=require("./_plugin-vue_export-helper.js"),a={notice:t.X4,error:t.p4,warning:t.H3,success:t.K7},u=e.defineComponent({name:"CdxInfoChip",components:{CdxIcon:t.CdxIcon},props:{status:{type:String,default:"notice",validator:i.statusTypeValidator},icon:{type:[String,Object],default:null}},setup(o){const n=e.computed(()=>({["cdx-info-chip--".concat(o.status)]:!0})),c=e.computed(()=>o.status==="notice"?o.icon:a[o.status]);return{rootClasses:n,computedIcon:c}}}),d={class:"cdx-info-chip__text"};function p(o,n,c,m,f,_){const s=e.resolveComponent("cdx-icon");return e.openBlock(),e.createElementBlock("div",{class:e.normalizeClass(["cdx-info-chip",o.rootClasses])},[o.computedIcon?(e.openBlock(),e.createBlock(s,{key:0,class:"cdx-info-chip__icon--vue",icon:o.computedIcon},null,8,["icon"])):e.createCommentVNode("v-if",!0),e.createElementVNode("span",d,[e.renderSlot(o.$slots,"default")])],2)}const l=r._export_sfc(u,[["render",p]]);module.exports=l;
