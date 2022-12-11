var DoatKolomUi = {
	tab: function (tab_id, data_key, tab_button_bind_key, tablist_bind_key) {
		document.addEventListener('alpine:init', () => {
			Alpine.data(data_key, () => ({
				selectedId: null,
				contents: {},
				is_requesting_content: false,
				pulse: '',
				init() {
					this.$nextTick(() => {
						var pulseDocument = document.querySelector(`.${tab_id}-pulse`);
						this.pulse = pulseDocument.innerHTML;
						pulseDocument.remove();
						return this.select(this.$id(tab_id, 2))
					})
				},
				select(id) {
					this.fetchContent(id);
					this.selectedId = id
				},
				isSelected(id) { return this.selectedId === id },
				whichChild(el, parent) { return Array.from(parent.children).indexOf(el) + 1 },
				fetchContent(id) {
					if (!this.is_requesting_content) {
						var data = JSON.parse(document.querySelector('#' + id).dataset.content);
						var item_id = id.substr(id.length - 1);
						if (data.url && (this.contents[item_id] === undefined || data.content_cache === false)) {
							var contentArea = document.querySelector(`[aria-labelledby="${id}"]`);
							contentArea.innerHTML = this.pulse;
							this.is_requesting_content = true;
							fetch(data.url, data.options)
								.then(res => res.text())
								.then(content => {
									content = this.htmlToDocument(content);
									this.appendContent(content, item_id, contentArea);
								});
						}
					}
				},
				appendContent(content, item_id, contentArea) {
					contentArea.innerHTML = '';
					contentArea.appendChild(content.contentDocument);

					content.scripts.forEach(script => {
						var scriptDocument = document.createElement("script");
						scriptDocument.innerHTML = script.innerHTML;
						contentArea.appendChild(scriptDocument);
					});

					this.contents[item_id] = { content, is_fetch: true };
					this.is_requesting_content = false;
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
			Alpine.bind(tablist_bind_key, () => ({
				['x-ref']: 'tablist',
				['@keydown.right.prevent.stop']() { this.$focus.wrap().next() },
				['@keydown.home.prevent.stop']() { this.$focus.first() },
				['@keydown.page-up.prevent.stop']() { this.$focus.first() },
				['@keydown.left.prevent.stop']() { this.$focus.wrap().prev() },
				['@keydown.end.prevent.stop']() { this.$focus.last() },
				['@keydown.page-down.prevent.stop']() { this.$focus.last() }
			}))
			Alpine.bind(tab_button_bind_key, () => ({
				[':id']() {
					return this.$id(tab_id, this.whichChild(this.$el.parentElement, this.$refs.tablist))
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