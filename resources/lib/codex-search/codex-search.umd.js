(function(f,t){typeof exports=="object"&&typeof module!="undefined"?t(exports,require("vue")):typeof define=="function"&&define.amd?define(["exports","vue"],t):(f=typeof globalThis!="undefined"?globalThis:f||self,t(f["codex-search"]={},f.Vue))})(this,function(f,t){"use strict";var Kt=Object.defineProperty,Ot=Object.defineProperties;var zt=Object.getOwnPropertyDescriptors;var W=Object.getOwnPropertySymbols;var ce=Object.prototype.hasOwnProperty,pe=Object.prototype.propertyIsEnumerable;var de=(f,t,C)=>t in f?Kt(f,t,{enumerable:!0,configurable:!0,writable:!0,value:C}):f[t]=C,he=(f,t)=>{for(var C in t||(t={}))ce.call(t,C)&&de(f,C,t[C]);if(W)for(var C of W(t))pe.call(t,C)&&de(f,C,t[C]);return f},me=(f,t)=>Ot(f,zt(t));var G=(f,t)=>{var C={};for(var S in f)ce.call(f,S)&&t.indexOf(S)<0&&(C[S]=f[S]);if(f!=null&&W)for(var S of W(f))t.indexOf(S)<0&&pe.call(f,S)&&(C[S]=f[S]);return C};var ae=(f,t,C)=>new Promise((S,Q)=>{var X=I=>{try{O(C.next(I))}catch(z){Q(z)}},J=I=>{try{O(C.throw(I))}catch(z){Q(z)}},O=I=>I.done?S(I.value):Promise.resolve(I.value).then(X,J);O((C=C.apply(f,t)).next())});const C='<path d="M12.43 14.34A5 5 0 0110 15a5 5 0 113.95-2L17 16.09V3a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2h10a2 2 0 001.45-.63z"/><circle cx="10" cy="10" r="3"/>',S='<path d="M10 0a10 10 0 1010 10A10 10 0 0010 0zm5.66 14.24-1.41 1.41L10 11.41l-4.24 4.25-1.42-1.42L8.59 10 4.34 5.76l1.42-1.42L10 8.59l4.24-4.24 1.41 1.41L11.41 10z"/>',Q='<path d="M19 3H1v14h18zM3 14l3.5-4.5 2.5 3L12.5 8l4.5 6z"/><path d="M19 5H1V3h18zm0 12H1v-2h18z"/>',X='<path d="M12.2 13.6a7 7 0 111.4-1.4l5.4 5.4-1.4 1.4zM3 8a5 5 0 1010 0A5 5 0 003 8z"/>',J=C,O=S,I=Q,z=X;function fe(e,n,o){if(typeof e=="string"||"path"in e)return e;if("shouldFlip"in e)return e.ltr;if("rtl"in e)return o==="rtl"?e.rtl:e.ltr;const s=n in e.langCodeMap?e.langCodeMap[n]:e.default;return typeof s=="string"||"path"in s?s:s.ltr}function ge(e,n){if(typeof e=="string")return!1;if("langCodeMap"in e){const o=n in e.langCodeMap?e.langCodeMap[n]:e.default;if(typeof o=="string")return!1;e=o}if("shouldFlipExceptions"in e&&Array.isArray(e.shouldFlipExceptions)){const o=e.shouldFlipExceptions.indexOf(n);return o===void 0||o===-1}return"shouldFlip"in e?e.shouldFlip:!1}function ye(e){const n=t.ref(null);return t.onMounted(()=>{const o=window.getComputedStyle(e.value).direction;n.value=o==="ltr"||o==="rtl"?o:null}),n}function Ce(e){const n=t.ref("");return t.onMounted(()=>{let o=e.value;for(;o&&o.lang==="";)o=o.parentElement;n.value=o?o.lang:null}),n}const be=t.defineComponent({name:"CdxIcon",props:{icon:{type:[String,Object],required:!0},iconLabel:{type:String,default:""},lang:{type:String,default:null},dir:{type:String,default:null}},emits:["click"],setup(e,{emit:n}){const o=t.ref(),s=ye(o),a=Ce(o),d=t.computed(()=>e.dir||s.value),l=t.computed(()=>e.lang||a.value),c=t.computed(()=>({"cdx-icon--flipped":d.value==="rtl"&&l.value!==null&&ge(e.icon,l.value)})),u=t.computed(()=>fe(e.icon,l.value||"",d.value||"ltr")),i=t.computed(()=>typeof u.value=="string"?u.value:""),h=t.computed(()=>typeof u.value!="string"?u.value.path:"");return{rootElement:o,rootClasses:c,iconSvg:i,iconPath:h,onClick:b=>{n("click",b)}}}}),qt="",D=(e,n)=>{const o=e.__vccOpts||e;for(const[s,a]of n)o[s]=a;return o},Be=["aria-hidden"],Ae={key:0},ke=["innerHTML"],Ee=["d"];function Se(e,n,o,s,a,d){return t.openBlock(),t.createElementBlock("span",{ref:"rootElement",class:t.normalizeClass(["cdx-icon",e.rootClasses]),onClick:n[0]||(n[0]=(...l)=>e.onClick&&e.onClick(...l))},[(t.openBlock(),t.createElementBlock("svg",{xmlns:"http://www.w3.org/2000/svg",width:"20",height:"20",viewBox:"0 0 20 20","aria-hidden":!e.iconLabel},[e.iconLabel?(t.openBlock(),t.createElementBlock("title",Ae,t.toDisplayString(e.iconLabel),1)):t.createCommentVNode("",!0),e.iconSvg?(t.openBlock(),t.createElementBlock("g",{key:1,innerHTML:e.iconSvg},null,8,ke)):(t.openBlock(),t.createElementBlock("path",{key:2,d:e.iconPath},null,8,Ee))],8,Be))],2)}const q=D(be,[["render",Se]]),_e=t.defineComponent({name:"CdxThumbnail",components:{CdxIcon:q},props:{thumbnail:{type:[Object,null],default:null},placeholderIcon:{type:[String,Object],default:I}},setup:e=>{const n=t.ref(!1),o=t.ref({}),s=a=>{const d=a.replace(/([\\"\n])/g,"\\$1"),l=new Image;l.onload=()=>{o.value={backgroundImage:`url("${d}")`},n.value=!0},l.onerror=()=>{n.value=!1},l.src=d};return t.onMounted(()=>{var a;(a=e.thumbnail)!=null&&a.url&&s(e.thumbnail.url)}),{thumbnailStyle:o,thumbnailLoaded:n}}}),Pt="",$e={class:"cdx-thumbnail"},we={key:0,class:"cdx-thumbnail__placeholder"};function De(e,n,o,s,a,d){const l=t.resolveComponent("cdx-icon");return t.openBlock(),t.createElementBlock("span",$e,[e.thumbnailLoaded?t.createCommentVNode("",!0):(t.openBlock(),t.createElementBlock("span",we,[t.createVNode(l,{icon:e.placeholderIcon,class:"cdx-thumbnail__placeholder__icon"},null,8,["icon"])])),t.createVNode(t.Transition,{name:"cdx-thumbnail__image"},{default:t.withCtx(()=>[e.thumbnailLoaded?(t.openBlock(),t.createElementBlock("span",{key:0,style:t.normalizeStyle(e.thumbnailStyle),class:"cdx-thumbnail__image"},null,4)):t.createCommentVNode("",!0)]),_:1})])}const Ie=D(_e,[["render",De]]);function xe(e){return e.replace(/([\\{}()|.?*+\-^$[\]])/g,"\\$1")}const Fe="[\u0300-\u036F\u0483-\u0489\u0591-\u05BD\u05BF\u05C1\u05C2\u05C4\u05C5\u05C7\u0610-\u061A\u064B-\u065F\u0670\u06D6-\u06DC\u06DF-\u06E4\u06E7\u06E8\u06EA-\u06ED\u0711\u0730-\u074A\u07A6-\u07B0\u07EB-\u07F3\u07FD\u0816-\u0819\u081B-\u0823\u0825-\u0827\u0829-\u082D\u0859-\u085B\u08D3-\u08E1\u08E3-\u0903\u093A-\u093C\u093E-\u094F\u0951-\u0957\u0962\u0963\u0981-\u0983\u09BC\u09BE-\u09C4\u09C7\u09C8\u09CB-\u09CD\u09D7\u09E2\u09E3\u09FE\u0A01-\u0A03\u0A3C\u0A3E-\u0A42\u0A47\u0A48\u0A4B-\u0A4D\u0A51\u0A70\u0A71\u0A75\u0A81-\u0A83\u0ABC\u0ABE-\u0AC5\u0AC7-\u0AC9\u0ACB-\u0ACD\u0AE2\u0AE3\u0AFA-\u0AFF\u0B01-\u0B03\u0B3C\u0B3E-\u0B44\u0B47\u0B48\u0B4B-\u0B4D\u0B56\u0B57\u0B62\u0B63\u0B82\u0BBE-\u0BC2\u0BC6-\u0BC8\u0BCA-\u0BCD\u0BD7\u0C00-\u0C04\u0C3E-\u0C44\u0C46-\u0C48\u0C4A-\u0C4D\u0C55\u0C56\u0C62\u0C63\u0C81-\u0C83\u0CBC\u0CBE-\u0CC4\u0CC6-\u0CC8\u0CCA-\u0CCD\u0CD5\u0CD6\u0CE2\u0CE3\u0D00-\u0D03\u0D3B\u0D3C\u0D3E-\u0D44\u0D46-\u0D48\u0D4A-\u0D4D\u0D57\u0D62\u0D63\u0D82\u0D83\u0DCA\u0DCF-\u0DD4\u0DD6\u0DD8-\u0DDF\u0DF2\u0DF3\u0E31\u0E34-\u0E3A\u0E47-\u0E4E\u0EB1\u0EB4-\u0EB9\u0EBB\u0EBC\u0EC8-\u0ECD\u0F18\u0F19\u0F35\u0F37\u0F39\u0F3E\u0F3F\u0F71-\u0F84\u0F86\u0F87\u0F8D-\u0F97\u0F99-\u0FBC\u0FC6\u102B-\u103E\u1056-\u1059\u105E-\u1060\u1062-\u1064\u1067-\u106D\u1071-\u1074\u1082-\u108D\u108F\u109A-\u109D\u135D-\u135F\u1712-\u1714\u1732-\u1734\u1752\u1753\u1772\u1773\u17B4-\u17D3\u17DD\u180B-\u180D\u1885\u1886\u18A9\u1920-\u192B\u1930-\u193B\u1A17-\u1A1B\u1A55-\u1A5E\u1A60-\u1A7C\u1A7F\u1AB0-\u1ABE\u1B00-\u1B04\u1B34-\u1B44\u1B6B-\u1B73\u1B80-\u1B82\u1BA1-\u1BAD\u1BE6-\u1BF3\u1C24-\u1C37\u1CD0-\u1CD2\u1CD4-\u1CE8\u1CED\u1CF2-\u1CF4\u1CF7-\u1CF9\u1DC0-\u1DF9\u1DFB-\u1DFF\u20D0-\u20F0\u2CEF-\u2CF1\u2D7F\u2DE0-\u2DFF\u302A-\u302F\u3099\u309A\uA66F-\uA672\uA674-\uA67D\uA69E\uA69F\uA6F0\uA6F1\uA802\uA806\uA80B\uA823-\uA827\uA880\uA881\uA8B4-\uA8C5\uA8E0-\uA8F1\uA8FF\uA926-\uA92D\uA947-\uA953\uA980-\uA983\uA9B3-\uA9C0\uA9E5\uAA29-\uAA36\uAA43\uAA4C\uAA4D\uAA7B-\uAA7D\uAAB0\uAAB2-\uAAB4\uAAB7\uAAB8\uAABE\uAABF\uAAC1\uAAEB-\uAAEF\uAAF5\uAAF6\uABE3-\uABEA\uABEC\uABED\uFB1E\uFE00-\uFE0F\uFE20-\uFE2F]";function Me(e,n){if(!e)return[n,"",""];const o=xe(e),s=new RegExp(o+Fe+"*","i").exec(n);if(!s||s.index===void 0)return[n,"",""];const a=s.index,d=a+s[0].length,l=n.slice(a,d),c=n.slice(0,a),u=n.slice(d,n.length);return[c,l,u]}const Ve=t.defineComponent({name:"CdxSearchResultTitle",props:{title:{type:String,required:!0},searchQuery:{type:String,default:""}},setup:e=>({titleChunks:t.computed(()=>Me(e.searchQuery,String(e.title)))})}),Qt="",Ne={class:"cdx-search-result-title"},Te={class:"cdx-search-result-title__match"};function ve(e,n,o,s,a,d){return t.openBlock(),t.createElementBlock("span",Ne,[t.createElementVNode("bdi",null,[t.createTextVNode(t.toDisplayString(e.titleChunks[0]),1),t.createElementVNode("span",Te,t.toDisplayString(e.titleChunks[1]),1),t.createTextVNode(t.toDisplayString(e.titleChunks[2]),1)])])}const Le=D(Ve,[["render",ve]]),Re=t.defineComponent({name:"CdxMenuItem",components:{CdxIcon:q,CdxThumbnail:Ie,CdxSearchResultTitle:Le},props:{id:{type:String,required:!0},value:{type:[String,Number],required:!0},disabled:{type:Boolean,default:!1},selected:{type:Boolean,default:!1},active:{type:Boolean,default:!1},highlighted:{type:Boolean,default:!1},label:{type:String,default:""},match:{type:String,default:""},supportingText:{type:String,default:""},url:{type:String,default:""},icon:{type:[String,Object],default:""},showThumbnail:{type:Boolean,default:!1},thumbnail:{type:[Object,null],default:null},description:{type:[String,null],default:""},searchQuery:{type:String,default:""},boldLabel:{type:Boolean,default:!1},hideDescriptionOverflow:{type:Boolean,default:!1},language:{type:Object,default:()=>({})}},emits:["change"],setup:(e,{emit:n})=>{const o=()=>{n("change","highlighted",!0)},s=()=>{n("change","highlighted",!1)},a=h=>{h.button===0&&n("change","active",!0)},d=()=>{n("change","selected",!0)},l=t.computed(()=>e.searchQuery.length>0),c=t.computed(()=>({"cdx-menu-item--selected":e.selected,"cdx-menu-item--active":e.active&&e.highlighted,"cdx-menu-item--highlighted":e.highlighted,"cdx-menu-item--enabled":!e.disabled,"cdx-menu-item--disabled":e.disabled,"cdx-menu-item--highlight-query":l.value,"cdx-menu-item--bold-label":e.boldLabel,"cdx-menu-item--has-description":!!e.description,"cdx-menu-item--hide-description-overflow":e.hideDescriptionOverflow})),u=t.computed(()=>e.url?"a":"span"),i=t.computed(()=>e.label||String(e.value));return{onMouseEnter:o,onMouseLeave:s,onMouseDown:a,onClick:d,highlightQuery:l,rootClasses:c,contentTag:u,title:i}}}),Ut="",Ke=["id","aria-disabled","aria-selected"],Oe={class:"cdx-menu-item__text"},ze=["lang"],qe=["lang"],Pe=["lang"],Qe=["lang"];function Ue(e,n,o,s,a,d){const l=t.resolveComponent("cdx-thumbnail"),c=t.resolveComponent("cdx-icon"),u=t.resolveComponent("cdx-search-result-title");return t.openBlock(),t.createElementBlock("li",{id:e.id,role:"option",class:t.normalizeClass(["cdx-menu-item",e.rootClasses]),"aria-disabled":e.disabled,"aria-selected":e.selected,onMouseenter:n[0]||(n[0]=(...i)=>e.onMouseEnter&&e.onMouseEnter(...i)),onMouseleave:n[1]||(n[1]=(...i)=>e.onMouseLeave&&e.onMouseLeave(...i)),onMousedown:n[2]||(n[2]=t.withModifiers((...i)=>e.onMouseDown&&e.onMouseDown(...i),["prevent"])),onClick:n[3]||(n[3]=(...i)=>e.onClick&&e.onClick(...i))},[t.renderSlot(e.$slots,"default",{},()=>[(t.openBlock(),t.createBlock(t.resolveDynamicComponent(e.contentTag),{href:e.url?e.url:void 0,class:"cdx-menu-item__content"},{default:t.withCtx(()=>{var i,h,k,b,A,_;return[e.showThumbnail?(t.openBlock(),t.createBlock(l,{key:0,thumbnail:e.thumbnail,class:"cdx-menu-item__thumbnail"},null,8,["thumbnail"])):e.icon?(t.openBlock(),t.createBlock(c,{key:1,icon:e.icon,class:"cdx-menu-item__icon"},null,8,["icon"])):t.createCommentVNode("",!0),t.createElementVNode("span",Oe,[e.highlightQuery?(t.openBlock(),t.createBlock(u,{key:0,title:e.title,"search-query":e.searchQuery,lang:(i=e.language)==null?void 0:i.label},null,8,["title","search-query","lang"])):(t.openBlock(),t.createElementBlock("span",{key:1,class:"cdx-menu-item__text__label",lang:(h=e.language)==null?void 0:h.label},[t.createElementVNode("bdi",null,t.toDisplayString(e.title),1)],8,ze)),e.match?(t.openBlock(),t.createElementBlock(t.Fragment,{key:2},[t.createTextVNode(t.toDisplayString(" ")+" "),e.highlightQuery?(t.openBlock(),t.createBlock(u,{key:0,title:e.match,"search-query":e.searchQuery,lang:(k=e.language)==null?void 0:k.match},null,8,["title","search-query","lang"])):(t.openBlock(),t.createElementBlock("span",{key:1,class:"cdx-menu-item__text__match",lang:(b=e.language)==null?void 0:b.match},[t.createElementVNode("bdi",null,t.toDisplayString(e.match),1)],8,qe))],64)):t.createCommentVNode("",!0),e.supportingText?(t.openBlock(),t.createElementBlock(t.Fragment,{key:3},[t.createTextVNode(t.toDisplayString(" ")+" "),t.createElementVNode("span",{class:"cdx-menu-item__text__supporting-text",lang:(A=e.language)==null?void 0:A.supportingText},[t.createElementVNode("bdi",null,t.toDisplayString(e.supportingText),1)],8,Pe)],64)):t.createCommentVNode("",!0),e.description?(t.openBlock(),t.createElementBlock("span",{key:4,class:"cdx-menu-item__text__description",lang:(_=e.language)==null?void 0:_.description},[t.createElementVNode("bdi",null,t.toDisplayString(e.description),1)],8,Qe)):t.createCommentVNode("",!0)])]}),_:1},8,["href"]))])],42,Ke)}const He=D(Re,[["render",Ue]]),je=t.defineComponent({name:"CdxProgressBar",props:{inline:{type:Boolean,default:!1},disabled:{type:Boolean,default:!1}},setup(e){return{rootClasses:t.computed(()=>({"cdx-progress-bar--block":!e.inline,"cdx-progress-bar--inline":e.inline,"cdx-progress-bar--enabled":!e.disabled,"cdx-progress-bar--disabled":e.disabled}))}}}),Ht="",We=["aria-disabled"],Ge=[t.createElementVNode("div",{class:"cdx-progress-bar__bar"},null,-1)];function Xe(e,n,o,s,a,d){return t.openBlock(),t.createElementBlock("div",{class:t.normalizeClass(["cdx-progress-bar",e.rootClasses]),role:"progressbar","aria-disabled":e.disabled,"aria-valuemin":"0","aria-valuemax":"100"},Ge,10,We)}const Je=D(je,[["render",Xe]]),Y="cdx",Ye=["default","progressive","destructive"],Ze=["normal","primary","quiet"],et=["text","search"],se=["default","error"],tt=120,nt=500,v="cdx-menu-footer-item";let Z=0;function ue(e){const n=t.getCurrentInstance(),o=(n==null?void 0:n.props.id)||(n==null?void 0:n.attrs.id);return e?`${Y}-${e}-${Z++}`:o?`${Y}-${o}-${Z++}`:`${Y}-${Z++}`}function ot(e,n){const o=t.ref(!1);let s=!1;if(typeof window!="object"||!("IntersectionObserver"in window&&"IntersectionObserverEntry"in window&&"intersectionRatio"in window.IntersectionObserverEntry.prototype))return o;const a=new window.IntersectionObserver(d=>{const l=d[0];l&&(o.value=l.isIntersecting)},n);return t.onMounted(()=>{s=!0,e.value&&a.observe(e.value)}),t.onUnmounted(()=>{s=!1,a.disconnect()}),t.watch(e,d=>{!s||(a.disconnect(),o.value=!1,d&&a.observe(d))}),o}function U(e,n=t.computed(()=>({}))){const o=t.computed(()=>{const d=G(n.value,[]);return e.class&&e.class.split(" ").forEach(c=>{d[c]=!0}),d}),s=t.computed(()=>{if("style"in e)return e.style}),a=t.computed(()=>{const u=e,{class:d,style:l}=u;return G(u,["class","style"])});return{rootClasses:o,rootStyle:s,otherAttrs:a}}const lt=t.defineComponent({name:"CdxMenu",components:{CdxMenuItem:He,CdxProgressBar:Je},inheritAttrs:!1,props:{menuItems:{type:Array,required:!0},footer:{type:Object,default:null},selected:{type:[String,Number,null],required:!0},expanded:{type:Boolean,required:!0},showPending:{type:Boolean,default:!1},visibleItemLimit:{type:Number,default:null},showThumbnail:{type:Boolean,default:!1},boldLabel:{type:Boolean,default:!1},hideDescriptionOverflow:{type:Boolean,default:!1},searchQuery:{type:String,default:""},showNoResultsSlot:{type:Boolean,default:null}},emits:["update:selected","update:expanded","menu-item-click","menu-item-keyboard-navigation","load-more"],expose:["clearActive","getHighlightedMenuItem","delegateKeyNavigation"],setup(e,{emit:n,slots:o,attrs:s}){const a=t.computed(()=>(e.footer&&e.menuItems?[...e.menuItems,e.footer]:e.menuItems).map(m=>me(he({},m),{id:ue("menu-item")}))),d=t.computed(()=>o["no-results"]?e.showNoResultsSlot!==null?e.showNoResultsSlot:a.value.length===0:!1),l=t.ref(null),c=t.ref(null);function u(){return a.value.find(r=>r.value===e.selected)}function i(r,m){var y;if(!(m&&m.disabled))switch(r){case"selected":n("update:selected",(y=m==null?void 0:m.value)!=null?y:null),n("update:expanded",!1),c.value=null;break;case"highlighted":l.value=m||null;break;case"active":c.value=m||null;break}}const h=t.computed(()=>{if(l.value!==null)return a.value.findIndex(r=>r.value===l.value.value)});function k(r){!r||(i("highlighted",r),n("menu-item-keyboard-navigation",r))}function b(r){var B;const m=K=>{for(let N=K-1;N>=0;N--)if(!a.value[N].disabled)return a.value[N]};r=r||a.value.length;const y=(B=m(r))!=null?B:m(a.value.length);k(y)}function A(r){const m=B=>a.value.find((K,N)=>!K.disabled&&N>B);r=r!=null?r:-1;const y=m(r)||m(-1);k(y)}function _(r,m=!0){function y(){n("update:expanded",!0),i("highlighted",u())}function B(){m&&(r.preventDefault(),r.stopPropagation())}switch(r.key){case"Enter":case" ":return B(),e.expanded?(l.value&&n("update:selected",l.value.value),n("update:expanded",!1)):y(),!0;case"Tab":return e.expanded&&(l.value&&n("update:selected",l.value.value),n("update:expanded",!1)),!0;case"ArrowUp":return B(),e.expanded?(l.value===null&&i("highlighted",u()),b(h.value)):y(),M(),!0;case"ArrowDown":return B(),e.expanded?(l.value===null&&i("highlighted",u()),A(h.value)):y(),M(),!0;case"Home":return B(),e.expanded?(l.value===null&&i("highlighted",u()),A()):y(),M(),!0;case"End":return B(),e.expanded?(l.value===null&&i("highlighted",u()),b()):y(),M(),!0;case"Escape":return B(),n("update:expanded",!1),!0;default:return!1}}function $(){i("active")}const g=[],F=t.ref(void 0),te=ot(F,{threshold:.8});t.watch(te,r=>{r&&n("load-more")});function w(r,m){if(r){g[m]=r.$el;const y=e.visibleItemLimit;if(!y||e.menuItems.length<y)return;const B=Math.min(y,Math.max(2,Math.floor(.2*e.menuItems.length)));m===e.menuItems.length-B&&(F.value=r.$el)}}function M(){if(!e.visibleItemLimit||e.visibleItemLimit>e.menuItems.length||h.value===void 0)return;const r=h.value>=0?h.value:0;g[r].scrollIntoView({behavior:"smooth",block:"nearest"})}const V=t.ref(null),L=t.ref(null);function H(){if(L.value=null,!e.visibleItemLimit||g.length<=e.visibleItemLimit){V.value=null;return}const r=g[0],m=g[e.visibleItemLimit];if(V.value=ne(r,m),e.footer){const y=g[g.length-1];L.value=y.scrollHeight}}function ne(r,m){const y=r.getBoundingClientRect().top;return m.getBoundingClientRect().top-y+2}t.onMounted(()=>{document.addEventListener("mouseup",$)}),t.onUnmounted(()=>{document.removeEventListener("mouseup",$)}),t.watch(t.toRef(e,"expanded"),r=>ae(this,null,function*(){const m=u();!r&&l.value&&m===void 0&&i("highlighted"),r&&m!==void 0&&i("highlighted",m),r&&(yield t.nextTick(),H(),yield t.nextTick(),M())})),t.watch(t.toRef(e,"menuItems"),r=>ae(this,null,function*(){r.length<g.length&&(g.length=r.length),e.expanded&&(yield t.nextTick(),H(),yield t.nextTick(),M())}),{deep:!0});const oe=t.computed(()=>({"max-height":V.value?`${V.value}px`:void 0,"overflow-y":V.value?"scroll":void 0,"margin-bottom":L.value?`${L.value}px`:void 0})),le=t.computed(()=>({"cdx-menu--has-footer":!!e.footer,"cdx-menu--has-sticky-footer":!!e.footer&&!!V.value})),{rootClasses:R,rootStyle:x,otherAttrs:j}=U(s,le);return{listBoxStyle:oe,rootClasses:R,rootStyle:x,otherAttrs:j,assignTemplateRef:w,computedMenuItems:a,computedShowNoResultsSlot:d,highlightedMenuItem:l,activeMenuItem:c,handleMenuItemChange:i,handleKeyNavigation:_}},methods:{getHighlightedMenuItem(){return this.highlightedMenuItem},clearActive(){this.handleMenuItemChange("active")},delegateKeyNavigation(e,n=!0){return this.handleKeyNavigation(e,n)}}}),Wt="",at={key:0,class:"cdx-menu__pending cdx-menu-item"},st={key:1,class:"cdx-menu__no-results cdx-menu-item"};function ut(e,n,o,s,a,d){const l=t.resolveComponent("cdx-menu-item"),c=t.resolveComponent("cdx-progress-bar");return t.withDirectives((t.openBlock(),t.createElementBlock("div",{class:t.normalizeClass(["cdx-menu",e.rootClasses]),style:t.normalizeStyle(e.rootStyle)},[t.createElementVNode("ul",t.mergeProps({class:"cdx-menu__listbox",role:"listbox","aria-multiselectable":"false",style:e.listBoxStyle},e.otherAttrs),[e.showPending&&e.computedMenuItems.length===0&&e.$slots.pending?(t.openBlock(),t.createElementBlock("li",at,[t.renderSlot(e.$slots,"pending")])):t.createCommentVNode("",!0),e.computedShowNoResultsSlot?(t.openBlock(),t.createElementBlock("li",st,[t.renderSlot(e.$slots,"no-results")])):t.createCommentVNode("",!0),(t.openBlock(!0),t.createElementBlock(t.Fragment,null,t.renderList(e.computedMenuItems,(u,i)=>{var h,k;return t.openBlock(),t.createBlock(l,t.mergeProps({key:u.value,ref_for:!0,ref:b=>e.assignTemplateRef(b,i)},u,{selected:u.value===e.selected,active:u.value===((h=e.activeMenuItem)==null?void 0:h.value),highlighted:u.value===((k=e.highlightedMenuItem)==null?void 0:k.value),"show-thumbnail":e.showThumbnail,"bold-label":e.boldLabel,"hide-description-overflow":e.hideDescriptionOverflow,"search-query":e.searchQuery,onChange:(b,A)=>e.handleMenuItemChange(b,A&&u),onClick:b=>e.$emit("menu-item-click",u)}),{default:t.withCtx(()=>{var b,A;return[t.renderSlot(e.$slots,"default",{menuItem:u,active:u.value===((b=e.activeMenuItem)==null?void 0:b.value)&&u.value===((A=e.highlightedMenuItem)==null?void 0:A.value)})]}),_:2},1040,["selected","active","highlighted","show-thumbnail","bold-label","hide-description-overflow","search-query","onChange","onClick"])}),128)),e.showPending?(t.openBlock(),t.createBlock(c,{key:2,class:"cdx-menu__progress-bar",inline:!0})):t.createCommentVNode("",!0)],16)],6)),[[t.vShow,e.expanded]])}const rt=D(lt,[["render",ut]]);function P(e){return n=>typeof n=="string"&&e.indexOf(n)!==-1}const it=P(Ze),dt=P(Ye),ct=e=>{!e["aria-label"]&&!e["aria-hidden"]&&t.warn(`icon-only buttons require one of the following attribute: aria-label or aria-hidden.
		See documentation on https://doc.wikimedia.org/codex/latest/components/button.html#default-icon-only`)};function ee(e){const n=[];for(const o of e)typeof o=="string"&&o.trim()!==""?n.push(o):Array.isArray(o)?n.push(...ee(o)):typeof o=="object"&&o&&(typeof o.type=="string"||typeof o.type=="object"?n.push(o):o.type!==t.Comment&&(typeof o.children=="string"&&o.children.trim()!==""?n.push(o.children):Array.isArray(o.children)&&n.push(...ee(o.children))));return n}const pt=(e,n)=>{if(!e)return!1;const o=ee(e);if(o.length!==1)return!1;const s=o[0],a=typeof s=="object"&&typeof s.type=="object"&&"name"in s.type&&s.type.name===q.name,d=typeof s=="object"&&s.type==="svg";return a||d?(ct(n),!0):!1},ht=t.defineComponent({name:"CdxButton",props:{action:{type:String,default:"default",validator:dt},type:{type:String,default:"normal",validator:it}},emits:["click"],setup(e,{emit:n,slots:o,attrs:s}){const a=t.ref(!1);return{rootClasses:t.computed(()=>{var u;return{[`cdx-button--action-${e.action}`]:!0,[`cdx-button--type-${e.type}`]:!0,"cdx-button--framed":e.type!=="quiet","cdx-button--icon-only":pt((u=o.default)==null?void 0:u.call(o),s),"cdx-button--is-active":a.value}}),onClick:u=>{n("click",u)},setActive:u=>{a.value=u}}}}),Gt="";function mt(e,n,o,s,a,d){return t.openBlock(),t.createElementBlock("button",{class:t.normalizeClass(["cdx-button",e.rootClasses]),onClick:n[0]||(n[0]=(...l)=>e.onClick&&e.onClick(...l)),onKeydown:n[1]||(n[1]=t.withKeys(l=>e.setActive(!0),["space","enter"])),onKeyup:n[2]||(n[2]=t.withKeys(l=>e.setActive(!1),["space","enter"]))},[t.renderSlot(e.$slots,"default")],34)}const ft=D(ht,[["render",mt]]);function re(e,n,o){return t.computed({get:()=>e.value,set:s=>n(o||"update:modelValue",s)})}const gt=P(et),yt=P(se),Ct=t.defineComponent({name:"CdxTextInput",components:{CdxIcon:q},inheritAttrs:!1,expose:["focus"],props:{modelValue:{type:[String,Number],default:""},inputType:{type:String,default:"text",validator:gt},status:{type:String,default:"default",validator:yt},disabled:{type:Boolean,default:!1},startIcon:{type:[String,Object],default:void 0},endIcon:{type:[String,Object],default:void 0},clearable:{type:Boolean,default:!1}},emits:["update:modelValue","input","change","keydown","focus","blur"],setup(e,{emit:n,attrs:o}){const s=re(t.toRef(e,"modelValue"),n),a=t.computed(()=>e.clearable&&!!s.value&&!e.disabled),d=t.computed(()=>({"cdx-text-input--has-start-icon":!!e.startIcon,"cdx-text-input--has-end-icon":!!e.endIcon,"cdx-text-input--clearable":a.value})),{rootClasses:l,rootStyle:c,otherAttrs:u}=U(o,d),i=t.computed(()=>({"cdx-text-input__input--has-value":!!s.value,[`cdx-text-input__input--status-${e.status}`]:!0}));return{wrappedModel:s,isClearable:a,rootClasses:l,rootStyle:c,otherAttrs:u,inputClasses:i,onClear:()=>{s.value=""},onInput:g=>{n("input",g)},onChange:g=>{n("change",g)},onKeydown:g=>{(g.key==="Home"||g.key==="End")&&!g.ctrlKey&&!g.metaKey||n("keydown",g)},onFocus:g=>{n("focus",g)},onBlur:g=>{n("blur",g)},cdxIconClear:O}},methods:{focus(){this.$refs.input.focus()}}}),Xt="",bt=["type","disabled"];function Bt(e,n,o,s,a,d){const l=t.resolveComponent("cdx-icon");return t.openBlock(),t.createElementBlock("div",{class:t.normalizeClass(["cdx-text-input",e.rootClasses]),style:t.normalizeStyle(e.rootStyle)},[t.withDirectives(t.createElementVNode("input",t.mergeProps({ref:"input","onUpdate:modelValue":n[0]||(n[0]=c=>e.wrappedModel=c),class:["cdx-text-input__input",e.inputClasses]},e.otherAttrs,{type:e.inputType,disabled:e.disabled,onInput:n[1]||(n[1]=(...c)=>e.onInput&&e.onInput(...c)),onChange:n[2]||(n[2]=(...c)=>e.onChange&&e.onChange(...c)),onFocus:n[3]||(n[3]=(...c)=>e.onFocus&&e.onFocus(...c)),onBlur:n[4]||(n[4]=(...c)=>e.onBlur&&e.onBlur(...c)),onKeydown:n[5]||(n[5]=(...c)=>e.onKeydown&&e.onKeydown(...c))}),null,16,bt),[[t.vModelDynamic,e.wrappedModel]]),e.startIcon?(t.openBlock(),t.createBlock(l,{key:0,icon:e.startIcon,class:"cdx-text-input__icon cdx-text-input__start-icon"},null,8,["icon"])):t.createCommentVNode("",!0),e.endIcon?(t.openBlock(),t.createBlock(l,{key:1,icon:e.endIcon,class:"cdx-text-input__icon cdx-text-input__end-icon"},null,8,["icon"])):t.createCommentVNode("",!0),e.isClearable?(t.openBlock(),t.createBlock(l,{key:2,icon:e.cdxIconClear,class:"cdx-text-input__icon cdx-text-input__clear-icon",onMousedown:n[6]||(n[6]=t.withModifiers(()=>{},["prevent"])),onClick:e.onClear},null,8,["icon","onClick"])):t.createCommentVNode("",!0)],6)}const At=D(Ct,[["render",Bt]]),kt=P(se),Et=t.defineComponent({name:"CdxSearchInput",components:{CdxButton:ft,CdxTextInput:At},inheritAttrs:!1,props:{modelValue:{type:[String,Number],default:""},buttonLabel:{type:String,default:""},status:{type:String,default:"default",validator:kt}},emits:["update:modelValue","submit-click"],setup(e,{emit:n,attrs:o}){const s=re(t.toRef(e,"modelValue"),n),a=t.computed(()=>({"cdx-search-input--has-end-button":!!e.buttonLabel})),{rootClasses:d,rootStyle:l,otherAttrs:c}=U(o,a);return{wrappedModel:s,rootClasses:d,rootStyle:l,otherAttrs:c,handleSubmit:()=>{n("submit-click",s.value)},searchIcon:z}},methods:{focus(){this.$refs.textInput.focus()}}}),Jt="",St={class:"cdx-search-input__input-wrapper"};function _t(e,n,o,s,a,d){const l=t.resolveComponent("cdx-text-input"),c=t.resolveComponent("cdx-button");return t.openBlock(),t.createElementBlock("div",{class:t.normalizeClass(["cdx-search-input",e.rootClasses]),style:t.normalizeStyle(e.rootStyle)},[t.createElementVNode("div",St,[t.createVNode(l,t.mergeProps({ref:"textInput",modelValue:e.wrappedModel,"onUpdate:modelValue":n[0]||(n[0]=u=>e.wrappedModel=u),class:"cdx-search-input__text-input","input-type":"search","start-icon":e.searchIcon,status:e.status},e.otherAttrs,{onKeydown:t.withKeys(e.handleSubmit,["enter"])}),null,16,["modelValue","start-icon","status","onKeydown"]),t.renderSlot(e.$slots,"default")]),e.buttonLabel?(t.openBlock(),t.createBlock(c,{key:0,class:"cdx-search-input__end-button",onClick:e.handleSubmit},{default:t.withCtx(()=>[t.createTextVNode(t.toDisplayString(e.buttonLabel),1)]),_:1},8,["onClick"])):t.createCommentVNode("",!0)],6)}const $t=D(Et,[["render",_t]]),wt=t.defineComponent({name:"CdxTypeaheadSearch",components:{CdxIcon:q,CdxMenu:rt,CdxSearchInput:$t},inheritAttrs:!1,props:{id:{type:String,required:!0},formAction:{type:String,required:!0},searchResultsLabel:{type:String,required:!0},searchResults:{type:Array,required:!0},buttonLabel:{type:String,default:""},initialInputValue:{type:String,default:""},searchFooterUrl:{type:String,default:""},debounceInterval:{type:Number,default:tt},highlightQuery:{type:Boolean,default:!1},showThumbnail:{type:Boolean,default:!1},autoExpandWidth:{type:Boolean,default:!1},visibleItemLimit:{type:Number,default:null}},emits:["input","search-result-click","submit","load-more"],setup(e,{attrs:n,emit:o,slots:s}){const{searchResults:a,searchFooterUrl:d,debounceInterval:l}=t.toRefs(e),c=t.ref(),u=t.ref(),i=ue("typeahead-search-menu"),h=t.ref(!1),k=t.ref(!1),b=t.ref(!1),A=t.ref(!1),_=t.ref(e.initialInputValue),$=t.ref(""),g=t.computed(()=>{var p,E;return(E=(p=u.value)==null?void 0:p.getHighlightedMenuItem())==null?void 0:E.id}),F=t.ref(null),te=t.computed(()=>({"cdx-typeahead-search__menu-message--has-thumbnail":e.showThumbnail})),w=t.computed(()=>e.searchResults.find(p=>p.value===F.value)),M=t.computed(()=>d.value?{value:v,url:d.value}:void 0),V=t.computed(()=>({"cdx-typeahead-search--show-thumbnail":e.showThumbnail,"cdx-typeahead-search--expanded":h.value,"cdx-typeahead-search--auto-expand-width":e.showThumbnail&&e.autoExpandWidth})),{rootClasses:L,rootStyle:H,otherAttrs:ne}=U(n,V);function oe(p){return p}const le=t.computed(()=>({visibleItemLimit:e.visibleItemLimit,showThumbnail:e.showThumbnail,boldLabel:!0,hideDescriptionOverflow:!0}));let R,x;function j(p,E=!1){w.value&&w.value.label!==p&&w.value.value!==p&&(F.value=null),x!==void 0&&(clearTimeout(x),x=void 0),p===""?h.value=!1:(k.value=!0,s["search-results-pending"]&&(x=setTimeout(()=>{A.value&&(h.value=!0),b.value=!0},nt))),R!==void 0&&(clearTimeout(R),R=void 0);const T=()=>{o("input",p)};E?T():R=setTimeout(()=>{T()},l.value)}function r(p){if(p===v){F.value=null,_.value=$.value;return}F.value=p,p!==null&&(_.value=w.value?w.value.label||String(w.value.value):"")}function m(){A.value=!0,($.value||b.value)&&(h.value=!0)}function y(){A.value=!1,h.value=!1}function B(p){const ie=p,{id:E}=ie,T=G(ie,["id"]);if(T.value===v){o("search-result-click",{searchResult:null,index:a.value.length,numberOfResults:a.value.length});return}K(T)}function K(p){const E={searchResult:p,index:a.value.findIndex(T=>T.value===p.value),numberOfResults:a.value.length};o("search-result-click",E)}function N(p){if(p.value===v){_.value=$.value;return}_.value=p.value?p.label||String(p.value):""}function vt(p){var E;h.value=!1,(E=u.value)==null||E.clearActive(),B(p)}function Lt(p){if(w.value)K(w.value),p.stopPropagation(),window.location.assign(w.value.url),p.preventDefault();else{const E={searchResult:null,index:-1,numberOfResults:a.value.length};o("submit",E)}}function Rt(p){if(!u.value||!$.value||p.key===" "&&h.value)return;const E=u.value.getHighlightedMenuItem();switch(p.key){case"Enter":E&&(E.value===v?window.location.assign(d.value):u.value.delegateKeyNavigation(p,!1)),h.value=!1;break;case"Tab":h.value=!1;break;default:u.value.delegateKeyNavigation(p);break}}return t.onMounted(()=>{e.initialInputValue&&j(e.initialInputValue,!0)}),t.watch(t.toRef(e,"searchResults"),()=>{$.value=_.value.trim(),A.value&&k.value&&$.value.length>0&&(h.value=!0),x!==void 0&&(clearTimeout(x),x=void 0),k.value=!1,b.value=!1}),{form:c,menu:u,menuId:i,highlightedId:g,selection:F,menuMessageClass:te,footer:M,asSearchResult:oe,inputValue:_,searchQuery:$,expanded:h,showPending:b,rootClasses:L,rootStyle:H,otherAttrs:ne,menuConfig:le,onUpdateInputValue:j,onUpdateMenuSelection:r,onFocus:m,onBlur:y,onSearchResultClick:B,onSearchResultKeyboardNavigation:N,onSearchFooterClick:vt,onSubmit:Lt,onKeydown:Rt,MenuFooterValue:v,articleIcon:J}},methods:{focus(){this.$refs.searchInput.focus()}}}),Yt="",Dt=["id","action"],It={class:"cdx-typeahead-search__menu-message__text"},xt={class:"cdx-typeahead-search__menu-message__text"},Ft=["href","onClickCapture"],Mt={class:"cdx-typeahead-search__search-footer__text"},Vt={class:"cdx-typeahead-search__search-footer__query"};function Nt(e,n,o,s,a,d){const l=t.resolveComponent("cdx-icon"),c=t.resolveComponent("cdx-menu"),u=t.resolveComponent("cdx-search-input");return t.openBlock(),t.createElementBlock("div",{class:t.normalizeClass(["cdx-typeahead-search",e.rootClasses]),style:t.normalizeStyle(e.rootStyle)},[t.createElementVNode("form",{id:e.id,ref:"form",class:"cdx-typeahead-search__form",action:e.formAction,onSubmit:n[4]||(n[4]=(...i)=>e.onSubmit&&e.onSubmit(...i))},[t.createVNode(u,t.mergeProps({ref:"searchInput",modelValue:e.inputValue,"onUpdate:modelValue":n[3]||(n[3]=i=>e.inputValue=i),"button-label":e.buttonLabel},e.otherAttrs,{class:"cdx-typeahead-search__input",name:"search",role:"combobox",autocomplete:"off","aria-autocomplete":"list","aria-owns":e.menuId,"aria-expanded":e.expanded,"aria-activedescendant":e.highlightedId,"onUpdate:modelValue":e.onUpdateInputValue,onFocus:e.onFocus,onBlur:e.onBlur,onKeydown:e.onKeydown}),{default:t.withCtx(()=>[t.createVNode(c,t.mergeProps({id:e.menuId,ref:"menu",expanded:e.expanded,"onUpdate:expanded":n[0]||(n[0]=i=>e.expanded=i),"show-pending":e.showPending,selected:e.selection,"menu-items":e.searchResults,footer:e.footer,"search-query":e.highlightQuery?e.searchQuery:"","show-no-results-slot":e.searchQuery.length>0&&e.searchResults.length===0&&e.$slots["search-no-results-text"]&&e.$slots["search-no-results-text"]().length>0},e.menuConfig,{"aria-label":e.searchResultsLabel,"onUpdate:selected":e.onUpdateMenuSelection,onMenuItemClick:n[1]||(n[1]=i=>e.onSearchResultClick(e.asSearchResult(i))),onMenuItemKeyboardNavigation:e.onSearchResultKeyboardNavigation,onLoadMore:n[2]||(n[2]=i=>e.$emit("load-more"))}),{pending:t.withCtx(()=>[t.createElementVNode("div",{class:t.normalizeClass(["cdx-typeahead-search__menu-message",e.menuMessageClass])},[t.createElementVNode("span",It,[t.renderSlot(e.$slots,"search-results-pending")])],2)]),"no-results":t.withCtx(()=>[t.createElementVNode("div",{class:t.normalizeClass(["cdx-typeahead-search__menu-message",e.menuMessageClass])},[t.createElementVNode("span",xt,[t.renderSlot(e.$slots,"search-no-results-text")])],2)]),default:t.withCtx(({menuItem:i,active:h})=>[i.value===e.MenuFooterValue?(t.openBlock(),t.createElementBlock("a",{key:0,class:t.normalizeClass(["cdx-typeahead-search__search-footer",{"cdx-typeahead-search__search-footer__active":h}]),href:e.asSearchResult(i).url,onClickCapture:t.withModifiers(k=>e.onSearchFooterClick(e.asSearchResult(i)),["stop"])},[t.createVNode(l,{class:"cdx-typeahead-search__search-footer__icon",icon:e.articleIcon},null,8,["icon"]),t.createElementVNode("span",Mt,[t.renderSlot(e.$slots,"search-footer-text",{searchQuery:e.searchQuery},()=>[t.createElementVNode("strong",Vt,t.toDisplayString(e.searchQuery),1)])])],42,Ft)):t.createCommentVNode("",!0)]),_:3},16,["id","expanded","show-pending","selected","menu-items","footer","search-query","show-no-results-slot","aria-label","onUpdate:selected","onMenuItemKeyboardNavigation"])]),_:3},16,["modelValue","button-label","aria-owns","aria-expanded","aria-activedescendant","onUpdate:modelValue","onFocus","onBlur","onKeydown"]),t.renderSlot(e.$slots,"default")],40,Dt)],6)}const Tt=D(wt,[["render",Nt]]);f.CdxTypeaheadSearch=Tt,Object.defineProperties(f,{__esModule:{value:!0},[Symbol.toStringTag]:{value:"Module"}})});
