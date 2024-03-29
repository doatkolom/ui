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
		$defaultSettings = [
			'init'       => isset( $settingArgs['init'] ) ? $settingArgs['init'] : true,
			'position'   => $settingArgs['position'],
			'tabSelectedId' => isset( $settingArgs['selectedId'] ) ? $settingArgs['selectedId'] : 1,
			'classes'    => $this->positionClasses( $settingArgs['position'] )
		];
		
		$this->settings = Common::mergeClasses($defaultSettings, $settingArgs);
		$this->tabs     = Common::contentBuffer($tabs);

	?><div x-data="<?php echo $this->identifiers['dataKey'] ?>" x-id="[tabId]" :class="tabSettings.classes.body"><?php
	}

	public function content()
	{?>
		<div class="bg-slate-100" :class="tabSettings.classes.tablistBody">
			<ul x-bind="<?php echo $this->identifiers['tablistBind'] ?>" role="tablist" class="items-stretch font-medium text-slate-600 font-primary text-base" :class="tabSettings?.classes?.tablist">
				<template x-for="tab in tabs">
					<li x-show="tab !== undefined" x-cloak class="m-0 relative" :class="tab?.classes?.tab_selector">
						<button x-text="tab?.title" x-bind="<?php echo $this->identifiers['tablistButtonBind'] ?>" type="button" class="h-full px-5 py-2.5 capitalize" :class="selectTabButtonClass($el.id, tab)" role="tab">
						</button>
					</li>
				</template>
			</ul>
		</div>
		<div role="tabpanels" class="tabpanels min-h-[30rem] rounded-b-md border border-gray-20" :class="tabSettings.classes.tabpanels">
			<template x-for="tab in tabs">
				<section :class="tab?.classes?.content_section" x-cloak x-show="tab !== undefined && isTabSelected($id(tabId, whichTabChild($el, $el.parentElement)))"
				:aria-labelledby="$id(tabId, whichTabChild($el, $el.parentElement))"
				role="tabpanel">
				</section>
			</template>
		</div>
	<?php
	}

	public function end()
	{?>
		</div>
		<script>
			DoatKolomUi.Tab(<?php echo json_encode( $this->identifiers ); ?>, <?php echo json_encode( $this->settings ); ?>, <?php echo json_encode( $this->tabs ) ?>);
		</script>
	<?php
	}

	protected function positionClasses( string $position = 'top' )
	{
		switch ( $position ) {
			case 'left':
				return [
					'body'      => ' ',
					'tablistBody'   => '-mr-px w-1/5 float-left ',
					'tablist'   => ' ',
					'tabpanels' => 'w-[calc(80%-1px)] float-left ',
					'selectedButton' => ' '
				];
			default:
				return [
					'body'      => ' ',
					'tablistBody'   => '-mb-px flex ',
					'tablist'   => ' flex',
					'tabpanels' => ' ',
					'selectedButton' => ' '
				];
		}
	}
}