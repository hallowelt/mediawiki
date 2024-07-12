"use strict";const e=require("vue"),d=require("./Icon.js"),c=require("./useFieldData.cjs"),p=require("./useSplitAttributes.cjs"),u=require("./useI18n.cjs"),m=require("./_plugin-vue_export-helper.js"),_=e.defineComponent({name:"CdxLabel",components:{CdxIcon:d.CdxIcon},inheritAttrs:!1,props:{icon:{type:[String,Object],default:null},optional:{type:Boolean,default:!1},optionalFlag:{type:String,default:""},visuallyHidden:{type:Boolean,default:!1},isLegend:{type:Boolean,default:!1},inputId:{type:String,default:""},descriptionId:{type:String,default:""},disabled:{type:Boolean,default:!1}},setup(l,{attrs:t}){const{computedDisabled:n}=c(e.toRef(l,"disabled")),s=e.computed(()=>({"cdx-label--visually-hidden":l.visuallyHidden,"cdx-label--disabled":n.value})),{rootClasses:a,rootStyle:i,otherAttrs:o}=p(t,s),r=u("cdx-label-optional-flag",()=>l.optionalFlag||"(optional)");return{rootClasses:a,rootStyle:i,otherAttrs:o,translatedOptionalFlag:r}}}),b=["for"],f={class:"cdx-label__label__text"},y={key:1,class:"cdx-label__label__optional-flag"},g=["id"],k={class:"cdx-label__label"},B={class:"cdx-label__label__text"},C={key:1,class:"cdx-label__label__optional-flag"},S={key:0,class:"cdx-label__description"};function h(l,t,n,s,a,i){const o=e.resolveComponent("cdx-icon");return l.isLegend?(e.openBlock(),e.createElementBlock("legend",e.mergeProps({key:1,class:["cdx-label",l.rootClasses],style:l.rootStyle},l.otherAttrs),[e.createElementVNode("span",k,[l.icon?(e.openBlock(),e.createBlock(o,{key:0,icon:l.icon,class:"cdx-label__label__icon"},null,8,["icon"])):e.createCommentVNode("v-if",!0),e.createElementVNode("span",B,[e.renderSlot(l.$slots,"default")]),l.optionalFlag||l.optional?(e.openBlock(),e.createElementBlock("span",C,e.toDisplayString(" ")+" "+e.toDisplayString(l.translatedOptionalFlag),1)):e.createCommentVNode("v-if",!0)]),l.$slots.description&&l.$slots.description().length>0?(e.openBlock(),e.createElementBlock("span",S,[e.renderSlot(l.$slots,"description")])):e.createCommentVNode("v-if",!0)],16)):(e.openBlock(),e.createElementBlock("div",{key:0,class:e.normalizeClass(["cdx-label",l.rootClasses]),style:e.normalizeStyle(l.rootStyle)},[e.createElementVNode("label",e.mergeProps({class:"cdx-label__label",for:l.inputId?l.inputId:void 0},l.otherAttrs),[l.icon?(e.openBlock(),e.createBlock(o,{key:0,icon:l.icon,class:"cdx-label__label__icon"},null,8,["icon"])):e.createCommentVNode("v-if",!0),e.createElementVNode("span",f,[e.renderSlot(l.$slots,"default")]),l.optionalFlag||l.optional?(e.openBlock(),e.createElementBlock("span",y,e.toDisplayString(" ")+" "+e.toDisplayString(l.translatedOptionalFlag),1)):e.createCommentVNode("v-if",!0)],16,b),l.$slots.description&&l.$slots.description().length>0?(e.openBlock(),e.createElementBlock("span",{key:0,id:l.descriptionId||void 0,class:"cdx-label__description"},[e.renderSlot(l.$slots,"description")],8,g)):e.createCommentVNode("v-if",!0)],6))}const v=m._export_sfc(_,[["render",h]]);module.exports=v;
