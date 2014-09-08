<?php
$test = "<a href='test'>Test’</a><br />";
$new = htmlspecialchars($test, ENT_QUOTES);
echo $test;
echo $new; // &lt;a href=&#039;test&#039;&gt;Test&lt;/a&gt;

echo "<h1>Data Filters</h1>\n<table>\n<tr>\n";
echo "<td><strong>Filter ID</strong></td>\n";
echo "<td><strong>Filter Name</strong></td>\n</tr>";
foreach(filter_list() as $id =>$filter) {
    echo "<tr><td>$filter</td><td>".filter_id($filter)."</td></tr>\n";
}
echo "</table>\n";

echo filter_var($test, FILTER_SANITIZE_SPECIAL_CHARS);
echo htmlentities('’');

?>