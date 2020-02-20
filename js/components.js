angular.module("ui.bootstrap", ["ui.bootstrap.tpls", "ui.bootstrap.transition", "ui.bootstrap.collapse", "ui.bootstrap.accordion", "ui.bootstrap.alert", "ui.bootstrap.bindHtml", "ui.bootstrap.buttons", "ui.bootstrap.carousel", "ui.bootstrap.dateparser", "ui.bootstrap.position", "ui.bootstrap.datepicker", "ui.bootstrap.dropdown", "ui.bootstrap.modal", "ui.bootstrap.pagination", "ui.bootstrap.tooltip", "ui.bootstrap.popover", "ui.bootstrap.progressbar", "ui.bootstrap.rating", "ui.bootstrap.tabs", "ui.bootstrap.timepicker", "ui.bootstrap.typeahead"]), angular.module("ui.bootstrap.tpls", ["template/accordion/accordion-group.html", "template/accordion/accordion.html", "template/alert/alert.html", "template/carousel/carousel.html", "template/carousel/slide.html", "template/datepicker/datepicker.html", "template/datepicker/day.html", "template/datepicker/month.html", "template/datepicker/popup.html", "template/datepicker/year.html", "template/modal/backdrop.html", "template/modal/window.html", "template/pagination/pager.html", "template/pagination/pagination.html", "template/tooltip/tooltip-html-unsafe-popup.html", "template/tooltip/tooltip-popup.html", "template/popover/popover.html", "template/progressbar/bar.html", "template/progressbar/progress.html", "template/progressbar/progressbar.html", "template/rating/rating.html", "template/tabs/tab.html", "template/tabs/tabset.html", "template/timepicker/timepicker.html", "template/typeahead/typeahead-match.html", "template/typeahead/typeahead-popup.html"]), angular.module("ui.bootstrap.transition", []).factory("$transition", ["$q", "$timeout", "$rootScope", function(a, b, c) {
    function d(a) {
        for (var b in a)
            if (void 0 !== f.style[b]) return a[b]
    }
    var e = function(d, f, g) {
            g = g || {};
            var h = a.defer(),
                i = e[g.animation ? "animationEndEventName" : "transitionEndEventName"],
                j = function() {
                    c.$apply(function() {
                        d.unbind(i, j), h.resolve(d)
                    })
                };
            return i && d.bind(i, j), b(function() {
                angular.isString(f) ? d.addClass(f) : angular.isFunction(f) ? f(d) : angular.isObject(f) && d.css(f), i || h.resolve(d)
            }), h.promise.cancel = function() {
                i && d.unbind(i, j), h.reject("Transition cancelled")
            }, h.promise
        },
        f = document.createElement("trans"),
        g = {
            WebkitTransition: "webkitTransitionEnd",
            MozTransition: "transitionend",
            OTransition: "oTransitionEnd",
            transition: "transitionend"
        },
        h = {
            WebkitTransition: "webkitAnimationEnd",
            MozTransition: "animationend",
            OTransition: "oAnimationEnd",
            transition: "animationend"
        };
    return e.transitionEndEventName = d(g), e.animationEndEventName = d(h), e
}]), angular.module("ui.bootstrap.collapse", ["ui.bootstrap.transition"]).directive("collapse", ["$transition", function(a) {
    return {
        link: function(b, c, d) {
            function e(b) {
                function d() {
                    j === e && (j = void 0)
                }
                var e = a(c, b);
                return j && j.cancel(), j = e, e.then(d, d), e
            }

            function f() {
                k ? (k = !1, g()) : (c.removeClass("collapse").addClass("collapsing"), e({
                    height: c[0].scrollHeight + "px"
                }).then(g))
            }

            function g() {
                c.removeClass("collapsing"), c.addClass("collapse in"), c.css({
                    height: "auto"
                })
            }

            function h() {
                k ? (k = !1, i(), c.css({
                    height: 0
                })) : (c.css({
                    height: c[0].scrollHeight + "px"
                }), c[0].offsetWidth, c.removeClass("collapse in").addClass("collapsing"), e({
                    height: 0
                }).then(i))
            }

            function i() {
                c.removeClass("collapsing"), c.addClass("collapse")
            }
            var j, k = !0;
            b.$watch(d.collapse, function(a) {
                a ? h() : f()
            })
        }
    }
}]), angular.module("ui.bootstrap.accordion", ["ui.bootstrap.collapse"]).constant("accordionConfig", {
    closeOthers: !0
}).controller("AccordionController", ["$scope", "$attrs", "accordionConfig", function(a, b, c) {
    this.groups = [], this.closeOthers = function(d) {
        (angular.isDefined(b.closeOthers) ? a.$eval(b.closeOthers) : c.closeOthers) && angular.forEach(this.groups, function(a) {
            a !== d && (a.isOpen = !1)
        })
    }, this.addGroup = function(a) {
        var b = this;
        this.groups.push(a), a.$on("$destroy", function() {
            b.removeGroup(a)
        })
    }, this.removeGroup = function(a) {
        var b = this.groups.indexOf(a); - 1 !== b && this.groups.splice(b, 1)
    }
}]).directive("accordion", function() {
    return {
        restrict: "EA",
        controller: "AccordionController",
        transclude: !0,
        replace: !1,
        templateUrl: "template/accordion/accordion.html"
    }
}).directive("accordionGroup", function() {
    return {
        require: "^accordion",
        restrict: "EA",
        transclude: !0,
        replace: !0,
        templateUrl: "template/accordion/accordion-group.html",
        scope: {
            heading: "@",
            isOpen: "=?",
            isDisabled: "=?"
        },
        controller: function() {
            this.setHeading = function(a) {
                this.heading = a
            }
        },
        link: function(a, b, c, d) {
            d.addGroup(a), a.$watch("isOpen", function(b) {
                b && d.closeOthers(a)
            }), a.toggleOpen = function() {
                a.isDisabled || (a.isOpen = !a.isOpen)
            }
        }
    }
}).directive("accordionHeading", function() {
    return {
        restrict: "EA",
        transclude: !0,
        template: "",
        replace: !0,
        require: "^accordionGroup",
        link: function(a, b, c, d, e) {
            d.setHeading(e(a, function() {}))
        }
    }
}).directive("accordionTransclude", function() {
    return {
        require: "^accordionGroup",
        link: function(a, b, c, d) {
            a.$watch(function() {
                return d[c.accordionTransclude]
            }, function(a) {
                a && (b.html(""), b.append(a))
            })
        }
    }
}), angular.module("ui.bootstrap.alert", []).controller("AlertController", ["$scope", "$attrs", function(a, b) {
    a.closeable = "close" in b, this.close = a.close
}]).directive("alert", function() {
    return {
        restrict: "EA",
        controller: "AlertController",
        templateUrl: "template/alert/alert.html",
        transclude: !0,
        replace: !0,
        scope: {
            type: "@",
            close: "&"
        }
    }
}).directive("dismissOnTimeout", ["$timeout", function(a) {
    return {
        require: "alert",
        link: function(b, c, d, e) {
            a(function() {
                e.close()
            }, parseInt(d.dismissOnTimeout, 10))
        }
    }
}]), angular.module("ui.bootstrap.bindHtml", []).directive("bindHtmlUnsafe", function() {
    return function(a, b, c) {
        b.addClass("ng-binding").data("$binding", c.bindHtmlUnsafe), a.$watch(c.bindHtmlUnsafe, function(a) {
            b.html(a || "")
        })
    }
}), angular.module("ui.bootstrap.buttons", []).constant("buttonConfig", {
    activeClass: "active",
    toggleEvent: "click"
}).controller("ButtonsController", ["buttonConfig", function(a) {
    this.activeClass = a.activeClass || "active", this.toggleEvent = a.toggleEvent || "click"
}]).directive("btnRadio", function() {
    return {
        require: ["btnRadio", "ngModel"],
        controller: "ButtonsController",
        link: function(a, b, c, d) {
            var e = d[0],
                f = d[1];
            f.$render = function() {
                b.toggleClass(e.activeClass, angular.equals(f.$modelValue, a.$eval(c.btnRadio)))
            }, b.bind(e.toggleEvent, function() {
                var d = b.hasClass(e.activeClass);
                (!d || angular.isDefined(c.uncheckable)) && a.$apply(function() {
                    f.$setViewValue(d ? null : a.$eval(c.btnRadio)), f.$render()
                })
            })
        }
    }
}).directive("btnCheckbox", function() {
    return {
        require: ["btnCheckbox", "ngModel"],
        controller: "ButtonsController",
        link: function(a, b, c, d) {
            function e() {
                return g(c.btnCheckboxTrue, !0)
            }

            function f() {
                return g(c.btnCheckboxFalse, !1)
            }

            function g(b, c) {
                var d = a.$eval(b);
                return angular.isDefined(d) ? d : c
            }
            var h = d[0],
                i = d[1];
            i.$render = function() {
                b.toggleClass(h.activeClass, angular.equals(i.$modelValue, e()))
            }, b.bind(h.toggleEvent, function() {
                a.$apply(function() {
                    i.$setViewValue(b.hasClass(h.activeClass) ? f() : e()), i.$render()
                })
            })
        }
    }
}), angular.module("ui.bootstrap.carousel", ["ui.bootstrap.transition"]).controller("CarouselController", ["$scope", "$timeout", "$interval", "$transition", function(a, b, c, d) {
    function e() {
        f();
        var b = +a.interval;
        !isNaN(b) && b > 0 && (h = c(g, b))
    }

    function f() {
        h && (c.cancel(h), h = null)
    }

    function g() {
        var b = +a.interval;
        i && !isNaN(b) && b > 0 ? a.next() : a.pause()
    }
    var h, i, j = this,
        k = j.slides = a.slides = [],
        l = -1;
    j.currentSlide = null;
    var m = !1;
    j.select = a.select = function(c, f) {
        function g() {
            m || (j.currentSlide && angular.isString(f) && !a.noTransition && c.$element ? (c.$element.addClass(f), c.$element[0].offsetWidth, angular.forEach(k, function(a) {
                angular.extend(a, {
                    direction: "",
                    entering: !1,
                    leaving: !1,
                    active: !1
                })
            }), angular.extend(c, {
                direction: f,
                active: !0,
                entering: !0
            }), angular.extend(j.currentSlide || {}, {
                direction: f,
                leaving: !0
            }), a.$currentTransition = d(c.$element, {}), function(b, c) {
                a.$currentTransition.then(function() {
                    h(b, c)
                }, function() {
                    h(b, c)
                })
            }(c, j.currentSlide)) : h(c, j.currentSlide), j.currentSlide = c, l = i, e())
        }

        function h(b, c) {
            angular.extend(b, {
                direction: "",
                active: !0,
                leaving: !1,
                entering: !1
            }), angular.extend(c || {}, {
                direction: "",
                active: !1,
                leaving: !1,
                entering: !1
            }), a.$currentTransition = null
        }
        var i = k.indexOf(c);
        void 0 === f && (f = i > l ? "next" : "prev"), c && c !== j.currentSlide && (a.$currentTransition ? (a.$currentTransition.cancel(), b(g)) : g())
    }, a.$on("$destroy", function() {
        m = !0
    }), j.indexOfSlide = function(a) {
        return k.indexOf(a)
    }, a.next = function() {
        var b = (l + 1) % k.length;
        return a.$currentTransition ? void 0 : j.select(k[b], "next")
    }, a.prev = function() {
        var b = 0 > l - 1 ? k.length - 1 : l - 1;
        return a.$currentTransition ? void 0 : j.select(k[b], "prev")
    }, a.isActive = function(a) {
        return j.currentSlide === a
    }, a.$watch("interval", e), a.$on("$destroy", f), a.play = function() {
        i || (i = !0, e())
    }, a.pause = function() {
        a.noPause || (i = !1, f())
    }, j.addSlide = function(b, c) {
        b.$element = c, k.push(b), 1 === k.length || b.active ? (j.select(k[k.length - 1]), 1 == k.length && a.play()) : b.active = !1
    }, j.removeSlide = function(a) {
        var b = k.indexOf(a);
        k.splice(b, 1), k.length > 0 && a.active ? j.select(b >= k.length ? k[b - 1] : k[b]) : l > b && l--
    }
}]).directive("carousel", [function() {
    return {
        restrict: "EA",
        transclude: !0,
        replace: !0,
        controller: "CarouselController",
        require: "carousel",
        templateUrl: "template/carousel/carousel.html",
        scope: {
            interval: "=",
            noTransition: "=",
            noPause: "="
        }
    }
}]).directive("slide", function() {
    return {
        require: "^carousel",
        restrict: "EA",
        transclude: !0,
        replace: !0,
        templateUrl: "template/carousel/slide.html",
        scope: {
            active: "=?"
        },
        link: function(a, b, c, d) {
            d.addSlide(a, b), a.$on("$destroy", function() {
                d.removeSlide(a)
            }), a.$watch("active", function(b) {
                b && d.select(a)
            })
        }
    }
}), angular.module("ui.bootstrap.dateparser", []).service("dateParser", ["$locale", "orderByFilter", function(a, b) {
    function c(a) {
        var c = [],
            d = a.split("");
        return angular.forEach(e, function(b, e) {
            var f = a.indexOf(e);
            if (f > -1) {
                a = a.split(""), d[f] = "(" + b.regex + ")", a[f] = "$";
                for (var g = f + 1, h = f + e.length; h > g; g++) d[g] = "", a[g] = "$";
                a = a.join(""), c.push({
                    index: f,
                    apply: b.apply
                })
            }
        }), {
            regex: new RegExp("^" + d.join("") + "$"),
            map: b(c, "index")
        }
    }

    function d(a, b, c) {
        return 1 === b && c > 28 ? 29 === c && (a % 4 == 0 && a % 100 != 0 || a % 400 == 0) : 3 !== b && 5 !== b && 8 !== b && 10 !== b || 31 > c
    }
    this.parsers = {};
    var e = {
        yyyy: {
            regex: "\\d{4}",
            apply: function(a) {
                this.year = +a
            }
        },
        yy: {
            regex: "\\d{2}",
            apply: function(a) {
                this.year = +a + 2e3
            }
        },
        y: {
            regex: "\\d{1,4}",
            apply: function(a) {
                this.year = +a
            }
        },
        MMMM: {
            regex: a.DATETIME_FORMATS.MONTH.join("|"),
            apply: function(b) {
                this.month = a.DATETIME_FORMATS.MONTH.indexOf(b)
            }
        },
        MMM: {
            regex: a.DATETIME_FORMATS.SHORTMONTH.join("|"),
            apply: function(b) {
                this.month = a.DATETIME_FORMATS.SHORTMONTH.indexOf(b)
            }
        },
        MM: {
            regex: "0[1-9]|1[0-2]",
            apply: function(a) {
                this.month = a - 1
            }
        },
        M: {
            regex: "[1-9]|1[0-2]",
            apply: function(a) {
                this.month = a - 1
            }
        },
        dd: {
            regex: "[0-2][0-9]{1}|3[0-1]{1}",
            apply: function(a) {
                this.date = +a
            }
        },
        d: {
            regex: "[1-2]?[0-9]{1}|3[0-1]{1}",
            apply: function(a) {
                this.date = +a
            }
        },
        EEEE: {
            regex: a.DATETIME_FORMATS.DAY.join("|")
        },
        EEE: {
            regex: a.DATETIME_FORMATS.SHORTDAY.join("|")
        }
    };
    this.parse = function(b, e) {
        if (!angular.isString(b) || !e) return b;
        e = a.DATETIME_FORMATS[e] || e, this.parsers[e] || (this.parsers[e] = c(e));
        var f = this.parsers[e],
            g = f.regex,
            h = f.map,
            i = b.match(g);
        if (i && i.length) {
            for (var j, k = {
                    year: 1900,
                    month: 0,
                    date: 1,
                    hours: 0
                }, l = 1, m = i.length; m > l; l++) {
                var n = h[l - 1];
                n.apply && n.apply.call(k, i[l])
            }
            return d(k.year, k.month, k.date) && (j = new Date(k.year, k.month, k.date, k.hours)), j
        }
    }
}]), angular.module("ui.bootstrap.position", []).factory("$position", ["$document", "$window", function(a, b) {
    function c(a, c) {
        return a.currentStyle ? a.currentStyle[c] : b.getComputedStyle ? b.getComputedStyle(a)[c] : a.style[c]
    }

    function d(a) {
        return "static" === (c(a, "position") || "static")
    }
    var e = function(b) {
        for (var c = a[0], e = b.offsetParent || c; e && e !== c && d(e);) e = e.offsetParent;
        return e || c
    };
    return {
        position: function(b) {
            var c = this.offset(b),
                d = {
                    top: 0,
                    left: 0
                },
                f = e(b[0]);
            f != a[0] && (d = this.offset(angular.element(f)), d.top += f.clientTop - f.scrollTop, d.left += f.clientLeft - f.scrollLeft);
            var g = b[0].getBoundingClientRect();
            return {
                width: g.width || b.prop("offsetWidth"),
                height: g.height || b.prop("offsetHeight"),
                top: c.top - d.top,
                left: c.left - d.left
            }
        },
        offset: function(c) {
            var d = c[0].getBoundingClientRect();
            return {
                width: d.width || c.prop("offsetWidth"),
                height: d.height || c.prop("offsetHeight"),
                top: d.top + (b.pageYOffset || a[0].documentElement.scrollTop),
                left: d.left + (b.pageXOffset || a[0].documentElement.scrollLeft)
            }
        },
        positionElements: function(a, b, c, d) {
            var e, f, g, h, i = c.split("-"),
                j = i[0],
                k = i[1] || "center";
            e = d ? this.offset(a) : this.position(a), f = b.prop("offsetWidth"), g = b.prop("offsetHeight");
            var l = {
                    center: function() {
                        return e.left + e.width / 2 - f / 2
                    },
                    left: function() {
                        return e.left
                    },
                    right: function() {
                        return e.left + e.width
                    }
                },
                m = {
                    center: function() {
                        return e.top + e.height / 2 - g / 2
                    },
                    top: function() {
                        return e.top
                    },
                    bottom: function() {
                        return e.top + e.height
                    }
                };
            switch (j) {
                case "right":
                    h = {
                        top: m[k](),
                        left: l[j]()
                    };
                    break;
                case "left":
                    h = {
                        top: m[k](),
                        left: e.left - f
                    };
                    break;
                case "bottom":
                    h = {
                        top: m[j](),
                        left: l[k]()
                    };
                    break;
                default:
                    h = {
                        top: e.top - g,
                        left: l[k]()
                    }
            }
            return h
        }
    }
}]), angular.module("ui.bootstrap.datepicker", ["ui.bootstrap.dateparser", "ui.bootstrap.position"]).constant("datepickerConfig", {
    formatDay: "dd",
    formatMonth: "MMMM",
    formatYear: "yyyy",
    formatDayHeader: "EEE",
    formatDayTitle: "MMMM yyyy",
    formatMonthTitle: "yyyy",
    datepickerMode: "day",
    minMode: "day",
    maxMode: "year",
    showWeeks: !0,
    startingDay: 0,
    yearRange: 20,
    minDate: null,
    maxDate: null
}).controller("DatepickerController", ["$scope", "$attrs", "$parse", "$interpolate", "$timeout", "$log", "dateFilter", "datepickerConfig", function(a, b, c, d, e, f, g, h) {
    var i = this,
        j = {
            $setViewValue: angular.noop
        };
    this.modes = ["day", "month", "year"], angular.forEach(["formatDay", "formatMonth", "formatYear", "formatDayHeader", "formatDayTitle", "formatMonthTitle", "minMode", "maxMode", "showWeeks", "startingDay", "yearRange"], function(c, e) {
        i[c] = angular.isDefined(b[c]) ? 8 > e ? d(b[c])(a.$parent) : a.$parent.$eval(b[c]) : h[c]
    }), angular.forEach(["minDate", "maxDate"], function(d) {
        b[d] ? a.$parent.$watch(c(b[d]), function(a) {
            i[d] = a ? new Date(a) : null, i.refreshView()
        }) : i[d] = h[d] ? new Date(h[d]) : null
    }), a.datepickerMode = a.datepickerMode || h.datepickerMode, a.uniqueId = "datepicker-" + a.$id + "-" + Math.floor(1e4 * Math.random()), this.activeDate = angular.isDefined(b.initDate) ? a.$parent.$eval(b.initDate) : new Date, a.isActive = function(b) {
        return 0 === i.compare(b.date, i.activeDate) && (a.activeDateId = b.uid, !0)
    }, this.init = function(a) {
        j = a, j.$render = function() {
            i.render()
        }
    }, this.render = function() {
        if (j.$modelValue) {
            var a = new Date(j.$modelValue),
                b = !isNaN(a);
            b ? this.activeDate = a : f.error('Datepicker directive: "ng-model" value must be a Date object, a number of milliseconds since 01.01.1970 or a string representing an RFC2822 or ISO 8601 date.'), j.$setValidity("date", b)
        }
        this.refreshView()
    }, this.refreshView = function() {
        if (this.element) {
            this._refreshView();
            var a = j.$modelValue ? new Date(j.$modelValue) : null;
            j.$setValidity("date-disabled", !a || this.element && !this.isDisabled(a))
        }
    }, this.createDateObject = function(a, b) {
        var c = j.$modelValue ? new Date(j.$modelValue) : null;
        return {
            date: a,
            label: g(a, b),
            selected: c && 0 === this.compare(a, c),
            disabled: this.isDisabled(a),
            current: 0 === this.compare(a, new Date)
        }
    }, this.isDisabled = function(c) {
        return this.minDate && this.compare(c, this.minDate) < 0 || this.maxDate && this.compare(c, this.maxDate) > 0 || b.dateDisabled && a.dateDisabled({
            date: c,
            mode: a.datepickerMode
        })
    }, this.split = function(a, b) {
        for (var c = []; a.length > 0;) c.push(a.splice(0, b));
        return c
    }, a.select = function(b) {
        if (a.datepickerMode === i.minMode) {
            var c = j.$modelValue ? new Date(j.$modelValue) : new Date(0, 0, 0, 0, 0, 0, 0);
            c.setFullYear(b.getFullYear(), b.getMonth(), b.getDate()), j.$setViewValue(c), j.$render()
        } else i.activeDate = b, a.datepickerMode = i.modes[i.modes.indexOf(a.datepickerMode) - 1]
    }, a.move = function(a) {
        var b = i.activeDate.getFullYear() + a * (i.step.years || 0),
            c = i.activeDate.getMonth() + a * (i.step.months || 0);
        i.activeDate.setFullYear(b, c, 1), i.refreshView()
    }, a.toggleMode = function(b) {
        b = b || 1, a.datepickerMode === i.maxMode && 1 === b || a.datepickerMode === i.minMode && -1 === b || (a.datepickerMode = i.modes[i.modes.indexOf(a.datepickerMode) + b])
    }, a.keys = {
        13: "enter",
        32: "space",
        33: "pageup",
        34: "pagedown",
        35: "end",
        36: "home",
        37: "left",
        38: "up",
        39: "right",
        40: "down"
    };
    var k = function() {
        e(function() {
            i.element[0].focus()
        }, 0, !1)
    };
    a.$on("datepicker.focus", k), a.keydown = function(b) {
        var c = a.keys[b.which];
        if (c && !b.shiftKey && !b.altKey)
            if (b.preventDefault(), b.stopPropagation(), "enter" === c || "space" === c) {
                if (i.isDisabled(i.activeDate)) return;
                a.select(i.activeDate), k()
            } else !b.ctrlKey || "up" !== c && "down" !== c ? (i.handleKeyDown(c, b), i.refreshView()) : (a.toggleMode("up" === c ? 1 : -1), k())
    }
}]).directive("datepicker", function() {
    return {
        restrict: "EA",
        replace: !0,
        templateUrl: "template/datepicker/datepicker.html",
        scope: {
            datepickerMode: "=?",
            dateDisabled: "&"
        },
        require: ["datepicker", "?^ngModel"],
        controller: "DatepickerController",
        link: function(a, b, c, d) {
            var e = d[0],
                f = d[1];
            f && e.init(f)
        }
    }
}).directive("daypicker", ["dateFilter", function(a) {
    return {
        restrict: "EA",
        replace: !0,
        templateUrl: "template/datepicker/day.html",
        require: "^datepicker",
        link: function(b, c, d, e) {
            function f(a, b) {
                return 1 !== b || a % 4 != 0 || a % 100 == 0 && a % 400 != 0 ? i[b] : 29
            }

            function g(a, b) {
                var c = new Array(b),
                    d = new Date(a),
                    e = 0;
                for (d.setHours(12); b > e;) c[e++] = new Date(d), d.setDate(d.getDate() + 1);
                return c
            }

            function h(a) {
                var b = new Date(a);
                b.setDate(b.getDate() + 4 - (b.getDay() || 7));
                var c = b.getTime();
                return b.setMonth(0), b.setDate(1), Math.floor(Math.round((c - b) / 864e5) / 7) + 1
            }
            b.showWeeks = e.showWeeks, e.step = {
                months: 1
            }, e.element = c;
            var i = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
            e._refreshView = function() {
                var c = e.activeDate.getFullYear(),
                    d = e.activeDate.getMonth(),
                    f = new Date(c, d, 1),
                    i = e.startingDay - f.getDay(),
                    j = i > 0 ? 7 - i : -i,
                    k = new Date(f);
                j > 0 && k.setDate(1 - j);
                for (var l = g(k, 42), m = 0; 42 > m; m++) l[m] = angular.extend(e.createDateObject(l[m], e.formatDay), {
                    secondary: l[m].getMonth() !== d,
                    uid: b.uniqueId + "-" + m
                });
                b.labels = new Array(7);
                for (var n = 0; 7 > n; n++) b.labels[n] = {
                    abbr: a(l[n].date, e.formatDayHeader),
                    full: a(l[n].date, "EEEE")
                };
                if (b.title = a(e.activeDate, e.formatDayTitle), b.rows = e.split(l, 7), b.showWeeks) {
                    b.weekNumbers = [];
                    for (var o = h(b.rows[0][0].date), p = b.rows.length; b.weekNumbers.push(o++) < p;);
                }
            }, e.compare = function(a, b) {
                return new Date(a.getFullYear(), a.getMonth(), a.getDate()) - new Date(b.getFullYear(), b.getMonth(), b.getDate())
            }, e.handleKeyDown = function(a) {
                var b = e.activeDate.getDate();
                if ("left" === a) b -= 1;
                else if ("up" === a) b -= 7;
                else if ("right" === a) b += 1;
                else if ("down" === a) b += 7;
                else if ("pageup" === a || "pagedown" === a) {
                    var c = e.activeDate.getMonth() + ("pageup" === a ? -1 : 1);
                    e.activeDate.setMonth(c, 1), b = Math.min(f(e.activeDate.getFullYear(), e.activeDate.getMonth()), b)
                } else "home" === a ? b = 1 : "end" === a && (b = f(e.activeDate.getFullYear(), e.activeDate.getMonth()));
                e.activeDate.setDate(b)
            }, e.refreshView()
        }
    }
}]).directive("monthpicker", ["dateFilter", function(a) {
    return {
        restrict: "EA",
        replace: !0,
        templateUrl: "template/datepicker/month.html",
        require: "^datepicker",
        link: function(b, c, d, e) {
            e.step = {
                years: 1
            }, e.element = c, e._refreshView = function() {
                for (var c = new Array(12), d = e.activeDate.getFullYear(), f = 0; 12 > f; f++) c[f] = angular.extend(e.createDateObject(new Date(d, f, 1), e.formatMonth), {
                    uid: b.uniqueId + "-" + f
                });
                b.title = a(e.activeDate, e.formatMonthTitle), b.rows = e.split(c, 3)
            }, e.compare = function(a, b) {
                return new Date(a.getFullYear(), a.getMonth()) - new Date(b.getFullYear(), b.getMonth())
            }, e.handleKeyDown = function(a) {
                var b = e.activeDate.getMonth();
                if ("left" === a) b -= 1;
                else if ("up" === a) b -= 3;
                else if ("right" === a) b += 1;
                else if ("down" === a) b += 3;
                else if ("pageup" === a || "pagedown" === a) {
                    var c = e.activeDate.getFullYear() + ("pageup" === a ? -1 : 1);
                    e.activeDate.setFullYear(c)
                } else "home" === a ? b = 0 : "end" === a && (b = 11);
                e.activeDate.setMonth(b)
            }, e.refreshView()
        }
    }
}]).directive("yearpicker", ["dateFilter", function() {
    return {
        restrict: "EA",
        replace: !0,
        templateUrl: "template/datepicker/year.html",
        require: "^datepicker",
        link: function(a, b, c, d) {
            function e(a) {
                return parseInt((a - 1) / f, 10) * f + 1
            }
            var f = d.yearRange;
            d.step = {
                years: f
            }, d.element = b, d._refreshView = function() {
                for (var b = new Array(f), c = 0, g = e(d.activeDate.getFullYear()); f > c; c++) b[c] = angular.extend(d.createDateObject(new Date(g + c, 0, 1), d.formatYear), {
                    uid: a.uniqueId + "-" + c
                });
                a.title = [b[0].label, b[f - 1].label].join(" - "), a.rows = d.split(b, 5)
            }, d.compare = function(a, b) {
                return a.getFullYear() - b.getFullYear()
            }, d.handleKeyDown = function(a) {
                var b = d.activeDate.getFullYear();
                "left" === a ? b -= 1 : "up" === a ? b -= 5 : "right" === a ? b += 1 : "down" === a ? b += 5 : "pageup" === a || "pagedown" === a ? b += ("pageup" === a ? -1 : 1) * d.step.years : "home" === a ? b = e(d.activeDate.getFullYear()) : "end" === a && (b = e(d.activeDate.getFullYear()) + f - 1), d.activeDate.setFullYear(b)
            }, d.refreshView()
        }
    }
}]).constant("datepickerPopupConfig", {
    datepickerPopup: "yyyy-MM-dd",
    currentText: "Today",
    clearText: "Clear",
    closeText: "Done",
    closeOnDateSelection: !0,
    appendToBody: !1,
    showButtonBar: !0
}).directive("datepickerPopup", ["$compile", "$parse", "$document", "$position", "dateFilter", "dateParser", "datepickerPopupConfig", function(a, b, c, d, e, f, g) {
    return {
        restrict: "EA",
        require: "ngModel",
        scope: {
            isOpen: "=?",
            currentText: "@",
            clearText: "@",
            closeText: "@",
            dateDisabled: "&"
        },
        link: function(h, i, j, k) {
            function l(a) {
                return a.replace(/([A-Z])/g, function(a) {
                    return "-" + a.toLowerCase()
                })
            }

            function m(a) {
                if (a) {
                    if (angular.isDate(a) && !isNaN(a)) return k.$setValidity("date", !0), a;
                    if (angular.isString(a)) {
                        var b = f.parse(a, n) || new Date(a);
                        return isNaN(b) ? void k.$setValidity("date", !1) : (k.$setValidity("date", !0), b)
                    }
                    return void k.$setValidity("date", !1)
                }
                return k.$setValidity("date", !0), null
            }
            var n, o = angular.isDefined(j.closeOnDateSelection) ? h.$parent.$eval(j.closeOnDateSelection) : g.closeOnDateSelection,
                p = angular.isDefined(j.datepickerAppendToBody) ? h.$parent.$eval(j.datepickerAppendToBody) : g.appendToBody;
            h.showButtonBar = angular.isDefined(j.showButtonBar) ? h.$parent.$eval(j.showButtonBar) : g.showButtonBar, h.getText = function(a) {
                return h[a + "Text"] || g[a + "Text"]
            }, j.$observe("datepickerPopup", function(a) {
                n = a || g.datepickerPopup, k.$render()
            });
            var q = angular.element("<div datepicker-popup-wrap><div datepicker></div></div>");
            q.attr({
                "ng-model": "date",
                "ng-change": "dateSelection()"
            });
            var r = angular.element(q.children()[0]);
            j.datepickerOptions && angular.forEach(h.$parent.$eval(j.datepickerOptions), function(a, b) {
                r.attr(l(b), a)
            }), h.watchData = {}, angular.forEach(["minDate", "maxDate", "datepickerMode"], function(a) {
                if (j[a]) {
                    var c = b(j[a]);
                    if (h.$parent.$watch(c, function(b) {
                            h.watchData[a] = b
                        }), r.attr(l(a), "watchData." + a), "datepickerMode" === a) {
                        var d = c.assign;
                        h.$watch("watchData." + a, function(a, b) {
                            a !== b && d(h.$parent, a)
                        })
                    }
                }
            }), j.dateDisabled && r.attr("date-disabled", "dateDisabled({ date: date, mode: mode })"), k.$parsers.unshift(m), h.dateSelection = function(a) {
                angular.isDefined(a) && (h.date = a), k.$setViewValue(h.date), k.$render(), o && (h.isOpen = !1, i[0].focus())
            }, i.bind("input change keyup", function() {
                h.$apply(function() {
                    h.date = k.$modelValue
                })
            }), k.$render = function() {
                var a = k.$viewValue ? e(k.$viewValue, n) : "";
                i.val(a), h.date = m(k.$modelValue)
            };
            var s = function(a) {
                    h.isOpen && a.target !== i[0] && h.$apply(function() {
                        h.isOpen = !1
                    })
                },
                t = function(a) {
                    h.keydown(a)
                };
            i.bind("keydown", t), h.keydown = function(a) {
                27 === a.which ? (a.preventDefault(), a.stopPropagation(), h.close()) : 40 !== a.which || h.isOpen || (h.isOpen = !0)
            }, h.$watch("isOpen", function(a) {
                a ? (h.$broadcast("datepicker.focus"), h.position = p ? d.offset(i) : d.position(i), h.position.top = h.position.top + i.prop("offsetHeight"), c.bind("click", s)) : c.unbind("click", s)
            }), h.select = function(a) {
                if ("today" === a) {
                    var b = new Date;
                    angular.isDate(k.$modelValue) ? (a = new Date(k.$modelValue), a.setFullYear(b.getFullYear(), b.getMonth(), b.getDate())) : a = new Date(b.setHours(0, 0, 0, 0))
                }
                h.dateSelection(a)
            }, h.close = function() {
                h.isOpen = !1, i[0].focus()
            };
            var u = a(q)(h);
            q.remove(), p ? c.find("body").append(u) : i.after(u), h.$on("$destroy", function() {
                u.remove(), i.unbind("keydown", t), c.unbind("click", s)
            })
        }
    }
}]).directive("datepickerPopupWrap", function() {
    return {
        restrict: "EA",
        replace: !0,
        transclude: !0,
        templateUrl: "template/datepicker/popup.html",
        link: function(a, b) {
            b.bind("click", function(a) {
                a.preventDefault(), a.stopPropagation()
            })
        }
    }
}), angular.module("ui.bootstrap.dropdown", []).constant("dropdownConfig", {
    openClass: "open"
}).service("dropdownService", ["$document", function(a) {
    var b = null;
    this.open = function(e) {
        b || (a.bind("click", c), a.bind("keydown", d)), b && b !== e && (b.isOpen = !1), b = e
    }, this.close = function(e) {
        b === e && (b = null, a.unbind("click", c), a.unbind("keydown", d))
    };
    var c = function(a) {
            if (b) {
                var c = b.getToggleElement();
                a && c && c[0].contains(a.target) || b.$apply(function() {
                    b.isOpen = !1
                })
            }
        },
        d = function(a) {
            27 === a.which && (b.focusToggleElement(), c())
        }
}]).controller("DropdownController", ["$scope", "$attrs", "$parse", "dropdownConfig", "dropdownService", "$animate", function(a, b, c, d, e, f) {
    var g, h = this,
        i = a.$new(),
        j = d.openClass,
        k = angular.noop,
        l = b.onToggle ? c(b.onToggle) : angular.noop;
    this.init = function(d) {
        h.$element = d, b.isOpen && (g = c(b.isOpen), k = g.assign, a.$watch(g, function(a) {
            i.isOpen = !!a
        }))
    }, this.toggle = function(a) {
        return i.isOpen = arguments.length ? !!a : !i.isOpen
    }, this.isOpen = function() {
        return i.isOpen
    }, i.getToggleElement = function() {
        return h.toggleElement
    }, i.focusToggleElement = function() {
        h.toggleElement && h.toggleElement[0].focus()
    }, i.$watch("isOpen", function(b, c) {
        f[b ? "addClass" : "removeClass"](h.$element, j), b ? (i.focusToggleElement(), e.open(i)) : e.close(i), k(a, b), angular.isDefined(b) && b !== c && l(a, {
            open: !!b
        })
    }), a.$on("$locationChangeSuccess", function() {
        i.isOpen = !1
    }), a.$on("$destroy", function() {
        i.$destroy()
    })
}]).directive("dropdown", function() {
    return {
        controller: "DropdownController",
        link: function(a, b, c, d) {
            d.init(b)
        }
    }
}).directive("dropdownToggle", function() {
    return {
        require: "?^dropdown",
        link: function(a, b, c, d) {
            if (d) {
                d.toggleElement = b;
                var e = function(e) {
                    e.preventDefault(), b.hasClass("disabled") || c.disabled || a.$apply(function() {
                        d.toggle()
                    })
                };
                b.bind("click", e), b.attr({
                    "aria-haspopup": !0,
                    "aria-expanded": !1
                }), a.$watch(d.isOpen, function(a) {
                    b.attr("aria-expanded", !!a)
                }), a.$on("$destroy", function() {
                    b.unbind("click", e)
                })
            }
        }
    }
}), angular.module("ui.bootstrap.modal", ["ui.bootstrap.transition"]).factory("$$stackedMap", function() {
    return {
        createNew: function() {
            var a = [];
            return {
                add: function(b, c) {
                    a.push({
                        key: b,
                        value: c
                    })
                },
                get: function(b) {
                    for (var c = 0; c < a.length; c++)
                        if (b == a[c].key) return a[c]
                },
                keys: function() {
                    for (var b = [], c = 0; c < a.length; c++) b.push(a[c].key);
                    return b
                },
                top: function() {
                    return a[a.length - 1]
                },
                remove: function(b) {
                    for (var c = -1, d = 0; d < a.length; d++)
                        if (b == a[d].key) {
                            c = d;
                            break
                        }
                    return a.splice(c, 1)[0]
                },
                removeTop: function() {
                    return a.splice(a.length - 1, 1)[0]
                },
                length: function() {
                    return a.length
                }
            }
        }
    }
}).directive("modalBackdrop", ["$timeout", function(a) {
    return {
        restrict: "EA",
        replace: !0,
        templateUrl: "template/modal/backdrop.html",
        link: function(b, c, d) {
            b.backdropClass = d.backdropClass || "", b.animate = !1, a(function() {
                b.animate = !0
            })
        }
    }
}]).directive("modalWindow", ["$modalStack", "$timeout", function(a, b) {
    return {
        restrict: "EA",
        scope: {
            index: "@",
            animate: "="
        },
        replace: !0,
        transclude: !0,
        templateUrl: function(a, b) {
            return b.templateUrl || "template/modal/window.html"
        },
        link: function(c, d, e) {
            d.addClass(e.windowClass || ""), c.size = e.size, b(function() {
                c.animate = !0, d[0].querySelectorAll("[autofocus]").length || d[0].focus()
            }), c.close = function(b) {
                var c = a.getTop();
                c && c.value.backdrop && "static" != c.value.backdrop && b.target === b.currentTarget && (b.preventDefault(), b.stopPropagation(), a.dismiss(c.key, "backdrop click"))
            }
        }
    }
}]).directive("modalTransclude", function() {
    return {
        link: function(a, b, c, d, e) {
            e(a.$parent, function(a) {
                b.empty(), b.append(a)
            })
        }
    }
}).factory("$modalStack", ["$transition", "$timeout", "$document", "$compile", "$rootScope", "$$stackedMap", function(a, b, c, d, e, f) {
    function g() {
        for (var a = -1, b = n.keys(), c = 0; c < b.length; c++) n.get(b[c]).value.backdrop && (a = c);
        return a
    }

    function h(a) {
        var b = c.find("body").eq(0),
            d = n.get(a).value;
        n.remove(a), j(d.modalDomEl, d.modalScope, 300, function() {
            d.modalScope.$destroy(), b.toggleClass(m, n.length() > 0), i()
        })
    }

    function i() {
        if (k && -1 == g()) {
            var a = l;
            j(k, l, 150, function() {
                a.$destroy(), a = null
            }), k = void 0, l = void 0
        }
    }

    function j(c, d, e, f) {
        function g() {
            g.done || (g.done = !0, c.remove(), f && f())
        }
        d.animate = !1;
        var h = a.transitionEndEventName;
        if (h) {
            var i = b(g, e);
            c.bind(h, function() {
                b.cancel(i), g(), d.$apply()
            })
        } else b(g)
    }
    var k, l, m = "modal-open",
        n = f.createNew(),
        o = {};
    return e.$watch(g, function(a) {
        l && (l.index = a)
    }), c.bind("keydown", function(a) {
        var b;
        27 === a.which && (b = n.top()) && b.value.keyboard && (a.preventDefault(), e.$apply(function() {
            o.dismiss(b.key, "escape key press")
        }))
    }), o.open = function(a, b) {
        n.add(a, {
            deferred: b.deferred,
            modalScope: b.scope,
            backdrop: b.backdrop,
            keyboard: b.keyboard
        });
        var f = c.find("body").eq(0),
            h = g();
        if (h >= 0 && !k) {
            l = e.$new(!0), l.index = h;
            var i = angular.element("<div modal-backdrop></div>");
            i.attr("backdrop-class", b.backdropClass), k = d(i)(l), f.append(k)
        }
        var j = angular.element("<div modal-window></div>");
        j.attr({
            "template-url": b.windowTemplateUrl,
            "window-class": b.windowClass,
            size: b.size,
            index: n.length() - 1,
            animate: "animate"
        }).html(b.content);
        var o = d(j)(b.scope);
        n.top().value.modalDomEl = o, f.append(o), f.addClass(m)
    }, o.close = function(a, b) {
        var c = n.get(a);
        c && (c.value.deferred.resolve(b), h(a))
    }, o.dismiss = function(a, b) {
        var c = n.get(a);
        c && (c.value.deferred.reject(b), h(a))
    }, o.dismissAll = function(a) {
        for (var b = this.getTop(); b;) this.dismiss(b.key, a), b = this.getTop()
    }, o.getTop = function() {
        return n.top()
    }, o
}]).provider("$modal", function() {
    var a = {
        options: {
            backdrop: !0,
            keyboard: !0
        },
        $get: ["$injector", "$rootScope", "$q", "$http", "$templateCache", "$controller", "$modalStack", function(b, c, d, e, f, g, h) {
            function i(a) {
                return a.template ? d.when(a.template) : e.get(angular.isFunction(a.templateUrl) ? a.templateUrl() : a.templateUrl, {
                    cache: f
                }).then(function(a) {
                    return a.data
                })
            }

            function j(a) {
                var c = [];
                return angular.forEach(a, function(a) {
                    (angular.isFunction(a) || angular.isArray(a)) && c.push(d.when(b.invoke(a)))
                }), c
            }
            var k = {};
            return k.open = function(b) {
                var e = d.defer(),
                    f = d.defer(),
                    k = {
                        result: e.promise,
                        opened: f.promise,
                        close: function(a) {
                            h.close(k, a)
                        },
                        dismiss: function(a) {
                            h.dismiss(k, a)
                        }
                    };
                if (b = angular.extend({}, a.options, b), b.resolve = b.resolve || {}, !b.template && !b.templateUrl) throw new Error("One of template or templateUrl options is required.");
                var l = d.all([i(b)].concat(j(b.resolve)));
                return l.then(function(a) {
                    var d = (b.scope || c).$new();
                    d.$close = k.close, d.$dismiss = k.dismiss;
                    var f, i = {},
                        j = 1;
                    b.controller && (i.$scope = d, i.$modalInstance = k, angular.forEach(b.resolve, function(b, c) {
                        i[c] = a[j++]
                    }), f = g(b.controller, i), b.controllerAs && (d[b.controllerAs] = f)), h.open(k, {
                        scope: d,
                        deferred: e,
                        content: a[0],
                        backdrop: b.backdrop,
                        keyboard: b.keyboard,
                        backdropClass: b.backdropClass,
                        windowClass: b.windowClass,
                        windowTemplateUrl: b.windowTemplateUrl,
                        size: b.size
                    })
                }, function(a) {
                    e.reject(a)
                }), l.then(function() {
                    f.resolve(!0)
                }, function() {
                    f.reject(!1)
                }), k
            }, k
        }]
    };
    return a
}), angular.module("ui.bootstrap.pagination", []).controller("PaginationController", ["$scope", "$attrs", "$parse", function(a, b, c) {
    var d = this,
        e = {
            $setViewValue: angular.noop
        },
        f = b.numPages ? c(b.numPages).assign : angular.noop;
    this.init = function(f, g) {
        e = f, this.config = g, e.$render = function() {
            d.render()
        }, b.itemsPerPage ? a.$parent.$watch(c(b.itemsPerPage), function(b) {
            d.itemsPerPage = parseInt(b, 10), a.totalPages = d.calculateTotalPages()
        }) : this.itemsPerPage = g.itemsPerPage
    }, this.calculateTotalPages = function() {
        var b = this.itemsPerPage < 1 ? 1 : Math.ceil(a.totalItems / this.itemsPerPage);
        return Math.max(b || 0, 1)
    }, this.render = function() {
        a.page = parseInt(e.$viewValue, 10) || 1
    }, a.selectPage = function(b) {
        a.page !== b && b > 0 && b <= a.totalPages && (e.$setViewValue(b), e.$render())
    }, a.getText = function(b) {
        return a[b + "Text"] || d.config[b + "Text"]
    }, a.noPrevious = function() {
        return 1 === a.page
    }, a.noNext = function() {
        return a.page === a.totalPages
    }, a.$watch("totalItems", function() {
        a.totalPages = d.calculateTotalPages()
    }), a.$watch("totalPages", function(b) {
        f(a.$parent, b), a.page > b ? a.selectPage(b) : e.$render()
    })
}]).constant("paginationConfig", {
    itemsPerPage: 10,
    boundaryLinks: !1,
    directionLinks: !0,
    firstText: "First",
    previousText: "Previous",
    nextText: "Next",
    lastText: "Last",
    rotate: !0
}).directive("pagination", ["$parse", "paginationConfig", function(a, b) {
    return {
        restrict: "EA",
        scope: {
            totalItems: "=",
            firstText: "@",
            previousText: "@",
            nextText: "@",
            lastText: "@"
        },
        require: ["pagination", "?ngModel"],
        controller: "PaginationController",
        templateUrl: "template/pagination/pagination.html",
        replace: !0,
        link: function(c, d, e, f) {
            function g(a, b, c) {
                return {
                    number: a,
                    text: b,
                    active: c
                }
            }

            function h(a, b) {
                var c = [],
                    d = 1,
                    e = b,
                    f = angular.isDefined(k) && b > k;
                f && (l ? (d = Math.max(a - Math.floor(k / 2), 1), (e = d + k - 1) > b && (e = b, d = e - k + 1)) : (d = (Math.ceil(a / k) - 1) * k + 1, e = Math.min(d + k - 1, b)));
                for (var h = d; e >= h; h++) {
                    var i = g(h, h, h === a);
                    c.push(i)
                }
                if (f && !l) {
                    if (d > 1) {
                        var j = g(d - 1, "...", !1);
                        c.unshift(j)
                    }
                    if (b > e) {
                        var m = g(e + 1, "...", !1);
                        c.push(m)
                    }
                }
                return c
            }
            var i = f[0],
                j = f[1];
            if (j) {
                var k = angular.isDefined(e.maxSize) ? c.$parent.$eval(e.maxSize) : b.maxSize,
                    l = angular.isDefined(e.rotate) ? c.$parent.$eval(e.rotate) : b.rotate;
                c.boundaryLinks = angular.isDefined(e.boundaryLinks) ? c.$parent.$eval(e.boundaryLinks) : b.boundaryLinks,
                    c.directionLinks = angular.isDefined(e.directionLinks) ? c.$parent.$eval(e.directionLinks) : b.directionLinks, i.init(j, b), e.maxSize && c.$parent.$watch(a(e.maxSize), function(a) {
                        k = parseInt(a, 10), i.render()
                    });
                var m = i.render;
                i.render = function() {
                    m(), c.page > 0 && c.page <= c.totalPages && (c.pages = h(c.page, c.totalPages))
                }
            }
        }
    }
}]).constant("pagerConfig", {
    itemsPerPage: 10,
    previousText: "« Previous",
    nextText: "Next »",
    align: !0
}).directive("pager", ["pagerConfig", function(a) {
    return {
        restrict: "EA",
        scope: {
            totalItems: "=",
            previousText: "@",
            nextText: "@"
        },
        require: ["pager", "?ngModel"],
        controller: "PaginationController",
        templateUrl: "template/pagination/pager.html",
        replace: !0,
        link: function(b, c, d, e) {
            var f = e[0],
                g = e[1];
            g && (b.align = angular.isDefined(d.align) ? b.$parent.$eval(d.align) : a.align, f.init(g, a))
        }
    }
}]), angular.module("ui.bootstrap.tooltip", ["ui.bootstrap.position", "ui.bootstrap.bindHtml"]).provider("$tooltip", function() {
    function a(a) {
        var b = /[A-Z]/g;
        return a.replace(b, function(a, b) {
            return (b ? "-" : "") + a.toLowerCase()
        })
    }
    var b = {
            placement: "top",
            animation: !0,
            popupDelay: 0
        },
        c = {
            mouseenter: "mouseleave",
            click: "click",
            focus: "blur"
        },
        d = {};
    this.options = function(a) {
        angular.extend(d, a)
    }, this.setTriggers = function(a) {
        angular.extend(c, a)
    }, this.$get = ["$window", "$compile", "$timeout", "$document", "$position", "$interpolate", function(e, f, g, h, i, j) {
        return function(e, k, l) {
            function m(a) {
                var b = a || n.trigger || l;
                return {
                    show: b,
                    hide: c[b] || b
                }
            }
            var n = angular.extend({}, b, d),
                o = a(e),
                p = j.startSymbol(),
                q = j.endSymbol(),
                r = "<div " + o + '-popup title="' + p + "title" + q + '" content="' + p + "content" + q + '" placement="' + p + "placement" + q + '" animation="animation" is-open="isOpen"></div>';
            return {
                restrict: "EA",
                compile: function() {
                    var a = f(r);
                    return function(b, c, d) {
                        function f() {
                            C.isOpen ? l() : j()
                        }

                        function j() {
                            (!B || b.$eval(d[k + "Enable"])) && (s(), C.popupDelay ? y || (y = g(o, C.popupDelay, !1), y.then(function(a) {
                                a()
                            })) : o()())
                        }

                        function l() {
                            b.$apply(function() {
                                p()
                            })
                        }

                        function o() {
                            return y = null, x && (g.cancel(x), x = null), C.content ? (q(), v.css({
                                top: 0,
                                left: 0,
                                display: "block"
                            }), C.$digest(), D(), C.isOpen = !0, C.$digest(), D) : angular.noop
                        }

                        function p() {
                            C.isOpen = !1, g.cancel(y), y = null, C.animation ? x || (x = g(r, 500)) : r()
                        }

                        function q() {
                            v && r(), w = C.$new(), v = a(w, function(a) {
                                z ? h.find("body").append(a) : c.after(a)
                            })
                        }

                        function r() {
                            x = null, v && (v.remove(), v = null), w && (w.$destroy(), w = null)
                        }

                        function s() {
                            t(), u()
                        }

                        function t() {
                            var a = d[k + "Placement"];
                            C.placement = angular.isDefined(a) ? a : n.placement
                        }

                        function u() {
                            var a = d[k + "PopupDelay"],
                                b = parseInt(a, 10);
                            C.popupDelay = isNaN(b) ? n.popupDelay : b
                        }
                        var v, w, x, y, z = !!angular.isDefined(n.appendToBody) && n.appendToBody,
                            A = m(void 0),
                            B = angular.isDefined(d[k + "Enable"]),
                            C = b.$new(!0),
                            D = function() {
                                var a = i.positionElements(c, v, C.placement, z);
                                a.top += "px", a.left += "px", v.css(a)
                            };
                        C.isOpen = !1, d.$observe(e, function(a) {
                            C.content = a, !a && C.isOpen && p()
                        }), d.$observe(k + "Title", function(a) {
                            C.title = a
                        });
                        var E = function() {
                            c.unbind(A.show, j), c.unbind(A.hide, l)
                        };
                        ! function() {
                            var a = d[k + "Trigger"];
                            E(), A = m(a), A.show === A.hide ? c.bind(A.show, f) : (c.bind(A.show, j), c.bind(A.hide, l))
                        }();
                        var F = b.$eval(d[k + "Animation"]);
                        C.animation = angular.isDefined(F) ? !!F : n.animation;
                        var G = b.$eval(d[k + "AppendToBody"]);
                        z = angular.isDefined(G) ? G : z, z && b.$on("$locationChangeSuccess", function() {
                            C.isOpen && p()
                        }), b.$on("$destroy", function() {
                            g.cancel(x), g.cancel(y), E(), r(), C = null
                        })
                    }
                }
            }
        }
    }]
}).directive("tooltipPopup", function() {
    return {
        restrict: "EA",
        replace: !0,
        scope: {
            content: "@",
            placement: "@",
            animation: "&",
            isOpen: "&"
        },
        templateUrl: "template/tooltip/tooltip-popup.html"
    }
}).directive("tooltip", ["$tooltip", function(a) {
    return a("tooltip", "tooltip", "mouseenter")
}]).directive("tooltipHtmlUnsafePopup", function() {
    return {
        restrict: "EA",
        replace: !0,
        scope: {
            content: "@",
            placement: "@",
            animation: "&",
            isOpen: "&"
        },
        templateUrl: "template/tooltip/tooltip-html-unsafe-popup.html"
    }
}).directive("tooltipHtmlUnsafe", ["$tooltip", function(a) {
    return a("tooltipHtmlUnsafe", "tooltip", "mouseenter")
}]), angular.module("ui.bootstrap.popover", ["ui.bootstrap.tooltip"]).directive("popoverPopup", function() {
    return {
        restrict: "EA",
        replace: !0,
        scope: {
            title: "@",
            content: "@",
            placement: "@",
            animation: "&",
            isOpen: "&"
        },
        templateUrl: "template/popover/popover.html"
    }
}).directive("popover", ["$tooltip", function(a) {
    return a("popover", "popover", "click")
}]), angular.module("ui.bootstrap.progressbar", []).constant("progressConfig", {
    animate: !0,
    max: 100
}).controller("ProgressController", ["$scope", "$attrs", "progressConfig", function(a, b, c) {
    var d = this,
        e = angular.isDefined(b.animate) ? a.$parent.$eval(b.animate) : c.animate;
    this.bars = [], a.max = angular.isDefined(b.max) ? a.$parent.$eval(b.max) : c.max, this.addBar = function(b, c) {
        e || c.css({
            transition: "none"
        }), this.bars.push(b), b.$watch("value", function(c) {
            b.percent = +(100 * c / a.max).toFixed(2)
        }), b.$on("$destroy", function() {
            c = null, d.removeBar(b)
        })
    }, this.removeBar = function(a) {
        this.bars.splice(this.bars.indexOf(a), 1)
    }
}]).directive("progress", function() {
    return {
        restrict: "EA",
        replace: !0,
        transclude: !0,
        controller: "ProgressController",
        require: "progress",
        scope: {},
        templateUrl: "template/progressbar/progress.html"
    }
}).directive("bar", function() {
    return {
        restrict: "EA",
        replace: !0,
        transclude: !0,
        require: "^progress",
        scope: {
            value: "=",
            type: "@"
        },
        templateUrl: "template/progressbar/bar.html",
        link: function(a, b, c, d) {
            d.addBar(a, b)
        }
    }
}).directive("progressbar", function() {
    return {
        restrict: "EA",
        replace: !0,
        transclude: !0,
        controller: "ProgressController",
        scope: {
            value: "=",
            type: "@"
        },
        templateUrl: "template/progressbar/progressbar.html",
        link: function(a, b, c, d) {
            d.addBar(a, angular.element(b.children()[0]))
        }
    }
}), angular.module("ui.bootstrap.rating", []).constant("ratingConfig", {
    max: 5,
    stateOn: null,
    stateOff: null
}).controller("RatingController", ["$scope", "$attrs", "ratingConfig", function(a, b, c) {
    var d = {
        $setViewValue: angular.noop
    };
    this.init = function(e) {
        d = e, d.$render = this.render, this.stateOn = angular.isDefined(b.stateOn) ? a.$parent.$eval(b.stateOn) : c.stateOn, this.stateOff = angular.isDefined(b.stateOff) ? a.$parent.$eval(b.stateOff) : c.stateOff;
        var f = angular.isDefined(b.ratingStates) ? a.$parent.$eval(b.ratingStates) : new Array(angular.isDefined(b.max) ? a.$parent.$eval(b.max) : c.max);
        a.range = this.buildTemplateObjects(f)
    }, this.buildTemplateObjects = function(a) {
        for (var b = 0, c = a.length; c > b; b++) a[b] = angular.extend({
            index: b
        }, {
            stateOn: this.stateOn,
            stateOff: this.stateOff
        }, a[b]);
        return a
    }, a.rate = function(b) {
        !a.readonly && b >= 0 && b <= a.range.length && (d.$setViewValue(b), d.$render())
    }, a.enter = function(b) {
        a.readonly || (a.value = b), a.onHover({
            value: b
        })
    }, a.reset = function() {
        a.value = d.$viewValue, a.onLeave()
    }, a.onKeydown = function(b) {
        /(37|38|39|40)/.test(b.which) && (b.preventDefault(), b.stopPropagation(), a.rate(a.value + (38 === b.which || 39 === b.which ? 1 : -1)))
    }, this.render = function() {
        a.value = d.$viewValue
    }
}]).directive("rating", function() {
    return {
        restrict: "EA",
        require: ["rating", "ngModel"],
        scope: {
            readonly: "=?",
            onHover: "&",
            onLeave: "&"
        },
        controller: "RatingController",
        templateUrl: "template/rating/rating.html",
        replace: !0,
        link: function(a, b, c, d) {
            var e = d[0],
                f = d[1];
            f && e.init(f)
        }
    }
}), angular.module("ui.bootstrap.tabs", []).controller("TabsetController", ["$scope", function(a) {
    var b = this,
        c = b.tabs = a.tabs = [];
    b.select = function(a) {
        angular.forEach(c, function(b) {
            b.active && b !== a && (b.active = !1, b.onDeselect())
        }), a.active = !0, a.onSelect()
    }, b.addTab = function(a) {
        c.push(a), 1 === c.length ? a.active = !0 : a.active && b.select(a)
    }, b.removeTab = function(a) {
        var e = c.indexOf(a);
        if (a.active && c.length > 1 && !d) {
            var f = e == c.length - 1 ? e - 1 : e + 1;
            b.select(c[f])
        }
        c.splice(e, 1)
    };
    var d;
    a.$on("$destroy", function() {
        d = !0
    })
}]).directive("tabset", function() {
    return {
        restrict: "EA",
        transclude: !0,
        replace: !0,
        scope: {
            type: "@"
        },
        controller: "TabsetController",
        templateUrl: "template/tabs/tabset.html",
        link: function(a, b, c) {
            a.vertical = !!angular.isDefined(c.vertical) && a.$parent.$eval(c.vertical), a.justified = !!angular.isDefined(c.justified) && a.$parent.$eval(c.justified)
        }
    }
}).directive("tab", ["$parse", function(a) {
    return {
        require: "^tabset",
        restrict: "EA",
        replace: !0,
        templateUrl: "template/tabs/tab.html",
        transclude: !0,
        scope: {
            active: "=?",
            heading: "@",
            onSelect: "&select",
            onDeselect: "&deselect"
        },
        controller: function() {},
        compile: function(b, c, d) {
            return function(b, c, e, f) {
                b.$watch("active", function(a) {
                    a && f.select(b)
                }), b.disabled = !1, e.disabled && b.$parent.$watch(a(e.disabled), function(a) {
                    b.disabled = !!a
                }), b.select = function() {
                    b.disabled || (b.active = !0)
                }, f.addTab(b), b.$on("$destroy", function() {
                    f.removeTab(b)
                }), b.$transcludeFn = d
            }
        }
    }
}]).directive("tabHeadingTransclude", [function() {
    return {
        restrict: "A",
        require: "^tab",
        link: function(a, b) {
            a.$watch("headingElement", function(a) {
                a && (b.html(""), b.append(a))
            })
        }
    }
}]).directive("tabContentTransclude", function() {
    function a(a) {
        return a.tagName && (a.hasAttribute("tab-heading") || a.hasAttribute("data-tab-heading") || "tab-heading" === a.tagName.toLowerCase() || "data-tab-heading" === a.tagName.toLowerCase())
    }
    return {
        restrict: "A",
        require: "^tabset",
        link: function(b, c, d) {
            var e = b.$eval(d.tabContentTransclude);
            e.$transcludeFn(e.$parent, function(b) {
                angular.forEach(b, function(b) {
                    a(b) ? e.headingElement = b : c.append(b)
                })
            })
        }
    }
}), angular.module("ui.bootstrap.timepicker", []).constant("timepickerConfig", {
    hourStep: 1,
    minuteStep: 1,
    showMeridian: !0,
    meridians: null,
    readonlyInput: !1,
    mousewheel: !0
}).controller("TimepickerController", ["$scope", "$attrs", "$parse", "$log", "$locale", "timepickerConfig", function(a, b, c, d, e, f) {
    function g() {
        var b = parseInt(a.hours, 10);
        return (a.showMeridian ? b > 0 && 13 > b : b >= 0 && 24 > b) ? (a.showMeridian && (12 === b && (b = 0), a.meridian === p[1] && (b += 12)), b) : void 0
    }

    function h() {
        var b = parseInt(a.minutes, 10);
        return b >= 0 && 60 > b ? b : void 0
    }

    function i(a) {
        return angular.isDefined(a) && a.toString().length < 2 ? "0" + a : a
    }

    function j(a) {
        k(), o.$setViewValue(new Date(n)), l(a)
    }

    function k() {
        o.$setValidity("time", !0), a.invalidHours = !1, a.invalidMinutes = !1
    }

    function l(b) {
        var c = n.getHours(),
            d = n.getMinutes();
        a.showMeridian && (c = 0 === c || 12 === c ? 12 : c % 12), a.hours = "h" === b ? c : i(c), a.minutes = "m" === b ? d : i(d), a.meridian = n.getHours() < 12 ? p[0] : p[1]
    }

    function m(a) {
        var b = new Date(n.getTime() + 6e4 * a);
        n.setHours(b.getHours(), b.getMinutes()), j()
    }
    var n = new Date,
        o = {
            $setViewValue: angular.noop
        },
        p = angular.isDefined(b.meridians) ? a.$parent.$eval(b.meridians) : f.meridians || e.DATETIME_FORMATS.AMPMS;
    this.init = function(c, d) {
        o = c, o.$render = this.render;
        var e = d.eq(0),
            g = d.eq(1);
        (angular.isDefined(b.mousewheel) ? a.$parent.$eval(b.mousewheel) : f.mousewheel) && this.setupMousewheelEvents(e, g), a.readonlyInput = angular.isDefined(b.readonlyInput) ? a.$parent.$eval(b.readonlyInput) : f.readonlyInput, this.setupInputEvents(e, g)
    };
    var q = f.hourStep;
    b.hourStep && a.$parent.$watch(c(b.hourStep), function(a) {
        q = parseInt(a, 10)
    });
    var r = f.minuteStep;
    b.minuteStep && a.$parent.$watch(c(b.minuteStep), function(a) {
        r = parseInt(a, 10)
    }), a.showMeridian = f.showMeridian, b.showMeridian && a.$parent.$watch(c(b.showMeridian), function(b) {
        if (a.showMeridian = !!b, o.$error.time) {
            var c = g(),
                d = h();
            angular.isDefined(c) && angular.isDefined(d) && (n.setHours(c), j())
        } else l()
    }), this.setupMousewheelEvents = function(b, c) {
        var d = function(a) {
            a.originalEvent && (a = a.originalEvent);
            var b = a.wheelDelta ? a.wheelDelta : -a.deltaY;
            return a.detail || b > 0
        };
        b.bind("mousewheel wheel", function(b) {
            a.$apply(d(b) ? a.incrementHours() : a.decrementHours()), b.preventDefault()
        }), c.bind("mousewheel wheel", function(b) {
            a.$apply(d(b) ? a.incrementMinutes() : a.decrementMinutes()), b.preventDefault()
        })
    }, this.setupInputEvents = function(b, c) {
        if (a.readonlyInput) return a.updateHours = angular.noop, void(a.updateMinutes = angular.noop);
        var d = function(b, c) {
            o.$setViewValue(null), o.$setValidity("time", !1), angular.isDefined(b) && (a.invalidHours = b), angular.isDefined(c) && (a.invalidMinutes = c)
        };
        a.updateHours = function() {
            var a = g();
            angular.isDefined(a) ? (n.setHours(a), j("h")) : d(!0)
        }, b.bind("blur", function() {
            !a.invalidHours && a.hours < 10 && a.$apply(function() {
                a.hours = i(a.hours)
            })
        }), a.updateMinutes = function() {
            var a = h();
            angular.isDefined(a) ? (n.setMinutes(a), j("m")) : d(void 0, !0)
        }, c.bind("blur", function() {
            !a.invalidMinutes && a.minutes < 10 && a.$apply(function() {
                a.minutes = i(a.minutes)
            })
        })
    }, this.render = function() {
        var a = o.$modelValue ? new Date(o.$modelValue) : null;
        isNaN(a) ? (o.$setValidity("time", !1), d.error('Timepicker directive: "ng-model" value must be a Date object, a number of milliseconds since 01.01.1970 or a string representing an RFC2822 or ISO 8601 date.')) : (a && (n = a), k(), l())
    }, a.incrementHours = function() {
        m(60 * q)
    }, a.decrementHours = function() {
        m(60 * -q)
    }, a.incrementMinutes = function() {
        m(r)
    }, a.decrementMinutes = function() {
        m(-r)
    }, a.toggleMeridian = function() {
        m(720 * (n.getHours() < 12 ? 1 : -1))
    }
}]).directive("timepicker", function() {
    return {
        restrict: "EA",
        require: ["timepicker", "?^ngModel"],
        controller: "TimepickerController",
        replace: !0,
        scope: {},
        templateUrl: "template/timepicker/timepicker.html",
        link: function(a, b, c, d) {
            var e = d[0],
                f = d[1];
            f && e.init(f, b.find("input"))
        }
    }
}), angular.module("ui.bootstrap.typeahead", ["ui.bootstrap.position", "ui.bootstrap.bindHtml"]).factory("typeaheadParser", ["$parse", function(a) {
    var b = /^\s*([\s\S]+?)(?:\s+as\s+([\s\S]+?))?\s+for\s+(?:([\$\w][\$\w\d]*))\s+in\s+([\s\S]+?)$/;
    return {
        parse: function(c) {
            var d = c.match(b);
            if (!d) throw new Error('Expected typeahead specification in form of "_modelValue_ (as _label_)? for _item_ in _collection_" but got "' + c + '".');
            return {
                itemName: d[3],
                source: a(d[4]),
                viewMapper: a(d[2] || d[1]),
                modelMapper: a(d[1])
            }
        }
    }
}]).directive("typeahead", ["$compile", "$parse", "$q", "$timeout", "$document", "$position", "typeaheadParser", function(a, b, c, d, e, f, g) {
    var h = [9, 13, 27, 38, 40];
    return {
        require: "ngModel",
        link: function(i, j, k, l) {
            var m, n = i.$eval(k.typeaheadMinLength) || 1,
                o = i.$eval(k.typeaheadWaitMs) || 0,
                p = !1 !== i.$eval(k.typeaheadEditable),
                q = b(k.typeaheadLoading).assign || angular.noop,
                r = b(k.typeaheadOnSelect),
                s = k.typeaheadInputFormatter ? b(k.typeaheadInputFormatter) : void 0,
                t = !!k.typeaheadAppendToBody && i.$eval(k.typeaheadAppendToBody),
                u = !1 !== i.$eval(k.typeaheadFocusFirst),
                v = b(k.ngModel).assign,
                w = g.parse(k.typeahead),
                x = i.$new();
            i.$on("$destroy", function() {
                x.$destroy()
            });
            var y = "typeahead-" + x.$id + "-" + Math.floor(1e4 * Math.random());
            j.attr({
                "aria-autocomplete": "list",
                "aria-expanded": !1,
                "aria-owns": y
            });
            var z = angular.element("<div typeahead-popup></div>");
            z.attr({
                id: y,
                matches: "matches",
                active: "activeIdx",
                select: "select(activeIdx)",
                query: "query",
                position: "position"
            }), angular.isDefined(k.typeaheadTemplateUrl) && z.attr("template-url", k.typeaheadTemplateUrl);
            var A = function() {
                    x.matches = [], x.activeIdx = -1, j.attr("aria-expanded", !1)
                },
                B = function(a) {
                    return y + "-option-" + a
                };
            x.$watch("activeIdx", function(a) {
                0 > a ? j.removeAttr("aria-activedescendant") : j.attr("aria-activedescendant", B(a))
            });
            var C = function(a) {
                var b = {
                    $viewValue: a
                };
                q(i, !0), c.when(w.source(i, b)).then(function(c) {
                    var d = a === l.$viewValue;
                    if (d && m)
                        if (c.length > 0) {
                            x.activeIdx = u ? 0 : -1, x.matches.length = 0;
                            for (var e = 0; e < c.length; e++) b[w.itemName] = c[e], x.matches.push({
                                id: B(e),
                                label: w.viewMapper(x, b),
                                model: c[e]
                            });
                            x.query = a, x.position = t ? f.offset(j) : f.position(j), x.position.top = x.position.top + j.prop("offsetHeight"), j.attr("aria-expanded", !0)
                        } else A();
                    d && q(i, !1)
                }, function() {
                    A(), q(i, !1)
                })
            };
            A(), x.query = void 0;
            var D, E = function(a) {
                    D = d(function() {
                        C(a)
                    }, o)
                },
                F = function() {
                    D && d.cancel(D)
                };
            l.$parsers.unshift(function(a) {
                return m = !0, a && a.length >= n ? o > 0 ? (F(), E(a)) : C(a) : (q(i, !1), F(), A()), p ? a : a ? void l.$setValidity("editable", !1) : (l.$setValidity("editable", !0), a)
            }), l.$formatters.push(function(a) {
                var b, c, d = {};
                return s ? (d.$model = a, s(i, d)) : (d[w.itemName] = a, b = w.viewMapper(i, d), d[w.itemName] = void 0, c = w.viewMapper(i, d), b !== c ? b : a)
            }), x.select = function(a) {
                var b, c, e = {};
                e[w.itemName] = c = x.matches[a].model, b = w.modelMapper(i, e), v(i, b), l.$setValidity("editable", !0), r(i, {
                    $item: c,
                    $model: b,
                    $label: w.viewMapper(i, e)
                }), A(), d(function() {
                    j[0].focus()
                }, 0, !1)
            }, j.bind("keydown", function(a) {
                0 !== x.matches.length && -1 !== h.indexOf(a.which) && (-1 != x.activeIdx || 13 !== a.which && 9 !== a.which) && (a.preventDefault(), 40 === a.which ? (x.activeIdx = (x.activeIdx + 1) % x.matches.length, x.$digest()) : 38 === a.which ? (x.activeIdx = (x.activeIdx > 0 ? x.activeIdx : x.matches.length) - 1, x.$digest()) : 13 === a.which || 9 === a.which ? x.$apply(function() {
                    x.select(x.activeIdx)
                }) : 27 === a.which && (a.stopPropagation(), A(), x.$digest()))
            }), j.bind("blur", function() {
                m = !1
            });
            var G = function(a) {
                j[0] !== a.target && (A(), x.$digest())
            };
            e.bind("click", G), i.$on("$destroy", function() {
                e.unbind("click", G), t && H.remove()
            });
            var H = a(z)(x);
            t ? e.find("body").append(H) : j.after(H)
        }
    }
}]).directive("typeaheadPopup", function() {
    return {
        restrict: "EA",
        scope: {
            matches: "=",
            query: "=",
            active: "=",
            position: "=",
            select: "&"
        },
        replace: !0,
        templateUrl: "template/typeahead/typeahead-popup.html",
        link: function(a, b, c) {
            a.templateUrl = c.templateUrl, a.isOpen = function() {
                return a.matches.length > 0
            }, a.isActive = function(b) {
                return a.active == b
            }, a.selectActive = function(b) {
                a.active = b
            }, a.selectMatch = function(b) {
                a.select({
                    activeIdx: b
                })
            }
        }
    }
}).directive("typeaheadMatch", ["$http", "$templateCache", "$compile", "$parse", function(a, b, c, d) {
    return {
        restrict: "EA",
        scope: {
            index: "=",
            match: "=",
            query: "="
        },
        link: function(e, f, g) {
            var h = d(g.templateUrl)(e.$parent) || "template/typeahead/typeahead-match.html";
            a.get(h, {
                cache: b
            }).success(function(a) {
                f.replaceWith(c(a.trim())(e))
            })
        }
    }
}]).filter("typeaheadHighlight", function() {
    function a(a) {
        return a.replace(/([.?*+^$[\]\\(){}|-])/g, "\\$1")
    }
    return function(b, c) {
        return c ? ("" + b).replace(new RegExp(a(c), "gi"), "<strong>$&</strong>") : b
    }
}), angular.module("template/accordion/accordion-group.html", []).run(["$templateCache", function(a) {
    a.put("template/accordion/accordion-group.html", '<div class="panel panel-default">\n  <div class="panel-heading">\n    <h4 class="panel-title">\n      <a href class="accordion-toggle" ng-click="toggleOpen()" accordion-transclude="heading"><span ng-class="{\'text-muted\': isDisabled}">{{heading}}</span></a>\n    </h4>\n  </div>\n  <div class="panel-collapse" collapse="!isOpen">\n\t  <div class="panel-body" ng-transclude></div>\n  </div>\n</div>\n')
}]), angular.module("template/accordion/accordion.html", []).run(["$templateCache", function(a) {
    a.put("template/accordion/accordion.html", '<div class="panel-group" ng-transclude></div>')
}]), angular.module("template/alert/alert.html", []).run(["$templateCache", function(a) {
    a.put("template/alert/alert.html", '<div class="alert" ng-class="[\'alert-\' + (type || \'warning\'), closeable ? \'alert-dismissable\' : null]" role="alert">\n    <button ng-show="closeable" type="button" class="close" ng-click="close()">\n        <span aria-hidden="true">&times;</span>\n        <span class="sr-only">Close</span>\n    </button>\n    <div ng-transclude></div>\n</div>\n')
}]), angular.module("template/carousel/carousel.html", []).run(["$templateCache", function(a) {
    a.put("template/carousel/carousel.html", '<div ng-mouseenter="pause()" ng-mouseleave="play()" class="carousel" ng-swipe-right="prev()" ng-swipe-left="next()">\n    <ol class="carousel-indicators" ng-show="slides.length > 1">\n        <li ng-repeat="slide in slides track by $index" ng-class="{active: isActive(slide)}" ng-click="select(slide)"></li>\n    </ol>\n    <div class="carousel-inner" ng-transclude></div>\n    <a class="left carousel-control" ng-click="prev()" ng-show="slides.length > 1"><span class="glyphicon glyphicon-chevron-left"></span></a>\n    <a class="right carousel-control" ng-click="next()" ng-show="slides.length > 1"><span class="glyphicon glyphicon-chevron-right"></span></a>\n</div>\n')
}]), angular.module("template/carousel/slide.html", []).run(["$templateCache", function(a) {
    a.put("template/carousel/slide.html", "<div ng-class=\"{\n    'active': leaving || (active && !entering),\n    'prev': (next || active) && direction=='prev',\n    'next': (next || active) && direction=='next',\n    'right': direction=='prev',\n    'left': direction=='next'\n  }\" class=\"item text-center\" ng-transclude></div>\n")
}]), angular.module("template/datepicker/datepicker.html", []).run(["$templateCache", function(a) {
    a.put("template/datepicker/datepicker.html", '<div ng-switch="datepickerMode" role="application" ng-keydown="keydown($event)">\n  <daypicker ng-switch-when="day" tabindex="0"></daypicker>\n  <monthpicker ng-switch-when="month" tabindex="0"></monthpicker>\n  <yearpicker ng-switch-when="year" tabindex="0"></yearpicker>\n</div>')
}]), angular.module("template/datepicker/day.html", []).run(["$templateCache", function(a) {
    a.put("template/datepicker/day.html", '<table role="grid" aria-labelledby="{{uniqueId}}-title" aria-activedescendant="{{activeDateId}}">\n  <thead>\n    <tr>\n      <th><button type="button" class="btn btn-default btn-sm pull-left" ng-click="move(-1)" tabindex="-1"><i class="glyphicon glyphicon-chevron-left"></i></button></th>\n      <th colspan="{{5 + showWeeks}}"><button id="{{uniqueId}}-title" role="heading" aria-live="assertive" aria-atomic="true" type="button" class="btn btn-default btn-sm" ng-click="toggleMode()" tabindex="-1" style="width:100%;"><strong>{{title}}</strong></button></th>\n      <th><button type="button" class="btn btn-default btn-sm pull-right" ng-click="move(1)" tabindex="-1"><i class="glyphicon glyphicon-chevron-right"></i></button></th>\n    </tr>\n    <tr>\n      <th ng-show="showWeeks" class="text-center"></th>\n      <th ng-repeat="label in labels track by $index" class="text-center"><small aria-label="{{label.full}}">{{label.abbr}}</small></th>\n    </tr>\n  </thead>\n  <tbody>\n    <tr ng-repeat="row in rows track by $index">\n      <td ng-show="showWeeks" class="text-center h6"><em>{{ weekNumbers[$index] }}</em></td>\n      <td ng-repeat="dt in row track by dt.date" class="text-center" role="gridcell" id="{{dt.uid}}" aria-disabled="{{!!dt.disabled}}">\n        <button type="button" style="width:100%;" class="btn btn-default btn-sm" ng-class="{\'btn-info\': dt.selected, active: isActive(dt)}" ng-click="select(dt.date)" ng-disabled="dt.disabled" tabindex="-1"><span ng-class="{\'text-muted\': dt.secondary, \'text-info\': dt.current}">{{dt.label}}</span></button>\n      </td>\n    </tr>\n  </tbody>\n</table>\n')
}]), angular.module("template/datepicker/month.html", []).run(["$templateCache", function(a) {
    a.put("template/datepicker/month.html", '<table role="grid" aria-labelledby="{{uniqueId}}-title" aria-activedescendant="{{activeDateId}}">\n  <thead>\n    <tr>\n      <th><button type="button" class="btn btn-default btn-sm pull-left" ng-click="move(-1)" tabindex="-1"><i class="glyphicon glyphicon-chevron-left"></i></button></th>\n      <th><button id="{{uniqueId}}-title" role="heading" aria-live="assertive" aria-atomic="true" type="button" class="btn btn-default btn-sm" ng-click="toggleMode()" tabindex="-1" style="width:100%;"><strong>{{title}}</strong></button></th>\n      <th><button type="button" class="btn btn-default btn-sm pull-right" ng-click="move(1)" tabindex="-1"><i class="glyphicon glyphicon-chevron-right"></i></button></th>\n    </tr>\n  </thead>\n  <tbody>\n    <tr ng-repeat="row in rows track by $index">\n      <td ng-repeat="dt in row track by dt.date" class="text-center" role="gridcell" id="{{dt.uid}}" aria-disabled="{{!!dt.disabled}}">\n        <button type="button" style="width:100%;" class="btn btn-default" ng-class="{\'btn-info\': dt.selected, active: isActive(dt)}" ng-click="select(dt.date)" ng-disabled="dt.disabled" tabindex="-1"><span ng-class="{\'text-info\': dt.current}">{{dt.label}}</span></button>\n      </td>\n    </tr>\n  </tbody>\n</table>\n')
}]), angular.module("template/datepicker/popup.html", []).run(["$templateCache", function(a) {
    a.put("template/datepicker/popup.html", '<ul class="dropdown-menu" ng-style="{display: (isOpen && \'block\') || \'none\', top: position.top+\'px\', left: position.left+\'px\'}" ng-keydown="keydown($event)">\n\t<li ng-transclude></li>\n\t<li ng-if="showButtonBar" style="padding:10px 9px 2px">\n\t\t<span class="btn-group pull-left">\n\t\t\t<button type="button" class="btn btn-sm btn-info" ng-click="select(\'today\')">{{ getText(\'current\') }}</button>\n\t\t\t<button type="button" class="btn btn-sm btn-danger" ng-click="select(null)">{{ getText(\'clear\') }}</button>\n\t\t</span>\n\t\t<button type="button" class="btn btn-sm btn-success pull-right" ng-click="close()">{{ getText(\'close\') }}</button>\n\t</li>\n</ul>\n')
}]), angular.module("template/datepicker/year.html", []).run(["$templateCache", function(a) {
    a.put("template/datepicker/year.html", '<table role="grid" aria-labelledby="{{uniqueId}}-title" aria-activedescendant="{{activeDateId}}">\n  <thead>\n    <tr>\n      <th><button type="button" class="btn btn-default btn-sm pull-left" ng-click="move(-1)" tabindex="-1"><i class="glyphicon glyphicon-chevron-left"></i></button></th>\n      <th colspan="3"><button id="{{uniqueId}}-title" role="heading" aria-live="assertive" aria-atomic="true" type="button" class="btn btn-default btn-sm" ng-click="toggleMode()" tabindex="-1" style="width:100%;"><strong>{{title}}</strong></button></th>\n      <th><button type="button" class="btn btn-default btn-sm pull-right" ng-click="move(1)" tabindex="-1"><i class="glyphicon glyphicon-chevron-right"></i></button></th>\n    </tr>\n  </thead>\n  <tbody>\n    <tr ng-repeat="row in rows track by $index">\n      <td ng-repeat="dt in row track by dt.date" class="text-center" role="gridcell" id="{{dt.uid}}" aria-disabled="{{!!dt.disabled}}">\n        <button type="button" style="width:100%;" class="btn btn-default" ng-class="{\'btn-info\': dt.selected, active: isActive(dt)}" ng-click="select(dt.date)" ng-disabled="dt.disabled" tabindex="-1"><span ng-class="{\'text-info\': dt.current}">{{dt.label}}</span></button>\n      </td>\n    </tr>\n  </tbody>\n</table>\n')
}]), angular.module("template/modal/backdrop.html", []).run(["$templateCache", function(a) {
    a.put("template/modal/backdrop.html", '<div class="modal-backdrop fade {{ backdropClass }}"\n     ng-class="{in: animate}"\n     ng-style="{\'z-index\': 1040 + (index && 1 || 0) + index*10}"\n></div>\n')
}]), angular.module("template/modal/window.html", []).run(["$templateCache", function(a) {
    a.put("template/modal/window.html", '<div tabindex="-1" role="dialog" class="modal fade" ng-class="{in: animate}" ng-style="{\'z-index\': 1050 + index*10, display: \'block\'}" ng-click="close($event)">\n    <div class="modal-dialog" ng-class="{\'modal-sm\': size == \'sm\', \'modal-lg\': size == \'lg\'}"><div class="modal-content" modal-transclude></div></div>\n</div>')
}]), angular.module("template/pagination/pager.html", []).run(["$templateCache", function(a) {
    a.put("template/pagination/pager.html", '<ul class="pager">\n  <li ng-class="{disabled: noPrevious(), previous: align}"><a href ng-click="selectPage(page - 1)">{{getText(\'previous\')}}</a></li>\n  <li ng-class="{disabled: noNext(), next: align}"><a href ng-click="selectPage(page + 1)">{{getText(\'next\')}}</a></li>\n</ul>')
}]), angular.module("template/pagination/pagination.html", []).run(["$templateCache", function(a) {
    a.put("template/pagination/pagination.html", '<ul class="pagination">\n  <li ng-if="boundaryLinks" ng-class="{disabled: noPrevious()}"><a href ng-click="selectPage(1)">{{getText(\'first\')}}</a></li>\n  <li ng-if="directionLinks" ng-class="{disabled: noPrevious()}"><a href ng-click="selectPage(page - 1)">{{getText(\'previous\')}}</a></li>\n  <li ng-repeat="page in pages track by $index" ng-class="{active: page.active}"><a href ng-click="selectPage(page.number)">{{page.text}}</a></li>\n  <li ng-if="directionLinks" ng-class="{disabled: noNext()}"><a href ng-click="selectPage(page + 1)">{{getText(\'next\')}}</a></li>\n  <li ng-if="boundaryLinks" ng-class="{disabled: noNext()}"><a href ng-click="selectPage(totalPages)">{{getText(\'last\')}}</a></li>\n</ul>')
}]), angular.module("template/tooltip/tooltip-html-unsafe-popup.html", []).run(["$templateCache", function(a) {
    a.put("template/tooltip/tooltip-html-unsafe-popup.html", '<div class="tooltip {{placement}}" ng-class="{ in: isOpen(), fade: animation() }">\n  <div class="tooltip-arrow"></div>\n  <div class="tooltip-inner" bind-html-unsafe="content"></div>\n</div>\n')
}]), angular.module("template/tooltip/tooltip-popup.html", []).run(["$templateCache", function(a) {
    a.put("template/tooltip/tooltip-popup.html", '<div class="tooltip {{placement}}" ng-class="{ in: isOpen(), fade: animation() }">\n  <div class="tooltip-arrow"></div>\n  <div class="tooltip-inner" ng-bind="content"></div>\n</div>\n')
}]), angular.module("template/popover/popover.html", []).run(["$templateCache", function(a) {
    a.put("template/popover/popover.html", '<div class="popover {{placement}}" ng-class="{ in: isOpen(), fade: animation() }">\n  <div class="arrow"></div>\n\n  <div class="popover-inner">\n      <h3 class="popover-title" ng-bind="title" ng-show="title"></h3>\n      <div class="popover-content" ng-bind="content"></div>\n  </div>\n</div>\n')
}]), angular.module("template/progressbar/bar.html", []).run(["$templateCache", function(a) {
    a.put("template/progressbar/bar.html", '<div class="progress-bar" ng-class="type && \'progress-bar-\' + type" role="progressbar" aria-valuenow="{{value}}" aria-valuemin="0" aria-valuemax="{{max}}" ng-style="{width: percent + \'%\'}" aria-valuetext="{{percent | number:0}}%" ng-transclude></div>')
}]), angular.module("template/progressbar/progress.html", []).run(["$templateCache", function(a) {
    a.put("template/progressbar/progress.html", '<div class="progress" ng-transclude></div>')
}]), angular.module("template/progressbar/progressbar.html", []).run(["$templateCache", function(a) {
    a.put("template/progressbar/progressbar.html", '<div class="progress">\n  <div class="progress-bar" ng-class="type && \'progress-bar-\' + type" role="progressbar" aria-valuenow="{{value}}" aria-valuemin="0" aria-valuemax="{{max}}" ng-style="{width: percent + \'%\'}" aria-valuetext="{{percent | number:0}}%" ng-transclude></div>\n</div>')
}]), angular.module("template/rating/rating.html", []).run(["$templateCache", function(a) {
    a.put("template/rating/rating.html", '<span ng-mouseleave="reset()" ng-keydown="onKeydown($event)" tabindex="0" role="slider" aria-valuemin="0" aria-valuemax="{{range.length}}" aria-valuenow="{{value}}">\n    <i ng-repeat="r in range track by $index" ng-mouseenter="enter($index + 1)" ng-click="rate($index + 1)" class="glyphicon" ng-class="$index < value && (r.stateOn || \'glyphicon-star\') || (r.stateOff || \'glyphicon-star-empty\')">\n        <span class="sr-only">({{ $index < value ? \'*\' : \' \' }})</span>\n    </i>\n</span>')
}]), angular.module("template/tabs/tab.html", []).run(["$templateCache", function(a) {
    a.put("template/tabs/tab.html", '<li ng-class="{active: active, disabled: disabled}">\n  <a href ng-click="select()" tab-heading-transclude>{{heading}}</a>\n</li>\n')
}]), angular.module("template/tabs/tabset.html", []).run(["$templateCache", function(a) {
    a.put("template/tabs/tabset.html", '<div>\n  <ul class="nav nav-{{type || \'tabs\'}}" ng-class="{\'nav-stacked\': vertical, \'nav-justified\': justified}" ng-transclude></ul>\n  <div class="tab-content">\n    <div class="tab-pane" \n         ng-repeat="tab in tabs" \n         ng-class="{active: tab.active}"\n         tab-content-transclude="tab">\n    </div>\n  </div>\n</div>\n')
}]), angular.module("template/timepicker/timepicker.html", []).run(["$templateCache", function(a) {
    a.put("template/timepicker/timepicker.html", '<table>\n\t<tbody>\n\t\t<tr class="text-center">\n\t\t\t<td><a ng-click="incrementHours()" class="btn btn-link"><span class="glyphicon glyphicon-chevron-up"></span></a></td>\n\t\t\t<td>&nbsp;</td>\n\t\t\t<td><a ng-click="incrementMinutes()" class="btn btn-link"><span class="glyphicon glyphicon-chevron-up"></span></a></td>\n\t\t\t<td ng-show="showMeridian"></td>\n\t\t</tr>\n\t\t<tr>\n\t\t\t<td style="width:50px;" class="form-group" ng-class="{\'has-error\': invalidHours}">\n\t\t\t\t<input type="text" ng-model="hours" ng-change="updateHours()" class="form-control text-center" ng-mousewheel="incrementHours()" ng-readonly="readonlyInput" maxlength="2">\n\t\t\t</td>\n\t\t\t<td>:</td>\n\t\t\t<td style="width:50px;" class="form-group" ng-class="{\'has-error\': invalidMinutes}">\n\t\t\t\t<input type="text" ng-model="minutes" ng-change="updateMinutes()" class="form-control text-center" ng-readonly="readonlyInput" maxlength="2">\n\t\t\t</td>\n\t\t\t<td ng-show="showMeridian"><button type="button" class="btn btn-default text-center" ng-click="toggleMeridian()">{{meridian}}</button></td>\n\t\t</tr>\n\t\t<tr class="text-center">\n\t\t\t<td><a ng-click="decrementHours()" class="btn btn-link"><span class="glyphicon glyphicon-chevron-down"></span></a></td>\n\t\t\t<td>&nbsp;</td>\n\t\t\t<td><a ng-click="decrementMinutes()" class="btn btn-link"><span class="glyphicon glyphicon-chevron-down"></span></a></td>\n\t\t\t<td ng-show="showMeridian"></td>\n\t\t</tr>\n\t</tbody>\n</table>\n')
}]), angular.module("template/typeahead/typeahead-match.html", []).run(["$templateCache", function(a) {
    a.put("template/typeahead/typeahead-match.html", '<a tabindex="-1" bind-html-unsafe="match.label | typeaheadHighlight:query"></a>')
}]), angular.module("template/typeahead/typeahead-popup.html", []).run(["$templateCache", function(a) {
    a.put("template/typeahead/typeahead-popup.html", '<ul class="dropdown-menu" ng-show="isOpen()" ng-style="{top: position.top+\'px\', left: position.left+\'px\'}" style="display: block;" role="listbox" aria-hidden="{{!isOpen()}}">\n    <li ng-repeat="match in matches track by $index" ng-class="{active: isActive($index) }" ng-mouseenter="selectActive($index)" ng-click="selectMatch($index)" role="option" id="{{match.id}}">\n        <div typeahead-match index="$index" match="match" query="query" template-url="templateUrl"></div>\n    </li>\n</ul>\n')
}]), angular.module("ui.bootstrap", ["ui.bootstrap.transition", "ui.bootstrap.collapse", "ui.bootstrap.accordion", "ui.bootstrap.alert", "ui.bootstrap.bindHtml", "ui.bootstrap.buttons", "ui.bootstrap.carousel", "ui.bootstrap.dateparser", "ui.bootstrap.position", "ui.bootstrap.datepicker", "ui.bootstrap.dropdown", "ui.bootstrap.modal", "ui.bootstrap.pagination", "ui.bootstrap.tooltip", "ui.bootstrap.popover", "ui.bootstrap.progressbar", "ui.bootstrap.rating", "ui.bootstrap.tabs", "ui.bootstrap.timepicker", "ui.bootstrap.typeahead"]), angular.module("ui.bootstrap.transition", []).factory("$transition", ["$q", "$timeout", "$rootScope", function(a, b, c) {
    function d(a) {
        for (var b in a)
            if (void 0 !== f.style[b]) return a[b]
    }
    var e = function(d, f, g) {
            g = g || {};
            var h = a.defer(),
                i = e[g.animation ? "animationEndEventName" : "transitionEndEventName"],
                j = function() {
                    c.$apply(function() {
                        d.unbind(i, j), h.resolve(d)
                    })
                };
            return i && d.bind(i, j), b(function() {
                angular.isString(f) ? d.addClass(f) : angular.isFunction(f) ? f(d) : angular.isObject(f) && d.css(f), i || h.resolve(d)
            }), h.promise.cancel = function() {
                i && d.unbind(i, j), h.reject("Transition cancelled")
            }, h.promise
        },
        f = document.createElement("trans"),
        g = {
            WebkitTransition: "webkitTransitionEnd",
            MozTransition: "transitionend",
            OTransition: "oTransitionEnd",
            transition: "transitionend"
        },
        h = {
            WebkitTransition: "webkitAnimationEnd",
            MozTransition: "animationend",
            OTransition: "oAnimationEnd",
            transition: "animationend"
        };
    return e.transitionEndEventName = d(g), e.animationEndEventName = d(h), e
}]), angular.module("ui.bootstrap.collapse", ["ui.bootstrap.transition"]).directive("collapse", ["$transition", function(a) {
    return {
        link: function(b, c, d) {
            function e(b) {
                function d() {
                    j === e && (j = void 0)
                }
                var e = a(c, b);
                return j && j.cancel(), j = e, e.then(d, d), e
            }

            function f() {
                k ? (k = !1, g()) : (c.removeClass("collapse").addClass("collapsing"), e({
                    height: c[0].scrollHeight + "px"
                }).then(g))
            }

            function g() {
                c.removeClass("collapsing"), c.addClass("collapse in"), c.css({
                    height: "auto"
                })
            }

            function h() {
                k ? (k = !1, i(), c.css({
                    height: 0
                })) : (c.css({
                    height: c[0].scrollHeight + "px"
                }), c[0].offsetWidth, c.removeClass("collapse in").addClass("collapsing"), e({
                    height: 0
                }).then(i))
            }

            function i() {
                c.removeClass("collapsing"), c.addClass("collapse")
            }
            var j, k = !0;
            b.$watch(d.collapse, function(a) {
                a ? h() : f()
            })
        }
    }
}]), angular.module("ui.bootstrap.accordion", ["ui.bootstrap.collapse"]).constant("accordionConfig", {
    closeOthers: !0
}).controller("AccordionController", ["$scope", "$attrs", "accordionConfig", function(a, b, c) {
    this.groups = [], this.closeOthers = function(d) {
        (angular.isDefined(b.closeOthers) ? a.$eval(b.closeOthers) : c.closeOthers) && angular.forEach(this.groups, function(a) {
            a !== d && (a.isOpen = !1)
        })
    }, this.addGroup = function(a) {
        var b = this;
        this.groups.push(a), a.$on("$destroy", function() {
            b.removeGroup(a)
        })
    }, this.removeGroup = function(a) {
        var b = this.groups.indexOf(a); - 1 !== b && this.groups.splice(b, 1)
    }
}]).directive("accordion", function() {
    return {
        restrict: "EA",
        controller: "AccordionController",
        transclude: !0,
        replace: !1,
        templateUrl: "template/accordion/accordion.html"
    }
}).directive("accordionGroup", function() {
    return {
        require: "^accordion",
        restrict: "EA",
        transclude: !0,
        replace: !0,
        templateUrl: "template/accordion/accordion-group.html",
        scope: {
            heading: "@",
            isOpen: "=?",
            isDisabled: "=?"
        },
        controller: function() {
            this.setHeading = function(a) {
                this.heading = a
            }
        },
        link: function(a, b, c, d) {
            d.addGroup(a), a.$watch("isOpen", function(b) {
                b && d.closeOthers(a)
            }), a.toggleOpen = function() {
                a.isDisabled || (a.isOpen = !a.isOpen)
            }
        }
    }
}).directive("accordionHeading", function() {
    return {
        restrict: "EA",
        transclude: !0,
        template: "",
        replace: !0,
        require: "^accordionGroup",
        link: function(a, b, c, d, e) {
            d.setHeading(e(a, function() {}))
        }
    }
}).directive("accordionTransclude", function() {
    return {
        require: "^accordionGroup",
        link: function(a, b, c, d) {
            a.$watch(function() {
                return d[c.accordionTransclude]
            }, function(a) {
                a && (b.html(""), b.append(a))
            })
        }
    }
}), angular.module("ui.bootstrap.alert", []).controller("AlertController", ["$scope", "$attrs", function(a, b) {
    a.closeable = "close" in b, this.close = a.close
}]).directive("alert", function() {
    return {
        restrict: "EA",
        controller: "AlertController",
        templateUrl: "template/alert/alert.html",
        transclude: !0,
        replace: !0,
        scope: {
            type: "@",
            close: "&"
        }
    }
}).directive("dismissOnTimeout", ["$timeout", function(a) {
    return {
        require: "alert",
        link: function(b, c, d, e) {
            a(function() {
                e.close()
            }, parseInt(d.dismissOnTimeout, 10))
        }
    }
}]), angular.module("ui.bootstrap.bindHtml", []).directive("bindHtmlUnsafe", function() {
    return function(a, b, c) {
        b.addClass("ng-binding").data("$binding", c.bindHtmlUnsafe), a.$watch(c.bindHtmlUnsafe, function(a) {
            b.html(a || "")
        })
    }
}), angular.module("ui.bootstrap.buttons", []).constant("buttonConfig", {
    activeClass: "active",
    toggleEvent: "click"
}).controller("ButtonsController", ["buttonConfig", function(a) {
    this.activeClass = a.activeClass || "active", this.toggleEvent = a.toggleEvent || "click"
}]).directive("btnRadio", function() {
    return {
        require: ["btnRadio", "ngModel"],
        controller: "ButtonsController",
        link: function(a, b, c, d) {
            var e = d[0],
                f = d[1];
            f.$render = function() {
                b.toggleClass(e.activeClass, angular.equals(f.$modelValue, a.$eval(c.btnRadio)))
            }, b.bind(e.toggleEvent, function() {
                var d = b.hasClass(e.activeClass);
                (!d || angular.isDefined(c.uncheckable)) && a.$apply(function() {
                    f.$setViewValue(d ? null : a.$eval(c.btnRadio)), f.$render()
                })
            })
        }
    }
}).directive("btnCheckbox", function() {
    return {
        require: ["btnCheckbox", "ngModel"],
        controller: "ButtonsController",
        link: function(a, b, c, d) {
            function e() {
                return g(c.btnCheckboxTrue, !0)
            }

            function f() {
                return g(c.btnCheckboxFalse, !1)
            }

            function g(b, c) {
                var d = a.$eval(b);
                return angular.isDefined(d) ? d : c
            }
            var h = d[0],
                i = d[1];
            i.$render = function() {
                b.toggleClass(h.activeClass, angular.equals(i.$modelValue, e()))
            }, b.bind(h.toggleEvent, function() {
                a.$apply(function() {
                    i.$setViewValue(b.hasClass(h.activeClass) ? f() : e()), i.$render()
                })
            })
        }
    }
}), angular.module("ui.bootstrap.carousel", ["ui.bootstrap.transition"]).controller("CarouselController", ["$scope", "$timeout", "$interval", "$transition", function(a, b, c, d) {
    function e() {
        f();
        var b = +a.interval;
        !isNaN(b) && b > 0 && (h = c(g, b))
    }

    function f() {
        h && (c.cancel(h), h = null)
    }

    function g() {
        var b = +a.interval;
        i && !isNaN(b) && b > 0 ? a.next() : a.pause()
    }
    var h, i, j = this,
        k = j.slides = a.slides = [],
        l = -1;
    j.currentSlide = null;
    var m = !1;
    j.select = a.select = function(c, f) {
        function g() {
            m || (j.currentSlide && angular.isString(f) && !a.noTransition && c.$element ? (c.$element.addClass(f), c.$element[0].offsetWidth, angular.forEach(k, function(a) {
                angular.extend(a, {
                    direction: "",
                    entering: !1,
                    leaving: !1,
                    active: !1
                })
            }), angular.extend(c, {
                direction: f,
                active: !0,
                entering: !0
            }), angular.extend(j.currentSlide || {}, {
                direction: f,
                leaving: !0
            }), a.$currentTransition = d(c.$element, {}), function(b, c) {
                a.$currentTransition.then(function() {
                    h(b, c)
                }, function() {
                    h(b, c)
                })
            }(c, j.currentSlide)) : h(c, j.currentSlide), j.currentSlide = c, l = i, e())
        }

        function h(b, c) {
            angular.extend(b, {
                direction: "",
                active: !0,
                leaving: !1,
                entering: !1
            }), angular.extend(c || {}, {
                direction: "",
                active: !1,
                leaving: !1,
                entering: !1
            }), a.$currentTransition = null
        }
        var i = k.indexOf(c);
        void 0 === f && (f = i > l ? "next" : "prev"), c && c !== j.currentSlide && (a.$currentTransition ? (a.$currentTransition.cancel(), b(g)) : g())
    }, a.$on("$destroy", function() {
        m = !0
    }), j.indexOfSlide = function(a) {
        return k.indexOf(a)
    }, a.next = function() {
        var b = (l + 1) % k.length;
        return a.$currentTransition ? void 0 : j.select(k[b], "next")
    }, a.prev = function() {
        var b = 0 > l - 1 ? k.length - 1 : l - 1;
        return a.$currentTransition ? void 0 : j.select(k[b], "prev")
    }, a.isActive = function(a) {
        return j.currentSlide === a
    }, a.$watch("interval", e), a.$on("$destroy", f), a.play = function() {
        i || (i = !0, e())
    }, a.pause = function() {
        a.noPause || (i = !1, f())
    }, j.addSlide = function(b, c) {
        b.$element = c, k.push(b), 1 === k.length || b.active ? (j.select(k[k.length - 1]), 1 == k.length && a.play()) : b.active = !1
    }, j.removeSlide = function(a) {
        var b = k.indexOf(a);
        k.splice(b, 1), k.length > 0 && a.active ? j.select(b >= k.length ? k[b - 1] : k[b]) : l > b && l--
    }
}]).directive("carousel", [function() {
    return {
        restrict: "EA",
        transclude: !0,
        replace: !0,
        controller: "CarouselController",
        require: "carousel",
        templateUrl: "template/carousel/carousel.html",
        scope: {
            interval: "=",
            noTransition: "=",
            noPause: "="
        }
    }
}]).directive("slide", function() {
    return {
        require: "^carousel",
        restrict: "EA",
        transclude: !0,
        replace: !0,
        templateUrl: "template/carousel/slide.html",
        scope: {
            active: "=?"
        },
        link: function(a, b, c, d) {
            d.addSlide(a, b), a.$on("$destroy", function() {
                d.removeSlide(a)
            }), a.$watch("active", function(b) {
                b && d.select(a)
            })
        }
    }
}), angular.module("ui.bootstrap.dateparser", []).service("dateParser", ["$locale", "orderByFilter", function(a, b) {
    function c(a) {
        var c = [],
            d = a.split("");
        return angular.forEach(e, function(b, e) {
            var f = a.indexOf(e);
            if (f > -1) {
                a = a.split(""), d[f] = "(" + b.regex + ")", a[f] = "$";
                for (var g = f + 1, h = f + e.length; h > g; g++) d[g] = "", a[g] = "$";
                a = a.join(""), c.push({
                    index: f,
                    apply: b.apply
                })
            }
        }), {
            regex: new RegExp("^" + d.join("") + "$"),
            map: b(c, "index")
        }
    }

    function d(a, b, c) {
        return 1 === b && c > 28 ? 29 === c && (a % 4 == 0 && a % 100 != 0 || a % 400 == 0) : 3 !== b && 5 !== b && 8 !== b && 10 !== b || 31 > c
    }
    this.parsers = {};
    var e = {
        yyyy: {
            regex: "\\d{4}",
            apply: function(a) {
                this.year = +a
            }
        },
        yy: {
            regex: "\\d{2}",
            apply: function(a) {
                this.year = +a + 2e3
            }
        },
        y: {
            regex: "\\d{1,4}",
            apply: function(a) {
                this.year = +a
            }
        },
        MMMM: {
            regex: a.DATETIME_FORMATS.MONTH.join("|"),
            apply: function(b) {
                this.month = a.DATETIME_FORMATS.MONTH.indexOf(b)
            }
        },
        MMM: {
            regex: a.DATETIME_FORMATS.SHORTMONTH.join("|"),
            apply: function(b) {
                this.month = a.DATETIME_FORMATS.SHORTMONTH.indexOf(b)
            }
        },
        MM: {
            regex: "0[1-9]|1[0-2]",
            apply: function(a) {
                this.month = a - 1
            }
        },
        M: {
            regex: "[1-9]|1[0-2]",
            apply: function(a) {
                this.month = a - 1
            }
        },
        dd: {
            regex: "[0-2][0-9]{1}|3[0-1]{1}",
            apply: function(a) {
                this.date = +a
            }
        },
        d: {
            regex: "[1-2]?[0-9]{1}|3[0-1]{1}",
            apply: function(a) {
                this.date = +a
            }
        },
        EEEE: {
            regex: a.DATETIME_FORMATS.DAY.join("|")
        },
        EEE: {
            regex: a.DATETIME_FORMATS.SHORTDAY.join("|")
        }
    };
    this.parse = function(b, e) {
        if (!angular.isString(b) || !e) return b;
        e = a.DATETIME_FORMATS[e] || e, this.parsers[e] || (this.parsers[e] = c(e));
        var f = this.parsers[e],
            g = f.regex,
            h = f.map,
            i = b.match(g);
        if (i && i.length) {
            for (var j, k = {
                    year: 1900,
                    month: 0,
                    date: 1,
                    hours: 0
                }, l = 1, m = i.length; m > l; l++) {
                var n = h[l - 1];
                n.apply && n.apply.call(k, i[l])
            }
            return d(k.year, k.month, k.date) && (j = new Date(k.year, k.month, k.date, k.hours)), j
        }
    }
}]), angular.module("ui.bootstrap.position", []).factory("$position", ["$document", "$window", function(a, b) {
    function c(a, c) {
        return a.currentStyle ? a.currentStyle[c] : b.getComputedStyle ? b.getComputedStyle(a)[c] : a.style[c]
    }

    function d(a) {
        return "static" === (c(a, "position") || "static")
    }
    var e = function(b) {
        for (var c = a[0], e = b.offsetParent || c; e && e !== c && d(e);) e = e.offsetParent;
        return e || c
    };
    return {
        position: function(b) {
            var c = this.offset(b),
                d = {
                    top: 0,
                    left: 0
                },
                f = e(b[0]);
            f != a[0] && (d = this.offset(angular.element(f)), d.top += f.clientTop - f.scrollTop, d.left += f.clientLeft - f.scrollLeft);
            var g = b[0].getBoundingClientRect();
            return {
                width: g.width || b.prop("offsetWidth"),
                height: g.height || b.prop("offsetHeight"),
                top: c.top - d.top,
                left: c.left - d.left
            }
        },
        offset: function(c) {
            var d = c[0].getBoundingClientRect();
            return {
                width: d.width || c.prop("offsetWidth"),
                height: d.height || c.prop("offsetHeight"),
                top: d.top + (b.pageYOffset || a[0].documentElement.scrollTop),
                left: d.left + (b.pageXOffset || a[0].documentElement.scrollLeft)
            }
        },
        positionElements: function(a, b, c, d) {
            var e, f, g, h, i = c.split("-"),
                j = i[0],
                k = i[1] || "center";
            e = d ? this.offset(a) : this.position(a), f = b.prop("offsetWidth"), g = b.prop("offsetHeight");
            var l = {
                    center: function() {
                        return e.left + e.width / 2 - f / 2
                    },
                    left: function() {
                        return e.left
                    },
                    right: function() {
                        return e.left + e.width
                    }
                },
                m = {
                    center: function() {
                        return e.top + e.height / 2 - g / 2
                    },
                    top: function() {
                        return e.top
                    },
                    bottom: function() {
                        return e.top + e.height
                    }
                };
            switch (j) {
                case "right":
                    h = {
                        top: m[k](),
                        left: l[j]()
                    };
                    break;
                case "left":
                    h = {
                        top: m[k](),
                        left: e.left - f
                    };
                    break;
                case "bottom":
                    h = {
                        top: m[j](),
                        left: l[k]()
                    };
                    break;
                default:
                    h = {
                        top: e.top - g,
                        left: l[k]()
                    }
            }
            return h
        }
    }
}]), angular.module("ui.bootstrap.datepicker", ["ui.bootstrap.dateparser", "ui.bootstrap.position"]).constant("datepickerConfig", {
    formatDay: "dd",
    formatMonth: "MMMM",
    formatYear: "yyyy",
    formatDayHeader: "EEE",
    formatDayTitle: "MMMM yyyy",
    formatMonthTitle: "yyyy",
    datepickerMode: "day",
    minMode: "day",
    maxMode: "year",
    showWeeks: !0,
    startingDay: 0,
    yearRange: 20,
    minDate: null,
    maxDate: null
}).controller("DatepickerController", ["$scope", "$attrs", "$parse", "$interpolate", "$timeout", "$log", "dateFilter", "datepickerConfig", function(a, b, c, d, e, f, g, h) {
    var i = this,
        j = {
            $setViewValue: angular.noop
        };
    this.modes = ["day", "month", "year"], angular.forEach(["formatDay", "formatMonth", "formatYear", "formatDayHeader", "formatDayTitle", "formatMonthTitle", "minMode", "maxMode", "showWeeks", "startingDay", "yearRange"], function(c, e) {
        i[c] = angular.isDefined(b[c]) ? 8 > e ? d(b[c])(a.$parent) : a.$parent.$eval(b[c]) : h[c]
    }), angular.forEach(["minDate", "maxDate"], function(d) {
        b[d] ? a.$parent.$watch(c(b[d]), function(a) {
            i[d] = a ? new Date(a) : null, i.refreshView()
        }) : i[d] = h[d] ? new Date(h[d]) : null
    }), a.datepickerMode = a.datepickerMode || h.datepickerMode, a.uniqueId = "datepicker-" + a.$id + "-" + Math.floor(1e4 * Math.random()), this.activeDate = angular.isDefined(b.initDate) ? a.$parent.$eval(b.initDate) : new Date, a.isActive = function(b) {
        return 0 === i.compare(b.date, i.activeDate) && (a.activeDateId = b.uid, !0)
    }, this.init = function(a) {
        j = a, j.$render = function() {
            i.render()
        }
    }, this.render = function() {
        if (j.$modelValue) {
            var a = new Date(j.$modelValue),
                b = !isNaN(a);
            b ? this.activeDate = a : f.error('Datepicker directive: "ng-model" value must be a Date object, a number of milliseconds since 01.01.1970 or a string representing an RFC2822 or ISO 8601 date.'), j.$setValidity("date", b)
        }
        this.refreshView()
    }, this.refreshView = function() {
        if (this.element) {
            this._refreshView();
            var a = j.$modelValue ? new Date(j.$modelValue) : null;
            j.$setValidity("date-disabled", !a || this.element && !this.isDisabled(a))
        }
    }, this.createDateObject = function(a, b) {
        var c = j.$modelValue ? new Date(j.$modelValue) : null;
        return {
            date: a,
            label: g(a, b),
            selected: c && 0 === this.compare(a, c),
            disabled: this.isDisabled(a),
            current: 0 === this.compare(a, new Date)
        }
    }, this.isDisabled = function(c) {
        return this.minDate && this.compare(c, this.minDate) < 0 || this.maxDate && this.compare(c, this.maxDate) > 0 || b.dateDisabled && a.dateDisabled({
            date: c,
            mode: a.datepickerMode
        })
    }, this.split = function(a, b) {
        for (var c = []; a.length > 0;) c.push(a.splice(0, b));
        return c
    }, a.select = function(b) {
        if (a.datepickerMode === i.minMode) {
            var c = j.$modelValue ? new Date(j.$modelValue) : new Date(0, 0, 0, 0, 0, 0, 0);
            c.setFullYear(b.getFullYear(), b.getMonth(), b.getDate()), j.$setViewValue(c), j.$render()
        } else i.activeDate = b, a.datepickerMode = i.modes[i.modes.indexOf(a.datepickerMode) - 1]
    }, a.move = function(a) {
        var b = i.activeDate.getFullYear() + a * (i.step.years || 0),
            c = i.activeDate.getMonth() + a * (i.step.months || 0);
        i.activeDate.setFullYear(b, c, 1), i.refreshView()
    }, a.toggleMode = function(b) {
        b = b || 1, a.datepickerMode === i.maxMode && 1 === b || a.datepickerMode === i.minMode && -1 === b || (a.datepickerMode = i.modes[i.modes.indexOf(a.datepickerMode) + b])
    }, a.keys = {
        13: "enter",
        32: "space",
        33: "pageup",
        34: "pagedown",
        35: "end",
        36: "home",
        37: "left",
        38: "up",
        39: "right",
        40: "down"
    };
    var k = function() {
        e(function() {
            i.element[0].focus()
        }, 0, !1)
    };
    a.$on("datepicker.focus", k), a.keydown = function(b) {
        var c = a.keys[b.which];
        if (c && !b.shiftKey && !b.altKey)
            if (b.preventDefault(), b.stopPropagation(), "enter" === c || "space" === c) {
                if (i.isDisabled(i.activeDate)) return;
                a.select(i.activeDate), k()
            } else !b.ctrlKey || "up" !== c && "down" !== c ? (i.handleKeyDown(c, b), i.refreshView()) : (a.toggleMode("up" === c ? 1 : -1), k())
    }
}]).directive("datepicker", function() {
    return {
        restrict: "EA",
        replace: !0,
        templateUrl: "template/datepicker/datepicker.html",
        scope: {
            datepickerMode: "=?",
            dateDisabled: "&"
        },
        require: ["datepicker", "?^ngModel"],
        controller: "DatepickerController",
        link: function(a, b, c, d) {
            var e = d[0],
                f = d[1];
            f && e.init(f)
        }
    }
}).directive("daypicker", ["dateFilter", function(a) {
    return {
        restrict: "EA",
        replace: !0,
        templateUrl: "template/datepicker/day.html",
        require: "^datepicker",
        link: function(b, c, d, e) {
            function f(a, b) {
                return 1 !== b || a % 4 != 0 || a % 100 == 0 && a % 400 != 0 ? i[b] : 29
            }

            function g(a, b) {
                var c = new Array(b),
                    d = new Date(a),
                    e = 0;
                for (d.setHours(12); b > e;) c[e++] = new Date(d), d.setDate(d.getDate() + 1);
                return c
            }

            function h(a) {
                var b = new Date(a);
                b.setDate(b.getDate() + 4 - (b.getDay() || 7));
                var c = b.getTime();
                return b.setMonth(0), b.setDate(1), Math.floor(Math.round((c - b) / 864e5) / 7) + 1
            }
            b.showWeeks = e.showWeeks, e.step = {
                months: 1
            }, e.element = c;
            var i = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
            e._refreshView = function() {
                var c = e.activeDate.getFullYear(),
                    d = e.activeDate.getMonth(),
                    f = new Date(c, d, 1),
                    i = e.startingDay - f.getDay(),
                    j = i > 0 ? 7 - i : -i,
                    k = new Date(f);
                j > 0 && k.setDate(1 - j);
                for (var l = g(k, 42), m = 0; 42 > m; m++) l[m] = angular.extend(e.createDateObject(l[m], e.formatDay), {
                    secondary: l[m].getMonth() !== d,
                    uid: b.uniqueId + "-" + m
                });
                b.labels = new Array(7);
                for (var n = 0; 7 > n; n++) b.labels[n] = {
                    abbr: a(l[n].date, e.formatDayHeader),
                    full: a(l[n].date, "EEEE")
                };
                if (b.title = a(e.activeDate, e.formatDayTitle), b.rows = e.split(l, 7), b.showWeeks) {
                    b.weekNumbers = [];
                    for (var o = h(b.rows[0][0].date), p = b.rows.length; b.weekNumbers.push(o++) < p;);
                }
            }, e.compare = function(a, b) {
                return new Date(a.getFullYear(), a.getMonth(), a.getDate()) - new Date(b.getFullYear(), b.getMonth(), b.getDate())
            }, e.handleKeyDown = function(a) {
                var b = e.activeDate.getDate();
                if ("left" === a) b -= 1;
                else if ("up" === a) b -= 7;
                else if ("right" === a) b += 1;
                else if ("down" === a) b += 7;
                else if ("pageup" === a || "pagedown" === a) {
                    var c = e.activeDate.getMonth() + ("pageup" === a ? -1 : 1);
                    e.activeDate.setMonth(c, 1), b = Math.min(f(e.activeDate.getFullYear(), e.activeDate.getMonth()), b)
                } else "home" === a ? b = 1 : "end" === a && (b = f(e.activeDate.getFullYear(), e.activeDate.getMonth()));
                e.activeDate.setDate(b)
            }, e.refreshView()
        }
    }
}]).directive("monthpicker", ["dateFilter", function(a) {
    return {
        restrict: "EA",
        replace: !0,
        templateUrl: "template/datepicker/month.html",
        require: "^datepicker",
        link: function(b, c, d, e) {
            e.step = {
                years: 1
            }, e.element = c, e._refreshView = function() {
                for (var c = new Array(12), d = e.activeDate.getFullYear(), f = 0; 12 > f; f++) c[f] = angular.extend(e.createDateObject(new Date(d, f, 1), e.formatMonth), {
                    uid: b.uniqueId + "-" + f
                });
                b.title = a(e.activeDate, e.formatMonthTitle), b.rows = e.split(c, 3)
            }, e.compare = function(a, b) {
                return new Date(a.getFullYear(), a.getMonth()) - new Date(b.getFullYear(), b.getMonth())
            }, e.handleKeyDown = function(a) {
                var b = e.activeDate.getMonth();
                if ("left" === a) b -= 1;
                else if ("up" === a) b -= 3;
                else if ("right" === a) b += 1;
                else if ("down" === a) b += 3;
                else if ("pageup" === a || "pagedown" === a) {
                    var c = e.activeDate.getFullYear() + ("pageup" === a ? -1 : 1);
                    e.activeDate.setFullYear(c)
                } else "home" === a ? b = 0 : "end" === a && (b = 11);
                e.activeDate.setMonth(b)
            }, e.refreshView()
        }
    }
}]).directive("yearpicker", ["dateFilter", function() {
    return {
        restrict: "EA",
        replace: !0,
        templateUrl: "template/datepicker/year.html",
        require: "^datepicker",
        link: function(a, b, c, d) {
            function e(a) {
                return parseInt((a - 1) / f, 10) * f + 1
            }
            var f = d.yearRange;
            d.step = {
                years: f
            }, d.element = b, d._refreshView = function() {
                for (var b = new Array(f), c = 0, g = e(d.activeDate.getFullYear()); f > c; c++) b[c] = angular.extend(d.createDateObject(new Date(g + c, 0, 1), d.formatYear), {
                    uid: a.uniqueId + "-" + c
                });
                a.title = [b[0].label, b[f - 1].label].join(" - "), a.rows = d.split(b, 5)
            }, d.compare = function(a, b) {
                return a.getFullYear() - b.getFullYear()
            }, d.handleKeyDown = function(a) {
                var b = d.activeDate.getFullYear();
                "left" === a ? b -= 1 : "up" === a ? b -= 5 : "right" === a ? b += 1 : "down" === a ? b += 5 : "pageup" === a || "pagedown" === a ? b += ("pageup" === a ? -1 : 1) * d.step.years : "home" === a ? b = e(d.activeDate.getFullYear()) : "end" === a && (b = e(d.activeDate.getFullYear()) + f - 1), d.activeDate.setFullYear(b)
            }, d.refreshView()
        }
    }
}]).constant("datepickerPopupConfig", {
    datepickerPopup: "yyyy-MM-dd",
    currentText: "Today",
    clearText: "Clear",
    closeText: "Done",
    closeOnDateSelection: !0,
    appendToBody: !1,
    showButtonBar: !0
}).directive("datepickerPopup", ["$compile", "$parse", "$document", "$position", "dateFilter", "dateParser", "datepickerPopupConfig", function(a, b, c, d, e, f, g) {
    return {
        restrict: "EA",
        require: "ngModel",
        scope: {
            isOpen: "=?",
            currentText: "@",
            clearText: "@",
            closeText: "@",
            dateDisabled: "&"
        },
        link: function(h, i, j, k) {
            function l(a) {
                return a.replace(/([A-Z])/g, function(a) {
                    return "-" + a.toLowerCase()
                })
            }

            function m(a) {
                if (a) {
                    if (angular.isDate(a) && !isNaN(a)) return k.$setValidity("date", !0), a;
                    if (angular.isString(a)) {
                        var b = f.parse(a, n) || new Date(a);
                        return isNaN(b) ? void k.$setValidity("date", !1) : (k.$setValidity("date", !0), b)
                    }
                    return void k.$setValidity("date", !1)
                }
                return k.$setValidity("date", !0), null
            }
            var n, o = angular.isDefined(j.closeOnDateSelection) ? h.$parent.$eval(j.closeOnDateSelection) : g.closeOnDateSelection,
                p = angular.isDefined(j.datepickerAppendToBody) ? h.$parent.$eval(j.datepickerAppendToBody) : g.appendToBody;
            h.showButtonBar = angular.isDefined(j.showButtonBar) ? h.$parent.$eval(j.showButtonBar) : g.showButtonBar, h.getText = function(a) {
                return h[a + "Text"] || g[a + "Text"]
            }, j.$observe("datepickerPopup", function(a) {
                n = a || g.datepickerPopup, k.$render()
            });
            var q = angular.element("<div datepicker-popup-wrap><div datepicker></div></div>");
            q.attr({
                "ng-model": "date",
                "ng-change": "dateSelection()"
            });
            var r = angular.element(q.children()[0]);
            j.datepickerOptions && angular.forEach(h.$parent.$eval(j.datepickerOptions), function(a, b) {
                r.attr(l(b), a)
            }), h.watchData = {}, angular.forEach(["minDate", "maxDate", "datepickerMode"], function(a) {
                if (j[a]) {
                    var c = b(j[a]);
                    if (h.$parent.$watch(c, function(b) {
                            h.watchData[a] = b
                        }), r.attr(l(a), "watchData." + a), "datepickerMode" === a) {
                        var d = c.assign;
                        h.$watch("watchData." + a, function(a, b) {
                            a !== b && d(h.$parent, a)
                        })
                    }
                }
            }), j.dateDisabled && r.attr("date-disabled", "dateDisabled({ date: date, mode: mode })"), k.$parsers.unshift(m), h.dateSelection = function(a) {
                angular.isDefined(a) && (h.date = a), k.$setViewValue(h.date), k.$render(), o && (h.isOpen = !1, i[0].focus())
            }, i.bind("input change keyup", function() {
                h.$apply(function() {
                    h.date = k.$modelValue
                })
            }), k.$render = function() {
                var a = k.$viewValue ? e(k.$viewValue, n) : "";
                i.val(a), h.date = m(k.$modelValue)
            };
            var s = function(a) {
                    h.isOpen && a.target !== i[0] && h.$apply(function() {
                        h.isOpen = !1
                    })
                },
                t = function(a) {
                    h.keydown(a)
                };
            i.bind("keydown", t), h.keydown = function(a) {
                27 === a.which ? (a.preventDefault(), a.stopPropagation(), h.close()) : 40 !== a.which || h.isOpen || (h.isOpen = !0)
            }, h.$watch("isOpen", function(a) {
                a ? (h.$broadcast("datepicker.focus"), h.position = p ? d.offset(i) : d.position(i), h.position.top = h.position.top + i.prop("offsetHeight"), c.bind("click", s)) : c.unbind("click", s)
            }), h.select = function(a) {
                if ("today" === a) {
                    var b = new Date;
                    angular.isDate(k.$modelValue) ? (a = new Date(k.$modelValue), a.setFullYear(b.getFullYear(), b.getMonth(), b.getDate())) : a = new Date(b.setHours(0, 0, 0, 0))
                }
                h.dateSelection(a)
            }, h.close = function() {
                h.isOpen = !1, i[0].focus()
            };
            var u = a(q)(h);
            q.remove(), p ? c.find("body").append(u) : i.after(u), h.$on("$destroy", function() {
                u.remove(), i.unbind("keydown", t), c.unbind("click", s)
            })
        }
    }
}]).directive("datepickerPopupWrap", function() {
    return {
        restrict: "EA",
        replace: !0,
        transclude: !0,
        templateUrl: "template/datepicker/popup.html",
        link: function(a, b) {
            b.bind("click", function(a) {
                a.preventDefault(), a.stopPropagation()
            })
        }
    }
}), angular.module("ui.bootstrap.dropdown", []).constant("dropdownConfig", {
    openClass: "open"
}).service("dropdownService", ["$document", function(a) {
    var b = null;
    this.open = function(e) {
        b || (a.bind("click", c), a.bind("keydown", d)), b && b !== e && (b.isOpen = !1), b = e
    }, this.close = function(e) {
        b === e && (b = null, a.unbind("click", c), a.unbind("keydown", d))
    };
    var c = function(a) {
            if (b) {
                var c = b.getToggleElement();
                a && c && c[0].contains(a.target) || b.$apply(function() {
                    b.isOpen = !1
                })
            }
        },
        d = function(a) {
            27 === a.which && (b.focusToggleElement(), c())
        }
}]).controller("DropdownController", ["$scope", "$attrs", "$parse", "dropdownConfig", "dropdownService", "$animate", function(a, b, c, d, e, f) {
    var g, h = this,
        i = a.$new(),
        j = d.openClass,
        k = angular.noop,
        l = b.onToggle ? c(b.onToggle) : angular.noop;
    this.init = function(d) {
        h.$element = d, b.isOpen && (g = c(b.isOpen), k = g.assign, a.$watch(g, function(a) {
            i.isOpen = !!a
        }))
    }, this.toggle = function(a) {
        return i.isOpen = arguments.length ? !!a : !i.isOpen
    }, this.isOpen = function() {
        return i.isOpen
    }, i.getToggleElement = function() {
        return h.toggleElement
    }, i.focusToggleElement = function() {
        h.toggleElement && h.toggleElement[0].focus()
    }, i.$watch("isOpen", function(b, c) {
        f[b ? "addClass" : "removeClass"](h.$element, j), b ? (i.focusToggleElement(), e.open(i)) : e.close(i), k(a, b), angular.isDefined(b) && b !== c && l(a, {
            open: !!b
        })
    }), a.$on("$locationChangeSuccess", function() {
        i.isOpen = !1
    }), a.$on("$destroy", function() {
        i.$destroy()
    })
}]).directive("dropdown", function() {
    return {
        controller: "DropdownController",
        link: function(a, b, c, d) {
            d.init(b)
        }
    }
}).directive("dropdownToggle", function() {
    return {
        require: "?^dropdown",
        link: function(a, b, c, d) {
            if (d) {
                d.toggleElement = b;
                var e = function(e) {
                    e.preventDefault(), b.hasClass("disabled") || c.disabled || a.$apply(function() {
                        d.toggle()
                    })
                };
                b.bind("click", e), b.attr({
                    "aria-haspopup": !0,
                    "aria-expanded": !1
                }), a.$watch(d.isOpen, function(a) {
                    b.attr("aria-expanded", !!a)
                }), a.$on("$destroy", function() {
                    b.unbind("click", e)
                })
            }
        }
    }
}), angular.module("ui.bootstrap.modal", ["ui.bootstrap.transition"]).factory("$$stackedMap", function() {
    return {
        createNew: function() {
            var a = [];
            return {
                add: function(b, c) {
                    a.push({
                        key: b,
                        value: c
                    })
                },
                get: function(b) {
                    for (var c = 0; c < a.length; c++)
                        if (b == a[c].key) return a[c]
                },
                keys: function() {
                    for (var b = [], c = 0; c < a.length; c++) b.push(a[c].key);
                    return b
                },
                top: function() {
                    return a[a.length - 1]
                },
                remove: function(b) {
                    for (var c = -1, d = 0; d < a.length; d++)
                        if (b == a[d].key) {
                            c = d;
                            break
                        }
                    return a.splice(c, 1)[0]
                },
                removeTop: function() {
                    return a.splice(a.length - 1, 1)[0]
                },
                length: function() {
                    return a.length
                }
            }
        }
    }
}).directive("modalBackdrop", ["$timeout", function(a) {
    return {
        restrict: "EA",
        replace: !0,
        templateUrl: "template/modal/backdrop.html",
        link: function(b, c, d) {
            b.backdropClass = d.backdropClass || "", b.animate = !1, a(function() {
                b.animate = !0
            })
        }
    }
}]).directive("modalWindow", ["$modalStack", "$timeout", function(a, b) {
    return {
        restrict: "EA",
        scope: {
            index: "@",
            animate: "="
        },
        replace: !0,
        transclude: !0,
        templateUrl: function(a, b) {
            return b.templateUrl || "template/modal/window.html"
        },
        link: function(c, d, e) {
            d.addClass(e.windowClass || ""), c.size = e.size, b(function() {
                c.animate = !0, d[0].querySelectorAll("[autofocus]").length || d[0].focus()
            }), c.close = function(b) {
                var c = a.getTop();
                c && c.value.backdrop && "static" != c.value.backdrop && b.target === b.currentTarget && (b.preventDefault(), b.stopPropagation(), a.dismiss(c.key, "backdrop click"))
            }
        }
    }
}]).directive("modalTransclude", function() {
    return {
        link: function(a, b, c, d, e) {
            e(a.$parent, function(a) {
                b.empty(), b.append(a)
            })
        }
    }
}).factory("$modalStack", ["$transition", "$timeout", "$document", "$compile", "$rootScope", "$$stackedMap", function(a, b, c, d, e, f) {
    function g() {
        for (var a = -1, b = n.keys(), c = 0; c < b.length; c++) n.get(b[c]).value.backdrop && (a = c);
        return a
    }

    function h(a) {
        var b = c.find("body").eq(0),
            d = n.get(a).value;
        n.remove(a), j(d.modalDomEl, d.modalScope, 300, function() {
            d.modalScope.$destroy(), b.toggleClass(m, n.length() > 0), i()
        })
    }

    function i() {
        if (k && -1 == g()) {
            var a = l;
            j(k, l, 150, function() {
                a.$destroy(), a = null
            }), k = void 0, l = void 0
        }
    }

    function j(c, d, e, f) {
        function g() {
            g.done || (g.done = !0, c.remove(), f && f())
        }
        d.animate = !1;
        var h = a.transitionEndEventName;
        if (h) {
            var i = b(g, e);
            c.bind(h, function() {
                b.cancel(i), g(), d.$apply()
            })
        } else b(g)
    }
    var k, l, m = "modal-open",
        n = f.createNew(),
        o = {};
    return e.$watch(g, function(a) {
        l && (l.index = a)
    }), c.bind("keydown", function(a) {
        var b;
        27 === a.which && (b = n.top()) && b.value.keyboard && (a.preventDefault(), e.$apply(function() {
            o.dismiss(b.key, "escape key press")
        }))
    }), o.open = function(a, b) {
        n.add(a, {
            deferred: b.deferred,
            modalScope: b.scope,
            backdrop: b.backdrop,
            keyboard: b.keyboard
        });
        var f = c.find("body").eq(0),
            h = g();
        if (h >= 0 && !k) {
            l = e.$new(!0), l.index = h;
            var i = angular.element("<div modal-backdrop></div>");
            i.attr("backdrop-class", b.backdropClass), k = d(i)(l), f.append(k)
        }
        var j = angular.element("<div modal-window></div>");
        j.attr({
            "template-url": b.windowTemplateUrl,
            "window-class": b.windowClass,
            size: b.size,
            index: n.length() - 1,
            animate: "animate"
        }).html(b.content);
        var o = d(j)(b.scope);
        n.top().value.modalDomEl = o, f.append(o), f.addClass(m)
    }, o.close = function(a, b) {
        var c = n.get(a);
        c && (c.value.deferred.resolve(b), h(a))
    }, o.dismiss = function(a, b) {
        var c = n.get(a);
        c && (c.value.deferred.reject(b), h(a))
    }, o.dismissAll = function(a) {
        for (var b = this.getTop(); b;) this.dismiss(b.key, a), b = this.getTop()
    }, o.getTop = function() {
        return n.top()
    }, o
}]).provider("$modal", function() {
    var a = {
        options: {
            backdrop: !0,
            keyboard: !0
        },
        $get: ["$injector", "$rootScope", "$q", "$http", "$templateCache", "$controller", "$modalStack", function(b, c, d, e, f, g, h) {
            function i(a) {
                return a.template ? d.when(a.template) : e.get(angular.isFunction(a.templateUrl) ? a.templateUrl() : a.templateUrl, {
                    cache: f
                }).then(function(a) {
                    return a.data
                })
            }

            function j(a) {
                var c = [];
                return angular.forEach(a, function(a) {
                    (angular.isFunction(a) || angular.isArray(a)) && c.push(d.when(b.invoke(a)))
                }), c
            }
            var k = {};
            return k.open = function(b) {
                var e = d.defer(),
                    f = d.defer(),
                    k = {
                        result: e.promise,
                        opened: f.promise,
                        close: function(a) {
                            h.close(k, a)
                        },
                        dismiss: function(a) {
                            h.dismiss(k, a)
                        }
                    };
                if (b = angular.extend({}, a.options, b), b.resolve = b.resolve || {}, !b.template && !b.templateUrl) throw new Error("One of template or templateUrl options is required.");
                var l = d.all([i(b)].concat(j(b.resolve)));
                return l.then(function(a) {
                    var d = (b.scope || c).$new();
                    d.$close = k.close, d.$dismiss = k.dismiss;
                    var f, i = {},
                        j = 1;
                    b.controller && (i.$scope = d, i.$modalInstance = k, angular.forEach(b.resolve, function(b, c) {
                        i[c] = a[j++]
                    }), f = g(b.controller, i), b.controllerAs && (d[b.controllerAs] = f)), h.open(k, {
                        scope: d,
                        deferred: e,
                        content: a[0],
                        backdrop: b.backdrop,
                        keyboard: b.keyboard,
                        backdropClass: b.backdropClass,
                        windowClass: b.windowClass,
                        windowTemplateUrl: b.windowTemplateUrl,
                        size: b.size
                    })
                }, function(a) {
                    e.reject(a)
                }), l.then(function() {
                    f.resolve(!0)
                }, function() {
                    f.reject(!1)
                }), k
            }, k
        }]
    };
    return a
}), angular.module("ui.bootstrap.pagination", []).controller("PaginationController", ["$scope", "$attrs", "$parse", function(a, b, c) {
    var d = this,
        e = {
            $setViewValue: angular.noop
        },
        f = b.numPages ? c(b.numPages).assign : angular.noop;
    this.init = function(f, g) {
            e = f, this.config = g, e.$render = function() {
                d.render()
            }, b.itemsPerPage ? a.$parent.$watch(c(b.itemsPerPage), function(b) {
                d.itemsPerPage = parseInt(b, 10), a.totalPages = d.calculateTotalPages()
            }) : this.itemsPerPage = g.itemsPerPage
        }, this.calculateTotalPages = function() {
            var b = this.itemsPerPage < 1 ? 1 : Math.ceil(a.totalItems / this.itemsPerPage);
            return Math.max(b || 0, 1)
        }, this.render = function() {
            a.page = parseInt(e.$viewValue, 10) || 1
        }, a.selectPage = function(b) {
            a.page !== b && b > 0 && b <= a.totalPages && (e.$setViewValue(b), e.$render())
        },
        a.getText = function(b) {
            return a[b + "Text"] || d.config[b + "Text"]
        }, a.noPrevious = function() {
            return 1 === a.page
        }, a.noNext = function() {
            return a.page === a.totalPages
        }, a.$watch("totalItems", function() {
            a.totalPages = d.calculateTotalPages()
        }), a.$watch("totalPages", function(b) {
            f(a.$parent, b), a.page > b ? a.selectPage(b) : e.$render()
        })
}]).constant("paginationConfig", {
    itemsPerPage: 10,
    boundaryLinks: !1,
    directionLinks: !0,
    firstText: "First",
    previousText: "Previous",
    nextText: "Next",
    lastText: "Last",
    rotate: !0
}).directive("pagination", ["$parse", "paginationConfig", function(a, b) {
    return {
        restrict: "EA",
        scope: {
            totalItems: "=",
            firstText: "@",
            previousText: "@",
            nextText: "@",
            lastText: "@"
        },
        require: ["pagination", "?ngModel"],
        controller: "PaginationController",
        templateUrl: "template/pagination/pagination.html",
        replace: !0,
        link: function(c, d, e, f) {
            function g(a, b, c) {
                return {
                    number: a,
                    text: b,
                    active: c
                }
            }

            function h(a, b) {
                var c = [],
                    d = 1,
                    e = b,
                    f = angular.isDefined(k) && b > k;
                f && (l ? (d = Math.max(a - Math.floor(k / 2), 1), (e = d + k - 1) > b && (e = b, d = e - k + 1)) : (d = (Math.ceil(a / k) - 1) * k + 1, e = Math.min(d + k - 1, b)));
                for (var h = d; e >= h; h++) {
                    var i = g(h, h, h === a);
                    c.push(i)
                }
                if (f && !l) {
                    if (d > 1) {
                        var j = g(d - 1, "...", !1);
                        c.unshift(j)
                    }
                    if (b > e) {
                        var m = g(e + 1, "...", !1);
                        c.push(m)
                    }
                }
                return c
            }
            var i = f[0],
                j = f[1];
            if (j) {
                var k = angular.isDefined(e.maxSize) ? c.$parent.$eval(e.maxSize) : b.maxSize,
                    l = angular.isDefined(e.rotate) ? c.$parent.$eval(e.rotate) : b.rotate;
                c.boundaryLinks = angular.isDefined(e.boundaryLinks) ? c.$parent.$eval(e.boundaryLinks) : b.boundaryLinks, c.directionLinks = angular.isDefined(e.directionLinks) ? c.$parent.$eval(e.directionLinks) : b.directionLinks, i.init(j, b), e.maxSize && c.$parent.$watch(a(e.maxSize), function(a) {
                    k = parseInt(a, 10), i.render()
                });
                var m = i.render;
                i.render = function() {
                    m(), c.page > 0 && c.page <= c.totalPages && (c.pages = h(c.page, c.totalPages))
                }
            }
        }
    }
}]).constant("pagerConfig", {
    itemsPerPage: 10,
    previousText: "« Previous",
    nextText: "Next »",
    align: !0
}).directive("pager", ["pagerConfig", function(a) {
    return {
        restrict: "EA",
        scope: {
            totalItems: "=",
            previousText: "@",
            nextText: "@"
        },
        require: ["pager", "?ngModel"],
        controller: "PaginationController",
        templateUrl: "template/pagination/pager.html",
        replace: !0,
        link: function(b, c, d, e) {
            var f = e[0],
                g = e[1];
            g && (b.align = angular.isDefined(d.align) ? b.$parent.$eval(d.align) : a.align, f.init(g, a))
        }
    }
}]), angular.module("ui.bootstrap.tooltip", ["ui.bootstrap.position", "ui.bootstrap.bindHtml"]).provider("$tooltip", function() {
    function a(a) {
        var b = /[A-Z]/g;
        return a.replace(b, function(a, b) {
            return (b ? "-" : "") + a.toLowerCase()
        })
    }
    var b = {
            placement: "top",
            animation: !0,
            popupDelay: 0
        },
        c = {
            mouseenter: "mouseleave",
            click: "click",
            focus: "blur"
        },
        d = {};
    this.options = function(a) {
        angular.extend(d, a)
    }, this.setTriggers = function(a) {
        angular.extend(c, a)
    }, this.$get = ["$window", "$compile", "$timeout", "$document", "$position", "$interpolate", function(e, f, g, h, i, j) {
        return function(e, k, l) {
            function m(a) {
                var b = a || n.trigger || l;
                return {
                    show: b,
                    hide: c[b] || b
                }
            }
            var n = angular.extend({}, b, d),
                o = a(e),
                p = j.startSymbol(),
                q = j.endSymbol(),
                r = "<div " + o + '-popup title="' + p + "title" + q + '" content="' + p + "content" + q + '" placement="' + p + "placement" + q + '" animation="animation" is-open="isOpen"></div>';
            return {
                restrict: "EA",
                compile: function() {
                    var a = f(r);
                    return function(b, c, d) {
                        function f() {
                            C.isOpen ? l() : j()
                        }

                        function j() {
                            (!B || b.$eval(d[k + "Enable"])) && (s(), C.popupDelay ? y || (y = g(o, C.popupDelay, !1), y.then(function(a) {
                                a()
                            })) : o()())
                        }

                        function l() {
                            b.$apply(function() {
                                p()
                            })
                        }

                        function o() {
                            return y = null, x && (g.cancel(x), x = null), C.content ? (q(), v.css({
                                top: 0,
                                left: 0,
                                display: "block"
                            }), C.$digest(), D(), C.isOpen = !0, C.$digest(), D) : angular.noop
                        }

                        function p() {
                            C.isOpen = !1, g.cancel(y), y = null, C.animation ? x || (x = g(r, 500)) : r()
                        }

                        function q() {
                            v && r(), w = C.$new(), v = a(w, function(a) {
                                z ? h.find("body").append(a) : c.after(a)
                            })
                        }

                        function r() {
                            x = null, v && (v.remove(), v = null), w && (w.$destroy(), w = null)
                        }

                        function s() {
                            t(), u()
                        }

                        function t() {
                            var a = d[k + "Placement"];
                            C.placement = angular.isDefined(a) ? a : n.placement
                        }

                        function u() {
                            var a = d[k + "PopupDelay"],
                                b = parseInt(a, 10);
                            C.popupDelay = isNaN(b) ? n.popupDelay : b
                        }
                        var v, w, x, y, z = !!angular.isDefined(n.appendToBody) && n.appendToBody,
                            A = m(void 0),
                            B = angular.isDefined(d[k + "Enable"]),
                            C = b.$new(!0),
                            D = function() {
                                var a = i.positionElements(c, v, C.placement, z);
                                a.top += "px", a.left += "px", v.css(a)
                            };
                        C.isOpen = !1, d.$observe(e, function(a) {
                            C.content = a, !a && C.isOpen && p()
                        }), d.$observe(k + "Title", function(a) {
                            C.title = a
                        });
                        var E = function() {
                            c.unbind(A.show, j), c.unbind(A.hide, l)
                        };
                        ! function() {
                            var a = d[k + "Trigger"];
                            E(), A = m(a), A.show === A.hide ? c.bind(A.show, f) : (c.bind(A.show, j), c.bind(A.hide, l))
                        }();
                        var F = b.$eval(d[k + "Animation"]);
                        C.animation = angular.isDefined(F) ? !!F : n.animation;
                        var G = b.$eval(d[k + "AppendToBody"]);
                        z = angular.isDefined(G) ? G : z, z && b.$on("$locationChangeSuccess", function() {
                            C.isOpen && p()
                        }), b.$on("$destroy", function() {
                            g.cancel(x), g.cancel(y), E(), r(), C = null
                        })
                    }
                }
            }
        }
    }]
}).directive("tooltipPopup", function() {
    return {
        restrict: "EA",
        replace: !0,
        scope: {
            content: "@",
            placement: "@",
            animation: "&",
            isOpen: "&"
        },
        templateUrl: "template/tooltip/tooltip-popup.html"
    }
}).directive("tooltip", ["$tooltip", function(a) {
    return a("tooltip", "tooltip", "mouseenter")
}]).directive("tooltipHtmlUnsafePopup", function() {
    return {
        restrict: "EA",
        replace: !0,
        scope: {
            content: "@",
            placement: "@",
            animation: "&",
            isOpen: "&"
        },
        templateUrl: "template/tooltip/tooltip-html-unsafe-popup.html"
    }
}).directive("tooltipHtmlUnsafe", ["$tooltip", function(a) {
    return a("tooltipHtmlUnsafe", "tooltip", "mouseenter")
}]), angular.module("ui.bootstrap.popover", ["ui.bootstrap.tooltip"]).directive("popoverPopup", function() {
    return {
        restrict: "EA",
        replace: !0,
        scope: {
            title: "@",
            content: "@",
            placement: "@",
            animation: "&",
            isOpen: "&"
        },
        templateUrl: "template/popover/popover.html"
    }
}).directive("popover", ["$tooltip", function(a) {
    return a("popover", "popover", "click")
}]), angular.module("ui.bootstrap.progressbar", []).constant("progressConfig", {
    animate: !0,
    max: 100
}).controller("ProgressController", ["$scope", "$attrs", "progressConfig", function(a, b, c) {
    var d = this,
        e = angular.isDefined(b.animate) ? a.$parent.$eval(b.animate) : c.animate;
    this.bars = [], a.max = angular.isDefined(b.max) ? a.$parent.$eval(b.max) : c.max, this.addBar = function(b, c) {
        e || c.css({
            transition: "none"
        }), this.bars.push(b), b.$watch("value", function(c) {
            b.percent = +(100 * c / a.max).toFixed(2)
        }), b.$on("$destroy", function() {
            c = null, d.removeBar(b)
        })
    }, this.removeBar = function(a) {
        this.bars.splice(this.bars.indexOf(a), 1)
    }
}]).directive("progress", function() {
    return {
        restrict: "EA",
        replace: !0,
        transclude: !0,
        controller: "ProgressController",
        require: "progress",
        scope: {},
        templateUrl: "template/progressbar/progress.html"
    }
}).directive("bar", function() {
    return {
        restrict: "EA",
        replace: !0,
        transclude: !0,
        require: "^progress",
        scope: {
            value: "=",
            type: "@"
        },
        templateUrl: "template/progressbar/bar.html",
        link: function(a, b, c, d) {
            d.addBar(a, b)
        }
    }
}).directive("progressbar", function() {
    return {
        restrict: "EA",
        replace: !0,
        transclude: !0,
        controller: "ProgressController",
        scope: {
            value: "=",
            type: "@"
        },
        templateUrl: "template/progressbar/progressbar.html",
        link: function(a, b, c, d) {
            d.addBar(a, angular.element(b.children()[0]))
        }
    }
}), angular.module("ui.bootstrap.rating", []).constant("ratingConfig", {
    max: 5,
    stateOn: null,
    stateOff: null
}).controller("RatingController", ["$scope", "$attrs", "ratingConfig", function(a, b, c) {
    var d = {
        $setViewValue: angular.noop
    };
    this.init = function(e) {
        d = e, d.$render = this.render, this.stateOn = angular.isDefined(b.stateOn) ? a.$parent.$eval(b.stateOn) : c.stateOn, this.stateOff = angular.isDefined(b.stateOff) ? a.$parent.$eval(b.stateOff) : c.stateOff;
        var f = angular.isDefined(b.ratingStates) ? a.$parent.$eval(b.ratingStates) : new Array(angular.isDefined(b.max) ? a.$parent.$eval(b.max) : c.max);
        a.range = this.buildTemplateObjects(f)
    }, this.buildTemplateObjects = function(a) {
        for (var b = 0, c = a.length; c > b; b++) a[b] = angular.extend({
            index: b
        }, {
            stateOn: this.stateOn,
            stateOff: this.stateOff
        }, a[b]);
        return a
    }, a.rate = function(b) {
        !a.readonly && b >= 0 && b <= a.range.length && (d.$setViewValue(b), d.$render())
    }, a.enter = function(b) {
        a.readonly || (a.value = b), a.onHover({
            value: b
        })
    }, a.reset = function() {
        a.value = d.$viewValue, a.onLeave()
    }, a.onKeydown = function(b) {
        /(37|38|39|40)/.test(b.which) && (b.preventDefault(), b.stopPropagation(), a.rate(a.value + (38 === b.which || 39 === b.which ? 1 : -1)))
    }, this.render = function() {
        a.value = d.$viewValue
    }
}]).directive("rating", function() {
    return {
        restrict: "EA",
        require: ["rating", "ngModel"],
        scope: {
            readonly: "=?",
            onHover: "&",
            onLeave: "&"
        },
        controller: "RatingController",
        templateUrl: "template/rating/rating.html",
        replace: !0,
        link: function(a, b, c, d) {
            var e = d[0],
                f = d[1];
            f && e.init(f)
        }
    }
}), angular.module("ui.bootstrap.tabs", []).controller("TabsetController", ["$scope", function(a) {
    var b = this,
        c = b.tabs = a.tabs = [];
    b.select = function(a) {
        angular.forEach(c, function(b) {
            b.active && b !== a && (b.active = !1, b.onDeselect())
        }), a.active = !0, a.onSelect()
    }, b.addTab = function(a) {
        c.push(a), 1 === c.length ? a.active = !0 : a.active && b.select(a)
    }, b.removeTab = function(a) {
        var e = c.indexOf(a);
        if (a.active && c.length > 1 && !d) {
            var f = e == c.length - 1 ? e - 1 : e + 1;
            b.select(c[f])
        }
        c.splice(e, 1)
    };
    var d;
    a.$on("$destroy", function() {
        d = !0
    })
}]).directive("tabset", function() {
    return {
        restrict: "EA",
        transclude: !0,
        replace: !0,
        scope: {
            type: "@"
        },
        controller: "TabsetController",
        templateUrl: "template/tabs/tabset.html",
        link: function(a, b, c) {
            a.vertical = !!angular.isDefined(c.vertical) && a.$parent.$eval(c.vertical), a.justified = !!angular.isDefined(c.justified) && a.$parent.$eval(c.justified)
        }
    }
}).directive("tab", ["$parse", function(a) {
    return {
        require: "^tabset",
        restrict: "EA",
        replace: !0,
        templateUrl: "template/tabs/tab.html",
        transclude: !0,
        scope: {
            active: "=?",
            heading: "@",
            onSelect: "&select",
            onDeselect: "&deselect"
        },
        controller: function() {},
        compile: function(b, c, d) {
            return function(b, c, e, f) {
                b.$watch("active", function(a) {
                    a && f.select(b)
                }), b.disabled = !1, e.disabled && b.$parent.$watch(a(e.disabled), function(a) {
                    b.disabled = !!a
                }), b.select = function() {
                    b.disabled || (b.active = !0)
                }, f.addTab(b), b.$on("$destroy", function() {
                    f.removeTab(b)
                }), b.$transcludeFn = d
            }
        }
    }
}]).directive("tabHeadingTransclude", [function() {
    return {
        restrict: "A",
        require: "^tab",
        link: function(a, b) {
            a.$watch("headingElement", function(a) {
                a && (b.html(""), b.append(a))
            })
        }
    }
}]).directive("tabContentTransclude", function() {
    function a(a) {
        return a.tagName && (a.hasAttribute("tab-heading") || a.hasAttribute("data-tab-heading") || "tab-heading" === a.tagName.toLowerCase() || "data-tab-heading" === a.tagName.toLowerCase())
    }
    return {
        restrict: "A",
        require: "^tabset",
        link: function(b, c, d) {
            var e = b.$eval(d.tabContentTransclude);
            e.$transcludeFn(e.$parent, function(b) {
                angular.forEach(b, function(b) {
                    a(b) ? e.headingElement = b : c.append(b)
                })
            })
        }
    }
}), angular.module("ui.bootstrap.timepicker", []).constant("timepickerConfig", {
    hourStep: 1,
    minuteStep: 1,
    showMeridian: !0,
    meridians: null,
    readonlyInput: !1,
    mousewheel: !0
}).controller("TimepickerController", ["$scope", "$attrs", "$parse", "$log", "$locale", "timepickerConfig", function(a, b, c, d, e, f) {
    function g() {
        var b = parseInt(a.hours, 10);
        return (a.showMeridian ? b > 0 && 13 > b : b >= 0 && 24 > b) ? (a.showMeridian && (12 === b && (b = 0), a.meridian === p[1] && (b += 12)), b) : void 0
    }

    function h() {
        var b = parseInt(a.minutes, 10);
        return b >= 0 && 60 > b ? b : void 0
    }

    function i(a) {
        return angular.isDefined(a) && a.toString().length < 2 ? "0" + a : a
    }

    function j(a) {
        k(), o.$setViewValue(new Date(n)), l(a)
    }

    function k() {
        o.$setValidity("time", !0), a.invalidHours = !1, a.invalidMinutes = !1
    }

    function l(b) {
        var c = n.getHours(),
            d = n.getMinutes();
        a.showMeridian && (c = 0 === c || 12 === c ? 12 : c % 12), a.hours = "h" === b ? c : i(c), a.minutes = "m" === b ? d : i(d), a.meridian = n.getHours() < 12 ? p[0] : p[1]
    }

    function m(a) {
        var b = new Date(n.getTime() + 6e4 * a);
        n.setHours(b.getHours(), b.getMinutes()), j()
    }
    var n = new Date,
        o = {
            $setViewValue: angular.noop
        },
        p = angular.isDefined(b.meridians) ? a.$parent.$eval(b.meridians) : f.meridians || e.DATETIME_FORMATS.AMPMS;
    this.init = function(c, d) {
        o = c, o.$render = this.render;
        var e = d.eq(0),
            g = d.eq(1);
        (angular.isDefined(b.mousewheel) ? a.$parent.$eval(b.mousewheel) : f.mousewheel) && this.setupMousewheelEvents(e, g), a.readonlyInput = angular.isDefined(b.readonlyInput) ? a.$parent.$eval(b.readonlyInput) : f.readonlyInput, this.setupInputEvents(e, g)
    };
    var q = f.hourStep;
    b.hourStep && a.$parent.$watch(c(b.hourStep), function(a) {
        q = parseInt(a, 10)
    });
    var r = f.minuteStep;
    b.minuteStep && a.$parent.$watch(c(b.minuteStep), function(a) {
        r = parseInt(a, 10)
    }), a.showMeridian = f.showMeridian, b.showMeridian && a.$parent.$watch(c(b.showMeridian), function(b) {
        if (a.showMeridian = !!b, o.$error.time) {
            var c = g(),
                d = h();
            angular.isDefined(c) && angular.isDefined(d) && (n.setHours(c), j())
        } else l()
    }), this.setupMousewheelEvents = function(b, c) {
        var d = function(a) {
            a.originalEvent && (a = a.originalEvent);
            var b = a.wheelDelta ? a.wheelDelta : -a.deltaY;
            return a.detail || b > 0
        };
        b.bind("mousewheel wheel", function(b) {
            a.$apply(d(b) ? a.incrementHours() : a.decrementHours()), b.preventDefault()
        }), c.bind("mousewheel wheel", function(b) {
            a.$apply(d(b) ? a.incrementMinutes() : a.decrementMinutes()), b.preventDefault()
        })
    }, this.setupInputEvents = function(b, c) {
        if (a.readonlyInput) return a.updateHours = angular.noop, void(a.updateMinutes = angular.noop);
        var d = function(b, c) {
            o.$setViewValue(null), o.$setValidity("time", !1), angular.isDefined(b) && (a.invalidHours = b), angular.isDefined(c) && (a.invalidMinutes = c)
        };
        a.updateHours = function() {
            var a = g();
            angular.isDefined(a) ? (n.setHours(a), j("h")) : d(!0)
        }, b.bind("blur", function() {
            !a.invalidHours && a.hours < 10 && a.$apply(function() {
                a.hours = i(a.hours)
            })
        }), a.updateMinutes = function() {
            var a = h();
            angular.isDefined(a) ? (n.setMinutes(a), j("m")) : d(void 0, !0)
        }, c.bind("blur", function() {
            !a.invalidMinutes && a.minutes < 10 && a.$apply(function() {
                a.minutes = i(a.minutes)
            })
        })
    }, this.render = function() {
        var a = o.$modelValue ? new Date(o.$modelValue) : null;
        isNaN(a) ? (o.$setValidity("time", !1), d.error('Timepicker directive: "ng-model" value must be a Date object, a number of milliseconds since 01.01.1970 or a string representing an RFC2822 or ISO 8601 date.')) : (a && (n = a), k(), l())
    }, a.incrementHours = function() {
        m(60 * q)
    }, a.decrementHours = function() {
        m(60 * -q)
    }, a.incrementMinutes = function() {
        m(r)
    }, a.decrementMinutes = function() {
        m(-r)
    }, a.toggleMeridian = function() {
        m(720 * (n.getHours() < 12 ? 1 : -1))
    }
}]).directive("timepicker", function() {
    return {
        restrict: "EA",
        require: ["timepicker", "?^ngModel"],
        controller: "TimepickerController",
        replace: !0,
        scope: {},
        templateUrl: "template/timepicker/timepicker.html",
        link: function(a, b, c, d) {
            var e = d[0],
                f = d[1];
            f && e.init(f, b.find("input"))
        }
    }
}), angular.module("ui.bootstrap.typeahead", ["ui.bootstrap.position", "ui.bootstrap.bindHtml"]).factory("typeaheadParser", ["$parse", function(a) {
    var b = /^\s*([\s\S]+?)(?:\s+as\s+([\s\S]+?))?\s+for\s+(?:([\$\w][\$\w\d]*))\s+in\s+([\s\S]+?)$/;
    return {
        parse: function(c) {
            var d = c.match(b);
            if (!d) throw new Error('Expected typeahead specification in form of "_modelValue_ (as _label_)? for _item_ in _collection_" but got "' + c + '".');
            return {
                itemName: d[3],
                source: a(d[4]),
                viewMapper: a(d[2] || d[1]),
                modelMapper: a(d[1])
            }
        }
    }
}]).directive("typeahead", ["$compile", "$parse", "$q", "$timeout", "$document", "$position", "typeaheadParser", function(a, b, c, d, e, f, g) {
    var h = [9, 13, 27, 38, 40];
    return {
        require: "ngModel",
        link: function(i, j, k, l) {
            var m, n = i.$eval(k.typeaheadMinLength) || 1,
                o = i.$eval(k.typeaheadWaitMs) || 0,
                p = !1 !== i.$eval(k.typeaheadEditable),
                q = b(k.typeaheadLoading).assign || angular.noop,
                r = b(k.typeaheadOnSelect),
                s = k.typeaheadInputFormatter ? b(k.typeaheadInputFormatter) : void 0,
                t = !!k.typeaheadAppendToBody && i.$eval(k.typeaheadAppendToBody),
                u = !1 !== i.$eval(k.typeaheadFocusFirst),
                v = b(k.ngModel).assign,
                w = g.parse(k.typeahead),
                x = i.$new();
            i.$on("$destroy", function() {
                x.$destroy()
            });
            var y = "typeahead-" + x.$id + "-" + Math.floor(1e4 * Math.random());
            j.attr({
                "aria-autocomplete": "list",
                "aria-expanded": !1,
                "aria-owns": y
            });
            var z = angular.element("<div typeahead-popup></div>");
            z.attr({
                id: y,
                matches: "matches",
                active: "activeIdx",
                select: "select(activeIdx)",
                query: "query",
                position: "position"
            }), angular.isDefined(k.typeaheadTemplateUrl) && z.attr("template-url", k.typeaheadTemplateUrl);
            var A = function() {
                    x.matches = [], x.activeIdx = -1, j.attr("aria-expanded", !1)
                },
                B = function(a) {
                    return y + "-option-" + a
                };
            x.$watch("activeIdx", function(a) {
                0 > a ? j.removeAttr("aria-activedescendant") : j.attr("aria-activedescendant", B(a))
            });
            var C = function(a) {
                var b = {
                    $viewValue: a
                };
                q(i, !0), c.when(w.source(i, b)).then(function(c) {
                    var d = a === l.$viewValue;
                    if (d && m)
                        if (c.length > 0) {
                            x.activeIdx = u ? 0 : -1, x.matches.length = 0;
                            for (var e = 0; e < c.length; e++) b[w.itemName] = c[e], x.matches.push({
                                id: B(e),
                                label: w.viewMapper(x, b),
                                model: c[e]
                            });
                            x.query = a, x.position = t ? f.offset(j) : f.position(j), x.position.top = x.position.top + j.prop("offsetHeight"), j.attr("aria-expanded", !0)
                        } else A();
                    d && q(i, !1)
                }, function() {
                    A(), q(i, !1)
                })
            };
            A(), x.query = void 0;
            var D, E = function(a) {
                    D = d(function() {
                        C(a)
                    }, o)
                },
                F = function() {
                    D && d.cancel(D)
                };
            l.$parsers.unshift(function(a) {
                return m = !0, a && a.length >= n ? o > 0 ? (F(), E(a)) : C(a) : (q(i, !1), F(), A()), p ? a : a ? void l.$setValidity("editable", !1) : (l.$setValidity("editable", !0), a)
            }), l.$formatters.push(function(a) {
                var b, c, d = {};
                return s ? (d.$model = a, s(i, d)) : (d[w.itemName] = a, b = w.viewMapper(i, d), d[w.itemName] = void 0, c = w.viewMapper(i, d), b !== c ? b : a)
            }), x.select = function(a) {
                var b, c, e = {};
                e[w.itemName] = c = x.matches[a].model, b = w.modelMapper(i, e), v(i, b), l.$setValidity("editable", !0), r(i, {
                    $item: c,
                    $model: b,
                    $label: w.viewMapper(i, e)
                }), A(), d(function() {
                    j[0].focus()
                }, 0, !1)
            }, j.bind("keydown", function(a) {
                0 !== x.matches.length && -1 !== h.indexOf(a.which) && (-1 != x.activeIdx || 13 !== a.which && 9 !== a.which) && (a.preventDefault(), 40 === a.which ? (x.activeIdx = (x.activeIdx + 1) % x.matches.length, x.$digest()) : 38 === a.which ? (x.activeIdx = (x.activeIdx > 0 ? x.activeIdx : x.matches.length) - 1, x.$digest()) : 13 === a.which || 9 === a.which ? x.$apply(function() {
                    x.select(x.activeIdx)
                }) : 27 === a.which && (a.stopPropagation(), A(), x.$digest()))
            }), j.bind("blur", function() {
                m = !1
            });
            var G = function(a) {
                j[0] !== a.target && (A(), x.$digest())
            };
            e.bind("click", G), i.$on("$destroy", function() {
                e.unbind("click", G), t && H.remove()
            });
            var H = a(z)(x);
            t ? e.find("body").append(H) : j.after(H)
        }
    }
}]).directive("typeaheadPopup", function() {
    return {
        restrict: "EA",
        scope: {
            matches: "=",
            query: "=",
            active: "=",
            position: "=",
            select: "&"
        },
        replace: !0,
        templateUrl: "template/typeahead/typeahead-popup.html",
        link: function(a, b, c) {
            a.templateUrl = c.templateUrl, a.isOpen = function() {
                return a.matches.length > 0
            }, a.isActive = function(b) {
                return a.active == b
            }, a.selectActive = function(b) {
                a.active = b
            }, a.selectMatch = function(b) {
                a.select({
                    activeIdx: b
                })
            }
        }
    }
}).directive("typeaheadMatch", ["$http", "$templateCache", "$compile", "$parse", function(a, b, c, d) {
    return {
        restrict: "EA",
        scope: {
            index: "=",
            match: "=",
            query: "="
        },
        link: function(e, f, g) {
            var h = d(g.templateUrl)(e.$parent) || "template/typeahead/typeahead-match.html";
            a.get(h, {
                cache: b
            }).success(function(a) {
                f.replaceWith(c(a.trim())(e))
            })
        }
    }
}]).filter("typeaheadHighlight", function() {
    function a(a) {
        return a.replace(/([.?*+^$[\]\\(){}|-])/g, "\\$1")
    }
    return function(b, c) {
        return c ? ("" + b).replace(new RegExp(a(c), "gi"), "<strong>$&</strong>") : b
    }
}), ! function(a) {
    "use strict";
    var b = a.HTMLCanvasElement && a.HTMLCanvasElement.prototype,
        c = a.Blob && function() {
            try {
                return Boolean(new Blob)
            } catch (a) {
                return !1
            }
        }(),
        d = c && a.Uint8Array && function() {
            try {
                return 100 === new Blob([new Uint8Array(100)]).size
            } catch (a) {
                return !1
            }
        }(),
        e = a.BlobBuilder || a.WebKitBlobBuilder || a.MozBlobBuilder || a.MSBlobBuilder,
        f = (c || e) && a.atob && a.ArrayBuffer && a.Uint8Array && function(a) {
            var b, f, g, h, i, j;
            for (b = a.split(",")[0].indexOf("base64") >= 0 ? atob(a.split(",")[1]) : decodeURIComponent(a.split(",")[1]), f = new ArrayBuffer(b.length), g = new Uint8Array(f), h = 0; h < b.length; h += 1) g[h] = b.charCodeAt(h);
            return i = a.split(",")[0].split(":")[1].split(";")[0], c ? new Blob([d ? g : f], {
                type: i
            }) : (j = new e, j.append(f), j.getBlob(i))
        };
    a.HTMLCanvasElement && !b.toBlob && (b.mozGetAsFile ? b.toBlob = function(a, c, d) {
        a(d && b.toDataURL && f ? f(this.toDataURL(c, d)) : this.mozGetAsFile("blob", c))
    } : b.toDataURL && f && (b.toBlob = function(a, b, c) {
        a(f(this.toDataURL(b, c)))
    })), a.dataURLtoBlob = f
}(window),
function(a, b) {
    "use strict";

    function c(a, b, c, d, e) {
        var f = {
            type: c.type || c,
            target: a,
            result: d
        };
        S(f, e), b(f)
    }

    function d(a) {
        return u && !!u.prototype["readAs" + a]
    }

    function e(a, e, f, g) {
        if (Y.isBlob(a) && d(f)) {
            var h = new u;
            T(h, M, function b(d) {
                var f = d.type;
                "progress" == f ? c(a, e, d, d.target.result, {
                    loaded: d.loaded,
                    total: d.total
                }) : "loadend" == f ? (U(h, M, b), h = null) : c(a, e, d, d.target.result)
            });
            try {
                g ? h["readAs" + f](a, g) : h["readAs" + f](a)
            } catch (d) {
                c(a, e, "error", b, {
                    error: d.toString()
                })
            }
        } else c(a, e, "error", b, {
            error: "filreader_not_support_" + f
        })
    }

    function f(a, b) {
        if (!a.type && a.size % 4096 == 0 && a.size <= 102400)
            if (u) try {
                var c = new u;
                V(c, M, function(a) {
                    var d = "error" != a.type;
                    b(d), d && c.abort()
                }), c.readAsDataURL(a)
            } catch (a) {
                b(!1)
            } else b(null);
            else b(!0)
    }

    function g(a) {
        var b;
        return a.getAsEntry ? b = a.getAsEntry() : a.webkitGetAsEntry && (b = a.webkitGetAsEntry()), b
    }

    function h(a, b) {
        if (a)
            if (a.isFile) a.file(function(c) {
                c.fullPath = a.fullPath, b(!1, [c])
            }, function(a) {
                b("FileError.code: " + a.code)
            });
            else if (a.isDirectory) {
            var c = a.createReader(),
                d = [];
            c.readEntries(function(a) {
                Y.afor(a, function(a, c) {
                    h(c, function(c, e) {
                        c ? Y.log(c) : d = d.concat(e), a ? a() : b(!1, d)
                    })
                })
            }, function(a) {
                b("directory_reader: " + a)
            })
        } else h(g(a), b);
        else b("invalid entry")
    }

    function i(a) {
        var b = {};
        return R(a, function(a, c) {
            a && "object" == typeof a && void 0 === a.nodeType && (a = S({}, a)), b[c] = a
        }), b
    }

    function j(a) {
        return F.test(a && a.tagName)
    }

    function k(a) {
        return (a.originalEvent || a || "").dataTransfer || {}
    }

    function l(a) {
        var b;
        for (b in a)
            if (a.hasOwnProperty(b) && !(a[b] instanceof Object || "overlay" === b || "filter" === b)) return !0;
        return !1
    }
    var m = 1,
        n = function() {},
        o = a.document,
        p = o.doctype || {},
        q = a.navigator.userAgent,
        r = a.createObjectURL && a || a.URL && URL.revokeObjectURL && URL || a.webkitURL && webkitURL,
        s = a.Blob,
        t = a.File,
        u = a.FileReader,
        v = a.FormData,
        w = a.XMLHttpRequest,
        x = a.jQuery,
        y = !(!(t && u && (a.Uint8Array || v || w.prototype.sendAsBinary)) || /safari\//i.test(q) && !/chrome\//i.test(q) && /windows/i.test(q)),
        z = y && "withCredentials" in new w,
        A = y && !!s && !!(s.prototype.webkitSlice || s.prototype.mozSlice || s.prototype.slice),
        B = a.dataURLtoBlob,
        C = /img/i,
        D = /canvas/i,
        E = /img|canvas/i,
        F = /input/i,
        G = /^data:[^,]+,/,
        H = {}.toString,
        I = a.Math,
        J = function(b) {
            return b = new a.Number(I.pow(1024, b)), b.from = function(a) {
                return I.round(a * this)
            }, b
        },
        K = {},
        L = [],
        M = "abort progress error load loadend",
        N = "status statusText readyState response responseXML responseText responseBody".split(" "),
        O = "currentTarget",
        P = "preventDefault",
        Q = function(a) {
            return a && "length" in a
        },
        R = function(a, b, c) {
            if (a)
                if (Q(a))
                    for (var d = 0, e = a.length; e > d; d++) d in a && b.call(c, a[d], d, a);
                else
                    for (var f in a) a.hasOwnProperty(f) && b.call(c, a[f], f, a)
        },
        S = function(a) {
            for (var b = arguments, c = 1, d = function(b, c) {
                    a[c] = b
                }; c < b.length; c++) R(b[c], d);
            return a
        },
        T = function(a, b, c) {
            if (a) {
                var d = Y.uid(a);
                K[d] || (K[d] = {});
                var e = u && a && a instanceof u;
                R(b.split(/\s+/), function(b) {
                    x && !e ? x.event.add(a, b, c) : (K[d][b] || (K[d][b] = []), K[d][b].push(c), a.addEventListener ? a.addEventListener(b, c, !1) : a.attachEvent ? a.attachEvent("on" + b, c) : a["on" + b] = c)
                })
            }
        },
        U = function(a, b, c) {
            if (a) {
                var d = Y.uid(a),
                    e = K[d] || {},
                    f = u && a && a instanceof u;
                R(b.split(/\s+/), function(b) {
                    if (x && !f) x.event.remove(a, b, c);
                    else {
                        for (var d = e[b] || [], g = d.length; g--;)
                            if (d[g] === c) {
                                d.splice(g, 1);
                                break
                            }
                        a.addEventListener ? a.removeEventListener(b, c, !1) : a.detachEvent ? a.detachEvent("on" + b, c) : a["on" + b] = null
                    }
                })
            }
        },
        V = function(a, b, c) {
            T(a, b, function d(e) {
                U(a, b, d), c(e)
            })
        },
        W = function(b) {
            return b.target || (b.target = a.event && a.event.srcElement || o), 3 === b.target.nodeType && (b.target = b.target.parentNode), b
        },
        X = function(a) {
            var b = o.createElement("input");
            return b.setAttribute("type", "file"), a in b
        },
        Y = {
            version: "2.0.7",
            cors: !1,
            html5: !0,
            media: !1,
            formData: !0,
            multiPassResize: !0,
            debug: !1,
            pingUrl: !1,
            multiFlash: !1,
            flashAbortTimeout: 0,
            withCredentials: !0,
            staticPath: "./dist/",
            flashUrl: 0,
            flashImageUrl: 0,
            postNameConcat: function(a, b) {
                return a + (null != b ? "[" + b + "]" : "")
            },
            ext2mime: {
                jpg: "image/jpeg",
                tif: "image/tiff",
                txt: "text/plain"
            },
            accept: {
                "image/*": "art bm bmp dwg dxf cbr cbz fif fpx gif ico iefs jfif jpe jpeg jpg jps jut mcf nap nif pbm pcx pgm pict pm png pnm qif qtif ras rast rf rp svf tga tif tiff xbm xbm xpm xwd",
                "audio/*": "m4a flac aac rm mpa wav wma ogg mp3 mp2 m3u mod amf dmf dsm far gdm imf it m15 med okt s3m stm sfx ult uni xm sid ac3 dts cue aif aiff wpl ape mac mpc mpp shn wv nsf spc gym adplug adx dsp adp ymf ast afc hps xs",
                "video/*": "m4v 3gp nsv ts ty strm rm rmvb m3u ifo mov qt divx xvid bivx vob nrg img iso pva wmv asf asx ogm m2v avi bin dat dvr-ms mpg mpeg mp4 mkv avc vp3 svq3 nuv viv dv fli flv wpl"
            },
            uploadRetry: 0,
            networkDownRetryTimeout: 5e3,
            chunkSize: 0,
            chunkUploadRetry: 0,
            chunkNetworkDownRetryTimeout: 2e3,
            KB: J(1),
            MB: J(2),
            GB: J(3),
            TB: J(4),
            EMPTY_PNG: "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAAC0lEQVQIW2NkAAIAAAoAAggA9GkAAAAASUVORK5CYII=",
            expando: "fileapi" + (new Date).getTime(),
            uid: function(a) {
                return a ? a[Y.expando] = a[Y.expando] || Y.uid() : (++m, Y.expando + m)
            },
            log: function() {
                Y.debug && a.console && console.log && (console.log.apply ? console.log.apply(console, arguments) : console.log([].join.call(arguments, " ")))
            },
            newImage: function(a, b) {
                var c = o.createElement("img");
                return b && Y.event.one(c, "error load", function(a) {
                    b("error" == a.type, c), c = null
                }), c.src = a, c
            },
            getXHR: function() {
                var b;
                if (w) b = new w;
                else if (a.ActiveXObject) try {
                    b = new ActiveXObject("MSXML2.XMLHttp.3.0")
                } catch (a) {
                    b = new ActiveXObject("Microsoft.XMLHTTP")
                }
                return b
            },
            isArray: Q,
            support: {
                dnd: z && "ondrop" in o.createElement("div"),
                cors: z,
                html5: y,
                chunked: A,
                dataURI: !0,
                accept: X("accept"),
                multiple: X("multiple")
            },
            event: {
                on: T,
                off: U,
                one: V,
                fix: W
            },
            throttle: function(b, c) {
                var d, e;
                return function() {
                    e = arguments, d || (b.apply(a, e), d = setTimeout(function() {
                        d = 0, b.apply(a, e)
                    }, c))
                }
            },
            F: function() {},
            parseJSON: function(b) {
                return a.JSON && JSON.parse ? JSON.parse(b) : new Function("return (" + b.replace(/([\r\n])/g, "\\$1") + ");")()
            },
            trim: function(a) {
                return a = String(a), a.trim ? a.trim() : a.replace(/^\s+|\s+$/g, "")
            },
            defer: function() {
                var a, c, d = [],
                    e = {
                        resolve: function(b, f) {
                            for (e.resolve = n, c = b || !1, a = f; f = d.shift();) f(c, a)
                        },
                        then: function(e) {
                            c !== b ? e(c, a) : d.push(e)
                        }
                    };
                return e
            },
            queue: function(a) {
                var b = 0,
                    c = 0,
                    d = !1,
                    e = !1,
                    f = {
                        inc: function() {
                            c++
                        },
                        next: function() {
                            b++, setTimeout(f.check, 0)
                        },
                        check: function() {
                            b >= c && !d && f.end()
                        },
                        isFail: function() {
                            return d
                        },
                        fail: function() {
                            !d && a(d = !0)
                        },
                        end: function() {
                            e || (e = !0, a())
                        }
                    };
                return f
            },
            each: R,
            afor: function(a, b) {
                var c = 0,
                    d = a.length;
                Q(a) && d-- ? function e() {
                    b(d != c && e, a[c], c++)
                }() : b(!1)
            },
            extend: S,
            isFile: function(a) {
                return "[object File]" === H.call(a)
            },
            isBlob: function(a) {
                return this.isFile(a) || "[object Blob]" === H.call(a)
            },
            isCanvas: function(a) {
                return a && D.test(a.nodeName)
            },
            getFilesFilter: function(a) {
                return a = "string" == typeof a ? a : a.getAttribute && a.getAttribute("accept") || "", a ? new RegExp("(" + a.replace(/\./g, "\\.").replace(/,/g, "|") + ")$", "i") : /./
            },
            readAsDataURL: function(a, b) {
                Y.isCanvas(a) ? c(a, b, "load", Y.toDataURL(a)) : e(a, b, "DataURL")
            },
            readAsBinaryString: function(a, b) {
                d("BinaryString") ? e(a, b, "BinaryString") : e(a, function(a) {
                    if ("load" == a.type) try {
                        a.result = Y.toBinaryString(a.result)
                    } catch (b) {
                        a.type = "error", a.message = b.toString()
                    }
                    b(a)
                }, "DataURL")
            },
            readAsArrayBuffer: function(a, b) {
                e(a, b, "ArrayBuffer")
            },
            readAsText: function(a, b, c) {
                c || (c = b, b = "utf-8"), e(a, c, "Text", b)
            },
            toDataURL: function(a, b) {
                return "string" == typeof a ? a : a.toDataURL ? a.toDataURL(b || "image/png") : void 0
            },
            toBinaryString: function(b) {
                return a.atob(Y.toDataURL(b).replace(G, ""))
            },
            readAsImage: function(a, d, e) {
                if (Y.isFile(a))
                    if (r) {
                        var f = r.createObjectURL(a);
                        f === b ? c(a, d, "error") : Y.readAsImage(f, d, e)
                    } else Y.readAsDataURL(a, function(b) {
                        "load" == b.type ? Y.readAsImage(b.result, d, e) : (e || "error" == b.type) && c(a, d, b, null, {
                            loaded: b.loaded,
                            total: b.total
                        })
                    });
                else if (Y.isCanvas(a)) c(a, d, "load", a);
                else if (C.test(a.nodeName))
                    if (a.complete) c(a, d, "load", a);
                    else {
                        var g = "error abort load";
                        V(a, g, function b(e) {
                            "load" == e.type && r && r.revokeObjectURL(a.src), U(a, g, b), c(a, d, e, a)
                        })
                    } else if (a.iframe) c(a, d, {
                    type: "error"
                });
                else {
                    var h = Y.newImage(a.dataURL || a);
                    Y.readAsImage(h, d, e)
                }
            },
            checkFileObj: function(a) {
                var b = {},
                    c = Y.accept;
                return "object" == typeof a ? b = a : b.name = (a + "").split(/\\|\//g).pop(), null == b.type && (b.type = b.name.split(".").pop()), R(c, function(a, c) {
                    a = new RegExp(a.replace(/\s/g, "|"), "i"), (a.test(b.type) || Y.ext2mime[b.type]) && (b.type = Y.ext2mime[b.type] || c.split("/")[0] + "/" + b.type)
                }), b
            },
            getDropFiles: function(a, b) {
                var c = [],
                    d = k(a),
                    e = Q(d.items) && d.items[0] && g(d.items[0]),
                    i = Y.queue(function() {
                        b(c)
                    });
                R((e ? d.items : d.files) || [], function(a) {
                    i.inc();
                    try {
                        e ? h(a, function(a, b) {
                            a ? Y.log("[err] getDropFiles:", a) : c.push.apply(c, b), i.next()
                        }) : f(a, function(b) {
                            b && c.push(a), i.next()
                        })
                    } catch (a) {
                        i.next(), Y.log("[err] getDropFiles: ", a)
                    }
                }), i.check()
            },
            getFiles: function(a, b, c) {
                var d = [];
                return c ? (Y.filterFiles(Y.getFiles(a), b, c), null) : (a.jquery && (a.each(function() {
                    d = d.concat(Y.getFiles(this))
                }), a = d, d = []), "string" == typeof b && (b = Y.getFilesFilter(b)), a.originalEvent ? a = W(a.originalEvent) : a.srcElement && (a = W(a)), a.dataTransfer ? a = a.dataTransfer : a.target && (a = a.target), a.files ? (d = a.files, y || (d[0].blob = a, d[0].iframe = !0)) : !y && j(a) ? Y.trim(a.value) && (d = [Y.checkFileObj(a.value)], d[0].blob = a, d[0].iframe = !0) : Q(a) && (d = a), Y.filter(d, function(a) {
                    return !b || b.test(a.name)
                }))
            },
            getTotalSize: function(a) {
                for (var b = 0, c = a && a.length; c--;) b += a[c].size;
                return b
            },
            getInfo: function(a, b) {
                var c = {},
                    d = L.concat();
                Y.isFile(a) ? function e() {
                    var f = d.shift();
                    f ? f.test(a.type) ? f(a, function(a, d) {
                        a ? b(a) : (S(c, d), e())
                    }) : e() : b(!1, c)
                }() : b("not_support_info", c)
            },
            addInfoReader: function(a, b) {
                b.test = function(b) {
                    return a.test(b)
                }, L.push(b)
            },
            filter: function(a, b) {
                for (var c, d = [], e = 0, f = a.length; f > e; e++) e in a && (c = a[e], b.call(c, c, e, a) && d.push(c));
                return d
            },
            filterFiles: function(a, b, c) {
                if (a.length) {
                    var d, e = a.concat(),
                        f = [],
                        g = [];
                    ! function a() {
                        e.length ? (d = e.shift(), Y.getInfo(d, function(c, e) {
                            (b(d, !c && e) ? f : g).push(d), a()
                        })) : c(f, g)
                    }()
                } else c([], a)
            },
            upload: function(a) {
                a = S({
                    jsonp: "callback",
                    prepare: Y.F,
                    beforeupload: Y.F,
                    upload: Y.F,
                    fileupload: Y.F,
                    fileprogress: Y.F,
                    filecomplete: Y.F,
                    progress: Y.F,
                    complete: Y.F,
                    pause: Y.F,
                    imageOriginal: !0,
                    chunkSize: Y.chunkSize,
                    chunkUploadRetry: Y.chunkUploadRetry,
                    uploadRetry: Y.uploadRetry
                }, a), a.imageAutoOrientation && !a.imageTransform && (a.imageTransform = {
                    rotate: "auto"
                });
                var b, c = new Y.XHR(a),
                    d = this._getFilesDataArray(a.files),
                    e = this,
                    f = 0,
                    g = 0,
                    h = !1;
                return R(d, function(a) {
                    f += a.size
                }), c.files = [], R(d, function(a) {
                    c.files.push(a.file)
                }), c.total = f, c.loaded = 0, c.filesLeft = d.length, a.beforeupload(c, a), b = function() {
                    var j = d.shift(),
                        k = j && j.file,
                        l = !1,
                        m = i(a);
                    if (c.filesLeft = d.length, k && k.name === Y.expando && (k = null, Y.log("[warn] FileAPI.upload() — called without files")), ("abort" != c.statusText || c.current) && j) {
                        if (h = !1, c.currentFile = k, k && !1 === a.prepare(k, m)) return void b.call(e);
                        m.file = k, e._getFormData(m, j, function(h) {
                            g || a.upload(c, a);
                            var i = new Y.XHR(S({}, m, {
                                upload: k ? function() {
                                    a.fileupload(k, i, m)
                                } : n,
                                progress: k ? function(b) {
                                    l || (l = b.loaded === b.total, a.fileprogress({
                                        type: "progress",
                                        total: j.total = b.total,
                                        loaded: j.loaded = b.loaded
                                    }, k, i, m), a.progress({
                                        type: "progress",
                                        total: f,
                                        loaded: c.loaded = g + j.size * (b.loaded / b.total) | 0
                                    }, k, i, m))
                                } : n,
                                complete: function(d) {
                                    R(N, function(a) {
                                        c[a] = i[a]
                                    }), k && (j.total = j.total || j.size, j.loaded = j.total, d || (this.progress(j), l = !0, g += j.size, c.loaded = g), a.filecomplete(d, i, k, m)), setTimeout(function() {
                                        b.call(e)
                                    }, 0)
                                }
                            }));
                            c.abort = function(a) {
                                a || (d.length = 0), this.current = a, i.abort()
                            }, i.send(h)
                        })
                    } else {
                        var o = 200 == c.status || 201 == c.status || 204 == c.status;
                        a.complete(!o && (c.statusText || "error"), c, a), h = !0
                    }
                }, setTimeout(b, 0), c.append = function(a, g) {
                    a = Y._getFilesDataArray([].concat(a)), R(a, function(a) {
                        f += a.size, c.files.push(a.file), g ? d.unshift(a) : d.push(a)
                    }), c.statusText = "", h && b.call(e)
                }, c.remove = function(a) {
                    for (var b, c = d.length; c--;) d[c].file == a && (b = d.splice(c, 1), f -= b.size);
                    return b
                }, c
            },
            _getFilesDataArray: function(a) {
                var b = [],
                    c = {};
                if (j(a)) {
                    var d = Y.getFiles(a);
                    c[a.name || "file"] = null !== a.getAttribute("multiple") ? d : d[0]
                } else Q(a) && j(a[0]) ? R(a, function(a) {
                    c[a.name || "file"] = Y.getFiles(a)
                }) : c = a;
                return R(c, function a(c, d) {
                    Q(c) ? R(c, function(b) {
                        a(b, d)
                    }) : c && (c.name || c.image) && b.push({
                        name: d,
                        file: c,
                        size: c.size,
                        total: c.size,
                        loaded: 0
                    })
                }), b.length || b.push({
                    file: {
                        name: Y.expando
                    }
                }), b
            },
            _getFormData: function(a, b, c) {
                var d = b.file,
                    e = b.name,
                    f = d.name,
                    g = d.type,
                    h = Y.support.transform && a.imageTransform,
                    i = new Y.Form,
                    j = Y.queue(function() {
                        c(i)
                    }),
                    k = h && l(h),
                    m = Y.postNameConcat;
                R(a.data, function a(b, c) {
                        "object" == typeof b ? R(b, function(b, d) {
                            a(b, m(c, d))
                        }) : i.append(c, b)
                    }),
                    function b(c) {
                        c.image ? (j.inc(), c.toData(function(a, c) {
                            f = f || (new Date).getTime() + ".png", b(c), j.next()
                        })) : Y.Image && h && (/^image/.test(c.type) || E.test(c.nodeName)) ? (j.inc(), k && (h = [h]), Y.Image.transform(c, h, a.imageAutoOrientation, function(b, d) {
                            if (k && !b) B || Y.flashEngine || (i.multipart = !0), i.append(e, d[0], f, h[0].type || g);
                            else {
                                var l = 0;
                                b || R(d, function(a, b) {
                                    B || Y.flashEngine || (i.multipart = !0), h[b].postName || (l = 1), i.append(h[b].postName || m(e, b), a, f, h[b].type || g)
                                }), (b || a.imageOriginal) && i.append(m(e, l ? "original" : null), c, f, g)
                            }
                            j.next()
                        })) : f !== Y.expando && i.append(e, c, f)
                    }(d), j.check()
            },
            reset: function(a, b) {
                var c, d;
                return x ? (d = x(a).clone(!0).insertBefore(a).val("")[0], b || x(a).remove()) : (c = a.parentNode, d = c.insertBefore(a.cloneNode(!0), a), d.value = "", b || c.removeChild(a), R(K[Y.uid(a)], function(b, c) {
                    R(b, function(b) {
                        U(a, c, b), T(d, c, b)
                    })
                })), d
            },
            load: function(a, b) {
                var c = Y.getXHR();
                return c ? (c.open("GET", a, !0), c.overrideMimeType && c.overrideMimeType("text/plain; charset=x-user-defined"), T(c, "progress", function(a) {
                    a.lengthComputable && b({
                        type: a.type,
                        loaded: a.loaded,
                        total: a.total
                    }, c)
                }), c.onreadystatechange = function() {
                    if (4 == c.readyState)
                        if (c.onreadystatechange = null, 200 == c.status) {
                            a = a.split("/");
                            var d = {
                                name: a[a.length - 1],
                                size: c.getResponseHeader("Content-Length"),
                                type: c.getResponseHeader("Content-Type")
                            };
                            d.dataURL = "data:" + d.type + ";base64," + Y.encode64(c.responseBody || c.responseText), b({
                                type: "load",
                                result: d
                            }, c)
                        } else b({
                            type: "error"
                        }, c)
                }, c.send(null)) : b({
                    type: "error"
                }), c
            },
            encode64: function(a) {
                var b = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",
                    c = "",
                    d = 0;
                for ("string" != typeof a && (a = String(a)); d < a.length;) {
                    var e, f, g = 255 & a.charCodeAt(d++),
                        h = 255 & a.charCodeAt(d++),
                        i = 255 & a.charCodeAt(d++),
                        j = g >> 2,
                        k = (3 & g) << 4 | h >> 4;
                    isNaN(h) ? e = f = 64 : (e = (15 & h) << 2 | i >> 6, f = isNaN(i) ? 64 : 63 & i), c += b.charAt(j) + b.charAt(k) + b.charAt(e) + b.charAt(f)
                }
                return c
            }
        };
    Y.addInfoReader(/^image/, function(a, b) {
        if (!a.__dimensions) {
            var c = a.__dimensions = Y.defer();
            Y.readAsImage(a, function(a) {
                var b = a.target;
                c.resolve("load" != a.type && "error", {
                    width: b.width,
                    height: b.height
                }), b.src = Y.EMPTY_PNG, b = null
            })
        }
        a.__dimensions.then(b)
    }), Y.event.dnd = function(a, b, c) {
        var d, e;
        c || (c = b, b = Y.F), u ? (T(a, "dragenter dragleave dragover", b.ff = b.ff || function(a) {
            for (var c = k(a).types, f = c && c.length, g = !1; f--;)
                if (~c[f].indexOf("File")) {
                    a[P](), e !== a.type && (e = a.type, "dragleave" != e && b.call(a[O], !0, a), g = !0);
                    break
                }
            g && (clearTimeout(d), d = setTimeout(function() {
                b.call(a[O], "dragleave" != e, a)
            }, 50))
        }), T(a, "drop", c.ff = c.ff || function(a) {
            a[P](), e = 0, b.call(a[O], !1, a), Y.getDropFiles(a, function(b) {
                c.call(a[O], b, a)
            })
        })) : Y.log("Drag'n'Drop -- not supported")
    }, Y.event.dnd.off = function(a, b, c) {
        U(a, "dragenter dragleave dragover", b.ff), U(a, "drop", c.ff)
    }, x && !x.fn.dnd && (x.fn.dnd = function(a, b) {
        return this.each(function() {
            Y.event.dnd(this, a, b)
        })
    }, x.fn.offdnd = function(a, b) {
        return this.each(function() {
            Y.event.dnd.off(this, a, b)
        })
    }), a.FileAPI = S(Y, a.FileAPI), Y.log("FileAPI: " + Y.version), Y.log("protocol: " + a.location.protocol), Y.log("doctype: [" + p.name + "] " + p.publicId + " " + p.systemId), R(o.getElementsByTagName("meta"), function(a) {
        /x-ua-compatible/i.test(a.getAttribute("http-equiv")) && Y.log("meta.http-equiv: " + a.getAttribute("content"))
    }), Y.flashUrl || (Y.flashUrl = Y.staticPath + "FileAPI.flash.swf"), Y.flashImageUrl || (Y.flashImageUrl = Y.staticPath + "FileAPI.flash.image.swf"), Y.flashWebcamUrl || (Y.flashWebcamUrl = Y.staticPath + "FileAPI.flash.camera.swf")
}(window, void 0),
function(a, b, c) {
    "use strict";

    function d(b) {
        if (b instanceof d) {
            var c = new d(b.file);
            return a.extend(c.matrix, b.matrix), c
        }
        return this instanceof d ? (this.file = b, this.size = b.size || 100, void(this.matrix = {
            sx: 0,
            sy: 0,
            sw: 0,
            sh: 0,
            dx: 0,
            dy: 0,
            dw: 0,
            dh: 0,
            resize: 0,
            deg: 0,
            quality: 1,
            filter: 0
        })) : new d(b)
    }
    var e = Math.min,
        f = Math.round,
        g = function() {
            return b.createElement("canvas")
        },
        h = !1,
        i = {
            8: 270,
            3: 180,
            6: 90,
            7: 270,
            4: 180,
            5: 90
        };
    try {
        h = g().toDataURL("image/png").indexOf("data:image/png") > -1
    } catch (a) {}
    d.prototype = {
        image: !0,
        constructor: d,
        set: function(b) {
            return a.extend(this.matrix, b), this
        },
        crop: function(a, b, d, e) {
            return d === c && (d = a, e = b, a = b = 0), this.set({
                sx: a,
                sy: b,
                sw: d,
                sh: e || d
            })
        },
        resize: function(a, b, c) {
            return /min|max/.test(b) && (c = b, b = a), this.set({
                dw: a,
                dh: b || a,
                resize: c
            })
        },
        preview: function(a, b) {
            return this.resize(a, b || a, "preview")
        },
        rotate: function(a) {
            return this.set({
                deg: a
            })
        },
        filter: function(a) {
            return this.set({
                filter: a
            })
        },
        overlay: function(a) {
            return this.set({
                overlay: a
            })
        },
        clone: function() {
            return new d(this)
        },
        _load: function(b, c) {
            var d = this;
            /img|video/i.test(b.nodeName) ? c.call(d, null, b) : a.readAsImage(b, function(a) {
                c.call(d, "load" != a.type, a.result)
            })
        },
        _apply: function(b, c) {
            var f, h = g(),
                i = this.getMatrix(b),
                j = h.getContext("2d"),
                k = b.videoWidth || b.width,
                l = b.videoHeight || b.height,
                m = i.deg,
                n = i.dw,
                o = i.dh,
                p = k,
                q = l,
                r = i.filter,
                s = b,
                t = i.overlay,
                u = a.queue(function() {
                    b.src = a.EMPTY_PNG, c(!1, h)
                }),
                v = a.renderImageToCanvas;
            for (m -= 360 * Math.floor(m / 360), b._type = this.file.type; i.multipass && e(p / n, q / o) > 2;) p = p / 2 + .5 | 0, q = q / 2 + .5 | 0, f = g(), f.width = p, f.height = q, s !== b ? (v(f, s, 0, 0, s.width, s.height, 0, 0, p, q), s = f) : (s = f, v(s, b, i.sx, i.sy, i.sw, i.sh, 0, 0, p, q), i.sx = i.sy = i.sw = i.sh = 0);
            h.width = m % 180 ? o : n, h.height = m % 180 ? n : o, h.type = i.type, h.quality = i.quality, j.rotate(m * Math.PI / 180), v(j.canvas, s, i.sx, i.sy, i.sw || s.width, i.sh || s.height, 180 == m || 270 == m ? -n : 0, 90 == m || 180 == m ? -o : 0, n, o), n = h.width, o = h.height, t && a.each([].concat(t), function(b) {
                u.inc();
                var c = new window.Image,
                    d = function() {
                        var e = 0 | b.x,
                            f = 0 | b.y,
                            g = b.w || c.width,
                            h = b.h || c.height,
                            i = b.rel;
                        e = 1 == i || 4 == i || 7 == i ? (n - g + e) / 2 : 2 == i || 5 == i || 8 == i ? n - (g + e) : e, f = 3 == i || 4 == i || 5 == i ? (o - h + f) / 2 : i >= 6 ? o - (h + f) : f, a.event.off(c, "error load abort", d);
                        try {
                            j.globalAlpha = b.opacity || 1, j.drawImage(c, e, f, g, h)
                        } catch (a) {}
                        u.next()
                    };
                a.event.on(c, "error load abort", d), c.src = b.src, c.complete && d()
            }), r && (u.inc(), d.applyFilter(h, r, u.next)), u.check()
        },
        getMatrix: function(b) {
            var c = a.extend({}, this.matrix),
                d = c.sw = c.sw || b.videoWidth || b.naturalWidth || b.width,
                g = c.sh = c.sh || b.videoHeight || b.naturalHeight || b.height,
                h = c.dw = c.dw || d,
                i = c.dh = c.dh || g,
                j = d / g,
                k = h / i,
                l = c.resize;
            if ("preview" == l) {
                if (h != d || i != g) {
                    var m, n;
                    k >= j ? (m = d, n = m / k) : (n = g, m = n * k), (m != d || n != g) && (c.sx = ~~((d - m) / 2), c.sy = ~~((g - n) / 2), d = m, g = n)
                }
            } else l && (d > h || g > i ? "min" == l ? (h = f(k > j ? e(d, h) : i * j), i = f(k > j ? h / j : e(g, i))) : (h = f(j >= k ? e(d, h) : i * j), i = f(j >= k ? h / j : e(g, i))) : (h = d, i = g));
            return c.sw = d, c.sh = g, c.dw = h, c.dh = i, c.multipass = a.multiPassResize, c
        },
        _trans: function(b) {
            this._load(this.file, function(c, d) {
                if (c) b(c);
                else try {
                    this._apply(d, b)
                } catch (c) {
                    a.log("[err] FileAPI.Image.fn._apply:", c), b(c)
                }
            })
        },
        get: function(b) {
            if (a.support.transform) {
                var c = this,
                    d = c.matrix;
                "auto" == d.deg ? a.getInfo(c.file, function(a, e) {
                    d.deg = i[e && e.exif && e.exif.Orientation] || 0, c._trans(b)
                }) : c._trans(b)
            } else b("not_support_transform");
            return this
        },
        toData: function(a) {
            return this.get(a)
        }
    }, d.exifOrientation = i, d.transform = function(b, e, f, g) {
        function h(h, i) {
            var j = {},
                k = a.queue(function(a) {
                    g(a, j)
                });
            h ? k.fail() : a.each(e, function(a, e) {
                if (!k.isFail()) {
                    var g = new d(i.nodeType ? i : b),
                        h = "function" == typeof a;
                    if (h ? a(i, g) : a.width ? g[a.preview ? "preview" : "resize"](a.width, a.height, a.strategy) : a.maxWidth && (i.width > a.maxWidth || i.height > a.maxHeight) && g.resize(a.maxWidth, a.maxHeight, "max"), a.crop) {
                        var l = a.crop;
                        g.crop(0 | l.x, 0 | l.y, l.w || l.width, l.h || l.height)
                    }
                    a.rotate === c && f && (a.rotate = "auto"), g.set({
                        type: g.matrix.type || a.type || b.type || "image/png"
                    }), h || g.set({
                        deg: a.rotate,
                        overlay: a.overlay,
                        filter: a.filter,
                        quality: a.quality || 1
                    }), k.inc(), g.toData(function(a, b) {
                        a ? k.fail() : (j[e] = b, k.next())
                    })
                }
            })
        }
        b.width ? h(!1, b) : a.getInfo(b, h)
    }, a.each(["TOP", "CENTER", "BOTTOM"], function(b, c) {
        a.each(["LEFT", "CENTER", "RIGHT"], function(a, e) {
            d[b + "_" + a] = 3 * c + e, d[a + "_" + b] = 3 * c + e
        })
    }), d.toCanvas = function(a) {
        var c = b.createElement("canvas");
        return c.width = a.videoWidth || a.width, c.height = a.videoHeight || a.height, c.getContext("2d").drawImage(a, 0, 0), c
    }, d.fromDataURL = function(b, c, d) {
        var e = a.newImage(b);
        a.extend(e, c), d(e)
    }, d.applyFilter = function(b, c, e) {
        "function" == typeof c ? c(b, e) : window.Caman && window.Caman("IMG" == b.tagName ? d.toCanvas(b) : b, function() {
            "string" == typeof c ? this[c]() : a.each(c, function(a, b) {
                this[b](a)
            }, this), this.render(e)
        })
    }, a.renderImageToCanvas = function(b, c, d, e, f, g, h, i, j, k) {
        try {
            return b.getContext("2d").drawImage(c, d, e, f, g, h, i, j, k)
        } catch (b) {
            throw a.log("renderImageToCanvas failed"), b
        }
    }, a.support.canvas = a.support.transform = h, a.Image = d
}(FileAPI, document),
function(a) {
    "use strict";
    ! function(a) {
        if (window.navigator && window.navigator.platform && /iP(hone|od|ad)/.test(window.navigator.platform)) {
            var b = a.renderImageToCanvas;
            a.detectSubsampling = function(a) {
                var b, c;
                return a.width * a.height > 1048576 && (b = document.createElement("canvas"), b.width = b.height = 1, c = b.getContext("2d"), c.drawImage(a, 1 - a.width, 0), 0 === c.getImageData(0, 0, 1, 1).data[3])
            }, a.detectVerticalSquash = function(a, b) {
                var c, d, e, f, g, h = a.naturalHeight || a.height,
                    i = document.createElement("canvas"),
                    j = i.getContext("2d");
                for (b && (h /= 2), i.width = 1, i.height = h, j.drawImage(a, 0, 0), c = j.getImageData(0, 0, 1, h).data, d = 0, e = h, f = h; f > d;) g = c[4 * (f - 1) + 3], 0 === g ? e = f : d = f, f = e + d >> 1;
                return f / h || 1
            }, a.renderImageToCanvas = function(c, d, e, f, g, h, i, j, k, l) {
                if ("image/jpeg" === d._type) {
                    var m, n, o, p, q = c.getContext("2d"),
                        r = document.createElement("canvas"),
                        s = 1024,
                        t = r.getContext("2d");
                    if (r.width = s, r.height = s, q.save(), m = a.detectSubsampling(d), m && (e /= 2, f /= 2, g /= 2, h /= 2), n = a.detectVerticalSquash(d, m), m || 1 !== n) {
                        for (f *= n, k = Math.ceil(s * k / g), l = Math.ceil(s * l / h / n), j = 0, p = 0; h > p;) {
                            for (i = 0, o = 0; g > o;) t.clearRect(0, 0, s, s), t.drawImage(d, e, f, g, h, -o, -p, g, h), q.drawImage(r, 0, 0, s, s, i, j, k, l), o += s, i += k;
                            p += s, j += l
                        }
                        return q.restore(), c
                    }
                }
                return b(c, d, e, f, g, h, i, j, k, l)
            }
        }
    }(FileAPI)
}(),
function(a, b) {
    "use strict";

    function c(b, c, d) {
        var e = b.blob,
            f = b.file;
        if (f) {
            if (!e.toDataURL) return void a.readAsBinaryString(e, function(a) {
                "load" == a.type && c(b, a.result)
            });
            var g = {
                    "image/jpeg": ".jpe?g",
                    "image/png": ".png"
                },
                h = g[b.type] ? b.type : "image/png",
                i = g[h] || ".png",
                j = e.quality || 1;
            f.match(new RegExp(i + "$", "i")) || (f += i.replace("?", "")), b.file = f, b.type = h, !d && e.toBlob ? e.toBlob(function(a) {
                c(b, a)
            }, h, j) : c(b, a.toBinaryString(e.toDataURL(h, j)))
        } else c(b, e)
    }
    var d = b.document,
        e = b.FormData,
        f = function() {
            this.items = []
        },
        g = b.encodeURIComponent;
    f.prototype = {
        append: function(a, b, c, d) {
            this.items.push({
                name: a,
                blob: b && b.blob || (void 0 == b ? "" : b),
                file: b && (c || b.name),
                type: b && (d || b.type)
            })
        },
        each: function(a) {
            for (var b = 0, c = this.items.length; c > b; b++) a.call(this, this.items[b])
        },
        toData: function(b, c) {
            c._chunked = a.support.chunked && c.chunkSize > 0 && 1 == a.filter(this.items, function(a) {
                return a.file
            }).length, a.support.html5 ? a.formData && !this.multipart && e ? c._chunked ? (a.log("FileAPI.Form.toPlainData"), this.toPlainData(b)) : (a.log("FileAPI.Form.toFormData"), this.toFormData(b)) : (a.log("FileAPI.Form.toMultipartData"), this.toMultipartData(b)) : (a.log("FileAPI.Form.toHtmlData"), this.toHtmlData(b))
        },
        _to: function(b, c, d, e) {
            var f = a.queue(function() {
                c(b)
            });
            this.each(function(a) {
                d(a, b, f, e)
            }), f.check()
        },
        toHtmlData: function(b) {
            this._to(d.createDocumentFragment(), b, function(b, c) {
                var e, f = b.blob;
                b.file ? (a.reset(f, !0), f.name = b.name, f.disabled = !1, c.appendChild(f)) : (e = d.createElement("input"), e.name = b.name, e.type = "hidden", e.value = f, c.appendChild(e))
            })
        },
        toPlainData: function(a) {
            this._to({}, a, function(a, b, d) {
                a.file && (b.type = a.file), a.blob.toBlob ? (d.inc(), c(a, function(a, c) {
                    b.name = a.name, b.file = c, b.size = c.length, b.type = a.type, d.next()
                })) : a.file ? (b.name = a.blob.name, b.file = a.blob, b.size = a.blob.size, b.type = a.type) : (b.params || (b.params = []), b.params.push(g(a.name) + "=" + g(a.blob))), b.start = -1, b.end = b.file && b.file.FileAPIReadPosition || -1, b.retry = 0
            })
        },
        toFormData: function(a) {
            this._to(new e, a, function(a, b, d) {
                a.blob && a.blob.toBlob ? (d.inc(), c(a, function(a, c) {
                    b.append(a.name, c, a.file), d.next()
                })) : a.file ? b.append(a.name, a.blob, a.file) : b.append(a.name, a.blob), a.file && b.append("_" + a.name, a.file)
            })
        },
        toMultipartData: function(b) {
            this._to([], b, function(a, b, d, e) {
                d.inc(), c(a, function(a, c) {
                    b.push("--_" + e + '\r\nContent-Disposition: form-data; name="' + a.name + '"' + (a.file ? '; filename="' + g(a.file) + '"' : "") + (a.file ? "\r\nContent-Type: " + (a.type || "application/octet-stream") : "") + "\r\n\r\n" + (a.file ? c : g(c)) + "\r\n"), d.next()
                }, !0)
            }, a.expando)
        }
    }, a.Form = f
}(FileAPI, window),
function(a, b) {
    "use strict";
    var c = function() {},
        d = a.document,
        e = function(a) {
            this.uid = b.uid(), this.xhr = {
                abort: c,
                getResponseHeader: c,
                getAllResponseHeaders: c
            }, this.options = a
        },
        f = {
            "": 1,
            XML: 1,
            Text: 1,
            Body: 1
        };
    e.prototype = {
        status: 0,
        statusText: "",
        constructor: e,
        getResponseHeader: function(a) {
            return this.xhr.getResponseHeader(a)
        },
        getAllResponseHeaders: function() {
            return this.xhr.getAllResponseHeaders() || {}
        },
        end: function(d, e) {
            var f = this,
                g = f.options;
            f.end = f.abort = c, f.status = d, e && (f.statusText = e), b.log("xhr.end:", d, e), g.complete(200 != d && 201 != d && (f.statusText || "unknown"), f), f.xhr && f.xhr.node && setTimeout(function() {
                var b = f.xhr.node;
                try {
                    b.parentNode.removeChild(b)
                } catch (a) {}
                try {
                    delete a[f.uid]
                } catch (a) {}
                a[f.uid] = f.xhr.node = null
            }, 9)
        },
        abort: function() {
            this.end(0, "abort"), this.xhr && (this.xhr.aborted = !0, this.xhr.abort())
        },
        send: function(a) {
            var b = this,
                c = this.options;
            a.toData(function(a) {
                c.upload(c, b), b._send.call(b, c, a)
            }, c)
        },
        _send: function(c, e) {
            var g, h = this,
                i = h.uid,
                j = h.uid + "Load",
                k = c.url;
            if (b.log("XHR._send:", e), c.cache || (k += (~k.indexOf("?") ? "&" : "?") + b.uid()), e.nodeName) {
                var l = c.jsonp;
                k = k.replace(/([a-z]+)=(\?)/i, "$1=" + i), c.upload(c, h);
                var m = function(a) {
                        if (~k.indexOf(a.origin)) try {
                            var c = b.parseJSON(a.data);
                            c.id == i && n(c.status, c.statusText, c.response)
                        } catch (a) {
                            n(0, a.message)
                        }
                    },
                    n = a[i] = function(c, d, e) {
                        h.readyState = 4, h.responseText = e, h.end(c, d), b.event.off(a, "message", m), a[i] = g = p = a[j] = null
                    };
                h.xhr.abort = function() {
                    try {
                        p.stop ? p.stop() : p.contentWindow.stop ? p.contentWindow.stop() : p.contentWindow.document.execCommand("Stop")
                    } catch (a) {}
                    n(0, "abort")
                }, b.event.on(a, "message", m), a[j] = function() {
                    try {
                        var a = p.contentWindow,
                            c = a.document,
                            d = a.result || b.parseJSON(c.body.innerHTML);
                        n(d.status, d.statusText, d.response)
                    } catch (a) {
                        b.log("[transport.onload]", a)
                    }
                }, g = d.createElement("div"), g.innerHTML = '<form target="' + i + '" action="' + k + '" method="POST" enctype="multipart/form-data" style="position: absolute; top: -1000px; overflow: hidden; width: 1px; height: 1px;"><iframe name="' + i + '" src="javascript:false;" onload="' + j + '()"></iframe>' + (l && c.url.indexOf("=?") < 0 ? '<input value="' + i + '" name="' + l + '" type="hidden"/>' : "") + "</form>";
                var o = g.getElementsByTagName("form")[0],
                    p = g.getElementsByTagName("iframe")[0];
                o.appendChild(e), b.log(o.parentNode.innerHTML), d.body.appendChild(g), h.xhr.node = g, h.readyState = 2, o.submit(), o = null
            } else {
                if (k = k.replace(/([a-z]+)=(\?)&?/i, ""), this.xhr && this.xhr.aborted) return void b.log("Error: already aborted");
                if (g = h.xhr = b.getXHR(), e.params && (k += (k.indexOf("?") < 0 ? "?" : "&") + e.params.join("&")), g.open("POST", k, !0), b.withCredentials && (g.withCredentials = "true"), c.headers && c.headers["X-Requested-With"] || g.setRequestHeader("X-Requested-With", "XMLHttpRequest"), b.each(c.headers, function(a, b) {
                        g.setRequestHeader(b, a)
                    }), c._chunked) {
                    g.upload && g.upload.addEventListener("progress", b.throttle(function(a) {
                        e.retry || c.progress({
                            type: a.type,
                            total: e.size,
                            loaded: e.start + a.loaded,
                            totalSize: e.size
                        }, h, c)
                    }, 100), !1), g.onreadystatechange = function() {
                        var a = parseInt(g.getResponseHeader("X-Last-Known-Byte"), 10);
                        if (h.status = g.status, h.statusText = g.statusText, h.readyState = g.readyState, 4 == g.readyState) {
                            try {
                                for (var d in f) h["response" + d] = g["response" + d]
                            } catch (a) {}
                            if (g.onreadystatechange = null, !g.status || g.status - 201 > 0)
                                if (b.log("Error: " + g.status), (!g.status && !g.aborted || 500 == g.status || 416 == g.status) && ++e.retry <= c.chunkUploadRetry) {
                                    var i = g.status ? 0 : b.chunkNetworkDownRetryTimeout;
                                    c.pause(e.file, c), b.log("X-Last-Known-Byte: " + a), a ? e.end = a : (e.end = e.start - 1, 416 == g.status && (e.end = e.end - c.chunkSize)), setTimeout(function() {
                                        h._send(c, e)
                                    }, i)
                                } else h.end(g.status);
                            else e.retry = 0, e.end == e.size - 1 ? h.end(g.status) : (b.log("X-Last-Known-Byte: " + a), a && (e.end = a), e.file.FileAPIReadPosition = e.end, setTimeout(function() {
                                h._send(c, e)
                            }, 0));
                            g = null
                        }
                    }, e.start = e.end + 1, e.end = Math.max(Math.min(e.start + c.chunkSize, e.size) - 1, e.start);
                    var q = e.file,
                        r = (q.slice || q.mozSlice || q.webkitSlice).call(q, e.start, e.end + 1);
                    e.size && !r.size ? setTimeout(function() {
                        h.end(-1)
                    }) : (g.setRequestHeader("Content-Range", "bytes " + e.start + "-" + e.end + "/" + e.size), g.setRequestHeader("Content-Disposition", "attachment; filename=" + encodeURIComponent(e.name)), g.setRequestHeader("Content-Type", e.type || "application/octet-stream"), g.send(r)), q = r = null
                } else if (g.upload && g.upload.addEventListener("progress", b.throttle(function(a) {
                        c.progress(a, h, c)
                    }, 100), !1), g.onreadystatechange = function() {
                        if (h.status = g.status, h.statusText = g.statusText, h.readyState = g.readyState, 4 == g.readyState) {
                            for (var a in f) h["response" + a] = g["response" + a];
                            if (g.onreadystatechange = null, !g.status || g.status > 201)
                                if (b.log("Error: " + g.status), (!g.status && !g.aborted || 500 == g.status) && (c.retry || 0) < c.uploadRetry) {
                                    c.retry = (c.retry || 0) + 1;
                                    var d = b.networkDownRetryTimeout;
                                    c.pause(c.file, c), setTimeout(function() {
                                        h._send(c, e)
                                    }, d)
                                } else h.end(g.status);
                            else h.end(g.status);
                            g = null
                        }
                    }, b.isArray(e)) {
                    g.setRequestHeader("Content-Type", "multipart/form-data; boundary=_" + b.expando);
                    var s = e.join("") + "--_" + b.expando + "--";
                    if (g.sendAsBinary) g.sendAsBinary(s);
                    else {
                        var t = Array.prototype.map.call(s, function(a) {
                            return 255 & a.charCodeAt(0)
                        });
                        g.send(new Uint8Array(t).buffer)
                    }
                } else g.send(e)
            }
        }
    }, b.XHR = e
}(window, FileAPI),
function(a, b) {
    "use strict";

    function c(a) {
        return a >= 0 ? a + "px" : a
    }

    function d(a) {
        var b, c = f.createElement("canvas"),
            d = !1;
        try {
            b = c.getContext("2d"), b.drawImage(a, 0, 0, 1, 1), d = 255 != b.getImageData(0, 0, 1, 1).data[4]
        } catch (a) {}
        return d
    }
    var e = a.URL || a.webkitURL,
        f = a.document,
        g = a.navigator,
        h = g.getUserMedia || g.webkitGetUserMedia || g.mozGetUserMedia || g.msGetUserMedia,
        i = !!h;
    b.support.media = i;
    var j = function(a) {
        this.video = a
    };
    j.prototype = {
        isActive: function() {
            return !!this._active
        },
        start: function(a) {
            var b, c, f = this,
                i = f.video,
                j = function(d) {
                    f._active = !d, clearTimeout(c), clearTimeout(b), a && a(d, f)
                };
            h.call(g, {
                video: !0
            }, function(a) {
                f.stream = a, i.src = e.createObjectURL(a), b = setInterval(function() {
                    d(i) && j(null)
                }, 1e3), c = setTimeout(function() {
                    j("timeout")
                }, 5e3), i.play()
            }, j)
        },
        stop: function() {
            try {
                this._active = !1, this.video.pause(), this.stream.stop()
            } catch (a) {}
        },
        shot: function() {
            return new k(this.video)
        }
    }, j.get = function(a) {
        return new j(a.firstChild)
    }, j.publish = function(d, e, g) {
        "function" == typeof e && (g = e, e = {}), e = b.extend({}, {
            width: "100%",
            height: "100%",
            start: !0
        }, e), d.jquery && (d = d[0]);
        var h = function(a) {
            if (a) g(a);
            else {
                var b = j.get(d);
                e.start ? b.start(g) : g(null, b)
            }
        };
        if (d.style.width = c(e.width), d.style.height = c(e.height), b.html5 && i) {
            var k = f.createElement("video");
            k.style.width = c(e.width), k.style.height = c(e.height), a.jQuery ? jQuery(d).empty() : d.innerHTML = "", d.appendChild(k), h()
        } else j.fallback(d, e, h)
    }, j.fallback = function(a, b, c) {
        c("not_support_camera")
    };
    var k = function(a) {
        var c = a.nodeName ? b.Image.toCanvas(a) : a,
            d = b.Image(c);
        return d.type = "image/png", d.width = c.width, d.height = c.height, d.size = c.width * c.height * 4, d
    };
    j.Shot = k, b.Camera = j
}(window, FileAPI),
function(a, b, c) {
    "use strict";
    var d = a.document,
        e = a.location,
        f = a.navigator,
        g = c.each;
    c.support.flash = function() {
        var b = f.mimeTypes,
            d = !1;
        if (f.plugins && "object" == typeof f.plugins["Shockwave Flash"]) d = f.plugins["Shockwave Flash"].description && !(b && b["application/x-shockwave-flash"] && !b["application/x-shockwave-flash"].enabledPlugin);
        else try {
            d = !(!a.ActiveXObject || !new ActiveXObject("ShockwaveFlash.ShockwaveFlash"))
        } catch (a) {
            c.log("Flash -- does not supported.")
        }
        return d && /^file:/i.test(e) && c.log("[warn] Flash does not work on `file:` protocol."), d
    }(), c.support.flash && (!c.html5 || !c.support.html5 || c.cors && !c.support.cors || c.media && !c.support.media) && function() {
        function h(a) {
            return ('<object id="#id#" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="' + (a.width || "100%") + '" height="' + (a.height || "100%") + '"><param name="movie" value="#src#" /><param name="flashvars" value="#flashvars#" /><param name="swliveconnect" value="true" /><param name="allowscriptaccess" value="always" /><param name="allownetworking" value="all" /><param name="menu" value="false" /><param name="wmode" value="#wmode#" /><embed flashvars="#flashvars#" swliveconnect="true" allownetworking="all" allowscriptaccess="always" name="#id#" src="#src#" width="' + (a.width || "100%") + '" height="' + (a.height || "100%") + '" menu="false" wmode="transparent" type="application/x-shockwave-flash"></embed></object>').replace(/#(\w+)#/gi, function(b, c) {
                return a[c]
            })
        }

        function i(a, b) {
            if (a && a.style) {
                var c, d;
                for (c in b) {
                    "number" == typeof(d = b[c]) && (d += "px");
                    try {
                        a.style[c] = d
                    } catch (a) {}
                }
            }
        }

        function j(a, b) {
            g(b, function(b, c) {
                var d = a[c];
                a[c] = function() {
                    return this.parent = d, b.apply(this, arguments)
                }
            })
        }

        function k(a) {
            return a && !a.flashId
        }

        function l(a) {
            var b = a.wid = c.uid();
            return v._fn[b] = a, "FileAPI.Flash._fn." + b
        }

        function m(a) {
            try {
                v._fn[a.wid] = null, delete v._fn[a.wid]
            } catch (a) {}
        }

        function n(a, b) {
            if (!u.test(a)) {
                if (/^\.\//.test(a) || "/" != a.charAt(0)) {
                    var c = e.pathname;
                    c = c.substr(0, c.lastIndexOf("/")), a = (c + "/" + a).replace("/./", "/")
                }
                "//" != a.substr(0, 2) && (a = "//" + e.host + a), u.test(a) || (a = e.protocol + a)
            }
            return b && (a += (/\?/.test(a) ? "&" : "?") + b), a
        }

        function o(a, b, e) {
            function f() {
                try {
                    v.get(j).setImage(b)
                } catch (a) {
                    c.log('[err] FlashAPI.Preview.setImage -- can not set "base64":', a)
                }
            }
            var g, j = c.uid(),
                k = d.createElement("div"),
                o = 10;
            for (g in a) k.setAttribute(g, a[g]), k[g] = a[g];
            i(k, a), a.width = "100%", a.height = "100%", k.innerHTML = h(c.extend({
                id: j,
                src: n(c.flashImageUrl, "r=" + c.uid()),
                wmode: "opaque",
                flashvars: "scale=" + a.scale + "&callback=" + l(function a() {
                    return m(a), --o > 0 && f(), !0
                })
            }, a)), e(!1, k), k = null
        }

        function p(a) {
            return {
                id: a.id,
                name: a.name,
                matrix: a.matrix,
                flashId: a.flashId
            }
        }

        function q(a) {
            function b(a) {
                var b, c;
                if (b = c = 0, a.offsetParent)
                    do {
                        b += a.offsetLeft, c += a.offsetTop
                    } while (a = a.offsetParent);
                return {
                    left: b,
                    top: c
                }
            }
            return a.getBoundingClientRect(), d.body, (a && a.ownerDocument).documentElement, {
                top: b(a).top,
                left: b(a).left,
                width: a.offsetWidth,
                height: a.offsetHeight
            }
        }
        var r = c.uid(),
            s = 0,
            t = {},
            u = /^https?:/i,
            v = {
                _fn: {},
                init: function() {
                    var a = d.body && d.body.firstChild;
                    if (a)
                        do {
                            if (1 == a.nodeType) {
                                c.log("FlashAPI.state: awaiting");
                                var b = d.createElement("div");
                                return b.id = "_" + r, i(b, {
                                    top: 1,
                                    right: 1,
                                    width: 5,
                                    height: 5,
                                    position: "absolute",
                                    zIndex: 1e6 + ""
                                }), a.parentNode.insertBefore(b, a), void v.publish(b, r)
                            }
                        } while (a = a.nextSibling);
                    10 > s && setTimeout(v.init, 50 * ++s)
                },
                publish: function(a, b, d) {
                    d = d || {}, a.innerHTML = h({
                        id: b,
                        src: n(c.flashUrl, "r=" + c.version),
                        wmode: d.camera ? "" : "transparent",
                        flashvars: "callback=" + (d.onEvent || "FileAPI.Flash.onEvent") + "&flashId=" + b + "&storeKey=" + f.userAgent.match(/\d/gi).join("") + "_" + c.version + (v.isReady || (c.pingUrl ? "&ping=" + c.pingUrl : "")) + "&timeout=" + c.flashAbortTimeout + (d.camera ? "&useCamera=" + n(c.flashWebcamUrl) : "") + "&debug=" + (c.debug ? "1" : "")
                    }, d)
                },
                ready: function() {
                    c.log("FlashAPI.state: ready"), v.ready = c.F, v.isReady = !0, v.patch(), v.patchCamera && v.patchCamera(), c.event.on(d, "mouseover", v.mouseover), c.event.on(d, "click", function(a) {
                        v.mouseover(a) && (a.preventDefault ? a.preventDefault() : a.returnValue = !0)
                    })
                },
                getEl: function() {
                    return d.getElementById("_" + r)
                },
                getWrapper: function(a) {
                    do {
                        if (/js-fileapi-wrapper/.test(a.className)) return a
                    } while ((a = a.parentNode) && a !== d.body)
                },
                disableMouseover: !1,
                mouseover: function(a) {
                    if (!v.disableMouseover) {
                        var b = c.event.fix(a).target;
                        if (/input/i.test(b.nodeName) && "file" == b.type && !b.disabled) {
                            var e = b.getAttribute(r),
                                f = v.getWrapper(b);
                            if (c.multiFlash) {
                                if ("i" == e || "r" == e) return !1;
                                if ("p" != e) {
                                    b.setAttribute(r, "i");
                                    var g = d.createElement("div");
                                    if (!f) return void c.log("[err] FlashAPI.mouseover: js-fileapi-wrapper not found");
                                    i(g, {
                                        top: 0,
                                        left: 0,
                                        width: b.offsetWidth,
                                        height: b.offsetHeight,
                                        zIndex: 1e6 + "",
                                        position: "absolute"
                                    }), f.appendChild(g), v.publish(g, c.uid()), b.setAttribute(r, "p")
                                }
                                return !0
                            }
                            if (f) {
                                var h = q(f);
                                i(v.getEl(), h), v.curInp = b
                            }
                        } else /object|embed/i.test(b.nodeName) || i(v.getEl(), {
                            top: 1,
                            left: 1,
                            width: 5,
                            height: 5
                        })
                    }
                },
                onEvent: function(a) {
                    var b = a.type;
                    if ("ready" == b) {
                        try {
                            v.getInput(a.flashId).setAttribute(r, "r")
                        } catch (a) {}
                        return v.ready(), setTimeout(function() {
                            v.mouseenter(a)
                        }, 50), !0
                    }
                    "ping" === b ? c.log("(flash -> js).ping:", [a.status, a.savedStatus], a.error) : "log" === b ? c.log("(flash -> js).log:", a.target) : b in v && setTimeout(function() {
                        c.log("FlashAPI.event." + a.type + ":", a), v[b](a)
                    }, 1)
                },
                mouseDown: function() {
                    v.disableMouseover = !0
                },
                cancel: function() {
                    v.disableMouseover = !1
                },
                mouseenter: function(a) {
                    var b = v.getInput(a.flashId);
                    if (b) {
                        v.cmd(a, "multiple", null != b.getAttribute("multiple"));
                        var d = [],
                            e = {};
                        g((b.getAttribute("accept") || "").split(/,\s*/), function(a) {
                            c.accept[a] && g(c.accept[a].split(" "), function(a) {
                                e[a] = 1
                            })
                        }), g(e, function(a, b) {
                            d.push(b)
                        }), v.cmd(a, "accept", d.length ? d.join(",") + "," + d.join(",").toUpperCase() : "*")
                    }
                },
                get: function(b) {
                    return d[b] || a[b] || d.embeds[b]
                },
                getInput: function(a) {
                    if (!c.multiFlash) return v.curInp;
                    try {
                        var b = v.getWrapper(v.get(a));
                        if (b) return b.getElementsByTagName("input")[0]
                    } catch (b) {
                        c.log('[err] Can not find "input" by flashId:', a, b)
                    }
                },
                select: function(a) {
                    try {
                        var e, f = v.getInput(a.flashId),
                            h = c.uid(f),
                            i = a.target.files;
                        g(i, function(a) {
                            c.checkFileObj(a)
                        }), t[h] = i, d.createEvent ? (e = d.createEvent("Event"), e.files = i, e.initEvent("change", !0, !0), f.dispatchEvent(e)) : b ? b(f).trigger({
                            type: "change",
                            files: i
                        }) : (e = d.createEventObject(), e.files = i, f.fireEvent("onchange", e))
                    } finally {
                        v.disableMouseover = !1
                    }
                },
                interval: null,
                cmd: function(a, b, c, d) {
                    v.uploadInProgress && v.readInProgress ? setTimeout(function() {
                        v.cmd(a, b, c, d)
                    }, 100) : this.cmdFn(a, b, c, d)
                },
                cmdFn: function(a, b, d, e) {
                    try {
                        return c.log("(js -> flash)." + b + ":", d), v.get(a.flashId || a).cmd(b, d)
                    } catch (f) {
                        c.log("(js -> flash).onError:", f), e || setTimeout(function() {
                            v.cmd(a, b, d, !0)
                        }, 50)
                    }
                },
                patch: function() {
                    c.flashEngine = !0, j(c, {
                        readAsDataURL: function(a, b) {
                            k(a) ? this.parent.apply(this, arguments) : (c.log("FlashAPI.readAsBase64"), v.readInProgress = !0, v.cmd(a, "readAsBase64", {
                                id: a.id,
                                callback: l(function d(e, f) {
                                    v.readInProgress = !1, m(d), c.log("FlashAPI.readAsBase64:", e), b({
                                        type: e ? "error" : "load",
                                        error: e,
                                        result: "data:" + a.type + ";base64," + f
                                    })
                                })
                            }))
                        },
                        readAsText: function(b, d, e) {
                            e ? c.log("[warn] FlashAPI.readAsText not supported `encoding` param") : e = d, c.readAsDataURL(b, function(b) {
                                if ("load" == b.type) try {
                                    b.result = a.atob(b.result.split(";base64,")[1])
                                } catch (a) {
                                    b.type = "error", b.error = a.toString()
                                }
                                e(b)
                            })
                        },
                        getFiles: function(a, b, d) {
                            if (d) return c.filterFiles(c.getFiles(a), b, d), null;
                            var e = c.isArray(a) ? a : t[c.uid(a.target || a.srcElement || a)];
                            return e ? (b && (b = c.getFilesFilter(b), e = c.filter(e, function(a) {
                                return b.test(a.name)
                            })), e) : this.parent.apply(this, arguments)
                        },
                        getInfo: function(a, b) {
                            if (k(a)) this.parent.apply(this, arguments);
                            else if (a.isShot) b(null, a.info = {
                                width: a.width,
                                height: a.height
                            });
                            else {
                                if (!a.__info) {
                                    var d = a.__info = c.defer();
                                    d.resolve(null, a.info = null)
                                }
                                a.__info.then(b)
                            }
                        }
                    }), c.support.transform = !0, c.Image && j(c.Image.prototype, {
                        get: function(a, b) {
                            return this.set({
                                scaleMode: b || "noScale"
                            }), this.parent(a)
                        },
                        _load: function(a, b) {
                            if (c.log("FlashAPI.Image._load:", a), k(a)) this.parent.apply(this, arguments);
                            else {
                                var d = this;
                                c.getInfo(a, function(c) {
                                    b.call(d, c, a)
                                })
                            }
                        },
                        _apply: function(a, b) {
                            if (c.log("FlashAPI.Image._apply:", a), k(a)) this.parent.apply(this, arguments);
                            else {
                                var d = this.getMatrix(a.info),
                                    e = b;
                                v.cmd(a, "imageTransform", {
                                    id: a.id,
                                    matrix: d,
                                    callback: l(function f(g, h) {
                                        c.log("FlashAPI.Image._apply.callback:", g), m(f), g ? e(g) : c.support.html5 || c.support.dataURI && !(h.length > 3e4) ? (d.filter && (e = function(a, e) {
                                            a ? b(a) : c.Image.applyFilter(e, d.filter, function() {
                                                b(a, this.canvas)
                                            })
                                        }), c.newImage("data:" + a.type + ";base64," + h, e)) : o({
                                            width: d.deg % 180 ? d.dh : d.dw,
                                            height: d.deg % 180 ? d.dw : d.dh,
                                            scale: d.scaleMode
                                        }, h, e)
                                    })
                                })
                            }
                        },
                        toData: function(a) {
                            var b = this.file,
                                d = b.info,
                                e = this.getMatrix(d);
                            c.log("FlashAPI.Image.toData"), k(b) ? this.parent.apply(this, arguments) : ("auto" == e.deg && (e.deg = c.Image.exifOrientation[d && d.exif && d.exif.Orientation] || 0), a.call(this, !b.info, {
                                id: b.id,
                                flashId: b.flashId,
                                name: b.name,
                                type: b.type,
                                matrix: e
                            }))
                        }
                    }), c.Image && j(c.Image, {
                        fromDataURL: function(a, b, d) {
                            !c.support.dataURI || a.length > 3e4 ? o(c.extend({
                                scale: "exactFit"
                            }, b), a.replace(/^data:[^,]+,/, ""), function(a, b) {
                                d(b)
                            }) : this.parent(a, b, d)
                        }
                    }), j(c.Form.prototype, {
                        toData: function(a) {
                            for (var b = this.items, d = b.length; d--;)
                                if (b[d].file && k(b[d].blob)) return this.parent.apply(this, arguments);
                            c.log("FlashAPI.Form.toData"), a(b)
                        }
                    }), j(c.XHR.prototype, {
                        _send: function(a, b) {
                            if (b.nodeName || b.append && c.support.html5 || c.isArray(b) && "string" == typeof b[0]) return this.parent.apply(this, arguments);
                            var d, e, f = {},
                                h = {},
                                i = this;
                            if (g(b, function(a) {
                                    a.file ? (h[a.name] = a = p(a.blob), e = a.id, d = a.flashId) : f[a.name] = a.blob
                                }), e || (d = r), !d) return c.log("[err] FlashAPI._send: flashId -- undefined"), this.parent.apply(this, arguments);
                            c.log("FlashAPI.XHR._send: " + d + " -> " + e), i.xhr = {
                                headers: {},
                                abort: function() {
                                    v.uploadInProgress = !1, v.cmd(d, "abort", {
                                        id: e
                                    })
                                },
                                getResponseHeader: function(a) {
                                    return this.headers[a]
                                },
                                getAllResponseHeaders: function() {
                                    return this.headers
                                }
                            };
                            var j = c.queue(function() {
                                v.uploadInProgress = !0, v.cmd(d, "upload", {
                                    url: n(a.url.replace(/([a-z]+)=(\?)&?/i, "")),
                                    data: f,
                                    files: e ? h : null,
                                    headers: a.headers || {},
                                    callback: l(function b(d) {
                                        var e = d.type,
                                            f = d.result;
                                        c.log("FlashAPI.upload." + e), "progress" == e ? (d.loaded = Math.min(d.loaded, d.total), d.lengthComputable = !0, a.progress(d)) : "complete" == e ? (v.uploadInProgress = !1, m(b), "string" == typeof f && (i.responseText = f.replace(/%22/g, '"').replace(/%5c/g, "\\").replace(/%26/g, "&").replace(/%25/g, "%")), i.end(d.status || 200)) : ("abort" == e || "error" == e) && (v.uploadInProgress = !1, i.end(d.status || 0, d.message), m(b))
                                    })
                                })
                            });
                            g(h, function(a) {
                                j.inc(), c.getInfo(a, j.next)
                            }), j.check()
                        }
                    })
                }
            };
        c.Flash = v, c.newImage("data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==", function(a, b) {
            c.support.dataURI = !(1 != b.width || 1 != b.height), v.init()
        })
    }()
}(window, window.jQuery, FileAPI),
function(a, b, c) {
    "use strict";
    var d = c.each,
        e = [];
    c.support.flash && c.media && !c.support.media && function() {
        function a(a) {
            var b = a.wid = c.uid();
            return c.Flash._fn[b] = a, "FileAPI.Flash._fn." + b
        }

        function b(a) {
            try {
                c.Flash._fn[a.wid] = null, delete c.Flash._fn[a.wid]
            } catch (a) {}
        }
        var f = c.Flash;
        c.extend(c.Flash, {
            patchCamera: function() {
                c.Camera.fallback = function(d, e, g) {
                    var h = c.uid();
                    c.log("FlashAPI.Camera.publish: " + h), f.publish(d, h, c.extend(e, {
                        camera: !0,
                        onEvent: a(function a(d) {
                            "camera" === d.type && (b(a), d.error ? (c.log("FlashAPI.Camera.publish.error: " + d.error), g(d.error)) : (c.log("FlashAPI.Camera.publish.success: " + h), g(null)))
                        })
                    }))
                }, d(e, function(a) {
                    c.Camera.fallback.apply(c.Camera, a)
                }), e = [], c.extend(c.Camera.prototype, {
                    _id: function() {
                        return this.video.id
                    },
                    start: function(d) {
                        var e = this;
                        f.cmd(this._id(), "camera.on", {
                            callback: a(function a(f) {
                                b(a), f.error ? (c.log("FlashAPI.camera.on.error: " + f.error), d(f.error, e)) : (c.log("FlashAPI.camera.on.success: " + e._id()), e._active = !0, d(null, e))
                            })
                        })
                    },
                    stop: function() {
                        this._active = !1, f.cmd(this._id(), "camera.off")
                    },
                    shot: function() {
                        c.log("FlashAPI.Camera.shot:", this._id());
                        var a = c.Flash.cmd(this._id(), "shot", {});
                        return a.type = "image/png", a.flashId = this._id(), a.isShot = !0, new c.Camera.Shot(a)
                    }
                })
            }
        }), c.Camera.fallback = function() {
            e.push(arguments)
        }
    }()
}(window, window.jQuery, FileAPI), "function" == typeof define && define.amd && define("FileAPI", [], function() {
    return FileAPI
}), ! function() {
    function a(a, b) {
        window.XMLHttpRequest.prototype[a] = b(window.XMLHttpRequest.prototype[a])
    }

    function b(a, b, c) {
        try {
            Object.defineProperty(a, b, {
                get: c
            })
        } catch (a) {}
    }
    if (window.FileAPI || (window.FileAPI = {}), FileAPI.shouldLoad = window.XMLHttpRequest && !window.FormData || FileAPI.forceLoad, FileAPI.shouldLoad) {
        var c = function(a) {
            if (!a.__listeners) {
                a.upload || (a.upload = {}), a.__listeners = [];
                var b = a.upload.addEventListener;
                a.upload.addEventListener = function(c, d) {
                    a.__listeners[c] = d, b && b.apply(this, arguments)
                }
            }
        };
        a("open", function(a) {
            return function(b, d, e) {
                c(this), this.__url = d;
                try {
                    a.apply(this, [b, d, e])
                } catch (c) {
                    c.message.indexOf("Access is denied") > -1 && (this.__origError = c, a.apply(this, [b, "_fix_for_ie_crossdomain__", e]))
                }
            }
        }), a("getResponseHeader", function(a) {
            return function(b) {
                return this.__fileApiXHR && this.__fileApiXHR.getResponseHeader ? this.__fileApiXHR.getResponseHeader(b) : null == a ? null : a.apply(this, [b])
            }
        }), a("getAllResponseHeaders", function(a) {
            return function() {
                return this.__fileApiXHR && this.__fileApiXHR.getAllResponseHeaders ? this.__fileApiXHR.getAllResponseHeaders() : null == a ? null : a.apply(this)
            }
        }), a("abort", function(a) {
            return function() {
                return this.__fileApiXHR && this.__fileApiXHR.abort ? this.__fileApiXHR.abort() : null == a ? null : a.apply(this)
            }
        }), a("setRequestHeader", function(a) {
            return function(b, d) {
                if ("__setXHR_" === b) {
                    c(this);
                    var e = d(this);
                    e instanceof Function && e(this)
                } else this.__requestHeaders = this.__requestHeaders || {}, this.__requestHeaders[b] = d, a.apply(this, arguments)
            }
        }), a("send", function(a) {
            return function() {
                var c = this;
                if (arguments[0] && arguments[0].__isFileAPIShim) {
                    var d = arguments[0],
                        e = {
                            url: c.__url,
                            jsonp: !1,
                            cache: !0,
                            complete: function(a, d) {
                                c.__completed = !0, !a && c.__listeners.load && c.__listeners.load({
                                        type: "load",
                                        loaded: c.__loaded,
                                        total: c.__total,
                                        target: c,
                                        lengthComputable: !0
                                    }), !a && c.__listeners.loadend && c.__listeners.loadend({
                                        type: "loadend",
                                        loaded: c.__loaded,
                                        total: c.__total,
                                        target: c,
                                        lengthComputable: !0
                                    }),
                                    "abort" === a && c.__listeners.abort && c.__listeners.abort({
                                        type: "abort",
                                        loaded: c.__loaded,
                                        total: c.__total,
                                        target: c,
                                        lengthComputable: !0
                                    }), void 0 !== d.status && b(c, "status", function() {
                                        return 0 === d.status && a && "abort" !== a ? 500 : d.status
                                    }), void 0 !== d.statusText && b(c, "statusText", function() {
                                        return d.statusText
                                    }), b(c, "readyState", function() {
                                        return 4
                                    }), void 0 !== d.response && b(c, "response", function() {
                                        return d.response
                                    });
                                var e = d.responseText || (a && 0 === d.status && "abort" !== a ? a : void 0);
                                b(c, "responseText", function() {
                                    return e
                                }), b(c, "response", function() {
                                    return e
                                }), a && b(c, "err", function() {
                                    return a
                                }), c.__fileApiXHR = d, c.onreadystatechange && c.onreadystatechange(), c.onload && c.onload()
                            },
                            progress: function(a) {
                                if (a.target = c, c.__listeners.progress && c.__listeners.progress(a), c.__total = a.total, c.__loaded = a.loaded, a.total === a.loaded) {
                                    var b = this;
                                    setTimeout(function() {
                                        c.__completed || (c.getAllResponseHeaders = function() {}, b.complete(null, {
                                            status: 204,
                                            statusText: "No Content"
                                        }))
                                    }, FileAPI.noContentTimeout || 1e4)
                                }
                            },
                            headers: c.__requestHeaders
                        };
                    e.data = {}, e.files = {};
                    for (var f = 0; f < d.data.length; f++) {
                        var g = d.data[f];
                        null != g.val && null != g.val.name && null != g.val.size && null != g.val.type ? e.files[g.key] = g.val : e.data[g.key] = g.val
                    }
                    setTimeout(function() {
                        if (!FileAPI.hasFlash) throw 'Adode Flash Player need to be installed. To check ahead use "FileAPI.hasFlash"';
                        c.__fileApiXHR = FileAPI.upload(e)
                    }, 1)
                } else {
                    if (this.__origError) throw this.__origError;
                    a.apply(c, arguments)
                }
            }
        }), window.XMLHttpRequest.__isFileAPIShim = !0, window.FormData = FormData = function() {
            return {
                append: function(a, b, c) {
                    b.__isFileAPIBlobShim && (b = b.data[0]), this.data.push({
                        key: a,
                        val: b,
                        name: c
                    })
                },
                data: [],
                __isFileAPIShim: !0
            }
        }, window.Blob = Blob = function(a) {
            return {
                data: a,
                __isFileAPIBlobShim: !0
            }
        }
    }
}(),
function() {
    function a(a) {
        return "input" === a[0].tagName.toLowerCase() && a.attr("type") && "file" === a.attr("type").toLowerCase()
    }

    function b() {
        try {
            if (new ActiveXObject("ShockwaveFlash.ShockwaveFlash")) return !0
        } catch (a) {
            if (void 0 !== navigator.mimeTypes["application/x-shockwave-flash"]) return !0
        }
        return !1
    }

    function c(a) {
        var b = 0,
            c = 0;
        if (window.jQuery) return jQuery(a).offset();
        if (a.offsetParent)
            do {
                b += a.offsetLeft - a.scrollLeft, c += a.offsetTop - a.scrollTop, a = a.offsetParent
            } while (a);
        return {
            left: b,
            top: c
        }
    }
    if (FileAPI.shouldLoad) {
        if (FileAPI.forceLoad && (FileAPI.html5 = !1), !FileAPI.upload) {
            var d, e, f, g, h, i = document.createElement("script"),
                j = document.getElementsByTagName("script");
            if (window.FileAPI.jsUrl) d = window.FileAPI.jsUrl;
            else if (window.FileAPI.jsPath) e = window.FileAPI.jsPath;
            else
                for (f = 0; f < j.length; f++)
                    if (h = j[f].src, (g = h.search(/\/ng\-file\-upload[\-a-zA-z0-9\.]*\.js/)) > -1) {
                        e = h.substring(0, g + 1);
                        break
                    }
            null == FileAPI.staticPath && (FileAPI.staticPath = e), i.setAttribute("src", d || e + "FileAPI.min.js"), document.getElementsByTagName("head")[0].appendChild(i), FileAPI.hasFlash = b()
        }
        FileAPI.ngfFixIE = function(d, e, f, g) {
            if (!b()) throw 'Adode Flash Player need to be installed. To check ahead use "FileAPI.hasFlash"';
            var h = function() {
                if (d.attr("disabled")) d.$$ngfRefElem.removeClass("js-fileapi-wrapper");
                else {
                    var b = d.$$ngfRefElem;
                    b ? f(d.$$ngfRefElem) : (b = d.$$ngfRefElem = e(), b.addClass("js-fileapi-wrapper"), a(d), setTimeout(function() {
                        b.bind("mouseenter", h)
                    }, 10), b.bind("change", function(a) {
                        i.apply(this, [a]), g.apply(this, [a])
                    })), a(d) || b.css("position", "absolute").css("top", c(d[0]).top + "px").css("left", c(d[0]).left + "px").css("width", d[0].offsetWidth + "px").css("height", d[0].offsetHeight + "px").css("filter", "alpha(opacity=0)").css("display", d.css("display")).css("overflow", "hidden").css("z-index", "900000").css("visibility", "visible")
                }
            };
            d.bind("mouseenter", h);
            var i = function(a) {
                for (var b = FileAPI.getFiles(a), c = 0; c < b.length; c++) void 0 === b[c].size && (b[c].size = 0), void 0 === b[c].name && (b[c].name = "file"), void 0 === b[c].type && (b[c].type = "undefined");
                a.target || (a.target = {}), a.target.files = b, a.target.files !== b && (a.__files_ = b), (a.__files_ || a.target.files).item = function(b) {
                    return (a.__files_ || a.target.files)[b] || null
                }
            }
        }, FileAPI.disableFileInput = function(a, b) {
            b ? a.removeClass("js-fileapi-wrapper") : a.addClass("js-fileapi-wrapper")
        }
    }
}(), window.FileReader || (window.FileReader = function() {
    var a = this,
        b = !1;
    this.listeners = {}, this.addEventListener = function(b, c) {
        a.listeners[b] = a.listeners[b] || [], a.listeners[b].push(c)
    }, this.removeEventListener = function(b, c) {
        a.listeners[b] && a.listeners[b].splice(a.listeners[b].indexOf(c), 1)
    }, this.dispatchEvent = function(b) {
        var c = a.listeners[b.type];
        if (c)
            for (var d = 0; d < c.length; d++) c[d].call(a, b)
    }, this.onabort = this.onerror = this.onload = this.onloadstart = this.onloadend = this.onprogress = null;
    var c = function(b, c) {
            var d = {
                type: b,
                target: a,
                loaded: c.loaded,
                total: c.total,
                error: c.error
            };
            return null != c.result && (d.target.result = c.result), d
        },
        d = function(d) {
            b || (b = !0, a.onloadstart && a.onloadstart(c("loadstart", d)));
            var e;
            "load" === d.type ? (a.onloadend && a.onloadend(c("loadend", d)), e = c("load", d), a.onload && a.onload(e), a.dispatchEvent(e)) : "progress" === d.type ? (e = c("progress", d), a.onprogress && a.onprogress(e), a.dispatchEvent(e)) : (e = c("error", d), a.onerror && a.onerror(e), a.dispatchEvent(e))
        };
    this.readAsArrayBuffer = function(a) {
        FileAPI.readAsBinaryString(a, d)
    }, this.readAsBinaryString = function(a) {
        FileAPI.readAsBinaryString(a, d)
    }, this.readAsDataURL = function(a) {
        FileAPI.readAsDataURL(a, d)
    }, this.readAsText = function(a) {
        FileAPI.readAsText(a, d)
    }
}), !window.XMLHttpRequest || window.FileAPI && FileAPI.shouldLoad || (window.XMLHttpRequest.prototype.setRequestHeader = function(a) {
    return function(b, c) {
        if ("__setXHR_" === b) {
            var d = c(this);
            d instanceof Function && d(this)
        } else a.apply(this, arguments)
    }
}(window.XMLHttpRequest.prototype.setRequestHeader));
var ngFileUpload = angular.module("ngFileUpload", []);
ngFileUpload.version = "5.0.9", ngFileUpload.service("Upload", ["$http", "$q", "$timeout", function(a, b, c) {
    function d(d) {
        d.method = d.method || "POST", d.headers = d.headers || {};
        var e = b.defer(),
            f = e.promise;
        return d.headers.__setXHR_ = function() {
            return function(a) {
                a && (d.__XHR = a, d.xhrFn && d.xhrFn(a), a.upload.addEventListener("progress", function(a) {
                    a.config = d, e.notify ? e.notify(a) : f.progressFunc && c(function() {
                        f.progressFunc(a)
                    })
                }, !1), a.upload.addEventListener("load", function(a) {
                    a.lengthComputable && (a.config = d, e.notify ? e.notify(a) : f.progressFunc && c(function() {
                        f.progressFunc(a)
                    }))
                }, !1))
            }
        }, a(d).then(function(a) {
            e.resolve(a)
        }, function(a) {
            e.reject(a)
        }, function(a) {
            e.notify(a)
        }), f.success = function(a) {
            return f.then(function(b) {
                a(b.data, b.status, b.headers, d)
            }), f
        }, f.error = function(a) {
            return f.then(null, function(b) {
                a(b.data, b.status, b.headers, d)
            }), f
        }, f.progress = function(a) {
            return f.progressFunc = a, f.then(null, null, function(b) {
                a(b)
            }), f
        }, f.abort = function() {
            return d.__XHR && c(function() {
                d.__XHR.abort()
            }), f
        }, f.xhr = function(a) {
            return d.xhrFn = function(b) {
                return function() {
                    b && b.apply(f, arguments), a.apply(f, arguments)
                }
            }(d.xhrFn), f
        }, f
    }
    this.upload = function(a) {
        function b(c, d, e) {
            if (void 0 !== d)
                if (angular.isDate(d) && (d = d.toISOString()), angular.isString(d)) c.append(e, d);
                else if ("form" === a.sendFieldsAs)
                if (angular.isObject(d))
                    for (var f in d) d.hasOwnProperty(f) && b(c, d[f], e + "[" + f + "]");
                else c.append(e, d);
            else d = angular.isString(d) ? d : JSON.stringify(d), "json-blob" === a.sendFieldsAs ? c.append(e, new Blob([d], {
                type: "application/json"
            })) : c.append(e, d)
        }
        return a.headers = a.headers || {}, a.headers["Content-Type"] = void 0, a.transformRequest = a.transformRequest ? angular.isArray(a.transformRequest) ? a.transformRequest : [a.transformRequest] : [], a.transformRequest.push(function(c) {
            var d, e = new FormData,
                f = {};
            for (d in a.fields) a.fields.hasOwnProperty(d) && (f[d] = a.fields[d]);
            c && (f.data = c);
            for (d in f)
                if (f.hasOwnProperty(d)) {
                    var g = f[d];
                    a.formDataAppender ? a.formDataAppender(e, d, g) : b(e, g, d)
                }
            if (null != a.file) {
                var h = a.fileFormDataName || "file";
                if (angular.isArray(a.file))
                    for (var i = angular.isString(h), j = 0; j < a.file.length; j++) e.append(i ? h : h[j], a.file[j], a.fileName && a.fileName[j] || a.file[j].name);
                else e.append(h, a.file, a.fileName || a.file.name)
            }
            return e
        }), d(a)
    }, this.http = function(b) {
        return b.transformRequest = b.transformRequest || function(b) {
            return window.ArrayBuffer && b instanceof window.ArrayBuffer || b instanceof Blob ? b : a.defaults.transformRequest[0](arguments)
        }, d(b)
    }
}]),
function() {
    function a(a, e, f, g, h, i, j) {
        function k() {
            return "input" === e[0].tagName.toLowerCase() && f.type && "file" === f.type.toLowerCase()
        }

        function l(b) {
            if (!r) {
                r = !0;
                try {
                    for (var e = b.__files_ || b.target && b.target.files, j = [], k = [], l = 0; l < e.length; l++) {
                        var m = e.item(l);
                        c(a, h, f, m, b) ? j.push(m) : k.push(m)
                    }
                    d(h, i, a, g, f, f.ngfChange || f.ngfSelect, j, k, b), 0 === j.length && (b.target.value = j)
                } finally {
                    r = !1
                }
            }
        }

        function m(b) {
            f.ngfMultiple && b.attr("multiple", h(f.ngfMultiple)(a)), f.ngfCapture && b.attr("capture", h(f.ngfCapture)(a)), f.accept && b.attr("accept", f.accept);
            for (var c = 0; c < e[0].attributes.length; c++) {
                var d = e[0].attributes[c];
                (k() && "type" !== d.name || "type" !== d.name && "class" !== d.name && "id" !== d.name && "style" !== d.name) && b.attr(d.name, d.value)
            }
        }

        function n(b, c) {
            if (!c && (b || k())) return e.$$ngfRefElem || e;
            var d = angular.element('<input type="file">');
            return m(d), k() ? (e.replaceWith(d), e = d, d.attr("__ngf_gen__", !0), j(e)(a)) : (d.css("visibility", "hidden").css("position", "absolute").css("overflow", "hidden").css("width", "0px").css("height", "0px").css("z-index", "-100000").css("border", "none").css("margin", "0px").css("padding", "0px").attr("tabindex", "-1"), e.$$ngfRefElem && e.$$ngfRefElem.remove(), e.$$ngfRefElem = d, document.body.appendChild(d[0])), d
        }

        function o(b) {
            d(h, i, a, g, f, f.ngfChange || f.ngfSelect, [], [], b, !0)
        }

        function p(c) {
            function d(a) {
                a && i[0].click(), (k() || !a) && e.bind("click touchend", p)
            }
            if (e.attr("disabled") || q) return !1;
            null != c && (c.preventDefault(), c.stopPropagation());
            var g = !1 !== h(f.ngfResetOnClick)(a),
                i = n(c, g);
            return i && ((!c || g) && i.bind("change", l), c && g && !1 !== h(f.ngfResetModelOnClick)(a) && o(c), b(navigator.userAgent) ? setTimeout(function() {
                d(c)
            }, 0) : d(c)), !1
        }
        if (!e.attr("__ngf_gen__")) {
            a.$on("$destroy", function() {
                e.$$ngfRefElem && e.$$ngfRefElem.remove()
            });
            var q = !1; - 1 === f.ngfSelect.search(/\W+$files\W+/) && a.$watch(f.ngfSelect, function(a) {
                q = !1 === a
            });
            var r = !1;
            window.FileAPI && window.FileAPI.ngfFixIE ? window.FileAPI.ngfFixIE(e, n, m, l) : p()
        }
    }

    function b(a) {
        var b = a.match(/Android[^\d]*(\d+)\.(\d+)/);
        return b && b.length > 2 ? parseInt(b[1]) < 4 || 4 === parseInt(b[1]) && parseInt(b[2]) < 4 : /.*Windows.*Safari.*/.test(a)
    }
    ngFileUpload.directive("ngfSelect", ["$parse", "$timeout", "$compile", function(b, c, d) {
        return {
            restrict: "AEC",
            require: "?ngModel",
            link: function(e, f, g, h) {
                a(e, f, g, h, b, c, d)
            }
        }
    }]), ngFileUpload.validate = function(a, b, c, d, e) {
        function f(a) {
            if (a.length > 2 && "/" === a[0] && "/" === a[a.length - 1]) return a.substring(1, a.length - 1);
            var b = a.split(","),
                c = "";
            if (b.length > 1)
                for (var d = 0; d < b.length; d++) c += "(" + f(b[d]) + ")", d < b.length - 1 && (c += "|");
            else 0 === a.indexOf(".") && (a = "*" + a), c = "^" + a.replace(new RegExp("[.\\\\+*?\\[\\^\\]$(){}=!<>|:\\-]", "g"), "\\$&") + "$", c = c.replace(/\\\*/g, ".*").replace(/\\\?/g, ".");
            return c
        }
        var g = b(c.ngfAccept)(a, {
                $file: d,
                $event: e
            }),
            h = b(c.ngfMaxSize)(a, {
                $file: d,
                $event: e
            }) || 9007199254740991,
            i = b(c.ngfMinSize)(a, {
                $file: d,
                $event: e
            }) || -1;
        if (null != g && angular.isString(g)) {
            var j = new RegExp(f(g), "gi");
            g = null != d.type && j.test(d.type.toLowerCase()) || null != d.name && j.test(d.name.toLowerCase())
        }
        return (null == g || g) && (null == d.size || d.size < h && d.size > i)
    }, ngFileUpload.updateModel = function(a, b, c, d, e, f, g, h, i, j) {
        function k() {
            if (!0 === a(e.ngfKeep)(c)) {
                var j = (d.$modelValue || []).slice(0);
                if (g && g.length)
                    if (!0 === a(e.ngfKeepDistinct)(c)) {
                        for (var k = j.length, l = 0; l < g.length; l++) {
                            for (var m = 0; k > m && g[l].name !== j[m].name; m++);
                            m === k && j.push(g[l])
                        }
                        g = j
                    } else g = j.concat(g);
                else g = j
            }
            d && (a(e.ngModel).assign(c, g), b(function() {
                d && d.$setViewValue(null != g && 0 === g.length ? null : g)
            })), e.ngModelRejected && a(e.ngModelRejected).assign(c, h), f && a(f)(c, {
                $files: g,
                $rejectedFiles: h,
                $event: i
            })
        }
        j ? k() : b(function() {
            k()
        })
    };
    var c = ngFileUpload.validate,
        d = ngFileUpload.updateModel
}(),
function() {
    function a(a, e, f, g, h, i, j) {
        function k(a, b, d) {
            var e = !0,
                f = d.dataTransfer.items;
            if (null != f)
                for (var g = 0; g < f.length && e; g++) e = e && ("file" === f[g].kind || "" === f[g].kind) && c(a, h, b, f[g], d);
            var i = h(b.ngfDragOverClass)(a, {
                $event: d
            });
            return i && (i.delay && (r = i.delay), i.accept && (i = e ? i.accept : i.reject)), i || b.ngfDragOverClass || "dragover"
        }

        function l(b, d, e, g) {
            function k(d) {
                c(a, h, f, d, b) ? m.push(d) : n.push(d)
            }

            function l(a, b, c) {
                if (null != b)
                    if (b.isDirectory) {
                        var d = (c || "") + b.name;
                        k({
                            name: b.name,
                            type: "directory",
                            path: d
                        });
                        var e = b.createReader(),
                            f = [];
                        p++;
                        var g = function() {
                            e.readEntries(function(d) {
                                try {
                                    if (d.length) f = f.concat(Array.prototype.slice.call(d || [], 0)), g();
                                    else {
                                        for (var e = 0; e < f.length; e++) l(a, f[e], (c || "") + b.name + "/");
                                        p--
                                    }
                                } catch (a) {
                                    p--, console.error(a)
                                }
                            }, function() {
                                p--
                            })
                        };
                        g()
                    } else p++, b.file(function(a) {
                        try {
                            p--, a.path = (c || "") + a.name, k(a)
                        } catch (a) {
                            p--, console.error(a)
                        }
                    }, function() {
                        p--
                    })
            }
            var m = [],
                n = [],
                o = b.dataTransfer.items,
                p = 0;
            if (o && o.length > 0 && "file" !== j.protocol())
                for (var q = 0; q < o.length; q++) {
                    if (o[q].webkitGetAsEntry && o[q].webkitGetAsEntry() && o[q].webkitGetAsEntry().isDirectory) {
                        var r = o[q].webkitGetAsEntry();
                        if (r.isDirectory && !e) continue;
                        null != r && l(m, r)
                    } else {
                        var s = o[q].getAsFile();
                        null != s && k(s)
                    }
                    if (!g && m.length > 0) break
                } else {
                    var t = b.dataTransfer.files;
                    if (null != t)
                        for (var u = 0; u < t.length && (k(t.item(u)), g || !(m.length > 0)); u++);
                }
            var v = 0;
            ! function a(b) {
                i(function() {
                    if (p) 10 * v++ < 2e4 && a(10);
                    else {
                        if (!g && m.length > 1) {
                            for (q = 0;
                                "directory" === m[q].type;) q++;
                            m = [m[q]]
                        }
                        d(m, n)
                    }
                }, b || 0)
            }()
        }
        var m = b();
        if (f.dropAvailable && i(function() {
                a[f.dropAvailable] ? a[f.dropAvailable].value = m : a[f.dropAvailable] = m
            }), !m) return void(!0 === h(f.ngfHideOnDropNotAvailable)(a) && e.css("display", "none"));
        var n = !1; - 1 === f.ngfDrop.search(/\W+$files\W+/) && a.$watch(f.ngfDrop, function(a) {
            n = !1 === a
        });
        var o, p = null,
            q = h(f.ngfStopPropagation),
            r = 1;
        e[0].addEventListener("dragover", function(b) {
            if (!e.attr("disabled") && !n) {
                if (b.preventDefault(), q(a) && b.stopPropagation(), navigator.userAgent.indexOf("Chrome") > -1) {
                    var c = b.dataTransfer.effectAllowed;
                    b.dataTransfer.dropEffect = "move" === c || "linkMove" === c ? "move" : "copy"
                }
                i.cancel(p), a.actualDragOverClass || (o = k(a, f, b)), e.addClass(o)
            }
        }, !1), e[0].addEventListener("dragenter", function(b) {
            e.attr("disabled") || n || (b.preventDefault(), q(a) && b.stopPropagation())
        }, !1), e[0].addEventListener("dragleave", function() {
            e.attr("disabled") || n || (p = i(function() {
                e.removeClass(o), o = null
            }, r || 1))
        }, !1), e[0].addEventListener("drop", function(b) {
            e.attr("disabled") || n || (b.preventDefault(), q(a) && b.stopPropagation(), e.removeClass(o), o = null, l(b, function(c, e) {
                d(h, i, a, g, f, f.ngfChange || f.ngfDrop, c, e, b)
            }, !1 !== h(f.ngfAllowDir)(a), f.multiple || h(f.ngfMultiple)(a)))
        }, !1)
    }

    function b() {
        var a = document.createElement("div");
        return "draggable" in a && "ondrop" in a
    }
    var c = ngFileUpload.validate,
        d = ngFileUpload.updateModel;
    ngFileUpload.directive("ngfDrop", ["$parse", "$timeout", "$location", function(b, c, d) {
        return {
            restrict: "AEC",
            require: "?ngModel",
            link: function(e, f, g, h) {
                a(e, f, g, h, b, c, d)
            }
        }
    }]), ngFileUpload.directive("ngfNoFileDrop", function() {
        return function(a, c) {
            b() && c.css("display", "none")
        }
    }), ngFileUpload.directive("ngfDropAvailable", ["$parse", "$timeout", function(a, c) {
        return function(d, e, f) {
            if (b()) {
                var g = a(f.ngfDropAvailable);
                c(function() {
                    g(d), g.assign && g.assign(d, !0)
                })
            }
        }
    }]), ngFileUpload.directive("ngfSrc", ["$parse", "$timeout", function(a, b) {
        return {
            restrict: "AE",
            link: function(d, e, f) {
                window.FileReader && d.$watch(f.ngfSrc, function(g) {
                    g && c(d, a, f, g, null) && (!window.FileAPI || -1 === navigator.userAgent.indexOf("MSIE 8") || g.size < 2e4) && (!window.FileAPI || -1 === navigator.userAgent.indexOf("MSIE 9") || g.size < 4e6) ? b(function() {
                        var a = window.URL || window.webkitURL;
                        if (a && a.createObjectURL) e.attr("src", a.createObjectURL(g));
                        else {
                            var c = new FileReader;
                            c.readAsDataURL(g), c.onload = function(a) {
                                b(function() {
                                    e.attr("src", a.target.result)
                                })
                            }
                        }
                    }) : e.attr("src", f.ngfDefaultSrc || "")
                })
            }
        }
    }])
}(),
function(a, b) {
    "object" == typeof exports && "undefined" != typeof module ? module.exports = b() : "function" == typeof define && define.amd ? define(b) : a.moment = b()
}(this, function() {
    "use strict";

    function a() {
        return Ld.apply(null, arguments)
    }

    function b(a) {
        return "[object Array]" === Object.prototype.toString.call(a)
    }

    function c(a) {
        return a instanceof Date || "[object Date]" === Object.prototype.toString.call(a)
    }

    function d(a, b) {
        var c, d = [];
        for (c = 0; c < a.length; ++c) d.push(b(a[c], c));
        return d
    }

    function e(a, b) {
        return Object.prototype.hasOwnProperty.call(a, b)
    }

    function f(a, b) {
        for (var c in b) e(b, c) && (a[c] = b[c]);
        return e(b, "toString") && (a.toString = b.toString), e(b, "valueOf") && (a.valueOf = b.valueOf), a
    }

    function g(a, b, c, d) {
        return Ba(a, b, c, d, !0).utc()
    }

    function h() {
        return {
            empty: !1,
            unusedTokens: [],
            unusedInput: [],
            overflow: -2,
            charsLeftOver: 0,
            nullInput: !1,
            invalidMonth: null,
            invalidFormat: !1,
            userInvalidated: !1,
            iso: !1
        }
    }

    function i(a) {
        return null == a._pf && (a._pf = h()), a._pf
    }

    function j(a) {
        if (null == a._isValid) {
            var b = i(a);
            a._isValid = !(isNaN(a._d.getTime()) || !(b.overflow < 0) || b.empty || b.invalidMonth || b.invalidWeekday || b.nullInput || b.invalidFormat || b.userInvalidated), a._strict && (a._isValid = a._isValid && 0 === b.charsLeftOver && 0 === b.unusedTokens.length && void 0 === b.bigHour)
        }
        return a._isValid
    }

    function k(a) {
        var b = g(NaN);
        return null != a ? f(i(b), a) : i(b).userInvalidated = !0, b
    }

    function l(a, b) {
        var c, d, e;
        if (void 0 !== b._isAMomentObject && (a._isAMomentObject = b._isAMomentObject), void 0 !== b._i && (a._i = b._i), void 0 !== b._f && (a._f = b._f), void 0 !== b._l && (a._l = b._l), void 0 !== b._strict && (a._strict = b._strict), void 0 !== b._tzm && (a._tzm = b._tzm), void 0 !== b._isUTC && (a._isUTC = b._isUTC), void 0 !== b._offset && (a._offset = b._offset), void 0 !== b._pf && (a._pf = i(b)), void 0 !== b._locale && (a._locale = b._locale), Nd.length > 0)
            for (c in Nd) d = Nd[c], void 0 !== (e = b[d]) && (a[d] = e);
        return a
    }

    function m(b) {
        l(this, b), this._d = new Date(null != b._d ? b._d.getTime() : NaN), !1 === Od && (Od = !0, a.updateOffset(this), Od = !1)
    }

    function n(a) {
        return a instanceof m || null != a && null != a._isAMomentObject
    }

    function o(a) {
        return 0 > a ? Math.ceil(a) : Math.floor(a)
    }

    function p(a) {
        var b = +a,
            c = 0;
        return 0 !== b && isFinite(b) && (c = o(b)), c
    }

    function q(a, b, c) {
        var d, e = Math.min(a.length, b.length),
            f = Math.abs(a.length - b.length),
            g = 0;
        for (d = 0; e > d; d++)(c && a[d] !== b[d] || !c && p(a[d]) !== p(b[d])) && g++;
        return g + f
    }

    function r() {}

    function s(a) {
        return a ? a.toLowerCase().replace("_", "-") : a
    }

    function t(a) {
        for (var b, c, d, e, f = 0; f < a.length;) {
            for (e = s(a[f]).split("-"), b = e.length, c = s(a[f + 1]), c = c ? c.split("-") : null; b > 0;) {
                if (d = u(e.slice(0, b).join("-"))) return d;
                if (c && c.length >= b && q(e, c, !0) >= b - 1) break;
                b--
            }
            f++
        }
        return null
    }

    function u(a) {
        var b = null;
        if (!Pd[a] && "undefined" != typeof module && module && module.exports) try {
            b = Md._abbr, require("./locale/" + a), v(b)
        } catch (a) {}
        return Pd[a]
    }

    function v(a, b) {
        var c;
        return a && (c = void 0 === b ? x(a) : w(a, b)) && (Md = c), Md._abbr
    }

    function w(a, b) {
        return null !== b ? (b.abbr = a, Pd[a] = Pd[a] || new r, Pd[a].set(b), v(a), Pd[a]) : (delete Pd[a], null)
    }

    function x(a) {
        var c;
        if (a && a._locale && a._locale._abbr && (a = a._locale._abbr), !a) return Md;
        if (!b(a)) {
            if (c = u(a)) return c;
            a = [a]
        }
        return t(a)
    }

    function y(a, b) {
        var c = a.toLowerCase();
        Qd[c] = Qd[c + "s"] = Qd[b] = a
    }

    function z(a) {
        return "string" == typeof a ? Qd[a] || Qd[a.toLowerCase()] : void 0
    }

    function A(a) {
        var b, c, d = {};
        for (c in a) e(a, c) && (b = z(c)) && (d[b] = a[c]);
        return d
    }

    function B(b, c) {
        return function(d) {
            return null != d ? (D(this, b, d), a.updateOffset(this, c), this) : C(this, b)
        }
    }

    function C(a, b) {
        return a._d["get" + (a._isUTC ? "UTC" : "") + b]()
    }

    function D(a, b, c) {
        return a._d["set" + (a._isUTC ? "UTC" : "") + b](c)
    }

    function E(a, b) {
        var c;
        if ("object" == typeof a)
            for (c in a) this.set(c, a[c]);
        else if (a = z(a), "function" == typeof this[a]) return this[a](b);
        return this
    }

    function F(a, b, c) {
        var d = "" + Math.abs(a),
            e = b - d.length;
        return (a >= 0 ? c ? "+" : "" : "-") + Math.pow(10, Math.max(0, e)).toString().substr(1) + d
    }

    function G(a, b, c, d) {
        var e = d;
        "string" == typeof d && (e = function() {
            return this[d]()
        }), a && (Ud[a] = e), b && (Ud[b[0]] = function() {
            return F(e.apply(this, arguments), b[1], b[2])
        }), c && (Ud[c] = function() {
            return this.localeData().ordinal(e.apply(this, arguments), a)
        })
    }

    function H(a) {
        return a.match(/\[[\s\S]/) ? a.replace(/^\[|\]$/g, "") : a.replace(/\\/g, "")
    }

    function I(a) {
        var b, c, d = a.match(Rd);
        for (b = 0, c = d.length; c > b; b++) Ud[d[b]] ? d[b] = Ud[d[b]] : d[b] = H(d[b]);
        return function(e) {
            var f = "";
            for (b = 0; c > b; b++) f += d[b] instanceof Function ? d[b].call(e, a) : d[b];
            return f
        }
    }

    function J(a, b) {
        return a.isValid() ? (b = K(b, a.localeData()), Td[b] = Td[b] || I(b), Td[b](a)) : a.localeData().invalidDate()
    }

    function K(a, b) {
        function c(a) {
            return b.longDateFormat(a) || a
        }
        var d = 5;
        for (Sd.lastIndex = 0; d >= 0 && Sd.test(a);) a = a.replace(Sd, c), Sd.lastIndex = 0, d -= 1;
        return a
    }

    function L(a) {
        return "function" == typeof a && "[object Function]" === Object.prototype.toString.call(a)
    }

    function M(a, b, c) {
        he[a] = L(b) ? b : function(a) {
            return a && c ? c : b
        }
    }

    function N(a, b) {
        return e(he, a) ? he[a](b._strict, b._locale) : new RegExp(O(a))
    }

    function O(a) {
        return a.replace("\\", "").replace(/\\(\[)|\\(\])|\[([^\]\[]*)\]|\\(.)/g, function(a, b, c, d, e) {
            return b || c || d || e
        }).replace(/[-\/\\^$*+?.()|[\]{}]/g, "\\$&")
    }

    function P(a, b) {
        var c, d = b;
        for ("string" == typeof a && (a = [a]), "number" == typeof b && (d = function(a, c) {
                c[b] = p(a)
            }), c = 0; c < a.length; c++) ie[a[c]] = d
    }

    function Q(a, b) {
        P(a, function(a, c, d, e) {
            d._w = d._w || {}, b(a, d._w, d, e)
        })
    }

    function R(a, b, c) {
        null != b && e(ie, a) && ie[a](b, c._a, c, a)
    }

    function S(a, b) {
        return new Date(Date.UTC(a, b + 1, 0)).getUTCDate()
    }

    function T(a) {
        return this._months[a.month()]
    }

    function U(a) {
        return this._monthsShort[a.month()]
    }

    function V(a, b, c) {
        var d, e, f;
        for (this._monthsParse || (this._monthsParse = [], this._longMonthsParse = [], this._shortMonthsParse = []), d = 0; 12 > d; d++) {
            if (e = g([2e3, d]), c && !this._longMonthsParse[d] && (this._longMonthsParse[d] = new RegExp("^" + this.months(e, "").replace(".", "") + "$", "i"), this._shortMonthsParse[d] = new RegExp("^" + this.monthsShort(e, "").replace(".", "") + "$", "i")), c || this._monthsParse[d] || (f = "^" + this.months(e, "") + "|^" + this.monthsShort(e, ""), this._monthsParse[d] = new RegExp(f.replace(".", ""), "i")), c && "MMMM" === b && this._longMonthsParse[d].test(a)) return d;
            if (c && "MMM" === b && this._shortMonthsParse[d].test(a)) return d;
            if (!c && this._monthsParse[d].test(a)) return d
        }
    }

    function W(a, b) {
        var c;
        return "string" == typeof b && "number" != typeof(b = a.localeData().monthsParse(b)) ? a : (c = Math.min(a.date(), S(a.year(), b)), a._d["set" + (a._isUTC ? "UTC" : "") + "Month"](b, c), a)
    }

    function X(b) {
        return null != b ? (W(this, b), a.updateOffset(this, !0), this) : C(this, "Month")
    }

    function Y() {
        return S(this.year(), this.month())
    }

    function Z(a) {
        var b, c = a._a;
        return c && -2 === i(a).overflow && (b = c[ke] < 0 || c[ke] > 11 ? ke : c[le] < 1 || c[le] > S(c[je], c[ke]) ? le : c[me] < 0 || c[me] > 24 || 24 === c[me] && (0 !== c[ne] || 0 !== c[oe] || 0 !== c[pe]) ? me : c[ne] < 0 || c[ne] > 59 ? ne : c[oe] < 0 || c[oe] > 59 ? oe : c[pe] < 0 || c[pe] > 999 ? pe : -1, i(a)._overflowDayOfYear && (je > b || b > le) && (b = le), i(a).overflow = b), a
    }

    function $(b) {
        !1 === a.suppressDeprecationWarnings && "undefined" != typeof console && console.warn && console.warn("Deprecation warning: " + b)
    }

    function _(a, b) {
        var c = !0;
        return f(function() {
            return c && ($(a + "\n" + (new Error).stack), c = !1), b.apply(this, arguments)
        }, b)
    }

    function aa(a, b) {
        se[a] || ($(b), se[a] = !0)
    }

    function ba(a) {
        var b, c, d = a._i,
            e = te.exec(d);
        if (e) {
            for (i(a).iso = !0, b = 0, c = ue.length; c > b; b++)
                if (ue[b][1].exec(d)) {
                    a._f = ue[b][0];
                    break
                }
            for (b = 0, c = ve.length; c > b; b++)
                if (ve[b][1].exec(d)) {
                    a._f += (e[6] || " ") + ve[b][0];
                    break
                }
            d.match(ee) && (a._f += "Z"), ua(a)
        } else a._isValid = !1
    }

    function ca(b) {
        var c = we.exec(b._i);
        return null !== c ? void(b._d = new Date(+c[1])) : (ba(b), void(!1 === b._isValid && (delete b._isValid, a.createFromInputFallback(b))))
    }

    function da(a, b, c, d, e, f, g) {
        var h = new Date(a, b, c, d, e, f, g);
        return 1970 > a && h.setFullYear(a), h
    }

    function ea(a) {
        var b = new Date(Date.UTC.apply(null, arguments));
        return 1970 > a && b.setUTCFullYear(a), b
    }

    function fa(a) {
        return ga(a) ? 366 : 365
    }

    function ga(a) {
        return a % 4 == 0 && a % 100 != 0 || a % 400 == 0
    }

    function ha() {
        return ga(this.year())
    }

    function ia(a, b, c) {
        var d, e = c - b,
            f = c - a.day();
        return f > e && (f -= 7), e - 7 > f && (f += 7), d = Ca(a).add(f, "d"), {
            week: Math.ceil(d.dayOfYear() / 7),
            year: d.year()
        }
    }

    function ja(a) {
        return ia(a, this._week.dow, this._week.doy).week
    }

    function ka() {
        return this._week.dow
    }

    function la() {
        return this._week.doy
    }

    function ma(a) {
        var b = this.localeData().week(this);
        return null == a ? b : this.add(7 * (a - b), "d")
    }

    function na(a) {
        var b = ia(this, 1, 4).week;
        return null == a ? b : this.add(7 * (a - b), "d")
    }

    function oa(a, b, c, d, e) {
        var f, g = 6 + e - d,
            h = ea(a, 0, 1 + g),
            i = h.getUTCDay();
        return e > i && (i += 7), c = null != c ? 1 * c : e, f = 1 + g + 7 * (b - 1) - i + c, {
            year: f > 0 ? a : a - 1,
            dayOfYear: f > 0 ? f : fa(a - 1) + f
        }
    }

    function pa(a) {
        var b = Math.round((this.clone().startOf("day") - this.clone().startOf("year")) / 864e5) + 1;
        return null == a ? b : this.add(a - b, "d")
    }

    function qa(a, b, c) {
        return null != a ? a : null != b ? b : c
    }

    function ra(a) {
        var b = new Date;
        return a._useUTC ? [b.getUTCFullYear(), b.getUTCMonth(), b.getUTCDate()] : [b.getFullYear(), b.getMonth(), b.getDate()]
    }

    function sa(a) {
        var b, c, d, e, f = [];
        if (!a._d) {
            for (d = ra(a), a._w && null == a._a[le] && null == a._a[ke] && ta(a), a._dayOfYear && (e = qa(a._a[je], d[je]), a._dayOfYear > fa(e) && (i(a)._overflowDayOfYear = !0), c = ea(e, 0, a._dayOfYear), a._a[ke] = c.getUTCMonth(), a._a[le] = c.getUTCDate()), b = 0; 3 > b && null == a._a[b]; ++b) a._a[b] = f[b] = d[b];
            for (; 7 > b; b++) a._a[b] = f[b] = null == a._a[b] ? 2 === b ? 1 : 0 : a._a[b];
            24 === a._a[me] && 0 === a._a[ne] && 0 === a._a[oe] && 0 === a._a[pe] && (a._nextDay = !0, a._a[me] = 0), a._d = (a._useUTC ? ea : da).apply(null, f), null != a._tzm && a._d.setUTCMinutes(a._d.getUTCMinutes() - a._tzm), a._nextDay && (a._a[me] = 24)
        }
    }

    function ta(a) {
        var b, c, d, e, f, g, h;
        b = a._w, null != b.GG || null != b.W || null != b.E ? (f = 1, g = 4, c = qa(b.GG, a._a[je], ia(Ca(), 1, 4).year), d = qa(b.W, 1), e = qa(b.E, 1)) : (f = a._locale._week.dow, g = a._locale._week.doy, c = qa(b.gg, a._a[je], ia(Ca(), f, g).year), d = qa(b.w, 1), null != b.d ? (e = b.d, f > e && ++d) : e = null != b.e ? b.e + f : f), h = oa(c, d, e, g, f), a._a[je] = h.year, a._dayOfYear = h.dayOfYear
    }

    function ua(b) {
        if (b._f === a.ISO_8601) return void ba(b);
        b._a = [], i(b).empty = !0;
        var c, d, e, f, g, h = "" + b._i,
            j = h.length,
            k = 0;
        for (e = K(b._f, b._locale).match(Rd) || [], c = 0; c < e.length; c++) f = e[c], d = (h.match(N(f, b)) || [])[0], d && (g = h.substr(0, h.indexOf(d)), g.length > 0 && i(b).unusedInput.push(g), h = h.slice(h.indexOf(d) + d.length), k += d.length), Ud[f] ? (d ? i(b).empty = !1 : i(b).unusedTokens.push(f), R(f, d, b)) : b._strict && !d && i(b).unusedTokens.push(f);
        i(b).charsLeftOver = j - k, h.length > 0 && i(b).unusedInput.push(h), !0 === i(b).bigHour && b._a[me] <= 12 && b._a[me] > 0 && (i(b).bigHour = void 0), b._a[me] = va(b._locale, b._a[me], b._meridiem), sa(b), Z(b)
    }

    function va(a, b, c) {
        var d;
        return null == c ? b : null != a.meridiemHour ? a.meridiemHour(b, c) : null != a.isPM ? (d = a.isPM(c), d && 12 > b && (b += 12), d || 12 !== b || (b = 0), b) : b
    }

    function wa(a) {
        var b, c, d, e, g;
        if (0 === a._f.length) return i(a).invalidFormat = !0, void(a._d = new Date(NaN));
        for (e = 0; e < a._f.length; e++) g = 0, b = l({}, a), null != a._useUTC && (b._useUTC = a._useUTC), b._f = a._f[e], ua(b), j(b) && (g += i(b).charsLeftOver, g += 10 * i(b).unusedTokens.length, i(b).score = g, (null == d || d > g) && (d = g, c = b));
        f(a, c || b)
    }

    function xa(a) {
        if (!a._d) {
            var b = A(a._i);
            a._a = [b.year, b.month, b.day || b.date, b.hour, b.minute, b.second, b.millisecond], sa(a)
        }
    }

    function ya(a) {
        var b = new m(Z(za(a)));
        return b._nextDay && (b.add(1, "d"), b._nextDay = void 0), b
    }

    function za(a) {
        var d = a._i,
            e = a._f;
        return a._locale = a._locale || x(a._l), null === d || void 0 === e && "" === d ? k({
            nullInput: !0
        }) : ("string" == typeof d && (a._i = d = a._locale.preparse(d)), n(d) ? new m(Z(d)) : (b(e) ? wa(a) : e ? ua(a) : c(d) ? a._d = d : Aa(a), a))
    }

    function Aa(e) {
        var f = e._i;
        void 0 === f ? e._d = new Date : c(f) ? e._d = new Date(+f) : "string" == typeof f ? ca(e) : b(f) ? (e._a = d(f.slice(0), function(a) {
            return parseInt(a, 10)
        }), sa(e)) : "object" == typeof f ? xa(e) : "number" == typeof f ? e._d = new Date(f) : a.createFromInputFallback(e)
    }

    function Ba(a, b, c, d, e) {
        var f = {};
        return "boolean" == typeof c && (d = c, c = void 0), f._isAMomentObject = !0, f._useUTC = f._isUTC = e, f._l = c, f._i = a, f._f = b, f._strict = d, ya(f)
    }

    function Ca(a, b, c, d) {
        return Ba(a, b, c, d, !1)
    }

    function Da(a, c) {
        var d, e;
        if (1 === c.length && b(c[0]) && (c = c[0]), !c.length) return Ca();
        for (d = c[0], e = 1; e < c.length; ++e)(!c[e].isValid() || c[e][a](d)) && (d = c[e]);
        return d
    }

    function Ea() {
        return Da("isBefore", [].slice.call(arguments, 0))
    }

    function Fa() {
        return Da("isAfter", [].slice.call(arguments, 0))
    }

    function Ga(a) {
        var b = A(a),
            c = b.year || 0,
            d = b.quarter || 0,
            e = b.month || 0,
            f = b.week || 0,
            g = b.day || 0,
            h = b.hour || 0,
            i = b.minute || 0,
            j = b.second || 0,
            k = b.millisecond || 0;
        this._milliseconds = +k + 1e3 * j + 6e4 * i + 36e5 * h, this._days = +g + 7 * f, this._months = +e + 3 * d + 12 * c, this._data = {}, this._locale = x(), this._bubble()
    }

    function Ha(a) {
        return a instanceof Ga
    }

    function Ia(a, b) {
        G(a, 0, 0, function() {
            var a = this.utcOffset(),
                c = "+";
            return 0 > a && (a = -a, c = "-"), c + F(~~(a / 60), 2) + b + F(~~a % 60, 2)
        })
    }

    function Ja(a) {
        var b = (a || "").match(ee) || [],
            c = b[b.length - 1] || [],
            d = (c + "").match(Be) || ["-", 0, 0],
            e = 60 * d[1] + p(d[2]);
        return "+" === d[0] ? e : -e
    }

    function Ka(b, d) {
        var e, f;
        return d._isUTC ? (e = d.clone(), f = (n(b) || c(b) ? +b : +Ca(b)) - +e, e._d.setTime(+e._d + f), a.updateOffset(e, !1), e) : Ca(b).local()
    }

    function La(a) {
        return 15 * -Math.round(a._d.getTimezoneOffset() / 15)
    }

    function Ma(b, c) {
        var d, e = this._offset || 0;
        return null != b ? ("string" == typeof b && (b = Ja(b)), Math.abs(b) < 16 && (b *= 60), !this._isUTC && c && (d = La(this)), this._offset = b, this._isUTC = !0, null != d && this.add(d, "m"), e !== b && (!c || this._changeInProgress ? ab(this, Xa(b - e, "m"), 1, !1) : this._changeInProgress || (this._changeInProgress = !0, a.updateOffset(this, !0), this._changeInProgress = null)), this) : this._isUTC ? e : La(this)
    }

    function Na(a, b) {
        return null != a ? ("string" != typeof a && (a = -a), this.utcOffset(a, b), this) : -this.utcOffset()
    }

    function Oa(a) {
        return this.utcOffset(0, a)
    }

    function Pa(a) {
        return this._isUTC && (this.utcOffset(0, a), this._isUTC = !1, a && this.subtract(La(this), "m")), this
    }

    function Qa() {
        return this._tzm ? this.utcOffset(this._tzm) : "string" == typeof this._i && this.utcOffset(Ja(this._i)), this
    }

    function Ra(a) {
        return a = a ? Ca(a).utcOffset() : 0, (this.utcOffset() - a) % 60 == 0
    }

    function Sa() {
        return this.utcOffset() > this.clone().month(0).utcOffset() || this.utcOffset() > this.clone().month(5).utcOffset()
    }

    function Ta() {
        if (void 0 !== this._isDSTShifted) return this._isDSTShifted;
        var a = {};
        if (l(a, this), a = za(a), a._a) {
            var b = a._isUTC ? g(a._a) : Ca(a._a);
            this._isDSTShifted = this.isValid() && q(a._a, b.toArray()) > 0
        } else this._isDSTShifted = !1;
        return this._isDSTShifted
    }

    function Ua() {
        return !this._isUTC
    }

    function Va() {
        return this._isUTC
    }

    function Wa() {
        return this._isUTC && 0 === this._offset
    }

    function Xa(a, b) {
        var c, d, f, g = a,
            h = null;
        return Ha(a) ? g = {
            ms: a._milliseconds,
            d: a._days,
            M: a._months
        } : "number" == typeof a ? (g = {}, b ? g[b] = a : g.milliseconds = a) : (h = Ce.exec(a)) ? (c = "-" === h[1] ? -1 : 1, g = {
            y: 0,
            d: p(h[le]) * c,
            h: p(h[me]) * c,
            m: p(h[ne]) * c,
            s: p(h[oe]) * c,
            ms: p(h[pe]) * c
        }) : (h = De.exec(a)) ? (c = "-" === h[1] ? -1 : 1, g = {
            y: Ya(h[2], c),
            M: Ya(h[3], c),
            d: Ya(h[4], c),
            h: Ya(h[5], c),
            m: Ya(h[6], c),
            s: Ya(h[7], c),
            w: Ya(h[8], c)
        }) : null == g ? g = {} : "object" == typeof g && ("from" in g || "to" in g) && (f = $a(Ca(g.from), Ca(g.to)), g = {}, g.ms = f.milliseconds, g.M = f.months), d = new Ga(g), Ha(a) && e(a, "_locale") && (d._locale = a._locale), d
    }

    function Ya(a, b) {
        var c = a && parseFloat(a.replace(",", "."));
        return (isNaN(c) ? 0 : c) * b
    }

    function Za(a, b) {
        var c = {
            milliseconds: 0,
            months: 0
        };
        return c.months = b.month() - a.month() + 12 * (b.year() - a.year()), a.clone().add(c.months, "M").isAfter(b) && --c.months, c.milliseconds = +b - +a.clone().add(c.months, "M"), c
    }

    function $a(a, b) {
        var c;
        return b = Ka(b, a), a.isBefore(b) ? c = Za(a, b) : (c = Za(b, a), c.milliseconds = -c.milliseconds, c.months = -c.months), c
    }

    function _a(a, b) {
        return function(c, d) {
            var e, f;
            return null === d || isNaN(+d) || (aa(b, "moment()." + b + "(period, number) is deprecated. Please use moment()." + b + "(number, period)."), f = c, c = d, d = f), c = "string" == typeof c ? +c : c, e = Xa(c, d), ab(this, e, a), this
        }
    }

    function ab(b, c, d, e) {
        var f = c._milliseconds,
            g = c._days,
            h = c._months;
        e = null == e || e, f && b._d.setTime(+b._d + f * d), g && D(b, "Date", C(b, "Date") + g * d), h && W(b, C(b, "Month") + h * d), e && a.updateOffset(b, g || h)
    }

    function bb(a, b) {
        var c = a || Ca(),
            d = Ka(c, this).startOf("day"),
            e = this.diff(d, "days", !0),
            f = -6 > e ? "sameElse" : -1 > e ? "lastWeek" : 0 > e ? "lastDay" : 1 > e ? "sameDay" : 2 > e ? "nextDay" : 7 > e ? "nextWeek" : "sameElse";
        return this.format(b && b[f] || this.localeData().calendar(f, this, Ca(c)))
    }

    function cb() {
        return new m(this)
    }

    function db(a, b) {
        return b = z(void 0 !== b ? b : "millisecond"), "millisecond" === b ? (a = n(a) ? a : Ca(a), +this > +a) : (n(a) ? +a : +Ca(a)) < +this.clone().startOf(b)
    }

    function eb(a, b) {
        var c;
        return b = z(void 0 !== b ? b : "millisecond"), "millisecond" === b ? +(a = n(a) ? a : Ca(a)) > +this : (c = n(a) ? +a : +Ca(a), +this.clone().endOf(b) < c)
    }

    function fb(a, b, c) {
        return this.isAfter(a, c) && this.isBefore(b, c)
    }

    function gb(a, b) {
        var c;
        return b = z(b || "millisecond"), "millisecond" === b ? (a = n(a) ? a : Ca(a), +this == +a) : (c = +Ca(a), +this.clone().startOf(b) <= c && c <= +this.clone().endOf(b))
    }

    function hb(a, b, c) {
        var d, e, f = Ka(a, this),
            g = 6e4 * (f.utcOffset() - this.utcOffset());
        return b = z(b), "year" === b || "month" === b || "quarter" === b ? (e = ib(this, f), "quarter" === b ? e /= 3 : "year" === b && (e /= 12)) : (d = this - f, e = "second" === b ? d / 1e3 : "minute" === b ? d / 6e4 : "hour" === b ? d / 36e5 : "day" === b ? (d - g) / 864e5 : "week" === b ? (d - g) / 6048e5 : d), c ? e : o(e)
    }

    function ib(a, b) {
        var c, d, e = 12 * (b.year() - a.year()) + (b.month() - a.month()),
            f = a.clone().add(e, "months");
        return 0 > b - f ? (c = a.clone().add(e - 1, "months"), d = (b - f) / (f - c)) : (c = a.clone().add(e + 1, "months"), d = (b - f) / (c - f)), -(e + d)
    }

    function jb() {
        return this.clone().locale("en").format("ddd MMM DD YYYY HH:mm:ss [GMT]ZZ")
    }

    function kb() {
        var a = this.clone().utc();
        return 0 < a.year() && a.year() <= 9999 ? "function" == typeof Date.prototype.toISOString ? this.toDate().toISOString() : J(a, "YYYY-MM-DD[T]HH:mm:ss.SSS[Z]") : J(a, "YYYYYY-MM-DD[T]HH:mm:ss.SSS[Z]")
    }

    function lb(b) {
        var c = J(this, b || a.defaultFormat);
        return this.localeData().postformat(c)
    }

    function mb(a, b) {
        return this.isValid() ? Xa({
            to: this,
            from: a
        }).locale(this.locale()).humanize(!b) : this.localeData().invalidDate()
    }

    function nb(a) {
        return this.from(Ca(), a)
    }

    function ob(a, b) {
        return this.isValid() ? Xa({
            from: this,
            to: a
        }).locale(this.locale()).humanize(!b) : this.localeData().invalidDate()
    }

    function pb(a) {
        return this.to(Ca(), a)
    }

    function qb(a) {
        var b;
        return void 0 === a ? this._locale._abbr : (b = x(a), null != b && (this._locale = b), this)
    }

    function rb() {
        return this._locale
    }

    function sb(a) {
        switch (a = z(a)) {
            case "year":
                this.month(0);
            case "quarter":
            case "month":
                this.date(1);
            case "week":
            case "isoWeek":
            case "day":
                this.hours(0);
            case "hour":
                this.minutes(0);
            case "minute":
                this.seconds(0);
            case "second":
                this.milliseconds(0)
        }
        return "week" === a && this.weekday(0), "isoWeek" === a && this.isoWeekday(1), "quarter" === a && this.month(3 * Math.floor(this.month() / 3)), this
    }

    function tb(a) {
        return a = z(a), void 0 === a || "millisecond" === a ? this : this.startOf(a).add(1, "isoWeek" === a ? "week" : a).subtract(1, "ms")
    }

    function ub() {
        return +this._d - 6e4 * (this._offset || 0)
    }

    function vb() {
        return Math.floor(+this / 1e3)
    }

    function wb() {
        return this._offset ? new Date(+this) : this._d
    }

    function xb() {
        var a = this;
        return [a.year(), a.month(), a.date(), a.hour(), a.minute(), a.second(), a.millisecond()]
    }

    function yb() {
        var a = this;
        return {
            years: a.year(),
            months: a.month(),
            date: a.date(),
            hours: a.hours(),
            minutes: a.minutes(),
            seconds: a.seconds(),
            milliseconds: a.milliseconds()
        }
    }

    function zb() {
        return j(this)
    }

    function Ab() {
        return f({}, i(this))
    }

    function Bb() {
        return i(this).overflow
    }

    function Cb(a, b) {
        G(0, [a, a.length], 0, b)
    }

    function Db(a, b, c) {
        return ia(Ca([a, 11, 31 + b - c]), b, c).week
    }

    function Eb(a) {
        var b = ia(this, this.localeData()._week.dow, this.localeData()._week.doy).year;
        return null == a ? b : this.add(a - b, "y")
    }

    function Fb(a) {
        var b = ia(this, 1, 4).year;
        return null == a ? b : this.add(a - b, "y")
    }

    function Gb() {
        return Db(this.year(), 1, 4)
    }

    function Hb() {
        var a = this.localeData()._week;
        return Db(this.year(), a.dow, a.doy)
    }

    function Ib(a) {
        return null == a ? Math.ceil((this.month() + 1) / 3) : this.month(3 * (a - 1) + this.month() % 3)
    }

    function Jb(a, b) {
        return "string" != typeof a ? a : isNaN(a) ? (a = b.weekdaysParse(a), "number" == typeof a ? a : null) : parseInt(a, 10)
    }

    function Kb(a) {
        return this._weekdays[a.day()]
    }

    function Lb(a) {
        return this._weekdaysShort[a.day()]
    }

    function Mb(a) {
        return this._weekdaysMin[a.day()]
    }

    function Nb(a) {
        var b, c, d;
        for (this._weekdaysParse = this._weekdaysParse || [], b = 0; 7 > b; b++)
            if (this._weekdaysParse[b] || (c = Ca([2e3, 1]).day(b), d = "^" + this.weekdays(c, "") + "|^" + this.weekdaysShort(c, "") + "|^" + this.weekdaysMin(c, ""), this._weekdaysParse[b] = new RegExp(d.replace(".", ""), "i")), this._weekdaysParse[b].test(a)) return b
    }

    function Ob(a) {
        var b = this._isUTC ? this._d.getUTCDay() : this._d.getDay();
        return null != a ? (a = Jb(a, this.localeData()), this.add(a - b, "d")) : b
    }

    function Pb(a) {
        var b = (this.day() + 7 - this.localeData()._week.dow) % 7;
        return null == a ? b : this.add(a - b, "d")
    }

    function Qb(a) {
        return null == a ? this.day() || 7 : this.day(this.day() % 7 ? a : a - 7)
    }

    function Rb(a, b) {
        G(a, 0, 0, function() {
            return this.localeData().meridiem(this.hours(), this.minutes(), b)
        })
    }

    function Sb(a, b) {
        return b._meridiemParse
    }

    function Tb(a) {
        return "p" === (a + "").toLowerCase().charAt(0)
    }

    function Ub(a, b, c) {
        return a > 11 ? c ? "pm" : "PM" : c ? "am" : "AM"
    }

    function Vb(a, b) {
        b[pe] = p(1e3 * ("0." + a))
    }

    function Wb() {
        return this._isUTC ? "UTC" : ""
    }

    function Xb() {
        return this._isUTC ? "Coordinated Universal Time" : ""
    }

    function Yb(a) {
        return Ca(1e3 * a)
    }

    function Zb() {
        return Ca.apply(null, arguments).parseZone()
    }

    function $b(a, b, c) {
        var d = this._calendar[a];
        return "function" == typeof d ? d.call(b, c) : d
    }

    function _b(a) {
        var b = this._longDateFormat[a],
            c = this._longDateFormat[a.toUpperCase()];
        return b || !c ? b : (this._longDateFormat[a] = c.replace(/MMMM|MM|DD|dddd/g, function(a) {
            return a.slice(1)
        }), this._longDateFormat[a])
    }

    function ac() {
        return this._invalidDate
    }

    function bc(a) {
        return this._ordinal.replace("%d", a)
    }

    function cc(a) {
        return a
    }

    function dc(a, b, c, d) {
        var e = this._relativeTime[c];
        return "function" == typeof e ? e(a, b, c, d) : e.replace(/%d/i, a)
    }

    function ec(a, b) {
        var c = this._relativeTime[a > 0 ? "future" : "past"];
        return "function" == typeof c ? c(b) : c.replace(/%s/i, b)
    }

    function fc(a) {
        var b, c;
        for (c in a) b = a[c], "function" == typeof b ? this[c] = b : this["_" + c] = b;
        this._ordinalParseLenient = new RegExp(this._ordinalParse.source + "|" + /\d{1,2}/.source)
    }

    function gc(a, b, c, d) {
        var e = x(),
            f = g().set(d, b);
        return e[c](f, a)
    }

    function hc(a, b, c, d, e) {
        if ("number" == typeof a && (b = a, a = void 0), a = a || "", null != b) return gc(a, b, c, e);
        var f, g = [];
        for (f = 0; d > f; f++) g[f] = gc(a, f, c, e);
        return g
    }

    function ic(a, b) {
        return hc(a, b, "months", 12, "month")
    }

    function jc(a, b) {
        return hc(a, b, "monthsShort", 12, "month")
    }

    function kc(a, b) {
        return hc(a, b, "weekdays", 7, "day")
    }

    function lc(a, b) {
        return hc(a, b, "weekdaysShort", 7, "day")
    }

    function mc(a, b) {
        return hc(a, b, "weekdaysMin", 7, "day")
    }

    function nc() {
        var a = this._data;
        return this._milliseconds = Ye(this._milliseconds), this._days = Ye(this._days), this._months = Ye(this._months), a.milliseconds = Ye(a.milliseconds), a.seconds = Ye(a.seconds), a.minutes = Ye(a.minutes), a.hours = Ye(a.hours), a.months = Ye(a.months), a.years = Ye(a.years), this
    }

    function oc(a, b, c, d) {
        var e = Xa(b, c);
        return a._milliseconds += d * e._milliseconds, a._days += d * e._days, a._months += d * e._months, a._bubble()
    }

    function pc(a, b) {
        return oc(this, a, b, 1)
    }

    function qc(a, b) {
        return oc(this, a, b, -1)
    }

    function rc(a) {
        return 0 > a ? Math.floor(a) : Math.ceil(a)
    }

    function sc() {
        var a, b, c, d, e, f = this._milliseconds,
            g = this._days,
            h = this._months,
            i = this._data;
        return f >= 0 && g >= 0 && h >= 0 || 0 >= f && 0 >= g && 0 >= h || (f += 864e5 * rc(uc(h) + g), g = 0, h = 0), i.milliseconds = f % 1e3, a = o(f / 1e3), i.seconds = a % 60, b = o(a / 60), i.minutes = b % 60, c = o(b / 60), i.hours = c % 24, g += o(c / 24), e = o(tc(g)), h += e, g -= rc(uc(e)), d = o(h / 12), h %= 12, i.days = g, i.months = h, i.years = d, this
    }

    function tc(a) {
        return 4800 * a / 146097
    }

    function uc(a) {
        return 146097 * a / 4800
    }

    function vc(a) {
        var b, c, d = this._milliseconds;
        if ("month" === (a = z(a)) || "year" === a) return b = this._days + d / 864e5, c = this._months + tc(b), "month" === a ? c : c / 12;
        switch (b = this._days + Math.round(uc(this._months)), a) {
            case "week":
                return b / 7 + d / 6048e5;
            case "day":
                return b + d / 864e5;
            case "hour":
                return 24 * b + d / 36e5;
            case "minute":
                return 1440 * b + d / 6e4;
            case "second":
                return 86400 * b + d / 1e3;
            case "millisecond":
                return Math.floor(864e5 * b) + d;
            default:
                throw new Error("Unknown unit " + a)
        }
    }

    function wc() {
        return this._milliseconds + 864e5 * this._days + this._months % 12 * 2592e6 + 31536e6 * p(this._months / 12)
    }

    function xc(a) {
        return function() {
            return this.as(a)
        }
    }

    function yc(a) {
        return a = z(a), this[a + "s"]()
    }

    function zc(a) {
        return function() {
            return this._data[a]
        }
    }

    function Ac() {
        return o(this.days() / 7)
    }

    function Bc(a, b, c, d, e) {
        return e.relativeTime(b || 1, !!c, a, d)
    }

    function Cc(a, b, c) {
        var d = Xa(a).abs(),
            e = nf(d.as("s")),
            f = nf(d.as("m")),
            g = nf(d.as("h")),
            h = nf(d.as("d")),
            i = nf(d.as("M")),
            j = nf(d.as("y")),
            k = e < of.s && ["s", e] || 1 === f && ["m"] || f < of.m && ["mm", f] || 1 === g && ["h"] || g < of.h && ["hh", g] || 1 === h && ["d"] || h < of.d && ["dd", h] || 1 === i && ["M"] || i < of.M && ["MM", i] || 1 === j && ["y"] || ["yy", j];
        return k[2] = b, k[3] = +a > 0, k[4] = c, Bc.apply(null, k)
    }

    function Dc(a, b) {
        return void 0 !== of[a] && (void 0 === b ? of[a] : (of[a] = b, !0))
    }

    function Ec(a) {
        var b = this.localeData(),
            c = Cc(this, !a, b);
        return a && (c = b.pastFuture(+this, c)), b.postformat(c)
    }

    function Fc() {
        var a, b, c, d = pf(this._milliseconds) / 1e3,
            e = pf(this._days),
            f = pf(this._months);
        a = o(d / 60), b = o(a / 60), d %= 60, a %= 60, c = o(f / 12), f %= 12;
        var g = c,
            h = f,
            i = e,
            j = b,
            k = a,
            l = d,
            m = this.asSeconds();
        return m ? (0 > m ? "-" : "") + "P" + (g ? g + "Y" : "") + (h ? h + "M" : "") + (i ? i + "D" : "") + (j || k || l ? "T" : "") + (j ? j + "H" : "") + (k ? k + "M" : "") + (l ? l + "S" : "") : "P0D"
    }

    function Gc(a, b) {
        var c = a.split("_");
        return b % 10 == 1 && b % 100 != 11 ? c[0] : b % 10 >= 2 && 4 >= b % 10 && (10 > b % 100 || b % 100 >= 20) ? c[1] : c[2]
    }

    function Hc(a, b, c) {
        var d = {
            mm: b ? "хвіліна_хвіліны_хвілін" : "хвіліну_хвіліны_хвілін",
            hh: b ? "гадзіна_гадзіны_гадзін" : "гадзіну_гадзіны_гадзін",
            dd: "дзень_дні_дзён",
            MM: "месяц_месяцы_месяцаў",
            yy: "год_гады_гадоў"
        };
        return "m" === c ? b ? "хвіліна" : "хвіліну" : "h" === c ? b ? "гадзіна" : "гадзіну" : a + " " + Gc(d[c], +a)
    }

    function Ic(a, b) {
        return {
            nominative: "студзень_люты_сакавік_красавік_травень_чэрвень_ліпень_жнівень_верасень_кастрычнік_лістапад_снежань".split("_"),
            accusative: "студзеня_лютага_сакавіка_красавіка_траўня_чэрвеня_ліпеня_жніўня_верасня_кастрычніка_лістапада_снежня".split("_")
        }[/D[oD]?(\[[^\[\]]*\]|\s+)+MMMM?/.test(b) ? "accusative" : "nominative"][a.month()]
    }

    function Jc(a, b) {
        return {
            nominative: "нядзеля_панядзелак_аўторак_серада_чацвер_пятніца_субота".split("_"),
            accusative: "нядзелю_панядзелак_аўторак_сераду_чацвер_пятніцу_суботу".split("_")
        }[/\[ ?[Вв] ?(?:мінулую|наступную)? ?\] ?dddd/.test(b) ? "accusative" : "nominative"][a.day()]
    }

    function Kc(a, b, c) {
        return a + " " + Nc({
            mm: "munutenn",
            MM: "miz",
            dd: "devezh"
        }[c], a)
    }

    function Lc(a) {
        switch (Mc(a)) {
            case 1:
            case 3:
            case 4:
            case 5:
            case 9:
                return a + " bloaz";
            default:
                return a + " vloaz"
        }
    }

    function Mc(a) {
        return a > 9 ? Mc(a % 10) : a
    }

    function Nc(a, b) {
        return 2 === b ? Oc(a) : a
    }

    function Oc(a) {
        var b = {
            m: "v",
            b: "v",
            d: "z"
        };
        return void 0 === b[a.charAt(0)] ? a : b[a.charAt(0)] + a.substring(1)
    }

    function Pc(a, b, c) {
        var d = a + " ";
        switch (c) {
            case "m":
                return b ? "jedna minuta" : "jedne minute";
            case "mm":
                return d += 1 === a ? "minuta" : 2 === a || 3 === a || 4 === a ? "minute" : "minuta";
            case "h":
                return b ? "jedan sat" : "jednog sata";
            case "hh":
                return d += 1 === a ? "sat" : 2 === a || 3 === a || 4 === a ? "sata" : "sati";
            case "dd":
                return d += 1 === a ? "dan" : "dana";
            case "MM":
                return d += 1 === a ? "mjesec" : 2 === a || 3 === a || 4 === a ? "mjeseca" : "mjeseci";
            case "yy":
                return d += 1 === a ? "godina" : 2 === a || 3 === a || 4 === a ? "godine" : "godina"
        }
    }

    function Qc(a) {
        return a > 1 && 5 > a && 1 != ~~(a / 10)
    }

    function Rc(a, b, c, d) {
        var e = a + " ";
        switch (c) {
            case "s":
                return b || d ? "pár sekund" : "pár sekundami";
            case "m":
                return b ? "minuta" : d ? "minutu" : "minutou";
            case "mm":
                return b || d ? e + (Qc(a) ? "minuty" : "minut") : e + "minutami";
            case "h":
                return b ? "hodina" : d ? "hodinu" : "hodinou";
            case "hh":
                return b || d ? e + (Qc(a) ? "hodiny" : "hodin") : e + "hodinami";
            case "d":
                return b || d ? "den" : "dnem";
            case "dd":
                return b || d ? e + (Qc(a) ? "dny" : "dní") : e + "dny";
            case "M":
                return b || d ? "měsíc" : "měsícem";
            case "MM":
                return b || d ? e + (Qc(a) ? "měsíce" : "měsíců") : e + "měsíci";
            case "y":
                return b || d ? "rok" : "rokem";
            case "yy":
                return b || d ? e + (Qc(a) ? "roky" : "let") : e + "lety"
        }
    }

    function Sc(a, b, c, d) {
        var e = {
            m: ["eine Minute", "einer Minute"],
            h: ["eine Stunde", "einer Stunde"],
            d: ["ein Tag", "einem Tag"],
            dd: [a + " Tage", a + " Tagen"],
            M: ["ein Monat", "einem Monat"],
            MM: [a + " Monate", a + " Monaten"],
            y: ["ein Jahr", "einem Jahr"],
            yy: [a + " Jahre", a + " Jahren"]
        };
        return b ? e[c][0] : e[c][1]
    }

    function Tc(a, b, c, d) {
        var e = {
            m: ["eine Minute", "einer Minute"],
            h: ["eine Stunde", "einer Stunde"],
            d: ["ein Tag", "einem Tag"],
            dd: [a + " Tage", a + " Tagen"],
            M: ["ein Monat", "einem Monat"],
            MM: [a + " Monate", a + " Monaten"],
            y: ["ein Jahr", "einem Jahr"],
            yy: [a + " Jahre", a + " Jahren"]
        };
        return b ? e[c][0] : e[c][1]
    }

    function Uc(a, b, c, d) {
        var e = {
            s: ["mõne sekundi", "mõni sekund", "paar sekundit"],
            m: ["ühe minuti", "üks minut"],
            mm: [a + " minuti", a + " minutit"],
            h: ["ühe tunni", "tund aega", "üks tund"],
            hh: [a + " tunni", a + " tundi"],
            d: ["ühe päeva", "üks päev"],
            M: ["kuu aja", "kuu aega", "üks kuu"],
            MM: [a + " kuu", a + " kuud"],
            y: ["ühe aasta", "aasta", "üks aasta"],
            yy: [a + " aasta", a + " aastat"]
        };
        return b ? e[c][2] ? e[c][2] : e[c][1] : d ? e[c][0] : e[c][1]
    }

    function Vc(a, b, c, d) {
        var e = "";
        switch (c) {
            case "s":
                return d ? "muutaman sekunnin" : "muutama sekunti";
            case "m":
                return d ? "minuutin" : "minuutti";
            case "mm":
                e = d ? "minuutin" : "minuuttia";
                break;
            case "h":
                return d ? "tunnin" : "tunti";
            case "hh":
                e = d ? "tunnin" : "tuntia";
                break;
            case "d":
                return d ? "päivän" : "päivä";
            case "dd":
                e = d ? "päivän" : "päivää";
                break;
            case "M":
                return d ? "kuukauden" : "kuukausi";
            case "MM":
                e = d ? "kuukauden" : "kuukautta";
                break;
            case "y":
                return d ? "vuoden" : "vuosi";
            case "yy":
                e = d ? "vuoden" : "vuotta"
        }
        return e = Wc(a, d) + " " + e
    }

    function Wc(a, b) {
        return 10 > a ? b ? Mf[a] : Lf[a] : a
    }

    function Xc(a, b, c) {
        var d = a + " ";
        switch (c) {
            case "m":
                return b ? "jedna minuta" : "jedne minute";
            case "mm":
                return d += 1 === a ? "minuta" : 2 === a || 3 === a || 4 === a ? "minute" : "minuta";
            case "h":
                return b ? "jedan sat" : "jednog sata";
            case "hh":
                return d += 1 === a ? "sat" : 2 === a || 3 === a || 4 === a ? "sata" : "sati";
            case "dd":
                return d += 1 === a ? "dan" : "dana";
            case "MM":
                return d += 1 === a ? "mjesec" : 2 === a || 3 === a || 4 === a ? "mjeseca" : "mjeseci";
            case "yy":
                return d += 1 === a ? "godina" : 2 === a || 3 === a || 4 === a ? "godine" : "godina"
        }
    }

    function Yc(a, b, c, d) {
        var e = a;
        switch (c) {
            case "s":
                return d || b ? "néhány másodperc" : "néhány másodperce";
            case "m":
                return "egy" + (d || b ? " perc" : " perce");
            case "mm":
                return e + (d || b ? " perc" : " perce");
            case "h":
                return "egy" + (d || b ? " óra" : " órája");
            case "hh":
                return e + (d || b ? " óra" : " órája");
            case "d":
                return "egy" + (d || b ? " nap" : " napja");
            case "dd":
                return e + (d || b ? " nap" : " napja");
            case "M":
                return "egy" + (d || b ? " hónap" : " hónapja");
            case "MM":
                return e + (d || b ? " hónap" : " hónapja");
            case "y":
                return "egy" + (d || b ? " év" : " éve");
            case "yy":
                return e + (d || b ? " év" : " éve")
        }
        return ""
    }

    function Zc(a) {
        return (a ? "" : "[múlt] ") + "[" + Rf[this.day()] + "] LT[-kor]"
    }

    function $c(a, b) {
        return {
            nominative: "հունվար_փետրվար_մարտ_ապրիլ_մայիս_հունիս_հուլիս_օգոստոս_սեպտեմբեր_հոկտեմբեր_նոյեմբեր_դեկտեմբեր".split("_"),
            accusative: "հունվարի_փետրվարի_մարտի_ապրիլի_մայիսի_հունիսի_հուլիսի_օգոստոսի_սեպտեմբերի_հոկտեմբերի_նոյեմբերի_դեկտեմբերի".split("_")
        }[/D[oD]?(\[[^\[\]]*\]|\s+)+MMMM?/.test(b) ? "accusative" : "nominative"][a.month()]
    }

    function _c(a, b) {
        return "հնվ_փտր_մրտ_ապր_մյս_հնս_հլս_օգս_սպտ_հկտ_նմբ_դկտ".split("_")[a.month()]
    }

    function ad(a, b) {
        return "կիրակի_երկուշաբթի_երեքշաբթի_չորեքշաբթի_հինգշաբթի_ուրբաթ_շաբաթ".split("_")[a.day()]
    }

    function bd(a) {
        return a % 100 == 11 || a % 10 != 1
    }

    function cd(a, b, c, d) {
        var e = a + " ";
        switch (c) {
            case "s":
                return b || d ? "nokkrar sekúndur" : "nokkrum sekúndum";
            case "m":
                return b ? "mínúta" : "mínútu";
            case "mm":
                return bd(a) ? e + (b || d ? "mínútur" : "mínútum") : b ? e + "mínúta" : e + "mínútu";
            case "hh":
                return bd(a) ? e + (b || d ? "klukkustundir" : "klukkustundum") : e + "klukkustund";
            case "d":
                return b ? "dagur" : d ? "dag" : "degi";
            case "dd":
                return bd(a) ? b ? e + "dagar" : e + (d ? "daga" : "dögum") : b ? e + "dagur" : e + (d ? "dag" : "degi");
            case "M":
                return b ? "mánuður" : d ? "mánuð" : "mánuði";
            case "MM":
                return bd(a) ? b ? e + "mánuðir" : e + (d ? "mánuði" : "mánuðum") : b ? e + "mánuður" : e + (d ? "mánuð" : "mánuði");
            case "y":
                return b || d ? "ár" : "ári";
            case "yy":
                return bd(a) ? e + (b || d ? "ár" : "árum") : e + (b || d ? "ár" : "ári")
        }
    }

    function dd(a, b) {
        return {
            nominative: "იანვარი_თებერვალი_მარტი_აპრილი_მაისი_ივნისი_ივლისი_აგვისტო_სექტემბერი_ოქტომბერი_ნოემბერი_დეკემბერი".split("_"),
            accusative: "იანვარს_თებერვალს_მარტს_აპრილის_მაისს_ივნისს_ივლისს_აგვისტს_სექტემბერს_ოქტომბერს_ნოემბერს_დეკემბერს".split("_")
        }[/D[oD] *MMMM?/.test(b) ? "accusative" : "nominative"][a.month()]
    }

    function ed(a, b) {
        return {
            nominative: "კვირა_ორშაბათი_სამშაბათი_ოთხშაბათი_ხუთშაბათი_პარასკევი_შაბათი".split("_"),
            accusative: "კვირას_ორშაბათს_სამშაბათს_ოთხშაბათს_ხუთშაბათს_პარასკევს_შაბათს".split("_")
        }[/(წინა|შემდეგ)/.test(b) ? "accusative" : "nominative"][a.day()]
    }

    function fd(a, b, c, d) {
        var e = {
            m: ["eng Minutt", "enger Minutt"],
            h: ["eng Stonn", "enger Stonn"],
            d: ["een Dag", "engem Dag"],
            M: ["ee Mount", "engem Mount"],
            y: ["ee Joer", "engem Joer"]
        };
        return b ? e[c][0] : e[c][1]
    }

    function gd(a) {
        return id(a.substr(0, a.indexOf(" "))) ? "a " + a : "an " + a
    }

    function hd(a) {
        return id(a.substr(0, a.indexOf(" "))) ? "viru " + a : "virun " + a
    }

    function id(a) {
        if (a = parseInt(a, 10), isNaN(a)) return !1;
        if (0 > a) return !0;
        if (10 > a) return a >= 4 && 7 >= a;
        if (100 > a) {
            var b = a % 10,
                c = a / 10;
            return id(0 === b ? c : b)
        }
        if (1e4 > a) {
            for (; a >= 10;) a /= 10;
            return id(a)
        }
        return a /= 1e3, id(a)
    }

    function jd(a, b, c, d) {
        return b ? "kelios sekundės" : d ? "kelių sekundžių" : "kelias sekundes"
    }

    function kd(a, b) {
        return {
            nominative: "sausis_vasaris_kovas_balandis_gegužė_birželis_liepa_rugpjūtis_rugsėjis_spalis_lapkritis_gruodis".split("_"),
            accusative: "sausio_vasario_kovo_balandžio_gegužės_birželio_liepos_rugpjūčio_rugsėjo_spalio_lapkričio_gruodžio".split("_")
        }[/D[oD]?(\[[^\[\]]*\]|\s+)+MMMM?/.test(b) ? "accusative" : "nominative"][a.month()]
    }

    function ld(a, b, c, d) {
        return b ? nd(c)[0] : d ? nd(c)[1] : nd(c)[2]
    }

    function md(a) {
        return a % 10 == 0 || a > 10 && 20 > a
    }

    function nd(a) {
        return Sf[a].split("_")
    }

    function od(a, b, c, d) {
        var e = a + " ";
        return 1 === a ? e + ld(a, b, c[0], d) : b ? e + (md(a) ? nd(c)[1] : nd(c)[0]) : d ? e + nd(c)[1] : e + (md(a) ? nd(c)[1] : nd(c)[2])
    }

    function pd(a, b) {
        var c = -1 === b.indexOf("dddd HH:mm"),
            d = Tf[a.day()];
        return c ? d : d.substring(0, d.length - 2) + "į"
    }

    function qd(a, b, c) {
        return c ? b % 10 == 1 && 11 !== b ? a[2] : a[3] : b % 10 == 1 && 11 !== b ? a[0] : a[1]
    }

    function rd(a, b, c) {
        return a + " " + qd(Uf[c], a, b)
    }

    function sd(a, b, c) {
        return qd(Uf[c], a, b)
    }

    function td(a, b) {
        return b ? "dažas sekundes" : "dažām sekundēm"
    }

    function ud(a) {
        return 5 > a % 10 && a % 10 > 1 && ~~(a / 10) % 10 != 1
    }

    function vd(a, b, c) {
        var d = a + " ";
        switch (c) {
            case "m":
                return b ? "minuta" : "minutę";
            case "mm":
                return d + (ud(a) ? "minuty" : "minut");
            case "h":
                return b ? "godzina" : "godzinę";
            case "hh":
                return d + (ud(a) ? "godziny" : "godzin");
            case "MM":
                return d + (ud(a) ? "miesiące" : "miesięcy");
            case "yy":
                return d + (ud(a) ? "lata" : "lat")
        }
    }

    function wd(a, b, c) {
        var d = {
                mm: "minute",
                hh: "ore",
                dd: "zile",
                MM: "luni",
                yy: "ani"
            },
            e = " ";
        return (a % 100 >= 20 || a >= 100 && a % 100 == 0) && (e = " de "), a + e + d[c]
    }

    function xd(a, b) {
        var c = a.split("_");
        return b % 10 == 1 && b % 100 != 11 ? c[0] : b % 10 >= 2 && 4 >= b % 10 && (10 > b % 100 || b % 100 >= 20) ? c[1] : c[2]
    }

    function yd(a, b, c) {
        var d = {
            mm: b ? "минута_минуты_минут" : "минуту_минуты_минут",
            hh: "час_часа_часов",
            dd: "день_дня_дней",
            MM: "месяц_месяца_месяцев",
            yy: "год_года_лет"
        };
        return "m" === c ? b ? "минута" : "минуту" : a + " " + xd(d[c], +a)
    }

    function zd(a, b) {
        return {
            nominative: "январь_февраль_март_апрель_май_июнь_июль_август_сентябрь_октябрь_ноябрь_декабрь".split("_"),
            accusative: "января_февраля_марта_апреля_мая_июня_июля_августа_сентября_октября_ноября_декабря".split("_")
        }[/D[oD]?(\[[^\[\]]*\]|\s+)+MMMM?/.test(b) ? "accusative" : "nominative"][a.month()]
    }

    function Ad(a, b) {
        return {
            nominative: "янв_фев_март_апр_май_июнь_июль_авг_сен_окт_ноя_дек".split("_"),
            accusative: "янв_фев_мар_апр_мая_июня_июля_авг_сен_окт_ноя_дек".split("_")
        }[/D[oD]?(\[[^\[\]]*\]|\s+)+MMMM?/.test(b) ? "accusative" : "nominative"][a.month()]
    }

    function Bd(a, b) {
        return {
            nominative: "воскресенье_понедельник_вторник_среда_четверг_пятница_суббота".split("_"),
            accusative: "воскресенье_понедельник_вторник_среду_четверг_пятницу_субботу".split("_")
        }[/\[ ?[Вв] ?(?:прошлую|следующую|эту)? ?\] ?dddd/.test(b) ? "accusative" : "nominative"][a.day()]
    }

    function Cd(a) {
        return a > 1 && 5 > a
    }

    function Dd(a, b, c, d) {
        var e = a + " ";
        switch (c) {
            case "s":
                return b || d ? "pár sekúnd" : "pár sekundami";
            case "m":
                return b ? "minúta" : d ? "minútu" : "minútou";
            case "mm":
                return b || d ? e + (Cd(a) ? "minúty" : "minút") : e + "minútami";
            case "h":
                return b ? "hodina" : d ? "hodinu" : "hodinou";
            case "hh":
                return b || d ? e + (Cd(a) ? "hodiny" : "hodín") : e + "hodinami";
            case "d":
                return b || d ? "deň" : "dňom";
            case "dd":
                return b || d ? e + (Cd(a) ? "dni" : "dní") : e + "dňami";
            case "M":
                return b || d ? "mesiac" : "mesiacom";
            case "MM":
                return b || d ? e + (Cd(a) ? "mesiace" : "mesiacov") : e + "mesiacmi";
            case "y":
                return b || d ? "rok" : "rokom";
            case "yy":
                return b || d ? e + (Cd(a) ? "roky" : "rokov") : e + "rokmi"
        }
    }

    function Ed(a, b, c, d) {
        var e = a + " ";
        switch (c) {
            case "s":
                return b || d ? "nekaj sekund" : "nekaj sekundami";
            case "m":
                return b ? "ena minuta" : "eno minuto";
            case "mm":
                return e += 1 === a ? b ? "minuta" : "minuto" : 2 === a ? b || d ? "minuti" : "minutama" : 5 > a ? b || d ? "minute" : "minutami" : b || d ? "minut" : "minutami";
            case "h":
                return b ? "ena ura" : "eno uro";
            case "hh":
                return e += 1 === a ? b ? "ura" : "uro" : 2 === a ? b || d ? "uri" : "urama" : 5 > a ? b || d ? "ure" : "urami" : b || d ? "ur" : "urami";
            case "d":
                return b || d ? "en dan" : "enim dnem";
            case "dd":
                return e += 1 === a ? b || d ? "dan" : "dnem" : 2 === a ? b || d ? "dni" : "dnevoma" : b || d ? "dni" : "dnevi";
            case "M":
                return b || d ? "en mesec" : "enim mesecem";
            case "MM":
                return e += 1 === a ? b || d ? "mesec" : "mesecem" : 2 === a ? b || d ? "meseca" : "mesecema" : 5 > a ? b || d ? "mesece" : "meseci" : b || d ? "mesecev" : "meseci";
            case "y":
                return b || d ? "eno leto" : "enim letom";
            case "yy":
                return e += 1 === a ? b || d ? "leto" : "letom" : 2 === a ? b || d ? "leti" : "letoma" : 5 > a ? b || d ? "leta" : "leti" : b || d ? "let" : "leti"
        }
    }

    function Fd(a, b, c, d) {
        var e = {
            s: ["viensas secunds", "'iensas secunds"],
            m: ["'n míut", "'iens míut"],
            mm: [a + " míuts", " " + a + " míuts"],
            h: ["'n þora", "'iensa þora"],
            hh: [a + " þoras", " " + a + " þoras"],
            d: ["'n ziua", "'iensa ziua"],
            dd: [a + " ziuas", " " + a + " ziuas"],
            M: ["'n mes", "'iens mes"],
            MM: [a + " mesen", " " + a + " mesen"],
            y: ["'n ar", "'iens ar"],
            yy: [a + " ars", " " + a + " ars"]
        };
        return d ? e[c][0] : b ? e[c][0] : e[c][1].trim()
    }

    function Gd(a, b) {
        var c = a.split("_");
        return b % 10 == 1 && b % 100 != 11 ? c[0] : b % 10 >= 2 && 4 >= b % 10 && (10 > b % 100 || b % 100 >= 20) ? c[1] : c[2]
    }

    function Hd(a, b, c) {
        var d = {
            mm: "хвилина_хвилини_хвилин",
            hh: "година_години_годин",
            dd: "день_дні_днів",
            MM: "місяць_місяці_місяців",
            yy: "рік_роки_років"
        };
        return "m" === c ? b ? "хвилина" : "хвилину" : "h" === c ? b ? "година" : "годину" : a + " " + Gd(d[c], +a)
    }

    function Id(a, b) {
        return {
            nominative: "січень_лютий_березень_квітень_травень_червень_липень_серпень_вересень_жовтень_листопад_грудень".split("_"),
            accusative: "січня_лютого_березня_квітня_травня_червня_липня_серпня_вересня_жовтня_листопада_грудня".split("_")
        }[/D[oD]? *MMMM?/.test(b) ? "accusative" : "nominative"][a.month()]
    }

    function Jd(a, b) {
        return {
            nominative: "неділя_понеділок_вівторок_середа_четвер_п’ятниця_субота".split("_"),
            accusative: "неділю_понеділок_вівторок_середу_четвер_п’ятницю_суботу".split("_"),
            genitive: "неділі_понеділка_вівторка_середи_четверга_п’ятниці_суботи".split("_")
        }[/(\[[ВвУу]\]) ?dddd/.test(b) ? "accusative" : /\[?(?:минулої|наступної)? ?\] ?dddd/.test(b) ? "genitive" : "nominative"][a.day()]
    }

    function Kd(a) {
        return function() {
            return a + "о" + (11 === this.hours() ? "б" : "") + "] LT"
        }
    }
    var Ld, Md, Nd = a.momentProperties = [],
        Od = !1,
        Pd = {},
        Qd = {},
        Rd = /(\[[^\[]*\])|(\\)?(Mo|MM?M?M?|Do|DDDo|DD?D?D?|ddd?d?|do?|w[o|w]?|W[o|W]?|Q|YYYYYY|YYYYY|YYYY|YY|gg(ggg?)?|GG(GGG?)?|e|E|a|A|hh?|HH?|mm?|ss?|S{1,9}|x|X|zz?|ZZ?|.)/g,
        Sd = /(\[[^\[]*\])|(\\)?(LTS|LT|LL?L?L?|l{1,4})/g,
        Td = {},
        Ud = {},
        Vd = /\d/,
        Wd = /\d\d/,
        Xd = /\d{3}/,
        Yd = /\d{4}/,
        Zd = /[+-]?\d{6}/,
        $d = /\d\d?/,
        _d = /\d{1,3}/,
        ae = /\d{1,4}/,
        be = /[+-]?\d{1,6}/,
        ce = /\d+/,
        de = /[+-]?\d+/,
        ee = /Z|[+-]\d\d:?\d\d/gi,
        fe = /[+-]?\d+(\.\d{1,3})?/,
        ge = /[0-9]*['a-z\u00A0-\u05FF\u0700-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+|[\u0600-\u06FF\/]+(\s*?[\u0600-\u06FF]+){1,2}/i,
        he = {},
        ie = {},
        je = 0,
        ke = 1,
        le = 2,
        me = 3,
        ne = 4,
        oe = 5,
        pe = 6;
    G("M", ["MM", 2], "Mo", function() {
        return this.month() + 1
    }), G("MMM", 0, 0, function(a) {
        return this.localeData().monthsShort(this, a)
    }), G("MMMM", 0, 0, function(a) {
        return this.localeData().months(this, a)
    }), y("month", "M"), M("M", $d), M("MM", $d, Wd), M("MMM", ge), M("MMMM", ge), P(["M", "MM"], function(a, b) {
        b[ke] = p(a) - 1
    }), P(["MMM", "MMMM"], function(a, b, c, d) {
        var e = c._locale.monthsParse(a, d, c._strict);
        null != e ? b[ke] = e : i(c).invalidMonth = a
    });
    var qe = "January_February_March_April_May_June_July_August_September_October_November_December".split("_"),
        re = "Jan_Feb_Mar_Apr_May_Jun_Jul_Aug_Sep_Oct_Nov_Dec".split("_"),
        se = {};
    a.suppressDeprecationWarnings = !1;
    var te = /^\s*(?:[+-]\d{6}|\d{4})-(?:(\d\d-\d\d)|(W\d\d$)|(W\d\d-\d)|(\d\d\d))((T| )(\d\d(:\d\d(:\d\d(\.\d+)?)?)?)?([\+\-]\d\d(?::?\d\d)?|\s*Z)?)?$/,
        ue = [
            ["YYYYYY-MM-DD", /[+-]\d{6}-\d{2}-\d{2}/],
            ["YYYY-MM-DD", /\d{4}-\d{2}-\d{2}/],
            ["GGGG-[W]WW-E", /\d{4}-W\d{2}-\d/],
            ["GGGG-[W]WW", /\d{4}-W\d{2}/],
            ["YYYY-DDD", /\d{4}-\d{3}/]
        ],
        ve = [
            ["HH:mm:ss.SSSS", /(T| )\d\d:\d\d:\d\d\.\d+/],
            ["HH:mm:ss", /(T| )\d\d:\d\d:\d\d/],
            ["HH:mm", /(T| )\d\d:\d\d/],
            ["HH", /(T| )\d\d/]
        ],
        we = /^\/?Date\((\-?\d+)/i;
    a.createFromInputFallback = _("moment construction falls back to js Date. This is discouraged and will be removed in upcoming major release. Please refer to https://github.com/moment/moment/issues/1407 for more info.", function(a) {
        a._d = new Date(a._i + (a._useUTC ? " UTC" : ""))
    }), G(0, ["YY", 2], 0, function() {
        return this.year() % 100
    }), G(0, ["YYYY", 4], 0, "year"), G(0, ["YYYYY", 5], 0, "year"), G(0, ["YYYYYY", 6, !0], 0, "year"), y("year", "y"), M("Y", de), M("YY", $d, Wd), M("YYYY", ae, Yd), M("YYYYY", be, Zd), M("YYYYYY", be, Zd), P(["YYYYY", "YYYYYY"], je), P("YYYY", function(b, c) {
        c[je] = 2 === b.length ? a.parseTwoDigitYear(b) : p(b)
    }), P("YY", function(b, c) {
        c[je] = a.parseTwoDigitYear(b)
    }), a.parseTwoDigitYear = function(a) {
        return p(a) + (p(a) > 68 ? 1900 : 2e3)
    };
    var xe = B("FullYear", !1);
    G("w", ["ww", 2], "wo", "week"), G("W", ["WW", 2], "Wo", "isoWeek"), y("week", "w"), y("isoWeek", "W"), M("w", $d), M("ww", $d, Wd), M("W", $d), M("WW", $d, Wd), Q(["w", "ww", "W", "WW"], function(a, b, c, d) {
        b[d.substr(0, 1)] = p(a)
    });
    var ye = {
        dow: 0,
        doy: 6
    };
    G("DDD", ["DDDD", 3], "DDDo", "dayOfYear"), y("dayOfYear", "DDD"), M("DDD", _d), M("DDDD", Xd), P(["DDD", "DDDD"], function(a, b, c) {
        c._dayOfYear = p(a)
    }), a.ISO_8601 = function() {};
    var ze = _("moment().min is deprecated, use moment.min instead. https://github.com/moment/moment/issues/1548", function() {
            var a = Ca.apply(null, arguments);
            return this > a ? this : a
        }),
        Ae = _("moment().max is deprecated, use moment.max instead. https://github.com/moment/moment/issues/1548", function() {
            var a = Ca.apply(null, arguments);
            return a > this ? this : a
        });
    Ia("Z", ":"), Ia("ZZ", ""), M("Z", ee), M("ZZ", ee), P(["Z", "ZZ"], function(a, b, c) {
        c._useUTC = !0, c._tzm = Ja(a)
    });
    var Be = /([\+\-]|\d\d)/gi;
    a.updateOffset = function() {};
    var Ce = /(\-)?(?:(\d*)\.)?(\d+)\:(\d+)(?:\:(\d+)\.?(\d{3})?)?/,
        De = /^(-)?P(?:(?:([0-9,.]*)Y)?(?:([0-9,.]*)M)?(?:([0-9,.]*)D)?(?:T(?:([0-9,.]*)H)?(?:([0-9,.]*)M)?(?:([0-9,.]*)S)?)?|([0-9,.]*)W)$/;
    Xa.fn = Ga.prototype;
    var Ee = _a(1, "add"),
        Fe = _a(-1, "subtract");
    a.defaultFormat = "YYYY-MM-DDTHH:mm:ssZ";
    var Ge = _("moment().lang() is deprecated. Instead, use moment().localeData() to get the language configuration. Use moment().locale() to change languages.", function(a) {
        return void 0 === a ? this.localeData() : this.locale(a)
    });
    G(0, ["gg", 2], 0, function() {
        return this.weekYear() % 100
    }), G(0, ["GG", 2], 0, function() {
        return this.isoWeekYear() % 100
    }), Cb("gggg", "weekYear"), Cb("ggggg", "weekYear"), Cb("GGGG", "isoWeekYear"), Cb("GGGGG", "isoWeekYear"), y("weekYear", "gg"), y("isoWeekYear", "GG"), M("G", de), M("g", de), M("GG", $d, Wd), M("gg", $d, Wd), M("GGGG", ae, Yd), M("gggg", ae, Yd), M("GGGGG", be, Zd), M("ggggg", be, Zd), Q(["gggg", "ggggg", "GGGG", "GGGGG"], function(a, b, c, d) {
        b[d.substr(0, 2)] = p(a)
    }), Q(["gg", "GG"], function(b, c, d, e) {
        c[e] = a.parseTwoDigitYear(b)
    }), G("Q", 0, 0, "quarter"), y("quarter", "Q"), M("Q", Vd), P("Q", function(a, b) {
        b[ke] = 3 * (p(a) - 1)
    }), G("D", ["DD", 2], "Do", "date"), y("date", "D"), M("D", $d), M("DD", $d, Wd), M("Do", function(a, b) {
        return a ? b._ordinalParse : b._ordinalParseLenient
    }), P(["D", "DD"], le), P("Do", function(a, b) {
        b[le] = p(a.match($d)[0], 10)
    });
    var He = B("Date", !0);
    G("d", 0, "do", "day"), G("dd", 0, 0, function(a) {
        return this.localeData().weekdaysMin(this, a)
    }), G("ddd", 0, 0, function(a) {
        return this.localeData().weekdaysShort(this, a)
    }), G("dddd", 0, 0, function(a) {
        return this.localeData().weekdays(this, a)
    }), G("e", 0, 0, "weekday"), G("E", 0, 0, "isoWeekday"), y("day", "d"), y("weekday", "e"), y("isoWeekday", "E"), M("d", $d), M("e", $d), M("E", $d), M("dd", ge), M("ddd", ge), M("dddd", ge), Q(["dd", "ddd", "dddd"], function(a, b, c) {
        var d = c._locale.weekdaysParse(a);
        null != d ? b.d = d : i(c).invalidWeekday = a
    }), Q(["d", "e", "E"], function(a, b, c, d) {
        b[d] = p(a)
    });
    var Ie = "Sunday_Monday_Tuesday_Wednesday_Thursday_Friday_Saturday".split("_"),
        Je = "Sun_Mon_Tue_Wed_Thu_Fri_Sat".split("_"),
        Ke = "Su_Mo_Tu_We_Th_Fr_Sa".split("_");
    G("H", ["HH", 2], 0, "hour"), G("h", ["hh", 2], 0, function() {
        return this.hours() % 12 || 12
    }), Rb("a", !0), Rb("A", !1), y("hour", "h"), M("a", Sb), M("A", Sb), M("H", $d), M("h", $d), M("HH", $d, Wd), M("hh", $d, Wd), P(["H", "HH"], me), P(["a", "A"], function(a, b, c) {
        c._isPm = c._locale.isPM(a), c._meridiem = a
    }), P(["h", "hh"], function(a, b, c) {
        b[me] = p(a), i(c).bigHour = !0
    });
    var Le = /[ap]\.?m?\.?/i,
        Me = B("Hours", !0);
    G("m", ["mm", 2], 0, "minute"), y("minute", "m"), M("m", $d), M("mm", $d, Wd), P(["m", "mm"], ne);
    var Ne = B("Minutes", !1);
    G("s", ["ss", 2], 0, "second"), y("second", "s"), M("s", $d), M("ss", $d, Wd), P(["s", "ss"], oe);
    var Oe = B("Seconds", !1);
    G("S", 0, 0, function() {
        return ~~(this.millisecond() / 100)
    }), G(0, ["SS", 2], 0, function() {
        return ~~(this.millisecond() / 10)
    }), G(0, ["SSS", 3], 0, "millisecond"), G(0, ["SSSS", 4], 0, function() {
        return 10 * this.millisecond()
    }), G(0, ["SSSSS", 5], 0, function() {
        return 100 * this.millisecond()
    }), G(0, ["SSSSSS", 6], 0, function() {
        return 1e3 * this.millisecond()
    }), G(0, ["SSSSSSS", 7], 0, function() {
        return 1e4 * this.millisecond()
    }), G(0, ["SSSSSSSS", 8], 0, function() {
        return 1e5 * this.millisecond()
    }), G(0, ["SSSSSSSSS", 9], 0, function() {
        return 1e6 * this.millisecond()
    }), y("millisecond", "ms"), M("S", _d, Vd), M("SS", _d, Wd), M("SSS", _d, Xd);
    var Pe;
    for (Pe = "SSSS"; Pe.length <= 9; Pe += "S") M(Pe, ce);
    for (Pe = "S"; Pe.length <= 9; Pe += "S") P(Pe, Vb);
    var Qe = B("Milliseconds", !1);
    G("z", 0, 0, "zoneAbbr"), G("zz", 0, 0, "zoneName");
    var Re = m.prototype;
    Re.add = Ee, Re.calendar = bb, Re.clone = cb, Re.diff = hb, Re.endOf = tb, Re.format = lb, Re.from = mb, Re.fromNow = nb, Re.to = ob, Re.toNow = pb, Re.get = E, Re.invalidAt = Bb, Re.isAfter = db, Re.isBefore = eb, Re.isBetween = fb, Re.isSame = gb, Re.isValid = zb, Re.lang = Ge, Re.locale = qb, Re.localeData = rb, Re.max = Ae, Re.min = ze, Re.parsingFlags = Ab, Re.set = E, Re.startOf = sb, Re.subtract = Fe, Re.toArray = xb, Re.toObject = yb, Re.toDate = wb, Re.toISOString = kb, Re.toJSON = kb, Re.toString = jb, Re.unix = vb, Re.valueOf = ub, Re.year = xe, Re.isLeapYear = ha, Re.weekYear = Eb, Re.isoWeekYear = Fb, Re.quarter = Re.quarters = Ib, Re.month = X, Re.daysInMonth = Y, Re.week = Re.weeks = ma, Re.isoWeek = Re.isoWeeks = na, Re.weeksInYear = Hb, Re.isoWeeksInYear = Gb, Re.date = He, Re.day = Re.days = Ob, Re.weekday = Pb, Re.isoWeekday = Qb, Re.dayOfYear = pa, Re.hour = Re.hours = Me, Re.minute = Re.minutes = Ne, Re.second = Re.seconds = Oe, Re.millisecond = Re.milliseconds = Qe, Re.utcOffset = Ma, Re.utc = Oa, Re.local = Pa, Re.parseZone = Qa, Re.hasAlignedHourOffset = Ra, Re.isDST = Sa, Re.isDSTShifted = Ta, Re.isLocal = Ua, Re.isUtcOffset = Va, Re.isUtc = Wa, Re.isUTC = Wa, Re.zoneAbbr = Wb, Re.zoneName = Xb, Re.dates = _("dates accessor is deprecated. Use date instead.", He), Re.months = _("months accessor is deprecated. Use month instead", X), Re.years = _("years accessor is deprecated. Use year instead", xe), Re.zone = _("moment().zone is deprecated, use moment().utcOffset instead. https://github.com/moment/moment/issues/1779", Na);
    var Se = Re,
        Te = {
            sameDay: "[Today at] LT",
            nextDay: "[Tomorrow at] LT",
            nextWeek: "dddd [at] LT",
            lastDay: "[Yesterday at] LT",
            lastWeek: "[Last] dddd [at] LT",
            sameElse: "L"
        },
        Ue = {
            LTS: "h:mm:ss A",
            LT: "h:mm A",
            L: "MM/DD/YYYY",
            LL: "MMMM D, YYYY",
            LLL: "MMMM D, YYYY h:mm A",
            LLLL: "dddd, MMMM D, YYYY h:mm A"
        },
        Ve = /\d{1,2}/,
        We = {
            future: "in %s",
            past: "%s ago",
            s: "a few seconds",
            m: "a minute",
            mm: "%d minutes",
            h: "an hour",
            hh: "%d hours",
            d: "a day",
            dd: "%d days",
            M: "a month",
            MM: "%d months",
            y: "a year",
            yy: "%d years"
        },
        Xe = r.prototype;
    Xe._calendar = Te, Xe.calendar = $b, Xe._longDateFormat = Ue, Xe.longDateFormat = _b, Xe._invalidDate = "Invalid date", Xe.invalidDate = ac, Xe._ordinal = "%d", Xe.ordinal = bc, Xe._ordinalParse = Ve, Xe.preparse = cc, Xe.postformat = cc, Xe._relativeTime = We, Xe.relativeTime = dc, Xe.pastFuture = ec, Xe.set = fc, Xe.months = T, Xe._months = qe, Xe.monthsShort = U, Xe._monthsShort = re, Xe.monthsParse = V, Xe.week = ja, Xe._week = ye, Xe.firstDayOfYear = la, Xe.firstDayOfWeek = ka, Xe.weekdays = Kb, Xe._weekdays = Ie, Xe.weekdaysMin = Mb, Xe._weekdaysMin = Ke, Xe.weekdaysShort = Lb, Xe._weekdaysShort = Je, Xe.weekdaysParse = Nb, Xe.isPM = Tb, Xe._meridiemParse = Le, Xe.meridiem = Ub, v("en", {
        ordinalParse: /\d{1,2}(th|st|nd|rd)/,
        ordinal: function(a) {
            var b = a % 10;
            return a + (1 === p(a % 100 / 10) ? "th" : 1 === b ? "st" : 2 === b ? "nd" : 3 === b ? "rd" : "th")
        }
    }), a.lang = _("moment.lang is deprecated. Use moment.locale instead.", v), a.langData = _("moment.langData is deprecated. Use moment.localeData instead.", x);
    var Ye = Math.abs,
        Ze = xc("ms"),
        $e = xc("s"),
        _e = xc("m"),
        af = xc("h"),
        bf = xc("d"),
        cf = xc("w"),
        df = xc("M"),
        ef = xc("y"),
        ff = zc("milliseconds"),
        gf = zc("seconds"),
        hf = zc("minutes"),
        jf = zc("hours"),
        kf = zc("days"),
        lf = zc("months"),
        mf = zc("years"),
        nf = Math.round,
        of = {
            s: 45,
            m: 45,
            h: 22,
            d: 26,
            M: 11
        },
        pf = Math.abs,
        qf = Ga.prototype;
    qf.abs = nc, qf.add = pc, qf.subtract = qc, qf.as = vc, qf.asMilliseconds = Ze, qf.asSeconds = $e, qf.asMinutes = _e, qf.asHours = af, qf.asDays = bf, qf.asWeeks = cf, qf.asMonths = df, qf.asYears = ef, qf.valueOf = wc, qf._bubble = sc, qf.get = yc, qf.milliseconds = ff, qf.seconds = gf, qf.minutes = hf, qf.hours = jf, qf.days = kf, qf.weeks = Ac, qf.months = lf, qf.years = mf, qf.humanize = Ec, qf.toISOString = Fc, qf.toString = Fc, qf.toJSON = Fc, qf.locale = qb, qf.localeData = rb, qf.toIsoString = _("toIsoString() is deprecated. Please use toISOString() instead (notice the capitals)", Fc), qf.lang = Ge, G("X", 0, 0, "unix"), G("x", 0, 0, "valueOf"), M("x", de), M("X", fe), P("X", function(a, b, c) {
            c._d = new Date(1e3 * parseFloat(a, 10))
        }), P("x", function(a, b, c) {
            c._d = new Date(p(a))
        }), a.version = "2.10.6",
        function(a) {
            Ld = a
        }(Ca), a.fn = Se, a.min = Ea, a.max = Fa, a.utc = g, a.unix = Yb, a.months = ic, a.isDate = c, a.locale = v, a.invalid = k, a.duration = Xa, a.isMoment = n, a.weekdays = kc, a.parseZone = Zb, a.localeData = x, a.isDuration = Ha, a.monthsShort = jc, a.weekdaysMin = mc, a.defineLocale = w, a.weekdaysShort = lc, a.normalizeUnits = z, a.relativeTimeThreshold = Dc;
    var rf = a,
        sf = (rf.defineLocale("af", {
            months: "Januarie_Februarie_Maart_April_Mei_Junie_Julie_Augustus_September_Oktober_November_Desember".split("_"),
            monthsShort: "Jan_Feb_Mar_Apr_Mei_Jun_Jul_Aug_Sep_Okt_Nov_Des".split("_"),
            weekdays: "Sondag_Maandag_Dinsdag_Woensdag_Donderdag_Vrydag_Saterdag".split("_"),
            weekdaysShort: "Son_Maa_Din_Woe_Don_Vry_Sat".split("_"),
            weekdaysMin: "So_Ma_Di_Wo_Do_Vr_Sa".split("_"),
            meridiemParse: /vm|nm/i,
            isPM: function(a) {
                return /^nm$/i.test(a)
            },
            meridiem: function(a, b, c) {
                return 12 > a ? c ? "vm" : "VM" : c ? "nm" : "NM"
            },
            longDateFormat: {
                LT: "HH:mm",
                LTS: "HH:mm:ss",
                L: "DD/MM/YYYY",
                LL: "D MMMM YYYY",
                LLL: "D MMMM YYYY HH:mm",
                LLLL: "dddd, D MMMM YYYY HH:mm"
            },
            calendar: {
                sameDay: "[Vandag om] LT",
                nextDay: "[Môre om] LT",
                nextWeek: "dddd [om] LT",
                lastDay: "[Gister om] LT",
                lastWeek: "[Laas] dddd [om] LT",
                sameElse: "L"
            },
            relativeTime: {
                future: "oor %s",
                past: "%s gelede",
                s: "'n paar sekondes",
                m: "'n minuut",
                mm: "%d minute",
                h: "'n uur",
                hh: "%d ure",
                d: "'n dag",
                dd: "%d dae",
                M: "'n maand",
                MM: "%d maande",
                y: "'n jaar",
                yy: "%d jaar"
            },
            ordinalParse: /\d{1,2}(ste|de)/,
            ordinal: function(a) {
                return a + (1 === a || 8 === a || a >= 20 ? "ste" : "de")
            },
            week: {
                dow: 1,
                doy: 4
            }
        }), rf.defineLocale("ar-ma", {
            months: "يناير_فبراير_مارس_أبريل_ماي_يونيو_يوليوز_غشت_شتنبر_أكتوبر_نونبر_دجنبر".split("_"),
            monthsShort: "يناير_فبراير_مارس_أبريل_ماي_يونيو_يوليوز_غشت_شتنبر_أكتوبر_نونبر_دجنبر".split("_"),
            weekdays: "الأحد_الإتنين_الثلاثاء_الأربعاء_الخميس_الجمعة_السبت".split("_"),
            weekdaysShort: "احد_اتنين_ثلاثاء_اربعاء_خميس_جمعة_سبت".split("_"),
            weekdaysMin: "ح_ن_ث_ر_خ_ج_س".split("_"),
            longDateFormat: {
                LT: "HH:mm",
                LTS: "HH:mm:ss",
                L: "DD/MM/YYYY",
                LL: "D MMMM YYYY",
                LLL: "D MMMM YYYY HH:mm",
                LLLL: "dddd D MMMM YYYY HH:mm"
            },
            calendar: {
                sameDay: "[اليوم على الساعة] LT",
                nextDay: "[غدا على الساعة] LT",
                nextWeek: "dddd [على الساعة] LT",
                lastDay: "[أمس على الساعة] LT",
                lastWeek: "dddd [على الساعة] LT",
                sameElse: "L"
            },
            relativeTime: {
                future: "في %s",
                past: "منذ %s",
                s: "ثوان",
                m: "دقيقة",
                mm: "%d دقائق",
                h: "ساعة",
                hh: "%d ساعات",
                d: "يوم",
                dd: "%d أيام",
                M: "شهر",
                MM: "%d أشهر",
                y: "سنة",
                yy: "%d سنوات"
            },
            week: {
                dow: 6,
                doy: 12
            }
        }), {
            1: "١",
            2: "٢",
            3: "٣",
            4: "٤",
            5: "٥",
            6: "٦",
            7: "٧",
            8: "٨",
            9: "٩",
            0: "٠"
        }),
        tf = {
            "١": "1",
            "٢": "2",
            "٣": "3",
            "٤": "4",
            "٥": "5",
            "٦": "6",
            "٧": "7",
            "٨": "8",
            "٩": "9",
            "٠": "0"
        },
        uf = (rf.defineLocale("ar-sa", {
            months: "يناير_فبراير_مارس_أبريل_مايو_يونيو_يوليو_أغسطس_سبتمبر_أكتوبر_نوفمبر_ديسمبر".split("_"),
            monthsShort: "يناير_فبراير_مارس_أبريل_مايو_يونيو_يوليو_أغسطس_سبتمبر_أكتوبر_نوفمبر_ديسمبر".split("_"),
            weekdays: "الأحد_الإثنين_الثلاثاء_الأربعاء_الخميس_الجمعة_السبت".split("_"),
            weekdaysShort: "أحد_إثنين_ثلاثاء_أربعاء_خميس_جمعة_سبت".split("_"),
            weekdaysMin: "ح_ن_ث_ر_خ_ج_س".split("_"),
            longDateFormat: {
                LT: "HH:mm",
                LTS: "HH:mm:ss",
                L: "DD/MM/YYYY",
                LL: "D MMMM YYYY",
                LLL: "D MMMM YYYY HH:mm",
                LLLL: "dddd D MMMM YYYY HH:mm"
            },
            meridiemParse: /ص|م/,
            isPM: function(a) {
                return "م" === a
            },
            meridiem: function(a, b, c) {
                return 12 > a ? "ص" : "م"
            },
            calendar: {
                sameDay: "[اليوم على الساعة] LT",
                nextDay: "[غدا على الساعة] LT",
                nextWeek: "dddd [على الساعة] LT",
                lastDay: "[أمس على الساعة] LT",
                lastWeek: "dddd [على الساعة] LT",
                sameElse: "L"
            },
            relativeTime: {
                future: "في %s",
                past: "منذ %s",
                s: "ثوان",
                m: "دقيقة",
                mm: "%d دقائق",
                h: "ساعة",
                hh: "%d ساعات",
                d: "يوم",
                dd: "%d أيام",
                M: "شهر",
                MM: "%d أشهر",
                y: "سنة",
                yy: "%d سنوات"
            },
            preparse: function(a) {
                return a.replace(/[١٢٣٤٥٦٧٨٩٠]/g, function(a) {
                    return tf[a]
                }).replace(/،/g, ",")
            },
            postformat: function(a) {
                return a.replace(/\d/g, function(a) {
                    return sf[a]
                }).replace(/,/g, "،")
            },
            week: {
                dow: 6,
                doy: 12
            }
        }), rf.defineLocale("ar-tn", {
            months: "جانفي_فيفري_مارس_أفريل_ماي_جوان_جويلية_أوت_سبتمبر_أكتوبر_نوفمبر_ديسمبر".split("_"),
            monthsShort: "جانفي_فيفري_مارس_أفريل_ماي_جوان_جويلية_أوت_سبتمبر_أكتوبر_نوفمبر_ديسمبر".split("_"),
            weekdays: "الأحد_الإثنين_الثلاثاء_الأربعاء_الخميس_الجمعة_السبت".split("_"),
            weekdaysShort: "أحد_إثنين_ثلاثاء_أربعاء_خميس_جمعة_سبت".split("_"),
            weekdaysMin: "ح_ن_ث_ر_خ_ج_س".split("_"),
            longDateFormat: {
                LT: "HH:mm",
                LTS: "HH:mm:ss",
                L: "DD/MM/YYYY",
                LL: "D MMMM YYYY",
                LLL: "D MMMM YYYY HH:mm",
                LLLL: "dddd D MMMM YYYY HH:mm"
            },
            calendar: {
                sameDay: "[اليوم على الساعة] LT",
                nextDay: "[غدا على الساعة] LT",
                nextWeek: "dddd [على الساعة] LT",
                lastDay: "[أمس على الساعة] LT",
                lastWeek: "dddd [على الساعة] LT",
                sameElse: "L"
            },
            relativeTime: {
                future: "في %s",
                past: "منذ %s",
                s: "ثوان",
                m: "دقيقة",
                mm: "%d دقائق",
                h: "ساعة",
                hh: "%d ساعات",
                d: "يوم",
                dd: "%d أيام",
                M: "شهر",
                MM: "%d أشهر",
                y: "سنة",
                yy: "%d سنوات"
            },
            week: {
                dow: 1,
                doy: 4
            }
        }), {
            1: "١",
            2: "٢",
            3: "٣",
            4: "٤",
            5: "٥",
            6: "٦",
            7: "٧",
            8: "٨",
            9: "٩",
            0: "٠"
        }),
        vf = {
            "١": "1",
            "٢": "2",
            "٣": "3",
            "٤": "4",
            "٥": "5",
            "٦": "6",
            "٧": "7",
            "٨": "8",
            "٩": "9",
            "٠": "0"
        },
        wf = function(a) {
            return 0 === a ? 0 : 1 === a ? 1 : 2 === a ? 2 : a % 100 >= 3 && 10 >= a % 100 ? 3 : a % 100 >= 11 ? 4 : 5
        },
        xf = {
            s: ["أقل من ثانية", "ثانية واحدة", ["ثانيتان", "ثانيتين"], "%d ثوان", "%d ثانية", "%d ثانية"],
            m: ["أقل من دقيقة", "دقيقة واحدة", ["دقيقتان", "دقيقتين"], "%d دقائق", "%d دقيقة", "%d دقيقة"],
            h: ["أقل من ساعة", "ساعة واحدة", ["ساعتان", "ساعتين"], "%d ساعات", "%d ساعة", "%d ساعة"],
            d: ["أقل من يوم", "يوم واحد", ["يومان", "يومين"], "%d أيام", "%d يومًا", "%d يوم"],
            M: ["أقل من شهر", "شهر واحد", ["شهران", "شهرين"], "%d أشهر", "%d شهرا", "%d شهر"],
            y: ["أقل من عام", "عام واحد", ["عامان", "عامين"], "%d أعوام", "%d عامًا", "%d عام"]
        },
        yf = function(a) {
            return function(b, c, d, e) {
                var f = wf(b),
                    g = xf[a][wf(b)];
                return 2 === f && (g = g[c ? 0 : 1]), g.replace(/%d/i, b)
            }
        },
        zf = ["كانون الثاني يناير", "شباط فبراير", "آذار مارس", "نيسان أبريل", "أيار مايو", "حزيران يونيو", "تموز يوليو", "آب أغسطس", "أيلول سبتمبر", "تشرين الأول أكتوبر", "تشرين الثاني نوفمبر", "كانون الأول ديسمبر"],
        Af = (rf.defineLocale("ar", {
            months: zf,
            monthsShort: zf,
            weekdays: "الأحد_الإثنين_الثلاثاء_الأربعاء_الخميس_الجمعة_السبت".split("_"),
            weekdaysShort: "أحد_إثنين_ثلاثاء_أربعاء_خميس_جمعة_سبت".split("_"),
            weekdaysMin: "ح_ن_ث_ر_خ_ج_س".split("_"),
            longDateFormat: {
                LT: "HH:mm",
                LTS: "HH:mm:ss",
                L: "D/‏M/‏YYYY",
                LL: "D MMMM YYYY",
                LLL: "D MMMM YYYY HH:mm",
                LLLL: "dddd D MMMM YYYY HH:mm"
            },
            meridiemParse: /ص|م/,
            isPM: function(a) {
                return "م" === a
            },
            meridiem: function(a, b, c) {
                return 12 > a ? "ص" : "م"
            },
            calendar: {
                sameDay: "[اليوم عند الساعة] LT",
                nextDay: "[غدًا عند الساعة] LT",
                nextWeek: "dddd [عند الساعة] LT",
                lastDay: "[أمس عند الساعة] LT",
                lastWeek: "dddd [عند الساعة] LT",
                sameElse: "L"
            },
            relativeTime: {
                future: "بعد %s",
                past: "منذ %s",
                s: yf("s"),
                m: yf("m"),
                mm: yf("m"),
                h: yf("h"),
                hh: yf("h"),
                d: yf("d"),
                dd: yf("d"),
                M: yf("M"),
                MM: yf("M"),
                y: yf("y"),
                yy: yf("y")
            },
            preparse: function(a) {
                return a.replace(/\u200f/g, "").replace(/[١٢٣٤٥٦٧٨٩٠]/g, function(a) {
                    return vf[a]
                }).replace(/،/g, ",")
            },
            postformat: function(a) {
                return a.replace(/\d/g, function(a) {
                    return uf[a]
                }).replace(/,/g, "،")
            },
            week: {
                dow: 6,
                doy: 12
            }
        }), {
            1: "-inci",
            5: "-inci",
            8: "-inci",
            70: "-inci",
            80: "-inci",
            2: "-nci",
            7: "-nci",
            20: "-nci",
            50: "-nci",
            3: "-üncü",
            4: "-üncü",
            100: "-üncü",
            6: "-ncı",
            9: "-uncu",
            10: "-uncu",
            30: "-uncu",
            60: "-ıncı",
            90: "-ıncı"
        }),
        Bf = (rf.defineLocale("az", {
            months: "yanvar_fevral_mart_aprel_may_iyun_iyul_avqust_sentyabr_oktyabr_noyabr_dekabr".split("_"),
            monthsShort: "yan_fev_mar_apr_may_iyn_iyl_avq_sen_okt_noy_dek".split("_"),
            weekdays: "Bazar_Bazar ertəsi_Çərşənbə axşamı_Çərşənbə_Cümə axşamı_Cümə_Şənbə".split("_"),
            weekdaysShort: "Baz_BzE_ÇAx_Çər_CAx_Cüm_Şən".split("_"),
            weekdaysMin: "Bz_BE_ÇA_Çə_CA_Cü_Şə".split("_"),
            longDateFormat: {
                LT: "HH:mm",
                LTS: "HH:mm:ss",
                L: "DD.MM.YYYY",
                LL: "D MMMM YYYY",
                LLL: "D MMMM YYYY HH:mm",
                LLLL: "dddd, D MMMM YYYY HH:mm"
            },
            calendar: {
                sameDay: "[bugün saat] LT",
                nextDay: "[sabah saat] LT",
                nextWeek: "[gələn həftə] dddd [saat] LT",
                lastDay: "[dünən] LT",
                lastWeek: "[keçən həftə] dddd [saat] LT",
                sameElse: "L"
            },
            relativeTime: {
                future: "%s sonra",
                past: "%s əvvəl",
                s: "birneçə saniyyə",
                m: "bir dəqiqə",
                mm: "%d dəqiqə",
                h: "bir saat",
                hh: "%d saat",
                d: "bir gün",
                dd: "%d gün",
                M: "bir ay",
                MM: "%d ay",
                y: "bir il",
                yy: "%d il"
            },
            meridiemParse: /gecə|səhər|gündüz|axşam/,
            isPM: function(a) {
                return /^(gündüz|axşam)$/.test(a)
            },
            meridiem: function(a, b, c) {
                return 4 > a ? "gecə" : 12 > a ? "səhər" : 17 > a ? "gündüz" : "axşam"
            },
            ordinalParse: /\d{1,2}-(ıncı|inci|nci|üncü|ncı|uncu)/,
            ordinal: function(a) {
                if (0 === a) return a + "-ıncı";
                var b = a % 10,
                    c = a % 100 - b,
                    d = a >= 100 ? 100 : null;
                return a + (Af[b] || Af[c] || Af[d])
            },
            week: {
                dow: 1,
                doy: 7
            }
        }), rf.defineLocale("be", {
            months: Ic,
            monthsShort: "студ_лют_сак_крас_трав_чэрв_ліп_жнів_вер_каст_ліст_снеж".split("_"),
            weekdays: Jc,
            weekdaysShort: "нд_пн_ат_ср_чц_пт_сб".split("_"),
            weekdaysMin: "нд_пн_ат_ср_чц_пт_сб".split("_"),
            longDateFormat: {
                LT: "HH:mm",
                LTS: "HH:mm:ss",
                L: "DD.MM.YYYY",
                LL: "D MMMM YYYY г.",
                LLL: "D MMMM YYYY г., HH:mm",
                LLLL: "dddd, D MMMM YYYY г., HH:mm"
            },
            calendar: {
                sameDay: "[Сёння ў] LT",
                nextDay: "[Заўтра ў] LT",
                lastDay: "[Учора ў] LT",
                nextWeek: function() {
                    return "[У] dddd [ў] LT"
                },
                lastWeek: function() {
                    switch (this.day()) {
                        case 0:
                        case 3:
                        case 5:
                        case 6:
                            return "[У мінулую] dddd [ў] LT";
                        case 1:
                        case 2:
                        case 4:
                            return "[У мінулы] dddd [ў] LT"
                    }
                },
                sameElse: "L"
            },
            relativeTime: {
                future: "праз %s",
                past: "%s таму",
                s: "некалькі секунд",
                m: Hc,
                mm: Hc,
                h: Hc,
                hh: Hc,
                d: "дзень",
                dd: Hc,
                M: "месяц",
                MM: Hc,
                y: "год",
                yy: Hc
            },
            meridiemParse: /ночы|раніцы|дня|вечара/,
            isPM: function(a) {
                return /^(дня|вечара)$/.test(a)
            },
            meridiem: function(a, b, c) {
                return 4 > a ? "ночы" : 12 > a ? "раніцы" : 17 > a ? "дня" : "вечара"
            },
            ordinalParse: /\d{1,2}-(і|ы|га)/,
            ordinal: function(a, b) {
                switch (b) {
                    case "M":
                    case "d":
                    case "DDD":
                    case "w":
                    case "W":
                        return a % 10 != 2 && a % 10 != 3 || a % 100 == 12 || a % 100 == 13 ? a + "-ы" : a + "-і";
                    case "D":
                        return a + "-га";
                    default:
                        return a
                }
            },
            week: {
                dow: 1,
                doy: 7
            }
        }), rf.defineLocale("bg", {
            months: "януари_февруари_март_април_май_юни_юли_август_септември_октомври_ноември_декември".split("_"),
            monthsShort: "янр_фев_мар_апр_май_юни_юли_авг_сеп_окт_ное_дек".split("_"),
            weekdays: "неделя_понеделник_вторник_сряда_четвъртък_петък_събота".split("_"),
            weekdaysShort: "нед_пон_вто_сря_чет_пет_съб".split("_"),
            weekdaysMin: "нд_пн_вт_ср_чт_пт_сб".split("_"),
            longDateFormat: {
                LT: "H:mm",
                LTS: "H:mm:ss",
                L: "D.MM.YYYY",
                LL: "D MMMM YYYY",
                LLL: "D MMMM YYYY H:mm",
                LLLL: "dddd, D MMMM YYYY H:mm"
            },
            calendar: {
                sameDay: "[Днес в] LT",
                nextDay: "[Утре в] LT",
                nextWeek: "dddd [в] LT",
                lastDay: "[Вчера в] LT",
                lastWeek: function() {
                    switch (this.day()) {
                        case 0:
                        case 3:
                        case 6:
                            return "[В изминалата] dddd [в] LT";
                        case 1:
                        case 2:
                        case 4:
                        case 5:
                            return "[В изминалия] dddd [в] LT"
                    }
                },
                sameElse: "L"
            },
            relativeTime: {
                future: "след %s",
                past: "преди %s",
                s: "няколко секунди",
                m: "минута",
                mm: "%d минути",
                h: "час",
                hh: "%d часа",
                d: "ден",
                dd: "%d дни",
                M: "месец",
                MM: "%d месеца",
                y: "година",
                yy: "%d години"
            },
            ordinalParse: /\d{1,2}-(ев|ен|ти|ви|ри|ми)/,
            ordinal: function(a) {
                var b = a % 10,
                    c = a % 100;
                return 0 === a ? a + "-ев" : 0 === c ? a + "-ен" : c > 10 && 20 > c ? a + "-ти" : 1 === b ? a + "-ви" : 2 === b ? a + "-ри" : 7 === b || 8 === b ? a + "-ми" : a + "-ти"
            },
            week: {
                dow: 1,
                doy: 7
            }
        }), {
            1: "১",
            2: "২",
            3: "৩",
            4: "৪",
            5: "৫",
            6: "৬",
            7: "৭",
            8: "৮",
            9: "৯",
            0: "০"
        }),
        Cf = {
            "১": "1",
            "২": "2",
            "৩": "3",
            "৪": "4",
            "৫": "5",
            "৬": "6",
            "৭": "7",
            "৮": "8",
            "৯": "9",
            "০": "0"
        },
        Df = (rf.defineLocale("bn", {
            months: "জানুয়ারী_ফেবুয়ারী_মার্চ_এপ্রিল_মে_জুন_জুলাই_অগাস্ট_সেপ্টেম্বর_অক্টোবর_নভেম্বর_ডিসেম্বর".split("_"),
            monthsShort: "জানু_ফেব_মার্চ_এপর_মে_জুন_জুল_অগ_সেপ্ট_অক্টো_নভ_ডিসেম্".split("_"),
            weekdays: "রবিবার_সোমবার_মঙ্গলবার_বুধবার_বৃহস্পত্তিবার_শুক্রুবার_শনিবার".split("_"),
            weekdaysShort: "রবি_সোম_মঙ্গল_বুধ_বৃহস্পত্তি_শুক্রু_শনি".split("_"),
            weekdaysMin: "রব_সম_মঙ্গ_বু_ব্রিহ_শু_শনি".split("_"),
            longDateFormat: {
                LT: "A h:mm সময়",
                LTS: "A h:mm:ss সময়",
                L: "DD/MM/YYYY",
                LL: "D MMMM YYYY",
                LLL: "D MMMM YYYY, A h:mm সময়",
                LLLL: "dddd, D MMMM YYYY, A h:mm সময়"
            },
            calendar: {
                sameDay: "[আজ] LT",
                nextDay: "[আগামীকাল] LT",
                nextWeek: "dddd, LT",
                lastDay: "[গতকাল] LT",
                lastWeek: "[গত] dddd, LT",
                sameElse: "L"
            },
            relativeTime: {
                future: "%s পরে",
                past: "%s আগে",
                s: "কএক সেকেন্ড",
                m: "এক মিনিট",
                mm: "%d মিনিট",
                h: "এক ঘন্টা",
                hh: "%d ঘন্টা",
                d: "এক দিন",
                dd: "%d দিন",
                M: "এক মাস",
                MM: "%d মাস",
                y: "এক বছর",
                yy: "%d বছর"
            },
            preparse: function(a) {
                return a.replace(/[১২৩৪৫৬৭৮৯০]/g, function(a) {
                    return Cf[a]
                })
            },
            postformat: function(a) {
                return a.replace(/\d/g, function(a) {
                    return Bf[a]
                })
            },
            meridiemParse: /রাত|সকাল|দুপুর|বিকেল|রাত/,
            isPM: function(a) {
                return /^(দুপুর|বিকেল|রাত)$/.test(a)
            },
            meridiem: function(a, b, c) {
                return 4 > a ? "রাত" : 10 > a ? "সকাল" : 17 > a ? "দুপুর" : 20 > a ? "বিকেল" : "রাত"
            },
            week: {
                dow: 0,
                doy: 6
            }
        }), {
            1: "༡",
            2: "༢",
            3: "༣",
            4: "༤",
            5: "༥",
            6: "༦",
            7: "༧",
            8: "༨",
            9: "༩",
            0: "༠"
        }),
        Ef = {
            "༡": "1",
            "༢": "2",
            "༣": "3",
            "༤": "4",
            "༥": "5",
            "༦": "6",
            "༧": "7",
            "༨": "8",
            "༩": "9",
            "༠": "0"
        },
        Ff = (rf.defineLocale("bo", {
            months: "ཟླ་བ་དང་པོ_ཟླ་བ་གཉིས་པ_ཟླ་བ་གསུམ་པ_ཟླ་བ་བཞི་པ_ཟླ་བ་ལྔ་པ_ཟླ་བ་དྲུག་པ_ཟླ་བ་བདུན་པ_ཟླ་བ་བརྒྱད་པ_ཟླ་བ་དགུ་པ_ཟླ་བ་བཅུ་པ_ཟླ་བ་བཅུ་གཅིག་པ_ཟླ་བ་བཅུ་གཉིས་པ".split("_"),
            monthsShort: "ཟླ་བ་དང་པོ_ཟླ་བ་གཉིས་པ_ཟླ་བ་གསུམ་པ_ཟླ་བ་བཞི་པ_ཟླ་བ་ལྔ་པ_ཟླ་བ་དྲུག་པ_ཟླ་བ་བདུན་པ_ཟླ་བ་བརྒྱད་པ_ཟླ་བ་དགུ་པ_ཟླ་བ་བཅུ་པ_ཟླ་བ་བཅུ་གཅིག་པ_ཟླ་བ་བཅུ་གཉིས་པ".split("_"),
            weekdays: "གཟའ་ཉི་མ་_གཟའ་ཟླ་བ་_གཟའ་མིག་དམར་_གཟའ་ལྷག་པ་_གཟའ་ཕུར་བུ_གཟའ་པ་སངས་_གཟའ་སྤེན་པ་".split("_"),
            weekdaysShort: "ཉི་མ་_ཟླ་བ་_མིག་དམར་_ལྷག་པ་_ཕུར་བུ_པ་སངས་_སྤེན་པ་".split("_"),
            weekdaysMin: "ཉི་མ་_ཟླ་བ་_མིག་དམར་_ལྷག་པ་_ཕུར་བུ_པ་སངས་_སྤེན་པ་".split("_"),
            longDateFormat: {
                LT: "A h:mm",
                LTS: "A h:mm:ss",
                L: "DD/MM/YYYY",
                LL: "D MMMM YYYY",
                LLL: "D MMMM YYYY, A h:mm",
                LLLL: "dddd, D MMMM YYYY, A h:mm"
            },
            calendar: {
                sameDay: "[དི་རིང] LT",
                nextDay: "[སང་ཉིན] LT",
                nextWeek: "[བདུན་ཕྲག་རྗེས་མ], LT",
                lastDay: "[ཁ་སང] LT",
                lastWeek: "[བདུན་ཕྲག་མཐའ་མ] dddd, LT",
                sameElse: "L"
            },
            relativeTime: {
                future: "%s ལ་",
                past: "%s སྔན་ལ",
                s: "ལམ་སང",
                m: "སྐར་མ་གཅིག",
                mm: "%d སྐར་མ",
                h: "ཆུ་ཚོད་གཅིག",
                hh: "%d ཆུ་ཚོད",
                d: "ཉིན་གཅིག",
                dd: "%d ཉིན་",
                M: "ཟླ་བ་གཅིག",
                MM: "%d ཟླ་བ",
                y: "ལོ་གཅིག",
                yy: "%d ལོ"
            },
            preparse: function(a) {
                return a.replace(/[༡༢༣༤༥༦༧༨༩༠]/g, function(a) {
                    return Ef[a]
                })
            },
            postformat: function(a) {
                return a.replace(/\d/g, function(a) {
                    return Df[a]
                })
            },
            meridiemParse: /མཚན་མོ|ཞོགས་ཀས|ཉིན་གུང|དགོང་དག|མཚན་མོ/,
            isPM: function(a) {
                return /^(ཉིན་གུང|དགོང་དག|མཚན་མོ)$/.test(a)
            },
            meridiem: function(a, b, c) {
                return 4 > a ? "མཚན་མོ" : 10 > a ? "ཞོགས་ཀས" : 17 > a ? "ཉིན་གུང" : 20 > a ? "དགོང་དག" : "མཚན་མོ"
            },
            week: {
                dow: 0,
                doy: 6
            }
        }), rf.defineLocale("br", {
            months: "Genver_C'hwevrer_Meurzh_Ebrel_Mae_Mezheven_Gouere_Eost_Gwengolo_Here_Du_Kerzu".split("_"),
            monthsShort: "Gen_C'hwe_Meu_Ebr_Mae_Eve_Gou_Eos_Gwe_Her_Du_Ker".split("_"),
            weekdays: "Sul_Lun_Meurzh_Merc'her_Yaou_Gwener_Sadorn".split("_"),
            weekdaysShort: "Sul_Lun_Meu_Mer_Yao_Gwe_Sad".split("_"),
            weekdaysMin: "Su_Lu_Me_Mer_Ya_Gw_Sa".split("_"),
            longDateFormat: {
                LT: "h[e]mm A",
                LTS: "h[e]mm:ss A",
                L: "DD/MM/YYYY",
                LL: "D [a viz] MMMM YYYY",
                LLL: "D [a viz] MMMM YYYY h[e]mm A",
                LLLL: "dddd, D [a viz] MMMM YYYY h[e]mm A"
            },
            calendar: {
                sameDay: "[Hiziv da] LT",
                nextDay: "[Warc'hoazh da] LT",
                nextWeek: "dddd [da] LT",
                lastDay: "[Dec'h da] LT",
                lastWeek: "dddd [paset da] LT",
                sameElse: "L"
            },
            relativeTime: {
                future: "a-benn %s",
                past: "%s 'zo",
                s: "un nebeud segondennoù",
                m: "ur vunutenn",
                mm: Kc,
                h: "un eur",
                hh: "%d eur",
                d: "un devezh",
                dd: Kc,
                M: "ur miz",
                MM: Kc,
                y: "ur bloaz",
                yy: Lc
            },
            ordinalParse: /\d{1,2}(añ|vet)/,
            ordinal: function(a) {
                return a + (1 === a ? "añ" : "vet")
            },
            week: {
                dow: 1,
                doy: 4
            }
        }), rf.defineLocale("bs", {
            months: "januar_februar_mart_april_maj_juni_juli_august_septembar_oktobar_novembar_decembar".split("_"),
            monthsShort: "jan._feb._mar._apr._maj._jun._jul._aug._sep._okt._nov._dec.".split("_"),
            weekdays: "nedjelja_ponedjeljak_utorak_srijeda_četvrtak_petak_subota".split("_"),
            weekdaysShort: "ned._pon._uto._sri._čet._pet._sub.".split("_"),
            weekdaysMin: "ne_po_ut_sr_če_pe_su".split("_"),
            longDateFormat: {
                LT: "H:mm",
                LTS: "H:mm:ss",
                L: "DD. MM. YYYY",
                LL: "D. MMMM YYYY",
                LLL: "D. MMMM YYYY H:mm",
                LLLL: "dddd, D. MMMM YYYY H:mm"
            },
            calendar: {
                sameDay: "[danas u] LT",
                nextDay: "[sutra u] LT",
                nextWeek: function() {
                    switch (this.day()) {
                        case 0:
                            return "[u] [nedjelju] [u] LT";
                        case 3:
                            return "[u] [srijedu] [u] LT";
                        case 6:
                            return "[u] [subotu] [u] LT";
                        case 1:
                        case 2:
                        case 4:
                        case 5:
                            return "[u] dddd [u] LT"
                    }
                },
                lastDay: "[jučer u] LT",
                lastWeek: function() {
                    switch (this.day()) {
                        case 0:
                        case 3:
                            return "[prošlu] dddd [u] LT";
                        case 6:
                            return "[prošle] [subote] [u] LT";
                        case 1:
                        case 2:
                        case 4:
                        case 5:
                            return "[prošli] dddd [u] LT"
                    }
                },
                sameElse: "L"
            },
            relativeTime: {
                future: "za %s",
                past: "prije %s",
                s: "par sekundi",
                m: Pc,
                mm: Pc,
                h: Pc,
                hh: Pc,
                d: "dan",
                dd: Pc,
                M: "mjesec",
                MM: Pc,
                y: "godinu",
                yy: Pc
            },
            ordinalParse: /\d{1,2}\./,
            ordinal: "%d.",
            week: {
                dow: 1,
                doy: 7
            }
        }), rf.defineLocale("ca", {
            months: "gener_febrer_març_abril_maig_juny_juliol_agost_setembre_octubre_novembre_desembre".split("_"),
            monthsShort: "gen._febr._mar._abr._mai._jun._jul._ag._set._oct._nov._des.".split("_"),
            weekdays: "diumenge_dilluns_dimarts_dimecres_dijous_divendres_dissabte".split("_"),
            weekdaysShort: "dg._dl._dt._dc._dj._dv._ds.".split("_"),
            weekdaysMin: "Dg_Dl_Dt_Dc_Dj_Dv_Ds".split("_"),
            longDateFormat: {
                LT: "H:mm",
                LTS: "LT:ss",
                L: "DD/MM/YYYY",
                LL: "D MMMM YYYY",
                LLL: "D MMMM YYYY H:mm",
                LLLL: "dddd D MMMM YYYY H:mm"
            },
            calendar: {
                sameDay: function() {
                    return "[avui a " + (1 !== this.hours() ? "les" : "la") + "] LT"
                },
                nextDay: function() {
                    return "[demà a " + (1 !== this.hours() ? "les" : "la") + "] LT"
                },
                nextWeek: function() {
                    return "dddd [a " + (1 !== this.hours() ? "les" : "la") + "] LT"
                },
                lastDay: function() {
                    return "[ahir a " + (1 !== this.hours() ? "les" : "la") + "] LT"
                },
                lastWeek: function() {
                    return "[el] dddd [passat a " + (1 !== this.hours() ? "les" : "la") + "] LT"
                },
                sameElse: "L"
            },
            relativeTime: {
                future: "en %s",
                past: "fa %s",
                s: "uns segons",
                m: "un minut",
                mm: "%d minuts",
                h: "una hora",
                hh: "%d hores",
                d: "un dia",
                dd: "%d dies",
                M: "un mes",
                MM: "%d mesos",
                y: "un any",
                yy: "%d anys"
            },
            ordinalParse: /\d{1,2}(r|n|t|è|a)/,
            ordinal: function(a, b) {
                var c = 1 === a ? "r" : 2 === a ? "n" : 3 === a ? "r" : 4 === a ? "t" : "è";
                return ("w" === b || "W" === b) && (c = "a"), a + c
            },
            week: {
                dow: 1,
                doy: 4
            }
        }), "leden_únor_březen_duben_květen_červen_červenec_srpen_září_říjen_listopad_prosinec".split("_")),
        Gf = "led_úno_bře_dub_kvě_čvn_čvc_srp_zář_říj_lis_pro".split("_"),
        Hf = (rf.defineLocale("cs", {
            months: Ff,
            monthsShort: Gf,
            monthsParse: function(a, b) {
                var c, d = [];
                for (c = 0; 12 > c; c++) d[c] = new RegExp("^" + a[c] + "$|^" + b[c] + "$", "i");
                return d
            }(Ff, Gf),
            weekdays: "neděle_pondělí_úterý_středa_čtvrtek_pátek_sobota".split("_"),
            weekdaysShort: "ne_po_út_st_čt_pá_so".split("_"),
            weekdaysMin: "ne_po_út_st_čt_pá_so".split("_"),
            longDateFormat: {
                LT: "H:mm",
                LTS: "H:mm:ss",
                L: "DD.MM.YYYY",
                LL: "D. MMMM YYYY",
                LLL: "D. MMMM YYYY H:mm",
                LLLL: "dddd D. MMMM YYYY H:mm"
            },
            calendar: {
                sameDay: "[dnes v] LT",
                nextDay: "[zítra v] LT",
                nextWeek: function() {
                    switch (this.day()) {
                        case 0:
                            return "[v neděli v] LT";
                        case 1:
                        case 2:
                            return "[v] dddd [v] LT";
                        case 3:
                            return "[ve středu v] LT";
                        case 4:
                            return "[ve čtvrtek v] LT";
                        case 5:
                            return "[v pátek v] LT";
                        case 6:
                            return "[v sobotu v] LT"
                    }
                },
                lastDay: "[včera v] LT",
                lastWeek: function() {
                    switch (this.day()) {
                        case 0:
                            return "[minulou neděli v] LT";
                        case 1:
                        case 2:
                            return "[minulé] dddd [v] LT";
                        case 3:
                            return "[minulou středu v] LT";
                        case 4:
                        case 5:
                            return "[minulý] dddd [v] LT";
                        case 6:
                            return "[minulou sobotu v] LT"
                    }
                },
                sameElse: "L"
            },
            relativeTime: {
                future: "za %s",
                past: "před %s",
                s: Rc,
                m: Rc,
                mm: Rc,
                h: Rc,
                hh: Rc,
                d: Rc,
                dd: Rc,
                M: Rc,
                MM: Rc,
                y: Rc,
                yy: Rc
            },
            ordinalParse: /\d{1,2}\./,
            ordinal: "%d.",
            week: {
                dow: 1,
                doy: 4
            }
        }), rf.defineLocale("cv", {
            months: "кӑрлач_нарӑс_пуш_ака_май_ҫӗртме_утӑ_ҫурла_авӑн_юпа_чӳк_раштав".split("_"),
            monthsShort: "кӑр_нар_пуш_ака_май_ҫӗр_утӑ_ҫур_авн_юпа_чӳк_раш".split("_"),
            weekdays: "вырсарникун_тунтикун_ытларикун_юнкун_кӗҫнерникун_эрнекун_шӑматкун".split("_"),
            weekdaysShort: "выр_тун_ытл_юн_кӗҫ_эрн_шӑм".split("_"),
            weekdaysMin: "вр_тн_ыт_юн_кҫ_эр_шм".split("_"),
            longDateFormat: {
                LT: "HH:mm",
                LTS: "HH:mm:ss",
                L: "DD-MM-YYYY",
                LL: "YYYY [ҫулхи] MMMM [уйӑхӗн] D[-мӗшӗ]",
                LLL: "YYYY [ҫулхи] MMMM [уйӑхӗн] D[-мӗшӗ], HH:mm",
                LLLL: "dddd, YYYY [ҫулхи] MMMM [уйӑхӗн] D[-мӗшӗ], HH:mm"
            },
            calendar: {
                sameDay: "[Паян] LT [сехетре]",
                nextDay: "[Ыран] LT [сехетре]",
                lastDay: "[Ӗнер] LT [сехетре]",
                nextWeek: "[Ҫитес] dddd LT [сехетре]",
                lastWeek: "[Иртнӗ] dddd LT [сехетре]",
                sameElse: "L"
            },
            relativeTime: {
                future: function(a) {
                    return a + (/сехет$/i.exec(a) ? "рен" : /ҫул$/i.exec(a) ? "тан" : "ран")
                },
                past: "%s каялла",
                s: "пӗр-ик ҫеккунт",
                m: "пӗр минут",
                mm: "%d минут",
                h: "пӗр сехет",
                hh: "%d сехет",
                d: "пӗр кун",
                dd: "%d кун",
                M: "пӗр уйӑх",
                MM: "%d уйӑх",
                y: "пӗр ҫул",
                yy: "%d ҫул"
            },
            ordinalParse: /\d{1,2}-мӗш/,
            ordinal: "%d-мӗш",
            week: {
                dow: 1,
                doy: 7
            }
        }), rf.defineLocale("cy", {
            months: "Ionawr_Chwefror_Mawrth_Ebrill_Mai_Mehefin_Gorffennaf_Awst_Medi_Hydref_Tachwedd_Rhagfyr".split("_"),
            monthsShort: "Ion_Chwe_Maw_Ebr_Mai_Meh_Gor_Aws_Med_Hyd_Tach_Rhag".split("_"),
            weekdays: "Dydd Sul_Dydd Llun_Dydd Mawrth_Dydd Mercher_Dydd Iau_Dydd Gwener_Dydd Sadwrn".split("_"),
            weekdaysShort: "Sul_Llun_Maw_Mer_Iau_Gwe_Sad".split("_"),
            weekdaysMin: "Su_Ll_Ma_Me_Ia_Gw_Sa".split("_"),
            longDateFormat: {
                LT: "HH:mm",
                LTS: "HH:mm:ss",
                L: "DD/MM/YYYY",
                LL: "D MMMM YYYY",
                LLL: "D MMMM YYYY HH:mm",
                LLLL: "dddd, D MMMM YYYY HH:mm"
            },
            calendar: {
                sameDay: "[Heddiw am] LT",
                nextDay: "[Yfory am] LT",
                nextWeek: "dddd [am] LT",
                lastDay: "[Ddoe am] LT",
                lastWeek: "dddd [diwethaf am] LT",
                sameElse: "L"
            },
            relativeTime: {
                future: "mewn %s",
                past: "%s yn ôl",
                s: "ychydig eiliadau",
                m: "munud",
                mm: "%d munud",
                h: "awr",
                hh: "%d awr",
                d: "diwrnod",
                dd: "%d diwrnod",
                M: "mis",
                MM: "%d mis",
                y: "blwyddyn",
                yy: "%d flynedd"
            },
            ordinalParse: /\d{1,2}(fed|ain|af|il|ydd|ed|eg)/,
            ordinal: function(a) {
                var b = a,
                    c = "",
                    d = ["", "af", "il", "ydd", "ydd", "ed", "ed", "ed", "fed", "fed", "fed", "eg", "fed", "eg", "eg", "fed", "eg", "eg", "fed", "eg", "fed"];
                return b > 20 ? c = 40 === b || 50 === b || 60 === b || 80 === b || 100 === b ? "fed" : "ain" : b > 0 && (c = d[b]), a + c
            },
            week: {
                dow: 1,
                doy: 4
            }
        }), rf.defineLocale("da", {
            months: "januar_februar_marts_april_maj_juni_juli_august_september_oktober_november_december".split("_"),
            monthsShort: "jan_feb_mar_apr_maj_jun_jul_aug_sep_okt_nov_dec".split("_"),
            weekdays: "søndag_mandag_tirsdag_onsdag_torsdag_fredag_lørdag".split("_"),
            weekdaysShort: "søn_man_tir_ons_tor_fre_lør".split("_"),
            weekdaysMin: "sø_ma_ti_on_to_fr_lø".split("_"),
            longDateFormat: {
                LT: "HH:mm",
                LTS: "HH:mm:ss",
                L: "DD/MM/YYYY",
                LL: "D. MMMM YYYY",
                LLL: "D. MMMM YYYY HH:mm",
                LLLL: "dddd [d.] D. MMMM YYYY HH:mm"
            },
            calendar: {
                sameDay: "[I dag kl.] LT",
                nextDay: "[I morgen kl.] LT",
                nextWeek: "dddd [kl.] LT",
                lastDay: "[I går kl.] LT",
                lastWeek: "[sidste] dddd [kl] LT",
                sameElse: "L"
            },
            relativeTime: {
                future: "om %s",
                past: "%s siden",
                s: "få sekunder",
                m: "et minut",
                mm: "%d minutter",
                h: "en time",
                hh: "%d timer",
                d: "en dag",
                dd: "%d dage",
                M: "en måned",
                MM: "%d måneder",
                y: "et år",
                yy: "%d år"
            },
            ordinalParse: /\d{1,2}\./,
            ordinal: "%d.",
            week: {
                dow: 1,
                doy: 4
            }
        }), rf.defineLocale("de-at", {
            months: "Jänner_Februar_März_April_Mai_Juni_Juli_August_September_Oktober_November_Dezember".split("_"),
            monthsShort: "Jän._Febr._Mrz._Apr._Mai_Jun._Jul._Aug._Sept._Okt._Nov._Dez.".split("_"),
            weekdays: "Sonntag_Montag_Dienstag_Mittwoch_Donnerstag_Freitag_Samstag".split("_"),
            weekdaysShort: "So._Mo._Di._Mi._Do._Fr._Sa.".split("_"),
            weekdaysMin: "So_Mo_Di_Mi_Do_Fr_Sa".split("_"),
            longDateFormat: {
                LT: "HH:mm",
                LTS: "HH:mm:ss",
                L: "DD.MM.YYYY",
                LL: "D. MMMM YYYY",
                LLL: "D. MMMM YYYY HH:mm",
                LLLL: "dddd, D. MMMM YYYY HH:mm"
            },
            calendar: {
                sameDay: "[Heute um] LT [Uhr]",
                sameElse: "L",
                nextDay: "[Morgen um] LT [Uhr]",
                nextWeek: "dddd [um] LT [Uhr]",
                lastDay: "[Gestern um] LT [Uhr]",
                lastWeek: "[letzten] dddd [um] LT [Uhr]"
            },
            relativeTime: {
                future: "in %s",
                past: "vor %s",
                s: "ein paar Sekunden",
                m: Sc,
                mm: "%d Minuten",
                h: Sc,
                hh: "%d Stunden",
                d: Sc,
                dd: Sc,
                M: Sc,
                MM: Sc,
                y: Sc,
                yy: Sc
            },
            ordinalParse: /\d{1,2}\./,
            ordinal: "%d.",
            week: {
                dow: 1,
                doy: 4
            }
        }), rf.defineLocale("de", {
            months: "Januar_Februar_März_April_Mai_Juni_Juli_August_September_Oktober_November_Dezember".split("_"),
            monthsShort: "Jan._Febr._Mrz._Apr._Mai_Jun._Jul._Aug._Sept._Okt._Nov._Dez.".split("_"),
            weekdays: "Sonntag_Montag_Dienstag_Mittwoch_Donnerstag_Freitag_Samstag".split("_"),
            weekdaysShort: "So._Mo._Di._Mi._Do._Fr._Sa.".split("_"),
            weekdaysMin: "So_Mo_Di_Mi_Do_Fr_Sa".split("_"),
            longDateFormat: {
                LT: "HH:mm",
                LTS: "HH:mm:ss",
                L: "DD.MM.YYYY",
                LL: "D. MMMM YYYY",
                LLL: "D. MMMM YYYY HH:mm",
                LLLL: "dddd, D. MMMM YYYY HH:mm"
            },
            calendar: {
                sameDay: "[Heute um] LT [Uhr]",
                sameElse: "L",
                nextDay: "[Morgen um] LT [Uhr]",
                nextWeek: "dddd [um] LT [Uhr]",
                lastDay: "[Gestern um] LT [Uhr]",
                lastWeek: "[letzten] dddd [um] LT [Uhr]"
            },
            relativeTime: {
                future: "in %s",
                past: "vor %s",
                s: "ein paar Sekunden",
                m: Tc,
                mm: "%d Minuten",
                h: Tc,
                hh: "%d Stunden",
                d: Tc,
                dd: Tc,
                M: Tc,
                MM: Tc,
                y: Tc,
                yy: Tc
            },
            ordinalParse: /\d{1,2}\./,
            ordinal: "%d.",
            week: {
                dow: 1,
                doy: 4
            }
        }), rf.defineLocale("el", {
            monthsNominativeEl: "Ιανουάριος_Φεβρουάριος_Μάρτιος_Απρίλιος_Μάιος_Ιούνιος_Ιούλιος_Αύγουστος_Σεπτέμβριος_Οκτώβριος_Νοέμβριος_Δεκέμβριος".split("_"),
            monthsGenitiveEl: "Ιανουαρίου_Φεβρουαρίου_Μαρτίου_Απριλίου_Μαΐου_Ιουνίου_Ιουλίου_Αυγούστου_Σεπτεμβρίου_Οκτωβρίου_Νοεμβρίου_Δεκεμβρίου".split("_"),
            months: function(a, b) {
                return /D/.test(b.substring(0, b.indexOf("MMMM"))) ? this._monthsGenitiveEl[a.month()] : this._monthsNominativeEl[a.month()]
            },
            monthsShort: "Ιαν_Φεβ_Μαρ_Απρ_Μαϊ_Ιουν_Ιουλ_Αυγ_Σεπ_Οκτ_Νοε_Δεκ".split("_"),
            weekdays: "Κυριακή_Δευτέρα_Τρίτη_Τετάρτη_Πέμπτη_Παρασκευή_Σάββατο".split("_"),
            weekdaysShort: "Κυρ_Δευ_Τρι_Τετ_Πεμ_Παρ_Σαβ".split("_"),
            weekdaysMin: "Κυ_Δε_Τρ_Τε_Πε_Πα_Σα".split("_"),
            meridiem: function(a, b, c) {
                return a > 11 ? c ? "μμ" : "ΜΜ" : c ? "πμ" : "ΠΜ"
            },
            isPM: function(a) {
                return "μ" === (a + "").toLowerCase()[0]
            },
            meridiemParse: /[ΠΜ]\.?Μ?\.?/i,
            longDateFormat: {
                LT: "h:mm A",
                LTS: "h:mm:ss A",
                L: "DD/MM/YYYY",
                LL: "D MMMM YYYY",
                LLL: "D MMMM YYYY h:mm A",
                LLLL: "dddd, D MMMM YYYY h:mm A"
            },
            calendarEl: {
                sameDay: "[Σήμερα {}] LT",
                nextDay: "[Αύριο {}] LT",
                nextWeek: "dddd [{}] LT",
                lastDay: "[Χθες {}] LT",
                lastWeek: function() {
                    switch (this.day()) {
                        case 6:
                            return "[το προηγούμενο] dddd [{}] LT";
                        default:
                            return "[την προηγούμενη] dddd [{}] LT"
                    }
                },
                sameElse: "L"
            },
            calendar: function(a, b) {
                var c = this._calendarEl[a],
                    d = b && b.hours();
                return "function" == typeof c && (c = c.apply(b)), c.replace("{}", d % 12 == 1 ? "στη" : "στις")
            },
            relativeTime: {
                future: "σε %s",
                past: "%s πριν",
                s: "λίγα δευτερόλεπτα",
                m: "ένα λεπτό",
                mm: "%d λεπτά",
                h: "μία ώρα",
                hh: "%d ώρες",
                d: "μία μέρα",
                dd: "%d μέρες",
                M: "ένας μήνας",
                MM: "%d μήνες",
                y: "ένας χρόνος",
                yy: "%d χρόνια"
            },
            ordinalParse: /\d{1,2}η/,
            ordinal: "%dη",
            week: {
                dow: 1,
                doy: 4
            }
        }), rf.defineLocale("en-au", {
            months: "January_February_March_April_May_June_July_August_September_October_November_December".split("_"),
            monthsShort: "Jan_Feb_Mar_Apr_May_Jun_Jul_Aug_Sep_Oct_Nov_Dec".split("_"),
            weekdays: "Sunday_Monday_Tuesday_Wednesday_Thursday_Friday_Saturday".split("_"),
            weekdaysShort: "Sun_Mon_Tue_Wed_Thu_Fri_Sat".split("_"),
            weekdaysMin: "Su_Mo_Tu_We_Th_Fr_Sa".split("_"),
            longDateFormat: {
                LT: "h:mm A",
                LTS: "h:mm:ss A",
                L: "DD/MM/YYYY",
                LL: "D MMMM YYYY",
                LLL: "D MMMM YYYY h:mm A",
                LLLL: "dddd, D MMMM YYYY h:mm A"
            },
            calendar: {
                sameDay: "[Today at] LT",
                nextDay: "[Tomorrow at] LT",
                nextWeek: "dddd [at] LT",
                lastDay: "[Yesterday at] LT",
                lastWeek: "[Last] dddd [at] LT",
                sameElse: "L"
            },
            relativeTime: {
                future: "in %s",
                past: "%s ago",
                s: "a few seconds",
                m: "a minute",
                mm: "%d minutes",
                h: "an hour",
                hh: "%d hours",
                d: "a day",
                dd: "%d days",
                M: "a month",
                MM: "%d months",
                y: "a year",
                yy: "%d years"
            },
            ordinalParse: /\d{1,2}(st|nd|rd|th)/,
            ordinal: function(a) {
                var b = a % 10;
                return a + (1 == ~~(a % 100 / 10) ? "th" : 1 === b ? "st" : 2 === b ? "nd" : 3 === b ? "rd" : "th")
            },
            week: {
                dow: 1,
                doy: 4
            }
        }), rf.defineLocale("en-ca", {
            months: "January_February_March_April_May_June_July_August_September_October_November_December".split("_"),
            monthsShort: "Jan_Feb_Mar_Apr_May_Jun_Jul_Aug_Sep_Oct_Nov_Dec".split("_"),
            weekdays: "Sunday_Monday_Tuesday_Wednesday_Thursday_Friday_Saturday".split("_"),
            weekdaysShort: "Sun_Mon_Tue_Wed_Thu_Fri_Sat".split("_"),
            weekdaysMin: "Su_Mo_Tu_We_Th_Fr_Sa".split("_"),
            longDateFormat: {
                LT: "h:mm A",
                LTS: "h:mm:ss A",
                L: "YYYY-MM-DD",
                LL: "D MMMM, YYYY",
                LLL: "D MMMM, YYYY h:mm A",
                LLLL: "dddd, D MMMM, YYYY h:mm A"
            },
            calendar: {
                sameDay: "[Today at] LT",
                nextDay: "[Tomorrow at] LT",
                nextWeek: "dddd [at] LT",
                lastDay: "[Yesterday at] LT",
                lastWeek: "[Last] dddd [at] LT",
                sameElse: "L"
            },
            relativeTime: {
                future: "in %s",
                past: "%s ago",
                s: "a few seconds",
                m: "a minute",
                mm: "%d minutes",
                h: "an hour",
                hh: "%d hours",
                d: "a day",
                dd: "%d days",
                M: "a month",
                MM: "%d months",
                y: "a year",
                yy: "%d years"
            },
            ordinalParse: /\d{1,2}(st|nd|rd|th)/,
            ordinal: function(a) {
                var b = a % 10;
                return a + (1 == ~~(a % 100 / 10) ? "th" : 1 === b ? "st" : 2 === b ? "nd" : 3 === b ? "rd" : "th")
            }
        }), rf.defineLocale("en-gb", {
            months: "January_February_March_April_May_June_July_August_September_October_November_December".split("_"),
            monthsShort: "Jan_Feb_Mar_Apr_May_Jun_Jul_Aug_Sep_Oct_Nov_Dec".split("_"),
            weekdays: "Sunday_Monday_Tuesday_Wednesday_Thursday_Friday_Saturday".split("_"),
            weekdaysShort: "Sun_Mon_Tue_Wed_Thu_Fri_Sat".split("_"),
            weekdaysMin: "Su_Mo_Tu_We_Th_Fr_Sa".split("_"),
            longDateFormat: {
                LT: "HH:mm",
                LTS: "HH:mm:ss",
                L: "DD/MM/YYYY",
                LL: "D MMMM YYYY",
                LLL: "D MMMM YYYY HH:mm",
                LLLL: "dddd, D MMMM YYYY HH:mm"
            },
            calendar: {
                sameDay: "[Today at] LT",
                nextDay: "[Tomorrow at] LT",
                nextWeek: "dddd [at] LT",
                lastDay: "[Yesterday at] LT",
                lastWeek: "[Last] dddd [at] LT",
                sameElse: "L"
            },
            relativeTime: {
                future: "in %s",
                past: "%s ago",
                s: "a few seconds",
                m: "a minute",
                mm: "%d minutes",
                h: "an hour",
                hh: "%d hours",
                d: "a day",
                dd: "%d days",
                M: "a month",
                MM: "%d months",
                y: "a year",
                yy: "%d years"
            },
            ordinalParse: /\d{1,2}(st|nd|rd|th)/,
            ordinal: function(a) {
                var b = a % 10;
                return a + (1 == ~~(a % 100 / 10) ? "th" : 1 === b ? "st" : 2 === b ? "nd" : 3 === b ? "rd" : "th")
            },
            week: {
                dow: 1,
                doy: 4
            }
        }), rf.defineLocale("eo", {
            months: "januaro_februaro_marto_aprilo_majo_junio_julio_aŭgusto_septembro_oktobro_novembro_decembro".split("_"),
            monthsShort: "jan_feb_mar_apr_maj_jun_jul_aŭg_sep_okt_nov_dec".split("_"),
            weekdays: "Dimanĉo_Lundo_Mardo_Merkredo_Ĵaŭdo_Vendredo_Sabato".split("_"),
            weekdaysShort: "Dim_Lun_Mard_Merk_Ĵaŭ_Ven_Sab".split("_"),
            weekdaysMin: "Di_Lu_Ma_Me_Ĵa_Ve_Sa".split("_"),
            longDateFormat: {
                LT: "HH:mm",
                LTS: "HH:mm:ss",
                L: "YYYY-MM-DD",
                LL: "D[-an de] MMMM, YYYY",
                LLL: "D[-an de] MMMM, YYYY HH:mm",
                LLLL: "dddd, [la] D[-an de] MMMM, YYYY HH:mm"
            },
            meridiemParse: /[ap]\.t\.m/i,
            isPM: function(a) {
                return "p" === a.charAt(0).toLowerCase()
            },
            meridiem: function(a, b, c) {
                return a > 11 ? c ? "p.t.m." : "P.T.M." : c ? "a.t.m." : "A.T.M."
            },
            calendar: {
                sameDay: "[Hodiaŭ je] LT",
                nextDay: "[Morgaŭ je] LT",
                nextWeek: "dddd [je] LT",
                lastDay: "[Hieraŭ je] LT",
                lastWeek: "[pasinta] dddd [je] LT",
                sameElse: "L"
            },
            relativeTime: {
                future: "je %s",
                past: "antaŭ %s",
                s: "sekundoj",
                m: "minuto",
                mm: "%d minutoj",
                h: "horo",
                hh: "%d horoj",
                d: "tago",
                dd: "%d tagoj",
                M: "monato",
                MM: "%d monatoj",
                y: "jaro",
                yy: "%d jaroj"
            },
            ordinalParse: /\d{1,2}a/,
            ordinal: "%da",
            week: {
                dow: 1,
                doy: 7
            }
        }), "Ene._Feb._Mar._Abr._May._Jun._Jul._Ago._Sep._Oct._Nov._Dic.".split("_")),
        If = "Ene_Feb_Mar_Abr_May_Jun_Jul_Ago_Sep_Oct_Nov_Dic".split("_"),
        Jf = (rf.defineLocale("es", {
            months: "Enero_Febrero_Marzo_Abril_Mayo_Junio_Julio_Agosto_Septiembre_Octubre_Noviembre_Diciembre".split("_"),
            monthsShort: function(a, b) {
                return /-MMM-/.test(b) ? If[a.month()] : Hf[a.month()]
            },
            weekdays: "Domingo_Lunes_Martes_Miércoles_Jueves_Viernes_Sábado".split("_"),
            weekdaysShort: "Dom._Lun._Mar._Mié._Jue._Vie._Sáb.".split("_"),
            weekdaysMin: "Do_Lu_Ma_Mi_Ju_Vi_Sá".split("_"),
            longDateFormat: {
                LT: "H:mm",
                LTS: "H:mm:ss",
                L: "DD/MM/YYYY",
                LL: "D [de] MMMM [de] YYYY",
                LLL: "D [de] MMMM [de] YYYY H:mm",
                LLLL: "dddd, D [de] MMMM [de] YYYY H:mm"
            },
            calendar: {
                sameDay: function() {
                    return "[hoy a la" + (1 !== this.hours() ? "s" : "") + "] LT"
                },
                nextDay: function() {
                    return "[mañana a la" + (1 !== this.hours() ? "s" : "") + "] LT"
                },
                nextWeek: function() {
                    return "dddd [a la" + (1 !== this.hours() ? "s" : "") + "] LT"
                },
                lastDay: function() {
                    return "[ayer a la" + (1 !== this.hours() ? "s" : "") + "] LT"
                },
                lastWeek: function() {
                    return "[el] dddd [pasado a la" + (1 !== this.hours() ? "s" : "") + "] LT"
                },
                sameElse: "L"
            },
            relativeTime: {
                future: "en %s",
                past: "hace %s",
                s: "unos segundos",
                m: "un minuto",
                mm: "%d minutos",
                h: "una hora",
                hh: "%d horas",
                d: "un día",
                dd: "%d días",
                M: "un mes",
                MM: "%d meses",
                y: "un año",
                yy: "%d años"
            },
            ordinalParse: /\d{1,2}º/,
            ordinal: "%dº",
            week: {
                dow: 1,
                doy: 4
            }
        }), rf.defineLocale("et", {
            months: "jaanuar_veebruar_märts_aprill_mai_juuni_juuli_august_september_oktoober_november_detsember".split("_"),
            monthsShort: "jaan_veebr_märts_apr_mai_juuni_juuli_aug_sept_okt_nov_dets".split("_"),
            weekdays: "pühapäev_esmaspäev_teisipäev_kolmapäev_neljapäev_reede_laupäev".split("_"),
            weekdaysShort: "P_E_T_K_N_R_L".split("_"),
            weekdaysMin: "P_E_T_K_N_R_L".split("_"),
            longDateFormat: {
                LT: "H:mm",
                LTS: "H:mm:ss",
                L: "DD.MM.YYYY",
                LL: "D. MMMM YYYY",
                LLL: "D. MMMM YYYY H:mm",
                LLLL: "dddd, D. MMMM YYYY H:mm"
            },
            calendar: {
                sameDay: "[Täna,] LT",
                nextDay: "[Homme,] LT",
                nextWeek: "[Järgmine] dddd LT",
                lastDay: "[Eile,] LT",
                lastWeek: "[Eelmine] dddd LT",
                sameElse: "L"
            },
            relativeTime: {
                future: "%s pärast",
                past: "%s tagasi",
                s: Uc,
                m: Uc,
                mm: Uc,
                h: Uc,
                hh: Uc,
                d: Uc,
                dd: "%d päeva",
                M: Uc,
                MM: Uc,
                y: Uc,
                yy: Uc
            },
            ordinalParse: /\d{1,2}\./,
            ordinal: "%d.",
            week: {
                dow: 1,
                doy: 4
            }
        }), rf.defineLocale("eu", {
            months: "urtarrila_otsaila_martxoa_apirila_maiatza_ekaina_uztaila_abuztua_iraila_urria_azaroa_abendua".split("_"),
            monthsShort: "urt._ots._mar._api._mai._eka._uzt._abu._ira._urr._aza._abe.".split("_"),
            weekdays: "igandea_astelehena_asteartea_asteazkena_osteguna_ostirala_larunbata".split("_"),
            weekdaysShort: "ig._al._ar._az._og._ol._lr.".split("_"),
            weekdaysMin: "ig_al_ar_az_og_ol_lr".split("_"),
            longDateFormat: {
                LT: "HH:mm",
                LTS: "HH:mm:ss",
                L: "YYYY-MM-DD",
                LL: "YYYY[ko] MMMM[ren] D[a]",
                LLL: "YYYY[ko] MMMM[ren] D[a] HH:mm",
                LLLL: "dddd, YYYY[ko] MMMM[ren] D[a] HH:mm",
                l: "YYYY-M-D",
                ll: "YYYY[ko] MMM D[a]",
                lll: "YYYY[ko] MMM D[a] HH:mm",
                llll: "ddd, YYYY[ko] MMM D[a] HH:mm"
            },
            calendar: {
                sameDay: "[gaur] LT[etan]",
                nextDay: "[bihar] LT[etan]",
                nextWeek: "dddd LT[etan]",
                lastDay: "[atzo] LT[etan]",
                lastWeek: "[aurreko] dddd LT[etan]",
                sameElse: "L"
            },
            relativeTime: {
                future: "%s barru",
                past: "duela %s",
                s: "segundo batzuk",
                m: "minutu bat",
                mm: "%d minutu",
                h: "ordu bat",
                hh: "%d ordu",
                d: "egun bat",
                dd: "%d egun",
                M: "hilabete bat",
                MM: "%d hilabete",
                y: "urte bat",
                yy: "%d urte"
            },
            ordinalParse: /\d{1,2}\./,
            ordinal: "%d.",
            week: {
                dow: 1,
                doy: 7
            }
        }), {
            1: "۱",
            2: "۲",
            3: "۳",
            4: "۴",
            5: "۵",
            6: "۶",
            7: "۷",
            8: "۸",
            9: "۹",
            0: "۰"
        }),
        Kf = {
            "۱": "1",
            "۲": "2",
            "۳": "3",
            "۴": "4",
            "۵": "5",
            "۶": "6",
            "۷": "7",
            "۸": "8",
            "۹": "9",
            "۰": "0"
        },
        Lf = (rf.defineLocale("fa", {
            months: "ژانویه_فوریه_مارس_آوریل_مه_ژوئن_ژوئیه_اوت_سپتامبر_اکتبر_نوامبر_دسامبر".split("_"),
            monthsShort: "ژانویه_فوریه_مارس_آوریل_مه_ژوئن_ژوئیه_اوت_سپتامبر_اکتبر_نوامبر_دسامبر".split("_"),
            weekdays: "یک‌شنبه_دوشنبه_سه‌شنبه_چهارشنبه_پنج‌شنبه_جمعه_شنبه".split("_"),
            weekdaysShort: "یک‌شنبه_دوشنبه_سه‌شنبه_چهارشنبه_پنج‌شنبه_جمعه_شنبه".split("_"),
            weekdaysMin: "ی_د_س_چ_پ_ج_ش".split("_"),
            longDateFormat: {
                LT: "HH:mm",
                LTS: "HH:mm:ss",
                L: "DD/MM/YYYY",
                LL: "D MMMM YYYY",
                LLL: "D MMMM YYYY HH:mm",
                LLLL: "dddd, D MMMM YYYY HH:mm"
            },
            meridiemParse: /قبل از ظهر|بعد از ظهر/,
            isPM: function(a) {
                return /بعد از ظهر/.test(a)
            },
            meridiem: function(a, b, c) {
                return 12 > a ? "قبل از ظهر" : "بعد از ظهر"
            },
            calendar: {
                sameDay: "[امروز ساعت] LT",
                nextDay: "[فردا ساعت] LT",
                nextWeek: "dddd [ساعت] LT",
                lastDay: "[دیروز ساعت] LT",
                lastWeek: "dddd [پیش] [ساعت] LT",
                sameElse: "L"
            },
            relativeTime: {
                future: "در %s",
                past: "%s پیش",
                s: "چندین ثانیه",
                m: "یک دقیقه",
                mm: "%d دقیقه",
                h: "یک ساعت",
                hh: "%d ساعت",
                d: "یک روز",
                dd: "%d روز",
                M: "یک ماه",
                MM: "%d ماه",
                y: "یک سال",
                yy: "%d سال"
            },
            preparse: function(a) {
                return a.replace(/[۰-۹]/g, function(a) {
                    return Kf[a]
                }).replace(/،/g, ",")
            },
            postformat: function(a) {
                return a.replace(/\d/g, function(a) {
                    return Jf[a]
                }).replace(/,/g, "،")
            },
            ordinalParse: /\d{1,2}م/,
            ordinal: "%dم",
            week: {
                dow: 6,
                doy: 12
            }
        }), "nolla yksi kaksi kolme neljä viisi kuusi seitsemän kahdeksan yhdeksän".split(" ")),
        Mf = ["nolla", "yhden", "kahden", "kolmen", "neljän", "viiden", "kuuden", Lf[7], Lf[8], Lf[9]],
        Nf = (rf.defineLocale("fi", {
            months: "tammikuu_helmikuu_maaliskuu_huhtikuu_toukokuu_kesäkuu_heinäkuu_elokuu_syyskuu_lokakuu_marraskuu_joulukuu".split("_"),
            monthsShort: "tammi_helmi_maalis_huhti_touko_kesä_heinä_elo_syys_loka_marras_joulu".split("_"),
            weekdays: "sunnuntai_maanantai_tiistai_keskiviikko_torstai_perjantai_lauantai".split("_"),
            weekdaysShort: "su_ma_ti_ke_to_pe_la".split("_"),
            weekdaysMin: "su_ma_ti_ke_to_pe_la".split("_"),
            longDateFormat: {
                LT: "HH.mm",
                LTS: "HH.mm.ss",
                L: "DD.MM.YYYY",
                LL: "Do MMMM[ta] YYYY",
                LLL: "Do MMMM[ta] YYYY, [klo] HH.mm",
                LLLL: "dddd, Do MMMM[ta] YYYY, [klo] HH.mm",
                l: "D.M.YYYY",
                ll: "Do MMM YYYY",
                lll: "Do MMM YYYY, [klo] HH.mm",
                llll: "ddd, Do MMM YYYY, [klo] HH.mm"
            },
            calendar: {
                sameDay: "[tänään] [klo] LT",
                nextDay: "[huomenna] [klo] LT",
                nextWeek: "dddd [klo] LT",
                lastDay: "[eilen] [klo] LT",
                lastWeek: "[viime] dddd[na] [klo] LT",
                sameElse: "L"
            },
            relativeTime: {
                future: "%s päästä",
                past: "%s sitten",
                s: Vc,
                m: Vc,
                mm: Vc,
                h: Vc,
                hh: Vc,
                d: Vc,
                dd: Vc,
                M: Vc,
                MM: Vc,
                y: Vc,
                yy: Vc
            },
            ordinalParse: /\d{1,2}\./,
            ordinal: "%d.",
            week: {
                dow: 1,
                doy: 4
            }
        }), rf.defineLocale("fo", {
            months: "januar_februar_mars_apríl_mai_juni_juli_august_september_oktober_november_desember".split("_"),
            monthsShort: "jan_feb_mar_apr_mai_jun_jul_aug_sep_okt_nov_des".split("_"),
            weekdays: "sunnudagur_mánadagur_týsdagur_mikudagur_hósdagur_fríggjadagur_leygardagur".split("_"),
            weekdaysShort: "sun_mán_týs_mik_hós_frí_ley".split("_"),
            weekdaysMin: "su_má_tý_mi_hó_fr_le".split("_"),
            longDateFormat: {
                LT: "HH:mm",
                LTS: "HH:mm:ss",
                L: "DD/MM/YYYY",
                LL: "D MMMM YYYY",
                LLL: "D MMMM YYYY HH:mm",
                LLLL: "dddd D. MMMM, YYYY HH:mm"
            },
            calendar: {
                sameDay: "[Í dag kl.] LT",
                nextDay: "[Í morgin kl.] LT",
                nextWeek: "dddd [kl.] LT",
                lastDay: "[Í gjár kl.] LT",
                lastWeek: "[síðstu] dddd [kl] LT",
                sameElse: "L"
            },
            relativeTime: {
                future: "um %s",
                past: "%s síðani",
                s: "fá sekund",
                m: "ein minutt",
                mm: "%d minuttir",
                h: "ein tími",
                hh: "%d tímar",
                d: "ein dagur",
                dd: "%d dagar",
                M: "ein mánaði",
                MM: "%d mánaðir",
                y: "eitt ár",
                yy: "%d ár"
            },
            ordinalParse: /\d{1,2}\./,
            ordinal: "%d.",
            week: {
                dow: 1,
                doy: 4
            }
        }), rf.defineLocale("fr-ca", {
            months: "janvier_février_mars_avril_mai_juin_juillet_août_septembre_octobre_novembre_décembre".split("_"),
            monthsShort: "janv._févr._mars_avr._mai_juin_juil._août_sept._oct._nov._déc.".split("_"),
            weekdays: "dimanche_lundi_mardi_mercredi_jeudi_vendredi_samedi".split("_"),
            weekdaysShort: "dim._lun._mar._mer._jeu._ven._sam.".split("_"),
            weekdaysMin: "Di_Lu_Ma_Me_Je_Ve_Sa".split("_"),
            longDateFormat: {
                LT: "HH:mm",
                LTS: "HH:mm:ss",
                L: "YYYY-MM-DD",
                LL: "D MMMM YYYY",
                LLL: "D MMMM YYYY HH:mm",
                LLLL: "dddd D MMMM YYYY HH:mm"
            },
            calendar: {
                sameDay: "[Aujourd'hui à] LT",
                nextDay: "[Demain à] LT",
                nextWeek: "dddd [à] LT",
                lastDay: "[Hier à] LT",
                lastWeek: "dddd [dernier à] LT",
                sameElse: "L"
            },
            relativeTime: {
                future: "dans %s",
                past: "il y a %s",
                s: "quelques secondes",
                m: "une minute",
                mm: "%d minutes",
                h: "une heure",
                hh: "%d heures",
                d: "un jour",
                dd: "%d jours",
                M: "un mois",
                MM: "%d mois",
                y: "un an",
                yy: "%d ans"
            },
            ordinalParse: /\d{1,2}(er|e)/,
            ordinal: function(a) {
                return a + (1 === a ? "er" : "e")
            }
        }), rf.defineLocale("fr", {
            months: "janvier_février_mars_avril_mai_juin_juillet_août_septembre_octobre_novembre_décembre".split("_"),
            monthsShort: "janv._févr._mars_avr._mai_juin_juil._août_sept._oct._nov._déc.".split("_"),
            weekdays: "dimanche_lundi_mardi_mercredi_jeudi_vendredi_samedi".split("_"),
            weekdaysShort: "dim._lun._mar._mer._jeu._ven._sam.".split("_"),
            weekdaysMin: "Di_Lu_Ma_Me_Je_Ve_Sa".split("_"),
            longDateFormat: {
                LT: "HH:mm",
                LTS: "HH:mm:ss",
                L: "DD/MM/YYYY",
                LL: "D MMMM YYYY",
                LLL: "D MMMM YYYY HH:mm",
                LLLL: "dddd D MMMM YYYY HH:mm"
            },
            calendar: {
                sameDay: "[Aujourd'hui à] LT",
                nextDay: "[Demain à] LT",
                nextWeek: "dddd [à] LT",
                lastDay: "[Hier à] LT",
                lastWeek: "dddd [dernier à] LT",
                sameElse: "L"
            },
            relativeTime: {
                future: "dans %s",
                past: "il y a %s",
                s: "quelques secondes",
                m: "une minute",
                mm: "%d minutes",
                h: "une heure",
                hh: "%d heures",
                d: "un jour",
                dd: "%d jours",
                M: "un mois",
                MM: "%d mois",
                y: "un an",
                yy: "%d ans"
            },
            ordinalParse: /\d{1,2}(er|)/,
            ordinal: function(a) {
                return a + (1 === a ? "er" : "")
            },
            week: {
                dow: 1,
                doy: 4
            }
        }), "jan._feb._mrt._apr._mai_jun._jul._aug._sep._okt._nov._des.".split("_")),
        Of = "jan_feb_mrt_apr_mai_jun_jul_aug_sep_okt_nov_des".split("_"),
        Pf = (rf.defineLocale("fy", {
            months: "jannewaris_febrewaris_maart_april_maaie_juny_july_augustus_septimber_oktober_novimber_desimber".split("_"),
            monthsShort: function(a, b) {
                return /-MMM-/.test(b) ? Of[a.month()] : Nf[a.month()]
            },
            weekdays: "snein_moandei_tiisdei_woansdei_tongersdei_freed_sneon".split("_"),
            weekdaysShort: "si._mo._ti._wo._to._fr._so.".split("_"),
            weekdaysMin: "Si_Mo_Ti_Wo_To_Fr_So".split("_"),
            longDateFormat: {
                LT: "HH:mm",
                LTS: "HH:mm:ss",
                L: "DD-MM-YYYY",
                LL: "D MMMM YYYY",
                LLL: "D MMMM YYYY HH:mm",
                LLLL: "dddd D MMMM YYYY HH:mm"
            },
            calendar: {
                sameDay: "[hjoed om] LT",
                nextDay: "[moarn om] LT",
                nextWeek: "dddd [om] LT",
                lastDay: "[juster om] LT",
                lastWeek: "[ôfrûne] dddd [om] LT",
                sameElse: "L"
            },
            relativeTime: {
                future: "oer %s",
                past: "%s lyn",
                s: "in pear sekonden",
                m: "ien minút",
                mm: "%d minuten",
                h: "ien oere",
                hh: "%d oeren",
                d: "ien dei",
                dd: "%d dagen",
                M: "ien moanne",
                MM: "%d moannen",
                y: "ien jier",
                yy: "%d jierren"
            },
            ordinalParse: /\d{1,2}(ste|de)/,
            ordinal: function(a) {
                return a + (1 === a || 8 === a || a >= 20 ? "ste" : "de")
            },
            week: {
                dow: 1,
                doy: 4
            }
        }), rf.defineLocale("gl", {
            months: "Xaneiro_Febreiro_Marzo_Abril_Maio_Xuño_Xullo_Agosto_Setembro_Outubro_Novembro_Decembro".split("_"),
            monthsShort: "Xan._Feb._Mar._Abr._Mai._Xuñ._Xul._Ago._Set._Out._Nov._Dec.".split("_"),
            weekdays: "Domingo_Luns_Martes_Mércores_Xoves_Venres_Sábado".split("_"),
            weekdaysShort: "Dom._Lun._Mar._Mér._Xov._Ven._Sáb.".split("_"),
            weekdaysMin: "Do_Lu_Ma_Mé_Xo_Ve_Sá".split("_"),
            longDateFormat: {
                LT: "H:mm",
                LTS: "H:mm:ss",
                L: "DD/MM/YYYY",
                LL: "D MMMM YYYY",
                LLL: "D MMMM YYYY H:mm",
                LLLL: "dddd D MMMM YYYY H:mm"
            },
            calendar: {
                sameDay: function() {
                    return "[hoxe " + (1 !== this.hours() ? "ás" : "á") + "] LT"
                },
                nextDay: function() {
                    return "[mañá " + (1 !== this.hours() ? "ás" : "á") + "] LT"
                },
                nextWeek: function() {
                    return "dddd [" + (1 !== this.hours() ? "ás" : "a") + "] LT"
                },
                lastDay: function() {
                    return "[onte " + (1 !== this.hours() ? "á" : "a") + "] LT"
                },
                lastWeek: function() {
                    return "[o] dddd [pasado " + (1 !== this.hours() ? "ás" : "a") + "] LT"
                },
                sameElse: "L"
            },
            relativeTime: {
                future: function(a) {
                    return "uns segundos" === a ? "nuns segundos" : "en " + a
                },
                past: "hai %s",
                s: "uns segundos",
                m: "un minuto",
                mm: "%d minutos",
                h: "unha hora",
                hh: "%d horas",
                d: "un día",
                dd: "%d días",
                M: "un mes",
                MM: "%d meses",
                y: "un ano",
                yy: "%d anos"
            },
            ordinalParse: /\d{1,2}º/,
            ordinal: "%dº",
            week: {
                dow: 1,
                doy: 7
            }
        }), rf.defineLocale("he", {
            months: "ינואר_פברואר_מרץ_אפריל_מאי_יוני_יולי_אוגוסט_ספטמבר_אוקטובר_נובמבר_דצמבר".split("_"),
            monthsShort: "ינו׳_פבר׳_מרץ_אפר׳_מאי_יוני_יולי_אוג׳_ספט׳_אוק׳_נוב׳_דצמ׳".split("_"),
            weekdays: "ראשון_שני_שלישי_רביעי_חמישי_שישי_שבת".split("_"),
            weekdaysShort: "א׳_ב׳_ג׳_ד׳_ה׳_ו׳_ש׳".split("_"),
            weekdaysMin: "א_ב_ג_ד_ה_ו_ש".split("_"),
            longDateFormat: {
                LT: "HH:mm",
                LTS: "HH:mm:ss",
                L: "DD/MM/YYYY",
                LL: "D [ב]MMMM YYYY",
                LLL: "D [ב]MMMM YYYY HH:mm",
                LLLL: "dddd, D [ב]MMMM YYYY HH:mm",
                l: "D/M/YYYY",
                ll: "D MMM YYYY",
                lll: "D MMM YYYY HH:mm",
                llll: "ddd, D MMM YYYY HH:mm"
            },
            calendar: {
                sameDay: "[היום ב־]LT",
                nextDay: "[מחר ב־]LT",
                nextWeek: "dddd [בשעה] LT",
                lastDay: "[אתמול ב־]LT",
                lastWeek: "[ביום] dddd [האחרון בשעה] LT",
                sameElse: "L"
            },
            relativeTime: {
                future: "בעוד %s",
                past: "לפני %s",
                s: "מספר שניות",
                m: "דקה",
                mm: "%d דקות",
                h: "שעה",
                hh: function(a) {
                    return 2 === a ? "שעתיים" : a + " שעות"
                },
                d: "יום",
                dd: function(a) {
                    return 2 === a ? "יומיים" : a + " ימים"
                },
                M: "חודש",
                MM: function(a) {
                    return 2 === a ? "חודשיים" : a + " חודשים"
                },
                y: "שנה",
                yy: function(a) {
                    return 2 === a ? "שנתיים" : a % 10 == 0 && 10 !== a ? a + " שנה" : a + " שנים"
                }
            }
        }), {
            1: "१",
            2: "२",
            3: "३",
            4: "४",
            5: "५",
            6: "६",
            7: "७",
            8: "८",
            9: "९",
            0: "०"
        }),
        Qf = {
            "१": "1",
            "२": "2",
            "३": "3",
            "४": "4",
            "५": "5",
            "६": "6",
            "७": "7",
            "८": "8",
            "९": "9",
            "०": "0"
        },
        Rf = (rf.defineLocale("hi", {
            months: "जनवरी_फ़रवरी_मार्च_अप्रैल_मई_जून_जुलाई_अगस्त_सितम्बर_अक्टूबर_नवम्बर_दिसम्बर".split("_"),
            monthsShort: "जन._फ़र._मार्च_अप्रै._मई_जून_जुल._अग._सित._अक्टू._नव._दिस.".split("_"),
            weekdays: "रविवार_सोमवार_मंगलवार_बुधवार_गुरूवार_शुक्रवार_शनिवार".split("_"),
            weekdaysShort: "रवि_सोम_मंगल_बुध_गुरू_शुक्र_शनि".split("_"),
            weekdaysMin: "र_सो_मं_बु_गु_शु_श".split("_"),
            longDateFormat: {
                LT: "A h:mm बजे",
                LTS: "A h:mm:ss बजे",
                L: "DD/MM/YYYY",
                LL: "D MMMM YYYY",
                LLL: "D MMMM YYYY, A h:mm बजे",
                LLLL: "dddd, D MMMM YYYY, A h:mm बजे"
            },
            calendar: {
                sameDay: "[आज] LT",
                nextDay: "[कल] LT",
                nextWeek: "dddd, LT",
                lastDay: "[कल] LT",
                lastWeek: "[पिछले] dddd, LT",
                sameElse: "L"
            },
            relativeTime: {
                future: "%s में",
                past: "%s पहले",
                s: "कुछ ही क्षण",
                m: "एक मिनट",
                mm: "%d मिनट",
                h: "एक घंटा",
                hh: "%d घंटे",
                d: "एक दिन",
                dd: "%d दिन",
                M: "एक महीने",
                MM: "%d महीने",
                y: "एक वर्ष",
                yy: "%d वर्ष"
            },
            preparse: function(a) {
                return a.replace(/[१२३४५६७८९०]/g, function(a) {
                    return Qf[a]
                })
            },
            postformat: function(a) {
                return a.replace(/\d/g, function(a) {
                    return Pf[a]
                })
            },
            meridiemParse: /रात|सुबह|दोपहर|शाम/,
            meridiemHour: function(a, b) {
                return 12 === a && (a = 0), "रात" === b ? 4 > a ? a : a + 12 : "सुबह" === b ? a : "दोपहर" === b ? a >= 10 ? a : a + 12 : "शाम" === b ? a + 12 : void 0
            },
            meridiem: function(a, b, c) {
                return 4 > a ? "रात" : 10 > a ? "सुबह" : 17 > a ? "दोपहर" : 20 > a ? "शाम" : "रात"
            },
            week: {
                dow: 0,
                doy: 6
            }
        }), rf.defineLocale("hr", {
            months: "siječanj_veljača_ožujak_travanj_svibanj_lipanj_srpanj_kolovoz_rujan_listopad_studeni_prosinac".split("_"),
            monthsShort: "sij._velj._ožu._tra._svi._lip._srp._kol._ruj._lis._stu._pro.".split("_"),
            weekdays: "nedjelja_ponedjeljak_utorak_srijeda_četvrtak_petak_subota".split("_"),
            weekdaysShort: "ned._pon._uto._sri._čet._pet._sub.".split("_"),
            weekdaysMin: "ne_po_ut_sr_če_pe_su".split("_"),
            longDateFormat: {
                LT: "H:mm",
                LTS: "H:mm:ss",
                L: "DD. MM. YYYY",
                LL: "D. MMMM YYYY",
                LLL: "D. MMMM YYYY H:mm",
                LLLL: "dddd, D. MMMM YYYY H:mm"
            },
            calendar: {
                sameDay: "[danas u] LT",
                nextDay: "[sutra u] LT",
                nextWeek: function() {
                    switch (this.day()) {
                        case 0:
                            return "[u] [nedjelju] [u] LT";
                        case 3:
                            return "[u] [srijedu] [u] LT";
                        case 6:
                            return "[u] [subotu] [u] LT";
                        case 1:
                        case 2:
                        case 4:
                        case 5:
                            return "[u] dddd [u] LT"
                    }
                },
                lastDay: "[jučer u] LT",
                lastWeek: function() {
                    switch (this.day()) {
                        case 0:
                        case 3:
                            return "[prošlu] dddd [u] LT";
                        case 6:
                            return "[prošle] [subote] [u] LT";
                        case 1:
                        case 2:
                        case 4:
                        case 5:
                            return "[prošli] dddd [u] LT"
                    }
                },
                sameElse: "L"
            },
            relativeTime: {
                future: "za %s",
                past: "prije %s",
                s: "par sekundi",
                m: Xc,
                mm: Xc,
                h: Xc,
                hh: Xc,
                d: "dan",
                dd: Xc,
                M: "mjesec",
                MM: Xc,
                y: "godinu",
                yy: Xc
            },
            ordinalParse: /\d{1,2}\./,
            ordinal: "%d.",
            week: {
                dow: 1,
                doy: 7
            }
        }), "vasárnap hétfőn kedden szerdán csütörtökön pénteken szombaton".split(" ")),
        Sf = (rf.defineLocale("hu", {
            months: "január_február_március_április_május_június_július_augusztus_szeptember_október_november_december".split("_"),
            monthsShort: "jan_feb_márc_ápr_máj_jún_júl_aug_szept_okt_nov_dec".split("_"),
            weekdays: "vasárnap_hétfő_kedd_szerda_csütörtök_péntek_szombat".split("_"),
            weekdaysShort: "vas_hét_kedd_sze_csüt_pén_szo".split("_"),
            weekdaysMin: "v_h_k_sze_cs_p_szo".split("_"),
            longDateFormat: {
                LT: "H:mm",
                LTS: "H:mm:ss",
                L: "YYYY.MM.DD.",
                LL: "YYYY. MMMM D.",
                LLL: "YYYY. MMMM D. H:mm",
                LLLL: "YYYY. MMMM D., dddd H:mm"
            },
            meridiemParse: /de|du/i,
            isPM: function(a) {
                return "u" === a.charAt(1).toLowerCase()
            },
            meridiem: function(a, b, c) {
                return 12 > a ? !0 === c ? "de" : "DE" : !0 === c ? "du" : "DU"
            },
            calendar: {
                sameDay: "[ma] LT[-kor]",
                nextDay: "[holnap] LT[-kor]",
                nextWeek: function() {
                    return Zc.call(this, !0)
                },
                lastDay: "[tegnap] LT[-kor]",
                lastWeek: function() {
                    return Zc.call(this, !1)
                },
                sameElse: "L"
            },
            relativeTime: {
                future: "%s múlva",
                past: "%s",
                s: Yc,
                m: Yc,
                mm: Yc,
                h: Yc,
                hh: Yc,
                d: Yc,
                dd: Yc,
                M: Yc,
                MM: Yc,
                y: Yc,
                yy: Yc
            },
            ordinalParse: /\d{1,2}\./,
            ordinal: "%d.",
            week: {
                dow: 1,
                doy: 7
            }
        }), rf.defineLocale("hy-am", {
            months: $c,
            monthsShort: _c,
            weekdays: ad,
            weekdaysShort: "կրկ_երկ_երք_չրք_հնգ_ուրբ_շբթ".split("_"),
            weekdaysMin: "կրկ_երկ_երք_չրք_հնգ_ուրբ_շբթ".split("_"),
            longDateFormat: {
                LT: "HH:mm",
                LTS: "HH:mm:ss",
                L: "DD.MM.YYYY",
                LL: "D MMMM YYYY թ.",
                LLL: "D MMMM YYYY թ., HH:mm",
                LLLL: "dddd, D MMMM YYYY թ., HH:mm"
            },
            calendar: {
                sameDay: "[այսօր] LT",
                nextDay: "[վաղը] LT",
                lastDay: "[երեկ] LT",
                nextWeek: function() {
                    return "dddd [օրը ժամը] LT"
                },
                lastWeek: function() {
                    return "[անցած] dddd [օրը ժամը] LT"
                },
                sameElse: "L"
            },
            relativeTime: {
                future: "%s հետո",
                past: "%s առաջ",
                s: "մի քանի վայրկյան",
                m: "րոպե",
                mm: "%d րոպե",
                h: "ժամ",
                hh: "%d ժամ",
                d: "օր",
                dd: "%d օր",
                M: "ամիս",
                MM: "%d ամիս",
                y: "տարի",
                yy: "%d տարի"
            },
            meridiemParse: /գիշերվա|առավոտվա|ցերեկվա|երեկոյան/,
            isPM: function(a) {
                return /^(ցերեկվա|երեկոյան)$/.test(a)
            },
            meridiem: function(a) {
                return 4 > a ? "գիշերվա" : 12 > a ? "առավոտվա" : 17 > a ? "ցերեկվա" : "երեկոյան"
            },
            ordinalParse: /\d{1,2}|\d{1,2}-(ին|րդ)/,
            ordinal: function(a, b) {
                switch (b) {
                    case "DDD":
                    case "w":
                    case "W":
                    case "DDDo":
                        return 1 === a ? a + "-ին" : a + "-րդ";
                    default:
                        return a
                }
            },
            week: {
                dow: 1,
                doy: 7
            }
        }), rf.defineLocale("id", {
            months: "Januari_Februari_Maret_April_Mei_Juni_Juli_Agustus_September_Oktober_November_Desember".split("_"),
            monthsShort: "Jan_Feb_Mar_Apr_Mei_Jun_Jul_Ags_Sep_Okt_Nov_Des".split("_"),
            weekdays: "Minggu_Senin_Selasa_Rabu_Kamis_Jumat_Sabtu".split("_"),
            weekdaysShort: "Min_Sen_Sel_Rab_Kam_Jum_Sab".split("_"),
            weekdaysMin: "Mg_Sn_Sl_Rb_Km_Jm_Sb".split("_"),
            longDateFormat: {
                LT: "HH.mm",
                LTS: "HH.mm.ss",
                L: "DD/MM/YYYY",
                LL: "D MMMM YYYY",
                LLL: "D MMMM YYYY [pukul] HH.mm",
                LLLL: "dddd, D MMMM YYYY [pukul] HH.mm"
            },
            meridiemParse: /pagi|siang|sore|malam/,
            meridiemHour: function(a, b) {
                return 12 === a && (a = 0), "pagi" === b ? a : "siang" === b ? a >= 11 ? a : a + 12 : "sore" === b || "malam" === b ? a + 12 : void 0
            },
            meridiem: function(a, b, c) {
                return 11 > a ? "pagi" : 15 > a ? "siang" : 19 > a ? "sore" : "malam"
            },
            calendar: {
                sameDay: "[Hari ini pukul] LT",
                nextDay: "[Besok pukul] LT",
                nextWeek: "dddd [pukul] LT",
                lastDay: "[Kemarin pukul] LT",
                lastWeek: "dddd [lalu pukul] LT",
                sameElse: "L"
            },
            relativeTime: {
                future: "dalam %s",
                past: "%s yang lalu",
                s: "beberapa detik",
                m: "semenit",
                mm: "%d menit",
                h: "sejam",
                hh: "%d jam",
                d: "sehari",
                dd: "%d hari",
                M: "sebulan",
                MM: "%d bulan",
                y: "setahun",
                yy: "%d tahun"
            },
            week: {
                dow: 1,
                doy: 7
            }
        }), rf.defineLocale("is", {
            months: "janúar_febrúar_mars_apríl_maí_júní_júlí_ágúst_september_október_nóvember_desember".split("_"),
            monthsShort: "jan_feb_mar_apr_maí_jún_júl_ágú_sep_okt_nóv_des".split("_"),
            weekdays: "sunnudagur_mánudagur_þriðjudagur_miðvikudagur_fimmtudagur_föstudagur_laugardagur".split("_"),
            weekdaysShort: "sun_mán_þri_mið_fim_fös_lau".split("_"),
            weekdaysMin: "Su_Má_Þr_Mi_Fi_Fö_La".split("_"),
            longDateFormat: {
                LT: "H:mm",
                LTS: "H:mm:ss",
                L: "DD/MM/YYYY",
                LL: "D. MMMM YYYY",
                LLL: "D. MMMM YYYY [kl.] H:mm",
                LLLL: "dddd, D. MMMM YYYY [kl.] H:mm"
            },
            calendar: {
                sameDay: "[í dag kl.] LT",
                nextDay: "[á morgun kl.] LT",
                nextWeek: "dddd [kl.] LT",
                lastDay: "[í gær kl.] LT",
                lastWeek: "[síðasta] dddd [kl.] LT",
                sameElse: "L"
            },
            relativeTime: {
                future: "eftir %s",
                past: "fyrir %s síðan",
                s: cd,
                m: cd,
                mm: cd,
                h: "klukkustund",
                hh: cd,
                d: cd,
                dd: cd,
                M: cd,
                MM: cd,
                y: cd,
                yy: cd
            },
            ordinalParse: /\d{1,2}\./,
            ordinal: "%d.",
            week: {
                dow: 1,
                doy: 4
            }
        }), rf.defineLocale("it", {
            months: "gennaio_febbraio_marzo_aprile_maggio_giugno_luglio_agosto_settembre_ottobre_novembre_dicembre".split("_"),
            monthsShort: "gen_feb_mar_apr_mag_giu_lug_ago_set_ott_nov_dic".split("_"),
            weekdays: "Domenica_Lunedì_Martedì_Mercoledì_Giovedì_Venerdì_Sabato".split("_"),
            weekdaysShort: "Dom_Lun_Mar_Mer_Gio_Ven_Sab".split("_"),
            weekdaysMin: "D_L_Ma_Me_G_V_S".split("_"),
            longDateFormat: {
                LT: "HH:mm",
                LTS: "HH:mm:ss",
                L: "DD/MM/YYYY",
                LL: "D MMMM YYYY",
                LLL: "D MMMM YYYY HH:mm",
                LLLL: "dddd, D MMMM YYYY HH:mm"
            },
            calendar: {
                sameDay: "[Oggi alle] LT",
                nextDay: "[Domani alle] LT",
                nextWeek: "dddd [alle] LT",
                lastDay: "[Ieri alle] LT",
                lastWeek: function() {
                    switch (this.day()) {
                        case 0:
                            return "[la scorsa] dddd [alle] LT";
                        default:
                            return "[lo scorso] dddd [alle] LT"
                    }
                },
                sameElse: "L"
            },
            relativeTime: {
                future: function(a) {
                    return (/^[0-9].+$/.test(a) ? "tra" : "in") + " " + a
                },
                past: "%s fa",
                s: "alcuni secondi",
                m: "un minuto",
                mm: "%d minuti",
                h: "un'ora",
                hh: "%d ore",
                d: "un giorno",
                dd: "%d giorni",
                M: "un mese",
                MM: "%d mesi",
                y: "un anno",
                yy: "%d anni"
            },
            ordinalParse: /\d{1,2}º/,
            ordinal: "%dº",
            week: {
                dow: 1,
                doy: 4
            }
        }), rf.defineLocale("ja", {
            months: "1月_2月_3月_4月_5月_6月_7月_8月_9月_10月_11月_12月".split("_"),
            monthsShort: "1月_2月_3月_4月_5月_6月_7月_8月_9月_10月_11月_12月".split("_"),
            weekdays: "日曜日_月曜日_火曜日_水曜日_木曜日_金曜日_土曜日".split("_"),
            weekdaysShort: "日_月_火_水_木_金_土".split("_"),
            weekdaysMin: "日_月_火_水_木_金_土".split("_"),
            longDateFormat: {
                LT: "Ah時m分",
                LTS: "Ah時m分s秒",
                L: "YYYY/MM/DD",
                LL: "YYYY年M月D日",
                LLL: "YYYY年M月D日Ah時m分",
                LLLL: "YYYY年M月D日Ah時m分 dddd"
            },
            meridiemParse: /午前|午後/i,
            isPM: function(a) {
                return "午後" === a
            },
            meridiem: function(a, b, c) {
                return 12 > a ? "午前" : "午後"
            },
            calendar: {
                sameDay: "[今日] LT",
                nextDay: "[明日] LT",
                nextWeek: "[来週]dddd LT",
                lastDay: "[昨日] LT",
                lastWeek: "[前週]dddd LT",
                sameElse: "L"
            },
            relativeTime: {
                future: "%s後",
                past: "%s前",
                s: "数秒",
                m: "1分",
                mm: "%d分",
                h: "1時間",
                hh: "%d時間",
                d: "1日",
                dd: "%d日",
                M: "1ヶ月",
                MM: "%dヶ月",
                y: "1年",
                yy: "%d年"
            }
        }), rf.defineLocale("jv", {
            months: "Januari_Februari_Maret_April_Mei_Juni_Juli_Agustus_September_Oktober_Nopember_Desember".split("_"),
            monthsShort: "Jan_Feb_Mar_Apr_Mei_Jun_Jul_Ags_Sep_Okt_Nop_Des".split("_"),
            weekdays: "Minggu_Senen_Seloso_Rebu_Kemis_Jemuwah_Septu".split("_"),
            weekdaysShort: "Min_Sen_Sel_Reb_Kem_Jem_Sep".split("_"),
            weekdaysMin: "Mg_Sn_Sl_Rb_Km_Jm_Sp".split("_"),
            longDateFormat: {
                LT: "HH.mm",
                LTS: "HH.mm.ss",
                L: "DD/MM/YYYY",
                LL: "D MMMM YYYY",
                LLL: "D MMMM YYYY [pukul] HH.mm",
                LLLL: "dddd, D MMMM YYYY [pukul] HH.mm"
            },
            meridiemParse: /enjing|siyang|sonten|ndalu/,
            meridiemHour: function(a, b) {
                return 12 === a && (a = 0), "enjing" === b ? a : "siyang" === b ? a >= 11 ? a : a + 12 : "sonten" === b || "ndalu" === b ? a + 12 : void 0
            },
            meridiem: function(a, b, c) {
                return 11 > a ? "enjing" : 15 > a ? "siyang" : 19 > a ? "sonten" : "ndalu"
            },
            calendar: {
                sameDay: "[Dinten puniko pukul] LT",
                nextDay: "[Mbenjang pukul] LT",
                nextWeek: "dddd [pukul] LT",
                lastDay: "[Kala wingi pukul] LT",
                lastWeek: "dddd [kepengker pukul] LT",
                sameElse: "L"
            },
            relativeTime: {
                future: "wonten ing %s",
                past: "%s ingkang kepengker",
                s: "sawetawis detik",
                m: "setunggal menit",
                mm: "%d menit",
                h: "setunggal jam",
                hh: "%d jam",
                d: "sedinten",
                dd: "%d dinten",
                M: "sewulan",
                MM: "%d wulan",
                y: "setaun",
                yy: "%d taun"
            },
            week: {
                dow: 1,
                doy: 7
            }
        }), rf.defineLocale("ka", {
            months: dd,
            monthsShort: "იან_თებ_მარ_აპრ_მაი_ივნ_ივლ_აგვ_სექ_ოქტ_ნოე_დეკ".split("_"),
            weekdays: ed,
            weekdaysShort: "კვი_ორშ_სამ_ოთხ_ხუთ_პარ_შაბ".split("_"),
            weekdaysMin: "კვ_ორ_სა_ოთ_ხუ_პა_შა".split("_"),
            longDateFormat: {
                LT: "h:mm A",
                LTS: "h:mm:ss A",
                L: "DD/MM/YYYY",
                LL: "D MMMM YYYY",
                LLL: "D MMMM YYYY h:mm A",
                LLLL: "dddd, D MMMM YYYY h:mm A"
            },
            calendar: {
                sameDay: "[დღეს] LT[-ზე]",
                nextDay: "[ხვალ] LT[-ზე]",
                lastDay: "[გუშინ] LT[-ზე]",
                nextWeek: "[შემდეგ] dddd LT[-ზე]",
                lastWeek: "[წინა] dddd LT-ზე",
                sameElse: "L"
            },
            relativeTime: {
                future: function(a) {
                    return /(წამი|წუთი|საათი|წელი)/.test(a) ? a.replace(/ი$/, "ში") : a + "ში"
                },
                past: function(a) {
                    return /(წამი|წუთი|საათი|დღე|თვე)/.test(a) ? a.replace(/(ი|ე)$/, "ის წინ") : /წელი/.test(a) ? a.replace(/წელი$/, "წლის წინ") : void 0
                },
                s: "რამდენიმე წამი",
                m: "წუთი",
                mm: "%d წუთი",
                h: "საათი",
                hh: "%d საათი",
                d: "დღე",
                dd: "%d დღე",
                M: "თვე",
                MM: "%d თვე",
                y: "წელი",
                yy: "%d წელი"
            },
            ordinalParse: /0|1-ლი|მე-\d{1,2}|\d{1,2}-ე/,
            ordinal: function(a) {
                return 0 === a ? a : 1 === a ? a + "-ლი" : 20 > a || 100 >= a && a % 20 == 0 || a % 100 == 0 ? "მე-" + a : a + "-ე"
            },
            week: {
                dow: 1,
                doy: 7
            }
        }), rf.defineLocale("km", {
            months: "មករា_កុម្ភៈ_មិនា_មេសា_ឧសភា_មិថុនា_កក្កដា_សីហា_កញ្ញា_តុលា_វិច្ឆិកា_ធ្នូ".split("_"),
            monthsShort: "មករា_កុម្ភៈ_មិនា_មេសា_ឧសភា_មិថុនា_កក្កដា_សីហា_កញ្ញា_តុលា_វិច្ឆិកា_ធ្នូ".split("_"),
            weekdays: "អាទិត្យ_ច័ន្ទ_អង្គារ_ពុធ_ព្រហស្បតិ៍_សុក្រ_សៅរ៍".split("_"),
            weekdaysShort: "អាទិត្យ_ច័ន្ទ_អង្គារ_ពុធ_ព្រហស្បតិ៍_សុក្រ_សៅរ៍".split("_"),
            weekdaysMin: "អាទិត្យ_ច័ន្ទ_អង្គារ_ពុធ_ព្រហស្បតិ៍_សុក្រ_សៅរ៍".split("_"),
            longDateFormat: {
                LT: "HH:mm",
                LTS: "HH:mm:ss",
                L: "DD/MM/YYYY",
                LL: "D MMMM YYYY",
                LLL: "D MMMM YYYY HH:mm",
                LLLL: "dddd, D MMMM YYYY HH:mm"
            },
            calendar: {
                sameDay: "[ថ្ងៃនៈ ម៉ោង] LT",
                nextDay: "[ស្អែក ម៉ោង] LT",
                nextWeek: "dddd [ម៉ោង] LT",
                lastDay: "[ម្សិលមិញ ម៉ោង] LT",
                lastWeek: "dddd [សប្តាហ៍មុន] [ម៉ោង] LT",
                sameElse: "L"
            },
            relativeTime: {
                future: "%sទៀត",
                past: "%sមុន",
                s: "ប៉ុន្មានវិនាទី",
                m: "មួយនាទី",
                mm: "%d នាទី",
                h: "មួយម៉ោង",
                hh: "%d ម៉ោង",
                d: "មួយថ្ងៃ",
                dd: "%d ថ្ងៃ",
                M: "មួយខែ",
                MM: "%d ខែ",
                y: "មួយឆ្នាំ",
                yy: "%d ឆ្នាំ"
            },
            week: {
                dow: 1,
                doy: 4
            }
        }), rf.defineLocale("ko", {
            months: "1월_2월_3월_4월_5월_6월_7월_8월_9월_10월_11월_12월".split("_"),
            monthsShort: "1월_2월_3월_4월_5월_6월_7월_8월_9월_10월_11월_12월".split("_"),
            weekdays: "일요일_월요일_화요일_수요일_목요일_금요일_토요일".split("_"),
            weekdaysShort: "일_월_화_수_목_금_토".split("_"),
            weekdaysMin: "일_월_화_수_목_금_토".split("_"),
            longDateFormat: {
                LT: "A h시 m분",
                LTS: "A h시 m분 s초",
                L: "YYYY.MM.DD",
                LL: "YYYY년 MMMM D일",
                LLL: "YYYY년 MMMM D일 A h시 m분",
                LLLL: "YYYY년 MMMM D일 dddd A h시 m분"
            },
            calendar: {
                sameDay: "오늘 LT",
                nextDay: "내일 LT",
                nextWeek: "dddd LT",
                lastDay: "어제 LT",
                lastWeek: "지난주 dddd LT",
                sameElse: "L"
            },
            relativeTime: {
                future: "%s 후",
                past: "%s 전",
                s: "몇초",
                ss: "%d초",
                m: "일분",
                mm: "%d분",
                h: "한시간",
                hh: "%d시간",
                d: "하루",
                dd: "%d일",
                M: "한달",
                MM: "%d달",
                y: "일년",
                yy: "%d년"
            },
            ordinalParse: /\d{1,2}일/,
            ordinal: "%d일",
            meridiemParse: /오전|오후/,
            isPM: function(a) {
                return "오후" === a
            },
            meridiem: function(a, b, c) {
                return 12 > a ? "오전" : "오후"
            }
        }), rf.defineLocale("lb", {
            months: "Januar_Februar_Mäerz_Abrëll_Mee_Juni_Juli_August_September_Oktober_November_Dezember".split("_"),
            monthsShort: "Jan._Febr._Mrz._Abr._Mee_Jun._Jul._Aug._Sept._Okt._Nov._Dez.".split("_"),
            weekdays: "Sonndeg_Méindeg_Dënschdeg_Mëttwoch_Donneschdeg_Freideg_Samschdeg".split("_"),
            weekdaysShort: "So._Mé._Dë._Më._Do._Fr._Sa.".split("_"),
            weekdaysMin: "So_Mé_Dë_Më_Do_Fr_Sa".split("_"),
            longDateFormat: {
                LT: "H:mm [Auer]",
                LTS: "H:mm:ss [Auer]",
                L: "DD.MM.YYYY",
                LL: "D. MMMM YYYY",
                LLL: "D. MMMM YYYY H:mm [Auer]",
                LLLL: "dddd, D. MMMM YYYY H:mm [Auer]"
            },
            calendar: {
                sameDay: "[Haut um] LT",
                sameElse: "L",
                nextDay: "[Muer um] LT",
                nextWeek: "dddd [um] LT",
                lastDay: "[Gëschter um] LT",
                lastWeek: function() {
                    switch (this.day()) {
                        case 2:
                        case 4:
                            return "[Leschten] dddd [um] LT";
                        default:
                            return "[Leschte] dddd [um] LT"
                    }
                }
            },
            relativeTime: {
                future: gd,
                past: hd,
                s: "e puer Sekonnen",
                m: fd,
                mm: "%d Minutten",
                h: fd,
                hh: "%d Stonnen",
                d: fd,
                dd: "%d Deeg",
                M: fd,
                MM: "%d Méint",
                y: fd,
                yy: "%d Joer"
            },
            ordinalParse: /\d{1,2}\./,
            ordinal: "%d.",
            week: {
                dow: 1,
                doy: 4
            }
        }), {
            m: "minutė_minutės_minutę",
            mm: "minutės_minučių_minutes",
            h: "valanda_valandos_valandą",
            hh: "valandos_valandų_valandas",
            d: "diena_dienos_dieną",
            dd: "dienos_dienų_dienas",
            M: "mėnuo_mėnesio_mėnesį",
            MM: "mėnesiai_mėnesių_mėnesius",
            y: "metai_metų_metus",
            yy: "metai_metų_metus"
        }),
        Tf = "sekmadienis_pirmadienis_antradienis_trečiadienis_ketvirtadienis_penktadienis_šeštadienis".split("_"),
        Uf = (rf.defineLocale("lt", {
            months: kd,
            monthsShort: "sau_vas_kov_bal_geg_bir_lie_rgp_rgs_spa_lap_grd".split("_"),
            weekdays: pd,
            weekdaysShort: "Sek_Pir_Ant_Tre_Ket_Pen_Šeš".split("_"),
            weekdaysMin: "S_P_A_T_K_Pn_Š".split("_"),
            longDateFormat: {
                LT: "HH:mm",
                LTS: "HH:mm:ss",
                L: "YYYY-MM-DD",
                LL: "YYYY [m.] MMMM D [d.]",
                LLL: "YYYY [m.] MMMM D [d.], HH:mm [val.]",
                LLLL: "YYYY [m.] MMMM D [d.], dddd, HH:mm [val.]",
                l: "YYYY-MM-DD",
                ll: "YYYY [m.] MMMM D [d.]",
                lll: "YYYY [m.] MMMM D [d.], HH:mm [val.]",
                llll: "YYYY [m.] MMMM D [d.], ddd, HH:mm [val.]"
            },
            calendar: {
                sameDay: "[Šiandien] LT",
                nextDay: "[Rytoj] LT",
                nextWeek: "dddd LT",
                lastDay: "[Vakar] LT",
                lastWeek: "[Praėjusį] dddd LT",
                sameElse: "L"
            },
            relativeTime: {
                future: "po %s",
                past: "prieš %s",
                s: jd,
                m: ld,
                mm: od,
                h: ld,
                hh: od,
                d: ld,
                dd: od,
                M: ld,
                MM: od,
                y: ld,
                yy: od
            },
            ordinalParse: /\d{1,2}-oji/,
            ordinal: function(a) {
                return a + "-oji"
            },
            week: {
                dow: 1,
                doy: 4
            }
        }), {
            m: "minūtes_minūtēm_minūte_minūtes".split("_"),
            mm: "minūtes_minūtēm_minūte_minūtes".split("_"),
            h: "stundas_stundām_stunda_stundas".split("_"),
            hh: "stundas_stundām_stunda_stundas".split("_"),
            d: "dienas_dienām_diena_dienas".split("_"),
            dd: "dienas_dienām_diena_dienas".split("_"),
            M: "mēneša_mēnešiem_mēnesis_mēneši".split("_"),
            MM: "mēneša_mēnešiem_mēnesis_mēneši".split("_"),
            y: "gada_gadiem_gads_gadi".split("_"),
            yy: "gada_gadiem_gads_gadi".split("_")
        }),
        Vf = (rf.defineLocale("lv", {
            months: "janvāris_februāris_marts_aprīlis_maijs_jūnijs_jūlijs_augusts_septembris_oktobris_novembris_decembris".split("_"),
            monthsShort: "jan_feb_mar_apr_mai_jūn_jūl_aug_sep_okt_nov_dec".split("_"),
            weekdays: "svētdiena_pirmdiena_otrdiena_trešdiena_ceturtdiena_piektdiena_sestdiena".split("_"),
            weekdaysShort: "Sv_P_O_T_C_Pk_S".split("_"),
            weekdaysMin: "Sv_P_O_T_C_Pk_S".split("_"),
            longDateFormat: {
                LT: "HH:mm",
                LTS: "HH:mm:ss",
                L: "DD.MM.YYYY.",
                LL: "YYYY. [gada] D. MMMM",
                LLL: "YYYY. [gada] D. MMMM, HH:mm",
                LLLL: "YYYY. [gada] D. MMMM, dddd, HH:mm"
            },
            calendar: {
                sameDay: "[Šodien pulksten] LT",
                nextDay: "[Rīt pulksten] LT",
                nextWeek: "dddd [pulksten] LT",
                lastDay: "[Vakar pulksten] LT",
                lastWeek: "[Pagājušā] dddd [pulksten] LT",
                sameElse: "L"
            },
            relativeTime: {
                future: "pēc %s",
                past: "pirms %s",
                s: td,
                m: sd,
                mm: rd,
                h: sd,
                hh: rd,
                d: sd,
                dd: rd,
                M: sd,
                MM: rd,
                y: sd,
                yy: rd
            },
            ordinalParse: /\d{1,2}\./,
            ordinal: "%d.",
            week: {
                dow: 1,
                doy: 4
            }
        }), {
            words: {
                m: ["jedan minut", "jednog minuta"],
                mm: ["minut", "minuta", "minuta"],
                h: ["jedan sat", "jednog sata"],
                hh: ["sat", "sata", "sati"],
                dd: ["dan", "dana", "dana"],
                MM: ["mjesec", "mjeseca", "mjeseci"],
                yy: ["godina", "godine", "godina"]
            },
            correctGrammaticalCase: function(a, b) {
                return 1 === a ? b[0] : a >= 2 && 4 >= a ? b[1] : b[2]
            },
            translate: function(a, b, c) {
                var d = Vf.words[c];
                return 1 === c.length ? b ? d[0] : d[1] : a + " " + Vf.correctGrammaticalCase(a, d)
            }
        }),
        Wf = (rf.defineLocale("me", {
            months: ["januar", "februar", "mart", "april", "maj", "jun", "jul", "avgust", "septembar", "oktobar", "novembar", "decembar"],
            monthsShort: ["jan.", "feb.", "mar.", "apr.", "maj", "jun", "jul", "avg.", "sep.", "okt.", "nov.", "dec."],
            weekdays: ["nedjelja", "ponedjeljak", "utorak", "srijeda", "četvrtak", "petak", "subota"],
            weekdaysShort: ["ned.", "pon.", "uto.", "sri.", "čet.", "pet.", "sub."],
            weekdaysMin: ["ne", "po", "ut", "sr", "če", "pe", "su"],
            longDateFormat: {
                LT: "H:mm",
                LTS: "H:mm:ss",
                L: "DD. MM. YYYY",
                LL: "D. MMMM YYYY",
                LLL: "D. MMMM YYYY H:mm",
                LLLL: "dddd, D. MMMM YYYY H:mm"
            },
            calendar: {
                sameDay: "[danas u] LT",
                nextDay: "[sjutra u] LT",
                nextWeek: function() {
                    switch (this.day()) {
                        case 0:
                            return "[u] [nedjelju] [u] LT";
                        case 3:
                            return "[u] [srijedu] [u] LT";
                        case 6:
                            return "[u] [subotu] [u] LT";
                        case 1:
                        case 2:
                        case 4:
                        case 5:
                            return "[u] dddd [u] LT"
                    }
                },
                lastDay: "[juče u] LT",
                lastWeek: function() {
                    return ["[prošle] [nedjelje] [u] LT", "[prošlog] [ponedjeljka] [u] LT", "[prošlog] [utorka] [u] LT", "[prošle] [srijede] [u] LT", "[prošlog] [četvrtka] [u] LT", "[prošlog] [petka] [u] LT", "[prošle] [subote] [u] LT"][this.day()]
                },
                sameElse: "L"
            },
            relativeTime: {
                future: "za %s",
                past: "prije %s",
                s: "nekoliko sekundi",
                m: Vf.translate,
                mm: Vf.translate,
                h: Vf.translate,
                hh: Vf.translate,
                d: "dan",
                dd: Vf.translate,
                M: "mjesec",
                MM: Vf.translate,
                y: "godinu",
                yy: Vf.translate
            },
            ordinalParse: /\d{1,2}\./,
            ordinal: "%d.",
            week: {
                dow: 1,
                doy: 7
            }
        }), rf.defineLocale("mk", {
            months: "јануари_февруари_март_април_мај_јуни_јули_август_септември_октомври_ноември_декември".split("_"),
            monthsShort: "јан_фев_мар_апр_мај_јун_јул_авг_сеп_окт_ное_дек".split("_"),
            weekdays: "недела_понеделник_вторник_среда_четврток_петок_сабота".split("_"),
            weekdaysShort: "нед_пон_вто_сре_чет_пет_саб".split("_"),
            weekdaysMin: "нe_пo_вт_ср_че_пе_сa".split("_"),
            longDateFormat: {
                LT: "H:mm",
                LTS: "H:mm:ss",
                L: "D.MM.YYYY",
                LL: "D MMMM YYYY",
                LLL: "D MMMM YYYY H:mm",
                LLLL: "dddd, D MMMM YYYY H:mm"
            },
            calendar: {
                sameDay: "[Денес во] LT",
                nextDay: "[Утре во] LT",
                nextWeek: "dddd [во] LT",
                lastDay: "[Вчера во] LT",
                lastWeek: function() {
                    switch (this.day()) {
                        case 0:
                        case 3:
                        case 6:
                            return "[Во изминатата] dddd [во] LT";
                        case 1:
                        case 2:
                        case 4:
                        case 5:
                            return "[Во изминатиот] dddd [во] LT"
                    }
                },
                sameElse: "L"
            },
            relativeTime: {
                future: "после %s",
                past: "пред %s",
                s: "неколку секунди",
                m: "минута",
                mm: "%d минути",
                h: "час",
                hh: "%d часа",
                d: "ден",
                dd: "%d дена",
                M: "месец",
                MM: "%d месеци",
                y: "година",
                yy: "%d години"
            },
            ordinalParse: /\d{1,2}-(ев|ен|ти|ви|ри|ми)/,
            ordinal: function(a) {
                var b = a % 10,
                    c = a % 100;
                return 0 === a ? a + "-ев" : 0 === c ? a + "-ен" : c > 10 && 20 > c ? a + "-ти" : 1 === b ? a + "-ви" : 2 === b ? a + "-ри" : 7 === b || 8 === b ? a + "-ми" : a + "-ти"
            },
            week: {
                dow: 1,
                doy: 7
            }
        }), rf.defineLocale("ml", {
            months: "ജനുവരി_ഫെബ്രുവരി_മാർച്ച്_ഏപ്രിൽ_മേയ്_ജൂൺ_ജൂലൈ_ഓഗസ്റ്റ്_സെപ്റ്റംബർ_ഒക്ടോബർ_നവംബർ_ഡിസംബർ".split("_"),
            monthsShort: "ജനു._ഫെബ്രു._മാർ._ഏപ്രി._മേയ്_ജൂൺ_ജൂലൈ._ഓഗ._സെപ്റ്റ._ഒക്ടോ._നവം._ഡിസം.".split("_"),
            weekdays: "ഞായറാഴ്ച_തിങ്കളാഴ്ച_ചൊവ്വാഴ്ച_ബുധനാഴ്ച_വ്യാഴാഴ്ച_വെള്ളിയാഴ്ച_ശനിയാഴ്ച".split("_"),
            weekdaysShort: "ഞായർ_തിങ്കൾ_ചൊവ്വ_ബുധൻ_വ്യാഴം_വെള്ളി_ശനി".split("_"),
            weekdaysMin: "ഞാ_തി_ചൊ_ബു_വ്യാ_വെ_ശ".split("_"),
            longDateFormat: {
                LT: "A h:mm -നു",
                LTS: "A h:mm:ss -നു",
                L: "DD/MM/YYYY",
                LL: "D MMMM YYYY",
                LLL: "D MMMM YYYY, A h:mm -നു",
                LLLL: "dddd, D MMMM YYYY, A h:mm -നു"
            },
            calendar: {
                sameDay: "[ഇന്ന്] LT",
                nextDay: "[നാളെ] LT",
                nextWeek: "dddd, LT",
                lastDay: "[ഇന്നലെ] LT",
                lastWeek: "[കഴിഞ്ഞ] dddd, LT",
                sameElse: "L"
            },
            relativeTime: {
                future: "%s കഴിഞ്ഞ്",
                past: "%s മുൻപ്",
                s: "അൽപ നിമിഷങ്ങൾ",
                m: "ഒരു മിനിറ്റ്",
                mm: "%d മിനിറ്റ്",
                h: "ഒരു മണിക്കൂർ",
                hh: "%d മണിക്കൂർ",
                d: "ഒരു ദിവസം",
                dd: "%d ദിവസം",
                M: "ഒരു മാസം",
                MM: "%d മാസം",
                y: "ഒരു വർഷം",
                yy: "%d വർഷം"
            },
            meridiemParse: /രാത്രി|രാവിലെ|ഉച്ച കഴിഞ്ഞ്|വൈകുന്നേരം|രാത്രി/i,
            isPM: function(a) {
                return /^(ഉച്ച കഴിഞ്ഞ്|വൈകുന്നേരം|രാത്രി)$/.test(a)
            },
            meridiem: function(a, b, c) {
                return 4 > a ? "രാത്രി" : 12 > a ? "രാവിലെ" : 17 > a ? "ഉച്ച കഴിഞ്ഞ്" : 20 > a ? "വൈകുന്നേരം" : "രാത്രി"
            }
        }), {
            1: "१",
            2: "२",
            3: "३",
            4: "४",
            5: "५",
            6: "६",
            7: "७",
            8: "८",
            9: "९",
            0: "०"
        }),
        Xf = {
            "१": "1",
            "२": "2",
            "३": "3",
            "४": "4",
            "५": "5",
            "६": "6",
            "७": "7",
            "८": "8",
            "९": "9",
            "०": "0"
        },
        Yf = (rf.defineLocale("mr", {
            months: "जानेवारी_फेब्रुवारी_मार्च_एप्रिल_मे_जून_जुलै_ऑगस्ट_सप्टेंबर_ऑक्टोबर_नोव्हेंबर_डिसेंबर".split("_"),
            monthsShort: "जाने._फेब्रु._मार्च._एप्रि._मे._जून._जुलै._ऑग._सप्टें._ऑक्टो._नोव्हें._डिसें.".split("_"),
            weekdays: "रविवार_सोमवार_मंगळवार_बुधवार_गुरूवार_शुक्रवार_शनिवार".split("_"),
            weekdaysShort: "रवि_सोम_मंगळ_बुध_गुरू_शुक्र_शनि".split("_"),
            weekdaysMin: "र_सो_मं_बु_गु_शु_श".split("_"),
            longDateFormat: {
                LT: "A h:mm वाजता",
                LTS: "A h:mm:ss वाजता",
                L: "DD/MM/YYYY",
                LL: "D MMMM YYYY",
                LLL: "D MMMM YYYY, A h:mm वाजता",
                LLLL: "dddd, D MMMM YYYY, A h:mm वाजता"
            },
            calendar: {
                sameDay: "[आज] LT",
                nextDay: "[उद्या] LT",
                nextWeek: "dddd, LT",
                lastDay: "[काल] LT",
                lastWeek: "[मागील] dddd, LT",
                sameElse: "L"
            },
            relativeTime: {
                future: "%s नंतर",
                past: "%s पूर्वी",
                s: "सेकंद",
                m: "एक मिनिट",
                mm: "%d मिनिटे",
                h: "एक तास",
                hh: "%d तास",
                d: "एक दिवस",
                dd: "%d दिवस",
                M: "एक महिना",
                MM: "%d महिने",
                y: "एक वर्ष",
                yy: "%d वर्षे"
            },
            preparse: function(a) {
                return a.replace(/[१२३४५६७८९०]/g, function(a) {
                    return Xf[a]
                })
            },
            postformat: function(a) {
                return a.replace(/\d/g, function(a) {
                    return Wf[a]
                })
            },
            meridiemParse: /रात्री|सकाळी|दुपारी|सायंकाळी/,
            meridiemHour: function(a, b) {
                return 12 === a && (a = 0), "रात्री" === b ? 4 > a ? a : a + 12 : "सकाळी" === b ? a : "दुपारी" === b ? a >= 10 ? a : a + 12 : "सायंकाळी" === b ? a + 12 : void 0
            },
            meridiem: function(a, b, c) {
                return 4 > a ? "रात्री" : 10 > a ? "सकाळी" : 17 > a ? "दुपारी" : 20 > a ? "सायंकाळी" : "रात्री"
            },
            week: {
                dow: 0,
                doy: 6
            }
        }), rf.defineLocale("ms-my", {
            months: "Januari_Februari_Mac_April_Mei_Jun_Julai_Ogos_September_Oktober_November_Disember".split("_"),
            monthsShort: "Jan_Feb_Mac_Apr_Mei_Jun_Jul_Ogs_Sep_Okt_Nov_Dis".split("_"),
            weekdays: "Ahad_Isnin_Selasa_Rabu_Khamis_Jumaat_Sabtu".split("_"),
            weekdaysShort: "Ahd_Isn_Sel_Rab_Kha_Jum_Sab".split("_"),
            weekdaysMin: "Ah_Is_Sl_Rb_Km_Jm_Sb".split("_"),
            longDateFormat: {
                LT: "HH.mm",
                LTS: "HH.mm.ss",
                L: "DD/MM/YYYY",
                LL: "D MMMM YYYY",
                LLL: "D MMMM YYYY [pukul] HH.mm",
                LLLL: "dddd, D MMMM YYYY [pukul] HH.mm"
            },
            meridiemParse: /pagi|tengahari|petang|malam/,
            meridiemHour: function(a, b) {
                return 12 === a && (a = 0), "pagi" === b ? a : "tengahari" === b ? a >= 11 ? a : a + 12 : "petang" === b || "malam" === b ? a + 12 : void 0
            },
            meridiem: function(a, b, c) {
                return 11 > a ? "pagi" : 15 > a ? "tengahari" : 19 > a ? "petang" : "malam"
            },
            calendar: {
                sameDay: "[Hari ini pukul] LT",
                nextDay: "[Esok pukul] LT",
                nextWeek: "dddd [pukul] LT",
                lastDay: "[Kelmarin pukul] LT",
                lastWeek: "dddd [lepas pukul] LT",
                sameElse: "L"
            },
            relativeTime: {
                future: "dalam %s",
                past: "%s yang lepas",
                s: "beberapa saat",
                m: "seminit",
                mm: "%d minit",
                h: "sejam",
                hh: "%d jam",
                d: "sehari",
                dd: "%d hari",
                M: "sebulan",
                MM: "%d bulan",
                y: "setahun",
                yy: "%d tahun"
            },
            week: {
                dow: 1,
                doy: 7
            }
        }), rf.defineLocale("ms", {
            months: "Januari_Februari_Mac_April_Mei_Jun_Julai_Ogos_September_Oktober_November_Disember".split("_"),
            monthsShort: "Jan_Feb_Mac_Apr_Mei_Jun_Jul_Ogs_Sep_Okt_Nov_Dis".split("_"),
            weekdays: "Ahad_Isnin_Selasa_Rabu_Khamis_Jumaat_Sabtu".split("_"),
            weekdaysShort: "Ahd_Isn_Sel_Rab_Kha_Jum_Sab".split("_"),
            weekdaysMin: "Ah_Is_Sl_Rb_Km_Jm_Sb".split("_"),
            longDateFormat: {
                LT: "HH.mm",
                LTS: "HH.mm.ss",
                L: "DD/MM/YYYY",
                LL: "D MMMM YYYY",
                LLL: "D MMMM YYYY [pukul] HH.mm",
                LLLL: "dddd, D MMMM YYYY [pukul] HH.mm"
            },
            meridiemParse: /pagi|tengahari|petang|malam/,
            meridiemHour: function(a, b) {
                return 12 === a && (a = 0), "pagi" === b ? a : "tengahari" === b ? a >= 11 ? a : a + 12 : "petang" === b || "malam" === b ? a + 12 : void 0
            },
            meridiem: function(a, b, c) {
                return 11 > a ? "pagi" : 15 > a ? "tengahari" : 19 > a ? "petang" : "malam"
            },
            calendar: {
                sameDay: "[Hari ini pukul] LT",
                nextDay: "[Esok pukul] LT",
                nextWeek: "dddd [pukul] LT",
                lastDay: "[Kelmarin pukul] LT",
                lastWeek: "dddd [lepas pukul] LT",
                sameElse: "L"
            },
            relativeTime: {
                future: "dalam %s",
                past: "%s yang lepas",
                s: "beberapa saat",
                m: "seminit",
                mm: "%d minit",
                h: "sejam",
                hh: "%d jam",
                d: "sehari",
                dd: "%d hari",
                M: "sebulan",
                MM: "%d bulan",
                y: "setahun",
                yy: "%d tahun"
            },
            week: {
                dow: 1,
                doy: 7
            }
        }), {
            1: "၁",
            2: "၂",
            3: "၃",
            4: "၄",
            5: "၅",
            6: "၆",
            7: "၇",
            8: "၈",
            9: "၉",
            0: "၀"
        }),
        Zf = {
            "၁": "1",
            "၂": "2",
            "၃": "3",
            "၄": "4",
            "၅": "5",
            "၆": "6",
            "၇": "7",
            "၈": "8",
            "၉": "9",
            "၀": "0"
        },
        $f = (rf.defineLocale("my", {
            months: "ဇန်နဝါရီ_ဖေဖော်ဝါရီ_မတ်_ဧပြီ_မေ_ဇွန်_ဇူလိုင်_သြဂုတ်_စက်တင်ဘာ_အောက်တိုဘာ_နိုဝင်ဘာ_ဒီဇင်ဘာ".split("_"),
            monthsShort: "ဇန်_ဖေ_မတ်_ပြီ_မေ_ဇွန်_လိုင်_သြ_စက်_အောက်_နို_ဒီ".split("_"),
            weekdays: "တနင်္ဂနွေ_တနင်္လာ_အင်္ဂါ_ဗုဒ္ဓဟူး_ကြာသပတေး_သောကြာ_စနေ".split("_"),
            weekdaysShort: "နွေ_လာ_ဂါ_ဟူး_ကြာ_သော_နေ".split("_"),
            weekdaysMin: "နွေ_လာ_ဂါ_ဟူး_ကြာ_သော_နေ".split("_"),
            longDateFormat: {
                LT: "HH:mm",
                LTS: "HH:mm:ss",
                L: "DD/MM/YYYY",
                LL: "D MMMM YYYY",
                LLL: "D MMMM YYYY HH:mm",
                LLLL: "dddd D MMMM YYYY HH:mm"
            },
            calendar: {
                sameDay: "[ယနေ.] LT [မှာ]",
                nextDay: "[မနက်ဖြန်] LT [မှာ]",
                nextWeek: "dddd LT [မှာ]",
                lastDay: "[မနေ.က] LT [မှာ]",
                lastWeek: "[ပြီးခဲ့သော] dddd LT [မှာ]",
                sameElse: "L"
            },
            relativeTime: {
                future: "လာမည့် %s မှာ",
                past: "လွန်ခဲ့သော %s က",
                s: "စက္ကန်.အနည်းငယ်",
                m: "တစ်မိနစ်",
                mm: "%d မိနစ်",
                h: "တစ်နာရီ",
                hh: "%d နာရီ",
                d: "တစ်ရက်",
                dd: "%d ရက်",
                M: "တစ်လ",
                MM: "%d လ",
                y: "တစ်နှစ်",
                yy: "%d နှစ်"
            },
            preparse: function(a) {
                return a.replace(/[၁၂၃၄၅၆၇၈၉၀]/g, function(a) {
                    return Zf[a]
                })
            },
            postformat: function(a) {
                return a.replace(/\d/g, function(a) {
                    return Yf[a]
                })
            },
            week: {
                dow: 1,
                doy: 4
            }
        }), rf.defineLocale("nb", {
            months: "januar_februar_mars_april_mai_juni_juli_august_september_oktober_november_desember".split("_"),
            monthsShort: "jan_feb_mar_apr_mai_jun_jul_aug_sep_okt_nov_des".split("_"),
            weekdays: "søndag_mandag_tirsdag_onsdag_torsdag_fredag_lørdag".split("_"),
            weekdaysShort: "søn_man_tirs_ons_tors_fre_lør".split("_"),
            weekdaysMin: "sø_ma_ti_on_to_fr_lø".split("_"),
            longDateFormat: {
                LT: "H.mm",
                LTS: "H.mm.ss",
                L: "DD.MM.YYYY",
                LL: "D. MMMM YYYY",
                LLL: "D. MMMM YYYY [kl.] H.mm",
                LLLL: "dddd D. MMMM YYYY [kl.] H.mm"
            },
            calendar: {
                sameDay: "[i dag kl.] LT",
                nextDay: "[i morgen kl.] LT",
                nextWeek: "dddd [kl.] LT",
                lastDay: "[i går kl.] LT",
                lastWeek: "[forrige] dddd [kl.] LT",
                sameElse: "L"
            },
            relativeTime: {
                future: "om %s",
                past: "for %s siden",
                s: "noen sekunder",
                m: "ett minutt",
                mm: "%d minutter",
                h: "en time",
                hh: "%d timer",
                d: "en dag",
                dd: "%d dager",
                M: "en måned",
                MM: "%d måneder",
                y: "ett år",
                yy: "%d år"
            },
            ordinalParse: /\d{1,2}\./,
            ordinal: "%d.",
            week: {
                dow: 1,
                doy: 4
            }
        }), {
            1: "१",
            2: "२",
            3: "३",
            4: "४",
            5: "५",
            6: "६",
            7: "७",
            8: "८",
            9: "९",
            0: "०"
        }),
        _f = {
            "१": "1",
            "२": "2",
            "३": "3",
            "४": "4",
            "५": "5",
            "६": "6",
            "७": "7",
            "८": "8",
            "९": "9",
            "०": "0"
        },
        ag = (rf.defineLocale("ne", {
            months: "जनवरी_फेब्रुवरी_मार्च_अप्रिल_मई_जुन_जुलाई_अगष्ट_सेप्टेम्बर_अक्टोबर_नोभेम्बर_डिसेम्बर".split("_"),
            monthsShort: "जन._फेब्रु._मार्च_अप्रि._मई_जुन_जुलाई._अग._सेप्ट._अक्टो._नोभे._डिसे.".split("_"),
            weekdays: "आइतबार_सोमबार_मङ्गलबार_बुधबार_बिहिबार_शुक्रबार_शनिबार".split("_"),
            weekdaysShort: "आइत._सोम._मङ्गल._बुध._बिहि._शुक्र._शनि.".split("_"),
            weekdaysMin: "आइ._सो._मङ्_बु._बि._शु._श.".split("_"),
            longDateFormat: {
                LT: "Aको h:mm बजे",
                LTS: "Aको h:mm:ss बजे",
                L: "DD/MM/YYYY",
                LL: "D MMMM YYYY",
                LLL: "D MMMM YYYY, Aको h:mm बजे",
                LLLL: "dddd, D MMMM YYYY, Aको h:mm बजे"
            },
            preparse: function(a) {
                return a.replace(/[१२३४५६७८९०]/g, function(a) {
                    return _f[a]
                })
            },
            postformat: function(a) {
                return a.replace(/\d/g, function(a) {
                    return $f[a]
                })
            },
            meridiemParse: /राती|बिहान|दिउँसो|बेलुका|साँझ|राती/,
            meridiemHour: function(a, b) {
                return 12 === a && (a = 0), "राती" === b ? 3 > a ? a : a + 12 : "बिहान" === b ? a : "दिउँसो" === b ? a >= 10 ? a : a + 12 : "बेलुका" === b || "साँझ" === b ? a + 12 : void 0
            },
            meridiem: function(a, b, c) {
                return 3 > a ? "राती" : 10 > a ? "बिहान" : 15 > a ? "दिउँसो" : 18 > a ? "बेलुका" : 20 > a ? "साँझ" : "राती"
            },
            calendar: {
                sameDay: "[आज] LT",
                nextDay: "[भोली] LT",
                nextWeek: "[आउँदो] dddd[,] LT",
                lastDay: "[हिजो] LT",
                lastWeek: "[गएको] dddd[,] LT",
                sameElse: "L"
            },
            relativeTime: {
                future: "%sमा",
                past: "%s अगाडी",
                s: "केही समय",
                m: "एक मिनेट",
                mm: "%d मिनेट",
                h: "एक घण्टा",
                hh: "%d घण्टा",
                d: "एक दिन",
                dd: "%d दिन",
                M: "एक महिना",
                MM: "%d महिना",
                y: "एक बर्ष",
                yy: "%d बर्ष"
            },
            week: {
                dow: 1,
                doy: 7
            }
        }), "jan._feb._mrt._apr._mei_jun._jul._aug._sep._okt._nov._dec.".split("_")),
        bg = "jan_feb_mrt_apr_mei_jun_jul_aug_sep_okt_nov_dec".split("_"),
        cg = (rf.defineLocale("nl", {
            months: "januari_februari_maart_april_mei_juni_juli_augustus_september_oktober_november_december".split("_"),
            monthsShort: function(a, b) {
                return /-MMM-/.test(b) ? bg[a.month()] : ag[a.month()]
            },
            weekdays: "zondag_maandag_dinsdag_woensdag_donderdag_vrijdag_zaterdag".split("_"),
            weekdaysShort: "zo._ma._di._wo._do._vr._za.".split("_"),
            weekdaysMin: "Zo_Ma_Di_Wo_Do_Vr_Za".split("_"),
            longDateFormat: {
                LT: "HH:mm",
                LTS: "HH:mm:ss",
                L: "DD-MM-YYYY",
                LL: "D MMMM YYYY",
                LLL: "D MMMM YYYY HH:mm",
                LLLL: "dddd D MMMM YYYY HH:mm"
            },
            calendar: {
                sameDay: "[vandaag om] LT",
                nextDay: "[morgen om] LT",
                nextWeek: "dddd [om] LT",
                lastDay: "[gisteren om] LT",
                lastWeek: "[afgelopen] dddd [om] LT",
                sameElse: "L"
            },
            relativeTime: {
                future: "over %s",
                past: "%s geleden",
                s: "een paar seconden",
                m: "één minuut",
                mm: "%d minuten",
                h: "één uur",
                hh: "%d uur",
                d: "één dag",
                dd: "%d dagen",
                M: "één maand",
                MM: "%d maanden",
                y: "één jaar",
                yy: "%d jaar"
            },
            ordinalParse: /\d{1,2}(ste|de)/,
            ordinal: function(a) {
                return a + (1 === a || 8 === a || a >= 20 ? "ste" : "de")
            },
            week: {
                dow: 1,
                doy: 4
            }
        }), rf.defineLocale("nn", {
            months: "januar_februar_mars_april_mai_juni_juli_august_september_oktober_november_desember".split("_"),
            monthsShort: "jan_feb_mar_apr_mai_jun_jul_aug_sep_okt_nov_des".split("_"),
            weekdays: "sundag_måndag_tysdag_onsdag_torsdag_fredag_laurdag".split("_"),
            weekdaysShort: "sun_mån_tys_ons_tor_fre_lau".split("_"),
            weekdaysMin: "su_må_ty_on_to_fr_lø".split("_"),
            longDateFormat: {
                LT: "HH:mm",
                LTS: "HH:mm:ss",
                L: "DD.MM.YYYY",
                LL: "D MMMM YYYY",
                LLL: "D MMMM YYYY HH:mm",
                LLLL: "dddd D MMMM YYYY HH:mm"
            },
            calendar: {
                sameDay: "[I dag klokka] LT",
                nextDay: "[I morgon klokka] LT",
                nextWeek: "dddd [klokka] LT",
                lastDay: "[I går klokka] LT",
                lastWeek: "[Føregåande] dddd [klokka] LT",
                sameElse: "L"
            },
            relativeTime: {
                future: "om %s",
                past: "for %s sidan",
                s: "nokre sekund",
                m: "eit minutt",
                mm: "%d minutt",
                h: "ein time",
                hh: "%d timar",
                d: "ein dag",
                dd: "%d dagar",
                M: "ein månad",
                MM: "%d månader",
                y: "eit år",
                yy: "%d år"
            },
            ordinalParse: /\d{1,2}\./,
            ordinal: "%d.",
            week: {
                dow: 1,
                doy: 4
            }
        }), "styczeń_luty_marzec_kwiecień_maj_czerwiec_lipiec_sierpień_wrzesień_październik_listopad_grudzień".split("_")),
        dg = "stycznia_lutego_marca_kwietnia_maja_czerwca_lipca_sierpnia_września_października_listopada_grudnia".split("_"),
        eg = (rf.defineLocale("pl", {
            months: function(a, b) {
                return "" === b ? "(" + dg[a.month()] + "|" + cg[a.month()] + ")" : /D MMMM/.test(b) ? dg[a.month()] : cg[a.month()]
            },
            monthsShort: "sty_lut_mar_kwi_maj_cze_lip_sie_wrz_paź_lis_gru".split("_"),
            weekdays: "niedziela_poniedziałek_wtorek_środa_czwartek_piątek_sobota".split("_"),
            weekdaysShort: "nie_pon_wt_śr_czw_pt_sb".split("_"),
            weekdaysMin: "N_Pn_Wt_Śr_Cz_Pt_So".split("_"),
            longDateFormat: {
                LT: "HH:mm",
                LTS: "HH:mm:ss",
                L: "DD.MM.YYYY",
                LL: "D MMMM YYYY",
                LLL: "D MMMM YYYY HH:mm",
                LLLL: "dddd, D MMMM YYYY HH:mm"
            },
            calendar: {
                sameDay: "[Dziś o] LT",
                nextDay: "[Jutro o] LT",
                nextWeek: "[W] dddd [o] LT",
                lastDay: "[Wczoraj o] LT",
                lastWeek: function() {
                    switch (this.day()) {
                        case 0:
                            return "[W zeszłą niedzielę o] LT";
                        case 3:
                            return "[W zeszłą środę o] LT";
                        case 6:
                            return "[W zeszłą sobotę o] LT";
                        default:
                            return "[W zeszły] dddd [o] LT"
                    }
                },
                sameElse: "L"
            },
            relativeTime: {
                future: "za %s",
                past: "%s temu",
                s: "kilka sekund",
                m: vd,
                mm: vd,
                h: vd,
                hh: vd,
                d: "1 dzień",
                dd: "%d dni",
                M: "miesiąc",
                MM: vd,
                y: "rok",
                yy: vd
            },
            ordinalParse: /\d{1,2}\./,
            ordinal: "%d.",
            week: {
                dow: 1,
                doy: 4
            }
        }), rf.defineLocale("pt-br", {
            months: "Janeiro_Fevereiro_Março_Abril_Maio_Junho_Julho_Agosto_Setembro_Outubro_Novembro_Dezembro".split("_"),
            monthsShort: "Jan_Fev_Mar_Abr_Mai_Jun_Jul_Ago_Set_Out_Nov_Dez".split("_"),
            weekdays: "Domingo_Segunda-Feira_Terça-Feira_Quarta-Feira_Quinta-Feira_Sexta-Feira_Sábado".split("_"),
            weekdaysShort: "Dom_Seg_Ter_Qua_Qui_Sex_Sáb".split("_"),
            weekdaysMin: "Dom_2ª_3ª_4ª_5ª_6ª_Sáb".split("_"),
            longDateFormat: {
                LT: "HH:mm",
                LTS: "HH:mm:ss",
                L: "DD/MM/YYYY",
                LL: "D [de] MMMM [de] YYYY",
                LLL: "D [de] MMMM [de] YYYY [às] HH:mm",
                LLLL: "dddd, D [de] MMMM [de] YYYY [às] HH:mm"
            },
            calendar: {
                sameDay: "[Hoje às] LT",
                nextDay: "[Amanhã às] LT",
                nextWeek: "dddd [às] LT",
                lastDay: "[Ontem às] LT",
                lastWeek: function() {
                    return 0 === this.day() || 6 === this.day() ? "[Último] dddd [às] LT" : "[Última] dddd [às] LT"
                },
                sameElse: "L"
            },
            relativeTime: {
                future: "em %s",
                past: "%s atrás",
                s: "poucos segundos",
                m: "um minuto",
                mm: "%d minutos",
                h: "uma hora",
                hh: "%d horas",
                d: "um dia",
                dd: "%d dias",
                M: "um mês",
                MM: "%d meses",
                y: "um ano",
                yy: "%d anos"
            },
            ordinalParse: /\d{1,2}º/,
            ordinal: "%dº"
        }), rf.defineLocale("pt", {
            months: "Janeiro_Fevereiro_Março_Abril_Maio_Junho_Julho_Agosto_Setembro_Outubro_Novembro_Dezembro".split("_"),
            monthsShort: "Jan_Fev_Mar_Abr_Mai_Jun_Jul_Ago_Set_Out_Nov_Dez".split("_"),
            weekdays: "Domingo_Segunda-Feira_Terça-Feira_Quarta-Feira_Quinta-Feira_Sexta-Feira_Sábado".split("_"),
            weekdaysShort: "Dom_Seg_Ter_Qua_Qui_Sex_Sáb".split("_"),
            weekdaysMin: "Dom_2ª_3ª_4ª_5ª_6ª_Sáb".split("_"),
            longDateFormat: {
                LT: "HH:mm",
                LTS: "HH:mm:ss",
                L: "DD/MM/YYYY",
                LL: "D [de] MMMM [de] YYYY",
                LLL: "D [de] MMMM [de] YYYY HH:mm",
                LLLL: "dddd, D [de] MMMM [de] YYYY HH:mm"
            },
            calendar: {
                sameDay: "[Hoje às] LT",
                nextDay: "[Amanhã às] LT",
                nextWeek: "dddd [às] LT",
                lastDay: "[Ontem às] LT",
                lastWeek: function() {
                    return 0 === this.day() || 6 === this.day() ? "[Último] dddd [às] LT" : "[Última] dddd [às] LT"
                },
                sameElse: "L"
            },
            relativeTime: {
                future: "em %s",
                past: "há %s",
                s: "segundos",
                m: "um minuto",
                mm: "%d minutos",
                h: "uma hora",
                hh: "%d horas",
                d: "um dia",
                dd: "%d dias",
                M: "um mês",
                MM: "%d meses",
                y: "um ano",
                yy: "%d anos"
            },
            ordinalParse: /\d{1,2}º/,
            ordinal: "%dº",
            week: {
                dow: 1,
                doy: 4
            }
        }), rf.defineLocale("ro", {
            months: "ianuarie_februarie_martie_aprilie_mai_iunie_iulie_august_septembrie_octombrie_noiembrie_decembrie".split("_"),
            monthsShort: "ian._febr._mart._apr._mai_iun._iul._aug._sept._oct._nov._dec.".split("_"),
            weekdays: "duminică_luni_marți_miercuri_joi_vineri_sâmbătă".split("_"),
            weekdaysShort: "Dum_Lun_Mar_Mie_Joi_Vin_Sâm".split("_"),
            weekdaysMin: "Du_Lu_Ma_Mi_Jo_Vi_Sâ".split("_"),
            longDateFormat: {
                LT: "H:mm",
                LTS: "H:mm:ss",
                L: "DD.MM.YYYY",
                LL: "D MMMM YYYY",
                LLL: "D MMMM YYYY H:mm",
                LLLL: "dddd, D MMMM YYYY H:mm"
            },
            calendar: {
                sameDay: "[azi la] LT",
                nextDay: "[mâine la] LT",
                nextWeek: "dddd [la] LT",
                lastDay: "[ieri la] LT",
                lastWeek: "[fosta] dddd [la] LT",
                sameElse: "L"
            },
            relativeTime: {
                future: "peste %s",
                past: "%s în urmă",
                s: "câteva secunde",
                m: "un minut",
                mm: wd,
                h: "o oră",
                hh: wd,
                d: "o zi",
                dd: wd,
                M: "o lună",
                MM: wd,
                y: "un an",
                yy: wd
            },
            week: {
                dow: 1,
                doy: 7
            }
        }), rf.defineLocale("ru", {
            months: zd,
            monthsShort: Ad,
            weekdays: Bd,
            weekdaysShort: "вс_пн_вт_ср_чт_пт_сб".split("_"),
            weekdaysMin: "вс_пн_вт_ср_чт_пт_сб".split("_"),
            monthsParse: [/^янв/i, /^фев/i, /^мар/i, /^апр/i, /^ма[й|я]/i, /^июн/i, /^июл/i, /^авг/i, /^сен/i, /^окт/i, /^ноя/i, /^дек/i],
            longDateFormat: {
                LT: "HH:mm",
                LTS: "HH:mm:ss",
                L: "DD.MM.YYYY",
                LL: "D MMMM YYYY г.",
                LLL: "D MMMM YYYY г., HH:mm",
                LLLL: "dddd, D MMMM YYYY г., HH:mm"
            },
            calendar: {
                sameDay: "[Сегодня в] LT",
                nextDay: "[Завтра в] LT",
                lastDay: "[Вчера в] LT",
                nextWeek: function() {
                    return 2 === this.day() ? "[Во] dddd [в] LT" : "[В] dddd [в] LT"
                },
                lastWeek: function(a) {
                    if (a.week() === this.week()) return 2 === this.day() ? "[Во] dddd [в] LT" : "[В] dddd [в] LT";
                    switch (this.day()) {
                        case 0:
                            return "[В прошлое] dddd [в] LT";
                        case 1:
                        case 2:
                        case 4:
                            return "[В прошлый] dddd [в] LT";
                        case 3:
                        case 5:
                        case 6:
                            return "[В прошлую] dddd [в] LT"
                    }
                },
                sameElse: "L"
            },
            relativeTime: {
                future: "через %s",
                past: "%s назад",
                s: "несколько секунд",
                m: yd,
                mm: yd,
                h: "час",
                hh: yd,
                d: "день",
                dd: yd,
                M: "месяц",
                MM: yd,
                y: "год",
                yy: yd
            },
            meridiemParse: /ночи|утра|дня|вечера/i,
            isPM: function(a) {
                return /^(дня|вечера)$/.test(a)
            },
            meridiem: function(a, b, c) {
                return 4 > a ? "ночи" : 12 > a ? "утра" : 17 > a ? "дня" : "вечера"
            },
            ordinalParse: /\d{1,2}-(й|го|я)/,
            ordinal: function(a, b) {
                switch (b) {
                    case "M":
                    case "d":
                    case "DDD":
                        return a + "-й";
                    case "D":
                        return a + "-го";
                    case "w":
                    case "W":
                        return a + "-я";
                    default:
                        return a
                }
            },
            week: {
                dow: 1,
                doy: 7
            }
        }), rf.defineLocale("si", {
            months: "ජනවාරි_පෙබරවාරි_මාර්තු_අප්‍රේල්_මැයි_ජූනි_ජූලි_අගෝස්තු_සැප්තැම්බර්_ඔක්තෝබර්_නොවැම්බර්_දෙසැම්බර්".split("_"),
            monthsShort: "ජන_පෙබ_මාර්_අප්_මැයි_ජූනි_ජූලි_අගෝ_සැප්_ඔක්_නොවැ_දෙසැ".split("_"),
            weekdays: "ඉරිදා_සඳුදා_අඟහරුවාදා_බදාදා_බ්‍රහස්පතින්දා_සිකුරාදා_සෙනසුරාදා".split("_"),
            weekdaysShort: "ඉරි_සඳු_අඟ_බදා_බ්‍රහ_සිකු_සෙන".split("_"),
            weekdaysMin: "ඉ_ස_අ_බ_බ්‍ර_සි_සෙ".split("_"),
            longDateFormat: {
                LT: "a h:mm",
                LTS: "a h:mm:ss",
                L: "YYYY/MM/DD",
                LL: "YYYY MMMM D",
                LLL: "YYYY MMMM D, a h:mm",
                LLLL: "YYYY MMMM D [වැනි] dddd, a h:mm:ss"
            },
            calendar: {
                sameDay: "[අද] LT[ට]",
                nextDay: "[හෙට] LT[ට]",
                nextWeek: "dddd LT[ට]",
                lastDay: "[ඊයේ] LT[ට]",
                lastWeek: "[පසුගිය] dddd LT[ට]",
                sameElse: "L"
            },
            relativeTime: {
                future: "%sකින්",
                past: "%sකට පෙර",
                s: "තත්පර කිහිපය",
                m: "මිනිත්තුව",
                mm: "මිනිත්තු %d",
                h: "පැය",
                hh: "පැය %d",
                d: "දිනය",
                dd: "දින %d",
                M: "මාසය",
                MM: "මාස %d",
                y: "වසර",
                yy: "වසර %d"
            },
            ordinalParse: /\d{1,2} වැනි/,
            ordinal: function(a) {
                return a + " වැනි"
            },
            meridiem: function(a, b, c) {
                return a > 11 ? c ? "ප.ව." : "පස් වරු" : c ? "පෙ.ව." : "පෙර වරු"
            }
        }), "január_február_marec_apríl_máj_jún_júl_august_september_október_november_december".split("_")),
        fg = "jan_feb_mar_apr_máj_jún_júl_aug_sep_okt_nov_dec".split("_"),
        gg = (rf.defineLocale("sk", {
            months: eg,
            monthsShort: fg,
            monthsParse: function(a, b) {
                var c, d = [];
                for (c = 0; 12 > c; c++) d[c] = new RegExp("^" + a[c] + "$|^" + b[c] + "$", "i");
                return d
            }(eg, fg),
            weekdays: "nedeľa_pondelok_utorok_streda_štvrtok_piatok_sobota".split("_"),
            weekdaysShort: "ne_po_ut_st_št_pi_so".split("_"),
            weekdaysMin: "ne_po_ut_st_št_pi_so".split("_"),
            longDateFormat: {
                LT: "H:mm",
                LTS: "H:mm:ss",
                L: "DD.MM.YYYY",
                LL: "D. MMMM YYYY",
                LLL: "D. MMMM YYYY H:mm",
                LLLL: "dddd D. MMMM YYYY H:mm"
            },
            calendar: {
                sameDay: "[dnes o] LT",
                nextDay: "[zajtra o] LT",
                nextWeek: function() {
                    switch (this.day()) {
                        case 0:
                            return "[v nedeľu o] LT";
                        case 1:
                        case 2:
                            return "[v] dddd [o] LT";
                        case 3:
                            return "[v stredu o] LT";
                        case 4:
                            return "[vo štvrtok o] LT";
                        case 5:
                            return "[v piatok o] LT";
                        case 6:
                            return "[v sobotu o] LT"
                    }
                },
                lastDay: "[včera o] LT",
                lastWeek: function() {
                    switch (this.day()) {
                        case 0:
                            return "[minulú nedeľu o] LT";
                        case 1:
                        case 2:
                            return "[minulý] dddd [o] LT";
                        case 3:
                            return "[minulú stredu o] LT";
                        case 4:
                        case 5:
                            return "[minulý] dddd [o] LT";
                        case 6:
                            return "[minulú sobotu o] LT"
                    }
                },
                sameElse: "L"
            },
            relativeTime: {
                future: "za %s",
                past: "pred %s",
                s: Dd,
                m: Dd,
                mm: Dd,
                h: Dd,
                hh: Dd,
                d: Dd,
                dd: Dd,
                M: Dd,
                MM: Dd,
                y: Dd,
                yy: Dd
            },
            ordinalParse: /\d{1,2}\./,
            ordinal: "%d.",
            week: {
                dow: 1,
                doy: 4
            }
        }), rf.defineLocale("sl", {
            months: "januar_februar_marec_april_maj_junij_julij_avgust_september_oktober_november_december".split("_"),
            monthsShort: "jan._feb._mar._apr._maj._jun._jul._avg._sep._okt._nov._dec.".split("_"),
            weekdays: "nedelja_ponedeljek_torek_sreda_četrtek_petek_sobota".split("_"),
            weekdaysShort: "ned._pon._tor._sre._čet._pet._sob.".split("_"),
            weekdaysMin: "ne_po_to_sr_če_pe_so".split("_"),
            longDateFormat: {
                LT: "H:mm",
                LTS: "H:mm:ss",
                L: "DD. MM. YYYY",
                LL: "D. MMMM YYYY",
                LLL: "D. MMMM YYYY H:mm",
                LLLL: "dddd, D. MMMM YYYY H:mm"
            },
            calendar: {
                sameDay: "[danes ob] LT",
                nextDay: "[jutri ob] LT",
                nextWeek: function() {
                    switch (this.day()) {
                        case 0:
                            return "[v] [nedeljo] [ob] LT";
                        case 3:
                            return "[v] [sredo] [ob] LT";
                        case 6:
                            return "[v] [soboto] [ob] LT";
                        case 1:
                        case 2:
                        case 4:
                        case 5:
                            return "[v] dddd [ob] LT"
                    }
                },
                lastDay: "[včeraj ob] LT",
                lastWeek: function() {
                    switch (this.day()) {
                        case 0:
                            return "[prejšnjo] [nedeljo] [ob] LT";
                        case 3:
                            return "[prejšnjo] [sredo] [ob] LT";
                        case 6:
                            return "[prejšnjo] [soboto] [ob] LT";
                        case 1:
                        case 2:
                        case 4:
                        case 5:
                            return "[prejšnji] dddd [ob] LT"
                    }
                },
                sameElse: "L"
            },
            relativeTime: {
                future: "čez %s",
                past: "pred %s",
                s: Ed,
                m: Ed,
                mm: Ed,
                h: Ed,
                hh: Ed,
                d: Ed,
                dd: Ed,
                M: Ed,
                MM: Ed,
                y: Ed,
                yy: Ed
            },
            ordinalParse: /\d{1,2}\./,
            ordinal: "%d.",
            week: {
                dow: 1,
                doy: 7
            }
        }), rf.defineLocale("sq", {
            months: "Janar_Shkurt_Mars_Prill_Maj_Qershor_Korrik_Gusht_Shtator_Tetor_Nëntor_Dhjetor".split("_"),
            monthsShort: "Jan_Shk_Mar_Pri_Maj_Qer_Kor_Gus_Sht_Tet_Nën_Dhj".split("_"),
            weekdays: "E Diel_E Hënë_E Martë_E Mërkurë_E Enjte_E Premte_E Shtunë".split("_"),
            weekdaysShort: "Die_Hën_Mar_Mër_Enj_Pre_Sht".split("_"),
            weekdaysMin: "D_H_Ma_Më_E_P_Sh".split("_"),
            meridiemParse: /PD|MD/,
            isPM: function(a) {
                return "M" === a.charAt(0)
            },
            meridiem: function(a, b, c) {
                return 12 > a ? "PD" : "MD"
            },
            longDateFormat: {
                LT: "HH:mm",
                LTS: "HH:mm:ss",
                L: "DD/MM/YYYY",
                LL: "D MMMM YYYY",
                LLL: "D MMMM YYYY HH:mm",
                LLLL: "dddd, D MMMM YYYY HH:mm"
            },
            calendar: {
                sameDay: "[Sot në] LT",
                nextDay: "[Nesër në] LT",
                nextWeek: "dddd [në] LT",
                lastDay: "[Dje në] LT",
                lastWeek: "dddd [e kaluar në] LT",
                sameElse: "L"
            },
            relativeTime: {
                future: "në %s",
                past: "%s më parë",
                s: "disa sekonda",
                m: "një minutë",
                mm: "%d minuta",
                h: "një orë",
                hh: "%d orë",
                d: "një ditë",
                dd: "%d ditë",
                M: "një muaj",
                MM: "%d muaj",
                y: "një vit",
                yy: "%d vite"
            },
            ordinalParse: /\d{1,2}\./,
            ordinal: "%d.",
            week: {
                dow: 1,
                doy: 4
            }
        }), {
            words: {
                m: ["један минут", "једне минуте"],
                mm: ["минут", "минуте", "минута"],
                h: ["један сат", "једног сата"],
                hh: ["сат", "сата", "сати"],
                dd: ["дан", "дана", "дана"],
                MM: ["месец", "месеца", "месеци"],
                yy: ["година", "године", "година"]
            },
            correctGrammaticalCase: function(a, b) {
                return 1 === a ? b[0] : a >= 2 && 4 >= a ? b[1] : b[2]
            },
            translate: function(a, b, c) {
                var d = gg.words[c];
                return 1 === c.length ? b ? d[0] : d[1] : a + " " + gg.correctGrammaticalCase(a, d)
            }
        }),
        hg = (rf.defineLocale("sr-cyrl", {
            months: ["јануар", "фебруар", "март", "април", "мај", "јун", "јул", "август", "септембар", "октобар", "новембар", "децембар"],
            monthsShort: ["јан.", "феб.", "мар.", "апр.", "мај", "јун", "јул", "авг.", "сеп.", "окт.", "нов.", "дец."],
            weekdays: ["недеља", "понедељак", "уторак", "среда", "четвртак", "петак", "субота"],
            weekdaysShort: ["нед.", "пон.", "уто.", "сре.", "чет.", "пет.", "суб."],
            weekdaysMin: ["не", "по", "ут", "ср", "че", "пе", "су"],
            longDateFormat: {
                LT: "H:mm",
                LTS: "H:mm:ss",
                L: "DD. MM. YYYY",
                LL: "D. MMMM YYYY",
                LLL: "D. MMMM YYYY H:mm",
                LLLL: "dddd, D. MMMM YYYY H:mm"
            },
            calendar: {
                sameDay: "[данас у] LT",
                nextDay: "[сутра у] LT",
                nextWeek: function() {
                    switch (this.day()) {
                        case 0:
                            return "[у] [недељу] [у] LT";
                        case 3:
                            return "[у] [среду] [у] LT";
                        case 6:
                            return "[у] [суботу] [у] LT";
                        case 1:
                        case 2:
                        case 4:
                        case 5:
                            return "[у] dddd [у] LT"
                    }
                },
                lastDay: "[јуче у] LT",
                lastWeek: function() {
                    return ["[прошле] [недеље] [у] LT", "[прошлог] [понедељка] [у] LT", "[прошлог] [уторка] [у] LT", "[прошле] [среде] [у] LT", "[прошлог] [четвртка] [у] LT", "[прошлог] [петка] [у] LT", "[прошле] [суботе] [у] LT"][this.day()]
                },
                sameElse: "L"
            },
            relativeTime: {
                future: "за %s",
                past: "пре %s",
                s: "неколико секунди",
                m: gg.translate,
                mm: gg.translate,
                h: gg.translate,
                hh: gg.translate,
                d: "дан",
                dd: gg.translate,
                M: "месец",
                MM: gg.translate,
                y: "годину",
                yy: gg.translate
            },
            ordinalParse: /\d{1,2}\./,
            ordinal: "%d.",
            week: {
                dow: 1,
                doy: 7
            }
        }), {
            words: {
                m: ["jedan minut", "jedne minute"],
                mm: ["minut", "minute", "minuta"],
                h: ["jedan sat", "jednog sata"],
                hh: ["sat", "sata", "sati"],
                dd: ["dan", "dana", "dana"],
                MM: ["mesec", "meseca", "meseci"],
                yy: ["godina", "godine", "godina"]
            },
            correctGrammaticalCase: function(a, b) {
                return 1 === a ? b[0] : a >= 2 && 4 >= a ? b[1] : b[2]
            },
            translate: function(a, b, c) {
                var d = hg.words[c];
                return 1 === c.length ? b ? d[0] : d[1] : a + " " + hg.correctGrammaticalCase(a, d)
            }
        }),
        ig = (rf.defineLocale("sr", {
            months: ["januar", "februar", "mart", "april", "maj", "jun", "jul", "avgust", "septembar", "oktobar", "novembar", "decembar"],
            monthsShort: ["jan.", "feb.", "mar.", "apr.", "maj", "jun", "jul", "avg.", "sep.", "okt.", "nov.", "dec."],
            weekdays: ["nedelja", "ponedeljak", "utorak", "sreda", "četvrtak", "petak", "subota"],
            weekdaysShort: ["ned.", "pon.", "uto.", "sre.", "čet.", "pet.", "sub."],
            weekdaysMin: ["ne", "po", "ut", "sr", "če", "pe", "su"],
            longDateFormat: {
                LT: "H:mm",
                LTS: "H:mm:ss",
                L: "DD. MM. YYYY",
                LL: "D. MMMM YYYY",
                LLL: "D. MMMM YYYY H:mm",
                LLLL: "dddd, D. MMMM YYYY H:mm"
            },
            calendar: {
                sameDay: "[danas u] LT",
                nextDay: "[sutra u] LT",
                nextWeek: function() {
                    switch (this.day()) {
                        case 0:
                            return "[u] [nedelju] [u] LT";
                        case 3:
                            return "[u] [sredu] [u] LT";
                        case 6:
                            return "[u] [subotu] [u] LT";
                        case 1:
                        case 2:
                        case 4:
                        case 5:
                            return "[u] dddd [u] LT"
                    }
                },
                lastDay: "[juče u] LT",
                lastWeek: function() {
                    return ["[prošle] [nedelje] [u] LT", "[prošlog] [ponedeljka] [u] LT", "[prošlog] [utorka] [u] LT", "[prošle] [srede] [u] LT", "[prošlog] [četvrtka] [u] LT", "[prošlog] [petka] [u] LT", "[prošle] [subote] [u] LT"][this.day()]
                },
                sameElse: "L"
            },
            relativeTime: {
                future: "za %s",
                past: "pre %s",
                s: "nekoliko sekundi",
                m: hg.translate,
                mm: hg.translate,
                h: hg.translate,
                hh: hg.translate,
                d: "dan",
                dd: hg.translate,
                M: "mesec",
                MM: hg.translate,
                y: "godinu",
                yy: hg.translate
            },
            ordinalParse: /\d{1,2}\./,
            ordinal: "%d.",
            week: {
                dow: 1,
                doy: 7
            }
        }), rf.defineLocale("sv", {
            months: "januari_februari_mars_april_maj_juni_juli_augusti_september_oktober_november_december".split("_"),
            monthsShort: "jan_feb_mar_apr_maj_jun_jul_aug_sep_okt_nov_dec".split("_"),
            weekdays: "söndag_måndag_tisdag_onsdag_torsdag_fredag_lördag".split("_"),
            weekdaysShort: "sön_mån_tis_ons_tor_fre_lör".split("_"),
            weekdaysMin: "sö_må_ti_on_to_fr_lö".split("_"),
            longDateFormat: {
                LT: "HH:mm",
                LTS: "HH:mm:ss",
                L: "YYYY-MM-DD",
                LL: "D MMMM YYYY",
                LLL: "D MMMM YYYY HH:mm",
                LLLL: "dddd D MMMM YYYY HH:mm"
            },
            calendar: {
                sameDay: "[Idag] LT",
                nextDay: "[Imorgon] LT",
                lastDay: "[Igår] LT",
                nextWeek: "[På] dddd LT",
                lastWeek: "[I] dddd[s] LT",
                sameElse: "L"
            },
            relativeTime: {
                future: "om %s",
                past: "för %s sedan",
                s: "några sekunder",
                m: "en minut",
                mm: "%d minuter",
                h: "en timme",
                hh: "%d timmar",
                d: "en dag",
                dd: "%d dagar",
                M: "en månad",
                MM: "%d månader",
                y: "ett år",
                yy: "%d år"
            },
            ordinalParse: /\d{1,2}(e|a)/,
            ordinal: function(a) {
                var b = a % 10;
                return a + (1 == ~~(a % 100 / 10) ? "e" : 1 === b ? "a" : 2 === b ? "a" : "e")
            },
            week: {
                dow: 1,
                doy: 4
            }
        }), rf.defineLocale("ta", {
            months: "ஜனவரி_பிப்ரவரி_மார்ச்_ஏப்ரல்_மே_ஜூன்_ஜூலை_ஆகஸ்ட்_செப்டெம்பர்_அக்டோபர்_நவம்பர்_டிசம்பர்".split("_"),
            monthsShort: "ஜனவரி_பிப்ரவரி_மார்ச்_ஏப்ரல்_மே_ஜூன்_ஜூலை_ஆகஸ்ட்_செப்டெம்பர்_அக்டோபர்_நவம்பர்_டிசம்பர்".split("_"),
            weekdays: "ஞாயிற்றுக்கிழமை_திங்கட்கிழமை_செவ்வாய்கிழமை_புதன்கிழமை_வியாழக்கிழமை_வெள்ளிக்கிழமை_சனிக்கிழமை".split("_"),
            weekdaysShort: "ஞாயிறு_திங்கள்_செவ்வாய்_புதன்_வியாழன்_வெள்ளி_சனி".split("_"),
            weekdaysMin: "ஞா_தி_செ_பு_வி_வெ_ச".split("_"),
            longDateFormat: {
                LT: "HH:mm",
                LTS: "HH:mm:ss",
                L: "DD/MM/YYYY",
                LL: "D MMMM YYYY",
                LLL: "D MMMM YYYY, HH:mm",
                LLLL: "dddd, D MMMM YYYY, HH:mm"
            },
            calendar: {
                sameDay: "[இன்று] LT",
                nextDay: "[நாளை] LT",
                nextWeek: "dddd, LT",
                lastDay: "[நேற்று] LT",
                lastWeek: "[கடந்த வாரம்] dddd, LT",
                sameElse: "L"
            },
            relativeTime: {
                future: "%s இல்",
                past: "%s முன்",
                s: "ஒரு சில விநாடிகள்",
                m: "ஒரு நிமிடம்",
                mm: "%d நிமிடங்கள்",
                h: "ஒரு மணி நேரம்",
                hh: "%d மணி நேரம்",
                d: "ஒரு நாள்",
                dd: "%d நாட்கள்",
                M: "ஒரு மாதம்",
                MM: "%d மாதங்கள்",
                y: "ஒரு வருடம்",
                yy: "%d ஆண்டுகள்"
            },
            ordinalParse: /\d{1,2}வது/,
            ordinal: function(a) {
                return a + "வது"
            },
            meridiemParse: /யாமம்|வைகறை|காலை|நண்பகல்|எற்பாடு|மாலை/,
            meridiem: function(a, b, c) {
                return 2 > a ? " யாமம்" : 6 > a ? " வைகறை" : 10 > a ? " காலை" : 14 > a ? " நண்பகல்" : 18 > a ? " எற்பாடு" : 22 > a ? " மாலை" : " யாமம்"
            },
            meridiemHour: function(a, b) {
                return 12 === a && (a = 0), "யாமம்" === b ? 2 > a ? a : a + 12 : "வைகறை" === b || "காலை" === b ? a : "நண்பகல்" === b && a >= 10 ? a : a + 12
            },
            week: {
                dow: 0,
                doy: 6
            }
        }), rf.defineLocale("th", {
            months: "มกราคม_กุมภาพันธ์_มีนาคม_เมษายน_พฤษภาคม_มิถุนายน_กรกฎาคม_สิงหาคม_กันยายน_ตุลาคม_พฤศจิกายน_ธันวาคม".split("_"),
            monthsShort: "มกรา_กุมภา_มีนา_เมษา_พฤษภา_มิถุนา_กรกฎา_สิงหา_กันยา_ตุลา_พฤศจิกา_ธันวา".split("_"),
            weekdays: "อาทิตย์_จันทร์_อังคาร_พุธ_พฤหัสบดี_ศุกร์_เสาร์".split("_"),
            weekdaysShort: "อาทิตย์_จันทร์_อังคาร_พุธ_พฤหัส_ศุกร์_เสาร์".split("_"),
            weekdaysMin: "อา._จ._อ._พ._พฤ._ศ._ส.".split("_"),
            longDateFormat: {
                LT: "H นาฬิกา m นาที",
                LTS: "H นาฬิกา m นาที s วินาที",
                L: "YYYY/MM/DD",
                LL: "D MMMM YYYY",
                LLL: "D MMMM YYYY เวลา H นาฬิกา m นาที",
                LLLL: "วันddddที่ D MMMM YYYY เวลา H นาฬิกา m นาที"
            },
            meridiemParse: /ก่อนเที่ยง|หลังเที่ยง/,
            isPM: function(a) {
                return "หลังเที่ยง" === a
            },
            meridiem: function(a, b, c) {
                return 12 > a ? "ก่อนเที่ยง" : "หลังเที่ยง"
            },
            calendar: {
                sameDay: "[วันนี้ เวลา] LT",
                nextDay: "[พรุ่งนี้ เวลา] LT",
                nextWeek: "dddd[หน้า เวลา] LT",
                lastDay: "[เมื่อวานนี้ เวลา] LT",
                lastWeek: "[วัน]dddd[ที่แล้ว เวลา] LT",
                sameElse: "L"
            },
            relativeTime: {
                future: "อีก %s",
                past: "%sที่แล้ว",
                s: "ไม่กี่วินาที",
                m: "1 นาที",
                mm: "%d นาที",
                h: "1 ชั่วโมง",
                hh: "%d ชั่วโมง",
                d: "1 วัน",
                dd: "%d วัน",
                M: "1 เดือน",
                MM: "%d เดือน",
                y: "1 ปี",
                yy: "%d ปี"
            }
        }), rf.defineLocale("tl-ph", {
            months: "Enero_Pebrero_Marso_Abril_Mayo_Hunyo_Hulyo_Agosto_Setyembre_Oktubre_Nobyembre_Disyembre".split("_"),
            monthsShort: "Ene_Peb_Mar_Abr_May_Hun_Hul_Ago_Set_Okt_Nob_Dis".split("_"),
            weekdays: "Linggo_Lunes_Martes_Miyerkules_Huwebes_Biyernes_Sabado".split("_"),
            weekdaysShort: "Lin_Lun_Mar_Miy_Huw_Biy_Sab".split("_"),
            weekdaysMin: "Li_Lu_Ma_Mi_Hu_Bi_Sab".split("_"),
            longDateFormat: {
                LT: "HH:mm",
                LTS: "HH:mm:ss",
                L: "MM/D/YYYY",
                LL: "MMMM D, YYYY",
                LLL: "MMMM D, YYYY HH:mm",
                LLLL: "dddd, MMMM DD, YYYY HH:mm"
            },
            calendar: {
                sameDay: "[Ngayon sa] LT",
                nextDay: "[Bukas sa] LT",
                nextWeek: "dddd [sa] LT",
                lastDay: "[Kahapon sa] LT",
                lastWeek: "dddd [huling linggo] LT",
                sameElse: "L"
            },
            relativeTime: {
                future: "sa loob ng %s",
                past: "%s ang nakalipas",
                s: "ilang segundo",
                m: "isang minuto",
                mm: "%d minuto",
                h: "isang oras",
                hh: "%d oras",
                d: "isang araw",
                dd: "%d araw",
                M: "isang buwan",
                MM: "%d buwan",
                y: "isang taon",
                yy: "%d taon"
            },
            ordinalParse: /\d{1,2}/,
            ordinal: function(a) {
                return a
            },
            week: {
                dow: 1,
                doy: 4
            }
        }), {
            1: "'inci",
            5: "'inci",
            8: "'inci",
            70: "'inci",
            80: "'inci",
            2: "'nci",
            7: "'nci",
            20: "'nci",
            50: "'nci",
            3: "'üncü",
            4: "'üncü",
            100: "'üncü",
            6: "'ncı",
            9: "'uncu",
            10: "'uncu",
            30: "'uncu",
            60: "'ıncı",
            90: "'ıncı"
        }),
        jg = (rf.defineLocale("tr", {
            months: "Ocak_Şubat_Mart_Nisan_Mayıs_Haziran_Temmuz_Ağustos_Eylül_Ekim_Kasım_Aralık".split("_"),
            monthsShort: "Oca_Şub_Mar_Nis_May_Haz_Tem_Ağu_Eyl_Eki_Kas_Ara".split("_"),
            weekdays: "Pazar_Pazartesi_Salı_Çarşamba_Perşembe_Cuma_Cumartesi".split("_"),
            weekdaysShort: "Paz_Pts_Sal_Çar_Per_Cum_Cts".split("_"),
            weekdaysMin: "Pz_Pt_Sa_Ça_Pe_Cu_Ct".split("_"),
            longDateFormat: {
                LT: "HH:mm",
                LTS: "HH:mm:ss",
                L: "DD.MM.YYYY",
                LL: "D MMMM YYYY",
                LLL: "D MMMM YYYY HH:mm",
                LLLL: "dddd, D MMMM YYYY HH:mm"
            },
            calendar: {
                sameDay: "[bugün saat] LT",
                nextDay: "[yarın saat] LT",
                nextWeek: "[haftaya] dddd [saat] LT",
                lastDay: "[dün] LT",
                lastWeek: "[geçen hafta] dddd [saat] LT",
                sameElse: "L"
            },
            relativeTime: {
                future: "%s sonra",
                past: "%s önce",
                s: "birkaç saniye",
                m: "bir dakika",
                mm: "%d dakika",
                h: "bir saat",
                hh: "%d saat",
                d: "bir gün",
                dd: "%d gün",
                M: "bir ay",
                MM: "%d ay",
                y: "bir yıl",
                yy: "%d yıl"
            },
            ordinalParse: /\d{1,2}'(inci|nci|üncü|ncı|uncu|ıncı)/,
            ordinal: function(a) {
                if (0 === a) return a + "'ıncı";
                var b = a % 10,
                    c = a % 100 - b,
                    d = a >= 100 ? 100 : null;
                return a + (ig[b] || ig[c] || ig[d])
            },
            week: {
                dow: 1,
                doy: 7
            }
        }), rf.defineLocale("tzl", {
            months: "Januar_Fevraglh_Març_Avrïu_Mai_Gün_Julia_Guscht_Setemvar_Listopäts_Noemvar_Zecemvar".split("_"),
            monthsShort: "Jan_Fev_Mar_Avr_Mai_Gün_Jul_Gus_Set_Lis_Noe_Zec".split("_"),
            weekdays: "Súladi_Lúneçi_Maitzi_Márcuri_Xhúadi_Viénerçi_Sáturi".split("_"),
            weekdaysShort: "Súl_Lún_Mai_Már_Xhú_Vié_Sát".split("_"),
            weekdaysMin: "Sú_Lú_Ma_Má_Xh_Vi_Sá".split("_"),
            longDateFormat: {
                LT: "HH.mm",
                LTS: "LT.ss",
                L: "DD.MM.YYYY",
                LL: "D. MMMM [dallas] YYYY",
                LLL: "D. MMMM [dallas] YYYY LT",
                LLLL: "dddd, [li] D. MMMM [dallas] YYYY LT"
            },
            meridiem: function(a, b, c) {
                return a > 11 ? c ? "d'o" : "D'O" : c ? "d'a" : "D'A"
            },
            calendar: {
                sameDay: "[oxhi à] LT",
                nextDay: "[demà à] LT",
                nextWeek: "dddd [à] LT",
                lastDay: "[ieiri à] LT",
                lastWeek: "[sür el] dddd [lasteu à] LT",
                sameElse: "L"
            },
            relativeTime: {
                future: "osprei %s",
                past: "ja%s",
                s: Fd,
                m: Fd,
                mm: Fd,
                h: Fd,
                hh: Fd,
                d: Fd,
                dd: Fd,
                M: Fd,
                MM: Fd,
                y: Fd,
                yy: Fd
            },
            ordinalParse: /\d{1,2}\./,
            ordinal: "%d.",
            week: {
                dow: 1,
                doy: 4
            }
        }), rf.defineLocale("tzm-latn", {
            months: "innayr_brˤayrˤ_marˤsˤ_ibrir_mayyw_ywnyw_ywlywz_ɣwšt_šwtanbir_ktˤwbrˤ_nwwanbir_dwjnbir".split("_"),
            monthsShort: "innayr_brˤayrˤ_marˤsˤ_ibrir_mayyw_ywnyw_ywlywz_ɣwšt_šwtanbir_ktˤwbrˤ_nwwanbir_dwjnbir".split("_"),
            weekdays: "asamas_aynas_asinas_akras_akwas_asimwas_asiḍyas".split("_"),
            weekdaysShort: "asamas_aynas_asinas_akras_akwas_asimwas_asiḍyas".split("_"),
            weekdaysMin: "asamas_aynas_asinas_akras_akwas_asimwas_asiḍyas".split("_"),
            longDateFormat: {
                LT: "HH:mm",
                LTS: "HH:mm:ss",
                L: "DD/MM/YYYY",
                LL: "D MMMM YYYY",
                LLL: "D MMMM YYYY HH:mm",
                LLLL: "dddd D MMMM YYYY HH:mm"
            },
            calendar: {
                sameDay: "[asdkh g] LT",
                nextDay: "[aska g] LT",
                nextWeek: "dddd [g] LT",
                lastDay: "[assant g] LT",
                lastWeek: "dddd [g] LT",
                sameElse: "L"
            },
            relativeTime: {
                future: "dadkh s yan %s",
                past: "yan %s",
                s: "imik",
                m: "minuḍ",
                mm: "%d minuḍ",
                h: "saɛa",
                hh: "%d tassaɛin",
                d: "ass",
                dd: "%d ossan",
                M: "ayowr",
                MM: "%d iyyirn",
                y: "asgas",
                yy: "%d isgasn"
            },
            week: {
                dow: 6,
                doy: 12
            }
        }), rf.defineLocale("tzm", {
            months: "ⵉⵏⵏⴰⵢⵔ_ⴱⵕⴰⵢⵕ_ⵎⴰⵕⵚ_ⵉⴱⵔⵉⵔ_ⵎⴰⵢⵢⵓ_ⵢⵓⵏⵢⵓ_ⵢⵓⵍⵢⵓⵣ_ⵖⵓⵛⵜ_ⵛⵓⵜⴰⵏⴱⵉⵔ_ⴽⵟⵓⴱⵕ_ⵏⵓⵡⴰⵏⴱⵉⵔ_ⴷⵓⵊⵏⴱⵉⵔ".split("_"),
            monthsShort: "ⵉⵏⵏⴰⵢⵔ_ⴱⵕⴰⵢⵕ_ⵎⴰⵕⵚ_ⵉⴱⵔⵉⵔ_ⵎⴰⵢⵢⵓ_ⵢⵓⵏⵢⵓ_ⵢⵓⵍⵢⵓⵣ_ⵖⵓⵛⵜ_ⵛⵓⵜⴰⵏⴱⵉⵔ_ⴽⵟⵓⴱⵕ_ⵏⵓⵡⴰⵏⴱⵉⵔ_ⴷⵓⵊⵏⴱⵉⵔ".split("_"),
            weekdays: "ⴰⵙⴰⵎⴰⵙ_ⴰⵢⵏⴰⵙ_ⴰⵙⵉⵏⴰⵙ_ⴰⴽⵔⴰⵙ_ⴰⴽⵡⴰⵙ_ⴰⵙⵉⵎⵡⴰⵙ_ⴰⵙⵉⴹⵢⴰⵙ".split("_"),
            weekdaysShort: "ⴰⵙⴰⵎⴰⵙ_ⴰⵢⵏⴰⵙ_ⴰⵙⵉⵏⴰⵙ_ⴰⴽⵔⴰⵙ_ⴰⴽⵡⴰⵙ_ⴰⵙⵉⵎⵡⴰⵙ_ⴰⵙⵉⴹⵢⴰⵙ".split("_"),
            weekdaysMin: "ⴰⵙⴰⵎⴰⵙ_ⴰⵢⵏⴰⵙ_ⴰⵙⵉⵏⴰⵙ_ⴰⴽⵔⴰⵙ_ⴰⴽⵡⴰⵙ_ⴰⵙⵉⵎⵡⴰⵙ_ⴰⵙⵉⴹⵢⴰⵙ".split("_"),
            longDateFormat: {
                LT: "HH:mm",
                LTS: "HH:mm:ss",
                L: "DD/MM/YYYY",
                LL: "D MMMM YYYY",
                LLL: "D MMMM YYYY HH:mm",
                LLLL: "dddd D MMMM YYYY HH:mm"
            },
            calendar: {
                sameDay: "[ⴰⵙⴷⵅ ⴴ] LT",
                nextDay: "[ⴰⵙⴽⴰ ⴴ] LT",
                nextWeek: "dddd [ⴴ] LT",
                lastDay: "[ⴰⵚⴰⵏⵜ ⴴ] LT",
                lastWeek: "dddd [ⴴ] LT",
                sameElse: "L"
            },
            relativeTime: {
                future: "ⴷⴰⴷⵅ ⵙ ⵢⴰⵏ %s",
                past: "ⵢⴰⵏ %s",
                s: "ⵉⵎⵉⴽ",
                m: "ⵎⵉⵏⵓⴺ",
                mm: "%d ⵎⵉⵏⵓⴺ",
                h: "ⵙⴰⵄⴰ",
                hh: "%d ⵜⴰⵙⵙⴰⵄⵉⵏ",
                d: "ⴰⵙⵙ",
                dd: "%d oⵙⵙⴰⵏ",
                M: "ⴰⵢoⵓⵔ",
                MM: "%d ⵉⵢⵢⵉⵔⵏ",
                y: "ⴰⵙⴳⴰⵙ",
                yy: "%d ⵉⵙⴳⴰⵙⵏ"
            },
            week: {
                dow: 6,
                doy: 12
            }
        }), rf.defineLocale("uk", {
            months: Id,
            monthsShort: "січ_лют_бер_квіт_трав_черв_лип_серп_вер_жовт_лист_груд".split("_"),
            weekdays: Jd,
            weekdaysShort: "нд_пн_вт_ср_чт_пт_сб".split("_"),
            weekdaysMin: "нд_пн_вт_ср_чт_пт_сб".split("_"),
            longDateFormat: {
                LT: "HH:mm",
                LTS: "HH:mm:ss",
                L: "DD.MM.YYYY",
                LL: "D MMMM YYYY р.",
                LLL: "D MMMM YYYY р., HH:mm",
                LLLL: "dddd, D MMMM YYYY р., HH:mm"
            },
            calendar: {
                sameDay: Kd("[Сьогодні "),
                nextDay: Kd("[Завтра "),
                lastDay: Kd("[Вчора "),
                nextWeek: Kd("[У] dddd ["),
                lastWeek: function() {
                    switch (this.day()) {
                        case 0:
                        case 3:
                        case 5:
                        case 6:
                            return Kd("[Минулої] dddd [").call(this);
                        case 1:
                        case 2:
                        case 4:
                            return Kd("[Минулого] dddd [").call(this)
                    }
                },
                sameElse: "L"
            },
            relativeTime: {
                future: "за %s",
                past: "%s тому",
                s: "декілька секунд",
                m: Hd,
                mm: Hd,
                h: "годину",
                hh: Hd,
                d: "день",
                dd: Hd,
                M: "місяць",
                MM: Hd,
                y: "рік",
                yy: Hd
            },
            meridiemParse: /ночі|ранку|дня|вечора/,
            isPM: function(a) {
                return /^(дня|вечора)$/.test(a)
            },
            meridiem: function(a, b, c) {
                return 4 > a ? "ночі" : 12 > a ? "ранку" : 17 > a ? "дня" : "вечора"
            },
            ordinalParse: /\d{1,2}-(й|го)/,
            ordinal: function(a, b) {
                switch (b) {
                    case "M":
                    case "d":
                    case "DDD":
                    case "w":
                    case "W":
                        return a + "-й";
                    case "D":
                        return a + "-го";
                    default:
                        return a
                }
            },
            week: {
                dow: 1,
                doy: 7
            }
        }), rf.defineLocale("uz", {
            months: "январь_февраль_март_апрель_май_июнь_июль_август_сентябрь_октябрь_ноябрь_декабрь".split("_"),
            monthsShort: "янв_фев_мар_апр_май_июн_июл_авг_сен_окт_ноя_дек".split("_"),
            weekdays: "Якшанба_Душанба_Сешанба_Чоршанба_Пайшанба_Жума_Шанба".split("_"),
            weekdaysShort: "Якш_Душ_Сеш_Чор_Пай_Жум_Шан".split("_"),
            weekdaysMin: "Як_Ду_Се_Чо_Па_Жу_Ша".split("_"),
            longDateFormat: {
                LT: "HH:mm",
                LTS: "HH:mm:ss",
                L: "DD/MM/YYYY",
                LL: "D MMMM YYYY",
                LLL: "D MMMM YYYY HH:mm",
                LLLL: "D MMMM YYYY, dddd HH:mm"
            },
            calendar: {
                sameDay: "[Бугун соат] LT [да]",
                nextDay: "[Эртага] LT [да]",
                nextWeek: "dddd [куни соат] LT [да]",
                lastDay: "[Кеча соат] LT [да]",
                lastWeek: "[Утган] dddd [куни соат] LT [да]",
                sameElse: "L"
            },
            relativeTime: {
                future: "Якин %s ичида",
                past: "Бир неча %s олдин",
                s: "фурсат",
                m: "бир дакика",
                mm: "%d дакика",
                h: "бир соат",
                hh: "%d соат",
                d: "бир кун",
                dd: "%d кун",
                M: "бир ой",
                MM: "%d ой",
                y: "бир йил",
                yy: "%d йил"
            },
            week: {
                dow: 1,
                doy: 7
            }
        }), rf.defineLocale("vi", {
            months: "tháng 1_tháng 2_tháng 3_tháng 4_tháng 5_tháng 6_tháng 7_tháng 8_tháng 9_tháng 10_tháng 11_tháng 12".split("_"),
            monthsShort: "Th01_Th02_Th03_Th04_Th05_Th06_Th07_Th08_Th09_Th10_Th11_Th12".split("_"),
            weekdays: "chủ nhật_thứ hai_thứ ba_thứ tư_thứ năm_thứ sáu_thứ bảy".split("_"),
            weekdaysShort: "CN_T2_T3_T4_T5_T6_T7".split("_"),
            weekdaysMin: "CN_T2_T3_T4_T5_T6_T7".split("_"),
            longDateFormat: {
                LT: "HH:mm",
                LTS: "HH:mm:ss",
                L: "DD/MM/YYYY",
                LL: "D MMMM [năm] YYYY",
                LLL: "D MMMM [năm] YYYY HH:mm",
                LLLL: "dddd, D MMMM [năm] YYYY HH:mm",
                l: "DD/M/YYYY",
                ll: "D MMM YYYY",
                lll: "D MMM YYYY HH:mm",
                llll: "ddd, D MMM YYYY HH:mm"
            },
            calendar: {
                sameDay: "[Hôm nay lúc] LT",
                nextDay: "[Ngày mai lúc] LT",
                nextWeek: "dddd [tuần tới lúc] LT",
                lastDay: "[Hôm qua lúc] LT",
                lastWeek: "dddd [tuần rồi lúc] LT",
                sameElse: "L"
            },
            relativeTime: {
                future: "%s tới",
                past: "%s trước",
                s: "vài giây",
                m: "một phút",
                mm: "%d phút",
                h: "một giờ",
                hh: "%d giờ",
                d: "một ngày",
                dd: "%d ngày",
                M: "một tháng",
                MM: "%d tháng",
                y: "một năm",
                yy: "%d năm"
            },
            ordinalParse: /\d{1,2}/,
            ordinal: function(a) {
                return a
            },
            week: {
                dow: 1,
                doy: 4
            }
        }), rf.defineLocale("zh-cn", {
            months: "一月_二月_三月_四月_五月_六月_七月_八月_九月_十月_十一月_十二月".split("_"),
            monthsShort: "1月_2月_3月_4月_5月_6月_7月_8月_9月_10月_11月_12月".split("_"),
            weekdays: "星期日_星期一_星期二_星期三_星期四_星期五_星期六".split("_"),
            weekdaysShort: "周日_周一_周二_周三_周四_周五_周六".split("_"),
            weekdaysMin: "日_一_二_三_四_五_六".split("_"),
            longDateFormat: {
                LT: "Ah点mm分",
                LTS: "Ah点m分s秒",
                L: "YYYY-MM-DD",
                LL: "YYYY年MMMD日",
                LLL: "YYYY年MMMD日Ah点mm分",
                LLLL: "YYYY年MMMD日ddddAh点mm分",
                l: "YYYY-MM-DD",
                ll: "YYYY年MMMD日",
                lll: "YYYY年MMMD日Ah点mm分",
                llll: "YYYY年MMMD日ddddAh点mm分"
            },
            meridiemParse: /凌晨|早上|上午|中午|下午|晚上/,
            meridiemHour: function(a, b) {
                return 12 === a && (a = 0), "凌晨" === b || "早上" === b || "上午" === b ? a : "下午" === b || "晚上" === b ? a + 12 : a >= 11 ? a : a + 12
            },
            meridiem: function(a, b, c) {
                var d = 100 * a + b;
                return 600 > d ? "凌晨" : 900 > d ? "早上" : 1130 > d ? "上午" : 1230 > d ? "中午" : 1800 > d ? "下午" : "晚上"
            },
            calendar: {
                sameDay: function() {
                    return 0 === this.minutes() ? "[今天]Ah[点整]" : "[今天]LT"
                },
                nextDay: function() {
                    return 0 === this.minutes() ? "[明天]Ah[点整]" : "[明天]LT"
                },
                lastDay: function() {
                    return 0 === this.minutes() ? "[昨天]Ah[点整]" : "[昨天]LT"
                },
                nextWeek: function() {
                    var a, b;
                    return a = rf().startOf("week"), b = this.unix() - a.unix() >= 604800 ? "[下]" : "[本]", 0 === this.minutes() ? b + "dddAh点整" : b + "dddAh点mm"
                },
                lastWeek: function() {
                    var a, b;
                    return a = rf().startOf("week"), b = this.unix() < a.unix() ? "[上]" : "[本]", 0 === this.minutes() ? b + "dddAh点整" : b + "dddAh点mm"
                },
                sameElse: "LL"
            },
            ordinalParse: /\d{1,2}(日|月|周)/,
            ordinal: function(a, b) {
                switch (b) {
                    case "d":
                    case "D":
                    case "DDD":
                        return a + "日";
                    case "M":
                        return a + "月";
                    case "w":
                    case "W":
                        return a + "周";
                    default:
                        return a
                }
            },
            relativeTime: {
                future: "%s内",
                past: "%s前",
                s: "几秒",
                m: "1 分钟",
                mm: "%d 分钟",
                h: "1 小时",
                hh: "%d 小时",
                d: "1 天",
                dd: "%d 天",
                M: "1 个月",
                MM: "%d 个月",
                y: "1 年",
                yy: "%d 年"
            },
            week: {
                dow: 1,
                doy: 4
            }
        }), rf.defineLocale("zh-tw", {
            months: "一月_二月_三月_四月_五月_六月_七月_八月_九月_十月_十一月_十二月".split("_"),
            monthsShort: "1月_2月_3月_4月_5月_6月_7月_8月_9月_10月_11月_12月".split("_"),
            weekdays: "星期日_星期一_星期二_星期三_星期四_星期五_星期六".split("_"),
            weekdaysShort: "週日_週一_週二_週三_週四_週五_週六".split("_"),
            weekdaysMin: "日_一_二_三_四_五_六".split("_"),
            longDateFormat: {
                LT: "Ah點mm分",
                LTS: "Ah點m分s秒",
                L: "YYYY年MMMD日",
                LL: "YYYY年MMMD日",
                LLL: "YYYY年MMMD日Ah點mm分",
                LLLL: "YYYY年MMMD日ddddAh點mm分",
                l: "YYYY年MMMD日",
                ll: "YYYY年MMMD日",
                lll: "YYYY年MMMD日Ah點mm分",
                llll: "YYYY年MMMD日ddddAh點mm分"
            },
            meridiemParse: /早上|上午|中午|下午|晚上/,
            meridiemHour: function(a, b) {
                return 12 === a && (a = 0), "早上" === b || "上午" === b ? a : "中午" === b ? a >= 11 ? a : a + 12 : "下午" === b || "晚上" === b ? a + 12 : void 0
            },
            meridiem: function(a, b, c) {
                var d = 100 * a + b;
                return 900 > d ? "早上" : 1130 > d ? "上午" : 1230 > d ? "中午" : 1800 > d ? "下午" : "晚上"
            },
            calendar: {
                sameDay: "[今天]LT",
                nextDay: "[明天]LT",
                nextWeek: "[下]ddddLT",
                lastDay: "[昨天]LT",
                lastWeek: "[上]ddddLT",
                sameElse: "L"
            },
            ordinalParse: /\d{1,2}(日|月|週)/,
            ordinal: function(a, b) {
                switch (b) {
                    case "d":
                    case "D":
                    case "DDD":
                        return a + "日";
                    case "M":
                        return a + "月";
                    case "w":
                    case "W":
                        return a + "週";
                    default:
                        return a
                }
            },
            relativeTime: {
                future: "%s內",
                past: "%s前",
                s: "幾秒",
                m: "一分鐘",
                mm: "%d分鐘",
                h: "一小時",
                hh: "%d小時",
                d: "一天",
                dd: "%d天",
                M: "一個月",
                MM: "%d個月",
                y: "一年",
                yy: "%d年"
            }
        }), rf);
    return jg.locale("en"), jg
}),
function() {
    "use strict";

    function a(a, b) {
        return a.module("angularMoment", []).constant("angularMomentConfig", {
            preprocess: null,
            timezone: "",
            format: null,
            statefulFilters: !0
        }).constant("moment", b).constant("amTimeAgoConfig", {
            withoutSuffix: !1,
            serverTime: null,
            titleFormat: null,
            fullDateThreshold: null,
            fullDateFormat: null
        }).directive("amTimeAgo", ["$window", "moment", "amMoment", "amTimeAgoConfig", "angularMomentConfig", function(b, c, d, e, f) {
            return function(g, h, i) {
                function j() {
                    var a;
                    if (p) a = p;
                    else if (e.serverTime) {
                        var b = (new Date).getTime(),
                            d = b - w + e.serverTime;
                        a = c(d)
                    } else a = c();
                    return a
                }

                function k() {
                    q && (b.clearTimeout(q), q = null)
                }

                function l(a) {
                    var c = j().diff(a, "day"),
                        d = u && c >= u;
                    if (h.text(d ? a.format(v) : a.from(j(), s)), t && !h.attr("title") && h.attr("title", a.local().format(t)), !d) {
                        var e = Math.abs(j().diff(a, "minute")),
                            f = 3600;
                        1 > e ? f = 1 : 60 > e ? f = 30 : 180 > e && (f = 300), q = b.setTimeout(function() {
                            l(a)
                        }, 1e3 * f)
                    }
                }

                function m(a) {
                    z && h.attr("datetime", a)
                }

                function n() {
                    if (k(), o) {
                        var a = d.preprocessDate(o, x, r);
                        l(a), m(a.toISOString())
                    }
                }
                var o, p, q = null,
                    r = f.format,
                    s = e.withoutSuffix,
                    t = e.titleFormat,
                    u = e.fullDateThreshold,
                    v = e.fullDateFormat,
                    w = (new Date).getTime(),
                    x = f.preprocess,
                    y = i.amTimeAgo,
                    z = "TIME" === h[0].nodeName.toUpperCase();
                g.$watch(y, function(a) {
                    return void 0 === a || null === a || "" === a ? (k(), void(o && (h.text(""), m(""), o = null))) : (o = a, void n())
                }), a.isDefined(i.amFrom) && g.$watch(i.amFrom, function(a) {
                    p = void 0 === a || null === a || "" === a ? null : c(a), n()
                }), a.isDefined(i.amWithoutSuffix) && g.$watch(i.amWithoutSuffix, function(a) {
                    "boolean" == typeof a ? (s = a, n()) : s = e.withoutSuffix
                }), i.$observe("amFormat", function(a) {
                    void 0 !== a && (r = a, n())
                }), i.$observe("amPreprocess", function(a) {
                    x = a, n()
                }), i.$observe("amFullDateThreshold", function(a) {
                    u = a, n()
                }), i.$observe("amFullDateFormat", function(a) {
                    v = a, n()
                }), g.$on("$destroy", function() {
                    k()
                }), g.$on("amMoment:localeChanged", function() {
                    n()
                })
            }
        }]).service("amMoment", ["moment", "$rootScope", "$log", "angularMomentConfig", function(b, c, d, e) {
            this.preprocessors = {
                utc: b.utc,
                unix: b.unix
            }, this.changeLocale = function(d, e) {
                var f = b.locale(d, e);
                return a.isDefined(d) && c.$broadcast("amMoment:localeChanged"), f
            }, this.changeTimezone = function(a) {
                e.timezone = a, c.$broadcast("amMoment:timezoneChanged")
            }, this.preprocessDate = function(c, f, g) {
                return a.isUndefined(f) && (f = e.preprocess), this.preprocessors[f] ? this.preprocessors[f](c, g) : (f && d.warn("angular-moment: Ignoring unsupported value for preprocess: " + f), !isNaN(parseFloat(c)) && isFinite(c) ? b(parseInt(c, 10)) : b(c, g))
            }, this.applyTimezone = function(a, b) {
                return (b = b || e.timezone) ? (b.match(/Z|[+-]\d\d:?\d\d/gi) ? a = a.utcOffset(b) : a.tz ? a = a.tz(b) : d.warn("angular-moment: named timezone specified but moment.tz() is undefined. Did you forget to include moment-timezone.js?"), a) : a
            }
        }]).filter("amCalendar", ["moment", "amMoment", "angularMomentConfig", function(a, b, c) {
            function d(c, d, e) {
                if (void 0 === c || null === c) return "";
                c = b.preprocessDate(c, d);
                var f = a(c);
                return f.isValid() ? b.applyTimezone(f, e).calendar() : ""
            }
            return d.$stateful = c.statefulFilters, d
        }]).filter("amDifference", ["moment", "amMoment", "angularMomentConfig", function(a, b, c) {
            function d(c, d, e, f, g, h) {
                if (void 0 === c || null === c) return "";
                c = b.preprocessDate(c, g);
                var i = a(c);
                if (!i.isValid()) return "";
                var j;
                if (void 0 === d || null === d) j = a();
                else if (d = b.preprocessDate(d, h), j = a(d), !j.isValid()) return "";
                return b.applyTimezone(i).diff(b.applyTimezone(j), e, f)
            }
            return d.$stateful = c.statefulFilters, d
        }]).filter("amDateFormat", ["moment", "amMoment", "angularMomentConfig", function(a, b, c) {
            function d(c, d, e, f) {
                if (void 0 === c || null === c) return "";
                c = b.preprocessDate(c, e);
                var g = a(c);
                return g.isValid() ? b.applyTimezone(g, f).format(d) : ""
            }
            return d.$stateful = c.statefulFilters, d
        }]).filter("amDurationFormat", ["moment", "angularMomentConfig", function(a, b) {
            function c(b, c, d) {
                return void 0 === b || null === b ? "" : a.duration(b, c).humanize(d)
            }
            return c.$stateful = b.statefulFilters, c
        }]).filter("amTimeAgo", ["moment", "amMoment", "angularMomentConfig", function(a, b, c) {
            function d(c, d, e, f) {
                var g, h;
                return void 0 === c || null === c ? "" : (c = b.preprocessDate(c, d), g = a(c), g.isValid() ? (h = a(f), void 0 !== f && h.isValid() ? b.applyTimezone(g).from(h, e) : b.applyTimezone(g).fromNow(e)) : "")
            }
            return d.$stateful = c.statefulFilters, d
        }])
    }
    "function" == typeof define && define.amd ? define(["angular", "moment"], a) : "undefined" != typeof module && module && module.exports ? (a(angular, require("moment")), module.exports = "angularMoment") : a(angular, ("undefined" != typeof global ? global : window).moment)
}();