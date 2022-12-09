<?php

namespace DoatKolom\Ui\Components;

trait Tab
{
	public function tab(array $tabs)
	{
		$id           = $this->generateRandomString( 10 );
        $data         = $this->generateRandomString( 10 );
        $bind_tablist = $this->generateRandomString( 10 );
        $bind_button  = $this->generateRandomString( 10 );

        if(empty($tabs['classes'])) {
          $tab_classes = array_merge($this->tab_classes(), []);
        } else {
          $tab_classes = array_merge($this->tab_classes(), $tabs['classes']);
        }

		$nav_list = '';
		$content_list = '';

		foreach ( $tabs['tabs'] as $key => $tab ) {
			$key ++;
			if(empty($tab['classes'])) {
				$item_classes = array_merge($this->tab_item_classes(), []);
			} else {
				$item_classes = array_merge($this->tab_item_classes(), $tab['classes']);
			}
			ob_start();
			$content_data = [];
	
			if(!empty($tab['content_url'])) {
				$content_data['url'] = $tab['content_url'];
				$x_html= 'x-html="contents[' . $key . ']"';
			} else {
				$x_html= '';
				$content_data['url'] = false;
			}
			$content_data['content_cache'] = isset($tab['content_cache']) ? $tab['content_cache'] : true;
			?>
			<li class="m-0 <?php echo $item_classes['tab_selector'] ?>">
				<button x-bind="<?php echo $bind_button ?>" data-content="<?php echo doatkolom_ui_json_encode_html($content_data)?>" type="button" :class="isSelected($el.id) ? 'border-gray-200 bg-white' : 'border-transparent'" class="inline-flex rounded-t-md border-t border-l border-r px-5 py-2.5 <?php echo $item_classes['selector_button'] ?>" role="tab">
					<?php echo $tab['title'] ?>
				</button>
			</li>
			<?php 
			$nav_list .= ob_get_clean();
			ob_start();
			?>
			<section <?php echo $x_html; ?> class="<?php echo $item_classes['content_inner'] ?>" x-show="isSelected($id('<?php echo $id; ?>', whichChild($el, $el.parentElement)))"
				:aria-labelledby="$id('<?php echo $id; ?>', whichChild($el, $el.parentElement))"
				role="tabpanel" class="p-8">
				<?php isset( $tab['content']) ? $tab['content']() : $this->pulse()?>
			</section>
		<?php 
			$content_list .= ob_get_clean();
		}?>

		<div x-data="<?php echo $data ?>" x-id="['<?php echo $id; ?>']" class="<?php echo $tab_classes['body'] ?>">
			<ul x-bind="<?php echo $bind_tablist ?>" role="tablist" class="-mb-px flex items-stretch <?php echo $tab_classes['selectors_body'] ?>">
				<?php echo $nav_list; ?>
			</ul>
			<div role="tabpanels" class="rounded-b-md border border-gray-200 bg-white <?php echo $tab_classes['content_body'] ?>">
				<?php echo $content_list;?>
			</div>
		</div>
		<div class="<?php echo $id?>-pulse hidden">
			<?php $this->pulse(); ?>
		</div>
		<script>
			document.addEventListener('alpine:init', () => {
				Alpine.data('<?php echo $data ?>', () => ({
					selectedId: null,
					contents: {},
					is_requesting_content: false,
					fetch_tab_status: {},
					pulse: '',
					init() { this.$nextTick(() => {
						var pulseDocument = document.querySelector('.<?php echo $id?>-pulse');
						this.pulse = pulseDocument.innerHTML;
						pulseDocument.remove();
						for (let i = 1; i < this.$refs.tablist.childElementCount + 1; i++) {
							this.contents[i] = this.pulse;
						}
						return this.select(this.$id('<?php echo $id ?>', 1))
					}) },
					select(id) {
						this.fetchContent(id);
						this.selectedId = id
					},
					isSelected(id) { return this.selectedId === id },
					whichChild(el, parent) { return Array.from(parent.children).indexOf(el) + 1 },
					fetchContent(id) {
						if(!this.is_requesting_content) {
							var data = JSON.parse(document.querySelector('#'+id).dataset.content);
							var id = id.substr(id.length - 1);
							if(data.url && (this.fetch_tab_status[id] === undefined || this.fetch_tab_status[id] === false || data.content_cache === false)) {
								this.contents[id] = this.pulse;
								this.is_requesting_content = true;
								fetch(data.url)
								.then(res => res.text())
								.then(content => {
									this.contents[id] = content;
									this.fetch_tab_status[id] = true;
									this.is_requesting_content = false;
								});
							}
						}
					},
				}))
				Alpine.bind('<?php echo $bind_tablist ?>', () => ({
					['x-ref']: 'tablist',
					['@keydown.right.prevent.stop']() { this.$focus.wrap().next() },
					['@keydown.home.prevent.stop']() { this.$focus.first() },
					['@keydown.page-up.prevent.stop']() { this.$focus.first() },
					['@keydown.left.prevent.stop']() { this.$focus.wrap().prev() },
					['@keydown.end.prevent.stop']() { this.$focus.last() },
					['@keydown.page-down.prevent.stop']() { this.$focus.last() }
				}))
				Alpine.bind('<?php echo $bind_button ?>', () => ({
					[':id']() {
						return this.$id('<?php echo $id ?>', this.whichChild(this.$el.parentElement, this.$refs.tablist))
					},
					['@click']() {
						this.select(this.$el.id);
					},
					['@focus']() {
						this.select(this.$el.id)
					},
					[':tabindex']() { return this.isSelected(this.$el.id) ? 0 : -1 },
					[':aria-selected']() { return this.isSelected(this.$el.id) },
					[':class']() {
						return this.isSelected(this.$el.id) ? 'border-black bg-white' : 'border-transparent'
					}
				}))
			})
		</script>
		<?php
	}

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
