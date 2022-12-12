<?php

namespace DoatKolom\Ui\Components;

use DoatKolom\Ui\Utils\Common;

class Accordion extends ComponentBase
{
    protected $identifiers;
    protected $settings;
    protected $items;

	public function setIdentifiers()
	{
		$this->identifiers = [
			'accordionId'         => Common::generateRandomString( 10 ),
			'dataKey'             => Common::generateRandomString( 10 ),
			'accordionListBind'   => Common::generateRandomString( 10 ),
			'accordionButtonBind' => Common::generateRandomString( 10 )
		];
	}
	protected function defaultClasses()
	{
		return [
			'body'             => ' ',
			'inner'            => ' ',
			'accordion'        => ' ',
			'accordionContent' => ' ',
			'title'            => ' ',
			'buttonMinus'      => ' ',
			'buttonPlus'       => ' '
		];
	}

	public function start(array $settingArgs, array $items)
	{
		$settings = [
			'init'        => isset( $settingArgs['init'] ) ? $settingArgs['init'] : true,
			'activeItems' => isset( $settingArgs['activeItems'] ) ? $settingArgs['activeItems'] : [1 => true],
			'multiple'    => isset( $settingArgs['multiple'] ) ? $settingArgs['multiple'] : false,
			'classes'     => $this->defaultClasses()
		];

		$this->settings = $settings;
		$this->items    = Common::contentBuffer($items);
		?>
		<div x-data="<?php echo $this->identifiers['dataKey'] ?>" x-id="['<?php echo $this->identifiers['accordionId'] ?>']" class="w-full" :class="settings.classes.body">
		<?php
	}

	public function content()
	{?>
		<div x-bind="<?php echo $this->identifiers['accordionListBind'];?>" role="accordionList" class="mx-auto max-w-3xl min-h-[16rem] space-y-4" :class="settings.classes.inner">
			<template x-for="(accordion, index) in accordions" >
				<div class="rounded-lg bg-white shadow" :id="$id('<?php echo $this->identifiers['accordionId'] ?>', accordion.index)" :class="settings.classes.accordion">
					<div class="justify-between px-3 py-4">
						<template x-if="accordion.icon !== undefined">
							<span class="!inline-block" x-html="accordion.icon"></span>
						</template>
						<span x-bind="<?php echo $this->identifiers['accordionButtonBind'];?>" class="items-center justify-between text-xl font-bold cursor-pointer">
							<span x-text="accordion.title" class="pl-3 capitalize select-none" :class="settings.classes.title"></span>
							<span x-show="isSelected(index)" aria-hidden="true" class="ml-4 float-right select-none" :class="settings.classes.buttonMinus">&minus;</span>
							<span x-show="!isSelected(index)" aria-hidden="true" class="ml-4 float-right select-none" :class="settings.classes.buttonPlus">&plus;</span>
						</span>
					</div>
					<div x-show="isSelected(index)" x-collapse>
						<div class="px-6 pb-4 grid gap-3 grid-cols-1" x-html="accordion.content" :class="settings.classes.accordionContent"></div>
					</div>
				</div>
			</template>
		</div>
		<?php
	}

	public function end()
	{
		?>
		</div>
		<script>
			DoatKolomUi.Accordion(<?php echo json_encode( $this->identifiers ); ?>,<?php echo json_encode( $this->settings ); ?>,<?php echo json_encode( $this->items ) ?>)
		</script>
		<?php
	}
}
