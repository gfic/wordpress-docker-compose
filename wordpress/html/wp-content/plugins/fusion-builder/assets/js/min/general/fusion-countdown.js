!function(o){"use strict";o.fn.fusion_countdown=function(){var t=o(this),e=t.attr("data-timer").split("-"),i=t.attr("data-gmt-offset"),n=t.attr("data-omit-weeks");t.countDown({gmtOffset:i,omitWeeks:n,targetDate:{year:e[0],month:e[1],day:e[2],hour:e[3],min:e[4],sec:e[5]}}),t.css("visibility","visible")}}(jQuery),jQuery(document).ready(function(){jQuery("body").hasClass("fusion-builder-live")||jQuery(".fusion-countdown-counter-wrapper").each(function(){var t=jQuery(this).attr("id");jQuery("#"+t).fusion_countdown()})});