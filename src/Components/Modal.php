<?php

namespace DoatKolom\Ui\Components;

class Modal extends ComponentBase
{
    protected $settings;
    protected $tabs;

	public function button(string $buttonText)
	{
		?>
		<span x-on:click="$store.DoatKolomUiModal.changeStatus()">
			<button type="button" class="rounded-md bg-white px-5 py-2.5 shadow">
				<?php echo $buttonText?>
			</button>
		</span>
		<?php
	}

    public function start( array $settingArgs = [], array $tabs )
    {
		?><div x-data class="doatkolom-ui"><?php
	}

    public function content()
    {
		?>
		<div x-show="$store.DoatKolomUiModal.status" style="display: none" class="fixed inset-0 overflow-y-auto">
			<div x-show="$store.DoatKolomUiModal.status" x-transition.opacity class="fixed inset-0 bg-black bg-opacity-50"></div>

			<div x-show="$store.DoatKolomUiModal.status" x-transition
				class="relative flex min-h-screen items-center justify-center p-4">
				<div
					x-on:click.outside="$store.DoatKolomUiModal.changeStatus()"
				 	x-trap.noscroll.inert="$store.DoatKolomUiModal.status"
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
				let modalData = {
					getContentArea() {
						return document.querySelector('.doatkolom-ui-modal-content');
					}
				};
				Alpine.store('DoatKolomUiModal', {
					...DoatKolomUiUtils.modalAndDrawerData,
					...modalData
				})
			});
		</script>
	<?php
    }
}
