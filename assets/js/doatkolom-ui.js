var DoatKolomUiUtils = {
	pulse: `<div class="p-8 w-full mx-auto">
	<div class="animate-pulse flex space-x-4">
		<div class="rounded-full bg-slate-200 h-10 w-10"></div>
		<div class="flex-1 space-y-6 py-1">
			<div class="h-2 bg-slate-200 rounded"></div>
			<div class="space-y-3">
				<div class="grid grid-cols-3 gap-4">
					<div class="h-2 bg-slate-200 rounded col-span-2"></div>
					<div class="h-2 bg-slate-200 rounded col-span-1"></div>
				</div>
				<div class="h-2 bg-slate-200 rounded"></div>
			</div>
		</div>
	</div>
</div>`,
	htmlToDocument(html) {
		var contentDocument = document.createElement("div");
		contentDocument.innerHTML = html;
		var scriptDocuments = contentDocument.querySelectorAll('script');
		var scripts = scriptDocuments;
		scriptDocuments.forEach(script => {
			script.remove();
		});
		return { contentDocument, scripts };
	},
	getChildNo(child, parent) { return Array.from(parent.children).indexOf(child) },
	modalAndDrawerData: {
		status: false,
		contents: {},
		changeStatus() {
			this.status = !this.status;
		},
		setContent(content) {
			this.content = content;
		},
		setContentByApi(api, apiOptions, cacheKey = false) {
			if (cacheKey && this.contents[cacheKey] !== undefined) {
				this.setContent(this.contents[cacheKey]);
			} else {
				this.getContentArea().innerHTML = DoatKolomUiUtils.pulse;
				fetch(api, apiOptions).then(res => res.text()).then(content => this.prepareContent(content, cacheKey));
			}
		},
		prepareContent(content, cacheKey) {
			if (cacheKey) {
				this.contents[cacheKey] = content;
			}
			this.setContent(content)
		},
		pushData(key, value) {
			this[key] = value;
		},
		setContent(content) {
			this.content = content;
			var contentArea = this.getContentArea();
			contentArea.innerHTML = '';
			content = DoatKolomUiUtils.htmlToDocument(content);
			contentArea.appendChild(content.contentDocument);
			content.scripts.forEach(script => {
				var scriptDocument = document.createElement("script");
				scriptDocument.innerHTML = script.innerHTML;
				contentArea.appendChild(scriptDocument);
			});
		}
	}
}
var DoatKolomUi = {
	Tab: function (tabIdentifiers, tabSettings, tabs) {
		function TabFunction(tabIdentifiers, tabSettings, tabs) {
			Alpine.data(tabIdentifiers.dataKey, () => ({
				tabs: tabs,
				tabId: tabIdentifiers.tabId,
				tabSettings: tabSettings,
				tabSelectedId: null,
				tabContents: {},
				isFetchingTabContent: false,
				init() {
					this.$nextTick(() => {
						setTimeout(function () {
							this.selectTab(this.$id(tabIdentifiers.tabId, tabSettings.tabSelectedId))
						}.bind(this), 1)
					})
				},
				selectTab(id) {
					this.tabContent(id);
					this.tabSelectedId = id
				},
				isTabSelected(id) { return this.tabSelectedId === id },
				whichTabChild(el, parent) { return Array.from(parent.children).indexOf(el) },
				getIndexFromId(id) {
					return id.substr(id.length - 1)
				},
				tabContent(id) {

					if (!this.isFetchingTabContent) {

						var index = parseInt(this.getIndexFromId(id)) - 1;
						var tab = this.tabs[index];
						var contentArea = document.querySelector(`[aria-labelledby="${id}"]`);

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
				fetchTabContent(tab, index, contentArea) {
					fetch(tab.content_api, tab.contentApiOptions)
						.then(res => res.text())
						.then(content => {
							this.appendTabContent(content, index, contentArea);
						});
				},
				getTabPositionClasses(elementName, classes) {
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
				appendTabContent(content, index, contentArea) {
					contentArea.innerHTML = '';
					content = DoatKolomUiUtils.htmlToDocument(content);
					contentArea.appendChild(content.contentDocument);

					content.scripts.forEach(script => {
						var scriptDocument = document.createElement("script");
						scriptDocument.innerHTML = script.innerHTML;
						contentArea.appendChild(scriptDocument);
					});

					this.tabContents[index] = { content, is_fetch: true };
					this.isFetchingTabContent = false;
				},
				selectTabButtonClass(id, tab) {
					if (this.isTabSelected(id)) {
						return 'border-gray-200 bg-white ' + this.getTabPositionClasses('tab_button', tab?.classes?.tab_button) + ' ' + this.tabSettings.classes.selectedButton;
					}
					return 'border-transparent ' + this.getTabPositionClasses('tab_button', tab?.classes?.tab_button);
				}
			}))

			/**
			 * Keyboard focus
			 */
			Alpine.bind(tabIdentifiers.tablistBind, () => ({
				['x-ref']: 'tablist',
				['@keydown.right.prevent.stop']() { this.$focus.wrap().next() },
				['@keydown.home.prevent.stop']() { this.$focus.first() },
				['@keydown.page-up.prevent.stop']() { this.$focus.first() },
				['@keydown.left.prevent.stop']() { this.$focus.wrap().prev() },
				['@keydown.end.prevent.stop']() { this.$focus.last() },
				['@keydown.page-down.prevent.stop']() { this.$focus.last() }
			}))

			/**
			 * Tab selector actions
			 */
			Alpine.bind(tabIdentifiers.tablistButtonBind, () => ({
				[':id']() {
					return this.$id(tabIdentifiers.tabId, this.whichTabChild(this.$el.parentElement, this.$refs.tablist))
				},
				['@click']() {
					this.selectTab(this.$el.id);
				},
				['@focus']() {
					this.selectTab(this.$el.id)
				},
				[':tabindex']() { return this.isTabSelected(this.$el.id) ? 0 : -1 },
				[':aria-selected']() { return this.isTabSelected(this.$el.id) },
			}))
		}

		if (tabSettings.init) {
			document.addEventListener('alpine:init', () => {
				TabFunction(tabIdentifiers, tabSettings, tabs)
			});
		} else {
			TabFunction(tabIdentifiers, tabSettings, tabs)
		}
	},
	Accordion: function (identifiers, accordionSettings, items) {
		function AccordionFunction() {
			Alpine.data(identifiers.dataKey, () => ({
				accordions: items,
				activeAccordions: {},
				accordionSettings: accordionSettings,
				init() {
					this.activeAccordions = accordionSettings.activeItems;
				},
				isAccordionSelected(item_index) {
					item_index = item_index + 1;
					if (this.activeAccordions[item_index] !== undefined && this.activeAccordions[item_index] === true) {
						return true;
					}
					return false;
				},
				getIndexFromId(id) {
					return id.substr(id.length - 1)
				},
				selectClickEvent() {
					var index = this.getIndexFromId(this.$el.id);
					var is_selected = this.isAccordionSelected(index - 1);
					if (!accordionSettings.multiple) {
						this.activeAccordions = {};
					}
					this.activeAccordions[index] = !is_selected;
				},
				accordionWhichChild(el, parent) { return Array.from(parent.children).indexOf(el) },
			}));
			Alpine.bind(identifiers.accordionListBind, () => ({
				['x-ref']: 'accordionList',
			}))
			Alpine.bind(identifiers.accordionButtonBind, () => ({
				[':id']() {
					return this.$id(identifiers.accordionId, this.accordionWhichChild(this.$el.closest('.accordionItem'), this.$refs.accordionList))
				},
				['@click']() {
					this.selectClickEvent();
				}
			}))

			Alpine.bind(identifiers.accordionCollapseButton, () => ({
				[':id']() {
					return this.$id(identifiers.accordionId, this.accordionWhichChild(this.$el.closest('.accordionItem'), this.$refs.accordionList))
				},
				['@click']() {
					this.selectClickEvent();
				}
			}))
		}

		if (accordionSettings.init) {
			document.addEventListener('alpine:init', () => {
				AccordionFunction(identifiers, accordionSettings, items)
			});
		} else {
			AccordionFunction(identifiers, accordionSettings, items)
		}
	},
	Notification: function (identifiers, notificationSettings) {
		function NotificationFunction(identifiers) {
			Alpine.data(identifiers.dataKey, () => ({
				notifications: [],
				add(e) {
					this.notifications.push({
						id: e.timeStamp,
						type: e.detail.type,
						content: e.detail.content
					})
				},
				remove(notification) {
					this.notifications = this.notifications.filter(i => i.id !== notification.id)
				}
			}))
			Alpine.data(identifiers.notificationKey, () => ({
				show: false,
				init() {
					this.$nextTick(() => this.show = true)
					setTimeout(() => this.transitionOut(), 2500)
				},
				transitionOut() {
					this.show = false;
					setTimeout(() => this.remove(this.notification), 300)
				},
			}))
		}

		if (notificationSettings.init) {
			document.addEventListener('alpine:init', () => {
				NotificationFunction(identifiers)
			});
		} else {
			NotificationFunction(identifiers)
		}
	}
}