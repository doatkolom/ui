var DoatKolomUiUtils = {
	pulse: `<div class="p-4 w-full mx-auto">
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
}
var DoatKolomUi = {
	tab: function (Identifiers, settings, tabs) {
		document.addEventListener('alpine:init', () => {
			Alpine.data(Identifiers.dataKey, () => ({
				tabs: {},
				tabId: Identifiers.tabId,
				settings: settings,
				selectedId: null,
				contents: {},
				isfetchingContent: false,
				pulse: '',
				init() {
					this.$nextTick(() => {
						this.select(this.$id(Identifiers.tabId, 1))
					})
				},
				select(id) {
					this.content(id);
					this.selectedId = id
				},
				isSelected(id) { return this.selectedId === id },
				whichChild(el, parent) { return Array.from(parent.children).indexOf(el) },
				getIndexFromId(id) {
					return id.substr(id.length - 1)
				},
				content(id) {
					if (!this.isfetchingContent) {
						var index = parseInt(this.getIndexFromId(id)) - 1;
						var tab = this.tabs[index];
						var contentArea = document.querySelector(`[aria-labelledby="${id}"]`);
						this.isfetchingContent = true;
						if (this.contents[index] === undefined) {
							if (tab.content_api) {
								contentArea.innerHTML = DoatKolomUiUtils.pulse;
								this.fetchContent(tab, index, contentArea);
							} else {
								this.appendContent(tab.content, index, contentArea);
							}
						} else if (tab.content_api && tab.content_cache === false) {
							contentArea.innerHTML = DoatKolomUiUtils.pulse;
							this.fetchContent(tab, index, contentArea);
						}
					}
				},
				fetchContent(tab, index, contentArea) {
					fetch(tab.content_api, tab.content_api_options)
						.then(res => res.text())
						.then(content => {
							this.appendContent(content, index, contentArea);
						});
				},
				getPositionClasses(elementName, classes) {
					switch (this.settings.position) {
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
				appendContent(content, index, contentArea) {
					contentArea.innerHTML = '';
					content = this.htmlToDocument(content);
					contentArea.appendChild(content.contentDocument);

					content.scripts.forEach(script => {
						var scriptDocument = document.createElement("script");
						scriptDocument.innerHTML = script.innerHTML;
						contentArea.appendChild(scriptDocument);
					});

					this.contents[index] = { content, is_fetch: true };
					this.isfetchingContent = false;
				},
				htmlToDocument(html) {
					var contentDocument = document.createElement("div");
					contentDocument.innerHTML = html;
					var scriptDocuments = contentDocument.querySelectorAll('script');
					var scripts = scriptDocuments;
					scriptDocuments.forEach(script => {
						script.remove();
					});
					return { contentDocument, scripts };
				}
			}))

			/**
			 * Keyboard focus
			 */
			Alpine.bind(Identifiers.tablistBind, () => ({
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
			Alpine.bind(Identifiers.tablistButtonBind, () => ({
				[':id']() {
					return this.$id(Identifiers.tabId, this.whichChild(this.$el.parentElement, this.$refs.tablist))
				},
				['@click']() {
					this.select(this.$el.id);
				},
				['@focus']() {
					this.select(this.$el.id)
				},
				[':tabindex']() { return this.isSelected(this.$el.id) ? 0 : -1 },
				[':aria-selected']() { return this.isSelected(this.$el.id) },
				[':class']() {
					return this.isSelected(this.$el.id) ? 'border-black bg-white' : 'border-transparent'
				}
			}))
		})
	}
}