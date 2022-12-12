<?php

namespace DoatKolom\Ui\Components;

use DoatKolom\Ui\Utils\Common;

class Tab extends ComponentBase
{
	protected $identifiers;
	protected $settings;
	protected $tabs;

	public function setIdentifiers()
	{
		$this->identifiers = [
			'tabId'             => Common::generateRandomString( 10 ),
			'dataKey'           => Common::generateRandomString( 10 ),
			'tablistBind'       => Common::generateRandomString( 10 ),
			'tablistButtonBind' => Common::generateRandomString( 10 )
		];
	}

	public function start( array $settingArgs, array $tabs )
	{
		$settings = [
			'init'       => isset( $settingArgs['init'] ) ? $settingArgs['init'] : true,
			'position'   => $settingArgs['position'],
			'selectedId' => isset( $settingArgs['selectedId'] ) ? $settingArgs['selectedId'] : 1,
			'classes'    => $this->positionClasses( $settingArgs['position'] )
		];

		if ( !empty( $settingArgs['classes'] ) ) {
			foreach ( $settings['classes'] as $key => $classes ) {
				if ( isset( $settingArgs['classes'][$key] ) ) {
					$settings['classes'][$key] .= $classes;
				}
			}
		}

		$this->settings = $settings;
		$this->tabs     = Common::contentBuffer($tabs);

	?><div x-data="<?php echo $this->identifiers['dataKey'] ?>" x-id="[tabId]" :class="settings.classes.body"><?php
	}

	public function content()
	{?>
		<ul x-bind="<?php echo $this->identifiers['tablistBind'] ?>" role="tablist" class="items-stretch" :class="settings.classes.tablist">
			<template x-for="tab in tabs">
				<li x-show="tab !== undefined" class="m-0 relative" :class="tab?.classes?.tab_selector">
					<button x-text="tab?.title" x-bind="<?php echo $this->identifiers['tablistButtonBind'] ?>" type="button" class="h-full px-5 py-2.5" :class="(isSelected($el.id) ? 'border-gray-200 bg-white ' + getPositionClasses('tab_button', tab?.classes?.tab_button) : 'border-transparent '+ getPositionClasses('tab_button', tab?.classes?.tab_button))" role="tab">
					</button>
				</li>
			</template>
		</ul>
		<div role="tabpanels" class="tabpanels min-h-[30rem] rounded-b-md border border-gray-20" :class="settings.classes.tabpanels">
			<template x-for="tab in tabs">
				<section :class="tab?.classes?.content_section" x-show="tab !== undefined && isSelected($id(tabId, whichChild($el, $el.parentElement)))"
				:aria-labelledby="$id(tabId, whichChild($el, $el.parentElement))"
				role="tabpanel" class="p-8">
				</section>
			</template>
		</div>
	<?php
	}

	public function end()
	{?>
		</div>
		<script>
			DoatKolomUi.Tab(<?php echo json_encode( $this->identifiers ); ?>,<?php echo json_encode( $this->settings ); ?>,<?php echo json_encode( $this->tabs ) ?>)
		</script>
	<?php
	}

	protected function positionClasses( string $position = 'top' )
	{
		switch ( $position ) {
			case 'left':
				return [
					'body'      => ' ',
					'tablist'   => '-mr-px w-1/5 float-left ',
					'tabpanels' => 'w-[calc(80%-1px)] float-left '
				];
			default:
				return [
					'body'      => ' ',
					'tablist'   => '-mb-px flex ',
					'tabpanels' => ' '
				];
		}
	}
}