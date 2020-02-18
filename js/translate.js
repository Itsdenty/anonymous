angular.module("mailerlite.translate",[]),angular.module("mailerlite.translate").filter("translate",["$timeout","$http","translate","md5",function(a,b,c,d){function e(a,d){var e=c.translatesEndpointUrl;"i18n"===a&&(e="/translations/i18n_translations"),b({method:"GET",url:e,params:{category:a,locale:c.locale},headers:{"X-Mailer-Account":c["X-Mailer-Account"],"X-Mailer-Token":c["X-Mailer-Token"]}}).then(function(a){var b={};a.data.forEach(function(a){b[a.key]=a.value}),d(b)},function(a){d()})}function f(a,b){a&&(window.top.translatesToCreate[b]=window.top.translatesToCreate[b]||{},window.top.translatesToCreate[b][a]=1)}function g(a,b){if("string"!=typeof a)return a;if(b=b||c.defaultCategory,!h[b]){var g="mailerlite:translates:"+b+":"+c.locale;void 0===h[b]&&(h[b]=!1,e(b,function(a){h[b]=a||{},localStorage.setItem(g,JSON.stringify(h[b]))}));var i=localStorage.getItem(g);if(!i)return a;h[b]=JSON.parse(i)}var j=d.createHash(a);h[b][j]||(h[b][j]=a,f(a,b));var k=h[b][j]||a;return k=k.replace(/\[url\](.+?)\[\/url\]/g,function(a,b){return'<a href="'+b+'">'+b+"</a>"})}window.top.translatesToCreate={},window.mlTranslates=function(){console.log("mlTranslatesPending() - show pending translates"),console.log("mlTranslatesCreate() - create translates")},window.mlTranslatesPending=function(){console.log(window.top.translatesToCreate),console.log("mlTranslatesCreate() - create translates")},window.mlTranslatesCreate=function(){Object.keys(window.top.translatesToCreate).forEach(function(a){Object.keys(window.top.translatesToCreate[a]).forEach(function(d){console.log('Creating translate in category "'+a+'": '+d),b.post(c.missingTranslatesEndpointUrl,$.param({value:d,category:a}),{headers:{"X-Mailer-Account":c["X-Mailer-Account"],"X-Mailer-Token":c["X-Mailer-Token"],"Content-Type":"application/x-www-form-urlencoded; charset=UTF-8"}})})})};var h={};return g.$stateful=!0,g}]),angular.module("mailerlite.translate").factory("md5",[function(){"use strict";return{createHash:function(a){var b,c,d,e,f,g,h,i,j,k,l=function(a,b){return a<<b|a>>>32-b},m=function(a,b){var c,d,e,f,g;return e=2147483648&a,f=2147483648&b,c=1073741824&a,d=1073741824&b,g=(1073741823&a)+(1073741823&b),c&d?2147483648^g^e^f:c|d?1073741824&g?3221225472^g^e^f:1073741824^g^e^f:g^e^f},n=function(a,b,c){return a&b|~a&c},o=function(a,b,c){return a&c|b&~c},p=function(a,b,c){return a^b^c},q=function(a,b,c){return b^(a|~c)},r=function(a,b,c,d,e,f,g){return a=m(a,m(m(n(b,c,d),e),g)),m(l(a,f),b)},s=function(a,b,c,d,e,f,g){return a=m(a,m(m(o(b,c,d),e),g)),m(l(a,f),b)},t=function(a,b,c,d,e,f,g){return a=m(a,m(m(p(b,c,d),e),g)),m(l(a,f),b)},u=function(a,b,c,d,e,f,g){return a=m(a,m(m(q(b,c,d),e),g)),m(l(a,f),b)},v=function(a){var b,c,d="",e="";for(c=0;c<=3;c++)b=a>>>8*c&255,e="0"+b.toString(16),d+=e.substr(e.length-2,2);return d},w=[];for(w=function(a){for(var b,c=a.length,d=c+8,e=(d-d%64)/64,f=16*(e+1),g=new Array(f-1),h=0,i=0;i<c;)b=(i-i%4)/4,h=i%4*8,g[b]=g[b]|a.charCodeAt(i)<<h,i++;return b=(i-i%4)/4,h=i%4*8,g[b]=g[b]|128<<h,g[f-2]=c<<3,g[f-1]=c>>>29,g}(a),h=1732584193,i=4023233417,j=2562383102,k=271733878,b=w.length,c=0;c<b;c+=16)d=h,e=i,f=j,g=k,h=r(h,i,j,k,w[c+0],7,3614090360),k=r(k,h,i,j,w[c+1],12,3905402710),j=r(j,k,h,i,w[c+2],17,606105819),i=r(i,j,k,h,w[c+3],22,3250441966),h=r(h,i,j,k,w[c+4],7,4118548399),k=r(k,h,i,j,w[c+5],12,1200080426),j=r(j,k,h,i,w[c+6],17,2821735955),i=r(i,j,k,h,w[c+7],22,4249261313),h=r(h,i,j,k,w[c+8],7,1770035416),k=r(k,h,i,j,w[c+9],12,2336552879),j=r(j,k,h,i,w[c+10],17,4294925233),i=r(i,j,k,h,w[c+11],22,2304563134),h=r(h,i,j,k,w[c+12],7,1804603682),k=r(k,h,i,j,w[c+13],12,4254626195),j=r(j,k,h,i,w[c+14],17,2792965006),i=r(i,j,k,h,w[c+15],22,1236535329),h=s(h,i,j,k,w[c+1],5,4129170786),k=s(k,h,i,j,w[c+6],9,3225465664),j=s(j,k,h,i,w[c+11],14,643717713),i=s(i,j,k,h,w[c+0],20,3921069994),h=s(h,i,j,k,w[c+5],5,3593408605),k=s(k,h,i,j,w[c+10],9,38016083),j=s(j,k,h,i,w[c+15],14,3634488961),i=s(i,j,k,h,w[c+4],20,3889429448),h=s(h,i,j,k,w[c+9],5,568446438),k=s(k,h,i,j,w[c+14],9,3275163606),j=s(j,k,h,i,w[c+3],14,4107603335),i=s(i,j,k,h,w[c+8],20,1163531501),h=s(h,i,j,k,w[c+13],5,2850285829),k=s(k,h,i,j,w[c+2],9,4243563512),j=s(j,k,h,i,w[c+7],14,1735328473),i=s(i,j,k,h,w[c+12],20,2368359562),h=t(h,i,j,k,w[c+5],4,4294588738),k=t(k,h,i,j,w[c+8],11,2272392833),j=t(j,k,h,i,w[c+11],16,1839030562),i=t(i,j,k,h,w[c+14],23,4259657740),h=t(h,i,j,k,w[c+1],4,2763975236),k=t(k,h,i,j,w[c+4],11,1272893353),j=t(j,k,h,i,w[c+7],16,4139469664),i=t(i,j,k,h,w[c+10],23,3200236656),h=t(h,i,j,k,w[c+13],4,681279174),k=t(k,h,i,j,w[c+0],11,3936430074),j=t(j,k,h,i,w[c+3],16,3572445317),i=t(i,j,k,h,w[c+6],23,76029189),h=t(h,i,j,k,w[c+9],4,3654602809),k=t(k,h,i,j,w[c+12],11,3873151461),j=t(j,k,h,i,w[c+15],16,530742520),i=t(i,j,k,h,w[c+2],23,3299628645),h=u(h,i,j,k,w[c+0],6,4096336452),k=u(k,h,i,j,w[c+7],10,1126891415),j=u(j,k,h,i,w[c+14],15,2878612391),i=u(i,j,k,h,w[c+5],21,4237533241),h=u(h,i,j,k,w[c+12],6,1700485571),k=u(k,h,i,j,w[c+3],10,2399980690),j=u(j,k,h,i,w[c+10],15,4293915773),i=u(i,j,k,h,w[c+1],21,2240044497),h=u(h,i,j,k,w[c+8],6,1873313359),k=u(k,h,i,j,w[c+15],10,4264355552),j=u(j,k,h,i,w[c+6],15,2734768916),i=u(i,j,k,h,w[c+13],21,1309151649),h=u(h,i,j,k,w[c+4],6,4149444226),k=u(k,h,i,j,w[c+11],10,3174756917),j=u(j,k,h,i,w[c+2],15,718787259),i=u(i,j,k,h,w[c+9],21,3951481745),h=m(h,d),i=m(i,e),j=m(j,f),k=m(k,g);return(v(h)+v(i)+v(j)+v(k)).toLowerCase()}}}]),angular.module("mailerlite.translate").provider("translate",function(){var a={locale:"en-US",defaultCategory:"no_category"};return{setLocale:function(b){a.locale=b},setAuthorizationHeaders:function(b,c){a["X-MailerLite-Account"]=b,a["X-MailerLite-Token"]=c},setDefaultCategory:function(b){a.defaultCategory=b},setTranslatesEndpointUrl:function(b){a.translatesEndpointUrl=b},setMissingTranslatesEndpointUrl:function(b){a.missingTranslatesEndpointUrl=b},$get:function(){return a}}});