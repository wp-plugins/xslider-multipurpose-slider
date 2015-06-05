var xSlider;app.controller("listCtrl",["$scope","$rootScope","$http","$location","$timeout","defaults","selectValues",function(e,o,t,n,a,r,i){e.globals={},e.projectsLoaded=!1,e.selectValues=i,e.safeApply=function(e){var o=this.$root.$$phase;"$apply"==o||"$digest"==o?e&&"function"==typeof e&&e():this.$apply(e)},e.updateGlobalsParams=function(o,t){e.globals[o]=t,e.updateGlobals()},e.updateGlobals=function(){var o={action:"ajax_router","do":"updateGlobals",globals:e.globals,wpnonce:xslider_ajax_router.wpnonce};jQuery.ajax({type:"POST",url:ajaxurl,data:o,success:function(){e.safeApply(function(){})},dataType:"json"})},e.getGlobals=function(){var o={action:"ajax_router","do":"getGlobals",wpnonce:xslider_ajax_router.wpnonce};jQuery.ajax({type:"POST",url:ajaxurl,data:o,success:function(o){e.safeApply(function(){e.globals=o})},dataType:"json"})},e.joinNewsletter=function(){var o={action:"ajax_router","do":"joinNewsletter",email:e.globalSettings.newsletterEmail,wpnonce:xslider_ajax_router.wpnonce};jQuery.ajax({type:"POST",url:ajaxurl,data:o,success:function(){TweenMax.set("#joined",{display:"inline-block",opacity:0}),TweenMax.to("#joined",.3,{opacity:1,ease:Linear.easeNone}),TweenMax.to("#joined",.3,{delay:3,opacity:0,ease:Linear.easeNone,onComplete:function(){TweenMax.set("#joined",{display:"none"}),e.closeNewsletterPanel(.5)}})},dataType:"json"})},e.toggleNewsletterPanel=function(){var o={action:"ajax_router","do":"toggleNewsletterPanel",wpnonce:xslider_ajax_router.wpnonce};jQuery.ajax({type:"POST",url:ajaxurl,data:o,success:function(o){o.result?e.openNewsletterPanel(.3):e.closeNewsletterPanel(.3)},dataType:"json"})},e.openNewsletterPanel=function(e){TweenMax.to("#newsletterPanel",e,{force3D:!0,height:"auto",paddingTop:20,paddingBottom:20})},e.closeNewsletterPanel=function(e){TweenMax.to("#newsletterPanel",e,{force3D:!0,height:0,paddingTop:0,paddingBottom:0})},e.updateGlobalSettings=function(){var o={action:"ajax_router","do":"updateGlobalSettings",globalSettings:e.globalSettings,wpnonce:xslider_ajax_router.wpnonce};jQuery.ajax({type:"POST",url:ajaxurl,data:o,success:function(){TweenMax.set("#globals_saved",{display:"inline-block",opacity:0}),TweenMax.to("#globals_saved",.3,{opacity:1,ease:Linear.easeNone}),TweenMax.to("#globals_saved",.3,{delay:3,opacity:0,ease:Linear.easeNone,onComplete:function(){TweenMax.set("#globals_saved",{display:"none"})}})},dataType:"json"})},e.loadGlobalSettings=function(){var o={action:"ajax_router","do":"loadGlobalSettings",wpnonce:xslider_ajax_router.wpnonce};jQuery.ajax({type:"POST",url:ajaxurl,data:o,success:function(o){e.safeApply(function(){e.globalSettings=o.globalSettings,e.globalSettings.newsletterPanel&&e.openNewsletterPanel(0)})},dataType:"json"})},e.loadProjects=function(){var o={action:"ajax_router","do":"loadProjects",wpnonce:xslider_ajax_router.wpnonce};jQuery.ajax({type:"POST",url:ajaxurl,data:o,success:function(o){e.safeApply(function(){e.projectsLoaded=!0,e.projects=o})},dataType:"json"})},e.duplicateProject=function(o){var t={action:"ajax_router","do":"duplicateProject",id:o.id,wpnonce:xslider_ajax_router.wpnonce};jQuery.ajax({type:"POST",url:ajaxurl,data:t,success:function(){e.loadProjects()},dataType:"json"})},e.exportSlider=function(e){var o={action:"ajax_router","do":"exportSlider",id:e.id,wpnonce:xslider_ajax_router.wpnonce};jQuery.ajax({type:"POST",url:ajaxurl,data:o,success:function(e){"ZipArchive"==e.error?alert("To be able to import/export sliders the ZipArchive php extension have to be installed. Please contact your webserver administrator."):document.location=e.path},dataType:"json"})},e.importSlider=function(){e.currentPopup="popup_import",e.showPopup(e.currentPopup)},e.importSelectFile=function(){jQuery("#fileSelector").click()},e.importFileSelected=function(o){e.safeApply(function(){e.fileToImport=o[0]})},e.deleteProject=function(o){e.currentPopup="popup_confirm",e.popup_confirm_do=e.doDeleteProject,e.projectToDelete=o,e.popup_confirm_text="Do you really want to delete the slider <strong>"+o.name+"</strong>?<br><br>",e.showPopup(e.currentPopup)},e.doDeleteProject=function(){var o={action:"ajax_router","do":"deleteProject",id:e.projectToDelete.id,wpnonce:xslider_ajax_router.wpnonce};jQuery.ajax({type:"POST",url:ajaxurl,data:o,success:function(){e.closePopup(),e.loadProjects()},dataType:"json"})},e.preview=function(o){var t={action:"ajax_router","do":"loadPreviewById",id:o,wpnonce:xslider_ajax_router.wpnonce};jQuery.ajax({type:"POST",url:ajaxurl,data:t,success:function(t){e.project=t.project,e.showPreview(t,o)},dataType:"json"})},e.previewActive=!1,e.showPreview=function(o,t){e.previewActive=!0,elContainer=jQuery("#preview_container"),elContainer.html(o.css+o.html+o.js),TweenMax.set("#xslider_"+t,{margin:"0 auto"}),e.currentPopup="popup_preview",e.currentPopupCloseCallback=function(){e.destroyPreview()},jQuery(window).trigger("resize"),e.previewLayout=xSliderPreview.layoutManager(),TweenMax.to("#preview_container",0,{height:e.previewLayout.layoutHeight}),e.showPopup("popup_preview",function(){TweenMax.killAll(!1,!0,!0,!0),xSliderPreview.play(0,!1),TweenMax.to("#preview_container",0,{width:"100%",height:"auto"}),e.onResize()})},e.closePreview=function(){e.previewActive=!1,e.closePopup(e.destroyPreview)},e.destroyPreview=function(){xSliderPreview.destroy(),jQuery("#preview_container").find("*").each(function(){jQuery(this).unbind()}),jQuery("#preview_container").html(""),jQuery(window).off("resize",xSliderPreview.windowResizeHandler)},e.loadGoogleFonts=function(){var o={action:"ajax_router","do":"loadGoogleFonts",wpnonce:xslider_ajax_router.wpnonce};jQuery.ajax({type:"POST",url:ajaxurl,data:o,success:function(o){e.safeApply(function(){e.googleFonts=o;for(var t=0;t<e.googleFonts.length;t++)e.googleFonts[t].loadOnWebsite="true"===e.googleFonts[t].loadOnWebsite})},dataType:"json"})},e.addGoogleFont=function(){if("undefined"!=typeof e.fontToAdd&&""!=e.fontToAdd){var o=e.extractGoogleFontLink(e.fontToAdd);("undefined"==typeof e.googleFonts||0==e.googleFonts.length)&&(e.googleFonts=[]);var t=jQuery.grep(e.googleFonts,function(e){return e.url==o});t.length>0||(e.googleFonts.push({url:o,loadOnWebsite:!1}),e.updateGoogleFont(!0))}},e.extractGoogleFontLink=function(e){return jQuery(e).attr("href")},e.updateGoogleFont=function(o){("undefined"==typeof e.googleFonts||0==e.googleFonts.length)&&(e.googleFonts=[]);var t={action:"ajax_router","do":"updateGoogleFonts",fonts:e.googleFonts,wpnonce:xslider_ajax_router.wpnonce};jQuery.ajax({type:"POST",url:ajaxurl,data:t,success:function(){o&&e.loadGoogleFonts()},dataType:"json"})},e.deleteGoogleFont=function(o){e.currentPopup="popup_confirm",e.popup_confirm_do=e.doDeleteGoogleFont,e.fontToDelete=o,e.popup_confirm_text="Do you really want to delete the font?<br><br>",e.showPopup(e.currentPopup)},e.doDeleteGoogleFont=function(){e.googleFonts.splice(e.fontToDelete,1),e.updateGoogleFont(),e.closePopup()},e.getUsedFonts=function(){},e.gotoEditPage=function(e,o){document.location=e+"&id="+o},e.gotoEditPageGetTpl=function(e,o){document.location=e+"&id="+o+"&tpl=1"},e.showPopup=function(o,t){e.popupOpened=!0;var n=jQuery("#overlay"),a=jQuery("#"+o);n.show(0),a.show(0),TweenMax.fromTo(n,.3,{opacity:0},{autoAlpha:1,z:99,ease:Linear.easeNone}),TweenMax.fromTo(a,.3,{opacity:0},{autoAlpha:1,z:99,ease:Linear.easeNone,onComplete:function(){e.onResize(),"undefined"!=typeof t&&t()}}),e.onResize()},e.closePopup=function(o){e.popupOpened=!1,TweenMax.killAll(!1,!0,!0,!0),TweenMax.to(jQuery("#overlay"),.3,{autoAlpha:0,z:99,ease:Linear.easeNone}),TweenMax.to(jQuery(".popup"),.3,{autoAlpha:0,z:99,ease:Linear.easeNone,onComplete:function(){TweenMax.killAll(!1,!0,!0,!0),"undefined"!=typeof o&&o()}})},e.onResize=function(){var o=jQuery(window).width(),t=jQuery(window).height();if("popup_preview"==e.currentPopup){var n=e.project.width/e.project.height;if("responsive"==e.project.layoutSlide&&"responsive"==e.project.layoutSlideContainer&&"responsive"==e.project.layout){if("undefined"!=typeof e.project.layoutSlideContainerMaxWidth&&""!=e.project.layoutSlideContainerMaxWidth){var a=e.project.layoutSlideContainerMaxWidth/n,r=o-200;a>t-240&&(a=t-240)}else if("undefined"!=typeof e.project.layoutSlideMaxWidth&&""!=e.project.layoutSlideMaxWidth){var a=e.project.layoutSlideMaxWidth/n,r=o-200;a>t-240&&(a=t-240)}else{var r=o-200,a=r/n;a>t-240&&(a=t-240,r=a*n)}"undefined"!=typeof e.project.layoutSlideContainerMinWidth&&""!=e.project.layoutSlideContainerMinWidth&&e.project.layoutSlideContainerMinWidth>r&&(r=e.project.layoutSlideContainerMinWidth,a=r/n),"undefined"!=typeof e.project.layoutSlideMinWidth&&""!=e.project.layoutSlideMinWidth&&e.project.layoutSlideMinWidth>r&&(r=e.project.layoutSlideMinWidth,a=r/n),TweenMax.to("#preview_container",0,{width:r,height:a})}TweenMax.to("#"+e.currentPopup,0,{width:o-100,height:t-100,left:50,top:50});var i=xSliderPreview.layoutManager();jQuery("#preview_container").width()>i.slideWidth?TweenMax.to("#preview_container",0,{marginLeft:(jQuery("#preview_container").parent().width()-jQuery("#preview_container").width())/2}):TweenMax.to("#preview_container",0,{marginLeft:(jQuery("#preview_container").parent().width()-i.slideWidth)/2})}else e.currentPopup&&TweenMax.to("#"+e.currentPopup,0,{left:(o-jQuery("#"+e.currentPopup).width())/2,top:(t-jQuery("#"+e.currentPopup).height())/2});jQuery("#overlay").width(o).height(t)},e.init=function(){e.wpnonce=xslider_ajax_router.wpnonce,e.getGlobals(),e.loadProjects(),e.loadGoogleFonts(),e.loadGlobalSettings(),jQuery(window).on("resize",function(){e.onResize()}).trigger("resize"),jQuery(document).keyup(function(o){e.popupOpened&&27==o.keyCode&&(e.previewActive?e.closePreview():e.closePopup())})}}]),angular.element(document).ready(function(){angular.bootstrap(document,["app"]),jQuery("body").tooltip({selector:"*[tooltip]",show:{effect:"fade",duration:200},position:{my:"center bottom-10",at:"center top",using:function(e,o){jQuery(this).css(e),jQuery("<div>").addClass(o.vertical).addClass(o.horizontal).appendTo(this)}}})});