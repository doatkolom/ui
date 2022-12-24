<?php

namespace DoatKolom\Ui\Components;

class Drawer extends ComponentBase
{
	protected $settings;
	protected $tabs;

	public function start(array $settingArgs, array $items = [])
	{
		$this->settings = $settingArgs;
		if(empty($this->settings['width'])) {
			$this->settings['width'] = '300px';
		}
		?><div class="doatkolom-ui" x-data><?php
	}

	public function content()
	{
	?>
		<div class="drawer" x-cloak x-show="$store.DoatKolomUiDrawer.status" x-trap.noscroll.inert="$store.DoatKolomUiDrawer.status">
			<div class="fixed top-0 left-0 w-full h-screen z-[9999] bg-black bg-opacity-50">
				<div x-show="$store.DoatKolomUiDrawer.status" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in duration-500" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full" x-on:click.outside="$store.DoatKolomUiDrawer.changeStatus()" class="fixed top-0 right-0 h-screen bg-white overflow-scroll" :style="$store.DoatKolomUiDrawer.getWidth()">
					<div class="doatkolom-ui-drawer-content">
						<div class="mt-8">
							<button x-on:click="$store.DoatKolomUiDrawer.changeStatus()" class="inline-flex justify-center rounded-lg text-sm font-semibold py-3 px-4 bg-slate-500 text-white pointer-events-auto">X</button>
						</div>
					</div>
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
				let drawerData = {
					width: "<?php echo $this->settings['width'] ?>",
					getWidth() {
						return 'width:' + this.width + ';';
					},
					getContentArea() {
						return document.querySelector('.doatkolom-ui-drawer-content');
					}
				}
				Alpine.store('DoatKolomUiDrawer', {
					...DoatKolomUiUtils.modalAndDrawerData,
					...drawerData
				})
			})
		</script>
		<?php
	}
}
