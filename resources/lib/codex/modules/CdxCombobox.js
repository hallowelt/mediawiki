"use strict";const o=require("vue"),x=require("./Icon.js"),$=require("./CdxButton.js"),k=require("./CdxMenu.js"),M=require("./CdxTextInput.js"),W=require("./useModelWrapper.js"),N=require("./useGeneratedId.js"),F=require("./useSplitAttributes.js"),A=require("./useFieldData.js"),K=require("./useFloatingMenu.js"),v=require("./constants.js"),D=require("./_plugin-vue_export-helper.js");require("./useComputedDirection.js");require("./useComputedLanguage.js");require("./useIconOnlyButton.js");require("./useSlotContents2.js");require("./useWarnOnce.js");require("./CdxMenuItem.js");require("./CdxThumbnail.js");require("./CdxSearchResultTitle.js");require("./CdxProgressBar.js");require("./useIntersectionObserver.js");require("./useComputedDisabled.js");const E=v.makeStringTypeValidator(v.ValidationStatusTypes),P=o.defineComponent({name:"CdxCombobox",components:{CdxButton:$,CdxIcon:x.CdxIcon,CdxMenu:k,CdxTextInput:M},inheritAttrs:!1,props:{menuItems:{type:Array,required:!0},selected:{type:[String,Number],required:!0},disabled:{type:Boolean,default:!1},menuConfig:{type:Object,default:()=>({})},status:{type:String,default:"default",validator:E}},emits:["update:selected","load-more","input","change","focus","blur"],setup(e,{emit:t,attrs:c,slots:m}){const s=o.ref(),b=o.ref(),u=o.ref(),l=N("combobox"),i=o.toRef(e,"selected"),p=W(i,t,"update:selected"),n=o.ref(!1),a=o.ref(!1),C=o.computed(()=>{var r,f;return(f=(r=u.value)==null?void 0:r.getHighlightedMenuItem())==null?void 0:f.id}),{computedDisabled:d}=A(o.toRef(e,"disabled")),q=o.computed(()=>({"cdx-combobox--expanded":n.value,"cdx-combobox--disabled":d.value})),{rootClasses:g,rootStyle:I,otherAttrs:y}=F(c,q);function w(r){a.value&&n.value?n.value=!1:(e.menuItems.length>0||m["no-results"])&&(n.value=!0),t("focus",r)}function B(r){n.value=a.value&&n.value,t("blur",r)}function V(){d.value||(a.value=!0)}function h(){var r;d.value||(r=s.value)==null||r.focus()}function S(r){!u.value||d.value||e.menuItems.length===0||r.key===" "||u.value.delegateKeyNavigation(r)}return K(s,u),o.watch(n,()=>{a.value=!1}),{input:s,inputWrapper:b,menu:u,menuId:l,modelWrapper:p,expanded:n,highlightedId:C,computedDisabled:d,onInputFocus:w,onInputBlur:B,onKeydown:S,onButtonClick:h,onButtonMousedown:V,cdxIconExpand:x.p4,rootClasses:g,rootStyle:I,otherAttrs:y}}});const T={ref:"inputWrapper",class:"cdx-combobox__input-wrapper"};function U(e,t,c,m,s,b){const u=o.resolveComponent("cdx-text-input"),l=o.resolveComponent("cdx-icon"),i=o.resolveComponent("cdx-button"),p=o.resolveComponent("cdx-menu");return o.openBlock(),o.createElementBlock("div",{class:o.normalizeClass(["cdx-combobox",e.rootClasses]),style:o.normalizeStyle(e.rootStyle)},[o.createElementVNode("div",T,[o.createVNode(u,o.mergeProps({ref:"input",modelValue:e.modelWrapper,"onUpdate:modelValue":t[0]||(t[0]=n=>e.modelWrapper=n)},e.otherAttrs,{class:"cdx-combobox__input","aria-activedescendant":e.highlightedId,"aria-expanded":e.expanded,"aria-controls":e.menuId,disabled:e.computedDisabled,status:e.status,autocomplete:"off",role:"combobox",onKeydown:e.onKeydown,onInput:t[1]||(t[1]=n=>e.$emit("input",n)),onChange:t[2]||(t[2]=n=>e.$emit("change",n)),onFocus:e.onInputFocus,onBlur:e.onInputBlur}),null,16,["modelValue","aria-activedescendant","aria-expanded","aria-controls","disabled","status","onKeydown","onFocus","onBlur"]),o.createVNode(i,{class:"cdx-combobox__expand-button","aria-hidden":"true",disabled:e.computedDisabled,tabindex:"-1",type:"button",onMousedown:e.onButtonMousedown,onClick:e.onButtonClick},{default:o.withCtx(()=>[o.createVNode(l,{class:"cdx-combobox__expand-icon",icon:e.cdxIconExpand},null,8,["icon"])]),_:1},8,["disabled","onMousedown","onClick"])],512),o.createVNode(p,o.mergeProps({id:e.menuId,ref:"menu",selected:e.modelWrapper,"onUpdate:selected":t[3]||(t[3]=n=>e.modelWrapper=n),expanded:e.expanded,"onUpdate:expanded":t[4]||(t[4]=n=>e.expanded=n),"menu-items":e.menuItems},e.menuConfig,{onLoadMore:t[5]||(t[5]=n=>e.$emit("load-more"))}),{default:o.withCtx(({menuItem:n})=>[o.renderSlot(e.$slots,"menu-item",{menuItem:n})]),"no-results":o.withCtx(()=>[o.renderSlot(e.$slots,"no-results")]),_:3},16,["id","selected","expanded","menu-items"])],6)}const z=D._export_sfc(P,[["render",U]]);module.exports=z;
