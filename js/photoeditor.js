angular.module("ml.photoeditor",["mailerlite.httpPrefix"]).config(["$httpProvider",function(a){a.defaults.headers.common["X-MailerLite-Account"]=X_MAILER_ACCOUNT,a.defaults.headers.common["X-MailerLite-Token"]=X_MAILER_TOKEN}]).run(["amMoment",function(a){a.changeLocale(TRANSLATES_LOCALE)}]).run(["$rootScope",function(a){a.translateCategory="photoeditor"}]),angular.module("ml.photoeditor").controller("photoeditorController",["$scope","$window","$http","$cookies","$timeout","photoeditorService","photoeditor",function(a,b,c,d,e,f,g){e(function(){var b=document.getElementById("ml-photoeditor"),c=new PhotoEditorSDK.UI.DesktopUI({container:b,responsive:!0,logLevel:"log",license:'{"owner":"MailerLite","version":"2.1","enterprise_license":false,"available_actions":["magic","filter","transform","sticker","text","adjustments","brush","focus","frames","camera"],"features":["adjustment","filter","focus","overlay","transform","text","sticker","frame","brush","camera","textdesign","library","export"],"platform":"HTML5","app_identifiers":["*.mailerlite.com","*.e-mailer.lt"],"api_token":"8f0zpe3bCr3tdrzccbTGnQ","domains":["https://api.photoeditorsdk.com"],"issued_at":1548064735,"expires_at":null,"signature":"kiazRKToCquVRNRPwjF5UjLSqLdrdEkyRFwz5RvB5FblIxrHv4hG0gltJ6w3ls9EfM1xHB6obsCUfSmSGinpYVLo1S3GIuhfTOO91zJ+6i9Ub11jCocFfl3Si4tBOvGu/mRsuCiYiJyuC5usC1aAiHyiJrdmOOx7UtNcNUMsTZcs3wtDZRnMA+KsPUQBnd81KfC/ZZMvY+Zpjl4kaAMA4ZsaORn29JENWuH/maRve9vrK+WKwZDJ77v0ujAdeAW0v1BYCmLVrsuSV3RS3bhG68Sr4tCZCPs4MN0rhaWjua4LJdEZxnS35QhjnURY1vsDoM5oN+VjhxiycGGkWTziiK5GjV3hK2Gf/esaJv3gk3Xhy+ihyLQFDN5E6bhOquGYOLJpeOkbc0mB4LTJ6xLAdyuwmlHEQ/OI0mhyW4ecmCrt8r7PpBzl/ko9fMVfvlmtAE4jgzKwgdq+BuIUcelIWV1DmC+yDGFVGU95usnrx2JghF2lcgXOprbSRx12WK88MWW3Wxl6l6UWLBQZdKrkpkJGPvTUFAkZds0KGGDzOOCA3XrjIaeVnZcuPe+yoQEsKFc0CvuW4WmaAydoc/G5YHu4WBD9CSe7kduuugNq/AH1EHqRrTsf68ZgS21dePGLlPfEITXZbQEVGkfipi6uLKi7HbwKWz2lkj9nOqQ0oIo="}',editor:{image:a.image,displayCloseButton:!0,enableSave:!0,enableExport:!1,displayResizeMessage:!1,controlsOrder:[["transform","filter","adjustments","focus"],["text","textdesign","sticker","brush","frame","overlay"]],export:{type:"blob",download:!1,fileBasename:"image"},save:{download:!1},controlsOptions:{transform:{enableAcceptButton:!0}}},assets:{baseUrl:g.path+"../components/photoeditorsdk/assets"}});c.on("close",function(){a.deactivate()}),c.on("export",function(b){b.name="image.png",a.deactivate(b)}),c.on("save",function(b,d){a.hide=!0,c.export(!1)})}),a.deactivate=function(a){window.removePhotoeditorListeners(),f.deactivate(a)};var h=function(b){var c=window.event||b;27==(c.which||c.keyCode)&&a.deactivate()};document.addEventListener("keydown",h,!1),window.removePhotoeditorListeners=function(){document.removeEventListener("keydown",h,!1)}}]),angular.module("ml.photoeditor").provider("photoeditor",function(){var a=this;return a.path="/assets/plugins/photoeditor/views/",{setPath:function(b){a.path=b},$get:function(){return{path:a.path}}}}),angular.module("ml.photoeditor").factory("photoeditorService",["$animate","$timeout","$q","$http","$document","$compile","$controller","$rootScope","photoeditor",function(a,b,c,d,e,f,g,h,i){function j(b,c){return q=c,n.then(function(c){if(c=c.data,!o){if(o=angular.element(c),0===o.length)throw new Error("The template contains no elements; you need to wrap text nodes");m=h.$new(),b=b||{},m.image=b.image||null,b.$scope=m;g("photoeditorController",b);return o=f(c)(m),a.enter(o,p)}})}function k(a){if(!o)return c.when();q&&q(a),m.$destroy(),m=null,o.remove(),o=null}function l(){return!!o}var m,n,o,p=angular.element(document.body);n=d.get(i.path+"layout.html",{cache:!0});var q;return{activate:j,deactivate:k,active:l}}]);