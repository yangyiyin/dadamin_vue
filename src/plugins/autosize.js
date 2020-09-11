
(function (doc, win) {
    window.no_dingding = true;

    var docEl = doc.documentElement
    var resizeEvt = 'orientationchange' in window ? 'orientationchange' : 'resize'

    // console.log(/(iPhone|iPad|iPod|iOS|Android)/i.test(navigator.userAgent));

    var recalc = function () {
        var clientWidth = docEl.clientWidth
        if (!clientWidth) return

        if (/(iPhone|iPad|iPod|iOS|Android)/i.test(navigator.userAgent)) { //移动端


        } else {

            window.from_pc = 1;
        }
        window.from_pc = 1;
        function getQueryVariable(variable)
        {
            var query = window.location.href.split('?')[1];
            if (!query) {
                return false;
            }
            var vars = query.split("&");
            for (var i=0;i<vars.length;i++) {
                var pair = vars[i].split("=");
                if(pair[0] == variable){return pair[1];}
            }
            return(false);
        }
        if (getQueryVariable('from') == 'pc') {
            window.from_pc = 1;
        }

        if (window.no_dingding || getQueryVariable('not_in_dingding')) {
            window.is_in_dingding = false;
        } else {
            window.is_in_dingding = true;
        }

        docEl.style.fontSize = 20*clientWidth/667 + 'px'
    }
    if (!doc.addEventListener) return
    recalc();
    win.addEventListener(resizeEvt, recalc, false)
    doc.addEventListener('DOMContentLoaded', recalc, false)
})(document, window)