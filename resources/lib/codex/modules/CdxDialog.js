"use strict";var B=(o,t,a)=>new Promise((s,d)=>{var i=l=>{try{c(a.next(l))}catch(f){d(f)}},u=l=>{try{c(a.throw(l))}catch(f){d(f)}},c=l=>l.done?s(l.value):Promise.resolve(l.value).then(i,u);c((a=a.apply(o,t)).next())});const e=require("vue"),q=require("./CdxButton.js"),w=require("./Icon.js"),F=require("./useGeneratedId.js"),H=require("./_plugin-vue_export-helper.js");require("./constants.js");require("./useIconOnlyButton.js");require("./useSlotContents.js");require("./useWarnOnce.js");function L(o){const t=e.ref({width:void 0,height:void 0});if(typeof window!="object"||!("ResizeObserver"in window)||!("ResizeObserverEntry"in window))return t;const a=new window.ResizeObserver(d=>{const i=d[0];i&&(t.value={width:i.borderBoxSize[0].inlineSize,height:i.borderBoxSize[0].blockSize})});let s=!1;return e.onMounted(()=>{s=!0,o.value&&a.observe(o.value)}),e.onUnmounted(()=>{s=!1,a.disconnect()}),e.watch(o,d=>{s&&(a.disconnect(),t.value={width:void 0,height:void 0},d&&a.observe(d))}),t}const O=e.defineComponent({name:"CdxDialog",components:{CdxButton:q,CdxIcon:w.CdxIcon},inheritAttrs:!1,props:{open:{type:Boolean,default:!1},title:{type:String,required:!0},subtitle:{type:String,required:!1,default:null},hideTitle:{type:Boolean,default:!1},closeButtonLabel:{type:String,default:""},primaryAction:{type:Object,default:null},defaultAction:{type:Object,default:null},stackedActions:{type:Boolean,default:!1},target:{type:String,default:null},renderInPlace:{type:Boolean,default:!1}},emits:["update:open","primary","default"],setup(o,{emit:t}){const a=F.useGeneratedId("dialog-label"),s=e.ref(),d=e.ref(),i=e.ref(),u=e.ref(),c=e.ref(),l=e.ref(),f=e.computed(()=>!o.hideTitle||!!o.closeButtonLabel),A=e.computed(()=>!!o.primaryAction||!!o.defaultAction),N=L(i),E=e.computed(()=>{var n;return(n=N.value.height)!=null?n:0}),v=e.ref(!1),V=e.computed(()=>({"cdx-dialog--vertical-actions":o.stackedActions,"cdx-dialog--horizontal-actions":!o.stackedActions,"cdx-dialog--dividers":v.value})),$=e.inject("CdxTeleportTarget",void 0),T=e.computed(()=>{var n,r;return(r=(n=o.target)!=null?n:$)!=null?r:"body"}),h=e.ref(0);function S(){t("update:open",!1)}function _(){m(d.value)}function D(){m(d.value,!0)}function m(n,r=!1){let g=Array.from(n.querySelectorAll('\n					input, select, textarea, button, object, a, area,\n					[contenteditable], [tabindex]:not([tabindex^="-"])\n				'));r&&(g=g.reverse());for(const C of g)if(C.focus(),document.activeElement===C)return!0;return!1}let p=[],b=[];function I(){let n=s.value;for(;n.parentElement&&n.nodeName!=="BODY";){for(const r of Array.from(n.parentElement.children))r===n||r.nodeName==="SCRIPT"||(r.hasAttribute("aria-hidden")||(r.setAttribute("aria-hidden","true"),p.push(r)),r.hasAttribute("inert")||(r.setAttribute("inert",""),b.push(r)));n=n.parentElement}}function z(){for(const n of p)n.removeAttribute("aria-hidden");for(const n of b)n.removeAttribute("inert");p=[],b=[]}function y(){return B(this,null,function*(){var n;yield e.nextTick(),h.value=window.innerWidth-document.documentElement.clientWidth,document.documentElement.style.setProperty("margin-right","".concat(h.value,"px")),document.body.classList.add("cdx-dialog-open"),I(),m(i.value)||(n=u.value)==null||n.focus()})}function k(){document.body.classList.remove("cdx-dialog-open"),document.documentElement.style.removeProperty("margin-right"),z()}return e.onMounted(()=>{o.open&&y()}),e.onUnmounted(()=>{o.open&&k()}),e.watch(e.toRef(o,"open"),n=>{n?y():k()}),e.watch(E,()=>{i.value&&(v.value=i.value.clientHeight<i.value.scrollHeight)}),{close:S,cdxIconClose:w.j4,labelId:a,rootClasses:V,backdrop:s,dialogElement:d,focusTrapStart:c,focusTrapEnd:l,focusFirst:_,focusLast:D,dialogBody:i,focusHolder:u,showHeader:f,showFooterActions:A,computedTarget:T}}});const x=["aria-label","aria-labelledby"],j={key:0,class:"cdx-dialog__header__title-group"},P=["id"],R={key:0,class:"cdx-dialog__header__subtitle"},M={ref:"focusHolder",class:"cdx-dialog-focus-trap",tabindex:"-1"},W={key:0,class:"cdx-dialog__footer__text"},G={key:1,class:"cdx-dialog__footer__actions"};function K(o,t,a,s,d,i){const u=e.resolveComponent("cdx-icon"),c=e.resolveComponent("cdx-button");return e.openBlock(),e.createBlock(e.Teleport,{to:o.computedTarget,disabled:o.renderInPlace},[e.createVNode(e.Transition,{name:"cdx-dialog-fade",appear:""},{default:e.withCtx(()=>[o.open?(e.openBlock(),e.createElementBlock("div",{key:0,ref:"backdrop",class:"cdx-dialog-backdrop",onClick:t[5]||(t[5]=(...l)=>o.close&&o.close(...l)),onKeyup:t[6]||(t[6]=e.withKeys((...l)=>o.close&&o.close(...l),["escape"]))},[e.createCommentVNode(" Focus trap start "),e.createElementVNode("div",{ref:"focusTrapStart",tabindex:"0",onFocus:t[0]||(t[0]=(...l)=>o.focusLast&&o.focusLast(...l))},null,544),e.createElementVNode("div",e.mergeProps({ref:"dialogElement",class:["cdx-dialog",o.rootClasses],role:"dialog"},o.$attrs,{"aria-label":o.$slots.header||o.hideTitle?o.title:void 0,"aria-labelledby":!o.$slots.header&&!o.hideTitle?o.labelId:void 0,"aria-modal":"true",onClick:t[3]||(t[3]=e.withModifiers(()=>{},["stop"]))}),[o.showHeader||o.$slots.header?(e.openBlock(),e.createElementBlock("header",{key:0,class:e.normalizeClass(["cdx-dialog__header",{"cdx-dialog__header--default":!o.$slots.header}])},[e.createCommentVNode(" @slot Customizable Dialog header "),e.renderSlot(o.$slots,"header",{},()=>[o.hideTitle?e.createCommentVNode("v-if",!0):(e.openBlock(),e.createElementBlock("div",j,[e.createElementVNode("h2",{id:o.labelId,class:"cdx-dialog__header__title"},e.toDisplayString(o.title),9,P),o.subtitle?(e.openBlock(),e.createElementBlock("p",R,e.toDisplayString(o.subtitle),1)):e.createCommentVNode("v-if",!0)])),o.closeButtonLabel?(e.openBlock(),e.createBlock(c,{key:1,class:"cdx-dialog__header__close-button",weight:"quiet",type:"button","aria-label":o.closeButtonLabel,onClick:o.close},{default:e.withCtx(()=>[e.createVNode(u,{icon:o.cdxIconClose,"icon-label":o.closeButtonLabel},null,8,["icon","icon-label"])]),_:1},8,["aria-label","onClick"])):e.createCommentVNode("v-if",!0)])],2)):e.createCommentVNode("v-if",!0),e.createElementVNode("div",M,null,512),e.createElementVNode("div",{ref:"dialogBody",class:e.normalizeClass(["cdx-dialog__body",{"cdx-dialog__body--no-header":!(o.showHeader||o.$slots.header),"cdx-dialog__body--no-footer":!(o.showFooterActions||o.$slots.footer||o.$slots["footer-text"])}])},[e.createCommentVNode(" @slot Dialog content "),e.renderSlot(o.$slots,"default")],2),o.showFooterActions||o.$slots.footer||o.$slots["footer-text"]?(e.openBlock(),e.createElementBlock("footer",{key:1,class:e.normalizeClass(["cdx-dialog__footer",{"cdx-dialog__footer--default":!o.$slots.footer}])},[e.createCommentVNode(" @slot Customizable Dialog footer "),e.renderSlot(o.$slots,"footer",{},()=>[o.$slots["footer-text"]?(e.openBlock(),e.createElementBlock("p",W,[e.createCommentVNode(" @slot Optional footer text "),e.renderSlot(o.$slots,"footer-text")])):e.createCommentVNode("v-if",!0),o.showFooterActions?(e.openBlock(),e.createElementBlock("div",G,[o.primaryAction?(e.openBlock(),e.createBlock(c,{key:0,class:"cdx-dialog__footer__primary-action",weight:"primary",action:o.primaryAction.actionType,disabled:o.primaryAction.disabled,onClick:t[1]||(t[1]=l=>o.$emit("primary"))},{default:e.withCtx(()=>[e.createTextVNode(e.toDisplayString(o.primaryAction.label),1)]),_:1},8,["action","disabled"])):e.createCommentVNode("v-if",!0),o.defaultAction?(e.openBlock(),e.createBlock(c,{key:1,class:"cdx-dialog__footer__default-action",disabled:o.defaultAction.disabled,onClick:t[2]||(t[2]=l=>o.$emit("default"))},{default:e.withCtx(()=>[e.createTextVNode(e.toDisplayString(o.defaultAction.label),1)]),_:1},8,["disabled"])):e.createCommentVNode("v-if",!0)])):e.createCommentVNode("v-if",!0)])],2)):e.createCommentVNode("v-if",!0)],16,x),e.createCommentVNode(" Focus trap end "),e.createElementVNode("div",{ref:"focusTrapEnd",tabindex:"0",onFocus:t[4]||(t[4]=(...l)=>o.focusFirst&&o.focusFirst(...l))},null,544)],544)):e.createCommentVNode("v-if",!0)]),_:3})],8,["to","disabled"])}const U=H._export_sfc(O,[["render",K]]);module.exports=U;
