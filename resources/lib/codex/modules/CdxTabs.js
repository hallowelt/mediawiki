"use strict";const e=require("vue"),m=require("./Icon.js"),M=require("./CdxButton.js"),A=require("./CdxTab.js"),$=require("./useGeneratedId.js"),R=require("./useModelWrapper.js"),L=require("./useIntersectionObserver.js"),D=require("./useSlotContents.js"),K=require("./constants.js"),W=require("./_plugin-vue_export-helper.js");require("./useIconOnlyButton.js");require("./useWarnOnce.js");const O=e.defineComponent({name:"CdxTabs",components:{CdxButton:M,CdxIcon:m.CdxIcon},props:{active:{type:String,required:!0},framed:{type:Boolean,default:!1}},emits:["update:active"],expose:["select","next","prev"],setup(t,{slots:o,emit:c}){const b=e.ref(),i=e.ref(),p=e.ref(),d=e.ref(),v=m.useComputedDirection(b),a=e.computed(()=>{const s=D.useSlotContents(o.default);if(!s.every(n=>typeof n=="object"&&D.isComponentVNode(n,A.name)))throw new Error("Slot content may only contain CdxTab components");if(s.length===0)throw new Error("Slot content cannot be empty");return s}),f=e.computed(()=>a.value.reduce((s,n)=>{var l;if((l=n.props)!=null&&l.name&&typeof n.props.name=="string"){if(s.get(n.props.name))throw new Error("Tab names must be unique");s.set(n.props.name,{name:n.props.name,id:$.useGeneratedId(n.props.name),label:n.props.label||n.props.name,disabled:n.props.disabled})}return s},new Map)),r=R.useModelWrapper(e.toRef(t,"active"),c,"update:active"),w=e.computed(()=>Array.from(f.value.keys())),T=e.computed(()=>w.value.indexOf(r.value)),y=e.computed(()=>{var s;return(s=f.value.get(r.value))==null?void 0:s.id});e.provide(K.ActiveTabKey,r),e.provide(K.TabsKey,f);const g=e.ref(new Map),C=e.ref(),E=e.ref(),S=L.useIntersectionObserver(C,{threshold:.95}),q=L.useIntersectionObserver(E,{threshold:.95});function V(s,n){const l=s;l&&(g.value.set(n,l),n===0?C.value=l:n===w.value.length-1&&(E.value=l))}const B=e.computed(()=>({"cdx-tabs--framed":t.framed,"cdx-tabs--quiet":!t.framed}));function _(){var s;(s=g.value.get(T.value))==null||s.focus()}function x(s){if(!i.value||!p.value||!d.value)return 0;const n=v.value==="rtl"?d.value:p.value,l=v.value==="rtl"?p.value:d.value,u=s.offsetLeft,h=u+s.clientWidth,I=i.value.scrollLeft+n.clientWidth,N=i.value.scrollLeft+i.value.clientWidth-l.clientWidth;return u<I?u-I:h>N?h-N:0}function k(s){if(!i.value||!p.value||!d.value)return;const n=s==="next"&&v.value==="ltr"||s==="prev"&&v.value==="rtl"?1:-1;let l=0,u=s==="next"?i.value.firstElementChild:i.value.lastElementChild;for(;u;){const h=s==="next"?u.nextElementSibling:u.previousElementSibling;if(l=x(u),Math.sign(l)===n){h&&Math.abs(l)<.25*i.value.clientWidth&&(l=x(h));break}u=h}i.value.scrollBy({left:l,behavior:"smooth"}),_()}return e.watch(r,()=>{if(y.value===void 0||!i.value||!p.value||!d.value)return;const s=document.getElementById("".concat(y.value,"-label"));s&&i.value.scrollBy({left:x(s),behavior:"smooth"})}),{activeTab:r,activeTabIndex:T,activeTabId:y,currentDirection:v,rootElement:b,tabListElement:i,prevScroller:p,nextScroller:d,rootClasses:B,tabNames:w,tabsData:f,firstLabelVisible:S,lastLabelVisible:q,assignTemplateRefForTabButton:V,scrollTabs:k,focusActiveTab:_,cdxIconPrevious:m.n7,cdxIconNext:m.Y6}},methods:{select(t,o){const c=this.tabsData.get(t);c&&!(c!=null&&c.disabled)&&(this.activeTab=t,o&&e.nextTick(()=>{this.focusActiveTab()}))},selectNonDisabled(t,o,c){const b=this.tabsData.get(this.tabNames[t+o]);b&&(b.disabled?this.selectNonDisabled(t+o,o,c):this.select(b.name,c))},next(t){this.selectNonDisabled(this.activeTabIndex,1,t)},prev(t){this.selectNonDisabled(this.activeTabIndex,-1,t)},onLeftArrowKeypress(){this.currentDirection==="rtl"?this.next(!0):this.prev(!0)},onRightArrowKeypress(){this.currentDirection==="rtl"?this.prev(!0):this.next(!0)},onDownArrowKeypress(){var t;this.activeTabId&&((t=document.getElementById(this.activeTabId))==null||t.focus())}}});const F={class:"cdx-tabs__header"},G={ref:"prevScroller",class:"cdx-tabs__prev-scroller"},P={ref:"tabListElement",class:"cdx-tabs__list",role:"tablist"},j=["id","disabled","aria-controls","aria-selected","tabindex","onClick","onKeyup"],z={ref:"nextScroller",class:"cdx-tabs__next-scroller"},H={class:"cdx-tabs__content"};function Y(t,o,c,b,i,p){const d=e.resolveComponent("cdx-icon"),v=e.resolveComponent("cdx-button");return e.openBlock(),e.createElementBlock("div",{ref:"rootElement",class:e.normalizeClass(["cdx-tabs",t.rootClasses])},[e.createElementVNode("div",F,[e.withDirectives(e.createElementVNode("div",G,[e.createVNode(v,{class:"cdx-tabs__scroll-button",weight:"quiet",type:"button",tabindex:"-1","aria-hidden":!0,onMousedown:o[0]||(o[0]=e.withModifiers(()=>{},["prevent"])),onClick:o[1]||(o[1]=a=>t.scrollTabs("prev"))},{default:e.withCtx(()=>[e.createVNode(d,{icon:t.cdxIconPrevious},null,8,["icon"])]),_:1})],512),[[e.vShow,!t.firstLabelVisible]]),e.createElementVNode("div",P,[(e.openBlock(!0),e.createElementBlock(e.Fragment,null,e.renderList(t.tabsData.values(),(a,f)=>(e.openBlock(),e.createElementBlock("button",{id:"".concat(a.id,"-label"),key:f,ref_for:!0,ref:r=>t.assignTemplateRefForTabButton(r,f),disabled:a.disabled?!0:void 0,"aria-controls":a.id,"aria-selected":a.name===t.activeTab,tabindex:a.name===t.activeTab?void 0:-1,class:"cdx-tabs__list__item",role:"tab",onClick:e.withModifiers(r=>t.select(a.name),["prevent"]),onKeyup:e.withKeys(r=>t.select(a.name),["enter"]),onKeydown:[o[2]||(o[2]=e.withKeys(e.withModifiers((...r)=>t.onRightArrowKeypress&&t.onRightArrowKeypress(...r),["prevent"]),["right"])),o[3]||(o[3]=e.withKeys(e.withModifiers((...r)=>t.onDownArrowKeypress&&t.onDownArrowKeypress(...r),["prevent"]),["down"])),o[4]||(o[4]=e.withKeys(e.withModifiers((...r)=>t.onLeftArrowKeypress&&t.onLeftArrowKeypress(...r),["prevent"]),["left"]))]},[e.createElementVNode("span",null,e.toDisplayString(a.label),1)],40,j))),128))],512),e.withDirectives(e.createElementVNode("div",z,[e.createVNode(v,{class:"cdx-tabs__scroll-button",weight:"quiet",type:"button",tabindex:"-1","aria-hidden":!0,onMousedown:o[5]||(o[5]=e.withModifiers(()=>{},["prevent"])),onClick:o[6]||(o[6]=a=>t.scrollTabs("next"))},{default:e.withCtx(()=>[e.createVNode(d,{icon:t.cdxIconNext},null,8,["icon"])]),_:1})],512),[[e.vShow,!t.lastLabelVisible]])]),e.createElementVNode("div",H,[e.createCommentVNode(" @slot One or more Tab components must be provided here "),e.renderSlot(t.$slots,"default")])],2)}const J=W._export_sfc(O,[["render",Y]]);module.exports=J;
