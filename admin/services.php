<?php
$table='services'; $current_page='services'; $page_title='Services';
$intro='These appear in the accordion (Section 3). Drag order with the arrows.';
$columns=['title'=>['label'=>'Service title','type'=>'text'],'description'=>['label'=>'Description','type'=>'textarea']];
require __DIR__.'/crud.php';
