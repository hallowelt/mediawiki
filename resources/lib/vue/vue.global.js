var Vue = (function () {
  'use strict';

  /**
   * Make a map and return a function for checking if a key
   * is in that map.
   * IMPORTANT: all calls of this function must be prefixed with
   * \/\*#\_\_PURE\_\_\*\/
   * So that rollup can tree-shake them if necessary.
   */
  function makeMap(str, expectsLowerCase) {
      const map = Object.create(null);
      const list = str.split(',');
      for (let i = 0; i < list.length; i++) {
          map[list[i]] = true;
      }
      return expectsLowerCase ? val => !!map[val.toLowerCase()] : val => !!map[val];
  }

  /**
   * dev only flag -> name mapping
   */
  const PatchFlagNames = {
      [1 /* TEXT */]: `TEXT`,
      [2 /* CLASS */]: `CLASS`,
      [4 /* STYLE */]: `STYLE`,
      [8 /* PROPS */]: `PROPS`,
      [16 /* FULL_PROPS */]: `FULL_PROPS`,
      [32 /* HYDRATE_EVENTS */]: `HYDRATE_EVENTS`,
      [64 /* STABLE_FRAGMENT */]: `STABLE_FRAGMENT`,
      [128 /* KEYED_FRAGMENT */]: `KEYED_FRAGMENT`,
      [256 /* UNKEYED_FRAGMENT */]: `UNKEYED_FRAGMENT`,
      [512 /* NEED_PATCH */]: `NEED_PATCH`,
      [1024 /* DYNAMIC_SLOTS */]: `DYNAMIC_SLOTS`,
      [2048 /* DEV_ROOT_FRAGMENT */]: `DEV_ROOT_FRAGMENT`,
      [-1 /* HOISTED */]: `HOISTED`,
      [-2 /* BAIL */]: `BAIL`
  };

  /**
   * Dev only
   */
  const slotFlagsText = {
      [1 /* STABLE */]: 'STABLE',
      [2 /* DYNAMIC */]: 'DYNAMIC',
      [3 /* FORWARDED */]: 'FORWARDED'
  };

  const GLOBALS_WHITE_LISTED = 'Infinity,undefined,NaN,isFinite,isNaN,parseFloat,parseInt,decodeURI,' +
      'decodeURIComponent,encodeURI,encodeURIComponent,Math,Number,Date,Array,' +
      'Object,Boolean,String,RegExp,Map,Set,JSON,Intl,BigInt';
  const isGloballyWhitelisted = /*#__PURE__*/ makeMap(GLOBALS_WHITE_LISTED);

  const range = 2;
  function generateCodeFrame(source, start = 0, end = source.length) {
      // Split the content into individual lines but capture the newline sequence
      // that separated each line. This is important because the actual sequence is
      // needed to properly take into account the full line length for offset
      // comparison
      let lines = source.split(/(\r?\n)/);
      // Separate the lines and newline sequences into separate arrays for easier referencing
      const newlineSequences = lines.filter((_, idx) => idx % 2 === 1);
      lines = lines.filter((_, idx) => idx % 2 === 0);
      let count = 0;
      const res = [];
      for (let i = 0; i < lines.length; i++) {
          count +=
              lines[i].length +
                  ((newlineSequences[i] && newlineSequences[i].length) || 0);
          if (count >= start) {
              for (let j = i - range; j <= i + range || end > count; j++) {
                  if (j < 0 || j >= lines.length)
                      continue;
                  const line = j + 1;
                  res.push(`${line}${' '.repeat(Math.max(3 - String(line).length, 0))}|  ${lines[j]}`);
                  const lineLength = lines[j].length;
                  const newLineSeqLength = (newlineSequences[j] && newlineSequences[j].length) || 0;
                  if (j === i) {
                      // push underline
                      const pad = start - (count - (lineLength + newLineSeqLength));
                      const length = Math.max(1, end > count ? lineLength - pad : end - start);
                      res.push(`   |  ` + ' '.repeat(pad) + '^'.repeat(length));
                  }
                  else if (j > i) {
                      if (end > count) {
                          const length = Math.max(Math.min(end - count, lineLength), 1);
                          res.push(`   |  ` + '^'.repeat(length));
                      }
                      count += lineLength + newLineSeqLength;
                  }
              }
              break;
          }
      }
      return res.join('\n');
  }

  /**
   * On the client we only need to offer special cases for boolean attributes that
   * have different names from their corresponding dom properties:
   * - itemscope -> N/A
   * - allowfullscreen -> allowFullscreen
   * - formnovalidate -> formNoValidate
   * - ismap -> isMap
   * - nomodule -> noModule
   * - novalidate -> noValidate
   * - readonly -> readOnly
   */
  const specialBooleanAttrs = `itemscope,allowfullscreen,formnovalidate,ismap,nomodule,novalidate,readonly`;
  const isSpecialBooleanAttr = /*#__PURE__*/ makeMap(specialBooleanAttrs);
  /**
   * Boolean attributes should be included if the value is truthy or ''.
   * e.g. `<select multiple>` compiles to `{ multiple: '' }`
   */
  function includeBooleanAttr(value) {
      return !!value || value === '';
  }

  function normalizeStyle(value) {
      if (isArray(value)) {
          const res = {};
          for (let i = 0; i < value.length; i++) {
              const item = value[i];
              const normalized = isString(item)
                  ? parseStringStyle(item)
                  : normalizeStyle(item);
              if (normalized) {
                  for (const key in normalized) {
                      res[key] = normalized[key];
                  }
              }
          }
          return res;
      }
      else if (isString(value)) {
          return value;
      }
      else if (isObject(value)) {
          return value;
      }
  }
  const listDelimiterRE = /;(?![^(]*\))/g;
  const propertyDelimiterRE = /:(.+)/;
  function parseStringStyle(cssText) {
      const ret = {};
      cssText.split(listDelimiterRE).forEach(item => {
          if (item) {
              const tmp = item.split(propertyDelimiterRE);
              tmp.length > 1 && (ret[tmp[0].trim()] = tmp[1].trim());
          }
      });
      return ret;
  }
  function normalizeClass(value) {
      let res = '';
      if (isString(value)) {
          res = value;
      }
      else if (isArray(value)) {
          for (let i = 0; i < value.length; i++) {
              const normalized = normalizeClass(value[i]);
              if (normalized) {
                  res += normalized + ' ';
              }
          }
      }
      else if (isObject(value)) {
          for (const name in value) {
              if (value[name]) {
                  res += name + ' ';
              }
          }
      }
      return res.trim();
  }
  function normalizeProps(props) {
      if (!props)
          return null;
      let { class: klass, style } = props;
      if (klass && !isString(klass)) {
          props.class = normalizeClass(klass);
      }
      if (style) {
          props.style = normalizeStyle(style);
      }
      return props;
  }

  // These tag configs are shared between compiler-dom and runtime-dom, so they
  // https://developer.mozilla.org/en-US/docs/Web/HTML/Element
  const HTML_TAGS = 'html,body,base,head,link,meta,style,title,address,article,aside,footer,' +
      'header,h1,h2,h3,h4,h5,h6,nav,section,div,dd,dl,dt,figcaption,' +
      'figure,picture,hr,img,li,main,ol,p,pre,ul,a,b,abbr,bdi,bdo,br,cite,code,' +
      'data,dfn,em,i,kbd,mark,q,rp,rt,ruby,s,samp,small,span,strong,sub,sup,' +
      'time,u,var,wbr,area,audio,map,track,video,embed,object,param,source,' +
      'canvas,script,noscript,del,ins,caption,col,colgroup,table,thead,tbody,td,' +
      'th,tr,button,datalist,fieldset,form,input,label,legend,meter,optgroup,' +
      'option,output,progress,select,textarea,details,dialog,menu,' +
      'summary,template,blockquote,iframe,tfoot';
  // https://developer.mozilla.org/en-US/docs/Web/SVG/Element
  const SVG_TAGS = 'svg,animate,animateMotion,animateTransform,circle,clipPath,color-profile,' +
      'defs,desc,discard,ellipse,feBlend,feColorMatrix,feComponentTransfer,' +
      'feComposite,feConvolveMatrix,feDiffuseLighting,feDisplacementMap,' +
      'feDistanceLight,feDropShadow,feFlood,feFuncA,feFuncB,feFuncG,feFuncR,' +
      'feGaussianBlur,feImage,feMerge,feMergeNode,feMorphology,feOffset,' +
      'fePointLight,feSpecularLighting,feSpotLight,feTile,feTurbulence,filter,' +
      'foreignObject,g,hatch,hatchpath,image,line,linearGradient,marker,mask,' +
      'mesh,meshgradient,meshpatch,meshrow,metadata,mpath,path,pattern,' +
      'polygon,polyline,radialGradient,rect,set,solidcolor,stop,switch,symbol,' +
      'text,textPath,title,tspan,unknown,use,view';
  const VOID_TAGS = 'area,base,br,col,embed,hr,img,input,link,meta,param,source,track,wbr';
  /**
   * Compiler only.
   * Do NOT use in runtime code paths unless behind `true` flag.
   */
  const isHTMLTag = /*#__PURE__*/ makeMap(HTML_TAGS);
  /**
   * Compiler only.
   * Do NOT use in runtime code paths unless behind `true` flag.
   */
  const isSVGTag = /*#__PURE__*/ makeMap(SVG_TAGS);
  /**
   * Compiler only.
   * Do NOT use in runtime code paths unless behind `true` flag.
   */
  const isVoidTag = /*#__PURE__*/ makeMap(VOID_TAGS);

  function looseCompareArrays(a, b) {
      if (a.length !== b.length)
          return false;
      let equal = true;
      for (let i = 0; equal && i < a.length; i++) {
          equal = looseEqual(a[i], b[i]);
      }
      return equal;
  }
  function looseEqual(a, b) {
      if (a === b)
          return true;
      let aValidType = isDate(a);
      let bValidType = isDate(b);
      if (aValidType || bValidType) {
          return aValidType && bValidType ? a.getTime() === b.getTime() : false;
      }
      aValidType = isSymbol(a);
      bValidType = isSymbol(b);
      if (aValidType || bValidType) {
          return a === b;
      }
      aValidType = isArray(a);
      bValidType = isArray(b);
      if (aValidType || bValidType) {
          return aValidType && bValidType ? looseCompareArrays(a, b) : false;
      }
      aValidType = isObject(a);
      bValidType = isObject(b);
      if (aValidType || bValidType) {
          /* istanbul ignore if: this if will probably never be called */
          if (!aValidType || !bValidType) {
              return false;
          }
          const aKeysCount = Object.keys(a).length;
          const bKeysCount = Object.keys(b).length;
          if (aKeysCount !== bKeysCount) {
              return false;
          }
          for (const key in a) {
              const aHasKey = a.hasOwnProperty(key);
              const bHasKey = b.hasOwnProperty(key);
              if ((aHasKey && !bHasKey) ||
                  (!aHasKey && bHasKey) ||
                  !looseEqual(a[key], b[key])) {
                  return false;
              }
          }
      }
      return String(a) === String(b);
  }
  function looseIndexOf(arr, val) {
      return arr.findIndex(item => looseEqual(item, val));
  }

  /**
   * For converting {{ interpolation }} values to displayed strings.
   * @private
   */
  const toDisplayString = (val) => {
      return isString(val)
          ? val
          : val == null
              ? ''
              : isArray(val) ||
                  (isObject(val) &&
                      (val.toString === objectToString || !isFunction(val.toString)))
                  ? JSON.stringify(val, replacer, 2)
                  : String(val);
  };
  const replacer = (_key, val) => {
      // can't use isRef here since @vue/shared has no deps
      if (val && val.__v_isRef) {
          return replacer(_key, val.value);
      }
      else if (isMap(val)) {
          return {
              [`Map(${val.size})`]: [...val.entries()].reduce((entries, [key, val]) => {
                  entries[`${key} =>`] = val;
                  return entries;
              }, {})
          };
      }
      else if (isSet(val)) {
          return {
              [`Set(${val.size})`]: [...val.values()]
          };
      }
      else if (isObject(val) && !isArray(val) && !isPlainObject(val)) {
          return String(val);
      }
      return val;
  };

  const EMPTY_OBJ = Object.freeze({})
      ;
  const EMPTY_ARR = Object.freeze([]) ;
  const NOOP = () => { };
  /**
   * Always return false.
   */
  const NO = () => false;
  const onRE = /^on[^a-z]/;
  const isOn = (key) => onRE.test(key);
  const isModelListener = (key) => key.startsWith('onUpdate:');
  const extend = Object.assign;
  const remove = (arr, el) => {
      const i = arr.indexOf(el);
      if (i > -1) {
          arr.splice(i, 1);
      }
  };
  const hasOwnProperty = Object.prototype.hasOwnProperty;
  const hasOwn = (val, key) => hasOwnProperty.call(val, key);
  const isArray = Array.isArray;
  const isMap = (val) => toTypeString(val) === '[object Map]';
  const isSet = (val) => toTypeString(val) === '[object Set]';
  const isDate = (val) => toTypeString(val) === '[object Date]';
  const isFunction = (val) => typeof val === 'function';
  const isString = (val) => typeof val === 'string';
  const isSymbol = (val) => typeof val === 'symbol';
  const isObject = (val) => val !== null && typeof val === 'object';
  const isPromise = (val) => {
      return isObject(val) && isFunction(val.then) && isFunction(val.catch);
  };
  const objectToString = Object.prototype.toString;
  const toTypeString = (value) => objectToString.call(value);
  const toRawType = (value) => {
      // extract "RawType" from strings like "[object RawType]"
      return toTypeString(value).slice(8, -1);
  };
  const isPlainObject = (val) => toTypeString(val) === '[object Object]';
  const isIntegerKey = (key) => isString(key) &&
      key !== 'NaN' &&
      key[0] !== '-' &&
      '' + parseInt(key, 10) === key;
  const isReservedProp = /*#__PURE__*/ makeMap(
  // the leading comma is intentional so empty string "" is also included
  ',key,ref,ref_for,ref_key,' +
      'onVnodeBeforeMount,onVnodeMounted,' +
      'onVnodeBeforeUpdate,onVnodeUpdated,' +
      'onVnodeBeforeUnmount,onVnodeUnmounted');
  const isBuiltInDirective = /*#__PURE__*/ makeMap('bind,cloak,else-if,else,for,html,if,model,on,once,pre,show,slot,text,memo');
  const cacheStringFunction = (fn) => {
      const cache = Object.create(null);
      return ((str) => {
          const hit = cache[str];
          return hit || (cache[str] = fn(str));
      });
  };
  const camelizeRE = /-(\w)/g;
  /**
   * @private
   */
  const camelize = cacheStringFunction((str) => {
      return str.replace(camelizeRE, (_, c) => (c ? c.toUpperCase() : ''));
  });
  const hyphenateRE = /\B([A-Z])/g;
  /**
   * @private
   */
  const hyphenate = cacheStringFunction((str) => str.replace(hyphenateRE, '-$1').toLowerCase());
  /**
   * @private
   */
  const capitalize = cacheStringFunction((str) => str.charAt(0).toUpperCase() + str.slice(1));
  /**
   * @private
   */
  const toHandlerKey = cacheStringFunction((str) => str ? `on${capitalize(str)}` : ``);
  // compare whether a value has changed, accounting for NaN.
  const hasChanged = (value, oldValue) => !Object.is(value, oldValue);
  const invokeArrayFns = (fns, arg) => {
      for (let i = 0; i < fns.length; i++) {
          fns[i](arg);
      }
  };
  const def = (obj, key, value) => {
      Object.defineProperty(obj, key, {
          configurable: true,
          enumerable: false,
          value
      });
  };
  const toNumber = (val) => {
      const n = parseFloat(val);
      return isNaN(n) ? val : n;
  };
  let _globalThis;
  const getGlobalThis = () => {
      return (_globalThis ||
          (_globalThis =
              typeof globalThis !== 'undefined'
                  ? globalThis
                  : typeof self !== 'undefined'
                      ? self
                      : typeof window !== 'undefined'
                          ? window
                          : typeof global !== 'undefined'
                              ? global
                              : {}));
  };

  function warn(msg, ...args) {
      console.warn(`[Vue warn] ${msg}`, ...args);
  }

  let activeEffectScope;
  class EffectScope {
      constructor(detached = false) {
          /**
           * @internal
           */
          this.active = true;
          /**
           * @internal
           */
          this.effects = [];
          /**
           * @internal
           */
          this.cleanups = [];
          if (!detached && activeEffectScope) {
              this.parent = activeEffectScope;
              this.index =
                  (activeEffectScope.scopes || (activeEffectScope.scopes = [])).push(this) - 1;
          }
      }
      run(fn) {
          if (this.active) {
              const currentEffectScope = activeEffectScope;
              try {
                  activeEffectScope = this;
                  return fn();
              }
              finally {
                  activeEffectScope = currentEffectScope;
              }
          }
          else {
              warn(`cannot run an inactive effect scope.`);
          }
      }
      /**
       * This should only be called on non-detached scopes
       * @internal
       */
      on() {
          activeEffectScope = this;
      }
      /**
       * This should only be called on non-detached scopes
       * @internal
       */
      off() {
          activeEffectScope = this.parent;
      }
      stop(fromParent) {
          if (this.active) {
              let i, l;
              for (i = 0, l = this.effects.length; i < l; i++) {
                  this.effects[i].stop();
              }
              for (i = 0, l = this.cleanups.length; i < l; i++) {
                  this.cleanups[i]();
              }
              if (this.scopes) {
                  for (i = 0, l = this.scopes.length; i < l; i++) {
                      this.scopes[i].stop(true);
                  }
              }
              // nested scope, dereference from parent to avoid memory leaks
              if (this.parent && !fromParent) {
                  // optimized O(1) removal
                  const last = this.parent.scopes.pop();
                  if (last && last !== this) {
                      this.parent.scopes[this.index] = last;
                      last.index = this.index;
                  }
              }
              this.active = false;
          }
      }
  }
  function effectScope(detached) {
      return new EffectScope(detached);
  }
  function recordEffectScope(effect, scope = activeEffectScope) {
      if (scope && scope.active) {
          scope.effects.push(effect);
      }
  }
  function getCurrentScope() {
      return activeEffectScope;
  }
  function onScopeDispose(fn) {
      if (activeEffectScope) {
          activeEffectScope.cleanups.push(fn);
      }
      else {
          warn(`onScopeDispose() is called when there is no active effect scope` +
              ` to be associated with.`);
      }
  }

  const createDep = (effects) => {
      const dep = new Set(effects);
      dep.w = 0;
      dep.n = 0;
      return dep;
  };
  const wasTracked = (dep) => (dep.w & trackOpBit) > 0;
  const newTracked = (dep) => (dep.n & trackOpBit) > 0;
  const initDepMarkers = ({ deps }) => {
      if (deps.length) {
          for (let i = 0; i < deps.length; i++) {
              deps[i].w |= trackOpBit; // set was tracked
          }
      }
  };
  const finalizeDepMarkers = (effect) => {
      const { deps } = effect;
      if (deps.length) {
          let ptr = 0;
          for (let i = 0; i < deps.length; i++) {
              const dep = deps[i];
              if (wasTracked(dep) && !newTracked(dep)) {
                  dep.delete(effect);
              }
              else {
                  deps[ptr++] = dep;
              }
              // clear bits
              dep.w &= ~trackOpBit;
              dep.n &= ~trackOpBit;
          }
          deps.length = ptr;
      }
  };

  const targetMap = new WeakMap();
  // The number of effects currently being tracked recursively.
  let effectTrackDepth = 0;
  let trackOpBit = 1;
  /**
   * The bitwise track markers support at most 30 levels of recursion.
   * This value is chosen to enable modern JS engines to use a SMI on all platforms.
   * When recursion depth is greater, fall back to using a full cleanup.
   */
  const maxMarkerBits = 30;
  let activeEffect;
  const ITERATE_KEY = Symbol('iterate' );
  const MAP_KEY_ITERATE_KEY = Symbol('Map key iterate' );
  class ReactiveEffect {
      constructor(fn, scheduler = null, scope) {
          this.fn = fn;
          this.scheduler = scheduler;
          this.active = true;
          this.deps = [];
          this.parent = undefined;
          recordEffectScope(this, scope);
      }
      run() {
          if (!this.active) {
              return this.fn();
          }
          let parent = activeEffect;
          let lastShouldTrack = shouldTrack;
          while (parent) {
              if (parent === this) {
                  return;
              }
              parent = parent.parent;
          }
          try {
              this.parent = activeEffect;
              activeEffect = this;
              shouldTrack = true;
              trackOpBit = 1 << ++effectTrackDepth;
              if (effectTrackDepth <= maxMarkerBits) {
                  initDepMarkers(this);
              }
              else {
                  cleanupEffect(this);
              }
              return this.fn();
          }
          finally {
              if (effectTrackDepth <= maxMarkerBits) {
                  finalizeDepMarkers(this);
              }
              trackOpBit = 1 << --effectTrackDepth;
              activeEffect = this.parent;
              shouldTrack = lastShouldTrack;
              this.parent = undefined;
              if (this.deferStop) {
                  this.stop();
              }
          }
      }
      stop() {
          // stopped while running itself - defer the cleanup
          if (activeEffect === this) {
              this.deferStop = true;
          }
          else if (this.active) {
              cleanupEffect(this);
              if (this.onStop) {
                  this.onStop();
              }
              this.active = false;
          }
      }
  }
  function cleanupEffect(effect) {
      const { deps } = effect;
      if (deps.length) {
          for (let i = 0; i < deps.length; i++) {
              deps[i].delete(effect);
          }
          deps.length = 0;
      }
  }
  function effect(fn, options) {
      if (fn.effect) {
          fn = fn.effect.fn;
      }
      const _effect = new ReactiveEffect(fn);
      if (options) {
          extend(_effect, options);
          if (options.scope)
              recordEffectScope(_effect, options.scope);
      }
      if (!options || !options.lazy) {
          _effect.run();
      }
      const runner = _effect.run.bind(_effect);
      runner.effect = _effect;
      return runner;
  }
  function stop(runner) {
      runner.effect.stop();
  }
  let shouldTrack = true;
  const trackStack = [];
  function pauseTracking() {
      trackStack.push(shouldTrack);
      shouldTrack = false;
  }
  function resetTracking() {
      const last = trackStack.pop();
      shouldTrack = last === undefined ? true : last;
  }
  function track(target, type, key) {
      if (shouldTrack && activeEffect) {
          let depsMap = targetMap.get(target);
          if (!depsMap) {
              targetMap.set(target, (depsMap = new Map()));
          }
          let dep = depsMap.get(key);
          if (!dep) {
              depsMap.set(key, (dep = createDep()));
          }
          const eventInfo = { effect: activeEffect, target, type, key }
              ;
          trackEffects(dep, eventInfo);
      }
  }
  function trackEffects(dep, debuggerEventExtraInfo) {
      let shouldTrack = false;
      if (effectTrackDepth <= maxMarkerBits) {
          if (!newTracked(dep)) {
              dep.n |= trackOpBit; // set newly tracked
              shouldTrack = !wasTracked(dep);
          }
      }
      else {
          // Full cleanup mode.
          shouldTrack = !dep.has(activeEffect);
      }
      if (shouldTrack) {
          dep.add(activeEffect);
          activeEffect.deps.push(dep);
          if (activeEffect.onTrack) {
              activeEffect.onTrack(Object.assign({ effect: activeEffect }, debuggerEventExtraInfo));
          }
      }
  }
  function trigger(target, type, key, newValue, oldValue, oldTarget) {
      const depsMap = targetMap.get(target);
      if (!depsMap) {
          // never been tracked
          return;
      }
      let deps = [];
      if (type === "clear" /* CLEAR */) {
          // collection being cleared
          // trigger all effects for target
          deps = [...depsMap.values()];
      }
      else if (key === 'length' && isArray(target)) {
          depsMap.forEach((dep, key) => {
              if (key === 'length' || key >= newValue) {
                  deps.push(dep);
              }
          });
      }
      else {
          // schedule runs for SET | ADD | DELETE
          if (key !== void 0) {
              deps.push(depsMap.get(key));
          }
          // also run for iteration key on ADD | DELETE | Map.SET
          switch (type) {
              case "add" /* ADD */:
                  if (!isArray(target)) {
                      deps.push(depsMap.get(ITERATE_KEY));
                      if (isMap(target)) {
                          deps.push(depsMap.get(MAP_KEY_ITERATE_KEY));
                      }
                  }
                  else if (isIntegerKey(key)) {
                      // new index added to array -> length changes
                      deps.push(depsMap.get('length'));
                  }
                  break;
              case "delete" /* DELETE */:
                  if (!isArray(target)) {
                      deps.push(depsMap.get(ITERATE_KEY));
                      if (isMap(target)) {
                          deps.push(depsMap.get(MAP_KEY_ITERATE_KEY));
                      }
                  }
                  break;
              case "set" /* SET */:
                  if (isMap(target)) {
                      deps.push(depsMap.get(ITERATE_KEY));
                  }
                  break;
          }
      }
      const eventInfo = { target, type, key, newValue, oldValue, oldTarget }
          ;
      if (deps.length === 1) {
          if (deps[0]) {
              {
                  triggerEffects(deps[0], eventInfo);
              }
          }
      }
      else {
          const effects = [];
          for (const dep of deps) {
              if (dep) {
                  effects.push(...dep);
              }
          }
          {
              triggerEffects(createDep(effects), eventInfo);
          }
      }
  }
  function triggerEffects(dep, debuggerEventExtraInfo) {
      // spread into array for stabilization
      const effects = isArray(dep) ? dep : [...dep];
      for (const effect of effects) {
          if (effect.computed) {
              triggerEffect(effect, debuggerEventExtraInfo);
          }
      }
      for (const effect of effects) {
          if (!effect.computed) {
              triggerEffect(effect, debuggerEventExtraInfo);
          }
      }
  }
  function triggerEffect(effect, debuggerEventExtraInfo) {
      if (effect !== activeEffect || effect.allowRecurse) {
          if (effect.onTrigger) {
              effect.onTrigger(extend({ effect }, debuggerEventExtraInfo));
          }
          if (effect.scheduler) {
              effect.scheduler();
          }
          else {
              effect.run();
          }
      }
  }

  const isNonTrackableKeys = /*#__PURE__*/ makeMap(`__proto__,__v_isRef,__isVue`);
  const builtInSymbols = new Set(
  /*#__PURE__*/
  Object.getOwnPropertyNames(Symbol)
      // ios10.x Object.getOwnPropertyNames(Symbol) can enumerate 'arguments' and 'caller'
      // but accessing them on Symbol leads to TypeError because Symbol is a strict mode
      // function
      .filter(key => key !== 'arguments' && key !== 'caller')
      .map(key => Symbol[key])
      .filter(isSymbol));
  const get = /*#__PURE__*/ createGetter();
  const shallowGet = /*#__PURE__*/ createGetter(false, true);
  const readonlyGet = /*#__PURE__*/ createGetter(true);
  const shallowReadonlyGet = /*#__PURE__*/ createGetter(true, true);
  const arrayInstrumentations = /*#__PURE__*/ createArrayInstrumentations();
  function createArrayInstrumentations() {
      const instrumentations = {};
      ['includes', 'indexOf', 'lastIndexOf'].forEach(key => {
          instrumentations[key] = function (...args) {
              const arr = toRaw(this);
              for (let i = 0, l = this.length; i < l; i++) {
                  track(arr, "get" /* GET */, i + '');
              }
              // we run the method using the original args first (which may be reactive)
              const res = arr[key](...args);
              if (res === -1 || res === false) {
                  // if that didn't work, run it again using raw values.
                  return arr[key](...args.map(toRaw));
              }
              else {
                  return res;
              }
          };
      });
      ['push', 'pop', 'shift', 'unshift', 'splice'].forEach(key => {
          instrumentations[key] = function (...args) {
              pauseTracking();
              const res = toRaw(this)[key].apply(this, args);
              resetTracking();
              return res;
          };
      });
      return instrumentations;
  }
  function createGetter(isReadonly = false, shallow = false) {
      return function get(target, key, receiver) {
          if (key === "__v_isReactive" /* IS_REACTIVE */) {
              return !isReadonly;
          }
          else if (key === "__v_isReadonly" /* IS_READONLY */) {
              return isReadonly;
          }
          else if (key === "__v_isShallow" /* IS_SHALLOW */) {
              return shallow;
          }
          else if (key === "__v_raw" /* RAW */ &&
              receiver ===
                  (isReadonly
                      ? shallow
                          ? shallowReadonlyMap
                          : readonlyMap
                      : shallow
                          ? shallowReactiveMap
                          : reactiveMap).get(target)) {
              return target;
          }
          const targetIsArray = isArray(target);
          if (!isReadonly && targetIsArray && hasOwn(arrayInstrumentations, key)) {
              return Reflect.get(arrayInstrumentations, key, receiver);
          }
          const res = Reflect.get(target, key, receiver);
          if (isSymbol(key) ? builtInSymbols.has(key) : isNonTrackableKeys(key)) {
              return res;
          }
          if (!isReadonly) {
              track(target, "get" /* GET */, key);
          }
          if (shallow) {
              return res;
          }
          if (isRef(res)) {
              // ref unwrapping - skip unwrap for Array + integer key.
              return targetIsArray && isIntegerKey(key) ? res : res.value;
          }
          if (isObject(res)) {
              // Convert returned value into a proxy as well. we do the isObject check
              // here to avoid invalid value warning. Also need to lazy access readonly
              // and reactive here to avoid circular dependency.
              return isReadonly ? readonly(res) : reactive(res);
          }
          return res;
      };
  }
  const set = /*#__PURE__*/ createSetter();
  const shallowSet = /*#__PURE__*/ createSetter(true);
  function createSetter(shallow = false) {
      return function set(target, key, value, receiver) {
          let oldValue = target[key];
          if (isReadonly(oldValue) && isRef(oldValue) && !isRef(value)) {
              return false;
          }
          if (!shallow && !isReadonly(value)) {
              if (!isShallow(value)) {
                  value = toRaw(value);
                  oldValue = toRaw(oldValue);
              }
              if (!isArray(target) && isRef(oldValue) && !isRef(value)) {
                  oldValue.value = value;
                  return true;
              }
          }
          const hadKey = isArray(target) && isIntegerKey(key)
              ? Number(key) < target.length
              : hasOwn(target, key);
          const result = Reflect.set(target, key, value, receiver);
          // don't trigger if target is something up in the prototype chain of original
          if (target === toRaw(receiver)) {
              if (!hadKey) {
                  trigger(target, "add" /* ADD */, key, value);
              }
              else if (hasChanged(value, oldValue)) {
                  trigger(target, "set" /* SET */, key, value, oldValue);
              }
          }
          return result;
      };
  }
  function deleteProperty(target, key) {
      const hadKey = hasOwn(target, key);
      const oldValue = target[key];
      const result = Reflect.deleteProperty(target, key);
      if (result && hadKey) {
          trigger(target, "delete" /* DELETE */, key, undefined, oldValue);
      }
      return result;
  }
  function has(target, key) {
      const result = Reflect.has(target, key);
      if (!isSymbol(key) || !builtInSymbols.has(key)) {
          track(target, "has" /* HAS */, key);
      }
      return result;
  }
  function ownKeys(target) {
      track(target, "iterate" /* ITERATE */, isArray(target) ? 'length' : ITERATE_KEY);
      return Reflect.ownKeys(target);
  }
  const mutableHandlers = {
      get,
      set,
      deleteProperty,
      has,
      ownKeys
  };
  const readonlyHandlers = {
      get: readonlyGet,
      set(target, key) {
          {
              warn(`Set operation on key "${String(key)}" failed: target is readonly.`, target);
          }
          return true;
      },
      deleteProperty(target, key) {
          {
              warn(`Delete operation on key "${String(key)}" failed: target is readonly.`, target);
          }
          return true;
      }
  };
  const shallowReactiveHandlers = /*#__PURE__*/ extend({}, mutableHandlers, {
      get: shallowGet,
      set: shallowSet
  });
  // Props handlers are special in the sense that it should not unwrap top-level
  // refs (in order to allow refs to be explicitly passed down), but should
  // retain the reactivity of the normal readonly object.
  const shallowReadonlyHandlers = /*#__PURE__*/ extend({}, readonlyHandlers, {
      get: shallowReadonlyGet
  });

  const toShallow = (value) => value;
  const getProto = (v) => Reflect.getPrototypeOf(v);
  function get$1(target, key, isReadonly = false, isShallow = false) {
      // #1772: readonly(reactive(Map)) should return readonly + reactive version
      // of the value
      target = target["__v_raw" /* RAW */];
      const rawTarget = toRaw(target);
      const rawKey = toRaw(key);
      if (!isReadonly) {
          if (key !== rawKey) {
              track(rawTarget, "get" /* GET */, key);
          }
          track(rawTarget, "get" /* GET */, rawKey);
      }
      const { has } = getProto(rawTarget);
      const wrap = isShallow ? toShallow : isReadonly ? toReadonly : toReactive;
      if (has.call(rawTarget, key)) {
          return wrap(target.get(key));
      }
      else if (has.call(rawTarget, rawKey)) {
          return wrap(target.get(rawKey));
      }
      else if (target !== rawTarget) {
          // #3602 readonly(reactive(Map))
          // ensure that the nested reactive `Map` can do tracking for itself
          target.get(key);
      }
  }
  function has$1(key, isReadonly = false) {
      const target = this["__v_raw" /* RAW */];
      const rawTarget = toRaw(target);
      const rawKey = toRaw(key);
      if (!isReadonly) {
          if (key !== rawKey) {
              track(rawTarget, "has" /* HAS */, key);
          }
          track(rawTarget, "has" /* HAS */, rawKey);
      }
      return key === rawKey
          ? target.has(key)
          : target.has(key) || target.has(rawKey);
  }
  function size(target, isReadonly = false) {
      target = target["__v_raw" /* RAW */];
      !isReadonly && track(toRaw(target), "iterate" /* ITERATE */, ITERATE_KEY);
      return Reflect.get(target, 'size', target);
  }
  function add(value) {
      value = toRaw(value);
      const target = toRaw(this);
      const proto = getProto(target);
      const hadKey = proto.has.call(target, value);
      if (!hadKey) {
          target.add(value);
          trigger(target, "add" /* ADD */, value, value);
      }
      return this;
  }
  function set$1(key, value) {
      value = toRaw(value);
      const target = toRaw(this);
      const { has, get } = getProto(target);
      let hadKey = has.call(target, key);
      if (!hadKey) {
          key = toRaw(key);
          hadKey = has.call(target, key);
      }
      else {
          checkIdentityKeys(target, has, key);
      }
      const oldValue = get.call(target, key);
      target.set(key, value);
      if (!hadKey) {
          trigger(target, "add" /* ADD */, key, value);
      }
      else if (hasChanged(value, oldValue)) {
          trigger(target, "set" /* SET */, key, value, oldValue);
      }
      return this;
  }
  function deleteEntry(key) {
      const target = toRaw(this);
      const { has, get } = getProto(target);
      let hadKey = has.call(target, key);
      if (!hadKey) {
          key = toRaw(key);
          hadKey = has.call(target, key);
      }
      else {
          checkIdentityKeys(target, has, key);
      }
      const oldValue = get ? get.call(target, key) : undefined;
      // forward the operation before queueing reactions
      const result = target.delete(key);
      if (hadKey) {
          trigger(target, "delete" /* DELETE */, key, undefined, oldValue);
      }
      return result;
  }
  function clear() {
      const target = toRaw(this);
      const hadItems = target.size !== 0;
      const oldTarget = isMap(target)
              ? new Map(target)
              : new Set(target)
          ;
      // forward the operation before queueing reactions
      const result = target.clear();
      if (hadItems) {
          trigger(target, "clear" /* CLEAR */, undefined, undefined, oldTarget);
      }
      return result;
  }
  function createForEach(isReadonly, isShallow) {
      return function forEach(callback, thisArg) {
          const observed = this;
          const target = observed["__v_raw" /* RAW */];
          const rawTarget = toRaw(target);
          const wrap = isShallow ? toShallow : isReadonly ? toReadonly : toReactive;
          !isReadonly && track(rawTarget, "iterate" /* ITERATE */, ITERATE_KEY);
          return target.forEach((value, key) => {
              // important: make sure the callback is
              // 1. invoked with the reactive map as `this` and 3rd arg
              // 2. the value received should be a corresponding reactive/readonly.
              return callback.call(thisArg, wrap(value), wrap(key), observed);
          });
      };
  }
  function createIterableMethod(method, isReadonly, isShallow) {
      return function (...args) {
          const target = this["__v_raw" /* RAW */];
          const rawTarget = toRaw(target);
          const targetIsMap = isMap(rawTarget);
          const isPair = method === 'entries' || (method === Symbol.iterator && targetIsMap);
          const isKeyOnly = method === 'keys' && targetIsMap;
          const innerIterator = target[method](...args);
          const wrap = isShallow ? toShallow : isReadonly ? toReadonly : toReactive;
          !isReadonly &&
              track(rawTarget, "iterate" /* ITERATE */, isKeyOnly ? MAP_KEY_ITERATE_KEY : ITERATE_KEY);
          // return a wrapped iterator which returns observed versions of the
          // values emitted from the real iterator
          return {
              // iterator protocol
              next() {
                  const { value, done } = innerIterator.next();
                  return done
                      ? { value, done }
                      : {
                          value: isPair ? [wrap(value[0]), wrap(value[1])] : wrap(value),
                          done
                      };
              },
              // iterable protocol
              [Symbol.iterator]() {
                  return this;
              }
          };
      };
  }
  function createReadonlyMethod(type) {
      return function (...args) {
          {
              const key = args[0] ? `on key "${args[0]}" ` : ``;
              console.warn(`${capitalize(type)} operation ${key}failed: target is readonly.`, toRaw(this));
          }
          return type === "delete" /* DELETE */ ? false : this;
      };
  }
  function createInstrumentations() {
      const mutableInstrumentations = {
          get(key) {
              return get$1(this, key);
          },
          get size() {
              return size(this);
          },
          has: has$1,
          add,
          set: set$1,
          delete: deleteEntry,
          clear,
          forEach: createForEach(false, false)
      };
      const shallowInstrumentations = {
          get(key) {
              return get$1(this, key, false, true);
          },
          get size() {
              return size(this);
          },
          has: has$1,
          add,
          set: set$1,
          delete: deleteEntry,
          clear,
          forEach: createForEach(false, true)
      };
      const readonlyInstrumentations = {
          get(key) {
              return get$1(this, key, true);
          },
          get size() {
              return size(this, true);
          },
          has(key) {
              return has$1.call(this, key, true);
          },
          add: createReadonlyMethod("add" /* ADD */),
          set: createReadonlyMethod("set" /* SET */),
          delete: createReadonlyMethod("delete" /* DELETE */),
          clear: createReadonlyMethod("clear" /* CLEAR */),
          forEach: createForEach(true, false)
      };
      const shallowReadonlyInstrumentations = {
          get(key) {
              return get$1(this, key, true, true);
          },
          get size() {
              return size(this, true);
          },
          has(key) {
              return has$1.call(this, key, true);
          },
          add: createReadonlyMethod("add" /* ADD */),
          set: createReadonlyMethod("set" /* SET */),
          delete: createReadonlyMethod("delete" /* DELETE */),
          clear: createReadonlyMethod("clear" /* CLEAR */),
          forEach: createForEach(true, true)
      };
      const iteratorMethods = ['keys', 'values', 'entries', Symbol.iterator];
      iteratorMethods.forEach(method => {
          mutableInstrumentations[method] = createIterableMethod(method, false, false);
          readonlyInstrumentations[method] = createIterableMethod(method, true, false);
          shallowInstrumentations[method] = createIterableMethod(method, false, true);
          shallowReadonlyInstrumentations[method] = createIterableMethod(method, true, true);
      });
      return [
          mutableInstrumentations,
          readonlyInstrumentations,
          shallowInstrumentations,
          shallowReadonlyInstrumentations
      ];
  }
  const [mutableInstrumentations, readonlyInstrumentations, shallowInstrumentations, shallowReadonlyInstrumentations] = /* #__PURE__*/ createInstrumentations();
  function createInstrumentationGetter(isReadonly, shallow) {
      const instrumentations = shallow
          ? isReadonly
              ? shallowReadonlyInstrumentations
              : shallowInstrumentations
          : isReadonly
              ? readonlyInstrumentations
              : mutableInstrumentations;
      return (target, key, receiver) => {
          if (key === "__v_isReactive" /* IS_REACTIVE */) {
              return !isReadonly;
          }
          else if (key === "__v_isReadonly" /* IS_READONLY */) {
              return isReadonly;
          }
          else if (key === "__v_raw" /* RAW */) {
              return target;
          }
          return Reflect.get(hasOwn(instrumentations, key) && key in target
              ? instrumentations
              : target, key, receiver);
      };
  }
  const mutableCollectionHandlers = {
      get: /*#__PURE__*/ createInstrumentationGetter(false, false)
  };
  const shallowCollectionHandlers = {
      get: /*#__PURE__*/ createInstrumentationGetter(false, true)
  };
  const readonlyCollectionHandlers = {
      get: /*#__PURE__*/ createInstrumentationGetter(true, false)
  };
  const shallowReadonlyCollectionHandlers = {
      get: /*#__PURE__*/ createInstrumentationGetter(true, true)
  };
  function checkIdentityKeys(target, has, key) {
      const rawKey = toRaw(key);
      if (rawKey !== key && has.call(target, rawKey)) {
          const type = toRawType(target);
          console.warn(`Reactive ${type} contains both the raw and reactive ` +
              `versions of the same object${type === `Map` ? ` as keys` : ``}, ` +
              `which can lead to inconsistencies. ` +
              `Avoid differentiating between the raw and reactive versions ` +
              `of an object and only use the reactive version if possible.`);
      }
  }

  const reactiveMap = new WeakMap();
  const shallowReactiveMap = new WeakMap();
  const readonlyMap = new WeakMap();
  const shallowReadonlyMap = new WeakMap();
  function targetTypeMap(rawType) {
      switch (rawType) {
          case 'Object':
          case 'Array':
              return 1 /* COMMON */;
          case 'Map':
          case 'Set':
          case 'WeakMap':
          case 'WeakSet':
              return 2 /* COLLECTION */;
          default:
              return 0 /* INVALID */;
      }
  }
  function getTargetType(value) {
      return value["__v_skip" /* SKIP */] || !Object.isExtensible(value)
          ? 0 /* INVALID */
          : targetTypeMap(toRawType(value));
  }
  function reactive(target) {
      // if trying to observe a readonly proxy, return the readonly version.
      if (isReadonly(target)) {
          return target;
      }
      return createReactiveObject(target, false, mutableHandlers, mutableCollectionHandlers, reactiveMap);
  }
  /**
   * Return a shallowly-reactive copy of the original object, where only the root
   * level properties are reactive. It also does not auto-unwrap refs (even at the
   * root level).
   */
  function shallowReactive(target) {
      return createReactiveObject(target, false, shallowReactiveHandlers, shallowCollectionHandlers, shallowReactiveMap);
  }
  /**
   * Creates a readonly copy of the original object. Note the returned copy is not
   * made reactive, but `readonly` can be called on an already reactive object.
   */
  function readonly(target) {
      return createReactiveObject(target, true, readonlyHandlers, readonlyCollectionHandlers, readonlyMap);
  }
  /**
   * Returns a reactive-copy of the original object, where only the root level
   * properties are readonly, and does NOT unwrap refs nor recursively convert
   * returned properties.
   * This is used for creating the props proxy object for stateful components.
   */
  function shallowReadonly(target) {
      return createReactiveObject(target, true, shallowReadonlyHandlers, shallowReadonlyCollectionHandlers, shallowReadonlyMap);
  }
  function createReactiveObject(target, isReadonly, baseHandlers, collectionHandlers, proxyMap) {
      if (!isObject(target)) {
          {
              console.warn(`value cannot be made reactive: ${String(target)}`);
          }
          return target;
      }
      // target is already a Proxy, return it.
      // exception: calling readonly() on a reactive object
      if (target["__v_raw" /* RAW */] &&
          !(isReadonly && target["__v_isReactive" /* IS_REACTIVE */])) {
          return target;
      }
      // target already has corresponding Proxy
      const existingProxy = proxyMap.get(target);
      if (existingProxy) {
          return existingProxy;
      }
      // only specific value types can be observed.
      const targetType = getTargetType(target);
      if (targetType === 0 /* INVALID */) {
          return target;
      }
      const proxy = new Proxy(target, targetType === 2 /* COLLECTION */ ? collectionHandlers : baseHandlers);
      proxyMap.set(target, proxy);
      return proxy;
  }
  function isReactive(value) {
      if (isReadonly(value)) {
          return isReactive(value["__v_raw" /* RAW */]);
      }
      return !!(value && value["__v_isReactive" /* IS_REACTIVE */]);
  }
  function isReadonly(value) {
      return !!(value && value["__v_isReadonly" /* IS_READONLY */]);
  }
  function isShallow(value) {
      return !!(value && value["__v_isShallow" /* IS_SHALLOW */]);
  }
  function isProxy(value) {
      return isReactive(value) || isReadonly(value);
  }
  function toRaw(observed) {
      const raw = observed && observed["__v_raw" /* RAW */];
      return raw ? toRaw(raw) : observed;
  }
  function markRaw(value) {
      def(value, "__v_skip" /* SKIP */, true);
      return value;
  }
  const toReactive = (value) => isObject(value) ? reactive(value) : value;
  const toReadonly = (value) => isObject(value) ? readonly(value) : value;

  function trackRefValue(ref) {
      if (shouldTrack && activeEffect) {
          ref = toRaw(ref);
          {
              trackEffects(ref.dep || (ref.dep = createDep()), {
                  target: ref,
                  type: "get" /* GET */,
                  key: 'value'
              });
          }
      }
  }
  function triggerRefValue(ref, newVal) {
      ref = toRaw(ref);
      if (ref.dep) {
          {
              triggerEffects(ref.dep, {
                  target: ref,
                  type: "set" /* SET */,
                  key: 'value',
                  newValue: newVal
              });
          }
      }
  }
  function isRef(r) {
      return !!(r && r.__v_isRef === true);
  }
  function ref(value) {
      return createRef(value, false);
  }
  function shallowRef(value) {
      return createRef(value, true);
  }
  function createRef(rawValue, shallow) {
      if (isRef(rawValue)) {
          return rawValue;
      }
      return new RefImpl(rawValue, shallow);
  }
  class RefImpl {
      constructor(value, __v_isShallow) {
          this.__v_isShallow = __v_isShallow;
          this.dep = undefined;
          this.__v_isRef = true;
          this._rawValue = __v_isShallow ? value : toRaw(value);
          this._value = __v_isShallow ? value : toReactive(value);
      }
      get value() {
          trackRefValue(this);
          return this._value;
      }
      set value(newVal) {
          newVal = this.__v_isShallow ? newVal : toRaw(newVal);
          if (hasChanged(newVal, this._rawValue)) {
              this._rawValue = newVal;
              this._value = this.__v_isShallow ? newVal : toReactive(newVal);
              triggerRefValue(this, newVal);
          }
      }
  }
  function triggerRef(ref) {
      triggerRefValue(ref, ref.value );
  }
  function unref(ref) {
      return isRef(ref) ? ref.value : ref;
  }
  const shallowUnwrapHandlers = {
      get: (target, key, receiver) => unref(Reflect.get(target, key, receiver)),
      set: (target, key, value, receiver) => {
          const oldValue = target[key];
          if (isRef(oldValue) && !isRef(value)) {
              oldValue.value = value;
              return true;
          }
          else {
              return Reflect.set(target, key, value, receiver);
          }
      }
  };
  function proxyRefs(objectWithRefs) {
      return isReactive(objectWithRefs)
          ? objectWithRefs
          : new Proxy(objectWithRefs, shallowUnwrapHandlers);
  }
  class CustomRefImpl {
      constructor(factory) {
          this.dep = undefined;
          this.__v_isRef = true;
          const { get, set } = factory(() => trackRefValue(this), () => triggerRefValue(this));
          this._get = get;
          this._set = set;
      }
      get value() {
          return this._get();
      }
      set value(newVal) {
          this._set(newVal);
      }
  }
  function customRef(factory) {
      return new CustomRefImpl(factory);
  }
  function toRefs(object) {
      if (!isProxy(object)) {
          console.warn(`toRefs() expects a reactive object but received a plain one.`);
      }
      const ret = isArray(object) ? new Array(object.length) : {};
      for (const key in object) {
          ret[key] = toRef(object, key);
      }
      return ret;
  }
  class ObjectRefImpl {
      constructor(_object, _key, _defaultValue) {
          this._object = _object;
          this._key = _key;
          this._defaultValue = _defaultValue;
          this.__v_isRef = true;
      }
      get value() {
          const val = this._object[this._key];
          return val === undefined ? this._defaultValue : val;
      }
      set value(newVal) {
          this._object[this._key] = newVal;
      }
  }
  function toRef(object, key, defaultValue) {
      const val = object[key];
      return isRef(val)
          ? val
          : new ObjectRefImpl(object, key, defaultValue);
  }

  class ComputedRefImpl {
      constructor(getter, _setter, isReadonly, isSSR) {
          this._setter = _setter;
          this.dep = undefined;
          this.__v_isRef = true;
          this._dirty = true;
          this.effect = new ReactiveEffect(getter, () => {
              if (!this._dirty) {
                  this._dirty = true;
                  triggerRefValue(this);
              }
          });
          this.effect.computed = this;
          this.effect.active = this._cacheable = !isSSR;
          this["__v_isReadonly" /* IS_READONLY */] = isReadonly;
      }
      get value() {
          // the computed ref may get wrapped by other proxies e.g. readonly() #3376
          const self = toRaw(this);
          trackRefValue(self);
          if (self._dirty || !self._cacheable) {
              self._dirty = false;
              self._value = self.effect.run();
          }
          return self._value;
      }
      set value(newValue) {
          this._setter(newValue);
      }
  }
  function computed(getterOrOptions, debugOptions, isSSR = false) {
      let getter;
      let setter;
      const onlyGetter = isFunction(getterOrOptions);
      if (onlyGetter) {
          getter = getterOrOptions;
          setter = () => {
                  console.warn('Write operation failed: computed value is readonly');
              }
              ;
      }
      else {
          getter = getterOrOptions.get;
          setter = getterOrOptions.set;
      }
      const cRef = new ComputedRefImpl(getter, setter, onlyGetter || !setter, isSSR);
      if (debugOptions && !isSSR) {
          cRef.effect.onTrack = debugOptions.onTrack;
          cRef.effect.onTrigger = debugOptions.onTrigger;
      }
      return cRef;
  }

  const stack = [];
  function pushWarningContext(vnode) {
      stack.push(vnode);
  }
  function popWarningContext() {
      stack.pop();
  }
  function warn$1(msg, ...args) {
      // avoid props formatting or warn handler tracking deps that might be mutated
      // during patch, leading to infinite recursion.
      pauseTracking();
      const instance = stack.length ? stack[stack.length - 1].component : null;
      const appWarnHandler = instance && instance.appContext.config.warnHandler;
      const trace = getComponentTrace();
      if (appWarnHandler) {
          callWithErrorHandling(appWarnHandler, instance, 11 /* APP_WARN_HANDLER */, [
              msg + args.join(''),
              instance && instance.proxy,
              trace
                  .map(({ vnode }) => `at <${formatComponentName(instance, vnode.type)}>`)
                  .join('\n'),
              trace
          ]);
      }
      else {
          const warnArgs = [`[Vue warn]: ${msg}`, ...args];
          /* istanbul ignore if */
          if (trace.length &&
              // avoid spamming console during tests
              !false) {
              warnArgs.push(`\n`, ...formatTrace(trace));
          }
          console.warn(...warnArgs);
      }
      resetTracking();
  }
  function getComponentTrace() {
      let currentVNode = stack[stack.length - 1];
      if (!currentVNode) {
          return [];
      }
      // we can't just use the stack because it will be incomplete during updates
      // that did not start from the root. Re-construct the parent chain using
      // instance parent pointers.
      const normalizedStack = [];
      while (currentVNode) {
          const last = normalizedStack[0];
          if (last && last.vnode === currentVNode) {
              last.recurseCount++;
          }
          else {
              normalizedStack.push({
                  vnode: currentVNode,
                  recurseCount: 0
              });
          }
          const parentInstance = currentVNode.component && currentVNode.component.parent;
          currentVNode = parentInstance && parentInstance.vnode;
      }
      return normalizedStack;
  }
  /* istanbul ignore next */
  function formatTrace(trace) {
      const logs = [];
      trace.forEach((entry, i) => {
          logs.push(...(i === 0 ? [] : [`\n`]), ...formatTraceEntry(entry));
      });
      return logs;
  }
  function formatTraceEntry({ vnode, recurseCount }) {
      const postfix = recurseCount > 0 ? `... (${recurseCount} recursive calls)` : ``;
      const isRoot = vnode.component ? vnode.component.parent == null : false;
      const open = ` at <${formatComponentName(vnode.component, vnode.type, isRoot)}`;
      const close = `>` + postfix;
      return vnode.props
          ? [open, ...formatProps(vnode.props), close]
          : [open + close];
  }
  /* istanbul ignore next */
  function formatProps(props) {
      const res = [];
      const keys = Object.keys(props);
      keys.slice(0, 3).forEach(key => {
          res.push(...formatProp(key, props[key]));
      });
      if (keys.length > 3) {
          res.push(` ...`);
      }
      return res;
  }
  /* istanbul ignore next */
  function formatProp(key, value, raw) {
      if (isString(value)) {
          value = JSON.stringify(value);
          return raw ? value : [`${key}=${value}`];
      }
      else if (typeof value === 'number' ||
          typeof value === 'boolean' ||
          value == null) {
          return raw ? value : [`${key}=${value}`];
      }
      else if (isRef(value)) {
          value = formatProp(key, toRaw(value.value), true);
          return raw ? value : [`${key}=Ref<`, value, `>`];
      }
      else if (isFunction(value)) {
          return [`${key}=fn${value.name ? `<${value.name}>` : ``}`];
      }
      else {
          value = toRaw(value);
          return raw ? value : [`${key}=`, value];
      }
  }

  const ErrorTypeStrings = {
      ["sp" /* SERVER_PREFETCH */]: 'serverPrefetch hook',
      ["bc" /* BEFORE_CREATE */]: 'beforeCreate hook',
      ["c" /* CREATED */]: 'created hook',
      ["bm" /* BEFORE_MOUNT */]: 'beforeMount hook',
      ["m" /* MOUNTED */]: 'mounted hook',
      ["bu" /* BEFORE_UPDATE */]: 'beforeUpdate hook',
      ["u" /* UPDATED */]: 'updated',
      ["bum" /* BEFORE_UNMOUNT */]: 'beforeUnmount hook',
      ["um" /* UNMOUNTED */]: 'unmounted hook',
      ["a" /* ACTIVATED */]: 'activated hook',
      ["da" /* DEACTIVATED */]: 'deactivated hook',
      ["ec" /* ERROR_CAPTURED */]: 'errorCaptured hook',
      ["rtc" /* RENDER_TRACKED */]: 'renderTracked hook',
      ["rtg" /* RENDER_TRIGGERED */]: 'renderTriggered hook',
      [0 /* SETUP_FUNCTION */]: 'setup function',
      [1 /* RENDER_FUNCTION */]: 'render function',
      [2 /* WATCH_GETTER */]: 'watcher getter',
      [3 /* WATCH_CALLBACK */]: 'watcher callback',
      [4 /* WATCH_CLEANUP */]: 'watcher cleanup function',
      [5 /* NATIVE_EVENT_HANDLER */]: 'native event handler',
      [6 /* COMPONENT_EVENT_HANDLER */]: 'component event handler',
      [7 /* VNODE_HOOK */]: 'vnode hook',
      [8 /* DIRECTIVE_HOOK */]: 'directive hook',
      [9 /* TRANSITION_HOOK */]: 'transition hook',
      [10 /* APP_ERROR_HANDLER */]: 'app errorHandler',
      [11 /* APP_WARN_HANDLER */]: 'app warnHandler',
      [12 /* FUNCTION_REF */]: 'ref function',
      [13 /* ASYNC_COMPONENT_LOADER */]: 'async component loader',
      [14 /* SCHEDULER */]: 'scheduler flush. This is likely a Vue internals bug. ' +
          'Please open an issue at https://new-issue.vuejs.org/?repo=vuejs/core'
  };
  function callWithErrorHandling(fn, instance, type, args) {
      let res;
      try {
          res = args ? fn(...args) : fn();
      }
      catch (err) {
          handleError(err, instance, type);
      }
      return res;
  }
  function callWithAsyncErrorHandling(fn, instance, type, args) {
      if (isFunction(fn)) {
          const res = callWithErrorHandling(fn, instance, type, args);
          if (res && isPromise(res)) {
              res.catch(err => {
                  handleError(err, instance, type);
              });
          }
          return res;
      }
      const values = [];
      for (let i = 0; i < fn.length; i++) {
          values.push(callWithAsyncErrorHandling(fn[i], instance, type, args));
      }
      return values;
  }
  function handleError(err, instance, type, throwInDev = true) {
      const contextVNode = instance ? instance.vnode : null;
      if (instance) {
          let cur = instance.parent;
          // the exposed instance is the render proxy to keep it consistent with 2.x
          const exposedInstance = instance.proxy;
          // in production the hook receives only the error code
          const errorInfo = ErrorTypeStrings[type] ;
          while (cur) {
              const errorCapturedHooks = cur.ec;
              if (errorCapturedHooks) {
                  for (let i = 0; i < errorCapturedHooks.length; i++) {
                      if (errorCapturedHooks[i](err, exposedInstance, errorInfo) === false) {
                          return;
                      }
                  }
              }
              cur = cur.parent;
          }
          // app-level handling
          const appErrorHandler = instance.appContext.config.errorHandler;
          if (appErrorHandler) {
              callWithErrorHandling(appErrorHandler, null, 10 /* APP_ERROR_HANDLER */, [err, exposedInstance, errorInfo]);
              return;
          }
      }
      logError(err, type, contextVNode, throwInDev);
  }
  function logError(err, type, contextVNode, throwInDev = true) {
      {
          const info = ErrorTypeStrings[type];
          if (contextVNode) {
              pushWarningContext(contextVNode);
          }
          warn$1(`Unhandled error${info ? ` during execution of ${info}` : ``}`);
          if (contextVNode) {
              popWarningContext();
          }
          // crash in dev by default so it's more noticeable
          if (throwInDev) {
              throw err;
          }
          else {
              console.error(err);
          }
      }
  }

  let isFlushing = false;
  let isFlushPending = false;
  const queue = [];
  let flushIndex = 0;
  const pendingPreFlushCbs = [];
  let activePreFlushCbs = null;
  let preFlushIndex = 0;
  const pendingPostFlushCbs = [];
  let activePostFlushCbs = null;
  let postFlushIndex = 0;
  const resolvedPromise = /*#__PURE__*/ Promise.resolve();
  let currentFlushPromise = null;
  let currentPreFlushParentJob = null;
  const RECURSION_LIMIT = 100;
  function nextTick(fn) {
      const p = currentFlushPromise || resolvedPromise;
      return fn ? p.then(this ? fn.bind(this) : fn) : p;
  }
  // #2768
  // Use binary-search to find a suitable position in the queue,
  // so that the queue maintains the increasing order of job's id,
  // which can prevent the job from being skipped and also can avoid repeated patching.
  function findInsertionIndex(id) {
      // the start index should be `flushIndex + 1`
      let start = flushIndex + 1;
      let end = queue.length;
      while (start < end) {
          const middle = (start + end) >>> 1;
          const middleJobId = getId(queue[middle]);
          middleJobId < id ? (start = middle + 1) : (end = middle);
      }
      return start;
  }
  function queueJob(job) {
      // the dedupe search uses the startIndex argument of Array.includes()
      // by default the search index includes the current job that is being run
      // so it cannot recursively trigger itself again.
      // if the job is a watch() callback, the search will start with a +1 index to
      // allow it recursively trigger itself - it is the user's responsibility to
      // ensure it doesn't end up in an infinite loop.
      if ((!queue.length ||
          !queue.includes(job, isFlushing && job.allowRecurse ? flushIndex + 1 : flushIndex)) &&
          job !== currentPreFlushParentJob) {
          if (job.id == null) {
              queue.push(job);
          }
          else {
              queue.splice(findInsertionIndex(job.id), 0, job);
          }
          queueFlush();
      }
  }
  function queueFlush() {
      if (!isFlushing && !isFlushPending) {
          isFlushPending = true;
          currentFlushPromise = resolvedPromise.then(flushJobs);
      }
  }
  function invalidateJob(job) {
      const i = queue.indexOf(job);
      if (i > flushIndex) {
          queue.splice(i, 1);
      }
  }
  function queueCb(cb, activeQueue, pendingQueue, index) {
      if (!isArray(cb)) {
          if (!activeQueue ||
              !activeQueue.includes(cb, cb.allowRecurse ? index + 1 : index)) {
              pendingQueue.push(cb);
          }
      }
      else {
          // if cb is an array, it is a component lifecycle hook which can only be
          // triggered by a job, which is already deduped in the main queue, so
          // we can skip duplicate check here to improve perf
          pendingQueue.push(...cb);
      }
      queueFlush();
  }
  function queuePreFlushCb(cb) {
      queueCb(cb, activePreFlushCbs, pendingPreFlushCbs, preFlushIndex);
  }
  function queuePostFlushCb(cb) {
      queueCb(cb, activePostFlushCbs, pendingPostFlushCbs, postFlushIndex);
  }
  function flushPreFlushCbs(seen, parentJob = null) {
      if (pendingPreFlushCbs.length) {
          currentPreFlushParentJob = parentJob;
          activePreFlushCbs = [...new Set(pendingPreFlushCbs)];
          pendingPreFlushCbs.length = 0;
          {
              seen = seen || new Map();
          }
          for (preFlushIndex = 0; preFlushIndex < activePreFlushCbs.length; preFlushIndex++) {
              if (checkRecursiveUpdates(seen, activePreFlushCbs[preFlushIndex])) {
                  continue;
              }
              activePreFlushCbs[preFlushIndex]();
          }
          activePreFlushCbs = null;
          preFlushIndex = 0;
          currentPreFlushParentJob = null;
          // recursively flush until it drains
          flushPreFlushCbs(seen, parentJob);
      }
  }
  function flushPostFlushCbs(seen) {
      // flush any pre cbs queued during the flush (e.g. pre watchers)
      flushPreFlushCbs();
      if (pendingPostFlushCbs.length) {
          const deduped = [...new Set(pendingPostFlushCbs)];
          pendingPostFlushCbs.length = 0;
          // #1947 already has active queue, nested flushPostFlushCbs call
          if (activePostFlushCbs) {
              activePostFlushCbs.push(...deduped);
              return;
          }
          activePostFlushCbs = deduped;
          {
              seen = seen || new Map();
          }
          activePostFlushCbs.sort((a, b) => getId(a) - getId(b));
          for (postFlushIndex = 0; postFlushIndex < activePostFlushCbs.length; postFlushIndex++) {
              if (checkRecursiveUpdates(seen, activePostFlushCbs[postFlushIndex])) {
                  continue;
              }
              activePostFlushCbs[postFlushIndex]();
          }
          activePostFlushCbs = null;
          postFlushIndex = 0;
      }
  }
  const getId = (job) => job.id == null ? Infinity : job.id;
  function flushJobs(seen) {
      isFlushPending = false;
      isFlushing = true;
      {
          seen = seen || new Map();
      }
      flushPreFlushCbs(seen);
      // Sort queue before flush.
      // This ensures that:
      // 1. Components are updated from parent to child. (because parent is always
      //    created before the child so its render effect will have smaller
      //    priority number)
      // 2. If a component is unmounted during a parent component's update,
      //    its update can be skipped.
      queue.sort((a, b) => getId(a) - getId(b));
      // conditional usage of checkRecursiveUpdate must be determined out of
      // try ... catch block since Rollup by default de-optimizes treeshaking
      // inside try-catch. This can leave all warning code unshaked. Although
      // they would get eventually shaken by a minifier like terser, some minifiers
      // would fail to do that (e.g. https://github.com/evanw/esbuild/issues/1610)
      const check = (job) => checkRecursiveUpdates(seen, job)
          ;
      try {
          for (flushIndex = 0; flushIndex < queue.length; flushIndex++) {
              const job = queue[flushIndex];
              if (job && job.active !== false) {
                  if (true && check(job)) {
                      continue;
                  }
                  // console.log(`running:`, job.id)
                  callWithErrorHandling(job, null, 14 /* SCHEDULER */);
              }
          }
      }
      finally {
          flushIndex = 0;
          queue.length = 0;
          flushPostFlushCbs(seen);
          isFlushing = false;
          currentFlushPromise = null;
          // some postFlushCb queued jobs!
          // keep flushing until it drains.
          if (queue.length ||
              pendingPreFlushCbs.length ||
              pendingPostFlushCbs.length) {
              flushJobs(seen);
          }
      }
  }
  function checkRecursiveUpdates(seen, fn) {
      if (!seen.has(fn)) {
          seen.set(fn, 1);
      }
      else {
          const count = seen.get(fn);
          if (count > RECURSION_LIMIT) {
              const instance = fn.ownerInstance;
              const componentName = instance && getComponentName(instance.type);
              warn$1(`Maximum recursive updates exceeded${componentName ? ` in component <${componentName}>` : ``}. ` +
                  `This means you have a reactive effect that is mutating its own ` +
                  `dependencies and thus recursively triggering itself. Possible sources ` +
                  `include component template, render function, updated hook or ` +
                  `watcher source function.`);
              return true;
          }
          else {
              seen.set(fn, count + 1);
          }
      }
  }

  /* eslint-disable no-restricted-globals */
  let isHmrUpdating = false;
  const hmrDirtyComponents = new Set();
  // Expose the HMR runtime on the global object
  // This makes it entirely tree-shakable without polluting the exports and makes
  // it easier to be used in toolings like vue-loader
  // Note: for a component to be eligible for HMR it also needs the __hmrId option
  // to be set so that its instances can be registered / removed.
  {
      getGlobalThis().__VUE_HMR_RUNTIME__ = {
          createRecord: tryWrap(createRecord),
          rerender: tryWrap(rerender),
          reload: tryWrap(reload)
      };
  }
  const map = new Map();
  function registerHMR(instance) {
      const id = instance.type.__hmrId;
      let record = map.get(id);
      if (!record) {
          createRecord(id, instance.type);
          record = map.get(id);
      }
      record.instances.add(instance);
  }
  function unregisterHMR(instance) {
      map.get(instance.type.__hmrId).instances.delete(instance);
  }
  function createRecord(id, initialDef) {
      if (map.has(id)) {
          return false;
      }
      map.set(id, {
          initialDef: normalizeClassComponent(initialDef),
          instances: new Set()
      });
      return true;
  }
  function normalizeClassComponent(component) {
      return isClassComponent(component) ? component.__vccOpts : component;
  }
  function rerender(id, newRender) {
      const record = map.get(id);
      if (!record) {
          return;
      }
      // update initial record (for not-yet-rendered component)
      record.initialDef.render = newRender;
      [...record.instances].forEach(instance => {
          if (newRender) {
              instance.render = newRender;
              normalizeClassComponent(instance.type).render = newRender;
          }
          instance.renderCache = [];
          // this flag forces child components with slot content to update
          isHmrUpdating = true;
          instance.update();
          isHmrUpdating = false;
      });
  }
  function reload(id, newComp) {
      const record = map.get(id);
      if (!record)
          return;
      newComp = normalizeClassComponent(newComp);
      // update initial def (for not-yet-rendered components)
      updateComponentDef(record.initialDef, newComp);
      // create a snapshot which avoids the set being mutated during updates
      const instances = [...record.instances];
      for (const instance of instances) {
          const oldComp = normalizeClassComponent(instance.type);
          if (!hmrDirtyComponents.has(oldComp)) {
              // 1. Update existing comp definition to match new one
              if (oldComp !== record.initialDef) {
                  updateComponentDef(oldComp, newComp);
              }
              // 2. mark definition dirty. This forces the renderer to replace the
              // component on patch.
              hmrDirtyComponents.add(oldComp);
          }
          // 3. invalidate options resolution cache
          instance.appContext.optionsCache.delete(instance.type);
          // 4. actually update
          if (instance.ceReload) {
              // custom element
              hmrDirtyComponents.add(oldComp);
              instance.ceReload(newComp.styles);
              hmrDirtyComponents.delete(oldComp);
          }
          else if (instance.parent) {
              // 4. Force the parent instance to re-render. This will cause all updated
              // components to be unmounted and re-mounted. Queue the update so that we
              // don't end up forcing the same parent to re-render multiple times.
              queueJob(instance.parent.update);
              // instance is the inner component of an async custom element
              // invoke to reset styles
              if (instance.parent.type.__asyncLoader &&
                  instance.parent.ceReload) {
                  instance.parent.ceReload(newComp.styles);
              }
          }
          else if (instance.appContext.reload) {
              // root instance mounted via createApp() has a reload method
              instance.appContext.reload();
          }
          else if (typeof window !== 'undefined') {
              // root instance inside tree created via raw render(). Force reload.
              window.location.reload();
          }
          else {
              console.warn('[HMR] Root or manually mounted instance modified. Full reload required.');
          }
      }
      // 5. make sure to cleanup dirty hmr components after update
      queuePostFlushCb(() => {
          for (const instance of instances) {
              hmrDirtyComponents.delete(normalizeClassComponent(instance.type));
          }
      });
  }
  function updateComponentDef(oldComp, newComp) {
      extend(oldComp, newComp);
      for (const key in oldComp) {
          if (key !== '__file' && !(key in newComp)) {
              delete oldComp[key];
          }
      }
  }
  function tryWrap(fn) {
      return (id, arg) => {
          try {
              return fn(id, arg);
          }
          catch (e) {
              console.error(e);
              console.warn(`[HMR] Something went wrong during Vue component hot-reload. ` +
                  `Full reload required.`);
          }
      };
  }

  let devtools;
  let buffer = [];
  let devtoolsNotInstalled = false;
  function emit(event, ...args) {
      if (devtools) {
          devtools.emit(event, ...args);
      }
      else if (!devtoolsNotInstalled) {
          buffer.push({ event, args });
      }
  }
  function setDevtoolsHook(hook, target) {
      var _a, _b;
      devtools = hook;
      if (devtools) {
          devtools.enabled = true;
          buffer.forEach(({ event, args }) => devtools.emit(event, ...args));
          buffer = [];
      }
      else if (
      // handle late devtools injection - only do this if we are in an actual
      // browser environment to avoid the timer handle stalling test runner exit
      // (#4815)
      typeof window !== 'undefined' &&
          // some envs mock window but not fully
          window.HTMLElement &&
          // also exclude jsdom
          !((_b = (_a = window.navigator) === null || _a === void 0 ? void 0 : _a.userAgent) === null || _b === void 0 ? void 0 : _b.includes('jsdom'))) {
          const replay = (target.__VUE_DEVTOOLS_HOOK_REPLAY__ =
              target.__VUE_DEVTOOLS_HOOK_REPLAY__ || []);
          replay.push((newHook) => {
              setDevtoolsHook(newHook, target);
          });
          // clear buffer after 3s - the user probably doesn't have devtools installed
          // at all, and keeping the buffer will cause memory leaks (#4738)
          setTimeout(() => {
              if (!devtools) {
                  target.__VUE_DEVTOOLS_HOOK_REPLAY__ = null;
                  devtoolsNotInstalled = true;
                  buffer = [];
              }
          }, 3000);
      }
      else {
          // non-browser env, assume not installed
          devtoolsNotInstalled = true;
          buffer = [];
      }
  }
  function devtoolsInitApp(app, version) {
      emit("app:init" /* APP_INIT */, app, version, {
          Fragment,
          Text,
          Comment,
          Static
      });
  }
  function devtoolsUnmountApp(app) {
      emit("app:unmount" /* APP_UNMOUNT */, app);
  }
  const devtoolsComponentAdded = /*#__PURE__*/ createDevtoolsComponentHook("component:added" /* COMPONENT_ADDED */);
  const devtoolsComponentUpdated = 
  /*#__PURE__*/ createDevtoolsComponentHook("component:updated" /* COMPONENT_UPDATED */);
  const devtoolsComponentRemoved = 
  /*#__PURE__*/ createDevtoolsComponentHook("component:removed" /* COMPONENT_REMOVED */);
  function createDevtoolsComponentHook(hook) {
      return (component) => {
          emit(hook, component.appContext.app, component.uid, component.parent ? component.parent.uid : undefined, component);
      };
  }
  const devtoolsPerfStart = /*#__PURE__*/ createDevtoolsPerformanceHook("perf:start" /* PERFORMANCE_START */);
  const devtoolsPerfEnd = /*#__PURE__*/ createDevtoolsPerformanceHook("perf:end" /* PERFORMANCE_END */);
  function createDevtoolsPerformanceHook(hook) {
      return (component, type, time) => {
          emit(hook, component.appContext.app, component.uid, component, type, time);
      };
  }
  function devtoolsComponentEmit(component, event, params) {
      emit("component:emit" /* COMPONENT_EMIT */, component.appContext.app, component, event, params);
  }

  const deprecationData = {
      ["GLOBAL_MOUNT" /* GLOBAL_MOUNT */]: {
          message: `The global app bootstrapping API has changed: vm.$mount() and the "el" ` +
              `option have been removed. Use createApp(RootComponent).mount() instead.`,
          link: `https://v3-migration.vuejs.org/breaking-changes/global-api.html#mounting-app-instance`
      },
      ["GLOBAL_MOUNT_CONTAINER" /* GLOBAL_MOUNT_CONTAINER */]: {
          message: `Vue detected directives on the mount container. ` +
              `In Vue 3, the container is no longer considered part of the template ` +
              `and will not be processed/replaced.`,
          link: `https://v3-migration.vuejs.org/breaking-changes/mount-changes.html`
      },
      ["GLOBAL_EXTEND" /* GLOBAL_EXTEND */]: {
          message: `Vue.extend() has been removed in Vue 3. ` +
              `Use defineComponent() instead.`,
          link: `https://vuejs.org/api/general.html#definecomponent`
      },
      ["GLOBAL_PROTOTYPE" /* GLOBAL_PROTOTYPE */]: {
          message: `Vue.prototype is no longer available in Vue 3. ` +
              `Use app.config.globalProperties instead.`,
          link: `https://v3-migration.vuejs.org/breaking-changes/global-api.html#vue-prototype-replaced-by-config-globalproperties`
      },
      ["GLOBAL_SET" /* GLOBAL_SET */]: {
          message: `Vue.set() has been removed as it is no longer needed in Vue 3. ` +
              `Simply use native JavaScript mutations.`
      },
      ["GLOBAL_DELETE" /* GLOBAL_DELETE */]: {
          message: `Vue.delete() has been removed as it is no longer needed in Vue 3. ` +
              `Simply use native JavaScript mutations.`
      },
      ["GLOBAL_OBSERVABLE" /* GLOBAL_OBSERVABLE */]: {
          message: `Vue.observable() has been removed. ` +
              `Use \`import { reactive } from "vue"\` from Composition API instead.`,
          link: `https://vuejs.org/api/reactivity-core.html#reactive`
      },
      ["GLOBAL_PRIVATE_UTIL" /* GLOBAL_PRIVATE_UTIL */]: {
          message: `Vue.util has been removed. Please refactor to avoid its usage ` +
              `since it was an internal API even in Vue 2.`
      },
      ["CONFIG_SILENT" /* CONFIG_SILENT */]: {
          message: `config.silent has been removed because it is not good practice to ` +
              `intentionally suppress warnings. You can use your browser console's ` +
              `filter features to focus on relevant messages.`
      },
      ["CONFIG_DEVTOOLS" /* CONFIG_DEVTOOLS */]: {
          message: `config.devtools has been removed. To enable devtools for ` +
              `production, configure the __VUE_PROD_DEVTOOLS__ compile-time flag.`,
          link: `https://github.com/vuejs/core/tree/main/packages/vue#bundler-build-feature-flags`
      },
      ["CONFIG_KEY_CODES" /* CONFIG_KEY_CODES */]: {
          message: `config.keyCodes has been removed. ` +
              `In Vue 3, you can directly use the kebab-case key names as v-on modifiers.`,
          link: `https://v3-migration.vuejs.org/breaking-changes/keycode-modifiers.html`
      },
      ["CONFIG_PRODUCTION_TIP" /* CONFIG_PRODUCTION_TIP */]: {
          message: `config.productionTip has been removed.`,
          link: `https://v3-migration.vuejs.org/breaking-changes/global-api.html#config-productiontip-removed`
      },
      ["CONFIG_IGNORED_ELEMENTS" /* CONFIG_IGNORED_ELEMENTS */]: {
          message: () => {
              let msg = `config.ignoredElements has been removed.`;
              if (isRuntimeOnly()) {
                  msg += ` Pass the "isCustomElement" option to @vue/compiler-dom instead.`;
              }
              else {
                  msg += ` Use config.isCustomElement instead.`;
              }
              return msg;
          },
          link: `https://v3-migration.vuejs.org/breaking-changes/global-api.html#config-ignoredelements-is-now-config-iscustomelement`
      },
      ["CONFIG_WHITESPACE" /* CONFIG_WHITESPACE */]: {
          // this warning is only relevant in the full build when using runtime
          // compilation, so it's put in the runtime compatConfig list.
          message: `Vue 3 compiler's whitespace option will default to "condense" instead of ` +
              `"preserve". To suppress this warning, provide an explicit value for ` +
              `\`config.compilerOptions.whitespace\`.`
      },
      ["CONFIG_OPTION_MERGE_STRATS" /* CONFIG_OPTION_MERGE_STRATS */]: {
          message: `config.optionMergeStrategies no longer exposes internal strategies. ` +
              `Use custom merge functions instead.`
      },
      ["INSTANCE_SET" /* INSTANCE_SET */]: {
          message: `vm.$set() has been removed as it is no longer needed in Vue 3. ` +
              `Simply use native JavaScript mutations.`
      },
      ["INSTANCE_DELETE" /* INSTANCE_DELETE */]: {
          message: `vm.$delete() has been removed as it is no longer needed in Vue 3. ` +
              `Simply use native JavaScript mutations.`
      },
      ["INSTANCE_DESTROY" /* INSTANCE_DESTROY */]: {
          message: `vm.$destroy() has been removed. Use app.unmount() instead.`,
          link: `https://vuejs.org/api/application.html#app-unmount`
      },
      ["INSTANCE_EVENT_EMITTER" /* INSTANCE_EVENT_EMITTER */]: {
          message: `vm.$on/$once/$off() have been removed. ` +
              `Use an external event emitter library instead.`,
          link: `https://v3-migration.vuejs.org/breaking-changes/events-api.html`
      },
      ["INSTANCE_EVENT_HOOKS" /* INSTANCE_EVENT_HOOKS */]: {
          message: event => `"${event}" lifecycle events are no longer supported. From templates, ` +
              `use the "vnode" prefix instead of "hook:". For example, @${event} ` +
              `should be changed to @vnode-${event.slice(5)}. ` +
              `From JavaScript, use Composition API to dynamically register lifecycle ` +
              `hooks.`,
          link: `https://v3-migration.vuejs.org/breaking-changes/vnode-lifecycle-events.html`
      },
      ["INSTANCE_CHILDREN" /* INSTANCE_CHILDREN */]: {
          message: `vm.$children has been removed. Consider refactoring your logic ` +
              `to avoid relying on direct access to child components.`,
          link: `https://v3-migration.vuejs.org/breaking-changes/children.html`
      },
      ["INSTANCE_LISTENERS" /* INSTANCE_LISTENERS */]: {
          message: `vm.$listeners has been removed. In Vue 3, parent v-on listeners are ` +
              `included in vm.$attrs and it is no longer necessary to separately use ` +
              `v-on="$listeners" if you are already using v-bind="$attrs". ` +
              `(Note: the Vue 3 behavior only applies if this compat config is disabled)`,
          link: `https://v3-migration.vuejs.org/breaking-changes/listeners-removed.html`
      },
      ["INSTANCE_SCOPED_SLOTS" /* INSTANCE_SCOPED_SLOTS */]: {
          message: `vm.$scopedSlots has been removed. Use vm.$slots instead.`,
          link: `https://v3-migration.vuejs.org/breaking-changes/slots-unification.html`
      },
      ["INSTANCE_ATTRS_CLASS_STYLE" /* INSTANCE_ATTRS_CLASS_STYLE */]: {
          message: componentName => `Component <${componentName || 'Anonymous'}> has \`inheritAttrs: false\` but is ` +
              `relying on class/style fallthrough from parent. In Vue 3, class/style ` +
              `are now included in $attrs and will no longer fallthrough when ` +
              `inheritAttrs is false. If you are already using v-bind="$attrs" on ` +
              `component root it should render the same end result. ` +
              `If you are binding $attrs to a non-root element and expecting ` +
              `class/style to fallthrough on root, you will need to now manually bind ` +
              `them on root via :class="$attrs.class".`,
          link: `https://v3-migration.vuejs.org/breaking-changes/attrs-includes-class-style.html`
      },
      ["OPTIONS_DATA_FN" /* OPTIONS_DATA_FN */]: {
          message: `The "data" option can no longer be a plain object. ` +
              `Always use a function.`,
          link: `https://v3-migration.vuejs.org/breaking-changes/data-option.html`
      },
      ["OPTIONS_DATA_MERGE" /* OPTIONS_DATA_MERGE */]: {
          message: (key) => `Detected conflicting key "${key}" when merging data option values. ` +
              `In Vue 3, data keys are merged shallowly and will override one another.`,
          link: `https://v3-migration.vuejs.org/breaking-changes/data-option.html#mixin-merge-behavior-change`
      },
      ["OPTIONS_BEFORE_DESTROY" /* OPTIONS_BEFORE_DESTROY */]: {
          message: `\`beforeDestroy\` has been renamed to \`beforeUnmount\`.`
      },
      ["OPTIONS_DESTROYED" /* OPTIONS_DESTROYED */]: {
          message: `\`destroyed\` has been renamed to \`unmounted\`.`
      },
      ["WATCH_ARRAY" /* WATCH_ARRAY */]: {
          message: `"watch" option or vm.$watch on an array value will no longer ` +
              `trigger on array mutation unless the "deep" option is specified. ` +
              `If current usage is intended, you can disable the compat behavior and ` +
              `suppress this warning with:` +
              `\n\n  configureCompat({ ${"WATCH_ARRAY" /* WATCH_ARRAY */}: false })\n`,
          link: `https://v3-migration.vuejs.org/breaking-changes/watch.html`
      },
      ["PROPS_DEFAULT_THIS" /* PROPS_DEFAULT_THIS */]: {
          message: (key) => `props default value function no longer has access to "this". The compat ` +
              `build only offers access to this.$options.` +
              `(found in prop "${key}")`,
          link: `https://v3-migration.vuejs.org/breaking-changes/props-default-this.html`
      },
      ["CUSTOM_DIR" /* CUSTOM_DIR */]: {
          message: (legacyHook, newHook) => `Custom directive hook "${legacyHook}" has been removed. ` +
              `Use "${newHook}" instead.`,
          link: `https://v3-migration.vuejs.org/breaking-changes/custom-directives.html`
      },
      ["V_ON_KEYCODE_MODIFIER" /* V_ON_KEYCODE_MODIFIER */]: {
          message: `Using keyCode as v-on modifier is no longer supported. ` +
              `Use kebab-case key name modifiers instead.`,
          link: `https://v3-migration.vuejs.org/breaking-changes/keycode-modifiers.html`
      },
      ["ATTR_FALSE_VALUE" /* ATTR_FALSE_VALUE */]: {
          message: (name) => `Attribute "${name}" with v-bind value \`false\` will render ` +
              `${name}="false" instead of removing it in Vue 3. To remove the attribute, ` +
              `use \`null\` or \`undefined\` instead. If the usage is intended, ` +
              `you can disable the compat behavior and suppress this warning with:` +
              `\n\n  configureCompat({ ${"ATTR_FALSE_VALUE" /* ATTR_FALSE_VALUE */}: false })\n`,
          link: `https://v3-migration.vuejs.org/breaking-changes/attribute-coercion.html`
      },
      ["ATTR_ENUMERATED_COERCION" /* ATTR_ENUMERATED_COERCION */]: {
          message: (name, value, coerced) => `Enumerated attribute "${name}" with v-bind value \`${value}\` will ` +
              `${value === null ? `be removed` : `render the value as-is`} instead of coercing the value to "${coerced}" in Vue 3. ` +
              `Always use explicit "true" or "false" values for enumerated attributes. ` +
              `If the usage is intended, ` +
              `you can disable the compat behavior and suppress this warning with:` +
              `\n\n  configureCompat({ ${"ATTR_ENUMERATED_COERCION" /* ATTR_ENUMERATED_COERCION */}: false })\n`,
          link: `https://v3-migration.vuejs.org/breaking-changes/attribute-coercion.html`
      },
      ["TRANSITION_CLASSES" /* TRANSITION_CLASSES */]: {
          message: `` // this feature cannot be runtime-detected
      },
      ["TRANSITION_GROUP_ROOT" /* TRANSITION_GROUP_ROOT */]: {
          message: `<TransitionGroup> no longer renders a root <span> element by ` +
              `default if no "tag" prop is specified. If you do not rely on the span ` +
              `for styling, you can disable the compat behavior and suppress this ` +
              `warning with:` +
              `\n\n  configureCompat({ ${"TRANSITION_GROUP_ROOT" /* TRANSITION_GROUP_ROOT */}: false })\n`,
          link: `https://v3-migration.vuejs.org/breaking-changes/transition-group.html`
      },
      ["COMPONENT_ASYNC" /* COMPONENT_ASYNC */]: {
          message: (comp) => {
              const name = getComponentName(comp);
              return (`Async component${name ? ` <${name}>` : `s`} should be explicitly created via \`defineAsyncComponent()\` ` +
                  `in Vue 3. Plain functions will be treated as functional components in ` +
                  `non-compat build. If you have already migrated all async component ` +
                  `usage and intend to use plain functions for functional components, ` +
                  `you can disable the compat behavior and suppress this ` +
                  `warning with:` +
                  `\n\n  configureCompat({ ${"COMPONENT_ASYNC" /* COMPONENT_ASYNC */}: false })\n`);
          },
          link: `https://v3-migration.vuejs.org/breaking-changes/async-components.html`
      },
      ["COMPONENT_FUNCTIONAL" /* COMPONENT_FUNCTIONAL */]: {
          message: (comp) => {
              const name = getComponentName(comp);
              return (`Functional component${name ? ` <${name}>` : `s`} should be defined as a plain function in Vue 3. The "functional" ` +
                  `option has been removed. NOTE: Before migrating to use plain ` +
                  `functions for functional components, first make sure that all async ` +
                  `components usage have been migrated and its compat behavior has ` +
                  `been disabled.`);
          },
          link: `https://v3-migration.vuejs.org/breaking-changes/functional-components.html`
      },
      ["COMPONENT_V_MODEL" /* COMPONENT_V_MODEL */]: {
          message: (comp) => {
              const configMsg = `opt-in to ` +
                  `Vue 3 behavior on a per-component basis with \`compatConfig: { ${"COMPONENT_V_MODEL" /* COMPONENT_V_MODEL */}: false }\`.`;
              if (comp.props &&
                  (isArray(comp.props)
                      ? comp.props.includes('modelValue')
                      : hasOwn(comp.props, 'modelValue'))) {
                  return (`Component declares "modelValue" prop, which is Vue 3 usage, but ` +
                      `is running under Vue 2 compat v-model behavior. You can ${configMsg}`);
              }
              return (`v-model usage on component has changed in Vue 3. Component that expects ` +
                  `to work with v-model should now use the "modelValue" prop and emit the ` +
                  `"update:modelValue" event. You can update the usage and then ${configMsg}`);
          },
          link: `https://v3-migration.vuejs.org/breaking-changes/v-model.html`
      },
      ["RENDER_FUNCTION" /* RENDER_FUNCTION */]: {
          message: `Vue 3's render function API has changed. ` +
              `You can opt-in to the new API with:` +
              `\n\n  configureCompat({ ${"RENDER_FUNCTION" /* RENDER_FUNCTION */}: false })\n` +
              `\n  (This can also be done per-component via the "compatConfig" option.)`,
          link: `https://v3-migration.vuejs.org/breaking-changes/render-function-api.html`
      },
      ["FILTERS" /* FILTERS */]: {
          message: `filters have been removed in Vue 3. ` +
              `The "|" symbol will be treated as native JavaScript bitwise OR operator. ` +
              `Use method calls or computed properties instead.`,
          link: `https://v3-migration.vuejs.org/breaking-changes/filters.html`
      },
      ["PRIVATE_APIS" /* PRIVATE_APIS */]: {
          message: name => `"${name}" is a Vue 2 private API that no longer exists in Vue 3. ` +
              `If you are seeing this warning only due to a dependency, you can ` +
              `suppress this warning via { PRIVATE_APIS: 'suppress-warning' }.`
      }
  };
  const instanceWarned = Object.create(null);
  const warnCount = Object.create(null);
  function warnDeprecation(key, instance, ...args) {
      instance = instance || getCurrentInstance();
      // check user config
      const config = getCompatConfigForKey(key, instance);
      if (config === 'suppress-warning') {
          return;
      }
      const dupKey = key + args.join('');
      let compId = instance && formatComponentName(instance, instance.type);
      if (compId === 'Anonymous' && instance) {
          compId = instance.uid;
      }
      // skip if the same warning is emitted for the same component type
      const componentDupKey = dupKey + compId;
      if (componentDupKey in instanceWarned) {
          return;
      }
      instanceWarned[componentDupKey] = true;
      // same warning, but different component. skip the long message and just
      // log the key and count.
      if (dupKey in warnCount) {
          warn$1(`(deprecation ${key}) (${++warnCount[dupKey] + 1})`);
          return;
      }
      warnCount[dupKey] = 0;
      const { message, link } = deprecationData[key];
      warn$1(`(deprecation ${key}) ${typeof message === 'function' ? message(...args) : message}${link ? `\n  Details: ${link}` : ``}`);
      if (!isCompatEnabled(key, instance, true)) {
          console.error(`^ The above deprecation's compat behavior is disabled and will likely ` +
              `lead to runtime errors.`);
      }
  }
  const globalCompatConfig = {
      MODE: 2
  };
  function configureCompat(config) {
      {
          validateCompatConfig(config);
      }
      extend(globalCompatConfig, config);
  }
  const seenConfigObjects = /*#__PURE__*/ new WeakSet();
  const warnedInvalidKeys = {};
  // dev only
  function validateCompatConfig(config, instance) {
      if (seenConfigObjects.has(config)) {
          return;
      }
      seenConfigObjects.add(config);
      for (const key of Object.keys(config)) {
          if (key !== 'MODE' &&
              !(key in deprecationData) &&
              !(key in warnedInvalidKeys)) {
              if (key.startsWith('COMPILER_')) {
                  if (isRuntimeOnly()) {
                      warn$1(`Deprecation config "${key}" is compiler-specific and you are ` +
                          `running a runtime-only build of Vue. This deprecation should be ` +
                          `configured via compiler options in your build setup instead.\n` +
                          `Details: https://v3-migration.vuejs.org/breaking-changes/migration-build.html`);
                  }
              }
              else {
                  warn$1(`Invalid deprecation config "${key}".`);
              }
              warnedInvalidKeys[key] = true;
          }
      }
      if (instance && config["OPTIONS_DATA_MERGE" /* OPTIONS_DATA_MERGE */] != null) {
          warn$1(`Deprecation config "${"OPTIONS_DATA_MERGE" /* OPTIONS_DATA_MERGE */}" can only be configured globally.`);
      }
  }
  function getCompatConfigForKey(key, instance) {
      const instanceConfig = instance && instance.type.compatConfig;
      if (instanceConfig && key in instanceConfig) {
          return instanceConfig[key];
      }
      return globalCompatConfig[key];
  }
  function isCompatEnabled(key, instance, enableForBuiltIn = false) {
      // skip compat for built-in components
      if (!enableForBuiltIn && instance && instance.type.__isBuiltIn) {
          return false;
      }
      const rawMode = getCompatConfigForKey('MODE', instance) || 2;
      const val = getCompatConfigForKey(key, instance);
      const mode = isFunction(rawMode)
          ? rawMode(instance && instance.type)
          : rawMode;
      if (mode === 2) {
          return val !== false;
      }
      else {
          return val === true || val === 'suppress-warning';
      }
  }
  /**
   * Use this for features that are completely removed in non-compat build.
   */
  function assertCompatEnabled(key, instance, ...args) {
      if (!isCompatEnabled(key, instance)) {
          throw new Error(`${key} compat has been disabled.`);
      }
      else {
          warnDeprecation(key, instance, ...args);
      }
  }
  /**
   * Use this for features where legacy usage is still possible, but will likely
   * lead to runtime error if compat is disabled. (warn in all cases)
   */
  function softAssertCompatEnabled(key, instance, ...args) {
      {
          warnDeprecation(key, instance, ...args);
      }
      return isCompatEnabled(key, instance);
  }
  /**
   * Use this for features with the same syntax but with mutually exclusive
   * behavior in 2 vs 3. Only warn if compat is enabled.
   * e.g. render function
   */
  function checkCompatEnabled(key, instance, ...args) {
      const enabled = isCompatEnabled(key, instance);
      if (enabled) {
          warnDeprecation(key, instance, ...args);
      }
      return enabled;
  }

  const eventRegistryMap = /*#__PURE__*/ new WeakMap();
  function getRegistry(instance) {
      let events = eventRegistryMap.get(instance);
      if (!events) {
          eventRegistryMap.set(instance, (events = Object.create(null)));
      }
      return events;
  }
  function on(instance, event, fn) {
      if (isArray(event)) {
          event.forEach(e => on(instance, e, fn));
      }
      else {
          if (event.startsWith('hook:')) {
              assertCompatEnabled("INSTANCE_EVENT_HOOKS" /* INSTANCE_EVENT_HOOKS */, instance, event);
          }
          else {
              assertCompatEnabled("INSTANCE_EVENT_EMITTER" /* INSTANCE_EVENT_EMITTER */, instance);
          }
          const events = getRegistry(instance);
          (events[event] || (events[event] = [])).push(fn);
      }
      return instance.proxy;
  }
  function once(instance, event, fn) {
      const wrapped = (...args) => {
          off(instance, event, wrapped);
          fn.call(instance.proxy, ...args);
      };
      wrapped.fn = fn;
      on(instance, event, wrapped);
      return instance.proxy;
  }
  function off(instance, event, fn) {
      assertCompatEnabled("INSTANCE_EVENT_EMITTER" /* INSTANCE_EVENT_EMITTER */, instance);
      const vm = instance.proxy;
      // all
      if (!event) {
          eventRegistryMap.set(instance, Object.create(null));
          return vm;
      }
      // array of events
      if (isArray(event)) {
          event.forEach(e => off(instance, e, fn));
          return vm;
      }
      // specific event
      const events = getRegistry(instance);
      const cbs = events[event];
      if (!cbs) {
          return vm;
      }
      if (!fn) {
          events[event] = undefined;
          return vm;
      }
      events[event] = cbs.filter(cb => !(cb === fn || cb.fn === fn));
      return vm;
  }
  function emit$1(instance, event, args) {
      const cbs = getRegistry(instance)[event];
      if (cbs) {
          callWithAsyncErrorHandling(cbs.map(cb => cb.bind(instance.proxy)), instance, 6 /* COMPONENT_EVENT_HANDLER */, args);
      }
      return instance.proxy;
  }

  const compatModelEventPrefix = `onModelCompat:`;
  const warnedTypes = new WeakSet();
  function convertLegacyVModelProps(vnode) {
      const { type, shapeFlag, props, dynamicProps } = vnode;
      const comp = type;
      if (shapeFlag & 6 /* COMPONENT */ && props && 'modelValue' in props) {
          if (!isCompatEnabled("COMPONENT_V_MODEL" /* COMPONENT_V_MODEL */, 
          // this is a special case where we want to use the vnode component's
          // compat config instead of the current rendering instance (which is the
          // parent of the component that exposes v-model)
          { type })) {
              return;
          }
          if (!warnedTypes.has(comp)) {
              pushWarningContext(vnode);
              warnDeprecation("COMPONENT_V_MODEL" /* COMPONENT_V_MODEL */, { type }, comp);
              popWarningContext();
              warnedTypes.add(comp);
          }
          // v3 compiled model code -> v2 compat props
          // modelValue -> value
          // onUpdate:modelValue -> onModelCompat:input
          const model = comp.model || {};
          applyModelFromMixins(model, comp.mixins);
          const { prop = 'value', event = 'input' } = model;
          if (prop !== 'modelValue') {
              props[prop] = props.modelValue;
              delete props.modelValue;
          }
          // important: update dynamic props
          if (dynamicProps) {
              dynamicProps[dynamicProps.indexOf('modelValue')] = prop;
          }
          props[compatModelEventPrefix + event] = props['onUpdate:modelValue'];
          delete props['onUpdate:modelValue'];
      }
  }
  function applyModelFromMixins(model, mixins) {
      if (mixins) {
          mixins.forEach(m => {
              if (m.model)
                  extend(model, m.model);
              if (m.mixins)
                  applyModelFromMixins(model, m.mixins);
          });
      }
  }
  function compatModelEmit(instance, event, args) {
      if (!isCompatEnabled("COMPONENT_V_MODEL" /* COMPONENT_V_MODEL */, instance)) {
          return;
      }
      const props = instance.vnode.props;
      const modelHandler = props && props[compatModelEventPrefix + event];
      if (modelHandler) {
          callWithErrorHandling(modelHandler, instance, 6 /* COMPONENT_EVENT_HANDLER */, args);
      }
  }

  function emit$2(instance, event, ...rawArgs) {
      if (instance.isUnmounted)
          return;
      const props = instance.vnode.props || EMPTY_OBJ;
      {
          const { emitsOptions, propsOptions: [propsOptions] } = instance;
          if (emitsOptions) {
              if (!(event in emitsOptions) &&
                  !((event.startsWith('hook:') ||
                          event.startsWith(compatModelEventPrefix)))) {
                  if (!propsOptions || !(toHandlerKey(event) in propsOptions)) {
                      warn$1(`Component emitted event "${event}" but it is neither declared in ` +
                          `the emits option nor as an "${toHandlerKey(event)}" prop.`);
                  }
              }
              else {
                  const validator = emitsOptions[event];
                  if (isFunction(validator)) {
                      const isValid = validator(...rawArgs);
                      if (!isValid) {
                          warn$1(`Invalid event arguments: event validation failed for event "${event}".`);
                      }
                  }
              }
          }
      }
      let args = rawArgs;
      const isModelListener = event.startsWith('update:');
      // for v-model update:xxx events, apply modifiers on args
      const modelArg = isModelListener && event.slice(7);
      if (modelArg && modelArg in props) {
          const modifiersKey = `${modelArg === 'modelValue' ? 'model' : modelArg}Modifiers`;
          const { number, trim } = props[modifiersKey] || EMPTY_OBJ;
          if (trim) {
              args = rawArgs.map(a => a.trim());
          }
          if (number) {
              args = rawArgs.map(toNumber);
          }
      }
      {
          devtoolsComponentEmit(instance, event, args);
      }
      {
          const lowerCaseEvent = event.toLowerCase();
          if (lowerCaseEvent !== event && props[toHandlerKey(lowerCaseEvent)]) {
              warn$1(`Event "${lowerCaseEvent}" is emitted in component ` +
                  `${formatComponentName(instance, instance.type)} but the handler is registered for "${event}". ` +
                  `Note that HTML attributes are case-insensitive and you cannot use ` +
                  `v-on to listen to camelCase events when using in-DOM templates. ` +
                  `You should probably use "${hyphenate(event)}" instead of "${event}".`);
          }
      }
      let handlerName;
      let handler = props[(handlerName = toHandlerKey(event))] ||
          // also try camelCase event handler (#2249)
          props[(handlerName = toHandlerKey(camelize(event)))];
      // for v-model update:xxx events, also trigger kebab-case equivalent
      // for props passed via kebab-case
      if (!handler && isModelListener) {
          handler = props[(handlerName = toHandlerKey(hyphenate(event)))];
      }
      if (handler) {
          callWithAsyncErrorHandling(handler, instance, 6 /* COMPONENT_EVENT_HANDLER */, args);
      }
      const onceHandler = props[handlerName + `Once`];
      if (onceHandler) {
          if (!instance.emitted) {
              instance.emitted = {};
          }
          else if (instance.emitted[handlerName]) {
              return;
          }
          instance.emitted[handlerName] = true;
          callWithAsyncErrorHandling(onceHandler, instance, 6 /* COMPONENT_EVENT_HANDLER */, args);
      }
      {
          compatModelEmit(instance, event, args);
          return emit$1(instance, event, args);
      }
  }
  function normalizeEmitsOptions(comp, appContext, asMixin = false) {
      const cache = appContext.emitsCache;
      const cached = cache.get(comp);
      if (cached !== undefined) {
          return cached;
      }
      const raw = comp.emits;
      let normalized = {};
      // apply mixin/extends props
      let hasExtends = false;
      if (!isFunction(comp)) {
          const extendEmits = (raw) => {
              const normalizedFromExtend = normalizeEmitsOptions(raw, appContext, true);
              if (normalizedFromExtend) {
                  hasExtends = true;
                  extend(normalized, normalizedFromExtend);
              }
          };
          if (!asMixin && appContext.mixins.length) {
              appContext.mixins.forEach(extendEmits);
          }
          if (comp.extends) {
              extendEmits(comp.extends);
          }
          if (comp.mixins) {
              comp.mixins.forEach(extendEmits);
          }
      }
      if (!raw && !hasExtends) {
          cache.set(comp, null);
          return null;
      }
      if (isArray(raw)) {
          raw.forEach(key => (normalized[key] = null));
      }
      else {
          extend(normalized, raw);
      }
      cache.set(comp, normalized);
      return normalized;
  }
  // Check if an incoming prop key is a declared emit event listener.
  // e.g. With `emits: { click: null }`, props named `onClick` and `onclick` are
  // both considered matched listeners.
  function isEmitListener(options, key) {
      if (!options || !isOn(key)) {
          return false;
      }
      if (key.startsWith(compatModelEventPrefix)) {
          return true;
      }
      key = key.slice(2).replace(/Once$/, '');
      return (hasOwn(options, key[0].toLowerCase() + key.slice(1)) ||
          hasOwn(options, hyphenate(key)) ||
          hasOwn(options, key));
  }

  /**
   * mark the current rendering instance for asset resolution (e.g.
   * resolveComponent, resolveDirective) during render
   */
  let currentRenderingInstance = null;
  let currentScopeId = null;
  /**
   * Note: rendering calls maybe nested. The function returns the parent rendering
   * instance if present, which should be restored after the render is done:
   *
   * ```js
   * const prev = setCurrentRenderingInstance(i)
   * // ...render
   * setCurrentRenderingInstance(prev)
   * ```
   */
  function setCurrentRenderingInstance(instance) {
      const prev = currentRenderingInstance;
      currentRenderingInstance = instance;
      currentScopeId = (instance && instance.type.__scopeId) || null;
      // v2 pre-compiled components uses _scopeId instead of __scopeId
      if (!currentScopeId) {
          currentScopeId = (instance && instance.type._scopeId) || null;
      }
      return prev;
  }
  /**
   * Set scope id when creating hoisted vnodes.
   * @private compiler helper
   */
  function pushScopeId(id) {
      currentScopeId = id;
  }
  /**
   * Technically we no longer need this after 3.0.8 but we need to keep the same
   * API for backwards compat w/ code generated by compilers.
   * @private
   */
  function popScopeId() {
      currentScopeId = null;
  }
  /**
   * Only for backwards compat
   * @private
   */
  const withScopeId = (_id) => withCtx;
  /**
   * Wrap a slot function to memoize current rendering instance
   * @private compiler helper
   */
  function withCtx(fn, ctx = currentRenderingInstance, isNonScopedSlot // true only
  ) {
      if (!ctx)
          return fn;
      // already normalized
      if (fn._n) {
          return fn;
      }
      const renderFnWithContext = (...args) => {
          // If a user calls a compiled slot inside a template expression (#1745), it
          // can mess up block tracking, so by default we disable block tracking and
          // force bail out when invoking a compiled slot (indicated by the ._d flag).
          // This isn't necessary if rendering a compiled `<slot>`, so we flip the
          // ._d flag off when invoking the wrapped fn inside `renderSlot`.
          if (renderFnWithContext._d) {
              setBlockTracking(-1);
          }
          const prevInstance = setCurrentRenderingInstance(ctx);
          const res = fn(...args);
          setCurrentRenderingInstance(prevInstance);
          if (renderFnWithContext._d) {
              setBlockTracking(1);
          }
          {
              devtoolsComponentUpdated(ctx);
          }
          return res;
      };
      // mark normalized to avoid duplicated wrapping
      renderFnWithContext._n = true;
      // mark this as compiled by default
      // this is used in vnode.ts -> normalizeChildren() to set the slot
      // rendering flag.
      renderFnWithContext._c = true;
      // disable block tracking by default
      renderFnWithContext._d = true;
      // compat build only flag to distinguish scoped slots from non-scoped ones
      if (isNonScopedSlot) {
          renderFnWithContext._ns = true;
      }
      return renderFnWithContext;
  }

  /**
   * dev only flag to track whether $attrs was used during render.
   * If $attrs was used during render then the warning for failed attrs
   * fallthrough can be suppressed.
   */
  let accessedAttrs = false;
  function markAttrsAccessed() {
      accessedAttrs = true;
  }
  function renderComponentRoot(instance) {
      const { type: Component, vnode, proxy, withProxy, props, propsOptions: [propsOptions], slots, attrs, emit, render, renderCache, data, setupState, ctx, inheritAttrs } = instance;
      let result;
      let fallthroughAttrs;
      const prev = setCurrentRenderingInstance(instance);
      {
          accessedAttrs = false;
      }
      try {
          if (vnode.shapeFlag & 4 /* STATEFUL_COMPONENT */) {
              // withProxy is a proxy with a different `has` trap only for
              // runtime-compiled render functions using `with` block.
              const proxyToUse = withProxy || proxy;
              result = normalizeVNode(render.call(proxyToUse, proxyToUse, renderCache, props, setupState, data, ctx));
              fallthroughAttrs = attrs;
          }
          else {
              // functional
              const render = Component;
              // in dev, mark attrs accessed if optional props (attrs === props)
              if (true && attrs === props) {
                  markAttrsAccessed();
              }
              result = normalizeVNode(render.length > 1
                  ? render(props, true
                      ? {
                          get attrs() {
                              markAttrsAccessed();
                              return attrs;
                          },
                          slots,
                          emit
                      }
                      : { attrs, slots, emit })
                  : render(props, null /* we know it doesn't need it */));
              fallthroughAttrs = Component.props
                  ? attrs
                  : getFunctionalFallthrough(attrs);
          }
      }
      catch (err) {
          blockStack.length = 0;
          handleError(err, instance, 1 /* RENDER_FUNCTION */);
          result = createVNode(Comment);
      }
      // attr merging
      // in dev mode, comments are preserved, and it's possible for a template
      // to have comments along side the root element which makes it a fragment
      let root = result;
      let setRoot = undefined;
      if (result.patchFlag > 0 &&
          result.patchFlag & 2048 /* DEV_ROOT_FRAGMENT */) {
          [root, setRoot] = getChildRoot(result);
      }
      if (fallthroughAttrs && inheritAttrs !== false) {
          const keys = Object.keys(fallthroughAttrs);
          const { shapeFlag } = root;
          if (keys.length) {
              if (shapeFlag & (1 /* ELEMENT */ | 6 /* COMPONENT */)) {
                  if (propsOptions && keys.some(isModelListener)) {
                      // If a v-model listener (onUpdate:xxx) has a corresponding declared
                      // prop, it indicates this component expects to handle v-model and
                      // it should not fallthrough.
                      // related: #1543, #1643, #1989
                      fallthroughAttrs = filterModelListeners(fallthroughAttrs, propsOptions);
                  }
                  root = cloneVNode(root, fallthroughAttrs);
              }
              else if (!accessedAttrs && root.type !== Comment) {
                  const allAttrs = Object.keys(attrs);
                  const eventAttrs = [];
                  const extraAttrs = [];
                  for (let i = 0, l = allAttrs.length; i < l; i++) {
                      const key = allAttrs[i];
                      if (isOn(key)) {
                          // ignore v-model handlers when they fail to fallthrough
                          if (!isModelListener(key)) {
                              // remove `on`, lowercase first letter to reflect event casing
                              // accurately
                              eventAttrs.push(key[2].toLowerCase() + key.slice(3));
                          }
                      }
                      else {
                          extraAttrs.push(key);
                      }
                  }
                  if (extraAttrs.length) {
                      warn$1(`Extraneous non-props attributes (` +
                          `${extraAttrs.join(', ')}) ` +
                          `were passed to component but could not be automatically inherited ` +
                          `because component renders fragment or text root nodes.`);
                  }
                  if (eventAttrs.length) {
                      warn$1(`Extraneous non-emits event listeners (` +
                          `${eventAttrs.join(', ')}) ` +
                          `were passed to component but could not be automatically inherited ` +
                          `because component renders fragment or text root nodes. ` +
                          `If the listener is intended to be a component custom event listener only, ` +
                          `declare it using the "emits" option.`);
                  }
              }
          }
      }
      if (isCompatEnabled("INSTANCE_ATTRS_CLASS_STYLE" /* INSTANCE_ATTRS_CLASS_STYLE */, instance) &&
          vnode.shapeFlag & 4 /* STATEFUL_COMPONENT */ &&
          root.shapeFlag & (1 /* ELEMENT */ | 6 /* COMPONENT */)) {
          const { class: cls, style } = vnode.props || {};
          if (cls || style) {
              if (inheritAttrs === false) {
                  warnDeprecation("INSTANCE_ATTRS_CLASS_STYLE" /* INSTANCE_ATTRS_CLASS_STYLE */, instance, getComponentName(instance.type));
              }
              root = cloneVNode(root, {
                  class: cls,
                  style: style
              });
          }
      }
      // inherit directives
      if (vnode.dirs) {
          if (!isElementRoot(root)) {
              warn$1(`Runtime directive used on component with non-element root node. ` +
                  `The directives will not function as intended.`);
          }
          // clone before mutating since the root may be a hoisted vnode
          root = cloneVNode(root);
          root.dirs = root.dirs ? root.dirs.concat(vnode.dirs) : vnode.dirs;
      }
      // inherit transition data
      if (vnode.transition) {
          if (!isElementRoot(root)) {
              warn$1(`Component inside <Transition> renders non-element root node ` +
                  `that cannot be animated.`);
          }
          root.transition = vnode.transition;
      }
      if (setRoot) {
          setRoot(root);
      }
      else {
          result = root;
      }
      setCurrentRenderingInstance(prev);
      return result;
  }
  /**
   * dev only
   * In dev mode, template root level comments are rendered, which turns the
   * template into a fragment root, but we need to locate the single element
   * root for attrs and scope id processing.
   */
  const getChildRoot = (vnode) => {
      const rawChildren = vnode.children;
      const dynamicChildren = vnode.dynamicChildren;
      const childRoot = filterSingleRoot(rawChildren);
      if (!childRoot) {
          return [vnode, undefined];
      }
      const index = rawChildren.indexOf(childRoot);
      const dynamicIndex = dynamicChildren ? dynamicChildren.indexOf(childRoot) : -1;
      const setRoot = (updatedRoot) => {
          rawChildren[index] = updatedRoot;
          if (dynamicChildren) {
              if (dynamicIndex > -1) {
                  dynamicChildren[dynamicIndex] = updatedRoot;
              }
              else if (updatedRoot.patchFlag > 0) {
                  vnode.dynamicChildren = [...dynamicChildren, updatedRoot];
              }
          }
      };
      return [normalizeVNode(childRoot), setRoot];
  };
  function filterSingleRoot(children) {
      let singleRoot;
      for (let i = 0; i < children.length; i++) {
          const child = children[i];
          if (isVNode(child)) {
              // ignore user comment
              if (child.type !== Comment || child.children === 'v-if') {
                  if (singleRoot) {
                      // has more than 1 non-comment child, return now
                      return;
                  }
                  else {
                      singleRoot = child;
                  }
              }
          }
          else {
              return;
          }
      }
      return singleRoot;
  }
  const getFunctionalFallthrough = (attrs) => {
      let res;
      for (const key in attrs) {
          if (key === 'class' || key === 'style' || isOn(key)) {
              (res || (res = {}))[key] = attrs[key];
          }
      }
      return res;
  };
  const filterModelListeners = (attrs, props) => {
      const res = {};
      for (const key in attrs) {
          if (!isModelListener(key) || !(key.slice(9) in props)) {
              res[key] = attrs[key];
          }
      }
      return res;
  };
  const isElementRoot = (vnode) => {
      return (vnode.shapeFlag & (6 /* COMPONENT */ | 1 /* ELEMENT */) ||
          vnode.type === Comment // potential v-if branch switch
      );
  };
  function shouldUpdateComponent(prevVNode, nextVNode, optimized) {
      const { props: prevProps, children: prevChildren, component } = prevVNode;
      const { props: nextProps, children: nextChildren, patchFlag } = nextVNode;
      const emits = component.emitsOptions;
      // Parent component's render function was hot-updated. Since this may have
      // caused the child component's slots content to have changed, we need to
      // force the child to update as well.
      if ((prevChildren || nextChildren) && isHmrUpdating) {
          return true;
      }
      // force child update for runtime directive or transition on component vnode.
      if (nextVNode.dirs || nextVNode.transition) {
          return true;
      }
      if (optimized && patchFlag >= 0) {
          if (patchFlag & 1024 /* DYNAMIC_SLOTS */) {
              // slot content that references values that might have changed,
              // e.g. in a v-for
              return true;
          }
          if (patchFlag & 16 /* FULL_PROPS */) {
              if (!prevProps) {
                  return !!nextProps;
              }
              // presence of this flag indicates props are always non-null
              return hasPropsChanged(prevProps, nextProps, emits);
          }
          else if (patchFlag & 8 /* PROPS */) {
              const dynamicProps = nextVNode.dynamicProps;
              for (let i = 0; i < dynamicProps.length; i++) {
                  const key = dynamicProps[i];
                  if (nextProps[key] !== prevProps[key] &&
                      !isEmitListener(emits, key)) {
                      return true;
                  }
              }
          }
      }
      else {
          // this path is only taken by manually written render functions
          // so presence of any children leads to a forced update
          if (prevChildren || nextChildren) {
              if (!nextChildren || !nextChildren.$stable) {
                  return true;
              }
          }
          if (prevProps === nextProps) {
              return false;
          }
          if (!prevProps) {
              return !!nextProps;
          }
          if (!nextProps) {
              return true;
          }
          return hasPropsChanged(prevProps, nextProps, emits);
      }
      return false;
  }
  function hasPropsChanged(prevProps, nextProps, emitsOptions) {
      const nextKeys = Object.keys(nextProps);
      if (nextKeys.length !== Object.keys(prevProps).length) {
          return true;
      }
      for (let i = 0; i < nextKeys.length; i++) {
          const key = nextKeys[i];
          if (nextProps[key] !== prevProps[key] &&
              !isEmitListener(emitsOptions, key)) {
              return true;
          }
      }
      return false;
  }
  function updateHOCHostEl({ vnode, parent }, el // HostNode
  ) {
      while (parent && parent.subTree === vnode) {
          (vnode = parent.vnode).el = el;
          parent = parent.parent;
      }
  }

  const isSuspense = (type) => type.__isSuspense;
  // Suspense exposes a component-like API, and is treated like a component
  // in the compiler, but internally it's a special built-in type that hooks
  // directly into the renderer.
  const SuspenseImpl = {
      name: 'Suspense',
      // In order to make Suspense tree-shakable, we need to avoid importing it
      // directly in the renderer. The renderer checks for the __isSuspense flag
      // on a vnode's type and calls the `process` method, passing in renderer
      // internals.
      __isSuspense: true,
      process(n1, n2, container, anchor, parentComponent, parentSuspense, isSVG, slotScopeIds, optimized, 
      // platform-specific impl passed from renderer
      rendererInternals) {
          if (n1 == null) {
              mountSuspense(n2, container, anchor, parentComponent, parentSuspense, isSVG, slotScopeIds, optimized, rendererInternals);
          }
          else {
              patchSuspense(n1, n2, container, anchor, parentComponent, isSVG, slotScopeIds, optimized, rendererInternals);
          }
      },
      hydrate: hydrateSuspense,
      create: createSuspenseBoundary,
      normalize: normalizeSuspenseChildren
  };
  // Force-casted public typing for h and TSX props inference
  const Suspense = (SuspenseImpl );
  function triggerEvent(vnode, name) {
      const eventListener = vnode.props && vnode.props[name];
      if (isFunction(eventListener)) {
          eventListener();
      }
  }
  function mountSuspense(vnode, container, anchor, parentComponent, parentSuspense, isSVG, slotScopeIds, optimized, rendererInternals) {
      const { p: patch, o: { createElement } } = rendererInternals;
      const hiddenContainer = createElement('div');
      const suspense = (vnode.suspense = createSuspenseBoundary(vnode, parentSuspense, parentComponent, container, hiddenContainer, anchor, isSVG, slotScopeIds, optimized, rendererInternals));
      // start mounting the content subtree in an off-dom container
      patch(null, (suspense.pendingBranch = vnode.ssContent), hiddenContainer, null, parentComponent, suspense, isSVG, slotScopeIds);
      // now check if we have encountered any async deps
      if (suspense.deps > 0) {
          // has async
          // invoke @fallback event
          triggerEvent(vnode, 'onPending');
          triggerEvent(vnode, 'onFallback');
          // mount the fallback tree
          patch(null, vnode.ssFallback, container, anchor, parentComponent, null, // fallback tree will not have suspense context
          isSVG, slotScopeIds);
          setActiveBranch(suspense, vnode.ssFallback);
      }
      else {
          // Suspense has no async deps. Just resolve.
          suspense.resolve();
      }
  }
  function patchSuspense(n1, n2, container, anchor, parentComponent, isSVG, slotScopeIds, optimized, { p: patch, um: unmount, o: { createElement } }) {
      const suspense = (n2.suspense = n1.suspense);
      suspense.vnode = n2;
      n2.el = n1.el;
      const newBranch = n2.ssContent;
      const newFallback = n2.ssFallback;
      const { activeBranch, pendingBranch, isInFallback, isHydrating } = suspense;
      if (pendingBranch) {
          suspense.pendingBranch = newBranch;
          if (isSameVNodeType(newBranch, pendingBranch)) {
              // same root type but content may have changed.
              patch(pendingBranch, newBranch, suspense.hiddenContainer, null, parentComponent, suspense, isSVG, slotScopeIds, optimized);
              if (suspense.deps <= 0) {
                  suspense.resolve();
              }
              else if (isInFallback) {
                  patch(activeBranch, newFallback, container, anchor, parentComponent, null, // fallback tree will not have suspense context
                  isSVG, slotScopeIds, optimized);
                  setActiveBranch(suspense, newFallback);
              }
          }
          else {
              // toggled before pending tree is resolved
              suspense.pendingId++;
              if (isHydrating) {
                  // if toggled before hydration is finished, the current DOM tree is
                  // no longer valid. set it as the active branch so it will be unmounted
                  // when resolved
                  suspense.isHydrating = false;
                  suspense.activeBranch = pendingBranch;
              }
              else {
                  unmount(pendingBranch, parentComponent, suspense);
              }
              // increment pending ID. this is used to invalidate async callbacks
              // reset suspense state
              suspense.deps = 0;
              // discard effects from pending branch
              suspense.effects.length = 0;
              // discard previous container
              suspense.hiddenContainer = createElement('div');
              if (isInFallback) {
                  // already in fallback state
                  patch(null, newBranch, suspense.hiddenContainer, null, parentComponent, suspense, isSVG, slotScopeIds, optimized);
                  if (suspense.deps <= 0) {
                      suspense.resolve();
                  }
                  else {
                      patch(activeBranch, newFallback, container, anchor, parentComponent, null, // fallback tree will not have suspense context
                      isSVG, slotScopeIds, optimized);
                      setActiveBranch(suspense, newFallback);
                  }
              }
              else if (activeBranch && isSameVNodeType(newBranch, activeBranch)) {
                  // toggled "back" to current active branch
                  patch(activeBranch, newBranch, container, anchor, parentComponent, suspense, isSVG, slotScopeIds, optimized);
                  // force resolve
                  suspense.resolve(true);
              }
              else {
                  // switched to a 3rd branch
                  patch(null, newBranch, suspense.hiddenContainer, null, parentComponent, suspense, isSVG, slotScopeIds, optimized);
                  if (suspense.deps <= 0) {
                      suspense.resolve();
                  }
              }
          }
      }
      else {
          if (activeBranch && isSameVNodeType(newBranch, activeBranch)) {
              // root did not change, just normal patch
              patch(activeBranch, newBranch, container, anchor, parentComponent, suspense, isSVG, slotScopeIds, optimized);
              setActiveBranch(suspense, newBranch);
          }
          else {
              // root node toggled
              // invoke @pending event
              triggerEvent(n2, 'onPending');
              // mount pending branch in off-dom container
              suspense.pendingBranch = newBranch;
              suspense.pendingId++;
              patch(null, newBranch, suspense.hiddenContainer, null, parentComponent, suspense, isSVG, slotScopeIds, optimized);
              if (suspense.deps <= 0) {
                  // incoming branch has no async deps, resolve now.
                  suspense.resolve();
              }
              else {
                  const { timeout, pendingId } = suspense;
                  if (timeout > 0) {
                      setTimeout(() => {
                          if (suspense.pendingId === pendingId) {
                              suspense.fallback(newFallback);
                          }
                      }, timeout);
                  }
                  else if (timeout === 0) {
                      suspense.fallback(newFallback);
                  }
              }
          }
      }
  }
  let hasWarned = false;
  function createSuspenseBoundary(vnode, parent, parentComponent, container, hiddenContainer, anchor, isSVG, slotScopeIds, optimized, rendererInternals, isHydrating = false) {
      /* istanbul ignore if */
      if (!hasWarned) {
          hasWarned = true;
          // @ts-ignore `console.info` cannot be null error
          console[console.info ? 'info' : 'log'](`<Suspense> is an experimental feature and its API will likely change.`);
      }
      const { p: patch, m: move, um: unmount, n: next, o: { parentNode, remove } } = rendererInternals;
      const timeout = toNumber(vnode.props && vnode.props.timeout);
      const suspense = {
          vnode,
          parent,
          parentComponent,
          isSVG,
          container,
          hiddenContainer,
          anchor,
          deps: 0,
          pendingId: 0,
          timeout: typeof timeout === 'number' ? timeout : -1,
          activeBranch: null,
          pendingBranch: null,
          isInFallback: true,
          isHydrating,
          isUnmounted: false,
          effects: [],
          resolve(resume = false) {
              {
                  if (!resume && !suspense.pendingBranch) {
                      throw new Error(`suspense.resolve() is called without a pending branch.`);
                  }
                  if (suspense.isUnmounted) {
                      throw new Error(`suspense.resolve() is called on an already unmounted suspense boundary.`);
                  }
              }
              const { vnode, activeBranch, pendingBranch, pendingId, effects, parentComponent, container } = suspense;
              if (suspense.isHydrating) {
                  suspense.isHydrating = false;
              }
              else if (!resume) {
                  const delayEnter = activeBranch &&
                      pendingBranch.transition &&
                      pendingBranch.transition.mode === 'out-in';
                  if (delayEnter) {
                      activeBranch.transition.afterLeave = () => {
                          if (pendingId === suspense.pendingId) {
                              move(pendingBranch, container, anchor, 0 /* ENTER */);
                          }
                      };
                  }
                  // this is initial anchor on mount
                  let { anchor } = suspense;
                  // unmount current active tree
                  if (activeBranch) {
                      // if the fallback tree was mounted, it may have been moved
                      // as part of a parent suspense. get the latest anchor for insertion
                      anchor = next(activeBranch);
                      unmount(activeBranch, parentComponent, suspense, true);
                  }
                  if (!delayEnter) {
                      // move content from off-dom container to actual container
                      move(pendingBranch, container, anchor, 0 /* ENTER */);
                  }
              }
              setActiveBranch(suspense, pendingBranch);
              suspense.pendingBranch = null;
              suspense.isInFallback = false;
              // flush buffered effects
              // check if there is a pending parent suspense
              let parent = suspense.parent;
              let hasUnresolvedAncestor = false;
              while (parent) {
                  if (parent.pendingBranch) {
                      // found a pending parent suspense, merge buffered post jobs
                      // into that parent
                      parent.effects.push(...effects);
                      hasUnresolvedAncestor = true;
                      break;
                  }
                  parent = parent.parent;
              }
              // no pending parent suspense, flush all jobs
              if (!hasUnresolvedAncestor) {
                  queuePostFlushCb(effects);
              }
              suspense.effects = [];
              // invoke @resolve event
              triggerEvent(vnode, 'onResolve');
          },
          fallback(fallbackVNode) {
              if (!suspense.pendingBranch) {
                  return;
              }
              const { vnode, activeBranch, parentComponent, container, isSVG } = suspense;
              // invoke @fallback event
              triggerEvent(vnode, 'onFallback');
              const anchor = next(activeBranch);
              const mountFallback = () => {
                  if (!suspense.isInFallback) {
                      return;
                  }
                  // mount the fallback tree
                  patch(null, fallbackVNode, container, anchor, parentComponent, null, // fallback tree will not have suspense context
                  isSVG, slotScopeIds, optimized);
                  setActiveBranch(suspense, fallbackVNode);
              };
              const delayEnter = fallbackVNode.transition && fallbackVNode.transition.mode === 'out-in';
              if (delayEnter) {
                  activeBranch.transition.afterLeave = mountFallback;
              }
              suspense.isInFallback = true;
              // unmount current active branch
              unmount(activeBranch, parentComponent, null, // no suspense so unmount hooks fire now
              true // shouldRemove
              );
              if (!delayEnter) {
                  mountFallback();
              }
          },
          move(container, anchor, type) {
              suspense.activeBranch &&
                  move(suspense.activeBranch, container, anchor, type);
              suspense.container = container;
          },
          next() {
              return suspense.activeBranch && next(suspense.activeBranch);
          },
          registerDep(instance, setupRenderEffect) {
              const isInPendingSuspense = !!suspense.pendingBranch;
              if (isInPendingSuspense) {
                  suspense.deps++;
              }
              const hydratedEl = instance.vnode.el;
              instance
                  .asyncDep.catch(err => {
                  handleError(err, instance, 0 /* SETUP_FUNCTION */);
              })
                  .then(asyncSetupResult => {
                  // retry when the setup() promise resolves.
                  // component may have been unmounted before resolve.
                  if (instance.isUnmounted ||
                      suspense.isUnmounted ||
                      suspense.pendingId !== instance.suspenseId) {
                      return;
                  }
                  // retry from this component
                  instance.asyncResolved = true;
                  const { vnode } = instance;
                  {
                      pushWarningContext(vnode);
                  }
                  handleSetupResult(instance, asyncSetupResult, false);
                  if (hydratedEl) {
                      // vnode may have been replaced if an update happened before the
                      // async dep is resolved.
                      vnode.el = hydratedEl;
                  }
                  const placeholder = !hydratedEl && instance.subTree.el;
                  setupRenderEffect(instance, vnode, 
                  // component may have been moved before resolve.
                  // if this is not a hydration, instance.subTree will be the comment
                  // placeholder.
                  parentNode(hydratedEl || instance.subTree.el), 
                  // anchor will not be used if this is hydration, so only need to
                  // consider the comment placeholder case.
                  hydratedEl ? null : next(instance.subTree), suspense, isSVG, optimized);
                  if (placeholder) {
                      remove(placeholder);
                  }
                  updateHOCHostEl(instance, vnode.el);
                  {
                      popWarningContext();
                  }
                  // only decrease deps count if suspense is not already resolved
                  if (isInPendingSuspense && --suspense.deps === 0) {
                      suspense.resolve();
                  }
              });
          },
          unmount(parentSuspense, doRemove) {
              suspense.isUnmounted = true;
              if (suspense.activeBranch) {
                  unmount(suspense.activeBranch, parentComponent, parentSuspense, doRemove);
              }
              if (suspense.pendingBranch) {
                  unmount(suspense.pendingBranch, parentComponent, parentSuspense, doRemove);
              }
          }
      };
      return suspense;
  }
  function hydrateSuspense(node, vnode, parentComponent, parentSuspense, isSVG, slotScopeIds, optimized, rendererInternals, hydrateNode) {
      /* eslint-disable no-restricted-globals */
      const suspense = (vnode.suspense = createSuspenseBoundary(vnode, parentSuspense, parentComponent, node.parentNode, document.createElement('div'), null, isSVG, slotScopeIds, optimized, rendererInternals, true /* hydrating */));
      // there are two possible scenarios for server-rendered suspense:
      // - success: ssr content should be fully resolved
      // - failure: ssr content should be the fallback branch.
      // however, on the client we don't really know if it has failed or not
      // attempt to hydrate the DOM assuming it has succeeded, but we still
      // need to construct a suspense boundary first
      const result = hydrateNode(node, (suspense.pendingBranch = vnode.ssContent), parentComponent, suspense, slotScopeIds, optimized);
      if (suspense.deps === 0) {
          suspense.resolve();
      }
      return result;
      /* eslint-enable no-restricted-globals */
  }
  function normalizeSuspenseChildren(vnode) {
      const { shapeFlag, children } = vnode;
      const isSlotChildren = shapeFlag & 32 /* SLOTS_CHILDREN */;
      vnode.ssContent = normalizeSuspenseSlot(isSlotChildren ? children.default : children);
      vnode.ssFallback = isSlotChildren
          ? normalizeSuspenseSlot(children.fallback)
          : createVNode(Comment);
  }
  function normalizeSuspenseSlot(s) {
      let block;
      if (isFunction(s)) {
          const trackBlock = isBlockTreeEnabled && s._c;
          if (trackBlock) {
              // disableTracking: false
              // allow block tracking for compiled slots
              // (see ./componentRenderContext.ts)
              s._d = false;
              openBlock();
          }
          s = s();
          if (trackBlock) {
              s._d = true;
              block = currentBlock;
              closeBlock();
          }
      }
      if (isArray(s)) {
          const singleChild = filterSingleRoot(s);
          if (!singleChild) {
              warn$1(`<Suspense> slots expect a single root node.`);
          }
          s = singleChild;
      }
      s = normalizeVNode(s);
      if (block && !s.dynamicChildren) {
          s.dynamicChildren = block.filter(c => c !== s);
      }
      return s;
  }
  function queueEffectWithSuspense(fn, suspense) {
      if (suspense && suspense.pendingBranch) {
          if (isArray(fn)) {
              suspense.effects.push(...fn);
          }
          else {
              suspense.effects.push(fn);
          }
      }
      else {
          queuePostFlushCb(fn);
      }
  }
  function setActiveBranch(suspense, branch) {
      suspense.activeBranch = branch;
      const { vnode, parentComponent } = suspense;
      const el = (vnode.el = branch.el);
      // in case suspense is the root node of a component,
      // recursively update the HOC el
      if (parentComponent && parentComponent.subTree === vnode) {
          parentComponent.vnode.el = el;
          updateHOCHostEl(parentComponent, el);
      }
  }

  function provide(key, value) {
      if (!currentInstance) {
          {
              warn$1(`provide() can only be used inside setup().`);
          }
      }
      else {
          let provides = currentInstance.provides;
          // by default an instance inherits its parent's provides object
          // but when it needs to provide values of its own, it creates its
          // own provides object using parent provides object as prototype.
          // this way in `inject` we can simply look up injections from direct
          // parent and let the prototype chain do the work.
          const parentProvides = currentInstance.parent && currentInstance.parent.provides;
          if (parentProvides === provides) {
              provides = currentInstance.provides = Object.create(parentProvides);
          }
          // TS doesn't allow symbol as index type
          provides[key] = value;
      }
  }
  function inject(key, defaultValue, treatDefaultAsFactory = false) {
      // fallback to `currentRenderingInstance` so that this can be called in
      // a functional component
      const instance = currentInstance || currentRenderingInstance;
      if (instance) {
          // #2400
          // to support `app.use` plugins,
          // fallback to appContext's `provides` if the instance is at root
          const provides = instance.parent == null
              ? instance.vnode.appContext && instance.vnode.appContext.provides
              : instance.parent.provides;
          if (provides && key in provides) {
              // TS doesn't allow symbol as index type
              return provides[key];
          }
          else if (arguments.length > 1) {
              return treatDefaultAsFactory && isFunction(defaultValue)
                  ? defaultValue.call(instance.proxy)
                  : defaultValue;
          }
          else {
              warn$1(`injection "${String(key)}" not found.`);
          }
      }
      else {
          warn$1(`inject() can only be used inside setup() or functional components.`);
      }
  }

  // Simple effect.
  function watchEffect(effect, options) {
      return doWatch(effect, null, options);
  }
  function watchPostEffect(effect, options) {
      return doWatch(effect, null, (Object.assign(Object.assign({}, options), { flush: 'post' }) ));
  }
  function watchSyncEffect(effect, options) {
      return doWatch(effect, null, (Object.assign(Object.assign({}, options), { flush: 'sync' }) ));
  }
  // initial value for watchers to trigger on undefined initial values
  const INITIAL_WATCHER_VALUE = {};
  // implementation
  function watch(source, cb, options) {
      if (!isFunction(cb)) {
          warn$1(`\`watch(fn, options?)\` signature has been moved to a separate API. ` +
              `Use \`watchEffect(fn, options?)\` instead. \`watch\` now only ` +
              `supports \`watch(source, cb, options?) signature.`);
      }
      return doWatch(source, cb, options);
  }
  function doWatch(source, cb, { immediate, deep, flush, onTrack, onTrigger } = EMPTY_OBJ) {
      if (!cb) {
          if (immediate !== undefined) {
              warn$1(`watch() "immediate" option is only respected when using the ` +
                  `watch(source, callback, options?) signature.`);
          }
          if (deep !== undefined) {
              warn$1(`watch() "deep" option is only respected when using the ` +
                  `watch(source, callback, options?) signature.`);
          }
      }
      const warnInvalidSource = (s) => {
          warn$1(`Invalid watch source: `, s, `A watch source can only be a getter/effect function, a ref, ` +
              `a reactive object, or an array of these types.`);
      };
      const instance = currentInstance;
      let getter;
      let forceTrigger = false;
      let isMultiSource = false;
      if (isRef(source)) {
          getter = () => source.value;
          forceTrigger = isShallow(source);
      }
      else if (isReactive(source)) {
          getter = () => source;
          deep = true;
      }
      else if (isArray(source)) {
          isMultiSource = true;
          forceTrigger = source.some(s => isReactive(s) || isShallow(s));
          getter = () => source.map(s => {
              if (isRef(s)) {
                  return s.value;
              }
              else if (isReactive(s)) {
                  return traverse(s);
              }
              else if (isFunction(s)) {
                  return callWithErrorHandling(s, instance, 2 /* WATCH_GETTER */);
              }
              else {
                  warnInvalidSource(s);
              }
          });
      }
      else if (isFunction(source)) {
          if (cb) {
              // getter with cb
              getter = () => callWithErrorHandling(source, instance, 2 /* WATCH_GETTER */);
          }
          else {
              // no cb -> simple effect
              getter = () => {
                  if (instance && instance.isUnmounted) {
                      return;
                  }
                  if (cleanup) {
                      cleanup();
                  }
                  return callWithAsyncErrorHandling(source, instance, 3 /* WATCH_CALLBACK */, [onCleanup]);
              };
          }
      }
      else {
          getter = NOOP;
          warnInvalidSource(source);
      }
      // 2.x array mutation watch compat
      if (cb && !deep) {
          const baseGetter = getter;
          getter = () => {
              const val = baseGetter();
              if (isArray(val) &&
                  checkCompatEnabled("WATCH_ARRAY" /* WATCH_ARRAY */, instance)) {
                  traverse(val);
              }
              return val;
          };
      }
      if (cb && deep) {
          const baseGetter = getter;
          getter = () => traverse(baseGetter());
      }
      let cleanup;
      let onCleanup = (fn) => {
          cleanup = effect.onStop = () => {
              callWithErrorHandling(fn, instance, 4 /* WATCH_CLEANUP */);
          };
      };
      let oldValue = isMultiSource ? [] : INITIAL_WATCHER_VALUE;
      const job = () => {
          if (!effect.active) {
              return;
          }
          if (cb) {
              // watch(source, cb)
              const newValue = effect.run();
              if (deep ||
                  forceTrigger ||
                  (isMultiSource
                      ? newValue.some((v, i) => hasChanged(v, oldValue[i]))
                      : hasChanged(newValue, oldValue)) ||
                  (isArray(newValue) &&
                      isCompatEnabled("WATCH_ARRAY" /* WATCH_ARRAY */, instance))) {
                  // cleanup before running cb again
                  if (cleanup) {
                      cleanup();
                  }
                  callWithAsyncErrorHandling(cb, instance, 3 /* WATCH_CALLBACK */, [
                      newValue,
                      // pass undefined as the old value when it's changed for the first time
                      oldValue === INITIAL_WATCHER_VALUE ? undefined : oldValue,
                      onCleanup
                  ]);
                  oldValue = newValue;
              }
          }
          else {
              // watchEffect
              effect.run();
          }
      };
      // important: mark the job as a watcher callback so that scheduler knows
      // it is allowed to self-trigger (#1727)
      job.allowRecurse = !!cb;
      let scheduler;
      if (flush === 'sync') {
          scheduler = job; // the scheduler function gets called directly
      }
      else if (flush === 'post') {
          scheduler = () => queuePostRenderEffect(job, instance && instance.suspense);
      }
      else {
          // default: 'pre'
          scheduler = () => queuePreFlushCb(job);
      }
      const effect = new ReactiveEffect(getter, scheduler);
      {
          effect.onTrack = onTrack;
          effect.onTrigger = onTrigger;
      }
      // initial run
      if (cb) {
          if (immediate) {
              job();
          }
          else {
              oldValue = effect.run();
          }
      }
      else if (flush === 'post') {
          queuePostRenderEffect(effect.run.bind(effect), instance && instance.suspense);
      }
      else {
          effect.run();
      }
      return () => {
          effect.stop();
          if (instance && instance.scope) {
              remove(instance.scope.effects, effect);
          }
      };
  }
  // this.$watch
  function instanceWatch(source, value, options) {
      const publicThis = this.proxy;
      const getter = isString(source)
          ? source.includes('.')
              ? createPathGetter(publicThis, source)
              : () => publicThis[source]
          : source.bind(publicThis, publicThis);
      let cb;
      if (isFunction(value)) {
          cb = value;
      }
      else {
          cb = value.handler;
          options = value;
      }
      const cur = currentInstance;
      setCurrentInstance(this);
      const res = doWatch(getter, cb.bind(publicThis), options);
      if (cur) {
          setCurrentInstance(cur);
      }
      else {
          unsetCurrentInstance();
      }
      return res;
  }
  function createPathGetter(ctx, path) {
      const segments = path.split('.');
      return () => {
          let cur = ctx;
          for (let i = 0; i < segments.length && cur; i++) {
              cur = cur[segments[i]];
          }
          return cur;
      };
  }
  function traverse(value, seen) {
      if (!isObject(value) || value["__v_skip" /* SKIP */]) {
          return value;
      }
      seen = seen || new Set();
      if (seen.has(value)) {
          return value;
      }
      seen.add(value);
      if (isRef(value)) {
          traverse(value.value, seen);
      }
      else if (isArray(value)) {
          for (let i = 0; i < value.length; i++) {
              traverse(value[i], seen);
          }
      }
      else if (isSet(value) || isMap(value)) {
          value.forEach((v) => {
              traverse(v, seen);
          });
      }
      else if (isPlainObject(value)) {
          for (const key in value) {
              traverse(value[key], seen);
          }
      }
      return value;
  }

  function useTransitionState() {
      const state = {
          isMounted: false,
          isLeaving: false,
          isUnmounting: false,
          leavingVNodes: new Map()
      };
      onMounted(() => {
          state.isMounted = true;
      });
      onBeforeUnmount(() => {
          state.isUnmounting = true;
      });
      return state;
  }
  const TransitionHookValidator = [Function, Array];
  const BaseTransitionImpl = {
      name: `BaseTransition`,
      props: {
          mode: String,
          appear: Boolean,
          persisted: Boolean,
          // enter
          onBeforeEnter: TransitionHookValidator,
          onEnter: TransitionHookValidator,
          onAfterEnter: TransitionHookValidator,
          onEnterCancelled: TransitionHookValidator,
          // leave
          onBeforeLeave: TransitionHookValidator,
          onLeave: TransitionHookValidator,
          onAfterLeave: TransitionHookValidator,
          onLeaveCancelled: TransitionHookValidator,
          // appear
          onBeforeAppear: TransitionHookValidator,
          onAppear: TransitionHookValidator,
          onAfterAppear: TransitionHookValidator,
          onAppearCancelled: TransitionHookValidator
      },
      setup(props, { slots }) {
          const instance = getCurrentInstance();
          const state = useTransitionState();
          let prevTransitionKey;
          return () => {
              const children = slots.default && getTransitionRawChildren(slots.default(), true);
              if (!children || !children.length) {
                  return;
              }
              let child = children[0];
              if (children.length > 1) {
                  let hasFound = false;
                  // locate first non-comment child
                  for (const c of children) {
                      if (c.type !== Comment) {
                          if (hasFound) {
                              // warn more than one non-comment child
                              warn$1('<transition> can only be used on a single element or component. ' +
                                  'Use <transition-group> for lists.');
                              break;
                          }
                          child = c;
                          hasFound = true;
                      }
                  }
              }
              // there's no need to track reactivity for these props so use the raw
              // props for a bit better perf
              const rawProps = toRaw(props);
              const { mode } = rawProps;
              // check mode
              if (mode &&
                  mode !== 'in-out' &&
                  mode !== 'out-in' &&
                  mode !== 'default') {
                  warn$1(`invalid <transition> mode: ${mode}`);
              }
              if (state.isLeaving) {
                  return emptyPlaceholder(child);
              }
              // in the case of <transition><keep-alive/></transition>, we need to
              // compare the type of the kept-alive children.
              const innerChild = getKeepAliveChild(child);
              if (!innerChild) {
                  return emptyPlaceholder(child);
              }
              const enterHooks = resolveTransitionHooks(innerChild, rawProps, state, instance);
              setTransitionHooks(innerChild, enterHooks);
              const oldChild = instance.subTree;
              const oldInnerChild = oldChild && getKeepAliveChild(oldChild);
              let transitionKeyChanged = false;
              const { getTransitionKey } = innerChild.type;
              if (getTransitionKey) {
                  const key = getTransitionKey();
                  if (prevTransitionKey === undefined) {
                      prevTransitionKey = key;
                  }
                  else if (key !== prevTransitionKey) {
                      prevTransitionKey = key;
                      transitionKeyChanged = true;
                  }
              }
              // handle mode
              if (oldInnerChild &&
                  oldInnerChild.type !== Comment &&
                  (!isSameVNodeType(innerChild, oldInnerChild) || transitionKeyChanged)) {
                  const leavingHooks = resolveTransitionHooks(oldInnerChild, rawProps, state, instance);
                  // update old tree's hooks in case of dynamic transition
                  setTransitionHooks(oldInnerChild, leavingHooks);
                  // switching between different views
                  if (mode === 'out-in') {
                      state.isLeaving = true;
                      // return placeholder node and queue update when leave finishes
                      leavingHooks.afterLeave = () => {
                          state.isLeaving = false;
                          instance.update();
                      };
                      return emptyPlaceholder(child);
                  }
                  else if (mode === 'in-out' && innerChild.type !== Comment) {
                      leavingHooks.delayLeave = (el, earlyRemove, delayedLeave) => {
                          const leavingVNodesCache = getLeavingNodesForType(state, oldInnerChild);
                          leavingVNodesCache[String(oldInnerChild.key)] = oldInnerChild;
                          // early removal callback
                          el._leaveCb = () => {
                              earlyRemove();
                              el._leaveCb = undefined;
                              delete enterHooks.delayedLeave;
                          };
                          enterHooks.delayedLeave = delayedLeave;
                      };
                  }
              }
              return child;
          };
      }
  };
  {
      BaseTransitionImpl.__isBuiltIn = true;
  }
  // export the public type for h/tsx inference
  // also to avoid inline import() in generated d.ts files
  const BaseTransition = BaseTransitionImpl;
  function getLeavingNodesForType(state, vnode) {
      const { leavingVNodes } = state;
      let leavingVNodesCache = leavingVNodes.get(vnode.type);
      if (!leavingVNodesCache) {
          leavingVNodesCache = Object.create(null);
          leavingVNodes.set(vnode.type, leavingVNodesCache);
      }
      return leavingVNodesCache;
  }
  // The transition hooks are attached to the vnode as vnode.transition
  // and will be called at appropriate timing in the renderer.
  function resolveTransitionHooks(vnode, props, state, instance) {
      const { appear, mode, persisted = false, onBeforeEnter, onEnter, onAfterEnter, onEnterCancelled, onBeforeLeave, onLeave, onAfterLeave, onLeaveCancelled, onBeforeAppear, onAppear, onAfterAppear, onAppearCancelled } = props;
      const key = String(vnode.key);
      const leavingVNodesCache = getLeavingNodesForType(state, vnode);
      const callHook = (hook, args) => {
          hook &&
              callWithAsyncErrorHandling(hook, instance, 9 /* TRANSITION_HOOK */, args);
      };
      const callAsyncHook = (hook, args) => {
          const done = args[1];
          callHook(hook, args);
          if (isArray(hook)) {
              if (hook.every(hook => hook.length <= 1))
                  done();
          }
          else if (hook.length <= 1) {
              done();
          }
      };
      const hooks = {
          mode,
          persisted,
          beforeEnter(el) {
              let hook = onBeforeEnter;
              if (!state.isMounted) {
                  if (appear) {
                      hook = onBeforeAppear || onBeforeEnter;
                  }
                  else {
                      return;
                  }
              }
              // for same element (v-show)
              if (el._leaveCb) {
                  el._leaveCb(true /* cancelled */);
              }
              // for toggled element with same key (v-if)
              const leavingVNode = leavingVNodesCache[key];
              if (leavingVNode &&
                  isSameVNodeType(vnode, leavingVNode) &&
                  leavingVNode.el._leaveCb) {
                  // force early removal (not cancelled)
                  leavingVNode.el._leaveCb();
              }
              callHook(hook, [el]);
          },
          enter(el) {
              let hook = onEnter;
              let afterHook = onAfterEnter;
              let cancelHook = onEnterCancelled;
              if (!state.isMounted) {
                  if (appear) {
                      hook = onAppear || onEnter;
                      afterHook = onAfterAppear || onAfterEnter;
                      cancelHook = onAppearCancelled || onEnterCancelled;
                  }
                  else {
                      return;
                  }
              }
              let called = false;
              const done = (el._enterCb = (cancelled) => {
                  if (called)
                      return;
                  called = true;
                  if (cancelled) {
                      callHook(cancelHook, [el]);
                  }
                  else {
                      callHook(afterHook, [el]);
                  }
                  if (hooks.delayedLeave) {
                      hooks.delayedLeave();
                  }
                  el._enterCb = undefined;
              });
              if (hook) {
                  callAsyncHook(hook, [el, done]);
              }
              else {
                  done();
              }
          },
          leave(el, remove) {
              const key = String(vnode.key);
              if (el._enterCb) {
                  el._enterCb(true /* cancelled */);
              }
              if (state.isUnmounting) {
                  return remove();
              }
              callHook(onBeforeLeave, [el]);
              let called = false;
              const done = (el._leaveCb = (cancelled) => {
                  if (called)
                      return;
                  called = true;
                  remove();
                  if (cancelled) {
                      callHook(onLeaveCancelled, [el]);
                  }
                  else {
                      callHook(onAfterLeave, [el]);
                  }
                  el._leaveCb = undefined;
                  if (leavingVNodesCache[key] === vnode) {
                      delete leavingVNodesCache[key];
                  }
              });
              leavingVNodesCache[key] = vnode;
              if (onLeave) {
                  callAsyncHook(onLeave, [el, done]);
              }
              else {
                  done();
              }
          },
          clone(vnode) {
              return resolveTransitionHooks(vnode, props, state, instance);
          }
      };
      return hooks;
  }
  // the placeholder really only handles one special case: KeepAlive
  // in the case of a KeepAlive in a leave phase we need to return a KeepAlive
  // placeholder with empty content to avoid the KeepAlive instance from being
  // unmounted.
  function emptyPlaceholder(vnode) {
      if (isKeepAlive(vnode)) {
          vnode = cloneVNode(vnode);
          vnode.children = null;
          return vnode;
      }
  }
  function getKeepAliveChild(vnode) {
      return isKeepAlive(vnode)
          ? vnode.children
              ? vnode.children[0]
              : undefined
          : vnode;
  }
  function setTransitionHooks(vnode, hooks) {
      if (vnode.shapeFlag & 6 /* COMPONENT */ && vnode.component) {
          setTransitionHooks(vnode.component.subTree, hooks);
      }
      else if (vnode.shapeFlag & 128 /* SUSPENSE */) {
          vnode.ssContent.transition = hooks.clone(vnode.ssContent);
          vnode.ssFallback.transition = hooks.clone(vnode.ssFallback);
      }
      else {
          vnode.transition = hooks;
      }
  }
  function getTransitionRawChildren(children, keepComment = false, parentKey) {
      let ret = [];
      let keyedFragmentCount = 0;
      for (let i = 0; i < children.length; i++) {
          let child = children[i];
          // #5360 inherit parent key in case of <template v-for>
          const key = parentKey == null
              ? child.key
              : String(parentKey) + String(child.key != null ? child.key : i);
          // handle fragment children case, e.g. v-for
          if (child.type === Fragment) {
              if (child.patchFlag & 128 /* KEYED_FRAGMENT */)
                  keyedFragmentCount++;
              ret = ret.concat(getTransitionRawChildren(child.children, keepComment, key));
          }
          // comment placeholders should be skipped, e.g. v-if
          else if (keepComment || child.type !== Comment) {
              ret.push(key != null ? cloneVNode(child, { key }) : child);
          }
      }
      // #1126 if a transition children list contains multiple sub fragments, these
      // fragments will be merged into a flat children array. Since each v-for
      // fragment may contain different static bindings inside, we need to de-op
      // these children to force full diffs to ensure correct behavior.
      if (keyedFragmentCount > 1) {
          for (let i = 0; i < ret.length; i++) {
              ret[i].patchFlag = -2 /* BAIL */;
          }
      }
      return ret;
  }

  // implementation, close to no-op
  function defineComponent(options) {
      return isFunction(options) ? { setup: options, name: options.name } : options;
  }

  const isAsyncWrapper = (i) => !!i.type.__asyncLoader;
  function defineAsyncComponent(source) {
      if (isFunction(source)) {
          source = { loader: source };
      }
      const { loader, loadingComponent, errorComponent, delay = 200, timeout, // undefined = never times out
      suspensible = true, onError: userOnError } = source;
      let pendingRequest = null;
      let resolvedComp;
      let retries = 0;
      const retry = () => {
          retries++;
          pendingRequest = null;
          return load();
      };
      const load = () => {
          let thisRequest;
          return (pendingRequest ||
              (thisRequest = pendingRequest =
                  loader()
                      .catch(err => {
                      err = err instanceof Error ? err : new Error(String(err));
                      if (userOnError) {
                          return new Promise((resolve, reject) => {
                              const userRetry = () => resolve(retry());
                              const userFail = () => reject(err);
                              userOnError(err, userRetry, userFail, retries + 1);
                          });
                      }
                      else {
                          throw err;
                      }
                  })
                      .then((comp) => {
                      if (thisRequest !== pendingRequest && pendingRequest) {
                          return pendingRequest;
                      }
                      if (!comp) {
                          warn$1(`Async component loader resolved to undefined. ` +
                              `If you are using retry(), make sure to return its return value.`);
                      }
                      // interop module default
                      if (comp &&
                          (comp.__esModule || comp[Symbol.toStringTag] === 'Module')) {
                          comp = comp.default;
                      }
                      if (comp && !isObject(comp) && !isFunction(comp)) {
                          throw new Error(`Invalid async component load result: ${comp}`);
                      }
                      resolvedComp = comp;
                      return comp;
                  })));
      };
      return defineComponent({
          name: 'AsyncComponentWrapper',
          __asyncLoader: load,
          get __asyncResolved() {
              return resolvedComp;
          },
          setup() {
              const instance = currentInstance;
              // already resolved
              if (resolvedComp) {
                  return () => createInnerComp(resolvedComp, instance);
              }
              const onError = (err) => {
                  pendingRequest = null;
                  handleError(err, instance, 13 /* ASYNC_COMPONENT_LOADER */, !errorComponent /* do not throw in dev if user provided error component */);
              };
              // suspense-controlled or SSR.
              if ((suspensible && instance.suspense) ||
                  (false )) {
                  return load()
                      .then(comp => {
                      return () => createInnerComp(comp, instance);
                  })
                      .catch(err => {
                      onError(err);
                      return () => errorComponent
                          ? createVNode(errorComponent, {
                              error: err
                          })
                          : null;
                  });
              }
              const loaded = ref(false);
              const error = ref();
              const delayed = ref(!!delay);
              if (delay) {
                  setTimeout(() => {
                      delayed.value = false;
                  }, delay);
              }
              if (timeout != null) {
                  setTimeout(() => {
                      if (!loaded.value && !error.value) {
                          const err = new Error(`Async component timed out after ${timeout}ms.`);
                          onError(err);
                          error.value = err;
                      }
                  }, timeout);
              }
              load()
                  .then(() => {
                  loaded.value = true;
                  if (instance.parent && isKeepAlive(instance.parent.vnode)) {
                      // parent is keep-alive, force update so the loaded component's
                      // name is taken into account
                      queueJob(instance.parent.update);
                  }
              })
                  .catch(err => {
                  onError(err);
                  error.value = err;
              });
              return () => {
                  if (loaded.value && resolvedComp) {
                      return createInnerComp(resolvedComp, instance);
                  }
                  else if (error.value && errorComponent) {
                      return createVNode(errorComponent, {
                          error: error.value
                      });
                  }
                  else if (loadingComponent && !delayed.value) {
                      return createVNode(loadingComponent);
                  }
              };
          }
      });
  }
  function createInnerComp(comp, { vnode: { ref, props, children, shapeFlag }, parent }) {
      const vnode = createVNode(comp, props, children);
      // ensure inner component inherits the async wrapper's ref owner
      vnode.ref = ref;
      return vnode;
  }

  const isKeepAlive = (vnode) => vnode.type.__isKeepAlive;
  const KeepAliveImpl = {
      name: `KeepAlive`,
      // Marker for special handling inside the renderer. We are not using a ===
      // check directly on KeepAlive in the renderer, because importing it directly
      // would prevent it from being tree-shaken.
      __isKeepAlive: true,
      props: {
          include: [String, RegExp, Array],
          exclude: [String, RegExp, Array],
          max: [String, Number]
      },
      setup(props, { slots }) {
          const instance = getCurrentInstance();
          // KeepAlive communicates with the instantiated renderer via the
          // ctx where the renderer passes in its internals,
          // and the KeepAlive instance exposes activate/deactivate implementations.
          // The whole point of this is to avoid importing KeepAlive directly in the
          // renderer to facilitate tree-shaking.
          const sharedContext = instance.ctx;
          const cache = new Map();
          const keys = new Set();
          let current = null;
          {
              instance.__v_cache = cache;
          }
          const parentSuspense = instance.suspense;
          const { renderer: { p: patch, m: move, um: _unmount, o: { createElement } } } = sharedContext;
          const storageContainer = createElement('div');
          sharedContext.activate = (vnode, container, anchor, isSVG, optimized) => {
              const instance = vnode.component;
              move(vnode, container, anchor, 0 /* ENTER */, parentSuspense);
              // in case props have changed
              patch(instance.vnode, vnode, container, anchor, instance, parentSuspense, isSVG, vnode.slotScopeIds, optimized);
              queuePostRenderEffect(() => {
                  instance.isDeactivated = false;
                  if (instance.a) {
                      invokeArrayFns(instance.a);
                  }
                  const vnodeHook = vnode.props && vnode.props.onVnodeMounted;
                  if (vnodeHook) {
                      invokeVNodeHook(vnodeHook, instance.parent, vnode);
                  }
              }, parentSuspense);
              {
                  // Update components tree
                  devtoolsComponentAdded(instance);
              }
          };
          sharedContext.deactivate = (vnode) => {
              const instance = vnode.component;
              move(vnode, storageContainer, null, 1 /* LEAVE */, parentSuspense);
              queuePostRenderEffect(() => {
                  if (instance.da) {
                      invokeArrayFns(instance.da);
                  }
                  const vnodeHook = vnode.props && vnode.props.onVnodeUnmounted;
                  if (vnodeHook) {
                      invokeVNodeHook(vnodeHook, instance.parent, vnode);
                  }
                  instance.isDeactivated = true;
              }, parentSuspense);
              {
                  // Update components tree
                  devtoolsComponentAdded(instance);
              }
          };
          function unmount(vnode) {
              // reset the shapeFlag so it can be properly unmounted
              resetShapeFlag(vnode);
              _unmount(vnode, instance, parentSuspense, true);
          }
          function pruneCache(filter) {
              cache.forEach((vnode, key) => {
                  const name = getComponentName(vnode.type);
                  if (name && (!filter || !filter(name))) {
                      pruneCacheEntry(key);
                  }
              });
          }
          function pruneCacheEntry(key) {
              const cached = cache.get(key);
              if (!current || cached.type !== current.type) {
                  unmount(cached);
              }
              else if (current) {
                  // current active instance should no longer be kept-alive.
                  // we can't unmount it now but it might be later, so reset its flag now.
                  resetShapeFlag(current);
              }
              cache.delete(key);
              keys.delete(key);
          }
          // prune cache on include/exclude prop change
          watch(() => [props.include, props.exclude], ([include, exclude]) => {
              include && pruneCache(name => matches(include, name));
              exclude && pruneCache(name => !matches(exclude, name));
          }, 
          // prune post-render after `current` has been updated
          { flush: 'post', deep: true });
          // cache sub tree after render
          let pendingCacheKey = null;
          const cacheSubtree = () => {
              // fix #1621, the pendingCacheKey could be 0
              if (pendingCacheKey != null) {
                  cache.set(pendingCacheKey, getInnerChild(instance.subTree));
              }
          };
          onMounted(cacheSubtree);
          onUpdated(cacheSubtree);
          onBeforeUnmount(() => {
              cache.forEach(cached => {
                  const { subTree, suspense } = instance;
                  const vnode = getInnerChild(subTree);
                  if (cached.type === vnode.type) {
                      // current instance will be unmounted as part of keep-alive's unmount
                      resetShapeFlag(vnode);
                      // but invoke its deactivated hook here
                      const da = vnode.component.da;
                      da && queuePostRenderEffect(da, suspense);
                      return;
                  }
                  unmount(cached);
              });
          });
          return () => {
              pendingCacheKey = null;
              if (!slots.default) {
                  return null;
              }
              const children = slots.default();
              const rawVNode = children[0];
              if (children.length > 1) {
                  {
                      warn$1(`KeepAlive should contain exactly one component child.`);
                  }
                  current = null;
                  return children;
              }
              else if (!isVNode(rawVNode) ||
                  (!(rawVNode.shapeFlag & 4 /* STATEFUL_COMPONENT */) &&
                      !(rawVNode.shapeFlag & 128 /* SUSPENSE */))) {
                  current = null;
                  return rawVNode;
              }
              let vnode = getInnerChild(rawVNode);
              const comp = vnode.type;
              // for async components, name check should be based in its loaded
              // inner component if available
              const name = getComponentName(isAsyncWrapper(vnode)
                  ? vnode.type.__asyncResolved || {}
                  : comp);
              const { include, exclude, max } = props;
              if ((include && (!name || !matches(include, name))) ||
                  (exclude && name && matches(exclude, name))) {
                  current = vnode;
                  return rawVNode;
              }
              const key = vnode.key == null ? comp : vnode.key;
              const cachedVNode = cache.get(key);
              // clone vnode if it's reused because we are going to mutate it
              if (vnode.el) {
                  vnode = cloneVNode(vnode);
                  if (rawVNode.shapeFlag & 128 /* SUSPENSE */) {
                      rawVNode.ssContent = vnode;
                  }
              }
              // #1513 it's possible for the returned vnode to be cloned due to attr
              // fallthrough or scopeId, so the vnode here may not be the final vnode
              // that is mounted. Instead of caching it directly, we store the pending
              // key and cache `instance.subTree` (the normalized vnode) in
              // beforeMount/beforeUpdate hooks.
              pendingCacheKey = key;
              if (cachedVNode) {
                  // copy over mounted state
                  vnode.el = cachedVNode.el;
                  vnode.component = cachedVNode.component;
                  if (vnode.transition) {
                      // recursively update transition hooks on subTree
                      setTransitionHooks(vnode, vnode.transition);
                  }
                  // avoid vnode being mounted as fresh
                  vnode.shapeFlag |= 512 /* COMPONENT_KEPT_ALIVE */;
                  // make this key the freshest
                  keys.delete(key);
                  keys.add(key);
              }
              else {
                  keys.add(key);
                  // prune oldest entry
                  if (max && keys.size > parseInt(max, 10)) {
                      pruneCacheEntry(keys.values().next().value);
                  }
              }
              // avoid vnode being unmounted
              vnode.shapeFlag |= 256 /* COMPONENT_SHOULD_KEEP_ALIVE */;
              current = vnode;
              return isSuspense(rawVNode.type) ? rawVNode : vnode;
          };
      }
  };
  {
      KeepAliveImpl.__isBuildIn = true;
  }
  // export the public type for h/tsx inference
  // also to avoid inline import() in generated d.ts files
  const KeepAlive = KeepAliveImpl;
  function matches(pattern, name) {
      if (isArray(pattern)) {
          return pattern.some((p) => matches(p, name));
      }
      else if (isString(pattern)) {
          return pattern.split(',').includes(name);
      }
      else if (pattern.test) {
          return pattern.test(name);
      }
      /* istanbul ignore next */
      return false;
  }
  function onActivated(hook, target) {
      registerKeepAliveHook(hook, "a" /* ACTIVATED */, target);
  }
  function onDeactivated(hook, target) {
      registerKeepAliveHook(hook, "da" /* DEACTIVATED */, target);
  }
  function registerKeepAliveHook(hook, type, target = currentInstance) {
      // cache the deactivate branch check wrapper for injected hooks so the same
      // hook can be properly deduped by the scheduler. "__wdc" stands for "with
      // deactivation check".
      const wrappedHook = hook.__wdc ||
          (hook.__wdc = () => {
              // only fire the hook if the target instance is NOT in a deactivated branch.
              let current = target;
              while (current) {
                  if (current.isDeactivated) {
                      return;
                  }
                  current = current.parent;
              }
              return hook();
          });
      injectHook(type, wrappedHook, target);
      // In addition to registering it on the target instance, we walk up the parent
      // chain and register it on all ancestor instances that are keep-alive roots.
      // This avoids the need to walk the entire component tree when invoking these
      // hooks, and more importantly, avoids the need to track child components in
      // arrays.
      if (target) {
          let current = target.parent;
          while (current && current.parent) {
              if (isKeepAlive(current.parent.vnode)) {
                  injectToKeepAliveRoot(wrappedHook, type, target, current);
              }
              current = current.parent;
          }
      }
  }
  function injectToKeepAliveRoot(hook, type, target, keepAliveRoot) {
      // injectHook wraps the original for error handling, so make sure to remove
      // the wrapped version.
      const injected = injectHook(type, hook, keepAliveRoot, true /* prepend */);
      onUnmounted(() => {
          remove(keepAliveRoot[type], injected);
      }, target);
  }
  function resetShapeFlag(vnode) {
      let shapeFlag = vnode.shapeFlag;
      if (shapeFlag & 256 /* COMPONENT_SHOULD_KEEP_ALIVE */) {
          shapeFlag -= 256 /* COMPONENT_SHOULD_KEEP_ALIVE */;
      }
      if (shapeFlag & 512 /* COMPONENT_KEPT_ALIVE */) {
          shapeFlag -= 512 /* COMPONENT_KEPT_ALIVE */;
      }
      vnode.shapeFlag = shapeFlag;
  }
  function getInnerChild(vnode) {
      return vnode.shapeFlag & 128 /* SUSPENSE */ ? vnode.ssContent : vnode;
  }

  function injectHook(type, hook, target = currentInstance, prepend = false) {
      if (target) {
          const hooks = target[type] || (target[type] = []);
          // cache the error handling wrapper for injected hooks so the same hook
          // can be properly deduped by the scheduler. "__weh" stands for "with error
          // handling".
          const wrappedHook = hook.__weh ||
              (hook.__weh = (...args) => {
                  if (target.isUnmounted) {
                      return;
                  }
                  // disable tracking inside all lifecycle hooks
                  // since they can potentially be called inside effects.
                  pauseTracking();
                  // Set currentInstance during hook invocation.
                  // This assumes the hook does not synchronously trigger other hooks, which
                  // can only be false when the user does something really funky.
                  setCurrentInstance(target);
                  const res = callWithAsyncErrorHandling(hook, target, type, args);
                  unsetCurrentInstance();
                  resetTracking();
                  return res;
              });
          if (prepend) {
              hooks.unshift(wrappedHook);
          }
          else {
              hooks.push(wrappedHook);
          }
          return wrappedHook;
      }
      else {
          const apiName = toHandlerKey(ErrorTypeStrings[type].replace(/ hook$/, ''));
          warn$1(`${apiName} is called when there is no active component instance to be ` +
              `associated with. ` +
              `Lifecycle injection APIs can only be used during execution of setup().` +
              (` If you are using async setup(), make sure to register lifecycle ` +
                      `hooks before the first await statement.`
                  ));
      }
  }
  const createHook = (lifecycle) => (hook, target = currentInstance) => 
  // post-create lifecycle registrations are noops during SSR (except for serverPrefetch)
  (!isInSSRComponentSetup || lifecycle === "sp" /* SERVER_PREFETCH */) &&
      injectHook(lifecycle, hook, target);
  const onBeforeMount = createHook("bm" /* BEFORE_MOUNT */);
  const onMounted = createHook("m" /* MOUNTED */);
  const onBeforeUpdate = createHook("bu" /* BEFORE_UPDATE */);
  const onUpdated = createHook("u" /* UPDATED */);
  const onBeforeUnmount = createHook("bum" /* BEFORE_UNMOUNT */);
  const onUnmounted = createHook("um" /* UNMOUNTED */);
  const onServerPrefetch = createHook("sp" /* SERVER_PREFETCH */);
  const onRenderTriggered = createHook("rtg" /* RENDER_TRIGGERED */);
  const onRenderTracked = createHook("rtc" /* RENDER_TRACKED */);
  function onErrorCaptured(hook, target = currentInstance) {
      injectHook("ec" /* ERROR_CAPTURED */, hook, target);
  }

  function getCompatChildren(instance) {
      assertCompatEnabled("INSTANCE_CHILDREN" /* INSTANCE_CHILDREN */, instance);
      const root = instance.subTree;
      const children = [];
      if (root) {
          walk(root, children);
      }
      return children;
  }
  function walk(vnode, children) {
      if (vnode.component) {
          children.push(vnode.component.proxy);
      }
      else if (vnode.shapeFlag & 16 /* ARRAY_CHILDREN */) {
          const vnodes = vnode.children;
          for (let i = 0; i < vnodes.length; i++) {
              walk(vnodes[i], children);
          }
      }
  }

  function getCompatListeners(instance) {
      assertCompatEnabled("INSTANCE_LISTENERS" /* INSTANCE_LISTENERS */, instance);
      const listeners = {};
      const rawProps = instance.vnode.props;
      if (!rawProps) {
          return listeners;
      }
      for (const key in rawProps) {
          if (isOn(key)) {
              listeners[key[2].toLowerCase() + key.slice(3)] = rawProps[key];
          }
      }
      return listeners;
  }

  const legacyDirectiveHookMap = {
      beforeMount: 'bind',
      mounted: 'inserted',
      updated: ['update', 'componentUpdated'],
      unmounted: 'unbind'
  };
  function mapCompatDirectiveHook(name, dir, instance) {
      const mappedName = legacyDirectiveHookMap[name];
      if (mappedName) {
          if (isArray(mappedName)) {
              const hook = [];
              mappedName.forEach(mapped => {
                  const mappedHook = dir[mapped];
                  if (mappedHook) {
                      softAssertCompatEnabled("CUSTOM_DIR" /* CUSTOM_DIR */, instance, mapped, name);
                      hook.push(mappedHook);
                  }
              });
              return hook.length ? hook : undefined;
          }
          else {
              if (dir[mappedName]) {
                  softAssertCompatEnabled("CUSTOM_DIR" /* CUSTOM_DIR */, instance, mappedName, name);
              }
              return dir[mappedName];
          }
      }
  }

  /**
  Runtime helper for applying directives to a vnode. Example usage:

  const comp = resolveComponent('comp')
  const foo = resolveDirective('foo')
  const bar = resolveDirective('bar')

  return withDirectives(h(comp), [
    [foo, this.x],
    [bar, this.y]
  ])
  */
  function validateDirectiveName(name) {
      if (isBuiltInDirective(name)) {
          warn$1('Do not use built-in directive ids as custom directive id: ' + name);
      }
  }
  /**
   * Adds directives to a VNode.
   */
  function withDirectives(vnode, directives) {
      const internalInstance = currentRenderingInstance;
      if (internalInstance === null) {
          warn$1(`withDirectives can only be used inside render functions.`);
          return vnode;
      }
      const instance = getExposeProxy(internalInstance) ||
          internalInstance.proxy;
      const bindings = vnode.dirs || (vnode.dirs = []);
      for (let i = 0; i < directives.length; i++) {
          let [dir, value, arg, modifiers = EMPTY_OBJ] = directives[i];
          if (isFunction(dir)) {
              dir = {
                  mounted: dir,
                  updated: dir
              };
          }
          if (dir.deep) {
              traverse(value);
          }
          bindings.push({
              dir,
              instance,
              value,
              oldValue: void 0,
              arg,
              modifiers
          });
      }
      return vnode;
  }
  function invokeDirectiveHook(vnode, prevVNode, instance, name) {
      const bindings = vnode.dirs;
      const oldBindings = prevVNode && prevVNode.dirs;
      for (let i = 0; i < bindings.length; i++) {
          const binding = bindings[i];
          if (oldBindings) {
              binding.oldValue = oldBindings[i].value;
          }
          let hook = binding.dir[name];
          if (!hook) {
              hook = mapCompatDirectiveHook(name, binding.dir, instance);
          }
          if (hook) {
              // disable tracking inside all lifecycle hooks
              // since they can potentially be called inside effects.
              pauseTracking();
              callWithAsyncErrorHandling(hook, instance, 8 /* DIRECTIVE_HOOK */, [
                  vnode.el,
                  binding,
                  vnode,
                  prevVNode
              ]);
              resetTracking();
          }
      }
  }

  const COMPONENTS = 'components';
  const DIRECTIVES = 'directives';
  const FILTERS = 'filters';
  /**
   * @private
   */
  function resolveComponent(name, maybeSelfReference) {
      return resolveAsset(COMPONENTS, name, true, maybeSelfReference) || name;
  }
  const NULL_DYNAMIC_COMPONENT = Symbol();
  /**
   * @private
   */
  function resolveDynamicComponent(component) {
      if (isString(component)) {
          return resolveAsset(COMPONENTS, component, false) || component;
      }
      else {
          // invalid types will fallthrough to createVNode and raise warning
          return (component || NULL_DYNAMIC_COMPONENT);
      }
  }
  /**
   * @private
   */
  function resolveDirective(name) {
      return resolveAsset(DIRECTIVES, name);
  }
  /**
   * v2 compat only
   * @internal
   */
  function resolveFilter(name) {
      return resolveAsset(FILTERS, name);
  }
  // implementation
  function resolveAsset(type, name, warnMissing = true, maybeSelfReference = false) {
      const instance = currentRenderingInstance || currentInstance;
      if (instance) {
          const Component = instance.type;
          // explicit self name has highest priority
          if (type === COMPONENTS) {
              const selfName = getComponentName(Component, false /* do not include inferred name to avoid breaking existing code */);
              if (selfName &&
                  (selfName === name ||
                      selfName === camelize(name) ||
                      selfName === capitalize(camelize(name)))) {
                  return Component;
              }
          }
          const res = 
          // local registration
          // check instance[type] first which is resolved for options API
          resolve(instance[type] || Component[type], name) ||
              // global registration
              resolve(instance.appContext[type], name);
          if (!res && maybeSelfReference) {
              // fallback to implicit self-reference
              return Component;
          }
          if (warnMissing && !res) {
              const extra = type === COMPONENTS
                  ? `\nIf this is a native custom element, make sure to exclude it from ` +
                      `component resolution via compilerOptions.isCustomElement.`
                  : ``;
              warn$1(`Failed to resolve ${type.slice(0, -1)}: ${name}${extra}`);
          }
          return res;
      }
      else {
          warn$1(`resolve${capitalize(type.slice(0, -1))} ` +
              `can only be used in render() or setup().`);
      }
  }
  function resolve(registry, name) {
      return (registry &&
          (registry[name] ||
              registry[camelize(name)] ||
              registry[capitalize(camelize(name))]));
  }

  function convertLegacyRenderFn(instance) {
      const Component = instance.type;
      const render = Component.render;
      // v3 runtime compiled, or already checked / wrapped
      if (!render || render._rc || render._compatChecked || render._compatWrapped) {
          return;
      }
      if (render.length >= 2) {
          // v3 pre-compiled function, since v2 render functions never need more than
          // 2 arguments, and v2 functional render functions would have already been
          // normalized into v3 functional components
          render._compatChecked = true;
          return;
      }
      // v2 render function, try to provide compat
      if (checkCompatEnabled("RENDER_FUNCTION" /* RENDER_FUNCTION */, instance)) {
          const wrapped = (Component.render = function compatRender() {
              // @ts-ignore
              return render.call(this, compatH);
          });
          // @ts-ignore
          wrapped._compatWrapped = true;
      }
  }
  function compatH(type, propsOrChildren, children) {
      if (!type) {
          type = Comment;
      }
      // to support v2 string component name look!up
      if (typeof type === 'string') {
          const t = hyphenate(type);
          if (t === 'transition' || t === 'transition-group' || t === 'keep-alive') {
              // since transition and transition-group are runtime-dom-specific,
              // we cannot import them directly here. Instead they are registered using
              // special keys in @vue/compat entry.
              type = `__compat__${t}`;
          }
          type = resolveDynamicComponent(type);
      }
      const l = arguments.length;
      const is2ndArgArrayChildren = isArray(propsOrChildren);
      if (l === 2 || is2ndArgArrayChildren) {
          if (isObject(propsOrChildren) && !is2ndArgArrayChildren) {
              // single vnode without props
              if (isVNode(propsOrChildren)) {
                  return convertLegacySlots(createVNode(type, null, [propsOrChildren]));
              }
              // props without children
              return convertLegacySlots(convertLegacyDirectives(createVNode(type, convertLegacyProps(propsOrChildren, type)), propsOrChildren));
          }
          else {
              // omit props
              return convertLegacySlots(createVNode(type, null, propsOrChildren));
          }
      }
      else {
          if (isVNode(children)) {
              children = [children];
          }
          return convertLegacySlots(convertLegacyDirectives(createVNode(type, convertLegacyProps(propsOrChildren, type), children), propsOrChildren));
      }
  }
  const skipLegacyRootLevelProps = /*#__PURE__*/ makeMap('staticStyle,staticClass,directives,model,hook');
  function convertLegacyProps(legacyProps, type) {
      if (!legacyProps) {
          return null;
      }
      const converted = {};
      for (const key in legacyProps) {
          if (key === 'attrs' || key === 'domProps' || key === 'props') {
              extend(converted, legacyProps[key]);
          }
          else if (key === 'on' || key === 'nativeOn') {
              const listeners = legacyProps[key];
              for (const event in listeners) {
                  let handlerKey = convertLegacyEventKey(event);
                  if (key === 'nativeOn')
                      handlerKey += `Native`;
                  const existing = converted[handlerKey];
                  const incoming = listeners[event];
                  if (existing !== incoming) {
                      if (existing) {
                          converted[handlerKey] = [].concat(existing, incoming);
                      }
                      else {
                          converted[handlerKey] = incoming;
                      }
                  }
              }
          }
          else if (!skipLegacyRootLevelProps(key)) {
              converted[key] = legacyProps[key];
          }
      }
      if (legacyProps.staticClass) {
          converted.class = normalizeClass([legacyProps.staticClass, converted.class]);
      }
      if (legacyProps.staticStyle) {
          converted.style = normalizeStyle([legacyProps.staticStyle, converted.style]);
      }
      if (legacyProps.model && isObject(type)) {
          // v2 compiled component v-model
          const { prop = 'value', event = 'input' } = type.model || {};
          converted[prop] = legacyProps.model.value;
          converted[compatModelEventPrefix + event] = legacyProps.model.callback;
      }
      return converted;
  }
  function convertLegacyEventKey(event) {
      // normalize v2 event prefixes
      if (event[0] === '&') {
          event = event.slice(1) + 'Passive';
      }
      if (event[0] === '~') {
          event = event.slice(1) + 'Once';
      }
      if (event[0] === '!') {
          event = event.slice(1) + 'Capture';
      }
      return toHandlerKey(event);
  }
  function convertLegacyDirectives(vnode, props) {
      if (props && props.directives) {
          return withDirectives(vnode, props.directives.map(({ name, value, arg, modifiers }) => {
              return [
                  resolveDirective(name),
                  value,
                  arg,
                  modifiers
              ];
          }));
      }
      return vnode;
  }
  function convertLegacySlots(vnode) {
      const { props, children } = vnode;
      let slots;
      if (vnode.shapeFlag & 6 /* COMPONENT */ && isArray(children)) {
          slots = {};
          // check "slot" property on vnodes and turn them into v3 function slots
          for (let i = 0; i < children.length; i++) {
              const child = children[i];
              const slotName = (isVNode(child) && child.props && child.props.slot) || 'default';
              const slot = slots[slotName] || (slots[slotName] = []);
              if (isVNode(child) && child.type === 'template') {
                  slot.push(child.children);
              }
              else {
                  slot.push(child);
              }
          }
          if (slots) {
              for (const key in slots) {
                  const slotChildren = slots[key];
                  slots[key] = () => slotChildren;
                  slots[key]._ns = true; /* non-scoped slot */
              }
          }
      }
      const scopedSlots = props && props.scopedSlots;
      if (scopedSlots) {
          delete props.scopedSlots;
          if (slots) {
              extend(slots, scopedSlots);
          }
          else {
              slots = scopedSlots;
          }
      }
      if (slots) {
          normalizeChildren(vnode, slots);
      }
      return vnode;
  }
  function defineLegacyVNodeProperties(vnode) {
      /* istanbul ignore if */
      if (isCompatEnabled("RENDER_FUNCTION" /* RENDER_FUNCTION */, currentRenderingInstance, true /* enable for built-ins */) &&
          isCompatEnabled("PRIVATE_APIS" /* PRIVATE_APIS */, currentRenderingInstance, true /* enable for built-ins */)) {
          const context = currentRenderingInstance;
          const getInstance = () => vnode.component && vnode.component.proxy;
          let componentOptions;
          Object.defineProperties(vnode, {
              tag: { get: () => vnode.type },
              data: { get: () => vnode.props || {}, set: p => (vnode.props = p) },
              elm: { get: () => vnode.el },
              componentInstance: { get: getInstance },
              child: { get: getInstance },
              text: { get: () => (isString(vnode.children) ? vnode.children : null) },
              context: { get: () => context && context.proxy },
              componentOptions: {
                  get: () => {
                      if (vnode.shapeFlag & 4 /* STATEFUL_COMPONENT */) {
                          if (componentOptions) {
                              return componentOptions;
                          }
                          return (componentOptions = {
                              Ctor: vnode.type,
                              propsData: vnode.props,
                              children: vnode.children
                          });
                      }
                  }
              }
          });
      }
  }

  const normalizedFunctionalComponentMap = new Map();
  const legacySlotProxyHandlers = {
      get(target, key) {
          const slot = target[key];
          return slot && slot();
      }
  };
  function convertLegacyFunctionalComponent(comp) {
      if (normalizedFunctionalComponentMap.has(comp)) {
          return normalizedFunctionalComponentMap.get(comp);
      }
      const legacyFn = comp.render;
      const Func = (props, ctx) => {
          const instance = getCurrentInstance();
          const legacyCtx = {
              props,
              children: instance.vnode.children || [],
              data: instance.vnode.props || {},
              scopedSlots: ctx.slots,
              parent: instance.parent && instance.parent.proxy,
              slots() {
                  return new Proxy(ctx.slots, legacySlotProxyHandlers);
              },
              get listeners() {
                  return getCompatListeners(instance);
              },
              get injections() {
                  if (comp.inject) {
                      const injections = {};
                      resolveInjections(comp.inject, injections);
                      return injections;
                  }
                  return {};
              }
          };
          return legacyFn(compatH, legacyCtx);
      };
      Func.props = comp.props;
      Func.displayName = comp.name;
      Func.compatConfig = comp.compatConfig;
      // v2 functional components do not inherit attrs
      Func.inheritAttrs = false;
      normalizedFunctionalComponentMap.set(comp, Func);
      return Func;
  }

  /**
   * Actual implementation
   */
  function renderList(source, renderItem, cache, index) {
      let ret;
      const cached = (cache && cache[index]);
      if (isArray(source) || isString(source)) {
          ret = new Array(source.length);
          for (let i = 0, l = source.length; i < l; i++) {
              ret[i] = renderItem(source[i], i, undefined, cached && cached[i]);
          }
      }
      else if (typeof source === 'number') {
          if (!Number.isInteger(source)) {
              warn$1(`The v-for range expect an integer value but got ${source}.`);
          }
          ret = new Array(source);
          for (let i = 0; i < source; i++) {
              ret[i] = renderItem(i + 1, i, undefined, cached && cached[i]);
          }
      }
      else if (isObject(source)) {
          if (source[Symbol.iterator]) {
              ret = Array.from(source, (item, i) => renderItem(item, i, undefined, cached && cached[i]));
          }
          else {
              const keys = Object.keys(source);
              ret = new Array(keys.length);
              for (let i = 0, l = keys.length; i < l; i++) {
                  const key = keys[i];
                  ret[i] = renderItem(source[key], key, i, cached && cached[i]);
              }
          }
      }
      else {
          ret = [];
      }
      if (cache) {
          cache[index] = ret;
      }
      return ret;
  }

  /**
   * Compiler runtime helper for creating dynamic slots object
   * @private
   */
  function createSlots(slots, dynamicSlots) {
      for (let i = 0; i < dynamicSlots.length; i++) {
          const slot = dynamicSlots[i];
          // array of dynamic slot generated by <template v-for="..." #[...]>
          if (isArray(slot)) {
              for (let j = 0; j < slot.length; j++) {
                  slots[slot[j].name] = slot[j].fn;
              }
          }
          else if (slot) {
              // conditional single slot generated by <template v-if="..." #foo>
              slots[slot.name] = slot.fn;
          }
      }
      return slots;
  }

  /**
   * Compiler runtime helper for rendering `<slot/>`
   * @private
   */
  function renderSlot(slots, name, props = {}, 
  // this is not a user-facing function, so the fallback is always generated by
  // the compiler and guaranteed to be a function returning an array
  fallback, noSlotted) {
      if (currentRenderingInstance.isCE ||
          (currentRenderingInstance.parent &&
              isAsyncWrapper(currentRenderingInstance.parent) &&
              currentRenderingInstance.parent.isCE)) {
          return createVNode('slot', name === 'default' ? null : { name }, fallback && fallback());
      }
      let slot = slots[name];
      if (slot && slot.length > 1) {
          warn$1(`SSR-optimized slot function detected in a non-SSR-optimized render ` +
              `function. You need to mark this component with $dynamic-slots in the ` +
              `parent template.`);
          slot = () => [];
      }
      // a compiled slot disables block tracking by default to avoid manual
      // invocation interfering with template-based block tracking, but in
      // `renderSlot` we can be sure that it's template-based so we can force
      // enable it.
      if (slot && slot._c) {
          slot._d = false;
      }
      openBlock();
      const validSlotContent = slot && ensureValidVNode(slot(props));
      const rendered = createBlock(Fragment, { key: props.key || `_${name}` }, validSlotContent || (fallback ? fallback() : []), validSlotContent && slots._ === 1 /* STABLE */
          ? 64 /* STABLE_FRAGMENT */
          : -2 /* BAIL */);
      if (!noSlotted && rendered.scopeId) {
          rendered.slotScopeIds = [rendered.scopeId + '-s'];
      }
      if (slot && slot._c) {
          slot._d = true;
      }
      return rendered;
  }
  function ensureValidVNode(vnodes) {
      return vnodes.some(child => {
          if (!isVNode(child))
              return true;
          if (child.type === Comment)
              return false;
          if (child.type === Fragment &&
              !ensureValidVNode(child.children))
              return false;
          return true;
      })
          ? vnodes
          : null;
  }

  /**
   * For prefixing keys in v-on="obj" with "on"
   * @private
   */
  function toHandlers(obj) {
      const ret = {};
      if (!isObject(obj)) {
          warn$1(`v-on with no argument expects an object value.`);
          return ret;
      }
      for (const key in obj) {
          ret[toHandlerKey(key)] = obj[key];
      }
      return ret;
  }

  function toObject(arr) {
      const res = {};
      for (let i = 0; i < arr.length; i++) {
          if (arr[i]) {
              extend(res, arr[i]);
          }
      }
      return res;
  }
  function legacyBindObjectProps(data, _tag, value, _asProp, isSync) {
      if (value && isObject(value)) {
          if (isArray(value)) {
              value = toObject(value);
          }
          for (const key in value) {
              if (isReservedProp(key)) {
                  data[key] = value[key];
              }
              else if (key === 'class') {
                  data.class = normalizeClass([data.class, value.class]);
              }
              else if (key === 'style') {
                  data.style = normalizeClass([data.style, value.style]);
              }
              else {
                  const attrs = data.attrs || (data.attrs = {});
                  const camelizedKey = camelize(key);
                  const hyphenatedKey = hyphenate(key);
                  if (!(camelizedKey in attrs) && !(hyphenatedKey in attrs)) {
                      attrs[key] = value[key];
                      if (isSync) {
                          const on = data.on || (data.on = {});
                          on[`update:${key}`] = function ($event) {
                              value[key] = $event;
                          };
                      }
                  }
              }
          }
      }
      return data;
  }
  function legacyBindObjectListeners(props, listeners) {
      return mergeProps(props, toHandlers(listeners));
  }
  function legacyRenderSlot(instance, name, fallback, props, bindObject) {
      if (bindObject) {
          props = mergeProps(props, bindObject);
      }
      return renderSlot(instance.slots, name, props, fallback && (() => fallback));
  }
  function legacyresolveScopedSlots(fns, raw, 
  // the following are added in 2.6
  hasDynamicKeys) {
      // v2 default slot doesn't have name
      return createSlots(raw || { $stable: !hasDynamicKeys }, mapKeyToName(fns));
  }
  function mapKeyToName(slots) {
      for (let i = 0; i < slots.length; i++) {
          const fn = slots[i];
          if (fn) {
              if (isArray(fn)) {
                  mapKeyToName(fn);
              }
              else {
                  fn.name = fn.key || 'default';
              }
          }
      }
      return slots;
  }
  const staticCacheMap = /*#__PURE__*/ new WeakMap();
  function legacyRenderStatic(instance, index) {
      let cache = staticCacheMap.get(instance);
      if (!cache) {
          staticCacheMap.set(instance, (cache = []));
      }
      if (cache[index]) {
          return cache[index];
      }
      const fn = instance.type.staticRenderFns[index];
      const ctx = instance.proxy;
      return (cache[index] = fn.call(ctx, null, ctx));
  }
  function legacyCheckKeyCodes(instance, eventKeyCode, key, builtInKeyCode, eventKeyName, builtInKeyName) {
      const config = instance.appContext.config;
      const configKeyCodes = config.keyCodes || {};
      const mappedKeyCode = configKeyCodes[key] || builtInKeyCode;
      if (builtInKeyName && eventKeyName && !configKeyCodes[key]) {
          return isKeyNotMatch(builtInKeyName, eventKeyName);
      }
      else if (mappedKeyCode) {
          return isKeyNotMatch(mappedKeyCode, eventKeyCode);
      }
      else if (eventKeyName) {
          return hyphenate(eventKeyName) !== key;
      }
  }
  function isKeyNotMatch(expect, actual) {
      if (isArray(expect)) {
          return !expect.includes(actual);
      }
      else {
          return expect !== actual;
      }
  }
  function legacyMarkOnce(tree) {
      return tree;
  }
  function legacyBindDynamicKeys(props, values) {
      for (let i = 0; i < values.length; i += 2) {
          const key = values[i];
          if (typeof key === 'string' && key) {
              props[values[i]] = values[i + 1];
          }
      }
      return props;
  }
  function legacyPrependModifier(value, symbol) {
      return typeof value === 'string' ? symbol + value : value;
  }

  function installCompatInstanceProperties(map) {
      const set = (target, key, val) => {
          target[key] = val;
      };
      const del = (target, key) => {
          delete target[key];
      };
      extend(map, {
          $set: i => {
              assertCompatEnabled("INSTANCE_SET" /* INSTANCE_SET */, i);
              return set;
          },
          $delete: i => {
              assertCompatEnabled("INSTANCE_DELETE" /* INSTANCE_DELETE */, i);
              return del;
          },
          $mount: i => {
              assertCompatEnabled("GLOBAL_MOUNT" /* GLOBAL_MOUNT */, null /* this warning is global */);
              // root mount override from ./global.ts in installCompatMount
              return i.ctx._compat_mount || NOOP;
          },
          $destroy: i => {
              assertCompatEnabled("INSTANCE_DESTROY" /* INSTANCE_DESTROY */, i);
              // root destroy override from ./global.ts in installCompatMount
              return i.ctx._compat_destroy || NOOP;
          },
          // overrides existing accessor
          $slots: i => {
              if (isCompatEnabled("RENDER_FUNCTION" /* RENDER_FUNCTION */, i) &&
                  i.render &&
                  i.render._compatWrapped) {
                  return new Proxy(i.slots, legacySlotProxyHandlers);
              }
              return shallowReadonly(i.slots) ;
          },
          $scopedSlots: i => {
              assertCompatEnabled("INSTANCE_SCOPED_SLOTS" /* INSTANCE_SCOPED_SLOTS */, i);
              const res = {};
              for (const key in i.slots) {
                  const fn = i.slots[key];
                  if (!fn._ns /* non-scoped slot */) {
                      res[key] = fn;
                  }
              }
              return res;
          },
          $on: i => on.bind(null, i),
          $once: i => once.bind(null, i),
          $off: i => off.bind(null, i),
          $children: getCompatChildren,
          $listeners: getCompatListeners
      });
      /* istanbul ignore if */
      if (isCompatEnabled("PRIVATE_APIS" /* PRIVATE_APIS */, null)) {
          extend(map, {
              // needed by many libs / render fns
              $vnode: i => i.vnode,
              // inject additional properties into $options for compat
              // e.g. vuex needs this.$options.parent
              $options: i => {
                  const res = extend({}, resolveMergedOptions(i));
                  res.parent = i.proxy.$parent;
                  res.propsData = i.vnode.props;
                  return res;
              },
              // some private properties that are likely accessed...
              _self: i => i.proxy,
              _uid: i => i.uid,
              _data: i => i.data,
              _isMounted: i => i.isMounted,
              _isDestroyed: i => i.isUnmounted,
              // v2 render helpers
              $createElement: () => compatH,
              _c: () => compatH,
              _o: () => legacyMarkOnce,
              _n: () => toNumber,
              _s: () => toDisplayString,
              _l: () => renderList,
              _t: i => legacyRenderSlot.bind(null, i),
              _q: () => looseEqual,
              _i: () => looseIndexOf,
              _m: i => legacyRenderStatic.bind(null, i),
              _f: () => resolveFilter,
              _k: i => legacyCheckKeyCodes.bind(null, i),
              _b: () => legacyBindObjectProps,
              _v: () => createTextVNode,
              _e: () => createCommentVNode,
              _u: () => legacyresolveScopedSlots,
              _g: () => legacyBindObjectListeners,
              _d: () => legacyBindDynamicKeys,
              _p: () => legacyPrependModifier
          });
      }
  }

  /**
   * #2437 In Vue 3, functional components do not have a public instance proxy but
   * they exist in the internal parent chain. For code that relies on traversing
   * public $parent chains, skip functional ones and go to the parent instead.
   */
  const getPublicInstance = (i) => {
      if (!i)
          return null;
      if (isStatefulComponent(i))
          return getExposeProxy(i) || i.proxy;
      return getPublicInstance(i.parent);
  };
  const publicPropertiesMap = 
  // Move PURE marker to new line to workaround compiler discarding it
  // due to type annotation
  /*#__PURE__*/ extend(Object.create(null), {
      $: i => i,
      $el: i => i.vnode.el,
      $data: i => i.data,
      $props: i => (shallowReadonly(i.props) ),
      $attrs: i => (shallowReadonly(i.attrs) ),
      $slots: i => (shallowReadonly(i.slots) ),
      $refs: i => (shallowReadonly(i.refs) ),
      $parent: i => getPublicInstance(i.parent),
      $root: i => getPublicInstance(i.root),
      $emit: i => i.emit,
      $options: i => (resolveMergedOptions(i) ),
      $forceUpdate: i => i.f || (i.f = () => queueJob(i.update)),
      $nextTick: i => i.n || (i.n = nextTick.bind(i.proxy)),
      $watch: i => (instanceWatch.bind(i) )
  });
  {
      installCompatInstanceProperties(publicPropertiesMap);
  }
  const isReservedPrefix = (key) => key === '_' || key === '$';
  const PublicInstanceProxyHandlers = {
      get({ _: instance }, key) {
          const { ctx, setupState, data, props, accessCache, type, appContext } = instance;
          // for internal formatters to know that this is a Vue instance
          if (key === '__isVue') {
              return true;
          }
          // prioritize <script setup> bindings during dev.
          // this allows even properties that start with _ or $ to be used - so that
          // it aligns with the production behavior where the render fn is inlined and
          // indeed has access to all declared variables.
          if (setupState !== EMPTY_OBJ &&
              setupState.__isScriptSetup &&
              hasOwn(setupState, key)) {
              return setupState[key];
          }
          // data / props / ctx
          // This getter gets called for every property access on the render context
          // during render and is a major hotspot. The most expensive part of this
          // is the multiple hasOwn() calls. It's much faster to do a simple property
          // access on a plain object, so we use an accessCache object (with null
          // prototype) to memoize what access type a key corresponds to.
          let normalizedProps;
          if (key[0] !== '$') {
              const n = accessCache[key];
              if (n !== undefined) {
                  switch (n) {
                      case 1 /* SETUP */:
                          return setupState[key];
                      case 2 /* DATA */:
                          return data[key];
                      case 4 /* CONTEXT */:
                          return ctx[key];
                      case 3 /* PROPS */:
                          return props[key];
                      // default: just fallthrough
                  }
              }
              else if (setupState !== EMPTY_OBJ && hasOwn(setupState, key)) {
                  accessCache[key] = 1 /* SETUP */;
                  return setupState[key];
              }
              else if (data !== EMPTY_OBJ && hasOwn(data, key)) {
                  accessCache[key] = 2 /* DATA */;
                  return data[key];
              }
              else if (
              // only cache other properties when instance has declared (thus stable)
              // props
              (normalizedProps = instance.propsOptions[0]) &&
                  hasOwn(normalizedProps, key)) {
                  accessCache[key] = 3 /* PROPS */;
                  return props[key];
              }
              else if (ctx !== EMPTY_OBJ && hasOwn(ctx, key)) {
                  accessCache[key] = 4 /* CONTEXT */;
                  return ctx[key];
              }
              else if (shouldCacheAccess) {
                  accessCache[key] = 0 /* OTHER */;
              }
          }
          const publicGetter = publicPropertiesMap[key];
          let cssModule, globalProperties;
          // public $xxx properties
          if (publicGetter) {
              if (key === '$attrs') {
                  track(instance, "get" /* GET */, key);
                  markAttrsAccessed();
              }
              return publicGetter(instance);
          }
          else if (
          // css module (injected by vue-loader)
          (cssModule = type.__cssModules) &&
              (cssModule = cssModule[key])) {
              return cssModule;
          }
          else if (ctx !== EMPTY_OBJ && hasOwn(ctx, key)) {
              // user may set custom properties to `this` that start with `$`
              accessCache[key] = 4 /* CONTEXT */;
              return ctx[key];
          }
          else if (
          // global properties
          ((globalProperties = appContext.config.globalProperties),
              hasOwn(globalProperties, key))) {
              {
                  const desc = Object.getOwnPropertyDescriptor(globalProperties, key);
                  if (desc.get) {
                      return desc.get.call(instance.proxy);
                  }
                  else {
                      const val = globalProperties[key];
                      return isFunction(val)
                          ? Object.assign(val.bind(instance.proxy), val)
                          : val;
                  }
              }
          }
          else if (currentRenderingInstance &&
              (!isString(key) ||
                  // #1091 avoid internal isRef/isVNode checks on component instance leading
                  // to infinite warning loop
                  key.indexOf('__v') !== 0)) {
              if (data !== EMPTY_OBJ && isReservedPrefix(key[0]) && hasOwn(data, key)) {
                  warn$1(`Property ${JSON.stringify(key)} must be accessed via $data because it starts with a reserved ` +
                      `character ("$" or "_") and is not proxied on the render context.`);
              }
              else if (instance === currentRenderingInstance) {
                  warn$1(`Property ${JSON.stringify(key)} was accessed during render ` +
                      `but is not defined on instance.`);
              }
          }
      },
      set({ _: instance }, key, value) {
          const { data, setupState, ctx } = instance;
          if (setupState !== EMPTY_OBJ && hasOwn(setupState, key)) {
              setupState[key] = value;
              return true;
          }
          else if (data !== EMPTY_OBJ && hasOwn(data, key)) {
              data[key] = value;
              return true;
          }
          else if (hasOwn(instance.props, key)) {
              warn$1(`Attempting to mutate prop "${key}". Props are readonly.`, instance);
              return false;
          }
          if (key[0] === '$' && key.slice(1) in instance) {
              warn$1(`Attempting to mutate public property "${key}". ` +
                      `Properties starting with $ are reserved and readonly.`, instance);
              return false;
          }
          else {
              if (key in instance.appContext.config.globalProperties) {
                  Object.defineProperty(ctx, key, {
                      enumerable: true,
                      configurable: true,
                      value
                  });
              }
              else {
                  ctx[key] = value;
              }
          }
          return true;
      },
      has({ _: { data, setupState, accessCache, ctx, appContext, propsOptions } }, key) {
          let normalizedProps;
          return (!!accessCache[key] ||
              (data !== EMPTY_OBJ && hasOwn(data, key)) ||
              (setupState !== EMPTY_OBJ && hasOwn(setupState, key)) ||
              ((normalizedProps = propsOptions[0]) && hasOwn(normalizedProps, key)) ||
              hasOwn(ctx, key) ||
              hasOwn(publicPropertiesMap, key) ||
              hasOwn(appContext.config.globalProperties, key));
      },
      defineProperty(target, key, descriptor) {
          if (descriptor.get != null) {
              // invalidate key cache of a getter based property #5417
              target._.accessCache[key] = 0;
          }
          else if (hasOwn(descriptor, 'value')) {
              this.set(target, key, descriptor.value, null);
          }
          return Reflect.defineProperty(target, key, descriptor);
      }
  };
  {
      PublicInstanceProxyHandlers.ownKeys = (target) => {
          warn$1(`Avoid app logic that relies on enumerating keys on a component instance. ` +
              `The keys will be empty in production mode to avoid performance overhead.`);
          return Reflect.ownKeys(target);
      };
  }
  const RuntimeCompiledPublicInstanceProxyHandlers = /*#__PURE__*/ extend({}, PublicInstanceProxyHandlers, {
      get(target, key) {
          // fast path for unscopables when using `with` block
          if (key === Symbol.unscopables) {
              return;
          }
          return PublicInstanceProxyHandlers.get(target, key, target);
      },
      has(_, key) {
          const has = key[0] !== '_' && !isGloballyWhitelisted(key);
          if (!has && PublicInstanceProxyHandlers.has(_, key)) {
              warn$1(`Property ${JSON.stringify(key)} should not start with _ which is a reserved prefix for Vue internals.`);
          }
          return has;
      }
  });
  // dev only
  // In dev mode, the proxy target exposes the same properties as seen on `this`
  // for easier console inspection. In prod mode it will be an empty object so
  // these properties definitions can be skipped.
  function createDevRenderContext(instance) {
      const target = {};
      // expose internal instance for proxy handlers
      Object.defineProperty(target, `_`, {
          configurable: true,
          enumerable: false,
          get: () => instance
      });
      // expose public properties
      Object.keys(publicPropertiesMap).forEach(key => {
          Object.defineProperty(target, key, {
              configurable: true,
              enumerable: false,
              get: () => publicPropertiesMap[key](instance),
              // intercepted by the proxy so no need for implementation,
              // but needed to prevent set errors
              set: NOOP
          });
      });
      return target;
  }
  // dev only
  function exposePropsOnRenderContext(instance) {
      const { ctx, propsOptions: [propsOptions] } = instance;
      if (propsOptions) {
          Object.keys(propsOptions).forEach(key => {
              Object.defineProperty(ctx, key, {
                  enumerable: true,
                  configurable: true,
                  get: () => instance.props[key],
                  set: NOOP
              });
          });
      }
  }
  // dev only
  function exposeSetupStateOnRenderContext(instance) {
      const { ctx, setupState } = instance;
      Object.keys(toRaw(setupState)).forEach(key => {
          if (!setupState.__isScriptSetup) {
              if (isReservedPrefix(key[0])) {
                  warn$1(`setup() return property ${JSON.stringify(key)} should not start with "$" or "_" ` +
                      `which are reserved prefixes for Vue internals.`);
                  return;
              }
              Object.defineProperty(ctx, key, {
                  enumerable: true,
                  configurable: true,
                  get: () => setupState[key],
                  set: NOOP
              });
          }
      });
  }

  function deepMergeData(to, from) {
      for (const key in from) {
          const toVal = to[key];
          const fromVal = from[key];
          if (key in to && isPlainObject(toVal) && isPlainObject(fromVal)) {
              warnDeprecation("OPTIONS_DATA_MERGE" /* OPTIONS_DATA_MERGE */, null, key);
              deepMergeData(toVal, fromVal);
          }
          else {
              to[key] = fromVal;
          }
      }
      return to;
  }

  function createDuplicateChecker() {
      const cache = Object.create(null);
      return (type, key) => {
          if (cache[key]) {
              warn$1(`${type} property "${key}" is already defined in ${cache[key]}.`);
          }
          else {
              cache[key] = type;
          }
      };
  }
  let shouldCacheAccess = true;
  function applyOptions(instance) {
      const options = resolveMergedOptions(instance);
      const publicThis = instance.proxy;
      const ctx = instance.ctx;
      // do not cache property access on public proxy during state initialization
      shouldCacheAccess = false;
      // call beforeCreate first before accessing other options since
      // the hook may mutate resolved options (#2791)
      if (options.beforeCreate) {
          callHook(options.beforeCreate, instance, "bc" /* BEFORE_CREATE */);
      }
      const { 
      // state
      data: dataOptions, computed: computedOptions, methods, watch: watchOptions, provide: provideOptions, inject: injectOptions, 
      // lifecycle
      created, beforeMount, mounted, beforeUpdate, updated, activated, deactivated, beforeDestroy, beforeUnmount, destroyed, unmounted, render, renderTracked, renderTriggered, errorCaptured, serverPrefetch, 
      // public API
      expose, inheritAttrs, 
      // assets
      components, directives, filters } = options;
      const checkDuplicateProperties = createDuplicateChecker() ;
      {
          const [propsOptions] = instance.propsOptions;
          if (propsOptions) {
              for (const key in propsOptions) {
                  checkDuplicateProperties("Props" /* PROPS */, key);
              }
          }
      }
      // options initialization order (to be consistent with Vue 2):
      // - props (already done outside of this function)
      // - inject
      // - methods
      // - data (deferred since it relies on `this` access)
      // - computed
      // - watch (deferred since it relies on `this` access)
      if (injectOptions) {
          resolveInjections(injectOptions, ctx, checkDuplicateProperties, instance.appContext.config.unwrapInjectedRef);
      }
      if (methods) {
          for (const key in methods) {
              const methodHandler = methods[key];
              if (isFunction(methodHandler)) {
                  // In dev mode, we use the `createRenderContext` function to define
                  // methods to the proxy target, and those are read-only but
                  // reconfigurable, so it needs to be redefined here
                  {
                      Object.defineProperty(ctx, key, {
                          value: methodHandler.bind(publicThis),
                          configurable: true,
                          enumerable: true,
                          writable: true
                      });
                  }
                  {
                      checkDuplicateProperties("Methods" /* METHODS */, key);
                  }
              }
              else {
                  warn$1(`Method "${key}" has type "${typeof methodHandler}" in the component definition. ` +
                      `Did you reference the function correctly?`);
              }
          }
      }
      if (dataOptions) {
          if (!isFunction(dataOptions)) {
              warn$1(`The data option must be a function. ` +
                  `Plain object usage is no longer supported.`);
          }
          const data = dataOptions.call(publicThis, publicThis);
          if (isPromise(data)) {
              warn$1(`data() returned a Promise - note data() cannot be async; If you ` +
                  `intend to perform data fetching before component renders, use ` +
                  `async setup() + <Suspense>.`);
          }
          if (!isObject(data)) {
              warn$1(`data() should return an object.`);
          }
          else {
              instance.data = reactive(data);
              {
                  for (const key in data) {
                      checkDuplicateProperties("Data" /* DATA */, key);
                      // expose data on ctx during dev
                      if (!isReservedPrefix(key[0])) {
                          Object.defineProperty(ctx, key, {
                              configurable: true,
                              enumerable: true,
                              get: () => data[key],
                              set: NOOP
                          });
                      }
                  }
              }
          }
      }
      // state initialization complete at this point - start caching access
      shouldCacheAccess = true;
      if (computedOptions) {
          for (const key in computedOptions) {
              const opt = computedOptions[key];
              const get = isFunction(opt)
                  ? opt.bind(publicThis, publicThis)
                  : isFunction(opt.get)
                      ? opt.get.bind(publicThis, publicThis)
                      : NOOP;
              if (get === NOOP) {
                  warn$1(`Computed property "${key}" has no getter.`);
              }
              const set = !isFunction(opt) && isFunction(opt.set)
                  ? opt.set.bind(publicThis)
                  : () => {
                          warn$1(`Write operation failed: computed property "${key}" is readonly.`);
                      }
                      ;
              const c = computed$1({
                  get,
                  set
              });
              Object.defineProperty(ctx, key, {
                  enumerable: true,
                  configurable: true,
                  get: () => c.value,
                  set: v => (c.value = v)
              });
              {
                  checkDuplicateProperties("Computed" /* COMPUTED */, key);
              }
          }
      }
      if (watchOptions) {
          for (const key in watchOptions) {
              createWatcher(watchOptions[key], ctx, publicThis, key);
          }
      }
      if (provideOptions) {
          const provides = isFunction(provideOptions)
              ? provideOptions.call(publicThis)
              : provideOptions;
          Reflect.ownKeys(provides).forEach(key => {
              provide(key, provides[key]);
          });
      }
      if (created) {
          callHook(created, instance, "c" /* CREATED */);
      }
      function registerLifecycleHook(register, hook) {
          if (isArray(hook)) {
              hook.forEach(_hook => register(_hook.bind(publicThis)));
          }
          else if (hook) {
              register(hook.bind(publicThis));
          }
      }
      registerLifecycleHook(onBeforeMount, beforeMount);
      registerLifecycleHook(onMounted, mounted);
      registerLifecycleHook(onBeforeUpdate, beforeUpdate);
      registerLifecycleHook(onUpdated, updated);
      registerLifecycleHook(onActivated, activated);
      registerLifecycleHook(onDeactivated, deactivated);
      registerLifecycleHook(onErrorCaptured, errorCaptured);
      registerLifecycleHook(onRenderTracked, renderTracked);
      registerLifecycleHook(onRenderTriggered, renderTriggered);
      registerLifecycleHook(onBeforeUnmount, beforeUnmount);
      registerLifecycleHook(onUnmounted, unmounted);
      registerLifecycleHook(onServerPrefetch, serverPrefetch);
      {
          if (beforeDestroy &&
              softAssertCompatEnabled("OPTIONS_BEFORE_DESTROY" /* OPTIONS_BEFORE_DESTROY */, instance)) {
              registerLifecycleHook(onBeforeUnmount, beforeDestroy);
          }
          if (destroyed &&
              softAssertCompatEnabled("OPTIONS_DESTROYED" /* OPTIONS_DESTROYED */, instance)) {
              registerLifecycleHook(onUnmounted, destroyed);
          }
      }
      if (isArray(expose)) {
          if (expose.length) {
              const exposed = instance.exposed || (instance.exposed = {});
              expose.forEach(key => {
                  Object.defineProperty(exposed, key, {
                      get: () => publicThis[key],
                      set: val => (publicThis[key] = val)
                  });
              });
          }
          else if (!instance.exposed) {
              instance.exposed = {};
          }
      }
      // options that are handled when creating the instance but also need to be
      // applied from mixins
      if (render && instance.render === NOOP) {
          instance.render = render;
      }
      if (inheritAttrs != null) {
          instance.inheritAttrs = inheritAttrs;
      }
      // asset options.
      if (components)
          instance.components = components;
      if (directives)
          instance.directives = directives;
      if (filters &&
          isCompatEnabled("FILTERS" /* FILTERS */, instance)) {
          instance.filters = filters;
      }
  }
  function resolveInjections(injectOptions, ctx, checkDuplicateProperties = NOOP, unwrapRef = false) {
      if (isArray(injectOptions)) {
          injectOptions = normalizeInject(injectOptions);
      }
      for (const key in injectOptions) {
          const opt = injectOptions[key];
          let injected;
          if (isObject(opt)) {
              if ('default' in opt) {
                  injected = inject(opt.from || key, opt.default, true /* treat default function as factory */);
              }
              else {
                  injected = inject(opt.from || key);
              }
          }
          else {
              injected = inject(opt);
          }
          if (isRef(injected)) {
              // TODO remove the check in 3.3
              if (unwrapRef) {
                  Object.defineProperty(ctx, key, {
                      enumerable: true,
                      configurable: true,
                      get: () => injected.value,
                      set: v => (injected.value = v)
                  });
              }
              else {
                  {
                      warn$1(`injected property "${key}" is a ref and will be auto-unwrapped ` +
                          `and no longer needs \`.value\` in the next minor release. ` +
                          `To opt-in to the new behavior now, ` +
                          `set \`app.config.unwrapInjectedRef = true\` (this config is ` +
                          `temporary and will not be needed in the future.)`);
                  }
                  ctx[key] = injected;
              }
          }
          else {
              ctx[key] = injected;
          }
          {
              checkDuplicateProperties("Inject" /* INJECT */, key);
          }
      }
  }
  function callHook(hook, instance, type) {
      callWithAsyncErrorHandling(isArray(hook)
          ? hook.map(h => h.bind(instance.proxy))
          : hook.bind(instance.proxy), instance, type);
  }
  function createWatcher(raw, ctx, publicThis, key) {
      const getter = key.includes('.')
          ? createPathGetter(publicThis, key)
          : () => publicThis[key];
      if (isString(raw)) {
          const handler = ctx[raw];
          if (isFunction(handler)) {
              watch(getter, handler);
          }
          else {
              warn$1(`Invalid watch handler specified by key "${raw}"`, handler);
          }
      }
      else if (isFunction(raw)) {
          watch(getter, raw.bind(publicThis));
      }
      else if (isObject(raw)) {
          if (isArray(raw)) {
              raw.forEach(r => createWatcher(r, ctx, publicThis, key));
          }
          else {
              const handler = isFunction(raw.handler)
                  ? raw.handler.bind(publicThis)
                  : ctx[raw.handler];
              if (isFunction(handler)) {
                  watch(getter, handler, raw);
              }
              else {
                  warn$1(`Invalid watch handler specified by key "${raw.handler}"`, handler);
              }
          }
      }
      else {
          warn$1(`Invalid watch option: "${key}"`, raw);
      }
  }
  /**
   * Resolve merged options and cache it on the component.
   * This is done only once per-component since the merging does not involve
   * instances.
   */
  function resolveMergedOptions(instance) {
      const base = instance.type;
      const { mixins, extends: extendsOptions } = base;
      const { mixins: globalMixins, optionsCache: cache, config: { optionMergeStrategies } } = instance.appContext;
      const cached = cache.get(base);
      let resolved;
      if (cached) {
          resolved = cached;
      }
      else if (!globalMixins.length && !mixins && !extendsOptions) {
          if (isCompatEnabled("PRIVATE_APIS" /* PRIVATE_APIS */, instance)) {
              resolved = extend({}, base);
              resolved.parent = instance.parent && instance.parent.proxy;
              resolved.propsData = instance.vnode.props;
          }
          else {
              resolved = base;
          }
      }
      else {
          resolved = {};
          if (globalMixins.length) {
              globalMixins.forEach(m => mergeOptions(resolved, m, optionMergeStrategies, true));
          }
          mergeOptions(resolved, base, optionMergeStrategies);
      }
      cache.set(base, resolved);
      return resolved;
  }
  function mergeOptions(to, from, strats, asMixin = false) {
      if (isFunction(from)) {
          from = from.options;
      }
      const { mixins, extends: extendsOptions } = from;
      if (extendsOptions) {
          mergeOptions(to, extendsOptions, strats, true);
      }
      if (mixins) {
          mixins.forEach((m) => mergeOptions(to, m, strats, true));
      }
      for (const key in from) {
          if (asMixin && key === 'expose') {
              warn$1(`"expose" option is ignored when declared in mixins or extends. ` +
                      `It should only be declared in the base component itself.`);
          }
          else {
              const strat = internalOptionMergeStrats[key] || (strats && strats[key]);
              to[key] = strat ? strat(to[key], from[key]) : from[key];
          }
      }
      return to;
  }
  const internalOptionMergeStrats = {
      data: mergeDataFn,
      props: mergeObjectOptions,
      emits: mergeObjectOptions,
      // objects
      methods: mergeObjectOptions,
      computed: mergeObjectOptions,
      // lifecycle
      beforeCreate: mergeAsArray,
      created: mergeAsArray,
      beforeMount: mergeAsArray,
      mounted: mergeAsArray,
      beforeUpdate: mergeAsArray,
      updated: mergeAsArray,
      beforeDestroy: mergeAsArray,
      beforeUnmount: mergeAsArray,
      destroyed: mergeAsArray,
      unmounted: mergeAsArray,
      activated: mergeAsArray,
      deactivated: mergeAsArray,
      errorCaptured: mergeAsArray,
      serverPrefetch: mergeAsArray,
      // assets
      components: mergeObjectOptions,
      directives: mergeObjectOptions,
      // watch
      watch: mergeWatchOptions,
      // provide / inject
      provide: mergeDataFn,
      inject: mergeInject
  };
  {
      internalOptionMergeStrats.filters = mergeObjectOptions;
  }
  function mergeDataFn(to, from) {
      if (!from) {
          return to;
      }
      if (!to) {
          return from;
      }
      return function mergedDataFn() {
          return (isCompatEnabled("OPTIONS_DATA_MERGE" /* OPTIONS_DATA_MERGE */, null)
              ? deepMergeData
              : extend)(isFunction(to) ? to.call(this, this) : to, isFunction(from) ? from.call(this, this) : from);
      };
  }
  function mergeInject(to, from) {
      return mergeObjectOptions(normalizeInject(to), normalizeInject(from));
  }
  function normalizeInject(raw) {
      if (isArray(raw)) {
          const res = {};
          for (let i = 0; i < raw.length; i++) {
              res[raw[i]] = raw[i];
          }
          return res;
      }
      return raw;
  }
  function mergeAsArray(to, from) {
      return to ? [...new Set([].concat(to, from))] : from;
  }
  function mergeObjectOptions(to, from) {
      return to ? extend(extend(Object.create(null), to), from) : from;
  }
  function mergeWatchOptions(to, from) {
      if (!to)
          return from;
      if (!from)
          return to;
      const merged = extend(Object.create(null), to);
      for (const key in from) {
          merged[key] = mergeAsArray(to[key], from[key]);
      }
      return merged;
  }

  function createPropsDefaultThis(instance, rawProps, propKey) {
      return new Proxy({}, {
          get(_, key) {
              warnDeprecation("PROPS_DEFAULT_THIS" /* PROPS_DEFAULT_THIS */, null, propKey);
              // $options
              if (key === '$options') {
                  return resolveMergedOptions(instance);
              }
              // props
              if (key in rawProps) {
                  return rawProps[key];
              }
              // injections
              const injections = instance.type.inject;
              if (injections) {
                  if (isArray(injections)) {
                      if (injections.includes(key)) {
                          return inject(key);
                      }
                  }
                  else if (key in injections) {
                      return inject(key);
                  }
              }
          }
      });
  }

  function shouldSkipAttr(key, instance) {
      if (key === 'is') {
          return true;
      }
      if ((key === 'class' || key === 'style') &&
          isCompatEnabled("INSTANCE_ATTRS_CLASS_STYLE" /* INSTANCE_ATTRS_CLASS_STYLE */, instance)) {
          return true;
      }
      if (isOn(key) &&
          isCompatEnabled("INSTANCE_LISTENERS" /* INSTANCE_LISTENERS */, instance)) {
          return true;
      }
      // vue-router
      if (key.startsWith('routerView') || key === 'registerRouteInstance') {
          return true;
      }
      return false;
  }

  function initProps(instance, rawProps, isStateful, // result of bitwise flag comparison
  isSSR = false) {
      const props = {};
      const attrs = {};
      def(attrs, InternalObjectKey, 1);
      instance.propsDefaults = Object.create(null);
      setFullProps(instance, rawProps, props, attrs);
      // ensure all declared prop keys are present
      for (const key in instance.propsOptions[0]) {
          if (!(key in props)) {
              props[key] = undefined;
          }
      }
      // validation
      {
          validateProps(rawProps || {}, props, instance);
      }
      if (isStateful) {
          // stateful
          instance.props = isSSR ? props : shallowReactive(props);
      }
      else {
          if (!instance.type.props) {
              // functional w/ optional props, props === attrs
              instance.props = attrs;
          }
          else {
              // functional w/ declared props
              instance.props = props;
          }
      }
      instance.attrs = attrs;
  }
  function updateProps(instance, rawProps, rawPrevProps, optimized) {
      const { props, attrs, vnode: { patchFlag } } = instance;
      const rawCurrentProps = toRaw(props);
      const [options] = instance.propsOptions;
      let hasAttrsChanged = false;
      if (
      // always force full diff in dev
      // - #1942 if hmr is enabled with sfc component
      // - vite#872 non-sfc component used by sfc component
      !((instance.type.__hmrId ||
              (instance.parent && instance.parent.type.__hmrId))) &&
          (optimized || patchFlag > 0) &&
          !(patchFlag & 16 /* FULL_PROPS */)) {
          if (patchFlag & 8 /* PROPS */) {
              // Compiler-generated props & no keys change, just set the updated
              // the props.
              const propsToUpdate = instance.vnode.dynamicProps;
              for (let i = 0; i < propsToUpdate.length; i++) {
                  let key = propsToUpdate[i];
                  // skip if the prop key is a declared emit event listener
                  if (isEmitListener(instance.emitsOptions, key)) {
                      continue;
                  }
                  // PROPS flag guarantees rawProps to be non-null
                  const value = rawProps[key];
                  if (options) {
                      // attr / props separation was done on init and will be consistent
                      // in this code path, so just check if attrs have it.
                      if (hasOwn(attrs, key)) {
                          if (value !== attrs[key]) {
                              attrs[key] = value;
                              hasAttrsChanged = true;
                          }
                      }
                      else {
                          const camelizedKey = camelize(key);
                          props[camelizedKey] = resolvePropValue(options, rawCurrentProps, camelizedKey, value, instance, false /* isAbsent */);
                      }
                  }
                  else {
                      {
                          if (isOn(key) && key.endsWith('Native')) {
                              key = key.slice(0, -6); // remove Native postfix
                          }
                          else if (shouldSkipAttr(key, instance)) {
                              continue;
                          }
                      }
                      if (value !== attrs[key]) {
                          attrs[key] = value;
                          hasAttrsChanged = true;
                      }
                  }
              }
          }
      }
      else {
          // full props update.
          if (setFullProps(instance, rawProps, props, attrs)) {
              hasAttrsChanged = true;
          }
          // in case of dynamic props, check if we need to delete keys from
          // the props object
          let kebabKey;
          for (const key in rawCurrentProps) {
              if (!rawProps ||
                  // for camelCase
                  (!hasOwn(rawProps, key) &&
                      // it's possible the original props was passed in as kebab-case
                      // and converted to camelCase (#955)
                      ((kebabKey = hyphenate(key)) === key || !hasOwn(rawProps, kebabKey)))) {
                  if (options) {
                      if (rawPrevProps &&
                          // for camelCase
                          (rawPrevProps[key] !== undefined ||
                              // for kebab-case
                              rawPrevProps[kebabKey] !== undefined)) {
                          props[key] = resolvePropValue(options, rawCurrentProps, key, undefined, instance, true /* isAbsent */);
                      }
                  }
                  else {
                      delete props[key];
                  }
              }
          }
          // in the case of functional component w/o props declaration, props and
          // attrs point to the same object so it should already have been updated.
          if (attrs !== rawCurrentProps) {
              for (const key in attrs) {
                  if (!rawProps ||
                      (!hasOwn(rawProps, key) &&
                          (!hasOwn(rawProps, key + 'Native')))) {
                      delete attrs[key];
                      hasAttrsChanged = true;
                  }
              }
          }
      }
      // trigger updates for $attrs in case it's used in component slots
      if (hasAttrsChanged) {
          trigger(instance, "set" /* SET */, '$attrs');
      }
      {
          validateProps(rawProps || {}, props, instance);
      }
  }
  function setFullProps(instance, rawProps, props, attrs) {
      const [options, needCastKeys] = instance.propsOptions;
      let hasAttrsChanged = false;
      let rawCastValues;
      if (rawProps) {
          for (let key in rawProps) {
              // key, ref are reserved and never passed down
              if (isReservedProp(key)) {
                  continue;
              }
              {
                  if (key.startsWith('onHook:')) {
                      softAssertCompatEnabled("INSTANCE_EVENT_HOOKS" /* INSTANCE_EVENT_HOOKS */, instance, key.slice(2).toLowerCase());
                  }
                  if (key === 'inline-template') {
                      continue;
                  }
              }
              const value = rawProps[key];
              // prop option names are camelized during normalization, so to support
              // kebab -> camel conversion here we need to camelize the key.
              let camelKey;
              if (options && hasOwn(options, (camelKey = camelize(key)))) {
                  if (!needCastKeys || !needCastKeys.includes(camelKey)) {
                      props[camelKey] = value;
                  }
                  else {
                      (rawCastValues || (rawCastValues = {}))[camelKey] = value;
                  }
              }
              else if (!isEmitListener(instance.emitsOptions, key)) {
                  // Any non-declared (either as a prop or an emitted event) props are put
                  // into a separate `attrs` object for spreading. Make sure to preserve
                  // original key casing
                  {
                      if (isOn(key) && key.endsWith('Native')) {
                          key = key.slice(0, -6); // remove Native postfix
                      }
                      else if (shouldSkipAttr(key, instance)) {
                          continue;
                      }
                  }
                  if (!(key in attrs) || value !== attrs[key]) {
                      attrs[key] = value;
                      hasAttrsChanged = true;
                  }
              }
          }
      }
      if (needCastKeys) {
          const rawCurrentProps = toRaw(props);
          const castValues = rawCastValues || EMPTY_OBJ;
          for (let i = 0; i < needCastKeys.length; i++) {
              const key = needCastKeys[i];
              props[key] = resolvePropValue(options, rawCurrentProps, key, castValues[key], instance, !hasOwn(castValues, key));
          }
      }
      return hasAttrsChanged;
  }
  function resolvePropValue(options, props, key, value, instance, isAbsent) {
      const opt = options[key];
      if (opt != null) {
          const hasDefault = hasOwn(opt, 'default');
          // default values
          if (hasDefault && value === undefined) {
              const defaultValue = opt.default;
              if (opt.type !== Function && isFunction(defaultValue)) {
                  const { propsDefaults } = instance;
                  if (key in propsDefaults) {
                      value = propsDefaults[key];
                  }
                  else {
                      setCurrentInstance(instance);
                      value = propsDefaults[key] = defaultValue.call(isCompatEnabled("PROPS_DEFAULT_THIS" /* PROPS_DEFAULT_THIS */, instance)
                          ? createPropsDefaultThis(instance, props, key)
                          : null, props);
                      unsetCurrentInstance();
                  }
              }
              else {
                  value = defaultValue;
              }
          }
          // boolean casting
          if (opt[0 /* shouldCast */]) {
              if (isAbsent && !hasDefault) {
                  value = false;
              }
              else if (opt[1 /* shouldCastTrue */] &&
                  (value === '' || value === hyphenate(key))) {
                  value = true;
              }
          }
      }
      return value;
  }
  function normalizePropsOptions(comp, appContext, asMixin = false) {
      const cache = appContext.propsCache;
      const cached = cache.get(comp);
      if (cached) {
          return cached;
      }
      const raw = comp.props;
      const normalized = {};
      const needCastKeys = [];
      // apply mixin/extends props
      let hasExtends = false;
      if (!isFunction(comp)) {
          const extendProps = (raw) => {
              if (isFunction(raw)) {
                  raw = raw.options;
              }
              hasExtends = true;
              const [props, keys] = normalizePropsOptions(raw, appContext, true);
              extend(normalized, props);
              if (keys)
                  needCastKeys.push(...keys);
          };
          if (!asMixin && appContext.mixins.length) {
              appContext.mixins.forEach(extendProps);
          }
          if (comp.extends) {
              extendProps(comp.extends);
          }
          if (comp.mixins) {
              comp.mixins.forEach(extendProps);
          }
      }
      if (!raw && !hasExtends) {
          cache.set(comp, EMPTY_ARR);
          return EMPTY_ARR;
      }
      if (isArray(raw)) {
          for (let i = 0; i < raw.length; i++) {
              if (!isString(raw[i])) {
                  warn$1(`props must be strings when using array syntax.`, raw[i]);
              }
              const normalizedKey = camelize(raw[i]);
              if (validatePropName(normalizedKey)) {
                  normalized[normalizedKey] = EMPTY_OBJ;
              }
          }
      }
      else if (raw) {
          if (!isObject(raw)) {
              warn$1(`invalid props options`, raw);
          }
          for (const key in raw) {
              const normalizedKey = camelize(key);
              if (validatePropName(normalizedKey)) {
                  const opt = raw[key];
                  const prop = (normalized[normalizedKey] =
                      isArray(opt) || isFunction(opt) ? { type: opt } : opt);
                  if (prop) {
                      const booleanIndex = getTypeIndex(Boolean, prop.type);
                      const stringIndex = getTypeIndex(String, prop.type);
                      prop[0 /* shouldCast */] = booleanIndex > -1;
                      prop[1 /* shouldCastTrue */] =
                          stringIndex < 0 || booleanIndex < stringIndex;
                      // if the prop needs boolean casting or default value
                      if (booleanIndex > -1 || hasOwn(prop, 'default')) {
                          needCastKeys.push(normalizedKey);
                      }
                  }
              }
          }
      }
      const res = [normalized, needCastKeys];
      cache.set(comp, res);
      return res;
  }
  function validatePropName(key) {
      if (key[0] !== '$') {
          return true;
      }
      else {
          warn$1(`Invalid prop name: "${key}" is a reserved property.`);
      }
      return false;
  }
  // use function string name to check type constructors
  // so that it works across vms / iframes.
  function getType(ctor) {
      const match = ctor && ctor.toString().match(/^\s*function (\w+)/);
      return match ? match[1] : ctor === null ? 'null' : '';
  }
  function isSameType(a, b) {
      return getType(a) === getType(b);
  }
  function getTypeIndex(type, expectedTypes) {
      if (isArray(expectedTypes)) {
          return expectedTypes.findIndex(t => isSameType(t, type));
      }
      else if (isFunction(expectedTypes)) {
          return isSameType(expectedTypes, type) ? 0 : -1;
      }
      return -1;
  }
  /**
   * dev only
   */
  function validateProps(rawProps, props, instance) {
      const resolvedValues = toRaw(props);
      const options = instance.propsOptions[0];
      for (const key in options) {
          let opt = options[key];
          if (opt == null)
              continue;
          validateProp(key, resolvedValues[key], opt, !hasOwn(rawProps, key) && !hasOwn(rawProps, hyphenate(key)));
      }
  }
  /**
   * dev only
   */
  function validateProp(name, value, prop, isAbsent) {
      const { type, required, validator } = prop;
      // required!
      if (required && isAbsent) {
          warn$1('Missing required prop: "' + name + '"');
          return;
      }
      // missing but optional
      if (value == null && !prop.required) {
          return;
      }
      // type check
      if (type != null && type !== true) {
          let isValid = false;
          const types = isArray(type) ? type : [type];
          const expectedTypes = [];
          // value is valid as long as one of the specified types match
          for (let i = 0; i < types.length && !isValid; i++) {
              const { valid, expectedType } = assertType(value, types[i]);
              expectedTypes.push(expectedType || '');
              isValid = valid;
          }
          if (!isValid) {
              warn$1(getInvalidTypeMessage(name, value, expectedTypes));
              return;
          }
      }
      // custom validator
      if (validator && !validator(value)) {
          warn$1('Invalid prop: custom validator check failed for prop "' + name + '".');
      }
  }
  const isSimpleType = /*#__PURE__*/ makeMap('String,Number,Boolean,Function,Symbol,BigInt');
  /**
   * dev only
   */
  function assertType(value, type) {
      let valid;
      const expectedType = getType(type);
      if (isSimpleType(expectedType)) {
          const t = typeof value;
          valid = t === expectedType.toLowerCase();
          // for primitive wrapper objects
          if (!valid && t === 'object') {
              valid = value instanceof type;
          }
      }
      else if (expectedType === 'Object') {
          valid = isObject(value);
      }
      else if (expectedType === 'Array') {
          valid = isArray(value);
      }
      else if (expectedType === 'null') {
          valid = value === null;
      }
      else {
          valid = value instanceof type;
      }
      return {
          valid,
          expectedType
      };
  }
  /**
   * dev only
   */
  function getInvalidTypeMessage(name, value, expectedTypes) {
      let message = `Invalid prop: type check failed for prop "${name}".` +
          ` Expected ${expectedTypes.map(capitalize).join(' | ')}`;
      const expectedType = expectedTypes[0];
      const receivedType = toRawType(value);
      const expectedValue = styleValue(value, expectedType);
      const receivedValue = styleValue(value, receivedType);
      // check if we need to specify expected value
      if (expectedTypes.length === 1 &&
          isExplicable(expectedType) &&
          !isBoolean(expectedType, receivedType)) {
          message += ` with value ${expectedValue}`;
      }
      message += `, got ${receivedType} `;
      // check if we need to specify received value
      if (isExplicable(receivedType)) {
          message += `with value ${receivedValue}.`;
      }
      return message;
  }
  /**
   * dev only
   */
  function styleValue(value, type) {
      if (type === 'String') {
          return `"${value}"`;
      }
      else if (type === 'Number') {
          return `${Number(value)}`;
      }
      else {
          return `${value}`;
      }
  }
  /**
   * dev only
   */
  function isExplicable(type) {
      const explicitTypes = ['string', 'number', 'boolean'];
      return explicitTypes.some(elem => type.toLowerCase() === elem);
  }
  /**
   * dev only
   */
  function isBoolean(...args) {
      return args.some(elem => elem.toLowerCase() === 'boolean');
  }

  const isInternalKey = (key) => key[0] === '_' || key === '$stable';
  const normalizeSlotValue = (value) => isArray(value)
      ? value.map(normalizeVNode)
      : [normalizeVNode(value)];
  const normalizeSlot = (key, rawSlot, ctx) => {
      if (rawSlot._n) {
          // already normalized - #5353
          return rawSlot;
      }
      const normalized = withCtx((...args) => {
          if (currentInstance) {
              warn$1(`Slot "${key}" invoked outside of the render function: ` +
                  `this will not track dependencies used in the slot. ` +
                  `Invoke the slot function inside the render function instead.`);
          }
          return normalizeSlotValue(rawSlot(...args));
      }, ctx);
      normalized._c = false;
      return normalized;
  };
  const normalizeObjectSlots = (rawSlots, slots, instance) => {
      const ctx = rawSlots._ctx;
      for (const key in rawSlots) {
          if (isInternalKey(key))
              continue;
          const value = rawSlots[key];
          if (isFunction(value)) {
              slots[key] = normalizeSlot(key, value, ctx);
          }
          else if (value != null) {
              if (!(isCompatEnabled("RENDER_FUNCTION" /* RENDER_FUNCTION */, instance))) {
                  warn$1(`Non-function value encountered for slot "${key}". ` +
                      `Prefer function slots for better performance.`);
              }
              const normalized = normalizeSlotValue(value);
              slots[key] = () => normalized;
          }
      }
  };
  const normalizeVNodeSlots = (instance, children) => {
      if (!isKeepAlive(instance.vnode) &&
          !(isCompatEnabled("RENDER_FUNCTION" /* RENDER_FUNCTION */, instance))) {
          warn$1(`Non-function value encountered for default slot. ` +
              `Prefer function slots for better performance.`);
      }
      const normalized = normalizeSlotValue(children);
      instance.slots.default = () => normalized;
  };
  const initSlots = (instance, children) => {
      if (instance.vnode.shapeFlag & 32 /* SLOTS_CHILDREN */) {
          const type = children._;
          if (type) {
              // users can get the shallow readonly version of the slots object through `this.$slots`,
              // we should avoid the proxy object polluting the slots of the internal instance
              instance.slots = toRaw(children);
              // make compiler marker non-enumerable
              def(children, '_', type);
          }
          else {
              normalizeObjectSlots(children, (instance.slots = {}), instance);
          }
      }
      else {
          instance.slots = {};
          if (children) {
              normalizeVNodeSlots(instance, children);
          }
      }
      def(instance.slots, InternalObjectKey, 1);
  };
  const updateSlots = (instance, children, optimized) => {
      const { vnode, slots } = instance;
      let needDeletionCheck = true;
      let deletionComparisonTarget = EMPTY_OBJ;
      if (vnode.shapeFlag & 32 /* SLOTS_CHILDREN */) {
          const type = children._;
          if (type) {
              // compiled slots.
              if (isHmrUpdating) {
                  // Parent was HMR updated so slot content may have changed.
                  // force update slots and mark instance for hmr as well
                  extend(slots, children);
              }
              else if (optimized && type === 1 /* STABLE */) {
                  // compiled AND stable.
                  // no need to update, and skip stale slots removal.
                  needDeletionCheck = false;
              }
              else {
                  // compiled but dynamic (v-if/v-for on slots) - update slots, but skip
                  // normalization.
                  extend(slots, children);
                  // #2893
                  // when rendering the optimized slots by manually written render function,
                  // we need to delete the `slots._` flag if necessary to make subsequent updates reliable,
                  // i.e. let the `renderSlot` create the bailed Fragment
                  if (!optimized && type === 1 /* STABLE */) {
                      delete slots._;
                  }
              }
          }
          else {
              needDeletionCheck = !children.$stable;
              normalizeObjectSlots(children, slots, instance);
          }
          deletionComparisonTarget = children;
      }
      else if (children) {
          // non slot object children (direct value) passed to a component
          normalizeVNodeSlots(instance, children);
          deletionComparisonTarget = { default: 1 };
      }
      // delete stale slots
      if (needDeletionCheck) {
          for (const key in slots) {
              if (!isInternalKey(key) && !(key in deletionComparisonTarget)) {
                  delete slots[key];
              }
          }
      }
  };

  // dev only
  function installLegacyConfigWarnings(config) {
      const legacyConfigOptions = {
          silent: "CONFIG_SILENT" /* CONFIG_SILENT */,
          devtools: "CONFIG_DEVTOOLS" /* CONFIG_DEVTOOLS */,
          ignoredElements: "CONFIG_IGNORED_ELEMENTS" /* CONFIG_IGNORED_ELEMENTS */,
          keyCodes: "CONFIG_KEY_CODES" /* CONFIG_KEY_CODES */,
          productionTip: "CONFIG_PRODUCTION_TIP" /* CONFIG_PRODUCTION_TIP */
      };
      Object.keys(legacyConfigOptions).forEach(key => {
          let val = config[key];
          Object.defineProperty(config, key, {
              enumerable: true,
              get() {
                  return val;
              },
              set(newVal) {
                  if (!isCopyingConfig) {
                      warnDeprecation(legacyConfigOptions[key], null);
                  }
                  val = newVal;
              }
          });
      });
  }
  function installLegacyOptionMergeStrats(config) {
      config.optionMergeStrategies = new Proxy({}, {
          get(target, key) {
              if (key in target) {
                  return target[key];
              }
              if (key in internalOptionMergeStrats &&
                  softAssertCompatEnabled("CONFIG_OPTION_MERGE_STRATS" /* CONFIG_OPTION_MERGE_STRATS */, null)) {
                  return internalOptionMergeStrats[key];
              }
          }
      });
  }

  let isCopyingConfig = false;
  // exported only for test
  let singletonApp;
  let singletonCtor;
  // Legacy global Vue constructor
  function createCompatVue(createApp, createSingletonApp) {
      singletonApp = createSingletonApp({});
      const Vue = (singletonCtor = function Vue(options = {}) {
          return createCompatApp(options, Vue);
      });
      function createCompatApp(options = {}, Ctor) {
          assertCompatEnabled("GLOBAL_MOUNT" /* GLOBAL_MOUNT */, null);
          const { data } = options;
          if (data &&
              !isFunction(data) &&
              softAssertCompatEnabled("OPTIONS_DATA_FN" /* OPTIONS_DATA_FN */, null)) {
              options.data = () => data;
          }
          const app = createApp(options);
          if (Ctor !== Vue) {
              applySingletonPrototype(app, Ctor);
          }
          const vm = app._createRoot(options);
          if (options.el) {
              return vm.$mount(options.el);
          }
          else {
              return vm;
          }
      }
      Vue.version = `2.6.14-compat:${"3.2.37"}`;
      Vue.config = singletonApp.config;
      Vue.use = (p, ...options) => {
          if (p && isFunction(p.install)) {
              p.install(Vue, ...options);
          }
          else if (isFunction(p)) {
              p(Vue, ...options);
          }
          return Vue;
      };
      Vue.mixin = m => {
          singletonApp.mixin(m);
          return Vue;
      };
      Vue.component = ((name, comp) => {
          if (comp) {
              singletonApp.component(name, comp);
              return Vue;
          }
          else {
              return singletonApp.component(name);
          }
      });
      Vue.directive = ((name, dir) => {
          if (dir) {
              singletonApp.directive(name, dir);
              return Vue;
          }
          else {
              return singletonApp.directive(name);
          }
      });
      Vue.options = { _base: Vue };
      let cid = 1;
      Vue.cid = cid;
      Vue.nextTick = nextTick;
      const extendCache = new WeakMap();
      function extendCtor(extendOptions = {}) {
          assertCompatEnabled("GLOBAL_EXTEND" /* GLOBAL_EXTEND */, null);
          if (isFunction(extendOptions)) {
              extendOptions = extendOptions.options;
          }
          if (extendCache.has(extendOptions)) {
              return extendCache.get(extendOptions);
          }
          const Super = this;
          function SubVue(inlineOptions) {
              if (!inlineOptions) {
                  return createCompatApp(SubVue.options, SubVue);
              }
              else {
                  return createCompatApp(mergeOptions(extend({}, SubVue.options), inlineOptions, internalOptionMergeStrats), SubVue);
              }
          }
          SubVue.super = Super;
          SubVue.prototype = Object.create(Vue.prototype);
          SubVue.prototype.constructor = SubVue;
          // clone non-primitive base option values for edge case of mutating
          // extended options
          const mergeBase = {};
          for (const key in Super.options) {
              const superValue = Super.options[key];
              mergeBase[key] = isArray(superValue)
                  ? superValue.slice()
                  : isObject(superValue)
                      ? extend(Object.create(null), superValue)
                      : superValue;
          }
          SubVue.options = mergeOptions(mergeBase, extendOptions, internalOptionMergeStrats);
          SubVue.options._base = SubVue;
          SubVue.extend = extendCtor.bind(SubVue);
          SubVue.mixin = Super.mixin;
          SubVue.use = Super.use;
          SubVue.cid = ++cid;
          extendCache.set(extendOptions, SubVue);
          return SubVue;
      }
      Vue.extend = extendCtor.bind(Vue);
      Vue.set = (target, key, value) => {
          assertCompatEnabled("GLOBAL_SET" /* GLOBAL_SET */, null);
          target[key] = value;
      };
      Vue.delete = (target, key) => {
          assertCompatEnabled("GLOBAL_DELETE" /* GLOBAL_DELETE */, null);
          delete target[key];
      };
      Vue.observable = (target) => {
          assertCompatEnabled("GLOBAL_OBSERVABLE" /* GLOBAL_OBSERVABLE */, null);
          return reactive(target);
      };
      Vue.filter = ((name, filter) => {
          if (filter) {
              singletonApp.filter(name, filter);
              return Vue;
          }
          else {
              return singletonApp.filter(name);
          }
      });
      // internal utils - these are technically internal but some plugins use it.
      const util = {
          warn: warn$1 ,
          extend,
          mergeOptions: (parent, child, vm) => mergeOptions(parent, child, vm ? undefined : internalOptionMergeStrats),
          defineReactive
      };
      Object.defineProperty(Vue, 'util', {
          get() {
              assertCompatEnabled("GLOBAL_PRIVATE_UTIL" /* GLOBAL_PRIVATE_UTIL */, null);
              return util;
          }
      });
      Vue.configureCompat = configureCompat;
      return Vue;
  }
  function installAppCompatProperties(app, context, render) {
      installFilterMethod(app, context);
      installLegacyOptionMergeStrats(app.config);
      if (!singletonApp) {
          // this is the call of creating the singleton itself so the rest is
          // unnecessary
          return;
      }
      installCompatMount(app, context, render);
      installLegacyAPIs(app);
      applySingletonAppMutations(app);
      installLegacyConfigWarnings(app.config);
  }
  function installFilterMethod(app, context) {
      context.filters = {};
      app.filter = (name, filter) => {
          assertCompatEnabled("FILTERS" /* FILTERS */, null);
          if (!filter) {
              return context.filters[name];
          }
          if (context.filters[name]) {
              warn$1(`Filter "${name}" has already been registered.`);
          }
          context.filters[name] = filter;
          return app;
      };
  }
  function installLegacyAPIs(app) {
      // expose global API on app instance for legacy plugins
      Object.defineProperties(app, {
          // so that app.use() can work with legacy plugins that extend prototypes
          prototype: {
              get() {
                  warnDeprecation("GLOBAL_PROTOTYPE" /* GLOBAL_PROTOTYPE */, null);
                  return app.config.globalProperties;
              }
          },
          nextTick: { value: nextTick },
          extend: { value: singletonCtor.extend },
          set: { value: singletonCtor.set },
          delete: { value: singletonCtor.delete },
          observable: { value: singletonCtor.observable },
          util: {
              get() {
                  return singletonCtor.util;
              }
          }
      });
  }
  function applySingletonAppMutations(app) {
      // copy over asset registries and deopt flag
      app._context.mixins = [...singletonApp._context.mixins];
      ['components', 'directives', 'filters'].forEach(key => {
          // @ts-ignore
          app._context[key] = Object.create(singletonApp._context[key]);
      });
      // copy over global config mutations
      isCopyingConfig = true;
      for (const key in singletonApp.config) {
          if (key === 'isNativeTag')
              continue;
          if (isRuntimeOnly() &&
              (key === 'isCustomElement' || key === 'compilerOptions')) {
              continue;
          }
          const val = singletonApp.config[key];
          // @ts-ignore
          app.config[key] = isObject(val) ? Object.create(val) : val;
          // compat for runtime ignoredElements -> isCustomElement
          if (key === 'ignoredElements' &&
              isCompatEnabled("CONFIG_IGNORED_ELEMENTS" /* CONFIG_IGNORED_ELEMENTS */, null) &&
              !isRuntimeOnly() &&
              isArray(val)) {
              app.config.compilerOptions.isCustomElement = tag => {
                  return val.some(v => (isString(v) ? v === tag : v.test(tag)));
              };
          }
      }
      isCopyingConfig = false;
      applySingletonPrototype(app, singletonCtor);
  }
  function applySingletonPrototype(app, Ctor) {
      // copy prototype augmentations as config.globalProperties
      const enabled = isCompatEnabled("GLOBAL_PROTOTYPE" /* GLOBAL_PROTOTYPE */, null);
      if (enabled) {
          app.config.globalProperties = Object.create(Ctor.prototype);
      }
      let hasPrototypeAugmentations = false;
      const descriptors = Object.getOwnPropertyDescriptors(Ctor.prototype);
      for (const key in descriptors) {
          if (key !== 'constructor') {
              hasPrototypeAugmentations = true;
              if (enabled) {
                  Object.defineProperty(app.config.globalProperties, key, descriptors[key]);
              }
          }
      }
      if (hasPrototypeAugmentations) {
          warnDeprecation("GLOBAL_PROTOTYPE" /* GLOBAL_PROTOTYPE */, null);
      }
  }
  function installCompatMount(app, context, render) {
      let isMounted = false;
      /**
       * Vue 2 supports the behavior of creating a component instance but not
       * mounting it, which is no longer possible in Vue 3 - this internal
       * function simulates that behavior.
       */
      app._createRoot = options => {
          const component = app._component;
          const vnode = createVNode(component, options.propsData || null);
          vnode.appContext = context;
          const hasNoRender = !isFunction(component) && !component.render && !component.template;
          const emptyRender = () => { };
          // create root instance
          const instance = createComponentInstance(vnode, null, null);
          // suppress "missing render fn" warning since it can't be determined
          // until $mount is called
          if (hasNoRender) {
              instance.render = emptyRender;
          }
          setupComponent(instance);
          vnode.component = instance;
          vnode.isCompatRoot = true;
          // $mount & $destroy
          // these are defined on ctx and picked up by the $mount/$destroy
          // public property getters on the instance proxy.
          // Note: the following assumes DOM environment since the compat build
          // only targets web. It essentially includes logic for app.mount from
          // both runtime-core AND runtime-dom.
          instance.ctx._compat_mount = (selectorOrEl) => {
              if (isMounted) {
                  warn$1(`Root instance is already mounted.`);
                  return;
              }
              let container;
              if (typeof selectorOrEl === 'string') {
                  // eslint-disable-next-line
                  const result = document.querySelector(selectorOrEl);
                  if (!result) {
                      warn$1(`Failed to mount root instance: selector "${selectorOrEl}" returned null.`);
                      return;
                  }
                  container = result;
              }
              else {
                  // eslint-disable-next-line
                  container = selectorOrEl || document.createElement('div');
              }
              const isSVG = container instanceof SVGElement;
              // HMR root reload
              {
                  context.reload = () => {
                      const cloned = cloneVNode(vnode);
                      // compat mode will use instance if not reset to null
                      cloned.component = null;
                      render(cloned, container, isSVG);
                  };
              }
              // resolve in-DOM template if component did not provide render
              // and no setup/mixin render functions are provided (by checking
              // that the instance is still using the placeholder render fn)
              if (hasNoRender && instance.render === emptyRender) {
                  // root directives check
                  {
                      for (let i = 0; i < container.attributes.length; i++) {
                          const attr = container.attributes[i];
                          if (attr.name !== 'v-cloak' && /^(v-|:|@)/.test(attr.name)) {
                              warnDeprecation("GLOBAL_MOUNT_CONTAINER" /* GLOBAL_MOUNT_CONTAINER */, null);
                              break;
                          }
                      }
                  }
                  instance.render = null;
                  component.template = container.innerHTML;
                  finishComponentSetup(instance, false, true /* skip options */);
              }
              // clear content before mounting
              container.innerHTML = '';
              // TODO hydration
              render(vnode, container, isSVG);
              if (container instanceof Element) {
                  container.removeAttribute('v-cloak');
                  container.setAttribute('data-v-app', '');
              }
              isMounted = true;
              app._container = container;
              container.__vue_app__ = app;
              {
                  devtoolsInitApp(app, version);
              }
              return instance.proxy;
          };
          instance.ctx._compat_destroy = () => {
              if (isMounted) {
                  render(null, app._container);
                  {
                      devtoolsUnmountApp(app);
                  }
                  delete app._container.__vue_app__;
              }
              else {
                  const { bum, scope, um } = instance;
                  // beforeDestroy hooks
                  if (bum) {
                      invokeArrayFns(bum);
                  }
                  if (isCompatEnabled("INSTANCE_EVENT_HOOKS" /* INSTANCE_EVENT_HOOKS */, instance)) {
                      instance.emit('hook:beforeDestroy');
                  }
                  // stop effects
                  if (scope) {
                      scope.stop();
                  }
                  // unmounted hook
                  if (um) {
                      invokeArrayFns(um);
                  }
                  if (isCompatEnabled("INSTANCE_EVENT_HOOKS" /* INSTANCE_EVENT_HOOKS */, instance)) {
                      instance.emit('hook:destroyed');
                  }
              }
          };
          return instance.proxy;
      };
  }
  const methodsToPatch = [
      'push',
      'pop',
      'shift',
      'unshift',
      'splice',
      'sort',
      'reverse'
  ];
  const patched = new WeakSet();
  function defineReactive(obj, key, val) {
      // it's possible for the original object to be mutated after being defined
      // and expecting reactivity... we are covering it here because this seems to
      // be a bit more common.
      if (isObject(val) && !isReactive(val) && !patched.has(val)) {
          const reactiveVal = reactive(val);
          if (isArray(val)) {
              methodsToPatch.forEach(m => {
                  // @ts-ignore
                  val[m] = (...args) => {
                      // @ts-ignore
                      Array.prototype[m].call(reactiveVal, ...args);
                  };
              });
          }
          else {
              Object.keys(val).forEach(key => {
                  try {
                      defineReactiveSimple(val, key, val[key]);
                  }
                  catch (e) { }
              });
          }
      }
      const i = obj.$;
      if (i && obj === i.proxy) {
          // target is a Vue instance - define on instance.ctx
          defineReactiveSimple(i.ctx, key, val);
          i.accessCache = Object.create(null);
      }
      else if (isReactive(obj)) {
          obj[key] = val;
      }
      else {
          defineReactiveSimple(obj, key, val);
      }
  }
  function defineReactiveSimple(obj, key, val) {
      val = isObject(val) ? reactive(val) : val;
      Object.defineProperty(obj, key, {
          enumerable: true,
          configurable: true,
          get() {
              track(obj, "get" /* GET */, key);
              return val;
          },
          set(newVal) {
              val = isObject(newVal) ? reactive(newVal) : newVal;
              trigger(obj, "set" /* SET */, key, newVal);
          }
      });
  }

  function createAppContext() {
      return {
          app: null,
          config: {
              isNativeTag: NO,
              performance: false,
              globalProperties: {},
              optionMergeStrategies: {},
              errorHandler: undefined,
              warnHandler: undefined,
              compilerOptions: {}
          },
          mixins: [],
          components: {},
          directives: {},
          provides: Object.create(null),
          optionsCache: new WeakMap(),
          propsCache: new WeakMap(),
          emitsCache: new WeakMap()
      };
  }
  let uid = 0;
  function createAppAPI(render, hydrate) {
      return function createApp(rootComponent, rootProps = null) {
          if (!isFunction(rootComponent)) {
              rootComponent = Object.assign({}, rootComponent);
          }
          if (rootProps != null && !isObject(rootProps)) {
              warn$1(`root props passed to app.mount() must be an object.`);
              rootProps = null;
          }
          const context = createAppContext();
          const installedPlugins = new Set();
          let isMounted = false;
          const app = (context.app = {
              _uid: uid++,
              _component: rootComponent,
              _props: rootProps,
              _container: null,
              _context: context,
              _instance: null,
              version,
              get config() {
                  return context.config;
              },
              set config(v) {
                  {
                      warn$1(`app.config cannot be replaced. Modify individual options instead.`);
                  }
              },
              use(plugin, ...options) {
                  if (installedPlugins.has(plugin)) {
                      warn$1(`Plugin has already been applied to target app.`);
                  }
                  else if (plugin && isFunction(plugin.install)) {
                      installedPlugins.add(plugin);
                      plugin.install(app, ...options);
                  }
                  else if (isFunction(plugin)) {
                      installedPlugins.add(plugin);
                      plugin(app, ...options);
                  }
                  else {
                      warn$1(`A plugin must either be a function or an object with an "install" ` +
                          `function.`);
                  }
                  return app;
              },
              mixin(mixin) {
                  {
                      if (!context.mixins.includes(mixin)) {
                          context.mixins.push(mixin);
                      }
                      else {
                          warn$1('Mixin has already been applied to target app' +
                              (mixin.name ? `: ${mixin.name}` : ''));
                      }
                  }
                  return app;
              },
              component(name, component) {
                  {
                      validateComponentName(name, context.config);
                  }
                  if (!component) {
                      return context.components[name];
                  }
                  if (context.components[name]) {
                      warn$1(`Component "${name}" has already been registered in target app.`);
                  }
                  context.components[name] = component;
                  return app;
              },
              directive(name, directive) {
                  {
                      validateDirectiveName(name);
                  }
                  if (!directive) {
                      return context.directives[name];
                  }
                  if (context.directives[name]) {
                      warn$1(`Directive "${name}" has already been registered in target app.`);
                  }
                  context.directives[name] = directive;
                  return app;
              },
              mount(rootContainer, isHydrate, isSVG) {
                  if (!isMounted) {
                      // #5571
                      if (rootContainer.__vue_app__) {
                          warn$1(`There is already an app instance mounted on the host container.\n` +
                              ` If you want to mount another app on the same host container,` +
                              ` you need to unmount the previous app by calling \`app.unmount()\` first.`);
                      }
                      const vnode = createVNode(rootComponent, rootProps);
                      // store app context on the root VNode.
                      // this will be set on the root instance on initial mount.
                      vnode.appContext = context;
                      // HMR root reload
                      {
                          context.reload = () => {
                              render(cloneVNode(vnode), rootContainer, isSVG);
                          };
                      }
                      if (isHydrate && hydrate) {
                          hydrate(vnode, rootContainer);
                      }
                      else {
                          render(vnode, rootContainer, isSVG);
                      }
                      isMounted = true;
                      app._container = rootContainer;
                      rootContainer.__vue_app__ = app;
                      {
                          app._instance = vnode.component;
                          devtoolsInitApp(app, version);
                      }
                      return getExposeProxy(vnode.component) || vnode.component.proxy;
                  }
                  else {
                      warn$1(`App has already been mounted.\n` +
                          `If you want to remount the same app, move your app creation logic ` +
                          `into a factory function and create fresh app instances for each ` +
                          `mount - e.g. \`const createMyApp = () => createApp(App)\``);
                  }
              },
              unmount() {
                  if (isMounted) {
                      render(null, app._container);
                      {
                          app._instance = null;
                          devtoolsUnmountApp(app);
                      }
                      delete app._container.__vue_app__;
                  }
                  else {
                      warn$1(`Cannot unmount an app that is not mounted.`);
                  }
              },
              provide(key, value) {
                  if (key in context.provides) {
                      warn$1(`App already provides property with key "${String(key)}". ` +
                          `It will be overwritten with the new value.`);
                  }
                  context.provides[key] = value;
                  return app;
              }
          });
          {
              installAppCompatProperties(app, context, render);
          }
          return app;
      };
  }

  /**
   * Function for handling a template ref
   */
  function setRef(rawRef, oldRawRef, parentSuspense, vnode, isUnmount = false) {
      if (isArray(rawRef)) {
          rawRef.forEach((r, i) => setRef(r, oldRawRef && (isArray(oldRawRef) ? oldRawRef[i] : oldRawRef), parentSuspense, vnode, isUnmount));
          return;
      }
      if (isAsyncWrapper(vnode) && !isUnmount) {
          // when mounting async components, nothing needs to be done,
          // because the template ref is forwarded to inner component
          return;
      }
      const refValue = vnode.shapeFlag & 4 /* STATEFUL_COMPONENT */
          ? getExposeProxy(vnode.component) || vnode.component.proxy
          : vnode.el;
      const value = isUnmount ? null : refValue;
      const { i: owner, r: ref } = rawRef;
      if (!owner) {
          warn$1(`Missing ref owner context. ref cannot be used on hoisted vnodes. ` +
              `A vnode with ref must be created inside the render function.`);
          return;
      }
      const oldRef = oldRawRef && oldRawRef.r;
      const refs = owner.refs === EMPTY_OBJ ? (owner.refs = {}) : owner.refs;
      const setupState = owner.setupState;
      // dynamic ref changed. unset old ref
      if (oldRef != null && oldRef !== ref) {
          if (isString(oldRef)) {
              refs[oldRef] = null;
              if (hasOwn(setupState, oldRef)) {
                  setupState[oldRef] = null;
              }
          }
          else if (isRef(oldRef)) {
              oldRef.value = null;
          }
      }
      if (isFunction(ref)) {
          callWithErrorHandling(ref, owner, 12 /* FUNCTION_REF */, [value, refs]);
      }
      else {
          const _isString = isString(ref);
          const _isRef = isRef(ref);
          if (_isString || _isRef) {
              const doSet = () => {
                  if (rawRef.f) {
                      const existing = _isString ? refs[ref] : ref.value;
                      if (isUnmount) {
                          isArray(existing) && remove(existing, refValue);
                      }
                      else {
                          if (!isArray(existing)) {
                              if (_isString) {
                                  refs[ref] = [refValue];
                                  if (hasOwn(setupState, ref)) {
                                      setupState[ref] = refs[ref];
                                  }
                              }
                              else {
                                  ref.value = [refValue];
                                  if (rawRef.k)
                                      refs[rawRef.k] = ref.value;
                              }
                          }
                          else if (!existing.includes(refValue)) {
                              existing.push(refValue);
                          }
                      }
                  }
                  else if (_isString) {
                      refs[ref] = value;
                      if (hasOwn(setupState, ref)) {
                          setupState[ref] = value;
                      }
                  }
                  else if (_isRef) {
                      ref.value = value;
                      if (rawRef.k)
                          refs[rawRef.k] = value;
                  }
                  else {
                      warn$1('Invalid template ref type:', ref, `(${typeof ref})`);
                  }
              };
              if (value) {
                  doSet.id = -1;
                  queuePostRenderEffect(doSet, parentSuspense);
              }
              else {
                  doSet();
              }
          }
          else {
              warn$1('Invalid template ref type:', ref, `(${typeof ref})`);
          }
      }
  }

  let hasMismatch = false;
  const isSVGContainer = (container) => /svg/.test(container.namespaceURI) && container.tagName !== 'foreignObject';
  const isComment = (node) => node.nodeType === 8 /* COMMENT */;
  // Note: hydration is DOM-specific
  // But we have to place it in core due to tight coupling with core - splitting
  // it out creates a ton of unnecessary complexity.
  // Hydration also depends on some renderer internal logic which needs to be
  // passed in via arguments.
  function createHydrationFunctions(rendererInternals) {
      const { mt: mountComponent, p: patch, o: { patchProp, createText, nextSibling, parentNode, remove, insert, createComment } } = rendererInternals;
      const hydrate = (vnode, container) => {
          if (!container.hasChildNodes()) {
              warn$1(`Attempting to hydrate existing markup but container is empty. ` +
                      `Performing full mount instead.`);
              patch(null, vnode, container);
              flushPostFlushCbs();
              container._vnode = vnode;
              return;
          }
          hasMismatch = false;
          hydrateNode(container.firstChild, vnode, null, null, null);
          flushPostFlushCbs();
          container._vnode = vnode;
          if (hasMismatch && !false) {
              // this error should show up in production
              console.error(`Hydration completed but contains mismatches.`);
          }
      };
      const hydrateNode = (node, vnode, parentComponent, parentSuspense, slotScopeIds, optimized = false) => {
          const isFragmentStart = isComment(node) && node.data === '[';
          const onMismatch = () => handleMismatch(node, vnode, parentComponent, parentSuspense, slotScopeIds, isFragmentStart);
          const { type, ref, shapeFlag, patchFlag } = vnode;
          const domType = node.nodeType;
          vnode.el = node;
          if (patchFlag === -2 /* BAIL */) {
              optimized = false;
              vnode.dynamicChildren = null;
          }
          let nextNode = null;
          switch (type) {
              case Text:
                  if (domType !== 3 /* TEXT */) {
                      // #5728 empty text node inside a slot can cause hydration failure
                      // because the server rendered HTML won't contain a text node
                      if (vnode.children === '') {
                          insert((vnode.el = createText('')), parentNode(node), node);
                          nextNode = node;
                      }
                      else {
                          nextNode = onMismatch();
                      }
                  }
                  else {
                      if (node.data !== vnode.children) {
                          hasMismatch = true;
                          warn$1(`Hydration text mismatch:` +
                                  `\n- Client: ${JSON.stringify(node.data)}` +
                                  `\n- Server: ${JSON.stringify(vnode.children)}`);
                          node.data = vnode.children;
                      }
                      nextNode = nextSibling(node);
                  }
                  break;
              case Comment:
                  if (domType !== 8 /* COMMENT */ || isFragmentStart) {
                      nextNode = onMismatch();
                  }
                  else {
                      nextNode = nextSibling(node);
                  }
                  break;
              case Static:
                  if (domType !== 1 /* ELEMENT */ && domType !== 3 /* TEXT */) {
                      nextNode = onMismatch();
                  }
                  else {
                      // determine anchor, adopt content
                      nextNode = node;
                      // if the static vnode has its content stripped during build,
                      // adopt it from the server-rendered HTML.
                      const needToAdoptContent = !vnode.children.length;
                      for (let i = 0; i < vnode.staticCount; i++) {
                          if (needToAdoptContent)
                              vnode.children +=
                                  nextNode.nodeType === 1 /* ELEMENT */
                                      ? nextNode.outerHTML
                                      : nextNode.data;
                          if (i === vnode.staticCount - 1) {
                              vnode.anchor = nextNode;
                          }
                          nextNode = nextSibling(nextNode);
                      }
                      return nextNode;
                  }
                  break;
              case Fragment:
                  if (!isFragmentStart) {
                      nextNode = onMismatch();
                  }
                  else {
                      nextNode = hydrateFragment(node, vnode, parentComponent, parentSuspense, slotScopeIds, optimized);
                  }
                  break;
              default:
                  if (shapeFlag & 1 /* ELEMENT */) {
                      if (domType !== 1 /* ELEMENT */ ||
                          vnode.type.toLowerCase() !==
                              node.tagName.toLowerCase()) {
                          nextNode = onMismatch();
                      }
                      else {
                          nextNode = hydrateElement(node, vnode, parentComponent, parentSuspense, slotScopeIds, optimized);
                      }
                  }
                  else if (shapeFlag & 6 /* COMPONENT */) {
                      // when setting up the render effect, if the initial vnode already
                      // has .el set, the component will perform hydration instead of mount
                      // on its sub-tree.
                      vnode.slotScopeIds = slotScopeIds;
                      const container = parentNode(node);
                      mountComponent(vnode, container, null, parentComponent, parentSuspense, isSVGContainer(container), optimized);
                      // component may be async, so in the case of fragments we cannot rely
                      // on component's rendered output to determine the end of the fragment
                      // instead, we do a lookahead to find the end anchor node.
                      nextNode = isFragmentStart
                          ? locateClosingAsyncAnchor(node)
                          : nextSibling(node);
                      // #4293 teleport as component root
                      if (nextNode &&
                          isComment(nextNode) &&
                          nextNode.data === 'teleport end') {
                          nextNode = nextSibling(nextNode);
                      }
                      // #3787
                      // if component is async, it may get moved / unmounted before its
                      // inner component is loaded, so we need to give it a placeholder
                      // vnode that matches its adopted DOM.
                      if (isAsyncWrapper(vnode)) {
                          let subTree;
                          if (isFragmentStart) {
                              subTree = createVNode(Fragment);
                              subTree.anchor = nextNode
                                  ? nextNode.previousSibling
                                  : container.lastChild;
                          }
                          else {
                              subTree =
                                  node.nodeType === 3 ? createTextVNode('') : createVNode('div');
                          }
                          subTree.el = node;
                          vnode.component.subTree = subTree;
                      }
                  }
                  else if (shapeFlag & 64 /* TELEPORT */) {
                      if (domType !== 8 /* COMMENT */) {
                          nextNode = onMismatch();
                      }
                      else {
                          nextNode = vnode.type.hydrate(node, vnode, parentComponent, parentSuspense, slotScopeIds, optimized, rendererInternals, hydrateChildren);
                      }
                  }
                  else if (shapeFlag & 128 /* SUSPENSE */) {
                      nextNode = vnode.type.hydrate(node, vnode, parentComponent, parentSuspense, isSVGContainer(parentNode(node)), slotScopeIds, optimized, rendererInternals, hydrateNode);
                  }
                  else {
                      warn$1('Invalid HostVNode type:', type, `(${typeof type})`);
                  }
          }
          if (ref != null) {
              setRef(ref, null, parentSuspense, vnode);
          }
          return nextNode;
      };
      const hydrateElement = (el, vnode, parentComponent, parentSuspense, slotScopeIds, optimized) => {
          optimized = optimized || !!vnode.dynamicChildren;
          const { type, props, patchFlag, shapeFlag, dirs } = vnode;
          // #4006 for form elements with non-string v-model value bindings
          // e.g. <option :value="obj">, <input type="checkbox" :true-value="1">
          const forcePatchValue = (type === 'input' && dirs) || type === 'option';
          // skip props & children if this is hoisted static nodes
          // #5405 in dev, always hydrate children for HMR
          {
              if (dirs) {
                  invokeDirectiveHook(vnode, null, parentComponent, 'created');
              }
              // props
              if (props) {
                  if (forcePatchValue ||
                      !optimized ||
                      patchFlag & (16 /* FULL_PROPS */ | 32 /* HYDRATE_EVENTS */)) {
                      for (const key in props) {
                          if ((forcePatchValue && key.endsWith('value')) ||
                              (isOn(key) && !isReservedProp(key))) {
                              patchProp(el, key, null, props[key], false, undefined, parentComponent);
                          }
                      }
                  }
                  else if (props.onClick) {
                      // Fast path for click listeners (which is most often) to avoid
                      // iterating through props.
                      patchProp(el, 'onClick', null, props.onClick, false, undefined, parentComponent);
                  }
              }
              // vnode / directive hooks
              let vnodeHooks;
              if ((vnodeHooks = props && props.onVnodeBeforeMount)) {
                  invokeVNodeHook(vnodeHooks, parentComponent, vnode);
              }
              if (dirs) {
                  invokeDirectiveHook(vnode, null, parentComponent, 'beforeMount');
              }
              if ((vnodeHooks = props && props.onVnodeMounted) || dirs) {
                  queueEffectWithSuspense(() => {
                      vnodeHooks && invokeVNodeHook(vnodeHooks, parentComponent, vnode);
                      dirs && invokeDirectiveHook(vnode, null, parentComponent, 'mounted');
                  }, parentSuspense);
              }
              // children
              if (shapeFlag & 16 /* ARRAY_CHILDREN */ &&
                  // skip if element has innerHTML / textContent
                  !(props && (props.innerHTML || props.textContent))) {
                  let next = hydrateChildren(el.firstChild, vnode, el, parentComponent, parentSuspense, slotScopeIds, optimized);
                  let hasWarned = false;
                  while (next) {
                      hasMismatch = true;
                      if (!hasWarned) {
                          warn$1(`Hydration children mismatch in <${vnode.type}>: ` +
                              `server rendered element contains more child nodes than client vdom.`);
                          hasWarned = true;
                      }
                      // The SSRed DOM contains more nodes than it should. Remove them.
                      const cur = next;
                      next = next.nextSibling;
                      remove(cur);
                  }
              }
              else if (shapeFlag & 8 /* TEXT_CHILDREN */) {
                  if (el.textContent !== vnode.children) {
                      hasMismatch = true;
                      warn$1(`Hydration text content mismatch in <${vnode.type}>:\n` +
                              `- Client: ${el.textContent}\n` +
                              `- Server: ${vnode.children}`);
                      el.textContent = vnode.children;
                  }
              }
          }
          return el.nextSibling;
      };
      const hydrateChildren = (node, parentVNode, container, parentComponent, parentSuspense, slotScopeIds, optimized) => {
          optimized = optimized || !!parentVNode.dynamicChildren;
          const children = parentVNode.children;
          const l = children.length;
          let hasWarned = false;
          for (let i = 0; i < l; i++) {
              const vnode = optimized
                  ? children[i]
                  : (children[i] = normalizeVNode(children[i]));
              if (node) {
                  node = hydrateNode(node, vnode, parentComponent, parentSuspense, slotScopeIds, optimized);
              }
              else if (vnode.type === Text && !vnode.children) {
                  continue;
              }
              else {
                  hasMismatch = true;
                  if (!hasWarned) {
                      warn$1(`Hydration children mismatch in <${container.tagName.toLowerCase()}>: ` +
                          `server rendered element contains fewer child nodes than client vdom.`);
                      hasWarned = true;
                  }
                  // the SSRed DOM didn't contain enough nodes. Mount the missing ones.
                  patch(null, vnode, container, null, parentComponent, parentSuspense, isSVGContainer(container), slotScopeIds);
              }
          }
          return node;
      };
      const hydrateFragment = (node, vnode, parentComponent, parentSuspense, slotScopeIds, optimized) => {
          const { slotScopeIds: fragmentSlotScopeIds } = vnode;
          if (fragmentSlotScopeIds) {
              slotScopeIds = slotScopeIds
                  ? slotScopeIds.concat(fragmentSlotScopeIds)
                  : fragmentSlotScopeIds;
          }
          const container = parentNode(node);
          const next = hydrateChildren(nextSibling(node), vnode, container, parentComponent, parentSuspense, slotScopeIds, optimized);
          if (next && isComment(next) && next.data === ']') {
              return nextSibling((vnode.anchor = next));
          }
          else {
              // fragment didn't hydrate successfully, since we didn't get a end anchor
              // back. This should have led to node/children mismatch warnings.
              hasMismatch = true;
              // since the anchor is missing, we need to create one and insert it
              insert((vnode.anchor = createComment(`]`)), container, next);
              return next;
          }
      };
      const handleMismatch = (node, vnode, parentComponent, parentSuspense, slotScopeIds, isFragment) => {
          hasMismatch = true;
          warn$1(`Hydration node mismatch:\n- Client vnode:`, vnode.type, `\n- Server rendered DOM:`, node, node.nodeType === 3 /* TEXT */
                  ? `(text)`
                  : isComment(node) && node.data === '['
                      ? `(start of fragment)`
                      : ``);
          vnode.el = null;
          if (isFragment) {
              // remove excessive fragment nodes
              const end = locateClosingAsyncAnchor(node);
              while (true) {
                  const next = nextSibling(node);
                  if (next && next !== end) {
                      remove(next);
                  }
                  else {
                      break;
                  }
              }
          }
          const next = nextSibling(node);
          const container = parentNode(node);
          remove(node);
          patch(null, vnode, container, next, parentComponent, parentSuspense, isSVGContainer(container), slotScopeIds);
          return next;
      };
      const locateClosingAsyncAnchor = (node) => {
          let match = 0;
          while (node) {
              node = nextSibling(node);
              if (node && isComment(node)) {
                  if (node.data === '[')
                      match++;
                  if (node.data === ']') {
                      if (match === 0) {
                          return nextSibling(node);
                      }
                      else {
                          match--;
                      }
                  }
              }
          }
          return node;
      };
      return [hydrate, hydrateNode];
  }

  /* eslint-disable no-restricted-globals */
  let supported;
  let perf;
  function startMeasure(instance, type) {
      if (instance.appContext.config.performance && isSupported()) {
          perf.mark(`vue-${type}-${instance.uid}`);
      }
      {
          devtoolsPerfStart(instance, type, isSupported() ? perf.now() : Date.now());
      }
  }
  function endMeasure(instance, type) {
      if (instance.appContext.config.performance && isSupported()) {
          const startTag = `vue-${type}-${instance.uid}`;
          const endTag = startTag + `:end`;
          perf.mark(endTag);
          perf.measure(`<${formatComponentName(instance, instance.type)}> ${type}`, startTag, endTag);
          perf.clearMarks(startTag);
          perf.clearMarks(endTag);
      }
      {
          devtoolsPerfEnd(instance, type, isSupported() ? perf.now() : Date.now());
      }
  }
  function isSupported() {
      if (supported !== undefined) {
          return supported;
      }
      if (typeof window !== 'undefined' && window.performance) {
          supported = true;
          perf = window.performance;
      }
      else {
          supported = false;
      }
      return supported;
  }

  const queuePostRenderEffect = queueEffectWithSuspense
      ;
  /**
   * The createRenderer function accepts two generic arguments:
   * HostNode and HostElement, corresponding to Node and Element types in the
   * host environment. For example, for runtime-dom, HostNode would be the DOM
   * `Node` interface and HostElement would be the DOM `Element` interface.
   *
   * Custom renderers can pass in the platform specific types like this:
   *
   * ``` js
   * const { render, createApp } = createRenderer<Node, Element>({
   *   patchProp,
   *   ...nodeOps
   * })
   * ```
   */
  function createRenderer(options) {
      return baseCreateRenderer(options);
  }
  // Separate API for creating hydration-enabled renderer.
  // Hydration logic is only used when calling this function, making it
  // tree-shakable.
  function createHydrationRenderer(options) {
      return baseCreateRenderer(options, createHydrationFunctions);
  }
  // implementation
  function baseCreateRenderer(options, createHydrationFns) {
      const target = getGlobalThis();
      target.__VUE__ = true;
      {
          setDevtoolsHook(target.__VUE_DEVTOOLS_GLOBAL_HOOK__, target);
      }
      const { insert: hostInsert, remove: hostRemove, patchProp: hostPatchProp, createElement: hostCreateElement, createText: hostCreateText, createComment: hostCreateComment, setText: hostSetText, setElementText: hostSetElementText, parentNode: hostParentNode, nextSibling: hostNextSibling, setScopeId: hostSetScopeId = NOOP, cloneNode: hostCloneNode, insertStaticContent: hostInsertStaticContent } = options;
      // Note: functions inside this closure should use `const xxx = () => {}`
      // style in order to prevent being inlined by minifiers.
      const patch = (n1, n2, container, anchor = null, parentComponent = null, parentSuspense = null, isSVG = false, slotScopeIds = null, optimized = isHmrUpdating ? false : !!n2.dynamicChildren) => {
          if (n1 === n2) {
              return;
          }
          // patching & not same type, unmount old tree
          if (n1 && !isSameVNodeType(n1, n2)) {
              anchor = getNextHostNode(n1);
              unmount(n1, parentComponent, parentSuspense, true);
              n1 = null;
          }
          if (n2.patchFlag === -2 /* BAIL */) {
              optimized = false;
              n2.dynamicChildren = null;
          }
          const { type, ref, shapeFlag } = n2;
          switch (type) {
              case Text:
                  processText(n1, n2, container, anchor);
                  break;
              case Comment:
                  processCommentNode(n1, n2, container, anchor);
                  break;
              case Static:
                  if (n1 == null) {
                      mountStaticNode(n2, container, anchor, isSVG);
                  }
                  else {
                      patchStaticNode(n1, n2, container, isSVG);
                  }
                  break;
              case Fragment:
                  processFragment(n1, n2, container, anchor, parentComponent, parentSuspense, isSVG, slotScopeIds, optimized);
                  break;
              default:
                  if (shapeFlag & 1 /* ELEMENT */) {
                      processElement(n1, n2, container, anchor, parentComponent, parentSuspense, isSVG, slotScopeIds, optimized);
                  }
                  else if (shapeFlag & 6 /* COMPONENT */) {
                      processComponent(n1, n2, container, anchor, parentComponent, parentSuspense, isSVG, slotScopeIds, optimized);
                  }
                  else if (shapeFlag & 64 /* TELEPORT */) {
                      type.process(n1, n2, container, anchor, parentComponent, parentSuspense, isSVG, slotScopeIds, optimized, internals);
                  }
                  else if (shapeFlag & 128 /* SUSPENSE */) {
                      type.process(n1, n2, container, anchor, parentComponent, parentSuspense, isSVG, slotScopeIds, optimized, internals);
                  }
                  else {
                      warn$1('Invalid VNode type:', type, `(${typeof type})`);
                  }
          }
          // set ref
          if (ref != null && parentComponent) {
              setRef(ref, n1 && n1.ref, parentSuspense, n2 || n1, !n2);
          }
      };
      const processText = (n1, n2, container, anchor) => {
          if (n1 == null) {
              hostInsert((n2.el = hostCreateText(n2.children)), container, anchor);
          }
          else {
              const el = (n2.el = n1.el);
              if (n2.children !== n1.children) {
                  hostSetText(el, n2.children);
              }
          }
      };
      const processCommentNode = (n1, n2, container, anchor) => {
          if (n1 == null) {
              hostInsert((n2.el = hostCreateComment(n2.children || '')), container, anchor);
          }
          else {
              // there's no support for dynamic comments
              n2.el = n1.el;
          }
      };
      const mountStaticNode = (n2, container, anchor, isSVG) => {
          [n2.el, n2.anchor] = hostInsertStaticContent(n2.children, container, anchor, isSVG, n2.el, n2.anchor);
      };
      /**
       * Dev / HMR only
       */
      const patchStaticNode = (n1, n2, container, isSVG) => {
          // static nodes are only patched during dev for HMR
          if (n2.children !== n1.children) {
              const anchor = hostNextSibling(n1.anchor);
              // remove existing
              removeStaticNode(n1);
              [n2.el, n2.anchor] = hostInsertStaticContent(n2.children, container, anchor, isSVG);
          }
          else {
              n2.el = n1.el;
              n2.anchor = n1.anchor;
          }
      };
      const moveStaticNode = ({ el, anchor }, container, nextSibling) => {
          let next;
          while (el && el !== anchor) {
              next = hostNextSibling(el);
              hostInsert(el, container, nextSibling);
              el = next;
          }
          hostInsert(anchor, container, nextSibling);
      };
      const removeStaticNode = ({ el, anchor }) => {
          let next;
          while (el && el !== anchor) {
              next = hostNextSibling(el);
              hostRemove(el);
              el = next;
          }
          hostRemove(anchor);
      };
      const processElement = (n1, n2, container, anchor, parentComponent, parentSuspense, isSVG, slotScopeIds, optimized) => {
          isSVG = isSVG || n2.type === 'svg';
          if (n1 == null) {
              mountElement(n2, container, anchor, parentComponent, parentSuspense, isSVG, slotScopeIds, optimized);
          }
          else {
              patchElement(n1, n2, parentComponent, parentSuspense, isSVG, slotScopeIds, optimized);
          }
      };
      const mountElement = (vnode, container, anchor, parentComponent, parentSuspense, isSVG, slotScopeIds, optimized) => {
          let el;
          let vnodeHook;
          const { type, props, shapeFlag, transition, patchFlag, dirs } = vnode;
          {
              el = vnode.el = hostCreateElement(vnode.type, isSVG, props && props.is, props);
              // mount children first, since some props may rely on child content
              // being already rendered, e.g. `<select value>`
              if (shapeFlag & 8 /* TEXT_CHILDREN */) {
                  hostSetElementText(el, vnode.children);
              }
              else if (shapeFlag & 16 /* ARRAY_CHILDREN */) {
                  mountChildren(vnode.children, el, null, parentComponent, parentSuspense, isSVG && type !== 'foreignObject', slotScopeIds, optimized);
              }
              if (dirs) {
                  invokeDirectiveHook(vnode, null, parentComponent, 'created');
              }
              // props
              if (props) {
                  for (const key in props) {
                      if (key !== 'value' && !isReservedProp(key)) {
                          hostPatchProp(el, key, null, props[key], isSVG, vnode.children, parentComponent, parentSuspense, unmountChildren);
                      }
                  }
                  /**
                   * Special case for setting value on DOM elements:
                   * - it can be order-sensitive (e.g. should be set *after* min/max, #2325, #4024)
                   * - it needs to be forced (#1471)
                   * #2353 proposes adding another renderer option to configure this, but
                   * the properties affects are so finite it is worth special casing it
                   * here to reduce the complexity. (Special casing it also should not
                   * affect non-DOM renderers)
                   */
                  if ('value' in props) {
                      hostPatchProp(el, 'value', null, props.value);
                  }
                  if ((vnodeHook = props.onVnodeBeforeMount)) {
                      invokeVNodeHook(vnodeHook, parentComponent, vnode);
                  }
              }
              // scopeId
              setScopeId(el, vnode, vnode.scopeId, slotScopeIds, parentComponent);
          }
          {
              Object.defineProperty(el, '__vnode', {
                  value: vnode,
                  enumerable: false
              });
              Object.defineProperty(el, '__vueParentComponent', {
                  value: parentComponent,
                  enumerable: false
              });
          }
          if (dirs) {
              invokeDirectiveHook(vnode, null, parentComponent, 'beforeMount');
          }
          // #1583 For inside suspense + suspense not resolved case, enter hook should call when suspense resolved
          // #1689 For inside suspense + suspense resolved case, just call it
          const needCallTransitionHooks = (!parentSuspense || (parentSuspense && !parentSuspense.pendingBranch)) &&
              transition &&
              !transition.persisted;
          if (needCallTransitionHooks) {
              transition.beforeEnter(el);
          }
          hostInsert(el, container, anchor);
          if ((vnodeHook = props && props.onVnodeMounted) ||
              needCallTransitionHooks ||
              dirs) {
              queuePostRenderEffect(() => {
                  vnodeHook && invokeVNodeHook(vnodeHook, parentComponent, vnode);
                  needCallTransitionHooks && transition.enter(el);
                  dirs && invokeDirectiveHook(vnode, null, parentComponent, 'mounted');
              }, parentSuspense);
          }
      };
      const setScopeId = (el, vnode, scopeId, slotScopeIds, parentComponent) => {
          if (scopeId) {
              hostSetScopeId(el, scopeId);
          }
          if (slotScopeIds) {
              for (let i = 0; i < slotScopeIds.length; i++) {
                  hostSetScopeId(el, slotScopeIds[i]);
              }
          }
          if (parentComponent) {
              let subTree = parentComponent.subTree;
              if (subTree.patchFlag > 0 &&
                  subTree.patchFlag & 2048 /* DEV_ROOT_FRAGMENT */) {
                  subTree =
                      filterSingleRoot(subTree.children) || subTree;
              }
              if (vnode === subTree) {
                  const parentVNode = parentComponent.vnode;
                  setScopeId(el, parentVNode, parentVNode.scopeId, parentVNode.slotScopeIds, parentComponent.parent);
              }
          }
      };
      const mountChildren = (children, container, anchor, parentComponent, parentSuspense, isSVG, slotScopeIds, optimized, start = 0) => {
          for (let i = start; i < children.length; i++) {
              const child = (children[i] = optimized
                  ? cloneIfMounted(children[i])
                  : normalizeVNode(children[i]));
              patch(null, child, container, anchor, parentComponent, parentSuspense, isSVG, slotScopeIds, optimized);
          }
      };
      const patchElement = (n1, n2, parentComponent, parentSuspense, isSVG, slotScopeIds, optimized) => {
          const el = (n2.el = n1.el);
          let { patchFlag, dynamicChildren, dirs } = n2;
          // #1426 take the old vnode's patch flag into account since user may clone a
          // compiler-generated vnode, which de-opts to FULL_PROPS
          patchFlag |= n1.patchFlag & 16 /* FULL_PROPS */;
          const oldProps = n1.props || EMPTY_OBJ;
          const newProps = n2.props || EMPTY_OBJ;
          let vnodeHook;
          // disable recurse in beforeUpdate hooks
          parentComponent && toggleRecurse(parentComponent, false);
          if ((vnodeHook = newProps.onVnodeBeforeUpdate)) {
              invokeVNodeHook(vnodeHook, parentComponent, n2, n1);
          }
          if (dirs) {
              invokeDirectiveHook(n2, n1, parentComponent, 'beforeUpdate');
          }
          parentComponent && toggleRecurse(parentComponent, true);
          if (isHmrUpdating) {
              // HMR updated, force full diff
              patchFlag = 0;
              optimized = false;
              dynamicChildren = null;
          }
          const areChildrenSVG = isSVG && n2.type !== 'foreignObject';
          if (dynamicChildren) {
              patchBlockChildren(n1.dynamicChildren, dynamicChildren, el, parentComponent, parentSuspense, areChildrenSVG, slotScopeIds);
              if (parentComponent && parentComponent.type.__hmrId) {
                  traverseStaticChildren(n1, n2);
              }
          }
          else if (!optimized) {
              // full diff
              patchChildren(n1, n2, el, null, parentComponent, parentSuspense, areChildrenSVG, slotScopeIds, false);
          }
          if (patchFlag > 0) {
              // the presence of a patchFlag means this element's render code was
              // generated by the compiler and can take the fast path.
              // in this path old node and new node are guaranteed to have the same shape
              // (i.e. at the exact same position in the source template)
              if (patchFlag & 16 /* FULL_PROPS */) {
                  // element props contain dynamic keys, full diff needed
                  patchProps(el, n2, oldProps, newProps, parentComponent, parentSuspense, isSVG);
              }
              else {
                  // class
                  // this flag is matched when the element has dynamic class bindings.
                  if (patchFlag & 2 /* CLASS */) {
                      if (oldProps.class !== newProps.class) {
                          hostPatchProp(el, 'class', null, newProps.class, isSVG);
                      }
                  }
                  // style
                  // this flag is matched when the element has dynamic style bindings
                  if (patchFlag & 4 /* STYLE */) {
                      hostPatchProp(el, 'style', oldProps.style, newProps.style, isSVG);
                  }
                  // props
                  // This flag is matched when the element has dynamic prop/attr bindings
                  // other than class and style. The keys of dynamic prop/attrs are saved for
                  // faster iteration.
                  // Note dynamic keys like :[foo]="bar" will cause this optimization to
                  // bail out and go through a full diff because we need to unset the old key
                  if (patchFlag & 8 /* PROPS */) {
                      // if the flag is present then dynamicProps must be non-null
                      const propsToUpdate = n2.dynamicProps;
                      for (let i = 0; i < propsToUpdate.length; i++) {
                          const key = propsToUpdate[i];
                          const prev = oldProps[key];
                          const next = newProps[key];
                          // #1471 force patch value
                          if (next !== prev || key === 'value') {
                              hostPatchProp(el, key, prev, next, isSVG, n1.children, parentComponent, parentSuspense, unmountChildren);
                          }
                      }
                  }
              }
              // text
              // This flag is matched when the element has only dynamic text children.
              if (patchFlag & 1 /* TEXT */) {
                  if (n1.children !== n2.children) {
                      hostSetElementText(el, n2.children);
                  }
              }
          }
          else if (!optimized && dynamicChildren == null) {
              // unoptimized, full diff
              patchProps(el, n2, oldProps, newProps, parentComponent, parentSuspense, isSVG);
          }
          if ((vnodeHook = newProps.onVnodeUpdated) || dirs) {
              queuePostRenderEffect(() => {
                  vnodeHook && invokeVNodeHook(vnodeHook, parentComponent, n2, n1);
                  dirs && invokeDirectiveHook(n2, n1, parentComponent, 'updated');
              }, parentSuspense);
          }
      };
      // The fast path for blocks.
      const patchBlockChildren = (oldChildren, newChildren, fallbackContainer, parentComponent, parentSuspense, isSVG, slotScopeIds) => {
          for (let i = 0; i < newChildren.length; i++) {
              const oldVNode = oldChildren[i];
              const newVNode = newChildren[i];
              // Determine the container (parent element) for the patch.
              const container = 
              // oldVNode may be an errored async setup() component inside Suspense
              // which will not have a mounted element
              oldVNode.el &&
                  // - In the case of a Fragment, we need to provide the actual parent
                  // of the Fragment itself so it can move its children.
                  (oldVNode.type === Fragment ||
                      // - In the case of different nodes, there is going to be a replacement
                      // which also requires the correct parent container
                      !isSameVNodeType(oldVNode, newVNode) ||
                      // - In the case of a component, it could contain anything.
                      oldVNode.shapeFlag & (6 /* COMPONENT */ | 64 /* TELEPORT */))
                  ? hostParentNode(oldVNode.el)
                  : // In other cases, the parent container is not actually used so we
                      // just pass the block element here to avoid a DOM parentNode call.
                      fallbackContainer;
              patch(oldVNode, newVNode, container, null, parentComponent, parentSuspense, isSVG, slotScopeIds, true);
          }
      };
      const patchProps = (el, vnode, oldProps, newProps, parentComponent, parentSuspense, isSVG) => {
          if (oldProps !== newProps) {
              for (const key in newProps) {
                  // empty string is not valid prop
                  if (isReservedProp(key))
                      continue;
                  const next = newProps[key];
                  const prev = oldProps[key];
                  // defer patching value
                  if (next !== prev && key !== 'value') {
                      hostPatchProp(el, key, prev, next, isSVG, vnode.children, parentComponent, parentSuspense, unmountChildren);
                  }
              }
              if (oldProps !== EMPTY_OBJ) {
                  for (const key in oldProps) {
                      if (!isReservedProp(key) && !(key in newProps)) {
                          hostPatchProp(el, key, oldProps[key], null, isSVG, vnode.children, parentComponent, parentSuspense, unmountChildren);
                      }
                  }
              }
              if ('value' in newProps) {
                  hostPatchProp(el, 'value', oldProps.value, newProps.value);
              }
          }
      };
      const processFragment = (n1, n2, container, anchor, parentComponent, parentSuspense, isSVG, slotScopeIds, optimized) => {
          const fragmentStartAnchor = (n2.el = n1 ? n1.el : hostCreateText(''));
          const fragmentEndAnchor = (n2.anchor = n1 ? n1.anchor : hostCreateText(''));
          let { patchFlag, dynamicChildren, slotScopeIds: fragmentSlotScopeIds } = n2;
          if (// #5523 dev root fragment may inherit directives
              (isHmrUpdating || patchFlag & 2048 /* DEV_ROOT_FRAGMENT */)) {
              // HMR updated / Dev root fragment (w/ comments), force full diff
              patchFlag = 0;
              optimized = false;
              dynamicChildren = null;
          }
          // check if this is a slot fragment with :slotted scope ids
          if (fragmentSlotScopeIds) {
              slotScopeIds = slotScopeIds
                  ? slotScopeIds.concat(fragmentSlotScopeIds)
                  : fragmentSlotScopeIds;
          }
          if (n1 == null) {
              hostInsert(fragmentStartAnchor, container, anchor);
              hostInsert(fragmentEndAnchor, container, anchor);
              // a fragment can only have array children
              // since they are either generated by the compiler, or implicitly created
              // from arrays.
              mountChildren(n2.children, container, fragmentEndAnchor, parentComponent, parentSuspense, isSVG, slotScopeIds, optimized);
          }
          else {
              if (patchFlag > 0 &&
                  patchFlag & 64 /* STABLE_FRAGMENT */ &&
                  dynamicChildren &&
                  // #2715 the previous fragment could've been a BAILed one as a result
                  // of renderSlot() with no valid children
                  n1.dynamicChildren) {
                  // a stable fragment (template root or <template v-for>) doesn't need to
                  // patch children order, but it may contain dynamicChildren.
                  patchBlockChildren(n1.dynamicChildren, dynamicChildren, container, parentComponent, parentSuspense, isSVG, slotScopeIds);
                  if (parentComponent && parentComponent.type.__hmrId) {
                      traverseStaticChildren(n1, n2);
                  }
                  else if (
                  // #2080 if the stable fragment has a key, it's a <template v-for> that may
                  //  get moved around. Make sure all root level vnodes inherit el.
                  // #2134 or if it's a component root, it may also get moved around
                  // as the component is being moved.
                  n2.key != null ||
                      (parentComponent && n2 === parentComponent.subTree)) {
                      traverseStaticChildren(n1, n2, true /* shallow */);
                  }
              }
              else {
                  // keyed / unkeyed, or manual fragments.
                  // for keyed & unkeyed, since they are compiler generated from v-for,
                  // each child is guaranteed to be a block so the fragment will never
                  // have dynamicChildren.
                  patchChildren(n1, n2, container, fragmentEndAnchor, parentComponent, parentSuspense, isSVG, slotScopeIds, optimized);
              }
          }
      };
      const processComponent = (n1, n2, container, anchor, parentComponent, parentSuspense, isSVG, slotScopeIds, optimized) => {
          n2.slotScopeIds = slotScopeIds;
          if (n1 == null) {
              if (n2.shapeFlag & 512 /* COMPONENT_KEPT_ALIVE */) {
                  parentComponent.ctx.activate(n2, container, anchor, isSVG, optimized);
              }
              else {
                  mountComponent(n2, container, anchor, parentComponent, parentSuspense, isSVG, optimized);
              }
          }
          else {
              updateComponent(n1, n2, optimized);
          }
      };
      const mountComponent = (initialVNode, container, anchor, parentComponent, parentSuspense, isSVG, optimized) => {
          // 2.x compat may pre-create the component instance before actually
          // mounting
          const compatMountInstance = initialVNode.isCompatRoot && initialVNode.component;
          const instance = compatMountInstance ||
              (initialVNode.component = createComponentInstance(initialVNode, parentComponent, parentSuspense));
          if (instance.type.__hmrId) {
              registerHMR(instance);
          }
          {
              pushWarningContext(initialVNode);
              startMeasure(instance, `mount`);
          }
          // inject renderer internals for keepAlive
          if (isKeepAlive(initialVNode)) {
              instance.ctx.renderer = internals;
          }
          // resolve props and slots for setup context
          if (!(compatMountInstance)) {
              {
                  startMeasure(instance, `init`);
              }
              setupComponent(instance);
              {
                  endMeasure(instance, `init`);
              }
          }
          // setup() is async. This component relies on async logic to be resolved
          // before proceeding
          if (instance.asyncDep) {
              parentSuspense && parentSuspense.registerDep(instance, setupRenderEffect);
              // Give it a placeholder if this is not hydration
              // TODO handle self-defined fallback
              if (!initialVNode.el) {
                  const placeholder = (instance.subTree = createVNode(Comment));
                  processCommentNode(null, placeholder, container, anchor);
              }
              return;
          }
          setupRenderEffect(instance, initialVNode, container, anchor, parentSuspense, isSVG, optimized);
          {
              popWarningContext();
              endMeasure(instance, `mount`);
          }
      };
      const updateComponent = (n1, n2, optimized) => {
          const instance = (n2.component = n1.component);
          if (shouldUpdateComponent(n1, n2, optimized)) {
              if (instance.asyncDep &&
                  !instance.asyncResolved) {
                  // async & still pending - just update props and slots
                  // since the component's reactive effect for render isn't set-up yet
                  {
                      pushWarningContext(n2);
                  }
                  updateComponentPreRender(instance, n2, optimized);
                  {
                      popWarningContext();
                  }
                  return;
              }
              else {
                  // normal update
                  instance.next = n2;
                  // in case the child component is also queued, remove it to avoid
                  // double updating the same child component in the same flush.
                  invalidateJob(instance.update);
                  // instance.update is the reactive effect.
                  instance.update();
              }
          }
          else {
              // no update needed. just copy over properties
              n2.el = n1.el;
              instance.vnode = n2;
          }
      };
      const setupRenderEffect = (instance, initialVNode, container, anchor, parentSuspense, isSVG, optimized) => {
          const componentUpdateFn = () => {
              if (!instance.isMounted) {
                  let vnodeHook;
                  const { el, props } = initialVNode;
                  const { bm, m, parent } = instance;
                  const isAsyncWrapperVNode = isAsyncWrapper(initialVNode);
                  toggleRecurse(instance, false);
                  // beforeMount hook
                  if (bm) {
                      invokeArrayFns(bm);
                  }
                  // onVnodeBeforeMount
                  if (!isAsyncWrapperVNode &&
                      (vnodeHook = props && props.onVnodeBeforeMount)) {
                      invokeVNodeHook(vnodeHook, parent, initialVNode);
                  }
                  if (isCompatEnabled("INSTANCE_EVENT_HOOKS" /* INSTANCE_EVENT_HOOKS */, instance)) {
                      instance.emit('hook:beforeMount');
                  }
                  toggleRecurse(instance, true);
                  if (el && hydrateNode) {
                      // vnode has adopted host node - perform hydration instead of mount.
                      const hydrateSubTree = () => {
                          {
                              startMeasure(instance, `render`);
                          }
                          instance.subTree = renderComponentRoot(instance);
                          {
                              endMeasure(instance, `render`);
                          }
                          {
                              startMeasure(instance, `hydrate`);
                          }
                          hydrateNode(el, instance.subTree, instance, parentSuspense, null);
                          {
                              endMeasure(instance, `hydrate`);
                          }
                      };
                      if (isAsyncWrapperVNode) {
                          initialVNode.type.__asyncLoader().then(
                          // note: we are moving the render call into an async callback,
                          // which means it won't track dependencies - but it's ok because
                          // a server-rendered async wrapper is already in resolved state
                          // and it will never need to change.
                          () => !instance.isUnmounted && hydrateSubTree());
                      }
                      else {
                          hydrateSubTree();
                      }
                  }
                  else {
                      {
                          startMeasure(instance, `render`);
                      }
                      const subTree = (instance.subTree = renderComponentRoot(instance));
                      {
                          endMeasure(instance, `render`);
                      }
                      {
                          startMeasure(instance, `patch`);
                      }
                      patch(null, subTree, container, anchor, instance, parentSuspense, isSVG);
                      {
                          endMeasure(instance, `patch`);
                      }
                      initialVNode.el = subTree.el;
                  }
                  // mounted hook
                  if (m) {
                      queuePostRenderEffect(m, parentSuspense);
                  }
                  // onVnodeMounted
                  if (!isAsyncWrapperVNode &&
                      (vnodeHook = props && props.onVnodeMounted)) {
                      const scopedInitialVNode = initialVNode;
                      queuePostRenderEffect(() => invokeVNodeHook(vnodeHook, parent, scopedInitialVNode), parentSuspense);
                  }
                  if (isCompatEnabled("INSTANCE_EVENT_HOOKS" /* INSTANCE_EVENT_HOOKS */, instance)) {
                      queuePostRenderEffect(() => instance.emit('hook:mounted'), parentSuspense);
                  }
                  // activated hook for keep-alive roots.
                  // #1742 activated hook must be accessed after first render
                  // since the hook may be injected by a child keep-alive
                  if (initialVNode.shapeFlag & 256 /* COMPONENT_SHOULD_KEEP_ALIVE */ ||
                      (parent &&
                          isAsyncWrapper(parent.vnode) &&
                          parent.vnode.shapeFlag & 256 /* COMPONENT_SHOULD_KEEP_ALIVE */)) {
                      instance.a && queuePostRenderEffect(instance.a, parentSuspense);
                      if (isCompatEnabled("INSTANCE_EVENT_HOOKS" /* INSTANCE_EVENT_HOOKS */, instance)) {
                          queuePostRenderEffect(() => instance.emit('hook:activated'), parentSuspense);
                      }
                  }
                  instance.isMounted = true;
                  {
                      devtoolsComponentAdded(instance);
                  }
                  // #2458: deference mount-only object parameters to prevent memleaks
                  initialVNode = container = anchor = null;
              }
              else {
                  // updateComponent
                  // This is triggered by mutation of component's own state (next: null)
                  // OR parent calling processComponent (next: VNode)
                  let { next, bu, u, parent, vnode } = instance;
                  let originNext = next;
                  let vnodeHook;
                  {
                      pushWarningContext(next || instance.vnode);
                  }
                  // Disallow component effect recursion during pre-lifecycle hooks.
                  toggleRecurse(instance, false);
                  if (next) {
                      next.el = vnode.el;
                      updateComponentPreRender(instance, next, optimized);
                  }
                  else {
                      next = vnode;
                  }
                  // beforeUpdate hook
                  if (bu) {
                      invokeArrayFns(bu);
                  }
                  // onVnodeBeforeUpdate
                  if ((vnodeHook = next.props && next.props.onVnodeBeforeUpdate)) {
                      invokeVNodeHook(vnodeHook, parent, next, vnode);
                  }
                  if (isCompatEnabled("INSTANCE_EVENT_HOOKS" /* INSTANCE_EVENT_HOOKS */, instance)) {
                      instance.emit('hook:beforeUpdate');
                  }
                  toggleRecurse(instance, true);
                  // render
                  {
                      startMeasure(instance, `render`);
                  }
                  const nextTree = renderComponentRoot(instance);
                  {
                      endMeasure(instance, `render`);
                  }
                  const prevTree = instance.subTree;
                  instance.subTree = nextTree;
                  {
                      startMeasure(instance, `patch`);
                  }
                  patch(prevTree, nextTree, 
                  // parent may have changed if it's in a teleport
                  hostParentNode(prevTree.el), 
                  // anchor may have changed if it's in a fragment
                  getNextHostNode(prevTree), instance, parentSuspense, isSVG);
                  {
                      endMeasure(instance, `patch`);
                  }
                  next.el = nextTree.el;
                  if (originNext === null) {
                      // self-triggered update. In case of HOC, update parent component
                      // vnode el. HOC is indicated by parent instance's subTree pointing
                      // to child component's vnode
                      updateHOCHostEl(instance, nextTree.el);
                  }
                  // updated hook
                  if (u) {
                      queuePostRenderEffect(u, parentSuspense);
                  }
                  // onVnodeUpdated
                  if ((vnodeHook = next.props && next.props.onVnodeUpdated)) {
                      queuePostRenderEffect(() => invokeVNodeHook(vnodeHook, parent, next, vnode), parentSuspense);
                  }
                  if (isCompatEnabled("INSTANCE_EVENT_HOOKS" /* INSTANCE_EVENT_HOOKS */, instance)) {
                      queuePostRenderEffect(() => instance.emit('hook:updated'), parentSuspense);
                  }
                  {
                      devtoolsComponentUpdated(instance);
                  }
                  {
                      popWarningContext();
                  }
              }
          };
          // create reactive effect for rendering
          const effect = (instance.effect = new ReactiveEffect(componentUpdateFn, () => queueJob(update), instance.scope // track it in component's effect scope
          ));
          const update = (instance.update = () => effect.run());
          update.id = instance.uid;
          // allowRecurse
          // #1801, #2043 component render effects should allow recursive updates
          toggleRecurse(instance, true);
          {
              effect.onTrack = instance.rtc
                  ? e => invokeArrayFns(instance.rtc, e)
                  : void 0;
              effect.onTrigger = instance.rtg
                  ? e => invokeArrayFns(instance.rtg, e)
                  : void 0;
              update.ownerInstance = instance;
          }
          update();
      };
      const updateComponentPreRender = (instance, nextVNode, optimized) => {
          nextVNode.component = instance;
          const prevProps = instance.vnode.props;
          instance.vnode = nextVNode;
          instance.next = null;
          updateProps(instance, nextVNode.props, prevProps, optimized);
          updateSlots(instance, nextVNode.children, optimized);
          pauseTracking();
          // props update may have triggered pre-flush watchers.
          // flush them before the render update.
          flushPreFlushCbs(undefined, instance.update);
          resetTracking();
      };
      const patchChildren = (n1, n2, container, anchor, parentComponent, parentSuspense, isSVG, slotScopeIds, optimized = false) => {
          const c1 = n1 && n1.children;
          const prevShapeFlag = n1 ? n1.shapeFlag : 0;
          const c2 = n2.children;
          const { patchFlag, shapeFlag } = n2;
          // fast path
          if (patchFlag > 0) {
              if (patchFlag & 128 /* KEYED_FRAGMENT */) {
                  // this could be either fully-keyed or mixed (some keyed some not)
                  // presence of patchFlag means children are guaranteed to be arrays
                  patchKeyedChildren(c1, c2, container, anchor, parentComponent, parentSuspense, isSVG, slotScopeIds, optimized);
                  return;
              }
              else if (patchFlag & 256 /* UNKEYED_FRAGMENT */) {
                  // unkeyed
                  patchUnkeyedChildren(c1, c2, container, anchor, parentComponent, parentSuspense, isSVG, slotScopeIds, optimized);
                  return;
              }
          }
          // children has 3 possibilities: text, array or no children.
          if (shapeFlag & 8 /* TEXT_CHILDREN */) {
              // text children fast path
              if (prevShapeFlag & 16 /* ARRAY_CHILDREN */) {
                  unmountChildren(c1, parentComponent, parentSuspense);
              }
              if (c2 !== c1) {
                  hostSetElementText(container, c2);
              }
          }
          else {
              if (prevShapeFlag & 16 /* ARRAY_CHILDREN */) {
                  // prev children was array
                  if (shapeFlag & 16 /* ARRAY_CHILDREN */) {
                      // two arrays, cannot assume anything, do full diff
                      patchKeyedChildren(c1, c2, container, anchor, parentComponent, parentSuspense, isSVG, slotScopeIds, optimized);
                  }
                  else {
                      // no new children, just unmount old
                      unmountChildren(c1, parentComponent, parentSuspense, true);
                  }
              }
              else {
                  // prev children was text OR null
                  // new children is array OR null
                  if (prevShapeFlag & 8 /* TEXT_CHILDREN */) {
                      hostSetElementText(container, '');
                  }
                  // mount new if array
                  if (shapeFlag & 16 /* ARRAY_CHILDREN */) {
                      mountChildren(c2, container, anchor, parentComponent, parentSuspense, isSVG, slotScopeIds, optimized);
                  }
              }
          }
      };
      const patchUnkeyedChildren = (c1, c2, container, anchor, parentComponent, parentSuspense, isSVG, slotScopeIds, optimized) => {
          c1 = c1 || EMPTY_ARR;
          c2 = c2 || EMPTY_ARR;
          const oldLength = c1.length;
          const newLength = c2.length;
          const commonLength = Math.min(oldLength, newLength);
          let i;
          for (i = 0; i < commonLength; i++) {
              const nextChild = (c2[i] = optimized
                  ? cloneIfMounted(c2[i])
                  : normalizeVNode(c2[i]));
              patch(c1[i], nextChild, container, null, parentComponent, parentSuspense, isSVG, slotScopeIds, optimized);
          }
          if (oldLength > newLength) {
              // remove old
              unmountChildren(c1, parentComponent, parentSuspense, true, false, commonLength);
          }
          else {
              // mount new
              mountChildren(c2, container, anchor, parentComponent, parentSuspense, isSVG, slotScopeIds, optimized, commonLength);
          }
      };
      // can be all-keyed or mixed
      const patchKeyedChildren = (c1, c2, container, parentAnchor, parentComponent, parentSuspense, isSVG, slotScopeIds, optimized) => {
          let i = 0;
          const l2 = c2.length;
          let e1 = c1.length - 1; // prev ending index
          let e2 = l2 - 1; // next ending index
          // 1. sync from start
          // (a b) c
          // (a b) d e
          while (i <= e1 && i <= e2) {
              const n1 = c1[i];
              const n2 = (c2[i] = optimized
                  ? cloneIfMounted(c2[i])
                  : normalizeVNode(c2[i]));
              if (isSameVNodeType(n1, n2)) {
                  patch(n1, n2, container, null, parentComponent, parentSuspense, isSVG, slotScopeIds, optimized);
              }
              else {
                  break;
              }
              i++;
          }
          // 2. sync from end
          // a (b c)
          // d e (b c)
          while (i <= e1 && i <= e2) {
              const n1 = c1[e1];
              const n2 = (c2[e2] = optimized
                  ? cloneIfMounted(c2[e2])
                  : normalizeVNode(c2[e2]));
              if (isSameVNodeType(n1, n2)) {
                  patch(n1, n2, container, null, parentComponent, parentSuspense, isSVG, slotScopeIds, optimized);
              }
              else {
                  break;
              }
              e1--;
              e2--;
          }
          // 3. common sequence + mount
          // (a b)
          // (a b) c
          // i = 2, e1 = 1, e2 = 2
          // (a b)
          // c (a b)
          // i = 0, e1 = -1, e2 = 0
          if (i > e1) {
              if (i <= e2) {
                  const nextPos = e2 + 1;
                  const anchor = nextPos < l2 ? c2[nextPos].el : parentAnchor;
                  while (i <= e2) {
                      patch(null, (c2[i] = optimized
                          ? cloneIfMounted(c2[i])
                          : normalizeVNode(c2[i])), container, anchor, parentComponent, parentSuspense, isSVG, slotScopeIds, optimized);
                      i++;
                  }
              }
          }
          // 4. common sequence + unmount
          // (a b) c
          // (a b)
          // i = 2, e1 = 2, e2 = 1
          // a (b c)
          // (b c)
          // i = 0, e1 = 0, e2 = -1
          else if (i > e2) {
              while (i <= e1) {
                  unmount(c1[i], parentComponent, parentSuspense, true);
                  i++;
              }
          }
          // 5. unknown sequence
          // [i ... e1 + 1]: a b [c d e] f g
          // [i ... e2 + 1]: a b [e d c h] f g
          // i = 2, e1 = 4, e2 = 5
          else {
              const s1 = i; // prev starting index
              const s2 = i; // next starting index
              // 5.1 build key:index map for newChildren
              const keyToNewIndexMap = new Map();
              for (i = s2; i <= e2; i++) {
                  const nextChild = (c2[i] = optimized
                      ? cloneIfMounted(c2[i])
                      : normalizeVNode(c2[i]));
                  if (nextChild.key != null) {
                      if (keyToNewIndexMap.has(nextChild.key)) {
                          warn$1(`Duplicate keys found during update:`, JSON.stringify(nextChild.key), `Make sure keys are unique.`);
                      }
                      keyToNewIndexMap.set(nextChild.key, i);
                  }
              }
              // 5.2 loop through old children left to be patched and try to patch
              // matching nodes & remove nodes that are no longer present
              let j;
              let patched = 0;
              const toBePatched = e2 - s2 + 1;
              let moved = false;
              // used to track whether any node has moved
              let maxNewIndexSoFar = 0;
              // works as Map<newIndex, oldIndex>
              // Note that oldIndex is offset by +1
              // and oldIndex = 0 is a special value indicating the new node has
              // no corresponding old node.
              // used for determining longest stable subsequence
              const newIndexToOldIndexMap = new Array(toBePatched);
              for (i = 0; i < toBePatched; i++)
                  newIndexToOldIndexMap[i] = 0;
              for (i = s1; i <= e1; i++) {
                  const prevChild = c1[i];
                  if (patched >= toBePatched) {
                      // all new children have been patched so this can only be a removal
                      unmount(prevChild, parentComponent, parentSuspense, true);
                      continue;
                  }
                  let newIndex;
                  if (prevChild.key != null) {
                      newIndex = keyToNewIndexMap.get(prevChild.key);
                  }
                  else {
                      // key-less node, try to locate a key-less node of the same type
                      for (j = s2; j <= e2; j++) {
                          if (newIndexToOldIndexMap[j - s2] === 0 &&
                              isSameVNodeType(prevChild, c2[j])) {
                              newIndex = j;
                              break;
                          }
                      }
                  }
                  if (newIndex === undefined) {
                      unmount(prevChild, parentComponent, parentSuspense, true);
                  }
                  else {
                      newIndexToOldIndexMap[newIndex - s2] = i + 1;
                      if (newIndex >= maxNewIndexSoFar) {
                          maxNewIndexSoFar = newIndex;
                      }
                      else {
                          moved = true;
                      }
                      patch(prevChild, c2[newIndex], container, null, parentComponent, parentSuspense, isSVG, slotScopeIds, optimized);
                      patched++;
                  }
              }
              // 5.3 move and mount
              // generate longest stable subsequence only when nodes have moved
              const increasingNewIndexSequence = moved
                  ? getSequence(newIndexToOldIndexMap)
                  : EMPTY_ARR;
              j = increasingNewIndexSequence.length - 1;
              // looping backwards so that we can use last patched node as anchor
              for (i = toBePatched - 1; i >= 0; i--) {
                  const nextIndex = s2 + i;
                  const nextChild = c2[nextIndex];
                  const anchor = nextIndex + 1 < l2 ? c2[nextIndex + 1].el : parentAnchor;
                  if (newIndexToOldIndexMap[i] === 0) {
                      // mount new
                      patch(null, nextChild, container, anchor, parentComponent, parentSuspense, isSVG, slotScopeIds, optimized);
                  }
                  else if (moved) {
                      // move if:
                      // There is no stable subsequence (e.g. a reverse)
                      // OR current node is not among the stable sequence
                      if (j < 0 || i !== increasingNewIndexSequence[j]) {
                          move(nextChild, container, anchor, 2 /* REORDER */);
                      }
                      else {
                          j--;
                      }
                  }
              }
          }
      };
      const move = (vnode, container, anchor, moveType, parentSuspense = null) => {
          const { el, type, transition, children, shapeFlag } = vnode;
          if (shapeFlag & 6 /* COMPONENT */) {
              move(vnode.component.subTree, container, anchor, moveType);
              return;
          }
          if (shapeFlag & 128 /* SUSPENSE */) {
              vnode.suspense.move(container, anchor, moveType);
              return;
          }
          if (shapeFlag & 64 /* TELEPORT */) {
              type.move(vnode, container, anchor, internals);
              return;
          }
          if (type === Fragment) {
              hostInsert(el, container, anchor);
              for (let i = 0; i < children.length; i++) {
                  move(children[i], container, anchor, moveType);
              }
              hostInsert(vnode.anchor, container, anchor);
              return;
          }
          if (type === Static) {
              moveStaticNode(vnode, container, anchor);
              return;
          }
          // single nodes
          const needTransition = moveType !== 2 /* REORDER */ &&
              shapeFlag & 1 /* ELEMENT */ &&
              transition;
          if (needTransition) {
              if (moveType === 0 /* ENTER */) {
                  transition.beforeEnter(el);
                  hostInsert(el, container, anchor);
                  queuePostRenderEffect(() => transition.enter(el), parentSuspense);
              }
              else {
                  const { leave, delayLeave, afterLeave } = transition;
                  const remove = () => hostInsert(el, container, anchor);
                  const performLeave = () => {
                      leave(el, () => {
                          remove();
                          afterLeave && afterLeave();
                      });
                  };
                  if (delayLeave) {
                      delayLeave(el, remove, performLeave);
                  }
                  else {
                      performLeave();
                  }
              }
          }
          else {
              hostInsert(el, container, anchor);
          }
      };
      const unmount = (vnode, parentComponent, parentSuspense, doRemove = false, optimized = false) => {
          const { type, props, ref, children, dynamicChildren, shapeFlag, patchFlag, dirs } = vnode;
          // unset ref
          if (ref != null) {
              setRef(ref, null, parentSuspense, vnode, true);
          }
          if (shapeFlag & 256 /* COMPONENT_SHOULD_KEEP_ALIVE */) {
              parentComponent.ctx.deactivate(vnode);
              return;
          }
          const shouldInvokeDirs = shapeFlag & 1 /* ELEMENT */ && dirs;
          const shouldInvokeVnodeHook = !isAsyncWrapper(vnode);
          let vnodeHook;
          if (shouldInvokeVnodeHook &&
              (vnodeHook = props && props.onVnodeBeforeUnmount)) {
              invokeVNodeHook(vnodeHook, parentComponent, vnode);
          }
          if (shapeFlag & 6 /* COMPONENT */) {
              unmountComponent(vnode.component, parentSuspense, doRemove);
          }
          else {
              if (shapeFlag & 128 /* SUSPENSE */) {
                  vnode.suspense.unmount(parentSuspense, doRemove);
                  return;
              }
              if (shouldInvokeDirs) {
                  invokeDirectiveHook(vnode, null, parentComponent, 'beforeUnmount');
              }
              if (shapeFlag & 64 /* TELEPORT */) {
                  vnode.type.remove(vnode, parentComponent, parentSuspense, optimized, internals, doRemove);
              }
              else if (dynamicChildren &&
                  // #1153: fast path should not be taken for non-stable (v-for) fragments
                  (type !== Fragment ||
                      (patchFlag > 0 && patchFlag & 64 /* STABLE_FRAGMENT */))) {
                  // fast path for block nodes: only need to unmount dynamic children.
                  unmountChildren(dynamicChildren, parentComponent, parentSuspense, false, true);
              }
              else if ((type === Fragment &&
                  patchFlag &
                      (128 /* KEYED_FRAGMENT */ | 256 /* UNKEYED_FRAGMENT */)) ||
                  (!optimized && shapeFlag & 16 /* ARRAY_CHILDREN */)) {
                  unmountChildren(children, parentComponent, parentSuspense);
              }
              if (doRemove) {
                  remove(vnode);
              }
          }
          if ((shouldInvokeVnodeHook &&
              (vnodeHook = props && props.onVnodeUnmounted)) ||
              shouldInvokeDirs) {
              queuePostRenderEffect(() => {
                  vnodeHook && invokeVNodeHook(vnodeHook, parentComponent, vnode);
                  shouldInvokeDirs &&
                      invokeDirectiveHook(vnode, null, parentComponent, 'unmounted');
              }, parentSuspense);
          }
      };
      const remove = vnode => {
          const { type, el, anchor, transition } = vnode;
          if (type === Fragment) {
              if (vnode.patchFlag > 0 &&
                  vnode.patchFlag & 2048 /* DEV_ROOT_FRAGMENT */ &&
                  transition &&
                  !transition.persisted) {
                  vnode.children.forEach(child => {
                      if (child.type === Comment) {
                          hostRemove(child.el);
                      }
                      else {
                          remove(child);
                      }
                  });
              }
              else {
                  removeFragment(el, anchor);
              }
              return;
          }
          if (type === Static) {
              removeStaticNode(vnode);
              return;
          }
          const performRemove = () => {
              hostRemove(el);
              if (transition && !transition.persisted && transition.afterLeave) {
                  transition.afterLeave();
              }
          };
          if (vnode.shapeFlag & 1 /* ELEMENT */ &&
              transition &&
              !transition.persisted) {
              const { leave, delayLeave } = transition;
              const performLeave = () => leave(el, performRemove);
              if (delayLeave) {
                  delayLeave(vnode.el, performRemove, performLeave);
              }
              else {
                  performLeave();
              }
          }
          else {
              performRemove();
          }
      };
      const removeFragment = (cur, end) => {
          // For fragments, directly remove all contained DOM nodes.
          // (fragment child nodes cannot have transition)
          let next;
          while (cur !== end) {
              next = hostNextSibling(cur);
              hostRemove(cur);
              cur = next;
          }
          hostRemove(end);
      };
      const unmountComponent = (instance, parentSuspense, doRemove) => {
          if (instance.type.__hmrId) {
              unregisterHMR(instance);
          }
          const { bum, scope, update, subTree, um } = instance;
          // beforeUnmount hook
          if (bum) {
              invokeArrayFns(bum);
          }
          if (isCompatEnabled("INSTANCE_EVENT_HOOKS" /* INSTANCE_EVENT_HOOKS */, instance)) {
              instance.emit('hook:beforeDestroy');
          }
          // stop effects in component scope
          scope.stop();
          // update may be null if a component is unmounted before its async
          // setup has resolved.
          if (update) {
              // so that scheduler will no longer invoke it
              update.active = false;
              unmount(subTree, instance, parentSuspense, doRemove);
          }
          // unmounted hook
          if (um) {
              queuePostRenderEffect(um, parentSuspense);
          }
          if (isCompatEnabled("INSTANCE_EVENT_HOOKS" /* INSTANCE_EVENT_HOOKS */, instance)) {
              queuePostRenderEffect(() => instance.emit('hook:destroyed'), parentSuspense);
          }
          queuePostRenderEffect(() => {
              instance.isUnmounted = true;
          }, parentSuspense);
          // A component with async dep inside a pending suspense is unmounted before
          // its async dep resolves. This should remove the dep from the suspense, and
          // cause the suspense to resolve immediately if that was the last dep.
          if (parentSuspense &&
              parentSuspense.pendingBranch &&
              !parentSuspense.isUnmounted &&
              instance.asyncDep &&
              !instance.asyncResolved &&
              instance.suspenseId === parentSuspense.pendingId) {
              parentSuspense.deps--;
              if (parentSuspense.deps === 0) {
                  parentSuspense.resolve();
              }
          }
          {
              devtoolsComponentRemoved(instance);
          }
      };
      const unmountChildren = (children, parentComponent, parentSuspense, doRemove = false, optimized = false, start = 0) => {
          for (let i = start; i < children.length; i++) {
              unmount(children[i], parentComponent, parentSuspense, doRemove, optimized);
          }
      };
      const getNextHostNode = vnode => {
          if (vnode.shapeFlag & 6 /* COMPONENT */) {
              return getNextHostNode(vnode.component.subTree);
          }
          if (vnode.shapeFlag & 128 /* SUSPENSE */) {
              return vnode.suspense.next();
          }
          return hostNextSibling((vnode.anchor || vnode.el));
      };
      const render = (vnode, container, isSVG) => {
          if (vnode == null) {
              if (container._vnode) {
                  unmount(container._vnode, null, null, true);
              }
          }
          else {
              patch(container._vnode || null, vnode, container, null, null, null, isSVG);
          }
          flushPostFlushCbs();
          container._vnode = vnode;
      };
      const internals = {
          p: patch,
          um: unmount,
          m: move,
          r: remove,
          mt: mountComponent,
          mc: mountChildren,
          pc: patchChildren,
          pbc: patchBlockChildren,
          n: getNextHostNode,
          o: options
      };
      let hydrate;
      let hydrateNode;
      if (createHydrationFns) {
          [hydrate, hydrateNode] = createHydrationFns(internals);
      }
      return {
          render,
          hydrate,
          createApp: createAppAPI(render, hydrate)
      };
  }
  function toggleRecurse({ effect, update }, allowed) {
      effect.allowRecurse = update.allowRecurse = allowed;
  }
  /**
   * #1156
   * When a component is HMR-enabled, we need to make sure that all static nodes
   * inside a block also inherit the DOM element from the previous tree so that
   * HMR updates (which are full updates) can retrieve the element for patching.
   *
   * #2080
   * Inside keyed `template` fragment static children, if a fragment is moved,
   * the children will always be moved. Therefore, in order to ensure correct move
   * position, el should be inherited from previous nodes.
   */
  function traverseStaticChildren(n1, n2, shallow = false) {
      const ch1 = n1.children;
      const ch2 = n2.children;
      if (isArray(ch1) && isArray(ch2)) {
          for (let i = 0; i < ch1.length; i++) {
              // this is only called in the optimized path so array children are
              // guaranteed to be vnodes
              const c1 = ch1[i];
              let c2 = ch2[i];
              if (c2.shapeFlag & 1 /* ELEMENT */ && !c2.dynamicChildren) {
                  if (c2.patchFlag <= 0 || c2.patchFlag === 32 /* HYDRATE_EVENTS */) {
                      c2 = ch2[i] = cloneIfMounted(ch2[i]);
                      c2.el = c1.el;
                  }
                  if (!shallow)
                      traverseStaticChildren(c1, c2);
              }
              // also inherit for comment nodes, but not placeholders (e.g. v-if which
              // would have received .el during block patch)
              if (c2.type === Comment && !c2.el) {
                  c2.el = c1.el;
              }
          }
      }
  }
  // https://en.wikipedia.org/wiki/Longest_increasing_subsequence
  function getSequence(arr) {
      const p = arr.slice();
      const result = [0];
      let i, j, u, v, c;
      const len = arr.length;
      for (i = 0; i < len; i++) {
          const arrI = arr[i];
          if (arrI !== 0) {
              j = result[result.length - 1];
              if (arr[j] < arrI) {
                  p[i] = j;
                  result.push(i);
                  continue;
              }
              u = 0;
              v = result.length - 1;
              while (u < v) {
                  c = (u + v) >> 1;
                  if (arr[result[c]] < arrI) {
                      u = c + 1;
                  }
                  else {
                      v = c;
                  }
              }
              if (arrI < arr[result[u]]) {
                  if (u > 0) {
                      p[i] = result[u - 1];
                  }
                  result[u] = i;
              }
          }
      }
      u = result.length;
      v = result[u - 1];
      while (u-- > 0) {
          result[u] = v;
          v = p[v];
      }
      return result;
  }

  const isTeleport = (type) => type.__isTeleport;
  const isTeleportDisabled = (props) => props && (props.disabled || props.disabled === '');
  const isTargetSVG = (target) => typeof SVGElement !== 'undefined' && target instanceof SVGElement;
  const resolveTarget = (props, select) => {
      const targetSelector = props && props.to;
      if (isString(targetSelector)) {
          if (!select) {
              warn$1(`Current renderer does not support string target for Teleports. ` +
                      `(missing querySelector renderer option)`);
              return null;
          }
          else {
              const target = select(targetSelector);
              if (!target) {
                  warn$1(`Failed to locate Teleport target with selector "${targetSelector}". ` +
                          `Note the target element must exist before the component is mounted - ` +
                          `i.e. the target cannot be rendered by the component itself, and ` +
                          `ideally should be outside of the entire Vue component tree.`);
              }
              return target;
          }
      }
      else {
          if (!targetSelector && !isTeleportDisabled(props)) {
              warn$1(`Invalid Teleport target: ${targetSelector}`);
          }
          return targetSelector;
      }
  };
  const TeleportImpl = {
      __isTeleport: true,
      process(n1, n2, container, anchor, parentComponent, parentSuspense, isSVG, slotScopeIds, optimized, internals) {
          const { mc: mountChildren, pc: patchChildren, pbc: patchBlockChildren, o: { insert, querySelector, createText, createComment } } = internals;
          const disabled = isTeleportDisabled(n2.props);
          let { shapeFlag, children, dynamicChildren } = n2;
          // #3302
          // HMR updated, force full diff
          if (isHmrUpdating) {
              optimized = false;
              dynamicChildren = null;
          }
          if (n1 == null) {
              // insert anchors in the main view
              const placeholder = (n2.el = createComment('teleport start')
                  );
              const mainAnchor = (n2.anchor = createComment('teleport end')
                  );
              insert(placeholder, container, anchor);
              insert(mainAnchor, container, anchor);
              const target = (n2.target = resolveTarget(n2.props, querySelector));
              const targetAnchor = (n2.targetAnchor = createText(''));
              if (target) {
                  insert(targetAnchor, target);
                  // #2652 we could be teleporting from a non-SVG tree into an SVG tree
                  isSVG = isSVG || isTargetSVG(target);
              }
              else if (!disabled) {
                  warn$1('Invalid Teleport target on mount:', target, `(${typeof target})`);
              }
              const mount = (container, anchor) => {
                  // Teleport *always* has Array children. This is enforced in both the
                  // compiler and vnode children normalization.
                  if (shapeFlag & 16 /* ARRAY_CHILDREN */) {
                      mountChildren(children, container, anchor, parentComponent, parentSuspense, isSVG, slotScopeIds, optimized);
                  }
              };
              if (disabled) {
                  mount(container, mainAnchor);
              }
              else if (target) {
                  mount(target, targetAnchor);
              }
          }
          else {
              // update content
              n2.el = n1.el;
              const mainAnchor = (n2.anchor = n1.anchor);
              const target = (n2.target = n1.target);
              const targetAnchor = (n2.targetAnchor = n1.targetAnchor);
              const wasDisabled = isTeleportDisabled(n1.props);
              const currentContainer = wasDisabled ? container : target;
              const currentAnchor = wasDisabled ? mainAnchor : targetAnchor;
              isSVG = isSVG || isTargetSVG(target);
              if (dynamicChildren) {
                  // fast path when the teleport happens to be a block root
                  patchBlockChildren(n1.dynamicChildren, dynamicChildren, currentContainer, parentComponent, parentSuspense, isSVG, slotScopeIds);
                  // even in block tree mode we need to make sure all root-level nodes
                  // in the teleport inherit previous DOM references so that they can
                  // be moved in future patches.
                  traverseStaticChildren(n1, n2, true);
              }
              else if (!optimized) {
                  patchChildren(n1, n2, currentContainer, currentAnchor, parentComponent, parentSuspense, isSVG, slotScopeIds, false);
              }
              if (disabled) {
                  if (!wasDisabled) {
                      // enabled -> disabled
                      // move into main container
                      moveTeleport(n2, container, mainAnchor, internals, 1 /* TOGGLE */);
                  }
              }
              else {
                  // target changed
                  if ((n2.props && n2.props.to) !== (n1.props && n1.props.to)) {
                      const nextTarget = (n2.target = resolveTarget(n2.props, querySelector));
                      if (nextTarget) {
                          moveTeleport(n2, nextTarget, null, internals, 0 /* TARGET_CHANGE */);
                      }
                      else {
                          warn$1('Invalid Teleport target on update:', target, `(${typeof target})`);
                      }
                  }
                  else if (wasDisabled) {
                      // disabled -> enabled
                      // move into teleport target
                      moveTeleport(n2, target, targetAnchor, internals, 1 /* TOGGLE */);
                  }
              }
          }
      },
      remove(vnode, parentComponent, parentSuspense, optimized, { um: unmount, o: { remove: hostRemove } }, doRemove) {
          const { shapeFlag, children, anchor, targetAnchor, target, props } = vnode;
          if (target) {
              hostRemove(targetAnchor);
          }
          // an unmounted teleport should always remove its children if not disabled
          if (doRemove || !isTeleportDisabled(props)) {
              hostRemove(anchor);
              if (shapeFlag & 16 /* ARRAY_CHILDREN */) {
                  for (let i = 0; i < children.length; i++) {
                      const child = children[i];
                      unmount(child, parentComponent, parentSuspense, true, !!child.dynamicChildren);
                  }
              }
          }
      },
      move: moveTeleport,
      hydrate: hydrateTeleport
  };
  function moveTeleport(vnode, container, parentAnchor, { o: { insert }, m: move }, moveType = 2 /* REORDER */) {
      // move target anchor if this is a target change.
      if (moveType === 0 /* TARGET_CHANGE */) {
          insert(vnode.targetAnchor, container, parentAnchor);
      }
      const { el, anchor, shapeFlag, children, props } = vnode;
      const isReorder = moveType === 2 /* REORDER */;
      // move main view anchor if this is a re-order.
      if (isReorder) {
          insert(el, container, parentAnchor);
      }
      // if this is a re-order and teleport is enabled (content is in target)
      // do not move children. So the opposite is: only move children if this
      // is not a reorder, or the teleport is disabled
      if (!isReorder || isTeleportDisabled(props)) {
          // Teleport has either Array children or no children.
          if (shapeFlag & 16 /* ARRAY_CHILDREN */) {
              for (let i = 0; i < children.length; i++) {
                  move(children[i], container, parentAnchor, 2 /* REORDER */);
              }
          }
      }
      // move main view anchor if this is a re-order.
      if (isReorder) {
          insert(anchor, container, parentAnchor);
      }
  }
  function hydrateTeleport(node, vnode, parentComponent, parentSuspense, slotScopeIds, optimized, { o: { nextSibling, parentNode, querySelector } }, hydrateChildren) {
      const target = (vnode.target = resolveTarget(vnode.props, querySelector));
      if (target) {
          // if multiple teleports rendered to the same target element, we need to
          // pick up from where the last teleport finished instead of the first node
          const targetNode = target._lpa || target.firstChild;
          if (vnode.shapeFlag & 16 /* ARRAY_CHILDREN */) {
              if (isTeleportDisabled(vnode.props)) {
                  vnode.anchor = hydrateChildren(nextSibling(node), vnode, parentNode(node), parentComponent, parentSuspense, slotScopeIds, optimized);
                  vnode.targetAnchor = targetNode;
              }
              else {
                  vnode.anchor = nextSibling(node);
                  // lookahead until we find the target anchor
                  // we cannot rely on return value of hydrateChildren() because there
                  // could be nested teleports
                  let targetAnchor = targetNode;
                  while (targetAnchor) {
                      targetAnchor = nextSibling(targetAnchor);
                      if (targetAnchor &&
                          targetAnchor.nodeType === 8 &&
                          targetAnchor.data === 'teleport anchor') {
                          vnode.targetAnchor = targetAnchor;
                          target._lpa =
                              vnode.targetAnchor && nextSibling(vnode.targetAnchor);
                          break;
                      }
                  }
                  hydrateChildren(targetNode, vnode, target, parentComponent, parentSuspense, slotScopeIds, optimized);
              }
          }
      }
      return vnode.anchor && nextSibling(vnode.anchor);
  }
  // Force-casted public typing for h and TSX props inference
  const Teleport = TeleportImpl;

  const normalizedAsyncComponentMap = new Map();
  function convertLegacyAsyncComponent(comp) {
      if (normalizedAsyncComponentMap.has(comp)) {
          return normalizedAsyncComponentMap.get(comp);
      }
      // we have to call the function here due to how v2's API won't expose the
      // options until we call it
      let resolve;
      let reject;
      const fallbackPromise = new Promise((r, rj) => {
          (resolve = r), (reject = rj);
      });
      const res = comp(resolve, reject);
      let converted;
      if (isPromise(res)) {
          converted = defineAsyncComponent(() => res);
      }
      else if (isObject(res) && !isVNode(res) && !isArray(res)) {
          converted = defineAsyncComponent({
              loader: () => res.component,
              loadingComponent: res.loading,
              errorComponent: res.error,
              delay: res.delay,
              timeout: res.timeout
          });
      }
      else if (res == null) {
          converted = defineAsyncComponent(() => fallbackPromise);
      }
      else {
          converted = comp; // probably a v3 functional comp
      }
      normalizedAsyncComponentMap.set(comp, converted);
      return converted;
  }

  function convertLegacyComponent(comp, instance) {
      if (comp.__isBuiltIn) {
          return comp;
      }
      // 2.x constructor
      if (isFunction(comp) && comp.cid) {
          comp = comp.options;
      }
      // 2.x async component
      if (isFunction(comp) &&
          checkCompatEnabled("COMPONENT_ASYNC" /* COMPONENT_ASYNC */, instance, comp)) {
          // since after disabling this, plain functions are still valid usage, do not
          // use softAssert here.
          return convertLegacyAsyncComponent(comp);
      }
      // 2.x functional component
      if (isObject(comp) &&
          comp.functional &&
          softAssertCompatEnabled("COMPONENT_FUNCTIONAL" /* COMPONENT_FUNCTIONAL */, instance, comp)) {
          return convertLegacyFunctionalComponent(comp);
      }
      return comp;
  }

  const Fragment = Symbol('Fragment' );
  const Text = Symbol('Text' );
  const Comment = Symbol('Comment' );
  const Static = Symbol('Static' );
  // Since v-if and v-for are the two possible ways node structure can dynamically
  // change, once we consider v-if branches and each v-for fragment a block, we
  // can divide a template into nested blocks, and within each block the node
  // structure would be stable. This allows us to skip most children diffing
  // and only worry about the dynamic nodes (indicated by patch flags).
  const blockStack = [];
  let currentBlock = null;
  /**
   * Open a block.
   * This must be called before `createBlock`. It cannot be part of `createBlock`
   * because the children of the block are evaluated before `createBlock` itself
   * is called. The generated code typically looks like this:
   *
   * ```js
   * function render() {
   *   return (openBlock(),createBlock('div', null, [...]))
   * }
   * ```
   * disableTracking is true when creating a v-for fragment block, since a v-for
   * fragment always diffs its children.
   *
   * @private
   */
  function openBlock(disableTracking = false) {
      blockStack.push((currentBlock = disableTracking ? null : []));
  }
  function closeBlock() {
      blockStack.pop();
      currentBlock = blockStack[blockStack.length - 1] || null;
  }
  // Whether we should be tracking dynamic child nodes inside a block.
  // Only tracks when this value is > 0
  // We are not using a simple boolean because this value may need to be
  // incremented/decremented by nested usage of v-once (see below)
  let isBlockTreeEnabled = 1;
  /**
   * Block tracking sometimes needs to be disabled, for example during the
   * creation of a tree that needs to be cached by v-once. The compiler generates
   * code like this:
   *
   * ``` js
   * _cache[1] || (
   *   setBlockTracking(-1),
   *   _cache[1] = createVNode(...),
   *   setBlockTracking(1),
   *   _cache[1]
   * )
   * ```
   *
   * @private
   */
  function setBlockTracking(value) {
      isBlockTreeEnabled += value;
  }
  function setupBlock(vnode) {
      // save current block children on the block vnode
      vnode.dynamicChildren =
          isBlockTreeEnabled > 0 ? currentBlock || EMPTY_ARR : null;
      // close block
      closeBlock();
      // a block is always going to be patched, so track it as a child of its
      // parent block
      if (isBlockTreeEnabled > 0 && currentBlock) {
          currentBlock.push(vnode);
      }
      return vnode;
  }
  /**
   * @private
   */
  function createElementBlock(type, props, children, patchFlag, dynamicProps, shapeFlag) {
      return setupBlock(createBaseVNode(type, props, children, patchFlag, dynamicProps, shapeFlag, true /* isBlock */));
  }
  /**
   * Create a block root vnode. Takes the same exact arguments as `createVNode`.
   * A block root keeps track of dynamic nodes within the block in the
   * `dynamicChildren` array.
   *
   * @private
   */
  function createBlock(type, props, children, patchFlag, dynamicProps) {
      return setupBlock(createVNode(type, props, children, patchFlag, dynamicProps, true /* isBlock: prevent a block from tracking itself */));
  }
  function isVNode(value) {
      return value ? value.__v_isVNode === true : false;
  }
  function isSameVNodeType(n1, n2) {
      if (n2.shapeFlag & 6 /* COMPONENT */ &&
          hmrDirtyComponents.has(n2.type)) {
          // HMR only: if the component has been hot-updated, force a reload.
          return false;
      }
      return n1.type === n2.type && n1.key === n2.key;
  }
  let vnodeArgsTransformer;
  /**
   * Internal API for registering an arguments transform for createVNode
   * used for creating stubs in the test-utils
   * It is *internal* but needs to be exposed for test-utils to pick up proper
   * typings
   */
  function transformVNodeArgs(transformer) {
      vnodeArgsTransformer = transformer;
  }
  const createVNodeWithArgsTransform = (...args) => {
      return _createVNode(...(vnodeArgsTransformer
          ? vnodeArgsTransformer(args, currentRenderingInstance)
          : args));
  };
  const InternalObjectKey = `__vInternal`;
  const normalizeKey = ({ key }) => key != null ? key : null;
  const normalizeRef = ({ ref, ref_key, ref_for }) => {
      return (ref != null
          ? isString(ref) || isRef(ref) || isFunction(ref)
              ? { i: currentRenderingInstance, r: ref, k: ref_key, f: !!ref_for }
              : ref
          : null);
  };
  function createBaseVNode(type, props = null, children = null, patchFlag = 0, dynamicProps = null, shapeFlag = type === Fragment ? 0 : 1 /* ELEMENT */, isBlockNode = false, needFullChildrenNormalization = false) {
      const vnode = {
          __v_isVNode: true,
          __v_skip: true,
          type,
          props,
          key: props && normalizeKey(props),
          ref: props && normalizeRef(props),
          scopeId: currentScopeId,
          slotScopeIds: null,
          children,
          component: null,
          suspense: null,
          ssContent: null,
          ssFallback: null,
          dirs: null,
          transition: null,
          el: null,
          anchor: null,
          target: null,
          targetAnchor: null,
          staticCount: 0,
          shapeFlag,
          patchFlag,
          dynamicProps,
          dynamicChildren: null,
          appContext: null
      };
      if (needFullChildrenNormalization) {
          normalizeChildren(vnode, children);
          // normalize suspense children
          if (shapeFlag & 128 /* SUSPENSE */) {
              type.normalize(vnode);
          }
      }
      else if (children) {
          // compiled element vnode - if children is passed, only possible types are
          // string or Array.
          vnode.shapeFlag |= isString(children)
              ? 8 /* TEXT_CHILDREN */
              : 16 /* ARRAY_CHILDREN */;
      }
      // validate key
      if (vnode.key !== vnode.key) {
          warn$1(`VNode created with invalid key (NaN). VNode type:`, vnode.type);
      }
      // track vnode for block tree
      if (isBlockTreeEnabled > 0 &&
          // avoid a block node from tracking itself
          !isBlockNode &&
          // has current parent block
          currentBlock &&
          // presence of a patch flag indicates this node needs patching on updates.
          // component nodes also should always be patched, because even if the
          // component doesn't need to update, it needs to persist the instance on to
          // the next vnode so that it can be properly unmounted later.
          (vnode.patchFlag > 0 || shapeFlag & 6 /* COMPONENT */) &&
          // the EVENTS flag is only for hydration and if it is the only flag, the
          // vnode should not be considered dynamic due to handler caching.
          vnode.patchFlag !== 32 /* HYDRATE_EVENTS */) {
          currentBlock.push(vnode);
      }
      {
          convertLegacyVModelProps(vnode);
          defineLegacyVNodeProperties(vnode);
      }
      return vnode;
  }
  const createVNode = (createVNodeWithArgsTransform );
  function _createVNode(type, props = null, children = null, patchFlag = 0, dynamicProps = null, isBlockNode = false) {
      if (!type || type === NULL_DYNAMIC_COMPONENT) {
          if (!type) {
              warn$1(`Invalid vnode type when creating vnode: ${type}.`);
          }
          type = Comment;
      }
      if (isVNode(type)) {
          // createVNode receiving an existing vnode. This happens in cases like
          // <component :is="vnode"/>
          // #2078 make sure to merge refs during the clone instead of overwriting it
          const cloned = cloneVNode(type, props, true /* mergeRef: true */);
          if (children) {
              normalizeChildren(cloned, children);
          }
          if (isBlockTreeEnabled > 0 && !isBlockNode && currentBlock) {
              if (cloned.shapeFlag & 6 /* COMPONENT */) {
                  currentBlock[currentBlock.indexOf(type)] = cloned;
              }
              else {
                  currentBlock.push(cloned);
              }
          }
          cloned.patchFlag |= -2 /* BAIL */;
          return cloned;
      }
      // class component normalization.
      if (isClassComponent(type)) {
          type = type.__vccOpts;
      }
      // 2.x async/functional component compat
      {
          type = convertLegacyComponent(type, currentRenderingInstance);
      }
      // class & style normalization.
      if (props) {
          // for reactive or proxy objects, we need to clone it to enable mutation.
          props = guardReactiveProps(props);
          let { class: klass, style } = props;
          if (klass && !isString(klass)) {
              props.class = normalizeClass(klass);
          }
          if (isObject(style)) {
              // reactive state objects need to be cloned since they are likely to be
              // mutated
              if (isProxy(style) && !isArray(style)) {
                  style = extend({}, style);
              }
              props.style = normalizeStyle(style);
          }
      }
      // encode the vnode type information into a bitmap
      const shapeFlag = isString(type)
          ? 1 /* ELEMENT */
          : isSuspense(type)
              ? 128 /* SUSPENSE */
              : isTeleport(type)
                  ? 64 /* TELEPORT */
                  : isObject(type)
                      ? 4 /* STATEFUL_COMPONENT */
                      : isFunction(type)
                          ? 2 /* FUNCTIONAL_COMPONENT */
                          : 0;
      if (shapeFlag & 4 /* STATEFUL_COMPONENT */ && isProxy(type)) {
          type = toRaw(type);
          warn$1(`Vue received a Component which was made a reactive object. This can ` +
              `lead to unnecessary performance overhead, and should be avoided by ` +
              `marking the component with \`markRaw\` or using \`shallowRef\` ` +
              `instead of \`ref\`.`, `\nComponent that was made reactive: `, type);
      }
      return createBaseVNode(type, props, children, patchFlag, dynamicProps, shapeFlag, isBlockNode, true);
  }
  function guardReactiveProps(props) {
      if (!props)
          return null;
      return isProxy(props) || InternalObjectKey in props
          ? extend({}, props)
          : props;
  }
  function cloneVNode(vnode, extraProps, mergeRef = false) {
      // This is intentionally NOT using spread or extend to avoid the runtime
      // key enumeration cost.
      const { props, ref, patchFlag, children } = vnode;
      const mergedProps = extraProps ? mergeProps(props || {}, extraProps) : props;
      const cloned = {
          __v_isVNode: true,
          __v_skip: true,
          type: vnode.type,
          props: mergedProps,
          key: mergedProps && normalizeKey(mergedProps),
          ref: extraProps && extraProps.ref
              ? // #2078 in the case of <component :is="vnode" ref="extra"/>
                  // if the vnode itself already has a ref, cloneVNode will need to merge
                  // the refs so the single vnode can be set on multiple refs
                  mergeRef && ref
                      ? isArray(ref)
                          ? ref.concat(normalizeRef(extraProps))
                          : [ref, normalizeRef(extraProps)]
                      : normalizeRef(extraProps)
              : ref,
          scopeId: vnode.scopeId,
          slotScopeIds: vnode.slotScopeIds,
          children: patchFlag === -1 /* HOISTED */ && isArray(children)
              ? children.map(deepCloneVNode)
              : children,
          target: vnode.target,
          targetAnchor: vnode.targetAnchor,
          staticCount: vnode.staticCount,
          shapeFlag: vnode.shapeFlag,
          // if the vnode is cloned with extra props, we can no longer assume its
          // existing patch flag to be reliable and need to add the FULL_PROPS flag.
          // note: preserve flag for fragments since they use the flag for children
          // fast paths only.
          patchFlag: extraProps && vnode.type !== Fragment
              ? patchFlag === -1 // hoisted node
                  ? 16 /* FULL_PROPS */
                  : patchFlag | 16 /* FULL_PROPS */
              : patchFlag,
          dynamicProps: vnode.dynamicProps,
          dynamicChildren: vnode.dynamicChildren,
          appContext: vnode.appContext,
          dirs: vnode.dirs,
          transition: vnode.transition,
          // These should technically only be non-null on mounted VNodes. However,
          // they *should* be copied for kept-alive vnodes. So we just always copy
          // them since them being non-null during a mount doesn't affect the logic as
          // they will simply be overwritten.
          component: vnode.component,
          suspense: vnode.suspense,
          ssContent: vnode.ssContent && cloneVNode(vnode.ssContent),
          ssFallback: vnode.ssFallback && cloneVNode(vnode.ssFallback),
          el: vnode.el,
          anchor: vnode.anchor
      };
      {
          defineLegacyVNodeProperties(cloned);
      }
      return cloned;
  }
  /**
   * Dev only, for HMR of hoisted vnodes reused in v-for
   * https://github.com/vitejs/vite/issues/2022
   */
  function deepCloneVNode(vnode) {
      const cloned = cloneVNode(vnode);
      if (isArray(vnode.children)) {
          cloned.children = vnode.children.map(deepCloneVNode);
      }
      return cloned;
  }
  /**
   * @private
   */
  function createTextVNode(text = ' ', flag = 0) {
      return createVNode(Text, null, text, flag);
  }
  /**
   * @private
   */
  function createStaticVNode(content, numberOfNodes) {
      // A static vnode can contain multiple stringified elements, and the number
      // of elements is necessary for hydration.
      const vnode = createVNode(Static, null, content);
      vnode.staticCount = numberOfNodes;
      return vnode;
  }
  /**
   * @private
   */
  function createCommentVNode(text = '', 
  // when used as the v-else branch, the comment node must be created as a
  // block to ensure correct updates.
  asBlock = false) {
      return asBlock
          ? (openBlock(), createBlock(Comment, null, text))
          : createVNode(Comment, null, text);
  }
  function normalizeVNode(child) {
      if (child == null || typeof child === 'boolean') {
          // empty placeholder
          return createVNode(Comment);
      }
      else if (isArray(child)) {
          // fragment
          return createVNode(Fragment, null, 
          // #3666, avoid reference pollution when reusing vnode
          child.slice());
      }
      else if (typeof child === 'object') {
          // already vnode, this should be the most common since compiled templates
          // always produce all-vnode children arrays
          return cloneIfMounted(child);
      }
      else {
          // strings and numbers
          return createVNode(Text, null, String(child));
      }
  }
  // optimized normalization for template-compiled render fns
  function cloneIfMounted(child) {
      return child.el === null || child.memo ? child : cloneVNode(child);
  }
  function normalizeChildren(vnode, children) {
      let type = 0;
      const { shapeFlag } = vnode;
      if (children == null) {
          children = null;
      }
      else if (isArray(children)) {
          type = 16 /* ARRAY_CHILDREN */;
      }
      else if (typeof children === 'object') {
          if (shapeFlag & (1 /* ELEMENT */ | 64 /* TELEPORT */)) {
              // Normalize slot to plain children for plain element and Teleport
              const slot = children.default;
              if (slot) {
                  // _c marker is added by withCtx() indicating this is a compiled slot
                  slot._c && (slot._d = false);
                  normalizeChildren(vnode, slot());
                  slot._c && (slot._d = true);
              }
              return;
          }
          else {
              type = 32 /* SLOTS_CHILDREN */;
              const slotFlag = children._;
              if (!slotFlag && !(InternalObjectKey in children)) {
                  children._ctx = currentRenderingInstance;
              }
              else if (slotFlag === 3 /* FORWARDED */ && currentRenderingInstance) {
                  // a child component receives forwarded slots from the parent.
                  // its slot type is determined by its parent's slot type.
                  if (currentRenderingInstance.slots._ === 1 /* STABLE */) {
                      children._ = 1 /* STABLE */;
                  }
                  else {
                      children._ = 2 /* DYNAMIC */;
                      vnode.patchFlag |= 1024 /* DYNAMIC_SLOTS */;
                  }
              }
          }
      }
      else if (isFunction(children)) {
          children = { default: children, _ctx: currentRenderingInstance };
          type = 32 /* SLOTS_CHILDREN */;
      }
      else {
          children = String(children);
          // force teleport children to array so it can be moved around
          if (shapeFlag & 64 /* TELEPORT */) {
              type = 16 /* ARRAY_CHILDREN */;
              children = [createTextVNode(children)];
          }
          else {
              type = 8 /* TEXT_CHILDREN */;
          }
      }
      vnode.children = children;
      vnode.shapeFlag |= type;
  }
  function mergeProps(...args) {
      const ret = {};
      for (let i = 0; i < args.length; i++) {
          const toMerge = args[i];
          for (const key in toMerge) {
              if (key === 'class') {
                  if (ret.class !== toMerge.class) {
                      ret.class = normalizeClass([ret.class, toMerge.class]);
                  }
              }
              else if (key === 'style') {
                  ret.style = normalizeStyle([ret.style, toMerge.style]);
              }
              else if (isOn(key)) {
                  const existing = ret[key];
                  const incoming = toMerge[key];
                  if (incoming &&
                      existing !== incoming &&
                      !(isArray(existing) && existing.includes(incoming))) {
                      ret[key] = existing
                          ? [].concat(existing, incoming)
                          : incoming;
                  }
              }
              else if (key !== '') {
                  ret[key] = toMerge[key];
              }
          }
      }
      return ret;
  }
  function invokeVNodeHook(hook, instance, vnode, prevVNode = null) {
      callWithAsyncErrorHandling(hook, instance, 7 /* VNODE_HOOK */, [
          vnode,
          prevVNode
      ]);
  }

  const emptyAppContext = createAppContext();
  let uid$1 = 0;
  function createComponentInstance(vnode, parent, suspense) {
      const type = vnode.type;
      // inherit parent app context - or - if root, adopt from root vnode
      const appContext = (parent ? parent.appContext : vnode.appContext) || emptyAppContext;
      const instance = {
          uid: uid$1++,
          vnode,
          type,
          parent,
          appContext,
          root: null,
          next: null,
          subTree: null,
          effect: null,
          update: null,
          scope: new EffectScope(true /* detached */),
          render: null,
          proxy: null,
          exposed: null,
          exposeProxy: null,
          withProxy: null,
          provides: parent ? parent.provides : Object.create(appContext.provides),
          accessCache: null,
          renderCache: [],
          // local resolved assets
          components: null,
          directives: null,
          // resolved props and emits options
          propsOptions: normalizePropsOptions(type, appContext),
          emitsOptions: normalizeEmitsOptions(type, appContext),
          // emit
          emit: null,
          emitted: null,
          // props default value
          propsDefaults: EMPTY_OBJ,
          // inheritAttrs
          inheritAttrs: type.inheritAttrs,
          // state
          ctx: EMPTY_OBJ,
          data: EMPTY_OBJ,
          props: EMPTY_OBJ,
          attrs: EMPTY_OBJ,
          slots: EMPTY_OBJ,
          refs: EMPTY_OBJ,
          setupState: EMPTY_OBJ,
          setupContext: null,
          // suspense related
          suspense,
          suspenseId: suspense ? suspense.pendingId : 0,
          asyncDep: null,
          asyncResolved: false,
          // lifecycle hooks
          // not using enums here because it results in computed properties
          isMounted: false,
          isUnmounted: false,
          isDeactivated: false,
          bc: null,
          c: null,
          bm: null,
          m: null,
          bu: null,
          u: null,
          um: null,
          bum: null,
          da: null,
          a: null,
          rtg: null,
          rtc: null,
          ec: null,
          sp: null
      };
      {
          instance.ctx = createDevRenderContext(instance);
      }
      instance.root = parent ? parent.root : instance;
      instance.emit = emit$2.bind(null, instance);
      // apply custom element special handling
      if (vnode.ce) {
          vnode.ce(instance);
      }
      return instance;
  }
  let currentInstance = null;
  const getCurrentInstance = () => currentInstance || currentRenderingInstance;
  const setCurrentInstance = (instance) => {
      currentInstance = instance;
      instance.scope.on();
  };
  const unsetCurrentInstance = () => {
      currentInstance && currentInstance.scope.off();
      currentInstance = null;
  };
  const isBuiltInTag = /*#__PURE__*/ makeMap('slot,component');
  function validateComponentName(name, config) {
      const appIsNativeTag = config.isNativeTag || NO;
      if (isBuiltInTag(name) || appIsNativeTag(name)) {
          warn$1('Do not use built-in or reserved HTML elements as component id: ' + name);
      }
  }
  function isStatefulComponent(instance) {
      return instance.vnode.shapeFlag & 4 /* STATEFUL_COMPONENT */;
  }
  let isInSSRComponentSetup = false;
  function setupComponent(instance, isSSR = false) {
      isInSSRComponentSetup = isSSR;
      const { props, children } = instance.vnode;
      const isStateful = isStatefulComponent(instance);
      initProps(instance, props, isStateful, isSSR);
      initSlots(instance, children);
      const setupResult = isStateful
          ? setupStatefulComponent(instance, isSSR)
          : undefined;
      isInSSRComponentSetup = false;
      return setupResult;
  }
  function setupStatefulComponent(instance, isSSR) {
      var _a;
      const Component = instance.type;
      {
          if (Component.name) {
              validateComponentName(Component.name, instance.appContext.config);
          }
          if (Component.components) {
              const names = Object.keys(Component.components);
              for (let i = 0; i < names.length; i++) {
                  validateComponentName(names[i], instance.appContext.config);
              }
          }
          if (Component.directives) {
              const names = Object.keys(Component.directives);
              for (let i = 0; i < names.length; i++) {
                  validateDirectiveName(names[i]);
              }
          }
          if (Component.compilerOptions && isRuntimeOnly()) {
              warn$1(`"compilerOptions" is only supported when using a build of Vue that ` +
                  `includes the runtime compiler. Since you are using a runtime-only ` +
                  `build, the options should be passed via your build tool config instead.`);
          }
      }
      // 0. create render proxy property access cache
      instance.accessCache = Object.create(null);
      // 1. create public instance / render proxy
      // also mark it raw so it's never observed
      instance.proxy = markRaw(new Proxy(instance.ctx, PublicInstanceProxyHandlers));
      {
          exposePropsOnRenderContext(instance);
      }
      // 2. call setup()
      const { setup } = Component;
      if (setup) {
          const setupContext = (instance.setupContext =
              setup.length > 1 ? createSetupContext(instance) : null);
          setCurrentInstance(instance);
          pauseTracking();
          const setupResult = callWithErrorHandling(setup, instance, 0 /* SETUP_FUNCTION */, [shallowReadonly(instance.props) , setupContext]);
          resetTracking();
          unsetCurrentInstance();
          if (isPromise(setupResult)) {
              setupResult.then(unsetCurrentInstance, unsetCurrentInstance);
              if (isSSR) {
                  // return the promise so server-renderer can wait on it
                  return setupResult
                      .then((resolvedResult) => {
                      handleSetupResult(instance, resolvedResult, isSSR);
                  })
                      .catch(e => {
                      handleError(e, instance, 0 /* SETUP_FUNCTION */);
                  });
              }
              else {
                  // async setup returned Promise.
                  // bail here and wait for re-entry.
                  instance.asyncDep = setupResult;
                  if (!instance.suspense) {
                      const name = (_a = Component.name) !== null && _a !== void 0 ? _a : 'Anonymous';
                      warn$1(`Component <${name}>: setup function returned a promise, but no ` +
                          `<Suspense> boundary was found in the parent component tree. ` +
                          `A component with async setup() must be nested in a <Suspense> ` +
                          `in order to be rendered.`);
                  }
              }
          }
          else {
              handleSetupResult(instance, setupResult, isSSR);
          }
      }
      else {
          finishComponentSetup(instance, isSSR);
      }
  }
  function handleSetupResult(instance, setupResult, isSSR) {
      if (isFunction(setupResult)) {
          // setup returned an inline render function
          {
              instance.render = setupResult;
          }
      }
      else if (isObject(setupResult)) {
          if (isVNode(setupResult)) {
              warn$1(`setup() should not return VNodes directly - ` +
                  `return a render function instead.`);
          }
          // setup returned bindings.
          // assuming a render function compiled from template is present.
          {
              instance.devtoolsRawSetupState = setupResult;
          }
          instance.setupState = proxyRefs(setupResult);
          {
              exposeSetupStateOnRenderContext(instance);
          }
      }
      else if (setupResult !== undefined) {
          warn$1(`setup() should return an object. Received: ${setupResult === null ? 'null' : typeof setupResult}`);
      }
      finishComponentSetup(instance, isSSR);
  }
  let compile;
  let installWithProxy;
  /**
   * For runtime-dom to register the compiler.
   * Note the exported method uses any to avoid d.ts relying on the compiler types.
   */
  function registerRuntimeCompiler(_compile) {
      compile = _compile;
      installWithProxy = i => {
          if (i.render._rc) {
              i.withProxy = new Proxy(i.ctx, RuntimeCompiledPublicInstanceProxyHandlers);
          }
      };
  }
  // dev only
  const isRuntimeOnly = () => !compile;
  function finishComponentSetup(instance, isSSR, skipOptions) {
      const Component = instance.type;
      {
          convertLegacyRenderFn(instance);
          if (Component.compatConfig) {
              validateCompatConfig(Component.compatConfig);
          }
      }
      // template / render function normalization
      // could be already set when returned from setup()
      if (!instance.render) {
          // only do on-the-fly compile if not in SSR - SSR on-the-fly compilation
          // is done by server-renderer
          if (!isSSR && compile && !Component.render) {
              const template = (instance.vnode.props &&
                  instance.vnode.props['inline-template']) ||
                  Component.template;
              if (template) {
                  {
                      startMeasure(instance, `compile`);
                  }
                  const { isCustomElement, compilerOptions } = instance.appContext.config;
                  const { delimiters, compilerOptions: componentCompilerOptions } = Component;
                  const finalCompilerOptions = extend(extend({
                      isCustomElement,
                      delimiters
                  }, compilerOptions), componentCompilerOptions);
                  {
                      // pass runtime compat config into the compiler
                      finalCompilerOptions.compatConfig = Object.create(globalCompatConfig);
                      if (Component.compatConfig) {
                          extend(finalCompilerOptions.compatConfig, Component.compatConfig);
                      }
                  }
                  Component.render = compile(template, finalCompilerOptions);
                  {
                      endMeasure(instance, `compile`);
                  }
              }
          }
          instance.render = (Component.render || NOOP);
          // for runtime-compiled render functions using `with` blocks, the render
          // proxy used needs a different `has` handler which is more performant and
          // also only allows a whitelist of globals to fallthrough.
          if (installWithProxy) {
              installWithProxy(instance);
          }
      }
      // support for 2.x options
      if (!(skipOptions)) {
          setCurrentInstance(instance);
          pauseTracking();
          applyOptions(instance);
          resetTracking();
          unsetCurrentInstance();
      }
      // warn missing template/render
      // the runtime compilation of template in SSR is done by server-render
      if (!Component.render && instance.render === NOOP && !isSSR) {
          /* istanbul ignore if */
          if (!compile && Component.template) {
              warn$1(`Component provided template option but ` +
                  `runtime compilation is not supported in this build of Vue.` +
                  (` Use "vue.global.js" instead.`
                              ) /* should not happen */);
          }
          else {
              warn$1(`Component is missing template or render function.`);
          }
      }
  }
  function createAttrsProxy(instance) {
      return new Proxy(instance.attrs, {
              get(target, key) {
                  markAttrsAccessed();
                  track(instance, "get" /* GET */, '$attrs');
                  return target[key];
              },
              set() {
                  warn$1(`setupContext.attrs is readonly.`);
                  return false;
              },
              deleteProperty() {
                  warn$1(`setupContext.attrs is readonly.`);
                  return false;
              }
          }
          );
  }
  function createSetupContext(instance) {
      const expose = exposed => {
          if (instance.exposed) {
              warn$1(`expose() should be called only once per setup().`);
          }
          instance.exposed = exposed || {};
      };
      let attrs;
      {
          // We use getters in dev in case libs like test-utils overwrite instance
          // properties (overwrites should not be done in prod)
          return Object.freeze({
              get attrs() {
                  return attrs || (attrs = createAttrsProxy(instance));
              },
              get slots() {
                  return shallowReadonly(instance.slots);
              },
              get emit() {
                  return (event, ...args) => instance.emit(event, ...args);
              },
              expose
          });
      }
  }
  function getExposeProxy(instance) {
      if (instance.exposed) {
          return (instance.exposeProxy ||
              (instance.exposeProxy = new Proxy(proxyRefs(markRaw(instance.exposed)), {
                  get(target, key) {
                      if (key in target) {
                          return target[key];
                      }
                      else if (key in publicPropertiesMap) {
                          return publicPropertiesMap[key](instance);
                      }
                  }
              })));
      }
  }
  const classifyRE = /(?:^|[-_])(\w)/g;
  const classify = (str) => str.replace(classifyRE, c => c.toUpperCase()).replace(/[-_]/g, '');
  function getComponentName(Component, includeInferred = true) {
      return isFunction(Component)
          ? Component.displayName || Component.name
          : Component.name || (includeInferred && Component.__name);
  }
  /* istanbul ignore next */
  function formatComponentName(instance, Component, isRoot = false) {
      let name = getComponentName(Component);
      if (!name && Component.__file) {
          const match = Component.__file.match(/([^/\\]+)\.\w+$/);
          if (match) {
              name = match[1];
          }
      }
      if (!name && instance && instance.parent) {
          // try to infer the name based on reverse resolution
          const inferFromRegistry = (registry) => {
              for (const key in registry) {
                  if (registry[key] === Component) {
                      return key;
                  }
              }
          };
          name =
              inferFromRegistry(instance.components ||
                  instance.parent.type.components) || inferFromRegistry(instance.appContext.components);
      }
      return name ? classify(name) : isRoot ? `App` : `Anonymous`;
  }
  function isClassComponent(value) {
      return isFunction(value) && '__vccOpts' in value;
  }

  const computed$1 = ((getterOrOptions, debugOptions) => {
      // @ts-ignore
      return computed(getterOrOptions, debugOptions, isInSSRComponentSetup);
  });

  // dev only
  const warnRuntimeUsage = (method) => warn$1(`${method}() is a compiler-hint helper that is only usable inside ` +
      `<script setup> of a single file component. Its arguments should be ` +
      `compiled away and passing it at runtime has no effect.`);
  // implementation
  function defineProps() {
      {
          warnRuntimeUsage(`defineProps`);
      }
      return null;
  }
  // implementation
  function defineEmits() {
      {
          warnRuntimeUsage(`defineEmits`);
      }
      return null;
  }
  /**
   * Vue `<script setup>` compiler macro for declaring a component's exposed
   * instance properties when it is accessed by a parent component via template
   * refs.
   *
   * `<script setup>` components are closed by default - i.e. variables inside
   * the `<script setup>` scope is not exposed to parent unless explicitly exposed
   * via `defineExpose`.
   *
   * This is only usable inside `<script setup>`, is compiled away in the
   * output and should **not** be actually called at runtime.
   */
  function defineExpose(exposed) {
      {
          warnRuntimeUsage(`defineExpose`);
      }
  }
  /**
   * Vue `<script setup>` compiler macro for providing props default values when
   * using type-based `defineProps` declaration.
   *
   * Example usage:
   * ```ts
   * withDefaults(defineProps<{
   *   size?: number
   *   labels?: string[]
   * }>(), {
   *   size: 3,
   *   labels: () => ['default label']
   * })
   * ```
   *
   * This is only usable inside `<script setup>`, is compiled away in the output
   * and should **not** be actually called at runtime.
   */
  function withDefaults(props, defaults) {
      {
          warnRuntimeUsage(`withDefaults`);
      }
      return null;
  }
  function useSlots() {
      return getContext().slots;
  }
  function useAttrs() {
      return getContext().attrs;
  }
  function getContext() {
      const i = getCurrentInstance();
      if (!i) {
          warn$1(`useContext() called without active instance.`);
      }
      return i.setupContext || (i.setupContext = createSetupContext(i));
  }
  /**
   * Runtime helper for merging default declarations. Imported by compiled code
   * only.
   * @internal
   */
  function mergeDefaults(raw, defaults) {
      const props = isArray(raw)
          ? raw.reduce((normalized, p) => ((normalized[p] = {}), normalized), {})
          : raw;
      for (const key in defaults) {
          const opt = props[key];
          if (opt) {
              if (isArray(opt) || isFunction(opt)) {
                  props[key] = { type: opt, default: defaults[key] };
              }
              else {
                  opt.default = defaults[key];
              }
          }
          else if (opt === null) {
              props[key] = { default: defaults[key] };
          }
          else {
              warn$1(`props default key "${key}" has no corresponding declaration.`);
          }
      }
      return props;
  }
  /**
   * Used to create a proxy for the rest element when destructuring props with
   * defineProps().
   * @internal
   */
  function createPropsRestProxy(props, excludedKeys) {
      const ret = {};
      for (const key in props) {
          if (!excludedKeys.includes(key)) {
              Object.defineProperty(ret, key, {
                  enumerable: true,
                  get: () => props[key]
              });
          }
      }
      return ret;
  }
  /**
   * `<script setup>` helper for persisting the current instance context over
   * async/await flows.
   *
   * `@vue/compiler-sfc` converts the following:
   *
   * ```ts
   * const x = await foo()
   * ```
   *
   * into:
   *
   * ```ts
   * let __temp, __restore
   * const x = (([__temp, __restore] = withAsyncContext(() => foo())),__temp=await __temp,__restore(),__temp)
   * ```
   * @internal
   */
  function withAsyncContext(getAwaitable) {
      const ctx = getCurrentInstance();
      if (!ctx) {
          warn$1(`withAsyncContext called without active current instance. ` +
              `This is likely a bug.`);
      }
      let awaitable = getAwaitable();
      unsetCurrentInstance();
      if (isPromise(awaitable)) {
          awaitable = awaitable.catch(e => {
              setCurrentInstance(ctx);
              throw e;
          });
      }
      return [awaitable, () => setCurrentInstance(ctx)];
  }

  // Actual implementation
  function h(type, propsOrChildren, children) {
      const l = arguments.length;
      if (l === 2) {
          if (isObject(propsOrChildren) && !isArray(propsOrChildren)) {
              // single vnode without props
              if (isVNode(propsOrChildren)) {
                  return createVNode(type, null, [propsOrChildren]);
              }
              // props without children
              return createVNode(type, propsOrChildren);
          }
          else {
              // omit props
              return createVNode(type, null, propsOrChildren);
          }
      }
      else {
          if (l > 3) {
              children = Array.prototype.slice.call(arguments, 2);
          }
          else if (l === 3 && isVNode(children)) {
              children = [children];
          }
          return createVNode(type, propsOrChildren, children);
      }
  }

  const ssrContextKey = Symbol(`ssrContext` );
  const useSSRContext = () => {
      {
          warn$1(`useSSRContext() is not supported in the global build.`);
      }
  };

  function initCustomFormatter() {
      /* eslint-disable no-restricted-globals */
      if (typeof window === 'undefined') {
          return;
      }
      const vueStyle = { style: 'color:#3ba776' };
      const numberStyle = { style: 'color:#0b1bc9' };
      const stringStyle = { style: 'color:#b62e24' };
      const keywordStyle = { style: 'color:#9d288c' };
      // custom formatter for Chrome
      // https://www.mattzeunert.com/2016/02/19/custom-chrome-devtools-object-formatters.html
      const formatter = {
          header(obj) {
              // TODO also format ComponentPublicInstance & ctx.slots/attrs in setup
              if (!isObject(obj)) {
                  return null;
              }
              if (obj.__isVue) {
                  return ['div', vueStyle, `VueInstance`];
              }
              else if (isRef(obj)) {
                  return [
                      'div',
                      {},
                      ['span', vueStyle, genRefFlag(obj)],
                      '<',
                      formatValue(obj.value),
                      `>`
                  ];
              }
              else if (isReactive(obj)) {
                  return [
                      'div',
                      {},
                      ['span', vueStyle, isShallow(obj) ? 'ShallowReactive' : 'Reactive'],
                      '<',
                      formatValue(obj),
                      `>${isReadonly(obj) ? ` (readonly)` : ``}`
                  ];
              }
              else if (isReadonly(obj)) {
                  return [
                      'div',
                      {},
                      ['span', vueStyle, isShallow(obj) ? 'ShallowReadonly' : 'Readonly'],
                      '<',
                      formatValue(obj),
                      '>'
                  ];
              }
              return null;
          },
          hasBody(obj) {
              return obj && obj.__isVue;
          },
          body(obj) {
              if (obj && obj.__isVue) {
                  return [
                      'div',
                      {},
                      ...formatInstance(obj.$)
                  ];
              }
          }
      };
      function formatInstance(instance) {
          const blocks = [];
          if (instance.type.props && instance.props) {
              blocks.push(createInstanceBlock('props', toRaw(instance.props)));
          }
          if (instance.setupState !== EMPTY_OBJ) {
              blocks.push(createInstanceBlock('setup', instance.setupState));
          }
          if (instance.data !== EMPTY_OBJ) {
              blocks.push(createInstanceBlock('data', toRaw(instance.data)));
          }
          const computed = extractKeys(instance, 'computed');
          if (computed) {
              blocks.push(createInstanceBlock('computed', computed));
          }
          const injected = extractKeys(instance, 'inject');
          if (injected) {
              blocks.push(createInstanceBlock('injected', injected));
          }
          blocks.push([
              'div',
              {},
              [
                  'span',
                  {
                      style: keywordStyle.style + ';opacity:0.66'
                  },
                  '$ (internal): '
              ],
              ['object', { object: instance }]
          ]);
          return blocks;
      }
      function createInstanceBlock(type, target) {
          target = extend({}, target);
          if (!Object.keys(target).length) {
              return ['span', {}];
          }
          return [
              'div',
              { style: 'line-height:1.25em;margin-bottom:0.6em' },
              [
                  'div',
                  {
                      style: 'color:#476582'
                  },
                  type
              ],
              [
                  'div',
                  {
                      style: 'padding-left:1.25em'
                  },
                  ...Object.keys(target).map(key => {
                      return [
                          'div',
                          {},
                          ['span', keywordStyle, key + ': '],
                          formatValue(target[key], false)
                      ];
                  })
              ]
          ];
      }
      function formatValue(v, asRaw = true) {
          if (typeof v === 'number') {
              return ['span', numberStyle, v];
          }
          else if (typeof v === 'string') {
              return ['span', stringStyle, JSON.stringify(v)];
          }
          else if (typeof v === 'boolean') {
              return ['span', keywordStyle, v];
          }
          else if (isObject(v)) {
              return ['object', { object: asRaw ? toRaw(v) : v }];
          }
          else {
              return ['span', stringStyle, String(v)];
          }
      }
      function extractKeys(instance, type) {
          const Comp = instance.type;
          if (isFunction(Comp)) {
              return;
          }
          const extracted = {};
          for (const key in instance.ctx) {
              if (isKeyOfType(Comp, key, type)) {
                  extracted[key] = instance.ctx[key];
              }
          }
          return extracted;
      }
      function isKeyOfType(Comp, key, type) {
          const opts = Comp[type];
          if ((isArray(opts) && opts.includes(key)) ||
              (isObject(opts) && key in opts)) {
              return true;
          }
          if (Comp.extends && isKeyOfType(Comp.extends, key, type)) {
              return true;
          }
          if (Comp.mixins && Comp.mixins.some(m => isKeyOfType(m, key, type))) {
              return true;
          }
      }
      function genRefFlag(v) {
          if (isShallow(v)) {
              return `ShallowRef`;
          }
          if (v.effect) {
              return `ComputedRef`;
          }
          return `Ref`;
      }
      if (window.devtoolsFormatters) {
          window.devtoolsFormatters.push(formatter);
      }
      else {
          window.devtoolsFormatters = [formatter];
      }
  }

  function withMemo(memo, render, cache, index) {
      const cached = cache[index];
      if (cached && isMemoSame(cached, memo)) {
          return cached;
      }
      const ret = render();
      // shallow clone
      ret.memo = memo.slice();
      return (cache[index] = ret);
  }
  function isMemoSame(cached, memo) {
      const prev = cached.memo;
      if (prev.length != memo.length) {
          return false;
      }
      for (let i = 0; i < prev.length; i++) {
          if (hasChanged(prev[i], memo[i])) {
              return false;
          }
      }
      // make sure to let parent block track it when returning cached
      if (isBlockTreeEnabled > 0 && currentBlock) {
          currentBlock.push(cached);
      }
      return true;
  }

  // Core API ------------------------------------------------------------------
  const version = "3.2.37";
  /**
   * SSR utils for \@vue/server-renderer. Only exposed in ssr-possible builds.
   * @internal
   */
  const ssrUtils = (null);
  /**
   * @internal only exposed in compat builds
   */
  const resolveFilter$1 = resolveFilter ;
  const _compatUtils = {
      warnDeprecation,
      createCompatVue,
      isCompatEnabled,
      checkCompatEnabled,
      softAssertCompatEnabled
  };
  /**
   * @internal only exposed in compat builds.
   */
  const compatUtils = (_compatUtils );

  const svgNS = 'http://www.w3.org/2000/svg';
  const doc = (typeof document !== 'undefined' ? document : null);
  const templateContainer = doc && /*#__PURE__*/ doc.createElement('template');
  const nodeOps = {
      insert: (child, parent, anchor) => {
          parent.insertBefore(child, anchor || null);
      },
      remove: child => {
          const parent = child.parentNode;
          if (parent) {
              parent.removeChild(child);
          }
      },
      createElement: (tag, isSVG, is, props) => {
          const el = isSVG
              ? doc.createElementNS(svgNS, tag)
              : doc.createElement(tag, is ? { is } : undefined);
          if (tag === 'select' && props && props.multiple != null) {
              el.setAttribute('multiple', props.multiple);
          }
          return el;
      },
      createText: text => doc.createTextNode(text),
      createComment: text => doc.createComment(text),
      setText: (node, text) => {
          node.nodeValue = text;
      },
      setElementText: (el, text) => {
          el.textContent = text;
      },
      parentNode: node => node.parentNode,
      nextSibling: node => node.nextSibling,
      querySelector: selector => doc.querySelector(selector),
      setScopeId(el, id) {
          el.setAttribute(id, '');
      },
      cloneNode(el) {
          const cloned = el.cloneNode(true);
          // #3072
          // - in `patchDOMProp`, we store the actual value in the `el._value` property.
          // - normally, elements using `:value` bindings will not be hoisted, but if
          //   the bound value is a constant, e.g. `:value="true"` - they do get
          //   hoisted.
          // - in production, hoisted nodes are cloned when subsequent inserts, but
          //   cloneNode() does not copy the custom property we attached.
          // - This may need to account for other custom DOM properties we attach to
          //   elements in addition to `_value` in the future.
          if (`_value` in el) {
              cloned._value = el._value;
          }
          return cloned;
      },
      // __UNSAFE__
      // Reason: innerHTML.
      // Static content here can only come from compiled templates.
      // As long as the user only uses trusted templates, this is safe.
      insertStaticContent(content, parent, anchor, isSVG, start, end) {
          // <parent> before | first ... last | anchor </parent>
          const before = anchor ? anchor.previousSibling : parent.lastChild;
          // #5308 can only take cached path if:
          // - has a single root node
          // - nextSibling info is still available
          if (start && (start === end || start.nextSibling)) {
              // cached
              while (true) {
                  parent.insertBefore(start.cloneNode(true), anchor);
                  if (start === end || !(start = start.nextSibling))
                      break;
              }
          }
          else {
              // fresh insert
              templateContainer.innerHTML = isSVG ? `<svg>${content}</svg>` : content;
              const template = templateContainer.content;
              if (isSVG) {
                  // remove outer svg wrapper
                  const wrapper = template.firstChild;
                  while (wrapper.firstChild) {
                      template.appendChild(wrapper.firstChild);
                  }
                  template.removeChild(wrapper);
              }
              parent.insertBefore(template, anchor);
          }
          return [
              // first
              before ? before.nextSibling : parent.firstChild,
              // last
              anchor ? anchor.previousSibling : parent.lastChild
          ];
      }
  };

  // compiler should normalize class + :class bindings on the same element
  // into a single binding ['staticClass', dynamic]
  function patchClass(el, value, isSVG) {
      // directly setting className should be faster than setAttribute in theory
      // if this is an element during a transition, take the temporary transition
      // classes into account.
      const transitionClasses = el._vtc;
      if (transitionClasses) {
          value = (value ? [value, ...transitionClasses] : [...transitionClasses]).join(' ');
      }
      if (value == null) {
          el.removeAttribute('class');
      }
      else if (isSVG) {
          el.setAttribute('class', value);
      }
      else {
          el.className = value;
      }
  }

  function patchStyle(el, prev, next) {
      const style = el.style;
      const isCssString = isString(next);
      if (next && !isCssString) {
          for (const key in next) {
              setStyle(style, key, next[key]);
          }
          if (prev && !isString(prev)) {
              for (const key in prev) {
                  if (next[key] == null) {
                      setStyle(style, key, '');
                  }
              }
          }
      }
      else {
          const currentDisplay = style.display;
          if (isCssString) {
              if (prev !== next) {
                  style.cssText = next;
              }
          }
          else if (prev) {
              el.removeAttribute('style');
          }
          // indicates that the `display` of the element is controlled by `v-show`,
          // so we always keep the current `display` value regardless of the `style`
          // value, thus handing over control to `v-show`.
          if ('_vod' in el) {
              style.display = currentDisplay;
          }
      }
  }
  const importantRE = /\s*!important$/;
  function setStyle(style, name, val) {
      if (isArray(val)) {
          val.forEach(v => setStyle(style, name, v));
      }
      else {
          if (val == null)
              val = '';
          if (name.startsWith('--')) {
              // custom property definition
              style.setProperty(name, val);
          }
          else {
              const prefixed = autoPrefix(style, name);
              if (importantRE.test(val)) {
                  // !important
                  style.setProperty(hyphenate(prefixed), val.replace(importantRE, ''), 'important');
              }
              else {
                  style[prefixed] = val;
              }
          }
      }
  }
  const prefixes = ['Webkit', 'Moz', 'ms'];
  const prefixCache = {};
  function autoPrefix(style, rawName) {
      const cached = prefixCache[rawName];
      if (cached) {
          return cached;
      }
      let name = camelize(rawName);
      if (name !== 'filter' && name in style) {
          return (prefixCache[rawName] = name);
      }
      name = capitalize(name);
      for (let i = 0; i < prefixes.length; i++) {
          const prefixed = prefixes[i] + name;
          if (prefixed in style) {
              return (prefixCache[rawName] = prefixed);
          }
      }
      return rawName;
  }

  const xlinkNS = 'http://www.w3.org/1999/xlink';
  function patchAttr(el, key, value, isSVG, instance) {
      if (isSVG && key.startsWith('xlink:')) {
          if (value == null) {
              el.removeAttributeNS(xlinkNS, key.slice(6, key.length));
          }
          else {
              el.setAttributeNS(xlinkNS, key, value);
          }
      }
      else {
          if (compatCoerceAttr(el, key, value, instance)) {
              return;
          }
          // note we are only checking boolean attributes that don't have a
          // corresponding dom prop of the same name here.
          const isBoolean = isSpecialBooleanAttr(key);
          if (value == null || (isBoolean && !includeBooleanAttr(value))) {
              el.removeAttribute(key);
          }
          else {
              el.setAttribute(key, isBoolean ? '' : value);
          }
      }
  }
  // 2.x compat
  const isEnumeratedAttr = /*#__PURE__*/ makeMap('contenteditable,draggable,spellcheck')
      ;
  function compatCoerceAttr(el, key, value, instance = null) {
      if (isEnumeratedAttr(key)) {
          const v2CocercedValue = value === null
              ? 'false'
              : typeof value !== 'boolean' && value !== undefined
                  ? 'true'
                  : null;
          if (v2CocercedValue &&
              compatUtils.softAssertCompatEnabled("ATTR_ENUMERATED_COERCION" /* ATTR_ENUMERATED_COERCION */, instance, key, value, v2CocercedValue)) {
              el.setAttribute(key, v2CocercedValue);
              return true;
          }
      }
      else if (value === false &&
          !isSpecialBooleanAttr(key) &&
          compatUtils.softAssertCompatEnabled("ATTR_FALSE_VALUE" /* ATTR_FALSE_VALUE */, instance, key)) {
          el.removeAttribute(key);
          return true;
      }
      return false;
  }

  // __UNSAFE__
  // functions. The user is responsible for using them with only trusted content.
  function patchDOMProp(el, key, value, 
  // the following args are passed only due to potential innerHTML/textContent
  // overriding existing VNodes, in which case the old tree must be properly
  // unmounted.
  prevChildren, parentComponent, parentSuspense, unmountChildren) {
      if (key === 'innerHTML' || key === 'textContent') {
          if (prevChildren) {
              unmountChildren(prevChildren, parentComponent, parentSuspense);
          }
          el[key] = value == null ? '' : value;
          return;
      }
      if (key === 'value' &&
          el.tagName !== 'PROGRESS' &&
          // custom elements may use _value internally
          !el.tagName.includes('-')) {
          // store value as _value as well since
          // non-string values will be stringified.
          el._value = value;
          const newValue = value == null ? '' : value;
          if (el.value !== newValue ||
              // #4956: always set for OPTION elements because its value falls back to
              // textContent if no value attribute is present. And setting .value for
              // OPTION has no side effect
              el.tagName === 'OPTION') {
              el.value = newValue;
          }
          if (value == null) {
              el.removeAttribute(key);
          }
          return;
      }
      let needRemove = false;
      if (value === '' || value == null) {
          const type = typeof el[key];
          if (type === 'boolean') {
              // e.g. <select multiple> compiles to { multiple: '' }
              value = includeBooleanAttr(value);
          }
          else if (value == null && type === 'string') {
              // e.g. <div :id="null">
              value = '';
              needRemove = true;
          }
          else if (type === 'number') {
              // e.g. <img :width="null">
              // the value of some IDL attr must be greater than 0, e.g. input.size = 0 -> error
              value = 0;
              needRemove = true;
          }
      }
      else {
          if (value === false &&
              compatUtils.isCompatEnabled("ATTR_FALSE_VALUE" /* ATTR_FALSE_VALUE */, parentComponent)) {
              const type = typeof el[key];
              if (type === 'string' || type === 'number') {
                  compatUtils.warnDeprecation("ATTR_FALSE_VALUE" /* ATTR_FALSE_VALUE */, parentComponent, key);
                  value = type === 'number' ? 0 : '';
                  needRemove = true;
              }
          }
      }
      // some properties perform value validation and throw,
      // some properties has getter, no setter, will error in 'use strict'
      // eg. <select :type="null"></select> <select :willValidate="null"></select>
      try {
          el[key] = value;
      }
      catch (e) {
          {
              warn$1(`Failed setting prop "${key}" on <${el.tagName.toLowerCase()}>: ` +
                  `value ${value} is invalid.`, e);
          }
      }
      needRemove && el.removeAttribute(key);
  }

  // Async edge case fix requires storing an event listener's attach timestamp.
  const [_getNow, skipTimestampCheck] = /*#__PURE__*/ (() => {
      let _getNow = Date.now;
      let skipTimestampCheck = false;
      if (typeof window !== 'undefined') {
          // Determine what event timestamp the browser is using. Annoyingly, the
          // timestamp can either be hi-res (relative to page load) or low-res
          // (relative to UNIX epoch), so in order to compare time we have to use the
          // same timestamp type when saving the flush timestamp.
          if (Date.now() > document.createEvent('Event').timeStamp) {
              // if the low-res timestamp which is bigger than the event timestamp
              // (which is evaluated AFTER) it means the event is using a hi-res timestamp,
              // and we need to use the hi-res version for event listeners as well.
              _getNow = performance.now.bind(performance);
          }
          // #3485: Firefox <= 53 has incorrect Event.timeStamp implementation
          // and does not fire microtasks in between event propagation, so safe to exclude.
          const ffMatch = navigator.userAgent.match(/firefox\/(\d+)/i);
          skipTimestampCheck = !!(ffMatch && Number(ffMatch[1]) <= 53);
      }
      return [_getNow, skipTimestampCheck];
  })();
  // To avoid the overhead of repeatedly calling performance.now(), we cache
  // and use the same timestamp for all event listeners attached in the same tick.
  let cachedNow = 0;
  const p = /*#__PURE__*/ Promise.resolve();
  const reset = () => {
      cachedNow = 0;
  };
  const getNow = () => cachedNow || (p.then(reset), (cachedNow = _getNow()));
  function addEventListener(el, event, handler, options) {
      el.addEventListener(event, handler, options);
  }
  function removeEventListener(el, event, handler, options) {
      el.removeEventListener(event, handler, options);
  }
  function patchEvent(el, rawName, prevValue, nextValue, instance = null) {
      // vei = vue event invokers
      const invokers = el._vei || (el._vei = {});
      const existingInvoker = invokers[rawName];
      if (nextValue && existingInvoker) {
          // patch
          existingInvoker.value = nextValue;
      }
      else {
          const [name, options] = parseName(rawName);
          if (nextValue) {
              // add
              const invoker = (invokers[rawName] = createInvoker(nextValue, instance));
              addEventListener(el, name, invoker, options);
          }
          else if (existingInvoker) {
              // remove
              removeEventListener(el, name, existingInvoker, options);
              invokers[rawName] = undefined;
          }
      }
  }
  const optionsModifierRE = /(?:Once|Passive|Capture)$/;
  function parseName(name) {
      let options;
      if (optionsModifierRE.test(name)) {
          options = {};
          let m;
          while ((m = name.match(optionsModifierRE))) {
              name = name.slice(0, name.length - m[0].length);
              options[m[0].toLowerCase()] = true;
          }
      }
      return [hyphenate(name.slice(2)), options];
  }
  function createInvoker(initialValue, instance) {
      const invoker = (e) => {
          // async edge case #6566: inner click event triggers patch, event handler
          // attached to outer element during patch, and triggered again. This
          // happens because browsers fire microtask ticks between event propagation.
          // the solution is simple: we save the timestamp when a handler is attached,
          // and the handler would only fire if the event passed to it was fired
          // AFTER it was attached.
          const timeStamp = e.timeStamp || _getNow();
          if (skipTimestampCheck || timeStamp >= invoker.attached - 1) {
              callWithAsyncErrorHandling(patchStopImmediatePropagation(e, invoker.value), instance, 5 /* NATIVE_EVENT_HANDLER */, [e]);
          }
      };
      invoker.value = initialValue;
      invoker.attached = getNow();
      return invoker;
  }
  function patchStopImmediatePropagation(e, value) {
      if (isArray(value)) {
          const originalStop = e.stopImmediatePropagation;
          e.stopImmediatePropagation = () => {
              originalStop.call(e);
              e._stopped = true;
          };
          return value.map(fn => (e) => !e._stopped && fn && fn(e));
      }
      else {
          return value;
      }
  }

  const nativeOnRE = /^on[a-z]/;
  const patchProp = (el, key, prevValue, nextValue, isSVG = false, prevChildren, parentComponent, parentSuspense, unmountChildren) => {
      if (key === 'class') {
          patchClass(el, nextValue, isSVG);
      }
      else if (key === 'style') {
          patchStyle(el, prevValue, nextValue);
      }
      else if (isOn(key)) {
          // ignore v-model listeners
          if (!isModelListener(key)) {
              patchEvent(el, key, prevValue, nextValue, parentComponent);
          }
      }
      else if (key[0] === '.'
          ? ((key = key.slice(1)), true)
          : key[0] === '^'
              ? ((key = key.slice(1)), false)
              : shouldSetAsProp(el, key, nextValue, isSVG)) {
          patchDOMProp(el, key, nextValue, prevChildren, parentComponent, parentSuspense, unmountChildren);
      }
      else {
          // special case for <input v-model type="checkbox"> with
          // :true-value & :false-value
          // store value as dom properties since non-string values will be
          // stringified.
          if (key === 'true-value') {
              el._trueValue = nextValue;
          }
          else if (key === 'false-value') {
              el._falseValue = nextValue;
          }
          patchAttr(el, key, nextValue, isSVG, parentComponent);
      }
  };
  function shouldSetAsProp(el, key, value, isSVG) {
      if (isSVG) {
          // most keys must be set as attribute on svg elements to work
          // ...except innerHTML & textContent
          if (key === 'innerHTML' || key === 'textContent') {
              return true;
          }
          // or native onclick with function values
          if (key in el && nativeOnRE.test(key) && isFunction(value)) {
              return true;
          }
          return false;
      }
      // these are enumerated attrs, however their corresponding DOM properties
      // are actually booleans - this leads to setting it with a string "false"
      // value leading it to be coerced to `true`, so we need to always treat
      // them as attributes.
      // Note that `contentEditable` doesn't have this problem: its DOM
      // property is also enumerated string values.
      if (key === 'spellcheck' || key === 'draggable' || key === 'translate') {
          return false;
      }
      // #1787, #2840 form property on form elements is readonly and must be set as
      // attribute.
      if (key === 'form') {
          return false;
      }
      // #1526 <input list> must be set as attribute
      if (key === 'list' && el.tagName === 'INPUT') {
          return false;
      }
      // #2766 <textarea type> must be set as attribute
      if (key === 'type' && el.tagName === 'TEXTAREA') {
          return false;
      }
      // native onclick with string value, must be set as attribute
      if (nativeOnRE.test(key) && isString(value)) {
          return false;
      }
      return key in el;
  }

  function defineCustomElement(options, hydrate) {
      const Comp = defineComponent(options);
      class VueCustomElement extends VueElement {
          constructor(initialProps) {
              super(Comp, initialProps, hydrate);
          }
      }
      VueCustomElement.def = Comp;
      return VueCustomElement;
  }
  const defineSSRCustomElement = ((options) => {
      // @ts-ignore
      return defineCustomElement(options, hydrate);
  });
  const BaseClass = (typeof HTMLElement !== 'undefined' ? HTMLElement : class {
  });
  class VueElement extends BaseClass {
      constructor(_def, _props = {}, hydrate) {
          super();
          this._def = _def;
          this._props = _props;
          /**
           * @internal
           */
          this._instance = null;
          this._connected = false;
          this._resolved = false;
          this._numberProps = null;
          if (this.shadowRoot && hydrate) {
              hydrate(this._createVNode(), this.shadowRoot);
          }
          else {
              if (this.shadowRoot) {
                  warn$1(`Custom element has pre-rendered declarative shadow root but is not ` +
                      `defined as hydratable. Use \`defineSSRCustomElement\`.`);
              }
              this.attachShadow({ mode: 'open' });
          }
      }
      connectedCallback() {
          this._connected = true;
          if (!this._instance) {
              this._resolveDef();
          }
      }
      disconnectedCallback() {
          this._connected = false;
          nextTick(() => {
              if (!this._connected) {
                  render(null, this.shadowRoot);
                  this._instance = null;
              }
          });
      }
      /**
       * resolve inner component definition (handle possible async component)
       */
      _resolveDef() {
          if (this._resolved) {
              return;
          }
          this._resolved = true;
          // set initial attrs
          for (let i = 0; i < this.attributes.length; i++) {
              this._setAttr(this.attributes[i].name);
          }
          // watch future attr changes
          new MutationObserver(mutations => {
              for (const m of mutations) {
                  this._setAttr(m.attributeName);
              }
          }).observe(this, { attributes: true });
          const resolve = (def) => {
              const { props, styles } = def;
              const hasOptions = !isArray(props);
              const rawKeys = props ? (hasOptions ? Object.keys(props) : props) : [];
              // cast Number-type props set before resolve
              let numberProps;
              if (hasOptions) {
                  for (const key in this._props) {
                      const opt = props[key];
                      if (opt === Number || (opt && opt.type === Number)) {
                          this._props[key] = toNumber(this._props[key]);
                          (numberProps || (numberProps = Object.create(null)))[key] = true;
                      }
                  }
              }
              this._numberProps = numberProps;
              // check if there are props set pre-upgrade or connect
              for (const key of Object.keys(this)) {
                  if (key[0] !== '_') {
                      this._setProp(key, this[key], true, false);
                  }
              }
              // defining getter/setters on prototype
              for (const key of rawKeys.map(camelize)) {
                  Object.defineProperty(this, key, {
                      get() {
                          return this._getProp(key);
                      },
                      set(val) {
                          this._setProp(key, val);
                      }
                  });
              }
              // apply CSS
              this._applyStyles(styles);
              // initial render
              this._update();
          };
          const asyncDef = this._def.__asyncLoader;
          if (asyncDef) {
              asyncDef().then(resolve);
          }
          else {
              resolve(this._def);
          }
      }
      _setAttr(key) {
          let value = this.getAttribute(key);
          if (this._numberProps && this._numberProps[key]) {
              value = toNumber(value);
          }
          this._setProp(camelize(key), value, false);
      }
      /**
       * @internal
       */
      _getProp(key) {
          return this._props[key];
      }
      /**
       * @internal
       */
      _setProp(key, val, shouldReflect = true, shouldUpdate = true) {
          if (val !== this._props[key]) {
              this._props[key] = val;
              if (shouldUpdate && this._instance) {
                  this._update();
              }
              // reflect
              if (shouldReflect) {
                  if (val === true) {
                      this.setAttribute(hyphenate(key), '');
                  }
                  else if (typeof val === 'string' || typeof val === 'number') {
                      this.setAttribute(hyphenate(key), val + '');
                  }
                  else if (!val) {
                      this.removeAttribute(hyphenate(key));
                  }
              }
          }
      }
      _update() {
          render(this._createVNode(), this.shadowRoot);
      }
      _createVNode() {
          const vnode = createVNode(this._def, extend({}, this._props));
          if (!this._instance) {
              vnode.ce = instance => {
                  this._instance = instance;
                  instance.isCE = true;
                  // HMR
                  {
                      instance.ceReload = newStyles => {
                          // always reset styles
                          if (this._styles) {
                              this._styles.forEach(s => this.shadowRoot.removeChild(s));
                              this._styles.length = 0;
                          }
                          this._applyStyles(newStyles);
                          // if this is an async component, ceReload is called from the inner
                          // component so no need to reload the async wrapper
                          if (!this._def.__asyncLoader) {
                              // reload
                              this._instance = null;
                              this._update();
                          }
                      };
                  }
                  // intercept emit
                  instance.emit = (event, ...args) => {
                      this.dispatchEvent(new CustomEvent(event, {
                          detail: args
                      }));
                  };
                  // locate nearest Vue custom element parent for provide/inject
                  let parent = this;
                  while ((parent =
                      parent && (parent.parentNode || parent.host))) {
                      if (parent instanceof VueElement) {
                          instance.parent = parent._instance;
                          break;
                      }
                  }
              };
          }
          return vnode;
      }
      _applyStyles(styles) {
          if (styles) {
              styles.forEach(css => {
                  const s = document.createElement('style');
                  s.textContent = css;
                  this.shadowRoot.appendChild(s);
                  // record for HMR
                  {
                      (this._styles || (this._styles = [])).push(s);
                  }
              });
          }
      }
  }

  function useCssModule(name = '$style') {
      /* istanbul ignore else */
      {
          {
              warn$1(`useCssModule() is not supported in the global build.`);
          }
          return EMPTY_OBJ;
      }
  }

  /**
   * Runtime helper for SFC's CSS variable injection feature.
   * @private
   */
  function useCssVars(getter) {
      const instance = getCurrentInstance();
      /* istanbul ignore next */
      if (!instance) {
          warn$1(`useCssVars is called without current active component instance.`);
          return;
      }
      const setVars = () => setVarsOnVNode(instance.subTree, getter(instance.proxy));
      watchPostEffect(setVars);
      onMounted(() => {
          const ob = new MutationObserver(setVars);
          ob.observe(instance.subTree.el.parentNode, { childList: true });
          onUnmounted(() => ob.disconnect());
      });
  }
  function setVarsOnVNode(vnode, vars) {
      if (vnode.shapeFlag & 128 /* SUSPENSE */) {
          const suspense = vnode.suspense;
          vnode = suspense.activeBranch;
          if (suspense.pendingBranch && !suspense.isHydrating) {
              suspense.effects.push(() => {
                  setVarsOnVNode(suspense.activeBranch, vars);
              });
          }
      }
      // drill down HOCs until it's a non-component vnode
      while (vnode.component) {
          vnode = vnode.component.subTree;
      }
      if (vnode.shapeFlag & 1 /* ELEMENT */ && vnode.el) {
          setVarsOnNode(vnode.el, vars);
      }
      else if (vnode.type === Fragment) {
          vnode.children.forEach(c => setVarsOnVNode(c, vars));
      }
      else if (vnode.type === Static) {
          let { el, anchor } = vnode;
          while (el) {
              setVarsOnNode(el, vars);
              if (el === anchor)
                  break;
              el = el.nextSibling;
          }
      }
  }
  function setVarsOnNode(el, vars) {
      if (el.nodeType === 1) {
          const style = el.style;
          for (const key in vars) {
              style.setProperty(`--${key}`, vars[key]);
          }
      }
  }

  const TRANSITION = 'transition';
  const ANIMATION = 'animation';
  // DOM Transition is a higher-order-component based on the platform-agnostic
  // base Transition component, with DOM-specific logic.
  const Transition = (props, { slots }) => h(BaseTransition, resolveTransitionProps(props), slots);
  Transition.displayName = 'Transition';
  {
      Transition.__isBuiltIn = true;
  }
  const DOMTransitionPropsValidators = {
      name: String,
      type: String,
      css: {
          type: Boolean,
          default: true
      },
      duration: [String, Number, Object],
      enterFromClass: String,
      enterActiveClass: String,
      enterToClass: String,
      appearFromClass: String,
      appearActiveClass: String,
      appearToClass: String,
      leaveFromClass: String,
      leaveActiveClass: String,
      leaveToClass: String
  };
  const TransitionPropsValidators = (Transition.props =
      /*#__PURE__*/ extend({}, BaseTransition.props, DOMTransitionPropsValidators));
  /**
   * #3227 Incoming hooks may be merged into arrays when wrapping Transition
   * with custom HOCs.
   */
  const callHook$1 = (hook, args = []) => {
      if (isArray(hook)) {
          hook.forEach(h => h(...args));
      }
      else if (hook) {
          hook(...args);
      }
  };
  /**
   * Check if a hook expects a callback (2nd arg), which means the user
   * intends to explicitly control the end of the transition.
   */
  const hasExplicitCallback = (hook) => {
      return hook
          ? isArray(hook)
              ? hook.some(h => h.length > 1)
              : hook.length > 1
          : false;
  };
  function resolveTransitionProps(rawProps) {
      const baseProps = {};
      for (const key in rawProps) {
          if (!(key in DOMTransitionPropsValidators)) {
              baseProps[key] = rawProps[key];
          }
      }
      if (rawProps.css === false) {
          return baseProps;
      }
      const { name = 'v', type, duration, enterFromClass = `${name}-enter-from`, enterActiveClass = `${name}-enter-active`, enterToClass = `${name}-enter-to`, appearFromClass = enterFromClass, appearActiveClass = enterActiveClass, appearToClass = enterToClass, leaveFromClass = `${name}-leave-from`, leaveActiveClass = `${name}-leave-active`, leaveToClass = `${name}-leave-to` } = rawProps;
      // legacy transition class compat
      const legacyClassEnabled = compatUtils.isCompatEnabled("TRANSITION_CLASSES" /* TRANSITION_CLASSES */, null);
      let legacyEnterFromClass;
      let legacyAppearFromClass;
      let legacyLeaveFromClass;
      if (legacyClassEnabled) {
          const toLegacyClass = (cls) => cls.replace(/-from$/, '');
          if (!rawProps.enterFromClass) {
              legacyEnterFromClass = toLegacyClass(enterFromClass);
          }
          if (!rawProps.appearFromClass) {
              legacyAppearFromClass = toLegacyClass(appearFromClass);
          }
          if (!rawProps.leaveFromClass) {
              legacyLeaveFromClass = toLegacyClass(leaveFromClass);
          }
      }
      const durations = normalizeDuration(duration);
      const enterDuration = durations && durations[0];
      const leaveDuration = durations && durations[1];
      const { onBeforeEnter, onEnter, onEnterCancelled, onLeave, onLeaveCancelled, onBeforeAppear = onBeforeEnter, onAppear = onEnter, onAppearCancelled = onEnterCancelled } = baseProps;
      const finishEnter = (el, isAppear, done) => {
          removeTransitionClass(el, isAppear ? appearToClass : enterToClass);
          removeTransitionClass(el, isAppear ? appearActiveClass : enterActiveClass);
          done && done();
      };
      const finishLeave = (el, done) => {
          el._isLeaving = false;
          removeTransitionClass(el, leaveFromClass);
          removeTransitionClass(el, leaveToClass);
          removeTransitionClass(el, leaveActiveClass);
          done && done();
      };
      const makeEnterHook = (isAppear) => {
          return (el, done) => {
              const hook = isAppear ? onAppear : onEnter;
              const resolve = () => finishEnter(el, isAppear, done);
              callHook$1(hook, [el, resolve]);
              nextFrame(() => {
                  removeTransitionClass(el, isAppear ? appearFromClass : enterFromClass);
                  if (legacyClassEnabled) {
                      removeTransitionClass(el, isAppear ? legacyAppearFromClass : legacyEnterFromClass);
                  }
                  addTransitionClass(el, isAppear ? appearToClass : enterToClass);
                  if (!hasExplicitCallback(hook)) {
                      whenTransitionEnds(el, type, enterDuration, resolve);
                  }
              });
          };
      };
      return extend(baseProps, {
          onBeforeEnter(el) {
              callHook$1(onBeforeEnter, [el]);
              addTransitionClass(el, enterFromClass);
              if (legacyClassEnabled) {
                  addTransitionClass(el, legacyEnterFromClass);
              }
              addTransitionClass(el, enterActiveClass);
          },
          onBeforeAppear(el) {
              callHook$1(onBeforeAppear, [el]);
              addTransitionClass(el, appearFromClass);
              if (legacyClassEnabled) {
                  addTransitionClass(el, legacyAppearFromClass);
              }
              addTransitionClass(el, appearActiveClass);
          },
          onEnter: makeEnterHook(false),
          onAppear: makeEnterHook(true),
          onLeave(el, done) {
              el._isLeaving = true;
              const resolve = () => finishLeave(el, done);
              addTransitionClass(el, leaveFromClass);
              if (legacyClassEnabled) {
                  addTransitionClass(el, legacyLeaveFromClass);
              }
              // force reflow so *-leave-from classes immediately take effect (#2593)
              forceReflow();
              addTransitionClass(el, leaveActiveClass);
              nextFrame(() => {
                  if (!el._isLeaving) {
                      // cancelled
                      return;
                  }
                  removeTransitionClass(el, leaveFromClass);
                  if (legacyClassEnabled) {
                      removeTransitionClass(el, legacyLeaveFromClass);
                  }
                  addTransitionClass(el, leaveToClass);
                  if (!hasExplicitCallback(onLeave)) {
                      whenTransitionEnds(el, type, leaveDuration, resolve);
                  }
              });
              callHook$1(onLeave, [el, resolve]);
          },
          onEnterCancelled(el) {
              finishEnter(el, false);
              callHook$1(onEnterCancelled, [el]);
          },
          onAppearCancelled(el) {
              finishEnter(el, true);
              callHook$1(onAppearCancelled, [el]);
          },
          onLeaveCancelled(el) {
              finishLeave(el);
              callHook$1(onLeaveCancelled, [el]);
          }
      });
  }
  function normalizeDuration(duration) {
      if (duration == null) {
          return null;
      }
      else if (isObject(duration)) {
          return [NumberOf(duration.enter), NumberOf(duration.leave)];
      }
      else {
          const n = NumberOf(duration);
          return [n, n];
      }
  }
  function NumberOf(val) {
      const res = toNumber(val);
      validateDuration(res);
      return res;
  }
  function validateDuration(val) {
      if (typeof val !== 'number') {
          warn$1(`<transition> explicit duration is not a valid number - ` +
              `got ${JSON.stringify(val)}.`);
      }
      else if (isNaN(val)) {
          warn$1(`<transition> explicit duration is NaN - ` +
              'the duration expression might be incorrect.');
      }
  }
  function addTransitionClass(el, cls) {
      cls.split(/\s+/).forEach(c => c && el.classList.add(c));
      (el._vtc ||
          (el._vtc = new Set())).add(cls);
  }
  function removeTransitionClass(el, cls) {
      cls.split(/\s+/).forEach(c => c && el.classList.remove(c));
      const { _vtc } = el;
      if (_vtc) {
          _vtc.delete(cls);
          if (!_vtc.size) {
              el._vtc = undefined;
          }
      }
  }
  function nextFrame(cb) {
      requestAnimationFrame(() => {
          requestAnimationFrame(cb);
      });
  }
  let endId = 0;
  function whenTransitionEnds(el, expectedType, explicitTimeout, resolve) {
      const id = (el._endId = ++endId);
      const resolveIfNotStale = () => {
          if (id === el._endId) {
              resolve();
          }
      };
      if (explicitTimeout) {
          return setTimeout(resolveIfNotStale, explicitTimeout);
      }
      const { type, timeout, propCount } = getTransitionInfo(el, expectedType);
      if (!type) {
          return resolve();
      }
      const endEvent = type + 'end';
      let ended = 0;
      const end = () => {
          el.removeEventListener(endEvent, onEnd);
          resolveIfNotStale();
      };
      const onEnd = (e) => {
          if (e.target === el && ++ended >= propCount) {
              end();
          }
      };
      setTimeout(() => {
          if (ended < propCount) {
              end();
          }
      }, timeout + 1);
      el.addEventListener(endEvent, onEnd);
  }
  function getTransitionInfo(el, expectedType) {
      const styles = window.getComputedStyle(el);
      // JSDOM may return undefined for transition properties
      const getStyleProperties = (key) => (styles[key] || '').split(', ');
      const transitionDelays = getStyleProperties(TRANSITION + 'Delay');
      const transitionDurations = getStyleProperties(TRANSITION + 'Duration');
      const transitionTimeout = getTimeout(transitionDelays, transitionDurations);
      const animationDelays = getStyleProperties(ANIMATION + 'Delay');
      const animationDurations = getStyleProperties(ANIMATION + 'Duration');
      const animationTimeout = getTimeout(animationDelays, animationDurations);
      let type = null;
      let timeout = 0;
      let propCount = 0;
      /* istanbul ignore if */
      if (expectedType === TRANSITION) {
          if (transitionTimeout > 0) {
              type = TRANSITION;
              timeout = transitionTimeout;
              propCount = transitionDurations.length;
          }
      }
      else if (expectedType === ANIMATION) {
          if (animationTimeout > 0) {
              type = ANIMATION;
              timeout = animationTimeout;
              propCount = animationDurations.length;
          }
      }
      else {
          timeout = Math.max(transitionTimeout, animationTimeout);
          type =
              timeout > 0
                  ? transitionTimeout > animationTimeout
                      ? TRANSITION
                      : ANIMATION
                  : null;
          propCount = type
              ? type === TRANSITION
                  ? transitionDurations.length
                  : animationDurations.length
              : 0;
      }
      const hasTransform = type === TRANSITION &&
          /\b(transform|all)(,|$)/.test(styles[TRANSITION + 'Property']);
      return {
          type,
          timeout,
          propCount,
          hasTransform
      };
  }
  function getTimeout(delays, durations) {
      while (delays.length < durations.length) {
          delays = delays.concat(delays);
      }
      return Math.max(...durations.map((d, i) => toMs(d) + toMs(delays[i])));
  }
  // Old versions of Chromium (below 61.0.3163.100) formats floating pointer
  // numbers in a locale-dependent way, using a comma instead of a dot.
  // If comma is not replaced with a dot, the input will be rounded down
  // (i.e. acting as a floor function) causing unexpected behaviors
  function toMs(s) {
      return Number(s.slice(0, -1).replace(',', '.')) * 1000;
  }
  // synchronously force layout to put elements into a certain state
  function forceReflow() {
      return document.body.offsetHeight;
  }

  const positionMap = new WeakMap();
  const newPositionMap = new WeakMap();
  const TransitionGroupImpl = {
      name: 'TransitionGroup',
      props: /*#__PURE__*/ extend({}, TransitionPropsValidators, {
          tag: String,
          moveClass: String
      }),
      setup(props, { slots }) {
          const instance = getCurrentInstance();
          const state = useTransitionState();
          let prevChildren;
          let children;
          onUpdated(() => {
              // children is guaranteed to exist after initial render
              if (!prevChildren.length) {
                  return;
              }
              const moveClass = props.moveClass || `${props.name || 'v'}-move`;
              if (!hasCSSTransform(prevChildren[0].el, instance.vnode.el, moveClass)) {
                  return;
              }
              // we divide the work into three loops to avoid mixing DOM reads and writes
              // in each iteration - which helps prevent layout thrashing.
              prevChildren.forEach(callPendingCbs);
              prevChildren.forEach(recordPosition);
              const movedChildren = prevChildren.filter(applyTranslation);
              // force reflow to put everything in position
              forceReflow();
              movedChildren.forEach(c => {
                  const el = c.el;
                  const style = el.style;
                  addTransitionClass(el, moveClass);
                  style.transform = style.webkitTransform = style.transitionDuration = '';
                  const cb = (el._moveCb = (e) => {
                      if (e && e.target !== el) {
                          return;
                      }
                      if (!e || /transform$/.test(e.propertyName)) {
                          el.removeEventListener('transitionend', cb);
                          el._moveCb = null;
                          removeTransitionClass(el, moveClass);
                      }
                  });
                  el.addEventListener('transitionend', cb);
              });
          });
          return () => {
              const rawProps = toRaw(props);
              const cssTransitionProps = resolveTransitionProps(rawProps);
              let tag = rawProps.tag || Fragment;
              if (!rawProps.tag &&
                  compatUtils.checkCompatEnabled("TRANSITION_GROUP_ROOT" /* TRANSITION_GROUP_ROOT */, instance.parent)) {
                  tag = 'span';
              }
              prevChildren = children;
              children = slots.default ? getTransitionRawChildren(slots.default()) : [];
              for (let i = 0; i < children.length; i++) {
                  const child = children[i];
                  if (child.key != null) {
                      setTransitionHooks(child, resolveTransitionHooks(child, cssTransitionProps, state, instance));
                  }
                  else {
                      warn$1(`<TransitionGroup> children must be keyed.`);
                  }
              }
              if (prevChildren) {
                  for (let i = 0; i < prevChildren.length; i++) {
                      const child = prevChildren[i];
                      setTransitionHooks(child, resolveTransitionHooks(child, cssTransitionProps, state, instance));
                      positionMap.set(child, child.el.getBoundingClientRect());
                  }
              }
              return createVNode(tag, null, children);
          };
      }
  };
  {
      TransitionGroupImpl.__isBuiltIn = true;
  }
  const TransitionGroup = TransitionGroupImpl;
  function callPendingCbs(c) {
      const el = c.el;
      if (el._moveCb) {
          el._moveCb();
      }
      if (el._enterCb) {
          el._enterCb();
      }
  }
  function recordPosition(c) {
      newPositionMap.set(c, c.el.getBoundingClientRect());
  }
  function applyTranslation(c) {
      const oldPos = positionMap.get(c);
      const newPos = newPositionMap.get(c);
      const dx = oldPos.left - newPos.left;
      const dy = oldPos.top - newPos.top;
      if (dx || dy) {
          const s = c.el.style;
          s.transform = s.webkitTransform = `translate(${dx}px,${dy}px)`;
          s.transitionDuration = '0s';
          return c;
      }
  }
  function hasCSSTransform(el, root, moveClass) {
      // Detect whether an element with the move class applied has
      // CSS transitions. Since the element may be inside an entering
      // transition at this very moment, we make a clone of it and remove
      // all other transition classes applied to ensure only the move class
      // is applied.
      const clone = el.cloneNode();
      if (el._vtc) {
          el._vtc.forEach(cls => {
              cls.split(/\s+/).forEach(c => c && clone.classList.remove(c));
          });
      }
      moveClass.split(/\s+/).forEach(c => c && clone.classList.add(c));
      clone.style.display = 'none';
      const container = (root.nodeType === 1 ? root : root.parentNode);
      container.appendChild(clone);
      const { hasTransform } = getTransitionInfo(clone);
      container.removeChild(clone);
      return hasTransform;
  }

  const getModelAssigner = (vnode) => {
      const fn = vnode.props['onUpdate:modelValue'] ||
          (vnode.props['onModelCompat:input']);
      return isArray(fn) ? value => invokeArrayFns(fn, value) : fn;
  };
  function onCompositionStart(e) {
      e.target.composing = true;
  }
  function onCompositionEnd(e) {
      const target = e.target;
      if (target.composing) {
          target.composing = false;
          target.dispatchEvent(new Event('input'));
      }
  }
  // We are exporting the v-model runtime directly as vnode hooks so that it can
  // be tree-shaken in case v-model is never used.
  const vModelText = {
      created(el, { modifiers: { lazy, trim, number } }, vnode) {
          el._assign = getModelAssigner(vnode);
          const castToNumber = number || (vnode.props && vnode.props.type === 'number');
          addEventListener(el, lazy ? 'change' : 'input', e => {
              if (e.target.composing)
                  return;
              let domValue = el.value;
              if (trim) {
                  domValue = domValue.trim();
              }
              if (castToNumber) {
                  domValue = toNumber(domValue);
              }
              el._assign(domValue);
          });
          if (trim) {
              addEventListener(el, 'change', () => {
                  el.value = el.value.trim();
              });
          }
          if (!lazy) {
              addEventListener(el, 'compositionstart', onCompositionStart);
              addEventListener(el, 'compositionend', onCompositionEnd);
              // Safari < 10.2 & UIWebView doesn't fire compositionend when
              // switching focus before confirming composition choice
              // this also fixes the issue where some browsers e.g. iOS Chrome
              // fires "change" instead of "input" on autocomplete.
              addEventListener(el, 'change', onCompositionEnd);
          }
      },
      // set value on mounted so it's after min/max for type="range"
      mounted(el, { value }) {
          el.value = value == null ? '' : value;
      },
      beforeUpdate(el, { value, modifiers: { lazy, trim, number } }, vnode) {
          el._assign = getModelAssigner(vnode);
          // avoid clearing unresolved text. #2302
          if (el.composing)
              return;
          if (document.activeElement === el && el.type !== 'range') {
              if (lazy) {
                  return;
              }
              if (trim && el.value.trim() === value) {
                  return;
              }
              if ((number || el.type === 'number') && toNumber(el.value) === value) {
                  return;
              }
          }
          const newValue = value == null ? '' : value;
          if (el.value !== newValue) {
              el.value = newValue;
          }
      }
  };
  const vModelCheckbox = {
      // #4096 array checkboxes need to be deep traversed
      deep: true,
      created(el, _, vnode) {
          el._assign = getModelAssigner(vnode);
          addEventListener(el, 'change', () => {
              const modelValue = el._modelValue;
              const elementValue = getValue(el);
              const checked = el.checked;
              const assign = el._assign;
              if (isArray(modelValue)) {
                  const index = looseIndexOf(modelValue, elementValue);
                  const found = index !== -1;
                  if (checked && !found) {
                      assign(modelValue.concat(elementValue));
                  }
                  else if (!checked && found) {
                      const filtered = [...modelValue];
                      filtered.splice(index, 1);
                      assign(filtered);
                  }
              }
              else if (isSet(modelValue)) {
                  const cloned = new Set(modelValue);
                  if (checked) {
                      cloned.add(elementValue);
                  }
                  else {
                      cloned.delete(elementValue);
                  }
                  assign(cloned);
              }
              else {
                  assign(getCheckboxValue(el, checked));
              }
          });
      },
      // set initial checked on mount to wait for true-value/false-value
      mounted: setChecked,
      beforeUpdate(el, binding, vnode) {
          el._assign = getModelAssigner(vnode);
          setChecked(el, binding, vnode);
      }
  };
  function setChecked(el, { value, oldValue }, vnode) {
      el._modelValue = value;
      if (isArray(value)) {
          el.checked = looseIndexOf(value, vnode.props.value) > -1;
      }
      else if (isSet(value)) {
          el.checked = value.has(vnode.props.value);
      }
      else if (value !== oldValue) {
          el.checked = looseEqual(value, getCheckboxValue(el, true));
      }
  }
  const vModelRadio = {
      created(el, { value }, vnode) {
          el.checked = looseEqual(value, vnode.props.value);
          el._assign = getModelAssigner(vnode);
          addEventListener(el, 'change', () => {
              el._assign(getValue(el));
          });
      },
      beforeUpdate(el, { value, oldValue }, vnode) {
          el._assign = getModelAssigner(vnode);
          if (value !== oldValue) {
              el.checked = looseEqual(value, vnode.props.value);
          }
      }
  };
  const vModelSelect = {
      // <select multiple> value need to be deep traversed
      deep: true,
      created(el, { value, modifiers: { number } }, vnode) {
          const isSetModel = isSet(value);
          addEventListener(el, 'change', () => {
              const selectedVal = Array.prototype.filter
                  .call(el.options, (o) => o.selected)
                  .map((o) => number ? toNumber(getValue(o)) : getValue(o));
              el._assign(el.multiple
                  ? isSetModel
                      ? new Set(selectedVal)
                      : selectedVal
                  : selectedVal[0]);
          });
          el._assign = getModelAssigner(vnode);
      },
      // set value in mounted & updated because <select> relies on its children
      // <option>s.
      mounted(el, { value }) {
          setSelected(el, value);
      },
      beforeUpdate(el, _binding, vnode) {
          el._assign = getModelAssigner(vnode);
      },
      updated(el, { value }) {
          setSelected(el, value);
      }
  };
  function setSelected(el, value) {
      const isMultiple = el.multiple;
      if (isMultiple && !isArray(value) && !isSet(value)) {
          warn$1(`<select multiple v-model> expects an Array or Set value for its binding, ` +
                  `but got ${Object.prototype.toString.call(value).slice(8, -1)}.`);
          return;
      }
      for (let i = 0, l = el.options.length; i < l; i++) {
          const option = el.options[i];
          const optionValue = getValue(option);
          if (isMultiple) {
              if (isArray(value)) {
                  option.selected = looseIndexOf(value, optionValue) > -1;
              }
              else {
                  option.selected = value.has(optionValue);
              }
          }
          else {
              if (looseEqual(getValue(option), value)) {
                  if (el.selectedIndex !== i)
                      el.selectedIndex = i;
                  return;
              }
          }
      }
      if (!isMultiple && el.selectedIndex !== -1) {
          el.selectedIndex = -1;
      }
  }
  // retrieve raw value set via :value bindings
  function getValue(el) {
      return '_value' in el ? el._value : el.value;
  }
  // retrieve raw value for true-value and false-value set via :true-value or :false-value bindings
  function getCheckboxValue(el, checked) {
      const key = checked ? '_trueValue' : '_falseValue';
      return key in el ? el[key] : checked;
  }
  const vModelDynamic = {
      created(el, binding, vnode) {
          callModelHook(el, binding, vnode, null, 'created');
      },
      mounted(el, binding, vnode) {
          callModelHook(el, binding, vnode, null, 'mounted');
      },
      beforeUpdate(el, binding, vnode, prevVNode) {
          callModelHook(el, binding, vnode, prevVNode, 'beforeUpdate');
      },
      updated(el, binding, vnode, prevVNode) {
          callModelHook(el, binding, vnode, prevVNode, 'updated');
      }
  };
  function resolveDynamicModel(tagName, type) {
      switch (tagName) {
          case 'SELECT':
              return vModelSelect;
          case 'TEXTAREA':
              return vModelText;
          default:
              switch (type) {
                  case 'checkbox':
                      return vModelCheckbox;
                  case 'radio':
                      return vModelRadio;
                  default:
                      return vModelText;
              }
      }
  }
  function callModelHook(el, binding, vnode, prevVNode, hook) {
      const modelToUse = resolveDynamicModel(el.tagName, vnode.props && vnode.props.type);
      const fn = modelToUse[hook];
      fn && fn(el, binding, vnode, prevVNode);
  }

  const systemModifiers = ['ctrl', 'shift', 'alt', 'meta'];
  const modifierGuards = {
      stop: e => e.stopPropagation(),
      prevent: e => e.preventDefault(),
      self: e => e.target !== e.currentTarget,
      ctrl: e => !e.ctrlKey,
      shift: e => !e.shiftKey,
      alt: e => !e.altKey,
      meta: e => !e.metaKey,
      left: e => 'button' in e && e.button !== 0,
      middle: e => 'button' in e && e.button !== 1,
      right: e => 'button' in e && e.button !== 2,
      exact: (e, modifiers) => systemModifiers.some(m => e[`${m}Key`] && !modifiers.includes(m))
  };
  /**
   * @private
   */
  const withModifiers = (fn, modifiers) => {
      return (event, ...args) => {
          for (let i = 0; i < modifiers.length; i++) {
              const guard = modifierGuards[modifiers[i]];
              if (guard && guard(event, modifiers))
                  return;
          }
          return fn(event, ...args);
      };
  };
  // Kept for 2.x compat.
  // Note: IE11 compat for `spacebar` and `del` is removed for now.
  const keyNames = {
      esc: 'escape',
      space: ' ',
      up: 'arrow-up',
      left: 'arrow-left',
      right: 'arrow-right',
      down: 'arrow-down',
      delete: 'backspace'
  };
  /**
   * @private
   */
  const withKeys = (fn, modifiers) => {
      let globalKeyCodes;
      let instance = null;
      {
          instance = getCurrentInstance();
          if (compatUtils.isCompatEnabled("CONFIG_KEY_CODES" /* CONFIG_KEY_CODES */, instance)) {
              if (instance) {
                  globalKeyCodes = instance.appContext.config.keyCodes;
              }
          }
          if (modifiers.some(m => /^\d+$/.test(m))) {
              compatUtils.warnDeprecation("V_ON_KEYCODE_MODIFIER" /* V_ON_KEYCODE_MODIFIER */, instance);
          }
      }
      return (event) => {
          if (!('key' in event)) {
              return;
          }
          const eventKey = hyphenate(event.key);
          if (modifiers.some(k => k === eventKey || keyNames[k] === eventKey)) {
              return fn(event);
          }
          {
              const keyCode = String(event.keyCode);
              if (compatUtils.isCompatEnabled("V_ON_KEYCODE_MODIFIER" /* V_ON_KEYCODE_MODIFIER */, instance) &&
                  modifiers.some(mod => mod == keyCode)) {
                  return fn(event);
              }
              if (globalKeyCodes) {
                  for (const mod of modifiers) {
                      const codes = globalKeyCodes[mod];
                      if (codes) {
                          const matches = isArray(codes)
                              ? codes.some(code => String(code) === keyCode)
                              : String(codes) === keyCode;
                          if (matches) {
                              return fn(event);
                          }
                      }
                  }
              }
          }
      };
  };

  const vShow = {
      beforeMount(el, { value }, { transition }) {
          el._vod = el.style.display === 'none' ? '' : el.style.display;
          if (transition && value) {
              transition.beforeEnter(el);
          }
          else {
              setDisplay(el, value);
          }
      },
      mounted(el, { value }, { transition }) {
          if (transition && value) {
              transition.enter(el);
          }
      },
      updated(el, { value, oldValue }, { transition }) {
          if (!value === !oldValue)
              return;
          if (transition) {
              if (value) {
                  transition.beforeEnter(el);
                  setDisplay(el, true);
                  transition.enter(el);
              }
              else {
                  transition.leave(el, () => {
                      setDisplay(el, false);
                  });
              }
          }
          else {
              setDisplay(el, value);
          }
      },
      beforeUnmount(el, { value }) {
          setDisplay(el, value);
      }
  };
  function setDisplay(el, value) {
      el.style.display = value ? el._vod : 'none';
  }

  const rendererOptions = /*#__PURE__*/ extend({ patchProp }, nodeOps);
  // lazy create the renderer - this makes core renderer logic tree-shakable
  // in case the user only imports reactivity utilities from Vue.
  let renderer;
  let enabledHydration = false;
  function ensureRenderer() {
      return (renderer ||
          (renderer = createRenderer(rendererOptions)));
  }
  function ensureHydrationRenderer() {
      renderer = enabledHydration
          ? renderer
          : createHydrationRenderer(rendererOptions);
      enabledHydration = true;
      return renderer;
  }
  // use explicit type casts here to avoid import() calls in rolled-up d.ts
  const render = ((...args) => {
      ensureRenderer().render(...args);
  });
  const hydrate = ((...args) => {
      ensureHydrationRenderer().hydrate(...args);
  });
  const createApp = ((...args) => {
      const app = ensureRenderer().createApp(...args);
      {
          injectNativeTagCheck(app);
          injectCompilerOptionsCheck(app);
      }
      const { mount } = app;
      app.mount = (containerOrSelector) => {
          const container = normalizeContainer(containerOrSelector);
          if (!container)
              return;
          const component = app._component;
          if (!isFunction(component) && !component.render && !component.template) {
              // __UNSAFE__
              // Reason: potential execution of JS expressions in in-DOM template.
              // The user must make sure the in-DOM template is trusted. If it's
              // rendered by the server, the template should not contain any user data.
              component.template = container.innerHTML;
              // 2.x compat check
              {
                  for (let i = 0; i < container.attributes.length; i++) {
                      const attr = container.attributes[i];
                      if (attr.name !== 'v-cloak' && /^(v-|:|@)/.test(attr.name)) {
                          compatUtils.warnDeprecation("GLOBAL_MOUNT_CONTAINER" /* GLOBAL_MOUNT_CONTAINER */, null);
                          break;
                      }
                  }
              }
          }
          // clear content before mounting
          container.innerHTML = '';
          const proxy = mount(container, false, container instanceof SVGElement);
          if (container instanceof Element) {
              container.removeAttribute('v-cloak');
              container.setAttribute('data-v-app', '');
          }
          return proxy;
      };
      return app;
  });
  const createSSRApp = ((...args) => {
      const app = ensureHydrationRenderer().createApp(...args);
      {
          injectNativeTagCheck(app);
          injectCompilerOptionsCheck(app);
      }
      const { mount } = app;
      app.mount = (containerOrSelector) => {
          const container = normalizeContainer(containerOrSelector);
          if (container) {
              return mount(container, true, container instanceof SVGElement);
          }
      };
      return app;
  });
  function injectNativeTagCheck(app) {
      // Inject `isNativeTag`
      // this is used for component name validation (dev only)
      Object.defineProperty(app.config, 'isNativeTag', {
          value: (tag) => isHTMLTag(tag) || isSVGTag(tag),
          writable: false
      });
  }
  // dev only
  function injectCompilerOptionsCheck(app) {
      if (isRuntimeOnly()) {
          const isCustomElement = app.config.isCustomElement;
          Object.defineProperty(app.config, 'isCustomElement', {
              get() {
                  return isCustomElement;
              },
              set() {
                  warn$1(`The \`isCustomElement\` config option is deprecated. Use ` +
                      `\`compilerOptions.isCustomElement\` instead.`);
              }
          });
          const compilerOptions = app.config.compilerOptions;
          const msg = `The \`compilerOptions\` config option is only respected when using ` +
              `a build of Vue.js that includes the runtime compiler (aka "full build"). ` +
              `Since you are using the runtime-only build, \`compilerOptions\` ` +
              `must be passed to \`@vue/compiler-dom\` in the build setup instead.\n` +
              `- For vue-loader: pass it via vue-loader's \`compilerOptions\` loader option.\n` +
              `- For vue-cli: see https://cli.vuejs.org/guide/webpack.html#modifying-options-of-a-loader\n` +
              `- For vite: pass it via @vitejs/plugin-vue options. See https://github.com/vitejs/vite/tree/main/packages/plugin-vue#example-for-passing-options-to-vuecompiler-dom`;
          Object.defineProperty(app.config, 'compilerOptions', {
              get() {
                  warn$1(msg);
                  return compilerOptions;
              },
              set() {
                  warn$1(msg);
              }
          });
      }
  }
  function normalizeContainer(container) {
      if (isString(container)) {
          const res = document.querySelector(container);
          if (!res) {
              warn$1(`Failed to mount app: mount target selector "${container}" returned null.`);
          }
          return res;
      }
      if (window.ShadowRoot &&
          container instanceof window.ShadowRoot &&
          container.mode === 'closed') {
          warn$1(`mounting on a ShadowRoot with \`{mode: "closed"}\` may lead to unpredictable bugs`);
      }
      return container;
  }
  /**
   * @internal
   */
  const initDirectivesForSSR = NOOP;

  var runtimeDom = /*#__PURE__*/Object.freeze({
    __proto__: null,
    render: render,
    hydrate: hydrate,
    createApp: createApp,
    createSSRApp: createSSRApp,
    initDirectivesForSSR: initDirectivesForSSR,
    defineCustomElement: defineCustomElement,
    defineSSRCustomElement: defineSSRCustomElement,
    VueElement: VueElement,
    useCssModule: useCssModule,
    useCssVars: useCssVars,
    Transition: Transition,
    TransitionGroup: TransitionGroup,
    vModelText: vModelText,
    vModelCheckbox: vModelCheckbox,
    vModelRadio: vModelRadio,
    vModelSelect: vModelSelect,
    vModelDynamic: vModelDynamic,
    withModifiers: withModifiers,
    withKeys: withKeys,
    vShow: vShow,
    reactive: reactive,
    ref: ref,
    readonly: readonly,
    unref: unref,
    proxyRefs: proxyRefs,
    isRef: isRef,
    toRef: toRef,
    toRefs: toRefs,
    isProxy: isProxy,
    isReactive: isReactive,
    isReadonly: isReadonly,
    isShallow: isShallow,
    customRef: customRef,
    triggerRef: triggerRef,
    shallowRef: shallowRef,
    shallowReactive: shallowReactive,
    shallowReadonly: shallowReadonly,
    markRaw: markRaw,
    toRaw: toRaw,
    effect: effect,
    stop: stop,
    ReactiveEffect: ReactiveEffect,
    effectScope: effectScope,
    EffectScope: EffectScope,
    getCurrentScope: getCurrentScope,
    onScopeDispose: onScopeDispose,
    computed: computed$1,
    watch: watch,
    watchEffect: watchEffect,
    watchPostEffect: watchPostEffect,
    watchSyncEffect: watchSyncEffect,
    onBeforeMount: onBeforeMount,
    onMounted: onMounted,
    onBeforeUpdate: onBeforeUpdate,
    onUpdated: onUpdated,
    onBeforeUnmount: onBeforeUnmount,
    onUnmounted: onUnmounted,
    onActivated: onActivated,
    onDeactivated: onDeactivated,
    onRenderTracked: onRenderTracked,
    onRenderTriggered: onRenderTriggered,
    onErrorCaptured: onErrorCaptured,
    onServerPrefetch: onServerPrefetch,
    provide: provide,
    inject: inject,
    nextTick: nextTick,
    defineComponent: defineComponent,
    defineAsyncComponent: defineAsyncComponent,
    useAttrs: useAttrs,
    useSlots: useSlots,
    defineProps: defineProps,
    defineEmits: defineEmits,
    defineExpose: defineExpose,
    withDefaults: withDefaults,
    mergeDefaults: mergeDefaults,
    createPropsRestProxy: createPropsRestProxy,
    withAsyncContext: withAsyncContext,
    getCurrentInstance: getCurrentInstance,
    h: h,
    createVNode: createVNode,
    cloneVNode: cloneVNode,
    mergeProps: mergeProps,
    isVNode: isVNode,
    Fragment: Fragment,
    Text: Text,
    Comment: Comment,
    Static: Static,
    Teleport: Teleport,
    Suspense: Suspense,
    KeepAlive: KeepAlive,
    BaseTransition: BaseTransition,
    withDirectives: withDirectives,
    useSSRContext: useSSRContext,
    ssrContextKey: ssrContextKey,
    createRenderer: createRenderer,
    createHydrationRenderer: createHydrationRenderer,
    queuePostFlushCb: queuePostFlushCb,
    warn: warn$1,
    handleError: handleError,
    callWithErrorHandling: callWithErrorHandling,
    callWithAsyncErrorHandling: callWithAsyncErrorHandling,
    resolveComponent: resolveComponent,
    resolveDirective: resolveDirective,
    resolveDynamicComponent: resolveDynamicComponent,
    registerRuntimeCompiler: registerRuntimeCompiler,
    isRuntimeOnly: isRuntimeOnly,
    useTransitionState: useTransitionState,
    resolveTransitionHooks: resolveTransitionHooks,
    setTransitionHooks: setTransitionHooks,
    getTransitionRawChildren: getTransitionRawChildren,
    initCustomFormatter: initCustomFormatter,
    get devtools () { return devtools; },
    setDevtoolsHook: setDevtoolsHook,
    withCtx: withCtx,
    pushScopeId: pushScopeId,
    popScopeId: popScopeId,
    withScopeId: withScopeId,
    renderList: renderList,
    toHandlers: toHandlers,
    renderSlot: renderSlot,
    createSlots: createSlots,
    withMemo: withMemo,
    isMemoSame: isMemoSame,
    openBlock: openBlock,
    createBlock: createBlock,
    setBlockTracking: setBlockTracking,
    createTextVNode: createTextVNode,
    createCommentVNode: createCommentVNode,
    createStaticVNode: createStaticVNode,
    createElementVNode: createBaseVNode,
    createElementBlock: createElementBlock,
    guardReactiveProps: guardReactiveProps,
    toDisplayString: toDisplayString,
    camelize: camelize,
    capitalize: capitalize,
    toHandlerKey: toHandlerKey,
    normalizeProps: normalizeProps,
    normalizeClass: normalizeClass,
    normalizeStyle: normalizeStyle,
    transformVNodeArgs: transformVNodeArgs,
    version: version,
    ssrUtils: ssrUtils,
    resolveFilter: resolveFilter$1,
    compatUtils: compatUtils
  });

  function initDev() {
      {
          {
              console.info(`You are running a development build of Vue.\n` +
                  `Make sure to use the production build (*.prod.js) when deploying for production.`);
          }
          initCustomFormatter();
      }
  }

  // This entry exports the runtime only, and is built as
  {
      initDev();
  }
  function wrappedCreateApp(...args) {
      // @ts-ignore
      const app = createApp(...args);
      if (compatUtils.isCompatEnabled("RENDER_FUNCTION" /* RENDER_FUNCTION */, null)) {
          // register built-in components so that they can be resolved via strings
          // in the legacy h() call. The __compat__ prefix is to ensure that v3 h()
          // doesn't get affected.
          app.component('__compat__transition', Transition);
          app.component('__compat__transition-group', TransitionGroup);
          app.component('__compat__keep-alive', KeepAlive);
          // built-in directives. No need for prefix since there's no render fn API
          // for resolving directives via string in v3.
          app._context.directives.show = vShow;
          app._context.directives.model = vModelDynamic;
      }
      return app;
  }
  function createCompatVue$1() {
      const Vue = compatUtils.createCompatVue(createApp, wrappedCreateApp);
      extend(Vue, runtimeDom);
      return Vue;
  }

  function defaultOnError(error) {
      throw error;
  }
  function defaultOnWarn(msg) {
      console.warn(`[Vue warn] ${msg.message}`);
  }
  function createCompilerError(code, loc, messages, additionalMessage) {
      const msg = (messages || errorMessages)[code] + (additionalMessage || ``)
          ;
      const error = new SyntaxError(String(msg));
      error.code = code;
      error.loc = loc;
      return error;
  }
  const errorMessages = {
      // parse errors
      [0 /* ABRUPT_CLOSING_OF_EMPTY_COMMENT */]: 'Illegal comment.',
      [1 /* CDATA_IN_HTML_CONTENT */]: 'CDATA section is allowed only in XML context.',
      [2 /* DUPLICATE_ATTRIBUTE */]: 'Duplicate attribute.',
      [3 /* END_TAG_WITH_ATTRIBUTES */]: 'End tag cannot have attributes.',
      [4 /* END_TAG_WITH_TRAILING_SOLIDUS */]: "Illegal '/' in tags.",
      [5 /* EOF_BEFORE_TAG_NAME */]: 'Unexpected EOF in tag.',
      [6 /* EOF_IN_CDATA */]: 'Unexpected EOF in CDATA section.',
      [7 /* EOF_IN_COMMENT */]: 'Unexpected EOF in comment.',
      [8 /* EOF_IN_SCRIPT_HTML_COMMENT_LIKE_TEXT */]: 'Unexpected EOF in script.',
      [9 /* EOF_IN_TAG */]: 'Unexpected EOF in tag.',
      [10 /* INCORRECTLY_CLOSED_COMMENT */]: 'Incorrectly closed comment.',
      [11 /* INCORRECTLY_OPENED_COMMENT */]: 'Incorrectly opened comment.',
      [12 /* INVALID_FIRST_CHARACTER_OF_TAG_NAME */]: "Illegal tag name. Use '&lt;' to print '<'.",
      [13 /* MISSING_ATTRIBUTE_VALUE */]: 'Attribute value was expected.',
      [14 /* MISSING_END_TAG_NAME */]: 'End tag name was expected.',
      [15 /* MISSING_WHITESPACE_BETWEEN_ATTRIBUTES */]: 'Whitespace was expected.',
      [16 /* NESTED_COMMENT */]: "Unexpected '<!--' in comment.",
      [17 /* UNEXPECTED_CHARACTER_IN_ATTRIBUTE_NAME */]: 'Attribute name cannot contain U+0022 ("), U+0027 (\'), and U+003C (<).',
      [18 /* UNEXPECTED_CHARACTER_IN_UNQUOTED_ATTRIBUTE_VALUE */]: 'Unquoted attribute value cannot contain U+0022 ("), U+0027 (\'), U+003C (<), U+003D (=), and U+0060 (`).',
      [19 /* UNEXPECTED_EQUALS_SIGN_BEFORE_ATTRIBUTE_NAME */]: "Attribute name cannot start with '='.",
      [21 /* UNEXPECTED_QUESTION_MARK_INSTEAD_OF_TAG_NAME */]: "'<?' is allowed only in XML context.",
      [20 /* UNEXPECTED_NULL_CHARACTER */]: `Unexpected null character.`,
      [22 /* UNEXPECTED_SOLIDUS_IN_TAG */]: "Illegal '/' in tags.",
      // Vue-specific parse errors
      [23 /* X_INVALID_END_TAG */]: 'Invalid end tag.',
      [24 /* X_MISSING_END_TAG */]: 'Element is missing end tag.',
      [25 /* X_MISSING_INTERPOLATION_END */]: 'Interpolation end sign was not found.',
      [27 /* X_MISSING_DYNAMIC_DIRECTIVE_ARGUMENT_END */]: 'End bracket for dynamic directive argument was not found. ' +
          'Note that dynamic directive argument cannot contain spaces.',
      [26 /* X_MISSING_DIRECTIVE_NAME */]: 'Legal directive name was expected.',
      // transform errors
      [28 /* X_V_IF_NO_EXPRESSION */]: `v-if/v-else-if is missing expression.`,
      [29 /* X_V_IF_SAME_KEY */]: `v-if/else branches must use unique keys.`,
      [30 /* X_V_ELSE_NO_ADJACENT_IF */]: `v-else/v-else-if has no adjacent v-if or v-else-if.`,
      [31 /* X_V_FOR_NO_EXPRESSION */]: `v-for is missing expression.`,
      [32 /* X_V_FOR_MALFORMED_EXPRESSION */]: `v-for has invalid expression.`,
      [33 /* X_V_FOR_TEMPLATE_KEY_PLACEMENT */]: `<template v-for> key should be placed on the <template> tag.`,
      [34 /* X_V_BIND_NO_EXPRESSION */]: `v-bind is missing expression.`,
      [35 /* X_V_ON_NO_EXPRESSION */]: `v-on is missing expression.`,
      [36 /* X_V_SLOT_UNEXPECTED_DIRECTIVE_ON_SLOT_OUTLET */]: `Unexpected custom directive on <slot> outlet.`,
      [37 /* X_V_SLOT_MIXED_SLOT_USAGE */]: `Mixed v-slot usage on both the component and nested <template>.` +
          `When there are multiple named slots, all slots should use <template> ` +
          `syntax to avoid scope ambiguity.`,
      [38 /* X_V_SLOT_DUPLICATE_SLOT_NAMES */]: `Duplicate slot names found. `,
      [39 /* X_V_SLOT_EXTRANEOUS_DEFAULT_SLOT_CHILDREN */]: `Extraneous children found when component already has explicitly named ` +
          `default slot. These children will be ignored.`,
      [40 /* X_V_SLOT_MISPLACED */]: `v-slot can only be used on components or <template> tags.`,
      [41 /* X_V_MODEL_NO_EXPRESSION */]: `v-model is missing expression.`,
      [42 /* X_V_MODEL_MALFORMED_EXPRESSION */]: `v-model value must be a valid JavaScript member expression.`,
      [43 /* X_V_MODEL_ON_SCOPE_VARIABLE */]: `v-model cannot be used on v-for or v-slot scope variables because they are not writable.`,
      [44 /* X_INVALID_EXPRESSION */]: `Error parsing JavaScript expression: `,
      [45 /* X_KEEP_ALIVE_INVALID_CHILDREN */]: `<KeepAlive> expects exactly one child component.`,
      // generic errors
      [46 /* X_PREFIX_ID_NOT_SUPPORTED */]: `"prefixIdentifiers" option is not supported in this build of compiler.`,
      [47 /* X_MODULE_MODE_NOT_SUPPORTED */]: `ES module mode is not supported in this build of compiler.`,
      [48 /* X_CACHE_HANDLER_NOT_SUPPORTED */]: `"cacheHandlers" option is only supported when the "prefixIdentifiers" option is enabled.`,
      [49 /* X_SCOPE_ID_NOT_SUPPORTED */]: `"scopeId" option is only supported in module mode.`,
      // just to fulfill types
      [50 /* __EXTEND_POINT__ */]: ``
  };

  const FRAGMENT = Symbol(`Fragment` );
  const TELEPORT = Symbol(`Teleport` );
  const SUSPENSE = Symbol(`Suspense` );
  const KEEP_ALIVE = Symbol(`KeepAlive` );
  const BASE_TRANSITION = Symbol(`BaseTransition` );
  const OPEN_BLOCK = Symbol(`openBlock` );
  const CREATE_BLOCK = Symbol(`createBlock` );
  const CREATE_ELEMENT_BLOCK = Symbol(`createElementBlock` );
  const CREATE_VNODE = Symbol(`createVNode` );
  const CREATE_ELEMENT_VNODE = Symbol(`createElementVNode` );
  const CREATE_COMMENT = Symbol(`createCommentVNode` );
  const CREATE_TEXT = Symbol(`createTextVNode` );
  const CREATE_STATIC = Symbol(`createStaticVNode` );
  const RESOLVE_COMPONENT = Symbol(`resolveComponent` );
  const RESOLVE_DYNAMIC_COMPONENT = Symbol(`resolveDynamicComponent` );
  const RESOLVE_DIRECTIVE = Symbol(`resolveDirective` );
  const RESOLVE_FILTER = Symbol(`resolveFilter` );
  const WITH_DIRECTIVES = Symbol(`withDirectives` );
  const RENDER_LIST = Symbol(`renderList` );
  const RENDER_SLOT = Symbol(`renderSlot` );
  const CREATE_SLOTS = Symbol(`createSlots` );
  const TO_DISPLAY_STRING = Symbol(`toDisplayString` );
  const MERGE_PROPS = Symbol(`mergeProps` );
  const NORMALIZE_CLASS = Symbol(`normalizeClass` );
  const NORMALIZE_STYLE = Symbol(`normalizeStyle` );
  const NORMALIZE_PROPS = Symbol(`normalizeProps` );
  const GUARD_REACTIVE_PROPS = Symbol(`guardReactiveProps` );
  const TO_HANDLERS = Symbol(`toHandlers` );
  const CAMELIZE = Symbol(`camelize` );
  const CAPITALIZE = Symbol(`capitalize` );
  const TO_HANDLER_KEY = Symbol(`toHandlerKey` );
  const SET_BLOCK_TRACKING = Symbol(`setBlockTracking` );
  const PUSH_SCOPE_ID = Symbol(`pushScopeId` );
  const POP_SCOPE_ID = Symbol(`popScopeId` );
  const WITH_CTX = Symbol(`withCtx` );
  const UNREF = Symbol(`unref` );
  const IS_REF = Symbol(`isRef` );
  const WITH_MEMO = Symbol(`withMemo` );
  const IS_MEMO_SAME = Symbol(`isMemoSame` );
  // Name mapping for runtime helpers that need to be imported from 'vue' in
  // generated code. Make sure these are correctly exported in the runtime!
  // Using `any` here because TS doesn't allow symbols as index type.
  const helperNameMap = {
      [FRAGMENT]: `Fragment`,
      [TELEPORT]: `Teleport`,
      [SUSPENSE]: `Suspense`,
      [KEEP_ALIVE]: `KeepAlive`,
      [BASE_TRANSITION]: `BaseTransition`,
      [OPEN_BLOCK]: `openBlock`,
      [CREATE_BLOCK]: `createBlock`,
      [CREATE_ELEMENT_BLOCK]: `createElementBlock`,
      [CREATE_VNODE]: `createVNode`,
      [CREATE_ELEMENT_VNODE]: `createElementVNode`,
      [CREATE_COMMENT]: `createCommentVNode`,
      [CREATE_TEXT]: `createTextVNode`,
      [CREATE_STATIC]: `createStaticVNode`,
      [RESOLVE_COMPONENT]: `resolveComponent`,
      [RESOLVE_DYNAMIC_COMPONENT]: `resolveDynamicComponent`,
      [RESOLVE_DIRECTIVE]: `resolveDirective`,
      [RESOLVE_FILTER]: `resolveFilter`,
      [WITH_DIRECTIVES]: `withDirectives`,
      [RENDER_LIST]: `renderList`,
      [RENDER_SLOT]: `renderSlot`,
      [CREATE_SLOTS]: `createSlots`,
      [TO_DISPLAY_STRING]: `toDisplayString`,
      [MERGE_PROPS]: `mergeProps`,
      [NORMALIZE_CLASS]: `normalizeClass`,
      [NORMALIZE_STYLE]: `normalizeStyle`,
      [NORMALIZE_PROPS]: `normalizeProps`,
      [GUARD_REACTIVE_PROPS]: `guardReactiveProps`,
      [TO_HANDLERS]: `toHandlers`,
      [CAMELIZE]: `camelize`,
      [CAPITALIZE]: `capitalize`,
      [TO_HANDLER_KEY]: `toHandlerKey`,
      [SET_BLOCK_TRACKING]: `setBlockTracking`,
      [PUSH_SCOPE_ID]: `pushScopeId`,
      [POP_SCOPE_ID]: `popScopeId`,
      [WITH_CTX]: `withCtx`,
      [UNREF]: `unref`,
      [IS_REF]: `isRef`,
      [WITH_MEMO]: `withMemo`,
      [IS_MEMO_SAME]: `isMemoSame`
  };
  function registerRuntimeHelpers(helpers) {
      Object.getOwnPropertySymbols(helpers).forEach(s => {
          helperNameMap[s] = helpers[s];
      });
  }

  // AST Utilities ---------------------------------------------------------------
  // Some expressions, e.g. sequence and conditional expressions, are never
  // associated with template nodes, so their source locations are just a stub.
  // Container types like CompoundExpression also don't need a real location.
  const locStub = {
      source: '',
      start: { line: 1, column: 1, offset: 0 },
      end: { line: 1, column: 1, offset: 0 }
  };
  function createRoot(children, loc = locStub) {
      return {
          type: 0 /* ROOT */,
          children,
          helpers: [],
          components: [],
          directives: [],
          hoists: [],
          imports: [],
          cached: 0,
          temps: 0,
          codegenNode: undefined,
          loc
      };
  }
  function createVNodeCall(context, tag, props, children, patchFlag, dynamicProps, directives, isBlock = false, disableTracking = false, isComponent = false, loc = locStub) {
      if (context) {
          if (isBlock) {
              context.helper(OPEN_BLOCK);
              context.helper(getVNodeBlockHelper(context.inSSR, isComponent));
          }
          else {
              context.helper(getVNodeHelper(context.inSSR, isComponent));
          }
          if (directives) {
              context.helper(WITH_DIRECTIVES);
          }
      }
      return {
          type: 13 /* VNODE_CALL */,
          tag,
          props,
          children,
          patchFlag,
          dynamicProps,
          directives,
          isBlock,
          disableTracking,
          isComponent,
          loc
      };
  }
  function createArrayExpression(elements, loc = locStub) {
      return {
          type: 17 /* JS_ARRAY_EXPRESSION */,
          loc,
          elements
      };
  }
  function createObjectExpression(properties, loc = locStub) {
      return {
          type: 15 /* JS_OBJECT_EXPRESSION */,
          loc,
          properties
      };
  }
  function createObjectProperty(key, value) {
      return {
          type: 16 /* JS_PROPERTY */,
          loc: locStub,
          key: isString(key) ? createSimpleExpression(key, true) : key,
          value
      };
  }
  function createSimpleExpression(content, isStatic = false, loc = locStub, constType = 0 /* NOT_CONSTANT */) {
      return {
          type: 4 /* SIMPLE_EXPRESSION */,
          loc,
          content,
          isStatic,
          constType: isStatic ? 3 /* CAN_STRINGIFY */ : constType
      };
  }
  function createCompoundExpression(children, loc = locStub) {
      return {
          type: 8 /* COMPOUND_EXPRESSION */,
          loc,
          children
      };
  }
  function createCallExpression(callee, args = [], loc = locStub) {
      return {
          type: 14 /* JS_CALL_EXPRESSION */,
          loc,
          callee,
          arguments: args
      };
  }
  function createFunctionExpression(params, returns = undefined, newline = false, isSlot = false, loc = locStub) {
      return {
          type: 18 /* JS_FUNCTION_EXPRESSION */,
          params,
          returns,
          newline,
          isSlot,
          loc
      };
  }
  function createConditionalExpression(test, consequent, alternate, newline = true) {
      return {
          type: 19 /* JS_CONDITIONAL_EXPRESSION */,
          test,
          consequent,
          alternate,
          newline,
          loc: locStub
      };
  }
  function createCacheExpression(index, value, isVNode = false) {
      return {
          type: 20 /* JS_CACHE_EXPRESSION */,
          index,
          value,
          isVNode,
          loc: locStub
      };
  }
  function createBlockStatement(body) {
      return {
          type: 21 /* JS_BLOCK_STATEMENT */,
          body,
          loc: locStub
      };
  }

  const isStaticExp = (p) => p.type === 4 /* SIMPLE_EXPRESSION */ && p.isStatic;
  const isBuiltInType = (tag, expected) => tag === expected || tag === hyphenate(expected);
  function isCoreComponent(tag) {
      if (isBuiltInType(tag, 'Teleport')) {
          return TELEPORT;
      }
      else if (isBuiltInType(tag, 'Suspense')) {
          return SUSPENSE;
      }
      else if (isBuiltInType(tag, 'KeepAlive')) {
          return KEEP_ALIVE;
      }
      else if (isBuiltInType(tag, 'BaseTransition')) {
          return BASE_TRANSITION;
      }
  }
  const nonIdentifierRE = /^\d|[^\$\w]/;
  const isSimpleIdentifier = (name) => !nonIdentifierRE.test(name);
  const validFirstIdentCharRE = /[A-Za-z_$\xA0-\uFFFF]/;
  const validIdentCharRE = /[\.\?\w$\xA0-\uFFFF]/;
  const whitespaceRE = /\s+[.[]\s*|\s*[.[]\s+/g;
  /**
   * Simple lexer to check if an expression is a member expression. This is
   * lax and only checks validity at the root level (i.e. does not validate exps
   * inside square brackets), but it's ok since these are only used on template
   * expressions and false positives are invalid expressions in the first place.
   */
  const isMemberExpressionBrowser = (path) => {
      // remove whitespaces around . or [ first
      path = path.trim().replace(whitespaceRE, s => s.trim());
      let state = 0 /* inMemberExp */;
      let stateStack = [];
      let currentOpenBracketCount = 0;
      let currentOpenParensCount = 0;
      let currentStringType = null;
      for (let i = 0; i < path.length; i++) {
          const char = path.charAt(i);
          switch (state) {
              case 0 /* inMemberExp */:
                  if (char === '[') {
                      stateStack.push(state);
                      state = 1 /* inBrackets */;
                      currentOpenBracketCount++;
                  }
                  else if (char === '(') {
                      stateStack.push(state);
                      state = 2 /* inParens */;
                      currentOpenParensCount++;
                  }
                  else if (!(i === 0 ? validFirstIdentCharRE : validIdentCharRE).test(char)) {
                      return false;
                  }
                  break;
              case 1 /* inBrackets */:
                  if (char === `'` || char === `"` || char === '`') {
                      stateStack.push(state);
                      state = 3 /* inString */;
                      currentStringType = char;
                  }
                  else if (char === `[`) {
                      currentOpenBracketCount++;
                  }
                  else if (char === `]`) {
                      if (!--currentOpenBracketCount) {
                          state = stateStack.pop();
                      }
                  }
                  break;
              case 2 /* inParens */:
                  if (char === `'` || char === `"` || char === '`') {
                      stateStack.push(state);
                      state = 3 /* inString */;
                      currentStringType = char;
                  }
                  else if (char === `(`) {
                      currentOpenParensCount++;
                  }
                  else if (char === `)`) {
                      // if the exp ends as a call then it should not be considered valid
                      if (i === path.length - 1) {
                          return false;
                      }
                      if (!--currentOpenParensCount) {
                          state = stateStack.pop();
                      }
                  }
                  break;
              case 3 /* inString */:
                  if (char === currentStringType) {
                      state = stateStack.pop();
                      currentStringType = null;
                  }
                  break;
          }
      }
      return !currentOpenBracketCount && !currentOpenParensCount;
  };
  const isMemberExpression = isMemberExpressionBrowser
      ;
  function getInnerRange(loc, offset, length) {
      const source = loc.source.slice(offset, offset + length);
      const newLoc = {
          source,
          start: advancePositionWithClone(loc.start, loc.source, offset),
          end: loc.end
      };
      if (length != null) {
          newLoc.end = advancePositionWithClone(loc.start, loc.source, offset + length);
      }
      return newLoc;
  }
  function advancePositionWithClone(pos, source, numberOfCharacters = source.length) {
      return advancePositionWithMutation(extend({}, pos), source, numberOfCharacters);
  }
  // advance by mutation without cloning (for performance reasons), since this
  // gets called a lot in the parser
  function advancePositionWithMutation(pos, source, numberOfCharacters = source.length) {
      let linesCount = 0;
      let lastNewLinePos = -1;
      for (let i = 0; i < numberOfCharacters; i++) {
          if (source.charCodeAt(i) === 10 /* newline char code */) {
              linesCount++;
              lastNewLinePos = i;
          }
      }
      pos.offset += numberOfCharacters;
      pos.line += linesCount;
      pos.column =
          lastNewLinePos === -1
              ? pos.column + numberOfCharacters
              : numberOfCharacters - lastNewLinePos;
      return pos;
  }
  function assert(condition, msg) {
      /* istanbul ignore if */
      if (!condition) {
          throw new Error(msg || `unexpected compiler condition`);
      }
  }
  function findDir(node, name, allowEmpty = false) {
      for (let i = 0; i < node.props.length; i++) {
          const p = node.props[i];
          if (p.type === 7 /* DIRECTIVE */ &&
              (allowEmpty || p.exp) &&
              (isString(name) ? p.name === name : name.test(p.name))) {
              return p;
          }
      }
  }
  function findProp(node, name, dynamicOnly = false, allowEmpty = false) {
      for (let i = 0; i < node.props.length; i++) {
          const p = node.props[i];
          if (p.type === 6 /* ATTRIBUTE */) {
              if (dynamicOnly)
                  continue;
              if (p.name === name && (p.value || allowEmpty)) {
                  return p;
              }
          }
          else if (p.name === 'bind' &&
              (p.exp || allowEmpty) &&
              isStaticArgOf(p.arg, name)) {
              return p;
          }
      }
  }
  function isStaticArgOf(arg, name) {
      return !!(arg && isStaticExp(arg) && arg.content === name);
  }
  function hasDynamicKeyVBind(node) {
      return node.props.some(p => p.type === 7 /* DIRECTIVE */ &&
          p.name === 'bind' &&
          (!p.arg || // v-bind="obj"
              p.arg.type !== 4 /* SIMPLE_EXPRESSION */ || // v-bind:[_ctx.foo]
              !p.arg.isStatic) // v-bind:[foo]
      );
  }
  function isText(node) {
      return node.type === 5 /* INTERPOLATION */ || node.type === 2 /* TEXT */;
  }
  function isVSlot(p) {
      return p.type === 7 /* DIRECTIVE */ && p.name === 'slot';
  }
  function isTemplateNode(node) {
      return (node.type === 1 /* ELEMENT */ && node.tagType === 3 /* TEMPLATE */);
  }
  function isSlotOutlet(node) {
      return node.type === 1 /* ELEMENT */ && node.tagType === 2 /* SLOT */;
  }
  function getVNodeHelper(ssr, isComponent) {
      return ssr || isComponent ? CREATE_VNODE : CREATE_ELEMENT_VNODE;
  }
  function getVNodeBlockHelper(ssr, isComponent) {
      return ssr || isComponent ? CREATE_BLOCK : CREATE_ELEMENT_BLOCK;
  }
  const propsHelperSet = new Set([NORMALIZE_PROPS, GUARD_REACTIVE_PROPS]);
  function getUnnormalizedProps(props, callPath = []) {
      if (props &&
          !isString(props) &&
          props.type === 14 /* JS_CALL_EXPRESSION */) {
          const callee = props.callee;
          if (!isString(callee) && propsHelperSet.has(callee)) {
              return getUnnormalizedProps(props.arguments[0], callPath.concat(props));
          }
      }
      return [props, callPath];
  }
  function injectProp(node, prop, context) {
      let propsWithInjection;
      /**
       * 1. mergeProps(...)
       * 2. toHandlers(...)
       * 3. normalizeProps(...)
       * 4. normalizeProps(guardReactiveProps(...))
       *
       * we need to get the real props before normalization
       */
      let props = node.type === 13 /* VNODE_CALL */ ? node.props : node.arguments[2];
      let callPath = [];
      let parentCall;
      if (props &&
          !isString(props) &&
          props.type === 14 /* JS_CALL_EXPRESSION */) {
          const ret = getUnnormalizedProps(props);
          props = ret[0];
          callPath = ret[1];
          parentCall = callPath[callPath.length - 1];
      }
      if (props == null || isString(props)) {
          propsWithInjection = createObjectExpression([prop]);
      }
      else if (props.type === 14 /* JS_CALL_EXPRESSION */) {
          // merged props... add ours
          // only inject key to object literal if it's the first argument so that
          // if doesn't override user provided keys
          const first = props.arguments[0];
          if (!isString(first) && first.type === 15 /* JS_OBJECT_EXPRESSION */) {
              first.properties.unshift(prop);
          }
          else {
              if (props.callee === TO_HANDLERS) {
                  // #2366
                  propsWithInjection = createCallExpression(context.helper(MERGE_PROPS), [
                      createObjectExpression([prop]),
                      props
                  ]);
              }
              else {
                  props.arguments.unshift(createObjectExpression([prop]));
              }
          }
          !propsWithInjection && (propsWithInjection = props);
      }
      else if (props.type === 15 /* JS_OBJECT_EXPRESSION */) {
          let alreadyExists = false;
          // check existing key to avoid overriding user provided keys
          if (prop.key.type === 4 /* SIMPLE_EXPRESSION */) {
              const propKeyName = prop.key.content;
              alreadyExists = props.properties.some(p => p.key.type === 4 /* SIMPLE_EXPRESSION */ &&
                  p.key.content === propKeyName);
          }
          if (!alreadyExists) {
              props.properties.unshift(prop);
          }
          propsWithInjection = props;
      }
      else {
          // single v-bind with expression, return a merged replacement
          propsWithInjection = createCallExpression(context.helper(MERGE_PROPS), [
              createObjectExpression([prop]),
              props
          ]);
          // in the case of nested helper call, e.g. `normalizeProps(guardReactiveProps(props))`,
          // it will be rewritten as `normalizeProps(mergeProps({ key: 0 }, props))`,
          // the `guardReactiveProps` will no longer be needed
          if (parentCall && parentCall.callee === GUARD_REACTIVE_PROPS) {
              parentCall = callPath[callPath.length - 2];
          }
      }
      if (node.type === 13 /* VNODE_CALL */) {
          if (parentCall) {
              parentCall.arguments[0] = propsWithInjection;
          }
          else {
              node.props = propsWithInjection;
          }
      }
      else {
          if (parentCall) {
              parentCall.arguments[0] = propsWithInjection;
          }
          else {
              node.arguments[2] = propsWithInjection;
          }
      }
  }
  function toValidAssetId(name, type) {
      // see issue#4422, we need adding identifier on validAssetId if variable `name` has specific character
      return `_${type}_${name.replace(/[^\w]/g, (searchValue, replaceValue) => {
        return searchValue === '-' ? '_' : name.charCodeAt(replaceValue).toString();
    })}`;
  }
  function getMemoedVNodeCall(node) {
      if (node.type === 14 /* JS_CALL_EXPRESSION */ && node.callee === WITH_MEMO) {
          return node.arguments[1].returns;
      }
      else {
          return node;
      }
  }
  function makeBlock(node, { helper, removeHelper, inSSR }) {
      if (!node.isBlock) {
          node.isBlock = true;
          removeHelper(getVNodeHelper(inSSR, node.isComponent));
          helper(OPEN_BLOCK);
          helper(getVNodeBlockHelper(inSSR, node.isComponent));
      }
  }

  const deprecationData$1 = {
      ["COMPILER_IS_ON_ELEMENT" /* COMPILER_IS_ON_ELEMENT */]: {
          message: `Platform-native elements with "is" prop will no longer be ` +
              `treated as components in Vue 3 unless the "is" value is explicitly ` +
              `prefixed with "vue:".`,
          link: `https://v3-migration.vuejs.org/breaking-changes/custom-elements-interop.html`
      },
      ["COMPILER_V_BIND_SYNC" /* COMPILER_V_BIND_SYNC */]: {
          message: key => `.sync modifier for v-bind has been removed. Use v-model with ` +
              `argument instead. \`v-bind:${key}.sync\` should be changed to ` +
              `\`v-model:${key}\`.`,
          link: `https://v3-migration.vuejs.org/breaking-changes/v-model.html`
      },
      ["COMPILER_V_BIND_PROP" /* COMPILER_V_BIND_PROP */]: {
          message: `.prop modifier for v-bind has been removed and no longer necessary. ` +
              `Vue 3 will automatically set a binding as DOM property when appropriate.`
      },
      ["COMPILER_V_BIND_OBJECT_ORDER" /* COMPILER_V_BIND_OBJECT_ORDER */]: {
          message: `v-bind="obj" usage is now order sensitive and behaves like JavaScript ` +
              `object spread: it will now overwrite an existing non-mergeable attribute ` +
              `that appears before v-bind in the case of conflict. ` +
              `To retain 2.x behavior, move v-bind to make it the first attribute. ` +
              `You can also suppress this warning if the usage is intended.`,
          link: `https://v3-migration.vuejs.org/breaking-changes/v-bind.html`
      },
      ["COMPILER_V_ON_NATIVE" /* COMPILER_V_ON_NATIVE */]: {
          message: `.native modifier for v-on has been removed as is no longer necessary.`,
          link: `https://v3-migration.vuejs.org/breaking-changes/v-on-native-modifier-removed.html`
      },
      ["COMPILER_V_IF_V_FOR_PRECEDENCE" /* COMPILER_V_IF_V_FOR_PRECEDENCE */]: {
          message: `v-if / v-for precedence when used on the same element has changed ` +
              `in Vue 3: v-if now takes higher precedence and will no longer have ` +
              `access to v-for scope variables. It is best to avoid the ambiguity ` +
              `with <template> tags or use a computed property that filters v-for ` +
              `data source.`,
          link: `https://v3-migration.vuejs.org/breaking-changes/v-if-v-for.html`
      },
      ["COMPILER_NATIVE_TEMPLATE" /* COMPILER_NATIVE_TEMPLATE */]: {
          message: `<template> with no special directives will render as a native template ` +
              `element instead of its inner content in Vue 3.`
      },
      ["COMPILER_INLINE_TEMPLATE" /* COMPILER_INLINE_TEMPLATE */]: {
          message: `"inline-template" has been removed in Vue 3.`,
          link: `https://v3-migration.vuejs.org/breaking-changes/inline-template-attribute.html`
      },
      ["COMPILER_FILTER" /* COMPILER_FILTERS */]: {
          message: `filters have been removed in Vue 3. ` +
              `The "|" symbol will be treated as native JavaScript bitwise OR operator. ` +
              `Use method calls or computed properties instead.`,
          link: `https://v3-migration.vuejs.org/breaking-changes/filters.html`
      }
  };
  function getCompatValue(key, context) {
      const config = context.options
          ? context.options.compatConfig
          : context.compatConfig;
      const value = config && config[key];
      if (key === 'MODE') {
          return value || 3; // compiler defaults to v3 behavior
      }
      else {
          return value;
      }
  }
  function isCompatEnabled$1(key, context) {
      const mode = getCompatValue('MODE', context);
      const value = getCompatValue(key, context);
      // in v3 mode, only enable if explicitly set to true
      // otherwise enable for any non-false value
      return mode === 3 ? value === true : value !== false;
  }
  function checkCompatEnabled$1(key, context, loc, ...args) {
      const enabled = isCompatEnabled$1(key, context);
      if (enabled) {
          warnDeprecation$1(key, context, loc, ...args);
      }
      return enabled;
  }
  function warnDeprecation$1(key, context, loc, ...args) {
      const val = getCompatValue(key, context);
      if (val === 'suppress-warning') {
          return;
      }
      const { message, link } = deprecationData$1[key];
      const msg = `(deprecation ${key}) ${typeof message === 'function' ? message(...args) : message}${link ? `\n  Details: ${link}` : ``}`;
      const err = new SyntaxError(msg);
      err.code = key;
      if (loc)
          err.loc = loc;
      context.onWarn(err);
  }

  // The default decoder only provides escapes for characters reserved as part of
  // the template syntax, and is only used if the custom renderer did not provide
  // a platform-specific decoder.
  const decodeRE = /&(gt|lt|amp|apos|quot);/g;
  const decodeMap = {
      gt: '>',
      lt: '<',
      amp: '&',
      apos: "'",
      quot: '"'
  };
  const defaultParserOptions = {
      delimiters: [`{{`, `}}`],
      getNamespace: () => 0 /* HTML */,
      getTextMode: () => 0 /* DATA */,
      isVoidTag: NO,
      isPreTag: NO,
      isCustomElement: NO,
      decodeEntities: (rawText) => rawText.replace(decodeRE, (_, p1) => decodeMap[p1]),
      onError: defaultOnError,
      onWarn: defaultOnWarn,
      comments: true
  };
  function baseParse(content, options = {}) {
      const context = createParserContext(content, options);
      const start = getCursor(context);
      return createRoot(parseChildren(context, 0 /* DATA */, []), getSelection(context, start));
  }
  function createParserContext(content, rawOptions) {
      const options = extend({}, defaultParserOptions);
      let key;
      for (key in rawOptions) {
          // @ts-ignore
          options[key] =
              rawOptions[key] === undefined
                  ? defaultParserOptions[key]
                  : rawOptions[key];
      }
      return {
          options,
          column: 1,
          line: 1,
          offset: 0,
          originalSource: content,
          source: content,
          inPre: false,
          inVPre: false,
          onWarn: options.onWarn
      };
  }
  function parseChildren(context, mode, ancestors) {
      const parent = last(ancestors);
      const ns = parent ? parent.ns : 0 /* HTML */;
      const nodes = [];
      while (!isEnd(context, mode, ancestors)) {
          const s = context.source;
          let node = undefined;
          if (mode === 0 /* DATA */ || mode === 1 /* RCDATA */) {
              if (!context.inVPre && startsWith(s, context.options.delimiters[0])) {
                  // '{{'
                  node = parseInterpolation(context, mode);
              }
              else if (mode === 0 /* DATA */ && s[0] === '<') {
                  // https://html.spec.whatwg.org/multipage/parsing.html#tag-open-state
                  if (s.length === 1) {
                      emitError(context, 5 /* EOF_BEFORE_TAG_NAME */, 1);
                  }
                  else if (s[1] === '!') {
                      // https://html.spec.whatwg.org/multipage/parsing.html#markup-declaration-open-state
                      if (startsWith(s, '<!--')) {
                          node = parseComment(context);
                      }
                      else if (startsWith(s, '<!DOCTYPE')) {
                          // Ignore DOCTYPE by a limitation.
                          node = parseBogusComment(context);
                      }
                      else if (startsWith(s, '<![CDATA[')) {
                          if (ns !== 0 /* HTML */) {
                              node = parseCDATA(context, ancestors);
                          }
                          else {
                              emitError(context, 1 /* CDATA_IN_HTML_CONTENT */);
                              node = parseBogusComment(context);
                          }
                      }
                      else {
                          emitError(context, 11 /* INCORRECTLY_OPENED_COMMENT */);
                          node = parseBogusComment(context);
                      }
                  }
                  else if (s[1] === '/') {
                      // https://html.spec.whatwg.org/multipage/parsing.html#end-tag-open-state
                      if (s.length === 2) {
                          emitError(context, 5 /* EOF_BEFORE_TAG_NAME */, 2);
                      }
                      else if (s[2] === '>') {
                          emitError(context, 14 /* MISSING_END_TAG_NAME */, 2);
                          advanceBy(context, 3);
                          continue;
                      }
                      else if (/[a-z]/i.test(s[2])) {
                          emitError(context, 23 /* X_INVALID_END_TAG */);
                          parseTag(context, 1 /* End */, parent);
                          continue;
                      }
                      else {
                          emitError(context, 12 /* INVALID_FIRST_CHARACTER_OF_TAG_NAME */, 2);
                          node = parseBogusComment(context);
                      }
                  }
                  else if (/[a-z]/i.test(s[1])) {
                      node = parseElement(context, ancestors);
                      // 2.x <template> with no directive compat
                      if (isCompatEnabled$1("COMPILER_NATIVE_TEMPLATE" /* COMPILER_NATIVE_TEMPLATE */, context) &&
                          node &&
                          node.tag === 'template' &&
                          !node.props.some(p => p.type === 7 /* DIRECTIVE */ &&
                              isSpecialTemplateDirective(p.name))) {
                          warnDeprecation$1("COMPILER_NATIVE_TEMPLATE" /* COMPILER_NATIVE_TEMPLATE */, context, node.loc);
                          node = node.children;
                      }
                  }
                  else if (s[1] === '?') {
                      emitError(context, 21 /* UNEXPECTED_QUESTION_MARK_INSTEAD_OF_TAG_NAME */, 1);
                      node = parseBogusComment(context);
                  }
                  else {
                      emitError(context, 12 /* INVALID_FIRST_CHARACTER_OF_TAG_NAME */, 1);
                  }
              }
          }
          if (!node) {
              node = parseText(context, mode);
          }
          if (isArray(node)) {
              for (let i = 0; i < node.length; i++) {
                  pushNode(nodes, node[i]);
              }
          }
          else {
              pushNode(nodes, node);
          }
      }
      // Whitespace handling strategy like v2
      let removedWhitespace = false;
      if (mode !== 2 /* RAWTEXT */ && mode !== 1 /* RCDATA */) {
          const shouldCondense = context.options.whitespace !== 'preserve';
          for (let i = 0; i < nodes.length; i++) {
              const node = nodes[i];
              if (!context.inPre && node.type === 2 /* TEXT */) {
                  if (!/[^\t\r\n\f ]/.test(node.content)) {
                      const prev = nodes[i - 1];
                      const next = nodes[i + 1];
                      // Remove if:
                      // - the whitespace is the first or last node, or:
                      // - (condense mode) the whitespace is adjacent to a comment, or:
                      // - (condense mode) the whitespace is between two elements AND contains newline
                      if (!prev ||
                          !next ||
                          (shouldCondense &&
                              (prev.type === 3 /* COMMENT */ ||
                                  next.type === 3 /* COMMENT */ ||
                                  (prev.type === 1 /* ELEMENT */ &&
                                      next.type === 1 /* ELEMENT */ &&
                                      /[\r\n]/.test(node.content))))) {
                          removedWhitespace = true;
                          nodes[i] = null;
                      }
                      else {
                          // Otherwise, the whitespace is condensed into a single space
                          node.content = ' ';
                      }
                  }
                  else if (shouldCondense) {
                      // in condense mode, consecutive whitespaces in text are condensed
                      // down to a single space.
                      node.content = node.content.replace(/[\t\r\n\f ]+/g, ' ');
                  }
              }
              // Remove comment nodes if desired by configuration.
              else if (node.type === 3 /* COMMENT */ && !context.options.comments) {
                  removedWhitespace = true;
                  nodes[i] = null;
              }
          }
          if (context.inPre && parent && context.options.isPreTag(parent.tag)) {
              // remove leading newline per html spec
              // https://html.spec.whatwg.org/multipage/grouping-content.html#the-pre-element
              const first = nodes[0];
              if (first && first.type === 2 /* TEXT */) {
                  first.content = first.content.replace(/^\r?\n/, '');
              }
          }
      }
      return removedWhitespace ? nodes.filter(Boolean) : nodes;
  }
  function pushNode(nodes, node) {
      if (node.type === 2 /* TEXT */) {
          const prev = last(nodes);
          // Merge if both this and the previous node are text and those are
          // consecutive. This happens for cases like "a < b".
          if (prev &&
              prev.type === 2 /* TEXT */ &&
              prev.loc.end.offset === node.loc.start.offset) {
              prev.content += node.content;
              prev.loc.end = node.loc.end;
              prev.loc.source += node.loc.source;
              return;
          }
      }
      nodes.push(node);
  }
  function parseCDATA(context, ancestors) {
      advanceBy(context, 9);
      const nodes = parseChildren(context, 3 /* CDATA */, ancestors);
      if (context.source.length === 0) {
          emitError(context, 6 /* EOF_IN_CDATA */);
      }
      else {
          advanceBy(context, 3);
      }
      return nodes;
  }
  function parseComment(context) {
      const start = getCursor(context);
      let content;
      // Regular comment.
      const match = /--(\!)?>/.exec(context.source);
      if (!match) {
          content = context.source.slice(4);
          advanceBy(context, context.source.length);
          emitError(context, 7 /* EOF_IN_COMMENT */);
      }
      else {
          if (match.index <= 3) {
              emitError(context, 0 /* ABRUPT_CLOSING_OF_EMPTY_COMMENT */);
          }
          if (match[1]) {
              emitError(context, 10 /* INCORRECTLY_CLOSED_COMMENT */);
          }
          content = context.source.slice(4, match.index);
          // Advancing with reporting nested comments.
          const s = context.source.slice(0, match.index);
          let prevIndex = 1, nestedIndex = 0;
          while ((nestedIndex = s.indexOf('<!--', prevIndex)) !== -1) {
              advanceBy(context, nestedIndex - prevIndex + 1);
              if (nestedIndex + 4 < s.length) {
                  emitError(context, 16 /* NESTED_COMMENT */);
              }
              prevIndex = nestedIndex + 1;
          }
          advanceBy(context, match.index + match[0].length - prevIndex + 1);
      }
      return {
          type: 3 /* COMMENT */,
          content,
          loc: getSelection(context, start)
      };
  }
  function parseBogusComment(context) {
      const start = getCursor(context);
      const contentStart = context.source[1] === '?' ? 1 : 2;
      let content;
      const closeIndex = context.source.indexOf('>');
      if (closeIndex === -1) {
          content = context.source.slice(contentStart);
          advanceBy(context, context.source.length);
      }
      else {
          content = context.source.slice(contentStart, closeIndex);
          advanceBy(context, closeIndex + 1);
      }
      return {
          type: 3 /* COMMENT */,
          content,
          loc: getSelection(context, start)
      };
  }
  function parseElement(context, ancestors) {
      // Start tag.
      const wasInPre = context.inPre;
      const wasInVPre = context.inVPre;
      const parent = last(ancestors);
      const element = parseTag(context, 0 /* Start */, parent);
      const isPreBoundary = context.inPre && !wasInPre;
      const isVPreBoundary = context.inVPre && !wasInVPre;
      if (element.isSelfClosing || context.options.isVoidTag(element.tag)) {
          // #4030 self-closing <pre> tag
          if (isPreBoundary) {
              context.inPre = false;
          }
          if (isVPreBoundary) {
              context.inVPre = false;
          }
          return element;
      }
      // Children.
      ancestors.push(element);
      const mode = context.options.getTextMode(element, parent);
      const children = parseChildren(context, mode, ancestors);
      ancestors.pop();
      // 2.x inline-template compat
      {
          const inlineTemplateProp = element.props.find(p => p.type === 6 /* ATTRIBUTE */ && p.name === 'inline-template');
          if (inlineTemplateProp &&
              checkCompatEnabled$1("COMPILER_INLINE_TEMPLATE" /* COMPILER_INLINE_TEMPLATE */, context, inlineTemplateProp.loc)) {
              const loc = getSelection(context, element.loc.end);
              inlineTemplateProp.value = {
                  type: 2 /* TEXT */,
                  content: loc.source,
                  loc
              };
          }
      }
      element.children = children;
      // End tag.
      if (startsWithEndTagOpen(context.source, element.tag)) {
          parseTag(context, 1 /* End */, parent);
      }
      else {
          emitError(context, 24 /* X_MISSING_END_TAG */, 0, element.loc.start);
          if (context.source.length === 0 && element.tag.toLowerCase() === 'script') {
              const first = children[0];
              if (first && startsWith(first.loc.source, '<!--')) {
                  emitError(context, 8 /* EOF_IN_SCRIPT_HTML_COMMENT_LIKE_TEXT */);
              }
          }
      }
      element.loc = getSelection(context, element.loc.start);
      if (isPreBoundary) {
          context.inPre = false;
      }
      if (isVPreBoundary) {
          context.inVPre = false;
      }
      return element;
  }
  const isSpecialTemplateDirective = /*#__PURE__*/ makeMap(`if,else,else-if,for,slot`);
  function parseTag(context, type, parent) {
      // Tag open.
      const start = getCursor(context);
      const match = /^<\/?([a-z][^\t\r\n\f />]*)/i.exec(context.source);
      const tag = match[1];
      const ns = context.options.getNamespace(tag, parent);
      advanceBy(context, match[0].length);
      advanceSpaces(context);
      // save current state in case we need to re-parse attributes with v-pre
      const cursor = getCursor(context);
      const currentSource = context.source;
      // check <pre> tag
      if (context.options.isPreTag(tag)) {
          context.inPre = true;
      }
      // Attributes.
      let props = parseAttributes(context, type);
      // check v-pre
      if (type === 0 /* Start */ &&
          !context.inVPre &&
          props.some(p => p.type === 7 /* DIRECTIVE */ && p.name === 'pre')) {
          context.inVPre = true;
          // reset context
          extend(context, cursor);
          context.source = currentSource;
          // re-parse attrs and filter out v-pre itself
          props = parseAttributes(context, type).filter(p => p.name !== 'v-pre');
      }
      // Tag close.
      let isSelfClosing = false;
      if (context.source.length === 0) {
          emitError(context, 9 /* EOF_IN_TAG */);
      }
      else {
          isSelfClosing = startsWith(context.source, '/>');
          if (type === 1 /* End */ && isSelfClosing) {
              emitError(context, 4 /* END_TAG_WITH_TRAILING_SOLIDUS */);
          }
          advanceBy(context, isSelfClosing ? 2 : 1);
      }
      if (type === 1 /* End */) {
          return;
      }
      // 2.x deprecation checks
      if (isCompatEnabled$1("COMPILER_V_IF_V_FOR_PRECEDENCE" /* COMPILER_V_IF_V_FOR_PRECEDENCE */, context)) {
          let hasIf = false;
          let hasFor = false;
          for (let i = 0; i < props.length; i++) {
              const p = props[i];
              if (p.type === 7 /* DIRECTIVE */) {
                  if (p.name === 'if') {
                      hasIf = true;
                  }
                  else if (p.name === 'for') {
                      hasFor = true;
                  }
              }
              if (hasIf && hasFor) {
                  warnDeprecation$1("COMPILER_V_IF_V_FOR_PRECEDENCE" /* COMPILER_V_IF_V_FOR_PRECEDENCE */, context, getSelection(context, start));
                  break;
              }
          }
      }
      let tagType = 0 /* ELEMENT */;
      if (!context.inVPre) {
          if (tag === 'slot') {
              tagType = 2 /* SLOT */;
          }
          else if (tag === 'template') {
              if (props.some(p => p.type === 7 /* DIRECTIVE */ && isSpecialTemplateDirective(p.name))) {
                  tagType = 3 /* TEMPLATE */;
              }
          }
          else if (isComponent(tag, props, context)) {
              tagType = 1 /* COMPONENT */;
          }
      }
      return {
          type: 1 /* ELEMENT */,
          ns,
          tag,
          tagType,
          props,
          isSelfClosing,
          children: [],
          loc: getSelection(context, start),
          codegenNode: undefined // to be created during transform phase
      };
  }
  function isComponent(tag, props, context) {
      const options = context.options;
      if (options.isCustomElement(tag)) {
          return false;
      }
      if (tag === 'component' ||
          /^[A-Z]/.test(tag) ||
          isCoreComponent(tag) ||
          (options.isBuiltInComponent && options.isBuiltInComponent(tag)) ||
          (options.isNativeTag && !options.isNativeTag(tag))) {
          return true;
      }
      // at this point the tag should be a native tag, but check for potential "is"
      // casting
      for (let i = 0; i < props.length; i++) {
          const p = props[i];
          if (p.type === 6 /* ATTRIBUTE */) {
              if (p.name === 'is' && p.value) {
                  if (p.value.content.startsWith('vue:')) {
                      return true;
                  }
                  else if (checkCompatEnabled$1("COMPILER_IS_ON_ELEMENT" /* COMPILER_IS_ON_ELEMENT */, context, p.loc)) {
                      return true;
                  }
              }
          }
          else {
              // directive
              // v-is (TODO Deprecate)
              if (p.name === 'is') {
                  return true;
              }
              else if (
              // :is on plain element - only treat as component in compat mode
              p.name === 'bind' &&
                  isStaticArgOf(p.arg, 'is') &&
                  true &&
                  checkCompatEnabled$1("COMPILER_IS_ON_ELEMENT" /* COMPILER_IS_ON_ELEMENT */, context, p.loc)) {
                  return true;
              }
          }
      }
  }
  function parseAttributes(context, type) {
      const props = [];
      const attributeNames = new Set();
      while (context.source.length > 0 &&
          !startsWith(context.source, '>') &&
          !startsWith(context.source, '/>')) {
          if (startsWith(context.source, '/')) {
              emitError(context, 22 /* UNEXPECTED_SOLIDUS_IN_TAG */);
              advanceBy(context, 1);
              advanceSpaces(context);
              continue;
          }
          if (type === 1 /* End */) {
              emitError(context, 3 /* END_TAG_WITH_ATTRIBUTES */);
          }
          const attr = parseAttribute(context, attributeNames);
          // Trim whitespace between class
          // https://github.com/vuejs/core/issues/4251
          if (attr.type === 6 /* ATTRIBUTE */ &&
              attr.value &&
              attr.name === 'class') {
              attr.value.content = attr.value.content.replace(/\s+/g, ' ').trim();
          }
          if (type === 0 /* Start */) {
              props.push(attr);
          }
          if (/^[^\t\r\n\f />]/.test(context.source)) {
              emitError(context, 15 /* MISSING_WHITESPACE_BETWEEN_ATTRIBUTES */);
          }
          advanceSpaces(context);
      }
      return props;
  }
  function parseAttribute(context, nameSet) {
      // Name.
      const start = getCursor(context);
      const match = /^[^\t\r\n\f />][^\t\r\n\f />=]*/.exec(context.source);
      const name = match[0];
      if (nameSet.has(name)) {
          emitError(context, 2 /* DUPLICATE_ATTRIBUTE */);
      }
      nameSet.add(name);
      if (name[0] === '=') {
          emitError(context, 19 /* UNEXPECTED_EQUALS_SIGN_BEFORE_ATTRIBUTE_NAME */);
      }
      {
          const pattern = /["'<]/g;
          let m;
          while ((m = pattern.exec(name))) {
              emitError(context, 17 /* UNEXPECTED_CHARACTER_IN_ATTRIBUTE_NAME */, m.index);
          }
      }
      advanceBy(context, name.length);
      // Value
      let value = undefined;
      if (/^[\t\r\n\f ]*=/.test(context.source)) {
          advanceSpaces(context);
          advanceBy(context, 1);
          advanceSpaces(context);
          value = parseAttributeValue(context);
          if (!value) {
              emitError(context, 13 /* MISSING_ATTRIBUTE_VALUE */);
          }
      }
      const loc = getSelection(context, start);
      if (!context.inVPre && /^(v-[A-Za-z0-9-]|:|\.|@|#)/.test(name)) {
          const match = /(?:^v-([a-z0-9-]+))?(?:(?::|^\.|^@|^#)(\[[^\]]+\]|[^\.]+))?(.+)?$/i.exec(name);
          let isPropShorthand = startsWith(name, '.');
          let dirName = match[1] ||
              (isPropShorthand || startsWith(name, ':')
                  ? 'bind'
                  : startsWith(name, '@')
                      ? 'on'
                      : 'slot');
          let arg;
          if (match[2]) {
              const isSlot = dirName === 'slot';
              const startOffset = name.lastIndexOf(match[2]);
              const loc = getSelection(context, getNewPosition(context, start, startOffset), getNewPosition(context, start, startOffset + match[2].length + ((isSlot && match[3]) || '').length));
              let content = match[2];
              let isStatic = true;
              if (content.startsWith('[')) {
                  isStatic = false;
                  if (!content.endsWith(']')) {
                      emitError(context, 27 /* X_MISSING_DYNAMIC_DIRECTIVE_ARGUMENT_END */);
                      content = content.slice(1);
                  }
                  else {
                      content = content.slice(1, content.length - 1);
                  }
              }
              else if (isSlot) {
                  // #1241 special case for v-slot: vuetify relies extensively on slot
                  // names containing dots. v-slot doesn't have any modifiers and Vue 2.x
                  // supports such usage so we are keeping it consistent with 2.x.
                  content += match[3] || '';
              }
              arg = {
                  type: 4 /* SIMPLE_EXPRESSION */,
                  content,
                  isStatic,
                  constType: isStatic
                      ? 3 /* CAN_STRINGIFY */
                      : 0 /* NOT_CONSTANT */,
                  loc
              };
          }
          if (value && value.isQuoted) {
              const valueLoc = value.loc;
              valueLoc.start.offset++;
              valueLoc.start.column++;
              valueLoc.end = advancePositionWithClone(valueLoc.start, value.content);
              valueLoc.source = valueLoc.source.slice(1, -1);
          }
          const modifiers = match[3] ? match[3].slice(1).split('.') : [];
          if (isPropShorthand)
              modifiers.push('prop');
          // 2.x compat v-bind:foo.sync -> v-model:foo
          if (dirName === 'bind' && arg) {
              if (modifiers.includes('sync') &&
                  checkCompatEnabled$1("COMPILER_V_BIND_SYNC" /* COMPILER_V_BIND_SYNC */, context, loc, arg.loc.source)) {
                  dirName = 'model';
                  modifiers.splice(modifiers.indexOf('sync'), 1);
              }
              if (modifiers.includes('prop')) {
                  checkCompatEnabled$1("COMPILER_V_BIND_PROP" /* COMPILER_V_BIND_PROP */, context, loc);
              }
          }
          return {
              type: 7 /* DIRECTIVE */,
              name: dirName,
              exp: value && {
                  type: 4 /* SIMPLE_EXPRESSION */,
                  content: value.content,
                  isStatic: false,
                  // Treat as non-constant by default. This can be potentially set to
                  // other values by `transformExpression` to make it eligible for hoisting.
                  constType: 0 /* NOT_CONSTANT */,
                  loc: value.loc
              },
              arg,
              modifiers,
              loc
          };
      }
      // missing directive name or illegal directive name
      if (!context.inVPre && startsWith(name, 'v-')) {
          emitError(context, 26 /* X_MISSING_DIRECTIVE_NAME */);
      }
      return {
          type: 6 /* ATTRIBUTE */,
          name,
          value: value && {
              type: 2 /* TEXT */,
              content: value.content,
              loc: value.loc
          },
          loc
      };
  }
  function parseAttributeValue(context) {
      const start = getCursor(context);
      let content;
      const quote = context.source[0];
      const isQuoted = quote === `"` || quote === `'`;
      if (isQuoted) {
          // Quoted value.
          advanceBy(context, 1);
          const endIndex = context.source.indexOf(quote);
          if (endIndex === -1) {
              content = parseTextData(context, context.source.length, 4 /* ATTRIBUTE_VALUE */);
          }
          else {
              content = parseTextData(context, endIndex, 4 /* ATTRIBUTE_VALUE */);
              advanceBy(context, 1);
          }
      }
      else {
          // Unquoted
          const match = /^[^\t\r\n\f >]+/.exec(context.source);
          if (!match) {
              return undefined;
          }
          const unexpectedChars = /["'<=`]/g;
          let m;
          while ((m = unexpectedChars.exec(match[0]))) {
              emitError(context, 18 /* UNEXPECTED_CHARACTER_IN_UNQUOTED_ATTRIBUTE_VALUE */, m.index);
          }
          content = parseTextData(context, match[0].length, 4 /* ATTRIBUTE_VALUE */);
      }
      return { content, isQuoted, loc: getSelection(context, start) };
  }
  function parseInterpolation(context, mode) {
      const [open, close] = context.options.delimiters;
      const closeIndex = context.source.indexOf(close, open.length);
      if (closeIndex === -1) {
          emitError(context, 25 /* X_MISSING_INTERPOLATION_END */);
          return undefined;
      }
      const start = getCursor(context);
      advanceBy(context, open.length);
      const innerStart = getCursor(context);
      const innerEnd = getCursor(context);
      const rawContentLength = closeIndex - open.length;
      const rawContent = context.source.slice(0, rawContentLength);
      const preTrimContent = parseTextData(context, rawContentLength, mode);
      const content = preTrimContent.trim();
      const startOffset = preTrimContent.indexOf(content);
      if (startOffset > 0) {
          advancePositionWithMutation(innerStart, rawContent, startOffset);
      }
      const endOffset = rawContentLength - (preTrimContent.length - content.length - startOffset);
      advancePositionWithMutation(innerEnd, rawContent, endOffset);
      advanceBy(context, close.length);
      return {
          type: 5 /* INTERPOLATION */,
          content: {
              type: 4 /* SIMPLE_EXPRESSION */,
              isStatic: false,
              // Set `isConstant` to false by default and will decide in transformExpression
              constType: 0 /* NOT_CONSTANT */,
              content,
              loc: getSelection(context, innerStart, innerEnd)
          },
          loc: getSelection(context, start)
      };
  }
  function parseText(context, mode) {
      const endTokens = mode === 3 /* CDATA */ ? [']]>'] : ['<', context.options.delimiters[0]];
      let endIndex = context.source.length;
      for (let i = 0; i < endTokens.length; i++) {
          const index = context.source.indexOf(endTokens[i], 1);
          if (index !== -1 && endIndex > index) {
              endIndex = index;
          }
      }
      const start = getCursor(context);
      const content = parseTextData(context, endIndex, mode);
      return {
          type: 2 /* TEXT */,
          content,
          loc: getSelection(context, start)
      };
  }
  /**
   * Get text data with a given length from the current location.
   * This translates HTML entities in the text data.
   */
  function parseTextData(context, length, mode) {
      const rawText = context.source.slice(0, length);
      advanceBy(context, length);
      if (mode === 2 /* RAWTEXT */ ||
          mode === 3 /* CDATA */ ||
          !rawText.includes('&')) {
          return rawText;
      }
      else {
          // DATA or RCDATA containing "&"". Entity decoding required.
          return context.options.decodeEntities(rawText, mode === 4 /* ATTRIBUTE_VALUE */);
      }
  }
  function getCursor(context) {
      const { column, line, offset } = context;
      return { column, line, offset };
  }
  function getSelection(context, start, end) {
      end = end || getCursor(context);
      return {
          start,
          end,
          source: context.originalSource.slice(start.offset, end.offset)
      };
  }
  function last(xs) {
      return xs[xs.length - 1];
  }
  function startsWith(source, searchString) {
      return source.startsWith(searchString);
  }
  function advanceBy(context, numberOfCharacters) {
      const { source } = context;
      advancePositionWithMutation(context, source, numberOfCharacters);
      context.source = source.slice(numberOfCharacters);
  }
  function advanceSpaces(context) {
      const match = /^[\t\r\n\f ]+/.exec(context.source);
      if (match) {
          advanceBy(context, match[0].length);
      }
  }
  function getNewPosition(context, start, numberOfCharacters) {
      return advancePositionWithClone(start, context.originalSource.slice(start.offset, numberOfCharacters), numberOfCharacters);
  }
  function emitError(context, code, offset, loc = getCursor(context)) {
      if (offset) {
          loc.offset += offset;
          loc.column += offset;
      }
      context.options.onError(createCompilerError(code, {
          start: loc,
          end: loc,
          source: ''
      }));
  }
  function isEnd(context, mode, ancestors) {
      const s = context.source;
      switch (mode) {
          case 0 /* DATA */:
              if (startsWith(s, '</')) {
                  // TODO: probably bad performance
                  for (let i = ancestors.length - 1; i >= 0; --i) {
                      if (startsWithEndTagOpen(s, ancestors[i].tag)) {
                          return true;
                      }
                  }
              }
              break;
          case 1 /* RCDATA */:
          case 2 /* RAWTEXT */: {
              const parent = last(ancestors);
              if (parent && startsWithEndTagOpen(s, parent.tag)) {
                  return true;
              }
              break;
          }
          case 3 /* CDATA */:
              if (startsWith(s, ']]>')) {
                  return true;
              }
              break;
      }
      return !s;
  }
  function startsWithEndTagOpen(source, tag) {
      return (startsWith(source, '</') &&
          source.slice(2, 2 + tag.length).toLowerCase() === tag.toLowerCase() &&
          /[\t\r\n\f />]/.test(source[2 + tag.length] || '>'));
  }

  function hoistStatic(root, context) {
      walk$1(root, context, 
      // Root node is unfortunately non-hoistable due to potential parent
      // fallthrough attributes.
      isSingleElementRoot(root, root.children[0]));
  }
  function isSingleElementRoot(root, child) {
      const { children } = root;
      return (children.length === 1 &&
          child.type === 1 /* ELEMENT */ &&
          !isSlotOutlet(child));
  }
  function walk$1(node, context, doNotHoistNode = false) {
      const { children } = node;
      const originalCount = children.length;
      let hoistedCount = 0;
      for (let i = 0; i < children.length; i++) {
          const child = children[i];
          // only plain elements & text calls are eligible for hoisting.
          if (child.type === 1 /* ELEMENT */ &&
              child.tagType === 0 /* ELEMENT */) {
              const constantType = doNotHoistNode
                  ? 0 /* NOT_CONSTANT */
                  : getConstantType(child, context);
              if (constantType > 0 /* NOT_CONSTANT */) {
                  if (constantType >= 2 /* CAN_HOIST */) {
                      child.codegenNode.patchFlag =
                          -1 /* HOISTED */ + (` /* HOISTED */` );
                      child.codegenNode = context.hoist(child.codegenNode);
                      hoistedCount++;
                      continue;
                  }
              }
              else {
                  // node may contain dynamic children, but its props may be eligible for
                  // hoisting.
                  const codegenNode = child.codegenNode;
                  if (codegenNode.type === 13 /* VNODE_CALL */) {
                      const flag = getPatchFlag(codegenNode);
                      if ((!flag ||
                          flag === 512 /* NEED_PATCH */ ||
                          flag === 1 /* TEXT */) &&
                          getGeneratedPropsConstantType(child, context) >=
                              2 /* CAN_HOIST */) {
                          const props = getNodeProps(child);
                          if (props) {
                              codegenNode.props = context.hoist(props);
                          }
                      }
                      if (codegenNode.dynamicProps) {
                          codegenNode.dynamicProps = context.hoist(codegenNode.dynamicProps);
                      }
                  }
              }
          }
          else if (child.type === 12 /* TEXT_CALL */ &&
              getConstantType(child.content, context) >= 2 /* CAN_HOIST */) {
              child.codegenNode = context.hoist(child.codegenNode);
              hoistedCount++;
          }
          // walk further
          if (child.type === 1 /* ELEMENT */) {
              const isComponent = child.tagType === 1 /* COMPONENT */;
              if (isComponent) {
                  context.scopes.vSlot++;
              }
              walk$1(child, context);
              if (isComponent) {
                  context.scopes.vSlot--;
              }
          }
          else if (child.type === 11 /* FOR */) {
              // Do not hoist v-for single child because it has to be a block
              walk$1(child, context, child.children.length === 1);
          }
          else if (child.type === 9 /* IF */) {
              for (let i = 0; i < child.branches.length; i++) {
                  // Do not hoist v-if single child because it has to be a block
                  walk$1(child.branches[i], context, child.branches[i].children.length === 1);
              }
          }
      }
      if (hoistedCount && context.transformHoist) {
          context.transformHoist(children, context, node);
      }
      // all children were hoisted - the entire children array is hoistable.
      if (hoistedCount &&
          hoistedCount === originalCount &&
          node.type === 1 /* ELEMENT */ &&
          node.tagType === 0 /* ELEMENT */ &&
          node.codegenNode &&
          node.codegenNode.type === 13 /* VNODE_CALL */ &&
          isArray(node.codegenNode.children)) {
          node.codegenNode.children = context.hoist(createArrayExpression(node.codegenNode.children));
      }
  }
  function getConstantType(node, context) {
      const { constantCache } = context;
      switch (node.type) {
          case 1 /* ELEMENT */:
              if (node.tagType !== 0 /* ELEMENT */) {
                  return 0 /* NOT_CONSTANT */;
              }
              const cached = constantCache.get(node);
              if (cached !== undefined) {
                  return cached;
              }
              const codegenNode = node.codegenNode;
              if (codegenNode.type !== 13 /* VNODE_CALL */) {
                  return 0 /* NOT_CONSTANT */;
              }
              if (codegenNode.isBlock &&
                  node.tag !== 'svg' &&
                  node.tag !== 'foreignObject') {
                  return 0 /* NOT_CONSTANT */;
              }
              const flag = getPatchFlag(codegenNode);
              if (!flag) {
                  let returnType = 3 /* CAN_STRINGIFY */;
                  // Element itself has no patch flag. However we still need to check:
                  // 1. Even for a node with no patch flag, it is possible for it to contain
                  // non-hoistable expressions that refers to scope variables, e.g. compiler
                  // injected keys or cached event handlers. Therefore we need to always
                  // check the codegenNode's props to be sure.
                  const generatedPropsType = getGeneratedPropsConstantType(node, context);
                  if (generatedPropsType === 0 /* NOT_CONSTANT */) {
                      constantCache.set(node, 0 /* NOT_CONSTANT */);
                      return 0 /* NOT_CONSTANT */;
                  }
                  if (generatedPropsType < returnType) {
                      returnType = generatedPropsType;
                  }
                  // 2. its children.
                  for (let i = 0; i < node.children.length; i++) {
                      const childType = getConstantType(node.children[i], context);
                      if (childType === 0 /* NOT_CONSTANT */) {
                          constantCache.set(node, 0 /* NOT_CONSTANT */);
                          return 0 /* NOT_CONSTANT */;
                      }
                      if (childType < returnType) {
                          returnType = childType;
                      }
                  }
                  // 3. if the type is not already CAN_SKIP_PATCH which is the lowest non-0
                  // type, check if any of the props can cause the type to be lowered
                  // we can skip can_patch because it's guaranteed by the absence of a
                  // patchFlag.
                  if (returnType > 1 /* CAN_SKIP_PATCH */) {
                      for (let i = 0; i < node.props.length; i++) {
                          const p = node.props[i];
                          if (p.type === 7 /* DIRECTIVE */ && p.name === 'bind' && p.exp) {
                              const expType = getConstantType(p.exp, context);
                              if (expType === 0 /* NOT_CONSTANT */) {
                                  constantCache.set(node, 0 /* NOT_CONSTANT */);
                                  return 0 /* NOT_CONSTANT */;
                              }
                              if (expType < returnType) {
                                  returnType = expType;
                              }
                          }
                      }
                  }
                  // only svg/foreignObject could be block here, however if they are
                  // static then they don't need to be blocks since there will be no
                  // nested updates.
                  if (codegenNode.isBlock) {
                      // except set custom directives.
                      for (let i = 0; i < node.props.length; i++) {
                          const p = node.props[i];
                          if (p.type === 7 /* DIRECTIVE */) {
                              constantCache.set(node, 0 /* NOT_CONSTANT */);
                              return 0 /* NOT_CONSTANT */;
                          }
                      }
                      context.removeHelper(OPEN_BLOCK);
                      context.removeHelper(getVNodeBlockHelper(context.inSSR, codegenNode.isComponent));
                      codegenNode.isBlock = false;
                      context.helper(getVNodeHelper(context.inSSR, codegenNode.isComponent));
                  }
                  constantCache.set(node, returnType);
                  return returnType;
              }
              else {
                  constantCache.set(node, 0 /* NOT_CONSTANT */);
                  return 0 /* NOT_CONSTANT */;
              }
          case 2 /* TEXT */:
          case 3 /* COMMENT */:
              return 3 /* CAN_STRINGIFY */;
          case 9 /* IF */:
          case 11 /* FOR */:
          case 10 /* IF_BRANCH */:
              return 0 /* NOT_CONSTANT */;
          case 5 /* INTERPOLATION */:
          case 12 /* TEXT_CALL */:
              return getConstantType(node.content, context);
          case 4 /* SIMPLE_EXPRESSION */:
              return node.constType;
          case 8 /* COMPOUND_EXPRESSION */:
              let returnType = 3 /* CAN_STRINGIFY */;
              for (let i = 0; i < node.children.length; i++) {
                  const child = node.children[i];
                  if (isString(child) || isSymbol(child)) {
                      continue;
                  }
                  const childType = getConstantType(child, context);
                  if (childType === 0 /* NOT_CONSTANT */) {
                      return 0 /* NOT_CONSTANT */;
                  }
                  else if (childType < returnType) {
                      returnType = childType;
                  }
              }
              return returnType;
          default:
              return 0 /* NOT_CONSTANT */;
      }
  }
  const allowHoistedHelperSet = new Set([
      NORMALIZE_CLASS,
      NORMALIZE_STYLE,
      NORMALIZE_PROPS,
      GUARD_REACTIVE_PROPS
  ]);
  function getConstantTypeOfHelperCall(value, context) {
      if (value.type === 14 /* JS_CALL_EXPRESSION */ &&
          !isString(value.callee) &&
          allowHoistedHelperSet.has(value.callee)) {
          const arg = value.arguments[0];
          if (arg.type === 4 /* SIMPLE_EXPRESSION */) {
              return getConstantType(arg, context);
          }
          else if (arg.type === 14 /* JS_CALL_EXPRESSION */) {
              // in the case of nested helper call, e.g. `normalizeProps(guardReactiveProps(exp))`
              return getConstantTypeOfHelperCall(arg, context);
          }
      }
      return 0 /* NOT_CONSTANT */;
  }
  function getGeneratedPropsConstantType(node, context) {
      let returnType = 3 /* CAN_STRINGIFY */;
      const props = getNodeProps(node);
      if (props && props.type === 15 /* JS_OBJECT_EXPRESSION */) {
          const { properties } = props;
          for (let i = 0; i < properties.length; i++) {
              const { key, value } = properties[i];
              const keyType = getConstantType(key, context);
              if (keyType === 0 /* NOT_CONSTANT */) {
                  return keyType;
              }
              if (keyType < returnType) {
                  returnType = keyType;
              }
              let valueType;
              if (value.type === 4 /* SIMPLE_EXPRESSION */) {
                  valueType = getConstantType(value, context);
              }
              else if (value.type === 14 /* JS_CALL_EXPRESSION */) {
                  // some helper calls can be hoisted,
                  // such as the `normalizeProps` generated by the compiler for pre-normalize class,
                  // in this case we need to respect the ConstantType of the helper's arguments
                  valueType = getConstantTypeOfHelperCall(value, context);
              }
              else {
                  valueType = 0 /* NOT_CONSTANT */;
              }
              if (valueType === 0 /* NOT_CONSTANT */) {
                  return valueType;
              }
              if (valueType < returnType) {
                  returnType = valueType;
              }
          }
      }
      return returnType;
  }
  function getNodeProps(node) {
      const codegenNode = node.codegenNode;
      if (codegenNode.type === 13 /* VNODE_CALL */) {
          return codegenNode.props;
      }
  }
  function getPatchFlag(node) {
      const flag = node.patchFlag;
      return flag ? parseInt(flag, 10) : undefined;
  }

  function createTransformContext(root, { filename = '', prefixIdentifiers = false, hoistStatic = false, cacheHandlers = false, nodeTransforms = [], directiveTransforms = {}, transformHoist = null, isBuiltInComponent = NOOP, isCustomElement = NOOP, expressionPlugins = [], scopeId = null, slotted = true, ssr = false, inSSR = false, ssrCssVars = ``, bindingMetadata = EMPTY_OBJ, inline = false, isTS = false, onError = defaultOnError, onWarn = defaultOnWarn, compatConfig }) {
      const nameMatch = filename.replace(/\?.*$/, '').match(/([^/\\]+)\.\w+$/);
      const context = {
          // options
          selfName: nameMatch && capitalize(camelize(nameMatch[1])),
          prefixIdentifiers,
          hoistStatic,
          cacheHandlers,
          nodeTransforms,
          directiveTransforms,
          transformHoist,
          isBuiltInComponent,
          isCustomElement,
          expressionPlugins,
          scopeId,
          slotted,
          ssr,
          inSSR,
          ssrCssVars,
          bindingMetadata,
          inline,
          isTS,
          onError,
          onWarn,
          compatConfig,
          // state
          root,
          helpers: new Map(),
          components: new Set(),
          directives: new Set(),
          hoists: [],
          imports: [],
          constantCache: new Map(),
          temps: 0,
          cached: 0,
          identifiers: Object.create(null),
          scopes: {
              vFor: 0,
              vSlot: 0,
              vPre: 0,
              vOnce: 0
          },
          parent: null,
          currentNode: root,
          childIndex: 0,
          inVOnce: false,
          // methods
          helper(name) {
              const count = context.helpers.get(name) || 0;
              context.helpers.set(name, count + 1);
              return name;
          },
          removeHelper(name) {
              const count = context.helpers.get(name);
              if (count) {
                  const currentCount = count - 1;
                  if (!currentCount) {
                      context.helpers.delete(name);
                  }
                  else {
                      context.helpers.set(name, currentCount);
                  }
              }
          },
          helperString(name) {
              return `_${helperNameMap[context.helper(name)]}`;
          },
          replaceNode(node) {
              /* istanbul ignore if */
              {
                  if (!context.currentNode) {
                      throw new Error(`Node being replaced is already removed.`);
                  }
                  if (!context.parent) {
                      throw new Error(`Cannot replace root node.`);
                  }
              }
              context.parent.children[context.childIndex] = context.currentNode = node;
          },
          removeNode(node) {
              if (!context.parent) {
                  throw new Error(`Cannot remove root node.`);
              }
              const list = context.parent.children;
              const removalIndex = node
                  ? list.indexOf(node)
                  : context.currentNode
                      ? context.childIndex
                      : -1;
              /* istanbul ignore if */
              if (removalIndex < 0) {
                  throw new Error(`node being removed is not a child of current parent`);
              }
              if (!node || node === context.currentNode) {
                  // current node removed
                  context.currentNode = null;
                  context.onNodeRemoved();
              }
              else {
                  // sibling node removed
                  if (context.childIndex > removalIndex) {
                      context.childIndex--;
                      context.onNodeRemoved();
                  }
              }
              context.parent.children.splice(removalIndex, 1);
          },
          onNodeRemoved: () => { },
          addIdentifiers(exp) {
          },
          removeIdentifiers(exp) {
          },
          hoist(exp) {
              if (isString(exp))
                  exp = createSimpleExpression(exp);
              context.hoists.push(exp);
              const identifier = createSimpleExpression(`_hoisted_${context.hoists.length}`, false, exp.loc, 2 /* CAN_HOIST */);
              identifier.hoisted = exp;
              return identifier;
          },
          cache(exp, isVNode = false) {
              return createCacheExpression(context.cached++, exp, isVNode);
          }
      };
      {
          context.filters = new Set();
      }
      return context;
  }
  function transform(root, options) {
      const context = createTransformContext(root, options);
      traverseNode(root, context);
      if (options.hoistStatic) {
          hoistStatic(root, context);
      }
      if (!options.ssr) {
          createRootCodegen(root, context);
      }
      // finalize meta information
      root.helpers = [...context.helpers.keys()];
      root.components = [...context.components];
      root.directives = [...context.directives];
      root.imports = context.imports;
      root.hoists = context.hoists;
      root.temps = context.temps;
      root.cached = context.cached;
      {
          root.filters = [...context.filters];
      }
  }
  function createRootCodegen(root, context) {
      const { helper } = context;
      const { children } = root;
      if (children.length === 1) {
          const child = children[0];
          // if the single child is an element, turn it into a block.
          if (isSingleElementRoot(root, child) && child.codegenNode) {
              // single element root is never hoisted so codegenNode will never be
              // SimpleExpressionNode
              const codegenNode = child.codegenNode;
              if (codegenNode.type === 13 /* VNODE_CALL */) {
                  makeBlock(codegenNode, context);
              }
              root.codegenNode = codegenNode;
          }
          else {
              // - single <slot/>, IfNode, ForNode: already blocks.
              // - single text node: always patched.
              // root codegen falls through via genNode()
              root.codegenNode = child;
          }
      }
      else if (children.length > 1) {
          // root has multiple nodes - return a fragment block.
          let patchFlag = 64 /* STABLE_FRAGMENT */;
          let patchFlagText = PatchFlagNames[64 /* STABLE_FRAGMENT */];
          // check if the fragment actually contains a single valid child with
          // the rest being comments
          if (children.filter(c => c.type !== 3 /* COMMENT */).length === 1) {
              patchFlag |= 2048 /* DEV_ROOT_FRAGMENT */;
              patchFlagText += `, ${PatchFlagNames[2048 /* DEV_ROOT_FRAGMENT */]}`;
          }
          root.codegenNode = createVNodeCall(context, helper(FRAGMENT), undefined, root.children, patchFlag + (` /* ${patchFlagText} */` ), undefined, undefined, true, undefined, false /* isComponent */);
      }
      else ;
  }
  function traverseChildren(parent, context) {
      let i = 0;
      const nodeRemoved = () => {
          i--;
      };
      for (; i < parent.children.length; i++) {
          const child = parent.children[i];
          if (isString(child))
              continue;
          context.parent = parent;
          context.childIndex = i;
          context.onNodeRemoved = nodeRemoved;
          traverseNode(child, context);
      }
  }
  function traverseNode(node, context) {
      context.currentNode = node;
      // apply transform plugins
      const { nodeTransforms } = context;
      const exitFns = [];
      for (let i = 0; i < nodeTransforms.length; i++) {
          const onExit = nodeTransforms[i](node, context);
          if (onExit) {
              if (isArray(onExit)) {
                  exitFns.push(...onExit);
              }
              else {
                  exitFns.push(onExit);
              }
          }
          if (!context.currentNode) {
              // node was removed
              return;
          }
          else {
              // node may have been replaced
              node = context.currentNode;
          }
      }
      switch (node.type) {
          case 3 /* COMMENT */:
              if (!context.ssr) {
                  // inject import for the Comment symbol, which is needed for creating
                  // comment nodes with `createVNode`
                  context.helper(CREATE_COMMENT);
              }
              break;
          case 5 /* INTERPOLATION */:
              // no need to traverse, but we need to inject toString helper
              if (!context.ssr) {
                  context.helper(TO_DISPLAY_STRING);
              }
              break;
          // for container types, further traverse downwards
          case 9 /* IF */:
              for (let i = 0; i < node.branches.length; i++) {
                  traverseNode(node.branches[i], context);
              }
              break;
          case 10 /* IF_BRANCH */:
          case 11 /* FOR */:
          case 1 /* ELEMENT */:
          case 0 /* ROOT */:
              traverseChildren(node, context);
              break;
      }
      // exit transforms
      context.currentNode = node;
      let i = exitFns.length;
      while (i--) {
          exitFns[i]();
      }
  }
  function createStructuralDirectiveTransform(name, fn) {
      const matches = isString(name)
          ? (n) => n === name
          : (n) => name.test(n);
      return (node, context) => {
          if (node.type === 1 /* ELEMENT */) {
              const { props } = node;
              // structural directive transforms are not concerned with slots
              // as they are handled separately in vSlot.ts
              if (node.tagType === 3 /* TEMPLATE */ && props.some(isVSlot)) {
                  return;
              }
              const exitFns = [];
              for (let i = 0; i < props.length; i++) {
                  const prop = props[i];
                  if (prop.type === 7 /* DIRECTIVE */ && matches(prop.name)) {
                      // structural directives are removed to avoid infinite recursion
                      // also we remove them *before* applying so that it can further
                      // traverse itself in case it moves the node around
                      props.splice(i, 1);
                      i--;
                      const onExit = fn(node, prop, context);
                      if (onExit)
                          exitFns.push(onExit);
                  }
              }
              return exitFns;
          }
      };
  }

  const PURE_ANNOTATION = `/*#__PURE__*/`;
  const aliasHelper = (s) => `${helperNameMap[s]}: _${helperNameMap[s]}`;
  function createCodegenContext(ast, { mode = 'function', prefixIdentifiers = mode === 'module', sourceMap = false, filename = `template.vue.html`, scopeId = null, optimizeImports = false, runtimeGlobalName = `Vue`, runtimeModuleName = `vue`, ssrRuntimeModuleName = 'vue/server-renderer', ssr = false, isTS = false, inSSR = false }) {
      const context = {
          mode,
          prefixIdentifiers,
          sourceMap,
          filename,
          scopeId,
          optimizeImports,
          runtimeGlobalName,
          runtimeModuleName,
          ssrRuntimeModuleName,
          ssr,
          isTS,
          inSSR,
          source: ast.loc.source,
          code: ``,
          column: 1,
          line: 1,
          offset: 0,
          indentLevel: 0,
          pure: false,
          map: undefined,
          helper(key) {
              return `_${helperNameMap[key]}`;
          },
          push(code, node) {
              context.code += code;
          },
          indent() {
              newline(++context.indentLevel);
          },
          deindent(withoutNewLine = false) {
              if (withoutNewLine) {
                  --context.indentLevel;
              }
              else {
                  newline(--context.indentLevel);
              }
          },
          newline() {
              newline(context.indentLevel);
          }
      };
      function newline(n) {
          context.push('\n' + `  `.repeat(n));
      }
      return context;
  }
  function generate(ast, options = {}) {
      const context = createCodegenContext(ast, options);
      if (options.onContextCreated)
          options.onContextCreated(context);
      const { mode, push, prefixIdentifiers, indent, deindent, newline, scopeId, ssr } = context;
      const hasHelpers = ast.helpers.length > 0;
      const useWithBlock = !prefixIdentifiers && mode !== 'module';
      // preambles
      // in setup() inline mode, the preamble is generated in a sub context
      // and returned separately.
      const preambleContext = context;
      {
          genFunctionPreamble(ast, preambleContext);
      }
      // enter render function
      const functionName = ssr ? `ssrRender` : `render`;
      const args = ssr ? ['_ctx', '_push', '_parent', '_attrs'] : ['_ctx', '_cache'];
      const signature = args.join(', ');
      {
          push(`function ${functionName}(${signature}) {`);
      }
      indent();
      if (useWithBlock) {
          push(`with (_ctx) {`);
          indent();
          // function mode const declarations should be inside with block
          // also they should be renamed to avoid collision with user properties
          if (hasHelpers) {
              push(`const { ${ast.helpers.map(aliasHelper).join(', ')} } = _Vue`);
              push(`\n`);
              newline();
          }
      }
      // generate asset resolution statements
      if (ast.components.length) {
          genAssets(ast.components, 'component', context);
          if (ast.directives.length || ast.temps > 0) {
              newline();
          }
      }
      if (ast.directives.length) {
          genAssets(ast.directives, 'directive', context);
          if (ast.temps > 0) {
              newline();
          }
      }
      if (ast.filters && ast.filters.length) {
          newline();
          genAssets(ast.filters, 'filter', context);
          newline();
      }
      if (ast.temps > 0) {
          push(`let `);
          for (let i = 0; i < ast.temps; i++) {
              push(`${i > 0 ? `, ` : ``}_temp${i}`);
          }
      }
      if (ast.components.length || ast.directives.length || ast.temps) {
          push(`\n`);
          newline();
      }
      // generate the VNode tree expression
      if (!ssr) {
          push(`return `);
      }
      if (ast.codegenNode) {
          genNode(ast.codegenNode, context);
      }
      else {
          push(`null`);
      }
      if (useWithBlock) {
          deindent();
          push(`}`);
      }
      deindent();
      push(`}`);
      return {
          ast,
          code: context.code,
          preamble: ``,
          // SourceMapGenerator does have toJSON() method but it's not in the types
          map: context.map ? context.map.toJSON() : undefined
      };
  }
  function genFunctionPreamble(ast, context) {
      const { ssr, prefixIdentifiers, push, newline, runtimeModuleName, runtimeGlobalName, ssrRuntimeModuleName } = context;
      const VueBinding = runtimeGlobalName;
      // Generate const declaration for helpers
      // In prefix mode, we place the const declaration at top so it's done
      // only once; But if we not prefixing, we place the declaration inside the
      // with block so it doesn't incur the `in` check cost for every helper access.
      if (ast.helpers.length > 0) {
          {
              // "with" mode.
              // save Vue in a separate variable to avoid collision
              push(`const _Vue = ${VueBinding}\n`);
              // in "with" mode, helpers are declared inside the with block to avoid
              // has check cost, but hoists are lifted out of the function - we need
              // to provide the helper here.
              if (ast.hoists.length) {
                  const staticHelpers = [
                      CREATE_VNODE,
                      CREATE_ELEMENT_VNODE,
                      CREATE_COMMENT,
                      CREATE_TEXT,
                      CREATE_STATIC
                  ]
                      .filter(helper => ast.helpers.includes(helper))
                      .map(aliasHelper)
                      .join(', ');
                  push(`const { ${staticHelpers} } = _Vue\n`);
              }
          }
      }
      genHoists(ast.hoists, context);
      newline();
      push(`return `);
  }
  function genAssets(assets, type, { helper, push, newline, isTS }) {
      const resolver = helper(type === 'filter'
          ? RESOLVE_FILTER
          : type === 'component'
              ? RESOLVE_COMPONENT
              : RESOLVE_DIRECTIVE);
      for (let i = 0; i < assets.length; i++) {
          let id = assets[i];
          // potential component implicit self-reference inferred from SFC filename
          const maybeSelfReference = id.endsWith('__self');
          if (maybeSelfReference) {
              id = id.slice(0, -6);
          }
          push(`const ${toValidAssetId(id, type)} = ${resolver}(${JSON.stringify(id)}${maybeSelfReference ? `, true` : ``})${isTS ? `!` : ``}`);
          if (i < assets.length - 1) {
              newline();
          }
      }
  }
  function genHoists(hoists, context) {
      if (!hoists.length) {
          return;
      }
      context.pure = true;
      const { push, newline, helper, scopeId, mode } = context;
      newline();
      for (let i = 0; i < hoists.length; i++) {
          const exp = hoists[i];
          if (exp) {
              push(`const _hoisted_${i + 1} = ${``}`);
              genNode(exp, context);
              newline();
          }
      }
      context.pure = false;
  }
  function isText$1(n) {
      return (isString(n) ||
          n.type === 4 /* SIMPLE_EXPRESSION */ ||
          n.type === 2 /* TEXT */ ||
          n.type === 5 /* INTERPOLATION */ ||
          n.type === 8 /* COMPOUND_EXPRESSION */);
  }
  function genNodeListAsArray(nodes, context) {
      const multilines = nodes.length > 3 ||
          (nodes.some(n => isArray(n) || !isText$1(n)));
      context.push(`[`);
      multilines && context.indent();
      genNodeList(nodes, context, multilines);
      multilines && context.deindent();
      context.push(`]`);
  }
  function genNodeList(nodes, context, multilines = false, comma = true) {
      const { push, newline } = context;
      for (let i = 0; i < nodes.length; i++) {
          const node = nodes[i];
          if (isString(node)) {
              push(node);
          }
          else if (isArray(node)) {
              genNodeListAsArray(node, context);
          }
          else {
              genNode(node, context);
          }
          if (i < nodes.length - 1) {
              if (multilines) {
                  comma && push(',');
                  newline();
              }
              else {
                  comma && push(', ');
              }
          }
      }
  }
  function genNode(node, context) {
      if (isString(node)) {
          context.push(node);
          return;
      }
      if (isSymbol(node)) {
          context.push(context.helper(node));
          return;
      }
      switch (node.type) {
          case 1 /* ELEMENT */:
          case 9 /* IF */:
          case 11 /* FOR */:
              assert(node.codegenNode != null, `Codegen node is missing for element/if/for node. ` +
                      `Apply appropriate transforms first.`);
              genNode(node.codegenNode, context);
              break;
          case 2 /* TEXT */:
              genText(node, context);
              break;
          case 4 /* SIMPLE_EXPRESSION */:
              genExpression(node, context);
              break;
          case 5 /* INTERPOLATION */:
              genInterpolation(node, context);
              break;
          case 12 /* TEXT_CALL */:
              genNode(node.codegenNode, context);
              break;
          case 8 /* COMPOUND_EXPRESSION */:
              genCompoundExpression(node, context);
              break;
          case 3 /* COMMENT */:
              genComment(node, context);
              break;
          case 13 /* VNODE_CALL */:
              genVNodeCall(node, context);
              break;
          case 14 /* JS_CALL_EXPRESSION */:
              genCallExpression(node, context);
              break;
          case 15 /* JS_OBJECT_EXPRESSION */:
              genObjectExpression(node, context);
              break;
          case 17 /* JS_ARRAY_EXPRESSION */:
              genArrayExpression(node, context);
              break;
          case 18 /* JS_FUNCTION_EXPRESSION */:
              genFunctionExpression(node, context);
              break;
          case 19 /* JS_CONDITIONAL_EXPRESSION */:
              genConditionalExpression(node, context);
              break;
          case 20 /* JS_CACHE_EXPRESSION */:
              genCacheExpression(node, context);
              break;
          case 21 /* JS_BLOCK_STATEMENT */:
              genNodeList(node.body, context, true, false);
              break;
          // SSR only types
          case 22 /* JS_TEMPLATE_LITERAL */:
              break;
          case 23 /* JS_IF_STATEMENT */:
              break;
          case 24 /* JS_ASSIGNMENT_EXPRESSION */:
              break;
          case 25 /* JS_SEQUENCE_EXPRESSION */:
              break;
          case 26 /* JS_RETURN_STATEMENT */:
              break;
          /* istanbul ignore next */
          case 10 /* IF_BRANCH */:
              // noop
              break;
          default:
              {
                  assert(false, `unhandled codegen node type: ${node.type}`);
                  // make sure we exhaust all possible types
                  const exhaustiveCheck = node;
                  return exhaustiveCheck;
              }
      }
  }
  function genText(node, context) {
      context.push(JSON.stringify(node.content), node);
  }
  function genExpression(node, context) {
      const { content, isStatic } = node;
      context.push(isStatic ? JSON.stringify(content) : content, node);
  }
  function genInterpolation(node, context) {
      const { push, helper, pure } = context;
      if (pure)
          push(PURE_ANNOTATION);
      push(`${helper(TO_DISPLAY_STRING)}(`);
      genNode(node.content, context);
      push(`)`);
  }
  function genCompoundExpression(node, context) {
      for (let i = 0; i < node.children.length; i++) {
          const child = node.children[i];
          if (isString(child)) {
              context.push(child);
          }
          else {
              genNode(child, context);
          }
      }
  }
  function genExpressionAsPropertyKey(node, context) {
      const { push } = context;
      if (node.type === 8 /* COMPOUND_EXPRESSION */) {
          push(`[`);
          genCompoundExpression(node, context);
          push(`]`);
      }
      else if (node.isStatic) {
          // only quote keys if necessary
          const text = isSimpleIdentifier(node.content)
              ? node.content
              : JSON.stringify(node.content);
          push(text, node);
      }
      else {
          push(`[${node.content}]`, node);
      }
  }
  function genComment(node, context) {
      const { push, helper, pure } = context;
      if (pure) {
          push(PURE_ANNOTATION);
      }
      push(`${helper(CREATE_COMMENT)}(${JSON.stringify(node.content)})`, node);
  }
  function genVNodeCall(node, context) {
      const { push, helper, pure } = context;
      const { tag, props, children, patchFlag, dynamicProps, directives, isBlock, disableTracking, isComponent } = node;
      if (directives) {
          push(helper(WITH_DIRECTIVES) + `(`);
      }
      if (isBlock) {
          push(`(${helper(OPEN_BLOCK)}(${disableTracking ? `true` : ``}), `);
      }
      if (pure) {
          push(PURE_ANNOTATION);
      }
      const callHelper = isBlock
          ? getVNodeBlockHelper(context.inSSR, isComponent)
          : getVNodeHelper(context.inSSR, isComponent);
      push(helper(callHelper) + `(`, node);
      genNodeList(genNullableArgs([tag, props, children, patchFlag, dynamicProps]), context);
      push(`)`);
      if (isBlock) {
          push(`)`);
      }
      if (directives) {
          push(`, `);
          genNode(directives, context);
          push(`)`);
      }
  }
  function genNullableArgs(args) {
      let i = args.length;
      while (i--) {
          if (args[i] != null)
              break;
      }
      return args.slice(0, i + 1).map(arg => arg || `null`);
  }
  // JavaScript
  function genCallExpression(node, context) {
      const { push, helper, pure } = context;
      const callee = isString(node.callee) ? node.callee : helper(node.callee);
      if (pure) {
          push(PURE_ANNOTATION);
      }
      push(callee + `(`, node);
      genNodeList(node.arguments, context);
      push(`)`);
  }
  function genObjectExpression(node, context) {
      const { push, indent, deindent, newline } = context;
      const { properties } = node;
      if (!properties.length) {
          push(`{}`, node);
          return;
      }
      const multilines = properties.length > 1 ||
          (properties.some(p => p.value.type !== 4 /* SIMPLE_EXPRESSION */));
      push(multilines ? `{` : `{ `);
      multilines && indent();
      for (let i = 0; i < properties.length; i++) {
          const { key, value } = properties[i];
          // key
          genExpressionAsPropertyKey(key, context);
          push(`: `);
          // value
          genNode(value, context);
          if (i < properties.length - 1) {
              // will only reach this if it's multilines
              push(`,`);
              newline();
          }
      }
      multilines && deindent();
      push(multilines ? `}` : ` }`);
  }
  function genArrayExpression(node, context) {
      genNodeListAsArray(node.elements, context);
  }
  function genFunctionExpression(node, context) {
      const { push, indent, deindent } = context;
      const { params, returns, body, newline, isSlot } = node;
      if (isSlot) {
          // wrap slot functions with owner context
          push(`_${helperNameMap[WITH_CTX]}(`);
      }
      push(`(`, node);
      if (isArray(params)) {
          genNodeList(params, context);
      }
      else if (params) {
          genNode(params, context);
      }
      push(`) => `);
      if (newline || body) {
          push(`{`);
          indent();
      }
      if (returns) {
          if (newline) {
              push(`return `);
          }
          if (isArray(returns)) {
              genNodeListAsArray(returns, context);
          }
          else {
              genNode(returns, context);
          }
      }
      else if (body) {
          genNode(body, context);
      }
      if (newline || body) {
          deindent();
          push(`}`);
      }
      if (isSlot) {
          if (node.isNonScopedSlot) {
              push(`, undefined, true`);
          }
          push(`)`);
      }
  }
  function genConditionalExpression(node, context) {
      const { test, consequent, alternate, newline: needNewline } = node;
      const { push, indent, deindent, newline } = context;
      if (test.type === 4 /* SIMPLE_EXPRESSION */) {
          const needsParens = !isSimpleIdentifier(test.content);
          needsParens && push(`(`);
          genExpression(test, context);
          needsParens && push(`)`);
      }
      else {
          push(`(`);
          genNode(test, context);
          push(`)`);
      }
      needNewline && indent();
      context.indentLevel++;
      needNewline || push(` `);
      push(`? `);
      genNode(consequent, context);
      context.indentLevel--;
      needNewline && newline();
      needNewline || push(` `);
      push(`: `);
      const isNested = alternate.type === 19 /* JS_CONDITIONAL_EXPRESSION */;
      if (!isNested) {
          context.indentLevel++;
      }
      genNode(alternate, context);
      if (!isNested) {
          context.indentLevel--;
      }
      needNewline && deindent(true /* without newline */);
  }
  function genCacheExpression(node, context) {
      const { push, helper, indent, deindent, newline } = context;
      push(`_cache[${node.index}] || (`);
      if (node.isVNode) {
          indent();
          push(`${helper(SET_BLOCK_TRACKING)}(-1),`);
          newline();
      }
      push(`_cache[${node.index}] = `);
      genNode(node.value, context);
      if (node.isVNode) {
          push(`,`);
          newline();
          push(`${helper(SET_BLOCK_TRACKING)}(1),`);
          newline();
          push(`_cache[${node.index}]`);
          deindent();
      }
      push(`)`);
  }

  // these keywords should not appear inside expressions, but operators like
  // typeof, instanceof and in are allowed
  const prohibitedKeywordRE = new RegExp('\\b' +
      ('do,if,for,let,new,try,var,case,else,with,await,break,catch,class,const,' +
          'super,throw,while,yield,delete,export,import,return,switch,default,' +
          'extends,finally,continue,debugger,function,arguments,typeof,void')
          .split(',')
          .join('\\b|\\b') +
      '\\b');
  // strip strings in expressions
  const stripStringRE = /'(?:[^'\\]|\\.)*'|"(?:[^"\\]|\\.)*"|`(?:[^`\\]|\\.)*\$\{|\}(?:[^`\\]|\\.)*`|`(?:[^`\\]|\\.)*`/g;
  /**
   * Validate a non-prefixed expression.
   * This is only called when using the in-browser runtime compiler since it
   * doesn't prefix expressions.
   */
  function validateBrowserExpression(node, context, asParams = false, asRawStatements = false) {
      const exp = node.content;
      // empty expressions are validated per-directive since some directives
      // do allow empty expressions.
      if (!exp.trim()) {
          return;
      }
      try {
          new Function(asRawStatements
              ? ` ${exp} `
              : `return ${asParams ? `(${exp}) => {}` : `(${exp})`}`);
      }
      catch (e) {
          let message = e.message;
          const keywordMatch = exp
              .replace(stripStringRE, '')
              .match(prohibitedKeywordRE);
          if (keywordMatch) {
              message = `avoid using JavaScript keyword as property name: "${keywordMatch[0]}"`;
          }
          context.onError(createCompilerError(44 /* X_INVALID_EXPRESSION */, node.loc, undefined, message));
      }
  }

  const transformExpression = (node, context) => {
      if (node.type === 5 /* INTERPOLATION */) {
          node.content = processExpression(node.content, context);
      }
      else if (node.type === 1 /* ELEMENT */) {
          // handle directives on element
          for (let i = 0; i < node.props.length; i++) {
              const dir = node.props[i];
              // do not process for v-on & v-for since they are special handled
              if (dir.type === 7 /* DIRECTIVE */ && dir.name !== 'for') {
                  const exp = dir.exp;
                  const arg = dir.arg;
                  // do not process exp if this is v-on:arg - we need special handling
                  // for wrapping inline statements.
                  if (exp &&
                      exp.type === 4 /* SIMPLE_EXPRESSION */ &&
                      !(dir.name === 'on' && arg)) {
                      dir.exp = processExpression(exp, context, 
                      // slot args must be processed as function params
                      dir.name === 'slot');
                  }
                  if (arg && arg.type === 4 /* SIMPLE_EXPRESSION */ && !arg.isStatic) {
                      dir.arg = processExpression(arg, context);
                  }
              }
          }
      }
  };
  // Important: since this function uses Node.js only dependencies, it should
  // always be used with a leading !true check so that it can be
  // tree-shaken from the browser build.
  function processExpression(node, context, 
  // some expressions like v-slot props & v-for aliases should be parsed as
  // function params
  asParams = false, 
  // v-on handler values may contain multiple statements
  asRawStatements = false, localVars = Object.create(context.identifiers)) {
      {
          {
              // simple in-browser validation (same logic in 2.x)
              validateBrowserExpression(node, context, asParams, asRawStatements);
          }
          return node;
      }
  }

  const transformIf = createStructuralDirectiveTransform(/^(if|else|else-if)$/, (node, dir, context) => {
      return processIf(node, dir, context, (ifNode, branch, isRoot) => {
          // #1587: We need to dynamically increment the key based on the current
          // node's sibling nodes, since chained v-if/else branches are
          // rendered at the same depth
          const siblings = context.parent.children;
          let i = siblings.indexOf(ifNode);
          let key = 0;
          while (i-- >= 0) {
              const sibling = siblings[i];
              if (sibling && sibling.type === 9 /* IF */) {
                  key += sibling.branches.length;
              }
          }
          // Exit callback. Complete the codegenNode when all children have been
          // transformed.
          return () => {
              if (isRoot) {
                  ifNode.codegenNode = createCodegenNodeForBranch(branch, key, context);
              }
              else {
                  // attach this branch's codegen node to the v-if root.
                  const parentCondition = getParentCondition(ifNode.codegenNode);
                  parentCondition.alternate = createCodegenNodeForBranch(branch, key + ifNode.branches.length - 1, context);
              }
          };
      });
  });
  // target-agnostic transform used for both Client and SSR
  function processIf(node, dir, context, processCodegen) {
      if (dir.name !== 'else' &&
          (!dir.exp || !dir.exp.content.trim())) {
          const loc = dir.exp ? dir.exp.loc : node.loc;
          context.onError(createCompilerError(28 /* X_V_IF_NO_EXPRESSION */, dir.loc));
          dir.exp = createSimpleExpression(`true`, false, loc);
      }
      if (dir.exp) {
          validateBrowserExpression(dir.exp, context);
      }
      if (dir.name === 'if') {
          const branch = createIfBranch(node, dir);
          const ifNode = {
              type: 9 /* IF */,
              loc: node.loc,
              branches: [branch]
          };
          context.replaceNode(ifNode);
          if (processCodegen) {
              return processCodegen(ifNode, branch, true);
          }
      }
      else {
          // locate the adjacent v-if
          const siblings = context.parent.children;
          const comments = [];
          let i = siblings.indexOf(node);
          while (i-- >= -1) {
              const sibling = siblings[i];
              if (sibling && sibling.type === 3 /* COMMENT */) {
                  context.removeNode(sibling);
                  comments.unshift(sibling);
                  continue;
              }
              if (sibling &&
                  sibling.type === 2 /* TEXT */ &&
                  !sibling.content.trim().length) {
                  context.removeNode(sibling);
                  continue;
              }
              if (sibling && sibling.type === 9 /* IF */) {
                  // Check if v-else was followed by v-else-if
                  if (dir.name === 'else-if' &&
                      sibling.branches[sibling.branches.length - 1].condition === undefined) {
                      context.onError(createCompilerError(30 /* X_V_ELSE_NO_ADJACENT_IF */, node.loc));
                  }
                  // move the node to the if node's branches
                  context.removeNode();
                  const branch = createIfBranch(node, dir);
                  if (comments.length &&
                      // #3619 ignore comments if the v-if is direct child of <transition>
                      !(context.parent &&
                          context.parent.type === 1 /* ELEMENT */ &&
                          isBuiltInType(context.parent.tag, 'transition'))) {
                      branch.children = [...comments, ...branch.children];
                  }
                  // check if user is forcing same key on different branches
                  {
                      const key = branch.userKey;
                      if (key) {
                          sibling.branches.forEach(({ userKey }) => {
                              if (isSameKey(userKey, key)) {
                                  context.onError(createCompilerError(29 /* X_V_IF_SAME_KEY */, branch.userKey.loc));
                              }
                          });
                      }
                  }
                  sibling.branches.push(branch);
                  const onExit = processCodegen && processCodegen(sibling, branch, false);
                  // since the branch was removed, it will not be traversed.
                  // make sure to traverse here.
                  traverseNode(branch, context);
                  // call on exit
                  if (onExit)
                      onExit();
                  // make sure to reset currentNode after traversal to indicate this
                  // node has been removed.
                  context.currentNode = null;
              }
              else {
                  context.onError(createCompilerError(30 /* X_V_ELSE_NO_ADJACENT_IF */, node.loc));
              }
              break;
          }
      }
  }
  function createIfBranch(node, dir) {
      const isTemplateIf = node.tagType === 3 /* TEMPLATE */;
      return {
          type: 10 /* IF_BRANCH */,
          loc: node.loc,
          condition: dir.name === 'else' ? undefined : dir.exp,
          children: isTemplateIf && !findDir(node, 'for') ? node.children : [node],
          userKey: findProp(node, `key`),
          isTemplateIf
      };
  }
  function createCodegenNodeForBranch(branch, keyIndex, context) {
      if (branch.condition) {
          return createConditionalExpression(branch.condition, createChildrenCodegenNode(branch, keyIndex, context), 
          // make sure to pass in asBlock: true so that the comment node call
          // closes the current block.
          createCallExpression(context.helper(CREATE_COMMENT), [
              '"v-if"' ,
              'true'
          ]));
      }
      else {
          return createChildrenCodegenNode(branch, keyIndex, context);
      }
  }
  function createChildrenCodegenNode(branch, keyIndex, context) {
      const { helper } = context;
      const keyProperty = createObjectProperty(`key`, createSimpleExpression(`${keyIndex}`, false, locStub, 2 /* CAN_HOIST */));
      const { children } = branch;
      const firstChild = children[0];
      const needFragmentWrapper = children.length !== 1 || firstChild.type !== 1 /* ELEMENT */;
      if (needFragmentWrapper) {
          if (children.length === 1 && firstChild.type === 11 /* FOR */) {
              // optimize away nested fragments when child is a ForNode
              const vnodeCall = firstChild.codegenNode;
              injectProp(vnodeCall, keyProperty, context);
              return vnodeCall;
          }
          else {
              let patchFlag = 64 /* STABLE_FRAGMENT */;
              let patchFlagText = PatchFlagNames[64 /* STABLE_FRAGMENT */];
              // check if the fragment actually contains a single valid child with
              // the rest being comments
              if (!branch.isTemplateIf &&
                  children.filter(c => c.type !== 3 /* COMMENT */).length === 1) {
                  patchFlag |= 2048 /* DEV_ROOT_FRAGMENT */;
                  patchFlagText += `, ${PatchFlagNames[2048 /* DEV_ROOT_FRAGMENT */]}`;
              }
              return createVNodeCall(context, helper(FRAGMENT), createObjectExpression([keyProperty]), children, patchFlag + (` /* ${patchFlagText} */` ), undefined, undefined, true, false, false /* isComponent */, branch.loc);
          }
      }
      else {
          const ret = firstChild.codegenNode;
          const vnodeCall = getMemoedVNodeCall(ret);
          // Change createVNode to createBlock.
          if (vnodeCall.type === 13 /* VNODE_CALL */) {
              makeBlock(vnodeCall, context);
          }
          // inject branch key
          injectProp(vnodeCall, keyProperty, context);
          return ret;
      }
  }
  function isSameKey(a, b) {
      if (!a || a.type !== b.type) {
          return false;
      }
      if (a.type === 6 /* ATTRIBUTE */) {
          if (a.value.content !== b.value.content) {
              return false;
          }
      }
      else {
          // directive
          const exp = a.exp;
          const branchExp = b.exp;
          if (exp.type !== branchExp.type) {
              return false;
          }
          if (exp.type !== 4 /* SIMPLE_EXPRESSION */ ||
              exp.isStatic !== branchExp.isStatic ||
              exp.content !== branchExp.content) {
              return false;
          }
      }
      return true;
  }
  function getParentCondition(node) {
      while (true) {
          if (node.type === 19 /* JS_CONDITIONAL_EXPRESSION */) {
              if (node.alternate.type === 19 /* JS_CONDITIONAL_EXPRESSION */) {
                  node = node.alternate;
              }
              else {
                  return node;
              }
          }
          else if (node.type === 20 /* JS_CACHE_EXPRESSION */) {
              node = node.value;
          }
      }
  }

  const transformFor = createStructuralDirectiveTransform('for', (node, dir, context) => {
      const { helper, removeHelper } = context;
      return processFor(node, dir, context, forNode => {
          // create the loop render function expression now, and add the
          // iterator on exit after all children have been traversed
          const renderExp = createCallExpression(helper(RENDER_LIST), [
              forNode.source
          ]);
          const isTemplate = isTemplateNode(node);
          const memo = findDir(node, 'memo');
          const keyProp = findProp(node, `key`);
          const keyExp = keyProp &&
              (keyProp.type === 6 /* ATTRIBUTE */
                  ? createSimpleExpression(keyProp.value.content, true)
                  : keyProp.exp);
          const keyProperty = keyProp ? createObjectProperty(`key`, keyExp) : null;
          const isStableFragment = forNode.source.type === 4 /* SIMPLE_EXPRESSION */ &&
              forNode.source.constType > 0 /* NOT_CONSTANT */;
          const fragmentFlag = isStableFragment
              ? 64 /* STABLE_FRAGMENT */
              : keyProp
                  ? 128 /* KEYED_FRAGMENT */
                  : 256 /* UNKEYED_FRAGMENT */;
          forNode.codegenNode = createVNodeCall(context, helper(FRAGMENT), undefined, renderExp, fragmentFlag +
              (` /* ${PatchFlagNames[fragmentFlag]} */` ), undefined, undefined, true /* isBlock */, !isStableFragment /* disableTracking */, false /* isComponent */, node.loc);
          return () => {
              // finish the codegen now that all children have been traversed
              let childBlock;
              const { children } = forNode;
              // check <template v-for> key placement
              if (isTemplate) {
                  node.children.some(c => {
                      if (c.type === 1 /* ELEMENT */) {
                          const key = findProp(c, 'key');
                          if (key) {
                              context.onError(createCompilerError(33 /* X_V_FOR_TEMPLATE_KEY_PLACEMENT */, key.loc));
                              return true;
                          }
                      }
                  });
              }
              const needFragmentWrapper = children.length !== 1 || children[0].type !== 1 /* ELEMENT */;
              const slotOutlet = isSlotOutlet(node)
                  ? node
                  : isTemplate &&
                      node.children.length === 1 &&
                      isSlotOutlet(node.children[0])
                      ? node.children[0] // api-extractor somehow fails to infer this
                      : null;
              if (slotOutlet) {
                  // <slot v-for="..."> or <template v-for="..."><slot/></template>
                  childBlock = slotOutlet.codegenNode;
                  if (isTemplate && keyProperty) {
                      // <template v-for="..." :key="..."><slot/></template>
                      // we need to inject the key to the renderSlot() call.
                      // the props for renderSlot is passed as the 3rd argument.
                      injectProp(childBlock, keyProperty, context);
                  }
              }
              else if (needFragmentWrapper) {
                  // <template v-for="..."> with text or multi-elements
                  // should generate a fragment block for each loop
                  childBlock = createVNodeCall(context, helper(FRAGMENT), keyProperty ? createObjectExpression([keyProperty]) : undefined, node.children, 64 /* STABLE_FRAGMENT */ +
                      (` /* ${PatchFlagNames[64 /* STABLE_FRAGMENT */]} */`
                          ), undefined, undefined, true, undefined, false /* isComponent */);
              }
              else {
                  // Normal element v-for. Directly use the child's codegenNode
                  // but mark it as a block.
                  childBlock = children[0]
                      .codegenNode;
                  if (isTemplate && keyProperty) {
                      injectProp(childBlock, keyProperty, context);
                  }
                  if (childBlock.isBlock !== !isStableFragment) {
                      if (childBlock.isBlock) {
                          // switch from block to vnode
                          removeHelper(OPEN_BLOCK);
                          removeHelper(getVNodeBlockHelper(context.inSSR, childBlock.isComponent));
                      }
                      else {
                          // switch from vnode to block
                          removeHelper(getVNodeHelper(context.inSSR, childBlock.isComponent));
                      }
                  }
                  childBlock.isBlock = !isStableFragment;
                  if (childBlock.isBlock) {
                      helper(OPEN_BLOCK);
                      helper(getVNodeBlockHelper(context.inSSR, childBlock.isComponent));
                  }
                  else {
                      helper(getVNodeHelper(context.inSSR, childBlock.isComponent));
                  }
              }
              if (memo) {
                  const loop = createFunctionExpression(createForLoopParams(forNode.parseResult, [
                      createSimpleExpression(`_cached`)
                  ]));
                  loop.body = createBlockStatement([
                      createCompoundExpression([`const _memo = (`, memo.exp, `)`]),
                      createCompoundExpression([
                          `if (_cached`,
                          ...(keyExp ? [` && _cached.key === `, keyExp] : []),
                          ` && ${context.helperString(IS_MEMO_SAME)}(_cached, _memo)) return _cached`
                      ]),
                      createCompoundExpression([`const _item = `, childBlock]),
                      createSimpleExpression(`_item.memo = _memo`),
                      createSimpleExpression(`return _item`)
                  ]);
                  renderExp.arguments.push(loop, createSimpleExpression(`_cache`), createSimpleExpression(String(context.cached++)));
              }
              else {
                  renderExp.arguments.push(createFunctionExpression(createForLoopParams(forNode.parseResult), childBlock, true /* force newline */));
              }
          };
      });
  });
  // target-agnostic transform used for both Client and SSR
  function processFor(node, dir, context, processCodegen) {
      if (!dir.exp) {
          context.onError(createCompilerError(31 /* X_V_FOR_NO_EXPRESSION */, dir.loc));
          return;
      }
      const parseResult = parseForExpression(
      // can only be simple expression because vFor transform is applied
      // before expression transform.
      dir.exp, context);
      if (!parseResult) {
          context.onError(createCompilerError(32 /* X_V_FOR_MALFORMED_EXPRESSION */, dir.loc));
          return;
      }
      const { addIdentifiers, removeIdentifiers, scopes } = context;
      const { source, value, key, index } = parseResult;
      const forNode = {
          type: 11 /* FOR */,
          loc: dir.loc,
          source,
          valueAlias: value,
          keyAlias: key,
          objectIndexAlias: index,
          parseResult,
          children: isTemplateNode(node) ? node.children : [node]
      };
      context.replaceNode(forNode);
      // bookkeeping
      scopes.vFor++;
      const onExit = processCodegen && processCodegen(forNode);
      return () => {
          scopes.vFor--;
          if (onExit)
              onExit();
      };
  }
  const forAliasRE = /([\s\S]*?)\s+(?:in|of)\s+([\s\S]*)/;
  // This regex doesn't cover the case if key or index aliases have destructuring,
  // but those do not make sense in the first place, so this works in practice.
  const forIteratorRE = /,([^,\}\]]*)(?:,([^,\}\]]*))?$/;
  const stripParensRE = /^\(|\)$/g;
  function parseForExpression(input, context) {
      const loc = input.loc;
      const exp = input.content;
      const inMatch = exp.match(forAliasRE);
      if (!inMatch)
          return;
      const [, LHS, RHS] = inMatch;
      const result = {
          source: createAliasExpression(loc, RHS.trim(), exp.indexOf(RHS, LHS.length)),
          value: undefined,
          key: undefined,
          index: undefined
      };
      {
          validateBrowserExpression(result.source, context);
      }
      let valueContent = LHS.trim().replace(stripParensRE, '').trim();
      const trimmedOffset = LHS.indexOf(valueContent);
      const iteratorMatch = valueContent.match(forIteratorRE);
      if (iteratorMatch) {
          valueContent = valueContent.replace(forIteratorRE, '').trim();
          const keyContent = iteratorMatch[1].trim();
          let keyOffset;
          if (keyContent) {
              keyOffset = exp.indexOf(keyContent, trimmedOffset + valueContent.length);
              result.key = createAliasExpression(loc, keyContent, keyOffset);
              {
                  validateBrowserExpression(result.key, context, true);
              }
          }
          if (iteratorMatch[2]) {
              const indexContent = iteratorMatch[2].trim();
              if (indexContent) {
                  result.index = createAliasExpression(loc, indexContent, exp.indexOf(indexContent, result.key
                      ? keyOffset + keyContent.length
                      : trimmedOffset + valueContent.length));
                  {
                      validateBrowserExpression(result.index, context, true);
                  }
              }
          }
      }
      if (valueContent) {
          result.value = createAliasExpression(loc, valueContent, trimmedOffset);
          {
              validateBrowserExpression(result.value, context, true);
          }
      }
      return result;
  }
  function createAliasExpression(range, content, offset) {
      return createSimpleExpression(content, false, getInnerRange(range, offset, content.length));
  }
  function createForLoopParams({ value, key, index }, memoArgs = []) {
      return createParamsList([value, key, index, ...memoArgs]);
  }
  function createParamsList(args) {
      let i = args.length;
      while (i--) {
          if (args[i])
              break;
      }
      return args
          .slice(0, i + 1)
          .map((arg, i) => arg || createSimpleExpression(`_`.repeat(i + 1), false));
  }

  const defaultFallback = createSimpleExpression(`undefined`, false);
  // A NodeTransform that:
  // 1. Tracks scope identifiers for scoped slots so that they don't get prefixed
  //    by transformExpression. This is only applied in non-browser builds with
  //    { prefixIdentifiers: true }.
  // 2. Track v-slot depths so that we know a slot is inside another slot.
  //    Note the exit callback is executed before buildSlots() on the same node,
  //    so only nested slots see positive numbers.
  const trackSlotScopes = (node, context) => {
      if (node.type === 1 /* ELEMENT */ &&
          (node.tagType === 1 /* COMPONENT */ ||
              node.tagType === 3 /* TEMPLATE */)) {
          // We are only checking non-empty v-slot here
          // since we only care about slots that introduce scope variables.
          const vSlot = findDir(node, 'slot');
          if (vSlot) {
              vSlot.exp;
              context.scopes.vSlot++;
              return () => {
                  context.scopes.vSlot--;
              };
          }
      }
  };
  const buildClientSlotFn = (props, children, loc) => createFunctionExpression(props, children, false /* newline */, true /* isSlot */, children.length ? children[0].loc : loc);
  // Instead of being a DirectiveTransform, v-slot processing is called during
  // transformElement to build the slots object for a component.
  function buildSlots(node, context, buildSlotFn = buildClientSlotFn) {
      context.helper(WITH_CTX);
      const { children, loc } = node;
      const slotsProperties = [];
      const dynamicSlots = [];
      // If the slot is inside a v-for or another v-slot, force it to be dynamic
      // since it likely uses a scope variable.
      let hasDynamicSlots = context.scopes.vSlot > 0 || context.scopes.vFor > 0;
      // 1. Check for slot with slotProps on component itself.
      //    <Comp v-slot="{ prop }"/>
      const onComponentSlot = findDir(node, 'slot', true);
      if (onComponentSlot) {
          const { arg, exp } = onComponentSlot;
          if (arg && !isStaticExp(arg)) {
              hasDynamicSlots = true;
          }
          slotsProperties.push(createObjectProperty(arg || createSimpleExpression('default', true), buildSlotFn(exp, children, loc)));
      }
      // 2. Iterate through children and check for template slots
      //    <template v-slot:foo="{ prop }">
      let hasTemplateSlots = false;
      let hasNamedDefaultSlot = false;
      const implicitDefaultChildren = [];
      const seenSlotNames = new Set();
      for (let i = 0; i < children.length; i++) {
          const slotElement = children[i];
          let slotDir;
          if (!isTemplateNode(slotElement) ||
              !(slotDir = findDir(slotElement, 'slot', true))) {
              // not a <template v-slot>, skip.
              if (slotElement.type !== 3 /* COMMENT */) {
                  implicitDefaultChildren.push(slotElement);
              }
              continue;
          }
          if (onComponentSlot) {
              // already has on-component slot - this is incorrect usage.
              context.onError(createCompilerError(37 /* X_V_SLOT_MIXED_SLOT_USAGE */, slotDir.loc));
              break;
          }
          hasTemplateSlots = true;
          const { children: slotChildren, loc: slotLoc } = slotElement;
          const { arg: slotName = createSimpleExpression(`default`, true), exp: slotProps, loc: dirLoc } = slotDir;
          // check if name is dynamic.
          let staticSlotName;
          if (isStaticExp(slotName)) {
              staticSlotName = slotName ? slotName.content : `default`;
          }
          else {
              hasDynamicSlots = true;
          }
          const slotFunction = buildSlotFn(slotProps, slotChildren, slotLoc);
          // check if this slot is conditional (v-if/v-for)
          let vIf;
          let vElse;
          let vFor;
          if ((vIf = findDir(slotElement, 'if'))) {
              hasDynamicSlots = true;
              dynamicSlots.push(createConditionalExpression(vIf.exp, buildDynamicSlot(slotName, slotFunction), defaultFallback));
          }
          else if ((vElse = findDir(slotElement, /^else(-if)?$/, true /* allowEmpty */))) {
              // find adjacent v-if
              let j = i;
              let prev;
              while (j--) {
                  prev = children[j];
                  if (prev.type !== 3 /* COMMENT */) {
                      break;
                  }
              }
              if (prev && isTemplateNode(prev) && findDir(prev, 'if')) {
                  // remove node
                  children.splice(i, 1);
                  i--;
                  // attach this slot to previous conditional
                  let conditional = dynamicSlots[dynamicSlots.length - 1];
                  while (conditional.alternate.type === 19 /* JS_CONDITIONAL_EXPRESSION */) {
                      conditional = conditional.alternate;
                  }
                  conditional.alternate = vElse.exp
                      ? createConditionalExpression(vElse.exp, buildDynamicSlot(slotName, slotFunction), defaultFallback)
                      : buildDynamicSlot(slotName, slotFunction);
              }
              else {
                  context.onError(createCompilerError(30 /* X_V_ELSE_NO_ADJACENT_IF */, vElse.loc));
              }
          }
          else if ((vFor = findDir(slotElement, 'for'))) {
              hasDynamicSlots = true;
              const parseResult = vFor.parseResult ||
                  parseForExpression(vFor.exp, context);
              if (parseResult) {
                  // Render the dynamic slots as an array and add it to the createSlot()
                  // args. The runtime knows how to handle it appropriately.
                  dynamicSlots.push(createCallExpression(context.helper(RENDER_LIST), [
                      parseResult.source,
                      createFunctionExpression(createForLoopParams(parseResult), buildDynamicSlot(slotName, slotFunction), true /* force newline */)
                  ]));
              }
              else {
                  context.onError(createCompilerError(32 /* X_V_FOR_MALFORMED_EXPRESSION */, vFor.loc));
              }
          }
          else {
              // check duplicate static names
              if (staticSlotName) {
                  if (seenSlotNames.has(staticSlotName)) {
                      context.onError(createCompilerError(38 /* X_V_SLOT_DUPLICATE_SLOT_NAMES */, dirLoc));
                      continue;
                  }
                  seenSlotNames.add(staticSlotName);
                  if (staticSlotName === 'default') {
                      hasNamedDefaultSlot = true;
                  }
              }
              slotsProperties.push(createObjectProperty(slotName, slotFunction));
          }
      }
      if (!onComponentSlot) {
          const buildDefaultSlotProperty = (props, children) => {
              const fn = buildSlotFn(props, children, loc);
              if (context.compatConfig) {
                  fn.isNonScopedSlot = true;
              }
              return createObjectProperty(`default`, fn);
          };
          if (!hasTemplateSlots) {
              // implicit default slot (on component)
              slotsProperties.push(buildDefaultSlotProperty(undefined, children));
          }
          else if (implicitDefaultChildren.length &&
              // #3766
              // with whitespace: 'preserve', whitespaces between slots will end up in
              // implicitDefaultChildren. Ignore if all implicit children are whitespaces.
              implicitDefaultChildren.some(node => isNonWhitespaceContent(node))) {
              // implicit default slot (mixed with named slots)
              if (hasNamedDefaultSlot) {
                  context.onError(createCompilerError(39 /* X_V_SLOT_EXTRANEOUS_DEFAULT_SLOT_CHILDREN */, implicitDefaultChildren[0].loc));
              }
              else {
                  slotsProperties.push(buildDefaultSlotProperty(undefined, implicitDefaultChildren));
              }
          }
      }
      const slotFlag = hasDynamicSlots
          ? 2 /* DYNAMIC */
          : hasForwardedSlots(node.children)
              ? 3 /* FORWARDED */
              : 1 /* STABLE */;
      let slots = createObjectExpression(slotsProperties.concat(createObjectProperty(`_`, 
      // 2 = compiled but dynamic = can skip normalization, but must run diff
      // 1 = compiled and static = can skip normalization AND diff as optimized
      createSimpleExpression(slotFlag + (` /* ${slotFlagsText[slotFlag]} */` ), false))), loc);
      if (dynamicSlots.length) {
          slots = createCallExpression(context.helper(CREATE_SLOTS), [
              slots,
              createArrayExpression(dynamicSlots)
          ]);
      }
      return {
          slots,
          hasDynamicSlots
      };
  }
  function buildDynamicSlot(name, fn) {
      return createObjectExpression([
          createObjectProperty(`name`, name),
          createObjectProperty(`fn`, fn)
      ]);
  }
  function hasForwardedSlots(children) {
      for (let i = 0; i < children.length; i++) {
          const child = children[i];
          switch (child.type) {
              case 1 /* ELEMENT */:
                  if (child.tagType === 2 /* SLOT */ ||
                      hasForwardedSlots(child.children)) {
                      return true;
                  }
                  break;
              case 9 /* IF */:
                  if (hasForwardedSlots(child.branches))
                      return true;
                  break;
              case 10 /* IF_BRANCH */:
              case 11 /* FOR */:
                  if (hasForwardedSlots(child.children))
                      return true;
                  break;
          }
      }
      return false;
  }
  function isNonWhitespaceContent(node) {
      if (node.type !== 2 /* TEXT */ && node.type !== 12 /* TEXT_CALL */)
          return true;
      return node.type === 2 /* TEXT */
          ? !!node.content.trim()
          : isNonWhitespaceContent(node.content);
  }

  // some directive transforms (e.g. v-model) may return a symbol for runtime
  // import, which should be used instead of a resolveDirective call.
  const directiveImportMap = new WeakMap();
  // generate a JavaScript AST for this element's codegen
  const transformElement = (node, context) => {
      // perform the work on exit, after all child expressions have been
      // processed and merged.
      return function postTransformElement() {
          node = context.currentNode;
          if (!(node.type === 1 /* ELEMENT */ &&
              (node.tagType === 0 /* ELEMENT */ ||
                  node.tagType === 1 /* COMPONENT */))) {
              return;
          }
          const { tag, props } = node;
          const isComponent = node.tagType === 1 /* COMPONENT */;
          // The goal of the transform is to create a codegenNode implementing the
          // VNodeCall interface.
          let vnodeTag = isComponent
              ? resolveComponentType(node, context)
              : `"${tag}"`;
          const isDynamicComponent = isObject(vnodeTag) && vnodeTag.callee === RESOLVE_DYNAMIC_COMPONENT;
          let vnodeProps;
          let vnodeChildren;
          let vnodePatchFlag;
          let patchFlag = 0;
          let vnodeDynamicProps;
          let dynamicPropNames;
          let vnodeDirectives;
          let shouldUseBlock = 
          // dynamic component may resolve to plain elements
          isDynamicComponent ||
              vnodeTag === TELEPORT ||
              vnodeTag === SUSPENSE ||
              (!isComponent &&
                  // <svg> and <foreignObject> must be forced into blocks so that block
                  // updates inside get proper isSVG flag at runtime. (#639, #643)
                  // This is technically web-specific, but splitting the logic out of core
                  // leads to too much unnecessary complexity.
                  (tag === 'svg' || tag === 'foreignObject'));
          // props
          if (props.length > 0) {
              const propsBuildResult = buildProps(node, context, undefined, isComponent, isDynamicComponent);
              vnodeProps = propsBuildResult.props;
              patchFlag = propsBuildResult.patchFlag;
              dynamicPropNames = propsBuildResult.dynamicPropNames;
              const directives = propsBuildResult.directives;
              vnodeDirectives =
                  directives && directives.length
                      ? createArrayExpression(directives.map(dir => buildDirectiveArgs(dir, context)))
                      : undefined;
              if (propsBuildResult.shouldUseBlock) {
                  shouldUseBlock = true;
              }
          }
          // children
          if (node.children.length > 0) {
              if (vnodeTag === KEEP_ALIVE) {
                  // Although a built-in component, we compile KeepAlive with raw children
                  // instead of slot functions so that it can be used inside Transition
                  // or other Transition-wrapping HOCs.
                  // To ensure correct updates with block optimizations, we need to:
                  // 1. Force keep-alive into a block. This avoids its children being
                  //    collected by a parent block.
                  shouldUseBlock = true;
                  // 2. Force keep-alive to always be updated, since it uses raw children.
                  patchFlag |= 1024 /* DYNAMIC_SLOTS */;
                  if (node.children.length > 1) {
                      context.onError(createCompilerError(45 /* X_KEEP_ALIVE_INVALID_CHILDREN */, {
                          start: node.children[0].loc.start,
                          end: node.children[node.children.length - 1].loc.end,
                          source: ''
                      }));
                  }
              }
              const shouldBuildAsSlots = isComponent &&
                  // Teleport is not a real component and has dedicated runtime handling
                  vnodeTag !== TELEPORT &&
                  // explained above.
                  vnodeTag !== KEEP_ALIVE;
              if (shouldBuildAsSlots) {
                  const { slots, hasDynamicSlots } = buildSlots(node, context);
                  vnodeChildren = slots;
                  if (hasDynamicSlots) {
                      patchFlag |= 1024 /* DYNAMIC_SLOTS */;
                  }
              }
              else if (node.children.length === 1 && vnodeTag !== TELEPORT) {
                  const child = node.children[0];
                  const type = child.type;
                  // check for dynamic text children
                  const hasDynamicTextChild = type === 5 /* INTERPOLATION */ ||
                      type === 8 /* COMPOUND_EXPRESSION */;
                  if (hasDynamicTextChild &&
                      getConstantType(child, context) === 0 /* NOT_CONSTANT */) {
                      patchFlag |= 1 /* TEXT */;
                  }
                  // pass directly if the only child is a text node
                  // (plain / interpolation / expression)
                  if (hasDynamicTextChild || type === 2 /* TEXT */) {
                      vnodeChildren = child;
                  }
                  else {
                      vnodeChildren = node.children;
                  }
              }
              else {
                  vnodeChildren = node.children;
              }
          }
          // patchFlag & dynamicPropNames
          if (patchFlag !== 0) {
              {
                  if (patchFlag < 0) {
                      // special flags (negative and mutually exclusive)
                      vnodePatchFlag = patchFlag + ` /* ${PatchFlagNames[patchFlag]} */`;
                  }
                  else {
                      // bitwise flags
                      const flagNames = Object.keys(PatchFlagNames)
                          .map(Number)
                          .filter(n => n > 0 && patchFlag & n)
                          .map(n => PatchFlagNames[n])
                          .join(`, `);
                      vnodePatchFlag = patchFlag + ` /* ${flagNames} */`;
                  }
              }
              if (dynamicPropNames && dynamicPropNames.length) {
                  vnodeDynamicProps = stringifyDynamicPropNames(dynamicPropNames);
              }
          }
          node.codegenNode = createVNodeCall(context, vnodeTag, vnodeProps, vnodeChildren, vnodePatchFlag, vnodeDynamicProps, vnodeDirectives, !!shouldUseBlock, false /* disableTracking */, isComponent, node.loc);
      };
  };
  function resolveComponentType(node, context, ssr = false) {
      let { tag } = node;
      // 1. dynamic component
      const isExplicitDynamic = isComponentTag(tag);
      const isProp = findProp(node, 'is');
      if (isProp) {
          if (isExplicitDynamic ||
              (isCompatEnabled$1("COMPILER_IS_ON_ELEMENT" /* COMPILER_IS_ON_ELEMENT */, context))) {
              const exp = isProp.type === 6 /* ATTRIBUTE */
                  ? isProp.value && createSimpleExpression(isProp.value.content, true)
                  : isProp.exp;
              if (exp) {
                  return createCallExpression(context.helper(RESOLVE_DYNAMIC_COMPONENT), [
                      exp
                  ]);
              }
          }
          else if (isProp.type === 6 /* ATTRIBUTE */ &&
              isProp.value.content.startsWith('vue:')) {
              // <button is="vue:xxx">
              // if not <component>, only is value that starts with "vue:" will be
              // treated as component by the parse phase and reach here, unless it's
              // compat mode where all is values are considered components
              tag = isProp.value.content.slice(4);
          }
      }
      // 1.5 v-is (TODO: Deprecate)
      const isDir = !isExplicitDynamic && findDir(node, 'is');
      if (isDir && isDir.exp) {
          return createCallExpression(context.helper(RESOLVE_DYNAMIC_COMPONENT), [
              isDir.exp
          ]);
      }
      // 2. built-in components (Teleport, Transition, KeepAlive, Suspense...)
      const builtIn = isCoreComponent(tag) || context.isBuiltInComponent(tag);
      if (builtIn) {
          // built-ins are simply fallthroughs / have special handling during ssr
          // so we don't need to import their runtime equivalents
          if (!ssr)
              context.helper(builtIn);
          return builtIn;
      }
      // 5. user component (resolve)
      context.helper(RESOLVE_COMPONENT);
      context.components.add(tag);
      return toValidAssetId(tag, `component`);
  }
  function buildProps(node, context, props = node.props, isComponent, isDynamicComponent, ssr = false) {
      const { tag, loc: elementLoc, children } = node;
      let properties = [];
      const mergeArgs = [];
      const runtimeDirectives = [];
      const hasChildren = children.length > 0;
      let shouldUseBlock = false;
      // patchFlag analysis
      let patchFlag = 0;
      let hasRef = false;
      let hasClassBinding = false;
      let hasStyleBinding = false;
      let hasHydrationEventBinding = false;
      let hasDynamicKeys = false;
      let hasVnodeHook = false;
      const dynamicPropNames = [];
      const analyzePatchFlag = ({ key, value }) => {
          if (isStaticExp(key)) {
              const name = key.content;
              const isEventHandler = isOn(name);
              if (isEventHandler &&
                  (!isComponent || isDynamicComponent) &&
                  // omit the flag for click handlers because hydration gives click
                  // dedicated fast path.
                  name.toLowerCase() !== 'onclick' &&
                  // omit v-model handlers
                  name !== 'onUpdate:modelValue' &&
                  // omit onVnodeXXX hooks
                  !isReservedProp(name)) {
                  hasHydrationEventBinding = true;
              }
              if (isEventHandler && isReservedProp(name)) {
                  hasVnodeHook = true;
              }
              if (value.type === 20 /* JS_CACHE_EXPRESSION */ ||
                  ((value.type === 4 /* SIMPLE_EXPRESSION */ ||
                      value.type === 8 /* COMPOUND_EXPRESSION */) &&
                      getConstantType(value, context) > 0)) {
                  // skip if the prop is a cached handler or has constant value
                  return;
              }
              if (name === 'ref') {
                  hasRef = true;
              }
              else if (name === 'class') {
                  hasClassBinding = true;
              }
              else if (name === 'style') {
                  hasStyleBinding = true;
              }
              else if (name !== 'key' && !dynamicPropNames.includes(name)) {
                  dynamicPropNames.push(name);
              }
              // treat the dynamic class and style binding of the component as dynamic props
              if (isComponent &&
                  (name === 'class' || name === 'style') &&
                  !dynamicPropNames.includes(name)) {
                  dynamicPropNames.push(name);
              }
          }
          else {
              hasDynamicKeys = true;
          }
      };
      for (let i = 0; i < props.length; i++) {
          // static attribute
          const prop = props[i];
          if (prop.type === 6 /* ATTRIBUTE */) {
              const { loc, name, value } = prop;
              let isStatic = true;
              if (name === 'ref') {
                  hasRef = true;
                  if (context.scopes.vFor > 0) {
                      properties.push(createObjectProperty(createSimpleExpression('ref_for', true), createSimpleExpression('true')));
                  }
              }
              // skip is on <component>, or is="vue:xxx"
              if (name === 'is' &&
                  (isComponentTag(tag) ||
                      (value && value.content.startsWith('vue:')) ||
                      (isCompatEnabled$1("COMPILER_IS_ON_ELEMENT" /* COMPILER_IS_ON_ELEMENT */, context)))) {
                  continue;
              }
              properties.push(createObjectProperty(createSimpleExpression(name, true, getInnerRange(loc, 0, name.length)), createSimpleExpression(value ? value.content : '', isStatic, value ? value.loc : loc)));
          }
          else {
              // directives
              const { name, arg, exp, loc } = prop;
              const isVBind = name === 'bind';
              const isVOn = name === 'on';
              // skip v-slot - it is handled by its dedicated transform.
              if (name === 'slot') {
                  if (!isComponent) {
                      context.onError(createCompilerError(40 /* X_V_SLOT_MISPLACED */, loc));
                  }
                  continue;
              }
              // skip v-once/v-memo - they are handled by dedicated transforms.
              if (name === 'once' || name === 'memo') {
                  continue;
              }
              // skip v-is and :is on <component>
              if (name === 'is' ||
                  (isVBind &&
                      isStaticArgOf(arg, 'is') &&
                      (isComponentTag(tag) ||
                          (isCompatEnabled$1("COMPILER_IS_ON_ELEMENT" /* COMPILER_IS_ON_ELEMENT */, context))))) {
                  continue;
              }
              // skip v-on in SSR compilation
              if (isVOn && ssr) {
                  continue;
              }
              if (
              // #938: elements with dynamic keys should be forced into blocks
              (isVBind && isStaticArgOf(arg, 'key')) ||
                  // inline before-update hooks need to force block so that it is invoked
                  // before children
                  (isVOn && hasChildren && isStaticArgOf(arg, 'vue:before-update'))) {
                  shouldUseBlock = true;
              }
              if (isVBind && isStaticArgOf(arg, 'ref') && context.scopes.vFor > 0) {
                  properties.push(createObjectProperty(createSimpleExpression('ref_for', true), createSimpleExpression('true')));
              }
              // special case for v-bind and v-on with no argument
              if (!arg && (isVBind || isVOn)) {
                  hasDynamicKeys = true;
                  if (exp) {
                      if (properties.length) {
                          mergeArgs.push(createObjectExpression(dedupeProperties(properties), elementLoc));
                          properties = [];
                      }
                      if (isVBind) {
                          {
                              // 2.x v-bind object order compat
                              {
                                  const hasOverridableKeys = mergeArgs.some(arg => {
                                      if (arg.type === 15 /* JS_OBJECT_EXPRESSION */) {
                                          return arg.properties.some(({ key }) => {
                                              if (key.type !== 4 /* SIMPLE_EXPRESSION */ ||
                                                  !key.isStatic) {
                                                  return true;
                                              }
                                              return (key.content !== 'class' &&
                                                  key.content !== 'style' &&
                                                  !isOn(key.content));
                                          });
                                      }
                                      else {
                                          // dynamic expression
                                          return true;
                                      }
                                  });
                                  if (hasOverridableKeys) {
                                      checkCompatEnabled$1("COMPILER_V_BIND_OBJECT_ORDER" /* COMPILER_V_BIND_OBJECT_ORDER */, context, loc);
                                  }
                              }
                              if (isCompatEnabled$1("COMPILER_V_BIND_OBJECT_ORDER" /* COMPILER_V_BIND_OBJECT_ORDER */, context)) {
                                  mergeArgs.unshift(exp);
                                  continue;
                              }
                          }
                          mergeArgs.push(exp);
                      }
                      else {
                          // v-on="obj" -> toHandlers(obj)
                          mergeArgs.push({
                              type: 14 /* JS_CALL_EXPRESSION */,
                              loc,
                              callee: context.helper(TO_HANDLERS),
                              arguments: [exp]
                          });
                      }
                  }
                  else {
                      context.onError(createCompilerError(isVBind
                          ? 34 /* X_V_BIND_NO_EXPRESSION */
                          : 35 /* X_V_ON_NO_EXPRESSION */, loc));
                  }
                  continue;
              }
              const directiveTransform = context.directiveTransforms[name];
              if (directiveTransform) {
                  // has built-in directive transform.
                  const { props, needRuntime } = directiveTransform(prop, node, context);
                  !ssr && props.forEach(analyzePatchFlag);
                  properties.push(...props);
                  if (needRuntime) {
                      runtimeDirectives.push(prop);
                      if (isSymbol(needRuntime)) {
                          directiveImportMap.set(prop, needRuntime);
                      }
                  }
              }
              else if (!isBuiltInDirective(name)) {
                  // no built-in transform, this is a user custom directive.
                  runtimeDirectives.push(prop);
                  // custom dirs may use beforeUpdate so they need to force blocks
                  // to ensure before-update gets called before children update
                  if (hasChildren) {
                      shouldUseBlock = true;
                  }
              }
          }
      }
      let propsExpression = undefined;
      // has v-bind="object" or v-on="object", wrap with mergeProps
      if (mergeArgs.length) {
          if (properties.length) {
              mergeArgs.push(createObjectExpression(dedupeProperties(properties), elementLoc));
          }
          if (mergeArgs.length > 1) {
              propsExpression = createCallExpression(context.helper(MERGE_PROPS), mergeArgs, elementLoc);
          }
          else {
              // single v-bind with nothing else - no need for a mergeProps call
              propsExpression = mergeArgs[0];
          }
      }
      else if (properties.length) {
          propsExpression = createObjectExpression(dedupeProperties(properties), elementLoc);
      }
      // patchFlag analysis
      if (hasDynamicKeys) {
          patchFlag |= 16 /* FULL_PROPS */;
      }
      else {
          if (hasClassBinding && !isComponent) {
              patchFlag |= 2 /* CLASS */;
          }
          if (hasStyleBinding && !isComponent) {
              patchFlag |= 4 /* STYLE */;
          }
          if (dynamicPropNames.length) {
              patchFlag |= 8 /* PROPS */;
          }
          if (hasHydrationEventBinding) {
              patchFlag |= 32 /* HYDRATE_EVENTS */;
          }
      }
      if (!shouldUseBlock &&
          (patchFlag === 0 || patchFlag === 32 /* HYDRATE_EVENTS */) &&
          (hasRef || hasVnodeHook || runtimeDirectives.length > 0)) {
          patchFlag |= 512 /* NEED_PATCH */;
      }
      // pre-normalize props, SSR is skipped for now
      if (!context.inSSR && propsExpression) {
          switch (propsExpression.type) {
              case 15 /* JS_OBJECT_EXPRESSION */:
                  // means that there is no v-bind,
                  // but still need to deal with dynamic key binding
                  let classKeyIndex = -1;
                  let styleKeyIndex = -1;
                  let hasDynamicKey = false;
                  for (let i = 0; i < propsExpression.properties.length; i++) {
                      const key = propsExpression.properties[i].key;
                      if (isStaticExp(key)) {
                          if (key.content === 'class') {
                              classKeyIndex = i;
                          }
                          else if (key.content === 'style') {
                              styleKeyIndex = i;
                          }
                      }
                      else if (!key.isHandlerKey) {
                          hasDynamicKey = true;
                      }
                  }
                  const classProp = propsExpression.properties[classKeyIndex];
                  const styleProp = propsExpression.properties[styleKeyIndex];
                  // no dynamic key
                  if (!hasDynamicKey) {
                      if (classProp && !isStaticExp(classProp.value)) {
                          classProp.value = createCallExpression(context.helper(NORMALIZE_CLASS), [classProp.value]);
                      }
                      if (styleProp &&
                          // the static style is compiled into an object,
                          // so use `hasStyleBinding` to ensure that it is a dynamic style binding
                          (hasStyleBinding ||
                              (styleProp.value.type === 4 /* SIMPLE_EXPRESSION */ &&
                                  styleProp.value.content.trim()[0] === `[`) ||
                              // v-bind:style and style both exist,
                              // v-bind:style with static literal object
                              styleProp.value.type === 17 /* JS_ARRAY_EXPRESSION */)) {
                          styleProp.value = createCallExpression(context.helper(NORMALIZE_STYLE), [styleProp.value]);
                      }
                  }
                  else {
                      // dynamic key binding, wrap with `normalizeProps`
                      propsExpression = createCallExpression(context.helper(NORMALIZE_PROPS), [propsExpression]);
                  }
                  break;
              case 14 /* JS_CALL_EXPRESSION */:
                  // mergeProps call, do nothing
                  break;
              default:
                  // single v-bind
                  propsExpression = createCallExpression(context.helper(NORMALIZE_PROPS), [
                      createCallExpression(context.helper(GUARD_REACTIVE_PROPS), [
                          propsExpression
                      ])
                  ]);
                  break;
          }
      }
      return {
          props: propsExpression,
          directives: runtimeDirectives,
          patchFlag,
          dynamicPropNames,
          shouldUseBlock
      };
  }
  // Dedupe props in an object literal.
  // Literal duplicated attributes would have been warned during the parse phase,
  // however, it's possible to encounter duplicated `onXXX` handlers with different
  // modifiers. We also need to merge static and dynamic class / style attributes.
  // - onXXX handlers / style: merge into array
  // - class: merge into single expression with concatenation
  function dedupeProperties(properties) {
      const knownProps = new Map();
      const deduped = [];
      for (let i = 0; i < properties.length; i++) {
          const prop = properties[i];
          // dynamic keys are always allowed
          if (prop.key.type === 8 /* COMPOUND_EXPRESSION */ || !prop.key.isStatic) {
              deduped.push(prop);
              continue;
          }
          const name = prop.key.content;
          const existing = knownProps.get(name);
          if (existing) {
              if (name === 'style' || name === 'class' || isOn(name)) {
                  mergeAsArray$1(existing, prop);
              }
              // unexpected duplicate, should have emitted error during parse
          }
          else {
              knownProps.set(name, prop);
              deduped.push(prop);
          }
      }
      return deduped;
  }
  function mergeAsArray$1(existing, incoming) {
      if (existing.value.type === 17 /* JS_ARRAY_EXPRESSION */) {
          existing.value.elements.push(incoming.value);
      }
      else {
          existing.value = createArrayExpression([existing.value, incoming.value], existing.loc);
      }
  }
  function buildDirectiveArgs(dir, context) {
      const dirArgs = [];
      const runtime = directiveImportMap.get(dir);
      if (runtime) {
          // built-in directive with runtime
          dirArgs.push(context.helperString(runtime));
      }
      else {
          {
              // inject statement for resolving directive
              context.helper(RESOLVE_DIRECTIVE);
              context.directives.add(dir.name);
              dirArgs.push(toValidAssetId(dir.name, `directive`));
          }
      }
      const { loc } = dir;
      if (dir.exp)
          dirArgs.push(dir.exp);
      if (dir.arg) {
          if (!dir.exp) {
              dirArgs.push(`void 0`);
          }
          dirArgs.push(dir.arg);
      }
      if (Object.keys(dir.modifiers).length) {
          if (!dir.arg) {
              if (!dir.exp) {
                  dirArgs.push(`void 0`);
              }
              dirArgs.push(`void 0`);
          }
          const trueExpression = createSimpleExpression(`true`, false, loc);
          dirArgs.push(createObjectExpression(dir.modifiers.map(modifier => createObjectProperty(modifier, trueExpression)), loc));
      }
      return createArrayExpression(dirArgs, dir.loc);
  }
  function stringifyDynamicPropNames(props) {
      let propsNamesString = `[`;
      for (let i = 0, l = props.length; i < l; i++) {
          propsNamesString += JSON.stringify(props[i]);
          if (i < l - 1)
              propsNamesString += ', ';
      }
      return propsNamesString + `]`;
  }
  function isComponentTag(tag) {
      return tag === 'component' || tag === 'Component';
  }

  const transformSlotOutlet = (node, context) => {
      if (isSlotOutlet(node)) {
          const { children, loc } = node;
          const { slotName, slotProps } = processSlotOutlet(node, context);
          const slotArgs = [
              context.prefixIdentifiers ? `_ctx.$slots` : `$slots`,
              slotName,
              '{}',
              'undefined',
              'true'
          ];
          let expectedLen = 2;
          if (slotProps) {
              slotArgs[2] = slotProps;
              expectedLen = 3;
          }
          if (children.length) {
              slotArgs[3] = createFunctionExpression([], children, false, false, loc);
              expectedLen = 4;
          }
          if (context.scopeId && !context.slotted) {
              expectedLen = 5;
          }
          slotArgs.splice(expectedLen); // remove unused arguments
          node.codegenNode = createCallExpression(context.helper(RENDER_SLOT), slotArgs, loc);
      }
  };
  function processSlotOutlet(node, context) {
      let slotName = `"default"`;
      let slotProps = undefined;
      const nonNameProps = [];
      for (let i = 0; i < node.props.length; i++) {
          const p = node.props[i];
          if (p.type === 6 /* ATTRIBUTE */) {
              if (p.value) {
                  if (p.name === 'name') {
                      slotName = JSON.stringify(p.value.content);
                  }
                  else {
                      p.name = camelize(p.name);
                      nonNameProps.push(p);
                  }
              }
          }
          else {
              if (p.name === 'bind' && isStaticArgOf(p.arg, 'name')) {
                  if (p.exp)
                      slotName = p.exp;
              }
              else {
                  if (p.name === 'bind' && p.arg && isStaticExp(p.arg)) {
                      p.arg.content = camelize(p.arg.content);
                  }
                  nonNameProps.push(p);
              }
          }
      }
      if (nonNameProps.length > 0) {
          const { props, directives } = buildProps(node, context, nonNameProps, false, false);
          slotProps = props;
          if (directives.length) {
              context.onError(createCompilerError(36 /* X_V_SLOT_UNEXPECTED_DIRECTIVE_ON_SLOT_OUTLET */, directives[0].loc));
          }
      }
      return {
          slotName,
          slotProps
      };
  }

  const fnExpRE = /^\s*([\w$_]+|(async\s*)?\([^)]*?\))\s*=>|^\s*(async\s+)?function(?:\s+[\w$]+)?\s*\(/;
  const transformOn = (dir, node, context, augmentor) => {
      const { loc, modifiers, arg } = dir;
      if (!dir.exp && !modifiers.length) {
          context.onError(createCompilerError(35 /* X_V_ON_NO_EXPRESSION */, loc));
      }
      let eventName;
      if (arg.type === 4 /* SIMPLE_EXPRESSION */) {
          if (arg.isStatic) {
              let rawName = arg.content;
              // TODO deprecate @vnodeXXX usage
              if (rawName.startsWith('vue:')) {
                  rawName = `vnode-${rawName.slice(4)}`;
              }
              // for all event listeners, auto convert it to camelCase. See issue #2249
              eventName = createSimpleExpression(toHandlerKey(camelize(rawName)), true, arg.loc);
          }
          else {
              // #2388
              eventName = createCompoundExpression([
                  `${context.helperString(TO_HANDLER_KEY)}(`,
                  arg,
                  `)`
              ]);
          }
      }
      else {
          // already a compound expression.
          eventName = arg;
          eventName.children.unshift(`${context.helperString(TO_HANDLER_KEY)}(`);
          eventName.children.push(`)`);
      }
      // handler processing
      let exp = dir.exp;
      if (exp && !exp.content.trim()) {
          exp = undefined;
      }
      let shouldCache = context.cacheHandlers && !exp && !context.inVOnce;
      if (exp) {
          const isMemberExp = isMemberExpression(exp.content);
          const isInlineStatement = !(isMemberExp || fnExpRE.test(exp.content));
          const hasMultipleStatements = exp.content.includes(`;`);
          {
              validateBrowserExpression(exp, context, false, hasMultipleStatements);
          }
          if (isInlineStatement || (shouldCache && isMemberExp)) {
              // wrap inline statement in a function expression
              exp = createCompoundExpression([
                  `${isInlineStatement
                    ? `$event`
                    : `${``}(...args)`} => ${hasMultipleStatements ? `{` : `(`}`,
                  exp,
                  hasMultipleStatements ? `}` : `)`
              ]);
          }
      }
      let ret = {
          props: [
              createObjectProperty(eventName, exp || createSimpleExpression(`() => {}`, false, loc))
          ]
      };
      // apply extended compiler augmentor
      if (augmentor) {
          ret = augmentor(ret);
      }
      if (shouldCache) {
          // cache handlers so that it's always the same handler being passed down.
          // this avoids unnecessary re-renders when users use inline handlers on
          // components.
          ret.props[0].value = context.cache(ret.props[0].value);
      }
      // mark the key as handler for props normalization check
      ret.props.forEach(p => (p.key.isHandlerKey = true));
      return ret;
  };

  // v-bind without arg is handled directly in ./transformElements.ts due to it affecting
  // codegen for the entire props object. This transform here is only for v-bind
  // *with* args.
  const transformBind = (dir, _node, context) => {
      const { exp, modifiers, loc } = dir;
      const arg = dir.arg;
      if (arg.type !== 4 /* SIMPLE_EXPRESSION */) {
          arg.children.unshift(`(`);
          arg.children.push(`) || ""`);
      }
      else if (!arg.isStatic) {
          arg.content = `${arg.content} || ""`;
      }
      // .sync is replaced by v-model:arg
      if (modifiers.includes('camel')) {
          if (arg.type === 4 /* SIMPLE_EXPRESSION */) {
              if (arg.isStatic) {
                  arg.content = camelize(arg.content);
              }
              else {
                  arg.content = `${context.helperString(CAMELIZE)}(${arg.content})`;
              }
          }
          else {
              arg.children.unshift(`${context.helperString(CAMELIZE)}(`);
              arg.children.push(`)`);
          }
      }
      if (!context.inSSR) {
          if (modifiers.includes('prop')) {
              injectPrefix(arg, '.');
          }
          if (modifiers.includes('attr')) {
              injectPrefix(arg, '^');
          }
      }
      if (!exp ||
          (exp.type === 4 /* SIMPLE_EXPRESSION */ && !exp.content.trim())) {
          context.onError(createCompilerError(34 /* X_V_BIND_NO_EXPRESSION */, loc));
          return {
              props: [createObjectProperty(arg, createSimpleExpression('', true, loc))]
          };
      }
      return {
          props: [createObjectProperty(arg, exp)]
      };
  };
  const injectPrefix = (arg, prefix) => {
      if (arg.type === 4 /* SIMPLE_EXPRESSION */) {
          if (arg.isStatic) {
              arg.content = prefix + arg.content;
          }
          else {
              arg.content = `\`${prefix}\${${arg.content}}\``;
          }
      }
      else {
          arg.children.unshift(`'${prefix}' + (`);
          arg.children.push(`)`);
      }
  };

  // Merge adjacent text nodes and expressions into a single expression
  // e.g. <div>abc {{ d }} {{ e }}</div> should have a single expression node as child.
  const transformText = (node, context) => {
      if (node.type === 0 /* ROOT */ ||
          node.type === 1 /* ELEMENT */ ||
          node.type === 11 /* FOR */ ||
          node.type === 10 /* IF_BRANCH */) {
          // perform the transform on node exit so that all expressions have already
          // been processed.
          return () => {
              const children = node.children;
              let currentContainer = undefined;
              let hasText = false;
              for (let i = 0; i < children.length; i++) {
                  const child = children[i];
                  if (isText(child)) {
                      hasText = true;
                      for (let j = i + 1; j < children.length; j++) {
                          const next = children[j];
                          if (isText(next)) {
                              if (!currentContainer) {
                                  currentContainer = children[i] = createCompoundExpression([child], child.loc);
                              }
                              // merge adjacent text node into current
                              currentContainer.children.push(` + `, next);
                              children.splice(j, 1);
                              j--;
                          }
                          else {
                              currentContainer = undefined;
                              break;
                          }
                      }
                  }
              }
              if (!hasText ||
                  // if this is a plain element with a single text child, leave it
                  // as-is since the runtime has dedicated fast path for this by directly
                  // setting textContent of the element.
                  // for component root it's always normalized anyway.
                  (children.length === 1 &&
                      (node.type === 0 /* ROOT */ ||
                          (node.type === 1 /* ELEMENT */ &&
                              node.tagType === 0 /* ELEMENT */ &&
                              // #3756
                              // custom directives can potentially add DOM elements arbitrarily,
                              // we need to avoid setting textContent of the element at runtime
                              // to avoid accidentally overwriting the DOM elements added
                              // by the user through custom directives.
                              !node.props.find(p => p.type === 7 /* DIRECTIVE */ &&
                                  !context.directiveTransforms[p.name]) &&
                              // in compat mode, <template> tags with no special directives
                              // will be rendered as a fragment so its children must be
                              // converted into vnodes.
                              !(node.tag === 'template'))))) {
                  return;
              }
              // pre-convert text nodes into createTextVNode(text) calls to avoid
              // runtime normalization.
              for (let i = 0; i < children.length; i++) {
                  const child = children[i];
                  if (isText(child) || child.type === 8 /* COMPOUND_EXPRESSION */) {
                      const callArgs = [];
                      // createTextVNode defaults to single whitespace, so if it is a
                      // single space the code could be an empty call to save bytes.
                      if (child.type !== 2 /* TEXT */ || child.content !== ' ') {
                          callArgs.push(child);
                      }
                      // mark dynamic text with flag so it gets patched inside a block
                      if (!context.ssr &&
                          getConstantType(child, context) === 0 /* NOT_CONSTANT */) {
                          callArgs.push(1 /* TEXT */ +
                              (` /* ${PatchFlagNames[1 /* TEXT */]} */` ));
                      }
                      children[i] = {
                          type: 12 /* TEXT_CALL */,
                          content: child,
                          loc: child.loc,
                          codegenNode: createCallExpression(context.helper(CREATE_TEXT), callArgs)
                      };
                  }
              }
          };
      }
  };

  const seen = new WeakSet();
  const transformOnce = (node, context) => {
      if (node.type === 1 /* ELEMENT */ && findDir(node, 'once', true)) {
          if (seen.has(node) || context.inVOnce) {
              return;
          }
          seen.add(node);
          context.inVOnce = true;
          context.helper(SET_BLOCK_TRACKING);
          return () => {
              context.inVOnce = false;
              const cur = context.currentNode;
              if (cur.codegenNode) {
                  cur.codegenNode = context.cache(cur.codegenNode, true /* isVNode */);
              }
          };
      }
  };

  const transformModel = (dir, node, context) => {
      const { exp, arg } = dir;
      if (!exp) {
          context.onError(createCompilerError(41 /* X_V_MODEL_NO_EXPRESSION */, dir.loc));
          return createTransformProps();
      }
      const rawExp = exp.loc.source;
      const expString = exp.type === 4 /* SIMPLE_EXPRESSION */ ? exp.content : rawExp;
      // im SFC <script setup> inline mode, the exp may have been transformed into
      // _unref(exp)
      context.bindingMetadata[rawExp];
      const maybeRef = !true    /* SETUP_CONST */;
      if (!expString.trim() ||
          (!isMemberExpression(expString) && !maybeRef)) {
          context.onError(createCompilerError(42 /* X_V_MODEL_MALFORMED_EXPRESSION */, exp.loc));
          return createTransformProps();
      }
      const propName = arg ? arg : createSimpleExpression('modelValue', true);
      const eventName = arg
          ? isStaticExp(arg)
              ? `onUpdate:${arg.content}`
              : createCompoundExpression(['"onUpdate:" + ', arg])
          : `onUpdate:modelValue`;
      let assignmentExp;
      const eventArg = context.isTS ? `($event: any)` : `$event`;
      {
          assignmentExp = createCompoundExpression([
              `${eventArg} => ((`,
              exp,
              `) = $event)`
          ]);
      }
      const props = [
          // modelValue: foo
          createObjectProperty(propName, dir.exp),
          // "onUpdate:modelValue": $event => (foo = $event)
          createObjectProperty(eventName, assignmentExp)
      ];
      // modelModifiers: { foo: true, "bar-baz": true }
      if (dir.modifiers.length && node.tagType === 1 /* COMPONENT */) {
          const modifiers = dir.modifiers
              .map(m => (isSimpleIdentifier(m) ? m : JSON.stringify(m)) + `: true`)
              .join(`, `);
          const modifiersKey = arg
              ? isStaticExp(arg)
                  ? `${arg.content}Modifiers`
                  : createCompoundExpression([arg, ' + "Modifiers"'])
              : `modelModifiers`;
          props.push(createObjectProperty(modifiersKey, createSimpleExpression(`{ ${modifiers} }`, false, dir.loc, 2 /* CAN_HOIST */)));
      }
      return createTransformProps(props);
  };
  function createTransformProps(props = []) {
      return { props };
  }

  const validDivisionCharRE = /[\w).+\-_$\]]/;
  const transformFilter = (node, context) => {
      if (!isCompatEnabled$1("COMPILER_FILTER" /* COMPILER_FILTERS */, context)) {
          return;
      }
      if (node.type === 5 /* INTERPOLATION */) {
          // filter rewrite is applied before expression transform so only
          // simple expressions are possible at this stage
          rewriteFilter(node.content, context);
      }
      if (node.type === 1 /* ELEMENT */) {
          node.props.forEach((prop) => {
              if (prop.type === 7 /* DIRECTIVE */ &&
                  prop.name !== 'for' &&
                  prop.exp) {
                  rewriteFilter(prop.exp, context);
              }
          });
      }
  };
  function rewriteFilter(node, context) {
      if (node.type === 4 /* SIMPLE_EXPRESSION */) {
          parseFilter(node, context);
      }
      else {
          for (let i = 0; i < node.children.length; i++) {
              const child = node.children[i];
              if (typeof child !== 'object')
                  continue;
              if (child.type === 4 /* SIMPLE_EXPRESSION */) {
                  parseFilter(child, context);
              }
              else if (child.type === 8 /* COMPOUND_EXPRESSION */) {
                  rewriteFilter(node, context);
              }
              else if (child.type === 5 /* INTERPOLATION */) {
                  rewriteFilter(child.content, context);
              }
          }
      }
  }
  function parseFilter(node, context) {
      const exp = node.content;
      let inSingle = false;
      let inDouble = false;
      let inTemplateString = false;
      let inRegex = false;
      let curly = 0;
      let square = 0;
      let paren = 0;
      let lastFilterIndex = 0;
      let c, prev, i, expression, filters = [];
      for (i = 0; i < exp.length; i++) {
          prev = c;
          c = exp.charCodeAt(i);
          if (inSingle) {
              if (c === 0x27 && prev !== 0x5c)
                  inSingle = false;
          }
          else if (inDouble) {
              if (c === 0x22 && prev !== 0x5c)
                  inDouble = false;
          }
          else if (inTemplateString) {
              if (c === 0x60 && prev !== 0x5c)
                  inTemplateString = false;
          }
          else if (inRegex) {
              if (c === 0x2f && prev !== 0x5c)
                  inRegex = false;
          }
          else if (c === 0x7c && // pipe
              exp.charCodeAt(i + 1) !== 0x7c &&
              exp.charCodeAt(i - 1) !== 0x7c &&
              !curly &&
              !square &&
              !paren) {
              if (expression === undefined) {
                  // first filter, end of expression
                  lastFilterIndex = i + 1;
                  expression = exp.slice(0, i).trim();
              }
              else {
                  pushFilter();
              }
          }
          else {
              switch (c) {
                  case 0x22:
                      inDouble = true;
                      break; // "
                  case 0x27:
                      inSingle = true;
                      break; // '
                  case 0x60:
                      inTemplateString = true;
                      break; // `
                  case 0x28:
                      paren++;
                      break; // (
                  case 0x29:
                      paren--;
                      break; // )
                  case 0x5b:
                      square++;
                      break; // [
                  case 0x5d:
                      square--;
                      break; // ]
                  case 0x7b:
                      curly++;
                      break; // {
                  case 0x7d:
                      curly--;
                      break; // }
              }
              if (c === 0x2f) {
                  // /
                  let j = i - 1;
                  let p;
                  // find first non-whitespace prev char
                  for (; j >= 0; j--) {
                      p = exp.charAt(j);
                      if (p !== ' ')
                          break;
                  }
                  if (!p || !validDivisionCharRE.test(p)) {
                      inRegex = true;
                  }
              }
          }
      }
      if (expression === undefined) {
          expression = exp.slice(0, i).trim();
      }
      else if (lastFilterIndex !== 0) {
          pushFilter();
      }
      function pushFilter() {
          filters.push(exp.slice(lastFilterIndex, i).trim());
          lastFilterIndex = i + 1;
      }
      if (filters.length) {
          warnDeprecation$1("COMPILER_FILTER" /* COMPILER_FILTERS */, context, node.loc);
          for (i = 0; i < filters.length; i++) {
              expression = wrapFilter(expression, filters[i], context);
          }
          node.content = expression;
      }
  }
  function wrapFilter(exp, filter, context) {
      context.helper(RESOLVE_FILTER);
      const i = filter.indexOf('(');
      if (i < 0) {
          context.filters.add(filter);
          return `${toValidAssetId(filter, 'filter')}(${exp})`;
      }
      else {
          const name = filter.slice(0, i);
          const args = filter.slice(i + 1);
          context.filters.add(name);
          return `${toValidAssetId(name, 'filter')}(${exp}${args !== ')' ? ',' + args : args}`;
      }
  }

  const seen$1 = new WeakSet();
  const transformMemo = (node, context) => {
      if (node.type === 1 /* ELEMENT */) {
          const dir = findDir(node, 'memo');
          if (!dir || seen$1.has(node)) {
              return;
          }
          seen$1.add(node);
          return () => {
              const codegenNode = node.codegenNode ||
                  context.currentNode.codegenNode;
              if (codegenNode && codegenNode.type === 13 /* VNODE_CALL */) {
                  // non-component sub tree should be turned into a block
                  if (node.tagType !== 1 /* COMPONENT */) {
                      makeBlock(codegenNode, context);
                  }
                  node.codegenNode = createCallExpression(context.helper(WITH_MEMO), [
                      dir.exp,
                      createFunctionExpression(undefined, codegenNode),
                      `_cache`,
                      String(context.cached++)
                  ]);
              }
          };
      }
  };

  function getBaseTransformPreset(prefixIdentifiers) {
      return [
          [
              transformOnce,
              transformIf,
              transformMemo,
              transformFor,
              ...([transformFilter] ),
              ...([transformExpression]
                      ),
              transformSlotOutlet,
              transformElement,
              trackSlotScopes,
              transformText
          ],
          {
              on: transformOn,
              bind: transformBind,
              model: transformModel
          }
      ];
  }
  // we name it `baseCompile` so that higher order compilers like
  // @vue/compiler-dom can export `compile` while re-exporting everything else.
  function baseCompile(template, options = {}) {
      const onError = options.onError || defaultOnError;
      const isModuleMode = options.mode === 'module';
      /* istanbul ignore if */
      {
          if (options.prefixIdentifiers === true) {
              onError(createCompilerError(46 /* X_PREFIX_ID_NOT_SUPPORTED */));
          }
          else if (isModuleMode) {
              onError(createCompilerError(47 /* X_MODULE_MODE_NOT_SUPPORTED */));
          }
      }
      const prefixIdentifiers = !true ;
      if (options.cacheHandlers) {
          onError(createCompilerError(48 /* X_CACHE_HANDLER_NOT_SUPPORTED */));
      }
      if (options.scopeId && !isModuleMode) {
          onError(createCompilerError(49 /* X_SCOPE_ID_NOT_SUPPORTED */));
      }
      const ast = isString(template) ? baseParse(template, options) : template;
      const [nodeTransforms, directiveTransforms] = getBaseTransformPreset();
      transform(ast, extend({}, options, {
          prefixIdentifiers,
          nodeTransforms: [
              ...nodeTransforms,
              ...(options.nodeTransforms || []) // user transforms
          ],
          directiveTransforms: extend({}, directiveTransforms, options.directiveTransforms || {} // user transforms
          )
      }));
      return generate(ast, extend({}, options, {
          prefixIdentifiers
      }));
  }

  const noopDirectiveTransform = () => ({ props: [] });

  const V_MODEL_RADIO = Symbol(`vModelRadio` );
  const V_MODEL_CHECKBOX = Symbol(`vModelCheckbox` );
  const V_MODEL_TEXT = Symbol(`vModelText` );
  const V_MODEL_SELECT = Symbol(`vModelSelect` );
  const V_MODEL_DYNAMIC = Symbol(`vModelDynamic` );
  const V_ON_WITH_MODIFIERS = Symbol(`vOnModifiersGuard` );
  const V_ON_WITH_KEYS = Symbol(`vOnKeysGuard` );
  const V_SHOW = Symbol(`vShow` );
  const TRANSITION$1 = Symbol(`Transition` );
  const TRANSITION_GROUP = Symbol(`TransitionGroup` );
  registerRuntimeHelpers({
      [V_MODEL_RADIO]: `vModelRadio`,
      [V_MODEL_CHECKBOX]: `vModelCheckbox`,
      [V_MODEL_TEXT]: `vModelText`,
      [V_MODEL_SELECT]: `vModelSelect`,
      [V_MODEL_DYNAMIC]: `vModelDynamic`,
      [V_ON_WITH_MODIFIERS]: `withModifiers`,
      [V_ON_WITH_KEYS]: `withKeys`,
      [V_SHOW]: `vShow`,
      [TRANSITION$1]: `Transition`,
      [TRANSITION_GROUP]: `TransitionGroup`
  });

  /* eslint-disable no-restricted-globals */
  let decoder;
  function decodeHtmlBrowser(raw, asAttr = false) {
      if (!decoder) {
          decoder = document.createElement('div');
      }
      if (asAttr) {
          decoder.innerHTML = `<div foo="${raw.replace(/"/g, '&quot;')}">`;
          return decoder.children[0].getAttribute('foo');
      }
      else {
          decoder.innerHTML = raw;
          return decoder.textContent;
      }
  }

  const isRawTextContainer = /*#__PURE__*/ makeMap('style,iframe,script,noscript', true);
  const parserOptions = {
      isVoidTag,
      isNativeTag: tag => isHTMLTag(tag) || isSVGTag(tag),
      isPreTag: tag => tag === 'pre',
      decodeEntities: decodeHtmlBrowser ,
      isBuiltInComponent: (tag) => {
          if (isBuiltInType(tag, `Transition`)) {
              return TRANSITION$1;
          }
          else if (isBuiltInType(tag, `TransitionGroup`)) {
              return TRANSITION_GROUP;
          }
      },
      // https://html.spec.whatwg.org/multipage/parsing.html#tree-construction-dispatcher
      getNamespace(tag, parent) {
          let ns = parent ? parent.ns : 0 /* HTML */;
          if (parent && ns === 2 /* MATH_ML */) {
              if (parent.tag === 'annotation-xml') {
                  if (tag === 'svg') {
                      return 1 /* SVG */;
                  }
                  if (parent.props.some(a => a.type === 6 /* ATTRIBUTE */ &&
                      a.name === 'encoding' &&
                      a.value != null &&
                      (a.value.content === 'text/html' ||
                          a.value.content === 'application/xhtml+xml'))) {
                      ns = 0 /* HTML */;
                  }
              }
              else if (/^m(?:[ions]|text)$/.test(parent.tag) &&
                  tag !== 'mglyph' &&
                  tag !== 'malignmark') {
                  ns = 0 /* HTML */;
              }
          }
          else if (parent && ns === 1 /* SVG */) {
              if (parent.tag === 'foreignObject' ||
                  parent.tag === 'desc' ||
                  parent.tag === 'title') {
                  ns = 0 /* HTML */;
              }
          }
          if (ns === 0 /* HTML */) {
              if (tag === 'svg') {
                  return 1 /* SVG */;
              }
              if (tag === 'math') {
                  return 2 /* MATH_ML */;
              }
          }
          return ns;
      },
      // https://html.spec.whatwg.org/multipage/parsing.html#parsing-html-fragments
      getTextMode({ tag, ns }) {
          if (ns === 0 /* HTML */) {
              if (tag === 'textarea' || tag === 'title') {
                  return 1 /* RCDATA */;
              }
              if (isRawTextContainer(tag)) {
                  return 2 /* RAWTEXT */;
              }
          }
          return 0 /* DATA */;
      }
  };

  // Parse inline CSS strings for static style attributes into an object.
  // This is a NodeTransform since it works on the static `style` attribute and
  // converts it into a dynamic equivalent:
  // style="color: red" -> :style='{ "color": "red" }'
  // It is then processed by `transformElement` and included in the generated
  // props.
  const transformStyle = node => {
      if (node.type === 1 /* ELEMENT */) {
          node.props.forEach((p, i) => {
              if (p.type === 6 /* ATTRIBUTE */ && p.name === 'style' && p.value) {
                  // replace p with an expression node
                  node.props[i] = {
                      type: 7 /* DIRECTIVE */,
                      name: `bind`,
                      arg: createSimpleExpression(`style`, true, p.loc),
                      exp: parseInlineCSS(p.value.content, p.loc),
                      modifiers: [],
                      loc: p.loc
                  };
              }
          });
      }
  };
  const parseInlineCSS = (cssText, loc) => {
      const normalized = parseStringStyle(cssText);
      return createSimpleExpression(JSON.stringify(normalized), false, loc, 3 /* CAN_STRINGIFY */);
  };

  function createDOMCompilerError(code, loc) {
      return createCompilerError(code, loc, DOMErrorMessages );
  }
  const DOMErrorMessages = {
      [50 /* X_V_HTML_NO_EXPRESSION */]: `v-html is missing expression.`,
      [51 /* X_V_HTML_WITH_CHILDREN */]: `v-html will override element children.`,
      [52 /* X_V_TEXT_NO_EXPRESSION */]: `v-text is missing expression.`,
      [53 /* X_V_TEXT_WITH_CHILDREN */]: `v-text will override element children.`,
      [54 /* X_V_MODEL_ON_INVALID_ELEMENT */]: `v-model can only be used on <input>, <textarea> and <select> elements.`,
      [55 /* X_V_MODEL_ARG_ON_ELEMENT */]: `v-model argument is not supported on plain elements.`,
      [56 /* X_V_MODEL_ON_FILE_INPUT_ELEMENT */]: `v-model cannot be used on file inputs since they are read-only. Use a v-on:change listener instead.`,
      [57 /* X_V_MODEL_UNNECESSARY_VALUE */]: `Unnecessary value binding used alongside v-model. It will interfere with v-model's behavior.`,
      [58 /* X_V_SHOW_NO_EXPRESSION */]: `v-show is missing expression.`,
      [59 /* X_TRANSITION_INVALID_CHILDREN */]: `<Transition> expects exactly one child element or component.`,
      [60 /* X_IGNORED_SIDE_EFFECT_TAG */]: `Tags with side effect (<script> and <style>) are ignored in client component templates.`
  };

  const transformVHtml = (dir, node, context) => {
      const { exp, loc } = dir;
      if (!exp) {
          context.onError(createDOMCompilerError(50 /* X_V_HTML_NO_EXPRESSION */, loc));
      }
      if (node.children.length) {
          context.onError(createDOMCompilerError(51 /* X_V_HTML_WITH_CHILDREN */, loc));
          node.children.length = 0;
      }
      return {
          props: [
              createObjectProperty(createSimpleExpression(`innerHTML`, true, loc), exp || createSimpleExpression('', true))
          ]
      };
  };

  const transformVText = (dir, node, context) => {
      const { exp, loc } = dir;
      if (!exp) {
          context.onError(createDOMCompilerError(52 /* X_V_TEXT_NO_EXPRESSION */, loc));
      }
      if (node.children.length) {
          context.onError(createDOMCompilerError(53 /* X_V_TEXT_WITH_CHILDREN */, loc));
          node.children.length = 0;
      }
      return {
          props: [
              createObjectProperty(createSimpleExpression(`textContent`, true), exp
                  ? getConstantType(exp, context) > 0
                      ? exp
                      : createCallExpression(context.helperString(TO_DISPLAY_STRING), [exp], loc)
                  : createSimpleExpression('', true))
          ]
      };
  };

  const transformModel$1 = (dir, node, context) => {
      const baseResult = transformModel(dir, node, context);
      // base transform has errors OR component v-model (only need props)
      if (!baseResult.props.length || node.tagType === 1 /* COMPONENT */) {
          return baseResult;
      }
      if (dir.arg) {
          context.onError(createDOMCompilerError(55 /* X_V_MODEL_ARG_ON_ELEMENT */, dir.arg.loc));
      }
      function checkDuplicatedValue() {
          const value = findProp(node, 'value');
          if (value) {
              context.onError(createDOMCompilerError(57 /* X_V_MODEL_UNNECESSARY_VALUE */, value.loc));
          }
      }
      const { tag } = node;
      const isCustomElement = context.isCustomElement(tag);
      if (tag === 'input' ||
          tag === 'textarea' ||
          tag === 'select' ||
          isCustomElement) {
          let directiveToUse = V_MODEL_TEXT;
          let isInvalidType = false;
          if (tag === 'input' || isCustomElement) {
              const type = findProp(node, `type`);
              if (type) {
                  if (type.type === 7 /* DIRECTIVE */) {
                      // :type="foo"
                      directiveToUse = V_MODEL_DYNAMIC;
                  }
                  else if (type.value) {
                      switch (type.value.content) {
                          case 'radio':
                              directiveToUse = V_MODEL_RADIO;
                              break;
                          case 'checkbox':
                              directiveToUse = V_MODEL_CHECKBOX;
                              break;
                          case 'file':
                              isInvalidType = true;
                              context.onError(createDOMCompilerError(56 /* X_V_MODEL_ON_FILE_INPUT_ELEMENT */, dir.loc));
                              break;
                          default:
                              // text type
                              checkDuplicatedValue();
                              break;
                      }
                  }
              }
              else if (hasDynamicKeyVBind(node)) {
                  // element has bindings with dynamic keys, which can possibly contain
                  // "type".
                  directiveToUse = V_MODEL_DYNAMIC;
              }
              else {
                  // text type
                  checkDuplicatedValue();
              }
          }
          else if (tag === 'select') {
              directiveToUse = V_MODEL_SELECT;
          }
          else {
              // textarea
              checkDuplicatedValue();
          }
          // inject runtime directive
          // by returning the helper symbol via needRuntime
          // the import will replaced a resolveDirective call.
          if (!isInvalidType) {
              baseResult.needRuntime = context.helper(directiveToUse);
          }
      }
      else {
          context.onError(createDOMCompilerError(54 /* X_V_MODEL_ON_INVALID_ELEMENT */, dir.loc));
      }
      // native vmodel doesn't need the `modelValue` props since they are also
      // passed to the runtime as `binding.value`. removing it reduces code size.
      baseResult.props = baseResult.props.filter(p => !(p.key.type === 4 /* SIMPLE_EXPRESSION */ &&
          p.key.content === 'modelValue'));
      return baseResult;
  };

  const isEventOptionModifier = /*#__PURE__*/ makeMap(`passive,once,capture`);
  const isNonKeyModifier = /*#__PURE__*/ makeMap(
  // event propagation management
`stop,prevent,self,`   +
      // system modifiers + exact
      `ctrl,shift,alt,meta,exact,` +
      // mouse
      `middle`);
  // left & right could be mouse or key modifiers based on event type
  const maybeKeyModifier = /*#__PURE__*/ makeMap('left,right');
  const isKeyboardEvent = /*#__PURE__*/ makeMap(`onkeyup,onkeydown,onkeypress`, true);
  const resolveModifiers = (key, modifiers, context, loc) => {
      const keyModifiers = [];
      const nonKeyModifiers = [];
      const eventOptionModifiers = [];
      for (let i = 0; i < modifiers.length; i++) {
          const modifier = modifiers[i];
          if (modifier === 'native' &&
              checkCompatEnabled$1("COMPILER_V_ON_NATIVE" /* COMPILER_V_ON_NATIVE */, context, loc)) {
              eventOptionModifiers.push(modifier);
          }
          else if (isEventOptionModifier(modifier)) {
              // eventOptionModifiers: modifiers for addEventListener() options,
              // e.g. .passive & .capture
              eventOptionModifiers.push(modifier);
          }
          else {
              // runtimeModifiers: modifiers that needs runtime guards
              if (maybeKeyModifier(modifier)) {
                  if (isStaticExp(key)) {
                      if (isKeyboardEvent(key.content)) {
                          keyModifiers.push(modifier);
                      }
                      else {
                          nonKeyModifiers.push(modifier);
                      }
                  }
                  else {
                      keyModifiers.push(modifier);
                      nonKeyModifiers.push(modifier);
                  }
              }
              else {
                  if (isNonKeyModifier(modifier)) {
                      nonKeyModifiers.push(modifier);
                  }
                  else {
                      keyModifiers.push(modifier);
                  }
              }
          }
      }
      return {
          keyModifiers,
          nonKeyModifiers,
          eventOptionModifiers
      };
  };
  const transformClick = (key, event) => {
      const isStaticClick = isStaticExp(key) && key.content.toLowerCase() === 'onclick';
      return isStaticClick
          ? createSimpleExpression(event, true)
          : key.type !== 4 /* SIMPLE_EXPRESSION */
              ? createCompoundExpression([
                  `(`,
                  key,
                  `) === "onClick" ? "${event}" : (`,
                  key,
                  `)`
              ])
              : key;
  };
  const transformOn$1 = (dir, node, context) => {
      return transformOn(dir, node, context, baseResult => {
          const { modifiers } = dir;
          if (!modifiers.length)
              return baseResult;
          let { key, value: handlerExp } = baseResult.props[0];
          const { keyModifiers, nonKeyModifiers, eventOptionModifiers } = resolveModifiers(key, modifiers, context, dir.loc);
          // normalize click.right and click.middle since they don't actually fire
          if (nonKeyModifiers.includes('right')) {
              key = transformClick(key, `onContextmenu`);
          }
          if (nonKeyModifiers.includes('middle')) {
              key = transformClick(key, `onMouseup`);
          }
          if (nonKeyModifiers.length) {
              handlerExp = createCallExpression(context.helper(V_ON_WITH_MODIFIERS), [
                  handlerExp,
                  JSON.stringify(nonKeyModifiers)
              ]);
          }
          if (keyModifiers.length &&
              // if event name is dynamic, always wrap with keys guard
              (!isStaticExp(key) || isKeyboardEvent(key.content))) {
              handlerExp = createCallExpression(context.helper(V_ON_WITH_KEYS), [
                  handlerExp,
                  JSON.stringify(keyModifiers)
              ]);
          }
          if (eventOptionModifiers.length) {
              const modifierPostfix = eventOptionModifiers.map(capitalize).join('');
              key = isStaticExp(key)
                  ? createSimpleExpression(`${key.content}${modifierPostfix}`, true)
                  : createCompoundExpression([`(`, key, `) + "${modifierPostfix}"`]);
          }
          return {
              props: [createObjectProperty(key, handlerExp)]
          };
      });
  };

  const transformShow = (dir, node, context) => {
      const { exp, loc } = dir;
      if (!exp) {
          context.onError(createDOMCompilerError(58 /* X_V_SHOW_NO_EXPRESSION */, loc));
      }
      return {
          props: [],
          needRuntime: context.helper(V_SHOW)
      };
  };

  const transformTransition = (node, context) => {
      if (node.type === 1 /* ELEMENT */ &&
          node.tagType === 1 /* COMPONENT */) {
          const component = context.isBuiltInComponent(node.tag);
          if (component === TRANSITION$1) {
              return () => {
                  if (!node.children.length) {
                      return;
                  }
                  // warn multiple transition children
                  if (hasMultipleChildren(node)) {
                      context.onError(createDOMCompilerError(59 /* X_TRANSITION_INVALID_CHILDREN */, {
                          start: node.children[0].loc.start,
                          end: node.children[node.children.length - 1].loc.end,
                          source: ''
                      }));
                  }
                  // check if it's s single child w/ v-show
                  // if yes, inject "persisted: true" to the transition props
                  const child = node.children[0];
                  if (child.type === 1 /* ELEMENT */) {
                      for (const p of child.props) {
                          if (p.type === 7 /* DIRECTIVE */ && p.name === 'show') {
                              node.props.push({
                                  type: 6 /* ATTRIBUTE */,
                                  name: 'persisted',
                                  value: undefined,
                                  loc: node.loc
                              });
                          }
                      }
                  }
              };
          }
      }
  };
  function hasMultipleChildren(node) {
      // #1352 filter out potential comment nodes.
      const children = (node.children = node.children.filter(c => c.type !== 3 /* COMMENT */ &&
          !(c.type === 2 /* TEXT */ && !c.content.trim())));
      const child = children[0];
      return (children.length !== 1 ||
          child.type === 11 /* FOR */ ||
          (child.type === 9 /* IF */ && child.branches.some(hasMultipleChildren)));
  }

  const ignoreSideEffectTags = (node, context) => {
      if (node.type === 1 /* ELEMENT */ &&
          node.tagType === 0 /* ELEMENT */ &&
          (node.tag === 'script' || node.tag === 'style')) {
          context.onError(createDOMCompilerError(60 /* X_IGNORED_SIDE_EFFECT_TAG */, node.loc));
          context.removeNode();
      }
  };

  const DOMNodeTransforms = [
      transformStyle,
      ...([transformTransition] )
  ];
  const DOMDirectiveTransforms = {
      cloak: noopDirectiveTransform,
      html: transformVHtml,
      text: transformVText,
      model: transformModel$1,
      on: transformOn$1,
      show: transformShow
  };
  function compile$1(template, options = {}) {
      return baseCompile(template, extend({}, parserOptions, options, {
          nodeTransforms: [
              // ignore <script> and <tag>
              // this is not put inside DOMNodeTransforms because that list is used
              // by compiler-ssr to generate vnode fallback branches
              ignoreSideEffectTags,
              ...DOMNodeTransforms,
              ...(options.nodeTransforms || [])
          ],
          directiveTransforms: extend({}, DOMDirectiveTransforms, options.directiveTransforms || {}),
          transformHoist: null 
      }));
  }

  // This entry is the "full-build" that includes both the runtime
  const compileCache = Object.create(null);
  function compileToFunction(template, options) {
      if (!isString(template)) {
          if (template.nodeType) {
              template = template.innerHTML;
          }
          else {
              warn$1(`invalid template option: `, template);
              return NOOP;
          }
      }
      const key = template;
      const cached = compileCache[key];
      if (cached) {
          return cached;
      }
      if (template[0] === '#') {
          const el = document.querySelector(template);
          if (!el) {
              warn$1(`Template element not found or is empty: ${template}`);
          }
          // __UNSAFE__
          // Reason: potential execution of JS expressions in in-DOM template.
          // The user must make sure the in-DOM template is trusted. If it's rendered
          // by the server, the template should not contain any user data.
          template = el ? el.innerHTML : ``;
      }
      if ((!options || !options.whitespace)) {
          warnDeprecation("CONFIG_WHITESPACE" /* CONFIG_WHITESPACE */, null);
      }
      const { code } = compile$1(template, extend({
          hoistStatic: true,
          whitespace: 'preserve',
          onError: onError ,
          onWarn: e => onError(e, true) 
      }, options));
      function onError(err, asWarning = false) {
          const message = asWarning
              ? err.message
              : `Template compilation error: ${err.message}`;
          const codeFrame = err.loc &&
              generateCodeFrame(template, err.loc.start.offset, err.loc.end.offset);
          warn$1(codeFrame ? `${message}\n${codeFrame}` : message);
      }
      // The wildcard import results in a huge object with every export
      // with keys that cannot be mangled, and can be quite heavy size-wise.
      // In the global build we know `Vue` is available globally so we can avoid
      // the wildcard object.
      const render = (new Function(code)() );
      render._rc = true;
      return (compileCache[key] = render);
  }
  registerRuntimeCompiler(compileToFunction);
  const Vue = createCompatVue$1();
  Vue.compile = compileToFunction;

  return Vue;

}());
