!function(){var e,t="angularUtils.directives.dirPagination",n="__default";try{e=angular.module(t)}catch(i){e=angular.module(t,[])}e.directive("dirPaginate",["$compile","$parse","$timeout","paginationService",function(e,t,i,a){return{terminal:!0,multiElement:!0,priority:5e3,compile:function(i,r){var o=r.dirPaginate,s=o.match(/^\s*([\s\S]+?)\s+in\s+([\s\S]+?)(?:\s+track\s+by\s+([\s\S]+?))?\s*$/),g=/\|\s*itemsPerPage\s*:[^|]*/;if(null===s[2].match(g))throw"pagination directive: the 'itemsPerPage' filter must be set.";var c=s[2].replace(g,""),u=t(c),l=r.paginationId||n;return a.registerInstance(l),function(i,r,s){var g=t(s.paginationId)(i)||s.paginationId||n;a.registerInstance(g);var c,l=!!o.match(/(\|\s*itemsPerPage\s*:[^|]*:[^|]*)/);c=g===n||l?o:o.replace(/(\|\s*itemsPerPage\s*:[^|]*)/,"$1 : '"+g+"'"),r[0].hasAttribute("dir-paginate-start")||r[0].hasAttribute("data-dir-paginate-start")?(s.$set("ngRepeatStart",c),r.eq(r.length-1).attr("ng-repeat-end",!0)):s.$set("ngRepeat",c);var d,p=e(r,!1,5e3);if(s.currentPage)d=t(s.currentPage);else{var P=g+"__currentPage";i[P]=1,d=t(P)}a.setCurrentPageParser(g,d,i),"undefined"!=typeof s.totalItems?(a.setAsyncModeTrue(g),i.$watch(function(){return t(s.totalItems)(i)},function(e){e>=0&&a.setCollectionLength(g,e)})):i.$watchCollection(function(){return u(i)},function(e){e&&a.setCollectionLength(g,e.length)}),p(i)}}}}]),e.directive("dirPaginationControls",["paginationService","paginationTemplate",function(e,t){function i(e,t,n,i){var r,o=[],s=Math.ceil(t/n),g=Math.ceil(i/2);r=g>=e?"start":e>s-g?"end":"middle";for(var c=s>i,u=1;s>=u&&i>=u;){var l=a(u,e,i,s),d=2===u&&("middle"===r||"end"===r),p=u===i-1&&("middle"===r||"start"===r);o.push(c&&(d||p)?"...":l),u++}return o}function a(e,t,n,i){var a=Math.ceil(n/2);return e===n?i:1===e?e:i>n?t>i-a?i-n+e:t>a?t-a+e:e:e}var r=/^\d+$/;return{restrict:"AE",templateUrl:function(e,n){return n.templateUrl||t.getPath()},scope:{maxSize:"=?",onPageChange:"&?",paginationId:"=?"},link:function(t,a,o){function s(n){u(n)&&(t.pages=i(n,e.getCollectionLength(d),e.getItemsPerPage(d),P),t.pagination.current=n,c(),t.onPageChange&&t.onPageChange({newPageNumber:n}))}function g(){var n=parseInt(e.getCurrentPage(d))||1;t.pages=i(n,e.getCollectionLength(d),e.getItemsPerPage(d),P),t.pagination.current=n,t.pagination.last=t.pages[t.pages.length-1],t.pagination.last<t.pagination.current?t.setCurrent(t.pagination.last):c()}function c(){var n=e.getCurrentPage(d),i=e.getItemsPerPage(d),a=e.getCollectionLength(d);t.range.lower=(n-1)*i+1,t.range.upper=Math.min(n*i,a),t.range.total=a}function u(e){return r.test(e)&&e>0&&e<=t.pagination.last}var l=o.paginationId||n,d=t.paginationId||o.paginationId||n;if(!e.isRegistered(d)&&!e.isRegistered(l)){var p=d!==n?" (id: "+d+") ":" ";throw"pagination directive: the pagination controls"+p+"cannot be used without the corresponding pagination directive."}t.maxSize||(t.maxSize=9),t.directionLinks=angular.isDefined(o.directionLinks)?t.$parent.$eval(o.directionLinks):!0,t.boundaryLinks=angular.isDefined(o.boundaryLinks)?t.$parent.$eval(o.boundaryLinks):!1;var P=Math.max(t.maxSize,5);t.pages=[],t.pagination={last:1,current:1},t.range={lower:1,upper:1,total:1},t.$watch(function(){return(e.getCollectionLength(d)+1)*e.getItemsPerPage(d)},function(e){e>0&&g()}),t.$watch(function(){return e.getItemsPerPage(d)},function(e,n){e!=n&&s(t.pagination.current)}),t.$watch(function(){return e.getCurrentPage(d)},function(e,t){e!=t&&s(e)}),t.setCurrent=function(t){u(t)&&e.setCurrentPage(d,t)}}}}]),e.filter("itemsPerPage",["paginationService",function(e){return function(t,i,a){if("undefined"==typeof a&&(a=n),!e.isRegistered(a))throw"pagination directive: the itemsPerPage id argument (id: "+a+") does not match a registered pagination-id.";var r,o;return t instanceof Array?(i=parseInt(i)||9999999999,o=e.isAsyncMode(a)?0:(e.getCurrentPage(a)-1)*i,r=o+i,e.setItemsPerPage(a,i),t.slice(o,r)):t}}]),e.service("paginationService",function(){var e,t={};this.registerInstance=function(n){"undefined"==typeof t[n]&&(t[n]={asyncMode:!1},e=n)},this.isRegistered=function(e){return"undefined"!=typeof t[e]},this.getLastInstanceId=function(){return e},this.setCurrentPageParser=function(e,n,i){t[e].currentPageParser=n,t[e].context=i},this.setCurrentPage=function(e,n){t[e].currentPageParser.assign(t[e].context,n)},this.getCurrentPage=function(e){var n=t[e].currentPageParser;return n?n(t[e].context):1},this.setItemsPerPage=function(e,n){t[e].itemsPerPage=n},this.getItemsPerPage=function(e){return t[e].itemsPerPage},this.setCollectionLength=function(e,n){t[e].collectionLength=n},this.getCollectionLength=function(e){return t[e].collectionLength},this.setAsyncModeTrue=function(e){t[e].asyncMode=!0},this.isAsyncMode=function(e){return t[e].asyncMode}}),e.provider("paginationTemplate",function(){var e="./js/dirPagination.tpl.html";this.setPath=function(t){e=t},this.$get=function(){return{getPath:function(){return e}}}})}();