angular.module("mailerlite.httpPrefix",["ui.bootstrap"]),angular.module("mailerlite.httpPrefix").directive("httpPrefix",["$timeout","$modal",function(a){return{restrict:"A",require:"ngModel",link:function(b,c,d,e){if(!$(c).data("httpPrefixDefined")){$(c).data("httpPrefixDefined",!0);var f=function(){var a=["^http://","^https://","^mailto:","^ftp://","^tel:","^skype:","^viber://","^well-pumper://","^cyclemap://","^revivall://","^//","^#","^\\{\\$.+\\}$"],b="";a.forEach(function(c,d){b+=c,d<a.length-1&&(b+="|")});return new RegExp(b,"i")}();c.on("blur",function(){var d=c.val();if(d){if(d.indexOf("@")>0&&-1==d.indexOf("//")&&-1==d.indexOf("mailto:"))return c.val("mailto:"+d),void a(function(){b.$apply(e.$setViewValue("mailto:"+d))});return f.test(d)?void 0:(c.val("http://"+d),void a(function(){b.$apply(e.$setViewValue("http://"+d))}))}})}}}}]).controller("ModalInstanceController",["$scope","$modalInstance","data",function(a,b,c){a.data=c,a.ok=function(){b.close(a.data)},a.cancel=function(){b.dismiss("cancel")}}]);