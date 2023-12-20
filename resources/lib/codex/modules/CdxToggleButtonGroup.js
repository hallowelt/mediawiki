"use strict";const l=require("vue"),s=require("./buttonHelpers.js"),m=require("./Icon.js"),g=require("./CdxToggleButton.js"),p=require("./_plugin-vue_export-helper.js");require("./useComputedDirection.js");require("./useComputedLanguage.js");require("./constants.js");require("./useIconOnlyButton.js");require("./useSlotContents2.js");require("./useWarnOnce.js");const f=l.defineComponent({name:"CdxToggleButtonGroup",components:{CdxIcon:m.CdxIcon,CdxToggleButton:g},props:{buttons:{type:Array,required:!0,validator:e=>Array.isArray(e)&&e.length>=1},modelValue:{type:[String,Number,null,Array],required:!0},disabled:{type:Boolean,default:!1}},emits:["update:modelValue"],setup(e,{emit:n}){function d(t){return Array.isArray(e.modelValue)?e.modelValue.indexOf(t.value)!==-1:e.modelValue!==null?e.modelValue===t.value:!1}function i(t,r){if(Array.isArray(e.modelValue)){const a=e.modelValue.indexOf(t.value)!==-1;r&&!a?n("update:modelValue",e.modelValue.concat(t.value)):!r&&a&&n("update:modelValue",e.modelValue.filter(u=>u!==t.value))}else r&&e.modelValue!==t.value&&n("update:modelValue",t.value)}return{getButtonLabel:s.getButtonLabel,isSelected:d,onUpdate:i}}});const V={class:"cdx-toggle-button-group"};function _(e,n,d,i,t,r){const a=l.resolveComponent("cdx-icon"),u=l.resolveComponent("cdx-toggle-button");return l.openBlock(),l.createElementBlock("div",V,[(l.openBlock(!0),l.createElementBlock(l.Fragment,null,l.renderList(e.buttons,o=>(l.openBlock(),l.createBlock(u,{key:o.value,"model-value":e.isSelected(o),disabled:o.disabled||e.disabled,"aria-label":o.ariaLabel,"onUpdate:modelValue":c=>e.onUpdate(o,c)},{default:l.withCtx(()=>[l.createCommentVNode("\n				@slot Content of an individual button\n				@binding {ButtonGroupItem} button Object describing the button to display\n				@binding {boolean} selected Whether the button is selected\n			"),l.renderSlot(e.$slots,"default",{button:o,selected:e.isSelected(o)},()=>[o.icon?(l.openBlock(),l.createBlock(a,{key:0,icon:o.icon},null,8,["icon"])):l.createCommentVNode("v-if",!0),l.createTextVNode(" "+l.toDisplayString(e.getButtonLabel(o)),1)])]),_:2},1032,["model-value","disabled","aria-label","onUpdate:modelValue"]))),128))])}const v=p._export_sfc(f,[["render",_]]);module.exports=v;
