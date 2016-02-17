<?php

function generate_random_event(&$objUser, $number_updates)
{
    $iUserid     = $objUser->get_userid();
    $strRace     = $objUser->get_stat(RACE);
    $objAlliance = $objUser->get_alliance();

    for ($i = 1; $i <= $number_updates; $i++)
    {
        $do         = 0;

        $h          = date("H");
        $m          = date("m");
        $d          = date("d");
        $y          = date("Y");
        $event_time = mktime($h-$i + 1,0,0,$m,$d,$y); //hours,mins,secs,month,day,year
        $event_time = date("YmdHis", $event_time);

        // M: Convert to object methods                       -February 27, 2008
        $arrGoods     = $objUser->get_goods();
        $arrArmy      = $objUser->get_armys();
        $arrBuild     = $objUser->get_builds();
        $arrUser      = $objUser->get_user_infos();
        $arrPop       = $objUser->get_pops();
        $arrThieverys = $objUser->get_thieverys();
        $arrSpells    = $objUser->get_spells();
        $iWarTarget   = $objAlliance->get_war('target');

        // ES: halved the probability, changed from rand(0, 90)
        $rand_event = rand(0, 180);
        if ($rand_event == 1)
        {
            $bleh = rand(5,($arrUser['hours']));
            $do = 1;
            $newsnr = rand(1,5);
            if ($newsnr == "1") {
                $news = "<strong>In local news:</strong> Many of our citizens have been feeling patriotic, $bleh citizens have joined our army as soldiers.";
            } elseif ($newsnr == "2") {
                $news = "<strong>In local news:</strong> Your army, on the look for new soldiers, have forced $bleh citizens to join your army as basics.";
            } elseif ($newsnr == "3") {
                $news = "<strong>In local news:</strong> $bleh citizens, fed up with the labour and famine of daily existence, have joined your army as soldiers with hope for a better quality of life.";
            } elseif ($newsnr == "4") {
                $news = "<strong>In local news:</strong> The punishment of some local and dishonourable citizens has been to serve the army. $bleh prisoners have joined your army.";
            } else {
                $news = "<strong>In local news:</strong> A small group of people, looking for a better way of life, have stumbled upon our tribe. Upon arriving, $bleh subscribed to the army as soldiers.";
            }
            $objUser->set_army('unit1', $arrArmy['unit1'] + $bleh);
        }
        elseif ($rand_event == 2)
        {
            $bleh = rand(5,($arrUser['hours']));
            $do = 1;
            $newsnr = rand(1,5);
            if ($newsnr == "1") {
                $news = "<strong>In local news:</strong> Isolated fires have broken out around our tribe, $bleh citizens have been killed.";
            } elseif ($newsnr == "2") {
                $news = "<strong>In local news:</strong> A disruption at a local market has unfortunately resulted in $bleh citizens being crushed and killed. ";
            } elseif ($newsnr == "3") {
                $news = "<strong>In local news:</strong> Some of the tribe’s citizens have been performing religious rituals. $bleh citizens have sacrificed themselves in a mass suicide.";
            } elseif ($newsnr == "4") {
                $news = "<strong>In local news:</strong> A local group of bandits have been caught plotting, and been sentenced to death. $bleh citizens have been beheaded for this.";
            } else {
                $news = "<strong>In local news:</strong> A deadly plague has spread throughout the tribe and killed $bleh citizens.";
            }
            $objUser->set_pop('citizens', $arrPop['citizens'] + $bleh);
        }
        elseif ($rand_event == 3)
        {
            $bleh = rand(500,($arrUser['hours']+10000));
            $do = 1;
            $newsnr = rand(1,4);
            if ($newsnr == "1") {
                $news = "<strong>In local news:</strong> Our faithful citizens have rallied together to try and help out the tribe, their efforts have raised $bleh crowns for our use.";
            } elseif ($newsnr == "2") {
                $news = "<strong>In local news:</strong> Some citizens hold a celebration, thanking the tribe leader for his good will and kind gestures. They gather gifts and raise $bleh crowns for the tribe.";
            } elseif ($newsnr == "3") {
                $news = "<strong>In local news:</strong> The Army scamper through the tribe, forcing donations from citizens, and manage to bribe $bleh crowns from the people.";
            } else {
                $news = "<strong>In local news:</strong> The mines in your tribe have given their taxes to your tribe. Your tribe received $bleh crowns.";
            }
            $objUser->set_good('money', $arrGoods['money'] + $bleh);
        }
        elseif ($rand_event == 4)
        {
            $bleh = rand(5,25);
            $bleh2 = $arrBuild['farms'] * 0.05 * rand(100, 500); // 2-10% destroyed food production (abouts)
            if (rand(1,10) == 3)
                $bleh2 = $bleh2 * 10; // extra 1/10th chance of massive loss of food.

            $bleh2 = floor($bleh2);

            $do = 1;
            if ($bleh > $arrBuild['farms']) {$bleh = '0';}
            if ($bleh2 > $arrGoods['food']) {$bleh2 = $arrGoods['food'];}
            $newsnr = rand(1,5);
            if ($newsnr == "1") {
                $news = "<strong>In local news:</strong> A swarm of locusts invaded $bleh farms, destroying $bleh2 kilograms of grain.";
            } elseif ($newsnr == "2") {
                $news = "<strong>In local news:</strong> The farmers held a protest for the treatment of them and their farms, they destroy $bleh2 kilograms of food.";
            } elseif ($newsnr == "3") {
                $news = "<strong>In local news:</strong> Floods have overflown the farms of your tribe, $bleh2 kilograms of food has been ruined.";
            } elseif ($newsnr == "4") {
                $news = "<strong>In local news:</strong> Unfortunately, the harvest of your crops wasn’t as good as expected. $bleh2 kilograms of food failed to be produced.";
            } else {
                $news = "<strong>In local news:</strong> Local peasants felt they weren’t getting their fair share of food, they have stolen $bleh2 kilograms of food. Sadly they failed to be caught stealing.";
            }
            $objUser->set_good('food', $arrGoods['food'] - $bleh2);
        }
        elseif ($rand_event == 5)
        {
            $bleh = rand(5,25);
            $bleh2 = rand(7,25);
            $do = 1;
            if ($bleh > $arrArmy['unit5']) {$bleh = $arrArmy['unit5'];}
            if ($bleh2 > $arrArmy['unit1']) {$bleh2 = $arrArmy['unit1'];}
            if ($bleh > 5) {$bleh = '0';}
            if ($bleh2 > 7) {$bleh2 = '0';}
            if ($bleh == 0 ) {$display = "no";}
                else {$display = $bleh;}
            if ($bleh2 == 0 ) {$display2 = "no";}
                else {$display2 = $bleh2;}
            $newsnr = rand(1,5);
            if ($newsnr == "1") {
                $news = "<strong>In local news:</strong> A local rebellion was quelled. Military units had to step in and control the situation, $display military units were lost in the rioting. Our military also killed $display2 renegade thieves ";
            } elseif ($newsnr == "2") {
                $news = "<strong>In local news:</strong> In training, some troops stepped out of line and had to be relieved of their duties. The report shows that $display troops have been lost.";
            } elseif ($newsnr == "3") {
                $news = "<strong>In local news:</strong> Failing to obey their leader, some soldiers took it upon them to make a decision. The consequences ended with $display soldiers being lost.";
            } elseif ($newsnr == "4") {
                $news = "<strong>In local news:</strong> A soldier returned to the barracks, only to spread an infectious disease around the camp. Unable to treat the troops $display of them died. ";
            } else {
                $news = "<strong>In local news:</strong> Unable to cope with the harsh brutality of combat and battle $display troops have left the army. ";
            }
            $objUser->set_army('unit1', $arrArmy['unit1'] - $bleh2);
            $objUser->set_army('unit5', $arrArmy['unit5'] - $bleh);
        }
        elseif ($rand_event == 6)
        {
            $bleh = rand(500,($arrUser['hours']*200));
            $do = 1;
            if ($bleh > $arrGoods['money']) {$bleh = $arrGoods['money'];}
            $newsnr = rand(1,5);
            if ($newsnr == "1") {
                $news = "<strong>In local news:</strong> Santa Claus collected his taxes, you lost $bleh crowns.";
            } elseif ($newsnr == "2") {
                $news = "<strong>In local news:</strong> Scared of the consequences, our tribe have paid $bleh crowns as bribe money to the local thugs.";
            } elseif ($newsnr == "3") {
                $news = "<strong>In local news:</strong> At the local market, a few stalls were blown over due to gusty winds, $bleh crowns have been used to pay for the damage.";
            } elseif ($newsnr == "4") {
                $news = "<strong>In local news:</strong> A local group of rebellious peasants have stolen money from the tribe’s treasury, and given it to the poor. The tribe has lost $bleh crowns.";
            } else {
                $news = "<strong>In local news:</strong> Unable to control his gambling, a leader has used some of the tribes money in an outsider bet. The tribe has lost $bleh crowns.";
            }
            $objUser->set_good('money', $arrGoods['money'] - $bleh);
        }
        elseif ($rand_event == 7)
        {
            $bleh = rand(50,2500);
            $do = 1;
            if ($bleh > $arrGoods['food']) {$bleh = $arrGoods['food'];}
            $newsnr = rand(1,5);
            if ($newsnr == "1") {
                $news = "<strong>In local news:</strong> Our army has held a feast in your honour, $bleh extra kilograms of grain was consumed in this celebration.";
            } elseif ($newsnr == "2") {
                $news = "<strong>In local news:</strong> Your cattle run riot and escape from their pens, they manage to consume $bleh extra kilograms of food.";
            } elseif ($newsnr == "3") {
                $news = "<strong>In local news:</strong> Heavy winds leave the land unable to be farmed. Unfortunately $bleh kilograms of food was unable to be produced.";
            } elseif ($newsnr == "4") {
                $news = "<strong>In local news:</strong> A disease has spread through 2 farms and sadly leads to $bleh kilograms of food being destroyed.";
            } else {
                $news = "<strong>In local news:</strong> Drought has prevented the crops getting the water they desperately need, unfortunately $bleh kilograms of food failed to be harvested. ";
            }
            $objUser->set_good('food', $arrGoods['food'] - $bleh);
        }
        elseif ($rand_event == 8 || $rand_event == 9)
        {
            $bleh = rand(($arrGoods['wood']*0.05), ($arrGoods['wood']*0.20)); // ES: changed to allow 5-30% of total wood to be destroyed
            // $bleh = floor($bleh / $i); // ES: this was stupid - it shouldn't penalize players for logging in more often
            if ($bleh > $arrGoods['wood']) {$bleh = $arrGoods['wood'];}
            $do = 1;
            $newsnr = rand(1,5);
            if ($newsnr == "1") {
                $news = "<strong>In local news:</strong> Many of our buildings required repairs due to storm damage, $bleh logs were used.";
            } elseif ($newsnr == "2") {
                $news = "<strong>In local news:</strong> An irresponsible lumberjack accidentally set fire to the wood stock pile. $bleh logs have been destroyed.";
            } elseif ($newsnr == "3") {
                $news = "<strong>In local news:</strong> A fire breaks out in the nearby forest, $bleh logs were destroyed by the roaring flames.";
            } elseif ($newsnr == "4") {
                $news = "<strong>In local news:</strong> The tribe has been hit by an unusual batch of cold weather, $bleh extra logs were burnt to keep the citizens warm.";
            } else {
                $news = "<strong>In local news:</strong> The tribe, covered in darkness was forced to burn logs for light. $bleh logs were burnt in the process.";
            }
            $objUser->set_good('wood', $arrGoods['wood'] - $bleh);
        }
        elseif ($rand_event == 10 && $arrArmy['unit2'] > 0)
        {
            $bleh = rand(5,($arrUser['hours']*4));
            $do = 1;
            if ($bleh > $arrArmy['unit2']) {$bleh = $arrArmy['unit2'];}
            $newsnr = rand(1,4);
            if ($newsnr == "1") {
                $news = "<strong>In local news:</strong> Our army reported that during training, an accident occured in which $bleh offence specialists were badly wounded and had to quit.";
            } elseif ($newsnr == "2") {
                $news = "<strong>In local news:</strong> Sent out on a secret intelligence mission. $bleh offence units have been killed.";
            } elseif ($newsnr == "3") {
                $news = "<strong>In local news:</strong> Failing to obey their orders, a small amount of attacking units have been executed. $bleh units were punished. ";
            } else {
                $news = "<strong>In local news:</strong> An argument about who is the most fearsome started between a group of attacking units and ended up in a fight. $bleh offensive units were lost.";
            }
            $objUser->set_army('unit2', $arrArmy['unit2'] - $bleh);
        }
        elseif ($rand_event == 11)
        {
            // ES: improved the large RE calculation method (19.02.2006)
            $bleh = 0;
            $lcutoff = $arrBuild['land'] * 0.01;
            $hcutoff = $arrBuild['land'] * 0.05;

            while (($bleh < $lcutoff) || ($bleh > $hcutoff))
                $bleh = round(($arrBuild['land'] * 5) * (0.0060 + (1.5/(rand(100, 2500))) - (1.5/(rand(100, 2500)))));

            // ES: end of the new large RE method

            $do = 1;
            $newsnr = rand(1,5);
            if ($newsnr == "1") {
                $news = "<strong>In local news:</strong> Some of our farmers have explored new acres for our use while searching for more suitable farming land. We have gained $bleh new acres.";
            } elseif ($newsnr == "2") {
                $news = "<strong>In local news:</strong> Whilst out chopping trees, our lumberjacks have expanded further into the woodlands. Our tribe has gained $bleh new acres. ";
            } elseif ($newsnr == "3") {
                $news = "<strong>In local news:</strong> Some citizens, fed up with our cramp conditions, have set off to another land. When doing so they found a nearby area of barren land, appropriate for living on, and decided to stay with the tribe. Our tribe has gained $bleh new acres.";
            } elseif ($newsnr == "4") {
                $news = "<strong>In local news:</strong> A guard on watch spots a clean plot of land, not far from our outposts, and sets out to investigate it further. The guard and his men have claimed $bleh new acres.";
            } else {
                $news = "<strong>In local news:</strong> Military units set out on a training exercise. On doing so, they discovered $bleh new acres of land for the tribe to use.";
            }
            $objUser->set_build('land', $arrBuild['land'] + $bleh);
        }
        elseif ($rand_event == 12 || $rand_event == 13)
        {
            $bleh = rand(1,15);
            $do = 1;
            $newsnr = rand(1,5);
            if ($newsnr == "1") {
                $news = "<strong>In local news:</strong> Some of our farmers have explored new acres for our use while searching for more suitable farming land. We have gained $bleh new acres.";
            } elseif ($newsnr == "2") {
                $news = "<strong>In local news:</strong> Whilst out collecting wood, our lumberjacks have come across a new plot of land suitable for settling upon. Our tribe has gained $bleh new acres. ";
            } elseif ($newsnr == "3") {
                $news = "<strong>In local news:</strong> Some citizens, fed up with cramp conditions, set off to another land. Upon doing so they found a new area of land appropriate for living on, and decided to stay with the tribe. Our tribe has gained $bleh new acres.";
            } elseif ($newsnr == "4") {
                $news = "<strong>In local news:</strong> A guard on his watch, spots a clean plot of land not far from our own and sets out to see more. The guard manages to find $bleh new acres.";
            } else {
                $news = "<strong>In local news:</strong> Military units set out on a training exercise. On doing so, they discover $bleh new acres of land for the tribe to use.";
            }
            $objUser->set_build('land', $arrBuild['land'] + $bleh);
        }
/*        elseif ($rand_event == 14) {
            $bleh = rand(1,round($arrBuild['land'] * 0.02));
            $bleh2= rand(0,round($arrBuild['farms'] * 0.08));
            $do = 1;
            $news = "<strong>In local news:</strong> Rebel forces have been spotted razing land on the edge of our tribe! $bleh2 farms have been totally destroyed and $bleh acres have been made uninhabitable.";
//             $query = "Update build set land = land - $bleh, farms = farms - $bleh2 where id = $iUserid";
        }
*/
        elseif ($rand_event == 15)
        {
            $bleh = rand(1,$arrUser['hours']);
            $do = 1;
            $newsnr = rand(1,3);
            if ($newsnr == "1") {
                $news = "<strong>In local news:</strong> A wandering band of mercenaries are impressed by your leadership qualities and volunteer for your military as soldiers.";
            } elseif ($newsnr == "2") {
                $news = "<strong>In local news:</strong> A few homeless citizens join the army as soldiers, hoping that they will be catered for. $bleh citizens have joined your army as basics.";
            } else {
                $news = "<strong>In local news:</strong> Some citizens proved themselves worthy of fighting, their passion for violence allowed our tribe to gain $bleh new basic soldiers.";
            }
            $objUser->set_army('unit1', $arrArmy['unit1'] + $bleh);
        }
        elseif ($rand_event == 16)
        {
            $bleh = rand(1,($arrUser['hours']*2));
            $do = 1;
            $newsnr = rand(1,4);
            if ($newsnr == "1") {
                $news = "<strong>In local news:</strong> One of our science students has excelled in his studies creating $bleh more research points for us to use.";
            } elseif ($newsnr == "2") {
                $news = "<strong>In local news:</strong> Intelligent citizens have been doing some research in their free time and managed to produce $bleh research points for the tribe. ";
            } elseif ($newsnr == "3") {
                $news = "<strong>In local news:</strong> A lab has been pushed to it\'s limits. You have gained $bleh more research points. ";
            } else {
                $news = "<strong>In local news:</strong> A group of science students made a significant breakthrough and created $bleh more research points for the tribe.";
            }
            $objUser->set_good('research', $arrGoods['research'] + $bleh);
        }
        elseif ($rand_event == 17)
        {
            $bleh = rand(1,round($arrUser['hours']/2));
            $do = 1;
            $newsnr = rand(1,5);
            if ($newsnr == "1") {
                $news = "<strong>In local news:</strong> A well known thief has been released from prison in a tribe not too far from our own, seeking a new home for himself and his kinsmen he has settled on our lands. $bleh new thieves have joined our guild.";
            } else {
                $news = "<strong>In local news:</strong> An old thief has been rehired for his expertise; the thief brought along his fellow kinsmen. $bleh thieves have joined our military. ";
            }
            $objUser->set_army('unit5', $arrArmy['unit5'] + $bleh);
        }
        elseif ($rand_event == 18)
        {
            $bleh = rand(1,round($arrUser['hours']*9));
            $do = 1;
            $news = "<strong>In local news:</strong> Your thieves have been using their swift feet and light fingers again, $bleh crowns have found their way into our treasury, markings indicate they are from our own alliance tribes.";
            $objUser->set_good('money', $arrGoods['money'] + $bleh);
        }
        elseif ($rand_event == 19)
        {
            $bleh = rand(1,199);
            $do = 1;
            $newsnr = rand(1,5);
            if ($newsnr == "1") {
                $news = "<strong>In local news:</strong> $bleh citizens packed their donkeys and left to find a better life in a tribe more suited for their needs.";
            } elseif ($newsnr == "2") {
                $news = "<strong>In local news:</strong> $bleh citizens, fed up with the welfare of themselves and their family have left the tribe to find a better life. ";
            } elseif ($newsnr == "3") {
                $news = "<strong>In local news:</strong> Citizens trying to leave the tribe failed to remember the moat, and unfortunately they couldn't swim either. $bleh citizens have been lost.";
            } elseif ($newsnr == "4") {
                $news = "<strong>In local news:</strong> Some citizens fight back against the military of their own lands. The citizens fail to succeed, and fail to keep their lives as well. $bleh citizens have been lost.";
            } else {
                $news = "<strong>In local news:</strong> $bleh citizens refused to pay their taxes and were sentenced to death. The citizens were executed.";
            }
            $objUser->set_pop('citizens', $arrPop['citizens'] - $bleh);
        }
        elseif ($rand_event == 20)
        {
            $bleh = rand(500,($arrUser['hours']*20));
            $bleh2 = rand(200,($arrUser['hours']*5));
            $do = 1;
            if ($bleh > $arrGoods['money']) {$bleh = $arrGoods['money'];}
            if ($bleh2 > $arrGoods['wood']) {$bleh2 = $arrGoods['wood'];}
            $newsnr = rand(1,5);
            if ($newsnr == "1") {
                $news = "<strong>In local news:</strong> Your mage has been testing out a new spell which didn\'t appear to go so well, $bleh2 logs and $bleh crowns were needed to repair the castle after the explosion which resulted.";
            } else {
                $news = "<strong>In local news:</strong> The construction of some buildings went horribly wrong. $bleh2 logs have been wasted and $bleh crowns used.";
            }
            $objUser->set_good('money', $arrGoods['money'] - $bleh);
            $objUser->set_good('wood', $arrGoods['wood'] - $bleh2);
        }
        elseif ($rand_event == 21 && $iWarTarget > 0)
        {
            $do = 1;
            $bonus = round(0.1 * $arrThieverys["credits"]);
            $news = "<strong>In local news:</strong> Some of our thieves are afraid of our enemy and dare not to step over our borders. We have lost $bonus thievery points";
            $objUser->set_thievery('credits', $arrThieverys['credits'] - $bonus);
        }
        elseif ($rand_event == 22 && $iWarTarget > 0 && ($bonus = round(0.1 * $arrSpells["power"])) > 0)
        {
            $do = 1;
            $news = "<strong>In local news:</strong> The gods have cursed our war by draining our guilds! We have lost $bonus magic power.";
            $objUser->set_spell('power', $arrSpells['power'] - $bonus);
        }
        elseif ($rand_event == 23 && $iWarTarget > 0)
        {
            $do = 1;
            $bonus = round((1 + $arrBuild["guilds"] / (2 * $arrBuild["land"])) * $arrBuild["guilds"] * 0.1) + 1;
            if ($strRace == "Eagles")
                $bonus = round($bonus * 1.35);
            $news = "<strong>In local news:</strong> The gods have blessed our war by filling our guilds! We have gained $bonus magic power";
            $objUser->set_spell('power', $arrSpells['power'] + $bonus);
        }
        elseif ($rand_event == 24 && $iWarTarget > 0)
        {
            $do = 1;
            $bonus = round((1 + 2 * $arrBuild["hideouts"] / $arrBuild["land"]) * $arrBuild["hideouts"] * 0.1) + 1;
            $news = "<strong>In local news:</strong> Our thieves are extra motivated because of our war! We have gained $bonus thievery points.";
            $objUser->set_thievery('credits', $arrThieverys['credits'] + $bonus);
        }
        elseif ($rand_event == 25 && $iWarTarget > 0)
        {
            $do = 1;
            if ($strRace == "Mori Hai")
            {
                $arrMercs    = $objUser->get_army_mercs();
                $arrNewMercs = array(
                    MERC_T0 => $arrMercs[MERC_T1],
                    MERC_T1 => $arrMercs[MERC_T2],
                    MERC_T2 => $arrMercs[MERC_T3],
                    MERC_T3 => 0
                );
                $objUser->set_army_mercs($arrNewMercs);
            }

            $arrRets = $objUser->get_milreturns();
            $arrNewRets = array(
                UNIT1_T1 => $arrRets[UNIT1_T2], UNIT1_T2 => $arrRets[UNIT1_T3],
                UNIT1_T3 => $arrRets[UNIT1_T4], UNIT1_T4 => 0,
                UNIT2_T1 => $arrRets[UNIT2_T2], UNIT2_T2 => $arrRets[UNIT2_T3],
                UNIT2_T3 => $arrRets[UNIT2_T4], UNIT2_T4 => 0,
                UNIT3_T1 => $arrRets[UNIT3_T2], UNIT3_T2 => $arrRets[UNIT3_T3],
                UNIT3_T3 => $arrRets[UNIT3_T4], UNIT3_T4 => 0,
                UNIT4_T1 => $arrRets[UNIT4_T2], UNIT4_T2 => $arrRets[UNIT4_T3],
                UNIT4_T3 => $arrRets[UNIT4_T4], UNIT4_T4 => 0,
                UNIT5_T1 => $arrRets[UNIT5_T2], UNIT5_T2 => $arrRets[UNIT5_T3],
                UNIT5_T3 => $arrRets[UNIT5_T4], UNIT5_T4 => 0,
                UNIT6_T1 => $arrRets[UNIT6_T2], UNIT6_T2 => $arrRets[UNIT6_T3],
                UNIT6_T3 => $arrRets[UNIT6_T4], UNIT6_T4 => 0
            );
            $objUser->set_milreturns($arrNewRets);

            $news = "Our current war has encouraged our army to march at a higher pace. They will be home one hour sooner!";

        }
        elseif ($rand_event == 90 && date('n') == 4 && date('j') == 1)
        {
            $do = 1;
            $newsarray = array(
            "Random news: You have no defence because your army is eating random lollies, then your general beats their face in and sends them back to work.",
            "Random news: Your friendly local harpy tribe decides to BC the evil canucks. 8% losses * 5 * 5 = MASSIVE DAMAGE!",
            "Random news: some bunch of angry viks have invasioned your lands and stolen 1337 acres! They gave them back when Skathen couldn\'t spell the new tribename right though.",
            "Random news: Fight tribenews spam! Spam people\'s news with this message!",
            "Admin1: I thought you had removed this lame news?!<br />Admin2: Nuh-uh<br />Admin1: Well, at least change it.<br />Admin2: Alrighty then.",
            "Random news: Oleg babies throw bananas on ravens, that's why humans taste like green chicken.",
            "<form method=\"post\" action=\"index.php?cat=game&amp;page=message&amp;reporttype=n00b\"><input type=\"hidden\" name=\"n00bieness\" value=\"I clicked the don\'t-push-this-button!\" /><input type=\"submit\" name=\"submit\" value=\"Do NOT push this button!\" /></form>"
            );
            $news = array_rand($newsarray);
        }

        if ($do == 1)
        {
            //for safety:
            $news = stripslashes($news);
            $news = mysql_real_escape_string($news);
            $new_news = mysql_query("INSERT INTO `news` (`id`, `time`, `ip`, `type`, `duser`, `ouser`, `result`, `text`, `kingdom_text`) VALUES ('', '$event_time', '', 'local_news', '$iUserid', '', '1', '$news','')");
            $objUser->set_user_info(LAST_NEWS, 1);
        }
    }
}

?>