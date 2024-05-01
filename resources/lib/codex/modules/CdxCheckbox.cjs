"use strict";const t=require("vue"),h=require("./CdxLabel.cjs"),m=require("./useLabelChecker.js"),k=require("./useModelWrapper.cjs"),u=require("./useGeneratedId.cjs"),y=require("./useFieldData.cjs"),c=require("./constants.js"),C=require("./_plugin-vue_export-helper.js");require("./Icon.js");require("./useComputedDirection.cjs");require("./useComputedLanguage.cjs");require("./useSplitAttributes.cjs");require("./useSlotContents.js");require("./useWarnOnce.cjs");require("./useComputedDisabled.cjs");const q=c.makeStringTypeValidator(c.ValidationStatusTypes),v=t.defineComponent({name:"CdxCheckbox",components:{CdxLabel:h},props:{modelValue:{type:[Boolean,Array],default:!1},inputValue:{type:[String,Number,Boolean],default:!1},disabled:{type:Boolean,default:!1},indeterminate:{type:Boolean,default:!1},inline:{type:Boolean,default:!1},hideLabel:{type:Boolean,default:!1},status:{type:String,default:"default",validator:q}},emits:["update:modelValue"],setup(e,{emit:o,slots:d,attrs:n}){var r;m.useLabelChecker((r=d.default)==null?void 0:r.call(d),n,"CdxCheckbox");const{computedDisabled:l,computedStatus:a}=y(t.toRef(e,"disabled"),t.toRef(e,"status")),i=t.computed(()=>({"cdx-checkbox--inline":e.inline,["cdx-checkbox--status-".concat(a.value)]:!0})),s=t.ref(),p=u("checkbox"),b=u("description"),f=k(t.toRef(e,"modelValue"),o);return{rootClasses:i,computedDisabled:l,input:s,checkboxId:p,descriptionId:b,wrappedModel:f}}});const $=["id","aria-describedby","value","disabled",".indeterminate"],V=t.createElementVNode("span",{class:"cdx-checkbox__icon"},null,-1);function x(e,o,d,n,l,a){const i=t.resolveComponent("cdx-label");return t.openBlock(),t.createElementBlock("span",{class:t.normalizeClass(["cdx-checkbox",e.rootClasses])},[t.withDirectives(t.createElementVNode("input",{id:e.checkboxId,ref:"input","onUpdate:modelValue":o[0]||(o[0]=s=>e.wrappedModel=s),class:"cdx-checkbox__input",type:"checkbox","aria-describedby":e.$slots.description&&e.$slots.description().length>0?e.descriptionId:void 0,value:e.inputValue,disabled:e.computedDisabled,".indeterminate":e.indeterminate},null,40,$),[[t.vModelCheckbox,e.wrappedModel]]),V,e.$slots.default&&e.$slots.default().length?(t.openBlock(),t.createBlock(i,{key:0,class:"cdx-checkbox__label","input-id":e.checkboxId,"description-id":e.$slots.description&&e.$slots.description().length>0?e.descriptionId:void 0,disabled:e.computedDisabled,"visually-hidden":e.hideLabel},t.createSlots({default:t.withCtx(()=>[t.renderSlot(e.$slots,"default")]),_:2},[e.$slots.description&&e.$slots.description().length>0?{name:"description",fn:t.withCtx(()=>[t.renderSlot(e.$slots,"description")]),key:"0"}:void 0]),1032,["input-id","description-id","disabled","visually-hidden"])):t.createCommentVNode("",!0)],2)}const B=C._export_sfc(v,[["render",x]]);module.exports=B;
