parcelRequire=function(e,r,t,n){var i,o="function"==typeof parcelRequire&&parcelRequire,u="function"==typeof require&&require;function f(t,n){if(!r[t]){if(!e[t]){var i="function"==typeof parcelRequire&&parcelRequire;if(!n&&i)return i(t,!0);if(o)return o(t,!0);if(u&&"string"==typeof t)return u(t);var c=new Error("Cannot find module '"+t+"'");throw c.code="MODULE_NOT_FOUND",c}p.resolve=function(r){return e[t][1][r]||r},p.cache={};var l=r[t]=new f.Module(t);e[t][0].call(l.exports,p,l,l.exports,this)}return r[t].exports;function p(e){return f(p.resolve(e))}}f.isParcelRequire=!0,f.Module=function(e){this.id=e,this.bundle=f,this.exports={}},f.modules=e,f.cache=r,f.parent=o,f.register=function(r,t){e[r]=[function(e,r){r.exports=t},{}]};for(var c=0;c<t.length;c++)try{f(t[c])}catch(e){i||(i=e)}if(t.length){var l=f(t[t.length-1]);"object"==typeof exports&&"undefined"!=typeof module?module.exports=l:"function"==typeof define&&define.amd?define(function(){return l}):n&&(this[n]=l)}if(parcelRequire=f,i)throw i;return f}({"rrhY":[function(require,module,exports) {

},{}],"MQCK":[function(require,module,exports) {
var define;
var e;!function(){"use strict";var t=function(e){if(!(this instanceof t))return new t(e);if(this.version=1,this.support=!("undefined"==typeof File||"undefined"==typeof Blob||"undefined"==typeof FileList||!Blob.prototype.webkitSlice&&!Blob.prototype.mozSlice&&!Blob.prototype.slice),!this.support)return!1;var r=this;r.files=[],r.defaults={chunkSize:1048576,forceChunkSize:!1,simultaneousUploads:3,fileParameterName:"file",chunkNumberParameterName:"resumableChunkNumber",chunkSizeParameterName:"resumableChunkSize",currentChunkSizeParameterName:"resumableCurrentChunkSize",totalSizeParameterName:"resumableTotalSize",typeParameterName:"resumableType",identifierParameterName:"resumableIdentifier",fileNameParameterName:"resumableFilename",relativePathParameterName:"resumableRelativePath",totalChunksParameterName:"resumableTotalChunks",throttleProgressCallbacks:.5,query:{},headers:{},preprocess:null,method:"multipart",uploadMethod:"POST",testMethod:"GET",prioritizeFirstAndLastChunk:!1,target:"/",testTarget:null,parameterNamespace:"",testChunks:!0,generateUniqueIdentifier:null,getTarget:null,maxChunkRetries:100,chunkRetryInterval:void 0,permanentErrors:[400,404,415,500,501],maxFiles:void 0,withCredentials:!1,xhrTimeout:0,clearInput:!0,chunkFormat:"blob",setChunkTypeFromFile:!1,maxFilesErrorCallback:function(e,t){var n=r.getOpt("maxFiles");alert("Please upload no more than "+n+" file"+(1===n?"":"s")+" at a time.")},minFileSize:1,minFileSizeErrorCallback:function(e,t){alert(e.fileName||e.name+" is too small, please upload files larger than "+n.formatSize(r.getOpt("minFileSize"))+".")},maxFileSize:void 0,maxFileSizeErrorCallback:function(e,t){alert(e.fileName||e.name+" is too large, please upload files less than "+n.formatSize(r.getOpt("maxFileSize"))+".")},fileType:[],fileTypeErrorCallback:function(e,t){alert(e.fileName||e.name+" has type not allowed, please upload files of type "+r.getOpt("fileType")+".")}},r.opts=e||{},r.getOpt=function(e){var r=this;if(e instanceof Array){var i={};return n.each(e,function(e){i[e]=r.getOpt(e)}),i}if(r instanceof c){if(void 0!==r.opts[e])return r.opts[e];r=r.fileObj}if(r instanceof f){if(void 0!==r.opts[e])return r.opts[e];r=r.resumableObj}if(r instanceof t)return void 0!==r.opts[e]?r.opts[e]:r.defaults[e]},r.events=[],r.on=function(e,t){r.events.push(e.toLowerCase(),t)},r.fire=function(){for(var e=[],t=0;t<arguments.length;t++)e.push(arguments[t]);var n=e[0].toLowerCase();for(t=0;t<=r.events.length;t+=2)r.events[t]==n&&r.events[t+1].apply(r,e.slice(1)),"catchall"==r.events[t]&&r.events[t+1].apply(null,e);"fileerror"==n&&r.fire("error",e[2],e[1]),"fileprogress"==n&&r.fire("progress")};var n={stopEvent:function(e){e.stopPropagation(),e.preventDefault()},each:function(e,t){if(void 0!==e.length){for(var r=0;r<e.length;r++)if(!1===t(e[r]))return}else for(r in e)if(!1===t(r,e[r]))return},generateUniqueIdentifier:function(e,t){var n=r.getOpt("generateUniqueIdentifier");if("function"==typeof n)return n(e,t);var i=e.webkitRelativePath||e.fileName||e.name;return e.size+"-"+i.replace(/[^0-9a-zA-Z_-]/gim,"")},contains:function(e,t){var r=!1;return n.each(e,function(e){return e!=t||(r=!0,!1)}),r},formatSize:function(e){return e<1024?e+" bytes":e<1048576?(e/1024).toFixed(0)+" KB":e<1073741824?(e/1024/1024).toFixed(1)+" MB":(e/1024/1024/1024).toFixed(1)+" GB"},getTarget:function(e,t){var n=r.getOpt("target");if("test"===e&&r.getOpt("testTarget")&&(n="/"===r.getOpt("testTarget")?r.getOpt("target"):r.getOpt("testTarget")),"function"==typeof n)return n(t);var i=n.indexOf("?")<0?"?":"&";return n+i+t.join("&")}},i=function(e){n.stopEvent(e),e.dataTransfer&&e.dataTransfer.items?u(e.dataTransfer.items,e):e.dataTransfer&&e.dataTransfer.files&&u(e.dataTransfer.files,e)},a=function(e){e.preventDefault()};function s(e,t,r,n){var i;return e.isFile?e.file(function(e){e.relativePath=t+e.name,r.push(e),n()}):(e.isDirectory?i=e:e instanceof File&&r.push(e),"function"==typeof e.webkitGetAsEntry&&(i=e.webkitGetAsEntry()),i&&i.isDirectory?function(e,t,r,n){e.createReader().readEntries(function(e){if(!e.length)return n();o(e.map(function(e){return s.bind(null,e,t,r)}),n)})}(i,t+i.name+"/",r,n):("function"==typeof e.getAsFile&&(e=e.getAsFile())instanceof File&&(e.relativePath=t+e.name,r.push(e)),void n()))}function o(e,t){if(!e||0===e.length)return t();e[0](function(){o(e.slice(1),t)})}function u(e,t){if(e.length){r.fire("beforeAdd");var n=[];o(Array.prototype.map.call(e,function(e){return s.bind(null,e,"",n)}),function(){n.length&&l(n,t)})}}var l=function(e,t){var i=0,a=r.getOpt(["maxFiles","minFileSize","maxFileSize","maxFilesErrorCallback","minFileSizeErrorCallback","maxFileSizeErrorCallback","fileType","fileTypeErrorCallback"]);if(void 0!==a.maxFiles&&a.maxFiles<e.length+r.files.length){if(1!==a.maxFiles||1!==r.files.length||1!==e.length)return a.maxFilesErrorCallback(e,i++),!1;r.removeFile(r.files[0])}var s=[],o=[],u=e.length,l=function(){if(!--u){if(!s.length&&!o.length)return;window.setTimeout(function(){r.fire("filesAdded",s,o)},0)}};n.each(e,function(e){var u=e.name;if(a.fileType.length>0){var c=!1;for(var p in a.fileType){var d="."+a.fileType[p];if(-1!==u.toLowerCase().indexOf(d.toLowerCase(),u.length-d.length)){c=!0;break}}if(!c)return a.fileTypeErrorCallback(e,i++),!1}if(void 0!==a.minFileSize&&e.size<a.minFileSize)return a.minFileSizeErrorCallback(e,i++),!1;if(void 0!==a.maxFileSize&&e.size>a.maxFileSize)return a.maxFileSizeErrorCallback(e,i++),!1;function h(n){r.getFromUniqueIdentifier(n)?o.push(e):function(){e.uniqueIdentifier=n;var i=new f(r,e,n);r.files.push(i),s.push(i),i.container=void 0!==t?t.srcElement:null,window.setTimeout(function(){r.fire("fileAdded",i,t)},0)}(),l()}var m=n.generateUniqueIdentifier(e,t);m&&"function"==typeof m.then?m.then(function(e){h(e)},function(){l()}):h(m)})};function f(e,t,r){var i=this;i.opts={},i.getOpt=e.getOpt,i._prevProgress=0,i.resumableObj=e,i.file=t,i.fileName=t.fileName||t.name,i.size=t.size,i.relativePath=t.relativePath||t.webkitRelativePath||i.fileName,i.uniqueIdentifier=r,i._pause=!1,i.container="";var a=void 0!==r,s=function(e,t){switch(e){case"progress":i.resumableObj.fire("fileProgress",i,t);break;case"error":i.abort(),a=!0,i.chunks=[],i.resumableObj.fire("fileError",i,t);break;case"success":if(a)return;i.resumableObj.fire("fileProgress",i),i.isComplete()&&i.resumableObj.fire("fileSuccess",i,t);break;case"retry":i.resumableObj.fire("fileRetry",i)}};return i.chunks=[],i.abort=function(){var e=0;n.each(i.chunks,function(t){"uploading"==t.status()&&(t.abort(),e++)}),e>0&&i.resumableObj.fire("fileProgress",i)},i.cancel=function(){var e=i.chunks;i.chunks=[],n.each(e,function(e){"uploading"==e.status()&&(e.abort(),i.resumableObj.uploadNextChunk())}),i.resumableObj.removeFile(i),i.resumableObj.fire("fileProgress",i)},i.retry=function(){i.bootstrap();var e=!1;i.resumableObj.on("chunkingComplete",function(){e||i.resumableObj.upload(),e=!0})},i.bootstrap=function(){i.abort(),a=!1,i.chunks=[],i._prevProgress=0;for(var e=i.getOpt("forceChunkSize")?Math.ceil:Math.floor,t=Math.max(e(i.file.size/i.getOpt("chunkSize")),1),r=0;r<t;r++)!function(e){window.setTimeout(function(){i.chunks.push(new c(i.resumableObj,i,e,s)),i.resumableObj.fire("chunkingProgress",i,e/t)},0)}(r);window.setTimeout(function(){i.resumableObj.fire("chunkingComplete",i)},0)},i.progress=function(){if(a)return 1;var e=0,t=!1;return n.each(i.chunks,function(r){"error"==r.status()&&(t=!0),e+=r.progress(!0)}),e=t?1:e>.99999?1:e,e=Math.max(i._prevProgress,e),i._prevProgress=e,e},i.isUploading=function(){var e=!1;return n.each(i.chunks,function(t){if("uploading"==t.status())return e=!0,!1}),e},i.isComplete=function(){var e=!1;return n.each(i.chunks,function(t){var r=t.status();if("pending"==r||"uploading"==r||1===t.preprocessState)return e=!0,!1}),!e},i.pause=function(e){i._pause=void 0===e?!i._pause:e},i.isPaused=function(){return i._pause},i.resumableObj.fire("chunkingStart",i),i.bootstrap(),this}function c(e,t,r,i){var a=this;a.opts={},a.getOpt=e.getOpt,a.resumableObj=e,a.fileObj=t,a.fileObjSize=t.size,a.fileObjType=t.file.type,a.offset=r,a.callback=i,a.lastProgressCallback=new Date,a.tested=!1,a.retries=0,a.pendingRetry=!1,a.preprocessState=0;var s=a.getOpt("chunkSize");return a.loaded=0,a.startByte=a.offset*s,a.endByte=Math.min(a.fileObjSize,(a.offset+1)*s),a.fileObjSize-a.endByte<s&&!a.getOpt("forceChunkSize")&&(a.endByte=a.fileObjSize),a.xhr=null,a.test=function(){a.xhr=new XMLHttpRequest;var e=function(e){a.tested=!0;var t=a.status();"success"==t?(a.callback(t,a.message()),a.resumableObj.uploadNextChunk()):a.send()};a.xhr.addEventListener("load",e,!1),a.xhr.addEventListener("error",e,!1),a.xhr.addEventListener("timeout",e,!1);var t=[],r=a.getOpt("parameterNamespace"),i=a.getOpt("query");"function"==typeof i&&(i=i(a.fileObj,a)),n.each(i,function(e,n){t.push([encodeURIComponent(r+e),encodeURIComponent(n)].join("="))}),t=t.concat([["chunkNumberParameterName",a.offset+1],["chunkSizeParameterName",a.getOpt("chunkSize")],["currentChunkSizeParameterName",a.endByte-a.startByte],["totalSizeParameterName",a.fileObjSize],["typeParameterName",a.fileObjType],["identifierParameterName",a.fileObj.uniqueIdentifier],["fileNameParameterName",a.fileObj.fileName],["relativePathParameterName",a.fileObj.relativePath],["totalChunksParameterName",a.fileObj.chunks.length]].filter(function(e){return a.getOpt(e[0])}).map(function(e){return[r+a.getOpt(e[0]),encodeURIComponent(e[1])].join("=")})),a.xhr.open(a.getOpt("testMethod"),n.getTarget("test",t)),a.xhr.timeout=a.getOpt("xhrTimeout"),a.xhr.withCredentials=a.getOpt("withCredentials");var s=a.getOpt("headers");"function"==typeof s&&(s=s(a.fileObj,a)),n.each(s,function(e,t){a.xhr.setRequestHeader(e,t)}),a.xhr.send(null)},a.preprocessFinished=function(){a.preprocessState=2,a.send()},a.send=function(){var e=a.getOpt("preprocess");if("function"==typeof e)switch(a.preprocessState){case 0:return a.preprocessState=1,void e(a);case 1:return}if(!a.getOpt("testChunks")||a.tested){a.xhr=new XMLHttpRequest,a.xhr.upload.addEventListener("progress",function(e){new Date-a.lastProgressCallback>1e3*a.getOpt("throttleProgressCallbacks")&&(a.callback("progress"),a.lastProgressCallback=new Date),a.loaded=e.loaded||0},!1),a.loaded=0,a.pendingRetry=!1,a.callback("progress");var t=function(e){var t=a.status();if("success"==t||"error"==t)a.callback(t,a.message()),a.resumableObj.uploadNextChunk();else{a.callback("retry",a.message()),a.abort(),a.retries++;var r=a.getOpt("chunkRetryInterval");void 0!==r?(a.pendingRetry=!0,setTimeout(a.send,r)):a.send()}};a.xhr.addEventListener("load",t,!1),a.xhr.addEventListener("error",t,!1),a.xhr.addEventListener("timeout",t,!1);var r=[["chunkNumberParameterName",a.offset+1],["chunkSizeParameterName",a.getOpt("chunkSize")],["currentChunkSizeParameterName",a.endByte-a.startByte],["totalSizeParameterName",a.fileObjSize],["typeParameterName",a.fileObjType],["identifierParameterName",a.fileObj.uniqueIdentifier],["fileNameParameterName",a.fileObj.fileName],["relativePathParameterName",a.fileObj.relativePath],["totalChunksParameterName",a.fileObj.chunks.length]].filter(function(e){return a.getOpt(e[0])}).reduce(function(e,t){return e[a.getOpt(t[0])]=t[1],e},{}),i=a.getOpt("query");"function"==typeof i&&(i=i(a.fileObj,a)),n.each(i,function(e,t){r[e]=t});var s=a.fileObj.file.slice?"slice":a.fileObj.file.mozSlice?"mozSlice":a.fileObj.file.webkitSlice?"webkitSlice":"slice",o=a.fileObj.file[s](a.startByte,a.endByte,a.getOpt("setChunkTypeFromFile")?a.fileObj.file.type:""),u=null,l=[],f=a.getOpt("parameterNamespace");if("octet"===a.getOpt("method"))u=o,n.each(r,function(e,t){l.push([encodeURIComponent(f+e),encodeURIComponent(t)].join("="))});else if(u=new FormData,n.each(r,function(e,t){u.append(f+e,t),l.push([encodeURIComponent(f+e),encodeURIComponent(t)].join("="))}),"blob"==a.getOpt("chunkFormat"))u.append(f+a.getOpt("fileParameterName"),o,a.fileObj.fileName);else if("base64"==a.getOpt("chunkFormat")){var c=new FileReader;c.onload=function(e){u.append(f+a.getOpt("fileParameterName"),c.result),a.xhr.send(u)},c.readAsDataURL(o)}var p=n.getTarget("upload",l),d=a.getOpt("uploadMethod");a.xhr.open(d,p),"octet"===a.getOpt("method")&&a.xhr.setRequestHeader("Content-Type","application/octet-stream"),a.xhr.timeout=a.getOpt("xhrTimeout"),a.xhr.withCredentials=a.getOpt("withCredentials");var h=a.getOpt("headers");"function"==typeof h&&(h=h(a.fileObj,a)),n.each(h,function(e,t){a.xhr.setRequestHeader(e,t)}),"blob"==a.getOpt("chunkFormat")&&a.xhr.send(u)}else a.test()},a.abort=function(){a.xhr&&a.xhr.abort(),a.xhr=null},a.status=function(){return a.pendingRetry?"uploading":a.xhr?a.xhr.readyState<4?"uploading":200==a.xhr.status||201==a.xhr.status?"success":n.contains(a.getOpt("permanentErrors"),a.xhr.status)||a.retries>=a.getOpt("maxChunkRetries")?"error":(a.abort(),"pending"):"pending"},a.message=function(){return a.xhr?a.xhr.responseText:""},a.progress=function(e){void 0===e&&(e=!1);var t=e?(a.endByte-a.startByte)/a.fileObjSize:1;if(a.pendingRetry)return 0;switch(a.xhr&&a.xhr.status||(t*=.95),a.status()){case"success":case"error":return 1*t;case"pending":return 0*t;default:return a.loaded/(a.endByte-a.startByte)*t}},this}return r.uploadNextChunk=function(){var e=!1;if(r.getOpt("prioritizeFirstAndLastChunk")&&(n.each(r.files,function(t){return t.chunks.length&&"pending"==t.chunks[0].status()&&0===t.chunks[0].preprocessState?(t.chunks[0].send(),e=!0,!1):t.chunks.length>1&&"pending"==t.chunks[t.chunks.length-1].status()&&0===t.chunks[t.chunks.length-1].preprocessState?(t.chunks[t.chunks.length-1].send(),e=!0,!1):void 0}),e))return!0;if(n.each(r.files,function(t){if(!1===t.isPaused()&&n.each(t.chunks,function(t){if("pending"==t.status()&&0===t.preprocessState)return t.send(),e=!0,!1}),e)return!1}),e)return!0;var t=!1;return n.each(r.files,function(e){if(!e.isComplete())return t=!0,!1}),t||r.fire("complete"),!1},r.assignBrowse=function(e,t){void 0===e.length&&(e=[e]),n.each(e,function(e){var n;"INPUT"===e.tagName&&"file"===e.type?n=e:((n=document.createElement("input")).setAttribute("type","file"),n.style.display="none",e.addEventListener("click",function(){n.style.opacity=0,n.style.display="block",n.focus(),n.click(),n.style.display="none"},!1),e.appendChild(n));var i=r.getOpt("maxFiles");void 0===i||1!=i?n.setAttribute("multiple","multiple"):n.removeAttribute("multiple"),t?n.setAttribute("webkitdirectory","webkitdirectory"):n.removeAttribute("webkitdirectory");var a=r.getOpt("fileType");void 0!==a&&a.length>=1?n.setAttribute("accept",a.map(function(e){return"."+e}).join(",")):n.removeAttribute("accept"),n.addEventListener("change",function(e){l(e.target.files,e),r.getOpt("clearInput")&&(e.target.value="")},!1)})},r.assignDrop=function(e){void 0===e.length&&(e=[e]),n.each(e,function(e){e.addEventListener("dragover",a,!1),e.addEventListener("dragenter",a,!1),e.addEventListener("drop",i,!1)})},r.unAssignDrop=function(e){void 0===e.length&&(e=[e]),n.each(e,function(e){e.removeEventListener("dragover",a),e.removeEventListener("dragenter",a),e.removeEventListener("drop",i)})},r.isUploading=function(){var e=!1;return n.each(r.files,function(t){if(t.isUploading())return e=!0,!1}),e},r.upload=function(){if(!r.isUploading()){r.fire("uploadStart");for(var e=1;e<=r.getOpt("simultaneousUploads");e++)r.uploadNextChunk()}},r.pause=function(){n.each(r.files,function(e){e.abort()}),r.fire("pause")},r.cancel=function(){r.fire("beforeCancel");for(var e=r.files.length-1;e>=0;e--)r.files[e].cancel();r.fire("cancel")},r.progress=function(){var e=0,t=0;return n.each(r.files,function(r){e+=r.progress()*r.size,t+=r.size}),t>0?e/t:0},r.addFile=function(e,t){l([e],t)},r.addFiles=function(e,t){l(e,t)},r.removeFile=function(e){for(var t=r.files.length-1;t>=0;t--)r.files[t]===e&&r.files.splice(t,1)},r.getFromUniqueIdentifier=function(e){var t=!1;return n.each(r.files,function(r){r.uniqueIdentifier==e&&(t=r)}),t},r.getSize=function(){var e=0;return n.each(r.files,function(t){e+=t.size}),e},r.handleDropEvent=function(e){i(e)},r.handleChangeEvent=function(e){l(e.target.files,e),e.target.value=""},r.updateQuery=function(e){r.opts.query=e},this};"undefined"!=typeof module?module.exports=t:"function"==typeof e&&e.amd?e(function(){return t}):window.Resumable=t}();
},{}],"Se4y":[function(require,module,exports) {
"use strict";Object.defineProperty(exports,"__esModule",{value:!0}),exports.default=o;var e=n(require("resumablejs/resumable"));function n(e){return e&&e.__esModule?e:{default:e}}function o(){window.app.upload={count:0,success:0};var e=a();$("#btn-upload-upload").on("click",function(){e.upload()})}function a(){var n=new e.default({fileType:["geojson","json"],target:window.app.api,testChunks:!0});return n.assignBrowse($("#btn-upload-browse")),n.on("fileAdded",function(e,n){var o=e.fileName,a=e.uniqueIdentifier,t=document.createElement("li");window.app.upload.count++,$(t).text(o).data("id",a),$("#upload-list").append(t),$("#btn-upload-upload").prop("disabled",!1).addClass("btn-primary").removeClass("btn-outline-primary")}),n.on("progress",function(){var e=Math.round(100*n.progress(!0));$("#upload-progress").attr("aria-valuenow",e).css("width","".concat(e,"%"))}),n.on("fileSuccess",function(e,n){var o=e.uniqueIdentifier;$("#upload-list > li").each(function(e,n){$(n).data("id")===o&&$(n).addClass("text-success")}),window.app.upload.success++,window.app.upload.success===window.app.upload.count&&window.location.assign(window.app.next)}),n.on("fileError",function(e,n){var o=e.uniqueIdentifier;$("#upload-list > li").each(function(e,n){$(n).data("id")===o&&$(n).addClass("text-danger")})}),n.on("error",function(e,n){console.error(e,n)}),n}
},{"resumablejs/resumable":"MQCK"}],"YGik":[function(require,module,exports) {
"use strict";require("../sass/style.scss");var t=e(require("./fn/upload"));function e(t){return t&&t.__esModule?t:{default:t}}window.app=window.app||{},$(document).ready(function(){(0,t.default)(),$(".btn-delete").on("click",function(t){var e=$(t.target).attr("href"),n=$(t.target).closest("tr").data().path;t.preventDefault(),!0===confirm("Are you sure you want to delete this file ?")&&fetch(e,{method:"DELETE",headers:{"Content-Type":"application/json"},body:JSON.stringify({path:n})}).then(function(t){if(!0!==t.ok)throw t.statusText;return t.json()}).then(function(e){!0===e.deleted?$(t.target).closest("tr").remove():$(t.target).closest("tr").addClass("table-danger")}).catch(function(t){console.error(t)})})});
},{"../sass/style.scss":"rrhY","./fn/upload":"Se4y"}]},{},["YGik"], null)