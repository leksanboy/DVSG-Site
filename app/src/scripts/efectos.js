//-----------------------------------------------------------------------
// <copyright file="efectos.js" company="DVSG">
//     Copyright (c) 2015 DVSG. All rights reserved.
// </copyright>
//-----------------------------------------------------------------------
/* TODO: \/
 * √ - En CARS no aparece el footer
 * √ - En CARS añadir todas las marcas (IMG) en la BBDD
 * √ - NAV MENU corregir scroll, se queda pillado en movil
 * √ - CREATE POST hacer el cropper
 * ø - Buscador por palabras
 * ø - Buscador de Shop pasar una Cookie, cuando vuelvo atras no aparen los criterios del buscador
 * √ - Cuando creo un post se guardan mal las imagenes del contenido
 * √ - Crear: editar post
 */

/**
 * Domain URL
 */
// var url = 'http://www.dvsg.co/';
// var url = 'http://104.155.115.61/';
var url = 'http://localhost/DVSG-site/app/dist/';
var $;
var tabla;
var loaderName = $(".estadoDeName span.loader"),
    yesName = $(".estadoDeName span.yes"),
    noName = $(".estadoDeName span.no"),
    padlockEmail = $(".estadoDeEmail span.padlock"),
    loaderEmail = $(".estadoDeEmail span.loader"),
    yesEmail = $(".estadoDeEmail span.yes"),
    noEmail = $(".estadoDeEmail span.no"),
    loaderPass = $(".estadoDePassword span.loader"),
    yesPass = $(".estadoDePassword span.yes"),
    noPass = $(".estadoDePassword span.no"),
    loaderCaptcha = $(".estadoDeCaptcha span.loader"),
    yesCaptcha = $(".estadoDeCaptcha span.yes"),
    noCaptcha = $(".estadoDeCaptcha span.no"),
    valueCaptcha = $('.capcha').html();


/**
 * Cookies
 */
function cerrar_cookie(valor) {
    $.ajax({
        type: 'POST',
        url: url + 'includes/setCookies.php',
        data: 'valor=' + valor,
    });
    $('.cookieBox').addClass('hideCookieBox');
}

/**
 * Header Background color changing
 */
var fadeStart = 0,
    fadeUntil = 420,
    fadingTop = $('.headerTop'),
    fadingBottom = $('.headerBottom');
$(window).bind('scroll', function() {
    var offset = $(document).scrollTop(),
        opacity = 0;
    if (offset <= fadeStart) {
        opacity = 1;
    } else if (offset <= fadeUntil) {
        opacity = 1 - offset / fadeUntil;
    }
    fadingTop.css('background', 'rgba(33, 150, 243, ' + opacity + ')');
    fadingBottom.css('background', 'rgba(255, 255, 255, ' + opacity + ')');
});

/**
 * Header scroll on user page
 */
var $imagesBox = $('.backgroundImages .imgBox'),
    $dataBox = $('.dataBox'),
    $header = $('.header'),
    navHeight = 48;

$(window).scroll(function() {
    var scrollTop = $(this).scrollTop(),
        headlineHeight = $imagesBox.outerHeight() - navHeight,
        navOffset = $header.offset().top;

    $imagesBox.css({
        'opacity': (1 - scrollTop / headlineHeight)
    });
    $dataBox.children().css({
        'transform': 'translateY(' + scrollTop * 0.4 + 'px)'
    });
    if (navOffset > headlineHeight) {
        $header.addClass('scrolled');
        $('.userName').addClass('showUserName');
    } else {
        $header.removeClass('scrolled');
        $('.userName').removeClass('showUserName');
    }
});


/**
 * Open/Close Left menu
 */
function toggleLeftSide(type) {
    // if (matchMedia('only screen and (max-width: 768px)').matches) {}
    if (type === 1) {
        $('.innerBodyContent, .header, .leftSideClose, .hiddenBody').toggleClass('moveToRight');
        $('.leftSideBlock, .leftSideClose').toggleClass('activeBlock');
        $('body').toggleClass('stopPage');
        setTimeout(function() {
            $('.leftSideClose').toggleClass('addShadowColor');
        }, 300);
    }
    if (type === 0) {
        $('.innerBodyContent, .header, .leftSideClose, .hiddenBody').toggleClass('moveToRight');
        $('.leftSideClose').toggleClass('activeBlock addShadowColor');
        $('body').toggleClass('stopPage');
        setTimeout(function() {
            $('.leftSideBlock').toggleClass('activeBlock');
        }, 600);
    }
}


/**
 * Open/Close UserDataBox
 */
function openUserDataBox() {
    $('.userDataBox').toggle();
}


/**
 * Open/Close Right menu
 */
function toggleRightSide(type) {
    // if (matchMedia('only screen and (max-width: 768px)').matches) {}
    if (type === 1) {
        $('.innerBodyContent, .header, .rightSideClose, .hiddenBody').toggleClass('moveToLeft');
        $('.rightSideBlock, .rightSideClose').toggleClass('activeBlock');
        $('body').toggleClass('stopPage');
        setTimeout(function() {
            $('.rightSideClose').toggleClass('addShadowColor');
        }, 300);
    }
    if (type === 0) {
        $('.innerBodyContent, .header, .rightSideClose, .hiddenBody').toggleClass('moveToLeft');
        $('.rightSideClose').toggleClass('activeBlock addShadowColor');
        $('body').toggleClass('stopPage');
        setTimeout(function() {
            $('.rightSideBlock').toggleClass('activeBlock');
        }, 600);
    }
}


/**
 * Search box
 *  - Input empty
 *  - Hide result box
 * Search on Input
 *  - call results
 * Search to Empty
 *  - remove text to empty input
 */
function searchBox() {
    $('.searchBox').toggleClass('expandBox');
    $('.header').toggleClass('headerExpand');
    $('.noticesWindow').html('');
    $('.searchBoxInput').val('');
}

function searchNotices(valor) {
    if (valor.length > 0) {
        $.ajax({
            type: 'POST',
            url: url + 'includes/searchNotices/buscar.php',
            data: 'cadena=' + valor,
            success: function(htmlres) {
                $('.noticesWindow').css("display", "block");
                $('.noticesWindow').html(htmlres);
            }
        });
    } else {
        $('.noticesWindow').css("display", "none");
        $('.noticesWindow').html('');
    }
}

function searchBoxDelete() {
    $('.noticesWindow').html('');
    $('.searchBoxInput').val('');
}


/**
 * Load More Post on Index page
 */
function loadMorePost(paginado) {
    $.ajax({
        type: 'POST',
        url: url + 'pages/home/loadMoreNotices.php',
        data: 'paginado=' + paginado,
        success: function(response) {
            if (response !== '') {
                $('.loadMore').addClass('loadMoreSize');
                setTimeout(function() {
                    $('.loadingMore').addClass('loadingMoreSize');
                }, 300);
                setTimeout(function() {
                    $('.loadMore').removeClass('loadMoreSize');
                    $('.loadingMore').removeClass('loadingMoreSize');
                    $('.loadMoreNotices').append(response);
                }, 3000);
            } else {
                $('.loadMore').hide();
            }
        }
    });
}


/**
 * Show/Hide webAccess bar
 */
(function(document, window, index) {
    'use strict';

    var elSelector = '.webAccess',
        element = document.querySelector(elSelector);

    if (!element) return true;

    var elHeight = 0,
        elTop = 0,
        dHeight = 0,
        wHeight = 0,
        wScrollCurrent = 0,
        wScrollBefore = 0,
        wScrollDiff = 0;

    window.addEventListener('scroll', function() {
        elHeight = element.offsetHeight;
        dHeight = document.body.offsetHeight;
        wHeight = window.innerHeight;
        wScrollCurrent = window.pageYOffset;
        wScrollDiff = wScrollBefore - wScrollCurrent;
        elTop = parseInt(window.getComputedStyle(element).getPropertyValue('bottom')) + wScrollDiff;

        if (wScrollCurrent <= 0)
            element.style.bottom = '0px';

        else if (wScrollDiff > 0)
            element.style.bottom = (elTop > 0 ? 0 : elTop) + 'px';

        else if (wScrollDiff < 0) {
            if (wScrollCurrent + wHeight >= dHeight - elHeight)
                element.style.bottom = ((elTop = wScrollCurrent + wHeight - dHeight) < 0 ? elTop : 0) + 'px';

            else
                element.style.bottom = (Math.abs(elTop) > elHeight ? -elHeight : elTop) + 'px';
        }

        wScrollBefore = wScrollCurrent;
    });
}(document, window, 0));


/**
 * Header Effects
 */
var Delaunay;

(function() {
    "use strict";

    var EPSILON = 1.0 / 1048576.0;

    function supertriangle(vertices) {
        var xmin = Number.POSITIVE_INFINITY,
            ymin = Number.POSITIVE_INFINITY,
            xmax = Number.NEGATIVE_INFINITY,
            ymax = Number.NEGATIVE_INFINITY,
            i, dx, dy, dmax, xmid, ymid;

        for (i = vertices.length; i--;) {
            if (vertices[i][0] < xmin) xmin = vertices[i][0];
            if (vertices[i][0] > xmax) xmax = vertices[i][0];
            if (vertices[i][1] < ymin) ymin = vertices[i][1];
            if (vertices[i][1] > ymax) ymax = vertices[i][1];
        }

        dx = xmax - xmin;
        dy = ymax - ymin;
        dmax = Math.max(dx, dy);
        xmid = xmin + dx * 0.5;
        ymid = ymin + dy * 0.5;

        return [
            [xmid - 20 * dmax, ymid - dmax],
            [xmid, ymid + 20 * dmax],
            [xmid + 20 * dmax, ymid - dmax]
        ];
    }

    function circumcircle(vertices, i, j, k) {
        var x1 = vertices[i][0],
            y1 = vertices[i][1],
            x2 = vertices[j][0],
            y2 = vertices[j][1],
            x3 = vertices[k][0],
            y3 = vertices[k][1],
            fabsy1y2 = Math.abs(y1 - y2),
            fabsy2y3 = Math.abs(y2 - y3),
            xc, yc, m1, m2, mx1, mx2, my1, my2, dx, dy;

        /* Check for coincident points */
        if (fabsy1y2 < EPSILON && fabsy2y3 < EPSILON)
            throw new Error("Eek! Coincident points!");

        if (fabsy1y2 < EPSILON) {
            m2 = -((x3 - x2) / (y3 - y2));
            mx2 = (x2 + x3) / 2.0;
            my2 = (y2 + y3) / 2.0;
            xc = (x2 + x1) / 2.0;
            yc = m2 * (xc - mx2) + my2;
        } else if (fabsy2y3 < EPSILON) {
            m1 = -((x2 - x1) / (y2 - y1));
            mx1 = (x1 + x2) / 2.0;
            my1 = (y1 + y2) / 2.0;
            xc = (x3 + x2) / 2.0;
            yc = m1 * (xc - mx1) + my1;
        } else {
            m1 = -((x2 - x1) / (y2 - y1));
            m2 = -((x3 - x2) / (y3 - y2));
            mx1 = (x1 + x2) / 2.0;
            mx2 = (x2 + x3) / 2.0;
            my1 = (y1 + y2) / 2.0;
            my2 = (y2 + y3) / 2.0;
            xc = (m1 * mx1 - m2 * mx2 + my2 - my1) / (m1 - m2);
            yc = (fabsy1y2 > fabsy2y3) ?
                m1 * (xc - mx1) + my1 :
                m2 * (xc - mx2) + my2;
        }

        dx = x2 - xc;
        dy = y2 - yc;
        return {
            i: i,
            j: j,
            k: k,
            x: xc,
            y: yc,
            r: dx * dx + dy * dy
        };
    }

    function dedup(edges) {
        var i, j, a, b, m, n;

        for (j = edges.length; j;) {
            b = edges[--j];
            a = edges[--j];

            for (i = j; i;) {
                n = edges[--i];
                m = edges[--i];

                if ((a === m && b === n) || (a === n && b === m)) {
                    edges.splice(j, 2);
                    edges.splice(i, 2);
                    break;
                }
            }
        }
    }

    Delaunay = {
        triangulate: function(vertices, key) {
            var n = vertices.length,
                i, j, indices, st, open, closed, edges, dx, dy, a, b, c;

            /* Bail if there aren't enough vertices to form any triangles. */
            if (n < 3)
                return [];

            /* Slice out the actual vertices from the passed objects. (Duplicate the
             * array even if we don't, though, since we need to make a supertriangle
             * later on!) */
            vertices = vertices.slice(0);

            if (key)
                for (i = n; i--;)
                    vertices[i] = vertices[i][key];

            /* Make an array of indices into the vertex array, sorted by the
             * vertices' x-position. */
            indices = new Array(n);

            for (i = n; i--;)
                indices[i] = i;

            indices.sort(function(i, j) {
                return vertices[j][0] - vertices[i][0];
            });

            /* Next, find the vertices of the supertriangle (which contains all other
             * triangles), and append them onto the end of a (copy of) the vertex
             * array. */
            st = supertriangle(vertices);
            vertices.push(st[0], st[1], st[2]);

            /* Initialize the open list (containing the supertriangle and nothing
             * else) and the closed list (which is empty since we havn't processed
             * any triangles yet). */
            open = [circumcircle(vertices, n + 0, n + 1, n + 2)];
            closed = [];
            edges = [];

            /* Incrementally add each vertex to the mesh. */
            for (i = indices.length; i--; edges.length = 0) {
                c = indices[i];

                /* For each open triangle, check to see if the current point is
                 * inside it's circumcircle. If it is, remove the triangle and add
                 * it's edges to an edge list. */
                for (j = open.length; j--;) {
                    /* If this point is to the right of this triangle's circumcircle,
                     * then this triangle should never get checked again. Remove it
                     * from the open list, add it to the closed list, and skip. */
                    dx = vertices[c][0] - open[j].x;
                    if (dx > 0.0 && dx * dx > open[j].r) {
                        closed.push(open[j]);
                        open.splice(j, 1);
                        continue;
                    }

                    /* If we're outside the circumcircle, skip this triangle. */
                    dy = vertices[c][1] - open[j].y;
                    if (dx * dx + dy * dy - open[j].r > EPSILON)
                        continue;

                    /* Remove the triangle and add it's edges to the edge list. */
                    edges.push(
                        open[j].i, open[j].j,
                        open[j].j, open[j].k,
                        open[j].k, open[j].i
                    );
                    open.splice(j, 1);
                }

                /* Remove any doubled edges. */
                dedup(edges);

                /* Add a new triangle for each edge. */
                for (j = edges.length; j;) {
                    b = edges[--j];
                    a = edges[--j];
                    open.push(circumcircle(vertices, a, b, c));
                }
            }

            /* Copy any remaining open triangles to the closed list, and then
             * remove any triangles that share a vertex with the supertriangle,
             * building a list of triplets that represent triangles. */
            for (i = open.length; i--;)
                closed.push(open[i]);
            open.length = 0;

            for (i = closed.length; i--;)
                if (closed[i].i < n && closed[i].j < n && closed[i].k < n)
                    open.push(closed[i].i, closed[i].j, closed[i].k);

                /* Yay, we're done! */
            return open;
        },
        contains: function(tri, p) {
            /* Bounding box test first, for quick rejections. */
            if ((p[0] < tri[0][0] && p[0] < tri[1][0] && p[0] < tri[2][0]) ||
                (p[0] > tri[0][0] && p[0] > tri[1][0] && p[0] > tri[2][0]) ||
                (p[1] < tri[0][1] && p[1] < tri[1][1] && p[1] < tri[2][1]) ||
                (p[1] > tri[0][1] && p[1] > tri[1][1] && p[1] > tri[2][1]))
                return null;

            var a = tri[1][0] - tri[0][0],
                b = tri[2][0] - tri[0][0],
                c = tri[1][1] - tri[0][1],
                d = tri[2][1] - tri[0][1],
                i = a * d - b * c;

            /* Degenerate tri. */
            if (i === 0.0)
                return null;

            var u = (d * (p[0] - tri[0][0]) - b * (p[1] - tri[0][1])) / i,
                v = (a * (p[1] - tri[0][1]) - c * (p[0] - tri[0][0])) / i;

            /* If we're outside the tri, fail. */
            if (u < 0.0 || v < 0.0 || (u + v) > 1.0)
                return null;

            return [u, v];
        }
    };

    if (typeof module !== "undefined")
        module.exports = Delaunay;
})();
var Sketch = {
    rezizeTimer: null,
    init: function() {
        var me = this;
        this.canvas = document.getElementById('headerEffect');
        this.setViewport();
        window.onresize = function() {
            clearTimeout(me.rezizeTimer);
            me.rezizeTimer = setTimeout(function() {
                me.setViewport.call(me);
                me.createVertices.call(me);
                me.render.call(me);
            }, 200);

        };
        this.createVertices();
        this.render();
    },
    createVertices: function() {
        var i, x, y,
            gradient;
        this.vertices = new Array(~~(window.innerHeight / window.innerWidth * 64));
        for (i = this.vertices.length; i--;) {
            do {
                x = Math.random() - 0.5;
                y = Math.random() - 0.5;
                gradient = {
                    color: Math.random() - 0.5 > 0 ?
                        'rgba(255,255,255,0.1)' : 'rgba(0,0,0,0.1)'
                };
            } while (x * x + y * y > 0.25);

            x = (x * 2.96875 + 0.5) * this.canvas.width;
            y = (y * 2.96875 + 0.5) * this.canvas.height;

            this.vertices[i] = [x, y, gradient];
        }
    },
    render: function() {
        var ctx = this.canvas.getContext('2d');

        ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);

        var triangles = Delaunay.triangulate(this.vertices);

        for (i = triangles.length; i;) {
            ctx.beginPath();
            var x1 = this.vertices[triangles[--i]][0],
                y1 = this.vertices[triangles[i]][1],
                x2 = this.vertices[triangles[--i]][0],
                y2 = this.vertices[triangles[i]][1],
                x3 = this.vertices[triangles[--i]][0],
                y3 = this.vertices[triangles[i]][1];

            ctx.moveTo(x1, y1);
            ctx.lineTo(x2, y2);
            ctx.lineTo(x3, y3);

            var grd = ctx.createLinearGradient(x1, y1, x2, y3);
            // light blue
            grd.addColorStop(0, this.vertices[triangles[i]][2].color);
            // dark blue
            grd.addColorStop(1, 'transparent');
            ctx.closePath();
            ctx.fillStyle = grd;
            ctx.fill();
            //ctx.stroke();
        }
    },
    setViewport: function() {
        this.canvas.width = window.innerWidth;
        this.canvas.height = window.innerHeight;
    }
};
Sketch.init();


/**
 * Menu location links
 *  - Home
 *  - Cars
 *  - Shop
 *  - Versus
 *  - Blog
 *  - Social network
 *  - Register page
 *  - Create notice post
 */
function clickThePage(type) {
    setTimeout(function() {
        if (type === 1) {
            document.location.href = url;
        } else if (type === 2) {
            document.location.href = url + "cars/";
        } else if (type === 3) {
            document.location.href = url + "shop/";
        } else if (type === 4) {
            document.location.href = url + "versus/";
        } else if (type === 5) {
            document.location.href = url + "blog/";
        } else if (type === 6) {
            document.location.href = url + "social-network/";
        } else if (type === 7) {
            document.location.href = url + "register";
        } else if (type === 8) {
            document.location.href = url + "notice-post";
        } else if (type === 9) {
            document.location.href = url + "shop-post";
        } else if (type === 10) {
            document.location.href = url + "blog-post";
        } else if (type === 11) {
            document.location.href = url + "my";
        } else if (type === 12) {
            document.location.href = url + "settings.php";
        } else if (type === 13) {
            document.location.href = url + "messages.php";
        } else if (type === 14) {
            document.location.href = url + "friends.php";
        } else if (type === 15) {
            document.location.href = url + "music.php";
        } else if (type === 16) {
            document.location.href = url + "videos.php";
        } else if (type === 17) {
            document.location.href = url + "photos.php";
        }
    }, 350);
}


/**
 * MENU NAV Splash menu bar click effect
 */
$(function() {
    // Setup Line
    $('.papertabs').append("<li id='papertabs-line'></li>");
    var $line = $('#papertabs-line');
    var $activeItem = $('.papertabs .active').parent();
    var $activeX = $('.papertabs .active').parent().position().left;
    $line.width($activeItem.width()).css("left", $activeX);

    // Click Event
    $('.papertabs a').click(function(e) {

        var $el = $(this);
        var $offset = $el.offset();
        var $clickX = e.pageX - $offset.left;
        var $clickY = e.pageY - $offset.top;
        var $parentX = $el.parent().position().left;
        var $elWidth = $el.parent().width();

        e.preventDefault();

        $('.papertabs .active').removeClass('active');
        $el.addClass('pressed active');

        $el.find('.circleNav').css({
            left: $clickX + 'px',
            top: $clickY + 'px'
        });

        $line.animate({
            left: $parentX,
            width: $elWidth
        });

        $el.on("animationend webkitAnimationEnd oAnimationEnd MSAnimationEnd", function() {
            $el.removeClass('pressed').addClass('focused');
            setTimeout(function() {
                $el.removeClass('focused');
            }, 300);
        });
    });
});


/**
 * Show/Hide Logout window & fade out effect
 */
function logOut() {
    $('.logOutWindow').toggleClass('logOutWindowActive');
    $('.logOutWindowHidden').toggleClass('logOutWindowHiddenActive');
}

function logOutFade() {
    $('.logOutWindow').addClass('logOutFadeOut');
    $('.logOutWindowHidden').addClass('logOutFadeOut');

    setTimeout(function() {
        $('.logOutWindow').removeClass('logOutWindowActive');
        $('.logOutWindowHidden').removeClass('logOutWindowHiddenActive');
    }, 300);

    setTimeout(function() {
        $('.logOutWindow').removeClass('logOutFadeOut');
        $('.logOutWindowHidden').removeClass('logOutFadeOut');
    }, 1000);

    $('.confirmationDeleteBox').removeClass('confirmationDeleteBoxShow');
    $('body').removeClass('bodyFixed');
}


/**
 * Show/Hide Signin access window
 */
function singInAccess() {
    $('.signInBox').toggleClass('signInBoxActive');
    $('.signInBoxHidden').toggleClass('signInBoxHiddenActive');
}


/**
 * Signin login form access
 */
function loginAccess(name, password, checkbox) {
    if (name == "" || password == "") {
        $('.error').fadeIn(300).html('Complete the Fields');
        setTimeout(function() {
            $('.error').fadeOut(500);
        }, 3000);

        return false;
    } else {
        $.ajax({
            type: 'POST',
            url: url + 'includes/arrancar.php',
            data: 'name=' + name + '&password=' + password + '&recordar=' + checkbox,
            success: function(html) {
                if (html != "false") {
                    $('.signInLoading').val("Loading...");
                    location.reload();
                    return true;
                } else if (html == "false") {
                    $('.error').fadeIn(300);
                    setTimeout(function() {
                        $('.error').fadeOut(500);
                    }, 3000);
                    $('.error').html('Email or Password is incorrect');
                    return false;
                }
            }
        });
    }
}


/**
 * Register / Create an account
 */
//Valid name
function registerName(name) {
    loaderName.show();

    if (loaderName.is(':visible')) {
        yesName.hide();
        noName.hide();
    }

    setTimeout(function() {
        if (name.length > 0) {
            yesName.show();
            noName.hide();
            loaderName.hide();
        } else {
            yesName.hide();
            noName.show();
            loaderName.hide();
        }
    }, 1600);
}

//Valid email
function registerEmail(email) {

    if (email.indexOf("@") > 0) {

        loaderEmail.show();

        if (loaderEmail.is(':visible')) {
            yesEmail.hide();
            noEmail.hide();
            padlockEmail.hide();
        }

        setTimeout(function() {
            if (validateEmail(email)) {
                $.ajax({
                    type: 'POST',
                    url: url + 'pages/register/check-register.php',
                    data: 'email=' + email,
                    success: function(html) {
                        if (html === "false") {
                            padlockEmail.show();
                            noEmail.hide();
                            yesEmail.hide();
                            loaderEmail.hide();
                            return false;

                        } else {
                            padlockEmail.hide();
                            noEmail.hide();
                            yesEmail.show();
                            loaderEmail.hide();
                        }
                    }
                });
            } else {
                noEmail.show();
                yesEmail.hide();
                loaderEmail.hide();
                padlockEmail.hide();
            }
        }, 1600);

    } else if (email.length > 0) {
        loaderEmail.show();
        noEmail.hide();


        setTimeout(function() {
            loaderEmail.hide();
            noEmail.show();
        }, 1600);
    } else {
        noEmail.hide();
        yesEmail.hide();
    }
}

//Email validation key
function validateEmail(data) {
    var validar = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return validar.test(data);
}

//Valid password
function registerPassword(pass) {

    loaderPass.show();

    if (loaderPass.is(':visible')) {
        yesPass.hide();
        noPass.hide();
    }

    setTimeout(function() {
        if (pass.length > 1) {
            yesPass.show();
            noPass.hide();
            loaderPass.hide();
        } else {
            yesPass.hide();
            noPass.show();
            loaderPass.hide();
        }
    }, 1600);
}

//Valid captcha
function registerCaptcha(suma) {

    loaderCaptcha.show();

    if (loaderCaptcha.is(':visible')) {
        yesCaptcha.hide();
        noCaptcha.hide();
    }
    setTimeout(function() {
        if (suma != valueCaptcha) {
            noCaptcha.show();
            yesCaptcha.hide();
            loaderCaptcha.hide();
        } else {
            noCaptcha.hide();
            yesCaptcha.show();
            loaderCaptcha.hide();
        }
    }, 1600);
}

//Complete register validation
function registro(nombre, email, password, suma) {

    if (nombre === "") {

        loaderName.show();
        noName.hide();
        yesName.hide();

        setTimeout(function() {
            if (loaderName.is(':visible')) {
                loaderName.hide();
                noName.show();
            }
        }, 1600);
    }

    if (email === "") {

        loaderEmail.show();
        noEmail.hide();
        yesEmail.hide();

        setTimeout(function() {
            if (loaderEmail.is(':visible')) {
                loaderEmail.hide();
                noEmail.show();
            }
        }, 1600);
    }

    if (password === "") {

        loaderPass.show();
        noPass.hide();
        yesPass.hide();

        setTimeout(function() {
            if (loaderPass.is(':visible')) {
                loaderPass.hide();
                noPass.show();
            }
        }, 1600);
    }

    if (suma === "") {

        loaderCaptcha.show();
        noCaptcha.hide();
        yesCaptcha.hide();

        setTimeout(function() {
            if (loaderCaptcha.is(':visible')) {
                loaderCaptcha.hide();
                noCaptcha.show();
            }
        }, 1600);
    }

    function checkRegisterNo() {

        $('.buttonRegister').css('background', '#F24235').addClass('buttonRegisterChekingNo').val('');

        setTimeout(function() {
            $('.buttonRegister').css('border-radius', '50%').css('background', 'none');
        }, 120);

        setTimeout(function() {
            $('.buttonRegister').addClass('buttonRegisterChekingPumping');
        }, 400);

        setTimeout(function() {
            $('.buttonRegister').css('background', '#0C98F5').removeClass('buttonRegisterChekingNo').val('Sign Up');
            $('.buttonRegister').removeClass('buttonRegisterChekingPumping');
            $('.buttonRegister').css('border-radius', '3px');
        }, 2400);
    }

    function checkRegisterYes() {

        $('.buttonRegister').css('background', '#0C98F5').addClass('buttonRegisterChekingYes').val('');

        setTimeout(function() {
            $('.buttonRegister').css('border-radius', '50%').css('background', 'none');
        }, 120);

        setTimeout(function() {
            $('.buttonRegister').addClass('buttonRegisterChekingPumping');
        }, 400);

        setTimeout(function() {
            $('.buttonRegister').css('background', '#0C98F5').removeClass('buttonRegisterChekingYes').val('WELCOME');
            $('.buttonRegister').removeClass('buttonRegisterChekingPumping');
            $('.buttonRegister').css('border-radius', '3px');
        }, 2400);
    }

    if (nombre === "") {

        checkRegisterNo();

    } else if (password === "") {

        checkRegisterNo();

    } else if (suma != valueCaptcha) {

        checkRegisterNo();

    } else {

        if (email.indexOf("@") > 0) {
            if (validateEmail(email)) {

                $.ajax({
                    type: 'POST',
                    url: url + 'pages/register/check-register.php',
                    data: 'nombre=' + nombre + '&email=' + email + '&password=' + password + '&suma=' + suma,
                    success: function(html) {
                        if (html === "false") {
                            noEmail.hide();
                            padlockEmail.hide();
                            loaderEmail.show();
                            checkRegisterNo();

                            setTimeout(function() {
                                padlockEmail.show();
                                loaderEmail.hide();
                            }, 1600);
                            return false;
                        } else {

                            checkRegisterYes();

                            setTimeout(function() {
                                location.href = url;
                            }, 3200);
                        }
                    }
                });

            } else {
                noEmail.hide();
                loaderEmail.show();
                checkRegisterNo();

                setTimeout(function() {
                    noEmail.show();
                    loaderEmail.hide();
                }, 1600);
            }

        } else {
            noEmail.hide();
            loaderEmail.show();
            checkRegisterNo();

            setTimeout(function() {
                noEmail.show();
                loaderEmail.hide();
            }, 1600);
        }
    }
}


/**
 * Create post MultiUpload Images
 *  - multi image uploader
 *  - Delete temporal image on creteNoticePost
 *  - slide from Right side the edit image window
 */
// Multi image uploader
if ($('#uploadImagesNoticePost').length > 0) {
    window.onload = function() {
        var fileUpload = document.getElementById("uploadImagesNoticePost");
        fileUpload.onchange = function() {
            if (typeof(FileReader) != "undefined") {
                var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.jpg|.jpeg|.gif|.png|.bmp)$/;
                for (var i = 0; i < fileUpload.files.length; i++) {
                    var file = fileUpload.files[i];
                    if (regex.test(file.name.toLowerCase())) {
                        var reader = new FileReader();
                        console.log('reader-->', reader);
                        $('.uploadImages').html('Loading...');
                        reader.onload = function(e) {
                            var img = document.createElement("IMG");
                            img.src = e.target.result;
                            var dataImg = img.src;

                            $.post(url + 'pages/postFunctions/subidaTemporal.php', {
                                dataImagen: dataImg
                            }).done(function(htmlress) {
                                $('.imgPreviewArea').append(htmlress);

                                $(".imgPreviewArea img").on("load", function() {
                                    $('.uploadImages').html('<svg xmlns="http://www.w3.org/2000/svg" width="0" height="0" viewBox="0 0 48 48"><path d="M38 26H26v12h-4V26H10v-4h12V10h4v12h12v4z"/></svg><svg xmlns="http://www.w3.org/2000/svg" width="0" height="0" viewBox="0 0 48 48"><circle cx="24" cy="24" r="6.4"/><path d="M18 4l-3.66 4H8c-2.21 0-4 1.79-4 4v24c0 2.21 1.79 4 4 4h32c2.21 0 4-1.79 4-4V12c0-2.21-1.79-4-4-4h-6.34L30 4H18zm6 30c-5.52 0-10-4.48-10-10s4.48-10 10-10 10 4.48 10 10-4.48 10-10 10z"/></svg> add Photos');
                                });
                            });
                        };
                        reader.readAsDataURL(file);
                    } else {
                        alert(file.name + " is not a valid image file.");
                        return false;
                    }
                }
            } else {
                alert("This browser does not support HTML5 FileReader.");
            }
        };
    };
}

/**
 * Create notice post content
 *  - Focus at the end off image
 *  - Editor
 *  - Set title color
 *  - Upload image
 */
// focus at the end off image
if ($('#imagenEditCreateNoticePost').length > 0) {

    function focus_final(el) {
        el.focus();
        if (typeof window.getSelection != "undefined" && typeof document.createRange != "undefined") {
            var range = document.createRange();
            range.selectNodeContents(el);
            range.collapse(false);
            var sel = window.getSelection();
            sel.removeAllRanges();
            sel.addRange(range);
        } else if (typeof document.body.createTextRange != "undefined") {
            var textRange = document.body.createTextRange();
            textRange.moveToElementText(el);
            textRange.collapse(false);
            textRange.select();
        }
    }

    function postOperations(valor, idimagen) {
        //Enviar post
        if (valor == 1) {
            var titulo = $('#titlePost').val();

            var countryVal = $('#countries_states1 option:selected').text();
            $('#countryPost').val(countryVal);

            var cityVal = $('#cityPostSelect option:selected').text();
            $('#cityPost').val(cityVal);

            if (titulo.split(' ').join('') != '') {
                var prevPost = $('#mensaje').html();
                $('#contenidoPost').val(prevPost);
                return true;
            } else {
                console.log('Incompleto');
                return false;
            }
        }

        //Eliminar imagen del content
        else if (valor == 2) {
            $.ajax({
                type: 'POST',
                url: 'pages/postFunctions/subidaTemporalRemoveBBDD.php',
                data: 'id=' + idimagen
            });

            $('#image' + idimagen).remove();
        }
        //Eliminar imagen de las imagenes principales
        else if (valor == 3) {
            $.ajax({
                type: 'POST',
                url: url + 'pages/postFunctions/subidaTemporalRemoveBBDD.php',
                data: 'idtemporal=' + idimagen
            });
            $('#' + idimagen).hide();
        }
    }

    function el(id) {
        return document.getElementById(id);
    }

    function readImage() {
        if (this.files && this.files[0]) {
            var FR = new FileReader();
            FR.onload = function(e) {

                var imgData = e.target.result;
                $.post(url + 'pages/postFunctions/subidaImgContent.php', {
                    dataImagenContent: imgData
                }).done(function(htmlress) {
                    $('#mensaje').append(htmlress);
                    focus_final(document.getElementById("mensaje"));
                    $("#mensaje").animate({
                        scrollTop: $("#mensaje")[0].scrollHeight
                    });
                });

            };
            FR.readAsDataURL(this.files[0]);
        }
    }
    el("imagenEditCreateNoticePost").addEventListener("change", readImage, false);
}

// set title color
var inputColor = $('.createContainer .editBox .setColor');
var titleColor = $('.createContainer .title');
var color;

function editCurrentNoticeColorBox() {
    $('.createContainer .editBox').toggleClass('editBoxExpand');
}

function editTextColor(type) {
    if (type === 1) {
        color = 'F44336';
    }
    if (type === 2) {
        color = 'E91E63';
    }
    if (type === 3) {
        color = '9C27B0';
    }
    if (type === 4) {
        color = '673AB7';
    }
    if (type === 5) {
        color = '2196F3';
    }
    if (type === 6) {
        color = '00BCD4';
    }
    if (type === 7) {
        color = '009688';
    }
    if (type === 8) {
        color = '4CAF50';
    }
    if (type === 9) {
        color = 'CDDC39';
    }
    if (type === 10) {
        color = 'FFEB3B';
    }
    if (type === 11) {
        color = 'FF5722';
    }
    if (type === 12) {
        color = '607D8B';
    }
    if (type === 13) {
        color = 'FFFFFF';
    }
    if (type === 14) {
        color = '404040';
    }

    titleColor.css('color', '#' + color);
    inputColor.val(color);
}

function setEditTextColor(color) {
    titleColor.css('color', '#' + color);
    inputColor.val(color);

    if (color.length < 3) {
        color = 'fff';
        titleColor.css('color', '#' + color);
    }
}


//EDIT POST
function editPost(id) {
    location.href = url + "edit-post.php?id=" + id;
}
//DELETE POST
function deletePost(type, id) {
    if (type == 1) {
        $('.confirmationDeleteBox').toggleClass('confirmationDeleteBoxShow');
        $('.logOutWindowHidden').toggleClass('logOutWindowHiddenActive');
        $('body').toggleClass('bodyFixed');
    }
    if (type == 2) {
        location.href = url + "remove-post.php?id=" + id;
    }
}


/**
 * Cars page click effect
 *  - SquareBox Ripple Effect
 *  - timeOut onClick URL
 */
var addRippleEffect = function(e) {
    var target = e.target;
    if (target.tagName.toLowerCase() !== 'squarebox') return false;
    var rect = target.getBoundingClientRect();
    var ripple = target.querySelector('.ripplesquarebox');
    if (!ripple) {
        ripple = document.createElement('span');
        ripple.className = 'ripplesquarebox';
        ripple.style.height = ripple.style.width = Math.max(rect.width, rect.height) + 'px';
        target.appendChild(ripple);
    }
    ripple.classList.remove('showsquarebox');
    var top = e.pageY - rect.top - ripple.offsetHeight / 2 - document.body.scrollTop;
    var left = e.pageX - rect.left - ripple.offsetWidth / 2 - document.body.scrollLeft;
    ripple.style.top = top + 'px';
    ripple.style.left = left + 'px';
    ripple.classList.add('showsquarebox');
    return false;
};
document.addEventListener('click', addRippleEffect, false);



function createTimedLink(element, callback, timeout) {
    setTimeout(function() {
        callback(element);
    }, timeout);
    return false;
}

function myFunction(element) {
    window.location = element.href;
}