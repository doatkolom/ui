<?php

namespace DoatKolom\Ui\Components;

trait Tab
{
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
	public function tab( array $settingArgs, array $tabs )
	{
		$tabId             = $this->generateRandomString( 10 );
		$dataKey           = $this->generateRandomString( 10 );
		$tablistBind       = $this->generateRandomString( 10 );
		$tablistButtonBind = $this->generateRandomString( 10 );
		$Identifiers       = compact( 'dataKey', 'tabId', 'tablistBind', 'tablistButtonBind' );

		$settings = [
			'init'     => isset($settingArgs['init']) ? $settingArgs['init'] : true,
			'position' => $settingArgs['position'],
			'classes'  => $this->positionClasses( $settingArgs['position'] )
		];

		if ( !empty( $settingArgs['classes'] ) ) {
			foreach ( $settings['classes'] as $key => $classes ) {
				if ( isset( $settingArgs['classes'][$key] ) ) {
					$settings['classes'][$key] .= $classes;
				}
			}
		}

		foreach ( $tabs as $key => $tab ) {

			ob_start();

			if ( isset( $tabs[$key]['content'] ) ) {
				$tabs[$key]['content']();
			}

			$content = ob_get_clean();

			$tabs[$key]['content'] = $content;

			if ( isset( $tabs[$key]['content_cache'] ) ) {
				$tabs[$key]['content_cache'] = false;
			}
		}
	?>

	<div x-data="<?php echo $dataKey ?>" x-id="[tabId]" :class="settings.classes.body">
		<div>
			<ul x-bind="<?php echo $tablistBind ?>" role="tablist" class="items-stretch" :class="settings.classes.tablist">
				<template x-for="tab in tabs">
					<li x-show="tab !== undefined" class="m-0 relative" :class="tab?.classes?.tab_selector">
						<button x-text="tab?.title" x-bind="<?php echo $tablistButtonBind ?>" type="button" class="h-full px-5 py-2.5" :class="(isSelected($el.id) ? 'border-gray-200 bg-white ' + getPositionClasses('tab_button', tab?.classes?.tab_button) : 'border-transparent '+ getPositionClasses('tab_button', tab?.classes?.tab_button))" role="tab">
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
		</div>
	</div>
	<script>
		DoatKolomUi.tab(<?php echo json_encode( $Identifiers ); ?>,<?php echo json_encode( $settings ); ?>, <?php echo json_encode( $tabs ) ?>)
	</script>
<?php }
}
