<?php
//******************************************************************************
// staff tool visitors.inc.php                          Martel, October 05, 2006 
//******************************************************************************
include("inc/classes/count_visitors_class.php");

function call_visitors_text()
{
    global  $tool;

    include_once('inc/functions/resort_tools.php');
    if (! user_has_access($tool))
    {
        echo "Sorry, this page is restricted to ORKFiA Staff";
        include_game_down();
        exit;
    }

    $stats = new Count_visitors;
?>
<h2>Visitor statistics (<?php echo $_SERVER['HTTP_HOST']; ?>)</h2>
<table class="medium" cellspacing="0" cellpadding="0">
  <tr class="header">
    <th colspan="4">Overall</th>
  </tr>
  <tr class="subheader">
    <th>Type</th>
    <th>Date</th>
    <th>Type</th    >
    <td>#</td>
  </tr>
  <tr class="data">
    <th width="20%">First visit at </th>
    <td class="left" width="35%"><?php echo $stats->first_last_visit("first"); ?></td>
    <td width="20%">Visits today: </td>
    <td><?php echo $stats->show_visits_today(); ?></td>
  </tr>
  <tr class="data">
    <th>Last visit at </th>
    <td class="left"><?php echo $stats->first_last_visit("last"); ?></td>
    <td>Total visits: </td>
    <td><?php echo $stats->show_all_visits(); ?></td>
  </tr>
  <tr class="data">
    <th nowrap>Daily average (month) </th>
	<td class="left"><?php echo $stats->average_visits_day(date("m"), date("Y")); ?></td>
	<td nowrap>Most  visits a day: </td>
	<td><?php echo $stats->show_max_visited_day(); ?></td>
  </tr>
</table>
<?php echo $stats->stats_country(); ?>
<?php echo $stats->stats_totals(); ?>
<?php echo $stats->stats_top_referer(20); ?>
<?php echo $stats->stats_monthly(date("m"), date("Y")); ?>

<?php
}
?>