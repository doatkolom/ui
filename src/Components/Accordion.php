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
			'accordionId'             => Common::generateRandomString( 10 ),
			'dataKey'                 => Common::generateRandomString( 10 ),
			'accordionListBind'       => Common::generateRandomString( 10 ),
			'accordionButtonBind'     => Common::generateRandomString( 10 ),
			'accordionCollapseButton' => Common::generateRandomString( 10 )
		];
	}
	protected function defaultClasses()
	{
		return [
			'body'             => ' ',
			'inner'            => ' ',
			'accordion'        => ' ',
			'accordionHead'    => ' ',
			'accordionContent' => ' ',
			'title'            => ' ',
			'buttonMinus'      => ' ',
			'buttonPlus'       => ' '
		];
	}

	public function start(array $settingArgs, array $items)
	{
		$defaultSettings = [
			'init'        => isset( $settingArgs['init'] ) ? $settingArgs['init'] : true,
			'activeItems' => isset( $settingArgs['activeItems'] ) ? $settingArgs['activeItems'] : [1 => true],
			'multiple'    => isset( $settingArgs['multiple'] ) ? $settingArgs['multiple'] : false,
			'classes'     => $this->defaultClasses()
		];

		$this->settings = Common::mergeClasses($defaultSettings, $settingArgs);
		$this->items    = Common::contentBuffer($items);

		?>
		<div x-data="<?php echo $this->identifiers['dataKey'] ?>" x-id="['<?php echo $this->identifiers['accordionId'] ?>']" class="w-full" :class="accordionSettings.classes.body">
		<?php
	}

	public function content()
	{?>
		<div x-bind="<?php echo $this->identifiers['accordionListBind'];?>" role="accordionList" class="grid grid-cols-1 gap-5" :class="accordionSettings.classes.inner">
			<template x-for="(accordion, index) in accordions" >
				<div x-show="accordion !== undefined" class="rounded-lg bg-white shadow accordionItem" :class="accordionSettings.classes.accordion">
					<div class="px-3 py-2 h-10" :class="accordionSettings.classes.accordionHead">
						<template x-if="accordion?.icon !== undefined">
							<div class="float-left pt-1 w-5" x-html="accordion?.icon"></div>
						</template>
						<div class="float-left" :class="(accordion?.icon !== undefined ? 'w-[calc(100%-20px)]' : 'w-full')">
							<div class="w-full grid grid-flow-row-dense grid-cols-2">
								<div>
									<button x-bind="<?php echo $this->identifiers['accordionButtonBind'];?>" class="items-center text-base font-bold cursor-pointer">
										<div x-text="accordion?.title" class="pl-3 capitalize select-none" :class="accordionSettings.classes.title"></div>
									</button>
								</div>
								<div>
									<div class="float-right">
										<div class="float-left" x-html="accordion?.head"></div>
										<button x-bind="<?php echo $this->identifiers['accordionCollapseButton'];?>" class="select-none float-right text-lg">
											<span x-show="isAccordionSelected(index)" :class="accordionSettings.classes.buttonMinus">&minus;</span>
											<span x-show="!isAccordionSelected(index)" :class="accordionSettings.classes.buttonPlus">&plus;</span>
										</button>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div x-show="isAccordionSelected(index)" x-collapse>
						<div class="grid gap-3 grid-cols-1" x-html="accordion?.content" :class="accordionSettings.classes.accordionContent"></div>
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
