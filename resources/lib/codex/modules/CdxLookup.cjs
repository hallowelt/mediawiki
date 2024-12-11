"use strict";const t=require("vue"),N=require("./CdxMenu.cjs"),K=require("./CdxTextInput.cjs"),R=require("./useGeneratedId.cjs"),w=require("./useModelWrapper.cjs"),L=require("./useOptionalModelWrapper.js"),D=require("./useSplitAttributes.cjs"),E=require("./useFieldData.cjs"),O=require("./useFloatingMenu.cjs"),h=require("./constants.js"),P=require("./_plugin-vue_export-helper.js"),T=h.makeStringTypeValidator(h.ValidationStatusTypes),W=t.defineComponent({name:"CdxLookup",components:{CdxMenu:N,CdxTextInput:K},inheritAttrs:!1,props:{selected:{type:[String,Number,null],required:!0},menuItems:{type:Array,required:!0},inputValue:{type:[String,Number],default:null},initialInputValue:{type:[String,Number],default:"",validator:e=>(e&&console.warn('[CdxLookup]: prop "initialInputValue" is deprecated. Use "inputValue" instead.'),!0)},disabled:{type:Boolean,default:!1},menuConfig:{type:Object,default:()=>({})},status:{type:String,default:"default",validator:T}},emits:["update:selected","update:input-value","load-more","input","change","focus","blur"],setup:(e,{emit:u,attrs:g,slots:r})=>{const y=t.ref(),c=t.ref(),a=t.ref(),f=R("lookup-menu"),l=t.ref(!1),s=t.ref(!1),v=t.ref(!1),b=t.ref(e.menuItems),{computedDisabled:I,computedStatus:C}=E(t.toRef(e,"disabled"),t.toRef(e,"status")),V=t.toRef(e,"selected"),i=w(V,u,"update:selected"),d=t.computed(()=>{var n;return(n=a.value)==null?void 0:n.getComputedMenuItems().find(o=>o.value===i.value)}),S=t.computed(()=>{var n,o;return(o=(n=a.value)==null?void 0:n.getHighlightedMenuItem())==null?void 0:o.id}),q=t.ref(e.initialInputValue),p=L.useOptionalModelWrapper(q,t.toRef(e,"inputValue"),u,"update:input-value"),k=t.computed(()=>({"cdx-lookup--disabled":I.value,"cdx-lookup--pending":l.value})),{rootClasses:M,rootStyle:$,otherAttrs:x}=D(g,k);function U(n){d.value?d.value.label!==n&&d.value.value!==n&&(i.value=null):e.selected!==null&&e.selected!==n&&(i.value=null),n===""&&b.value.length===0?(s.value=!1,l.value=!1):l.value=!0,u("input",n)}function B(n){v.value=!0;const o=p.value!==null&&p.value!=="";!!(e.menuItems.length>0||r["no-results"])&&(o||b.value.length>0)&&(s.value=!0),u("focus",n)}function F(n){v.value=!1,s.value=!1,u("blur",n)}function A(n){!a.value||I.value||e.menuItems.length===0&&!r["no-results"]||n.key===" "||a.value.delegateKeyNavigation(n)}return O(c,a),t.watch(i,n=>{var o;if(n!==null){const m=d.value?(o=d.value.label)!=null?o:d.value.value:"";p.value!==m&&(p.value=m,u("input",m))}}),t.watch(t.toRef(e,"menuItems"),n=>{v.value&&l.value&&(n.length>0||r["no-results"])&&(s.value=!0),n.length===0&&!r["no-results"]&&(s.value=!1),l.value=!1}),{rootElement:y,textInput:c,menu:a,menuId:f,highlightedId:S,computedInputValue:p,selection:i,expanded:s,computedDisabled:I,computedStatus:C,onInputBlur:F,rootClasses:M,rootStyle:$,otherAttrs:x,onUpdateInput:U,onInputFocus:B,onKeydown:A}}});function z(e,u,g,r,y,c){const a=t.resolveComponent("cdx-text-input"),f=t.resolveComponent("cdx-menu");return t.openBlock(),t.createElementBlock("div",{ref:"rootElement",class:t.normalizeClass(["cdx-lookup",e.rootClasses]),style:t.normalizeStyle(e.rootStyle)},[t.createVNode(a,t.mergeProps({ref:"textInput",modelValue:e.computedInputValue,"onUpdate:modelValue":u[0]||(u[0]=l=>e.computedInputValue=l)},e.otherAttrs,{class:"cdx-lookup__input",role:"combobox",autocomplete:"off","aria-autocomplete":"list","aria-controls":e.menuId,"aria-expanded":e.expanded,"aria-activedescendant":e.highlightedId,disabled:e.computedDisabled,status:e.computedStatus,"onUpdate:modelValue":e.onUpdateInput,onChange:u[1]||(u[1]=l=>e.$emit("change",l)),onFocus:e.onInputFocus,onBlur:e.onInputBlur,onKeydown:e.onKeydown}),null,16,["modelValue","aria-controls","aria-expanded","aria-activedescendant","disabled","status","onUpdate:modelValue","onFocus","onBlur","onKeydown"]),t.createVNode(f,t.mergeProps({id:e.menuId,ref:"menu",selected:e.selection,"onUpdate:selected":u[2]||(u[2]=l=>e.selection=l),expanded:e.expanded,"onUpdate:expanded":u[3]||(u[3]=l=>e.expanded=l),"menu-items":e.menuItems},e.menuConfig,{onLoadMore:u[4]||(u[4]=l=>e.$emit("load-more"))}),{default:t.withCtx(({menuItem:l})=>[t.renderSlot(e.$slots,"menu-item",{menuItem:l})]),"no-results":t.withCtx(()=>[t.renderSlot(e.$slots,"no-results")]),_:3},16,["id","selected","expanded","menu-items"])],6)}const H=P._export_sfc(W,[["render",z]]);module.exports=H;
