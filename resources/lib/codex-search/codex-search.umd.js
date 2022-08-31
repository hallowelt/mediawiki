(function(m,t){typeof exports=="object"&&typeof module!="undefined"?t(exports,require("vue")):typeof define=="function"&&define.amd?define(["exports","vue"],t):(m=typeof globalThis!="undefined"?globalThis:m||self,t(m["codex-search"]={},m.Vue))})(this,function(m,t){"use strict";var Tt=Object.defineProperty,Rt=Object.defineProperties;var Lt=Object.getOwnPropertyDescriptors;var V=Object.getOwnPropertySymbols;var j=Object.prototype.hasOwnProperty,H=Object.prototype.propertyIsEnumerable;var K=(m,t,b)=>t in m?Tt(m,t,{enumerable:!0,configurable:!0,writable:!0,value:b}):m[t]=b,W=(m,t)=>{for(var b in t||(t={}))j.call(t,b)&&K(m,b,t[b]);if(V)for(var b of V(t))H.call(t,b)&&K(m,b,t[b]);return m},G=(m,t)=>Rt(m,Lt(t));var N=(m,t)=>{var b={};for(var _ in m)j.call(m,_)&&t.indexOf(_)<0&&(b[_]=m[_]);if(m!=null&&V)for(var _ of V(m))t.indexOf(_)<0&&H.call(m,_)&&(b[_]=m[_]);return b};const b='<path d="M12.43 14.34A5 5 0 0110 15a5 5 0 113.95-2L17 16.09V3a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2h10a2 2 0 001.45-.63z"/><circle cx="10" cy="10" r="3"/>',_='<path d="M10 0a10 10 0 1010 10A10 10 0 0010 0zm5.66 14.24-1.41 1.41L10 11.41l-4.24 4.25-1.42-1.42L8.59 10 4.34 5.76l1.42-1.42L10 8.59l4.24-4.24 1.41 1.41L11.41 10z"/>',X='<path d="M19 3H1v14h18zM3 14l3.5-4.5 2.5 3L12.5 8l4.5 6z"/><path d="M19 5H1V3h18zm0 12H1v-2h18z"/>',J='<path d="M12.2 13.6a7 7 0 111.4-1.4l5.4 5.4-1.4 1.4-5.4-5.4zM3 8a5 5 0 1010 0A5 5 0 103 8z"/>',Y=b,Z=_,ee=X,te=J;function ne(e,n,o){if(typeof e=="string"||"path"in e)return e;if("shouldFlip"in e)return e.ltr;if("rtl"in e)return o==="rtl"?e.rtl:e.ltr;const l=n in e.langCodeMap?e.langCodeMap[n]:e.default;return typeof l=="string"||"path"in l?l:l.ltr}function oe(e,n){if(typeof e=="string")return!1;if("langCodeMap"in e){const o=n in e.langCodeMap?e.langCodeMap[n]:e.default;if(typeof o=="string")return!1;e=o}if("shouldFlipExceptions"in e&&Array.isArray(e.shouldFlipExceptions)){const o=e.shouldFlipExceptions.indexOf(n);return o===void 0||o===-1}return"shouldFlip"in e?e.shouldFlip:!1}function le(e){const n=t.ref(null);return t.onMounted(()=>{const o=window.getComputedStyle(e.value).direction;n.value=o==="ltr"||o==="rtl"?o:null}),n}function ae(e){const n=t.ref("");return t.onMounted(()=>{let o=e.value;for(;o&&o.lang==="";)o=o.parentElement;n.value=o?o.lang:null}),n}const ue=t.defineComponent({name:"CdxIcon",props:{icon:{type:[String,Object],required:!0},iconLabel:{type:String,default:""},lang:{type:String,default:null},dir:{type:String,default:null}},emits:["click"],setup(e,{emit:n}){const o=t.ref(),l=le(o),i=ae(o),u=t.computed(()=>e.dir||l.value),s=t.computed(()=>e.lang||i.value),d=t.computed(()=>({"cdx-icon--flipped":u.value==="rtl"&&s.value!==null&&oe(e.icon,s.value)})),a=t.computed(()=>ne(e.icon,s.value||"",u.value||"ltr")),c=t.computed(()=>typeof a.value=="string"?a.value:""),h=t.computed(()=>typeof a.value!="string"?a.value.path:"");return{rootElement:o,rootClasses:d,iconSvg:c,iconPath:h,onClick:y=>{n("click",y)}}}}),vt="",S=(e,n)=>{const o=e.__vccOpts||e;for(const[l,i]of n)o[l]=i;return o},se=["aria-hidden"],re={key:0},ie=["innerHTML"],de=["d"];function ce(e,n,o,l,i,u){return t.openBlock(),t.createElementBlock("span",{ref:"rootElement",class:t.normalizeClass(["cdx-icon",e.rootClasses]),onClick:n[0]||(n[0]=(...s)=>e.onClick&&e.onClick(...s))},[(t.openBlock(),t.createElementBlock("svg",{xmlns:"http://www.w3.org/2000/svg",width:"20",height:"20",viewBox:"0 0 20 20","aria-hidden":!e.iconLabel},[e.iconLabel?(t.openBlock(),t.createElementBlock("title",re,t.toDisplayString(e.iconLabel),1)):t.createCommentVNode("",!0),e.iconSvg?(t.openBlock(),t.createElementBlock("g",{key:1,fill:"currentColor",innerHTML:e.iconSvg},null,8,ie)):(t.openBlock(),t.createElementBlock("path",{key:2,d:e.iconPath,fill:"currentColor"},null,8,de))],8,se))],2)}const M=S(ue,[["render",ce]]),pe=t.defineComponent({name:"CdxThumbnail",components:{CdxIcon:M},props:{thumbnail:{type:[Object,null],default:null},placeholderIcon:{type:[String,Object],default:ee}},setup:e=>{const n=t.ref(!1),o=t.ref({}),l=i=>{const u=i.replace(/([\\"\n])/g,"\\$1"),s=new Image;s.onload=()=>{o.value={backgroundImage:`url("${u}")`},n.value=!0},s.onerror=()=>{n.value=!1},s.src=u};return t.onMounted(()=>{var i;(i=e.thumbnail)!=null&&i.url&&l(e.thumbnail.url)}),{thumbnailStyle:o,thumbnailLoaded:n}}}),zt="",he={class:"cdx-thumbnail"},me={key:0,class:"cdx-thumbnail__placeholder"};function fe(e,n,o,l,i,u){const s=t.resolveComponent("cdx-icon");return t.openBlock(),t.createElementBlock("span",he,[e.thumbnailLoaded?t.createCommentVNode("",!0):(t.openBlock(),t.createElementBlock("span",me,[t.createVNode(s,{icon:e.placeholderIcon,class:"cdx-thumbnail__placeholder__icon"},null,8,["icon"])])),t.createVNode(t.Transition,{name:"cdx-thumbnail__image"},{default:t.withCtx(()=>[e.thumbnailLoaded?(t.openBlock(),t.createElementBlock("span",{key:0,style:t.normalizeStyle(e.thumbnailStyle),class:"cdx-thumbnail__image"},null,4)):t.createCommentVNode("",!0)]),_:1})])}const ge=S(pe,[["render",fe]]);function ye(e){return e.replace(/([\\{}()|.?*+\-^$[\]])/g,"\\$1")}const Ce="[\u0300-\u036F\u0483-\u0489\u0591-\u05BD\u05BF\u05C1\u05C2\u05C4\u05C5\u05C7\u0610-\u061A\u064B-\u065F\u0670\u06D6-\u06DC\u06DF-\u06E4\u06E7\u06E8\u06EA-\u06ED\u0711\u0730-\u074A\u07A6-\u07B0\u07EB-\u07F3\u07FD\u0816-\u0819\u081B-\u0823\u0825-\u0827\u0829-\u082D\u0859-\u085B\u08D3-\u08E1\u08E3-\u0903\u093A-\u093C\u093E-\u094F\u0951-\u0957\u0962\u0963\u0981-\u0983\u09BC\u09BE-\u09C4\u09C7\u09C8\u09CB-\u09CD\u09D7\u09E2\u09E3\u09FE\u0A01-\u0A03\u0A3C\u0A3E-\u0A42\u0A47\u0A48\u0A4B-\u0A4D\u0A51\u0A70\u0A71\u0A75\u0A81-\u0A83\u0ABC\u0ABE-\u0AC5\u0AC7-\u0AC9\u0ACB-\u0ACD\u0AE2\u0AE3\u0AFA-\u0AFF\u0B01-\u0B03\u0B3C\u0B3E-\u0B44\u0B47\u0B48\u0B4B-\u0B4D\u0B56\u0B57\u0B62\u0B63\u0B82\u0BBE-\u0BC2\u0BC6-\u0BC8\u0BCA-\u0BCD\u0BD7\u0C00-\u0C04\u0C3E-\u0C44\u0C46-\u0C48\u0C4A-\u0C4D\u0C55\u0C56\u0C62\u0C63\u0C81-\u0C83\u0CBC\u0CBE-\u0CC4\u0CC6-\u0CC8\u0CCA-\u0CCD\u0CD5\u0CD6\u0CE2\u0CE3\u0D00-\u0D03\u0D3B\u0D3C\u0D3E-\u0D44\u0D46-\u0D48\u0D4A-\u0D4D\u0D57\u0D62\u0D63\u0D82\u0D83\u0DCA\u0DCF-\u0DD4\u0DD6\u0DD8-\u0DDF\u0DF2\u0DF3\u0E31\u0E34-\u0E3A\u0E47-\u0E4E\u0EB1\u0EB4-\u0EB9\u0EBB\u0EBC\u0EC8-\u0ECD\u0F18\u0F19\u0F35\u0F37\u0F39\u0F3E\u0F3F\u0F71-\u0F84\u0F86\u0F87\u0F8D-\u0F97\u0F99-\u0FBC\u0FC6\u102B-\u103E\u1056-\u1059\u105E-\u1060\u1062-\u1064\u1067-\u106D\u1071-\u1074\u1082-\u108D\u108F\u109A-\u109D\u135D-\u135F\u1712-\u1714\u1732-\u1734\u1752\u1753\u1772\u1773\u17B4-\u17D3\u17DD\u180B-\u180D\u1885\u1886\u18A9\u1920-\u192B\u1930-\u193B\u1A17-\u1A1B\u1A55-\u1A5E\u1A60-\u1A7C\u1A7F\u1AB0-\u1ABE\u1B00-\u1B04\u1B34-\u1B44\u1B6B-\u1B73\u1B80-\u1B82\u1BA1-\u1BAD\u1BE6-\u1BF3\u1C24-\u1C37\u1CD0-\u1CD2\u1CD4-\u1CE8\u1CED\u1CF2-\u1CF4\u1CF7-\u1CF9\u1DC0-\u1DF9\u1DFB-\u1DFF\u20D0-\u20F0\u2CEF-\u2CF1\u2D7F\u2DE0-\u2DFF\u302A-\u302F\u3099\u309A\uA66F-\uA672\uA674-\uA67D\uA69E\uA69F\uA6F0\uA6F1\uA802\uA806\uA80B\uA823-\uA827\uA880\uA881\uA8B4-\uA8C5\uA8E0-\uA8F1\uA8FF\uA926-\uA92D\uA947-\uA953\uA980-\uA983\uA9B3-\uA9C0\uA9E5\uAA29-\uAA36\uAA43\uAA4C\uAA4D\uAA7B-\uAA7D\uAAB0\uAAB2-\uAAB4\uAAB7\uAAB8\uAABE\uAABF\uAAC1\uAAEB-\uAAEF\uAAF5\uAAF6\uABE3-\uABEA\uABEC\uABED\uFB1E\uFE00-\uFE0F\uFE20-\uFE2F]";function be(e,n){if(!e)return[n,"",""];const o=ye(e),l=new RegExp(o+Ce+"*","i").exec(n);if(!l||l.index===void 0)return[n,"",""];const i=l.index,u=i+l[0].length,s=n.slice(i,u),d=n.slice(0,i),a=n.slice(u,n.length);return[d,s,a]}const Be=t.defineComponent({name:"CdxSearchResultTitle",props:{title:{type:String,required:!0},searchQuery:{type:String,default:""}},setup:e=>({titleChunks:t.computed(()=>be(e.searchQuery,String(e.title)))})}),qt="",Ae={class:"cdx-search-result-title"},ke={class:"cdx-search-result-title__match"};function _e(e,n,o,l,i,u){return t.openBlock(),t.createElementBlock("span",Ae,[t.createElementVNode("bdi",null,[t.createTextVNode(t.toDisplayString(e.titleChunks[0]),1),t.createElementVNode("span",ke,t.toDisplayString(e.titleChunks[1]),1),t.createTextVNode(t.toDisplayString(e.titleChunks[2]),1)])])}const Ee=S(Be,[["render",_e]]),Se=t.defineComponent({name:"CdxMenuItem",components:{CdxIcon:M,CdxThumbnail:ge,CdxSearchResultTitle:Ee},props:{id:{type:String,required:!0},value:{type:[String,Number],required:!0},disabled:{type:Boolean,default:!1},selected:{type:Boolean,default:!1},active:{type:Boolean,default:!1},highlighted:{type:Boolean,default:!1},label:{type:String,default:""},match:{type:String,default:""},url:{type:String,default:""},icon:{type:[String,Object],default:""},showThumbnail:{type:Boolean,default:!1},thumbnail:{type:[Object,null],default:null},description:{type:[String,null],default:""},searchQuery:{type:String,default:""},boldLabel:{type:Boolean,default:!1},hideDescriptionOverflow:{type:Boolean,default:!1},language:{type:Object,default:()=>({})}},emits:["change"],setup:(e,{emit:n})=>{const o=()=>{n("change","highlighted",!0)},l=()=>{n("change","highlighted",!1)},i=h=>{h.button===0&&n("change","active",!0)},u=()=>{n("change","selected",!0)},s=t.computed(()=>e.searchQuery.length>0),d=t.computed(()=>({"cdx-menu-item--selected":e.selected,"cdx-menu-item--active":e.active&&e.highlighted,"cdx-menu-item--highlighted":e.highlighted,"cdx-menu-item--enabled":!e.disabled,"cdx-menu-item--disabled":e.disabled,"cdx-menu-item--highlight-query":s.value,"cdx-menu-item--bold-label":e.boldLabel,"cdx-menu-item--has-description":!!e.description,"cdx-menu-item--hide-description-overflow":e.hideDescriptionOverflow})),a=t.computed(()=>e.url?"a":"span"),c=t.computed(()=>e.label||String(e.value));return{onMouseEnter:o,onMouseLeave:l,onMouseDown:i,onClick:u,highlightQuery:s,rootClasses:d,contentTag:a,title:c}}}),Ot="",$e=["id","aria-disabled","aria-selected"],De={class:"cdx-menu-item__text"},Fe=["lang"],xe=t.createTextVNode(t.toDisplayString(" ")+" "),we=["lang"],Me=["lang"];function Ie(e,n,o,l,i,u){const s=t.resolveComponent("cdx-thumbnail"),d=t.resolveComponent("cdx-icon"),a=t.resolveComponent("cdx-search-result-title");return t.openBlock(),t.createElementBlock("li",{id:e.id,role:"option",class:t.normalizeClass(["cdx-menu-item",e.rootClasses]),"aria-disabled":e.disabled,"aria-selected":e.selected,onMouseenter:n[0]||(n[0]=(...c)=>e.onMouseEnter&&e.onMouseEnter(...c)),onMouseleave:n[1]||(n[1]=(...c)=>e.onMouseLeave&&e.onMouseLeave(...c)),onMousedown:n[2]||(n[2]=t.withModifiers((...c)=>e.onMouseDown&&e.onMouseDown(...c),["prevent"])),onClick:n[3]||(n[3]=(...c)=>e.onClick&&e.onClick(...c))},[t.renderSlot(e.$slots,"default",{},()=>[(t.openBlock(),t.createBlock(t.resolveDynamicComponent(e.contentTag),{href:e.url?e.url:void 0,class:"cdx-menu-item__content"},{default:t.withCtx(()=>{var c,h,g,y,E;return[e.showThumbnail?(t.openBlock(),t.createBlock(s,{key:0,thumbnail:e.thumbnail,class:"cdx-menu-item__thumbnail"},null,8,["thumbnail"])):e.icon?(t.openBlock(),t.createBlock(d,{key:1,icon:e.icon,class:"cdx-menu-item__icon"},null,8,["icon"])):t.createCommentVNode("",!0),t.createElementVNode("span",De,[e.highlightQuery?(t.openBlock(),t.createBlock(a,{key:0,title:e.title,"search-query":e.searchQuery,lang:(c=e.language)==null?void 0:c.label},null,8,["title","search-query","lang"])):(t.openBlock(),t.createElementBlock("span",{key:1,class:"cdx-menu-item__text__label",lang:(h=e.language)==null?void 0:h.label},[t.createElementVNode("bdi",null,t.toDisplayString(e.title),1)],8,Fe)),e.match?(t.openBlock(),t.createElementBlock(t.Fragment,{key:2},[xe,e.highlightQuery?(t.openBlock(),t.createBlock(a,{key:0,title:e.match,"search-query":e.searchQuery,lang:(g=e.language)==null?void 0:g.match},null,8,["title","search-query","lang"])):(t.openBlock(),t.createElementBlock("span",{key:1,class:"cdx-menu-item__text__match",lang:(y=e.language)==null?void 0:y.match},[t.createElementVNode("bdi",null,t.toDisplayString(e.match),1)],8,we))],64)):t.createCommentVNode("",!0),e.description?(t.openBlock(),t.createElementBlock("span",{key:3,class:"cdx-menu-item__text__description",lang:(E=e.language)==null?void 0:E.description},[t.createElementVNode("bdi",null,t.toDisplayString(e.description),1)],8,Me)):t.createCommentVNode("",!0)])]}),_:1},8,["href"]))])],42,$e)}const Ve=S(Se,[["render",Ie]]),Ne=t.defineComponent({name:"CdxProgressBar",props:{inline:{type:Boolean,default:!1}},setup(e){return{rootClasses:t.computed(()=>({"cdx-progress-bar--block":!e.inline,"cdx-progress-bar--inline":e.inline}))}}}),Pt="",Te=[t.createElementVNode("div",{class:"cdx-progress-bar__bar"},null,-1)];function Re(e,n,o,l,i,u){return t.openBlock(),t.createElementBlock("div",{class:t.normalizeClass(["cdx-progress-bar",e.rootClasses]),role:"progressbar","aria-valuemin":"0","aria-valuemax":"100"},Te,2)}const Le=S(Ne,[["render",Re]]),T="cdx",ve=["default","progressive","destructive"],ze=["normal","primary","quiet"],qe=["text","search"],Oe=120,Pe=500,w="cdx-menu-footer-item";let R=0;function q(e){const n=t.getCurrentInstance(),o=(n==null?void 0:n.props.id)||(n==null?void 0:n.attrs.id);return e?`${T}-${e}-${R++}`:o?`${T}-${o}-${R++}`:`${T}-${R++}`}const Qe=t.defineComponent({name:"CdxMenu",components:{CdxMenuItem:Ve,CdxProgressBar:Le},props:{menuItems:{type:Array,required:!0},selected:{type:[String,Number,null],required:!0},expanded:{type:Boolean,required:!0},showPending:{type:Boolean,default:!1},showThumbnail:{type:Boolean,default:!1},boldLabel:{type:Boolean,default:!1},hideDescriptionOverflow:{type:Boolean,default:!1},searchQuery:{type:String,default:""},showNoResultsSlot:{type:Boolean,default:null}},emits:["update:selected","update:expanded","menu-item-click","menu-item-keyboard-navigation"],expose:["clearActive","getHighlightedMenuItem","delegateKeyNavigation"],setup(e,{emit:n,slots:o}){const l=t.computed(()=>e.menuItems.map(r=>G(W({},r),{id:q("menu-item")}))),i=t.computed(()=>o["no-results"]?e.showNoResultsSlot!==null?e.showNoResultsSlot:l.value.length===0:!1),u=t.ref(null),s=t.ref(null);function d(){return l.value.find(r=>r.value===e.selected)}function a(r,f){var C;if(!(f&&f.disabled))switch(r){case"selected":n("update:selected",(C=f==null?void 0:f.value)!=null?C:null),n("update:expanded",!1),s.value=null;break;case"highlighted":u.value=f||null;break;case"active":s.value=f||null;break}}const c=t.computed(()=>{if(u.value!==null)return l.value.findIndex(r=>r.value===u.value.value)});function h(r){!r||(a("highlighted",r),n("menu-item-keyboard-navigation",r))}function g(r){var A;const f=k=>{for(let D=k-1;D>=0;D--)if(!l.value[D].disabled)return l.value[D]};r=r||l.value.length;const C=(A=f(r))!=null?A:f(l.value.length);h(C)}function y(r){const f=A=>l.value.find((k,D)=>!k.disabled&&D>A);r=r!=null?r:-1;const C=f(r)||f(-1);h(C)}function E(r,f=!0){function C(){n("update:expanded",!0),a("highlighted",d())}function A(){f&&(r.preventDefault(),r.stopPropagation())}switch(r.key){case"Enter":case" ":return A(),e.expanded?(u.value&&n("update:selected",u.value.value),n("update:expanded",!1)):C(),!0;case"Tab":return e.expanded&&(u.value&&n("update:selected",u.value.value),n("update:expanded",!1)),!0;case"ArrowUp":return A(),e.expanded?(u.value===null&&a("highlighted",d()),g(c.value)):C(),!0;case"ArrowDown":return A(),e.expanded?(u.value===null&&a("highlighted",d()),y(c.value)):C(),!0;case"Home":return A(),e.expanded?(u.value===null&&a("highlighted",d()),y()):C(),!0;case"End":return A(),e.expanded?(u.value===null&&a("highlighted",d()),g()):C(),!0;case"Escape":return A(),n("update:expanded",!1),!0;default:return!1}}function $(){a("active")}return t.onMounted(()=>{document.addEventListener("mouseup",$)}),t.onUnmounted(()=>{document.removeEventListener("mouseup",$)}),t.watch(t.toRef(e,"expanded"),r=>{const f=d();!r&&u.value&&f===void 0&&a("highlighted"),r&&f!==void 0&&a("highlighted",f)}),{computedMenuItems:l,computedShowNoResultsSlot:i,highlightedMenuItem:u,activeMenuItem:s,handleMenuItemChange:a,handleKeyNavigation:E}},methods:{getHighlightedMenuItem(){return this.highlightedMenuItem},clearActive(){this.handleMenuItemChange("active")},delegateKeyNavigation(e,n=!0){return this.handleKeyNavigation(e,n)}}}),Ut="",Ue={class:"cdx-menu",role:"listbox","aria-multiselectable":"false"},Ke={key:0,class:"cdx-menu__pending cdx-menu-item"},je={key:1,class:"cdx-menu__no-results cdx-menu-item"};function He(e,n,o,l,i,u){const s=t.resolveComponent("cdx-menu-item"),d=t.resolveComponent("cdx-progress-bar");return t.withDirectives((t.openBlock(),t.createElementBlock("ul",Ue,[e.showPending&&e.computedMenuItems.length===0&&e.$slots.pending?(t.openBlock(),t.createElementBlock("li",Ke,[t.renderSlot(e.$slots,"pending")])):t.createCommentVNode("",!0),e.computedShowNoResultsSlot?(t.openBlock(),t.createElementBlock("li",je,[t.renderSlot(e.$slots,"no-results")])):t.createCommentVNode("",!0),(t.openBlock(!0),t.createElementBlock(t.Fragment,null,t.renderList(e.computedMenuItems,a=>{var c,h;return t.openBlock(),t.createBlock(s,t.mergeProps({key:a.value},a,{selected:a.value===e.selected,active:a.value===((c=e.activeMenuItem)==null?void 0:c.value),highlighted:a.value===((h=e.highlightedMenuItem)==null?void 0:h.value),"show-thumbnail":e.showThumbnail,"bold-label":e.boldLabel,"hide-description-overflow":e.hideDescriptionOverflow,"search-query":e.searchQuery,onChange:(g,y)=>e.handleMenuItemChange(g,y&&a),onClick:g=>e.$emit("menu-item-click",a)}),{default:t.withCtx(()=>{var g,y;return[t.renderSlot(e.$slots,"default",{menuItem:a,active:a.value===((g=e.activeMenuItem)==null?void 0:g.value)&&a.value===((y=e.highlightedMenuItem)==null?void 0:y.value)})]}),_:2},1040,["selected","active","highlighted","show-thumbnail","bold-label","hide-description-overflow","search-query","onChange","onClick"])}),128)),e.showPending?(t.openBlock(),t.createBlock(d,{key:2,class:"cdx-menu__progress-bar",inline:!0})):t.createCommentVNode("",!0)],512)),[[t.vShow,e.expanded]])}const We=S(Qe,[["render",He]]);function L(e){return n=>typeof n=="string"&&e.indexOf(n)!==-1}const Ge=L(ze),Xe=L(ve),Je=e=>{!e["aria-label"]&&!e["aria-hidden"]&&t.warn(`icon-only buttons require one of the following attribute: aria-label or aria-hidden.
		See documentation on https://doc.wikimedia.org/codex/main/components/button.html#default-icon-only`)};function v(e){const n=[];for(const o of e)typeof o=="string"&&o.trim()!==""?n.push(o):Array.isArray(o)?n.push(...v(o)):typeof o=="object"&&o&&(typeof o.type=="string"||typeof o.type=="object"?n.push(o):o.type!==t.Comment&&(typeof o.children=="string"&&o.children.trim()!==""?n.push(o.children):Array.isArray(o.children)&&n.push(...v(o.children))));return n}const Ye=(e,n)=>{if(!e)return!1;const o=v(e);if(o.length!==1)return!1;const l=o[0],i=typeof l=="object"&&typeof l.type=="object"&&"name"in l.type&&l.type.name===M.name,u=typeof l=="object"&&l.type==="svg";return i||u?(Je(n),!0):!1},Ze=t.defineComponent({name:"CdxButton",props:{action:{type:String,default:"default",validator:Xe},type:{type:String,default:"normal",validator:Ge}},emits:["click"],setup(e,{emit:n,slots:o,attrs:l}){return{rootClasses:t.computed(()=>{var s;return{[`cdx-button--action-${e.action}`]:!0,[`cdx-button--type-${e.type}`]:!0,"cdx-button--framed":e.type!=="quiet","cdx-button--icon-only":Ye((s=o.default)==null?void 0:s.call(o),l)}}),onClick:s=>{n("click",s)}}}}),Kt="";function et(e,n,o,l,i,u){return t.openBlock(),t.createElementBlock("button",{class:t.normalizeClass(["cdx-button",e.rootClasses]),onClick:n[0]||(n[0]=(...s)=>e.onClick&&e.onClick(...s))},[t.renderSlot(e.$slots,"default")],2)}const tt=S(Ze,[["render",et]]);function O(e,n,o){return t.computed({get:()=>e.value,set:l=>n(o||"update:modelValue",l)})}function z(e,n=t.computed(()=>({}))){const o=t.computed(()=>{const u=N(n.value,[]);return e.class&&e.class.split(" ").forEach(d=>{u[d]=!0}),u}),l=t.computed(()=>{if("style"in e)return e.style}),i=t.computed(()=>{const a=e,{class:u,style:s}=a;return N(a,["class","style"])});return{rootClasses:o,rootStyle:l,otherAttrs:i}}const nt=L(qe),ot=t.defineComponent({name:"CdxTextInput",components:{CdxIcon:M},inheritAttrs:!1,expose:["focus"],props:{modelValue:{type:[String,Number],default:""},inputType:{type:String,default:"text",validator:nt},disabled:{type:Boolean,default:!1},startIcon:{type:[String,Object],default:void 0},endIcon:{type:[String,Object],default:void 0},clearable:{type:Boolean,default:!1}},emits:["update:modelValue","input","change","focus","blur"],setup(e,{emit:n,attrs:o}){const l=O(t.toRef(e,"modelValue"),n),i=t.computed(()=>e.clearable&&!!l.value&&!e.disabled),u=t.computed(()=>({"cdx-text-input--has-start-icon":!!e.startIcon,"cdx-text-input--has-end-icon":!!e.endIcon,"cdx-text-input--clearable":i.value})),{rootClasses:s,rootStyle:d,otherAttrs:a}=z(o,u),c=t.computed(()=>({"cdx-text-input__input--has-value":!!l.value}));return{wrappedModel:l,isClearable:i,rootClasses:s,rootStyle:d,otherAttrs:a,inputClasses:c,onClear:()=>{l.value=""},onInput:r=>{n("input",r)},onChange:r=>{n("change",r)},onFocus:r=>{n("focus",r)},onBlur:r=>{n("blur",r)},cdxIconClear:Z}},methods:{focus(){this.$refs.input.focus()}}}),jt="",lt=["type","disabled"];function at(e,n,o,l,i,u){const s=t.resolveComponent("cdx-icon");return t.openBlock(),t.createElementBlock("div",{class:t.normalizeClass(["cdx-text-input",e.rootClasses]),style:t.normalizeStyle(e.rootStyle)},[t.withDirectives(t.createElementVNode("input",t.mergeProps({ref:"input","onUpdate:modelValue":n[0]||(n[0]=d=>e.wrappedModel=d),class:["cdx-text-input__input",e.inputClasses]},e.otherAttrs,{type:e.inputType,disabled:e.disabled,onInput:n[1]||(n[1]=(...d)=>e.onInput&&e.onInput(...d)),onChange:n[2]||(n[2]=(...d)=>e.onChange&&e.onChange(...d)),onFocus:n[3]||(n[3]=(...d)=>e.onFocus&&e.onFocus(...d)),onBlur:n[4]||(n[4]=(...d)=>e.onBlur&&e.onBlur(...d))}),null,16,lt),[[t.vModelDynamic,e.wrappedModel]]),e.startIcon?(t.openBlock(),t.createBlock(s,{key:0,icon:e.startIcon,class:"cdx-text-input__icon cdx-text-input__start-icon"},null,8,["icon"])):t.createCommentVNode("",!0),e.endIcon?(t.openBlock(),t.createBlock(s,{key:1,icon:e.endIcon,class:"cdx-text-input__icon cdx-text-input__end-icon"},null,8,["icon"])):t.createCommentVNode("",!0),e.isClearable?(t.openBlock(),t.createBlock(s,{key:2,icon:e.cdxIconClear,class:"cdx-text-input__icon cdx-text-input__clear-icon",onMousedown:n[5]||(n[5]=t.withModifiers(()=>{},["prevent"])),onClick:e.onClear},null,8,["icon","onClick"])):t.createCommentVNode("",!0)],6)}const ut=S(ot,[["render",at]]),st=t.defineComponent({name:"CdxSearchInput",components:{CdxButton:tt,CdxTextInput:ut},inheritAttrs:!1,props:{modelValue:{type:[String,Number],default:""},buttonLabel:{type:String,default:""}},emits:["update:modelValue","submit-click"],setup(e,{emit:n,attrs:o}){const l=O(t.toRef(e,"modelValue"),n),i=t.computed(()=>({"cdx-search-input--has-end-button":!!e.buttonLabel})),{rootClasses:u,rootStyle:s,otherAttrs:d}=z(o,i);return{wrappedModel:l,rootClasses:u,rootStyle:s,otherAttrs:d,handleSubmit:()=>{n("submit-click",l.value)},searchIcon:te}},methods:{focus(){this.$refs.textInput.focus()}}}),Ht="",rt={class:"cdx-search-input__input-wrapper"};function it(e,n,o,l,i,u){const s=t.resolveComponent("cdx-text-input"),d=t.resolveComponent("cdx-button");return t.openBlock(),t.createElementBlock("div",{class:t.normalizeClass(["cdx-search-input",e.rootClasses]),style:t.normalizeStyle(e.rootStyle)},[t.createElementVNode("div",rt,[t.createVNode(s,t.mergeProps({ref:"textInput",modelValue:e.wrappedModel,"onUpdate:modelValue":n[0]||(n[0]=a=>e.wrappedModel=a),class:"cdx-search-input__text-input","input-type":"search","start-icon":e.searchIcon},e.otherAttrs,{onKeydown:t.withKeys(e.handleSubmit,["enter"])}),null,16,["modelValue","start-icon","onKeydown"]),t.renderSlot(e.$slots,"default")]),e.buttonLabel?(t.openBlock(),t.createBlock(d,{key:0,class:"cdx-search-input__end-button",onClick:e.handleSubmit},{default:t.withCtx(()=>[t.createTextVNode(t.toDisplayString(e.buttonLabel),1)]),_:1},8,["onClick"])):t.createCommentVNode("",!0)],6)}const dt=S(st,[["render",it]]),ct=t.defineComponent({name:"CdxTypeaheadSearch",components:{CdxIcon:M,CdxMenu:We,CdxSearchInput:dt},inheritAttrs:!1,props:{id:{type:String,required:!0},formAction:{type:String,required:!0},searchResultsLabel:{type:String,required:!0},searchResults:{type:Array,required:!0},buttonLabel:{type:String,default:""},initialInputValue:{type:String,default:""},searchFooterUrl:{type:String,default:""},debounceInterval:{type:Number,default:Oe},highlightQuery:{type:Boolean,default:!1},showThumbnail:{type:Boolean,default:!1},autoExpandWidth:{type:Boolean,default:!1}},emits:["input","search-result-click","submit"],setup(e,{attrs:n,emit:o,slots:l}){const{searchResults:i,searchFooterUrl:u,debounceInterval:s}=t.toRefs(e),d=t.ref(),a=t.ref(),c=q("typeahead-search-menu"),h=t.ref(!1),g=t.ref(!1),y=t.ref(!1),E=t.ref(!1),$=t.ref(e.initialInputValue),r=t.ref(""),f=t.computed(()=>{var p,B;return(B=(p=a.value)==null?void 0:p.getHighlightedMenuItem())==null?void 0:B.id}),C=t.ref(null),A=t.computed(()=>({"cdx-typeahead-search__menu-message--with-thumbnail":e.showThumbnail})),k=t.computed(()=>e.searchResults.find(p=>p.value===C.value)),D=t.computed(()=>u.value?i.value.concat([{value:w,url:u.value}]):i.value),Bt=t.computed(()=>({"cdx-typeahead-search--active":E.value,"cdx-typeahead-search--show-thumbnail":e.showThumbnail,"cdx-typeahead-search--expanded":h.value,"cdx-typeahead-search--auto-expand-width":e.showThumbnail&&e.autoExpandWidth})),{rootClasses:At,rootStyle:kt,otherAttrs:_t}=z(n,Bt);function Et(p){return p}const St=t.computed(()=>({showThumbnail:e.showThumbnail,boldLabel:!0,hideDescriptionOverflow:!0}));let I,F;function P(p,B=!1){k.value&&k.value.label!==p&&k.value.value!==p&&(C.value=null),F!==void 0&&(clearTimeout(F),F=void 0),p===""?h.value=!1:(g.value=!0,l["search-results-pending"]&&(F=setTimeout(()=>{E.value&&(h.value=!0),y.value=!0},Pe))),I!==void 0&&(clearTimeout(I),I=void 0);const x=()=>{o("input",p)};B?x():I=setTimeout(()=>{x()},s.value)}function $t(p){if(p===w){C.value=null,$.value=r.value;return}C.value=p,p!==null&&($.value=k.value?k.value.label||String(k.value.value):"")}function Dt(){E.value=!0,(r.value||y.value)&&(h.value=!0)}function Ft(){E.value=!1,h.value=!1}function Q(p){const U=p,{id:B}=U,x=N(U,["id"]),Vt={searchResult:x.value!==w?x:null,index:D.value.findIndex(Nt=>Nt.value===p.value),numberOfResults:i.value.length};o("search-result-click",Vt)}function xt(p){if(p.value===w){$.value=r.value;return}$.value=p.value?p.label||String(p.value):""}function wt(p){var B;h.value=!1,(B=a.value)==null||B.clearActive(),Q(p)}function Mt(){let p=null,B=-1;k.value&&(p=k.value,B=e.searchResults.indexOf(k.value));const x={searchResult:p,index:B,numberOfResults:i.value.length};o("submit",x)}function It(p){if(!a.value||!r.value||p.key===" "&&h.value)return;const B=a.value.getHighlightedMenuItem();switch(p.key){case"Enter":B&&(B.value===w?window.location.assign(u.value):a.value.delegateKeyNavigation(p,!1)),h.value=!1;break;case"Tab":h.value=!1;break;default:a.value.delegateKeyNavigation(p);break}}return t.onMounted(()=>{e.initialInputValue&&P(e.initialInputValue,!0)}),t.watch(t.toRef(e,"searchResults"),()=>{r.value=$.value.trim(),E.value&&g.value&&r.value.length>0&&(h.value=!0),F!==void 0&&(clearTimeout(F),F=void 0),g.value=!1,y.value=!1}),{form:d,menu:a,menuId:c,highlightedId:f,selection:C,menuMessageClass:A,searchResultsWithFooter:D,asSearchResult:Et,inputValue:$,searchQuery:r,expanded:h,showPending:y,rootClasses:At,rootStyle:kt,otherAttrs:_t,menuConfig:St,onUpdateInputValue:P,onUpdateMenuSelection:$t,onFocus:Dt,onBlur:Ft,onSearchResultClick:Q,onSearchResultKeyboardNavigation:xt,onSearchFooterClick:wt,onSubmit:Mt,onKeydown:It,MenuFooterValue:w,articleIcon:Y}},methods:{focus(){this.$refs.searchInput.focus()}}}),Wt="",pt=["id","action"],ht={class:"cdx-typeahead-search__menu-message__text"},mt={class:"cdx-typeahead-search__menu-message__text"},ft=["href","onClickCapture"],gt={class:"cdx-typeahead-search__search-footer__text"},yt={class:"cdx-typeahead-search__search-footer__query"};function Ct(e,n,o,l,i,u){const s=t.resolveComponent("cdx-icon"),d=t.resolveComponent("cdx-menu"),a=t.resolveComponent("cdx-search-input");return t.openBlock(),t.createElementBlock("div",{class:t.normalizeClass(["cdx-typeahead-search",e.rootClasses]),style:t.normalizeStyle(e.rootStyle)},[t.createElementVNode("form",{id:e.id,ref:"form",class:"cdx-typeahead-search__form",action:e.formAction,onSubmit:n[3]||(n[3]=(...c)=>e.onSubmit&&e.onSubmit(...c))},[t.createVNode(a,t.mergeProps({ref:"searchInput",modelValue:e.inputValue,"onUpdate:modelValue":n[2]||(n[2]=c=>e.inputValue=c),"button-label":e.buttonLabel},e.otherAttrs,{class:"cdx-typeahead-search__input",name:"search",role:"combobox",autocomplete:"off","aria-autocomplete":"list","aria-owns":e.menuId,"aria-expanded":e.expanded,"aria-activedescendant":e.highlightedId,autocapitalize:"off","onUpdate:modelValue":e.onUpdateInputValue,onFocus:e.onFocus,onBlur:e.onBlur,onKeydown:e.onKeydown}),{default:t.withCtx(()=>[t.createVNode(d,t.mergeProps({id:e.menuId,ref:"menu",expanded:e.expanded,"onUpdate:expanded":n[0]||(n[0]=c=>e.expanded=c),"show-pending":e.showPending,selected:e.selection,"menu-items":e.searchResultsWithFooter,"search-query":e.highlightQuery?e.searchQuery:"","show-no-results-slot":e.searchQuery.length>0&&e.searchResults.length===0&&e.$slots["search-no-results-text"]&&e.$slots["search-no-results-text"]().length>0},e.menuConfig,{"aria-label":e.searchResultsLabel,"onUpdate:selected":e.onUpdateMenuSelection,onMenuItemClick:n[1]||(n[1]=c=>e.onSearchResultClick(e.asSearchResult(c))),onMenuItemKeyboardNavigation:e.onSearchResultKeyboardNavigation}),{pending:t.withCtx(()=>[t.createElementVNode("div",{class:t.normalizeClass(["cdx-typeahead-search__menu-message",e.menuMessageClass])},[t.createElementVNode("span",ht,[t.renderSlot(e.$slots,"search-results-pending")])],2)]),"no-results":t.withCtx(()=>[t.createElementVNode("div",{class:t.normalizeClass(["cdx-typeahead-search__menu-message",e.menuMessageClass])},[t.createElementVNode("span",mt,[t.renderSlot(e.$slots,"search-no-results-text")])],2)]),default:t.withCtx(({menuItem:c,active:h})=>[c.value===e.MenuFooterValue?(t.openBlock(),t.createElementBlock("a",{key:0,class:t.normalizeClass(["cdx-typeahead-search__search-footer",{"cdx-typeahead-search__search-footer__active":h}]),href:e.asSearchResult(c).url,onClickCapture:t.withModifiers(g=>e.onSearchFooterClick(e.asSearchResult(c)),["stop"])},[t.createVNode(s,{class:"cdx-typeahead-search__search-footer__icon",icon:e.articleIcon},null,8,["icon"]),t.createElementVNode("span",gt,[t.renderSlot(e.$slots,"search-footer-text",{searchQuery:e.searchQuery},()=>[t.createElementVNode("strong",yt,t.toDisplayString(e.searchQuery),1)])])],42,ft)):t.createCommentVNode("",!0)]),_:3},16,["id","expanded","show-pending","selected","menu-items","search-query","show-no-results-slot","aria-label","onUpdate:selected","onMenuItemKeyboardNavigation"])]),_:3},16,["modelValue","button-label","aria-owns","aria-expanded","aria-activedescendant","onUpdate:modelValue","onFocus","onBlur","onKeydown"]),t.renderSlot(e.$slots,"default")],40,pt)],6)}const bt=S(ct,[["render",Ct]]);m.CdxTypeaheadSearch=bt,Object.defineProperties(m,{__esModule:{value:!0},[Symbol.toStringTag]:{value:"Module"}})});
