"use strict";const e=require("vue"),T=require("./Icon.js"),M=require("./CdxButton.cjs"),$=require("./CdxTab.cjs"),R=require("./useGeneratedId.cjs"),W=require("./useComputedDirection.cjs"),O=require("./useOptionalModelWrapper.js"),S=require("./useIntersectionObserver.cjs"),I=require("./useSlotContents.js"),V=require("./constants.js"),F=require("./_plugin-vue_export-helper.js"),j=e.defineComponent({name:"CdxTabs",components:{CdxButton:M,CdxIcon:T.CdxIcon},props:{active:{type:String,default:null},framed:{type:Boolean,default:!1}},emits:["update:active"],expose:["select","next","prev"],setup(t,{slots:s,emit:c}){const f=e.ref(),l=e.ref(),p=e.ref(),d=e.ref(),v=W(f),i=e.computed(()=>{const o=I.useSlotContents(s.default);if(!o.every(n=>typeof n=="object"&&I.isComponentVNode(n,$.name)))throw new Error("Slot content may only contain CdxTab components");if(o.length===0)throw new Error("Slot content cannot be empty");return o}),b=e.computed(()=>i.value.reduce((o,n)=>{var r;if((r=n.props)!=null&&r.name&&typeof n.props.name=="string"){if(o.get(n.props.name))throw new Error("Tab names must be unique");o.set(n.props.name,{name:n.props.name,id:R(n.props.name),label:n.props.label||n.props.name,disabled:n.props.disabled})}return o},new Map)),a=e.ref(Array.from(b.value.keys())[0]),m=O.useOptionalModelWrapper(a,e.toRef(t,"active"),c,"update:active"),w=e.computed(()=>Array.from(b.value.keys())),g=e.computed(()=>w.value.indexOf(m.value)),y=e.computed(()=>{var o;return(o=b.value.get(m.value))==null?void 0:o.id});e.provide(V.ActiveTabKey,m),e.provide(V.TabsKey,b);const C=e.ref(new Map),E=e.ref(),L=e.ref(),k=S(E,{threshold:.95}),q=S(L,{threshold:.95});function A(o,n){const r=o;r&&(C.value.set(n,r),n===0?E.value=r:n===w.value.length-1&&(L.value=r))}const B=e.computed(()=>({"cdx-tabs--framed":t.framed,"cdx-tabs--quiet":!t.framed}));function N(){var o;(o=C.value.get(g.value))==null||o.focus()}function x(o){if(!l.value||!p.value||!d.value)return 0;const n=v.value==="rtl"?d.value:p.value,r=v.value==="rtl"?p.value:d.value,u=o.offsetLeft,h=u+o.clientWidth,D=l.value.scrollLeft+n.clientWidth,K=l.value.scrollLeft+l.value.clientWidth-r.clientWidth;return u<D?u-D:h>K?h-K:0}function _(o){if(!l.value||!p.value||!d.value)return;const n=o==="next"&&v.value==="ltr"||o==="prev"&&v.value==="rtl"?1:-1;let r=0,u=o==="next"?l.value.firstElementChild:l.value.lastElementChild;for(;u;){const h=o==="next"?u.nextElementSibling:u.previousElementSibling;if(r=x(u),Math.sign(r)===n){h&&Math.abs(r)<.25*l.value.clientWidth&&(r=x(h));break}u=h}l.value.scrollBy({left:r,behavior:"smooth"}),N()}return e.watch(m,()=>{if(y.value===void 0||!l.value||!p.value||!d.value)return;const o=document.getElementById("".concat(y.value,"-label"));o&&l.value.scrollBy({left:x(o),behavior:"smooth"})}),{activeTab:m,activeTabIndex:g,activeTabId:y,currentDirection:v,rootElement:f,tabListElement:l,prevScroller:p,nextScroller:d,rootClasses:B,tabNames:w,tabsData:b,firstLabelVisible:k,lastLabelVisible:q,assignTemplateRefForTabButton:A,scrollTabs:_,focusActiveTab:N,cdxIconPrevious:T.d7,cdxIconNext:T.j6}},methods:{select(t,s){const c=this.tabsData.get(t);c&&!(c!=null&&c.disabled)&&(this.activeTab=t,s&&e.nextTick(()=>{this.focusActiveTab()}))},selectNonDisabled(t,s,c){const f=this.tabsData.get(this.tabNames[t+s]);f&&(f.disabled?this.selectNonDisabled(t+s,s,c):this.select(f.name,c))},next(t){this.selectNonDisabled(this.activeTabIndex,1,t)},prev(t){this.selectNonDisabled(this.activeTabIndex,-1,t)},onLeftArrowKeypress(){this.currentDirection==="rtl"?this.next(!0):this.prev(!0)},onRightArrowKeypress(){this.currentDirection==="rtl"?this.prev(!0):this.next(!0)},onDownArrowKeypress(){var t;this.activeTabId&&((t=document.getElementById(this.activeTabId))==null||t.focus())}}}),P={class:"cdx-tabs__header"},z={ref:"prevScroller",class:"cdx-tabs__prev-scroller"},G={ref:"tabListElement",class:"cdx-tabs__list",role:"tablist"},H=["id","disabled","aria-controls","aria-selected","tabindex","onClick","onKeyup"],J={ref:"nextScroller",class:"cdx-tabs__next-scroller"},Q={class:"cdx-tabs__content"};function U(t,s,c,f,l,p){const d=e.resolveComponent("cdx-icon"),v=e.resolveComponent("cdx-button");return e.openBlock(),e.createElementBlock("div",{ref:"rootElement",class:e.normalizeClass(["cdx-tabs",t.rootClasses])},[e.createElementVNode("div",P,[e.withDirectives(e.createElementVNode("div",z,[e.createVNode(v,{class:"cdx-tabs__scroll-button",weight:"quiet",type:"button",tabindex:"-1","aria-hidden":!0,onMousedown:s[0]||(s[0]=e.withModifiers(()=>{},["prevent"])),onClick:s[1]||(s[1]=i=>t.scrollTabs("prev"))},{default:e.withCtx(()=>[e.createVNode(d,{icon:t.cdxIconPrevious},null,8,["icon"])]),_:1})],512),[[e.vShow,!t.firstLabelVisible]]),e.createElementVNode("div",G,[(e.openBlock(!0),e.createElementBlock(e.Fragment,null,e.renderList(t.tabsData.values(),(i,b)=>(e.openBlock(),e.createElementBlock("button",{id:"".concat(i.id,"-label"),key:b,ref_for:!0,ref:a=>t.assignTemplateRefForTabButton(a,b),disabled:i.disabled?!0:void 0,"aria-controls":i.id,"aria-selected":i.name===t.activeTab,tabindex:i.name===t.activeTab?void 0:-1,class:"cdx-tabs__list__item",role:"tab",onClick:e.withModifiers(a=>t.select(i.name),["prevent"]),onKeyup:e.withKeys(a=>t.select(i.name),["enter"]),onKeydown:[s[2]||(s[2]=e.withKeys(e.withModifiers((...a)=>t.onRightArrowKeypress&&t.onRightArrowKeypress(...a),["prevent"]),["right"])),s[3]||(s[3]=e.withKeys(e.withModifiers((...a)=>t.onDownArrowKeypress&&t.onDownArrowKeypress(...a),["prevent"]),["down"])),s[4]||(s[4]=e.withKeys(e.withModifiers((...a)=>t.onLeftArrowKeypress&&t.onLeftArrowKeypress(...a),["prevent"]),["left"]))]},[e.createElementVNode("span",null,e.toDisplayString(i.label),1)],40,H))),128))],512),e.withDirectives(e.createElementVNode("div",J,[e.createVNode(v,{class:"cdx-tabs__scroll-button",weight:"quiet",type:"button",tabindex:"-1","aria-hidden":!0,onMousedown:s[5]||(s[5]=e.withModifiers(()=>{},["prevent"])),onClick:s[6]||(s[6]=i=>t.scrollTabs("next"))},{default:e.withCtx(()=>[e.createVNode(d,{icon:t.cdxIconNext},null,8,["icon"])]),_:1})],512),[[e.vShow,!t.lastLabelVisible]])]),e.createElementVNode("div",Q,[e.renderSlot(t.$slots,"default")])],2)}const X=F._export_sfc(j,[["render",U]]);module.exports=X;
