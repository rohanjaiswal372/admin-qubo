var DEBUG = true;
var BASEURL = "https://dev-admin.qubo.com/";
$(function () {

    $.ajaxSetup({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
    });

    if (window.location.pathname.split("/")[1] != "") {
        $('.sidebar-menu a[href^="/' + window.location.pathname.split("/")[1] + '"]').parents(".treeview-menu").addClass("menu-active").parents(".treeview,li").addClass("active");
    }

    tinymce.init({
        selector: "textarea.editor",
        plugins: [
            "advlist autolink lists link image charmap print preview anchor",
            "searchreplace visualblocks code fullscreen wordcount",
            "insertdatetime media table contextmenu paste"
        ],
        menubar: true,
        toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image source",

    });

    $('.hover').each(function () {
        $(this).hoverdir();
    });
    $('div.alert').not('.alert-important').delay(3000).slideUp(300);

    $('a.colorbox').colorbox({overlay: 0.4});
    $(".clickable-row").click(function() {window.document.location = $(this).data("href");});


    $(".select2").select2();

    $("table.tablesorter").on('search.dt', function (e) {

        var value = $('.dataTables_filter input').val();
        $('.wrapper').unhighlight().highlight(value, {wordsOnly: false}); // highlight selected search term on results

    }).DataTable({searchHighlight: true,  processing: true, stateSave:true, pageLength:15
    ,"lengthMenu": [ [10,12,15, 25, 50, -1], [10,12,15, 25, 50, "All"] ],  paging: true,  colReorder:true});


    if ($('.featuredMarker')) {
        $('table.tablesorter').on('click', '.featuredMarker', function (e) {
            var that = $(this);
            e.preventDefault();
            $.ajax({
                    url: that.attr('href')
                })
                .done(function (msg) {
                    if (msg === 'true') {
                        that.removeClass('red');
                        that.addClass('green');
                    } else {
                        that.removeClass('green');
                        that.addClass('red');
                    }
                });
        })
    }

    $('.colorpicker').colorpicker();

    //Timepicker
    $(".timepicker").timepicker({
        showInputs: true
    });

    $(".datepicker").datepicker();

    $('.datetimepicker').datetimepicker({
        format: 'm/d/Y g:ia'
    });

    $('#start_end_time').daterangepicker({timePicker: true, timePickerIncrement: 30, format: 'MM/DD/YYYY h:mm A', autoApply: true});

    $('#type_id').on('change', function () {
        if ($(this).val() == '2') {
            $('.only-large-banner').fadeIn();
        } else {
            $('.only-large-banner').fadeOut();
        }
    });

    // recipe add
    $('#add-instruction-btn').click(function () {
        $('#append-instructions').append($('#instructions-saver').html());
    });

    $('#add-ingredients-btn').click(function () {
        $('#append-ingredients').append($('#ingredients-saver').html());
    });

    // remove link confirm
    $('.remove-link').click(function () {
        return confirm('Are you sure you want to remove this entry?');
    });

    var sidebar_menu = $('ul.sidebar-menu').html();

    $("input[name='sidebar-search']").blur(function (e) {

        if ($(this).val() == "") {
            $('ul.sidebar-menu').html(sidebar_menu);
            load_search();
        }
    });

    load_search();

});

function debug(title, val) {
    if (DEBUG) {
        console.log("DEBUG::" + title + " : " + val);
        if ($("#debug")) $("#debug").html("<span class='text-danger'>" + title + " : " + val + "</span>");
    }
}

function load_search() {

    $('.treeview').on('click', function () {

        if ($(this).not('active')) {
            $(this).addClass('active').slideDown();
        }
        else if ($(this).has('active')) {
            $(this).removeClass('active').show().children('ul').toggleClass('menu-open').toggle();
        }

    });

    $("input[name='sidebar-search']").keyup(function (e) {

        search_menu($(this).val());

    });

}

function search_menu(input) {
    var searchTerm = input;
    var list = $('.sidebar-menu').children('li');

    if (searchTerm != "") {

        $.extend($.expr[':'], {
            'containsi': function (elem, i, match, array) {
                return (elem.textContent || elem.innerText || '').toLowerCase()
                        .indexOf((match[3] || "").toLowerCase()) >= 0;
            }
        });//end of case insensitive chunk

        var searchSplit = searchTerm.replace(/ /g, "'):containsi('");

        $(list).not(":containsi('" + searchSplit + "')").each(function (e) {

            //add a "hidden" class that will remove the item from the list
            $(this).addClass('hide');

        });

        //this does the opposite -- brings items back into view
        $(".main-sidebar li:containsi('" + searchSplit + "')").each(function (e) {

            //remove the hidden class (reintroduce the item to the list)
            $(this).removeClass('hide');
            $(this).has('ul').children('ul').each(function (e) {

                $(this).addClass('active').show();
            });

        });
    }
    else {
        $(list).each(function (e) {

            $(this).has('treeview').removeClass('active').hide();
        });
        load_search();

    }
}

function _url(path) {
    return window.location.origin + "/" + path;
}


// hover
!function (t, e) {
    "use strict";
    t.HoverDir = function (e, i) {
        this.$el = t(i), this._init(e)
    }, t.HoverDir.defaults = {
        speed: 300,
        easing: "ease",
        hoverDelay: 0,
        inverse: !1
    }, t.HoverDir.prototype = {
        _init: function (e) {
            this.options = t.extend(!0, {}, t.HoverDir.defaults, e), this.transitionProp = "all " + this.options.speed + "ms " + this.options.easing, this.support = Modernizr.csstransitions, this._loadEvents()
        }, _loadEvents: function () {
            var e = this;
            this.$el.on("mouseenter.hoverdir, mouseleave.hoverdir", function (i) {
                var o = t(this), n = o.find("div"), s = e._getDir(o, {x: i.pageX, y: i.pageY}), r = e._getStyle(s);
                "mouseenter" === i.type ? (n.hide().css(r.from), clearTimeout(e.tmhover), e.tmhover = setTimeout(function () {
                    n.show(0, function () {
                        var i = t(this);
                        e.support && i.css("transition", e.transitionProp), e._applyAnimation(i, r.to, e.options.speed)
                    })
                }, e.options.hoverDelay)) : (e.support && n.css("transition", e.transitionProp), clearTimeout(e.tmhover), e._applyAnimation(n, r.from, e.options.speed))
            })
        }, _getDir: function (t, e) {
            var i = t.width(), o = t.height(), n = (e.x - t.offset().left - i / 2) * (i > o ? o / i : 1), s = (e.y - t.offset().top - o / 2) * (o > i ? i / o : 1), r = Math.round((Math.atan2(s, n) * (180 / Math.PI) + 180) / 90 + 3) % 4;
            return r
        }, _getStyle: function (t) {
            var e, i, o = {left: "0px", top: "-100%"}, n = {left: "0px", top: "100%"}, s = {
                left: "-100%",
                top: "0px"
            }, r = {left: "100%", top: "0px"}, a = {top: "0px"}, p = {left: "0px"};
            switch (t) {
                case 0:
                    e = this.options.inverse ? n : o, i = a;
                    break;
                case 1:
                    e = this.options.inverse ? s : r, i = p;
                    break;
                case 2:
                    e = this.options.inverse ? o : n, i = a;
                    break;
                case 3:
                    e = this.options.inverse ? r : s, i = p
            }
            return {from: e, to: i}
        }, _applyAnimation: function (e, i, o) {
            t.fn.applyStyle = this.support ? t.fn.css : t.fn.animate, e.stop().applyStyle(i, t.extend(!0, [], {duration: o + "ms"}))
        }
    };
    var i = function (t) {
        e.console && e.console.error(t)
    };
    t.fn.hoverdir = function (e) {
        var o = t.data(this, "hoverdir");
        if ("string" == typeof e) {
            var n = Array.prototype.slice.call(arguments, 1);
            this.each(function () {
                return o ? t.isFunction(o[e]) && "_" !== e.charAt(0) ? void o[e].apply(o, n) : void i("no such method '" + e + "' for hoverdir instance") : void i("cannot call methods on hoverdir prior to initialization; attempted to call method '" + e + "'")
            })
        } else this.each(function () {
            o ? o._init() : o = t.data(this, "hoverdir", new t.HoverDir(e, this))
        });
        return o
    }
}(jQuery, window);

/* Modernizr 2.6.2 (Custom Build) | MIT & BSD
 * Build: http://modernizr.com/download/#-csstransitions-shiv-cssclasses-testprop-testallprops-domprefixes-load
 */
;
window.Modernizr = function (a, b, c) {
    function x(a) {
        j.cssText = a
    }

    function y(a, b) {
        return x(prefixes.join(a + ";") + (b || ""))
    }

    function z(a, b) {
        return typeof a === b
    }

    function A(a, b) {
        return !!~("" + a).indexOf(b)
    }

    function B(a, b) {
        for (var d in a) {
            var e = a[d];
            if (!A(e, "-") && j[e] !== c)return b == "pfx" ? e : !0
        }
        return !1
    }

    function C(a, b, d) {
        for (var e in a) {
            var f = b[a[e]];
            if (f !== c)return d === !1 ? a[e] : z(f, "function") ? f.bind(d || b) : f
        }
        return !1
    }

    function D(a, b, c) {
        var d = a.charAt(0).toUpperCase() + a.slice(1), e = (a + " " + n.join(d + " ") + d).split(" ");
        return z(b, "string") || z(b, "undefined") ? B(e, b) : (e = (a + " " + o.join(d + " ") + d).split(" "), C(e, b, c))
    }

    var d = "2.6.2", e = {}, f = !0, g = b.documentElement, h = "modernizr", i = b.createElement(h), j = i.style, k, l = {}.toString, m = "Webkit Moz O ms", n = m.split(" "), o = m.toLowerCase().split(" "), p = {}, q = {}, r = {}, s = [], t = s.slice, u, v = {}.hasOwnProperty, w;
    !z(v, "undefined") && !z(v.call, "undefined") ? w = function (a, b) {
        return v.call(a, b)
    } : w = function (a, b) {
        return b in a && z(a.constructor.prototype[b], "undefined")
    }, Function.prototype.bind || (Function.prototype.bind = function (b) {
        var c = this;
        if (typeof c != "function")throw new TypeError;
        var d = t.call(arguments, 1), e = function () {
            if (this instanceof e) {
                var a = function () {
                };
                a.prototype = c.prototype;
                var f = new a, g = c.apply(f, d.concat(t.call(arguments)));
                return Object(g) === g ? g : f
            }
            return c.apply(b, d.concat(t.call(arguments)))
        };
        return e
    }), p.csstransitions = function () {
        return D("transition")
    };
    for (var E in p)w(p, E) && (u = E.toLowerCase(), e[u] = p[E](), s.push((e[u] ? "" : "no-") + u));
    return e.addTest = function (a, b) {
        if (typeof a == "object")for (var d in a)w(a, d) && e.addTest(d, a[d]); else {
            a = a.toLowerCase();
            if (e[a] !== c)return e;
            b = typeof b == "function" ? b() : b, typeof f != "undefined" && f && (g.className += " " + (b ? "" : "no-") + a), e[a] = b
        }
        return e
    }, x(""), i = k = null, function (a, b) {
        function k(a, b) {
            var c = a.createElement("p"), d = a.getElementsByTagName("head")[0] || a.documentElement;
            return c.innerHTML = "x<style>" + b + "</style>", d.insertBefore(c.lastChild, d.firstChild)
        }

        function l() {
            var a = r.elements;
            return typeof a == "string" ? a.split(" ") : a
        }

        function m(a) {
            var b = i[a[g]];
            return b || (b = {}, h++, a[g] = h, i[h] = b), b
        }

        function n(a, c, f) {
            c || (c = b);
            if (j)return c.createElement(a);
            f || (f = m(c));
            var g;
            return f.cache[a] ? g = f.cache[a].cloneNode() : e.test(a) ? g = (f.cache[a] = f.createElem(a)).cloneNode() : g = f.createElem(a), g.canHaveChildren && !d.test(a) ? f.frag.appendChild(g) : g
        }

        function o(a, c) {
            a || (a = b);
            if (j)return a.createDocumentFragment();
            c = c || m(a);
            var d = c.frag.cloneNode(), e = 0, f = l(), g = f.length;
            for (; e < g; e++)d.createElement(f[e]);
            return d
        }

        function p(a, b) {
            b.cache || (b.cache = {}, b.createElem = a.createElement, b.createFrag = a.createDocumentFragment, b.frag = b.createFrag()), a.createElement = function (c) {
                return r.shivMethods ? n(c, a, b) : b.createElem(c)
            }, a.createDocumentFragment = Function("h,f", "return function(){var n=f.cloneNode(),c=n.createElement;h.shivMethods&&(" + l().join().replace(/\w+/g, function (a) {
                    return b.createElem(a), b.frag.createElement(a), 'c("' + a + '")'
                }) + ");return n}")(r, b.frag)
        }

        function q(a) {
            a || (a = b);
            var c = m(a);
            return r.shivCSS && !f && !c.hasCSS && (c.hasCSS = !!k(a, "article,aside,figcaption,figure,footer,header,hgroup,nav,section{display:block}mark{background:#FF0;color:#000}")), j || p(a, c), a
        }

        var c = a.html5 || {}, d = /^<|^(?:button|map|select|textarea|object|iframe|option|optgroup)$/i, e = /^(?:a|b|code|div|fieldset|h1|h2|h3|h4|h5|h6|i|label|li|ol|p|q|span|strong|style|table|tbody|td|th|tr|ul)$/i, f, g = "_html5shiv", h = 0, i = {}, j;
        (function () {
            try {
                var a = b.createElement("a");
                a.innerHTML = "<xyz></xyz>", f = "hidden" in a, j = a.childNodes.length == 1 || function () {
                        b.createElement("a");
                        var a = b.createDocumentFragment();
                        return typeof a.cloneNode == "undefined" || typeof a.createDocumentFragment == "undefined" || typeof a.createElement == "undefined"
                    }()
            } catch (c) {
                f = !0, j = !0
            }
        })();
        var r = {
            elements: c.elements || "abbr article aside audio bdi canvas data datalist details figcaption figure footer header hgroup mark meter nav output progress section summary time video",
            shivCSS: c.shivCSS !== !1,
            supportsUnknownElements: j,
            shivMethods: c.shivMethods !== !1,
            type: "default",
            shivDocument: q,
            createElement: n,
            createDocumentFragment: o
        };
        a.html5 = r, q(b)
    }(this, b), e._version = d, e._domPrefixes = o, e._cssomPrefixes = n, e.testProp = function (a) {
        return B([a])
    }, e.testAllProps = D, g.className = g.className.replace(/(^|\s)no-js(\s|$)/, "$1$2") + (f ? " js " + s.join(" ") : ""), e
}(this, this.document), function (a, b, c) {
    function d(a) {
        return "[object Function]" == o.call(a)
    }

    function e(a) {
        return "string" == typeof a
    }

    function f() {
    }

    function g(a) {
        return !a || "loaded" == a || "complete" == a || "uninitialized" == a
    }

    function h() {
        var a = p.shift();
        q = 1, a ? a.t ? m(function () {
            ("c" == a.t ? B.injectCss : B.injectJs)(a.s, 0, a.a, a.x, a.e, 1)
        }, 0) : (a(), h()) : q = 0
    }

    function i(a, c, d, e, f, i, j) {
        function k(b) {
            if (!o && g(l.readyState) && (u.r = o = 1, !q && h(), l.onload = l.onreadystatechange = null, b)) {
                "img" != a && m(function () {
                    t.removeChild(l)
                }, 50);
                for (var d in y[c])y[c].hasOwnProperty(d) && y[c][d].onload()
            }
        }

        var j = j || B.errorTimeout, l = b.createElement(a), o = 0, r = 0, u = {t: d, s: c, e: f, a: i, x: j};
        1 === y[c] && (r = 1, y[c] = []), "object" == a ? l.data = c : (l.src = c, l.type = a), l.width = l.height = "0", l.onerror = l.onload = l.onreadystatechange = function () {
            k.call(this, r)
        }, p.splice(e, 0, u), "img" != a && (r || 2 === y[c] ? (t.insertBefore(l, s ? null : n), m(k, j)) : y[c].push(l))
    }

    function j(a, b, c, d, f) {
        return q = 0, b = b || "j", e(a) ? i("c" == b ? v : u, a, b, this.i++, c, d, f) : (p.splice(this.i++, 0, a), 1 == p.length && h()), this
    }

    function k() {
        var a = B;
        return a.loader = {load: j, i: 0}, a
    }

    var l = b.documentElement, m = a.setTimeout, n = b.getElementsByTagName("script")[0], o = {}.toString, p = [], q = 0, r = "MozAppearance" in l.style, s = r && !!b.createRange().compareNode, t = s ? l : n.parentNode, l = a.opera && "[object Opera]" == o.call(a.opera), l = !!b.attachEvent && !l, u = r ? "object" : l ? "script" : "img", v = l ? "script" : u, w = Array.isArray || function (a) {
            return "[object Array]" == o.call(a)
        }, x = [], y = {}, z = {
        timeout: function (a, b) {
            return b.length && (a.timeout = b[0]), a
        }
    }, A, B;
    B = function (a) {
        function b(a) {
            var a = a.split("!"), b = x.length, c = a.pop(), d = a.length, c = {
                url: c,
                origUrl: c,
                prefixes: a
            }, e, f, g;
            for (f = 0; f < d; f++)g = a[f].split("="), (e = z[g.shift()]) && (c = e(c, g));
            for (f = 0; f < b; f++)c = x[f](c);
            return c
        }

        function g(a, e, f, g, h) {
            var i = b(a), j = i.autoCallback;
            i.url.split(".").pop().split("?").shift(), i.bypass || (e && (e = d(e) ? e : e[a] || e[g] || e[a.split("/").pop().split("?")[0]]), i.instead ? i.instead(a, e, f, g, h) : (y[i.url] ? i.noexec = !0 : y[i.url] = 1, f.load(i.url, i.forceCSS || !i.forceJS && "css" == i.url.split(".").pop().split("?").shift() ? "c" : c, i.noexec, i.attrs, i.timeout), (d(e) || d(j)) && f.load(function () {
                k(), e && e(i.origUrl, h, g), j && j(i.origUrl, h, g), y[i.url] = 2
            })))
        }

        function h(a, b) {
            function c(a, c) {
                if (a) {
                    if (e(a))c || (j = function () {
                        var a = [].slice.call(arguments);
                        k.apply(this, a), l()
                    }), g(a, j, b, 0, h); else if (Object(a) === a)for (n in m = function () {
                        var b = 0, c;
                        for (c in a)a.hasOwnProperty(c) && b++;
                        return b
                    }(), a)a.hasOwnProperty(n) && (!c && !--m && (d(j) ? j = function () {
                        var a = [].slice.call(arguments);
                        k.apply(this, a), l()
                    } : j[n] = function (a) {
                        return function () {
                            var b = [].slice.call(arguments);
                            a && a.apply(this, b), l()
                        }
                    }(k[n])), g(a[n], j, b, n, h))
                } else!c && l()
            }

            var h = !!a.test, i = a.load || a.both, j = a.callback || f, k = j, l = a.complete || f, m, n;
            c(h ? a.yep : a.nope, !!i), i && c(i)
        }

        var i, j, l = this.yepnope.loader;
        if (e(a))g(a, 0, l, 0); else if (w(a))for (i = 0; i < a.length; i++)j = a[i], e(j) ? g(j, 0, l, 0) : w(j) ? B(j) : Object(j) === j && h(j, l); else Object(a) === a && h(a, l)
    }, B.addPrefix = function (a, b) {
        z[a] = b
    }, B.addFilter = function (a) {
        x.push(a)
    }, B.errorTimeout = 1e4, null == b.readyState && b.addEventListener && (b.readyState = "loading", b.addEventListener("DOMContentLoaded", A = function () {
        b.removeEventListener("DOMContentLoaded", A, 0), b.readyState = "complete"
    }, 0)), a.yepnope = k(), a.yepnope.executeStack = h, a.yepnope.injectJs = function (a, c, d, e, i, j) {
        var k = b.createElement("script"), l, o, e = e || B.errorTimeout;
        k.src = a;
        for (o in d)k.setAttribute(o, d[o]);
        c = j ? h : c || f, k.onreadystatechange = k.onload = function () {
            !l && g(k.readyState) && (l = 1, c(), k.onload = k.onreadystatechange = null)
        }, m(function () {
            l || (l = 1, c(1))
        }, e), i ? k.onload() : n.parentNode.insertBefore(k, n)
    }, a.yepnope.injectCss = function (a, c, d, e, g, i) {
        var e = b.createElement("link"), j, c = i ? h : c || f;
        e.href = a, e.rel = "stylesheet", e.type = "text/css";
        for (j in d)e.setAttribute(j, d[j]);
        g || (n.parentNode.insertBefore(e, n), m(c, 0))
    }
}(this, document), Modernizr.load = function () {
    yepnope.apply(window, [].slice.call(arguments, 0))
};
