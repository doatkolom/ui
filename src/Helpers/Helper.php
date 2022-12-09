<?php

function doatkolom_ui_json_encode_html(array $data) {
	return htmlspecialchars(json_encode($data), ENT_QUOTES, 'UTF-8');
}