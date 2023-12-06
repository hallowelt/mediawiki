"use strict";const e=require("vue"),g=require("./CdxLabel.js"),y=require("./CdxMessage.js"),s=require("./constants.js"),a=require("./useGeneratedId.js"),h=require("./useFieldData.js"),C=require("./_plugin-vue_export-helper.js");require("./Icon.js");require("./useSplitAttributes.js");require("./CdxButton.js");require("./useIconOnlyButton.js");require("./useSlotContents.js");require("./useWarnOnce.js");const I=s.makeStringTypeValidator(s.ValidationStatusTypes),F=e.defineComponent({name:"CdxField",components:{CdxLabel:g,CdxMessage:y},props:{labelIcon:{type:[String,Object],default:""},optionalFlag:{type:String,default:""},hideLabel:{type:Boolean,default:!1},isFieldset:{type:Boolean,default:!1},disabled:{type:Boolean,default:!1},status:{type:String,default:"default",validator:I},messages:{type:Object,default:()=>({})}},setup(t,{slots:n}){const{disabled:r,status:u,isFieldset:i}=e.toRefs(t),d=h.useComputedDisabled(r),o=e.computed(()=>({"cdx-field--disabled":d.value})),l=a.useGeneratedId("label"),c=a.useGeneratedId("description"),p=a.useGeneratedId("input"),m=e.computed(()=>i.value?void 0:p);e.provide(s.FieldInputIdKey,m);const f=e.computed(()=>!i.value&&n.description?c:void 0);e.provide(s.FieldDescriptionIdKey,f),e.provide(s.DisabledKey,d),e.provide(s.FieldStatusKey,u);const v=e.computed(()=>t.status!=="default"&&t.status in t.messages?t.messages[t.status]:""),b=e.computed(()=>t.status==="default"?"notice":t.status);return{rootClasses:o,computedDisabled:d,labelId:l,descriptionId:c,inputId:p,validationMessage:v,validationMessageType:b}}});const V={class:"cdx-field__help-text"},q={key:0,class:"cdx-field__validation-message"};function S(t,n,r,u,i,d){const o=e.resolveComponent("cdx-label"),l=e.resolveComponent("cdx-message");return e.openBlock(),e.createBlock(e.resolveDynamicComponent(t.isFieldset?"fieldset":"div"),{class:e.normalizeClass(["cdx-field",t.rootClasses]),"aria-disabled":!t.isFieldset&&t.computedDisabled?!0:void 0,disabled:t.isFieldset&&t.computedDisabled?!0:void 0},{default:e.withCtx(()=>[e.createVNode(o,{id:t.labelId,icon:t.labelIcon,"visually-hidden":t.hideLabel,"optional-flag":t.optionalFlag,"input-id":t.inputId,"description-id":t.descriptionId,disabled:t.computedDisabled,"is-legend":t.isFieldset},e.createSlots({default:e.withCtx(()=>[e.createCommentVNode(" @slot Label text. "),e.renderSlot(t.$slots,"label")]),_:2},[t.$slots.description&&t.$slots.description().length>0?{name:"description",fn:e.withCtx(()=>[e.createCommentVNode(" @slot Short description text. "),e.renderSlot(t.$slots,"description")]),key:"0"}:void 0]),1032,["id","icon","visually-hidden","optional-flag","input-id","description-id","disabled","is-legend"]),e.createElementVNode("div",{class:e.normalizeClass(["cdx-field__control",{"cdx-field__control--has-help-text":t.$slots["help-text"]&&t.$slots["help-text"]().length>0||t.validationMessage}])},[e.createCommentVNode(" @slot Input, control, or input group. "),e.renderSlot(t.$slots,"default")],2),e.createElementVNode("div",V,[e.createCommentVNode(" @slot Further explanation of how to use this field. "),e.renderSlot(t.$slots,"help-text")]),!t.computedDisabled&&t.validationMessage?(e.openBlock(),e.createElementBlock("div",q,[e.createVNode(l,{type:t.validationMessageType,inline:!0},{default:e.withCtx(()=>[e.createTextVNode(e.toDisplayString(t.validationMessage),1)]),_:1},8,["type"])])):e.createCommentVNode("v-if",!0)]),_:3},8,["class","aria-disabled","disabled"])}const D=C._export_sfc(F,[["render",S]]);module.exports=D;
