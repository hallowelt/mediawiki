"use strict";var se=Object.defineProperty,de=Object.defineProperties;var ce=Object.getOwnPropertyDescriptors;var W=Object.getOwnPropertySymbols;var fe=Object.prototype.hasOwnProperty,ve=Object.prototype.propertyIsEnumerable;var z=(e,a,u)=>a in e?se(e,a,{enumerable:!0,configurable:!0,writable:!0,value:u}):e[a]=u,N=(e,a)=>{for(var u in a||(a={}))fe.call(a,u)&&z(e,u,a[u]);if(W)for(var u of W(a))ve.call(a,u)&&z(e,u,a[u]);return e},R=(e,a)=>de(e,ce(a));var T=(e,a,u)=>new Promise((M,b)=>{var f=c=>{try{r(u.next(c))}catch(o){b(o)}},_=c=>{try{r(u.throw(c))}catch(o){b(o)}},r=c=>c.done?M(c.value):Promise.resolve(c.value).then(f,_);r((u=u.apply(e,a)).next())});const t=require("vue"),he=require("./CdxMenuItem.cjs"),ge=require("./Icon.js"),me=require("./CdxProgressBar.cjs"),pe=require("./useIntersectionObserver.cjs"),be=require("./useSplitAttributes.cjs"),ye=require("./_plugin-vue_export-helper.js");function K(e){return e!==null&&Array.isArray(e)}function $(e){return"items"in e}const ke=t.defineComponent({name:"CdxMenu",components:{CdxMenuItem:he,CdxIcon:ge.CdxIcon,CdxProgressBar:me},inheritAttrs:!1,props:{menuItems:{type:Array,required:!0},footer:{type:Object,default:null},selected:{type:[String,Number,Array,null],required:!0},expanded:{type:Boolean,required:!0},showPending:{type:Boolean,default:!1},visibleItemLimit:{type:Number,default:null},showThumbnail:{type:Boolean,default:!1},boldLabel:{type:Boolean,default:!1},hideDescriptionOverflow:{type:Boolean,default:!1},searchQuery:{type:String,default:""},showNoResultsSlot:{type:Boolean,default:null}},emits:["update:selected","update:expanded","menu-item-click","menu-item-keyboard-navigation","load-more"],setup(e,{emit:a,slots:u,attrs:M}){const b=t.computed(()=>{const l=e.footer&&e.menuItems?[...e.menuItems,e.footer]:e.menuItems,n=i=>R(N({},i),{id:t.useId()});return l.map(i=>$(i)?R(N({},i),{id:t.useId(),items:i.items.map(s=>n(s))}):n(i))}),f=t.computed(()=>{const l=[];return b.value.forEach(n=>{$(n)?l.push(...n.items):l.push(n)}),l}),_=t.computed(()=>u["no-results"]?e.showNoResultsSlot!==null?e.showNoResultsSlot:f.value.length===0:!1),r=t.ref(null),c=t.ref(!1),o=t.ref(null),L="additions removals";let d="",m=null;function H(){d="",m!==null&&(clearTimeout(m),m=null)}function A(){m!==null&&clearTimeout(m),m=setTimeout(H,1500)}function B(){var l;return(l=f.value.find(n=>K(e.selected)?e.selected.includes(n.value):n.value===e.selected))!=null?l:null}const x=t.computed(()=>K(e.selected));function Q(l){return K(e.selected)?e.selected.includes(l):l===e.selected}function P(l){if(K(e.selected)){const n=e.selected.includes(l)?e.selected.filter(i=>i!==l):e.selected.concat(l);a("update:selected",n)}else a("update:selected",l)}function h(l,n){if(!(n!=null&&n.disabled))switch(l){case"selected":n&&P(n.value),x.value||a("update:expanded",!1),o.value=null;break;case"highlighted":r.value=n!=null?n:null,c.value=!1;break;case"highlightedViaKeyboard":r.value=n!=null?n:null,c.value=!0;break;case"active":o.value=n!=null?n:null;break}}const p=t.computed(()=>{if(r.value!==null)return f.value.findIndex(l=>l.value===r.value.value)});function D(l){l&&(h("highlightedViaKeyboard",l),a("menu-item-keyboard-navigation",l))}function q(l){var s;const n=k=>{for(let v=k-1;v>=0;v--)if(!f.value[v].disabled)return f.value[v]};l=l!=null?l:f.value.length;const i=(s=n(l))!=null?s:n(f.value.length);D(i)}function I(l){var s;const n=k=>f.value.find((v,V)=>!v.disabled&&V>k);l=l!=null?l:-1;const i=(s=n(l))!=null?s:n(-1);D(i)}function U(l){if(l.key==="Clear")return H(),!0;if(l.key==="Backspace")return d=d.slice(0,-1),A(),!0;if(l.key.length===1&&!l.metaKey&&!l.ctrlKey&&!l.altKey){if(e.expanded||a("update:expanded",!0),l.key===" "&&d.length<1)return!1;d+=l.key.toLowerCase();const n=d.length>1&&d.split("").every(v=>v===d[0]);let i=f.value,s=d;n&&p.value!==void 0&&(i=i.slice(p.value+1).concat(i.slice(0,p.value)),s=d[0]);const k=i.find(v=>{var V;return!v.disabled&&String((V=v.label)!=null?V:v.value).toLowerCase().startsWith(s)});return k&&(h("highlightedViaKeyboard",k),y()),A(),!0}return!1}function j(l,{prevent:n=!0,characterNavigation:i=!1}={}){if(i){if(U(l))return l.preventDefault(),!0;H()}function s(){n&&(l.preventDefault(),l.stopPropagation())}switch(l.key){case"Enter":case" ":return s(),e.expanded?(r.value&&c.value&&P(r.value.value),x.value||a("update:expanded",!1)):a("update:expanded",!0),!0;case"Tab":return e.expanded&&r.value&&c.value&&!x.value&&(P(r.value.value),a("update:expanded",!1)),!0;case"ArrowUp":return s(),e.expanded?(r.value===null&&h("highlightedViaKeyboard",B()),q(p.value)):a("update:expanded",!0),y(),!0;case"ArrowDown":return s(),e.expanded?(r.value===null&&h("highlightedViaKeyboard",B()),I(p.value)):a("update:expanded",!0),y(),!0;case"Home":return s(),e.expanded?(r.value===null&&h("highlightedViaKeyboard",B()),I()):a("update:expanded",!0),y(),!0;case"End":return s(),e.expanded?(r.value===null&&h("highlightedViaKeyboard",B()),q()):a("update:expanded",!0),y(),!0;case"Escape":return s(),a("update:expanded",!1),!0;default:return!1}}function E(){h("active",null)}const g=[],F=t.ref(void 0),G=pe(F,{threshold:.8});t.watch(G,l=>{l&&a("load-more")});function J(l,n){if(l){g[n]=l.$el;const i=e.visibleItemLimit;if(!i||e.menuItems.length<i)return;const s=Math.min(i,Math.max(2,Math.floor(.2*e.menuItems.length)));n===e.menuItems.length-s&&(F.value=l.$el)}}const w=t.ref();function y(){const l=w.value&&w.value.scrollHeight>w.value.clientHeight;if(p.value===void 0||!l)return;const n=p.value>=0?p.value:0;g[n].scrollIntoView({behavior:"smooth",block:"nearest"})}const C=t.ref(null),S=t.ref(null);function O(){return T(this,null,function*(){yield t.nextTick(),X(),Y(),yield t.nextTick(),y()})}function X(){if(e.footer){const l=g[g.length-1];S.value=l.scrollHeight}else S.value=null}function Y(){if(!e.visibleItemLimit||g.length<=e.visibleItemLimit){C.value=null;return}const l=g[0].getBoundingClientRect().top,n=g[e.visibleItemLimit].getBoundingClientRect().top;C.value=n-l+2}function Z(l){return{"cdx-menu__group-wrapper--hide-label":!!l.hideLabel}}function ee(l){return f.value.indexOf(l)}function te(l){var n,i;return N({selected:Q(l.value),active:l.value===((n=o.value)==null?void 0:n.value),highlighted:l.value===((i=r.value)==null?void 0:i.value),showThumbnail:e.showThumbnail,boldLabel:e.boldLabel,hideDescriptionOverflow:e.hideDescriptionOverflow,searchQuery:e.searchQuery,multiselect:x.value},l)}function le(l){return{change:(n,i)=>h(n,i?l:null),click:()=>a("menu-item-click",l)}}function ne(l){var n,i;return{menuItem:l,active:l.value===((n=o.value)==null?void 0:n.value)&&l.value===((i=r.value)==null?void 0:i.value)}}t.onMounted(()=>{document.addEventListener("mouseup",E)}),t.onUnmounted(()=>{document.removeEventListener("mouseup",E)}),t.watch(t.toRef(e,"expanded"),l=>T(this,null,function*(){if(l){const n=B();n&&!r.value&&h("highlighted",n),yield O()}else h("highlighted",null)})),t.watch(t.toRef(e,"menuItems"),l=>T(this,null,function*(){l.length<g.length&&(g.length=l.length),e.expanded&&(yield O())}),{deep:!0});const ae=t.computed(()=>({"max-height":C.value?"".concat(C.value,"px"):void 0,"margin-bottom":S.value?"".concat(S.value,"px"):void 0})),ie=t.computed(()=>({"cdx-menu--has-footer":!!e.footer})),{rootClasses:oe,rootStyle:re,otherAttrs:ue}=be(M,ie);return{listBoxStyle:ae,rootClasses:oe,rootStyle:re,otherAttrs:ue,assignTemplateRef:J,computedMenuEntries:b,computedMenuItems:f,computedShowNoResultsSlot:_,highlightedMenuItem:r,highlightedViaKeyboard:c,handleMenuItemChange:h,handleKeyNavigation:j,ariaRelevant:L,isMultiselect:x,menuListbox:w,getGroupWrapperClasses:Z,getMenuItemIndex:ee,getMenuItemBindings:te,getMenuItemHandlers:le,getSlotBindings:ne,isMenuGroupData:$}},methods:{isExpanded(){return this.expanded},getHighlightedMenuItem(){return this.expanded?this.highlightedMenuItem:null},getHighlightedViaKeyboard(){return this.highlightedViaKeyboard},getComputedMenuItems(){return this.computedMenuItems},clearActive(){this.handleMenuItemChange("active",null)},delegateKeyNavigation(e,{prevent:a=!0,characterNavigation:u=!1}={}){return this.handleKeyNavigation(e,{prevent:a,characterNavigation:u})}}}),_e=["aria-live","aria-relevant","aria-multiselectable"],Be={key:0,class:"cdx-menu__pending cdx-menu-item"},xe={key:1,class:"cdx-menu__no-results cdx-menu-item",role:"option"},Me=["aria-labelledby","aria-describedby"],we={class:"cdx-menu__group__meta"},Ce={class:"cdx-menu__group__meta__text"},Se=["id"],Ve=["id"];function Ne(e,a,u,M,b,f){const _=t.resolveComponent("cdx-icon"),r=t.resolveComponent("cdx-menu-item"),c=t.resolveComponent("cdx-progress-bar");return t.withDirectives((t.openBlock(),t.createElementBlock("div",{class:t.normalizeClass(["cdx-menu",e.rootClasses]),style:t.normalizeStyle(e.rootStyle)},[t.createElementVNode("ul",t.mergeProps({ref:"menuListbox",class:"cdx-menu__listbox",role:"listbox",style:e.listBoxStyle,"aria-live":e.showPending?"polite":void 0,"aria-relevant":e.showPending?e.ariaRelevant:void 0,"aria-multiselectable":e.isMultiselect?!0:void 0},e.otherAttrs),[e.showPending&&e.computedMenuItems.length===0&&e.$slots.pending?(t.openBlock(),t.createElementBlock("li",Be,[t.renderSlot(e.$slots,"pending")])):t.createCommentVNode("v-if",!0),e.computedShowNoResultsSlot?(t.openBlock(),t.createElementBlock("li",xe,[t.renderSlot(e.$slots,"no-results")])):t.createCommentVNode("v-if",!0),(t.openBlock(!0),t.createElementBlock(t.Fragment,null,t.renderList(e.computedMenuEntries,(o,L)=>(t.openBlock(),t.createElementBlock(t.Fragment,{key:L},[e.isMenuGroupData(o)?(t.openBlock(),t.createElementBlock("li",{key:0,class:t.normalizeClass(["cdx-menu__group-wrapper",e.getGroupWrapperClasses(o)])},[t.createElementVNode("ul",{class:"cdx-menu__group",role:"group","aria-labelledby":o.id+"-label","aria-describedby":o.id+"-description"},[t.createElementVNode("span",we,[o.icon?(t.openBlock(),t.createBlock(_,{key:0,class:"cdx-menu__group__icon",icon:o.icon},null,8,["icon"])):t.createCommentVNode("v-if",!0),t.createElementVNode("span",Ce,[t.createElementVNode("span",{id:o.id+"-label",class:"cdx-menu__group__label"},t.toDisplayString(o.label),9,Se),o.description?(t.openBlock(),t.createElementBlock("span",{key:0,id:o.id+"-description",class:"cdx-menu__group__description"},t.toDisplayString(o.description),9,Ve)):t.createCommentVNode("v-if",!0)])]),(t.openBlock(!0),t.createElementBlock(t.Fragment,null,t.renderList(o.items,d=>(t.openBlock(),t.createBlock(r,t.mergeProps({key:d.value,ref_for:!0,ref:m=>e.assignTemplateRef(m,e.getMenuItemIndex(d)),class:"cdx-menu__group__item"},e.getMenuItemBindings(d),t.toHandlers(e.getMenuItemHandlers(d))),{default:t.withCtx(()=>[t.renderSlot(e.$slots,"default",t.mergeProps({ref_for:!0},e.getSlotBindings(d)))]),_:2},1040))),128))],8,Me)],2)):(t.openBlock(),t.createBlock(r,t.mergeProps({key:1,ref_for:!0,ref:d=>e.assignTemplateRef(d,e.getMenuItemIndex(o))},e.getMenuItemBindings(o),t.toHandlers(e.getMenuItemHandlers(o))),{default:t.withCtx(()=>[t.renderSlot(e.$slots,"default",t.mergeProps({ref_for:!0},e.getSlotBindings(o)))]),_:2},1040))],64))),128)),e.showPending?(t.openBlock(),t.createBlock(c,{key:2,class:"cdx-menu__progress-bar",inline:!0})):t.createCommentVNode("v-if",!0)],16,_e)],6)),[[t.vShow,e.expanded]])}const Te=ye._export_sfc(ke,[["render",Ne]]);module.exports=Te;
