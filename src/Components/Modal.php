<?php

namespace DoatKolom\Ui\Components;

class Modal extends ComponentBase
{
    protected $settings;
    protected $tabs;

	public function button(string $buttonText)
	{
		?>
		<span x-on:click="$store.datokolomUiModal.changeModalStatus()">
			<button type="button" class="rounded-md bg-white px-5 py-2.5 shadow">
				<?php echo $buttonText?>
			</button>
		</span>
		<?php
	}

    public function start( array $settingArgs, array $tabs )
    {
		?><div x-data><?php
	}

    public function content()
    {
		?>
		<div x-show="$store.datokolomUiModal.modalStatus" style="display: none" class="fixed inset-0 overflow-y-auto">
			<div x-show="$store.datokolomUiModal.modalStatus" x-transition.opacity class="fixed inset-0 bg-black bg-opacity-50"></div>

			<div x-show="$store.datokolomUiModal.modalStatus" x-transition x-on:click="$store.datokolomUiModal.changeModalStatus()"
				class="relative flex min-h-screen items-center justify-center p-4">

				<div x-on:click.stop x-trap.noscroll.inert="$store.datokolomUiModal.modalStatus"
					class="doatkolom-ui-modal-content relative w-full max-w-2xl overflow-y-auto rounded-xl bg-white p-4 shadow-lg">
				</div>
			</div>
		</div>
		<?php
    }

    public function end()
    {
		?>
		</div>
		<script>
			document.addEventListener('alpine:init', () => {
				Alpine.store('datokolomUiModal', {
					modalStatus: false,
					contents: {},
					changeModalStatus() {
						this.modalStatus = !this.modalStatus;
					},
					setContent(content) {
						this.content = content;
					},
					setContentByApi(api, apiOptions, cacheKey = false) {
						if( cacheKey && this.contents[cacheKey] !== undefined ) {
							this.setContent(this.contents[cacheKey]);
						} else {
							this.getContentArea().innerHTML = DoatKolomUiUtils.pulse;
							fetch(api, apiOptions).then(res => res.text()).then(content => this.prepareContent(content, cacheKey));
						}
					},
					prepareContent(content, cacheKey) {
						if(cacheKey) {
							this.contents[cacheKey] = content;
						}
						this.setContent(content)
					},
					pushModalData(key, value) {
						this[key] = value;
					},
					setContent(content) {
						this.content          = content;
						var contentArea       = this.getContentArea();
						contentArea.innerHTML = '';
						content               = DoatKolomUiUtils.htmlToDocument(content);
						contentArea.appendChild(content.contentDocument);
						content.scripts.forEach(script => {
							var scriptDocument = document.createElement("script");
							scriptDocument.innerHTML = script.innerHTML;
							contentArea.appendChild(scriptDocument);
						});
					},
					getContentArea() {
						return document.querySelector('.doatkolom-ui-modal-content');
					}
				})
			});
		</script>
	<?php
    }
}
