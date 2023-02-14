(function(f,t){typeof exports=="object"&&typeof module!="undefined"?t(exports,require("vue")):typeof define=="function"&&define.amd?define(["exports","vue"],t):(f=typeof globalThis!="undefined"?globalThis:f||self,t(f["codex-search"]={},f.Vue))})(this,function(f,t){"use strict";var Ot=Object.defineProperty,qt=Object.defineProperties;var Pt=Object.getOwnPropertyDescriptors;var G=Object.getOwnPropertySymbols;var pe=Object.prototype.hasOwnProperty,he=Object.prototype.propertyIsEnumerable;var ce=(f,t,y)=>t in f?Ot(f,t,{enumerable:!0,configurable:!0,writable:!0,value:y}):f[t]=y,me=(f,t)=>{for(var y in t||(t={}))pe.call(t,y)&&ce(f,y,t[y]);if(G)for(var y of G(t))he.call(t,y)&&ce(f,y,t[y]);return f},fe=(f,t)=>qt(f,Pt(t));var X=(f,t)=>{var y={};for(var _ in f)pe.call(f,_)&&t.indexOf(_)<0&&(y[_]=f[_]);if(f!=null&&G)for(var _ of G(f))t.indexOf(_)<0&&he.call(f,_)&&(y[_]=f[_]);return y};var se=(f,t,y)=>new Promise((_,H)=>{var J=x=>{try{z(y.next(x))}catch(O){H(O)}},Y=x=>{try{z(y.throw(x))}catch(O){H(O)}},z=x=>x.done?_(x.value):Promise.resolve(x.value).then(J,Y);z((y=y.apply(f,t)).next())});const y='<path d="M12.43 14.34A5 5 0 0110 15a5 5 0 113.95-2L17 16.09V3a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2h10a2 2 0 001.45-.63z"/><circle cx="10" cy="10" r="3"/>',_='<path d="M10 0a10 10 0 1010 10A10 10 0 0010 0zm5.66 14.24-1.41 1.41L10 11.41l-4.24 4.25-1.42-1.42L8.59 10 4.34 5.76l1.42-1.42L10 8.59l4.24-4.24 1.41 1.41L11.41 10z"/>',H='<path d="M19 3H1v14h18zM3 14l3.5-4.5 2.5 3L12.5 8l4.5 6z"/><path d="M19 5H1V3h18zm0 12H1v-2h18z"/>',J='<path d="M12.2 13.6a7 7 0 111.4-1.4l5.4 5.4-1.4 1.4zM3 8a5 5 0 1010 0A5 5 0 003 8z"/>',Y=y,z=_,x=H,O=J;function ge(e,n,o){if(typeof e=="string"||"path"in e)return e;if("shouldFlip"in e)return e.ltr;if("rtl"in e)return o==="rtl"?e.rtl:e.ltr;const s=n in e.langCodeMap?e.langCodeMap[n]:e.default;return typeof s=="string"||"path"in s?s:s.ltr}function ye(e,n){if(typeof e=="string")return!1;if("langCodeMap"in e){const o=n in e.langCodeMap?e.langCodeMap[n]:e.default;if(typeof o=="string")return!1;e=o}if("shouldFlipExceptions"in e&&Array.isArray(e.shouldFlipExceptions)){const o=e.shouldFlipExceptions.indexOf(n);return o===void 0||o===-1}return"shouldFlip"in e?e.shouldFlip:!1}function Ce(e){const n=t.ref(null);return t.onMounted(()=>{const o=window.getComputedStyle(e.value).direction;n.value=o==="ltr"||o==="rtl"?o:null}),n}function be(e){const n=t.ref("");return t.onMounted(()=>{let o=e.value;for(;o&&o.lang==="";)o=o.parentElement;n.value=o?o.lang:null}),n}function v(e){return n=>typeof n=="string"&&e.indexOf(n)!==-1}const Z="cdx",Be=["default","progressive","destructive"],Ae=["normal","primary","quiet"],ke=["x-small","small","medium"],Ee=["text","search"],ie=["default","error"],Se=120,_e=500,L="cdx-menu-footer-item",$e=v(ke),we=t.defineComponent({name:"CdxIcon",props:{icon:{type:[String,Object],required:!0},iconLabel:{type:String,default:""},lang:{type:String,default:null},dir:{type:String,default:null},size:{type:String,default:"medium",validator:$e}},emits:["click"],setup(e,{emit:n}){const o=t.ref(),s=Ce(o),a=be(o),r=t.computed(()=>e.dir||s.value),l=t.computed(()=>e.lang||a.value),d=t.computed(()=>({"cdx-icon--flipped":r.value==="rtl"&&l.value!==null&&ye(e.icon,l.value),[`cdx-icon--${e.size}`]:!0})),i=t.computed(()=>ge(e.icon,l.value||"",r.value||"ltr")),c=t.computed(()=>typeof i.value=="string"?i.value:""),h=t.computed(()=>typeof i.value!="string"?i.value.path:"");return{rootElement:o,rootClasses:d,iconSvg:c,iconPath:h,onClick:C=>{n("click",C)}}}}),Ht="",D=(e,n)=>{const o=e.__vccOpts||e;for(const[s,a]of n)o[s]=a;return o},De=["aria-hidden"],Ie={key:0},xe=["innerHTML"],Fe=["d"];function Me(e,n,o,s,a,r){return t.openBlock(),t.createElementBlock("span",{ref:"rootElement",class:t.normalizeClass(["cdx-icon",e.rootClasses]),onClick:n[0]||(n[0]=(...l)=>e.onClick&&e.onClick(...l))},[(t.openBlock(),t.createElementBlock("svg",{xmlns:"http://www.w3.org/2000/svg",width:"20",height:"20",viewBox:"0 0 20 20","aria-hidden":e.iconLabel?void 0:!0},[e.iconLabel?(t.openBlock(),t.createElementBlock("title",Ie,t.toDisplayString(e.iconLabel),1)):t.createCommentVNode("",!0),e.iconSvg?(t.openBlock(),t.createElementBlock("g",{key:1,innerHTML:e.iconSvg},null,8,xe)):(t.openBlock(),t.createElementBlock("path",{key:2,d:e.iconPath},null,8,Fe))],8,De))],2)}const q=D(we,[["render",Me]]),Ve=t.defineComponent({name:"CdxThumbnail",components:{CdxIcon:q},props:{thumbnail:{type:[Object,null],default:null},placeholderIcon:{type:[String,Object],default:x}},setup:e=>{const n=t.ref(!1),o=t.ref({}),s=a=>{const r=a.replace(/([\\"\n])/g,"\\$1"),l=new Image;l.onload=()=>{o.value={backgroundImage:`url("${r}")`},n.value=!0},l.onerror=()=>{n.value=!1},l.src=r};return t.onMounted(()=>{var a;(a=e.thumbnail)!=null&&a.url&&s(e.thumbnail.url)}),{thumbnailStyle:o,thumbnailLoaded:n}}}),Qt="",Ne={class:"cdx-thumbnail"},Te={key:0,class:"cdx-thumbnail__placeholder"};function ve(e,n,o,s,a,r){const l=t.resolveComponent("cdx-icon");return t.openBlock(),t.createElementBlock("span",Ne,[e.thumbnailLoaded?t.createCommentVNode("",!0):(t.openBlock(),t.createElementBlock("span",Te,[t.createVNode(l,{icon:e.placeholderIcon,class:"cdx-thumbnail__placeholder__icon"},null,8,["icon"])])),t.createVNode(t.Transition,{name:"cdx-thumbnail__image"},{default:t.withCtx(()=>[e.thumbnailLoaded?(t.openBlock(),t.createElementBlock("span",{key:0,style:t.normalizeStyle(e.thumbnailStyle),class:"cdx-thumbnail__image"},null,4)):t.createCommentVNode("",!0)]),_:1})])}const Le=D(Ve,[["render",ve]]);function Ke(e){return e.replace(/([\\{}()|.?*+\-^$[\]])/g,"\\$1")}const Re="[\u0300-\u036F\u0483-\u0489\u0591-\u05BD\u05BF\u05C1\u05C2\u05C4\u05C5\u05C7\u0610-\u061A\u064B-\u065F\u0670\u06D6-\u06DC\u06DF-\u06E4\u06E7\u06E8\u06EA-\u06ED\u0711\u0730-\u074A\u07A6-\u07B0\u07EB-\u07F3\u07FD\u0816-\u0819\u081B-\u0823\u0825-\u0827\u0829-\u082D\u0859-\u085B\u08D3-\u08E1\u08E3-\u0903\u093A-\u093C\u093E-\u094F\u0951-\u0957\u0962\u0963\u0981-\u0983\u09BC\u09BE-\u09C4\u09C7\u09C8\u09CB-\u09CD\u09D7\u09E2\u09E3\u09FE\u0A01-\u0A03\u0A3C\u0A3E-\u0A42\u0A47\u0A48\u0A4B-\u0A4D\u0A51\u0A70\u0A71\u0A75\u0A81-\u0A83\u0ABC\u0ABE-\u0AC5\u0AC7-\u0AC9\u0ACB-\u0ACD\u0AE2\u0AE3\u0AFA-\u0AFF\u0B01-\u0B03\u0B3C\u0B3E-\u0B44\u0B47\u0B48\u0B4B-\u0B4D\u0B56\u0B57\u0B62\u0B63\u0B82\u0BBE-\u0BC2\u0BC6-\u0BC8\u0BCA-\u0BCD\u0BD7\u0C00-\u0C04\u0C3E-\u0C44\u0C46-\u0C48\u0C4A-\u0C4D\u0C55\u0C56\u0C62\u0C63\u0C81-\u0C83\u0CBC\u0CBE-\u0CC4\u0CC6-\u0CC8\u0CCA-\u0CCD\u0CD5\u0CD6\u0CE2\u0CE3\u0D00-\u0D03\u0D3B\u0D3C\u0D3E-\u0D44\u0D46-\u0D48\u0D4A-\u0D4D\u0D57\u0D62\u0D63\u0D82\u0D83\u0DCA\u0DCF-\u0DD4\u0DD6\u0DD8-\u0DDF\u0DF2\u0DF3\u0E31\u0E34-\u0E3A\u0E47-\u0E4E\u0EB1\u0EB4-\u0EB9\u0EBB\u0EBC\u0EC8-\u0ECD\u0F18\u0F19\u0F35\u0F37\u0F39\u0F3E\u0F3F\u0F71-\u0F84\u0F86\u0F87\u0F8D-\u0F97\u0F99-\u0FBC\u0FC6\u102B-\u103E\u1056-\u1059\u105E-\u1060\u1062-\u1064\u1067-\u106D\u1071-\u1074\u1082-\u108D\u108F\u109A-\u109D\u135D-\u135F\u1712-\u1714\u1732-\u1734\u1752\u1753\u1772\u1773\u17B4-\u17D3\u17DD\u180B-\u180D\u1885\u1886\u18A9\u1920-\u192B\u1930-\u193B\u1A17-\u1A1B\u1A55-\u1A5E\u1A60-\u1A7C\u1A7F\u1AB0-\u1ABE\u1B00-\u1B04\u1B34-\u1B44\u1B6B-\u1B73\u1B80-\u1B82\u1BA1-\u1BAD\u1BE6-\u1BF3\u1C24-\u1C37\u1CD0-\u1CD2\u1CD4-\u1CE8\u1CED\u1CF2-\u1CF4\u1CF7-\u1CF9\u1DC0-\u1DF9\u1DFB-\u1DFF\u20D0-\u20F0\u2CEF-\u2CF1\u2D7F\u2DE0-\u2DFF\u302A-\u302F\u3099\u309A\uA66F-\uA672\uA674-\uA67D\uA69E\uA69F\uA6F0\uA6F1\uA802\uA806\uA80B\uA823-\uA827\uA880\uA881\uA8B4-\uA8C5\uA8E0-\uA8F1\uA8FF\uA926-\uA92D\uA947-\uA953\uA980-\uA983\uA9B3-\uA9C0\uA9E5\uAA29-\uAA36\uAA43\uAA4C\uAA4D\uAA7B-\uAA7D\uAAB0\uAAB2-\uAAB4\uAAB7\uAAB8\uAABE\uAABF\uAAC1\uAAEB-\uAAEF\uAAF5\uAAF6\uABE3-\uABEA\uABEC\uABED\uFB1E\uFE00-\uFE0F\uFE20-\uFE2F]";function ze(e,n){if(!e)return[n,"",""];const o=Ke(e),s=new RegExp(o+Re+"*","i").exec(n);if(!s||s.index===void 0)return[n,"",""];const a=s.index,r=a+s[0].length,l=n.slice(a,r),d=n.slice(0,a),i=n.slice(r,n.length);return[d,l,i]}const Oe=t.defineComponent({name:"CdxSearchResultTitle",props:{title:{type:String,required:!0},searchQuery:{type:String,default:""}},setup:e=>({titleChunks:t.computed(()=>ze(e.searchQuery,String(e.title)))})}),Ut="",qe={class:"cdx-search-result-title"},Pe={class:"cdx-search-result-title__match"};function He(e,n,o,s,a,r){return t.openBlock(),t.createElementBlock("span",qe,[t.createElementVNode("bdi",null,[t.createTextVNode(t.toDisplayString(e.titleChunks[0]),1),t.createElementVNode("span",Pe,t.toDisplayString(e.titleChunks[1]),1),t.createTextVNode(t.toDisplayString(e.titleChunks[2]),1)])])}const Qe=D(Oe,[["render",He]]),Ue=t.defineComponent({name:"CdxMenuItem",components:{CdxIcon:q,CdxThumbnail:Le,CdxSearchResultTitle:Qe},props:{id:{type:String,required:!0},value:{type:[String,Number],required:!0},disabled:{type:Boolean,default:!1},selected:{type:Boolean,default:!1},active:{type:Boolean,default:!1},highlighted:{type:Boolean,default:!1},label:{type:String,default:""},match:{type:String,default:""},supportingText:{type:String,default:""},url:{type:String,default:""},icon:{type:[String,Object],default:""},showThumbnail:{type:Boolean,default:!1},thumbnail:{type:[Object,null],default:null},description:{type:[String,null],default:""},searchQuery:{type:String,default:""},boldLabel:{type:Boolean,default:!1},hideDescriptionOverflow:{type:Boolean,default:!1},language:{type:Object,default:()=>({})}},emits:["change"],setup:(e,{emit:n})=>{const o=()=>{n("change","highlighted",!0)},s=()=>{n("change","highlighted",!1)},a=h=>{h.button===0&&n("change","active",!0)},r=()=>{n("change","selected",!0)},l=t.computed(()=>e.searchQuery.length>0),d=t.computed(()=>({"cdx-menu-item--selected":e.selected,"cdx-menu-item--active":e.active&&e.highlighted,"cdx-menu-item--highlighted":e.highlighted,"cdx-menu-item--enabled":!e.disabled,"cdx-menu-item--disabled":e.disabled,"cdx-menu-item--highlight-query":l.value,"cdx-menu-item--bold-label":e.boldLabel,"cdx-menu-item--has-description":!!e.description,"cdx-menu-item--hide-description-overflow":e.hideDescriptionOverflow})),i=t.computed(()=>e.url?"a":"span"),c=t.computed(()=>e.label||String(e.value));return{onMouseEnter:o,onMouseLeave:s,onMouseDown:a,onClick:r,highlightQuery:l,rootClasses:d,contentTag:i,title:c}}}),jt="",je=["id","aria-disabled","aria-selected"],We={class:"cdx-menu-item__text"},Ge=["lang"],Xe=["lang"],Je=["lang"],Ye=["lang"];function Ze(e,n,o,s,a,r){const l=t.resolveComponent("cdx-thumbnail"),d=t.resolveComponent("cdx-icon"),i=t.resolveComponent("cdx-search-result-title");return t.openBlock(),t.createElementBlock("li",{id:e.id,role:"option",class:t.normalizeClass(["cdx-menu-item",e.rootClasses]),"aria-disabled":e.disabled,"aria-selected":e.selected,onMouseenter:n[0]||(n[0]=(...c)=>e.onMouseEnter&&e.onMouseEnter(...c)),onMouseleave:n[1]||(n[1]=(...c)=>e.onMouseLeave&&e.onMouseLeave(...c)),onMousedown:n[2]||(n[2]=t.withModifiers((...c)=>e.onMouseDown&&e.onMouseDown(...c),["prevent"])),onClick:n[3]||(n[3]=(...c)=>e.onClick&&e.onClick(...c))},[t.renderSlot(e.$slots,"default",{},()=>[(t.openBlock(),t.createBlock(t.resolveDynamicComponent(e.contentTag),{href:e.url?e.url:void 0,class:"cdx-menu-item__content"},{default:t.withCtx(()=>{var c,h,b,C,k,$;return[e.showThumbnail?(t.openBlock(),t.createBlock(l,{key:0,thumbnail:e.thumbnail,class:"cdx-menu-item__thumbnail"},null,8,["thumbnail"])):e.icon?(t.openBlock(),t.createBlock(d,{key:1,icon:e.icon,class:"cdx-menu-item__icon"},null,8,["icon"])):t.createCommentVNode("",!0),t.createElementVNode("span",We,[e.highlightQuery?(t.openBlock(),t.createBlock(i,{key:0,title:e.title,"search-query":e.searchQuery,lang:(c=e.language)==null?void 0:c.label},null,8,["title","search-query","lang"])):(t.openBlock(),t.createElementBlock("span",{key:1,class:"cdx-menu-item__text__label",lang:(h=e.language)==null?void 0:h.label},[t.createElementVNode("bdi",null,t.toDisplayString(e.title),1)],8,Ge)),e.match?(t.openBlock(),t.createElementBlock(t.Fragment,{key:2},[t.createTextVNode(t.toDisplayString(" ")+" "),e.highlightQuery?(t.openBlock(),t.createBlock(i,{key:0,title:e.match,"search-query":e.searchQuery,lang:(b=e.language)==null?void 0:b.match},null,8,["title","search-query","lang"])):(t.openBlock(),t.createElementBlock("span",{key:1,class:"cdx-menu-item__text__match",lang:(C=e.language)==null?void 0:C.match},[t.createElementVNode("bdi",null,t.toDisplayString(e.match),1)],8,Xe))],64)):t.createCommentVNode("",!0),e.supportingText?(t.openBlock(),t.createElementBlock(t.Fragment,{key:3},[t.createTextVNode(t.toDisplayString(" ")+" "),t.createElementVNode("span",{class:"cdx-menu-item__text__supporting-text",lang:(k=e.language)==null?void 0:k.supportingText},[t.createElementVNode("bdi",null,t.toDisplayString(e.supportingText),1)],8,Je)],64)):t.createCommentVNode("",!0),e.description?(t.openBlock(),t.createElementBlock("span",{key:4,class:"cdx-menu-item__text__description",lang:($=e.language)==null?void 0:$.description},[t.createElementVNode("bdi",null,t.toDisplayString(e.description),1)],8,Ye)):t.createCommentVNode("",!0)])]}),_:1},8,["href"]))])],42,je)}const et=D(Ue,[["render",Ze]]),tt=t.defineComponent({name:"CdxProgressBar",props:{inline:{type:Boolean,default:!1},disabled:{type:Boolean,default:!1}},setup(e){return{rootClasses:t.computed(()=>({"cdx-progress-bar--block":!e.inline,"cdx-progress-bar--inline":e.inline,"cdx-progress-bar--enabled":!e.disabled,"cdx-progress-bar--disabled":e.disabled}))}}}),Wt="",nt=["aria-disabled"],ot=[t.createElementVNode("div",{class:"cdx-progress-bar__bar"},null,-1)];function lt(e,n,o,s,a,r){return t.openBlock(),t.createElementBlock("div",{class:t.normalizeClass(["cdx-progress-bar",e.rootClasses]),role:"progressbar","aria-disabled":e.disabled,"aria-valuemin":"0","aria-valuemax":"100"},ot,10,nt)}const at=D(tt,[["render",lt]]);let ee=0;function ue(e){const n=t.getCurrentInstance(),o=(n==null?void 0:n.props.id)||(n==null?void 0:n.attrs.id);return e?`${Z}-${e}-${ee++}`:o?`${Z}-${o}-${ee++}`:`${Z}-${ee++}`}function st(e,n){const o=t.ref(!1);let s=!1;if(typeof window!="object"||!("IntersectionObserver"in window&&"IntersectionObserverEntry"in window&&"intersectionRatio"in window.IntersectionObserverEntry.prototype))return o;const a=new window.IntersectionObserver(r=>{const l=r[0];l&&(o.value=l.isIntersecting)},n);return t.onMounted(()=>{s=!0,e.value&&a.observe(e.value)}),t.onUnmounted(()=>{s=!1,a.disconnect()}),t.watch(e,r=>{!s||(a.disconnect(),o.value=!1,r&&a.observe(r))}),o}function Q(e,n=t.computed(()=>({}))){const o=t.computed(()=>{const r=X(n.value,[]);return e.class&&e.class.split(" ").forEach(d=>{r[d]=!0}),r}),s=t.computed(()=>{if("style"in e)return e.style}),a=t.computed(()=>{const i=e,{class:r,style:l}=i;return X(i,["class","style"])});return{rootClasses:o,rootStyle:s,otherAttrs:a}}const it=t.defineComponent({name:"CdxMenu",components:{CdxMenuItem:et,CdxProgressBar:at},inheritAttrs:!1,props:{menuItems:{type:Array,required:!0},footer:{type:Object,default:null},selected:{type:[String,Number,null],required:!0},expanded:{type:Boolean,required:!0},showPending:{type:Boolean,default:!1},visibleItemLimit:{type:Number,default:null},showThumbnail:{type:Boolean,default:!1},boldLabel:{type:Boolean,default:!1},hideDescriptionOverflow:{type:Boolean,default:!1},searchQuery:{type:String,default:""},showNoResultsSlot:{type:Boolean,default:null}},emits:["update:selected","update:expanded","menu-item-click","menu-item-keyboard-navigation","load-more"],expose:["clearActive","getHighlightedMenuItem","getHighlightedViaKeyboard","delegateKeyNavigation"],setup(e,{emit:n,slots:o,attrs:s}){const a=t.computed(()=>(e.footer&&e.menuItems?[...e.menuItems,e.footer]:e.menuItems).map(m=>fe(me({},m),{id:ue("menu-item")}))),r=t.computed(()=>o["no-results"]?e.showNoResultsSlot!==null?e.showNoResultsSlot:a.value.length===0:!1),l=t.ref(null),d=t.ref(!1),i=t.ref(null);function c(){return a.value.find(u=>u.value===e.selected)}function h(u,m){var g;if(!(m&&m.disabled))switch(u){case"selected":n("update:selected",(g=m==null?void 0:m.value)!=null?g:null),n("update:expanded",!1),i.value=null;break;case"highlighted":l.value=m||null,d.value=!1;break;case"highlightedViaKeyboard":l.value=m||null,d.value=!0;break;case"active":i.value=m||null;break}}const b=t.computed(()=>{if(l.value!==null)return a.value.findIndex(u=>u.value===l.value.value)});function C(u){!u||(h("highlightedViaKeyboard",u),n("menu-item-keyboard-navigation",u))}function k(u){var A;const m=P=>{for(let T=P-1;T>=0;T--)if(!a.value[T].disabled)return a.value[T]};u=u||a.value.length;const g=(A=m(u))!=null?A:m(a.value.length);C(g)}function $(u){const m=A=>a.value.find((P,T)=>!P.disabled&&T>A);u=u!=null?u:-1;const g=m(u)||m(-1);C(g)}function I(u,m=!0){function g(){n("update:expanded",!0),h("highlighted",c())}function A(){m&&(u.preventDefault(),u.stopPropagation())}switch(u.key){case"Enter":case" ":return A(),e.expanded?(l.value&&d.value&&n("update:selected",l.value.value),n("update:expanded",!1)):g(),!0;case"Tab":return e.expanded&&(l.value&&d.value&&n("update:selected",l.value.value),n("update:expanded",!1)),!0;case"ArrowUp":return A(),e.expanded?(l.value===null&&h("highlightedViaKeyboard",c()),k(b.value)):g(),V(),!0;case"ArrowDown":return A(),e.expanded?(l.value===null&&h("highlightedViaKeyboard",c()),$(b.value)):g(),V(),!0;case"Home":return A(),e.expanded?(l.value===null&&h("highlightedViaKeyboard",c()),$()):g(),V(),!0;case"End":return A(),e.expanded?(l.value===null&&h("highlightedViaKeyboard",c()),k()):g(),V(),!0;case"Escape":return A(),n("update:expanded",!1),!0;default:return!1}}function B(){h("active")}const E=[],U=t.ref(void 0),w=st(U,{threshold:.8});t.watch(w,u=>{u&&n("load-more")});function ne(u,m){if(u){E[m]=u.$el;const g=e.visibleItemLimit;if(!g||e.menuItems.length<g)return;const A=Math.min(g,Math.max(2,Math.floor(.2*e.menuItems.length)));m===e.menuItems.length-A&&(U.value=u.$el)}}function V(){if(!e.visibleItemLimit||e.visibleItemLimit>e.menuItems.length||b.value===void 0)return;const u=b.value>=0?b.value:0;E[u].scrollIntoView({behavior:"smooth",block:"nearest"})}const N=t.ref(null),K=t.ref(null);function j(){if(K.value=null,!e.visibleItemLimit||E.length<=e.visibleItemLimit){N.value=null;return}const u=E[0],m=E[e.visibleItemLimit];if(N.value=oe(u,m),e.footer){const g=E[E.length-1];K.value=g.scrollHeight}}function oe(u,m){const g=u.getBoundingClientRect().top;return m.getBoundingClientRect().top-g+2}t.onMounted(()=>{document.addEventListener("mouseup",B)}),t.onUnmounted(()=>{document.removeEventListener("mouseup",B)}),t.watch(t.toRef(e,"expanded"),u=>se(this,null,function*(){const m=c();!u&&l.value&&m===void 0&&h("highlighted"),u&&m!==void 0&&h("highlighted",m),u&&(yield t.nextTick(),j(),yield t.nextTick(),V())})),t.watch(t.toRef(e,"menuItems"),u=>se(this,null,function*(){u.length<E.length&&(E.length=u.length),e.expanded&&(yield t.nextTick(),j(),yield t.nextTick(),V())}),{deep:!0});const le=t.computed(()=>({"max-height":N.value?`${N.value}px`:void 0,"overflow-y":N.value?"scroll":void 0,"margin-bottom":K.value?`${K.value}px`:void 0})),R=t.computed(()=>({"cdx-menu--has-footer":!!e.footer,"cdx-menu--has-sticky-footer":!!e.footer&&!!N.value})),{rootClasses:F,rootStyle:W,otherAttrs:ae}=Q(s,R);return{listBoxStyle:le,rootClasses:F,rootStyle:W,otherAttrs:ae,assignTemplateRef:ne,computedMenuItems:a,computedShowNoResultsSlot:r,highlightedMenuItem:l,highlightedViaKeyboard:d,activeMenuItem:i,handleMenuItemChange:h,handleKeyNavigation:I}},methods:{getHighlightedMenuItem(){return this.highlightedMenuItem},getHighlightedViaKeyboard(){return this.highlightedViaKeyboard},clearActive(){this.handleMenuItemChange("active")},delegateKeyNavigation(e,n=!0){return this.handleKeyNavigation(e,n)}}}),Xt="",ut={key:0,class:"cdx-menu__pending cdx-menu-item"},rt={key:1,class:"cdx-menu__no-results cdx-menu-item"};function dt(e,n,o,s,a,r){const l=t.resolveComponent("cdx-menu-item"),d=t.resolveComponent("cdx-progress-bar");return t.withDirectives((t.openBlock(),t.createElementBlock("div",{class:t.normalizeClass(["cdx-menu",e.rootClasses]),style:t.normalizeStyle(e.rootStyle)},[t.createElementVNode("ul",t.mergeProps({class:"cdx-menu__listbox",role:"listbox","aria-multiselectable":"false",style:e.listBoxStyle},e.otherAttrs),[e.showPending&&e.computedMenuItems.length===0&&e.$slots.pending?(t.openBlock(),t.createElementBlock("li",ut,[t.renderSlot(e.$slots,"pending")])):t.createCommentVNode("",!0),e.computedShowNoResultsSlot?(t.openBlock(),t.createElementBlock("li",rt,[t.renderSlot(e.$slots,"no-results")])):t.createCommentVNode("",!0),(t.openBlock(!0),t.createElementBlock(t.Fragment,null,t.renderList(e.computedMenuItems,(i,c)=>{var h,b;return t.openBlock(),t.createBlock(l,t.mergeProps({key:i.value,ref_for:!0,ref:C=>e.assignTemplateRef(C,c)},i,{selected:i.value===e.selected,active:i.value===((h=e.activeMenuItem)==null?void 0:h.value),highlighted:i.value===((b=e.highlightedMenuItem)==null?void 0:b.value),"show-thumbnail":e.showThumbnail,"bold-label":e.boldLabel,"hide-description-overflow":e.hideDescriptionOverflow,"search-query":e.searchQuery,onChange:(C,k)=>e.handleMenuItemChange(C,k&&i),onClick:C=>e.$emit("menu-item-click",i)}),{default:t.withCtx(()=>{var C,k;return[t.renderSlot(e.$slots,"default",{menuItem:i,active:i.value===((C=e.activeMenuItem)==null?void 0:C.value)&&i.value===((k=e.highlightedMenuItem)==null?void 0:k.value)})]}),_:2},1040,["selected","active","highlighted","show-thumbnail","bold-label","hide-description-overflow","search-query","onChange","onClick"])}),128)),e.showPending?(t.openBlock(),t.createBlock(d,{key:2,class:"cdx-menu__progress-bar",inline:!0})):t.createCommentVNode("",!0)],16)],6)),[[t.vShow,e.expanded]])}const ct=D(it,[["render",dt]]),pt=v(Ae),ht=v(Be),mt=e=>{!e["aria-label"]&&!e["aria-hidden"]&&t.warn(`icon-only buttons require one of the following attribute: aria-label or aria-hidden.
		See documentation on https://doc.wikimedia.org/codex/latest/components/button.html#default-icon-only`)};function te(e){const n=[];for(const o of e)typeof o=="string"&&o.trim()!==""?n.push(o):Array.isArray(o)?n.push(...te(o)):typeof o=="object"&&o&&(typeof o.type=="string"||typeof o.type=="object"?n.push(o):o.type!==t.Comment&&(typeof o.children=="string"&&o.children.trim()!==""?n.push(o.children):Array.isArray(o.children)&&n.push(...te(o.children))));return n}const ft=(e,n)=>{if(!e)return!1;const o=te(e);if(o.length!==1)return!1;const s=o[0],a=typeof s=="object"&&typeof s.type=="object"&&"name"in s.type&&s.type.name===q.name,r=typeof s=="object"&&s.type==="svg";return a||r?(mt(n),!0):!1},gt=t.defineComponent({name:"CdxButton",props:{action:{type:String,default:"default",validator:ht},type:{type:String,default:"normal",validator:pt}},emits:["click"],setup(e,{emit:n,slots:o,attrs:s}){const a=t.ref(!1);return{rootClasses:t.computed(()=>{var i;return{[`cdx-button--action-${e.action}`]:!0,[`cdx-button--type-${e.type}`]:!0,"cdx-button--framed":e.type!=="quiet","cdx-button--icon-only":ft((i=o.default)==null?void 0:i.call(o),s),"cdx-button--is-active":a.value}}),onClick:i=>{n("click",i)},setActive:i=>{a.value=i}}}}),Jt="";function yt(e,n,o,s,a,r){return t.openBlock(),t.createElementBlock("button",{class:t.normalizeClass(["cdx-button",e.rootClasses]),onClick:n[0]||(n[0]=(...l)=>e.onClick&&e.onClick(...l)),onKeydown:n[1]||(n[1]=t.withKeys(l=>e.setActive(!0),["space","enter"])),onKeyup:n[2]||(n[2]=t.withKeys(l=>e.setActive(!1),["space","enter"]))},[t.renderSlot(e.$slots,"default")],34)}const Ct=D(gt,[["render",yt]]);function re(e,n,o){return t.computed({get:()=>e.value,set:s=>n(o||"update:modelValue",s)})}const bt=v(Ee),Bt=v(ie),At=t.defineComponent({name:"CdxTextInput",components:{CdxIcon:q},inheritAttrs:!1,expose:["focus"],props:{modelValue:{type:[String,Number],default:""},inputType:{type:String,default:"text",validator:bt},status:{type:String,default:"default",validator:Bt},disabled:{type:Boolean,default:!1},startIcon:{type:[String,Object],default:void 0},endIcon:{type:[String,Object],default:void 0},clearable:{type:Boolean,default:!1}},emits:["update:modelValue","input","change","keydown","focus","blur"],setup(e,{emit:n,attrs:o}){const s=re(t.toRef(e,"modelValue"),n),a=t.computed(()=>e.clearable&&!!s.value&&!e.disabled),r=t.computed(()=>({"cdx-text-input--has-start-icon":!!e.startIcon,"cdx-text-input--has-end-icon":!!e.endIcon,"cdx-text-input--clearable":a.value})),{rootClasses:l,rootStyle:d,otherAttrs:i}=Q(o,r),c=t.computed(()=>({"cdx-text-input__input--has-value":!!s.value,[`cdx-text-input__input--status-${e.status}`]:!0}));return{wrappedModel:s,isClearable:a,rootClasses:l,rootStyle:d,otherAttrs:i,inputClasses:c,onClear:()=>{s.value=""},onInput:B=>{n("input",B)},onChange:B=>{n("change",B)},onKeydown:B=>{(B.key==="Home"||B.key==="End")&&!B.ctrlKey&&!B.metaKey||n("keydown",B)},onFocus:B=>{n("focus",B)},onBlur:B=>{n("blur",B)},cdxIconClear:z}},methods:{focus(){this.$refs.input.focus()}}}),Yt="",kt=["type","disabled"];function Et(e,n,o,s,a,r){const l=t.resolveComponent("cdx-icon");return t.openBlock(),t.createElementBlock("div",{class:t.normalizeClass(["cdx-text-input",e.rootClasses]),style:t.normalizeStyle(e.rootStyle)},[t.withDirectives(t.createElementVNode("input",t.mergeProps({ref:"input","onUpdate:modelValue":n[0]||(n[0]=d=>e.wrappedModel=d),class:["cdx-text-input__input",e.inputClasses]},e.otherAttrs,{type:e.inputType,disabled:e.disabled,onInput:n[1]||(n[1]=(...d)=>e.onInput&&e.onInput(...d)),onChange:n[2]||(n[2]=(...d)=>e.onChange&&e.onChange(...d)),onFocus:n[3]||(n[3]=(...d)=>e.onFocus&&e.onFocus(...d)),onBlur:n[4]||(n[4]=(...d)=>e.onBlur&&e.onBlur(...d)),onKeydown:n[5]||(n[5]=(...d)=>e.onKeydown&&e.onKeydown(...d))}),null,16,kt),[[t.vModelDynamic,e.wrappedModel]]),e.startIcon?(t.openBlock(),t.createBlock(l,{key:0,icon:e.startIcon,class:"cdx-text-input__icon cdx-text-input__start-icon"},null,8,["icon"])):t.createCommentVNode("",!0),e.endIcon?(t.openBlock(),t.createBlock(l,{key:1,icon:e.endIcon,class:"cdx-text-input__icon cdx-text-input__end-icon"},null,8,["icon"])):t.createCommentVNode("",!0),e.isClearable?(t.openBlock(),t.createBlock(l,{key:2,icon:e.cdxIconClear,class:"cdx-text-input__icon cdx-text-input__clear-icon",onMousedown:n[6]||(n[6]=t.withModifiers(()=>{},["prevent"])),onClick:e.onClear},null,8,["icon","onClick"])):t.createCommentVNode("",!0)],6)}const St=D(At,[["render",Et]]),_t=v(ie),$t=t.defineComponent({name:"CdxSearchInput",components:{CdxButton:Ct,CdxTextInput:St},inheritAttrs:!1,props:{modelValue:{type:[String,Number],default:""},buttonLabel:{type:String,default:""},status:{type:String,default:"default",validator:_t}},emits:["update:modelValue","submit-click"],setup(e,{emit:n,attrs:o}){const s=re(t.toRef(e,"modelValue"),n),a=t.computed(()=>({"cdx-search-input--has-end-button":!!e.buttonLabel})),{rootClasses:r,rootStyle:l,otherAttrs:d}=Q(o,a);return{wrappedModel:s,rootClasses:r,rootStyle:l,otherAttrs:d,handleSubmit:()=>{n("submit-click",s.value)},searchIcon:O}},methods:{focus(){this.$refs.textInput.focus()}}}),Zt="",wt={class:"cdx-search-input__input-wrapper"};function Dt(e,n,o,s,a,r){const l=t.resolveComponent("cdx-text-input"),d=t.resolveComponent("cdx-button");return t.openBlock(),t.createElementBlock("div",{class:t.normalizeClass(["cdx-search-input",e.rootClasses]),style:t.normalizeStyle(e.rootStyle)},[t.createElementVNode("div",wt,[t.createVNode(l,t.mergeProps({ref:"textInput",modelValue:e.wrappedModel,"onUpdate:modelValue":n[0]||(n[0]=i=>e.wrappedModel=i),class:"cdx-search-input__text-input","input-type":"search","start-icon":e.searchIcon,status:e.status},e.otherAttrs,{onKeydown:t.withKeys(e.handleSubmit,["enter"])}),null,16,["modelValue","start-icon","status","onKeydown"]),t.renderSlot(e.$slots,"default")]),e.buttonLabel?(t.openBlock(),t.createBlock(d,{key:0,class:"cdx-search-input__end-button",onClick:e.handleSubmit},{default:t.withCtx(()=>[t.createTextVNode(t.toDisplayString(e.buttonLabel),1)]),_:1},8,["onClick"])):t.createCommentVNode("",!0)],6)}const It=D($t,[["render",Dt]]),xt=t.defineComponent({name:"CdxTypeaheadSearch",components:{CdxIcon:q,CdxMenu:ct,CdxSearchInput:It},inheritAttrs:!1,props:{id:{type:String,required:!0},formAction:{type:String,required:!0},searchResultsLabel:{type:String,required:!0},searchResults:{type:Array,required:!0},buttonLabel:{type:String,default:""},initialInputValue:{type:String,default:""},searchFooterUrl:{type:String,default:""},debounceInterval:{type:Number,default:Se},highlightQuery:{type:Boolean,default:!1},showThumbnail:{type:Boolean,default:!1},autoExpandWidth:{type:Boolean,default:!1},visibleItemLimit:{type:Number,default:null}},emits:["input","search-result-click","submit","load-more"],setup(e,{attrs:n,emit:o,slots:s}){const{searchResults:a,searchFooterUrl:r,debounceInterval:l}=t.toRefs(e),d=t.ref(),i=t.ref(),c=ue("typeahead-search-menu"),h=t.ref(!1),b=t.ref(!1),C=t.ref(!1),k=t.ref(!1),$=t.ref(e.initialInputValue),I=t.ref(""),B=t.computed(()=>{var p,S;return(S=(p=i.value)==null?void 0:p.getHighlightedMenuItem())==null?void 0:S.id}),E=t.ref(null),U=t.computed(()=>({"cdx-typeahead-search__menu-message--has-thumbnail":e.showThumbnail})),w=t.computed(()=>e.searchResults.find(p=>p.value===E.value)),ne=t.computed(()=>r.value?{value:L,url:r.value}:void 0),V=t.computed(()=>({"cdx-typeahead-search--show-thumbnail":e.showThumbnail,"cdx-typeahead-search--expanded":h.value,"cdx-typeahead-search--auto-expand-width":e.showThumbnail&&e.autoExpandWidth})),{rootClasses:N,rootStyle:K,otherAttrs:j}=Q(n,V);function oe(p){return p}const le=t.computed(()=>({visibleItemLimit:e.visibleItemLimit,showThumbnail:e.showThumbnail,boldLabel:!0,hideDescriptionOverflow:!0}));let R,F;function W(p,S=!1){w.value&&w.value.label!==p&&w.value.value!==p&&(E.value=null),F!==void 0&&(clearTimeout(F),F=void 0),p===""?h.value=!1:(b.value=!0,s["search-results-pending"]&&(F=setTimeout(()=>{k.value&&(h.value=!0),C.value=!0},_e))),R!==void 0&&(clearTimeout(R),R=void 0);const M=()=>{o("input",p)};S?M():R=setTimeout(()=>{M()},l.value)}function ae(p){if(p===L){E.value=null,$.value=I.value;return}E.value=p,p!==null&&($.value=w.value?w.value.label||String(w.value.value):"")}function u(){k.value=!0,(I.value||C.value)&&(h.value=!0)}function m(){k.value=!1,h.value=!1}function g(p){const de=p,{id:S}=de,M=X(de,["id"]);if(M.value===L){o("search-result-click",{searchResult:null,index:a.value.length,numberOfResults:a.value.length});return}A(M)}function A(p){const S={searchResult:p,index:a.value.findIndex(M=>M.value===p.value),numberOfResults:a.value.length};o("search-result-click",S)}function P(p){if(p.value===L){$.value=I.value;return}$.value=p.value?p.label||String(p.value):""}function T(p){var S;h.value=!1,(S=i.value)==null||S.clearActive(),g(p)}function Rt(p){if(w.value)A(w.value),p.stopPropagation(),window.location.assign(w.value.url),p.preventDefault();else{const S={searchResult:null,index:-1,numberOfResults:a.value.length};o("submit",S)}}function zt(p){if(!i.value||!I.value||p.key===" ")return;const S=i.value.getHighlightedMenuItem(),M=i.value.getHighlightedViaKeyboard();switch(p.key){case"Enter":S&&(S.value===L&&M?window.location.assign(r.value):i.value.delegateKeyNavigation(p,!1)),h.value=!1;break;case"Tab":h.value=!1;break;default:i.value.delegateKeyNavigation(p);break}}return t.onMounted(()=>{e.initialInputValue&&W(e.initialInputValue,!0)}),t.watch(t.toRef(e,"searchResults"),()=>{I.value=$.value.trim(),k.value&&b.value&&I.value.length>0&&(h.value=!0),F!==void 0&&(clearTimeout(F),F=void 0),b.value=!1,C.value=!1}),{form:d,menu:i,menuId:c,highlightedId:B,selection:E,menuMessageClass:U,footer:ne,asSearchResult:oe,inputValue:$,searchQuery:I,expanded:h,showPending:C,rootClasses:N,rootStyle:K,otherAttrs:j,menuConfig:le,onUpdateInputValue:W,onUpdateMenuSelection:ae,onFocus:u,onBlur:m,onSearchResultClick:g,onSearchResultKeyboardNavigation:P,onSearchFooterClick:T,onSubmit:Rt,onKeydown:zt,MenuFooterValue:L,articleIcon:Y}},methods:{focus(){this.$refs.searchInput.focus()}}}),en="",Ft=["id","action"],Mt={class:"cdx-typeahead-search__menu-message__text"},Vt={class:"cdx-typeahead-search__menu-message__text"},Nt=["href","onClickCapture"],Tt={class:"cdx-typeahead-search__search-footer__text"},vt={class:"cdx-typeahead-search__search-footer__query"};function Lt(e,n,o,s,a,r){const l=t.resolveComponent("cdx-icon"),d=t.resolveComponent("cdx-menu"),i=t.resolveComponent("cdx-search-input");return t.openBlock(),t.createElementBlock("div",{class:t.normalizeClass(["cdx-typeahead-search",e.rootClasses]),style:t.normalizeStyle(e.rootStyle)},[t.createElementVNode("form",{id:e.id,ref:"form",class:"cdx-typeahead-search__form",action:e.formAction,onSubmit:n[4]||(n[4]=(...c)=>e.onSubmit&&e.onSubmit(...c))},[t.createVNode(i,t.mergeProps({ref:"searchInput",modelValue:e.inputValue,"onUpdate:modelValue":n[3]||(n[3]=c=>e.inputValue=c),"button-label":e.buttonLabel},e.otherAttrs,{class:"cdx-typeahead-search__input",name:"search",role:"combobox",autocomplete:"off","aria-autocomplete":"list","aria-owns":e.menuId,"aria-expanded":e.expanded,"aria-activedescendant":e.highlightedId,"onUpdate:modelValue":e.onUpdateInputValue,onFocus:e.onFocus,onBlur:e.onBlur,onKeydown:e.onKeydown}),{default:t.withCtx(()=>[t.createVNode(d,t.mergeProps({id:e.menuId,ref:"menu",expanded:e.expanded,"onUpdate:expanded":n[0]||(n[0]=c=>e.expanded=c),"show-pending":e.showPending,selected:e.selection,"menu-items":e.searchResults,footer:e.footer,"search-query":e.highlightQuery?e.searchQuery:"","show-no-results-slot":e.searchQuery.length>0&&e.searchResults.length===0&&e.$slots["search-no-results-text"]&&e.$slots["search-no-results-text"]().length>0},e.menuConfig,{"aria-label":e.searchResultsLabel,"onUpdate:selected":e.onUpdateMenuSelection,onMenuItemClick:n[1]||(n[1]=c=>e.onSearchResultClick(e.asSearchResult(c))),onMenuItemKeyboardNavigation:e.onSearchResultKeyboardNavigation,onLoadMore:n[2]||(n[2]=c=>e.$emit("load-more"))}),{pending:t.withCtx(()=>[t.createElementVNode("div",{class:t.normalizeClass(["cdx-typeahead-search__menu-message",e.menuMessageClass])},[t.createElementVNode("span",Mt,[t.renderSlot(e.$slots,"search-results-pending")])],2)]),"no-results":t.withCtx(()=>[t.createElementVNode("div",{class:t.normalizeClass(["cdx-typeahead-search__menu-message",e.menuMessageClass])},[t.createElementVNode("span",Vt,[t.renderSlot(e.$slots,"search-no-results-text")])],2)]),default:t.withCtx(({menuItem:c,active:h})=>[c.value===e.MenuFooterValue?(t.openBlock(),t.createElementBlock("a",{key:0,class:t.normalizeClass(["cdx-typeahead-search__search-footer",{"cdx-typeahead-search__search-footer__active":h}]),href:e.asSearchResult(c).url,onClickCapture:t.withModifiers(b=>e.onSearchFooterClick(e.asSearchResult(c)),["stop"])},[t.createVNode(l,{class:"cdx-typeahead-search__search-footer__icon",icon:e.articleIcon},null,8,["icon"]),t.createElementVNode("span",Tt,[t.renderSlot(e.$slots,"search-footer-text",{searchQuery:e.searchQuery},()=>[t.createElementVNode("strong",vt,t.toDisplayString(e.searchQuery),1)])])],42,Nt)):t.createCommentVNode("",!0)]),_:3},16,["id","expanded","show-pending","selected","menu-items","footer","search-query","show-no-results-slot","aria-label","onUpdate:selected","onMenuItemKeyboardNavigation"])]),_:3},16,["modelValue","button-label","aria-owns","aria-expanded","aria-activedescendant","onUpdate:modelValue","onFocus","onBlur","onKeydown"]),t.renderSlot(e.$slots,"default")],40,Ft)],6)}const Kt=D(xt,[["render",Lt]]);f.CdxTypeaheadSearch=Kt,Object.defineProperties(f,{__esModule:{value:!0},[Symbol.toStringTag]:{value:"Module"}})});
