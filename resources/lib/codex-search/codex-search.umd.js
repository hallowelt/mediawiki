(function(g,t){typeof exports=="object"&&typeof module!="undefined"?t(exports,require("vue")):typeof define=="function"&&define.amd?define(["exports","vue"],t):(g=typeof globalThis!="undefined"?globalThis:g||self,t(g["codex-search"]={},g.Vue))})(this,function(g,t){"use strict";var Ft=Object.defineProperty,Ot=Object.defineProperties;var qt=Object.getOwnPropertyDescriptors;var j=Object.getOwnPropertySymbols;var ue=Object.prototype.hasOwnProperty,he=Object.prototype.propertyIsEnumerable;var ce=(g,t,C)=>t in g?Ft(g,t,{enumerable:!0,configurable:!0,writable:!0,value:C}):g[t]=C,me=(g,t)=>{for(var C in t||(t={}))ue.call(t,C)&&ce(g,C,t[C]);if(j)for(var C of j(t))he.call(t,C)&&ce(g,C,t[C]);return g},pe=(g,t)=>Ot(g,qt(t));var W=(g,t)=>{var C={};for(var _ in g)ue.call(g,_)&&t.indexOf(_)<0&&(C[_]=g[_]);if(g!=null&&j)for(var _ of j(g))t.indexOf(_)<0&&he.call(g,_)&&(C[_]=g[_]);return C};var ae=(g,t,C)=>new Promise((_,O)=>{var G=M=>{try{K(C.next(M))}catch(z){O(z)}},Z=M=>{try{K(C.throw(M))}catch(z){O(z)}},K=M=>M.done?_(M.value):Promise.resolve(M.value).then(G,Z);K((C=C.apply(g,t)).next())});const C='<path d="M12.43 14.34A5 5 0 0110 15a5 5 0 113.95-2L17 16.09V3a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2h10a2 2 0 001.45-.63z"/><circle cx="10" cy="10" r="3"/>',_='<path d="M10 0a10 10 0 1010 10A10 10 0 0010 0zm5.66 14.24-1.41 1.41L10 11.41l-4.24 4.25-1.42-1.42L8.59 10 4.34 5.76l1.42-1.42L10 8.59l4.24-4.24 1.41 1.41L11.41 10z"/>',O='<path d="M19 3H1v14h18zM3 14l3.5-4.5 2.5 3L12.5 8l4.5 6z"/><path d="M19 5H1V3h18zm0 12H1v-2h18z"/>',G='<path d="M12.2 13.6a7 7 0 111.4-1.4l5.4 5.4-1.4 1.4zM3 8a5 5 0 1010 0A5 5 0 003 8z"/>',Z=C,K=_,M=O,z=G;function fe(e,n,o){if(typeof e=="string"||"path"in e)return e;if("shouldFlip"in e)return e.ltr;if("rtl"in e)return o==="rtl"?e.rtl:e.ltr;const a=n in e.langCodeMap?e.langCodeMap[n]:e.default;return typeof a=="string"||"path"in a?a:a.ltr}function ge(e,n){if(typeof e=="string")return!1;if("langCodeMap"in e){const o=n in e.langCodeMap?e.langCodeMap[n]:e.default;if(typeof o=="string")return!1;e=o}if("shouldFlipExceptions"in e&&Array.isArray(e.shouldFlipExceptions)){const o=e.shouldFlipExceptions.indexOf(n);return o===void 0||o===-1}return"shouldFlip"in e?e.shouldFlip:!1}function ye(e){const n=t.ref(null);return t.onMounted(()=>{const o=window.getComputedStyle(e.value).direction;n.value=o==="ltr"||o==="rtl"?o:null}),n}function be(e){const n=t.ref("");return t.onMounted(()=>{let o=e.value;for(;o&&o.lang==="";)o=o.parentElement;n.value=o?o.lang:null}),n}function v(e){return n=>typeof n=="string"&&e.indexOf(n)!==-1}const J="cdx",Ce=["default","progressive","destructive"],ke=["normal","primary","quiet"],Se=["medium","large"],$e=["x-small","small","medium"],_e=["text","search","number","email","month","password","tel","url","week","date","datetime-local","time"],se=["default","error"],we=120,Be=500,R="cdx-menu-footer-item",Ie=v($e),xe=t.defineComponent({name:"CdxIcon",props:{icon:{type:[String,Object],required:!0},iconLabel:{type:String,default:""},lang:{type:String,default:null},dir:{type:String,default:null},size:{type:String,default:"medium",validator:Ie}},emits:["click"],setup(e,{emit:n}){const o=t.ref(),a=ye(o),i=be(o),d=t.computed(()=>e.dir||a.value),l=t.computed(()=>e.lang||i.value),r=t.computed(()=>({"cdx-icon--flipped":d.value==="rtl"&&l.value!==null&&ge(e.icon,l.value),[`cdx-icon--${e.size}`]:!0})),s=t.computed(()=>fe(e.icon,l.value||"",d.value||"ltr")),u=t.computed(()=>typeof s.value=="string"?s.value:""),p=t.computed(()=>typeof s.value!="string"?s.value.path:"");return{rootElement:o,rootClasses:r,iconSvg:u,iconPath:p,onClick:y=>{n("click",y)}}}}),Ht="",x=(e,n)=>{const o=e.__vccOpts||e;for(const[a,i]of n)o[a]=i;return o},Me=["aria-hidden"],Ve={key:0},Ee=["innerHTML"],Ne=["d"];function Te(e,n,o,a,i,d){return t.openBlock(),t.createElementBlock("span",{ref:"rootElement",class:t.normalizeClass(["cdx-icon",e.rootClasses]),onClick:n[0]||(n[0]=(...l)=>e.onClick&&e.onClick(...l))},[(t.openBlock(),t.createElementBlock("svg",{xmlns:"http://www.w3.org/2000/svg",width:"20",height:"20",viewBox:"0 0 20 20","aria-hidden":e.iconLabel?void 0:!0},[e.iconLabel?(t.openBlock(),t.createElementBlock("title",Ve,t.toDisplayString(e.iconLabel),1)):t.createCommentVNode("",!0),e.iconSvg?(t.openBlock(),t.createElementBlock("g",{key:1,innerHTML:e.iconSvg},null,8,Ee)):(t.openBlock(),t.createElementBlock("path",{key:2,d:e.iconPath},null,8,Ne))],8,Me))],2)}const D=x(xe,[["render",Te]]),ve=t.defineComponent({name:"CdxThumbnail",components:{CdxIcon:D},props:{thumbnail:{type:[Object,null],default:null},placeholderIcon:{type:[String,Object],default:M}},setup:e=>{const n=t.ref(!1),o=t.ref({}),a=i=>{const d=i.replace(/([\\"\n])/g,"\\$1"),l=new Image;l.onload=()=>{o.value={backgroundImage:`url("${d}")`},n.value=!0},l.onerror=()=>{n.value=!1},l.src=d};return t.onMounted(()=>{var i;(i=e.thumbnail)!=null&&i.url&&a(e.thumbnail.url)}),{thumbnailStyle:o,thumbnailLoaded:n}}}),Pt="",Le={class:"cdx-thumbnail"},Re={key:0,class:"cdx-thumbnail__placeholder"};function Ae(e,n,o,a,i,d){const l=t.resolveComponent("cdx-icon");return t.openBlock(),t.createElementBlock("span",Le,[e.thumbnailLoaded?t.createCommentVNode("",!0):(t.openBlock(),t.createElementBlock("span",Re,[t.createVNode(l,{icon:e.placeholderIcon,class:"cdx-thumbnail__placeholder__icon--vue"},null,8,["icon"])])),t.createVNode(t.Transition,{name:"cdx-thumbnail__image"},{default:t.withCtx(()=>[e.thumbnailLoaded?(t.openBlock(),t.createElementBlock("span",{key:0,style:t.normalizeStyle(e.thumbnailStyle),class:"cdx-thumbnail__image"},null,4)):t.createCommentVNode("",!0)]),_:1})])}const Ke=x(ve,[["render",Ae]]);function ze(e){return e.replace(/([\\{}()|.?*+\-^$[\]])/g,"\\$1")}const De="[̀-ͯ҃-҉֑-ׇֽֿׁׂׅׄؐ-ًؚ-ٰٟۖ-ۜ۟-۪ۤۧۨ-ܑۭܰ-݊ަ-ް߫-߽߳ࠖ-࠙ࠛ-ࠣࠥ-ࠧࠩ-࡙࠭-࡛࣓-ࣣ࣡-ःऺ-़ा-ॏ॑-ॗॢॣঁ-ঃ়া-ৄেৈো-্ৗৢৣ৾ਁ-ਃ਼ਾ-ੂੇੈੋ-੍ੑੰੱੵઁ-ઃ઼ા-ૅે-ૉો-્ૢૣૺ-૿ଁ-ଃ଼ା-ୄେୈୋ-୍ୖୗୢୣஂா-ூெ-ைொ-்ௗఀ-ఄా-ౄె-ైొ-్ౕౖౢౣಁ-ಃ಼ಾ-ೄೆ-ೈೊ-್ೕೖೢೣഀ-ഃ഻഼ാ-ൄെ-ൈൊ-്ൗൢൣංඃ්ා-ුූෘ-ෟෲෳัิ-ฺ็-๎ັິ-ູົຼ່-ໍ༹༘༙༵༷༾༿ཱ-྄྆྇ྍ-ྗྙ-ྼ࿆ါ-ှၖ-ၙၞ-ၠၢ-ၤၧ-ၭၱ-ၴႂ-ႍႏႚ-ႝ፝-፟ᜒ-᜔ᜲ-᜴ᝒᝓᝲᝳ឴-៓៝᠋-᠍ᢅᢆᢩᤠ-ᤫᤰ-᤻ᨗ-ᨛᩕ-ᩞ᩠-᩿᩼᪰-᪾ᬀ-ᬄ᬴-᭄᭫-᭳ᮀ-ᮂᮡ-ᮭ᯦-᯳ᰤ-᰷᳐-᳔᳒-᳨᳭ᳲ-᳴᳷-᳹᷀-᷹᷻-᷿⃐-⃰⳯-⵿⳱ⷠ-〪ⷿ-゙゚〯꙯-꙲ꙴ-꙽ꚞꚟ꛰꛱ꠂ꠆ꠋꠣ-ꠧꢀꢁꢴ-ꣅ꣠-꣱ꣿꤦ-꤭ꥇ-꥓ꦀ-ꦃ꦳-꧀ꧥꨩ-ꨶꩃꩌꩍꩻ-ꩽꪰꪲ-ꪴꪷꪸꪾ꪿꫁ꫫ-ꫯꫵ꫶ꯣ-ꯪ꯬꯭ﬞ︀-️︠-︯]";function Fe(e,n){if(!e)return[n,"",""];const o=ze(e),a=new RegExp(o+De+"*","i").exec(n);if(!a||a.index===void 0)return[n,"",""];const i=a.index,d=i+a[0].length,l=n.slice(i,d),r=n.slice(0,i),s=n.slice(d,n.length);return[r,l,s]}const Oe=t.defineComponent({name:"CdxSearchResultTitle",props:{title:{type:String,required:!0},searchQuery:{type:String,default:""}},setup:e=>({titleChunks:t.computed(()=>Fe(e.searchQuery,String(e.title)))})}),Ut="",qe={class:"cdx-search-result-title"},He={class:"cdx-search-result-title__match"};function Pe(e,n,o,a,i,d){return t.openBlock(),t.createElementBlock("span",qe,[t.createElementVNode("bdi",null,[t.createTextVNode(t.toDisplayString(e.titleChunks[0]),1),t.createElementVNode("span",He,t.toDisplayString(e.titleChunks[1]),1),t.createTextVNode(t.toDisplayString(e.titleChunks[2]),1)])])}const Ue=x(Oe,[["render",Pe]]),Qe=t.defineComponent({name:"CdxMenuItem",components:{CdxIcon:D,CdxThumbnail:Ke,CdxSearchResultTitle:Ue},props:{id:{type:String,required:!0},value:{type:[String,Number],required:!0},disabled:{type:Boolean,default:!1},selected:{type:Boolean,default:!1},active:{type:Boolean,default:!1},highlighted:{type:Boolean,default:!1},label:{type:String,default:""},match:{type:String,default:""},supportingText:{type:String,default:""},url:{type:String,default:""},icon:{type:[String,Object],default:""},showThumbnail:{type:Boolean,default:!1},thumbnail:{type:[Object,null],default:null},description:{type:[String,null],default:""},searchQuery:{type:String,default:""},boldLabel:{type:Boolean,default:!1},hideDescriptionOverflow:{type:Boolean,default:!1},language:{type:Object,default:()=>({})}},emits:["change"],setup:(e,{emit:n})=>{const o=()=>{e.highlighted||n("change","highlighted",!0)},a=()=>{n("change","highlighted",!1)},i=p=>{p.button===0&&n("change","active",!0)},d=()=>{n("change","selected",!0)},l=t.computed(()=>e.searchQuery.length>0),r=t.computed(()=>({"cdx-menu-item--selected":e.selected,"cdx-menu-item--active":e.active&&e.highlighted,"cdx-menu-item--highlighted":e.highlighted,"cdx-menu-item--enabled":!e.disabled,"cdx-menu-item--disabled":e.disabled,"cdx-menu-item--highlight-query":l.value,"cdx-menu-item--bold-label":e.boldLabel,"cdx-menu-item--has-description":!!e.description,"cdx-menu-item--hide-description-overflow":e.hideDescriptionOverflow})),s=t.computed(()=>e.url?"a":"span"),u=t.computed(()=>e.label||String(e.value));return{onMouseMove:o,onMouseLeave:a,onMouseDown:i,onClick:d,highlightQuery:l,rootClasses:r,contentTag:s,title:u}}}),Qt="",je=["id","aria-disabled","aria-selected"],We={class:"cdx-menu-item__text"},Ge=["lang"],Ze=["lang"],Je=["lang"],Xe=["lang"];function Ye(e,n,o,a,i,d){const l=t.resolveComponent("cdx-thumbnail"),r=t.resolveComponent("cdx-icon"),s=t.resolveComponent("cdx-search-result-title");return t.openBlock(),t.createElementBlock("li",{id:e.id,role:"option",class:t.normalizeClass(["cdx-menu-item",e.rootClasses]),"aria-disabled":e.disabled,"aria-selected":e.selected,onMousemove:n[0]||(n[0]=(...u)=>e.onMouseMove&&e.onMouseMove(...u)),onMouseleave:n[1]||(n[1]=(...u)=>e.onMouseLeave&&e.onMouseLeave(...u)),onMousedown:n[2]||(n[2]=t.withModifiers((...u)=>e.onMouseDown&&e.onMouseDown(...u),["prevent"])),onClick:n[3]||(n[3]=(...u)=>e.onClick&&e.onClick(...u))},[t.renderSlot(e.$slots,"default",{},()=>[(t.openBlock(),t.createBlock(t.resolveDynamicComponent(e.contentTag),{href:e.url?e.url:void 0,class:"cdx-menu-item__content"},{default:t.withCtx(()=>{var u,p,k,y,w,B;return[e.showThumbnail?(t.openBlock(),t.createBlock(l,{key:0,thumbnail:e.thumbnail,class:"cdx-menu-item__thumbnail"},null,8,["thumbnail"])):e.icon?(t.openBlock(),t.createBlock(r,{key:1,icon:e.icon,class:"cdx-menu-item__icon"},null,8,["icon"])):t.createCommentVNode("",!0),t.createElementVNode("span",We,[e.highlightQuery?(t.openBlock(),t.createBlock(s,{key:0,title:e.title,"search-query":e.searchQuery,lang:(u=e.language)==null?void 0:u.label},null,8,["title","search-query","lang"])):(t.openBlock(),t.createElementBlock("span",{key:1,class:"cdx-menu-item__text__label",lang:(p=e.language)==null?void 0:p.label},[t.createElementVNode("bdi",null,t.toDisplayString(e.title),1)],8,Ge)),e.match?(t.openBlock(),t.createElementBlock(t.Fragment,{key:2},[t.createTextVNode(t.toDisplayString(" ")+" "),e.highlightQuery?(t.openBlock(),t.createBlock(s,{key:0,title:e.match,"search-query":e.searchQuery,lang:(k=e.language)==null?void 0:k.match},null,8,["title","search-query","lang"])):(t.openBlock(),t.createElementBlock("span",{key:1,class:"cdx-menu-item__text__match",lang:(y=e.language)==null?void 0:y.match},[t.createElementVNode("bdi",null,t.toDisplayString(e.match),1)],8,Ze))],64)):t.createCommentVNode("",!0),e.supportingText?(t.openBlock(),t.createElementBlock(t.Fragment,{key:3},[t.createTextVNode(t.toDisplayString(" ")+" "),t.createElementVNode("span",{class:"cdx-menu-item__text__supporting-text",lang:(w=e.language)==null?void 0:w.supportingText},[t.createElementVNode("bdi",null,t.toDisplayString(e.supportingText),1)],8,Je)],64)):t.createCommentVNode("",!0),e.description?(t.openBlock(),t.createElementBlock("span",{key:4,class:"cdx-menu-item__text__description",lang:(B=e.language)==null?void 0:B.description},[t.createElementVNode("bdi",null,t.toDisplayString(e.description),1)],8,Xe)):t.createCommentVNode("",!0)])]}),_:1},8,["href"]))])],42,je)}const et=x(Qe,[["render",Ye]]),tt=t.defineComponent({name:"CdxProgressBar",props:{inline:{type:Boolean,default:!1},disabled:{type:Boolean,default:!1}},setup(e){return{rootClasses:t.computed(()=>({"cdx-progress-bar--block":!e.inline,"cdx-progress-bar--inline":e.inline,"cdx-progress-bar--enabled":!e.disabled,"cdx-progress-bar--disabled":e.disabled}))}}}),jt="",nt=["aria-disabled"],ot=[t.createElementVNode("div",{class:"cdx-progress-bar__bar"},null,-1)];function lt(e,n,o,a,i,d){return t.openBlock(),t.createElementBlock("div",{class:t.normalizeClass(["cdx-progress-bar",e.rootClasses]),role:"progressbar","aria-disabled":e.disabled,"aria-valuemin":"0","aria-valuemax":"100"},ot,10,nt)}const at=x(tt,[["render",lt]]);let X=0;function ie(e){const n=t.getCurrentInstance(),o=(n==null?void 0:n.props.id)||(n==null?void 0:n.attrs.id);return e?`${J}-${e}-${X++}`:o?`${J}-${o}-${X++}`:`${J}-${X++}`}function st(e,n){const o=t.ref(!1);let a=!1;if(typeof window!="object"||!("IntersectionObserver"in window&&"IntersectionObserverEntry"in window&&"intersectionRatio"in window.IntersectionObserverEntry.prototype))return o;const i=new window.IntersectionObserver(d=>{const l=d[0];l&&(o.value=l.isIntersecting)},n);return t.onMounted(()=>{a=!0,e.value&&i.observe(e.value)}),t.onUnmounted(()=>{a=!1,i.disconnect()}),t.watch(e,d=>{a&&(i.disconnect(),o.value=!1,d&&i.observe(d))}),o}function q(e,n=t.computed(()=>({}))){const o=t.computed(()=>{const d=W(n.value,[]);return e.class&&e.class.split(" ").forEach(r=>{d[r]=!0}),d}),a=t.computed(()=>{if("style"in e)return e.style}),i=t.computed(()=>{const s=e,{class:d,style:l}=s;return W(s,["class","style"])});return{rootClasses:o,rootStyle:a,otherAttrs:i}}const it=t.defineComponent({name:"CdxMenu",components:{CdxMenuItem:et,CdxProgressBar:at},inheritAttrs:!1,props:{menuItems:{type:Array,required:!0},footer:{type:Object,default:null},selected:{type:[String,Number,null],required:!0},expanded:{type:Boolean,required:!0},showPending:{type:Boolean,default:!1},visibleItemLimit:{type:Number,default:null},showThumbnail:{type:Boolean,default:!1},boldLabel:{type:Boolean,default:!1},hideDescriptionOverflow:{type:Boolean,default:!1},searchQuery:{type:String,default:""},showNoResultsSlot:{type:Boolean,default:null}},emits:["update:selected","update:expanded","menu-item-click","menu-item-keyboard-navigation","load-more"],expose:["clearActive","getHighlightedMenuItem","getHighlightedViaKeyboard","delegateKeyNavigation"],setup(e,{emit:n,slots:o,attrs:a}){const i=t.computed(()=>(e.footer&&e.menuItems?[...e.menuItems,e.footer]:e.menuItems).map(m=>pe(me({},m),{id:ie("menu-item")}))),d=t.computed(()=>o["no-results"]?e.showNoResultsSlot!==null?e.showNoResultsSlot:i.value.length===0:!1),l=t.ref(null),r=t.ref(!1),s=t.ref(null);function u(){return i.value.find(c=>c.value===e.selected)}function p(c,m){var b;if(!(m&&m.disabled))switch(c){case"selected":n("update:selected",(b=m==null?void 0:m.value)!=null?b:null),n("update:expanded",!1),s.value=null;break;case"highlighted":l.value=m||null,r.value=!1;break;case"highlightedViaKeyboard":l.value=m||null,r.value=!0;break;case"active":s.value=m||null;break}}const k=t.computed(()=>{if(l.value!==null)return i.value.findIndex(c=>c.value===l.value.value)});function y(c){c&&(p("highlightedViaKeyboard",c),n("menu-item-keyboard-navigation",c))}function w(c){var S;const m=F=>{for(let h=F-1;h>=0;h--)if(!i.value[h].disabled)return i.value[h]};c=c||i.value.length;const b=(S=m(c))!=null?S:m(i.value.length);y(b)}function B(c){const m=S=>i.value.find((F,h)=>!F.disabled&&h>S);c=c!=null?c:-1;const b=m(c)||m(-1);y(b)}function H(c,m=!0){function b(){n("update:expanded",!0),p("highlighted",u())}function S(){m&&(c.preventDefault(),c.stopPropagation())}switch(c.key){case"Enter":case" ":return S(),e.expanded?(l.value&&r.value&&n("update:selected",l.value.value),n("update:expanded",!1)):b(),!0;case"Tab":return e.expanded&&(l.value&&r.value&&n("update:selected",l.value.value),n("update:expanded",!1)),!0;case"ArrowUp":return S(),e.expanded?(l.value===null&&p("highlightedViaKeyboard",u()),w(k.value)):b(),N(),!0;case"ArrowDown":return S(),e.expanded?(l.value===null&&p("highlightedViaKeyboard",u()),B(k.value)):b(),N(),!0;case"Home":return S(),e.expanded?(l.value===null&&p("highlightedViaKeyboard",u()),B()):b(),N(),!0;case"End":return S(),e.expanded?(l.value===null&&p("highlightedViaKeyboard",u()),w()):b(),N(),!0;case"Escape":return S(),n("update:expanded",!1),!0;default:return!1}}function f(){p("active")}const I=[],P=t.ref(void 0),ee=st(P,{threshold:.8});t.watch(ee,c=>{c&&n("load-more")});function te(c,m){if(c){I[m]=c.$el;const b=e.visibleItemLimit;if(!b||e.menuItems.length<b)return;const S=Math.min(b,Math.max(2,Math.floor(.2*e.menuItems.length)));m===e.menuItems.length-S&&(P.value=c.$el)}}function N(){if(!e.visibleItemLimit||e.visibleItemLimit>e.menuItems.length||k.value===void 0)return;const c=k.value>=0?k.value:0;I[c].scrollIntoView({behavior:"smooth",block:"nearest"})}const T=t.ref(null),A=t.ref(null);function L(){if(A.value=null,!e.visibleItemLimit||I.length<=e.visibleItemLimit){T.value=null;return}const c=I[0],m=I[e.visibleItemLimit];if(T.value=V(c,m),e.footer){const b=I[I.length-1];A.value=b.scrollHeight}}function V(c,m){const b=c.getBoundingClientRect().top;return m.getBoundingClientRect().top-b+2}t.onMounted(()=>{document.addEventListener("mouseup",f)}),t.onUnmounted(()=>{document.removeEventListener("mouseup",f)}),t.watch(t.toRef(e,"expanded"),c=>ae(this,null,function*(){const m=u();!c&&l.value&&m===void 0&&p("highlighted"),c&&m!==void 0&&p("highlighted",m),c&&(yield t.nextTick(),L(),yield t.nextTick(),N())})),t.watch(t.toRef(e,"menuItems"),c=>ae(this,null,function*(){c.length<I.length&&(I.length=c.length),e.expanded&&(yield t.nextTick(),L(),yield t.nextTick(),N())}),{deep:!0});const U=t.computed(()=>({"max-height":T.value?`${T.value}px`:void 0,"overflow-y":T.value?"scroll":void 0,"margin-bottom":A.value?`${A.value}px`:void 0})),ne=t.computed(()=>({"cdx-menu--has-footer":!!e.footer,"cdx-menu--has-sticky-footer":!!e.footer&&!!T.value})),{rootClasses:oe,rootStyle:le,otherAttrs:Q}=q(a,ne);return{listBoxStyle:U,rootClasses:oe,rootStyle:le,otherAttrs:Q,assignTemplateRef:te,computedMenuItems:i,computedShowNoResultsSlot:d,highlightedMenuItem:l,highlightedViaKeyboard:r,activeMenuItem:s,handleMenuItemChange:p,handleKeyNavigation:H}},methods:{getHighlightedMenuItem(){return this.highlightedMenuItem},getHighlightedViaKeyboard(){return this.highlightedViaKeyboard},clearActive(){this.handleMenuItemChange("active")},delegateKeyNavigation(e,n=!0){return this.handleKeyNavigation(e,n)}}}),Gt="",rt={key:0,class:"cdx-menu__pending cdx-menu-item"},dt={key:1,class:"cdx-menu__no-results cdx-menu-item"};function ct(e,n,o,a,i,d){const l=t.resolveComponent("cdx-menu-item"),r=t.resolveComponent("cdx-progress-bar");return t.withDirectives((t.openBlock(),t.createElementBlock("div",{class:t.normalizeClass(["cdx-menu",e.rootClasses]),style:t.normalizeStyle(e.rootStyle)},[t.createElementVNode("ul",t.mergeProps({class:"cdx-menu__listbox",role:"listbox","aria-multiselectable":"false",style:e.listBoxStyle},e.otherAttrs),[e.showPending&&e.computedMenuItems.length===0&&e.$slots.pending?(t.openBlock(),t.createElementBlock("li",rt,[t.renderSlot(e.$slots,"pending")])):t.createCommentVNode("",!0),e.computedShowNoResultsSlot?(t.openBlock(),t.createElementBlock("li",dt,[t.renderSlot(e.$slots,"no-results")])):t.createCommentVNode("",!0),(t.openBlock(!0),t.createElementBlock(t.Fragment,null,t.renderList(e.computedMenuItems,(s,u)=>{var p,k;return t.openBlock(),t.createBlock(l,t.mergeProps({key:s.value,ref_for:!0,ref:y=>e.assignTemplateRef(y,u)},s,{selected:s.value===e.selected,active:s.value===((p=e.activeMenuItem)==null?void 0:p.value),highlighted:s.value===((k=e.highlightedMenuItem)==null?void 0:k.value),"show-thumbnail":e.showThumbnail,"bold-label":e.boldLabel,"hide-description-overflow":e.hideDescriptionOverflow,"search-query":e.searchQuery,onChange:(y,w)=>e.handleMenuItemChange(y,w&&s),onClick:y=>e.$emit("menu-item-click",s)}),{default:t.withCtx(()=>{var y,w;return[t.renderSlot(e.$slots,"default",{menuItem:s,active:s.value===((y=e.activeMenuItem)==null?void 0:y.value)&&s.value===((w=e.highlightedMenuItem)==null?void 0:w.value)})]}),_:2},1040,["selected","active","highlighted","show-thumbnail","bold-label","hide-description-overflow","search-query","onChange","onClick"])}),128)),e.showPending?(t.openBlock(),t.createBlock(r,{key:2,class:"cdx-menu__progress-bar",inline:!0})):t.createCommentVNode("",!0)],16)],6)),[[t.vShow,e.expanded]])}const ut=x(it,[["render",ct]]),ht=v(Ce),mt=v(ke),pt=v(Se),ft=e=>{!e["aria-label"]&&!e["aria-hidden"]&&t.warn(`icon-only buttons require one of the following attribute: aria-label or aria-hidden.
		See documentation on https://doc.wikimedia.org/codex/latest/components/demos/button.html#icon-only-button-1`)};function Y(e){const n=[];for(const o of e)typeof o=="string"&&o.trim()!==""?n.push(o):Array.isArray(o)?n.push(...Y(o)):typeof o=="object"&&o&&(typeof o.type=="string"||typeof o.type=="object"?n.push(o):o.type!==t.Comment&&(typeof o.children=="string"&&o.children.trim()!==""?n.push(o.children):Array.isArray(o.children)&&n.push(...Y(o.children))));return n}const gt=(e,n)=>{if(!e)return!1;const o=Y(e);if(o.length!==1)return!1;const a=o[0],i=typeof a=="object"&&typeof a.type=="object"&&"name"in a.type&&a.type.name===D.name,d=typeof a=="object"&&a.type==="svg";return i||d?(ft(n),!0):!1},yt=t.defineComponent({name:"CdxButton",props:{action:{type:String,default:"default",validator:ht},weight:{type:String,default:"normal",validator:mt},size:{type:String,default:"medium",validator:pt}},emits:["click"],setup(e,{emit:n,slots:o,attrs:a}){const i=t.ref(!1);return{rootClasses:t.computed(()=>{var s;return{[`cdx-button--action-${e.action}`]:!0,[`cdx-button--weight-${e.weight}`]:!0,[`cdx-button--size-${e.size}`]:!0,"cdx-button--framed":e.weight!=="quiet","cdx-button--icon-only":gt((s=o.default)==null?void 0:s.call(o),a),"cdx-button--is-active":i.value}}),onClick:s=>{n("click",s)},setActive:s=>{i.value=s}}}}),Zt="";function bt(e,n,o,a,i,d){return t.openBlock(),t.createElementBlock("button",{class:t.normalizeClass(["cdx-button",e.rootClasses]),onClick:n[0]||(n[0]=(...l)=>e.onClick&&e.onClick(...l)),onKeydown:n[1]||(n[1]=t.withKeys(l=>e.setActive(!0),["space","enter"])),onKeyup:n[2]||(n[2]=t.withKeys(l=>e.setActive(!1),["space","enter"]))},[t.renderSlot(e.$slots,"default")],34)}const Ct=x(yt,[["render",bt]]);function re(e,n,o){return t.computed({get:()=>e.value,set:a=>n(o||"update:modelValue",a)})}const kt=v(_e),St=v(se),$t=t.defineComponent({name:"CdxTextInput",components:{CdxIcon:D},inheritAttrs:!1,expose:["focus","blur"],props:{modelValue:{type:[String,Number],default:""},inputType:{type:String,default:"text",validator:kt},status:{type:String,default:"default",validator:St},disabled:{type:Boolean,default:!1},startIcon:{type:[String,Object],default:void 0},endIcon:{type:[String,Object],default:void 0},clearable:{type:Boolean,default:!1}},emits:["update:modelValue","keydown","input","change","focus","blur","clear"],setup(e,{emit:n,attrs:o}){const a=re(t.toRef(e,"modelValue"),n),i=t.computed(()=>e.clearable&&!!a.value&&!e.disabled),d=t.computed(()=>({"cdx-text-input--has-start-icon":!!e.startIcon,"cdx-text-input--has-end-icon":!!e.endIcon,"cdx-text-input--clearable":i.value,[`cdx-text-input--status-${e.status}`]:!0})),{rootClasses:l,rootStyle:r,otherAttrs:s}=q(o,d),u=t.computed(()=>({"cdx-text-input__input--has-value":!!a.value}));return{wrappedModel:a,isClearable:i,rootClasses:l,rootStyle:r,otherAttrs:s,inputClasses:u,onClear:f=>{a.value="",n("clear",f)},onInput:f=>{n("input",f)},onChange:f=>{n("change",f)},onKeydown:f=>{(f.key==="Home"||f.key==="End")&&!f.ctrlKey&&!f.metaKey||n("keydown",f)},onFocus:f=>{n("focus",f)},onBlur:f=>{n("blur",f)},cdxIconClear:K}},methods:{focus(){this.$refs.input.focus()},blur(){this.$refs.input.blur()}}}),Jt="",_t=["type","disabled"];function wt(e,n,o,a,i,d){const l=t.resolveComponent("cdx-icon");return t.openBlock(),t.createElementBlock("div",{class:t.normalizeClass(["cdx-text-input",e.rootClasses]),style:t.normalizeStyle(e.rootStyle)},[t.withDirectives(t.createElementVNode("input",t.mergeProps({ref:"input","onUpdate:modelValue":n[0]||(n[0]=r=>e.wrappedModel=r),class:["cdx-text-input__input",e.inputClasses]},e.otherAttrs,{type:e.inputType,disabled:e.disabled,onInput:n[1]||(n[1]=(...r)=>e.onInput&&e.onInput(...r)),onChange:n[2]||(n[2]=(...r)=>e.onChange&&e.onChange(...r)),onFocus:n[3]||(n[3]=(...r)=>e.onFocus&&e.onFocus(...r)),onBlur:n[4]||(n[4]=(...r)=>e.onBlur&&e.onBlur(...r)),onKeydown:n[5]||(n[5]=(...r)=>e.onKeydown&&e.onKeydown(...r))}),null,16,_t),[[t.vModelDynamic,e.wrappedModel]]),e.startIcon?(t.openBlock(),t.createBlock(l,{key:0,icon:e.startIcon,class:"cdx-text-input__icon-vue cdx-text-input__start-icon"},null,8,["icon"])):t.createCommentVNode("",!0),e.endIcon?(t.openBlock(),t.createBlock(l,{key:1,icon:e.endIcon,class:"cdx-text-input__icon-vue cdx-text-input__end-icon"},null,8,["icon"])):t.createCommentVNode("",!0),e.isClearable?(t.openBlock(),t.createBlock(l,{key:2,icon:e.cdxIconClear,class:"cdx-text-input__icon-vue cdx-text-input__clear-icon",onMousedown:n[6]||(n[6]=t.withModifiers(()=>{},["prevent"])),onClick:e.onClear},null,8,["icon","onClick"])):t.createCommentVNode("",!0)],6)}const Bt=x($t,[["render",wt]]),It=v(se),xt=t.defineComponent({name:"CdxSearchInput",components:{CdxButton:Ct,CdxTextInput:Bt},inheritAttrs:!1,props:{modelValue:{type:[String,Number],default:""},buttonLabel:{type:String,default:""},status:{type:String,default:"default",validator:It}},emits:["update:modelValue","submit-click","input","change","focus","blur"],setup(e,{emit:n,attrs:o}){const a=re(t.toRef(e,"modelValue"),n),i=t.computed(()=>({"cdx-search-input--has-end-button":!!e.buttonLabel})),{rootClasses:d,rootStyle:l,otherAttrs:r}=q(o,i);return{wrappedModel:a,rootClasses:d,rootStyle:l,otherAttrs:r,handleSubmit:()=>{n("submit-click",a.value)},searchIcon:z}},methods:{focus(){this.$refs.textInput.focus()}}}),Xt="",Mt={class:"cdx-search-input__input-wrapper"};function Vt(e,n,o,a,i,d){const l=t.resolveComponent("cdx-text-input"),r=t.resolveComponent("cdx-button");return t.openBlock(),t.createElementBlock("div",{class:t.normalizeClass(["cdx-search-input",e.rootClasses]),style:t.normalizeStyle(e.rootStyle)},[t.createElementVNode("div",Mt,[t.createVNode(l,t.mergeProps({ref:"textInput",modelValue:e.wrappedModel,"onUpdate:modelValue":n[0]||(n[0]=s=>e.wrappedModel=s),class:"cdx-search-input__text-input","input-type":"search","start-icon":e.searchIcon,status:e.status},e.otherAttrs,{onKeydown:t.withKeys(e.handleSubmit,["enter"]),onInput:n[1]||(n[1]=s=>e.$emit("input",s)),onChange:n[2]||(n[2]=s=>e.$emit("change",s)),onFocus:n[3]||(n[3]=s=>e.$emit("focus",s)),onBlur:n[4]||(n[4]=s=>e.$emit("blur",s))}),null,16,["modelValue","start-icon","status","onKeydown"]),t.renderSlot(e.$slots,"default")]),e.buttonLabel?(t.openBlock(),t.createBlock(r,{key:0,class:"cdx-search-input__end-button",onClick:e.handleSubmit},{default:t.withCtx(()=>[t.createTextVNode(t.toDisplayString(e.buttonLabel),1)]),_:1},8,["onClick"])):t.createCommentVNode("",!0)],6)}const Et=x(xt,[["render",Vt]]),Nt=t.defineComponent({name:"CdxTypeaheadSearch",components:{CdxIcon:D,CdxMenu:ut,CdxSearchInput:Et},inheritAttrs:!1,props:{id:{type:String,required:!0},formAction:{type:String,required:!0},searchResultsLabel:{type:String,required:!0},searchResults:{type:Array,required:!0},buttonLabel:{type:String,default:""},initialInputValue:{type:String,default:""},searchFooterUrl:{type:String,default:""},debounceInterval:{type:Number,default:we},highlightQuery:{type:Boolean,default:!1},showThumbnail:{type:Boolean,default:!1},autoExpandWidth:{type:Boolean,default:!1},visibleItemLimit:{type:Number,default:null}},emits:["input","search-result-click","submit","load-more"],setup(e,{attrs:n,emit:o,slots:a}){const i=t.ref(),d=t.ref(),l=ie("typeahead-search-menu"),r=t.ref(!1),s=t.ref(!1),u=t.ref(!1),p=t.ref(!1),k=t.ref(e.initialInputValue),y=t.ref(""),w=t.computed(()=>{var h,$;return($=(h=d.value)==null?void 0:h.getHighlightedMenuItem())==null?void 0:$.id}),B=t.ref(null),H=t.computed(()=>({"cdx-typeahead-search__menu-message--has-thumbnail":e.showThumbnail})),f=t.computed(()=>e.searchResults.find(h=>h.value===B.value)),I=t.computed(()=>e.searchFooterUrl?{value:R,url:e.searchFooterUrl}:void 0),P=t.computed(()=>({"cdx-typeahead-search--show-thumbnail":e.showThumbnail,"cdx-typeahead-search--expanded":r.value,"cdx-typeahead-search--auto-expand-width":e.showThumbnail&&e.autoExpandWidth})),{rootClasses:ee,rootStyle:te,otherAttrs:N}=q(n,P);function T(h){return h}const A=t.computed(()=>({visibleItemLimit:e.visibleItemLimit,showThumbnail:e.showThumbnail,boldLabel:!0,hideDescriptionOverflow:!0}));let L,V;function U(h,$=!1){f.value&&f.value.label!==h&&f.value.value!==h&&(B.value=null),V!==void 0&&(clearTimeout(V),V=void 0),h===""?r.value=!1:(s.value=!0,a["search-results-pending"]&&(V=setTimeout(()=>{p.value&&(r.value=!0),u.value=!0},Be))),L!==void 0&&(clearTimeout(L),L=void 0);const E=()=>{o("input",h)};$?E():L=setTimeout(()=>{E()},e.debounceInterval)}function ne(h){if(h===R){B.value=null,k.value=y.value;return}B.value=h,h!==null&&(k.value=f.value?f.value.label||String(f.value.value):"")}function oe(){p.value=!0,(y.value||u.value)&&(r.value=!0)}function le(){p.value=!1,r.value=!1}function Q(h){const de=h,{id:$}=de,E=W(de,["id"]);if(E.value===R){o("search-result-click",{searchResult:null,index:e.searchResults.length,numberOfResults:e.searchResults.length});return}c(E)}function c(h){const $={searchResult:h,index:e.searchResults.findIndex(E=>E.value===h.value),numberOfResults:e.searchResults.length};o("search-result-click",$)}function m(h){if(h.value===R){k.value=y.value;return}k.value=h.value?h.label||String(h.value):""}function b(h){var $;r.value=!1,($=d.value)==null||$.clearActive(),Q(h)}function S(h){if(f.value)c(f.value),h.stopPropagation(),window.location.assign(f.value.url),h.preventDefault();else{const $={searchResult:null,index:-1,numberOfResults:e.searchResults.length};o("submit",$)}}function F(h){if(!d.value||!y.value||h.key===" ")return;const $=d.value.getHighlightedMenuItem(),E=d.value.getHighlightedViaKeyboard();switch(h.key){case"Enter":$&&($.value===R&&E?window.location.assign(e.searchFooterUrl):d.value.delegateKeyNavigation(h,!1)),r.value=!1;break;case"Tab":r.value=!1;break;default:d.value.delegateKeyNavigation(h);break}}return t.onMounted(()=>{e.initialInputValue&&U(e.initialInputValue,!0)}),t.watch(t.toRef(e,"searchResults"),()=>{y.value=k.value.trim(),p.value&&s.value&&y.value.length>0&&(r.value=!0),V!==void 0&&(clearTimeout(V),V=void 0),s.value=!1,u.value=!1}),{form:i,menu:d,menuId:l,highlightedId:w,selection:B,menuMessageClass:H,footer:I,asSearchResult:T,inputValue:k,searchQuery:y,expanded:r,showPending:u,rootClasses:ee,rootStyle:te,otherAttrs:N,menuConfig:A,onUpdateInputValue:U,onUpdateMenuSelection:ne,onFocus:oe,onBlur:le,onSearchResultClick:Q,onSearchResultKeyboardNavigation:m,onSearchFooterClick:b,onSubmit:S,onKeydown:F,MenuFooterValue:R,articleIcon:Z}},methods:{focus(){this.$refs.searchInput.focus()}}}),Yt="",Tt=["id","action"],vt={class:"cdx-typeahead-search__menu-message__text"},Lt={class:"cdx-typeahead-search__menu-message__text"},Rt=["href","onClickCapture"],At={class:"cdx-menu-item__text cdx-typeahead-search__search-footer__text"},Kt={class:"cdx-typeahead-search__search-footer__query"};function zt(e,n,o,a,i,d){const l=t.resolveComponent("cdx-icon"),r=t.resolveComponent("cdx-menu"),s=t.resolveComponent("cdx-search-input");return t.openBlock(),t.createElementBlock("div",{class:t.normalizeClass(["cdx-typeahead-search",e.rootClasses]),style:t.normalizeStyle(e.rootStyle)},[t.createElementVNode("form",{id:e.id,ref:"form",class:"cdx-typeahead-search__form",action:e.formAction,onSubmit:n[4]||(n[4]=(...u)=>e.onSubmit&&e.onSubmit(...u))},[t.createVNode(s,t.mergeProps({ref:"searchInput",modelValue:e.inputValue,"onUpdate:modelValue":n[3]||(n[3]=u=>e.inputValue=u),"button-label":e.buttonLabel},e.otherAttrs,{class:"cdx-typeahead-search__input",name:"search",role:"combobox",autocomplete:"off","aria-autocomplete":"list","aria-owns":e.menuId,"aria-expanded":e.expanded,"aria-activedescendant":e.highlightedId,"onUpdate:modelValue":e.onUpdateInputValue,onFocus:e.onFocus,onBlur:e.onBlur,onKeydown:e.onKeydown}),{default:t.withCtx(()=>[t.createVNode(r,t.mergeProps({id:e.menuId,ref:"menu",expanded:e.expanded,"onUpdate:expanded":n[0]||(n[0]=u=>e.expanded=u),"show-pending":e.showPending,selected:e.selection,"menu-items":e.searchResults,footer:e.footer,"search-query":e.highlightQuery?e.searchQuery:"","show-no-results-slot":e.searchQuery.length>0&&e.searchResults.length===0&&e.$slots["search-no-results-text"]&&e.$slots["search-no-results-text"]().length>0},e.menuConfig,{"aria-label":e.searchResultsLabel,"onUpdate:selected":e.onUpdateMenuSelection,onMenuItemClick:n[1]||(n[1]=u=>e.onSearchResultClick(e.asSearchResult(u))),onMenuItemKeyboardNavigation:e.onSearchResultKeyboardNavigation,onLoadMore:n[2]||(n[2]=u=>e.$emit("load-more"))}),{pending:t.withCtx(()=>[t.createElementVNode("div",{class:t.normalizeClass(["cdx-menu-item__content cdx-typeahead-search__menu-message",e.menuMessageClass])},[t.createElementVNode("span",vt,[t.renderSlot(e.$slots,"search-results-pending")])],2)]),"no-results":t.withCtx(()=>[t.createElementVNode("div",{class:t.normalizeClass(["cdx-menu-item__content cdx-typeahead-search__menu-message",e.menuMessageClass])},[t.createElementVNode("span",Lt,[t.renderSlot(e.$slots,"search-no-results-text")])],2)]),default:t.withCtx(({menuItem:u,active:p})=>[u.value===e.MenuFooterValue?(t.openBlock(),t.createElementBlock("a",{key:0,class:t.normalizeClass(["cdx-menu-item__content cdx-typeahead-search__search-footer",{"cdx-typeahead-search__search-footer__active":p}]),href:e.asSearchResult(u).url,onClickCapture:t.withModifiers(k=>e.onSearchFooterClick(e.asSearchResult(u)),["stop"])},[t.createVNode(l,{class:"cdx-menu-item__thumbnail cdx-typeahead-search__search-footer__icon",icon:e.articleIcon},null,8,["icon"]),t.createElementVNode("span",At,[t.renderSlot(e.$slots,"search-footer-text",{searchQuery:e.searchQuery},()=>[t.createElementVNode("strong",Kt,t.toDisplayString(e.searchQuery),1)])])],42,Rt)):t.createCommentVNode("",!0)]),_:3},16,["id","expanded","show-pending","selected","menu-items","footer","search-query","show-no-results-slot","aria-label","onUpdate:selected","onMenuItemKeyboardNavigation"])]),_:3},16,["modelValue","button-label","aria-owns","aria-expanded","aria-activedescendant","onUpdate:modelValue","onFocus","onBlur","onKeydown"]),t.renderSlot(e.$slots,"default")],40,Tt)],6)}const Dt=x(Nt,[["render",zt]]);g.CdxTypeaheadSearch=Dt,Object.defineProperty(g,Symbol.toStringTag,{value:"Module"})});
