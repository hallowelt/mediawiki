"use strict";var a=Object.defineProperty;var p=(t,e,i)=>e in t?a(t,e,{enumerable:!0,configurable:!0,writable:!0,value:i}):t[e]=i;var n=(t,e,i)=>(p(t,typeof e!="symbol"?e+"":e,i),i);const c=require("./useGeneratedId.cjs"),s=require("./floating-ui.dom.js");class E{constructor(e,i){n(this,"referenceElement");n(this,"tooltipElement");n(this,"textContent");n(this,"placement");n(this,"autoUpdateCleanup");n(this,"referenceElementHandlers");n(this,"tooltipElementHandlers");n(this,"escapeHandler");n(this,"timeoutId");var h,o;const l=e.ownerDocument,r=c("tooltip");this.referenceElement=e,this.textContent=i.textContent,this.placement=(h=i.placement)!=null?h:"bottom",this.timeoutId=null,this.tooltipElement=l.createElement("div"),this.tooltipElement.classList.add("cdx-tooltip"),this.tooltipElement.role="tooltip",this.tooltipElement.id=r,this.referenceElement.setAttribute("aria-describedby",r),this.tooltipElement.textContent=this.textContent,(o=this.referenceElement.parentElement)==null||o.appendChild(this.tooltipElement),this.referenceElementHandlers={},this.referenceElementHandlers.mouseenter=this.show.bind(this),this.referenceElementHandlers.mouseleave=this.hideAfterDelay.bind(this),this.referenceElementHandlers.focus=this.show.bind(this),this.referenceElementHandlers.blur=this.hide.bind(this),this.tooltipElementHandlers={},this.tooltipElementHandlers.mouseenter=this.show.bind(this),this.tooltipElementHandlers.mouseleave=this.hideAfterDelay.bind(this),this.escapeHandler=this.onKeyup.bind(this),this.addEventListeners(),this.autoUpdateCleanup=s.autoUpdate(this.referenceElement,this.tooltipElement,()=>this.update())}isVisible(){return this.tooltipElement.style.display==="block"}show(){this.timeoutId&&clearTimeout(this.timeoutId),this.tooltipElement.style.display="block",this.tooltipElement.ownerDocument.addEventListener("keyup",this.escapeHandler)}hide(){this.tooltipElement.style.display="none",this.tooltipElement.ownerDocument.removeEventListener("keyup",this.escapeHandler)}hideAfterDelay(){this.timeoutId=setTimeout(this.hide.bind(this),250)}onKeyup(e){e.key==="Escape"&&this.isVisible()&&this.hide()}addEventListeners(){Object.keys(this.referenceElementHandlers).forEach(e=>{this.referenceElement.addEventListener(e,this.referenceElementHandlers[e])}),Object.keys(this.tooltipElementHandlers).forEach(e=>{this.tooltipElement.addEventListener(e,this.tooltipElementHandlers[e])})}removeEventListeners(){Object.keys(this.referenceElementHandlers).forEach(e=>{this.referenceElement.removeEventListener(e,this.referenceElementHandlers[e])}),Object.keys(this.tooltipElementHandlers).forEach(e=>{this.tooltipElement.removeEventListener(e,this.tooltipElementHandlers[e])})}update(){s.computePosition(this.referenceElement,this.tooltipElement,{placement:this.placement,middleware:[s.offset(4),s.flip(),s.shift(),s.hide()]}).then(({x:e,y:i,middlewareData:l})=>{var o,m,d;const r=(m=(o=l.offset)==null?void 0:o.placement)!=null?m:this.placement,h={left:"right","left-start":"right","left-end":"right",top:"bottom","top-start":"bottom","top-end":"bottom",bottom:"top","bottom-start":"top","bottom-end":"top",right:"left","right-start":"left","right-end":"left"};Object.assign(this.tooltipElement.style,{left:"".concat(e,"px"),top:"".concat(i,"px"),visibility:(d=l.hide)!=null&&d.referenceHidden?"hidden":"visible",transformOrigin:h[r]})})}remove(){this.tooltipElement.remove(),this.autoUpdateCleanup(),this.removeEventListeners()}}const f={mounted(t,{value:e,arg:i}){t.tooltip=new E(t,{textContent:String(e),placement:i})},beforeUnmount(t){t.tooltip&&t.tooltip.remove()}};module.exports=f;
