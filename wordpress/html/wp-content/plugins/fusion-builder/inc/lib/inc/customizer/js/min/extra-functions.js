var fusionTriggerResize=_.debounce(fusionResize,300),fusionTriggerScroll=_.debounce(fusionScroll,300),fusionTriggerLoad=_.debounce(fusionLoad,300);function fusionCustomizerGetSettings(){var i={};return void 0!==window.wp&&void 0!==window.wp.customize?window.wp.customize.get():("undefined"!=typeof FusionApp&&(void 0!==FusionApp.settings&&(i=jQuery.extend(i,FusionApp.settings)),void 0!==FusionApp.data&&void 0!==FusionApp.data.postMeta&&(i=jQuery.extend(i,FusionApp.data.postMeta))),i)}function fusionCustomizerColorLightnessAdjust(i,e){var n=jQuery.Color(i),t=Math.abs(e),o=0>t;return 1<t&&(t/=100),o?n.lightness("-="+t).toRgbaString():n.lightness("+="+t).toRgbaString()}function fusionCustomizerColorAlphaSet(i,e){var n=jQuery.Color(i),t=Math.abs(e);return 1<t&&(t/=100),n.alpha(t).toRgbaString()}function fusionCustomizerColorReadable(i,e){var n=jQuery.Color(i),t=Math.abs(e.threshold);return 1<t&&(t/=100),n.lightness()<t?e.dark:e.light}function fusionReturnStringIfTransparent(i,e){return"transparent"===i?"$"===e.transparent?i:e.transparent:0===jQuery.Color(i).alpha()?"$"===e.transparent?i:e.transparent:"$"===e.opaque?i:e.opaque}function fusionReturnStringIfSolid(i,e){return"transparent"===i?e.transparent:1===jQuery.Color(i).alpha()?e.opaque:e.transparent}function fusionReturnColorAlphaInt(i){return fusionReturnStringIfSolid(i,{opaque:0,transparent:1})}function fusionGlobalScriptSet(i,e){if(_.isUndefined(e.choice)||_.isUndefined(i[e.choice])||(i=i[e.choice]),_.isUndefined(e.callback)||_.isUndefined(window[e.callback])||!_.isFunction(window[e.callback])||(i=window[e.callback](i)),_.isUndefined(window.frames[0]))return i;if(e.condition&&e.condition[0]&&e.condition[1]&&e.condition[2]&&e.condition[3]&&e.condition[4])switch(e.condition[1]){case"===":i=fusionSanitize.getOption(e.condition[0])===e.condition[2]?e.condition[2].replace(/\$/g,i):e.condition[3].replace(/\$/g,i)}return _.isUndefined(window.frames[0][e.globalVar])&&(window.frames[0][e.globalVar]={}),_.isUndefined(e.id)?window.frames[0][e.globalVar]=i:window.frames[0][e.globalVar][e.id]=i,_.isUndefined(e.trigger)||_.each(e.trigger,function(i){fusionTriggerEvent(i),"function"==typeof window[i]?window[i]():"function"==typeof window.frames[0][i]&&window.frames[0][i]()}),_.isUndefined(e.runAfter)||_.each(e.runAfter,function(i){_.isFunction(i)&&window.frames[0][i]()}),i}function fusionTriggerEvent(i){"resize"===i?fusionTriggerResize():"scroll"===i?fusionTriggerScroll():"load"===i?fusionTriggerLoad():window.frames[0].dispatchEvent(new Event(i))}function fusionResize(){window.frames[0].dispatchEvent(new Event("resize"))}function fusionScroll(){window.frames[0].dispatchEvent(new Event("scroll"))}function fusionLoad(){window.frames[0].dispatchEvent(new Event("load"))}function fusionGetMediaQuery(i,e,n){var t,o=[],s="";return e||(e="only screen"),t=[e],_.each(i,function(i){"string"!=typeof i[0]?(t=[e],_.each(i,function(i){i[1]&&-1!==i[1].indexOf("px")&&-1===i[1].indexOf("dppx")&&(i[1]=parseInt(i[1],10)+"px"),t.push("("+i[0]+": "+i[1]+")")}),o.push(t.join(" and "))):(i[1]&&-1!==i.indexOf("px")&&-1===i.indexOf("dppx")&&(i[1]=parseInt(i[1],10)+"px"),t.push("("+i[0]+": "+i[1]+")"))}),_.isEmpty(o)||(s=o.join(", ")),s||(s=t.join(" and ")),n?"@media "+s:s}function fusionReturnMediaQuery(i){var e,n,t,o,s,a,r,u,d=360,f=0,p=fusionCustomizerGetSettings();switch("top"!==p.header_position&&(f=parseInt(p.side_header_width,10)),640<(e=parseInt(p.grid_main_break_point,10))&&(d=e-640),r=(a=(s=(o=(t=(n=e+f)-(u=parseInt(d/5,10)))-u)-u)-u)-u,i){case"fusion-max-1c":return fusionGetMediaQuery([["max-width",r+"px"]]);case"fusion-max-2c":return fusionGetMediaQuery([["max-width",a+"px"]]);case"fusion-min-2c-max-3c":return fusionGetMediaQuery([["min-width",a+"px"],["max-width",s+"px"]]);case"fusion-min-3c-max-4c":return fusionGetMediaQuery([["min-width",s+"px"],["max-width",o+"px"]]);case"fusion-min-4c-max-5c":return fusionGetMediaQuery([["min-width",o+"px"],["max-width",t+"px"]]);case"fusion-min-5c-max-6c":return fusionGetMediaQuery([["min-width",t+"px"],["max-width",n+"px"]]);case"fusion-min-shbp":return fusionGetMediaQuery([["min-width",parseInt(p.side_header_break_point,10)+"px"]]);case"fusion-max-shbp":return fusionGetMediaQuery([["max-width",parseInt(p.side_header_break_point,10)+"px"]]);case"fusion-max-sh-shbp":return fusionGetMediaQuery([["max-width",parseInt(f+parseInt(p.side_header_break_point,10),10)+"px"]]);case"fusion-max-sh-cbp":return fusionGetMediaQuery([["max-width",parseInt(f+parseInt(p.content_break_point,10),10)+"px"]]);case"fusion-max-sh-sbp":return fusionGetMediaQuery([["max-width",parseInt(f+parseInt(p.sidebar_break_point,10),10)+"px"]]);case"fusion-max-shbp-retina":return fusionGetMediaQuery([[["max-width",parseInt(p.side_header_break_point,10)+"px"],["-webkit-min-device-pixel-ratio","1.5"]],[["max-width",parseInt(p.side_header_break_point,10)+"px"],["min-resolution","144dpi"]],[["max-width",parseInt(p.side_header_break_point,10)+"px"],["min-resolution","1.5dppx"]]]);case"fusion-max-sh-640":return fusionGetMediaQuery([["max-width",parseInt(f+640,10)+"px"]]);case"fusion-max-shbp-18":return fusionGetMediaQuery([["max-width",parseInt(parseInt(p.side_header_break_point,10)-18,10)+"px"]]);case"fusion-max-shbp-32":return fusionGetMediaQuery([["max-width",parseInt(parseInt(p.side_header_break_point,10)-32,10)+"px"]]);case"fusion-min-sh-cbp":return fusionGetMediaQuery([["min-width",parseInt(f+parseInt(p.content_break_point,10),10)+"px"]]);case"fusion-max-sh-965-woo":return fusionGetMediaQuery([["max-width",parseInt(f+965,10)+"px"]]);case"fusion-max-sh-900-woo":return fusionGetMediaQuery([["max-width",parseInt(f+900,10)+"px"]]);case"fusion-max-cbp":return fusionGetMediaQuery([["max-width",parseInt(p.content_break_point,10)+"px"]]);case"fusion-min-768-max-1024":return fusionGetMediaQuery([["min-device-width","768px"],["max-device-width","1024px"]]);case"fusion-min-768-max-1024-p":return fusionGetMediaQuery([["min-device-width","768px"],["max-device-width","1024px"],["orientation","portrait"]]);case"fusion-min-768-max-1024-l":return fusionGetMediaQuery([["min-device-width","768px"],["max-device-width","1024px"],["orientation","landscape"]]);case"fusion-max-640":return fusionGetMediaQuery([["max-device-width","640px"]]);case"fusion-max-768":case"fusion-max-782":return fusionGetMediaQuery([["max-width","782px"]])}}function fusionGetPageOption(i){return!i||(0!==i.indexOf("pyre_")&&(i="pyre_"+i),_.isUndefined(FusionApp)||_.isUndefined(FusionApp.data.postMeta)||_.isUndefined(FusionApp.data.postMeta[i]))?"":FusionApp.data.postMeta[i]}function fusionCustomizerGetOption(i,e){var n=fusionGetPageOption(e),t=void 0!==fusionCustomizerGetSettings()[i]?fusionCustomizerGetSettings()[i]:"";return i&&e&&"default"!==n&&!_.isEmpty(n)?n:-1===t.indexOf("/")?t.toLowerCase():t}function fusionIsSiteWidthPercent(i){return fusionCustomizerGetSettings().site_width&&fusionCustomizerGetSettings().site_width.indexOf("%")?i:""}function fusionGetUnitsFromValue(i){return"string"==typeof i?i.replace(/\d+([,.]\d+)?/g,""):i}function fusionGetNumericValue(i){return parseFloat(i)}function fusionGetPercentPaddingHorizontal(i,e){return fusionCustomizerGetOption("hundredp_padding","hundredp_padding")||e}function fusionGetPercentPaddingHorizontalNegativeMargin(){var i=fusionGetPercentPaddingHorizontal(),e=fusionGetNumericValue(i),n="";return n="-"+i,"%"===fusionGetUnitsFromValue(i)&&(n="-"+(n=e/(100-2*e)*100)+"%"),n}function fusionGetPercentPaddingHorizontalNegativeMarginIfSiteWidthPercent(i,e){return fusionIsSiteWidthPercent()?fusionGetPercentPaddingHorizontalNegativeMargin():e}function fusionRecalcAllMediaQueries(){var i,e,n,t,o=["","avada-","fb-"],s=["","-bbpress","-gravity","-ec","-woo","-sliders","-eslider","-not-responsive","-cf7"];for(i in window.allFusionMediaIDs||(window.allFusionMediaIDs={},["max-sh-640","max-1c","max-2c","min-2c-max-3c","min-3c-max-4c","min-4c-max-5c","min-5c-max-6c","max-shbp","max-shbp-18","max-shbp-32","max-sh-shbp","min-768-max-1024-p","min-768-max-1024-l","max-sh-cbp","min-sh-cbp","max-sh-sbp","max-640","min-shbp"].forEach(function(i){o.forEach(function(e){s.forEach(function(n){window.allFusionMediaIDs[e+i+n+"-css"]=i})})})),window.allFusionMediaIDs)(e=window.frames[0].document.getElementById(i))&&(n=e.getAttribute("media"),(t=fusionReturnMediaQuery("fusion-"+window.allFusionMediaIDs[i]))!==n&&e.setAttribute("media",t))}function fusionRecalcVisibilityMediaQueries(){var i=fusionGetMediaQuery([["max-width",parseInt(fusionCustomizerGetOption("visibility_small"),10)+"px"]])+"{body:not(.fusion-builder-ui-wireframe) .fusion-no-small-visibility{display:none !important;}}",e=fusionGetMediaQuery([["min-width",parseInt(fusionCustomizerGetOption("visibility_small"),10)+"px"],["max-width",parseInt(fusionCustomizerGetOption("visibility_medium"),10)+"px"]])+"{body:not(.fusion-builder-ui-wireframe) .fusion-no-medium-visibility{display:none !important;}}",n=fusionGetMediaQuery([["min-width",parseInt(fusionCustomizerGetOption("visibility_medium"),10)+"px"]])+"{body:not(.fusion-builder-ui-wireframe) .fusion-no-large-visibility{display:none !important;}}";jQuery("#fb-preview").contents().find("head").find("#css-fb-visibility").length&&jQuery("#fb-preview").contents().find("head").find("#css-fb-visibility").remove(),jQuery("#fb-preview").contents().find("head").append('<style type="text/css" id="css-fb-visibility">'+i+e+n+"</style>")}