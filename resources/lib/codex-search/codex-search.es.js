var Te = Object.defineProperty, Le = Object.defineProperties;
var Ve = Object.getOwnPropertyDescriptors;
var ae = Object.getOwnPropertySymbols;
var Ae = Object.prototype.hasOwnProperty, Be = Object.prototype.propertyIsEnumerable;
var Ce = (e, t, n) => t in e ? Te(e, t, { enumerable: !0, configurable: !0, writable: !0, value: n }) : e[t] = n, _e = (e, t) => {
  for (var n in t || (t = {}))
    Ae.call(t, n) && Ce(e, n, t[n]);
  if (ae)
    for (var n of ae(t))
      Be.call(t, n) && Ce(e, n, t[n]);
  return e;
}, $e = (e, t) => Le(e, Ve(t));
var le = (e, t) => {
  var n = {};
  for (var u in e)
    Ae.call(e, u) && t.indexOf(u) < 0 && (n[u] = e[u]);
  if (e != null && ae)
    for (var u of ae(e))
      t.indexOf(u) < 0 && Be.call(e, u) && (n[u] = e[u]);
  return n;
};
var fe = (e, t, n) => new Promise((u, l) => {
  var r = (a) => {
    try {
      d(n.next(a));
    } catch (s) {
      l(s);
    }
  }, o = (a) => {
    try {
      d(n.throw(a));
    } catch (s) {
      l(s);
    }
  }, d = (a) => a.done ? u(a.value) : Promise.resolve(a.value).then(r, o);
  d((n = n.apply(e, t)).next());
});
import { ref as C, onMounted as X, defineComponent as T, computed as f, openBlock as h, createElementBlock as g, normalizeClass as F, toDisplayString as x, createCommentVNode as S, resolveComponent as w, createVNode as W, Transition as Re, withCtx as Q, normalizeStyle as Z, createElementVNode as B, createTextVNode as se, withModifiers as ye, renderSlot as M, createBlock as k, resolveDynamicComponent as Ne, Fragment as Ie, getCurrentInstance as Oe, onUnmounted as Se, watch as J, toRef as Y, nextTick as oe, withDirectives as we, mergeProps as G, renderList as Ke, vShow as qe, Comment as Qe, warn as Ue, withKeys as ve, vModelDynamic as He, toRefs as Pe } from "vue";
const je = '<path d="M12.43 14.34A5 5 0 0110 15a5 5 0 113.95-2L17 16.09V3a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2h10a2 2 0 001.45-.63z"/><circle cx="10" cy="10" r="3"/>', ze = '<path d="M10 0a10 10 0 1010 10A10 10 0 0010 0zm5.66 14.24-1.41 1.41L10 11.41l-4.24 4.25-1.42-1.42L8.59 10 4.34 5.76l1.42-1.42L10 8.59l4.24-4.24 1.41 1.41L11.41 10z"/>', We = '<path d="M19 3H1v14h18zM3 14l3.5-4.5 2.5 3L12.5 8l4.5 6z"/><path d="M19 5H1V3h18zm0 12H1v-2h18z"/>', Ge = '<path d="M12.2 13.6a7 7 0 111.4-1.4l5.4 5.4-1.4 1.4zM3 8a5 5 0 1010 0A5 5 0 003 8z"/>', Xe = je, Je = ze, Ye = We, Ze = Ge;
function et(e, t, n) {
  if (typeof e == "string" || "path" in e)
    return e;
  if ("shouldFlip" in e)
    return e.ltr;
  if ("rtl" in e)
    return n === "rtl" ? e.rtl : e.ltr;
  const u = t in e.langCodeMap ? e.langCodeMap[t] : e.default;
  return typeof u == "string" || "path" in u ? u : u.ltr;
}
function tt(e, t) {
  if (typeof e == "string")
    return !1;
  if ("langCodeMap" in e) {
    const n = t in e.langCodeMap ? e.langCodeMap[t] : e.default;
    if (typeof n == "string")
      return !1;
    e = n;
  }
  if ("shouldFlipExceptions" in e && Array.isArray(e.shouldFlipExceptions)) {
    const n = e.shouldFlipExceptions.indexOf(t);
    return n === void 0 || n === -1;
  }
  return "shouldFlip" in e ? e.shouldFlip : !1;
}
function nt(e) {
  const t = C(null);
  return X(() => {
    const n = window.getComputedStyle(e.value).direction;
    t.value = n === "ltr" || n === "rtl" ? n : null;
  }), t;
}
function ut(e) {
  const t = C("");
  return X(() => {
    let n = e.value;
    for (; n && n.lang === ""; )
      n = n.parentElement;
    t.value = n ? n.lang : null;
  }), t;
}
const at = T({
  name: "CdxIcon",
  props: {
    icon: {
      type: [String, Object],
      required: !0
    },
    iconLabel: {
      type: String,
      default: ""
    },
    lang: {
      type: String,
      default: null
    },
    dir: {
      type: String,
      default: null
    }
  },
  emits: ["click"],
  setup(e, { emit: t }) {
    const n = C(), u = nt(n), l = ut(n), r = f(() => e.dir || u.value), o = f(() => e.lang || l.value), d = f(() => ({
      "cdx-icon--flipped": r.value === "rtl" && o.value !== null && tt(e.icon, o.value)
    })), a = f(
      () => et(e.icon, o.value || "", r.value || "ltr")
    ), s = f(() => typeof a.value == "string" ? a.value : ""), p = f(() => typeof a.value != "string" ? a.value.path : "");
    return {
      rootElement: n,
      rootClasses: d,
      iconSvg: s,
      iconPath: p,
      onClick: (b) => {
        t("click", b);
      }
    };
  }
});
const L = (e, t) => {
  const n = e.__vccOpts || e;
  for (const [u, l] of t)
    n[u] = l;
  return n;
}, lt = ["aria-hidden"], ot = { key: 0 }, st = ["innerHTML"], it = ["d"];
function rt(e, t, n, u, l, r) {
  return h(), g("span", {
    ref: "rootElement",
    class: F(["cdx-icon", e.rootClasses]),
    onClick: t[0] || (t[0] = (...o) => e.onClick && e.onClick(...o))
  }, [
    (h(), g("svg", {
      xmlns: "http://www.w3.org/2000/svg",
      width: "20",
      height: "20",
      viewBox: "0 0 20 20",
      "aria-hidden": !e.iconLabel
    }, [
      e.iconLabel ? (h(), g("title", ot, x(e.iconLabel), 1)) : S("", !0),
      e.iconSvg ? (h(), g("g", {
        key: 1,
        innerHTML: e.iconSvg
      }, null, 8, st)) : (h(), g("path", {
        key: 2,
        d: e.iconPath
      }, null, 8, it))
    ], 8, lt))
  ], 2);
}
const ee = /* @__PURE__ */ L(at, [["render", rt]]), dt = T({
  name: "CdxThumbnail",
  components: { CdxIcon: ee },
  props: {
    thumbnail: {
      type: [Object, null],
      default: null
    },
    placeholderIcon: {
      type: [String, Object],
      default: Ye
    }
  },
  setup: (e) => {
    const t = C(!1), n = C({}), u = (l) => {
      const r = l.replace(/([\\"\n])/g, "\\$1"), o = new Image();
      o.onload = () => {
        n.value = { backgroundImage: `url("${r}")` }, t.value = !0;
      }, o.onerror = () => {
        t.value = !1;
      }, o.src = r;
    };
    return X(() => {
      var l;
      (l = e.thumbnail) != null && l.url && u(e.thumbnail.url);
    }), {
      thumbnailStyle: n,
      thumbnailLoaded: t
    };
  }
});
const ct = { class: "cdx-thumbnail" }, ht = {
  key: 0,
  class: "cdx-thumbnail__placeholder"
};
function ft(e, t, n, u, l, r) {
  const o = w("cdx-icon");
  return h(), g("span", ct, [
    e.thumbnailLoaded ? S("", !0) : (h(), g("span", ht, [
      W(o, {
        icon: e.placeholderIcon,
        class: "cdx-thumbnail__placeholder__icon"
      }, null, 8, ["icon"])
    ])),
    W(Re, { name: "cdx-thumbnail__image" }, {
      default: Q(() => [
        e.thumbnailLoaded ? (h(), g("span", {
          key: 0,
          style: Z(e.thumbnailStyle),
          class: "cdx-thumbnail__image"
        }, null, 4)) : S("", !0)
      ]),
      _: 1
    })
  ]);
}
const pt = /* @__PURE__ */ L(dt, [["render", ft]]);
function mt(e) {
  return e.replace(/([\\{}()|.?*+\-^$[\]])/g, "\\$1");
}
const vt = "[\u0300-\u036F\u0483-\u0489\u0591-\u05BD\u05BF\u05C1\u05C2\u05C4\u05C5\u05C7\u0610-\u061A\u064B-\u065F\u0670\u06D6-\u06DC\u06DF-\u06E4\u06E7\u06E8\u06EA-\u06ED\u0711\u0730-\u074A\u07A6-\u07B0\u07EB-\u07F3\u07FD\u0816-\u0819\u081B-\u0823\u0825-\u0827\u0829-\u082D\u0859-\u085B\u08D3-\u08E1\u08E3-\u0903\u093A-\u093C\u093E-\u094F\u0951-\u0957\u0962\u0963\u0981-\u0983\u09BC\u09BE-\u09C4\u09C7\u09C8\u09CB-\u09CD\u09D7\u09E2\u09E3\u09FE\u0A01-\u0A03\u0A3C\u0A3E-\u0A42\u0A47\u0A48\u0A4B-\u0A4D\u0A51\u0A70\u0A71\u0A75\u0A81-\u0A83\u0ABC\u0ABE-\u0AC5\u0AC7-\u0AC9\u0ACB-\u0ACD\u0AE2\u0AE3\u0AFA-\u0AFF\u0B01-\u0B03\u0B3C\u0B3E-\u0B44\u0B47\u0B48\u0B4B-\u0B4D\u0B56\u0B57\u0B62\u0B63\u0B82\u0BBE-\u0BC2\u0BC6-\u0BC8\u0BCA-\u0BCD\u0BD7\u0C00-\u0C04\u0C3E-\u0C44\u0C46-\u0C48\u0C4A-\u0C4D\u0C55\u0C56\u0C62\u0C63\u0C81-\u0C83\u0CBC\u0CBE-\u0CC4\u0CC6-\u0CC8\u0CCA-\u0CCD\u0CD5\u0CD6\u0CE2\u0CE3\u0D00-\u0D03\u0D3B\u0D3C\u0D3E-\u0D44\u0D46-\u0D48\u0D4A-\u0D4D\u0D57\u0D62\u0D63\u0D82\u0D83\u0DCA\u0DCF-\u0DD4\u0DD6\u0DD8-\u0DDF\u0DF2\u0DF3\u0E31\u0E34-\u0E3A\u0E47-\u0E4E\u0EB1\u0EB4-\u0EB9\u0EBB\u0EBC\u0EC8-\u0ECD\u0F18\u0F19\u0F35\u0F37\u0F39\u0F3E\u0F3F\u0F71-\u0F84\u0F86\u0F87\u0F8D-\u0F97\u0F99-\u0FBC\u0FC6\u102B-\u103E\u1056-\u1059\u105E-\u1060\u1062-\u1064\u1067-\u106D\u1071-\u1074\u1082-\u108D\u108F\u109A-\u109D\u135D-\u135F\u1712-\u1714\u1732-\u1734\u1752\u1753\u1772\u1773\u17B4-\u17D3\u17DD\u180B-\u180D\u1885\u1886\u18A9\u1920-\u192B\u1930-\u193B\u1A17-\u1A1B\u1A55-\u1A5E\u1A60-\u1A7C\u1A7F\u1AB0-\u1ABE\u1B00-\u1B04\u1B34-\u1B44\u1B6B-\u1B73\u1B80-\u1B82\u1BA1-\u1BAD\u1BE6-\u1BF3\u1C24-\u1C37\u1CD0-\u1CD2\u1CD4-\u1CE8\u1CED\u1CF2-\u1CF4\u1CF7-\u1CF9\u1DC0-\u1DF9\u1DFB-\u1DFF\u20D0-\u20F0\u2CEF-\u2CF1\u2D7F\u2DE0-\u2DFF\u302A-\u302F\u3099\u309A\uA66F-\uA672\uA674-\uA67D\uA69E\uA69F\uA6F0\uA6F1\uA802\uA806\uA80B\uA823-\uA827\uA880\uA881\uA8B4-\uA8C5\uA8E0-\uA8F1\uA8FF\uA926-\uA92D\uA947-\uA953\uA980-\uA983\uA9B3-\uA9C0\uA9E5\uAA29-\uAA36\uAA43\uAA4C\uAA4D\uAA7B-\uAA7D\uAAB0\uAAB2-\uAAB4\uAAB7\uAAB8\uAABE\uAABF\uAAC1\uAAEB-\uAAEF\uAAF5\uAAF6\uABE3-\uABEA\uABEC\uABED\uFB1E\uFE00-\uFE0F\uFE20-\uFE2F]";
function gt(e, t) {
  if (!e)
    return [t, "", ""];
  const n = mt(e), u = new RegExp(
    n + vt + "*",
    "i"
  ).exec(t);
  if (!u || u.index === void 0)
    return [t, "", ""];
  const l = u.index, r = l + u[0].length, o = t.slice(l, r), d = t.slice(0, l), a = t.slice(r, t.length);
  return [d, o, a];
}
const yt = T({
  name: "CdxSearchResultTitle",
  props: {
    title: {
      type: String,
      required: !0
    },
    searchQuery: {
      type: String,
      default: ""
    }
  },
  setup: (e) => ({
    titleChunks: f(() => gt(e.searchQuery, String(e.title)))
  })
});
const bt = { class: "cdx-search-result-title" }, Ct = { class: "cdx-search-result-title__match" };
function At(e, t, n, u, l, r) {
  return h(), g("span", bt, [
    B("bdi", null, [
      se(x(e.titleChunks[0]), 1),
      B("span", Ct, x(e.titleChunks[1]), 1),
      se(x(e.titleChunks[2]), 1)
    ])
  ]);
}
const Bt = /* @__PURE__ */ L(yt, [["render", At]]), _t = T({
  name: "CdxMenuItem",
  components: { CdxIcon: ee, CdxThumbnail: pt, CdxSearchResultTitle: Bt },
  props: {
    id: {
      type: String,
      required: !0
    },
    value: {
      type: [String, Number],
      required: !0
    },
    disabled: {
      type: Boolean,
      default: !1
    },
    selected: {
      type: Boolean,
      default: !1
    },
    active: {
      type: Boolean,
      default: !1
    },
    highlighted: {
      type: Boolean,
      default: !1
    },
    label: {
      type: String,
      default: ""
    },
    match: {
      type: String,
      default: ""
    },
    url: {
      type: String,
      default: ""
    },
    icon: {
      type: [String, Object],
      default: ""
    },
    showThumbnail: {
      type: Boolean,
      default: !1
    },
    thumbnail: {
      type: [Object, null],
      default: null
    },
    description: {
      type: [String, null],
      default: ""
    },
    searchQuery: {
      type: String,
      default: ""
    },
    boldLabel: {
      type: Boolean,
      default: !1
    },
    hideDescriptionOverflow: {
      type: Boolean,
      default: !1
    },
    language: {
      type: Object,
      default: () => ({})
    }
  },
  emits: [
    "change"
  ],
  setup: (e, { emit: t }) => {
    const n = () => {
      t("change", "highlighted", !0);
    }, u = () => {
      t("change", "highlighted", !1);
    }, l = (p) => {
      p.button === 0 && t("change", "active", !0);
    }, r = () => {
      t("change", "selected", !0);
    }, o = f(() => e.searchQuery.length > 0), d = f(() => ({
      "cdx-menu-item--selected": e.selected,
      "cdx-menu-item--active": e.active && e.highlighted,
      "cdx-menu-item--highlighted": e.highlighted,
      "cdx-menu-item--enabled": !e.disabled,
      "cdx-menu-item--disabled": e.disabled,
      "cdx-menu-item--highlight-query": o.value,
      "cdx-menu-item--bold-label": e.boldLabel,
      "cdx-menu-item--has-description": !!e.description,
      "cdx-menu-item--hide-description-overflow": e.hideDescriptionOverflow
    })), a = f(() => e.url ? "a" : "span"), s = f(() => e.label || String(e.value));
    return {
      onMouseEnter: n,
      onMouseLeave: u,
      onMouseDown: l,
      onClick: r,
      highlightQuery: o,
      rootClasses: d,
      contentTag: a,
      title: s
    };
  }
});
const $t = ["id", "aria-disabled", "aria-selected"], It = { class: "cdx-menu-item__text" }, St = ["lang"], wt = /* @__PURE__ */ se(/* @__PURE__ */ x(" ") + " "), Et = ["lang"], Dt = ["lang"];
function kt(e, t, n, u, l, r) {
  const o = w("cdx-thumbnail"), d = w("cdx-icon"), a = w("cdx-search-result-title");
  return h(), g("li", {
    id: e.id,
    role: "option",
    class: F(["cdx-menu-item", e.rootClasses]),
    "aria-disabled": e.disabled,
    "aria-selected": e.selected,
    onMouseenter: t[0] || (t[0] = (...s) => e.onMouseEnter && e.onMouseEnter(...s)),
    onMouseleave: t[1] || (t[1] = (...s) => e.onMouseLeave && e.onMouseLeave(...s)),
    onMousedown: t[2] || (t[2] = ye((...s) => e.onMouseDown && e.onMouseDown(...s), ["prevent"])),
    onClick: t[3] || (t[3] = (...s) => e.onClick && e.onClick(...s))
  }, [
    M(e.$slots, "default", {}, () => [
      (h(), k(Ne(e.contentTag), {
        href: e.url ? e.url : void 0,
        class: "cdx-menu-item__content"
      }, {
        default: Q(() => {
          var s, p, $, b, _;
          return [
            e.showThumbnail ? (h(), k(o, {
              key: 0,
              thumbnail: e.thumbnail,
              class: "cdx-menu-item__thumbnail"
            }, null, 8, ["thumbnail"])) : e.icon ? (h(), k(d, {
              key: 1,
              icon: e.icon,
              class: "cdx-menu-item__icon"
            }, null, 8, ["icon"])) : S("", !0),
            B("span", It, [
              e.highlightQuery ? (h(), k(a, {
                key: 0,
                title: e.title,
                "search-query": e.searchQuery,
                lang: (s = e.language) == null ? void 0 : s.label
              }, null, 8, ["title", "search-query", "lang"])) : (h(), g("span", {
                key: 1,
                class: "cdx-menu-item__text__label",
                lang: (p = e.language) == null ? void 0 : p.label
              }, [
                B("bdi", null, x(e.title), 1)
              ], 8, St)),
              e.match ? (h(), g(Ie, { key: 2 }, [
                wt,
                e.highlightQuery ? (h(), k(a, {
                  key: 0,
                  title: e.match,
                  "search-query": e.searchQuery,
                  lang: ($ = e.language) == null ? void 0 : $.match
                }, null, 8, ["title", "search-query", "lang"])) : (h(), g("span", {
                  key: 1,
                  class: "cdx-menu-item__text__match",
                  lang: (b = e.language) == null ? void 0 : b.match
                }, [
                  B("bdi", null, x(e.match), 1)
                ], 8, Et))
              ], 64)) : S("", !0),
              e.description ? (h(), g("span", {
                key: 3,
                class: "cdx-menu-item__text__description",
                lang: (_ = e.language) == null ? void 0 : _.description
              }, [
                B("bdi", null, x(e.description), 1)
              ], 8, Dt)) : S("", !0)
            ])
          ];
        }),
        _: 1
      }, 8, ["href"]))
    ])
  ], 42, $t);
}
const Ft = /* @__PURE__ */ L(_t, [["render", kt]]), Mt = T({
  name: "CdxProgressBar",
  props: {
    inline: {
      type: Boolean,
      default: !1
    },
    disabled: {
      type: Boolean,
      default: !1
    }
  },
  setup(e) {
    return {
      rootClasses: f(() => ({
        "cdx-progress-bar--block": !e.inline,
        "cdx-progress-bar--inline": e.inline,
        "cdx-progress-bar--enabled": !e.disabled,
        "cdx-progress-bar--disabled": e.disabled
      }))
    };
  }
});
const xt = ["aria-disabled"], Tt = /* @__PURE__ */ B("div", { class: "cdx-progress-bar__bar" }, null, -1), Lt = [
  Tt
];
function Vt(e, t, n, u, l, r) {
  return h(), g("div", {
    class: F(["cdx-progress-bar", e.rootClasses]),
    role: "progressbar",
    "aria-disabled": e.disabled,
    "aria-valuemin": "0",
    "aria-valuemax": "100"
  }, Lt, 10, xt);
}
const Rt = /* @__PURE__ */ L(Mt, [["render", Vt]]), pe = "cdx", Nt = [
  "default",
  "progressive",
  "destructive"
], Ot = [
  "normal",
  "primary",
  "quiet"
], Kt = [
  "text",
  "search"
], Ee = [
  "default",
  "error"
], qt = 120, Qt = 500, z = "cdx-menu-footer-item";
let me = 0;
function De(e) {
  const t = Oe(), n = (t == null ? void 0 : t.props.id) || (t == null ? void 0 : t.attrs.id);
  return e ? `${pe}-${e}-${me++}` : n ? `${pe}-${n}-${me++}` : `${pe}-${me++}`;
}
function Ut(e, t) {
  const n = C(!1);
  let u = !1;
  if (typeof window != "object" || !("IntersectionObserver" in window && "IntersectionObserverEntry" in window && "intersectionRatio" in window.IntersectionObserverEntry.prototype))
    return n;
  const l = new window.IntersectionObserver(
    (r) => {
      const o = r[0];
      o && (n.value = o.isIntersecting);
    },
    t
  );
  return X(() => {
    u = !0, e.value && l.observe(e.value);
  }), Se(() => {
    u = !1, l.disconnect();
  }), J(e, (r) => {
    !u || (l.disconnect(), n.value = !1, r && l.observe(r));
  }), n;
}
function ie(e, t = f(() => ({}))) {
  const n = f(() => {
    const r = le(t.value, []);
    return e.class && e.class.split(" ").forEach((d) => {
      r[d] = !0;
    }), r;
  }), u = f(() => {
    if ("style" in e)
      return e.style;
  }), l = f(() => {
    const a = e, { class: r, style: o } = a;
    return le(a, ["class", "style"]);
  });
  return {
    rootClasses: n,
    rootStyle: u,
    otherAttrs: l
  };
}
const Ht = T({
  name: "CdxMenu",
  components: {
    CdxMenuItem: Ft,
    CdxProgressBar: Rt
  },
  inheritAttrs: !1,
  props: {
    menuItems: {
      type: Array,
      required: !0
    },
    footer: {
      type: Object,
      default: null
    },
    selected: {
      type: [String, Number, null],
      required: !0
    },
    expanded: {
      type: Boolean,
      required: !0
    },
    showPending: {
      type: Boolean,
      default: !1
    },
    visibleItemLimit: {
      type: Number,
      default: null
    },
    showThumbnail: {
      type: Boolean,
      default: !1
    },
    boldLabel: {
      type: Boolean,
      default: !1
    },
    hideDescriptionOverflow: {
      type: Boolean,
      default: !1
    },
    searchQuery: {
      type: String,
      default: ""
    },
    showNoResultsSlot: {
      type: Boolean,
      default: null
    }
  },
  emits: [
    "update:selected",
    "update:expanded",
    "menu-item-click",
    "menu-item-keyboard-navigation",
    "load-more"
  ],
  expose: [
    "clearActive",
    "getHighlightedMenuItem",
    "delegateKeyNavigation"
  ],
  setup(e, { emit: t, slots: n, attrs: u }) {
    const l = f(() => (e.footer && e.menuItems ? [...e.menuItems, e.footer] : e.menuItems).map((m) => $e(_e({}, m), {
      id: De("menu-item")
    }))), r = f(() => n["no-results"] ? e.showNoResultsSlot !== null ? e.showNoResultsSlot : l.value.length === 0 : !1), o = C(null), d = C(null);
    function a() {
      return l.value.find(
        (i) => i.value === e.selected
      );
    }
    function s(i, m) {
      var y;
      if (!(m && m.disabled))
        switch (i) {
          case "selected":
            t("update:selected", (y = m == null ? void 0 : m.value) != null ? y : null), t("update:expanded", !1), d.value = null;
            break;
          case "highlighted":
            o.value = m || null;
            break;
          case "active":
            d.value = m || null;
            break;
        }
    }
    const p = f(() => {
      if (o.value !== null)
        return l.value.findIndex(
          (i) => i.value === o.value.value
        );
    });
    function $(i) {
      !i || (s("highlighted", i), t("menu-item-keyboard-navigation", i));
    }
    function b(i) {
      var A;
      const m = (j) => {
        for (let q = j - 1; q >= 0; q--)
          if (!l.value[q].disabled)
            return l.value[q];
      };
      i = i || l.value.length;
      const y = (A = m(i)) != null ? A : m(l.value.length);
      $(y);
    }
    function _(i) {
      const m = (A) => l.value.find((j, q) => !j.disabled && q > A);
      i = i != null ? i : -1;
      const y = m(i) || m(-1);
      $(y);
    }
    function V(i, m = !0) {
      function y() {
        t("update:expanded", !0), s("highlighted", a());
      }
      function A() {
        m && (i.preventDefault(), i.stopPropagation());
      }
      switch (i.key) {
        case "Enter":
        case " ":
          return A(), e.expanded ? (o.value && t("update:selected", o.value.value), t("update:expanded", !1)) : y(), !0;
        case "Tab":
          return e.expanded && (o.value && t("update:selected", o.value.value), t("update:expanded", !1)), !0;
        case "ArrowUp":
          return A(), e.expanded ? (o.value === null && s("highlighted", a()), b(p.value)) : y(), O(), !0;
        case "ArrowDown":
          return A(), e.expanded ? (o.value === null && s("highlighted", a()), _(p.value)) : y(), O(), !0;
        case "Home":
          return A(), e.expanded ? (o.value === null && s("highlighted", a()), _()) : y(), O(), !0;
        case "End":
          return A(), e.expanded ? (o.value === null && s("highlighted", a()), b()) : y(), O(), !0;
        case "Escape":
          return A(), t("update:expanded", !1), !0;
        default:
          return !1;
      }
    }
    function E() {
      s("active");
    }
    const v = [], N = C(void 0), re = Ut(
      N,
      { threshold: 0.8 }
    );
    J(re, (i) => {
      i && t("load-more");
    });
    function D(i, m) {
      if (i) {
        v[m] = i.$el;
        const y = e.visibleItemLimit;
        if (!y || e.menuItems.length < y)
          return;
        const A = Math.min(
          y,
          Math.max(2, Math.floor(0.2 * e.menuItems.length))
        );
        m === e.menuItems.length - A && (N.value = i.$el);
      }
    }
    function O() {
      if (!e.visibleItemLimit || e.visibleItemLimit > e.menuItems.length || p.value === void 0)
        return;
      const i = p.value >= 0 ? p.value : 0;
      v[i].scrollIntoView({
        behavior: "smooth",
        block: "nearest"
      });
    }
    const K = C(null), H = C(null);
    function ne() {
      if (H.value = null, !e.visibleItemLimit || v.length <= e.visibleItemLimit) {
        K.value = null;
        return;
      }
      const i = v[0], m = v[e.visibleItemLimit];
      if (K.value = de(
        i,
        m
      ), e.footer) {
        const y = v[v.length - 1];
        H.value = y.scrollHeight;
      }
    }
    function de(i, m) {
      const y = i.getBoundingClientRect().top;
      return m.getBoundingClientRect().top - y + 2;
    }
    X(() => {
      document.addEventListener("mouseup", E);
    }), Se(() => {
      document.removeEventListener("mouseup", E);
    }), J(Y(e, "expanded"), (i) => fe(this, null, function* () {
      const m = a();
      !i && o.value && m === void 0 && s("highlighted"), i && m !== void 0 && s("highlighted", m), i && (yield oe(), ne(), yield oe(), O());
    })), J(Y(e, "menuItems"), (i) => fe(this, null, function* () {
      i.length < v.length && (v.length = i.length), e.expanded && (yield oe(), ne(), yield oe(), O());
    }), { deep: !0 });
    const ce = f(() => ({
      "max-height": K.value ? `${K.value}px` : void 0,
      "overflow-y": K.value ? "scroll" : void 0,
      "margin-bottom": H.value ? `${H.value}px` : void 0
    })), he = f(() => ({
      "cdx-menu--has-footer": !!e.footer,
      "cdx-menu--has-sticky-footer": !!e.footer && !!K.value
    })), {
      rootClasses: P,
      rootStyle: R,
      otherAttrs: ue
    } = ie(u, he);
    return {
      listBoxStyle: ce,
      rootClasses: P,
      rootStyle: R,
      otherAttrs: ue,
      assignTemplateRef: D,
      computedMenuItems: l,
      computedShowNoResultsSlot: r,
      highlightedMenuItem: o,
      activeMenuItem: d,
      handleMenuItemChange: s,
      handleKeyNavigation: V
    };
  },
  methods: {
    getHighlightedMenuItem() {
      return this.highlightedMenuItem;
    },
    clearActive() {
      this.handleMenuItemChange("active");
    },
    delegateKeyNavigation(e, t = !0) {
      return this.handleKeyNavigation(e, t);
    }
  }
});
const Pt = {
  key: 0,
  class: "cdx-menu__pending cdx-menu-item"
}, jt = {
  key: 1,
  class: "cdx-menu__no-results cdx-menu-item"
};
function zt(e, t, n, u, l, r) {
  const o = w("cdx-menu-item"), d = w("cdx-progress-bar");
  return we((h(), g("div", {
    class: F(["cdx-menu", e.rootClasses]),
    style: Z(e.rootStyle)
  }, [
    B("ul", G({
      class: "cdx-menu__listbox",
      role: "listbox",
      "aria-multiselectable": "false",
      style: e.listBoxStyle
    }, e.otherAttrs), [
      e.showPending && e.computedMenuItems.length === 0 && e.$slots.pending ? (h(), g("li", Pt, [
        M(e.$slots, "pending")
      ])) : S("", !0),
      e.computedShowNoResultsSlot ? (h(), g("li", jt, [
        M(e.$slots, "no-results")
      ])) : S("", !0),
      (h(!0), g(Ie, null, Ke(e.computedMenuItems, (a, s) => {
        var p, $;
        return h(), k(o, G({
          key: a.value,
          ref_for: !0,
          ref: (b) => e.assignTemplateRef(b, s)
        }, a, {
          selected: a.value === e.selected,
          active: a.value === ((p = e.activeMenuItem) == null ? void 0 : p.value),
          highlighted: a.value === (($ = e.highlightedMenuItem) == null ? void 0 : $.value),
          "show-thumbnail": e.showThumbnail,
          "bold-label": e.boldLabel,
          "hide-description-overflow": e.hideDescriptionOverflow,
          "search-query": e.searchQuery,
          onChange: (b, _) => e.handleMenuItemChange(b, _ && a),
          onClick: (b) => e.$emit("menu-item-click", a)
        }), {
          default: Q(() => {
            var b, _;
            return [
              M(e.$slots, "default", {
                menuItem: a,
                active: a.value === ((b = e.activeMenuItem) == null ? void 0 : b.value) && a.value === ((_ = e.highlightedMenuItem) == null ? void 0 : _.value)
              })
            ];
          }),
          _: 2
        }, 1040, ["selected", "active", "highlighted", "show-thumbnail", "bold-label", "hide-description-overflow", "search-query", "onChange", "onClick"]);
      }), 128)),
      e.showPending ? (h(), k(d, {
        key: 2,
        class: "cdx-menu__progress-bar",
        inline: !0
      })) : S("", !0)
    ], 16)
  ], 6)), [
    [qe, e.expanded]
  ]);
}
const Wt = /* @__PURE__ */ L(Ht, [["render", zt]]);
function te(e) {
  return (t) => typeof t == "string" && e.indexOf(t) !== -1;
}
const Gt = te(Ot), Xt = te(Nt), Jt = (e) => {
  !e["aria-label"] && !e["aria-hidden"] && Ue(`icon-only buttons require one of the following attribute: aria-label or aria-hidden.
		See documentation on https://doc.wikimedia.org/codex/main/components/button.html#default-icon-only`);
};
function ge(e) {
  const t = [];
  for (const n of e)
    typeof n == "string" && n.trim() !== "" ? t.push(n) : Array.isArray(n) ? t.push(...ge(n)) : typeof n == "object" && n && (typeof n.type == "string" || typeof n.type == "object" ? t.push(n) : n.type !== Qe && (typeof n.children == "string" && n.children.trim() !== "" ? t.push(n.children) : Array.isArray(n.children) && t.push(...ge(n.children))));
  return t;
}
const Yt = (e, t) => {
  if (!e)
    return !1;
  const n = ge(e);
  if (n.length !== 1)
    return !1;
  const u = n[0], l = typeof u == "object" && typeof u.type == "object" && "name" in u.type && u.type.name === ee.name, r = typeof u == "object" && u.type === "svg";
  return l || r ? (Jt(t), !0) : !1;
}, Zt = T({
  name: "CdxButton",
  props: {
    action: {
      type: String,
      default: "default",
      validator: Xt
    },
    type: {
      type: String,
      default: "normal",
      validator: Gt
    }
  },
  emits: ["click"],
  setup(e, { emit: t, slots: n, attrs: u }) {
    const l = C(!1);
    return {
      rootClasses: f(() => {
        var a;
        return {
          [`cdx-button--action-${e.action}`]: !0,
          [`cdx-button--type-${e.type}`]: !0,
          "cdx-button--framed": e.type !== "quiet",
          "cdx-button--icon-only": Yt((a = n.default) == null ? void 0 : a.call(n), u),
          "cdx-button--is-active": l.value
        };
      }),
      onClick: (a) => {
        t("click", a);
      },
      setActive: (a) => {
        l.value = a;
      }
    };
  }
});
function en(e, t, n, u, l, r) {
  return h(), g("button", {
    class: F(["cdx-button", e.rootClasses]),
    onClick: t[0] || (t[0] = (...o) => e.onClick && e.onClick(...o)),
    onKeydown: t[1] || (t[1] = ve((o) => e.setActive(!0), ["space", "enter"])),
    onKeyup: t[2] || (t[2] = ve((o) => e.setActive(!1), ["space", "enter"]))
  }, [
    M(e.$slots, "default")
  ], 34);
}
const tn = /* @__PURE__ */ L(Zt, [["render", en]]);
function ke(e, t, n) {
  return f({
    get: () => e.value,
    set: (u) => t(n || "update:modelValue", u)
  });
}
const nn = te(Kt), un = te(Ee), an = T({
  name: "CdxTextInput",
  components: { CdxIcon: ee },
  inheritAttrs: !1,
  expose: ["focus"],
  props: {
    modelValue: {
      type: [String, Number],
      default: ""
    },
    inputType: {
      type: String,
      default: "text",
      validator: nn
    },
    status: {
      type: String,
      default: "default",
      validator: un
    },
    disabled: {
      type: Boolean,
      default: !1
    },
    startIcon: {
      type: [String, Object],
      default: void 0
    },
    endIcon: {
      type: [String, Object],
      default: void 0
    },
    clearable: {
      type: Boolean,
      default: !1
    }
  },
  emits: [
    "update:modelValue",
    "input",
    "change",
    "keydown",
    "focus",
    "blur"
  ],
  setup(e, { emit: t, attrs: n }) {
    const u = ke(Y(e, "modelValue"), t), l = f(() => e.clearable && !!u.value && !e.disabled), r = f(() => ({
      "cdx-text-input--has-start-icon": !!e.startIcon,
      "cdx-text-input--has-end-icon": !!e.endIcon,
      "cdx-text-input--clearable": l.value
    })), {
      rootClasses: o,
      rootStyle: d,
      otherAttrs: a
    } = ie(n, r), s = f(() => ({
      "cdx-text-input__input--has-value": !!u.value,
      [`cdx-text-input__input--status-${e.status}`]: !0
    }));
    return {
      wrappedModel: u,
      isClearable: l,
      rootClasses: o,
      rootStyle: d,
      otherAttrs: a,
      inputClasses: s,
      onClear: () => {
        u.value = "";
      },
      onInput: (v) => {
        t("input", v);
      },
      onChange: (v) => {
        t("change", v);
      },
      onKeydown: (v) => {
        (v.key === "Home" || v.key === "End") && !v.ctrlKey && !v.metaKey || t("keydown", v);
      },
      onFocus: (v) => {
        t("focus", v);
      },
      onBlur: (v) => {
        t("blur", v);
      },
      cdxIconClear: Je
    };
  },
  methods: {
    focus() {
      this.$refs.input.focus();
    }
  }
});
const ln = ["type", "disabled"];
function on(e, t, n, u, l, r) {
  const o = w("cdx-icon");
  return h(), g("div", {
    class: F(["cdx-text-input", e.rootClasses]),
    style: Z(e.rootStyle)
  }, [
    we(B("input", G({
      ref: "input",
      "onUpdate:modelValue": t[0] || (t[0] = (d) => e.wrappedModel = d),
      class: ["cdx-text-input__input", e.inputClasses]
    }, e.otherAttrs, {
      type: e.inputType,
      disabled: e.disabled,
      onInput: t[1] || (t[1] = (...d) => e.onInput && e.onInput(...d)),
      onChange: t[2] || (t[2] = (...d) => e.onChange && e.onChange(...d)),
      onFocus: t[3] || (t[3] = (...d) => e.onFocus && e.onFocus(...d)),
      onBlur: t[4] || (t[4] = (...d) => e.onBlur && e.onBlur(...d)),
      onKeydown: t[5] || (t[5] = (...d) => e.onKeydown && e.onKeydown(...d))
    }), null, 16, ln), [
      [He, e.wrappedModel]
    ]),
    e.startIcon ? (h(), k(o, {
      key: 0,
      icon: e.startIcon,
      class: "cdx-text-input__icon cdx-text-input__start-icon"
    }, null, 8, ["icon"])) : S("", !0),
    e.endIcon ? (h(), k(o, {
      key: 1,
      icon: e.endIcon,
      class: "cdx-text-input__icon cdx-text-input__end-icon"
    }, null, 8, ["icon"])) : S("", !0),
    e.isClearable ? (h(), k(o, {
      key: 2,
      icon: e.cdxIconClear,
      class: "cdx-text-input__icon cdx-text-input__clear-icon",
      onMousedown: t[6] || (t[6] = ye(() => {
      }, ["prevent"])),
      onClick: e.onClear
    }, null, 8, ["icon", "onClick"])) : S("", !0)
  ], 6);
}
const sn = /* @__PURE__ */ L(an, [["render", on]]), rn = te(Ee), dn = T({
  name: "CdxSearchInput",
  components: {
    CdxButton: tn,
    CdxTextInput: sn
  },
  inheritAttrs: !1,
  props: {
    modelValue: {
      type: [String, Number],
      default: ""
    },
    buttonLabel: {
      type: String,
      default: ""
    },
    status: {
      type: String,
      default: "default",
      validator: rn
    }
  },
  emits: [
    "update:modelValue",
    "submit-click"
  ],
  setup(e, { emit: t, attrs: n }) {
    const u = ke(Y(e, "modelValue"), t), l = f(() => ({
      "cdx-search-input--has-end-button": !!e.buttonLabel
    })), {
      rootClasses: r,
      rootStyle: o,
      otherAttrs: d
    } = ie(n, l);
    return {
      wrappedModel: u,
      rootClasses: r,
      rootStyle: o,
      otherAttrs: d,
      handleSubmit: () => {
        t("submit-click", u.value);
      },
      searchIcon: Ze
    };
  },
  methods: {
    focus() {
      this.$refs.textInput.focus();
    }
  }
});
const cn = { class: "cdx-search-input__input-wrapper" };
function hn(e, t, n, u, l, r) {
  const o = w("cdx-text-input"), d = w("cdx-button");
  return h(), g("div", {
    class: F(["cdx-search-input", e.rootClasses]),
    style: Z(e.rootStyle)
  }, [
    B("div", cn, [
      W(o, G({
        ref: "textInput",
        modelValue: e.wrappedModel,
        "onUpdate:modelValue": t[0] || (t[0] = (a) => e.wrappedModel = a),
        class: "cdx-search-input__text-input",
        "input-type": "search",
        "start-icon": e.searchIcon,
        status: e.status
      }, e.otherAttrs, {
        onKeydown: ve(e.handleSubmit, ["enter"])
      }), null, 16, ["modelValue", "start-icon", "status", "onKeydown"]),
      M(e.$slots, "default")
    ]),
    e.buttonLabel ? (h(), k(d, {
      key: 0,
      class: "cdx-search-input__end-button",
      onClick: e.handleSubmit
    }, {
      default: Q(() => [
        se(x(e.buttonLabel), 1)
      ]),
      _: 1
    }, 8, ["onClick"])) : S("", !0)
  ], 6);
}
const fn = /* @__PURE__ */ L(dn, [["render", hn]]), pn = T({
  name: "CdxTypeaheadSearch",
  components: {
    CdxIcon: ee,
    CdxMenu: Wt,
    CdxSearchInput: fn
  },
  inheritAttrs: !1,
  props: {
    id: {
      type: String,
      required: !0
    },
    formAction: {
      type: String,
      required: !0
    },
    searchResultsLabel: {
      type: String,
      required: !0
    },
    searchResults: {
      type: Array,
      required: !0
    },
    buttonLabel: {
      type: String,
      default: ""
    },
    initialInputValue: {
      type: String,
      default: ""
    },
    searchFooterUrl: {
      type: String,
      default: ""
    },
    debounceInterval: {
      type: Number,
      default: qt
    },
    highlightQuery: {
      type: Boolean,
      default: !1
    },
    showThumbnail: {
      type: Boolean,
      default: !1
    },
    autoExpandWidth: {
      type: Boolean,
      default: !1
    },
    visibleItemLimit: {
      type: Number,
      default: null
    }
  },
  emits: [
    "input",
    "search-result-click",
    "submit",
    "load-more"
  ],
  setup(e, { attrs: t, emit: n, slots: u }) {
    const { searchResults: l, searchFooterUrl: r, debounceInterval: o } = Pe(e), d = C(), a = C(), s = De("typeahead-search-menu"), p = C(!1), $ = C(!1), b = C(!1), _ = C(!1), V = C(e.initialInputValue), E = C(""), v = f(() => {
      var c, I;
      return (I = (c = a.value) == null ? void 0 : c.getHighlightedMenuItem()) == null ? void 0 : I.id;
    }), N = C(null), re = f(() => ({
      "cdx-typeahead-search__menu-message--has-thumbnail": e.showThumbnail
    })), D = f(
      () => e.searchResults.find(
        (c) => c.value === N.value
      )
    ), O = f(
      () => r.value ? { value: z, url: r.value } : void 0
    ), K = f(() => ({
      "cdx-typeahead-search--show-thumbnail": e.showThumbnail,
      "cdx-typeahead-search--expanded": p.value,
      "cdx-typeahead-search--auto-expand-width": e.showThumbnail && e.autoExpandWidth
    })), {
      rootClasses: H,
      rootStyle: ne,
      otherAttrs: de
    } = ie(t, K);
    function ce(c) {
      return c;
    }
    const he = f(() => ({
      visibleItemLimit: e.visibleItemLimit,
      showThumbnail: e.showThumbnail,
      boldLabel: !0,
      hideDescriptionOverflow: !0
    }));
    let P, R;
    function ue(c, I = !1) {
      D.value && D.value.label !== c && D.value.value !== c && (N.value = null), R !== void 0 && (clearTimeout(R), R = void 0), c === "" ? p.value = !1 : ($.value = !0, u["search-results-pending"] && (R = setTimeout(() => {
        _.value && (p.value = !0), b.value = !0;
      }, Qt))), P !== void 0 && (clearTimeout(P), P = void 0);
      const U = () => {
        n("input", c);
      };
      I ? U() : P = setTimeout(() => {
        U();
      }, o.value);
    }
    function i(c) {
      if (c === z) {
        N.value = null, V.value = E.value;
        return;
      }
      N.value = c, c !== null && (V.value = D.value ? D.value.label || String(D.value.value) : "");
    }
    function m() {
      _.value = !0, (E.value || b.value) && (p.value = !0);
    }
    function y() {
      _.value = !1, p.value = !1;
    }
    function A(c) {
      const be = c, { id: I } = be, U = le(be, ["id"]);
      if (U.value === z) {
        n("search-result-click", {
          searchResult: null,
          index: l.value.length,
          numberOfResults: l.value.length
        });
        return;
      }
      j(U);
    }
    function j(c) {
      const I = {
        searchResult: c,
        index: l.value.findIndex(
          (U) => U.value === c.value
        ),
        numberOfResults: l.value.length
      };
      n("search-result-click", I);
    }
    function q(c) {
      if (c.value === z) {
        V.value = E.value;
        return;
      }
      V.value = c.value ? c.label || String(c.value) : "";
    }
    function Fe(c) {
      var I;
      p.value = !1, (I = a.value) == null || I.clearActive(), A(c);
    }
    function Me(c) {
      if (D.value)
        j(D.value), c.stopPropagation(), window.location.assign(D.value.url), c.preventDefault();
      else {
        const I = {
          searchResult: null,
          index: -1,
          numberOfResults: l.value.length
        };
        n("submit", I);
      }
    }
    function xe(c) {
      if (!a.value || !E.value || c.key === " " && p.value)
        return;
      const I = a.value.getHighlightedMenuItem();
      switch (c.key) {
        case "Enter":
          I && (I.value === z ? window.location.assign(r.value) : a.value.delegateKeyNavigation(c, !1)), p.value = !1;
          break;
        case "Tab":
          p.value = !1;
          break;
        default:
          a.value.delegateKeyNavigation(c);
          break;
      }
    }
    return X(() => {
      e.initialInputValue && ue(e.initialInputValue, !0);
    }), J(Y(e, "searchResults"), () => {
      E.value = V.value.trim(), _.value && $.value && E.value.length > 0 && (p.value = !0), R !== void 0 && (clearTimeout(R), R = void 0), $.value = !1, b.value = !1;
    }), {
      form: d,
      menu: a,
      menuId: s,
      highlightedId: v,
      selection: N,
      menuMessageClass: re,
      footer: O,
      asSearchResult: ce,
      inputValue: V,
      searchQuery: E,
      expanded: p,
      showPending: b,
      rootClasses: H,
      rootStyle: ne,
      otherAttrs: de,
      menuConfig: he,
      onUpdateInputValue: ue,
      onUpdateMenuSelection: i,
      onFocus: m,
      onBlur: y,
      onSearchResultClick: A,
      onSearchResultKeyboardNavigation: q,
      onSearchFooterClick: Fe,
      onSubmit: Me,
      onKeydown: xe,
      MenuFooterValue: z,
      articleIcon: Xe
    };
  },
  methods: {
    focus() {
      this.$refs.searchInput.focus();
    }
  }
});
const mn = ["id", "action"], vn = { class: "cdx-typeahead-search__menu-message__text" }, gn = { class: "cdx-typeahead-search__menu-message__text" }, yn = ["href", "onClickCapture"], bn = { class: "cdx-typeahead-search__search-footer__text" }, Cn = { class: "cdx-typeahead-search__search-footer__query" };
function An(e, t, n, u, l, r) {
  const o = w("cdx-icon"), d = w("cdx-menu"), a = w("cdx-search-input");
  return h(), g("div", {
    class: F(["cdx-typeahead-search", e.rootClasses]),
    style: Z(e.rootStyle)
  }, [
    B("form", {
      id: e.id,
      ref: "form",
      class: "cdx-typeahead-search__form",
      action: e.formAction,
      onSubmit: t[4] || (t[4] = (...s) => e.onSubmit && e.onSubmit(...s))
    }, [
      W(a, G({
        ref: "searchInput",
        modelValue: e.inputValue,
        "onUpdate:modelValue": t[3] || (t[3] = (s) => e.inputValue = s),
        "button-label": e.buttonLabel
      }, e.otherAttrs, {
        class: "cdx-typeahead-search__input",
        name: "search",
        role: "combobox",
        autocomplete: "off",
        "aria-autocomplete": "list",
        "aria-owns": e.menuId,
        "aria-expanded": e.expanded,
        "aria-activedescendant": e.highlightedId,
        "onUpdate:modelValue": e.onUpdateInputValue,
        onFocus: e.onFocus,
        onBlur: e.onBlur,
        onKeydown: e.onKeydown
      }), {
        default: Q(() => [
          W(d, G({
            id: e.menuId,
            ref: "menu",
            expanded: e.expanded,
            "onUpdate:expanded": t[0] || (t[0] = (s) => e.expanded = s),
            "show-pending": e.showPending,
            selected: e.selection,
            "menu-items": e.searchResults,
            footer: e.footer,
            "search-query": e.highlightQuery ? e.searchQuery : "",
            "show-no-results-slot": e.searchQuery.length > 0 && e.searchResults.length === 0 && e.$slots["search-no-results-text"] && e.$slots["search-no-results-text"]().length > 0
          }, e.menuConfig, {
            "aria-label": e.searchResultsLabel,
            "onUpdate:selected": e.onUpdateMenuSelection,
            onMenuItemClick: t[1] || (t[1] = (s) => e.onSearchResultClick(e.asSearchResult(s))),
            onMenuItemKeyboardNavigation: e.onSearchResultKeyboardNavigation,
            onLoadMore: t[2] || (t[2] = (s) => e.$emit("load-more"))
          }), {
            pending: Q(() => [
              B("div", {
                class: F(["cdx-typeahead-search__menu-message", e.menuMessageClass])
              }, [
                B("span", vn, [
                  M(e.$slots, "search-results-pending")
                ])
              ], 2)
            ]),
            "no-results": Q(() => [
              B("div", {
                class: F(["cdx-typeahead-search__menu-message", e.menuMessageClass])
              }, [
                B("span", gn, [
                  M(e.$slots, "search-no-results-text")
                ])
              ], 2)
            ]),
            default: Q(({ menuItem: s, active: p }) => [
              s.value === e.MenuFooterValue ? (h(), g("a", {
                key: 0,
                class: F(["cdx-typeahead-search__search-footer", {
                  "cdx-typeahead-search__search-footer__active": p
                }]),
                href: e.asSearchResult(s).url,
                onClickCapture: ye(($) => e.onSearchFooterClick(e.asSearchResult(s)), ["stop"])
              }, [
                W(o, {
                  class: "cdx-typeahead-search__search-footer__icon",
                  icon: e.articleIcon
                }, null, 8, ["icon"]),
                B("span", bn, [
                  M(e.$slots, "search-footer-text", { searchQuery: e.searchQuery }, () => [
                    B("strong", Cn, x(e.searchQuery), 1)
                  ])
                ])
              ], 42, yn)) : S("", !0)
            ]),
            _: 3
          }, 16, ["id", "expanded", "show-pending", "selected", "menu-items", "footer", "search-query", "show-no-results-slot", "aria-label", "onUpdate:selected", "onMenuItemKeyboardNavigation"])
        ]),
        _: 3
      }, 16, ["modelValue", "button-label", "aria-owns", "aria-expanded", "aria-activedescendant", "onUpdate:modelValue", "onFocus", "onBlur", "onKeydown"]),
      M(e.$slots, "default")
    ], 40, mn)
  ], 6);
}
const $n = /* @__PURE__ */ L(pn, [["render", An]]);
export {
  $n as CdxTypeaheadSearch
};
