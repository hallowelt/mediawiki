"use strict";var g=(e,n,c)=>new Promise((v,d)=>{var r=s=>{try{o(c.next(s))}catch(a){d(a)}},u=s=>{try{o(c.throw(s))}catch(a){d(a)}},o=s=>s.done?v(s.value):Promise.resolve(s.value).then(r,u);o((c=c.apply(e,n)).next())});const t=require("vue"),T=require("./CdxButton.js"),b=require("./Icon.js"),I=require("./_plugin-vue_export-helper.js"),w=require("./constants.js"),z=require("./useSplitAttributes.js"),L=require("./useFieldData.js"),O=require("./useComputedDirection.js");require("./useIconOnlyButton.js");require("./useSlotContents2.js");require("./useWarnOnce.js");require("./useComputedLanguage.js");require("./useComputedDisabled.js");const M=t.defineComponent({name:"CdxInputChip",components:{CdxButton:T,CdxIcon:b.CdxIcon},props:{chipAriaDescription:{type:String,required:!0},icon:{type:[String,Object],default:null},disabled:{type:Boolean,default:!1}},expose:["focus"],emits:["remove-chip","click-chip","arrow-left","arrow-right"],setup(e,{emit:n}){const c=t.ref(),v=t.computed(()=>({"cdx-input-chip--disabled":e.disabled}));function d(r){var u;switch(r.key){case"Enter":n("click-chip"),r.preventDefault(),r.stopPropagation();break;case"Escape":(u=c.value)==null||u.blur(),r.preventDefault(),r.stopPropagation();break;case"Backspace":case"Delete":n("remove-chip",r.key),r.preventDefault(),r.stopPropagation();break;case"ArrowLeft":n("arrow-left"),r.preventDefault(),r.stopPropagation();break;case"ArrowRight":n("arrow-right"),r.preventDefault(),r.stopPropagation();break}}return{rootElement:c,rootClasses:v,onKeydown:d,cdxIconClose:b.j3}},methods:{focus(){this.$refs.rootElement.focus()}}});const j=["aria-description"],U={class:"cdx-input-chip__text"};function H(e,n,c,v,d,r){const u=t.resolveComponent("cdx-icon"),o=t.resolveComponent("cdx-button");return t.openBlock(),t.createElementBlock("div",{ref:"rootElement",class:t.normalizeClass(["cdx-input-chip",e.rootClasses]),tabindex:"0",role:"option","aria-description":e.chipAriaDescription,onKeydown:n[1]||(n[1]=(...s)=>e.onKeydown&&e.onKeydown(...s)),onClick:n[2]||(n[2]=s=>e.$emit("click-chip"))},[e.icon?(t.openBlock(),t.createBlock(u,{key:0,icon:e.icon,size:"small"},null,8,["icon"])):t.createCommentVNode("v-if",!0),t.createElementVNode("span",U,[t.renderSlot(e.$slots,"default")]),t.createVNode(o,{class:"cdx-input-chip__button",weight:"quiet",tabindex:"-1","aria-hidden":"true",disabled:e.disabled,onClick:n[0]||(n[0]=t.withModifiers(s=>e.$emit("remove-chip","button"),["stop"]))},{default:t.withCtx(()=>[t.createVNode(u,{icon:e.cdxIconClose,size:"x-small"},null,8,["icon"])]),_:1},8,["disabled"])],42,j)}const G=I._export_sfc(M,[["render",H]]),J=w.makeStringTypeValidator(w.ValidationStatusTypes),Q=t.defineComponent({name:"CdxChipInput",components:{CdxInputChip:G},inheritAttrs:!1,props:{chipAriaDescription:{type:String,required:!0},inputChips:{type:Array,required:!0},separateInput:{type:Boolean,default:!1},status:{type:String,default:"default",validator:J},disabled:{type:Boolean,default:!1}},emits:["update:input-chips"],setup(e,{emit:n,attrs:c}){const v=t.ref(),d=O(v),r=t.ref(),u=t.ref(""),o=t.ref("default"),s=t.computed(()=>o.value==="error"||e.status==="error"?"error":"default"),{computedDisabled:a,computedStatus:B}=L(t.toRef(e,"disabled"),s),C=t.ref(!1),D=t.computed(()=>({"cdx-chip-input--has-separate-input":e.separateInput,["cdx-chip-input--status-".concat(B.value)]:!0,"cdx-chip-input--focused":C.value,"cdx-chip-input--disabled":a.value})),{rootClasses:$,rootStyle:V,otherAttrs:A}=z(c,D),m=[];function E(i,l){i!==null&&(m[l]=i)}const h=()=>{r.value.focus()};function k(){e.inputChips.find(i=>i.value===u.value)?o.value="error":u.value.length>0&&(n("update:input-chips",e.inputChips.concat({value:u.value})),u.value="")}function y(i){n("update:input-chips",e.inputChips.filter(l=>l.value!==i.value))}function q(i,l){const f=d.value==="ltr"&&i==="left"||d.value==="rtl"&&i==="right"?-1:1,p=l+f;if(!(p<0)){if(p>=e.inputChips.length){h();return}m[p].focus()}}function F(i){return g(this,null,function*(){k(),yield t.nextTick(),y(i),u.value=i.value,h()})}function S(i,l,f){if(f==="button")h();else if(f==="Backspace"){const p=l===0?1:l-1;p<e.inputChips.length?m[p].focus():h()}else if(f==="Delete"){const p=l+1;p<e.inputChips.length?m[p].focus():h()}y(i)}function R(i){var f,p;const l=d.value==="rtl"?"ArrowRight":"ArrowLeft";switch(i.key){case"Enter":if(u.value.length>0){k(),i.preventDefault(),i.stopPropagation();return}break;case"Escape":(f=r.value)==null||f.blur(),i.preventDefault(),i.stopPropagation();return;case"Backspace":case l:if(((p=r.value)==null?void 0:p.selectionStart)===0&&r.value.selectionEnd===0&&e.inputChips.length>0){m[e.inputChips.length-1].focus(),i.preventDefault(),i.stopPropagation();return}break}}function K(){C.value=!0}function P(){C.value=!1}function N(i){v.value.contains(i.relatedTarget)||k()}return t.watch(t.toRef(e,"inputChips"),i=>{const l=i.find(f=>f.value===u.value);o.value=l?"error":"default"}),t.watch(u,()=>{o.value==="error"&&(o.value="default")}),{rootElement:v,input:r,inputValue:u,rootClasses:$,rootStyle:V,otherAttrs:A,assignChipTemplateRef:E,handleChipClick:F,handleChipRemove:S,moveChipFocus:q,onInputKeydown:R,focusInput:h,onInputFocus:K,onInputBlur:P,onFocusOut:N,computedDisabled:a}}});const W={class:"cdx-chip-input__chips",role:"listbox","aria-orientation":"horizontal"},X=["disabled"],Y={key:0,class:"cdx-chip-input__separate-input"},Z=["disabled"];function x(e,n,c,v,d,r){const u=t.resolveComponent("cdx-input-chip");return t.openBlock(),t.createElementBlock("div",{ref:"rootElement",class:t.normalizeClass(["cdx-chip-input",e.rootClasses]),style:t.normalizeStyle(e.rootStyle),onClick:n[8]||(n[8]=(...o)=>e.focusInput&&e.focusInput(...o)),onFocusout:n[9]||(n[9]=(...o)=>e.onFocusOut&&e.onFocusOut(...o))},[t.createElementVNode("div",W,[(t.openBlock(!0),t.createElementBlock(t.Fragment,null,t.renderList(e.inputChips,(o,s)=>(t.openBlock(),t.createBlock(u,{key:o.value,ref_for:!0,ref:a=>e.assignChipTemplateRef(a,s),class:"cdx-chip-input__item","chip-aria-description":e.chipAriaDescription,icon:o.icon,disabled:e.computedDisabled,onClickChip:a=>e.handleChipClick(o),onRemoveChip:a=>e.handleChipRemove(o,s,a),onArrowLeft:a=>e.moveChipFocus("left",s),onArrowRight:a=>e.moveChipFocus("right",s)},{default:t.withCtx(()=>[t.createTextVNode(t.toDisplayString(o.value),1)]),_:2},1032,["chip-aria-description","icon","disabled","onClickChip","onRemoveChip","onArrowLeft","onArrowRight"]))),128)),e.separateInput?t.createCommentVNode("v-if",!0):t.withDirectives((t.openBlock(),t.createElementBlock("input",t.mergeProps({key:0,ref:"input","onUpdate:modelValue":n[0]||(n[0]=o=>e.inputValue=o),class:"cdx-chip-input__input",disabled:e.computedDisabled},e.otherAttrs,{onBlur:n[1]||(n[1]=(...o)=>e.onInputBlur&&e.onInputBlur(...o)),onFocus:n[2]||(n[2]=(...o)=>e.onInputFocus&&e.onInputFocus(...o)),onKeydown:n[3]||(n[3]=(...o)=>e.onInputKeydown&&e.onInputKeydown(...o))}),null,16,X)),[[t.vModelDynamic,e.inputValue]])]),e.separateInput?(t.openBlock(),t.createElementBlock("div",Y,[t.withDirectives(t.createElementVNode("input",t.mergeProps({ref:"input","onUpdate:modelValue":n[4]||(n[4]=o=>e.inputValue=o),class:"cdx-chip-input__input",disabled:e.computedDisabled},e.otherAttrs,{onBlur:n[5]||(n[5]=(...o)=>e.onInputBlur&&e.onInputBlur(...o)),onFocus:n[6]||(n[6]=(...o)=>e.onInputFocus&&e.onInputFocus(...o)),onKeydown:n[7]||(n[7]=(...o)=>e.onInputKeydown&&e.onInputKeydown(...o))}),null,16,Z),[[t.vModelDynamic,e.inputValue]])])):t.createCommentVNode("v-if",!0)],38)}const _=I._export_sfc(Q,[["render",x]]);module.exports=_;
