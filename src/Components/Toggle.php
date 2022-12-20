<?php

namespace DoatKolom\Ui\Components;

use DoatKolom\Ui\Utils\Common;

class Toggle extends ComponentBase
{
	public $dataKey;
	public $buttonBind;
	public $id;

	public function setIdentifiers()
	{
		$this->dataKey    = Common::generateRandomString();
		$this->buttonBind = Common::generateRandomString();
		$this->id         = Common::generateRandomString();
	}

    public function start( array $settingArgs, array $items )
    {
		?><div x-data="<?php echo $this->dataKey ?>" x-id="['<?php echo $this->id ?>']"><?php
    }

    public function content()
    {
		?>
			<input type="hidden" name="sendNotifications" :value="value" value="true">
			<label @click="$refs.toggle.click(); $refs.toggle.focus()" :id="$id('<?php echo $this->id ?>')"
				class="text-gray-900 font-medium" id="<?php echo $this->id ?>-1">
				On/Off
			</label>
			<button x-ref="toggle" type="button" role="switch" x-bind="<?php echo $this->buttonBind ?>"
				class="relative ml-4 inline-flex w-14 rounded-full py-1 transition bg-slate-400">
				<span :class="value ? 'translate-x-7' : 'translate-x-1'"
					class="bg-white h-6 w-6 rounded-full transition shadow-md translate-x-7" aria-hidden="true"></span>
			</button>
		<?php
    }

    public function end()
    {
		?>
		</div>
		<script>
			// document.addEventListener('alpine:init', () => {
				Alpine.data('<?php echo $this->dataKey?>', () => ({ value: false }))
				Alpine.bind('<?php echo $this->buttonBind?>', () => ({
					['@click']() {
						return this.value = !this.value
					},
					[':class']() {
						return this.value ? 'bg-slate-400' : 'bg-slate-300'
					},
				}))
			// })
		</script>
		<?php
    }
}
