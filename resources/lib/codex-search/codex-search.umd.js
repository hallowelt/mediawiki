(function(f,t){typeof exports=="object"&&typeof module!="undefined"?t(exports,require("vue")):typeof define=="function"&&define.amd?define(["exports","vue"],t):(f=typeof globalThis!="undefined"?globalThis:f||self,t(f["codex-search"]={},f.Vue))})(this,function(f,t){"use strict";var Ft=Object.defineProperty,qt=Object.defineProperties;var Ht=Object.getOwnPropertyDescriptors;var G=Object.getOwnPropertySymbols;var he=Object.prototype.hasOwnProperty,pe=Object.prototype.propertyIsEnumerable;var ue=(f,t,y)=>t in f?Ft(f,t,{enumerable:!0,configurable:!0,writable:!0,value:y}):f[t]=y,me=(f,t)=>{for(var y in t||(t={}))he.call(t,y)&&ue(f,y,t[y]);if(G)for(var y of G(t))pe.call(t,y)&&ue(f,y,t[y]);return f},fe=(f,t)=>qt(f,Ht(t));var J=(f,t)=>{var y={};for(var B in f)he.call(f,B)&&t.indexOf(B)<0&&(y[B]=f[B]);if(f!=null&&G)for(var B of G(f))t.indexOf(B)<0&&pe.call(f,B)&&(y[B]=f[B]);return y};var se=(f,t,y)=>new Promise((B,P)=>{var X=E=>{try{O(y.next(E))}catch(F){P(F)}},Y=E=>{try{O(y.throw(E))}catch(F){P(F)}},O=E=>E.done?B(E.value):Promise.resolve(E.value).then(X,Y);O((y=y.apply(f,t)).next())});const y='<path d="M12.43 14.34A5 5 0 0110 15a5 5 0 113.95-2L17 16.09V3a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2h10a2 2 0 001.45-.63z"/><circle cx="10" cy="10" r="3"/>',B='<path d="M10 0a10 10 0 1010 10A10 10 0 0010 0zm5.66 14.24-1.41 1.41L10 11.41l-4.24 4.25-1.42-1.42L8.59 10 4.34 5.76l1.42-1.42L10 8.59l4.24-4.24 1.41 1.41L11.41 10z"/>',P='<path d="M19 3H1v14h18zM3 14l3.5-4.5 2.5 3L12.5 8l4.5 6z"/><path d="M19 5H1V3h18zm0 12H1v-2h18z"/>',X='<path d="M12.2 13.6a7 7 0 111.4-1.4l5.4 5.4-1.4 1.4zM3 8a5 5 0 1010 0A5 5 0 003 8z"/>',Y=y,O=B,E=P,F=X;function ge(e,n,o){if(typeof e=="string"||"path"in e)return e;if("shouldFlip"in e)return e.ltr;if("rtl"in e)return o==="rtl"?e.rtl:e.ltr;const s=n in e.langCodeMap?e.langCodeMap[n]:e.default;return typeof s=="string"||"path"in s?s:s.ltr}function ye(e,n){if(typeof e=="string")return!1;if("langCodeMap"in e){const o=n in e.langCodeMap?e.langCodeMap[n]:e.default;if(typeof o=="string")return!1;e=o}if("shouldFlipExceptions"in e&&Array.isArray(e.shouldFlipExceptions)){const o=e.shouldFlipExceptions.indexOf(n);return o===void 0||o===-1}return"shouldFlip"in e?e.shouldFlip:!1}function be(e){const n=t.ref(null);return t.onMounted(()=>{const o=window.getComputedStyle(e.value).direction;n.value=o==="ltr"||o==="rtl"?o:null}),n}function Ce(e){const n=t.ref("");return t.onMounted(()=>{let o=e.value;for(;o&&o.lang==="";)o=o.parentElement;n.value=o?o.lang:null}),n}function K(e){return n=>typeof n=="string"&&e.indexOf(n)!==-1}const Z="cdx",ke=["default","progressive","destructive"],Se=["normal","primary","quiet"],_e=["x-small","small","medium"],$e=["text","search","number","email","month","password","tel","url","week","date","datetime-local","time"],ie=["default","error"],we=120,Be=500,R="cdx-menu-footer-item",Ie=K(_e),xe=t.defineComponent({name:"CdxIcon",props:{icon:{type:[String,Object],required:!0},iconLabel:{type:String,default:""},lang:{type:String,default:null},dir:{type:String,default:null},size:{type:String,default:"medium",validator:Ie}},emits:["click"],setup(e,{emit:n}){const o=t.ref(),s=be(o),a=Ce(o),d=t.computed(()=>e.dir||s.value),l=t.computed(()=>e.lang||a.value),c=t.computed(()=>({"cdx-icon--flipped":d.value==="rtl"&&l.value!==null&&ye(e.icon,l.value),[`cdx-icon--${e.size}`]:!0})),i=t.computed(()=>ge(e.icon,l.value||"",d.value||"ltr")),u=t.computed(()=>typeof i.value=="string"?i.value:""),p=t.computed(()=>typeof i.value!="string"?i.value.path:"");return{rootElement:o,rootClasses:c,iconSvg:u,iconPath:p,onClick:b=>{n("click",b)}}}}),Pt="",M=(e,n)=>{const o=e.__vccOpts||e;for(const[s,a]of n)o[s]=a;return o},Me=["aria-hidden"],Ve={key:0},Ee=["innerHTML"],Ne=["d"];function ve(e,n,o,s,a,d){return t.openBlock(),t.createElementBlock("span",{ref:"rootElement",class:t.normalizeClass(["cdx-icon",e.rootClasses]),onClick:n[0]||(n[0]=(...l)=>e.onClick&&e.onClick(...l))},[(t.openBlock(),t.createElementBlock("svg",{xmlns:"http://www.w3.org/2000/svg",width:"20",height:"20",viewBox:"0 0 20 20","aria-hidden":e.iconLabel?void 0:!0},[e.iconLabel?(t.openBlock(),t.createElementBlock("title",Ve,t.toDisplayString(e.iconLabel),1)):t.createCommentVNode("",!0),e.iconSvg?(t.openBlock(),t.createElementBlock("g",{key:1,innerHTML:e.iconSvg},null,8,Ee)):(t.openBlock(),t.createElementBlock("path",{key:2,d:e.iconPath},null,8,Ne))],8,Me))],2)}const q=M(xe,[["render",ve]]),Te=t.defineComponent({name:"CdxThumbnail",components:{CdxIcon:q},props:{thumbnail:{type:[Object,null],default:null},placeholderIcon:{type:[String,Object],default:E}},setup:e=>{const n=t.ref(!1),o=t.ref({}),s=a=>{const d=a.replace(/([\\"\n])/g,"\\$1"),l=new Image;l.onload=()=>{o.value={backgroundImage:`url("${d}")`},n.value=!0},l.onerror=()=>{n.value=!1},l.src=d};return t.onMounted(()=>{var a;(a=e.thumbnail)!=null&&a.url&&s(e.thumbnail.url)}),{thumbnailStyle:o,thumbnailLoaded:n}}}),Qt="",Le={class:"cdx-thumbnail"},Ae={key:0,class:"cdx-thumbnail__placeholder"};function Ke(e,n,o,s,a,d){const l=t.resolveComponent("cdx-icon");return t.openBlock(),t.createElementBlock("span",Le,[e.thumbnailLoaded?t.createCommentVNode("",!0):(t.openBlock(),t.createElementBlock("span",Ae,[t.createVNode(l,{icon:e.placeholderIcon,class:"cdx-thumbnail__placeholder__icon--vue"},null,8,["icon"])])),t.createVNode(t.Transition,{name:"cdx-thumbnail__image"},{default:t.withCtx(()=>[e.thumbnailLoaded?(t.openBlock(),t.createElementBlock("span",{key:0,style:t.normalizeStyle(e.thumbnailStyle),class:"cdx-thumbnail__image"},null,4)):t.createCommentVNode("",!0)]),_:1})])}const Re=M(Te,[["render",Ke]]);function De(e){return e.replace(/([\\{}()|.?*+\-^$[\]])/g,"\\$1")}const ze="[̀-ͯ҃-҉֑-ׇֽֿׁׂׅׄؐ-ًؚ-ٰٟۖ-ۜ۟-۪ۤۧۨ-ܑۭܰ-݊ަ-ް߫-߽߳ࠖ-࠙ࠛ-ࠣࠥ-ࠧࠩ-࡙࠭-࡛࣓-ࣣ࣡-ःऺ-़ा-ॏ॑-ॗॢॣঁ-ঃ়া-ৄেৈো-্ৗৢৣ৾ਁ-ਃ਼ਾ-ੂੇੈੋ-੍ੑੰੱੵઁ-ઃ઼ા-ૅે-ૉો-્ૢૣૺ-૿ଁ-ଃ଼ା-ୄେୈୋ-୍ୖୗୢୣஂா-ூெ-ைொ-்ௗఀ-ఄా-ౄె-ైొ-్ౕౖౢౣಁ-ಃ಼ಾ-ೄೆ-ೈೊ-್ೕೖೢೣഀ-ഃ഻഼ാ-ൄെ-ൈൊ-്ൗൢൣංඃ්ා-ුූෘ-ෟෲෳัิ-ฺ็-๎ັິ-ູົຼ່-ໍ༹༘༙༵༷༾༿ཱ-྄྆྇ྍ-ྗྙ-ྼ࿆ါ-ှၖ-ၙၞ-ၠၢ-ၤၧ-ၭၱ-ၴႂ-ႍႏႚ-ႝ፝-፟ᜒ-᜔ᜲ-᜴ᝒᝓᝲᝳ឴-៓៝᠋-᠍ᢅᢆᢩᤠ-ᤫᤰ-᤻ᨗ-ᨛᩕ-ᩞ᩠-᩿᩼᪰-᪾ᬀ-ᬄ᬴-᭄᭫-᭳ᮀ-ᮂᮡ-ᮭ᯦-᯳ᰤ-᰷᳐-᳔᳒-᳨᳭ᳲ-᳴᳷-᳹᷀-᷹᷻-᷿⃐-⃰⳯-⵿⳱ⷠ-〪ⷿ-゙゚〯꙯-꙲ꙴ-꙽ꚞꚟ꛰꛱ꠂ꠆ꠋꠣ-ꠧꢀꢁꢴ-ꣅ꣠-꣱ꣿꤦ-꤭ꥇ-꥓ꦀ-ꦃ꦳-꧀ꧥꨩ-ꨶꩃꩌꩍꩻ-ꩽꪰꪲ-ꪴꪷꪸꪾ꪿꫁ꫫ-ꫯꫵ꫶ꯣ-ꯪ꯬꯭ﬞ︀-️︠-︯]";function Oe(e,n){if(!e)return[n,"",""];const o=De(e),s=new RegExp(o+ze+"*","i").exec(n);if(!s||s.index===void 0)return[n,"",""];const a=s.index,d=a+s[0].length,l=n.slice(a,d),c=n.slice(0,a),i=n.slice(d,n.length);return[c,l,i]}const Fe=t.defineComponent({name:"CdxSearchResultTitle",props:{title:{type:String,required:!0},searchQuery:{type:String,default:""}},setup:e=>({titleChunks:t.computed(()=>Oe(e.searchQuery,String(e.title)))})}),Ut="",qe={class:"cdx-search-result-title"},He={class:"cdx-search-result-title__match"};function Pe(e,n,o,s,a,d){return t.openBlock(),t.createElementBlock("span",qe,[t.createElementVNode("bdi",null,[t.createTextVNode(t.toDisplayString(e.titleChunks[0]),1),t.createElementVNode("span",He,t.toDisplayString(e.titleChunks[1]),1),t.createTextVNode(t.toDisplayString(e.titleChunks[2]),1)])])}const Qe=M(Fe,[["render",Pe]]),Ue=t.defineComponent({name:"CdxMenuItem",components:{CdxIcon:q,CdxThumbnail:Re,CdxSearchResultTitle:Qe},props:{id:{type:String,required:!0},value:{type:[String,Number],required:!0},disabled:{type:Boolean,default:!1},selected:{type:Boolean,default:!1},active:{type:Boolean,default:!1},highlighted:{type:Boolean,default:!1},label:{type:String,default:""},match:{type:String,default:""},supportingText:{type:String,default:""},url:{type:String,default:""},icon:{type:[String,Object],default:""},showThumbnail:{type:Boolean,default:!1},thumbnail:{type:[Object,null],default:null},description:{type:[String,null],default:""},searchQuery:{type:String,default:""},boldLabel:{type:Boolean,default:!1},hideDescriptionOverflow:{type:Boolean,default:!1},language:{type:Object,default:()=>({})}},emits:["change"],setup:(e,{emit:n})=>{const o=()=>{e.highlighted||n("change","highlighted",!0)},s=()=>{n("change","highlighted",!1)},a=p=>{p.button===0&&n("change","active",!0)},d=()=>{n("change","selected",!0)},l=t.computed(()=>e.searchQuery.length>0),c=t.computed(()=>({"cdx-menu-item--selected":e.selected,"cdx-menu-item--active":e.active&&e.highlighted,"cdx-menu-item--highlighted":e.highlighted,"cdx-menu-item--enabled":!e.disabled,"cdx-menu-item--disabled":e.disabled,"cdx-menu-item--highlight-query":l.value,"cdx-menu-item--bold-label":e.boldLabel,"cdx-menu-item--has-description":!!e.description,"cdx-menu-item--hide-description-overflow":e.hideDescriptionOverflow})),i=t.computed(()=>e.url?"a":"span"),u=t.computed(()=>e.label||String(e.value));return{onMouseMove:o,onMouseLeave:s,onMouseDown:a,onClick:d,highlightQuery:l,rootClasses:c,contentTag:i,title:u}}}),jt="",je=["id","aria-disabled","aria-selected"],We={class:"cdx-menu-item__text"},Ge=["lang"],Je=["lang"],Xe=["lang"],Ye=["lang"];function Ze(e,n,o,s,a,d){const l=t.resolveComponent("cdx-thumbnail"),c=t.resolveComponent("cdx-icon"),i=t.resolveComponent("cdx-search-result-title");return t.openBlock(),t.createElementBlock("li",{id:e.id,role:"option",class:t.normalizeClass(["cdx-menu-item",e.rootClasses]),"aria-disabled":e.disabled,"aria-selected":e.selected,onMousemove:n[0]||(n[0]=(...u)=>e.onMouseMove&&e.onMouseMove(...u)),onMouseleave:n[1]||(n[1]=(...u)=>e.onMouseLeave&&e.onMouseLeave(...u)),onMousedown:n[2]||(n[2]=t.withModifiers((...u)=>e.onMouseDown&&e.onMouseDown(...u),["prevent"])),onClick:n[3]||(n[3]=(...u)=>e.onClick&&e.onClick(...u))},[t.renderSlot(e.$slots,"default",{},()=>[(t.openBlock(),t.createBlock(t.resolveDynamicComponent(e.contentTag),{href:e.url?e.url:void 0,class:"cdx-menu-item__content"},{default:t.withCtx(()=>{var u,p,C,b,_,I;return[e.showThumbnail?(t.openBlock(),t.createBlock(l,{key:0,thumbnail:e.thumbnail,class:"cdx-menu-item__thumbnail"},null,8,["thumbnail"])):e.icon?(t.openBlock(),t.createBlock(c,{key:1,icon:e.icon,class:"cdx-menu-item__icon"},null,8,["icon"])):t.createCommentVNode("",!0),t.createElementVNode("span",We,[e.highlightQuery?(t.openBlock(),t.createBlock(i,{key:0,title:e.title,"search-query":e.searchQuery,lang:(u=e.language)==null?void 0:u.label},null,8,["title","search-query","lang"])):(t.openBlock(),t.createElementBlock("span",{key:1,class:"cdx-menu-item__text__label",lang:(p=e.language)==null?void 0:p.label},[t.createElementVNode("bdi",null,t.toDisplayString(e.title),1)],8,Ge)),e.match?(t.openBlock(),t.createElementBlock(t.Fragment,{key:2},[t.createTextVNode(t.toDisplayString(" ")+" "),e.highlightQuery?(t.openBlock(),t.createBlock(i,{key:0,title:e.match,"search-query":e.searchQuery,lang:(C=e.language)==null?void 0:C.match},null,8,["title","search-query","lang"])):(t.openBlock(),t.createElementBlock("span",{key:1,class:"cdx-menu-item__text__match",lang:(b=e.language)==null?void 0:b.match},[t.createElementVNode("bdi",null,t.toDisplayString(e.match),1)],8,Je))],64)):t.createCommentVNode("",!0),e.supportingText?(t.openBlock(),t.createElementBlock(t.Fragment,{key:3},[t.createTextVNode(t.toDisplayString(" ")+" "),t.createElementVNode("span",{class:"cdx-menu-item__text__supporting-text",lang:(_=e.language)==null?void 0:_.supportingText},[t.createElementVNode("bdi",null,t.toDisplayString(e.supportingText),1)],8,Xe)],64)):t.createCommentVNode("",!0),e.description?(t.openBlock(),t.createElementBlock("span",{key:4,class:"cdx-menu-item__text__description",lang:(I=e.language)==null?void 0:I.description},[t.createElementVNode("bdi",null,t.toDisplayString(e.description),1)],8,Ye)):t.createCommentVNode("",!0)])]}),_:1},8,["href"]))])],42,je)}const et=M(Ue,[["render",Ze]]),tt=t.defineComponent({name:"CdxProgressBar",props:{inline:{type:Boolean,default:!1},disabled:{type:Boolean,default:!1}},setup(e){return{rootClasses:t.computed(()=>({"cdx-progress-bar--block":!e.inline,"cdx-progress-bar--inline":e.inline,"cdx-progress-bar--enabled":!e.disabled,"cdx-progress-bar--disabled":e.disabled}))}}}),Wt="",nt=["aria-disabled"],ot=[t.createElementVNode("div",{class:"cdx-progress-bar__bar"},null,-1)];function lt(e,n,o,s,a,d){return t.openBlock(),t.createElementBlock("div",{class:t.normalizeClass(["cdx-progress-bar",e.rootClasses]),role:"progressbar","aria-disabled":e.disabled,"aria-valuemin":"0","aria-valuemax":"100"},ot,10,nt)}const at=M(tt,[["render",lt]]);let ee=0;function re(e){const n=t.getCurrentInstance(),o=(n==null?void 0:n.props.id)||(n==null?void 0:n.attrs.id);return e?`${Z}-${e}-${ee++}`:o?`${Z}-${o}-${ee++}`:`${Z}-${ee++}`}function st(e,n){const o=t.ref(!1);let s=!1;if(typeof window!="object"||!("IntersectionObserver"in window&&"IntersectionObserverEntry"in window&&"intersectionRatio"in window.IntersectionObserverEntry.prototype))return o;const a=new window.IntersectionObserver(d=>{const l=d[0];l&&(o.value=l.isIntersecting)},n);return t.onMounted(()=>{s=!0,e.value&&a.observe(e.value)}),t.onUnmounted(()=>{s=!1,a.disconnect()}),t.watch(e,d=>{s&&(a.disconnect(),o.value=!1,d&&a.observe(d))}),o}function Q(e,n=t.computed(()=>({}))){const o=t.computed(()=>{const d=J(n.value,[]);return e.class&&e.class.split(" ").forEach(c=>{d[c]=!0}),d}),s=t.computed(()=>{if("style"in e)return e.style}),a=t.computed(()=>{const i=e,{class:d,style:l}=i;return J(i,["class","style"])});return{rootClasses:o,rootStyle:s,otherAttrs:a}}const it=t.defineComponent({name:"CdxMenu",components:{CdxMenuItem:et,CdxProgressBar:at},inheritAttrs:!1,props:{menuItems:{type:Array,required:!0},footer:{type:Object,default:null},selected:{type:[String,Number,null],required:!0},expanded:{type:Boolean,required:!0},showPending:{type:Boolean,default:!1},visibleItemLimit:{type:Number,default:null},showThumbnail:{type:Boolean,default:!1},boldLabel:{type:Boolean,default:!1},hideDescriptionOverflow:{type:Boolean,default:!1},searchQuery:{type:String,default:""},showNoResultsSlot:{type:Boolean,default:null}},emits:["update:selected","update:expanded","menu-item-click","menu-item-keyboard-navigation","load-more"],expose:["clearActive","getHighlightedMenuItem","getHighlightedViaKeyboard","delegateKeyNavigation"],setup(e,{emit:n,slots:o,attrs:s}){const a=t.computed(()=>(e.footer&&e.menuItems?[...e.menuItems,e.footer]:e.menuItems).map(m=>fe(me({},m),{id:re("menu-item")}))),d=t.computed(()=>o["no-results"]?e.showNoResultsSlot!==null?e.showNoResultsSlot:a.value.length===0:!1),l=t.ref(null),c=t.ref(!1),i=t.ref(null);function u(){return a.value.find(r=>r.value===e.selected)}function p(r,m){var g;if(!(m&&m.disabled))switch(r){case"selected":n("update:selected",(g=m==null?void 0:m.value)!=null?g:null),n("update:expanded",!1),i.value=null;break;case"highlighted":l.value=m||null,c.value=!1;break;case"highlightedViaKeyboard":l.value=m||null,c.value=!0;break;case"active":i.value=m||null;break}}const C=t.computed(()=>{if(l.value!==null)return a.value.findIndex(r=>r.value===l.value.value)});function b(r){r&&(p("highlightedViaKeyboard",r),n("menu-item-keyboard-navigation",r))}function _(r){var S;const m=H=>{for(let A=H-1;A>=0;A--)if(!a.value[A].disabled)return a.value[A]};r=r||a.value.length;const g=(S=m(r))!=null?S:m(a.value.length);b(g)}function I(r){const m=S=>a.value.find((H,A)=>!H.disabled&&A>S);r=r!=null?r:-1;const g=m(r)||m(-1);b(g)}function V(r,m=!0){function g(){n("update:expanded",!0),p("highlighted",u())}function S(){m&&(r.preventDefault(),r.stopPropagation())}switch(r.key){case"Enter":case" ":return S(),e.expanded?(l.value&&c.value&&n("update:selected",l.value.value),n("update:expanded",!1)):g(),!0;case"Tab":return e.expanded&&(l.value&&c.value&&n("update:selected",l.value.value),n("update:expanded",!1)),!0;case"ArrowUp":return S(),e.expanded?(l.value===null&&p("highlightedViaKeyboard",u()),_(C.value)):g(),T(),!0;case"ArrowDown":return S(),e.expanded?(l.value===null&&p("highlightedViaKeyboard",u()),I(C.value)):g(),T(),!0;case"Home":return S(),e.expanded?(l.value===null&&p("highlightedViaKeyboard",u()),I()):g(),T(),!0;case"End":return S(),e.expanded?(l.value===null&&p("highlightedViaKeyboard",u()),_()):g(),T(),!0;case"Escape":return S(),n("update:expanded",!1),!0;default:return!1}}function k(){p("active")}const $=[],U=t.ref(void 0),x=st(U,{threshold:.8});t.watch(x,r=>{r&&n("load-more")});function ne(r,m){if(r){$[m]=r.$el;const g=e.visibleItemLimit;if(!g||e.menuItems.length<g)return;const S=Math.min(g,Math.max(2,Math.floor(.2*e.menuItems.length)));m===e.menuItems.length-S&&(U.value=r.$el)}}function T(){if(!e.visibleItemLimit||e.visibleItemLimit>e.menuItems.length||C.value===void 0)return;const r=C.value>=0?C.value:0;$[r].scrollIntoView({behavior:"smooth",block:"nearest"})}const L=t.ref(null),D=t.ref(null);function j(){if(D.value=null,!e.visibleItemLimit||$.length<=e.visibleItemLimit){L.value=null;return}const r=$[0],m=$[e.visibleItemLimit];if(L.value=oe(r,m),e.footer){const g=$[$.length-1];D.value=g.scrollHeight}}function oe(r,m){const g=r.getBoundingClientRect().top;return m.getBoundingClientRect().top-g+2}t.onMounted(()=>{document.addEventListener("mouseup",k)}),t.onUnmounted(()=>{document.removeEventListener("mouseup",k)}),t.watch(t.toRef(e,"expanded"),r=>se(this,null,function*(){const m=u();!r&&l.value&&m===void 0&&p("highlighted"),r&&m!==void 0&&p("highlighted",m),r&&(yield t.nextTick(),j(),yield t.nextTick(),T())})),t.watch(t.toRef(e,"menuItems"),r=>se(this,null,function*(){r.length<$.length&&($.length=r.length),e.expanded&&(yield t.nextTick(),j(),yield t.nextTick(),T())}),{deep:!0});const le=t.computed(()=>({"max-height":L.value?`${L.value}px`:void 0,"overflow-y":L.value?"scroll":void 0,"margin-bottom":D.value?`${D.value}px`:void 0})),z=t.computed(()=>({"cdx-menu--has-footer":!!e.footer,"cdx-menu--has-sticky-footer":!!e.footer&&!!L.value})),{rootClasses:N,rootStyle:W,otherAttrs:ae}=Q(s,z);return{listBoxStyle:le,rootClasses:N,rootStyle:W,otherAttrs:ae,assignTemplateRef:ne,computedMenuItems:a,computedShowNoResultsSlot:d,highlightedMenuItem:l,highlightedViaKeyboard:c,activeMenuItem:i,handleMenuItemChange:p,handleKeyNavigation:V}},methods:{getHighlightedMenuItem(){return this.highlightedMenuItem},getHighlightedViaKeyboard(){return this.highlightedViaKeyboard},clearActive(){this.handleMenuItemChange("active")},delegateKeyNavigation(e,n=!0){return this.handleKeyNavigation(e,n)}}}),Jt="",rt={key:0,class:"cdx-menu__pending cdx-menu-item"},dt={key:1,class:"cdx-menu__no-results cdx-menu-item"};function ct(e,n,o,s,a,d){const l=t.resolveComponent("cdx-menu-item"),c=t.resolveComponent("cdx-progress-bar");return t.withDirectives((t.openBlock(),t.createElementBlock("div",{class:t.normalizeClass(["cdx-menu",e.rootClasses]),style:t.normalizeStyle(e.rootStyle)},[t.createElementVNode("ul",t.mergeProps({class:"cdx-menu__listbox",role:"listbox","aria-multiselectable":"false",style:e.listBoxStyle},e.otherAttrs),[e.showPending&&e.computedMenuItems.length===0&&e.$slots.pending?(t.openBlock(),t.createElementBlock("li",rt,[t.renderSlot(e.$slots,"pending")])):t.createCommentVNode("",!0),e.computedShowNoResultsSlot?(t.openBlock(),t.createElementBlock("li",dt,[t.renderSlot(e.$slots,"no-results")])):t.createCommentVNode("",!0),(t.openBlock(!0),t.createElementBlock(t.Fragment,null,t.renderList(e.computedMenuItems,(i,u)=>{var p,C;return t.openBlock(),t.createBlock(l,t.mergeProps({key:i.value,ref_for:!0,ref:b=>e.assignTemplateRef(b,u)},i,{selected:i.value===e.selected,active:i.value===((p=e.activeMenuItem)==null?void 0:p.value),highlighted:i.value===((C=e.highlightedMenuItem)==null?void 0:C.value),"show-thumbnail":e.showThumbnail,"bold-label":e.boldLabel,"hide-description-overflow":e.hideDescriptionOverflow,"search-query":e.searchQuery,onChange:(b,_)=>e.handleMenuItemChange(b,_&&i),onClick:b=>e.$emit("menu-item-click",i)}),{default:t.withCtx(()=>{var b,_;return[t.renderSlot(e.$slots,"default",{menuItem:i,active:i.value===((b=e.activeMenuItem)==null?void 0:b.value)&&i.value===((_=e.highlightedMenuItem)==null?void 0:_.value)})]}),_:2},1040,["selected","active","highlighted","show-thumbnail","bold-label","hide-description-overflow","search-query","onChange","onClick"])}),128)),e.showPending?(t.openBlock(),t.createBlock(c,{key:2,class:"cdx-menu__progress-bar",inline:!0})):t.createCommentVNode("",!0)],16)],6)),[[t.vShow,e.expanded]])}const ut=M(it,[["render",ct]]),ht=K(ke),pt=K(Se),mt=e=>{!e["aria-label"]&&!e["aria-hidden"]&&t.warn(`icon-only buttons require one of the following attribute: aria-label or aria-hidden.
		See documentation on https://doc.wikimedia.org/codex/latest/components/button.html#default-icon-only`)};function te(e){const n=[];for(const o of e)typeof o=="string"&&o.trim()!==""?n.push(o):Array.isArray(o)?n.push(...te(o)):typeof o=="object"&&o&&(typeof o.type=="string"||typeof o.type=="object"?n.push(o):o.type!==t.Comment&&(typeof o.children=="string"&&o.children.trim()!==""?n.push(o.children):Array.isArray(o.children)&&n.push(...te(o.children))));return n}const ft=(e,n)=>{if(!e)return!1;const o=te(e);if(o.length!==1)return!1;const s=o[0],a=typeof s=="object"&&typeof s.type=="object"&&"name"in s.type&&s.type.name===q.name,d=typeof s=="object"&&s.type==="svg";return a||d?(mt(n),!0):!1},gt=t.defineComponent({name:"CdxButton",props:{action:{type:String,default:"default",validator:ht},weight:{type:String,default:"normal",validator:pt}},emits:["click"],setup(e,{emit:n,slots:o,attrs:s}){const a=t.ref(!1);return{rootClasses:t.computed(()=>{var i;return{[`cdx-button--action-${e.action}`]:!0,[`cdx-button--weight-${e.weight}`]:!0,"cdx-button--framed":e.weight!=="quiet","cdx-button--icon-only":ft((i=o.default)==null?void 0:i.call(o),s),"cdx-button--is-active":a.value}}),onClick:i=>{n("click",i)},setActive:i=>{a.value=i}}}}),Xt="";function yt(e,n,o,s,a,d){return t.openBlock(),t.createElementBlock("button",{class:t.normalizeClass(["cdx-button",e.rootClasses]),onClick:n[0]||(n[0]=(...l)=>e.onClick&&e.onClick(...l)),onKeydown:n[1]||(n[1]=t.withKeys(l=>e.setActive(!0),["space","enter"])),onKeyup:n[2]||(n[2]=t.withKeys(l=>e.setActive(!1),["space","enter"]))},[t.renderSlot(e.$slots,"default")],34)}const bt=M(gt,[["render",yt]]);function de(e,n,o){return t.computed({get:()=>e.value,set:s=>n(o||"update:modelValue",s)})}const Ct=K($e),kt=K(ie),St=t.defineComponent({name:"CdxTextInput",components:{CdxIcon:q},inheritAttrs:!1,expose:["focus"],props:{modelValue:{type:[String,Number],default:""},inputType:{type:String,default:"text",validator:Ct},status:{type:String,default:"default",validator:kt},disabled:{type:Boolean,default:!1},startIcon:{type:[String,Object],default:void 0},endIcon:{type:[String,Object],default:void 0},clearable:{type:Boolean,default:!1}},emits:["update:modelValue","keydown","input","change","focus","blur"],setup(e,{emit:n,attrs:o}){const s=de(t.toRef(e,"modelValue"),n),a=t.computed(()=>e.clearable&&!!s.value&&!e.disabled),d=t.computed(()=>({"cdx-text-input--has-start-icon":!!e.startIcon,"cdx-text-input--has-end-icon":!!e.endIcon,"cdx-text-input--clearable":a.value,[`cdx-text-input--status-${e.status}`]:!0})),{rootClasses:l,rootStyle:c,otherAttrs:i}=Q(o,d),u=t.computed(()=>({"cdx-text-input__input--has-value":!!s.value}));return{wrappedModel:s,isClearable:a,rootClasses:l,rootStyle:c,otherAttrs:i,inputClasses:u,onClear:()=>{s.value=""},onInput:k=>{n("input",k)},onChange:k=>{n("change",k)},onKeydown:k=>{(k.key==="Home"||k.key==="End")&&!k.ctrlKey&&!k.metaKey||n("keydown",k)},onFocus:k=>{n("focus",k)},onBlur:k=>{n("blur",k)},cdxIconClear:O}},methods:{focus(){this.$refs.input.focus()}}}),Yt="",_t=["type","disabled"];function $t(e,n,o,s,a,d){const l=t.resolveComponent("cdx-icon");return t.openBlock(),t.createElementBlock("div",{class:t.normalizeClass(["cdx-text-input",e.rootClasses]),style:t.normalizeStyle(e.rootStyle)},[t.withDirectives(t.createElementVNode("input",t.mergeProps({ref:"input","onUpdate:modelValue":n[0]||(n[0]=c=>e.wrappedModel=c),class:["cdx-text-input__input",e.inputClasses]},e.otherAttrs,{type:e.inputType,disabled:e.disabled,onInput:n[1]||(n[1]=(...c)=>e.onInput&&e.onInput(...c)),onChange:n[2]||(n[2]=(...c)=>e.onChange&&e.onChange(...c)),onFocus:n[3]||(n[3]=(...c)=>e.onFocus&&e.onFocus(...c)),onBlur:n[4]||(n[4]=(...c)=>e.onBlur&&e.onBlur(...c)),onKeydown:n[5]||(n[5]=(...c)=>e.onKeydown&&e.onKeydown(...c))}),null,16,_t),[[t.vModelDynamic,e.wrappedModel]]),e.startIcon?(t.openBlock(),t.createBlock(l,{key:0,icon:e.startIcon,class:"cdx-text-input__icon-vue cdx-text-input__start-icon"},null,8,["icon"])):t.createCommentVNode("",!0),e.endIcon?(t.openBlock(),t.createBlock(l,{key:1,icon:e.endIcon,class:"cdx-text-input__icon-vue cdx-text-input__end-icon"},null,8,["icon"])):t.createCommentVNode("",!0),e.isClearable?(t.openBlock(),t.createBlock(l,{key:2,icon:e.cdxIconClear,class:"cdx-text-input__icon-vue cdx-text-input__clear-icon",onMousedown:n[6]||(n[6]=t.withModifiers(()=>{},["prevent"])),onClick:e.onClear},null,8,["icon","onClick"])):t.createCommentVNode("",!0)],6)}const wt=M(St,[["render",$t]]),Bt=K(ie),It=t.defineComponent({name:"CdxSearchInput",components:{CdxButton:bt,CdxTextInput:wt},inheritAttrs:!1,props:{modelValue:{type:[String,Number],default:""},buttonLabel:{type:String,default:""},status:{type:String,default:"default",validator:Bt}},emits:["update:modelValue","submit-click"],setup(e,{emit:n,attrs:o}){const s=de(t.toRef(e,"modelValue"),n),a=t.computed(()=>({"cdx-search-input--has-end-button":!!e.buttonLabel})),{rootClasses:d,rootStyle:l,otherAttrs:c}=Q(o,a);return{wrappedModel:s,rootClasses:d,rootStyle:l,otherAttrs:c,handleSubmit:()=>{n("submit-click",s.value)},searchIcon:F}},methods:{focus(){this.$refs.textInput.focus()}}}),Zt="",xt={class:"cdx-search-input__input-wrapper"};function Mt(e,n,o,s,a,d){const l=t.resolveComponent("cdx-text-input"),c=t.resolveComponent("cdx-button");return t.openBlock(),t.createElementBlock("div",{class:t.normalizeClass(["cdx-search-input",e.rootClasses]),style:t.normalizeStyle(e.rootStyle)},[t.createElementVNode("div",xt,[t.createVNode(l,t.mergeProps({ref:"textInput",modelValue:e.wrappedModel,"onUpdate:modelValue":n[0]||(n[0]=i=>e.wrappedModel=i),class:"cdx-search-input__text-input","input-type":"search","start-icon":e.searchIcon,status:e.status},e.otherAttrs,{onKeydown:t.withKeys(e.handleSubmit,["enter"])}),null,16,["modelValue","start-icon","status","onKeydown"]),t.renderSlot(e.$slots,"default")]),e.buttonLabel?(t.openBlock(),t.createBlock(c,{key:0,class:"cdx-search-input__end-button",onClick:e.handleSubmit},{default:t.withCtx(()=>[t.createTextVNode(t.toDisplayString(e.buttonLabel),1)]),_:1},8,["onClick"])):t.createCommentVNode("",!0)],6)}const Vt=M(It,[["render",Mt]]),Et=t.defineComponent({name:"CdxTypeaheadSearch",components:{CdxIcon:q,CdxMenu:ut,CdxSearchInput:Vt},inheritAttrs:!1,props:{id:{type:String,required:!0},formAction:{type:String,required:!0},searchResultsLabel:{type:String,required:!0},searchResults:{type:Array,required:!0},buttonLabel:{type:String,default:""},initialInputValue:{type:String,default:""},searchFooterUrl:{type:String,default:""},debounceInterval:{type:Number,default:we},highlightQuery:{type:Boolean,default:!1},showThumbnail:{type:Boolean,default:!1},autoExpandWidth:{type:Boolean,default:!1},visibleItemLimit:{type:Number,default:null}},emits:["input","search-result-click","submit","load-more"],setup(e,{attrs:n,emit:o,slots:s}){const{searchResults:a,searchFooterUrl:d,debounceInterval:l}=t.toRefs(e),c=t.ref(),i=t.ref(),u=re("typeahead-search-menu"),p=t.ref(!1),C=t.ref(!1),b=t.ref(!1),_=t.ref(!1),I=t.ref(e.initialInputValue),V=t.ref(""),k=t.computed(()=>{var h,w;return(w=(h=i.value)==null?void 0:h.getHighlightedMenuItem())==null?void 0:w.id}),$=t.ref(null),U=t.computed(()=>({"cdx-typeahead-search__menu-message--has-thumbnail":e.showThumbnail})),x=t.computed(()=>e.searchResults.find(h=>h.value===$.value)),ne=t.computed(()=>d.value?{value:R,url:d.value}:void 0),T=t.computed(()=>({"cdx-typeahead-search--show-thumbnail":e.showThumbnail,"cdx-typeahead-search--expanded":p.value,"cdx-typeahead-search--auto-expand-width":e.showThumbnail&&e.autoExpandWidth})),{rootClasses:L,rootStyle:D,otherAttrs:j}=Q(n,T);function oe(h){return h}const le=t.computed(()=>({visibleItemLimit:e.visibleItemLimit,showThumbnail:e.showThumbnail,boldLabel:!0,hideDescriptionOverflow:!0}));let z,N;function W(h,w=!1){x.value&&x.value.label!==h&&x.value.value!==h&&($.value=null),N!==void 0&&(clearTimeout(N),N=void 0),h===""?p.value=!1:(C.value=!0,s["search-results-pending"]&&(N=setTimeout(()=>{_.value&&(p.value=!0),b.value=!0},Be))),z!==void 0&&(clearTimeout(z),z=void 0);const v=()=>{o("input",h)};w?v():z=setTimeout(()=>{v()},l.value)}function ae(h){if(h===R){$.value=null,I.value=V.value;return}$.value=h,h!==null&&(I.value=x.value?x.value.label||String(x.value.value):"")}function r(){_.value=!0,(V.value||b.value)&&(p.value=!0)}function m(){_.value=!1,p.value=!1}function g(h){const ce=h,{id:w}=ce,v=J(ce,["id"]);if(v.value===R){o("search-result-click",{searchResult:null,index:a.value.length,numberOfResults:a.value.length});return}S(v)}function S(h){const w={searchResult:h,index:a.value.findIndex(v=>v.value===h.value),numberOfResults:a.value.length};o("search-result-click",w)}function H(h){if(h.value===R){I.value=V.value;return}I.value=h.value?h.label||String(h.value):""}function A(h){var w;p.value=!1,(w=i.value)==null||w.clearActive(),g(h)}function zt(h){if(x.value)S(x.value),h.stopPropagation(),window.location.assign(x.value.url),h.preventDefault();else{const w={searchResult:null,index:-1,numberOfResults:a.value.length};o("submit",w)}}function Ot(h){if(!i.value||!V.value||h.key===" ")return;const w=i.value.getHighlightedMenuItem(),v=i.value.getHighlightedViaKeyboard();switch(h.key){case"Enter":w&&(w.value===R&&v?window.location.assign(d.value):i.value.delegateKeyNavigation(h,!1)),p.value=!1;break;case"Tab":p.value=!1;break;default:i.value.delegateKeyNavigation(h);break}}return t.onMounted(()=>{e.initialInputValue&&W(e.initialInputValue,!0)}),t.watch(t.toRef(e,"searchResults"),()=>{V.value=I.value.trim(),_.value&&C.value&&V.value.length>0&&(p.value=!0),N!==void 0&&(clearTimeout(N),N=void 0),C.value=!1,b.value=!1}),{form:c,menu:i,menuId:u,highlightedId:k,selection:$,menuMessageClass:U,footer:ne,asSearchResult:oe,inputValue:I,searchQuery:V,expanded:p,showPending:b,rootClasses:L,rootStyle:D,otherAttrs:j,menuConfig:le,onUpdateInputValue:W,onUpdateMenuSelection:ae,onFocus:r,onBlur:m,onSearchResultClick:g,onSearchResultKeyboardNavigation:H,onSearchFooterClick:A,onSubmit:zt,onKeydown:Ot,MenuFooterValue:R,articleIcon:Y}},methods:{focus(){this.$refs.searchInput.focus()}}}),en="",Nt=["id","action"],vt={class:"cdx-typeahead-search__menu-message__text"},Tt={class:"cdx-typeahead-search__menu-message__text"},Lt=["href","onClickCapture"],At={class:"cdx-typeahead-search__search-footer__text"},Kt={class:"cdx-typeahead-search__search-footer__query"};function Rt(e,n,o,s,a,d){const l=t.resolveComponent("cdx-icon"),c=t.resolveComponent("cdx-menu"),i=t.resolveComponent("cdx-search-input");return t.openBlock(),t.createElementBlock("div",{class:t.normalizeClass(["cdx-typeahead-search",e.rootClasses]),style:t.normalizeStyle(e.rootStyle)},[t.createElementVNode("form",{id:e.id,ref:"form",class:"cdx-typeahead-search__form",action:e.formAction,onSubmit:n[4]||(n[4]=(...u)=>e.onSubmit&&e.onSubmit(...u))},[t.createVNode(i,t.mergeProps({ref:"searchInput",modelValue:e.inputValue,"onUpdate:modelValue":n[3]||(n[3]=u=>e.inputValue=u),"button-label":e.buttonLabel},e.otherAttrs,{class:"cdx-typeahead-search__input",name:"search",role:"combobox",autocomplete:"off","aria-autocomplete":"list","aria-owns":e.menuId,"aria-expanded":e.expanded,"aria-activedescendant":e.highlightedId,"onUpdate:modelValue":e.onUpdateInputValue,onFocus:e.onFocus,onBlur:e.onBlur,onKeydown:e.onKeydown}),{default:t.withCtx(()=>[t.createVNode(c,t.mergeProps({id:e.menuId,ref:"menu",expanded:e.expanded,"onUpdate:expanded":n[0]||(n[0]=u=>e.expanded=u),"show-pending":e.showPending,selected:e.selection,"menu-items":e.searchResults,footer:e.footer,"search-query":e.highlightQuery?e.searchQuery:"","show-no-results-slot":e.searchQuery.length>0&&e.searchResults.length===0&&e.$slots["search-no-results-text"]&&e.$slots["search-no-results-text"]().length>0},e.menuConfig,{"aria-label":e.searchResultsLabel,"onUpdate:selected":e.onUpdateMenuSelection,onMenuItemClick:n[1]||(n[1]=u=>e.onSearchResultClick(e.asSearchResult(u))),onMenuItemKeyboardNavigation:e.onSearchResultKeyboardNavigation,onLoadMore:n[2]||(n[2]=u=>e.$emit("load-more"))}),{pending:t.withCtx(()=>[t.createElementVNode("div",{class:t.normalizeClass(["cdx-typeahead-search__menu-message",e.menuMessageClass])},[t.createElementVNode("span",vt,[t.renderSlot(e.$slots,"search-results-pending")])],2)]),"no-results":t.withCtx(()=>[t.createElementVNode("div",{class:t.normalizeClass(["cdx-typeahead-search__menu-message",e.menuMessageClass])},[t.createElementVNode("span",Tt,[t.renderSlot(e.$slots,"search-no-results-text")])],2)]),default:t.withCtx(({menuItem:u,active:p})=>[u.value===e.MenuFooterValue?(t.openBlock(),t.createElementBlock("a",{key:0,class:t.normalizeClass(["cdx-typeahead-search__search-footer",{"cdx-typeahead-search__search-footer__active":p}]),href:e.asSearchResult(u).url,onClickCapture:t.withModifiers(C=>e.onSearchFooterClick(e.asSearchResult(u)),["stop"])},[t.createVNode(l,{class:"cdx-typeahead-search__search-footer__icon",icon:e.articleIcon},null,8,["icon"]),t.createElementVNode("span",At,[t.renderSlot(e.$slots,"search-footer-text",{searchQuery:e.searchQuery},()=>[t.createElementVNode("strong",Kt,t.toDisplayString(e.searchQuery),1)])])],42,Lt)):t.createCommentVNode("",!0)]),_:3},16,["id","expanded","show-pending","selected","menu-items","footer","search-query","show-no-results-slot","aria-label","onUpdate:selected","onMenuItemKeyboardNavigation"])]),_:3},16,["modelValue","button-label","aria-owns","aria-expanded","aria-activedescendant","onUpdate:modelValue","onFocus","onBlur","onKeydown"]),t.renderSlot(e.$slots,"default")],40,Nt)],6)}const Dt=M(Et,[["render",Rt]]);f.CdxTypeaheadSearch=Dt,Object.defineProperty(f,Symbol.toStringTag,{value:"Module"})});
