<?
function show_ranking($age, $type)
{
    echo "<tr><td class=\"tableheader\" colspan=\"4\" align=\"center\"> Alliance $type Rankings of Age $age </td></tr>";
    echo "<tr><td class=\"tablesubheader\" colspan=\"2\">Alliance Name</td><td class=\"tablesubheader\" align=\"center\">Alliance #</td><td class=\"tablesubheader\" align=\"right\">$type</td></tr>";

    // AGE 1
    if($age == "1" && $type == "Land") {
        echo "<tr><td>1</td><td>Bring Out Your Dead!</td><td align=\"center\">(#3)</td><td align=\"right\">86,253</td></tr>";
        echo "<tr><td>2</td><td>The Elven Council</td><td align=\"center\">(#9)</td><td align=\"right\">76,552</td></tr>";
        echo "<tr><td>3</td><td>C2H5OH</td><td align=\"center\">(#31)</td><td align=\"right\">75,432</td></tr>";
        echo "<tr><td>4</td><td>Is it Justice or Just us</td><td align=\"center\">(#36)</td><td align=\"right\">67,542</td></tr>";
        echo "<tr><td>5</td><td>AnnO Domini</td><td align=\"center\">(#86)</td><td align=\"right\">59,494</td></tr>";
        echo "<tr><td>6</td><td>sMiLiNg NaTiOn 8D</td><td align=\"center\">(#26)</td><td align=\"right\">53,751</td></tr>";
        echo "<tr><td>7</td><td>Cereal Killers</td><td align=\"center\">(#21)</td><td align=\"right\">48,456</td></tr>";
        echo "<tr><td>8</td><td>PeRioDiC KiLLeRs</td><td align=\"center\">(#7)</td><td align=\"right\">45,524</td></tr>";
        echo "<tr><td>9</td><td>Knights of Insanity</td><td align=\"center\">(#46)</td><td align=\"right\">45,159</td></tr>";
        echo "<tr><td>10</td><td>The Knighthood</td><td align=\"center\">(#65)</td><td align=\"right\">35,515</td></tr>";
    }
    elseif($age == "1" && $type == "Strength") {
        echo "<tr><td>1</td><td>Bring Out Your Dead!</td><td align=\"center\">(#3)</td><td align=\"right\">31,401,592</td></tr>";
        echo "<tr><td>2</td><td>C2H5OH</td><td align=\"center\">(#31)</td><td align=\"right\">27,897,457</td></tr>";
        echo "<tr><td>3</td><td>Is it Justice or Just us</td><td align=\"center\">(#36)</td><td align=\"right\">22,247,971</td></tr>";
        echo "<tr><td>4</td><td>The Elven Council</td><td align=\"center\">(#9)</td><td align=\"right\">22,049,028</td></tr>";
        echo "<tr><td>5</td><td>AnnO Domini</td><td align=\"center\">(#86)</td><td align=\"right\">19,603,412</td></tr>";
        echo "<tr><td>6</td><td>sMiLiNg NaTiOn 8D</td><td align=\"center\">(#26)</td><td align=\"right\">18,324,996</td></tr>";
        echo "<tr><td>7</td><td>Knights of Insanity</td><td align=\"center\">(#46)</td><td align=\"right\">15,598,346</td></tr>";
        echo "<tr><td>8</td><td>PeRioDiC KiLLeRs</td><td align=\"center\">(#7)</td><td align=\"right\">14,384,847</td></tr>";
        echo "<tr><td>9</td><td>Cereal Killers</td><td align=\"center\">(#21)</td><td align=\"right\">12,836,151</td></tr>";
        echo "<tr><td>10</td><td>*Dushì*</td><td align=\"center\">(#49)</td><td align=\"right\">12,492,153</td></tr>";
    }
    elseif($age == "1" && $type == "Fame") {
        echo "<tr><td>1</td><td>C2H5OH</td><td align=\"center\">(#31)</td><td align=\"right\">419,130</td></tr>";
        echo "<tr><td>2</td><td>Bring Out Your Dead!</td><td align=\"center\">(#3)</td><td align=\"right\">363,140</td></tr>";
        echo "<tr><td>3</td><td>THE CARNIVAL</td><td align=\"center\">(#45)</td><td align=\"right\">200,334</td></tr>";
        echo "<tr><td>4</td><td>Is it Justice or Just us</td><td align=\"center\">(#36)</td><td align=\"right\">183,356</td></tr>";
        echo "<tr><td>5</td><td>Cereal Killers</td><td align=\"center\">(#21)</td><td align=\"right\">173,592</td></tr>";
        echo "<tr><td>6</td><td>AnnO Domini</td><td align=\"center\">(#86)</td><td align=\"right\">171,874</td></tr>";
        echo "<tr><td>7</td><td>PeRioDiC KiLLeRs</td><td align=\"center\">(#7)</td><td align=\"right\">152,905</td></tr>";
        echo "<tr><td>8</td><td>Ancient tribes</td><td align=\"center\">(#14)</td><td align=\"right\">147,977</td></tr>";
        echo "<tr><td>9</td><td>The Elven Council</td><td align=\"center\">(#9)</td><td align=\"right\">143,436</td></tr>";
        echo "<tr><td>10</td><td>Gamers Reincarnate</td><td align=\"center\">(#189)</td><td align=\"right\">129,094</td></tr>";
    }
    
    // AGE 2
    elseif($age == "2" && $type == "Land") {
        echo "<tr><td>1</td><td>Svensk mat rulez!</td><td align=\"center\">(#37)</td><td align=\"right\">116,937</td></tr>";
        echo "<tr><td>2</td><td>The New Fellows</td><td align=\"center\">(#14)</td><td align=\"right\">51,530</td></tr>";
        echo "<tr><td>3</td><td>Chaos in Your Life</td><td align=\"center\">(#81)</td><td align=\"right\">51,254</td></tr>";
        echo "<tr><td>4</td><td>Night Skies</td><td align=\"center\">(#4)</td><td align=\"right\">50,341</td></tr>";
        echo "<tr><td>5</td><td>Cereal Killers</td><td align=\"center\">(#5)</td><td align=\"right\">49,032</td></tr>";
        echo "<tr><td>6</td><td>Nations Of War</td><td align=\"center\">(#42)</td><td align=\"right\">48,439</td></tr>";
        echo "<tr><td>7</td><td>::[Black Moon Chronicles]::</td><td align=\"center\">(#12)</td><td align=\"right\">46,566</td></tr>";
        echo "<tr><td>8</td><td>Poetic Justice!</td><td align=\"center\">(#3)</td><td align=\"right\">46,386</td></tr>";
        echo "<tr><td>9</td><td>sMiLiNg NaTiOn 8D</td><td align=\"center\">(#8)</td><td align=\"right\">44,895</td></tr>";
        echo "<tr><td>10</td><td>Cars Crash</td><td align=\"center\">(#21)</td><td align=\"right\">41,534</td></tr>";
    }
    elseif($age == "2" && $type == "Strength") {
        echo "<tr><td>1</td><td>Svensk mat rulez!</td><td align=\"center\">(#37)</td><td align=\"right\">50,216,799</td></tr>";
        echo "<tr><td>2</td><td>Cereal Killers</td><td align=\"center\">(#5)</td><td align=\"right\">18,718,474</td></tr>";
        echo "<tr><td>3</td><td>The New Fellows</td><td align=\"center\">(#14)</td><td align=\"right\">17,646,028</td></tr>";
        echo "<tr><td>4</td><td>Night Skies</td><td align=\"center\">(#4)</td><td align=\"right\">17,343,214</td></tr>";
        echo "<tr><td>5</td><td>Chaos in Your Life</td><td align=\"center\">(#81)</td><td align=\"right\">16,650,712</td></tr>";
        echo "<tr><td>6</td><td>Nations Of War</td><td align=\"center\">(#42)</td><td align=\"right\">16,436,445</td></tr>";
        echo "<tr><td>7</td><td>::[Black Moon Chronicles]::</td><td align=\"center\">(#12)</td><td align=\"right\">16,092,387</td></tr>";
        echo "<tr><td>8</td><td>sMiLiNg NaTiOn 8D</td><td align=\"center\">(#8)</td><td align=\"right\">15,763,957</td></tr>";
        echo "<tr><td>9</td><td>UAFB *united against fancy banners*</td><td align=\"center\">(#23)</td><td align=\"right\">15,402,470</td></tr>";
        echo "<tr><td>10</td><td>Cars Crash</td><td align=\"center\">(#21)</td><td align=\"right\">14,260,357</td></tr>";
    }
    elseif($age == "2" && $type == "Fame") {
        echo "<tr><td>1</td><td>Svensk mat rulez!</td><td align=\"center\">(#37)</td><td align=\"right\">346,206</td></tr>";
        echo "<tr><td>2</td><td>Cereal Killers</td><td align=\"center\">(#5)</td><td align=\"right\">314,961</td></tr>";
        echo "<tr><td>3</td><td>Night Skies</td><td align=\"center\">(#4)</td><td align=\"right\">203,462</td></tr>";
        echo "<tr><td>4</td><td>Nations Of War</td><td align=\"center\">(#42)</td><td align=\"right\">203,291</td></tr>";
        echo "<tr><td>5</td><td>THE CARNIVAL</td><td align=\"center\">(#30)</td><td align=\"right\">191,877</td></tr>";
        echo "<tr><td>6</td><td>House of Chaos</td><td align=\"center\">(#40)</td><td align=\"right\">158,217</td></tr>";
        echo "<tr><td>7</td><td>Ancient Mafia</td><td align=\"center\">(#140)</td><td align=\"right\">155,483</td></tr>";
        echo "<tr><td>8</td><td>::[Black Moon Chronicles]::</td><td align=\"center\">(#12)</td><td align=\"right\">155,264</td></tr>";
        echo "<tr><td>9</td><td>Cars Crash</td><td align=\"center\">(#21)</td><td align=\"right\">143,065</td></tr>";
        echo "<tr><td>10</td><td>Kurusetra-1</td><td align=\"center\">(#129)</td><td align=\"right\">136,677</td></tr>";
    }
    
    // AGE 3
    elseif($age == "3" && $type == "Land") {
        echo "<tr><td>1</td><td>Radioactive Puppies</td><td align=\"center\">(#168)</td><td align=\"right\">63,665</td></tr>";
        echo "<tr><td>2</td><td>The Shadow Alliance</td><td align=\"center\">(#52)</td><td align=\"right\">63,216</td></tr>";
        echo "<tr><td>3</td><td>Mr. Men & LittleMisses</td><td align=\"center\">(#3)</td><td align=\"right\">56,209</td></tr>";
        echo "<tr><td>4</td><td>Cereal Killers</td><td align=\"center\">(#36)</td><td align=\"right\">54,903</td></tr>";
        echo "<tr><td>5</td><td>Coed Naked Fun!</td><td align=\"center\">(#118)</td><td align=\"right\">52,665</td></tr>";
        echo "<tr><td>6</td><td>Revenge of the Forts</td><td align=\"center\">(#83)</td><td align=\"right\">51,268</td></tr>";
        echo "<tr><td>7</td><td>•In†erflora•</td><td align=\"center\">(#71)</td><td align=\"right\">50,814</td></tr>";
        echo "<tr><td>8</td><td>Bunnies vs Snails</td><td align=\"center\">(#47)</td><td align=\"right\">49,811</td></tr>";
        echo "<tr><td>9</td><td>Spawn Of The Unholy</td><td align=\"center\">(#300)</td><td align=\"right\">49,734</td></tr>";
        echo "<tr><td>10</td><td>Alliance of Chess</td><td align=\"center\">(#127)</td><td align=\"right\">48,926</td></tr>";
    }
    elseif($age == "3" && $type == "Strength") {
        echo "<tr><td>1</td><td>The Shadow Alliance</td><td align=\"center\">(#52)</td><td align=\"right\">21,428,541</td></tr>";
        echo "<tr><td>2</td><td>Bunnies vs Snails</td><td align=\"center\">(#47)</td><td align=\"right\">20,922,990</td></tr>";
        echo "<tr><td>3</td><td>Cereal Killers</td><td align=\"center\">(#36)</td><td align=\"right\">19,945,488</td></tr>";
        echo "<tr><td>4</td><td>Coed Naked Fun!</td><td align=\"center\">(#118)</td><td align=\"right\">19,923,949</td></tr>";
        echo "<tr><td>5</td><td>Radioactive Puppies</td><td align=\"center\">(#168)</td><td align=\"right\">18,964,441</td></tr>";
        echo "<tr><td>6</td><td>Mr. Men & LittleMisses</td><td align=\"center\">(#3)</td><td align=\"right\">18,646,621</td></tr>";
        echo "<tr><td>7</td><td>•In†erflora•</td><td align=\"center\">(#71)</td><td align=\"right\">18,592,346</td></tr>";
        echo "<tr><td>8</td><td>Alliance of Chess</td><td align=\"center\">(#127)</td><td align=\"right\">18,322,731</td></tr>";
        echo "<tr><td>9</td><td>Spawn Of The Unholy</td><td align=\"center\">(#300)</td><td align=\"right\">17,472,564</td></tr>";
        echo "<tr><td>10</td><td>Ancient Mafia</td><td align=\"center\">(#19)</td><td align=\"right\">15,903,156</td></tr>";
    }
    elseif($age == "3" && $type == "Fame") {
        echo "<tr><td>1</td><td>Random</td><td align=\"center\">(#43)</td><td align=\"right\">351,524</td></tr>";
        echo "<tr><td>2</td><td>Cereal Killers</td><td align=\"center\">(#36)</td><td align=\"right\">265,283</td></tr>";
        echo "<tr><td>3</td><td>THE CARNIVAL</td><td align=\"center\">(#54)</td><td align=\"right\">257,401</td></tr>";
        echo "<tr><td>4</td><td>The Shadow Alliance</td><td align=\"center\">(#52)</td><td align=\"right\">231,078</td></tr>";
        echo "<tr><td>5</td><td>Radioactive Puppies</td><td align=\"center\">(#168)</td><td align=\"right\">219,140</td></tr>";
        echo "<tr><td>6</td><td>Coed Naked Fun!</td><td align=\"center\">(#118)</td><td align=\"right\">207,328</td></tr>";
        echo "<tr><td>7</td><td>Mr. Men & LittleMisses</td><td align=\"center\">(#3)</td><td align=\"right\">184,775</td></tr>";
        echo "<tr><td>8</td><td>Gamerz payback time - Just Bring it!</td><td align=\"center\">(#23)</td><td align=\"right\">169,765</td></tr>";
        echo "<tr><td>9</td><td>Ancient Mafia</td><td align=\"center\">(#19)</td><td align=\"right\">166,697</td></tr>";
        echo "<tr><td>10</td><td>Brotherly Crusaders</td><td align=\"center\">(#159)</td><td align=\"right\">156,480</td></tr>";
    }
    
    // AGE 4
    elseif($age == "4" && $type == "Land") {
        echo "<tr><td>1</td><td>Blood of Heroes</td><td align=\"center\">(#53)</td><td align=\"right\">121,061</td></tr>";
        echo "<tr><td>2</td><td>The IRC Bunnies & Snails</td><td align=\"center\">(#86)</td><td align=\"right\">70,791</td></tr>";
        echo "<tr><td>3</td><td>**** THE KINKY KLINIC****</td><td align=\"center\">(#59)</td><td align=\"right\">55,407</td></tr>";
        echo "<tr><td>4</td><td>doGgIes HuNgRy foR ReVenGe</td><td align=\"center\">(#83)</td><td align=\"right\">50,950</td></tr>";
        echo "<tr><td>5</td><td>Comic Relief</td><td align=\"center\">(#159)</td><td align=\"right\">49,744</td></tr>";
        echo "<tr><td>6</td><td>Killer Krayons ^_^</td><td align=\"center\">(#18)</td><td align=\"right\">48,975</td></tr>";
        echo "<tr><td>7</td><td>Shinning Realm</td><td align=\"center\">(#84)</td><td align=\"right\">45,513</td></tr>";
        echo "<tr><td>8</td><td>Fireworks</td><td align=\"center\">(#104)</td><td align=\"right\">44,358</td></tr>";
        echo "<tr><td>9</td><td>Jump and Let`s Party!</td><td align=\"center\">(#202)</td><td align=\"right\">41,988</td></tr>";
        echo "<tr><td>10</td><td>Foretold Uprising</td><td align=\"center\">(#2)</td><td align=\"right\">41,282</td></tr>";
    }
    elseif($age == "4" && $type == "Strength") {
        echo "<tr><td>1</td><td>Blood of Heroes</td><td align=\"center\">(#53)</td><td align=\"right\">40,859,141</td></tr>";
        echo "<tr><td>2</td><td>The IRC Bunnies & Snails</td><td align=\"center\">(#86)</td><td align=\"right\">26,749,585</td></tr>";
        echo "<tr><td>3</td><td>**** THE KINKY KLINIC****</td><td align=\"center\">(#59)</td><td align=\"right\">21,138,239</td></tr>";
        echo "<tr><td>4</td><td>doGgIes HuNgRy foR ReVenGe</td><td align=\"center\">(#83)</td><td align=\"right\">20,381,076</td></tr>";
        echo "<tr><td>5</td><td>plz attack.. to retal would cheer us up</td><td align=\"center\">(#68)</td><td align=\"right\">20,034,725</td></tr>";
        echo "<tr><td>6</td><td>Screen Shots</td><td align=\"center\">(#32)</td><td align=\"right\">19,002,955</td></tr>";
        echo "<tr><td>7</td><td>Shinning Realm</td><td align=\"center\">(#84)</td><td align=\"right\">18,571,492</td></tr>";
        echo "<tr><td>8</td><td>Fireworks</td><td align=\"center\">(#104)</td><td align=\"right\">18,383,849</td></tr>";
        echo "<tr><td>9</td><td>Eternal Fame</td><td align=\"center\">(#214)</td><td align=\"right\">16,637,270</td></tr>";
        echo "<tr><td>10</td><td>Comic Relief</td><td align=\"center\">(#159)</td><td align=\"right\">16,566,843</td></tr>"; 
    }
    elseif($age == "4" && $type == "Fame") {
        echo "<tr><td>1</td><td>Eternal Fame</td><td align=\"center\">(#214)</td><td align=\"right\">487,985</td></tr>";
        echo "<tr><td>2</td><td>THE CARNIVAL</td><td align=\"center\">(#92)</td><td align=\"right\">312,582</td></tr>";
        echo "<tr><td>3</td><td>Tribes Of Darkness</td><td align=\"center\">(#66)</td><td align=\"right\">240,952</td></tr>";
        echo "<tr><td>4</td><td>Blood of Heroes</td><td align=\"center\">(#53)</td><td align=\"right\">230,977</td></tr>";
        echo "<tr><td>5</td><td>Killer Krayons ^_^</td><td align=\"center\">(#18)</td><td align=\"right\">213,167</td></tr>";
        echo "<tr><td>6</td><td>doGgIes HuNgRy foR ReVenGe</td><td align=\"center\">(#83)</td><td align=\"right\">206,275</td></tr>";
        echo "<tr><td>7</td><td>The IRC Bunnies & Snails</td><td align=\"center\">(#86)</td><td align=\"right\">206,098</td></tr>";
        echo "<tr><td>8</td><td>**** THE KINKY KLINIC****</td><td align=\"center\">(#59)</td><td align=\"right\">189,423</td></tr>";
        echo "<tr><td>9</td><td>Screen Shots</td><td align=\"center\">(#32)</td><td align=\"right\">188,354</td></tr>";
        echo "<tr><td>10</td><td>plz attack.. to retal would cheer us up</td><td align=\"center\">(#68)</td><td align=\"right\">166,880</td></tr>"; 
    }
    
    // AGE 5
    elseif($age == "5" && $type == "Land") {
        echo "<tr><td>1</td><td>***The New Crew***</td><td align=\"center\">(#31)</td><td align=\"right\">91,356</td></tr>";
        echo "<tr><td>2</td><td>Twisted Movies</td><td align=\"center\">(#103)</td><td align=\"right\">59,897</td></tr>";
        echo "<tr><td>3</td><td>~AA~</td><td align=\"center\">(#116)</td><td align=\"right\">52,903</td></tr>";
        echo "<tr><td>4</td><td>stay tuned for next episode</td><td align=\"center\">(#131)</td><td align=\"right\">48,363</td></tr>";
        echo "<tr><td>5</td><td>Cause of Death: Bunnies & Snails</td><td align=\"center\">(#398)</td><td align=\"right\">47,852</td></tr>";
        echo "<tr><td>6</td><td>BROWN on Vacation</td><td align=\"center\">(#82)</td><td align=\"right\">45,448</td></tr>";
        echo "<tr><td>7</td><td>Dark Order</td><td align=\"center\">(#263)</td><td align=\"right\">44,746</td></tr>";
        echo "<tr><td>8</td><td>killin time</td><td align=\"center\">(#393)</td><td align=\"right\">43,042</td></tr>";
        echo "<tr><td>9</td><td>The Cause of Death</td><td align=\"center\">(#345)</td><td align=\"right\">40,789</td></tr>";
        echo "<tr><td>10</td><td>Weapons Of Mass Destruction</td><td align=\"center\">(#50)</td><td align=\"right\">39,858</td></tr>";   
    }
    elseif($age == "5" && $type == "Strength") {
        echo "<tr><td>1</td><td>***The New Crew***</td><td align=\"center\">(#31)</td><td align=\"right\">22,861,862</td></tr>";
        echo "<tr><td>2</td><td>~AA~</td><td align=\"center\">(#116)</td><td align=\"right\">18,361,876</td></tr>";
        echo "<tr><td>3</td><td>Twisted Movies</td><td align=\"center\">(#103)</td><td align=\"right\">15,795,967</td></tr>";
        echo "<tr><td>4</td><td>stay tuned for next episode</td><td align=\"center\">(#131)</td><td align=\"right\">14,317,574</td></tr>";
        echo "<tr><td>5</td><td>Cause of Death: Bunnies & Snails</td><td align=\"center\">(#398)</td><td align=\"right\">13,093,872</td></tr>";
        echo "<tr><td>6</td><td>BROWN on Vacation</td><td align=\"center\">(#82)</td><td align=\"right\">12,283,795</td></tr>";
        echo "<tr><td>7</td><td>Dark Order</td><td align=\"center\">(#263)</td><td align=\"right\">11,860,982</td></tr>";
        echo "<tr><td>8</td><td>~Tribes of Darkness Reloaded~</td><td align=\"center\">(#16)</td><td align=\"right\">11,845,977</td></tr>";
        echo "<tr><td>9</td><td>killin time</td><td align=\"center\">(#393)</td><td align=\"right\">11,565,902</td></tr>";
        echo "<tr><td>10</td><td>Weapons Of Mass Destruction</td><td align=\"center\">(#50)</td><td align=\"right\">11,436,464</td></tr>";
    }
    elseif($age == "5" && $type == "Fame") {
        echo "<tr><td>1</td><td>***The New Crew***</td><td align=\"center\">(#31)</td><td align=\"right\">312,563</td></tr>";
        echo "<tr><td>2</td><td>~AA~</td><td align=\"center\">(#116)</td><td align=\"right\">229,965</td></tr>";
        echo "<tr><td>3</td><td>Hiduplah Indonesia Raya</td><td align=\"center\">(#154)</td><td align=\"right\">168,437</td></tr>";
        echo "<tr><td>4</td><td>Cause of Death: Bunnies & Snails</td><td align=\"center\">(#398)</td><td align=\"right\">156,178</td></tr>";
        echo "<tr><td>5</td><td>Twisted Movies</td><td align=\"center\">(#103)</td><td align=\"right\">142,552</td></tr>";
        echo "<tr><td>6</td><td>stay tuned for next episode</td><td align=\"center\">(#131)</td><td align=\"right\">140,615</td></tr>";
        echo "<tr><td>7</td><td>~Tribes of Darkness Reloaded~</td><td align=\"center\">(#16)</td><td align=\"right\">133,078</td></tr>";
        echo "<tr><td>8</td><td>?volution evoke</td><td align=\"center\">(#95)</td><td align=\"right\">122,097</td></tr>";
        echo "<tr><td>9</td><td>BROWN on Vacation</td><td align=\"center\">(#82)</td><td align=\"right\">121,824</td></tr>";
        echo "<tr><td>10</td><td>Insurrection: Cruel Revival</td><td align=\"center\">(#349)</td><td align=\"right\">117,253</td></tr>"; 
    }
    
    // AGE 6
    elseif($age == "6" && $type == "Land") {
        echo "<tr><td>1</td><td>SAVEN THE RAVEN - The New Crew</td><td align=\"center\">(#176)</td><td align=\"right\">85,689</td></tr>";
        echo "<tr><td>2</td><td>~Tribes of Drunkness~</td><td align=\"center\">(#6)</td><td align=\"right\">72,746</td></tr>";
        echo "<tr><td>3</td><td>Buttersweet and tender</td><td align=\"center\">(#124)</td><td align=\"right\">68,498</td></tr>";
        echo "<tr><td>4</td><td>•In†erflora• Last round, still strong</td><td align=\"center\">(#5)</td><td align=\"right\">67,919</td></tr>";
        echo "<tr><td>5</td><td>tLegion-Lights of Zion *Saven the Raven*</td><td align=\"center\">(#44)</td><td align=\"right\">54,739</td></tr>";
        echo "<tr><td>6</td><td>Bloodthirsty Pacifists</td><td align=\"center\">(#16)</td><td align=\"right\">51,442</td></tr>";
        echo "<tr><td>7</td><td>Bane of Existence</td><td align=\"center\">(#116)</td><td align=\"right\">50,226</td></tr>";
        echo "<tr><td>8</td><td>SAVEN THE RAVEN -BvS-</td><td align=\"center\">(#216)</td><td align=\"right\">50,143</td></tr>";
        echo "<tr><td>9</td><td>GONDOLIN</td><td align=\"center\">(#137)</td><td align=\"right\">47,679</td></tr>";
        echo "<tr><td>10</td><td>sdrawkcaB</td><td align=\"center\">(#52)</td><td align=\"right\">45,088</td></tr>";
    }
    elseif($age == "6" && $type == "Strength") {
        echo "<tr><td>1</td><td>SAVEN THE RAVEN - The New Crew</td><td align=\"center\">(#176)</td><td align=\"right\">31,748,732</td></tr>";
        echo "<tr><td>2</td><td>~Tribes of Drunkness~</td><td align=\"center\">(#6)</td><td align=\"right\">24,690,636</td></tr>";
        echo "<tr><td>3</td><td>Buttersweet and tender</td><td align=\"center\">(#124)</td><td align=\"right\">24,534,242</td></tr>";
        echo "<tr><td>4</td><td>•In†erflora• Last round, still strong</td><td align=\"center\">(#5)</td><td align=\"right\">22,715,144</td></tr>";
        echo "<tr><td>5</td><td>tLegion-Lights of Zion *Saven the Raven*</td><td align=\"center\">(#44)</td><td align=\"right\">19,246,171</td></tr>";
        echo "<tr><td>6</td><td>SAVEN THE RAVEN -BvS-</td><td align=\"center\">(#216)</td><td align=\"right\">19,145,122</td></tr>";
        echo "<tr><td>7</td><td>Bane of Existence</td><td align=\"center\">(#116)</td><td align=\"right\">18,492,418</td></tr>";
        echo "<tr><td>8</td><td>Bloodthirsty Pacifists</td><td align=\"center\">(#16)</td><td align=\"right\">17,779,886</td></tr>";
        echo "<tr><td>9</td><td>Saven the Raven-Orkfian Alphabet</td><td align=\"center\">(#126)</td><td align=\"right\">17,257,682</td></tr>";
        echo "<tr><td>10</td><td>The AnTir Sinisters</td><td align=\"center\">(#167)</td><td align=\"right\">16,776,100</td></tr>";
    }
    elseif($age == "6" && $type == "Fame") {
        echo "<tr><td>1</td><td>~Tribes of Drunkness~</td><td align=\"center\">(#6)</td><td align=\"right\">227,716</td></tr>";
        echo "<tr><td>2</td><td>Bane of Existence</td><td align=\"center\">(#116)</td><td align=\"right\">197,671</td></tr>";
        echo "<tr><td>3</td><td>Bloodthirsty Pacifists</td><td align=\"center\">(#16)</td><td align=\"right\">180,560</td></tr>";
        echo "<tr><td>4</td><td>SAVEN THE RAVEN - The New Crew</td><td align=\"center\">(#176)</td><td align=\"right\">177,891</td></tr>";
        echo "<tr><td>5</td><td>Buttersweet and tender</td><td align=\"center\">(#124)</td><td align=\"right\">157,125</td></tr>";
        echo "<tr><td>6</td><td>THE CARNIVAL</td><td align=\"center\">(#30)</td><td align=\"right\">148,358</td></tr>";
        echo "<tr><td>7</td><td>SAVEN THE RAVEN -BvS-</td><td align=\"center\">(#216)</td><td align=\"right\">128,541</td></tr>";
        echo "<tr><td>8</td><td>tLegion-Lights of Zion *Saven the Raven*</td><td align=\"center\">(#44)</td><td align=\"right\">123,993</td></tr>";
        echo "<tr><td>9</td><td>*~*Ancient Cities*~*</td><td align=\"center\">(#50)</td><td align=\"right\">121,848</td></tr>";
        echo "<tr><td>10</td><td>Fires Of Fun</td><td align=\"center\">(#210)</td><td align=\"right\">118,160</td></tr>";
    }
    
    // AGE 7
    elseif($age == "7" && $type == "Land") {
        echo "<tr><td>1</td><td>Force of Nature</td><td align=\"center\">(#396)</td><td align=\"right\">107,753</td></tr>";
        echo "<tr><td>2</td><td>Tribes of Darkness</td><td align=\"center\">(#129)</td><td align=\"right\">91,095</td></tr>";
        echo "<tr><td>3</td><td>Heroes Of Orkfia</td><td align=\"center\">(#372)</td><td align=\"right\">89,192</td></tr>";
        echo "<tr><td>4</td><td>Cola for featherless Ravens</td><td align=\"center\">(#138)</td><td align=\"right\">86,256</td></tr>";
        echo "<tr><td>5</td><td>GONDOLIN</td><td align=\"center\">(#293)</td><td align=\"right\">85,182</td></tr>";
        echo "<tr><td>6</td><td>Core Comes Clean</td><td align=\"center\">(#386)</td><td align=\"right\">79,727</td></tr>";
        echo "<tr><td>7</td><td>-BvS- Fluffle the Bunneh</td><td align=\"center\">(#190)</td><td align=\"right\">76,857</td></tr>";
        echo "<tr><td>8</td><td>In Articulo Mortis</td><td align=\"center\">(#32)</td><td align=\"right\">71,700</td></tr>";
        echo "<tr><td>9</td><td>Dark Minds</td><td align=\"center\">(#45)</td><td align=\"right\">71,383</td></tr>";
        echo "<tr><td>10</td><td>tLegion......</td><td align=\"center\">(#358)</td><td align=\"right\">67,912</td></tr>";        
    }
    elseif($age == "7" && $type == "Strength") {
        echo "<tr><td>1</td><td>Force of Nature</td><td align=\"center\">(#396)</td><td align=\"right\">41,433,156</td></tr>";
        echo "<tr><td>2</td><td>Tribes of Darkness</td><td align=\"center\">(#129)</td><td align=\"right\">40,889,423</td></tr>";
        echo "<tr><td>3</td><td>-BvS- Fluffle the Bunneh</td><td align=\"center\">(#190)</td><td align=\"right\">38,483,142</td></tr>";
        echo "<tr><td>4</td><td>Heroes Of Orkfia</td><td align=\"center\">(#372)</td><td align=\"right\">38,272,835</td></tr>";
        echo "<tr><td>5</td><td>GONDOLIN</td><td align=\"center\">(#293)</td><td align=\"right\">34,331,311</td></tr>";
        echo "<tr><td>6</td><td>Dark Minds</td><td align=\"center\">(#45)</td><td align=\"right\">31,844,976</td></tr>";
        echo "<tr><td>7</td><td>Cola for featherless Ravens</td><td align=\"center\">(#138)</td><td align=\"right\">30,441,795</td></tr>";
        echo "<tr><td>8</td><td>In Articulo Mortis</td><td align=\"center\">(#32)</td><td align=\"right\">29,160,032</td></tr>";
        echo "<tr><td>9</td><td>Core Comes Clean</td><td align=\"center\">(#386)</td><td align=\"right\">28,906,622</td></tr>";
        echo "<tr><td>10</td><td>- +* Fear *+ -</td><td align=\"center\">(#179)</td><td align=\"right\">28,060,419</td></tr>";
    }
    elseif($age == "7" && $type == "Fame") {
        echo "<tr><td>1</td><td>Force of Nature</td><td align=\"center\">(#396)</td><td align=\"right\">313,636</td></tr>";
        echo "<tr><td>2</td><td>Tribes of Darkness</td><td align=\"center\">(#129)</td><td align=\"right\">270,787</td></tr>";
        echo "<tr><td>3</td><td>-BvS- Fluffle the Bunneh</td><td align=\"center\">(#190)</td><td align=\"right\">241,392</td></tr>";
        echo "<tr><td>4</td><td>Heroes Of Orkfia</td><td align=\"center\">(#372)</td><td align=\"right\">240,055</td></tr>";
        echo "<tr><td>5</td><td>In Articulo Mortis</td><td align=\"center\">(#32)</td><td align=\"right\">217,874</td></tr>";
        echo "<tr><td>6</td><td>THE CARNIVAL</td><td align=\"center\">(#30)</td><td align=\"right\">215,999</td></tr>";
        echo "<tr><td>7</td><td>Frozen Ancestors</td><td align=\"center\">(#338)</td><td align=\"right\">200,618</td></tr>";
        echo "<tr><td>8</td><td>Core Comes Clean</td><td align=\"center\">(#386)</td><td align=\"right\">174,813</td></tr>";
        echo "<tr><td>9</td><td>Vogon Poetry Team</td><td align=\"center\">(#203)</td><td align=\"right\">162,507</td></tr>";
        echo "<tr><td>10</td><td>Dark Minds</td><td align=\"center\">(#45)</td><td align=\"right\">158,296</td></tr>";
    }
    
    // AGE 8
    elseif($age == "8" && $type == "Land") {
        echo "<tr><td>1</td><td>*** The New Crew ***</td><td align=\"center\">(#11)</td><td align=\"right\">109,047</td></tr>";
        echo "<tr><td>2</td><td>(#e^(i*pi)) + (#26)=</td><td align=\"center\">(#25)</td><td align=\"right\">88,763</td></tr>";
        echo "<tr><td>3</td><td>Fair Play: The 8th deadly [SIN]</td><td align=\"center\">(#8)</td><td align=\"right\">85,841</td></tr>";
        echo "<tr><td>4</td><td>It was fun, cya in 2 ages again.</td><td align=\"center\">(#4)</td><td align=\"right\">78,191</td></tr>";
        echo "<tr><td>5</td><td>tLegion...</td><td align=\"center\">(#180)</td><td align=\"right\">78,029</td></tr>";
        echo "<tr><td>6</td><td>The Supervillains</td><td align=\"center\">(#122)</td><td align=\"right\">77,523</td></tr>";
        echo "<tr><td>7</td><td>[core] +92 kills</td><td align=\"center\">(#64)</td><td align=\"right\">73,126</td></tr>";
        echo "<tr><td>8</td><td>Pirates Of Orkfia</td><td align=\"center\">(#81)</td><td align=\"right\">72,387</td></tr>";
        echo "<tr><td>9</td><td>[JJE] Justice</td><td align=\"center\">(#48)</td><td align=\"right\">71,778</td></tr>";
        echo "<tr><td>10</td><td>-BvS- Preparing to leave Springfield</td><td align=\"center\">(#5)</td><td align=\"right\">65,819</td></tr>";
    }
    elseif($age == "8" && $type == "Strength") {
        echo "<tr><td>1</td><td>*** The New Crew ***</td><td align=\"center\">(#11)</td><td align=\"right\">45,851,702</td></tr>";
        echo "<tr><td>2</td><td>S€samstrass€ngang 8D</td><td align=\"center\">(#152)</td><td align=\"right\">42,799,387</td></tr>";
        echo "<tr><td>3</td><td>(#e^(i*pi)) + (#26)=</td><td align=\"center\">(#25)</td><td align=\"right\">40,071,048</td></tr>";
        echo "<tr><td>4</td><td>Fair Play: The 8th deadly [SIN]</td><td align=\"center\">(#8)</td><td align=\"right\">36,339,552</td></tr>";
        echo "<tr><td>5</td><td>tLegion...</td><td align=\"center\">(#180)</td><td align=\"right\">35,788,663</td></tr>";
        echo "<tr><td>6</td><td>[JJE] Justice</td><td align=\"center\">(#48)</td><td align=\"right\">35,589,269</td></tr>";
        echo "<tr><td>7</td><td>It was fun, cya in 2 ages again.</td><td align=\"center\">(#4)</td><td align=\"right\">35,400,972</td></tr>";
        echo "<tr><td>8</td><td>Pirates Of Orkfia</td><td align=\"center\">(#81)</td><td align=\"right\">32,089,334</td></tr>";
        echo "<tr><td>9</td><td>The Supervillains</td><td align=\"center\">(#122)</td><td align=\"right\">31,179,291</td></tr>";
        echo "<tr><td>10</td><td>Inter Vivos Alta - Dark Order</td><td align=\"center\">(#59)</td><td align=\"right\">29,366,375</td></tr>"; 
    }
    elseif($age == "8" && $type == "Fame") {
        echo "<tr><td>1</td><td>The Supervillains</td><td align=\"center\">(#122)</td><td align=\"right\">246,575</td></tr>";
        echo "<tr><td>2</td><td>[core] +92 kills</td><td align=\"center\">(#64)</td><td align=\"right\">235,226</td></tr>";
        echo "<tr><td>3</td><td>FUNtouchable gamerz 52 kills</td><td align=\"center\">(#21)</td><td align=\"right\">219,130</td></tr>";
        echo "<tr><td>4</td><td>Fair Play: The 8th deadly [SIN]</td><td align=\"center\">(#8)</td><td align=\"right\">210,399</td></tr>";
        echo "<tr><td>5</td><td>[JJE] Justice</td><td align=\"center\">(#48)</td><td align=\"right\">203,770</td></tr>";
        echo "<tr><td>6</td><td>THE CARNIVAL</td><td align=\"center\">(#109)</td><td align=\"right\">203,375</td></tr>";
        echo "<tr><td>7</td><td>*** The New Crew ***</td><td align=\"center\">(#11)</td><td align=\"right\">199,723</td></tr>";
        echo "<tr><td>8</td><td>It was fun, cya in 2 ages again.</td><td align=\"center\">(#4)</td><td align=\"right\">177,420</td></tr>";
        echo "<tr><td>9</td><td>Inter Vivos Alta - Dark Order</td><td align=\"center\">(#59)</td><td align=\"right\">170,414</td></tr>";
        echo "<tr><td>10</td><td>tLegion...</td><td align=\"center\">(#180)</td><td align=\"right\">169,974</td></tr>";
    }
    
    // AGE 9
    elseif($age == "9" && $type == "Land") {
        echo "<tr><td>1</td><td>*** The New Crew ***</td><td align=\"center\">(#233)</td><td align=\"right\">130,456</td></tr>";
        echo "<tr><td>2</td><td>We Apologize for the Inconvenience</td><td align=\"center\">(#156)</td><td align=\"right\">81,492</td></tr>";
        echo "<tr><td>3</td><td>S.P. - Recruiting Msg Mips or PET</td><td align=\"center\">(#386)</td><td align=\"right\">80,732</td></tr>";
        echo "<tr><td>4</td><td>ICCC</td><td align=\"center\">(#292)</td><td align=\"right\">79,581</td></tr>";
        echo "<tr><td>5</td><td>The Bunnies and Snails</td><td align=\"center\">(#125)</td><td align=\"right\">77,618</td></tr>";
        echo "<tr><td>6</td><td>The Dark Minds Alliance</td><td align=\"center\">(#482)</td><td align=\"right\">76,705</td></tr>";
        echo "<tr><td>7</td><td>Old Crew :p</td><td align=\"center\">(#186)</td><td align=\"right\">76,353</td></tr>";
        echo "<tr><td>8</td><td>Nags on alcohol</td><td align=\"center\">(#211)</td><td align=\"right\">70,243</td></tr>";
        echo "<tr><td>9</td><td>Food Chain</td><td align=\"center\">(#127)</td><td align=\"right\">69,731</td></tr>";
        echo "<tr><td>10</td><td>MYSTIC</td><td align=\"center\">(#189)</td><td align=\"right\">64,093</td></tr>";
    }
    elseif($age == "9" && $type == "Strength") {
        echo "<tr><td>1</td><td>*** The New Crew ***</td><td align=\"center\">(#233)</td><td align=\"right\">49,310,148</td></tr>";
        echo "<tr><td>2</td><td>ICCC</td><td align=\"center\">(#292)</td><td align=\"right\">33,794,369</td></tr>";
        echo "<tr><td>3</td><td>The Dark Minds Alliance</td><td align=\"center\">(#482)</td><td align=\"right\">33,547,439</td></tr>";
        echo "<tr><td>4</td><td>We Apologize for the Inconvenience</td><td align=\"center\">(#156)</td><td align=\"right\">33,112,955</td></tr>";
        echo "<tr><td>5</td><td>S.P. - Recruiting Msg Mips or PET</td><td align=\"center\">(#386)</td><td align=\"right\">30,196,034</td></tr>";
        echo "<tr><td>6</td><td>Food Chain</td><td align=\"center\">(#127)</td><td align=\"right\">30,134,555</td></tr>";
        echo "<tr><td>7</td><td>The Bunnies and Snails</td><td align=\"center\">(#125)</td><td align=\"right\">27,862,134</td></tr>";
        echo "<tr><td>8</td><td>Nags on alcohol</td><td align=\"center\">(#211)</td><td align=\"right\">27,854,283</td></tr>";
        echo "<tr><td>9</td><td>Old Crew :p</td><td align=\"center\">(#186)</td><td align=\"right\">26,787,373</td></tr>";
        echo "<tr><td>10</td><td>-=[Gone in 5 Seconds]=-</td><td align=\"center\">(#157)</td><td align=\"right\">26,651,093</td></tr>"; 
    }
    elseif($age == "9" && $type == "Fame") {
        echo "<tr><td>1</td><td>*** The New Crew ***</td><td align=\"center\">(#233)</td><td align=\"right\">250,636</td></tr>";
        echo "<tr><td>2</td><td>The Dark Minds Alliance</td><td align=\"center\">(#482)</td><td align=\"right\">178,680</td></tr>";
        echo "<tr><td>3</td><td>¥.Endovelicus.¥ - We SIN a lot</td><td align=\"center\">(#59)</td><td align=\"right\">176,674</td></tr>";
        echo "<tr><td>4</td><td>[JJE] Imminent Disaster</td><td align=\"center\">(#60)</td><td align=\"right\">172,653</td></tr>";
        echo "<tr><td>5</td><td>We Apologize for the Inconvenience</td><td align=\"center\">(#156)</td><td align=\"right\">169,224</td></tr>";
        echo "<tr><td>6</td><td>-=[Gone in 5 Seconds]=-</td><td align=\"center\">(#157)</td><td align=\"right\">141,134</td></tr>";
        echo "<tr><td>7</td><td>Dark Order</td><td align=\"center\">(#227)</td><td align=\"right\">137,735</td></tr>";
        echo "<tr><td>8</td><td>ICCC</td><td align=\"center\">(#292)</td><td align=\"right\">135,545</td></tr>";
        echo "<tr><td>9</td><td>[NWG] FIVE</td><td align=\"center\">(#413)</td><td align=\"right\">126,100</td></tr>";
        echo "<tr><td>10</td><td>Disappearing into the myths of time</td><td align=\"center\">(#5)</td><td align=\"right\">125,064</td></tr>";
    }
    
    // AGE 10
    elseif($age == "10" && $type == "Land") {
        echo "<tr><td>1</td><td>Death By: (recruiting 2 or 3)</td><td align=\"center\">(#28)</td><td align=\"right\">96,962</td></tr>";
        echo "<tr><td>2</td><td>In[core]porated: 19% evil, 81% good</td><td align=\"center\">(#40)</td><td align=\"right\">96,881</td></tr>";
        echo "<tr><td>3</td><td>[Tomb] - The Age of The Crypt</td><td align=\"center\">(#9)</td><td align=\"right\">96,800</td></tr>";
        echo "<tr><td>4</td><td>[Bamse]s Vänner</td><td align=\"center\">(#3)</td><td align=\"right\">96,693</td></tr>";
        echo "<tr><td>5</td><td>[ICCC]</td><td align=\"center\">(#106)</td><td align=\"right\">96,477</td></tr>";
        echo "<tr><td>6</td><td>Love and Laughter</td><td align=\"center\">(#44)</td><td align=\"right\">95,398</td></tr>";
        echo "<tr><td>7</td><td>Mostly Harmless Infections</td><td align=\"center\">(#72)</td><td align=\"right\">95,193</td></tr>";
        echo "<tr><td>8</td><td>MYSTIC</td><td align=\"center\">(#98)</td><td align=\"right\">85,230</td></tr>";
        echo "<tr><td>9</td><td>The Orkfians Time Forgot</td><td align=\"center\">(#14)</td><td align=\"right\">84,117</td></tr>";
        echo "<tr><td>10</td><td>-=†TCK†=- The Chosen Knights</td><td align=\"center\">(#84)</td><td align=\"right\">75,964</td></tr>";          
    }
    elseif($age == "10" && $type == "Strength") {
        echo "<tr><td>1</td><td>[ICCC]</td><td align=\"center\">(#106)</td><td align=\"right\">43,236,916</td></tr>";
        echo "<tr><td>2</td><td>In[core]porated: 19% evil, 81% good</td><td align=\"center\">(#40)</td><td align=\"right\">36,431,251</td></tr>";
        echo "<tr><td>3</td><td>The Orkfians Time Forgot</td><td align=\"center\">(#14)</td><td align=\"right\">35,845,408</td></tr>";
        echo "<tr><td>4</td><td>[Bamse]s Vänner</td><td align=\"center\">(#3)</td><td align=\"right\">34,413,148</td></tr>";
        echo "<tr><td>5</td><td>[Tomb] - The Age of The Crypt</td><td align=\"center\">(#9)</td><td align=\"right\">33,960,415</td></tr>";
        echo "<tr><td>6</td><td>Mostly Harmless Infections</td><td align=\"center\">(#72)</td><td align=\"right\">33,815,715</td></tr>";
        echo "<tr><td>7</td><td>Love and Laughter</td><td align=\"center\">(#44)</td><td align=\"right\">33,136,449</td></tr>";
        echo "<tr><td>8</td><td>MYSTIC</td><td align=\"center\">(#98)</td><td align=\"right\">29,698,548</td></tr>";
        echo "<tr><td>9</td><td>Death By: (recruiting 2 or 3)</td><td align=\"center\">(#28)</td><td align=\"right\">29,594,001</td></tr>";
        echo "<tr><td>10</td><td>-=†TCK†=- The Chosen Knights</td><td align=\"center\">(#84)</td><td align=\"right\">29,022,698</td></tr>";
    }
    elseif($age == "10" && $type == "Fame") {
        echo "<tr><td>1</td><td>¥.Endovelicus.¥</td><td align=\"center\">(#49)</td><td align=\"right\">197,610</td></tr>";
        echo "<tr><td>2</td><td>Mostly Harmless Infections</td><td align=\"center\">(#72)</td><td align=\"right\">188,992</td></tr>";
        echo "<tr><td>3</td><td>Love and Laughter</td><td align=\"center\">(#44)</td><td align=\"right\">187,314</td></tr>";
        echo "<tr><td>4</td><td>[Bamse]s Vänner</td><td align=\"center\">(#3)</td><td align=\"right\">181,697</td></tr>";
        echo "<tr><td>5</td><td>In[core]porated: 19% evil, 81% good</td><td align=\"center\">(#40)</td><td align=\"right\">179,742</td></tr>";
        echo "<tr><td>6</td><td>[JJE] And Then You Die</td><td align=\"center\">(#22)</td><td align=\"right\">177,743</td></tr>";
        echo "<tr><td>7</td><td>[Tomb] - The Age of The Crypt</td><td align=\"center\">(#9)</td><td align=\"right\">170,417</td></tr>";
        echo "<tr><td>8</td><td>-=†TCK†=- The Chosen Knights</td><td align=\"center\">(#84)</td><td align=\"right\">143,769</td></tr>";
        echo "<tr><td>9</td><td>[ICCC]</td><td align=\"center\">(#106)</td><td align=\"right\">133,897</td></tr>";
        echo "<tr><td>10</td><td>Gundam 3rd Age</td><td align=\"center\">(#118)</td><td align=\"right\">132,318</td></tr>"; 
    }
    
    // AGE 11
    elseif($age == "11" && $type == "Land") {
        echo "<tr><td>1</td><td>MH: We apologize for the inconvenience</td><td align=\"center\">(#349)</td><td align=\"right\">164,567</td></tr>";
        echo "<tr><td>2</td><td>Orkfians Time Forgot</td><td align=\"center\">(#355)</td><td align=\"right\">131,550</td></tr>";
        echo "<tr><td>3</td><td>[Bamse] i Trollskogen</td><td align=\"center\">(#3)</td><td align=\"right\">123,681</td></tr>";
        echo "<tr><td>4</td><td>Lusitânia</td><td align=\"center\">(#369)</td><td align=\"right\">104,084</td></tr>";
        echo "<tr><td>5</td><td>COUNTDOWN</td><td align=\"center\">(#98)</td><td align=\"right\">96,057</td></tr>";
        echo "<tr><td>6</td><td>[core] gone wild! part 8</td><td align=\"center\">(#320)</td><td align=\"right\">81,426</td></tr>";
        echo "<tr><td>7</td><td>It is [UP2-HOLLAND!!]</td><td align=\"center\">(#274)</td><td align=\"right\">72,278</td></tr>";
        echo "<tr><td>8</td><td>FUN congrats The Dutch football team!!</td><td align=\"center\">(#295)</td><td align=\"right\">62,818</td></tr>";
        echo "<tr><td>9</td><td>Kings & Queens... and some Jokers</td><td align=\"center\">(#379)</td><td align=\"right\">62,392</td></tr>";
        echo "<tr><td>10</td><td>Bunch [O] dead [G]uys</td><td align=\"center\">(#91)</td><td align=\"right\">59,895</td></tr>";
    }
    elseif($age == "11" && $type == "Strength") {
        echo "<tr><td>1</td><td>MH: We apologize for the inconvenience</td><td align=\"center\">(#349)</td><td align=\"right\">66,370,969</td></tr>";
        echo "<tr><td>2</td><td>Orkfians Time Forgot</td><td align=\"center\">(#355)</td><td align=\"right\">51,758,268</td></tr>";
        echo "<tr><td>3</td><td>[Bamse] i Trollskogen</td><td align=\"center\">(#3)</td><td align=\"right\">42,113,204</td></tr>";
        echo "<tr><td>4</td><td>Lusitânia</td><td align=\"center\">(#369)</td><td align=\"right\">37,570,176</td></tr>";
        echo "<tr><td>5</td><td>COUNTDOWN</td><td align=\"center\">(#98)</td><td align=\"right\">35,185,777</td></tr>";
        echo "<tr><td>6</td><td>It is [UP2-HOLLAND!!]</td><td align=\"center\">(#274)</td><td align=\"right\">30,212,817</td></tr>";
        echo "<tr><td>7</td><td>[core] gone wild! part 8</td><td align=\"center\">(#320)</td><td align=\"right\">28,898,903</td></tr>";
        echo "<tr><td>8</td><td>FUN congrats The Dutch football team!!</td><td align=\"center\">(#295)</td><td align=\"right\">26,919,325</td></tr>";
        echo "<tr><td>9</td><td>Bunch [O] dead [G]uys</td><td align=\"center\">(#91)</td><td align=\"right\">25,035,060</td></tr>";
        echo "<tr><td>10</td><td>Kings & Queens... and some Jokers</td><td align=\"center\">(#379)</td><td align=\"right\">23,571,299</td></tr>"; 
    }
    elseif($age == "11" && $type == "Fame") {
        echo "<tr><td>1</td><td>MH: We apologize for the inconvenience</td><td align=\"center\">(#349)</td><td align=\"right\">215,977</td></tr>";
        echo "<tr><td>2</td><td>[Bamse] i Trollskogen</td><td align=\"center\">(#3)</td><td align=\"right\">170,877</td></tr>";
        echo "<tr><td>3</td><td>[core] gone wild! part 8</td><td align=\"center\">(#320)</td><td align=\"right\">160,769</td></tr>";
        echo "<tr><td>4</td><td>Lusitânia</td><td align=\"center\">(#369)</td><td align=\"right\">157,098</td></tr>";
        echo "<tr><td>5</td><td>Disney:Just a little animated!</td><td align=\"center\">(#45)</td><td align=\"right\">155,825</td></tr>";
        echo "<tr><td>6</td><td>COUNTDOWN</td><td align=\"center\">(#98)</td><td align=\"right\">154,818</td></tr>";
        echo "<tr><td>7</td><td>Kings & Queens... and some Jokers</td><td align=\"center\">(#379)</td><td align=\"right\">150,206</td></tr>";
        echo "<tr><td>8</td><td>FUN congrats The Dutch football team!!</td><td align=\"center\">(#295)</td><td align=\"right\">136,478</td></tr>";
        echo "<tr><td>9</td><td>[JJE] Bestial Justice</td><td align=\"center\">(#22)</td><td align=\"right\">136,357</td></tr>";
        echo "<tr><td>10</td><td>Orkfians Time Forgot</td><td align=\"center\">(#355)</td><td align=\"right\">134,755</td></tr>";
    }
    
    // AGE 12
    elseif($age == "12" && $type == "Land") {
        echo "<tr><td>1</td><td>Simply The Best</td><td align=\"center\">(#230)</td><td align=\"right\">174,011</td></tr>";
        echo "<tr><td>2</td><td>Final Fantasy</td><td align=\"center\">(#376)</td><td align=\"right\">82,822</td></tr>";
        echo "<tr><td>3</td><td>The Insane Flesh Eating Flowers</td><td align=\"center\">(#45)</td><td align=\"right\">82,545</td></tr>";
        echo "<tr><td>4</td><td>Sme-gl to all allies nxt age</td><td align=\"center\">(#134)</td><td align=\"right\">77,867</td></tr>";
        echo "<tr><td>5</td><td>[BAMSE] on Vacation: Planning Revenge</td><td align=\"center\">(#175)</td><td align=\"right\">76,840</td></tr>";
        echo "<tr><td>6</td><td>[OG] owning COUNTDOWN 8-1</td><td align=\"center\">(#246)</td><td align=\"right\">68,646</td></tr>";
        echo "<tr><td>7</td><td>[ANI]MALS</td><td align=\"center\">(#394)</td><td align=\"right\">61,686</td></tr>";
        echo "<tr><td>8</td><td>Cereal Killers</td><td align=\"center\">(#346)</td><td align=\"right\">59,129</td></tr>";
        echo "<tr><td>9</td><td>ORK FEAR</td><td align=\"center\">(#30)</td><td align=\"right\">51,754</td></tr>";
        echo "<tr><td>10</td><td>Dis(appearing)</td><td align=\"center\">(#143)</td><td align=\"right\">48,727</td></tr>";       
    }
    elseif($age == "12" && $type == "Strength") {
        echo "<tr><td>1</td><td>Simply The Best</td><td align=\"center\">(#230)</td><td align=\"right\">73,965,470</td></tr>";
        echo "<tr><td>2</td><td>Final Fantasy</td><td align=\"center\">(#376)</td><td align=\"right\">34,048,402</td></tr>";
        echo "<tr><td>3</td><td>[BAMSE] on Vacation: Planning Revenge</td><td align=\"center\">(#175)</td><td align=\"right\">32,431,674</td></tr>";
        echo "<tr><td>4</td><td>The Insane Flesh Eating Flowers</td><td align=\"center\">(#45)</td><td align=\"right\">30,718,000</td></tr>";
        echo "<tr><td>5</td><td>Sme-gl to all allies nxt age</td><td align=\"center\">(#134)</td><td align=\"right\">27,495,548</td></tr>";
        echo "<tr><td>6</td><td>[OG] owning COUNTDOWN 8-1</td><td align=\"center\">(#246)</td><td align=\"right\">27,155,471</td></tr>";
        echo "<tr><td>7</td><td>[ANI]MALS</td><td align=\"center\">(#394)</td><td align=\"right\">26,025,493</td></tr>";
        echo "<tr><td>8</td><td>Cereal Killers</td><td align=\"center\">(#346)</td><td align=\"right\">22,754,852</td></tr>";
        echo "<tr><td>9</td><td>ORK FEAR</td><td align=\"center\">(#30)</td><td align=\"right\">22,294,942</td></tr>";
        echo "<tr><td>10</td><td>Dis(appearing)</td><td align=\"center\">(#143)</td><td align=\"right\">20,552,707</td></tr>";
    }
    elseif($age == "12" && $type == "Fame") {
        echo "<tr><td>1</td><td>Simply The Best</td><td align=\"center\">(#230)</td><td align=\"right\">247,305</td></tr>";
        echo "<tr><td>2</td><td>Final Fantasy</td><td align=\"center\">(#376)</td><td align=\"right\">187,339</td></tr>";
        echo "<tr><td>3</td><td>Cereal Killers</td><td align=\"center\">(#346)</td><td align=\"right\">154,209</td></tr>";
        echo "<tr><td>4</td><td>[BAMSE] on Vacation: Planning Revenge</td><td align=\"center\">(#175)</td><td align=\"right\">137,929</td></tr>";
        echo "<tr><td>5</td><td>The Insane Flesh Eating Flowers</td><td align=\"center\">(#45)</td><td align=\"right\">128,282</td></tr>";
        echo "<tr><td>6</td><td>[OG] owning COUNTDOWN 8-1</td><td align=\"center\">(#246)</td><td align=\"right\">122,937</td></tr>";
        echo "<tr><td>7</td><td>Dying With Honor *19 Kills*</td><td align=\"center\">(#372)</td><td align=\"right\">120,678</td></tr>";
        echo "<tr><td>8</td><td>Sme-gl to all allies nxt age</td><td align=\"center\">(#134)</td><td align=\"right\">119,011</td></tr>";
        echo "<tr><td>9</td><td>[ANI]MALS</td><td align=\"center\">(#394)</td><td align=\"right\">114,595</td></tr>";
        echo "<tr><td>10</td><td>Ancient Allainces at war! |FUN|</td><td align=\"center\">(#348)</td><td align=\"right\">112,996</td></tr>"; 
    }
    
    // AGE 13
    elseif($age == "13" && $type == "Land") {
        echo "<tr><td>1</td><td>Mostly Harmless</td><td align=\"center\">(#268)</td><td align=\"right\">150,931</td></tr>";
        echo "<tr><td>2</td><td>[BAMSE] Sdrawkcab Gniworg</td><td align=\"center\">(#6)</td><td align=\"right\">117,109</td></tr>";
        echo "<tr><td>3</td><td>Hungry? We are!</td><td align=\"center\">(#190)</td><td align=\"right\">101,657</td></tr>";
        echo "<tr><td>4</td><td>Care Bears *recruiting for next age*</td><td align=\"center\">(#34)</td><td align=\"right\">88,316</td></tr>";
        echo "<tr><td>5</td><td>[FUN] in Orkfia</td><td align=\"center\">(#373)</td><td align=\"right\">86,024</td></tr>";
        echo "<tr><td>6</td><td>COUNTDOWN</td><td align=\"center\">(#283)</td><td align=\"right\">72,406</td></tr>";
        echo "<tr><td>7</td><td>We [SIN] so you dont have to</td><td align=\"center\">(#56)</td><td align=\"right\">70,687</td></tr>";
        echo "<tr><td>8</td><td>Core:Losing friends is easy in Hollywood</td><td align=\"center\">(#39)</td><td align=\"right\">68,877</td></tr>";
        echo "<tr><td>9</td><td>Who Are W[MM]E?</td><td align=\"center\">(#344)</td><td align=\"right\">68,138</td></tr>";
        echo "<tr><td>10</td><td>Gundam 6th Age:Recruiting</td><td align=\"center\">(#72)</td><td align=\"right\">63,849</td></tr>"; 
    }
    elseif($age == "13" && $type == "Strength") {
        echo "<tr><td>1</td><td>Mostly Harmless</td><td align=\"center\">(#268)</td><td align=\"right\">53,954,615</td></tr>";
        echo "<tr><td>2</td><td>[BAMSE] Sdrawkcab Gniworg</td><td align=\"center\">(#6)</td><td align=\"right\">49,371,012</td></tr>";
        echo "<tr><td>3</td><td>Care Bears *recruiting for next age*</td><td align=\"center\">(#34)</td><td align=\"right\">44,346,635</td></tr>";
        echo "<tr><td>4</td><td>Hungry? We are!</td><td align=\"center\">(#190)</td><td align=\"right\">42,283,876</td></tr>";
        echo "<tr><td>5</td><td>[FUN] in Orkfia</td><td align=\"center\">(#373)</td><td align=\"right\">35,592,607</td></tr>";
        echo "<tr><td>6</td><td>Gundam 6th Age:Recruiting</td><td align=\"center\">(#72)</td><td align=\"right\">25,541,651</td></tr>";
        echo "<tr><td>7</td><td>Core:Losing friends is easy in Hollywood</td><td align=\"center\">(#39)</td><td align=\"right\">24,832,356</td></tr>";
        echo "<tr><td>8</td><td>[OG] going crazy</td><td align=\"center\">(#94)</td><td align=\"right\">23,969,568</td></tr>";
        echo "<tr><td>9</td><td>COUNTDOWN</td><td align=\"center\">(#283)</td><td align=\"right\">23,428,553</td></tr>";
        echo "<tr><td>10</td><td>Who Are W[MM]E?</td><td align=\"center\">(#344)</td><td align=\"right\">23,129,505</td></tr>";      
    }
    elseif($age == "13" && $type == "Fame") {
        echo "<tr><td>1</td><td>Hungry? We are!</td><td align=\"center\">(#190)</td><td align=\"right\">236,615</td></tr>";
        echo "<tr><td>2</td><td>Mostly Harmless</td><td align=\"center\">(#268)</td><td align=\"right\">193,195</td></tr>";
        echo "<tr><td>3</td><td>[BAMSE] Sdrawkcab Gniworg</td><td align=\"center\">(#6)</td><td align=\"right\">179,820</td></tr>";
        echo "<tr><td>4</td><td>[FUN] in Orkfia</td><td align=\"center\">(#373)</td><td align=\"right\">164,245</td></tr>";
        echo "<tr><td>5</td><td>We [SIN] so you dont have to</td><td align=\"center\">(#56)</td><td align=\"right\">154,887</td></tr>";
        echo "<tr><td>6</td><td>One aim for all and all for [WAR]</td><td align=\"center\">(#154)</td><td align=\"right\">135,481</td></tr>";
        echo "<tr><td>7</td><td>Who Are W[MM]E?</td><td align=\"center\">(#344)</td><td align=\"right\">134,814</td></tr>";
        echo "<tr><td>8</td><td>[Mecha]nical Menagerie</td><td align=\"center\">(#14)</td><td align=\"right\">133,735</td></tr>";
        echo "<tr><td>9</td><td>Gondolin</td><td align=\"center\">(#59)</td><td align=\"right\">132,190</td></tr>";
        echo "<tr><td>10</td><td>[OG] going crazy</td><td align=\"center\">(#94)</td><td align=\"right\">120,747</td></tr>"; 
    }
    
    // AGE 14
    elseif($age == "14" && $type == "Land") {
        echo "<tr><td>1</td><td>GENESIS - A New Beginning</td><td align=\"center\">(#70)</td><td align=\"right\">181,935</td></tr>";
        echo "<tr><td>2</td><td>¥.Endovelicus.¥</td><td align=\"center\">(#2)</td><td align=\"right\">114,231</td></tr>";
        echo "<tr><td>3</td><td>Caring Care Bears</td><td align=\"center\">(#15)</td><td align=\"right\">103,744</td></tr>";
        echo "<tr><td>4</td><td>Gundam 7th age</td><td align=\"center\">(#83)</td><td align=\"right\">93,484</td></tr>";
        echo "<tr><td>5</td><td>***OTF***</td><td align=\"center\">(#5)</td><td align=\"right\">88,760</td></tr>";
        echo "<tr><td>6</td><td>The Round Table - Happy Birthday MnM</td><td align=\"center\">(#6)</td><td align=\"right\">84,012</td></tr>";
        echo "<tr><td>7</td><td>K.O.</td><td align=\"center\">(#28)</td><td align=\"right\">82,895</td></tr>";
        echo "<tr><td>8</td><td>[ADMIN]s Inc.</td><td align=\"center\">(#87)</td><td align=\"right\">67,938</td></tr>";
        echo "<tr><td>9</td><td>The Trojan war</td><td align=\"center\">(#49)</td><td align=\"right\">57,402</td></tr>";
        echo "<tr><td>10</td><td>[core]</td><td align=\"center\">(#3)</td><td align=\"right\">54,286</td></tr>";
    }
    elseif($age == "14" && $type == "Strength") {
        echo "<tr><td>1</td><td>GENESIS - A New Beginning</td><td align=\"center\">(#70)</td><td align=\"right\">75,651,452</td></tr>";
        echo "<tr><td>2</td><td>Caring Care Bears</td><td align=\"center\">(#15)</td><td align=\"right\">43,004,143</td></tr>";
        echo "<tr><td>3</td><td>¥.Endovelicus.¥</td><td align=\"center\">(#2)</td><td align=\"right\">41,919,926</td></tr>";
        echo "<tr><td>4</td><td>K.O.</td><td align=\"center\">(#28)</td><td align=\"right\">35,954,081</td></tr>";
        echo "<tr><td>5</td><td>Gundam 7th age</td><td align=\"center\">(#83)</td><td align=\"right\">34,868,361</td></tr>";
        echo "<tr><td>6</td><td>***OTF***</td><td align=\"center\">(#5)</td><td align=\"right\">34,392,925</td></tr>";
        echo "<tr><td>7</td><td>The Round Table - Happy Birthday MnM</td><td align=\"center\">(#6)</td><td align=\"right\">33,756,039</td></tr>";
        echo "<tr><td>8</td><td>The Trojan war</td><td align=\"center\">(#49)</td><td align=\"right\">33,090,954</td></tr>";
        echo "<tr><td>9</td><td>[ADMIN]s Inc.</td><td align=\"center\">(#87)</td><td align=\"right\">29,217,277</td></tr>";
        echo "<tr><td>10</td><td>J00 3nt4h t3h r34lm 0f d4 slack-m4st4hs!</td><td align=\"center\">(#84)</td><td align=\"right\">26,835,396</td></tr>"; 
    }
    elseif($age == "14" && $type == "Fame") {
        echo "<tr><td>1</td><td>GENESIS - A New Beginning</td><td align=\"center\">(#70)</td><td align=\"right\">213,209</td></tr>";
        echo "<tr><td>2</td><td>The Trojan war</td><td align=\"center\">(#49)</td><td align=\"right\">202,931</td></tr>";
        echo "<tr><td>3</td><td>Caring Care Bears</td><td align=\"center\">(#15)</td><td align=\"right\">188,260</td></tr>";
        echo "<tr><td>4</td><td>The Round Table - Happy Birthday MnM</td><td align=\"center\">(#6)</td><td align=\"right\">163,222</td></tr>";
        echo "<tr><td>5</td><td>Memorable Movies</td><td align=\"center\">(#89)</td><td align=\"right\">140,148</td></tr>";
        echo "<tr><td>6</td><td>#150 Die another Day</td><td align=\"center\">(#129)</td><td align=\"right\">136,977</td></tr>";
        echo "<tr><td>7</td><td>¥.Endovelicus.¥</td><td align=\"center\">(#2)</td><td align=\"right\">128,637</td></tr>";
        echo "<tr><td>8</td><td>Ardaea a vell Edhelea</td><td align=\"center\">(#26)</td><td align=\"right\">121,733</td></tr>";
        echo "<tr><td>9</td><td>K.O.</td><td align=\"center\">(#28)</td><td align=\"right\">118,490</td></tr>";
        echo "<tr><td>10</td><td>[core]</td><td align=\"center\">(#3)</td><td align=\"right\">113,951</td></tr>";
    }
    
    // AGE 15
    elseif($age == "15" && $type == "Land") {
        echo "<tr><td>1</td><td>MH: We Apologize For The Inconvenience</td><td align=\"center\">(#42)</td><td align=\"right\">120,043</td></tr>";
        echo "<tr><td>2</td><td>~ 10 Unholy Tribes ~</td><td align=\"center\">(#90)</td><td align=\"right\">85,861</td></tr>";
        echo "<tr><td>3</td><td>GENESIS</td><td align=\"center\">(#48)</td><td align=\"right\">84,967</td></tr>";
        echo "<tr><td>4</td><td>[ B v S ] Padds is a n00b!</td><td align=\"center\">(#11)</td><td align=\"right\">83,447</td></tr>";
        echo "<tr><td>5</td><td>Mostly Demons & Some Angels</td><td align=\"center\">(#21)</td><td align=\"right\">81,827</td></tr>";
        echo "<tr><td>6</td><td>Orkfian Gods of Destruction</td><td align=\"center\">(#67)</td><td align=\"right\">76,078</td></tr>";
        echo "<tr><td>7</td><td>[Phobian]®</td><td align=\"center\">(#99)</td><td align=\"right\">71,821</td></tr>";
        echo "<tr><td>8</td><td>Gondolin</td><td align=\"center\">(#60)</td><td align=\"right\">64,266</td></tr>";
        echo "<tr><td>9</td><td>A more personal [Core]</td><td align=\"center\">(#43)</td><td align=\"right\">62,172</td></tr>";
        echo "<tr><td>10</td><td>Random Insanity *never recruiting*</td><td align=\"center\">(#24)</td><td align=\"right\">61,532</td></tr>";            
    }
    elseif($age == "15" && $type == "Strength") {
        echo "<tr><td>1</td><td>~ 10 Unholy Tribes ~</td><td align=\"center\">(#90)</td><td align=\"right\">42,246,966</td></tr>";
        echo "<tr><td>2</td><td>MH: We Apologize For The Inconvenience</td><td align=\"center\">(#42)</td><td align=\"right\">38,474,125</td></tr>";
        echo "<tr><td>3</td><td>Orkfian Gods of Destruction</td><td align=\"center\">(#67)</td><td align=\"right\">27,050,513</td></tr>";
        echo "<tr><td>4</td><td>[ B v S ] Padds is a n00b!</td><td align=\"center\">(#11)</td><td align=\"right\">23,625,201</td></tr>";
        echo "<tr><td>5</td><td>GENESIS</td><td align=\"center\">(#48)</td><td align=\"right\">23,545,419</td></tr>";
        echo "<tr><td>6</td><td>[Phobian]®</td><td align=\"center\">(#99)</td><td align=\"right\">22,870,524</td></tr>";
        echo "<tr><td>7</td><td>Random Insanity *never recruiting*</td><td align=\"center\">(#24)</td><td align=\"right\">22,227,033</td></tr>";
        echo "<tr><td>8</td><td>Mostly Demons & Some Angels</td><td align=\"center\">(#21)</td><td align=\"right\">21,327,001</td></tr>";
        echo "<tr><td>9</td><td>A more personal [Core]</td><td align=\"center\">(#43)</td><td align=\"right\">17,672,919</td></tr>";
        echo "<tr><td>10</td><td>Watch out: Virus-attack!</td><td align=\"center\">(#47)</td><td align=\"right\">16,914,172</td></tr>"; 
    }
    elseif($age == "15" && $type == "Fame") {
        echo "<tr><td>1</td><td>MH: We Apologize For The Inconvenience</td><td align=\"center\">(#42)</td><td align=\"right\">160,368</td></tr>";
        echo "<tr><td>2</td><td>A more personal [Core]</td><td align=\"center\">(#43)</td><td align=\"right\">150,129</td></tr>";
        echo "<tr><td>3</td><td>Watch out: Virus-attack!</td><td align=\"center\">(#47)</td><td align=\"right\">150,105</td></tr>";
        echo "<tr><td>4</td><td>~ 10 Unholy Tribes ~</td><td align=\"center\">(#90)</td><td align=\"right\">140,788</td></tr>";
        echo "<tr><td>5</td><td>GENESIS</td><td align=\"center\">(#48)</td><td align=\"right\">130,609</td></tr>";
        echo "<tr><td>6</td><td>[ B v S ] Padds is a n00b!</td><td align=\"center\">(#11)</td><td align=\"right\">120,886</td></tr>";
        echo "<tr><td>7</td><td>Freedom Fighters</td><td align=\"center\">(#12)</td><td align=\"right\">117,067</td></tr>";
        echo "<tr><td>8</td><td>C E B U A N O inc.</td><td align=\"center\">(#45)</td><td align=\"right\">113,274</td></tr>";
        echo "<tr><td>9</td><td>Random Insanity *never recruiting*</td><td align=\"center\">(#24)</td><td align=\"right\">112,949</td></tr>";
        echo "<tr><td>10</td><td>[GInc.] -#20 can't write & count-</td><td align=\"center\">(#107)</td><td align=\"right\">112,585</td></tr>"; 
    }
    
    // AGE 16
    elseif($age == "16" && $type == "Land") {
        echo "<tr><td>1</td><td>\o/ MH: 1st anniversary \o/</td><td align=\"center\">(#142)</td><td align=\"right\">203,888</td></tr>";
        echo "<tr><td>2</td><td>**The FUN Alliance**</td><td align=\"center\">(#26)</td><td align=\"right\">102,675</td></tr>";
        echo "<tr><td>3</td><td>GNS, Confused Farm Yard Animal</td><td align=\"center\">(#58)</td><td align=\"right\">82,983</td></tr>";
        echo "<tr><td>4</td><td>? V.T.S ?</td><td align=\"center\">(#33)</td><td align=\"right\">81,855</td></tr>";
        echo "<tr><td>5</td><td>Core & BvS Present: BSU</td><td align=\"center\">(#149)</td><td align=\"right\">80,941</td></tr>";
        echo "<tr><td>6</td><td>[TPA] - The Parody Age</td><td align=\"center\">(#15)</td><td align=\"right\">76,346</td></tr>";
        echo "<tr><td>7</td><td>N W G</td><td align=\"center\">(#115)</td><td align=\"right\">70,564</td></tr>";
        echo "<tr><td>8</td><td>Beach Insandity</td><td align=\"center\">(#264)</td><td align=\"right\">64,050</td></tr>";
        echo "<tr><td>9</td><td>Comic Heroes - Love us</td><td align=\"center\">(#166)</td><td align=\"right\">60,583</td></tr>";
        echo "<tr><td>10</td><td>TribesFromHell-AlwaysAnnoying</td><td align=\"center\">(#185)</td><td align=\"right\">55,405</td></tr>";            
    }
    elseif($age == "16" && $type == "Strength") {
        echo "<tr><td>1</td><td>\o/ MH: 1st anniversary \o/</td><td align=\"center\">(#142)</td><td align=\"right\">56,008,541</td></tr>";
        echo "<tr><td>2</td><td>**The FUN Alliance**</td><td align=\"center\">(#26)</td><td align=\"right\">42,424,566</td></tr>";
        echo "<tr><td>3</td><td>? V.T.S ?</td><td align=\"center\">(#33)</td><td align=\"right\">28,785,041</td></tr>";
        echo "<tr><td>4</td><td>[TPA] - The Parody Age</td><td align=\"center\">(#15)</td><td align=\"right\">28,665,260</td></tr>";
        echo "<tr><td>5</td><td>Comic Heroes - Love us</td><td align=\"center\">(#166)</td><td align=\"right\">28,609,599</td></tr>";
        echo "<tr><td>6</td><td>N W G</td><td align=\"center\">(#115)</td><td align=\"right\">27,477,242</td></tr>";
        echo "<tr><td>7</td><td>GNS, Confused Farm Yard Animal</td><td align=\"center\">(#58)</td><td align=\"right\">26,152,152</td></tr>";
        echo "<tr><td>8</td><td>Core & BvS Present: BSU</td><td align=\"center\">(#149)</td><td align=\"right\">24,276,019</td></tr>";
        echo "<tr><td>9</td><td>TribesFromHell-AlwaysAnnoying</td><td align=\"center\">(#185)</td><td align=\"right\">22,208,917</td></tr>";
        echo "<tr><td>10</td><td>Beach Insandity</td><td align=\"center\">(#264)</td><td align=\"right\">20,080,884</td></tr>";
    }
    elseif($age == "16" && $type == "Fame") {
        echo "<tr><td>1</td><td>**The FUN Alliance**</td><td align=\"center\">(#26)</td><td align=\"right\">311,481</td></tr>";
        echo "<tr><td>2</td><td>? V.T.S ?</td><td align=\"center\">(#33)</td><td align=\"right\">168,265</td></tr>";
        echo "<tr><td>3</td><td>GNS, Confused Farm Yard Animal</td><td align=\"center\">(#58)</td><td align=\"right\">151,125</td></tr>";
        echo "<tr><td>4</td><td>[TPA] - The Parody Age</td><td align=\"center\">(#15)</td><td align=\"right\">137,403</td></tr>";
        echo "<tr><td>5</td><td>TribesFromHell-AlwaysAnnoying</td><td align=\"center\">(#185)</td><td align=\"right\">128,714</td></tr>";
        echo "<tr><td>6</td><td>\o/ MH: 1st anniversary \o/</td><td align=\"center\">(#142)</td><td align=\"right\">125,922</td></tr>";
        echo "<tr><td>7</td><td>Core & BvS Present: BSU</td><td align=\"center\">(#149)</td><td align=\"right\">114,780</td></tr>";
        echo "<tr><td>8</td><td>Origin: Happy new age :)</td><td align=\"center\">(#91)</td><td align=\"right\">114,323</td></tr>";
        echo "<tr><td>9</td><td>Diseased Tribe</td><td align=\"center\">(#12)</td><td align=\"right\">102,302</td></tr>";
        echo "<tr><td>10</td><td>-= DDR at WAR =-</td><td align=\"center\">(#69)</td><td align=\"right\">99,505</td></tr>";
    }
    
    // AGE 17
    elseif($age == "17" && $type == "Land") {
        echo "<tr><td>1</td><td>>> TorskMonkeys >></td><td align=\"center\">(#222)</td><td align=\"right\">93,759</td></tr>";
        echo "<tr><td>2</td><td>52 kills, 18 deaths...</td><td align=\"center\">(#183)</td><td align=\"right\">76,674</td></tr>";
        echo "<tr><td>3</td><td>[FUN] Waves Goodbye To All</td><td align=\"center\">(#168)</td><td align=\"right\">70,376</td></tr>";
        echo "<tr><td>4</td><td>Warriors till the end!</td><td align=\"center\">(#237)</td><td align=\"right\">70,139</td></tr>";
        echo "<tr><td>5</td><td>m4st4hs</td><td align=\"center\">(#42)</td><td align=\"right\">67,647</td></tr>";
        echo "<tr><td>6</td><td>--- N W G --</td><td align=\"center\">(#293)</td><td align=\"right\">63,853</td></tr>";
        echo "<tr><td>7</td><td>[OC] OrCfian Cods, AD=Lame!</td><td align=\"center\">(#72)</td><td align=\"right\">63,288</td></tr>";
        echo "<tr><td>8</td><td>GOT Gets Going</td><td align=\"center\">(#263)</td><td align=\"right\">60,633</td></tr>";
        echo "<tr><td>9</td><td>[TFH] - Tribes From Heaven</td><td align=\"center\">(#156)</td><td align=\"right\">59,577</td></tr>";
        echo "<tr><td>10</td><td>=VTS=</td><td align=\"center\">(#298)</td><td align=\"right\">56,067</td></tr>";            
    }
    elseif($age == "17" && $type == "Strength") {
        echo "<tr><td>1</td><td>>> TorskMonkeys >></td><td align=\"center\">(#222)</td><td align=\"right\">33,055,281</td></tr>";
        echo "<tr><td>2</td><td>m4st4hs</td><td align=\"center\">(#42)</td><td align=\"right\">31,911,730</td></tr>";
        echo "<tr><td>3</td><td>[FUN] Waves Goodbye To All</td><td align=\"center\">(#168)</td><td align=\"right\">26,920,265</td></tr>";
        echo "<tr><td>4</td><td>52 kills, 18 deaths...</td><td align=\"center\">(#183)</td><td align=\"right\">25,222,138</td></tr>";
        echo "<tr><td>5</td><td>[OC] OrCfian Cods, AD=Lame!</td><td align=\"center\">(#72)</td><td align=\"right\">19,915,054</td></tr>";
        echo "<tr><td>6</td><td>The Last Deal</td><td align=\"center\">(#33)</td><td align=\"right\">19,886,464</td></tr>";
        echo "<tr><td>7</td><td>--- N W G --</td><td align=\"center\">(#293)</td><td align=\"right\">19,661,328</td></tr>";
        echo "<tr><td>8</td><td>Warriors till the end!</td><td align=\"center\">(#237)</td><td align=\"right\">19,309,718</td></tr>";
        echo "<tr><td>9</td><td>Hidden Village</td><td align=\"center\">(#145)</td><td align=\"right\">18,045,737</td></tr>";
        echo "<tr><td>10</td><td>[TFH] - Tribes From Heaven</td><td align=\"center\">(#156)</td><td align=\"right\">17,970,258</td></tr>";
    }
    elseif($age == "17" && $type == "Fame") {
        echo "<tr><td>1</td><td>[FUN] Waves Goodbye To All</td><td align=\"center\">(#168)</td><td align=\"right\">269,298</td></tr>";
        echo "<tr><td>2</td><td>>> TorskMonkeys >></td><td align=\"center\">(#222)</td><td align=\"right\">218,577</td></tr>";
        echo "<tr><td>3</td><td>The Last Deal</td><td align=\"center\">(#33)</td><td align=\"right\">211,727</td></tr>";
        echo "<tr><td>4</td><td>Abatia</td><td align=\"center\">(#55)</td><td align=\"right\">168,843</td></tr>";
        echo "<tr><td>5</td><td>Hidden Village</td><td align=\"center\">(#145)</td><td align=\"right\">139,225</td></tr>";
        echo "<tr><td>6</td><td>We will quit Orkfia!!!RO</td><td align=\"center\">(#63)</td><td align=\"right\">128,413</td></tr>";
        echo "<tr><td>7</td><td>52 kills, 18 deaths...</td><td align=\"center\">(#183)</td><td align=\"right\">119,728</td></tr>";
        echo "<tr><td>8</td><td>Warriors till the end!</td><td align=\"center\">(#237)</td><td align=\"right\">112,833</td></tr>";
        echo "<tr><td>9</td><td>||Born||</td><td align=\"center\">(#22)</td><td align=\"right\">109,375</td></tr>";
        echo "<tr><td>10</td><td>--- N W G --</td><td align=\"center\">(#293)</td><td align=\"right\">104,970</td></tr>";
    }
    
    // AGE 18
    elseif($age == "18" && $type == "Land") {
        echo "<tr><td>1</td><td>GNS + MH = MG</td><td align=\"center\">(#339)</td><td align=\"right\">346,090</td></tr>";
        echo "<tr><td>2</td><td>[WAR]Girls!</td><td align=\"center\">(#236)</td><td align=\"right\">99,293</td></tr>";
        echo "<tr><td>3</td><td>[SK] The Final Countdown</td><td align=\"center\">(#173)</td><td align=\"right\">74,960</td></tr>";
        echo "<tr><td>4</td><td>Zânele Grase</td><td align=\"center\">(#55)</td><td align=\"right\">64,150</td></tr>";
        echo "<tr><td>5</td><td>Origin of a good age</td><td align=\"center\">(#93)</td><td align=\"right\">63,891</td></tr>";
        echo "<tr><td>6</td><td>Teh Fishies</td><td align=\"center\">(#216)</td><td align=\"right\">60,196</td></tr>";
        echo "<tr><td>7</td><td>Precision is Genocide</td><td align=\"center\">(#76)</td><td align=\"right\">58,381</td></tr>";
        echo "<tr><td>8</td><td>PoweR RangerS</td><td align=\"center\">(#149)</td><td align=\"right\">54,956</td></tr>";
        echo "<tr><td>9</td><td>Aztec Gods</td><td align=\"center\">(#51)</td><td align=\"right\">53,699</td></tr>";
        echo "<tr><td>10</td><td>--| NWG Recruiting |--</td><td align=\"center\">(#293)</td><td align=\"right\">51,533</td></tr>";           
    }
    elseif($age == "18" && $type == "Strength") {
        echo "<tr><td>1</td><td>GNS + MH = MG</td><td align=\"center\">(#339)</td><td align=\"right\">115,712,298</td></tr>";
        echo "<tr><td>2</td><td>[WAR]Girls!</td><td align=\"center\">(#236)</td><td align=\"right\">37,638,267</td></tr>";
        echo "<tr><td>3</td><td>[SK] The Final Countdown</td><td align=\"center\">(#173)</td><td align=\"right\">27,713,312</td></tr>";
        echo "<tr><td>4</td><td>Zânele Grase</td><td align=\"center\">(#55)</td><td align=\"right\">24,943,398</td></tr>";
        echo "<tr><td>5</td><td>Origin of a good age</td><td align=\"center\">(#93)</td><td align=\"right\">22,322,498</td></tr>";
        echo "<tr><td>6</td><td>Precision is Genocide</td><td align=\"center\">(#76)</td><td align=\"right\">20,978,259</td></tr>";
        echo "<tr><td>7</td><td>Aztec Gods</td><td align=\"center\">(#51)</td><td align=\"right\">18,920,923</td></tr>";
        echo "<tr><td>8</td><td>PoweR RangerS</td><td align=\"center\">(#149)</td><td align=\"right\">18,046,469</td></tr>";
        echo "<tr><td>9</td><td>Illegal Illusions</td><td align=\"center\">(#327)</td><td align=\"right\">17,590,082</td></tr>";
        echo "<tr><td>10</td><td>--| NWG Recruiting |--</td><td align=\"center\">(#293)</td><td align=\"right\">17,519,726 </td></tr>";
    }
    elseif($age == "18" && $type == "Fame") {
        echo "<tr><td>1</td><td>GNS + MH = MG</td><td align=\"center\">(#339)</td><td align=\"right\">264,150</td></tr>";
        echo "<tr><td>2</td><td>Zânele Grase</td><td align=\"center\">(#55)</td><td align=\"right\">176,864</td></tr>";
        echo "<tr><td>3</td><td>Origin of a good age</td><td align=\"center\">(#93)</td><td align=\"right\">170,440</td></tr>";
        echo "<tr><td>4</td><td>[WAR]Girls!</td><td align=\"center\">(#236)</td><td align=\"right\">169,604</td></tr>";
        echo "<tr><td>5</td><td>[SK] The Final Countdown</td><td align=\"center\">(#173)</td><td align=\"right\">131,577</td></tr>";
        echo "<tr><td>6</td><td>Independence</td><td align=\"center\">(#319)</td><td align=\"right\">112,014</td></tr>";
        echo "<tr><td>7</td><td>Slicing Þ~~~ 3 For BaPao</td><td align=\"center\">(#208)</td><td align=\"right\">106,095</td></tr>";
        echo "<tr><td>8</td><td>Disciples of War</td><td align=\"center\">(#16)</td><td align=\"right\">102,601</td></tr>";
        echo "<tr><td>9</td><td>Cybertron</td><td align=\"center\">(#164)</td><td align=\"right\">101,627</td></tr>";
        echo "<tr><td>10</td><td>Precision is Genocide</td><td align=\"center\">(#76)</td><td align=\"right\">100,526</td></tr>";
    }
    
    // AGE 19
    elseif($age == "19" && $type == "Land") {
        echo "<tr><td>1</td><td>Bacteria Gone AWOL</td><td align=\"center\">(#343)</td><td align=\"right\">128,534</td></tr>";
        echo "<tr><td>2</td><td>Aint it [FUN]?</td><td align=\"center\">(#155)</td><td align=\"right\">86,467</td></tr>";
        echo "<tr><td>3</td><td>!slairetaM suodrazaH!</td><td align=\"center\">(#24)</td><td align=\"right\">65,593</td></tr>";
        echo "<tr><td>4</td><td>[SK] Another Age Ends</td><td align=\"center\">(#173)</td><td align=\"right\">56,402</td></tr>";
        echo "<tr><td>5</td><td>Doublegrabbers Stole Torsk:o</td><td align=\"center\">(#158)</td><td align=\"right\">54,526</td></tr>";
        echo "<tr><td>6</td><td>B.R.O.W.N</td><td align=\"center\">(#12)</td><td align=\"right\">53,199</td></tr>";
        echo "<tr><td>7</td><td>Origin of Fast Wars :)</td><td align=\"center\">(#93)</td><td align=\"right\">52,873</td></tr>";
        echo "<tr><td>8</td><td>Dj Aggrovator ft. d4 m4st4hs</td><td align=\"center\">(#100)</td><td align=\"right\">50,301</td></tr>";
        echo "<tr><td>9</td><td>Cybertron</td><td align=\"center\">(#164)</td><td align=\"right\">49,927</td></tr>";
        echo "<tr><td>10</td><td>The Vigilantes</td><td align=\"center\">(#253)</td><td align=\"right\">45,854</td></tr>";           
    }
    elseif($age == "19" && $type == "Strength") {
        echo "<tr><td>1</td><td>Bacteria Gone AWOL</td><td align=\"center\">(#343)</td><td align=\"right\">33,851,056</td></tr>";
        echo "<tr><td>2</td><td>Aint it [FUN]?</td><td align=\"center\">(#155)</td><td align=\"right\">30,148,820</td></tr>";
        echo "<tr><td>3</td><td>!slairetaM suodrazaH!</td><td align=\"center\">(#24)</td><td align=\"right\">19,859,482</td></tr>";
        echo "<tr><td>4</td><td>[SK] Another Age Ends</td><td align=\"center\">(#173)</td><td align=\"right\">18,519,993</td></tr>";
        echo "<tr><td>5</td><td>Cybertron</td><td align=\"center\">(#164)</td><td align=\"right\">17,776,595</td></tr>";
        echo "<tr><td>6</td><td>Killing Tools</td><td align=\"center\">(#247)</td><td align=\"right\">17,499,156</td></tr>";
        echo "<tr><td>7</td><td>Zanele Grase</td><td align=\"center\">(#55)</td><td align=\"right\">16,687,871</td></tr>";
        echo "<tr><td>8</td><td>Origin of Fast Wars :)</td><td align=\"center\">(#93)</td><td align=\"right\">16,230,027</td></tr>";
        echo "<tr><td>9</td><td>B.R.O.W.N</td><td align=\"center\">(#12)</td><td align=\"right\">16,045,976</td></tr>";
        echo "<tr><td>10</td><td>Doublegrabbers Stole Torsk:o</td><td align=\"center\">(#158)</td><td align=\"right\">15,533,416</td></tr>";
    }
    elseif($age == "19" && $type == "Fame") {
        echo "<tr><td>1</td><td>Aint it [FUN]?</td><td align=\"center\">(#168)</td><td align=\"right\">183,757</td></tr>";
        echo "<tr><td>2</td><td>Warsaints Retired</td><td align=\"center\">(#222)</td><td align=\"right\">173,035</td></tr>";
        echo "<tr><td>3</td><td>Bacteria Gone AWOL</td><td align=\"center\">(#33)</td><td align=\"right\">151,281</td></tr>";
        echo "<tr><td>4</td><td>Origin of Fast Wars :)</td><td align=\"center\">(#55)</td><td align=\"right\">117,751</td></tr>";
        echo "<tr><td>5</td><td>Cybertron</td><td align=\"center\">(#145)</td><td align=\"right\">106,110</td></tr>";
        echo "<tr><td>6</td><td>[SK] Another Age Ends</td><td align=\"center\">(#63)</td><td align=\"right\">104,703</td></tr>";
        echo "<tr><td>7</td><td></td><td align=\"center\">(#304)</td><td align=\"right\">100,449</td></tr>";
        echo "<tr><td>8</td><td></td><td align=\"center\">(#185)</td><td align=\"right\">100,128</td></tr>";
        echo "<tr><td>9</td><td></td><td align=\"center\">(#134)</td><td align=\"right\">100,000</td></tr>";
        echo "<tr><td>10</td><td></td><td align=\"center\">(#127)</td><td align=\"right\">100,000</td></tr>";
    }
}       