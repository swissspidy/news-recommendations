this.NewsRecommendations=function(e){var t={};function n(o){if(t[o])return t[o].exports;var r=t[o]={i:o,l:!1,exports:{}};return e[o].call(r.exports,r,r.exports,n),r.l=!0,r.exports}return n.m=e,n.c=t,n.d=function(e,t,o){n.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:o})},n.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},n.t=function(e,t){if(1&t&&(e=n(e)),8&t)return e;if(4&t&&"object"==typeof e&&e&&e.__esModule)return e;var o=Object.create(null);if(n.r(o),Object.defineProperty(o,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var r in e)n.d(o,r,function(t){return e[t]}.bind(null,r));return o},n.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return n.d(t,"a",t),t},n.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},n.p="",n(n.s=5)}([function(e,t){!function(){e.exports=this.wp.i18n}()},function(e,t){!function(){e.exports=this.wp.element}()},function(e,t){!function(){e.exports=this.wp.components}()},function(e,t){!function(){e.exports=this.wp.blocks}()},function(e,t,n){},function(e,t,n){"use strict";n.r(t);var o=n(1),r=n(0),c=n(3),i=n(2);n(4);Object(c.registerBlockType)("news-recommendations/recommendation",{title:Object(r._x)("Recommendation ","block name","news-recommendations"),description:Object(r.__)("Enter the information for this recommendation.","news-recommendations"),icon:"paperclip",category:"common",keywords:[Object(r.__)("recommendation","news-recommendations"),Object(r.__)("source","news-recommendations")],supports:{customClassName:!1,html:!1,multiple:!1},attributes:{source:{type:"string",source:"meta",meta:"_recommendation_source"},url:{type:"string",source:"meta",meta:"_recommendation_url"}},edit:function(e){var t=e.className,n=e.attributes,c=n.source,s=n.url,u=e.setAttributes;return Object(o.createElement)("div",{className:t},Object(o.createElement)(i.TextControl,{label:Object(r.__)("Source","news-recommendations"),help:Object(r.__)("Type the name of the originating publication, e.g. New York Times","news-recommendations"),value:c,onChange:function(e){u({source:e})}}),Object(o.createElement)(i.TextControl,{label:Object(r.__)("URL","news-recommendations"),help:Object(r.__)("Enter the URL to the news story","news-recommendations"),value:s,type:"url",onChange:function(e){u({source:e})}}))},save:function(){return null}})}]);