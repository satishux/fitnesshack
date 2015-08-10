<?php 
 
$html = file_get_contents('test.txt');

echo str_replace(array('[nospin]','[/nospin]'), '', $html);

exit;

preg_match_all('{\[nospin\].*?\[/nospin\]}s', $html,$matches);
print_r($matches);

?>