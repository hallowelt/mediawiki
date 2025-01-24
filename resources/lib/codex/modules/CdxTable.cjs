"use strict";const e=require("vue"),te=require("./CdxCheckbox.cjs"),f=require("./Icon.js"),ae=require("./CdxButton.cjs"),ne=require("./CdxSelect.cjs"),P=require("./useModelWrapper.cjs"),c=require("./useI18n.cjs"),$=require("./_plugin-vue_export-helper.js"),oe=require("./CdxProgressBar.cjs"),y=require("./constants.js"),le=e.defineComponent({name:"CdxTablePager",components:{CdxButton:ae,CdxIcon:f.CdxIcon,CdxSelect:ne},props:{paginationSizeOptions:{type:Array,required:!0},itemsPerPage:{type:Number,required:!0},nextDisabled:{type:Boolean,default:!1},prevDisabled:{type:Boolean,default:!1},lastDisabled:{type:Boolean,default:!1}},emits:["update:itemsPerPage","first","last","next","prev"],setup(t,{emit:n}){const s=P(e.toRef(t,"itemsPerPage"),n,"update:itemsPerPage"),i=c("cdx-table-pager-items-per-page-default","Results per page"),v=c("cdx-table-pager-items-per-page-current",d=>"".concat(d," rows"),[s]),u=c("cdx-table-pager-button-first-page","First page"),p=c("cdx-table-pager-button-next-page","Next page"),g=c("cdx-table-pager-button-prev-page","Previous page"),m=c("cdx-table-pager-button-last-page","Last page");return{defaultItemsPerPageLabel:i,currentItemsPerPageLabel:v,btnLabelFirst:u,btnLabelNext:p,btnLabelPrev:g,btnLabelLast:m,wrappedItemsPerPage:s,cdxIconPrevious:f.g7,cdxIconNext:f.o7,cdxIconMoveFirst:f.j6,cdxIconMoveLast:f.X6}}}),se={class:"cdx-table-pager"},ie={class:"cdx-table-pager__start"},re={key:0},de={key:1},ce={class:"cdx-table-pager__center"},ue={class:"cdx-table-pager__end"};function pe(t,n,s,i,v,u){const p=e.resolveComponent("cdx-select"),g=e.resolveComponent("cdx-icon"),m=e.resolveComponent("cdx-button");return e.openBlock(),e.createElementBlock("div",se,[e.createElementVNode("div",ie,[e.createVNode(p,{selected:t.wrappedItemsPerPage,"onUpdate:selected":n[0]||(n[0]=d=>t.wrappedItemsPerPage=d),"default-label":t.defaultItemsPerPageLabel,"menu-items":t.paginationSizeOptions},{label:e.withCtx(({selectedMenuItem:d,defaultLabel:l})=>[d?(e.openBlock(),e.createElementBlock("span",re,[e.createElementVNode("span",null,e.toDisplayString(t.currentItemsPerPageLabel),1)])):(e.openBlock(),e.createElementBlock("span",de,e.toDisplayString(l),1))]),_:1},8,["selected","default-label","menu-items"])]),e.createElementVNode("div",ce,[e.renderSlot(t.$slots,"default")]),e.createElementVNode("div",ue,[e.createVNode(m,{disabled:t.prevDisabled,class:"cdx-table-pager__button-first",weight:"quiet","aria-label":t.btnLabelFirst,onClick:n[1]||(n[1]=d=>t.$emit("first"))},{default:e.withCtx(()=>[e.createVNode(g,{icon:t.cdxIconMoveFirst},null,8,["icon"])]),_:1},8,["disabled","aria-label"]),e.createVNode(m,{disabled:t.prevDisabled,class:"cdx-table-pager__button-prev",weight:"quiet","aria-label":t.btnLabelPrev,onClick:n[2]||(n[2]=d=>t.$emit("prev"))},{default:e.withCtx(()=>[e.createVNode(g,{icon:t.cdxIconPrevious},null,8,["icon"])]),_:1},8,["disabled","aria-label"]),e.createVNode(m,{disabled:t.nextDisabled,class:"cdx-table-pager__button-next",weight:"quiet","aria-label":t.btnLabelNext,onClick:n[3]||(n[3]=d=>t.$emit("next"))},{default:e.withCtx(()=>[e.createVNode(g,{icon:t.cdxIconNext},null,8,["icon"])]),_:1},8,["disabled","aria-label"]),e.createVNode(m,{disabled:t.nextDisabled||t.lastDisabled,class:"cdx-table-pager__button-last",weight:"quiet","aria-label":t.btnLabelLast,onClick:n[4]||(n[4]=d=>t.$emit("last"))},{default:e.withCtx(()=>[e.createVNode(g,{icon:t.cdxIconMoveLast},null,8,["icon"])]),_:1},8,["disabled","aria-label"])])])}const ge=$._export_sfc(le,[["render",pe]]),me=y.makeStringTypeValidator(y.TableTextAlignments),be=y.makeStringTypeValidator(y.TablePaginationPositions),ve={none:f.P7,asc:f.F8,desc:f.z4},fe={none:"none",asc:"ascending",desc:"descending"},he=e.defineComponent({name:"CdxTable",components:{CdxCheckbox:te,CdxIcon:f.CdxIcon,CdxTablePager:ge,CdxProgressBar:oe},props:{caption:{type:String,required:!0},hideCaption:{type:Boolean,default:!1},columns:{type:Array,default:()=>[],validator:t=>{const n=t.map(i=>i.id);return new Set(n).size===n.length?!0:(console.warn('[CdxTable]: Each column in the "columns" prop must have a unique "id".'),!1)}},data:{type:Array,default:()=>[],validator:(t,n)=>{if(!Array.isArray(n.columns)||n.columns.length===0||t.length===0)return!0;const s=n.columns.some(v=>"allowSort"in v),i=t.every(v=>y.TableRowIdentifier in v);return s&&n.useRowSelection&&!i?(console.warn('[CdxTable]: With sorting and row selection, each row in the "data" prop must have a "TableRowIdentifier".'),!1):!0}},useRowHeaders:{type:Boolean,default:!1},showVerticalBorders:{type:Boolean,default:!1},useRowSelection:{type:Boolean,default:!1},selectedRows:{type:Array,default:()=>[]},sort:{type:Object,default:()=>({})},pending:{type:Boolean,default:!1},paginate:{type:Boolean,default:!1},serverPagination:{type:Boolean,default:!1},totalRows:{type:Number,default:NaN},paginationPosition:{type:String,default:"bottom",validator:be},paginationSizeOptions:{type:Array,default:()=>[{value:10},{value:20},{value:50}],validator:t=>{const n=s=>typeof s.value=="number";return t.every(n)?!0:(console.warn('[CdxTable]: "value" property of all menu items in PaginationOptions must be a number.'),!1)}},paginationSizeDefault:{type:Number,default:t=>t.paginate&&t.serverPagination?t.data.length:t.paginationSizeOptions[0].value}},emits:["update:selectedRows","update:sort","load-more","last"],setup(t,{emit:n}){const s=e.ref(0),i=e.ref(t.paginationSizeDefault),v=e.computed(()=>t.serverPagination&&t.paginate?t.data:t.paginate?t.data.slice(s.value,i.value+s.value):t.data),u=e.computed(()=>{var a;return t.serverPagination?(a=t.totalRows)!=null?a:NaN:t.data.length}),p=e.computed(()=>isNaN(u.value)),g=e.computed(()=>v.value.length),m=e.computed(()=>s.value+1),d=e.computed(()=>s.value+g.value),l=e.computed(()=>p.value),h=e.computed(()=>s.value<=0),b=e.computed(()=>p.value?g.value<i.value:s.value+i.value>=u.value),x=c("cdx-table-pagination-status-message-determinate-short",(a,o,r)=>"".concat(a,"–").concat(o," of ").concat(r),[m,d,u]),D=c("cdx-table-pagination-status-message-determinate-long",(a,o,r)=>"Showing results ".concat(a,"–").concat(o," of ").concat(r),[m,d,u]),E=c("cdx-table-pagination-status-message-indeterminate-short",(a,o)=>"".concat(a,"–").concat(o," of many"),[m,d]),L=c("cdx-table-pagination-status-message-indeterminate-long",(a,o)=>"Showing results ".concat(a,"–").concat(o," of many"),[m,d]),B=c("cdx-table-pagination-status-message-indeterminate-final",a=>"Showing the last ".concat(a," results"),[g]),V=c("cdx-table-pagination-status-message-pending","Loading results..."),R=e.computed(()=>t.pending?V.value:p.value&&b.value?B.value:p.value?E.value:x.value),I=e.computed(()=>t.pending?V.value:p.value&&b.value?B.value:p.value?L.value:D.value);function F(){s.value+=i.value,t.serverPagination&&n("load-more",s.value,i.value)}function T(){s.value-i.value<1?_():(s.value-=i.value,t.serverPagination&&n("load-more",s.value,i.value))}function _(){s.value=0,t.serverPagination&&n("load-more",s.value,i.value)}function z(){u.value%i.value===0?(s.value=u.value-i.value,n("load-more",s.value,i.value)):(s.value=Math.floor(u.value/i.value)*i.value,n("load-more",s.value,i.value))}e.watch(i,a=>{t.serverPagination&&n("load-more",s.value,a)});const C=P(e.toRef(t,"selectedRows"),n,"update:selectedRows"),w=e.ref(u.value===C.value.length),k=e.ref(!1),A=e.computed(()=>Object.keys(t.sort)[0]),q=e.computed(()=>t.columns.some(a=>a.allowSort)),M=e.computed(()=>{var o;return{"cdx-table__table--layout-fixed":(o=t.columns)==null?void 0:o.some(r=>"width"in r||"minWidth"in r),"cdx-table__table--borders-vertical":t.showVerticalBorders}}),O=c("cdx-table-sort-caption",a=>"".concat(a," (column headers with buttons are sortable)."),[e.toRef(t,"caption")]),U=(a,o)=>c("cdx-table-select-row-label",(r,S)=>"Select row ".concat(r," of ").concat(S),[()=>a,()=>o]).value,H=c("cdx-table-select-all-label","Select all rows");function N(a,o){return y.TableRowIdentifier in a?a[y.TableRowIdentifier]:o}function W(a,o){const r=N(a,o);return{"cdx-table__row--selected":C.value.includes(r)}}function K(a){const o=t.columns[0].id;if(t.useRowHeaders&&a===o)return"row"}function j(a){const o=t.columns[0].id;return t.useRowHeaders&&a===o?"th":"td"}function X(a,o=!1){if("textAlign"in a&&!me(a.textAlign)){console.warn('[CdxTable]: Invalid value for TableColumn "textAlign" property.');return}return{["cdx-table__table__cell--align-".concat(a.textAlign)]:"textAlign"in a&&a.textAlign!=="start","cdx-table__table__cell--has-sort":o}}function G(a){const o={};return"width"in a&&(o.width=a.width),"minWidth"in a&&(o.minWidth=a.minWidth),o}function J(a){if(u.value===a.length){w.value=!0,k.value=!1;return}w.value=!1,u.value>a.length&&(k.value=!0),a.length===0&&(k.value=!1)}function Q(a){k.value=!1,a?C.value=t.data.map((o,r)=>N(o,r)):C.value=[]}function Y(a){var S;const o=(S=t.sort[a])!=null?S:"none";let r="asc";o==="asc"&&(r="desc"),o==="desc"&&(r="none"),n("update:sort",{[a]:r})}function Z(a){var r;const o=(r=t.sort[a])!=null?r:"none";return ve[o]}function ee(a,o=!1){var r;if(o){const S=(r=t.sort[a])!=null?r:"none";return S==="none"?void 0:fe[S]}}return{dataForDisplay:v,pageSize:i,onNext:F,onPrev:T,onFirst:_,onLast:z,nextDisabled:b,prevDisabled:h,lastDisabled:l,paginationStatusMessageShort:R,paginationStatusMessageLong:I,wrappedSelectedRows:C,selectAll:w,selectAllIndeterminate:k,activeSortColumn:A,hasSortableColumns:q,tableClasses:M,getRowKey:N,getRowClass:W,getRowHeaderScope:K,getCellElement:j,getCellClass:X,getCellStyle:G,handleRowSelection:J,handleSelectAll:Q,handleSort:Y,getSortIcon:Z,getSortOrder:ee,translatedSortCaption:O,translatedSelectRowLabel:U,translatedSelectAllLabel:H}}}),Se={class:"cdx-table",tabindex:"0"},ye={key:0,class:"cdx-table__header"},Ce=["aria-hidden"],ke={class:"cdx-table__header__content"},we={class:"cdx-table__pagination-status--long"},Ne={class:"cdx-table__pagination-status--short"},Be={class:"cdx-table__table-wrapper"},Ve={key:0},_e={key:0,class:"cdx-table__table__select-rows"},Pe=["aria-sort"],$e=["aria-selected","onClick"],xe={class:"cdx-table__table__sort-label"},De={key:0},Ee={key:0},Le={key:1},Re={class:"cdx-table__table__empty-state"},Ie=["colspan"],Fe={class:"cdx-table__pagination-status--long"},Te={class:"cdx-table__pagination-status--short"},ze={key:3,class:"cdx-table__footer"};function Ae(t,n,s,i,v,u){const p=e.resolveComponent("cdx-table-pager"),g=e.resolveComponent("cdx-checkbox"),m=e.resolveComponent("cdx-icon"),d=e.resolveComponent("cdx-progress-bar");return e.openBlock(),e.createElementBlock("div",Se,[!t.hideCaption||t.$slots.header&&t.$slots.header().length>0?(e.openBlock(),e.createElementBlock("div",ye,[e.createElementVNode("div",{class:"cdx-table__header__caption","aria-hidden":t.$slots.header&&t.$slots.header().length>0?void 0:!0},[t.hideCaption?e.createCommentVNode("v-if",!0):(e.openBlock(),e.createElementBlock(e.Fragment,{key:0},[e.createTextVNode(e.toDisplayString(t.caption),1)],64))],8,Ce),e.createElementVNode("div",ke,[e.renderSlot(t.$slots,"header")])])):e.createCommentVNode("v-if",!0),t.paginate&&(t.paginationPosition==="top"||t.paginationPosition==="both")?(e.openBlock(),e.createBlock(p,{key:1,"items-per-page":t.pageSize,"onUpdate:itemsPerPage":n[0]||(n[0]=l=>t.pageSize=l),class:"cdx-table__pagination--top","pagination-size-options":t.paginationSizeOptions,"prev-disabled":t.prevDisabled,"next-disabled":t.nextDisabled,"last-disabled":t.lastDisabled,onNext:t.onNext,onPrev:t.onPrev,onFirst:t.onFirst,onLast:t.onLast},{default:e.withCtx(()=>[e.createElementVNode("span",we,e.toDisplayString(t.paginationStatusMessageLong),1),e.createElementVNode("span",Ne,e.toDisplayString(t.paginationStatusMessageShort),1)]),_:1},8,["items-per-page","pagination-size-options","prev-disabled","next-disabled","last-disabled","onNext","onPrev","onFirst","onLast"])):e.createCommentVNode("v-if",!0),e.createElementVNode("div",Be,[e.createElementVNode("table",{class:e.normalizeClass(["cdx-table__table",t.tableClasses])},[e.createElementVNode("caption",null,[t.hasSortableColumns?(e.openBlock(),e.createElementBlock(e.Fragment,{key:1},[e.createTextVNode(e.toDisplayString(t.translatedSortCaption),1)],64)):(e.openBlock(),e.createElementBlock(e.Fragment,{key:0},[e.createTextVNode(e.toDisplayString(t.caption),1)],64))]),e.renderSlot(t.$slots,"thead",{},()=>[t.columns.length>0?(e.openBlock(),e.createElementBlock("thead",Ve,[e.createElementVNode("tr",null,[t.useRowSelection?(e.openBlock(),e.createElementBlock("th",_e,[e.createVNode(g,{modelValue:t.selectAll,"onUpdate:modelValue":[n[1]||(n[1]=l=>t.selectAll=l),t.handleSelectAll],"hide-label":!0,indeterminate:t.selectAllIndeterminate},{default:e.withCtx(()=>[e.createTextVNode(e.toDisplayString(t.translatedSelectAllLabel),1)]),_:1},8,["modelValue","indeterminate","onUpdate:modelValue"])])):e.createCommentVNode("v-if",!0),(e.openBlock(!0),e.createElementBlock(e.Fragment,null,e.renderList(t.columns,l=>(e.openBlock(),e.createElementBlock("th",{key:l.id,scope:"col",class:e.normalizeClass(t.getCellClass(l,l.allowSort)),"aria-sort":t.getSortOrder(l.id,l.allowSort),style:e.normalizeStyle(t.getCellStyle(l))},[l.allowSort?(e.openBlock(),e.createElementBlock("button",{key:0,"aria-selected":l.id===t.activeSortColumn,class:"cdx-table__table__sort-button",onClick:h=>t.handleSort(l.id)},[e.createElementVNode("span",xe,e.toDisplayString(l.label),1),e.createVNode(m,{icon:t.getSortIcon(l.id),size:"small",class:"cdx-table__table__sort-icon--vue","aria-hidden":"true"},null,8,["icon"])],8,$e)):(e.openBlock(),e.createElementBlock(e.Fragment,{key:1},[e.createTextVNode(e.toDisplayString(l.label),1)],64))],14,Pe))),128))])])):e.createCommentVNode("v-if",!0)]),t.pending?(e.openBlock(),e.createBlock(d,{key:0,inline:!0,class:"cdx-table__pending-indicator"})):e.createCommentVNode("v-if",!0),e.renderSlot(t.$slots,"tbody",{},()=>[t.dataForDisplay.length>0?(e.openBlock(),e.createElementBlock("tbody",De,[(e.openBlock(!0),e.createElementBlock(e.Fragment,null,e.renderList(t.dataForDisplay,(l,h)=>(e.openBlock(),e.createElementBlock("tr",{key:t.getRowKey(l,h),class:e.normalizeClass(t.getRowClass(l,h))},[t.useRowSelection?(e.openBlock(),e.createElementBlock("td",Ee,[e.createVNode(g,{modelValue:t.wrappedSelectedRows,"onUpdate:modelValue":[n[2]||(n[2]=b=>t.wrappedSelectedRows=b),t.handleRowSelection],"input-value":t.getRowKey(l,h),"hide-label":!0},{default:e.withCtx(()=>[e.createTextVNode(e.toDisplayString(t.translatedSelectRowLabel(h+1,t.dataForDisplay.length)),1)]),_:2},1032,["modelValue","input-value","onUpdate:modelValue"])])):e.createCommentVNode("v-if",!0),(e.openBlock(!0),e.createElementBlock(e.Fragment,null,e.renderList(t.columns,b=>(e.openBlock(),e.createBlock(e.resolveDynamicComponent(t.getCellElement(b.id)),{key:b.id,scope:t.getRowHeaderScope(b.id),class:e.normalizeClass(t.getCellClass(b))},{default:e.withCtx(()=>[e.renderSlot(t.$slots,"item-"+b.id,{item:l[b.id],row:l},()=>[e.createTextVNode(e.toDisplayString(l[b.id]),1)])]),_:2},1032,["scope","class"]))),128))],2))),128))])):t.$slots["empty-state"]&&t.$slots["empty-state"]().length>0?(e.openBlock(),e.createElementBlock("tbody",Le,[e.createElementVNode("tr",Re,[e.createElementVNode("td",{colspan:t.columns.length,class:"cdx-table__table__empty-state-content"},[e.renderSlot(t.$slots,"empty-state")],8,Ie)])])):e.createCommentVNode("v-if",!0)]),e.renderSlot(t.$slots,"tfoot")],2)]),t.paginate&&(t.paginationPosition==="bottom"||t.paginationPosition==="both")?(e.openBlock(),e.createBlock(p,{key:2,"items-per-page":t.pageSize,"onUpdate:itemsPerPage":n[3]||(n[3]=l=>t.pageSize=l),class:"cdx-table__pagination--bottom","pagination-size-options":t.paginationSizeOptions,"prev-disabled":t.prevDisabled,"next-disabled":t.nextDisabled,"last-disabled":t.lastDisabled,onNext:t.onNext,onPrev:t.onPrev,onFirst:t.onFirst,onLast:t.onLast},{default:e.withCtx(()=>[e.createElementVNode("span",Fe,e.toDisplayString(t.paginationStatusMessageLong),1),e.createElementVNode("span",Te,e.toDisplayString(t.paginationStatusMessageShort),1)]),_:1},8,["items-per-page","pagination-size-options","prev-disabled","next-disabled","last-disabled","onNext","onPrev","onFirst","onLast"])):e.createCommentVNode("v-if",!0),t.$slots.footer&&t.$slots.footer().length>0?(e.openBlock(),e.createElementBlock("div",ze,[e.renderSlot(t.$slots,"footer")])):e.createCommentVNode("v-if",!0)])}const qe=$._export_sfc(he,[["render",Ae]]);module.exports=qe;
