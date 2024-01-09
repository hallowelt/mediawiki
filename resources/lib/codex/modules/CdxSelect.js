"use strict";var y=Object.getOwnPropertySymbols;var K=Object.prototype.hasOwnProperty,w=Object.prototype.propertyIsEnumerable;var b=(e,n)=>{var a={};for(var o in e)K.call(e,o)&&n.indexOf(o)<0&&(a[o]=e[o]);if(e!=null&&y)for(var o of y(e))n.indexOf(o)<0&&w.call(e,o)&&(a[o]=e[o]);return a};const t=require("vue"),g=require("./Icon.js"),D=require("./CdxMenu.js"),q=require("./useGeneratedId.js"),H=require("./useModelWrapper.js"),W=require("./useFieldData.js"),j=require("./useSplitAttributes.js"),F=require("./useFloatingMenu.js"),p=require("./constants.js"),R=require("./_plugin-vue_export-helper.js");require("./useComputedDirection.js");require("./useComputedLanguage.js");require("./CdxMenuItem.js");require("./CdxThumbnail.js");require("./CdxSearchResultTitle.js");require("./CdxProgressBar.js");require("./useWarnOnce.js");require("./useIntersectionObserver.js");require("./useComputedDisabled.js");const T=p.makeStringTypeValidator(p.ValidationStatusTypes),z=t.defineComponent({name:"CdxSelect",components:{CdxIcon:g.CdxIcon,CdxMenu:D},inheritAttrs:!1,props:{menuItems:{type:Array,required:!0},selected:{type:[String,Number,null],required:!0},defaultLabel:{type:String,default:""},disabled:{type:Boolean,default:!1},menuConfig:{type:Object,default:()=>({})},defaultIcon:{type:[String,Object],default:void 0},status:{type:String,default:"default",validator:T}},emits:["update:selected","load-more"],setup(e,{emit:n,attrs:a}){const o=t.ref(),i=t.ref(),v=t.inject(p.FieldDescriptionIdKey,void 0),m=q("select-menu"),u=t.ref(!1),r=a.id||q("select-handle"),{computedDisabled:c,computedStatus:C,computedInputId:S}=W(t.toRef(e,"disabled"),t.toRef(e,"status"),r),h=H(t.toRef(e,"selected"),n,"update:selected"),l=t.computed(()=>e.menuItems.find(d=>d.value===e.selected)),x=t.computed(()=>{var d;return l.value?(d=l.value.label)!=null?d:l.value.value:e.defaultLabel}),f=t.computed(()=>{var d;if(e.defaultIcon&&!l.value)return e.defaultIcon;if((d=l.value)!=null&&d.icon)return l.value.icon}),k=t.computed(()=>({"cdx-select-vue--enabled":!c.value,"cdx-select-vue--disabled":c.value,"cdx-select-vue--expanded":u.value,"cdx-select-vue--value-selected":!!l.value,"cdx-select-vue--no-selections":!l.value,"cdx-select-vue--has-start-icon":!!f.value,["cdx-select-vue--status-".concat(C.value)]:!0})),{rootClasses:M,rootStyle:B,otherAttrs:V}=j(a,k),$=t.computed(()=>{const I=V.value,{id:d}=I;return b(I,["id"])}),N=t.computed(()=>{var d,s;return(s=(d=i.value)==null?void 0:d.getHighlightedMenuItem())==null?void 0:s.id});function L(){u.value=!1}function A(){var d;c.value||(u.value=!u.value,(d=o.value)==null||d.focus())}function E(d){var s;c.value||(s=i.value)==null||s.delegateKeyNavigation(d,{characterNavigation:!0})}return F(o,i),{handle:o,menu:i,computedHandleId:S,descriptionId:v,menuId:m,modelWrapper:h,selectedMenuItem:l,highlightedId:N,expanded:u,computedDisabled:c,onBlur:L,currentLabel:x,rootClasses:M,rootStyle:B,otherAttrsMinusId:$,onClick:A,onKeydown:E,startIcon:f,cdxIconExpand:g.p4}}});const O=["aria-disabled"],P=["id","aria-controls","aria-activedescendant","aria-expanded","aria-describedby"];function U(e,n,a,o,i,v){const m=t.resolveComponent("cdx-icon"),u=t.resolveComponent("cdx-menu");return t.openBlock(),t.createElementBlock("div",{class:t.normalizeClass(["cdx-select-vue",e.rootClasses]),style:t.normalizeStyle(e.rootStyle),"aria-disabled":e.computedDisabled},[t.createElementVNode("div",t.mergeProps({id:e.computedHandleId,ref:"handle",class:"cdx-select-vue__handle"},e.otherAttrsMinusId,{tabindex:"0",role:"combobox","aria-controls":e.menuId,"aria-activedescendant":e.highlightedId,"aria-expanded":e.expanded,"aria-describedby":e.descriptionId,onClick:n[0]||(n[0]=(...r)=>e.onClick&&e.onClick(...r)),onBlur:n[1]||(n[1]=(...r)=>e.onBlur&&e.onBlur(...r)),onKeydown:n[2]||(n[2]=(...r)=>e.onKeydown&&e.onKeydown(...r))}),[t.renderSlot(e.$slots,"label",{selectedMenuItem:e.selectedMenuItem,defaultLabel:e.defaultLabel},()=>[t.createTextVNode(t.toDisplayString(e.currentLabel),1)]),e.startIcon?(t.openBlock(),t.createBlock(m,{key:0,icon:e.startIcon,class:"cdx-select-vue__start-icon"},null,8,["icon"])):t.createCommentVNode("v-if",!0),t.createVNode(m,{icon:e.cdxIconExpand,class:"cdx-select-vue__indicator"},null,8,["icon"])],16,P),t.createVNode(u,t.mergeProps({id:e.menuId,ref:"menu",selected:e.modelWrapper,"onUpdate:selected":n[3]||(n[3]=r=>e.modelWrapper=r),expanded:e.expanded,"onUpdate:expanded":n[4]||(n[4]=r=>e.expanded=r),"menu-items":e.menuItems},e.menuConfig,{onLoadMore:n[5]||(n[5]=r=>e.$emit("load-more"))}),{default:t.withCtx(({menuItem:r})=>[t.renderSlot(e.$slots,"menu-item",{menuItem:r})]),_:3},16,["id","selected","expanded","menu-items"])],14,O)}const G=R._export_sfc(z,[["render",U]]);module.exports=G;
