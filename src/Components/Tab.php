<?php

namespace DoatKolom\Ui\Components;

trait Tab
{
	public function tab( array $tabs )
	{
		$tablist_position    = 'left';
		$tab_id              = $this->generateRandomString( 10 );
		$data_key            = $this->generateRandomString( 10 );
		$tablist_bind_key    = $this->generateRandomString( 10 );
		$tab_button_bind_key = $this->generateRandomString( 10 );

		if ( empty( $tabs['classes'] ) ) {
			$tab_classes = array_merge( $this->tab_classes(), [] );
		} else {
			$tab_classes = array_merge( $this->tab_classes(), $tabs['classes'] );
		}
		if ( 'top' === $tablist_position ) {
			$style_class = [
				'tablist'    => '-mb-px flex',
				'tab_button' => 'border-t border-l border-r rounded-t-md inline-flex',
				'tabpanels'  => ''
			];
		} else {
			$style_class = [
				'tablist'    => '-mr-px w-1/5 float-left',
				'tab_button' => 'border-t border-l border-b w-[calc(100%+1px)] text-left h-14',
				'tabpanels'  => 'w-[calc(80%-1px)] float-left'
			];
		}

		$nav_list     = '';
		$content_list = '';

		foreach ( $tabs['tabs'] as $key => $tab ) {
			$key++;
			if ( empty( $tab['classes'] ) ) {
				$item_classes = array_merge( $this->tab_item_classes(), [] );
			} else {
				$item_classes = array_merge( $this->tab_item_classes(), $tab['classes'] );
			}
			ob_start();
			$content_data = [];

			if ( !empty( $tab['content_api'] ) ) {
				$content_data['url']     = $tab['content_api'];
				$content_data['options'] = isset( $tab['content_api_options'] ) ? $tab['content_api_options'] : [];
			} else {
				$content_data['url']     = false;
				$content_data['options'] = false;
			}

			$content_data['content_cache'] = isset( $tab['content_cache'] ) ? $tab['content_cache'] : true;
		?>
		<li class="m-0 relative<?php echo $item_classes['tab_selector'] ?>">
			<button x-bind="<?php echo $tab_button_bind_key ?>" data-content="<?php echo doatkolom_ui_json_encode_html( $content_data ) ?>" type="button" :class="isSelected($el.id) ? 'border-gray-200 bg-white' : 'border-transparent'" class="h-full px-5 py-2.5<?php echo $style_class['tab_button'] ?><?php echo $item_classes['selector_button'] ?>" role="tab">
				<?php echo $tab['title'] ?>
			</button>
		</li>
		<?php
		$nav_list .= ob_get_clean();
		ob_start();
		?>
		<section class="<?php echo $item_classes['content_inner'] ?>" x-show="isSelected($id('<?php echo $tab_id; ?>', whichChild($el, $el.parentElement)))"
			:aria-labelledby="$id('<?php echo $tab_id; ?>', whichChild($el, $el.parentElement))"
			role="tabpanel" class="p-8">
			<?php isset( $tab['content'] ) ? $tab['content']() : $this->pulse()?>
		</section>
	<?php $content_list .= ob_get_clean(); }?>

	<div x-data="<?php echo $data_key ?>" x-id="['<?php echo $tab_id; ?>']" class="<?php echo $tab_classes['body'] ?>">
		<ul x-bind="<?php echo $tablist_bind_key ?>" role="tablist" class="<?php echo $style_class['tablist'] ?> items-stretch <?php echo $tab_classes['selectors_body'] ?>">
			<?php echo $nav_list; ?>
		</ul>
		<div role="tabpanels" class="min-h-[30rem] rounded-b-md border border-gray-20 <?php echo $style_class['tabpanels'] ?> <?php echo $tab_classes['content_body'] ?>">
			<?php echo $content_list; ?>
		</div>
	</div>
	<div class="<?php echo $tab_id ?>-pulse hidden">
		<?php $this->pulse();?>
	</div>
	<script>
		DoatKolomUi.tab('<?php echo $tab_id ?>', '<?php echo $data_key ?>', '<?php echo $tab_button_bind_key ?>', '<?php echo $tablist_bind_key ?>')
	</script>
	<?php }

	protected function tab_classes()
	{
		return [
			'body'           => '',
			'selectors_body' => ''
		];
	}

	protected function tab_item_classes()
	{
		return [
			'tab_selector'    => '',
			'selector_button' => '',
			'content_body'    => '',
			'content_inner'   => ''
		];
	}
}
