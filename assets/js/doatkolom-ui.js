/******/ (() => { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ "./resources/js/doatkolom-ui.js":
/*!**************************************!*\
  !*** ./resources/js/doatkolom-ui.js ***!
  \**************************************/
/***/ (() => {

function _typeof(obj) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (obj) { return typeof obj; } : function (obj) { return obj && "function" == typeof Symbol && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }, _typeof(obj); }
function _defineProperty(obj, key, value) { key = _toPropertyKey(key); if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }
function _toPropertyKey(arg) { var key = _toPrimitive(arg, "string"); return _typeof(key) === "symbol" ? key : String(key); }
function _toPrimitive(input, hint) { if (_typeof(input) !== "object" || input === null) return input; var prim = input[Symbol.toPrimitive]; if (prim !== undefined) { var res = prim.call(input, hint || "default"); if (_typeof(res) !== "object") return res; throw new TypeError("@@toPrimitive must return a primitive value."); } return (hint === "string" ? String : Number)(input); }
window.DoatKolomUiUtils = {
  pulse: "<div class=\"p-8 w-full mx-auto\">\n\t<div class=\"animate-pulse flex space-x-4\">\n\t\t<div class=\"rounded-full bg-slate-200 h-10 w-10\"></div>\n\t\t<div class=\"flex-1 space-y-6 py-1\">\n\t\t\t<div class=\"h-2 bg-slate-200 rounded\"></div>\n\t\t\t<div class=\"space-y-3\">\n\t\t\t\t<div class=\"grid grid-cols-3 gap-4\">\n\t\t\t\t\t<div class=\"h-2 bg-slate-200 rounded col-span-2\"></div>\n\t\t\t\t\t<div class=\"h-2 bg-slate-200 rounded col-span-1\"></div>\n\t\t\t\t</div>\n\t\t\t\t<div class=\"h-2 bg-slate-200 rounded\"></div>\n\t\t\t</div>\n\t\t</div>\n\t</div>\n</div>",
  htmlToDocument: function htmlToDocument(html) {
    var contentDocument = document.createElement("div");
    contentDocument.innerHTML = html;
    var scriptDocuments = contentDocument.querySelectorAll('script');
    var scripts = scriptDocuments;
    scriptDocuments.forEach(function (script) {
      script.remove();
    });
    return {
      contentDocument: contentDocument,
      scripts: scripts
    };
  },
  getChildNo: function getChildNo(child, parent) {
    return Array.from(parent.children).indexOf(child);
  },
  modalAndDrawerData: _defineProperty({
    status: false,
    contents: {},
    isUnLock: true,
    lock: function lock() {
      this.isUnLock = false;
    },
    unLock: function unLock() {
      this.isUnLock = true;
    },
    changeStatus: function changeStatus() {
      var unLock = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : false;
      if (unLock || this.isUnLock) {
        this.isUnLock = true;
        this.status = !this.status;
      }
    },
    clickOutSide: function clickOutSide() {
      if (this.overrideOutSideClick()) {
        this.changeStatus();
      }
    },
    overrideOutSideClick: function overrideOutSideClick() {
      return true;
    },
    setContent: function setContent(content) {
      this.content = content;
    },
    setContentByApi: function setContentByApi(api, apiOptions) {
      var _this = this;
      var cacheKey = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : false;
      if (cacheKey && this.contents[cacheKey] !== undefined) {
        this.setContent(this.contents[cacheKey]);
      } else {
        this.getContentArea().innerHTML = DoatKolomUiUtils.pulse;
        fetch(api, apiOptions).then(function (res) {
          return res.text();
        }).then(function (content) {
          return _this.prepareContent(content, cacheKey);
        });
      }
    },
    prepareContent: function prepareContent(content, cacheKey) {
      if (cacheKey) {
        this.contents[cacheKey] = content;
      }
      this.setContent(content);
    },
    pushData: function pushData(key, value) {
      this[key] = value;
    }
  }, "setContent", function setContent(content) {
    this.content = content;
    var contentArea = this.getContentArea();
    contentArea.innerHTML = '';
    content = DoatKolomUiUtils.htmlToDocument(content);
    contentArea.appendChild(content.contentDocument);
    content.scripts.forEach(function (script) {
      var scriptDocument = document.createElement("script");
      scriptDocument.innerHTML = script.innerHTML;
      contentArea.appendChild(scriptDocument);
    });
  })
};
window.DoatKolomUi = {
  Tab: function Tab(tabIdentifiers, tabSettings, tabs) {
    function TabFunction(tabIdentifiers, tabSettings, tabs) {
      Alpine.data(tabIdentifiers.dataKey, function () {
        return {
          tabs: tabs,
          tabId: tabIdentifiers.tabId,
          tabSettings: tabSettings,
          tabSelectedId: null,
          tabContents: {},
          isFetchingTabContent: false,
          init: function init() {
            var _this2 = this;
            this.$nextTick(function () {
              setTimeout(function () {
                this.selectTab(this.$id(tabIdentifiers.tabId, tabSettings.tabSelectedId));
              }.bind(_this2), 1);
            });
          },
          selectTab: function selectTab(id) {
            this.tabContent(id);
            this.tabSelectedId = id;
          },
          isTabSelected: function isTabSelected(id) {
            return this.tabSelectedId === id;
          },
          whichTabChild: function whichTabChild(el, parent) {
            return Array.from(parent.children).indexOf(el);
          },
          getIndexFromId: function getIndexFromId(id) {
            return id.substr(id.length - 1);
          },
          tabContent: function tabContent(id) {
            if (!this.isFetchingTabContent) {
              var index = parseInt(this.getIndexFromId(id)) - 1;
              var tab = this.tabs[index];
              var contentArea = document.querySelector("[aria-labelledby=\"".concat(id, "\"]"));
              if (this.tabContents[index] === undefined) {
                this.isFetchingTabContent = true;
                if (tab.content_api) {
                  contentArea.innerHTML = DoatKolomUiUtils.pulse;
                  this.fetchTabContent(tab, index, contentArea);
                } else {
                  this.appendTabContent(tab.content, index, contentArea);
                }
              } else if (tab.content_api && tab.contentCache === false) {
                this.isFetchingTabContent = true;
                contentArea.innerHTML = DoatKolomUiUtils.pulse;
                this.fetchTabContent(tab, index, contentArea);
              }
            }
          },
          fetchTabContent: function fetchTabContent(tab, index, contentArea) {
            var _this3 = this;
            fetch(tab.content_api, tab.contentApiOptions).then(function (res) {
              return res.text();
            }).then(function (content) {
              _this3.appendTabContent(content, index, contentArea);
            });
          },
          getTabPositionClasses: function getTabPositionClasses(elementName, classes) {
            switch (this.tabSettings.position) {
              case 'top':
                if ('tab_button' == elementName) {
                  return ' border-t border-l border-r rounded-t-md inline-flex ' + classes;
                }
              case 'left':
                if ('tab_button' == elementName) {
                  return ' border-t border-l border-b w-[calc(100%+1px)] text-left h-14 ' + classes;
                }
              default:
                return classes;
            }
          },
          appendTabContent: function appendTabContent(content, index, contentArea) {
            contentArea.innerHTML = '';
            content = DoatKolomUiUtils.htmlToDocument(content);
            contentArea.appendChild(content.contentDocument);
            content.scripts.forEach(function (script) {
              var scriptDocument = document.createElement("script");
              scriptDocument.innerHTML = script.innerHTML;
              contentArea.appendChild(scriptDocument);
            });
            this.tabContents[index] = {
              content: content,
              is_fetch: true
            };
            this.isFetchingTabContent = false;
          },
          selectTabButtonClass: function selectTabButtonClass(id, tab) {
            var _tab$classes2;
            if (this.isTabSelected(id)) {
              var _tab$classes;
              return 'border-gray-200 bg-white ' + this.getTabPositionClasses('tab_button', tab === null || tab === void 0 ? void 0 : (_tab$classes = tab.classes) === null || _tab$classes === void 0 ? void 0 : _tab$classes.tab_button) + ' ' + this.tabSettings.classes.selectedButton;
            }
            return 'border-transparent ' + this.getTabPositionClasses('tab_button', tab === null || tab === void 0 ? void 0 : (_tab$classes2 = tab.classes) === null || _tab$classes2 === void 0 ? void 0 : _tab$classes2.tab_button);
          }
        };
      });

      /**
       * Keyboard focus
       */
      Alpine.bind(tabIdentifiers.tablistBind, function () {
        var _ref;
        return _ref = {}, _defineProperty(_ref, 'x-ref', 'tablist'), _defineProperty(_ref, '@keydown.right.prevent.stop', function keydownRightPreventStop() {
          this.$focus.wrap().next();
        }), _defineProperty(_ref, '@keydown.home.prevent.stop', function keydownHomePreventStop() {
          this.$focus.first();
        }), _defineProperty(_ref, '@keydown.page-up.prevent.stop', function keydownPageUpPreventStop() {
          this.$focus.first();
        }), _defineProperty(_ref, '@keydown.left.prevent.stop', function keydownLeftPreventStop() {
          this.$focus.wrap().prev();
        }), _defineProperty(_ref, '@keydown.end.prevent.stop', function keydownEndPreventStop() {
          this.$focus.last();
        }), _defineProperty(_ref, '@keydown.page-down.prevent.stop', function keydownPageDownPreventStop() {
          this.$focus.last();
        }), _ref;
      });

      /**
       * Tab selector actions
       */
      Alpine.bind(tabIdentifiers.tablistButtonBind, function () {
        var _ref2;
        return _ref2 = {}, _defineProperty(_ref2, ':id', function id() {
          return this.$id(tabIdentifiers.tabId, this.whichTabChild(this.$el.parentElement, this.$refs.tablist));
        }), _defineProperty(_ref2, '@click', function click() {
          this.selectTab(this.$el.id);
        }), _defineProperty(_ref2, '@focus', function focus() {
          this.selectTab(this.$el.id);
        }), _defineProperty(_ref2, ':tabindex', function tabindex() {
          return this.isTabSelected(this.$el.id) ? 0 : -1;
        }), _defineProperty(_ref2, ':aria-selected', function ariaSelected() {
          return this.isTabSelected(this.$el.id);
        }), _ref2;
      });
    }
    if (tabSettings.init) {
      document.addEventListener('alpine:init', function () {
        TabFunction(tabIdentifiers, tabSettings, tabs);
      });
    } else {
      TabFunction(tabIdentifiers, tabSettings, tabs);
    }
  },
  Accordion: function Accordion(identifiers, accordionSettings, items) {
    function AccordionFunction() {
      Alpine.data(identifiers.dataKey, function () {
        return {
          accordions: items,
          activeAccordions: {},
          accordionSettings: accordionSettings,
          init: function init() {
            this.activeAccordions = accordionSettings.activeItems;
          },
          isAccordionSelected: function isAccordionSelected(item_index) {
            item_index = item_index + 1;
            if (this.activeAccordions[item_index] !== undefined && this.activeAccordions[item_index] === true) {
              return true;
            }
            return false;
          },
          getIndexFromId: function getIndexFromId(id) {
            return id.substr(id.length - 1);
          },
          selectClickEvent: function selectClickEvent() {
            var index = this.getIndexFromId(this.$el.id);
            var is_selected = this.isAccordionSelected(index - 1);
            if (!accordionSettings.multiple) {
              this.activeAccordions = {};
            }
            this.activeAccordions[index] = !is_selected;
          },
          accordionWhichChild: function accordionWhichChild(el, parent) {
            return Array.from(parent.children).indexOf(el);
          }
        };
      });
      Alpine.bind(identifiers.accordionListBind, function () {
        return _defineProperty({}, 'x-ref', 'accordionList');
      });
      Alpine.bind(identifiers.accordionButtonBind, function () {
        var _ref4;
        return _ref4 = {}, _defineProperty(_ref4, ':id', function id() {
          return this.$id(identifiers.accordionId, this.accordionWhichChild(this.$el.closest('.accordionItem'), this.$refs.accordionList));
        }), _defineProperty(_ref4, '@click', function click() {
          this.selectClickEvent();
        }), _ref4;
      });
      Alpine.bind(identifiers.accordionCollapseButton, function () {
        var _ref5;
        return _ref5 = {}, _defineProperty(_ref5, ':id', function id() {
          return this.$id(identifiers.accordionId, this.accordionWhichChild(this.$el.closest('.accordionItem'), this.$refs.accordionList));
        }), _defineProperty(_ref5, '@click', function click() {
          this.selectClickEvent();
        }), _ref5;
      });
    }
    if (accordionSettings.init) {
      document.addEventListener('alpine:init', function () {
        AccordionFunction(identifiers, accordionSettings, items);
      });
    } else {
      AccordionFunction(identifiers, accordionSettings, items);
    }
  },
  Notification: function Notification(identifiers, notificationSettings) {
    function NotificationFunction(identifiers) {
      Alpine.data(identifiers.dataKey, function () {
        return {
          notifications: [],
          add: function add(e) {
            this.notifications.push({
              id: e.timeStamp,
              type: e.detail.type,
              content: e.detail.content
            });
          },
          remove: function remove(notification) {
            this.notifications = this.notifications.filter(function (i) {
              return i.id !== notification.id;
            });
          }
        };
      });
      Alpine.data(identifiers.notificationKey, function () {
        return {
          show: false,
          init: function init() {
            var _this4 = this;
            this.$nextTick(function () {
              return _this4.show = true;
            });
            setTimeout(function () {
              return _this4.transitionOut();
            }, 2500);
          },
          transitionOut: function transitionOut() {
            var _this5 = this;
            this.show = false;
            setTimeout(function () {
              return _this5.remove(_this5.notification);
            }, 300);
          }
        };
      });
    }
    if (notificationSettings.init) {
      document.addEventListener('alpine:init', function () {
        NotificationFunction(identifiers);
      });
    } else {
      NotificationFunction(identifiers);
    }
  },
  Select2: function Select2() {
    Alpine.data('DoatKolomUiSelect2', function () {
      return {
        api: '',
        multiple: false,
        options: [],
        name: '',
        value: '',
        select2Init: function select2Init() {
          var _this6 = this;
          if (this.api && 0 !== this.api.length) {
            this.ajaxSelect();
          } else {
            this.select();
          }
          jQuery(this.$refs.select2).on('change', function () {
            var currentSelection = jQuery(_this6.$refs.select2).select2('data');
            _this6.value = _this6.multiple ? currentSelection.map(function (i) {
              return i.id;
            }) : currentSelection[0].id;
            _this6.formData[_this6.name] = _this6.value;
          });
        },
        select: function select() {
          var ids = this.multiple ? this.value : [this.value];
          jQuery(this.$refs.select2).select2({
            multiple: this.multiple,
            data: this.options.map(function (i) {
              return {
                id: i.id,
                text: i.text,
                selected: ids.map(function (i) {
                  return String(i);
                }).includes(String(i.id))
              };
            })
          });
        },
        ajaxSelect: function ajaxSelect() {
          var _this7 = this;
          var select = function select() {
            var defaultData = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : [];
            return jQuery(_this7.$refs.select2).select2({
              multiple: _this7.multiple,
              dropdownParent: jQuery('.create-modal'),
              ajax: {
                delay: 500,
                url: _this7.api,
                headers: {
                  'X-WP-Nonce': SuperDocsSettings.nonce
                },
                data: function data(params) {
                  var search = '';
                  if (params.term) {
                    search = params.term;
                  }
                  return {
                    search: search
                  };
                },
                processResults: function processResults(data) {
                  return {
                    results: data
                  };
                }
              },
              data: defaultData
            });
          };
          if (0 !== this.value.length) {
            var ids = this.multiple ? this.value : [this.value];
            ids = ids.join(',');
            jQuery.ajax({
              url: this.api,
              data: {
                ids: ids
              },
              headers: {
                'X-WP-Nonce': SuperDocsSettings.nonce
              },
              success: function success(data) {
                data = data.map(function (i) {
                  i.selected = true;
                  return i;
                });
                select(data);
              }
            });
          } else {
            select();
          }
        }
      };
    });
  }
};
document.addEventListener('alpine:init', function () {
  DoatKolomUi.Select2();
});

/***/ }),

/***/ "./resources/sass/app.scss":
/*!*********************************!*\
  !*** ./resources/sass/app.scss ***!
  \*********************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = __webpack_modules__;
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/chunk loaded */
/******/ 	(() => {
/******/ 		var deferred = [];
/******/ 		__webpack_require__.O = (result, chunkIds, fn, priority) => {
/******/ 			if(chunkIds) {
/******/ 				priority = priority || 0;
/******/ 				for(var i = deferred.length; i > 0 && deferred[i - 1][2] > priority; i--) deferred[i] = deferred[i - 1];
/******/ 				deferred[i] = [chunkIds, fn, priority];
/******/ 				return;
/******/ 			}
/******/ 			var notFulfilled = Infinity;
/******/ 			for (var i = 0; i < deferred.length; i++) {
/******/ 				var [chunkIds, fn, priority] = deferred[i];
/******/ 				var fulfilled = true;
/******/ 				for (var j = 0; j < chunkIds.length; j++) {
/******/ 					if ((priority & 1 === 0 || notFulfilled >= priority) && Object.keys(__webpack_require__.O).every((key) => (__webpack_require__.O[key](chunkIds[j])))) {
/******/ 						chunkIds.splice(j--, 1);
/******/ 					} else {
/******/ 						fulfilled = false;
/******/ 						if(priority < notFulfilled) notFulfilled = priority;
/******/ 					}
/******/ 				}
/******/ 				if(fulfilled) {
/******/ 					deferred.splice(i--, 1)
/******/ 					var r = fn();
/******/ 					if (r !== undefined) result = r;
/******/ 				}
/******/ 			}
/******/ 			return result;
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/jsonp chunk loading */
/******/ 	(() => {
/******/ 		// no baseURI
/******/ 		
/******/ 		// object to store loaded and loading chunks
/******/ 		// undefined = chunk not loaded, null = chunk preloaded/prefetched
/******/ 		// [resolve, reject, Promise] = chunk loading, 0 = chunk loaded
/******/ 		var installedChunks = {
/******/ 			"/assets/js/doatkolom-ui": 0,
/******/ 			"assets/css/app": 0
/******/ 		};
/******/ 		
/******/ 		// no chunk on demand loading
/******/ 		
/******/ 		// no prefetching
/******/ 		
/******/ 		// no preloaded
/******/ 		
/******/ 		// no HMR
/******/ 		
/******/ 		// no HMR manifest
/******/ 		
/******/ 		__webpack_require__.O.j = (chunkId) => (installedChunks[chunkId] === 0);
/******/ 		
/******/ 		// install a JSONP callback for chunk loading
/******/ 		var webpackJsonpCallback = (parentChunkLoadingFunction, data) => {
/******/ 			var [chunkIds, moreModules, runtime] = data;
/******/ 			// add "moreModules" to the modules object,
/******/ 			// then flag all "chunkIds" as loaded and fire callback
/******/ 			var moduleId, chunkId, i = 0;
/******/ 			if(chunkIds.some((id) => (installedChunks[id] !== 0))) {
/******/ 				for(moduleId in moreModules) {
/******/ 					if(__webpack_require__.o(moreModules, moduleId)) {
/******/ 						__webpack_require__.m[moduleId] = moreModules[moduleId];
/******/ 					}
/******/ 				}
/******/ 				if(runtime) var result = runtime(__webpack_require__);
/******/ 			}
/******/ 			if(parentChunkLoadingFunction) parentChunkLoadingFunction(data);
/******/ 			for(;i < chunkIds.length; i++) {
/******/ 				chunkId = chunkIds[i];
/******/ 				if(__webpack_require__.o(installedChunks, chunkId) && installedChunks[chunkId]) {
/******/ 					installedChunks[chunkId][0]();
/******/ 				}
/******/ 				installedChunks[chunkId] = 0;
/******/ 			}
/******/ 			return __webpack_require__.O(result);
/******/ 		}
/******/ 		
/******/ 		var chunkLoadingGlobal = self["webpackChunkdoatkolom_ui"] = self["webpackChunkdoatkolom_ui"] || [];
/******/ 		chunkLoadingGlobal.forEach(webpackJsonpCallback.bind(null, 0));
/******/ 		chunkLoadingGlobal.push = webpackJsonpCallback.bind(null, chunkLoadingGlobal.push.bind(chunkLoadingGlobal));
/******/ 	})();
/******/ 	
/************************************************************************/
/******/ 	
/******/ 	// startup
/******/ 	// Load entry module and return exports
/******/ 	// This entry module depends on other loaded chunks and execution need to be delayed
/******/ 	__webpack_require__.O(undefined, ["assets/css/app"], () => (__webpack_require__("./resources/js/doatkolom-ui.js")))
/******/ 	var __webpack_exports__ = __webpack_require__.O(undefined, ["assets/css/app"], () => (__webpack_require__("./resources/sass/app.scss")))
/******/ 	__webpack_exports__ = __webpack_require__.O(__webpack_exports__);
/******/ 	
/******/ })()
;