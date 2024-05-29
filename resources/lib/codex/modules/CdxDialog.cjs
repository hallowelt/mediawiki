"use strict";var B=(o,n,d)=>new Promise((u,s)=>{var i=l=>{try{r(d.next(l))}catch(f){s(f)}},c=l=>{try{r(d.throw(l))}catch(f){s(f)}},r=l=>l.done?u(l.value):Promise.resolve(l.value).then(i,c);r((d=d.apply(o,n)).next())});const e=require("vue"),q=require("./CdxButton.cjs"),A=require("./Icon.js"),L=require("./useGeneratedId.cjs"),_=require("./useResizeObserver.cjs"),P=require("./_plugin-vue_export-helper.js"),z=e.defineComponent({name:"CdxDialog",components:{CdxButton:q,CdxIcon:A.CdxIcon},inheritAttrs:!1,props:{open:{type:Boolean,default:!1},title:{type:String,required:!0},subtitle:{type:String,required:!1,default:null},hideTitle:{type:Boolean,default:!1},closeButtonLabel:{type:String,default:""},primaryAction:{type:Object,default:null},defaultAction:{type:Object,default:null},stackedActions:{type:Boolean,default:!1},target:{type:String,default:null},renderInPlace:{type:Boolean,default:!1}},emits:["update:open","primary","default"],setup(o,{emit:n}){const d=L("dialog-label"),u=e.ref(),s=e.ref(),i=e.ref(),c=e.ref(),r=e.ref(),l=e.ref(),f=e.computed(()=>!o.hideTitle||!!o.closeButtonLabel),E=e.computed(()=>!!o.primaryAction||!!o.defaultAction),$=_(i),w=e.computed(()=>{var t;return(t=$.value.height)!=null?t:0}),y=e.ref(!1),N=e.computed(()=>({"cdx-dialog--vertical-actions":o.stackedActions,"cdx-dialog--horizontal-actions":!o.stackedActions,"cdx-dialog--dividers":y.value})),T=e.inject("CdxTeleportTarget",void 0),V=e.computed(()=>{var t,a;return(a=(t=o.target)!=null?t:T)!=null?a:"body"}),h=e.ref(0);function S(){n("update:open",!1)}function I(){m(s.value)}function H(){m(s.value,!0)}function m(t,a=!1){let g=Array.from(t.querySelectorAll('\n					input, select, textarea, button, object, a, area,\n					[contenteditable], [tabindex]:not([tabindex^="-"])\n				'));a&&(g=g.reverse());for(const C of g)if(C.focus(),document.activeElement===C)return!0;return!1}let p=[],b=[];function D(){let t=u.value;for(;t.parentElement&&t.nodeName!=="BODY";){for(const a of Array.from(t.parentElement.children))a===t||a.nodeName==="SCRIPT"||(a.hasAttribute("aria-hidden")||(a.setAttribute("aria-hidden","true"),p.push(a)),a.hasAttribute("inert")||(a.setAttribute("inert",""),b.push(a)));t=t.parentElement}}function F(){for(const t of p)t.removeAttribute("aria-hidden");for(const t of b)t.removeAttribute("inert");p=[],b=[]}function v(){return B(this,null,function*(){var t;yield e.nextTick(),h.value=window.innerWidth-document.documentElement.clientWidth,document.documentElement.style.setProperty("margin-right","".concat(h.value,"px")),document.body.classList.add("cdx-dialog-open"),D(),m(i.value)||(t=c.value)==null||t.focus()})}function k(){document.body.classList.remove("cdx-dialog-open"),document.documentElement.style.removeProperty("margin-right"),F()}return e.onMounted(()=>{o.open&&v()}),e.onUnmounted(()=>{o.open&&k()}),e.watch(e.toRef(o,"open"),t=>{t?v():k()}),e.watch(w,()=>{i.value&&(y.value=i.value.clientHeight<i.value.scrollHeight)}),{close:S,cdxIconClose:A.X3,labelId:d,rootClasses:N,backdrop:u,dialogElement:s,focusTrapStart:r,focusTrapEnd:l,focusFirst:I,focusLast:H,dialogBody:i,focusHolder:c,showHeader:f,showFooterActions:E,computedTarget:V}}}),O=["aria-label","aria-labelledby"],j={key:0,class:"cdx-dialog__header__title-group"},x=["id"],R={key:0,class:"cdx-dialog__header__subtitle"},W={ref:"focusHolder",class:"cdx-dialog-focus-trap",tabindex:"-1"},K={key:0,class:"cdx-dialog__footer__text"},M={key:1,class:"cdx-dialog__footer__actions"};function G(o,n,d,u,s,i){const c=e.resolveComponent("cdx-icon"),r=e.resolveComponent("cdx-button");return e.openBlock(),e.createBlock(e.Teleport,{to:o.computedTarget,disabled:o.renderInPlace},[e.createVNode(e.Transition,{name:"cdx-dialog-fade",appear:""},{default:e.withCtx(()=>[o.open?(e.openBlock(),e.createElementBlock("div",{key:0,ref:"backdrop",class:"cdx-dialog-backdrop",onClick:n[5]||(n[5]=(...l)=>o.close&&o.close(...l)),onKeyup:n[6]||(n[6]=e.withKeys((...l)=>o.close&&o.close(...l),["escape"]))},[e.createElementVNode("div",{ref:"focusTrapStart",tabindex:"0",onFocus:n[0]||(n[0]=(...l)=>o.focusLast&&o.focusLast(...l))},null,544),e.createElementVNode("div",e.mergeProps({ref:"dialogElement",class:["cdx-dialog",o.rootClasses],role:"dialog"},o.$attrs,{"aria-label":o.$slots.header||o.hideTitle?o.title:void 0,"aria-labelledby":!o.$slots.header&&!o.hideTitle?o.labelId:void 0,"aria-modal":"true",onClick:n[3]||(n[3]=e.withModifiers(()=>{},["stop"]))}),[o.showHeader||o.$slots.header?(e.openBlock(),e.createElementBlock("header",{key:0,class:e.normalizeClass(["cdx-dialog__header",{"cdx-dialog__header--default":!o.$slots.header}])},[e.renderSlot(o.$slots,"header",{},()=>[o.hideTitle?e.createCommentVNode("v-if",!0):(e.openBlock(),e.createElementBlock("div",j,[e.createElementVNode("h2",{id:o.labelId,class:"cdx-dialog__header__title"},e.toDisplayString(o.title),9,x),o.subtitle?(e.openBlock(),e.createElementBlock("p",R,e.toDisplayString(o.subtitle),1)):e.createCommentVNode("v-if",!0)])),o.closeButtonLabel?(e.openBlock(),e.createBlock(r,{key:1,class:"cdx-dialog__header__close-button",weight:"quiet",type:"button","aria-label":o.closeButtonLabel,onClick:o.close},{default:e.withCtx(()=>[e.createVNode(c,{icon:o.cdxIconClose,"icon-label":o.closeButtonLabel},null,8,["icon","icon-label"])]),_:1},8,["aria-label","onClick"])):e.createCommentVNode("v-if",!0)])],2)):e.createCommentVNode("v-if",!0),e.createElementVNode("div",W,null,512),e.createElementVNode("div",{ref:"dialogBody",class:e.normalizeClass(["cdx-dialog__body",{"cdx-dialog__body--no-header":!(o.showHeader||o.$slots.header),"cdx-dialog__body--no-footer":!(o.showFooterActions||o.$slots.footer||o.$slots["footer-text"])}])},[e.renderSlot(o.$slots,"default")],2),o.showFooterActions||o.$slots.footer||o.$slots["footer-text"]?(e.openBlock(),e.createElementBlock("footer",{key:1,class:e.normalizeClass(["cdx-dialog__footer",{"cdx-dialog__footer--default":!o.$slots.footer}])},[e.renderSlot(o.$slots,"footer",{},()=>[o.$slots["footer-text"]?(e.openBlock(),e.createElementBlock("p",K,[e.renderSlot(o.$slots,"footer-text")])):e.createCommentVNode("v-if",!0),o.showFooterActions?(e.openBlock(),e.createElementBlock("div",M,[o.primaryAction?(e.openBlock(),e.createBlock(r,{key:0,class:"cdx-dialog__footer__primary-action",weight:"primary",action:o.primaryAction.actionType,disabled:o.primaryAction.disabled,onClick:n[1]||(n[1]=l=>o.$emit("primary"))},{default:e.withCtx(()=>[e.createTextVNode(e.toDisplayString(o.primaryAction.label),1)]),_:1},8,["action","disabled"])):e.createCommentVNode("v-if",!0),o.defaultAction?(e.openBlock(),e.createBlock(r,{key:1,class:"cdx-dialog__footer__default-action",disabled:o.defaultAction.disabled,onClick:n[2]||(n[2]=l=>o.$emit("default"))},{default:e.withCtx(()=>[e.createTextVNode(e.toDisplayString(o.defaultAction.label),1)]),_:1},8,["disabled"])):e.createCommentVNode("v-if",!0)])):e.createCommentVNode("v-if",!0)])],2)):e.createCommentVNode("v-if",!0)],16,O),e.createElementVNode("div",{ref:"focusTrapEnd",tabindex:"0",onFocus:n[4]||(n[4]=(...l)=>o.focusFirst&&o.focusFirst(...l))},null,544)],544)):e.createCommentVNode("v-if",!0)]),_:3})],8,["to","disabled"])}const U=P._export_sfc(z,[["render",G]]);module.exports=U;
