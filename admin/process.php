<?php
$table='process_steps'; $current_page='process'; $page_title='Process Steps';
$intro='Your working process (Section 5).';
$columns=['step_number'=>['label'=>'Step number (e.g. 01)','type'=>'text'],'title'=>['label'=>'Step title','type'=>'text'],'description'=>['label'=>'Description','type'=>'textarea']];
require __DIR__.'/crud.php';
