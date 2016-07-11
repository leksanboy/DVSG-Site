/*!
 * Cropper v0.10.1
 * https://github.com/fengyuanchen/cropper
 *
 * Copyright (c) 2014-2015 Fengyuan Chen and contributors
 * Released under the MIT license
 *
 * Date: 2015-07-05T10:44:58.203Z
 */
! function(a) {
    "function" == typeof define && define.amd ? define(["jquery"], a) : a("object" == typeof exports ? require("jquery") : jQuery)
}(function(a) {
    "use strict";

    function b(a) {
        return "number" == typeof a && !isNaN(a)
    }

    function c(a) {
        return "undefined" == typeof a
    }

    function d(a, c) {
        var d = [];
        return b(c) && d.push(c), d.slice.apply(a, d)
    }

    function e(a, b) {
        var c = d(arguments, 2);
        return function() {
            return a.apply(b, c.concat(d(arguments)))
        }
    }

    function f(a) {
        var b = a.match(/^(https?:)\/\/([^\:\/\?#]+):?(\d*)/i);
        return b && (b[1] !== n.protocol || b[2] !== n.hostname || b[3] !== n.port)
    }

    function g(a) {
        var b = "timestamp=" + (new Date).getTime();
        return a + (-1 === a.indexOf("?") ? "?" : "&") + b
    }

    function h(a) {
        return a ? "rotate(" + a + "deg)" : "none"
    }

    function i(a, b) {
        var c, d, e = R(a.degree) % 180,
            f = (e > 90 ? 180 - e : e) * Math.PI / 180,
            g = S(f),
            h = T(f),
            i = a.width,
            j = a.height,
            k = a.aspectRatio;
        return b ? (c = i / (h + g / k), d = c / k) : (c = i * h + j * g, d = i * g + j * h), {
            width: c,
            height: d
        }
    }

    function j(b, c) {
        var d = a("<canvas>")[0],
            e = d.getContext("2d"),
            f = c.naturalWidth,
            g = c.naturalHeight,
            h = c.rotate,
            j = i({
                width: f,
                height: g,
                degree: h
            });
        return h ? (d.width = j.width, d.height = j.height, e.save(), e.translate(j.width / 2, j.height / 2), e.rotate(h * Math.PI / 180), e.drawImage(b, -f / 2, -g / 2, f, g), e.restore()) : (d.width = f, d.height = g, e.drawImage(b, 0, 0, f, g)), d
    }

    function k(b, c) {
        this.$element = a(b), this.options = a.extend({}, k.DEFAULTS, a.isPlainObject(c) && c), this.ready = !1, this.built = !1, this.rotated = !1, this.cropped = !1, this.disabled = !1, this.replaced = !1, this.isImg = !1, this.originalUrl = "", this.canvas = null, this.cropBox = null, this.init()
    }
    var l = a(window),
        m = a(document),
        n = window.location,
        o = ".cropper",
        p = "preview" + o,
        q = /^(e|n|w|s|ne|nw|sw|se|all|crop|move|zoom)$/,
        r = "cropper-modal",
        s = "cropper-hide",
        t = "cropper-hidden",
        u = "cropper-invisible",
        v = "cropper-move",
        w = "cropper-crop",
        x = "cropper-disabled",
        y = "cropper-bg",
        z = "mousedown touchstart pointerdown MSPointerDown",
        A = "mousemove touchmove pointermove MSPointerMove",
        B = "mouseup touchend touchcancel pointerup pointercancel MSPointerUp MSPointerCancel",
        C = "wheel mousewheel DOMMouseScroll",
        D = "dblclick",
        E = "resize" + o,
        F = "build" + o,
        G = "built" + o,
        H = "dragstart" + o,
        I = "dragmove" + o,
        J = "dragend" + o,
        K = "zoomin" + o,
        L = "zoomout" + o,
        M = "change" + o,
        N = a.isFunction(a("<canvas>")[0].getContext),
        O = Math.sqrt,
        P = Math.min,
        Q = Math.max,
        R = Math.abs,
        S = Math.sin,
        T = Math.cos,
        U = parseFloat,
        V = {};
    V.init = function() {
        var a, b = this.$element;
        if (b.is("img")) {
            if (this.isImg = !0, this.originalUrl = a = b.attr("src"), !a) return;
            a = b.prop("src")
        } else b.is("canvas") && N && (a = b[0].toDataURL());
        this.load(a)
    }, V.load = function(b) {
        var c, d, e, h, i = this.options,
            j = this.$element;
        b && (e = a.Event(F), j.one(F, i.build).trigger(e), e.isDefaultPrevented() || (i.checkImageOrigin && f(b) && (c = ' crossOrigin="anonymous"', j.prop("crossOrigin") || (d = g(b))), this.$clone = h = a("<img" + (c || "") + ' src="' + (d || b) + '">'), h.one("load", a.proxy(function() {
            var a = h[0],
                c = a.naturalWidth || a.width,
                d = a.naturalHeight || a.height;
            this.image = {
                naturalWidth: c,
                naturalHeight: d,
                aspectRatio: c / d,
                rotate: 0
            }, this.url = b, this.ready = !0, this.build()
        }, this)).one("error", function() {
            h.remove()
        }), h.addClass(s).insertAfter(j)))
    }, V.build = function() {
        var b, c, d, e = this.$element,
            f = this.$clone,
            g = this.options;
        this.ready && (this.built && this.unbuild(), this.$cropper = b = a(k.TEMPLATE), e.addClass(t), f.removeClass(s), this.$container = e.parent().append(b), this.$canvas = b.find(".cropper-canvas").append(f), this.$dragBox = b.find(".cropper-drag-box"), this.$cropBox = c = b.find(".cropper-crop-box"), this.$viewBox = b.find(".cropper-view-box"), this.$face = d = c.find(".cropper-face"), this.addListeners(), this.initPreview(), g.aspectRatio = U(g.aspectRatio) || NaN, g.autoCrop ? (this.cropped = !0, g.modal && this.$dragBox.addClass(r)) : c.addClass(t), g.guides || c.find(".cropper-dashed").addClass(t), g.center || c.find(".cropper-center").addClass(t), g.cropBoxMovable && d.addClass(v).data("drag", "all"), g.highlight || d.addClass(u), g.background && b.addClass(y), g.cropBoxResizable || c.find(".cropper-line, .cropper-point").addClass(t), this.setDragMode(g.dragCrop ? "crop" : g.movable ? "move" : "none"), this.built = !0, this.render(), this.setData(g.data), e.one(G, g.built).trigger(G))
    }, V.unbuild = function() {
        this.built && (this.built = !1, this.initialImage = null, this.initialCanvas = null, this.initialCropBox = null, this.container = null, this.canvas = null, this.cropBox = null, this.removeListeners(), this.resetPreview(), this.$preview = null, this.$viewBox = null, this.$cropBox = null, this.$dragBox = null, this.$canvas = null, this.$container = null, this.$cropper.remove(), this.$cropper = null)
    }, a.extend(V, {
        render: function() {
            this.initContainer(), this.initCanvas(), this.initCropBox(), this.renderCanvas(), this.cropped && this.renderCropBox()
        },
        initContainer: function() {
            var a = this.$element,
                b = this.$container,
                c = this.$cropper,
                d = this.options;
            c.addClass(t), a.removeClass(t), c.css(this.container = {
                width: Q(b.width(), U(d.minContainerWidth) || 200),
                height: Q(b.height(), U(d.minContainerHeight) || 100)
            }), a.addClass(t), c.removeClass(t)
        },
        initCanvas: function() {
            var b = this.container,
                c = b.width,
                d = b.height,
                e = this.image,
                f = e.aspectRatio,
                g = {
                    aspectRatio: f,
                    width: c,
                    height: d
                };
            d * f > c ? g.height = c / f : g.width = d * f, g.oldLeft = g.left = (c - g.width) / 2, g.oldTop = g.top = (d - g.height) / 2, this.canvas = g, this.limitCanvas(!0, !0), this.initialImage = a.extend({}, e), this.initialCanvas = a.extend({}, g)
        },
        limitCanvas: function(b, c) {
            var d, e, f = this.options,
                g = f.strict,
                h = this.container,
                i = h.width,
                j = h.height,
                k = this.canvas,
                l = k.aspectRatio,
                m = this.cropBox,
                n = this.cropped && m,
                o = this.initialCanvas || k,
                p = o.width,
                q = o.height;
            b && (d = U(f.minCanvasWidth) || 0, e = U(f.minCanvasHeight) || 0, d ? (g && (d = Q(n ? m.width : p, d)), e = d / l) : e ? (g && (e = Q(n ? m.height : q, e)), d = e * l) : g && (n ? (d = m.width, e = m.height, e * l > d ? d = e * l : e = d / l) : (d = p, e = q)), a.extend(k, {
                minWidth: d,
                minHeight: e,
                maxWidth: 1 / 0,
                maxHeight: 1 / 0
            })), c && (g ? n ? (k.minLeft = P(m.left, m.left + m.width - k.width), k.minTop = P(m.top, m.top + m.height - k.height), k.maxLeft = m.left, k.maxTop = m.top) : (k.minLeft = P(0, i - k.width), k.minTop = P(0, j - k.height), k.maxLeft = Q(0, i - k.width), k.maxTop = Q(0, j - k.height)) : (k.minLeft = -k.width, k.minTop = -k.height, k.maxLeft = i, k.maxTop = j))
        },
        renderCanvas: function(a) {
            var b, c, d = this.options,
                e = this.canvas,
                f = this.image;
            this.rotated && (this.rotated = !1, c = i({
                width: f.width,
                height: f.height,
                degree: f.rotate
            }), b = c.width / c.height, b !== e.aspectRatio && (e.left -= (c.width - e.width) / 2, e.top -= (c.height - e.height) / 2, e.width = c.width, e.height = c.height, e.aspectRatio = b, this.limitCanvas(!0, !1))), (e.width > e.maxWidth || e.width < e.minWidth) && (e.left = e.oldLeft), (e.height > e.maxHeight || e.height < e.minHeight) && (e.top = e.oldTop), e.width = P(Q(e.width, e.minWidth), e.maxWidth), e.height = P(Q(e.height, e.minHeight), e.maxHeight), this.limitCanvas(!1, !0), e.oldLeft = e.left = P(Q(e.left, e.minLeft), e.maxLeft), e.oldTop = e.top = P(Q(e.top, e.minTop), e.maxTop), this.$canvas.css({
                width: e.width,
                height: e.height,
                left: e.left,
                top: e.top
            }), this.renderImage(), this.cropped && d.strict && this.limitCropBox(!0, !0), a && this.output()
        },
        renderImage: function() {
            var b, c = this.canvas,
                d = this.image;
            d.rotate && (b = i({
                width: c.width,
                height: c.height,
                degree: d.rotate,
                aspectRatio: d.aspectRatio
            }, !0)), a.extend(d, b ? {
                width: b.width,
                height: b.height,
                left: (c.width - b.width) / 2,
                top: (c.height - b.height) / 2
            } : {
                width: c.width,
                height: c.height,
                left: 0,
                top: 0
            }), this.$clone.css({
                width: d.width,
                height: d.height,
                marginLeft: d.left,
                marginTop: d.top,
                transform: h(d.rotate)
            })
        },
        initCropBox: function() {
            var b = this.options,
                c = this.canvas,
                d = b.aspectRatio,
                e = U(b.autoCropArea) || .8,
                f = {
                    width: c.width,
                    height: c.height
                };
            d && (c.height * d > c.width ? f.height = f.width / d : f.width = f.height * d), this.cropBox = f, this.limitCropBox(!0, !0), f.width = P(Q(f.width, f.minWidth), f.maxWidth), f.height = P(Q(f.height, f.minHeight), f.maxHeight), f.width = Q(f.minWidth, f.width * e), f.height = Q(f.minHeight, f.height * e), f.oldLeft = f.left = c.left + (c.width - f.width) / 2, f.oldTop = f.top = c.top + (c.height - f.height) / 2, this.initialCropBox = a.extend({}, f)
        },
        limitCropBox: function(a, b) {
            var c, d, e = this.options,
                f = e.strict,
                g = this.container,
                h = g.width,
                i = g.height,
                j = this.canvas,
                k = this.cropBox,
                l = e.aspectRatio;
            a && (c = U(e.minCropBoxWidth) || 0, d = U(e.minCropBoxHeight) || 0, k.minWidth = P(h, c), k.minHeight = P(i, d), k.maxWidth = P(h, f ? j.width : h), k.maxHeight = P(i, f ? j.height : i), l && (k.maxHeight * l > k.maxWidth ? (k.minHeight = k.minWidth / l, k.maxHeight = k.maxWidth / l) : (k.minWidth = k.minHeight * l, k.maxWidth = k.maxHeight * l)), k.minWidth = P(k.maxWidth, k.minWidth), k.minHeight = P(k.maxHeight, k.minHeight)), b && (f ? (k.minLeft = Q(0, j.left), k.minTop = Q(0, j.top), k.maxLeft = P(h, j.left + j.width) - k.width, k.maxTop = P(i, j.top + j.height) - k.height) : (k.minLeft = 0, k.minTop = 0, k.maxLeft = h - k.width, k.maxTop = i - k.height))
        },
        renderCropBox: function() {
            var a = this.options,
                b = this.container,
                c = b.width,
                d = b.height,
                e = this.cropBox;
            (e.width > e.maxWidth || e.width < e.minWidth) && (e.left = e.oldLeft), (e.height > e.maxHeight || e.height < e.minHeight) && (e.top = e.oldTop), e.width = P(Q(e.width, e.minWidth), e.maxWidth), e.height = P(Q(e.height, e.minHeight), e.maxHeight), this.limitCropBox(!1, !0), e.oldLeft = e.left = P(Q(e.left, e.minLeft), e.maxLeft), e.oldTop = e.top = P(Q(e.top, e.minTop), e.maxTop), a.movable && a.cropBoxMovable && this.$face.data("drag", e.width === c && e.height === d ? "move" : "all"), this.$cropBox.css({
                width: e.width,
                height: e.height,
                left: e.left,
                top: e.top
            }), this.cropped && a.strict && this.limitCanvas(!0, !0), this.disabled || this.output()
        },
        output: function() {
            var a = this.options,
                b = this.$element;
            this.preview(), a.crop && a.crop.call(b, this.getData()), b.trigger(M)
        }
    }), V.initPreview = function() {
        var b = this.url;
        this.$preview = a(this.options.preview), this.$viewBox.html('<img src="' + b + '">'), this.$preview.each(function() {
            var c = a(this);
            c.data(p, {
                width: c.width(),
                height: c.height(),
                original: c.html()
            }).html('<img src="' + b + '" style="display:block;width:100%;min-width:0!important;min-height:0!important;max-width:none!important;max-height:none!important;image-orientation: 0deg!important">')
        })
    }, V.resetPreview = function() {
        this.$preview.each(function() {
            var b = a(this);
            b.html(b.data(p).original).removeData(p)
        })
    }, V.preview = function() {
        var b = this.image,
            c = this.canvas,
            d = this.cropBox,
            e = b.width,
            f = b.height,
            g = d.left - c.left - b.left,
            i = d.top - c.top - b.top,
            j = b.rotate;
        this.cropped && !this.disabled && (this.$viewBox.find("img").css({
            width: e,
            height: f,
            marginLeft: -g,
            marginTop: -i,
            transform: h(j)
        }), this.$preview.each(function() {
            var b = a(this),
                c = b.data(p),
                k = c.width / d.width,
                l = c.width,
                m = d.height * k;
            m > c.height && (k = c.height / d.height, l = d.width * k, m = c.height), b.width(l).height(m).find("img").css({
                width: e * k,
                height: f * k,
                marginLeft: -g * k,
                marginTop: -i * k,
                transform: h(j)
            })
        }))
    }, V.addListeners = function() {
        var b = this.options,
            c = this.$element,
            d = this.$cropper;
        a.isFunction(b.dragstart) && c.on(H, b.dragstart), a.isFunction(b.dragmove) && c.on(I, b.dragmove), a.isFunction(b.dragend) && c.on(J, b.dragend), a.isFunction(b.zoomin) && c.on(K, b.zoomin), a.isFunction(b.zoomout) && c.on(L, b.zoomout), a.isFunction(b.change) && c.on(M, b.change), d.on(z, a.proxy(this.dragstart, this)), b.zoomable && b.mouseWheelZoom && d.on(C, a.proxy(this.wheel, this)), b.doubleClickToggle && d.on(D, a.proxy(this.dblclick, this)), m.on(A, this._dragmove = e(this.dragmove, this)).on(B, this._dragend = e(this.dragend, this)), b.responsive && l.on(E, this._resize = e(this.resize, this))
    }, V.removeListeners = function() {
        var b = this.options,
            c = this.$element,
            d = this.$cropper;
        a.isFunction(b.dragstart) && c.off(H, b.dragstart), a.isFunction(b.dragmove) && c.off(I, b.dragmove), a.isFunction(b.dragend) && c.off(J, b.dragend), a.isFunction(b.zoomin) && c.off(K, b.zoomin), a.isFunction(b.zoomout) && c.off(L, b.zoomout), a.isFunction(b.change) && c.off(M, b.change), d.off(z, this.dragstart), b.zoomable && b.mouseWheelZoom && d.off(C, this.wheel), b.doubleClickToggle && d.off(D, this.dblclick), m.off(A, this._dragmove).off(B, this._dragend), b.responsive && l.off(E, this._resize)
    }, a.extend(V, {
        resize: function() {
            var b, c, d, e = this.$container,
                f = this.container;
            !this.disabled && f && (d = e.width() / f.width, (1 !== d || e.height() !== f.height) && (b = this.getCanvasData(), c = this.getCropBoxData(), this.render(), this.setCanvasData(a.each(b, function(a, c) {
                b[a] = c * d
            })), this.setCropBoxData(a.each(c, function(a, b) {
                c[a] = b * d
            }))))
        },
        dblclick: function() {
            this.disabled || this.setDragMode(this.$dragBox.hasClass(w) ? "move" : "crop")
        },
        wheel: function(a) {
            var b = a.originalEvent,
                c = 1;
            this.disabled || (a.preventDefault(), b.deltaY ? c = b.deltaY > 0 ? 1 : -1 : b.wheelDelta ? c = -b.wheelDelta / 120 : b.detail && (c = b.detail > 0 ? 1 : -1), this.zoom(.1 * -c))
        },
        dragstart: function(b) {
            var c, d, e, f = this.options,
                g = b.originalEvent,
                h = g && g.touches,
                i = b;
            if (!this.disabled) {
                if (h) {
                    if (e = h.length, e > 1) {
                        if (!f.zoomable || !f.touchDragZoom || 2 !== e) return;
                        i = h[1], this.startX2 = i.pageX, this.startY2 = i.pageY, c = "zoom"
                    }
                    i = h[0]
                }
                if (c = c || a(i.target).data("drag"), q.test(c)) {
                    if (b.preventDefault(), d = a.Event(H, {
                        originalEvent: g,
                        dragType: c
                    }), this.$element.trigger(d), d.isDefaultPrevented()) return;
                    this.dragType = c, this.cropping = !1, this.startX = i.pageX, this.startY = i.pageY, "crop" === c && (this.cropping = !0, this.$dragBox.addClass(r))
                }
            }
        },
        dragmove: function(b) {
            var c, d, e = this.options,
                f = b.originalEvent,
                g = f && f.touches,
                h = b,
                i = this.dragType;
            if (!this.disabled) {
                if (g) {
                    if (d = g.length, d > 1) {
                        if (!e.zoomable || !e.touchDragZoom || 2 !== d) return;
                        h = g[1], this.endX2 = h.pageX, this.endY2 = h.pageY
                    }
                    h = g[0]
                }
                if (i) {
                    if (b.preventDefault(), c = a.Event(I, {
                        originalEvent: f,
                        dragType: i
                    }), this.$element.trigger(c), c.isDefaultPrevented()) return;
                    this.endX = h.pageX, this.endY = h.pageY, this.change(h.shiftKey)
                }
            }
        },
        dragend: function(b) {
            var c, d = this.dragType;
            if (!this.disabled && d) {
                if (b.preventDefault(), c = a.Event(J, {
                    originalEvent: b.originalEvent,
                    dragType: d
                }), this.$element.trigger(c), c.isDefaultPrevented()) return;
                this.cropping && (this.cropping = !1, this.$dragBox.toggleClass(r, this.cropped && this.options.modal)), this.dragType = ""
            }
        }
    }), a.extend(V, {
        crop: function() {
            this.built && !this.disabled && (this.cropped || (this.cropped = !0, this.limitCropBox(!0, !0), this.options.modal && this.$dragBox.addClass(r), this.$cropBox.removeClass(t)), this.setCropBoxData(this.initialCropBox))
        },
        reset: function() {
            this.built && !this.disabled && (this.image = a.extend({}, this.initialImage), this.canvas = a.extend({}, this.initialCanvas), this.cropBox = a.extend({}, this.initialCropBox), this.renderCanvas(), this.cropped && this.renderCropBox())
        },
        clear: function() {
            this.cropped && !this.disabled && (a.extend(this.cropBox, {
                left: 0,
                top: 0,
                width: 0,
                height: 0
            }), this.cropped = !1, this.renderCropBox(), this.limitCanvas(), this.renderCanvas(), this.$dragBox.removeClass(r), this.$cropBox.addClass(t))
        },
        destroy: function() {
            var a = this.$element;
            this.ready ? (this.isImg && a.attr("src", this.originalUrl), this.unbuild(), a.removeClass(t)) : this.$clone && this.$clone.remove(), a.removeData("cropper")
        },
        replace: function(a) {
            !this.disabled && a && (this.isImg && this.$element.attr("src", a), this.options.data = null, this.load(a))
        },
        enable: function() {
            this.built && (this.disabled = !1, this.$cropper.removeClass(x))
        },
        disable: function() {
            this.built && (this.disabled = !0, this.$cropper.addClass(x))
        },
        move: function(a, c) {
            var d = this.canvas;
            this.built && !this.disabled && this.options.movable && b(a) && b(c) && (d.left += a, d.top += c, this.renderCanvas(!0))
        },
        zoom: function(b) {
            var c, d, e, f = this.canvas;
            if (b = U(b), b && this.built && !this.disabled && this.options.zoomable) {
                if (c = a.Event(b > 0 ? K : L), this.$element.trigger(c), c.isDefaultPrevented()) return;
                b = -1 >= b ? 1 / (1 - b) : 1 >= b ? 1 + b : b, d = f.width * b, e = f.height * b, f.left -= (d - f.width) / 2, f.top -= (e - f.height) / 2, f.width = d, f.height = e, this.renderCanvas(!0), this.setDragMode("move")
            }
        },
        rotate: function(a) {
            var b = this.image;
            a = U(a), a && this.built && !this.disabled && this.options.rotatable && (b.rotate = (b.rotate + a) % 360, this.rotated = !0, this.renderCanvas(!0))
        },
        getData: function(b) {
            var c, d, e = this.cropBox,
                f = this.canvas,
                g = this.image;
            return this.built && this.cropped ? (d = {
                x: e.left - f.left,
                y: e.top - f.top,
                width: e.width,
                height: e.height
            }, c = g.width / g.naturalWidth, a.each(d, function(a, e) {
                e /= c, d[a] = b ? Math.round(e) : e
            })) : d = {
                x: 0,
                y: 0,
                width: 0,
                height: 0
            }, d.rotate = this.ready ? g.rotate : 0, d
        },
        setData: function(c) {
            var d, e = this.image,
                f = this.canvas,
                g = {};
            this.built && !this.disabled && a.isPlainObject(c) && (b(c.rotate) && c.rotate !== e.rotate && this.options.rotatable && (e.rotate = c.rotate, this.rotated = !0, this.renderCanvas(!0)), d = e.width / e.naturalWidth, b(c.x) && (g.left = c.x * d + f.left), b(c.y) && (g.top = c.y * d + f.top), b(c.width) && (g.width = c.width * d), b(c.height) && (g.height = c.height * d), this.setCropBoxData(g))
        },
        getContainerData: function() {
            return this.built ? this.container : {}
        },
        getImageData: function() {
            return this.ready ? this.image : {}
        },
        getCanvasData: function() {
            var a, b = this.canvas;
            return this.built && (a = {
                left: b.left,
                top: b.top,
                width: b.width,
                height: b.height
            }), a || {}
        },
        setCanvasData: function(c) {
            var d = this.canvas,
                e = d.aspectRatio;
            this.built && !this.disabled && a.isPlainObject(c) && (b(c.left) && (d.left = c.left), b(c.top) && (d.top = c.top), b(c.width) ? (d.width = c.width, d.height = c.width / e) : b(c.height) && (d.height = c.height, d.width = c.height * e), this.renderCanvas(!0))
        },
        getCropBoxData: function() {
            var a, b = this.cropBox;
            return this.built && this.cropped && (a = {
                left: b.left,
                top: b.top,
                width: b.width,
                height: b.height
            }), a || {}
        },
        setCropBoxData: function(c) {
            var d = this.cropBox,
                e = this.options.aspectRatio;
            this.built && this.cropped && !this.disabled && a.isPlainObject(c) && (b(c.left) && (d.left = c.left), b(c.top) && (d.top = c.top), b(c.width) && (d.width = c.width), b(c.height) && (d.height = c.height), e && (b(c.width) ? d.height = d.width / e : b(c.height) && (d.width = d.height * e)), this.renderCropBox())
        },
        getCroppedCanvas: function(b) {
            var c, d, e, f, g, h, i, k, l, m, n;
            if (this.built && this.cropped && N) return a.isPlainObject(b) || (b = {}), n = this.getData(), c = n.width, d = n.height, k = c / d, a.isPlainObject(b) && (g = b.width, h = b.height, g ? (h = g / k, i = g / c) : h && (g = h * k, i = h / d)), e = g || c, f = h || d, l = a("<canvas>")[0], l.width = e, l.height = f, m = l.getContext("2d"), b.fillColor && (m.fillStyle = b.fillColor, m.fillRect(0, 0, e, f)), m.drawImage.apply(m, function() {
                var a, b, e, f, g, h, k = j(this.$clone[0], this.image),
                    l = k.width,
                    m = k.height,
                    o = [k],
                    p = n.x,
                    q = n.y;
                return -c >= p || p > l ? p = a = e = g = 0 : 0 >= p ? (e = -p, p = 0, a = g = P(l, c + p)) : l >= p && (e = 0, a = g = P(c, l - p)), 0 >= a || -d >= q || q > m ? q = b = f = h = 0 : 0 >= q ? (f = -q, q = 0, b = h = P(m, d + q)) : m >= q && (f = 0, b = h = P(d, m - q)), o.push(p, q, a, b), i && (e *= i, f *= i, g *= i, h *= i), g > 0 && h > 0 && o.push(e, f, g, h), o
            }.call(this)), l
        },
        setAspectRatio: function(a) {
            var b = this.options;
            this.disabled || c(a) || (b.aspectRatio = U(a) || NaN, this.built && (this.initCropBox(), this.cropped && this.renderCropBox()))
        },
        setDragMode: function(a) {
            var b, c, d = this.options;
            this.ready && !this.disabled && (b = d.dragCrop && "crop" === a, c = d.movable && "move" === a, a = b || c ? a : "none", this.$dragBox.data("drag", a).toggleClass(w, b).toggleClass(v, c), d.cropBoxMovable || this.$face.data("drag", a).toggleClass(w, b).toggleClass(v, c))
        }
    }), V.change = function(a) {
        var b, c = this.dragType,
            d = this.options,
            e = this.canvas,
            f = this.container,
            g = this.cropBox,
            h = g.width,
            i = g.height,
            j = g.left,
            k = g.top,
            l = j + h,
            m = k + i,
            n = 0,
            o = 0,
            p = f.width,
            q = f.height,
            r = !0,
            s = d.aspectRatio,
            u = {
                x: this.endX - this.startX,
                y: this.endY - this.startY
            };
        switch (!s && a && (s = h && i ? h / i : 1), d.strict && (n = g.minLeft, o = g.minTop, p = n + P(f.width, e.width), q = o + P(f.height, e.height)), s && (u.X = u.y * s, u.Y = u.x / s), c) {
            case "all":
                j += u.x, k += u.y;
                break;
            case "e":
                if (u.x >= 0 && (l >= p || s && (o >= k || m >= q))) {
                    r = !1;
                    break
                }
                h += u.x, s && (i = h / s, k -= u.Y / 2), 0 > h && (c = "w", h = 0);
                break;
            case "n":
                if (u.y <= 0 && (o >= k || s && (n >= j || l >= p))) {
                    r = !1;
                    break
                }
                i -= u.y, k += u.y, s && (h = i * s, j += u.X / 2), 0 > i && (c = "s", i = 0);
                break;
            case "w":
                if (u.x <= 0 && (n >= j || s && (o >= k || m >= q))) {
                    r = !1;
                    break
                }
                h -= u.x, j += u.x, s && (i = h / s, k += u.Y / 2), 0 > h && (c = "e", h = 0);
                break;
            case "s":
                if (u.y >= 0 && (m >= q || s && (n >= j || l >= p))) {
                    r = !1;
                    break
                }
                i += u.y, s && (h = i * s, j -= u.X / 2), 0 > i && (c = "n", i = 0);
                break;
            case "ne":
                if (s) {
                    if (u.y <= 0 && (o >= k || l >= p)) {
                        r = !1;
                        break
                    }
                    i -= u.y, k += u.y, h = i * s
                } else u.x >= 0 ? p > l ? h += u.x : u.y <= 0 && o >= k && (r = !1) : h += u.x, u.y <= 0 ? k > o && (i -= u.y, k += u.y) : (i -= u.y, k += u.y);
                0 > h && 0 > i ? (c = "sw", i = 0, h = 0) : 0 > h ? (c = "nw", h = 0) : 0 > i && (c = "se", i = 0);
                break;
            case "nw":
                if (s) {
                    if (u.y <= 0 && (o >= k || n >= j)) {
                        r = !1;
                        break
                    }
                    i -= u.y, k += u.y, h = i * s, j += u.X
                } else u.x <= 0 ? j > n ? (h -= u.x, j += u.x) : u.y <= 0 && o >= k && (r = !1) : (h -= u.x, j += u.x), u.y <= 0 ? k > o && (i -= u.y, k += u.y) : (i -= u.y, k += u.y);
                0 > h && 0 > i ? (c = "se", i = 0, h = 0) : 0 > h ? (c = "ne", h = 0) : 0 > i && (c = "sw", i = 0);
                break;
            case "sw":
                if (s) {
                    if (u.x <= 0 && (n >= j || m >= q)) {
                        r = !1;
                        break
                    }
                    h -= u.x, j += u.x, i = h / s
                } else u.x <= 0 ? j > n ? (h -= u.x, j += u.x) : u.y >= 0 && m >= q && (r = !1) : (h -= u.x, j += u.x), u.y >= 0 ? q > m && (i += u.y) : i += u.y;
                0 > h && 0 > i ? (c = "ne", i = 0, h = 0) : 0 > h ? (c = "se", h = 0) : 0 > i && (c = "nw", i = 0);
                break;
            case "se":
                if (s) {
                    if (u.x >= 0 && (l >= p || m >= q)) {
                        r = !1;
                        break
                    }
                    h += u.x, i = h / s
                } else u.x >= 0 ? p > l ? h += u.x : u.y >= 0 && m >= q && (r = !1) : h += u.x, u.y >= 0 ? q > m && (i += u.y) : i += u.y;
                0 > h && 0 > i ? (c = "nw", i = 0, h = 0) : 0 > h ? (c = "sw", h = 0) : 0 > i && (c = "ne", i = 0);
                break;
            case "move":
                e.left += u.x, e.top += u.y, this.renderCanvas(!0), r = !1;
                break;
            case "zoom":
                this.zoom(function(a, b, c, d) {
                    var e = O(a * a + b * b),
                        f = O(c * c + d * d);
                    return (f - e) / e
                }(R(this.startX - this.startX2), R(this.startY - this.startY2), R(this.endX - this.endX2), R(this.endY - this.endY2))), this.startX2 = this.endX2, this.startY2 = this.endY2, r = !1;
                break;
            case "crop":
                u.x && u.y && (b = this.$cropper.offset(), j = this.startX - b.left, k = this.startY - b.top, h = g.minWidth, i = g.minHeight, u.x > 0 ? u.y > 0 ? c = "se" : (c = "ne", k -= i) : u.y > 0 ? (c = "sw", j -= h) : (c = "nw", j -= h, k -= i), this.cropped || (this.cropped = !0, this.$cropBox.removeClass(t)))
        }
        r && (g.width = h, g.height = i, g.left = j, g.top = k, this.dragType = c, this.renderCropBox()), this.startX = this.endX, this.startY = this.endY
    }, a.extend(k.prototype, V), k.DEFAULTS = {
        aspectRatio: NaN,
        autoCropArea: .8,
        crop: null,
        data: null,
        preview: "",
        strict: !0,
        responsive: !0,
        checkImageOrigin: !0,
        modal: !0,
        guides: !0,
        center: !0,
        highlight: !0,
        background: !0,
        autoCrop: !0,
        dragCrop: !0,
        movable: !0,
        rotatable: !0,
        zoomable: !0,
        touchDragZoom: !0,
        mouseWheelZoom: !0,
        cropBoxMovable: !0,
        cropBoxResizable: !0,
        doubleClickToggle: !0,
        minCanvasWidth: 0,
        minCanvasHeight: 0,
        minCropBoxWidth: 0,
        minCropBoxHeight: 0,
        minContainerWidth: 200,
        minContainerHeight: 100,
        build: null,
        built: null,
        dragstart: null,
        dragmove: null,
        dragend: null,
        zoomin: null,
        zoomout: null,
        change: null
    }, k.setDefaults = function(b) {
        a.extend(k.DEFAULTS, b)
    }, k.TEMPLATE = function(a, b) {
        return b = b.split(","), a.replace(/\d+/g, function(a) {
            return b[a]
        })
    }('<0 6="5-container"><0 6="5-canvas"></0><0 6="5-2-9"></0><0 6="5-crop-9"><1 6="5-view-9"></1><1 6="5-8 8-h"></1><1 6="5-8 8-v"></1><1 6="5-center"></1><1 6="5-face"></1><1 6="5-7 7-e" 3-2="e"></1><1 6="5-7 7-n" 3-2="n"></1><1 6="5-7 7-w" 3-2="w"></1><1 6="5-7 7-s" 3-2="s"></1><1 6="5-4 4-e" 3-2="e"></1><1 6="5-4 4-n" 3-2="n"></1><1 6="5-4 4-w" 3-2="w"></1><1 6="5-4 4-s" 3-2="s"></1><1 6="5-4 4-ne" 3-2="ne"></1><1 6="5-4 4-nw" 3-2="nw"></1><1 6="5-4 4-sw" 3-2="sw"></1><1 6="5-4 4-se" 3-2="se"></1></0></0>', "div,span,drag,data,point,cropper,class,line,dashed,box"), k.other = a.fn.cropper, a.fn.cropper = function(b) {
        var e, f = d(arguments, 1);
        return this.each(function() {
            var c, d = a(this),
                g = d.data("cropper");
            if (!g) {
                if (/destroy/.test(b)) return;
                d.data("cropper", g = new k(this, b))
            }
            "string" == typeof b && a.isFunction(c = g[b]) && (e = c.apply(g, f))
        }), c(e) ? this : e
    }, a.fn.cropper.Constructor = k, a.fn.cropper.setDefaults = k.setDefaults, a.fn.cropper.noConflict = function() {
        return a.fn.cropper = k.other, this
    }
});