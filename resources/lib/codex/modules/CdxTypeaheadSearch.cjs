"use strict";var k=Object.getOwnPropertySymbols;var H=Object.prototype.hasOwnProperty,O=Object.prototype.propertyIsEnumerable;var M=(e,o)=>{var r={};for(var s in e)H.call(e,s)&&o.indexOf(s)<0&&(r[s]=e[s]);if(e!=null&&k)for(var s of k(e))o.indexOf(s)<0&&O.call(e,s)&&(r[s]=e[s]);return r};const t=require("vue"),_=require("./Icon.js"),W=require("./CdxMenu.cjs"),G=require("./CdxSearchInput.cjs"),j=require("./useGeneratedId.cjs"),J=require("./useSplitAttributes.cjs"),h=require("./constants.js"),X=require("./_plugin-vue_export-helper.js"),Y=t.defineComponent({name:"CdxTypeaheadSearch",components:{CdxIcon:_.CdxIcon,CdxMenu:W,CdxSearchInput:G},inheritAttrs:!1,props:{id:{type:String,required:!0},formAction:{type:String,required:!0},searchResultsLabel:{type:String,required:!0},searchResults:{type:Array,required:!0},buttonLabel:{type:String,default:""},initialInputValue:{type:String,default:""},searchFooterUrl:{type:String,default:""},debounceInterval:{type:Number,default:h.DebounceInterval},highlightQuery:{type:Boolean,default:!1},showThumbnail:{type:Boolean,default:!1},autoExpandWidth:{type:Boolean,default:!1},visibleItemLimit:{type:Number,default:null}},emits:["input","search-result-click","submit","load-more"],setup(e,{attrs:o,emit:r,slots:s}){const S=t.ref(),i=t.ref(),C=j("typeahead-search-menu"),u=t.ref(!1),g=t.ref(!1),l=t.ref(!1),p=t.ref(!1),m=t.ref(e.initialInputValue),f=t.ref(""),N=t.computed(()=>{var a,n;return(n=(a=i.value)==null?void 0:a.getHighlightedMenuItem())==null?void 0:n.id}),y=t.ref(null),x=t.computed(()=>({"cdx-typeahead-search__menu-message--has-thumbnail":e.showThumbnail})),d=t.computed(()=>e.searchResults.find(a=>a.value===y.value)),F=t.computed(()=>e.searchFooterUrl?{value:h.MenuFooterValue,url:e.searchFooterUrl}:void 0),q=t.computed(()=>({"cdx-typeahead-search--show-thumbnail":e.showThumbnail,"cdx-typeahead-search--expanded":u.value,"cdx-typeahead-search--auto-expand-width":e.showThumbnail&&e.autoExpandWidth})),{rootClasses:U,rootStyle:E,otherAttrs:T}=J(o,q);function $(a){return a}const K=t.computed(()=>({visibleItemLimit:e.visibleItemLimit,showThumbnail:e.showThumbnail,boldLabel:!0,hideDescriptionOverflow:!0}));let b,v;function I(a,n=!1){d.value&&d.value.label!==a&&d.value.value!==a&&(y.value=null),v!==void 0&&(clearTimeout(v),v=void 0),a===""?u.value=!1:(g.value=!0,s["search-results-pending"]&&(v=setTimeout(()=>{p.value&&(u.value=!0),l.value=!0},h.PendingDelay))),b!==void 0&&(clearTimeout(b),b=void 0);const c=()=>{r("input",a)};n?c():b=setTimeout(()=>{c()},e.debounceInterval)}function B(a){var n;if(a===h.MenuFooterValue){y.value=null,m.value=f.value;return}y.value=a,a!==null&&(m.value=d.value?(n=d.value.label)!=null?n:String(d.value.value):"")}function A(){p.value=!0,(f.value||l.value)&&(u.value=!0)}function L(){p.value=!1,u.value=!1}function V(a){const w=a,{id:n}=w,c=M(w,["id"]);if(c.value===h.MenuFooterValue){r("search-result-click",{searchResult:null,index:e.searchResults.length,numberOfResults:e.searchResults.length});return}R(c)}function R(a){const n={searchResult:a,index:e.searchResults.findIndex(c=>c.value===a.value),numberOfResults:e.searchResults.length};r("search-result-click",n)}function Q(a){var n;if(a.value===h.MenuFooterValue){m.value=f.value;return}m.value=a.value?(n=a.label)!=null?n:String(a.value):""}function D(a){var n;u.value=!1,(n=i.value)==null||n.clearActive(),V(a)}function P(a){if(d.value)R(d.value),a.stopPropagation(),window.location.assign(d.value.url),a.preventDefault();else{const n={searchResult:null,index:-1,numberOfResults:e.searchResults.length};r("submit",n)}}function z(a){if(!i.value||!f.value||a.key===" ")return;const n=i.value.getHighlightedMenuItem(),c=i.value.getHighlightedViaKeyboard();switch(a.key){case"Enter":n&&(n.value===h.MenuFooterValue&&c?window.location.assign(e.searchFooterUrl):i.value.delegateKeyNavigation(a,{prevent:!1})),u.value=!1;break;case"Tab":u.value=!1;break;default:i.value.delegateKeyNavigation(a);break}}return t.onMounted(()=>{e.initialInputValue&&I(e.initialInputValue,!0)}),t.watch(t.toRef(e,"searchResults"),()=>{f.value=m.value.trim(),p.value&&g.value&&f.value.length>0&&(u.value=!0),v!==void 0&&(clearTimeout(v),v=void 0),g.value=!1,l.value=!1}),{form:S,menu:i,menuId:C,highlightedId:N,selection:y,menuMessageClass:x,footer:F,asSearchResult:$,inputValue:m,searchQuery:f,expanded:u,showPending:l,rootClasses:U,rootStyle:E,otherAttrs:T,menuConfig:K,onUpdateInputValue:I,onUpdateMenuSelection:B,onFocus:A,onBlur:L,onSearchResultClick:V,onSearchResultKeyboardNavigation:Q,onSearchFooterClick:D,onSubmit:P,onKeydown:z,MenuFooterValue:h.MenuFooterValue,articleIcon:_.S3}},methods:{focus(){this.$refs.searchInput.focus()}}}),Z=["id","action"],ee={class:"cdx-typeahead-search__menu-message__text"},te={class:"cdx-typeahead-search__menu-message__text"},ae=["href","onClickCapture"],ne={class:"cdx-menu-item__text cdx-typeahead-search__search-footer__text"},le={class:"cdx-typeahead-search__search-footer__query"};function oe(e,o,r,s,S,i){const C=t.resolveComponent("cdx-icon"),u=t.resolveComponent("cdx-menu"),g=t.resolveComponent("cdx-search-input");return t.openBlock(),t.createElementBlock("div",{class:t.normalizeClass(["cdx-typeahead-search",e.rootClasses]),style:t.normalizeStyle(e.rootStyle)},[t.createElementVNode("form",{id:e.id,ref:"form",class:"cdx-typeahead-search__form",action:e.formAction,onSubmit:o[4]||(o[4]=(...l)=>e.onSubmit&&e.onSubmit(...l))},[t.createVNode(g,t.mergeProps({ref:"searchInput",modelValue:e.inputValue,"onUpdate:modelValue":o[3]||(o[3]=l=>e.inputValue=l),"button-label":e.buttonLabel},e.otherAttrs,{class:"cdx-typeahead-search__input",name:"search",role:"combobox",autocomplete:"off","aria-autocomplete":"list","aria-controls":e.menuId,"aria-expanded":e.expanded,"aria-activedescendant":e.highlightedId,"onUpdate:modelValue":e.onUpdateInputValue,onFocus:e.onFocus,onBlur:e.onBlur,onKeydown:e.onKeydown}),{default:t.withCtx(()=>[t.createVNode(u,t.mergeProps({id:e.menuId,ref:"menu",expanded:e.expanded,"onUpdate:expanded":o[0]||(o[0]=l=>e.expanded=l),class:"cdx-typeahead-search__menu","show-pending":e.showPending,selected:e.selection,"menu-items":e.searchResults,footer:e.footer,"search-query":e.highlightQuery?e.searchQuery:"","show-no-results-slot":e.searchQuery.length>0&&e.searchResults.length===0&&e.$slots["search-no-results-text"]&&e.$slots["search-no-results-text"]().length>0},e.menuConfig,{"aria-label":e.searchResultsLabel,"onUpdate:selected":e.onUpdateMenuSelection,onMenuItemClick:o[1]||(o[1]=l=>e.onSearchResultClick(e.asSearchResult(l))),onMenuItemKeyboardNavigation:e.onSearchResultKeyboardNavigation,onLoadMore:o[2]||(o[2]=l=>e.$emit("load-more"))}),{pending:t.withCtx(()=>[t.createElementVNode("div",{class:t.normalizeClass(["cdx-menu-item__content cdx-typeahead-search__menu-message",e.menuMessageClass])},[t.createElementVNode("span",ee,[t.renderSlot(e.$slots,"search-results-pending")])],2)]),"no-results":t.withCtx(()=>[t.createElementVNode("div",{class:t.normalizeClass(["cdx-menu-item__content cdx-typeahead-search__menu-message",e.menuMessageClass])},[t.createElementVNode("span",te,[t.renderSlot(e.$slots,"search-no-results-text")])],2)]),default:t.withCtx(({menuItem:l,active:p})=>[l.value===e.MenuFooterValue?(t.openBlock(),t.createElementBlock("a",{key:0,class:t.normalizeClass(["cdx-menu-item__content cdx-typeahead-search__search-footer",{"cdx-typeahead-search__search-footer__active":p}]),href:e.asSearchResult(l).url,onClickCapture:t.withModifiers(m=>e.onSearchFooterClick(e.asSearchResult(l)),["stop"])},[t.createVNode(C,{class:"cdx-menu-item__thumbnail cdx-typeahead-search__search-footer__icon",icon:e.articleIcon},null,8,["icon"]),t.createElementVNode("span",ne,[t.renderSlot(e.$slots,"search-footer-text",{searchQuery:e.searchQuery},()=>[t.createElementVNode("strong",le,t.toDisplayString(e.searchQuery),1)])])],42,ae)):t.createCommentVNode("v-if",!0)]),_:3},16,["id","expanded","show-pending","selected","menu-items","footer","search-query","show-no-results-slot","aria-label","onUpdate:selected","onMenuItemKeyboardNavigation"])]),_:3},16,["modelValue","button-label","aria-controls","aria-expanded","aria-activedescendant","onUpdate:modelValue","onFocus","onBlur","onKeydown"]),t.renderSlot(e.$slots,"default")],40,Z)],6)}const se=X._export_sfc(Y,[["render",oe]]);module.exports=se;
