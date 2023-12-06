"use strict";const t=require("vue"),p=require("./Icon.js"),c=require("./CdxButton.js"),m=require("./CdxTextInput.js"),b=require("./useModelWrapper.js"),f=require("./useSplitAttributes.js"),h=require("./useFieldData.js"),d=require("./constants.js"),S=require("./_plugin-vue_export-helper.js");require("./useIconOnlyButton.js");require("./useSlotContents.js");require("./useWarnOnce.js");const y=d.makeStringTypeValidator(d.ValidationStatusTypes),C=t.defineComponent({name:"CdxSearchInput",components:{CdxButton:c,CdxTextInput:m},inheritAttrs:!1,props:{modelValue:{type:[String,Number],default:""},buttonLabel:{type:String,default:""},disabled:{type:Boolean,default:!1},status:{type:String,default:"default",validator:y}},emits:["update:modelValue","submit-click","input","change","focus","blur"],setup(e,{emit:n,attrs:a}){const s=b.useModelWrapper(t.toRef(e,"modelValue"),n),{computedDisabled:l}=h.useFieldData(t.toRef(e,"disabled")),i=t.computed(()=>({"cdx-search-input--has-end-button":!!e.buttonLabel})),{rootClasses:r,rootStyle:u,otherAttrs:o}=f.useSplitAttributes(a,i);return{wrappedModel:s,computedDisabled:l,rootClasses:r,rootStyle:u,otherAttrs:o,handleSubmit:()=>{n("submit-click",s.value)},searchIcon:p.L7}},methods:{focus(){this.$refs.textInput.focus()}}});const V={class:"cdx-search-input__input-wrapper"};function g(e,n,a,s,l,i){const r=t.resolveComponent("cdx-text-input"),u=t.resolveComponent("cdx-button");return t.openBlock(),t.createElementBlock("div",{class:t.normalizeClass(["cdx-search-input",e.rootClasses]),style:t.normalizeStyle(e.rootStyle)},[t.createElementVNode("div",V,[t.createVNode(r,t.mergeProps({ref:"textInput",modelValue:e.wrappedModel,"onUpdate:modelValue":n[0]||(n[0]=o=>e.wrappedModel=o),class:"cdx-search-input__text-input","input-type":"search","start-icon":e.searchIcon,disabled:e.computedDisabled,status:e.status},e.otherAttrs,{onKeydown:t.withKeys(e.handleSubmit,["enter"]),onInput:n[1]||(n[1]=o=>e.$emit("input",o)),onChange:n[2]||(n[2]=o=>e.$emit("change",o)),onFocus:n[3]||(n[3]=o=>e.$emit("focus",o)),onBlur:n[4]||(n[4]=o=>e.$emit("blur",o))}),null,16,["modelValue","start-icon","disabled","status","onKeydown"]),t.createCommentVNode("\n				@slot A slot for passing in an options menu that needs to be positioned\n				relatively to the text input. See TypeaheadSearch for sample usage.\n			"),t.renderSlot(e.$slots,"default")]),e.buttonLabel?(t.openBlock(),t.createBlock(u,{key:0,class:"cdx-search-input__end-button",disabled:e.computedDisabled,onClick:e.handleSubmit},{default:t.withCtx(()=>[t.createTextVNode(t.toDisplayString(e.buttonLabel),1)]),_:1},8,["disabled","onClick"])):t.createCommentVNode("v-if",!0)],6)}const q=S._export_sfc(C,[["render",g]]);module.exports=q;
