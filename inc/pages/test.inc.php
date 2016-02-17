<?php
// some testing page
if ($_SERVER['SERVER_NAME'] != 'development.orkfia.org')
{
    die('horribly');
}
function include_test_text()
{
    require_once('inc/races/clsRace.php');
    $objUruk = clsRace::getRace('Uruk Hai');
    test_race($objUruk);
    test_race(clsRace::getRace('Oleg Hai'));
    test_race(clsRace::getRace('Mori Hai'));
    test_race(clsRace::getRace('Dark Elf'));
    test_race(clsRace::getRace('Wood Elf'));
    test_race(clsRace::getRace('High Elf'));
    test_race(clsRace::getRace('Dwarf'));
    test_race(clsRace::getRace('Viking'));
    test_race(clsRace::getRace('Brittonian'));
    test_race(clsRace::getRace('Raven'));
    test_race(clsRace::getRace('Dragon'));
    test_race(clsRace::getRace('Eagle'));
    test_race(clsRace::getRace('Nazgul'));
    test_race(clsRace::getRace('Undead'));
    test_race(clsRace::getRace('Spirit'));
}
function test_race(&$objRace){
    echo "<br /><br />Race: " . $objRace->getRaceName();
    echo "<br />Names: ";
    var_dump($objRace->getUnitNames());
    echo "<br />Costs: ";
    var_dump($objRace->getUnitCosts());
    for($i=1;$i<6;$i++){
        echo "<br />Unit $i is: " . $objRace->getUnitName($i) . ' ' . $objRace->getUnitCost($i) . 'cr (' . $objRace->getUnitOffence($i) . '/' . $objRace->getUnitDefence($i) . ')';
    }
}
?>
