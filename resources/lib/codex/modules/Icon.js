"use strict";const t=require("vue"),m=require("./useComputedDirection.cjs"),g=require("./useComputedLanguage.cjs"),s=require("./constants.js"),v=require("./_plugin-vue_export-helper.js"),f='<path d="M11.53 2.3A1.85 1.85 0 0010 1.21 1.85 1.85 0 008.48 2.3L.36 16.36C-.48 17.81.21 19 1.88 19h16.24c1.67 0 2.36-1.19 1.52-2.64zM11 16H9v-2h2zm0-4H9V6h2z"/>',z='<path d="M12.43 14.34A5 5 0 0110 15a5 5 0 113.95-2L17 16.09V3a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2h10a2 2 0 001.45-.63z"/><circle cx="10" cy="10" r="3"/>',M='<path d="M10 0a10 10 0 1010 10A10 10 0 0010 0m5.66 14.24-1.41 1.41L10 11.41l-4.24 4.25-1.42-1.42L8.59 10 4.34 5.76l1.42-1.42L10 8.59l4.24-4.24 1.41 1.41L11.41 10z"/>',L='<path d="m4.34 2.93 12.73 12.73-1.41 1.41L2.93 4.35z"/><path d="M17.07 4.34 4.34 17.07l-1.41-1.41L15.66 2.93z"/>',y='<path d="M10 15 2 5h16z"/>',C='<path d="M13.728 1H6.272L1 6.272v7.456L6.272 19h7.456L19 13.728V6.272zM11 15H9v-2h2zm0-4H9V5h2z"/>',k='<path d="m17.5 4.75-7.5 7.5-7.5-7.5L1 6.25l9 9 9-9z"/>',H='<path d="M19 3H1v14h18zM3 14l3.5-4.5 2.5 3L12.5 8l4.5 6z"/><path d="M19 5H1V3h18zm0 12H1v-2h18z"/>',S='<path d="M8 19a1 1 0 001 1h2a1 1 0 001-1v-1H8zm9-12a7 7 0 10-12 4.9S7 14 7 15v1a1 1 0 001 1h4a1 1 0 001-1v-1c0-1 2-3.1 2-3.1A7 7 0 0017 7"/>',x='<path d="M10 0C4.477 0 0 4.477 0 10s4.477 10 10 10 10-4.477 10-10S15.523 0 10 0M9 5h2v2H9zm0 4h2v6H9z"/>',B='<path d="M7 1 5.6 2.5 13 10l-7.4 7.5L7 19l9-9z"/>',_='<path d="m4 10 9 9 1.4-1.5L7 10l7.4-7.5L13 1z"/>',w='<path d="M12.2 13.6a7 7 0 111.4-1.4l5.4 5.4-1.4 1.4zM3 8a5 5 0 1010 0A5 5 0 003 8"/>',E='<path d="M10 0 3 8h14zm0 18-7-8h14z"/>',V='<path d="M10 20a10 10 0 010-20 10 10 0 110 20m-2-5 9-8.5L15.5 5 8 12 4.5 8.5 3 10z"/>',A='<path d="m10 5 8 10H2z"/>',F=f,q=z,b=M,I=L,O=y,T=C,$=k,D=H,P={langCodeMap:{ar:S},default:x},Y={ltr:B,shouldFlip:!0},j={ltr:_,shouldFlip:!0},N=w,X=E,G=V,J=A;function K(e,n,o){if(typeof e=="string"||"path"in e)return e;if("shouldFlip"in e)return e.ltr;if("rtl"in e)return o==="rtl"?e.rtl:e.ltr;const i=n in e.langCodeMap?e.langCodeMap[n]:e.default;return typeof i=="string"||"path"in i?i:i.ltr}function Q(e,n){if(typeof e=="string")return!1;if("langCodeMap"in e){const o=n in e.langCodeMap?e.langCodeMap[n]:e.default;if(typeof o=="string")return!1;e=o}if("shouldFlipExceptions"in e&&Array.isArray(e.shouldFlipExceptions)){const o=e.shouldFlipExceptions.indexOf(n);return o===void 0||o===-1}return"shouldFlip"in e?e.shouldFlip:!1}const R=s.makeStringTypeValidator(s.IconSizes),U=t.defineComponent({name:"CdxIcon",props:{icon:{type:[String,Object],required:!0},iconLabel:{type:String,default:""},lang:{type:String,default:null},dir:{type:String,default:null},size:{type:String,default:"medium",validator:R}},setup(e){const n=t.ref(),o=m(n),i=g(n),d=t.computed(()=>{var l;return(l=e.dir)!=null?l:o.value}),a=t.computed(()=>{var l;return(l=e.lang)!=null?l:i.value}),u=t.computed(()=>({"cdx-icon--flipped":d.value==="rtl"&&a.value!==null&&Q(e.icon,a.value),["cdx-icon--".concat(e.size)]:!0})),r=t.computed(()=>{var l,p;return K(e.icon,(l=a.value)!=null?l:"",(p=d.value)!=null?p:"ltr")}),c=t.computed(()=>typeof r.value=="string"?r.value:""),h=t.computed(()=>typeof r.value!="string"?r.value.path:"");return{rootElement:n,rootClasses:u,iconSvg:c,iconPath:h}}}),W=["aria-hidden"],Z={key:0},e1=["innerHTML"],t1=["d"];function n1(e,n,o,i,d,a){return t.openBlock(),t.createElementBlock("span",{ref:"rootElement",class:t.normalizeClass(["cdx-icon",e.rootClasses])},[(t.openBlock(),t.createElementBlock("svg",{xmlns:"http://www.w3.org/2000/svg","xmlns:xlink":"http://www.w3.org/1999/xlink",width:"20",height:"20",viewBox:"0 0 20 20","aria-hidden":e.iconLabel?void 0:!0},[e.iconLabel?(t.openBlock(),t.createElementBlock("title",Z,t.toDisplayString(e.iconLabel),1)):t.createCommentVNode("v-if",!0),e.iconSvg?(t.openBlock(),t.createElementBlock("g",{key:1,innerHTML:e.iconSvg},null,8,e1)):(t.openBlock(),t.createElementBlock("path",{key:2,d:e.iconPath},null,8,t1))],8,W))],2)}const o1=v._export_sfc(U,[["render",n1]]);exports.A7=N;exports.CdxIcon=o1;exports.M3=F;exports.O7=G;exports.P4=D;exports.S3=q;exports.T7=X;exports.X3=I;exports.Y3=b;exports.Y4=P;exports.d7=j;exports.i4=T;exports.j6=Y;exports.l4=O;exports.m4=$;exports.x8=J;
