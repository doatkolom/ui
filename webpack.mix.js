const mix = require('laravel-mix');

require('mix-tailwindcss');

const parent_selector = '.doatkolom-ui'

mix.sass('resources/sass/app.scss', 'assets/css', {}, [{
	postcss: function (root) {
		root.nodes.map(node => {
			if (node.selectors !== undefined) {
				node.selectors = node.selectors.map((selector, index) => {
					return parent_selector + ' ' + selector;
				})
			}
			return node;
		})
		return root;
	}
}]).tailwind();

mix.js('resources/js/doatkolom-ui.js', 'assets/js');