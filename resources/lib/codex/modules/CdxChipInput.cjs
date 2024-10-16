"use strict";var V=(e,o,p)=>new Promise((f,v)=>{var h=r=>{try{n(p.next(r))}catch(a){v(a)}},u=r=>{try{n(p.throw(r))}catch(a){v(a)}},n=r=>r.done?f(r.value):Promise.resolve(r.value).then(h,u);n((p=p.apply(e,o)).next())});const t=require("vue"),X=require("./CdxButton.cjs"),$=require("./Icon.js"),k=require("./useI18n.cjs"),E=require("./_plugin-vue_export-helper.js"),y=require("./constants.js"),G=require("./useSplitAttributes.cjs"),J=require("./useFieldData.cjs"),Q=require("./useComputedDirection.cjs"),Y=require("./useOptionalModelWrapper.js"),Z=t.defineComponent({name:"CdxInputChip",components:{CdxButton:X,CdxIcon:$.CdxIcon},props:{icon:{type:[String,Object],default:null},disabled:{type:Boolean,default:!1}},expose:["focus"],emits:["remove-chip","click-chip","arrow-left","arrow-right"],setup(e,{emit:o}){const p=t.ref(),f=t.computed(()=>({"cdx-input-chip--disabled":e.disabled})),v=k("cdx-input-chip-aria-description","Press Enter to edit or Delete to remove");function h(u){var n;switch(u.key){case"Enter":o("click-chip"),u.preventDefault(),u.stopPropagation();break;case"Escape":(n=p.value)==null||n.blur(),u.preventDefault(),u.stopPropagation();break;case"Backspace":case"Delete":o("remove-chip",u.key),u.preventDefault(),u.stopPropagation();break;case"ArrowLeft":o("arrow-left"),u.preventDefault(),u.stopPropagation();break;case"ArrowRight":o("arrow-right"),u.preventDefault(),u.stopPropagation();break}}return{rootElement:p,rootClasses:f,ariaDescription:v,onKeydown:h,cdxIconClose:$.X3}},methods:{focus(){this.$refs.rootElement.focus()}}}),_=["aria-description"],ee={class:"cdx-input-chip__text"};function te(e,o,p,f,v,h){const u=t.resolveComponent("cdx-icon"),n=t.resolveComponent("cdx-button");return t.openBlock(),t.createElementBlock("div",{ref:"rootElement",class:t.normalizeClass(["cdx-input-chip",e.rootClasses]),tabindex:"0",role:"option","aria-description":e.ariaDescription,onKeydown:o[1]||(o[1]=(...r)=>e.onKeydown&&e.onKeydown(...r)),onClick:o[2]||(o[2]=r=>e.$emit("click-chip"))},[e.icon?(t.openBlock(),t.createBlock(u,{key:0,icon:e.icon,size:"small"},null,8,["icon"])):t.createCommentVNode("v-if",!0),t.createElementVNode("span",ee,[t.renderSlot(e.$slots,"default")]),t.createVNode(n,{class:"cdx-input-chip__button",weight:"quiet",tabindex:"-1","aria-hidden":"true",disabled:e.disabled,onClick:o[0]||(o[0]=t.withModifiers(r=>e.$emit("remove-chip","button"),["stop"]))},{default:t.withCtx(()=>[t.createVNode(u,{icon:e.cdxIconClose,size:"x-small"},null,8,["icon"])]),_:1},8,["disabled"])],42,_)}const ne=E._export_sfc(Z,[["render",te]]),oe=y.makeStringTypeValidator(y.ValidationStatusTypes),ie=t.defineComponent({name:"CdxChipInput",components:{CdxInputChip:ne},inheritAttrs:!1,props:{inputChips:{type:Array,required:!0},inputValue:{type:[String,Number],default:null},separateInput:{type:Boolean,default:!1},status:{type:String,default:"default",validator:oe},chipValidator:{type:Function,default:e=>!0},disabled:{type:Boolean,default:!1}},emits:["update:input-chips","update:input-value"],setup(e,{emit:o,attrs:p}){const f=t.ref(),v=t.ref(),h=t.ref(),u=t.ref(""),n=Q(f),r=t.ref(),a=t.inject(y.AllowArbitraryKey,t.ref(!0)),A=t.ref(""),c=Y.useOptionalModelWrapper(A,t.toRef(e,"inputValue"),o,"update:input-value"),C=t.ref("default"),F=t.computed(()=>C.value==="error"||e.status==="error"?"error":"default"),{computedDisabled:B,computedStatus:S}=J(t.toRef(e,"disabled"),F),b=t.ref(!1),R=t.computed(()=>({"cdx-chip-input--has-separate-input":e.separateInput,["cdx-chip-input--status-".concat(S.value)]:!0,"cdx-chip-input--focused":b.value,"cdx-chip-input--disabled":B.value})),{rootClasses:q,rootStyle:K,otherAttrs:N}=G(p,R),g=[],I=t.ref(null),P=t.computed(()=>I.value?I.value.value:""),M=k("cdx-chip-input-chip-added",i=>"Chip ".concat(i," was added."),[c]),T=k("cdx-chip-input-chip-removed",i=>"Chip ".concat(i," was removed."),[P]);function z(i,s){i!==null&&(g[s]=i)}const m=()=>{r.value.focus()};function w(){e.inputChips.find(i=>i.value===c.value)||!e.chipValidator(c.value)?C.value="error":c.value.toString().length>0&&(u.value=M.value,o("update:input-chips",e.inputChips.concat({value:c.value})),c.value="")}function D(i){o("update:input-chips",e.inputChips.filter(s=>s.value!==i.value))}function O(i,s){const d=n.value==="ltr"&&i==="left"||n.value==="rtl"&&i==="right"?-1:1,l=s+d;if(!(l<0)){if(l>=e.inputChips.length){m();return}g[l].focus()}}function L(i){return V(this,null,function*(){var s;w(),yield t.nextTick(),D(i),c.value=(s=i.label)!=null?s:i.value,m()})}function W(i,s,d){if(I.value=i,u.value=T.value,d==="button")m();else if(d==="Backspace"){const l=s===0?1:s-1;l<e.inputChips.length?g[l].focus():m()}else if(d==="Delete"){const l=s+1;l<e.inputChips.length?g[l].focus():m()}D(i)}function j(i){var d,l;const s=n.value==="rtl"?"ArrowRight":"ArrowLeft";switch(i.key){case"Enter":if(c.value.toString().length>0&&a.value){w(),i.preventDefault(),i.stopPropagation();return}break;case"Escape":(d=r.value)==null||d.blur(),i.preventDefault(),i.stopPropagation();return;case"Backspace":case s:if(((l=r.value)==null?void 0:l.selectionStart)===0&&r.value.selectionEnd===0&&e.inputChips.length>0){g[e.inputChips.length-1].focus(),i.preventDefault(),i.stopPropagation();return}break}}function U(){b.value=!0}function x(){b.value=!1}function H(i){var s;!((s=f.value)!=null&&s.contains(i.relatedTarget))&&a.value&&w()}return t.watch(t.toRef(e,"inputChips"),i=>{const s=i.find(d=>d.value===c.value);C.value=s?"error":"default"}),t.watch(c,()=>{C.value==="error"&&(C.value="default")}),{rootElement:f,chipsContainer:v,separateInputWrapper:h,input:r,computedInputValue:c,rootClasses:q,rootStyle:K,otherAttrs:N,assignChipTemplateRef:z,handleChipClick:L,handleChipRemove:W,moveChipFocus:O,onInputKeydown:j,focusInput:m,onInputFocus:U,onInputBlur:x,onFocusOut:H,computedDisabled:B,statusMessageContent:u}}}),ue={ref:"chipsContainer",class:"cdx-chip-input__chips",role:"listbox","aria-orientation":"horizontal"},re=["disabled"],se={key:0,ref:"separateInputWrapper",class:"cdx-chip-input__separate-input"},ae=["disabled"],le={class:"cdx-chip-input__aria-status",role:"status","aria-live":"polite"};function pe(e,o,p,f,v,h){const u=t.resolveComponent("cdx-input-chip");return t.openBlock(),t.createElementBlock("div",{ref:"rootElement",class:t.normalizeClass(["cdx-chip-input",e.rootClasses]),style:t.normalizeStyle(e.rootStyle),onClick:o[8]||(o[8]=(...n)=>e.focusInput&&e.focusInput(...n)),onFocusout:o[9]||(o[9]=(...n)=>e.onFocusOut&&e.onFocusOut(...n))},[t.createElementVNode("div",ue,[(t.openBlock(!0),t.createElementBlock(t.Fragment,null,t.renderList(e.inputChips,(n,r)=>(t.openBlock(),t.createBlock(u,{key:n.value,ref_for:!0,ref:a=>e.assignChipTemplateRef(a,r),class:"cdx-chip-input__item",icon:n.icon,disabled:e.computedDisabled,onClickChip:a=>e.handleChipClick(n),onRemoveChip:a=>e.handleChipRemove(n,r,a),onArrowLeft:a=>e.moveChipFocus("left",r),onArrowRight:a=>e.moveChipFocus("right",r)},{default:t.withCtx(()=>{var a;return[t.createTextVNode(t.toDisplayString((a=n.label)!=null?a:n.value),1)]}),_:2},1032,["icon","disabled","onClickChip","onRemoveChip","onArrowLeft","onArrowRight"]))),128)),e.separateInput?t.createCommentVNode("v-if",!0):t.withDirectives((t.openBlock(),t.createElementBlock("input",t.mergeProps({key:0,ref:"input","onUpdate:modelValue":o[0]||(o[0]=n=>e.computedInputValue=n),class:"cdx-chip-input__input",disabled:e.computedDisabled},e.otherAttrs,{onBlur:o[1]||(o[1]=(...n)=>e.onInputBlur&&e.onInputBlur(...n)),onFocus:o[2]||(o[2]=(...n)=>e.onInputFocus&&e.onInputFocus(...n)),onKeydown:o[3]||(o[3]=(...n)=>e.onInputKeydown&&e.onInputKeydown(...n))}),null,16,re)),[[t.vModelDynamic,e.computedInputValue]])],512),e.separateInput?(t.openBlock(),t.createElementBlock("div",se,[t.withDirectives(t.createElementVNode("input",t.mergeProps({ref:"input","onUpdate:modelValue":o[4]||(o[4]=n=>e.computedInputValue=n),class:"cdx-chip-input__input",disabled:e.computedDisabled},e.otherAttrs,{onBlur:o[5]||(o[5]=(...n)=>e.onInputBlur&&e.onInputBlur(...n)),onFocus:o[6]||(o[6]=(...n)=>e.onInputFocus&&e.onInputFocus(...n)),onKeydown:o[7]||(o[7]=(...n)=>e.onInputKeydown&&e.onInputKeydown(...n))}),null,16,ae),[[t.vModelDynamic,e.computedInputValue]])],512)):t.createCommentVNode("v-if",!0),t.createElementVNode("div",le,t.toDisplayString(e.statusMessageContent),1)],38)}const ce=E._export_sfc(ie,[["render",pe]]);module.exports=ce;
