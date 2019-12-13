<?php
/**
 * Created by PhpStorm.
 * User: Anh Tuan
 * Date: 12/3/2014
 * Time: 9:55 AM
 */


$height='10';
if($instance['height'] <>''){
	$height = $instance['height'];
}
echo '<div class="empty_space" style="height:'.$height.'px"></div>';
