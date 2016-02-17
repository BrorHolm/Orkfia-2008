<?
function show_ranking($age, $type){

    echo "<tr><td class=\"tableheader\" colspan=\"5\" align=\"center\"> Personal $type Rankings of Age $age </td></tr>";
    echo "<tr><td class=\"tablesubheader\" colspan=\"2\">Tribe Name</td><td class=\"tablesubheader\" align=\"center\">Race</td><td class=\"tablesubheader\" align=\"center\">Alliance</td><td class=\"tablesubheader\" align=\"right\">$type</td></tr>";

    // AGE 1
    if($age == "1" && $type == "Land") {
        echo "<tr heigth=\"20\"><td width=\"22\">1</td><td>Absolut Citron</td><td align=\"center\">Undead</td><td align=\"center\">C2H5OH (#31)</td><td align=\"right\">13412</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">2</td><td>Absolut Kurant</td><td align=\"center\">Undead</td><td align=\"center\">C2H5OH (#31)</td><td align=\"right\">12482</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">3</td><td>Nurladion</td><td align=\"center\">Wood Elf</td><td align=\"center\">The Elven Council (#9)</td><td align=\"right\">9350</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">4</td><td>Yakuza Knight</td><td align=\"center\">Wood Elf</td><td align=\"center\">The Knighthood (#65)</td><td align=\"right\">8500</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">5</td><td>Lhunthangiel</td><td align=\"center\">Wood Elf</td><td align=\"center\">The Elven Council (#9)</td><td align=\"right\">8499</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">6</td><td>Dimberaid</td><td align=\"center\">Wood Elf</td><td align=\"center\">The Elven Council (#9)</td><td align=\"right\">8485</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">7</td><td>Gnomes of Zurich</td><td align=\"center\">Spirit</td><td align=\"center\">One Man Army.. grr :) (#63)</td><td align=\"right\">8199</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">8</td><td>Donthôlion</td><td align=\"center\">Wood Elf</td><td align=\"center\">The Elven Council (#9)</td><td align=\"right\">8100</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">9</td><td>pExIcOnMaN</td><td align=\"center\">Viking</td><td align=\"center\">AnnO Domini (#86)</td><td align=\"right\">7936</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">10</td><td>NowImPissed</td><td align=\"center\">Mori Hai</td><td align=\"center\">Is it Justice or Just us (#36)</td><td align=\"right\">7897</td></tr>";      
    }
    elseif($age == "1" && $type == "Strength") {
        echo "<tr heigth=\"20\"><td width=\"22\">1</td><td>Absolut Citron</td><td align=\"center\">Undead</td><td align=\"center\">C2H5OH (#31)</td><td align=\"right\">5677025</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">2</td><td>Absolut Kurant</td><td align=\"center\">Undead</td><td align=\"center\">C2H5OH (#31)</td><td align=\"right\">4816202</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">3</td><td>Gnomes of Zurich</td><td align=\"center\">Spirit</td><td align=\"center\">One Man Army.. grr :) (#63)</td><td align=\"right\">3664245</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">4</td><td>Nurladion</td><td align=\"center\">Wood Elf</td><td align=\"center\">The Elven Council (#9)</td><td align=\"right\">3380484</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">5</td><td>TheMan</td><td align=\"center\">Viking</td><td align=\"center\">AnnO Domini (#86)</td><td align=\"right\">3124013</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">6</td><td>pExIcOnMaN</td><td align=\"center\">Viking</td><td align=\"center\">AnnO Domini (#86)</td><td align=\"right\">2858757</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">7</td><td>Lhunthangiel</td><td align=\"center\">Wood Elf</td><td align=\"center\">The Elven Council (#9)</td><td align=\"right\">2594644</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">8</td><td>Bright side of life</td><td align=\"center\">Undead</td><td align=\"center\">Bring Out Your Dead! (#3)</td><td align=\"right\">2578967</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">9</td><td>Black Beast of Aaugh</td><td align=\"center\">Eagle</td><td align=\"center\">Bring Out Your Dead! (#3)</td><td align=\"right\">2576093</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">10</td><td>Holy Hand Grenade</td><td align=\"center\">Undead</td><td align=\"center\">Bring Out Your Dead! (#3)</td><td align=\"right\">2564011</td></tr>";
    }
    elseif($age == "1" && $type == "Fame") {
        echo "<tr heigth=\"20\"><td width=\"22\">1</td><td>Dooley´s</td><td align=\"center\">Eagle</td><td align=\"center\">C2H5OH (#31)</td><td align=\"right\">59698</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">2</td><td>Explorer</td><td align=\"center\">Eagle</td><td align=\"center\">C2H5OH (#31)</td><td align=\"right\">53201</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">3</td><td>Fernet-Branca</td><td align=\"center\">Eagle</td><td align=\"center\">C2H5OH (#31)</td><td align=\"right\">52921</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">4</td><td>Citronmelissbrännvin</td><td align=\"center\">Eagle</td><td align=\"center\">C2H5OH (#31)</td><td align=\"right\">51446</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">5</td><td>Absolut Citron</td><td align=\"center\">Undead</td><td align=\"center\">C2H5OH (#31)</td><td align=\"right\">48117</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">6</td><td>Black Beast of Aaugh</td><td align=\"center\">Eagle</td><td align=\"center\">Bring Out Your Dead! (#3)</td><td align=\"right\">44801</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">7</td><td>Bacardi Limón</td><td align=\"center\">Eagle</td><td align=\"center\">C2H5OH (#31)</td><td align=\"right\">44664</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">8</td><td>Absolut Kurant</td><td align=\"center\">Undead</td><td align=\"center\">C2H5OH (#31)</td><td align=\"right\">43827</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">9</td><td>Bloody Peasant</td><td align=\"center\">Eagle</td><td align=\"center\">Bring Out Your Dead! (#3)</td><td align=\"right\">39324</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">10</td><td>GorgeofEternalPeril</td><td align=\"center\">Eagle</td><td align=\"center\">Bring Out Your Dead! (#3)</td><td align=\"right\">32407</td></tr>";
    }

    // AGE 2
    elseif($age == "2" && $type == "Land") {
        echo "<tr heigth=\"20\"><td width=\"22\">1</td><td>DarknezZ</td><td align=\"center\">Eagle</td><td align=\"center\">OWNAGE!!! ...of the Troccaholics (#9)</td><td align=\"right\">10719</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">2</td><td>Makaroner & Ketchup</td><td align=\"center\">Spirit</td><td align=\"center\">Svensk mat rulez! (#37)</td><td align=\"right\">10251</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">3</td><td>Skörper & Tomatpuré</td><td align=\"center\">Spirit</td><td align=\"center\">Svensk mat rulez! (#37)</td><td align=\"right\">9915</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">4</td><td>Vållulle med Lis</td><td align=\"center\">Wood Elf</td><td align=\"center\">Svensk mat rulez! (#37)</td><td align=\"right\">9750</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">5</td><td>Blodpudding m lingon</td><td align=\"center\">Spirit</td><td align=\"center\">Svensk mat rulez! (#37)</td><td align=\"right\">9174</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">6</td><td>Raggarballe</td><td align=\"center\">Viking</td><td align=\"center\">Svensk mat rulez! (#37)</td><td align=\"right\">8483</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">7</td><td>sMiLiNg R€b€l 8D</td><td align=\"center\">Eagle</td><td align=\"center\">sMiLiNg NaTiOn 8D (#8)</td><td align=\"right\">8390</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">8</td><td>Surströmming</td><td align=\"center\">Undead</td><td align=\"center\">Svensk mat rulez! (#37)</td><td align=\"right\">8306</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">9</td><td>Kroppkakor</td><td align=\"center\">Spirit</td><td align=\"center\">Svensk mat rulez! (#37)</td><td align=\"right\">7740</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">10</td><td>Knäckebröd</td><td align=\"center\">Spirit</td><td align=\"center\">Svensk mat rulez! (#37)</td><td align=\"right\">7337</td></tr>";
    }
    elseif($age == "2" && $type == "Strength") {
        echo "<tr heigth=\"20\"><td width=\"22\">1</td><td>Makaroner & Ketchup</td><td align=\"center\">Spirit</td><td align=\"center\">Svensk mat rulez! (#37)</td><td align=\"right\">5101966</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">2</td><td>Skörper & Tomatpuré</td><td align=\"center\">Spirit</td><td align=\"center\">Svensk mat rulez! (#37)</td><td align=\"right\">4768558</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">3</td><td>Kroppkakor</td><td align=\"center\">Spirit</td><td align=\"center\">Svensk mat rulez! (#37)</td><td align=\"right\">4265621</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">4</td><td>Blodpudding m lingon</td><td align=\"center\">Spirit</td><td align=\"center\">Svensk mat rulez! (#37)</td><td align=\"right\">4188372</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">5</td><td>Surströmming</td><td align=\"center\">Undead</td><td align=\"center\">Svensk mat rulez! (#37)</td><td align=\"right\">3524836</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">6</td><td>Raggarballe</td><td align=\"center\">Viking</td><td align=\"center\">Svensk mat rulez! (#37)</td><td align=\"right\">3509945</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">7</td><td>Snabburgare</td><td align=\"center\">Dark Elf</td><td align=\"center\">Svensk mat rulez! (#37)</td><td align=\"right\">3247133</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">8</td><td>Knäckebröd</td><td align=\"center\">Spirit</td><td align=\"center\">Svensk mat rulez! (#37)</td><td align=\"right\">3095612</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">9</td><td>NOLandsMan</td><td align=\"center\">Viking</td><td align=\"center\">TwoOfUsSciFarms (#107)</td><td align=\"right\">2821644</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">10</td><td>DarknezZ</td><td align=\"center\">Eagle</td><td align=\"center\">OWNAGE!!! ...of the Troccaholics (#9)</td><td align=\"right\">2804634</td></tr>"; 
    }
    elseif($age == "2" && $type == "Fame") {
        echo "<tr heigth=\"20\"><td width=\"22\">1</td><td>Screaming Silence</td><td align=\"center\">Eagle</td><td align=\"center\">House of Chaos (#40)</td><td align=\"right\">61587</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">2</td><td>Crack n Cheerios</td><td align=\"center\">Wood Elf</td><td align=\"center\">Cereal Killers (#5)</td><td align=\"right\">60206</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">3</td><td>Fläsklägg m rotmos</td><td align=\"center\">Dark Elf</td><td align=\"center\">Svensk mat rulez! (#37)</td><td align=\"right\">43702</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">4</td><td>FruiTy PeBBLeS</td><td align=\"center\">Wood Elf</td><td align=\"center\">Cereal Killers (#5)</td><td align=\"right\">42074</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">5</td><td>Dodge Caravan</td><td align=\"center\">Wood Elf</td><td align=\"center\">\"The Cars\" (#112)</td><td align=\"right\">40388</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">6</td><td>Nation of Cristall</td><td align=\"center\">Dark Elf</td><td align=\"center\">Nations Of War (#42)</td><td align=\"right\">37604</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">7</td><td>Snabburgare</td><td align=\"center\">Dark Elf</td><td align=\"center\">Svensk mat rulez! (#37)</td><td align=\"right\">35360</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">8</td><td>Scorpius</td><td align=\"center\">Wood Elf</td><td align=\"center\">Night Skies (#4)</td><td align=\"right\">31135</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">9</td><td>The Illusionist</td><td align=\"center\">Eagle</td><td align=\"center\">THE CARNIVAL (#30)</td><td align=\"right\">30278</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">10</td><td>Army of Darkness</td><td align=\"center\">Spirit</td><td align=\"center\">Cult Movies (#74)</td><td align=\"right\">29215</td></tr>";
    }

    // AGE 3
    elseif($age == "3" && $type == "Land") {
        echo "<tr heigth=\"20\"><td width=\"22\">1</td><td>Tempus Fugit?</td><td align=\"center\">Oleg Hai</td><td align=\"center\">Radioactive Puppies (#168)</td><td align=\"right\">10085</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">2</td><td>Aegis</td><td align=\"center\">Uruk Hai</td><td align=\"center\">Radioactive Puppies (#168)</td><td align=\"right\">9756</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">3</td><td>BROWN Sugar</td><td align=\"center\">Oleg Hai</td><td align=\"center\">BROWN (#4)</td><td align=\"right\">9243</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">4</td><td>W Monte Cristo</td><td align=\"center\">Dark Elf</td><td align=\"center\">Alliance of Chess (#127)</td><td align=\"right\">7800</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">5</td><td>Pointed Cap Land</td><td align=\"center\">Oleg Hai</td><td align=\"center\">Radioactive Puppies (#168)</td><td align=\"right\">7744</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">6</td><td>Keeper of the Mountain</td><td align=\"center\">Dwarf</td><td align=\"center\">Keepers of the Insane Asylum (#37)</td><td align=\"right\">7183</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">7</td><td>Urborg</td><td align=\"center\">Dark Elf</td><td align=\"center\">Magic: The Gathering (#24)</td><td align=\"right\">7154</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">8</td><td>W Fritz</td><td align=\"center\">Dark Elf</td><td align=\"center\">Alliance of Chess (#127)</td><td align=\"right\">7100</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">9</td><td>COED NAKED GAMBLING</td><td align=\"center\">Dark Elf</td><td align=\"center\">Coed Naked Fun! (#118)</td><td align=\"right\">7068</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">10</td><td>Blue Fluffy Bunny</td><td align=\"center\">Dark Elf</td><td align=\"center\">Bunnies vs Snails (#47)</td><td align=\"right\">6999</td></tr>";
    }
    elseif($age == "3" && $type == "Strength") {
        echo "<tr heigth=\"20\"><td width=\"22\">1</td><td>Blue Fluffy Bunny</td><td align=\"center\">Dark Elf</td><td align=\"center\">Bunnies vs Snails (#47)</td><td align=\"right\">3706682</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">2</td><td>COED NAKED GAMBLING</td><td align=\"center\">Dark Elf</td><td align=\"center\">Coed Naked Fun! (#118)</td><td align=\"right\">3677657</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">3</td><td>W Monte Cristo</td><td align=\"center\">Dark Elf</td><td align=\"center\">Alliance of Chess (#127)</td><td align=\"right\">3569550</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">4</td><td>Urborg</td><td align=\"center\">Dark Elf</td><td align=\"center\">Magic: The Gathering (#24)</td><td align=\"right\">3192141</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">5</td><td>W Fritz</td><td align=\"center\">Dark Elf</td><td align=\"center\">Alliance of Chess (#127)</td><td align=\"right\">3024322</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">6</td><td>Björnloka</td><td align=\"center\">Dark Elf</td><td align=\"center\">•In†erflora• (#71)</td><td align=\"right\">2908328</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">7</td><td>Scruffy C</td><td align=\"center\">Dark Elf</td><td align=\"center\">Rabid dogs will be put down (#123)</td><td align=\"right\">2844799</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">8</td><td>Aegis</td><td align=\"center\">Uruk Hai</td><td align=\"center\">Radioactive Puppies(#168)</td><td align=\"right\">2832193</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">9</td><td>Rainbow Fluffy Bunny</td><td align=\"center\">Dark Elf</td><td align=\"center\">Bunnies vs Snails (#47)</td><td align=\"right\">2755308</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">10</td><td>Keeper of the Mountain</td><td align=\"center\">Dwarf</td><td align=\"center\">Keepers of the Insane Asylum (#37)</td><td align=\"right\">2712924</td></tr>"; 
    }
    elseif($age == "3" && $type == "Fame") {
        echo "<tr heigth=\"20\"><td width=\"22\">1</td><td>Random of Amber</td><td align=\"center\">Dark Elf</td><td align=\"center\">Random (#43)</td><td align=\"right\">91616</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">2</td><td>Random Rivers</td><td align=\"center\">Eagle</td><td align=\"center\">Random (#43)</td><td align=\"right\">81753</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">3</td><td>Random_Fear</td><td align=\"center\">Dark Elf</td><td align=\"center\">Random (#43)</td><td align=\"right\">56094</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">4</td><td>Random_temper</td><td align=\"center\">Dark Elf</td><td align=\"center\">Random (#43)</td><td align=\"right\">47802</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">5</td><td>The Giant</td><td align=\"center\">Eagle</td><td align=\"center\">THE CARNIVAL (#54)</td><td align=\"right\">45472</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">6</td><td>zin</td><td align=\"center\">Eagle</td><td align=\"center\">InTenCity (#105)</td><td align=\"right\">39364</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">7</td><td>Bowen Ale</td><td align=\"center\">High Elf</td><td align=\"center\">Xavier Hall (#306)</td><td align=\"right\">36491</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">8</td><td>Im Just Right for U</td><td align=\"center\">Dark Elf</td><td align=\"center\">Cereal Killers (#36)</td><td align=\"right\">35974</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">9</td><td>Dark Destroyer</td><td align=\"center\">Wood Elf</td><td align=\"center\">~Tribes Of Darkness~ (#114)</td><td align=\"right\">35137</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">10</td><td>Tutti Frutti</td><td align=\"center\">Eagle</td><td align=\"center\">Radioactive Puppies (#168)</td><td align=\"right\">34496</td></tr>";
    }

    // AGE 4
    elseif($age == "4" && $type == "Land") {
        echo "<tr heigth=\"20\"><td width=\"22\">1</td><td>ZinEMiR</td><td align=\"center\">Uruk Hai</td><td align=\"center\">Blood of Heroes (#53)</td><td align=\"right\">12221</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">2</td><td>Nurse Death</td><td align=\"center\">Nazgul</td><td align=\"center\">**** THE KINKY KLINIC**** (#59)</td><td align=\"right\">12171</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">3</td><td>Dr Pepper</td><td align=\"center\">Uruk Hai</td><td align=\"center\">Blood of Heroes (#53)</td><td align=\"right\">11845</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">4</td><td>No death this time</td><td align=\"center\">Eagle</td><td align=\"center\">The Empire (#165)</td><td align=\"right\">11250</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">5</td><td>Grimlock</td><td align=\"center\">Oleg Hai</td><td align=\"center\">Robots in Disguise (#109)</td><td align=\"right\">11194</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">6</td><td>Don't lose ur head</td><td align=\"center\">Undead</td><td align=\"center\">Blood of Heroes (#53)</td><td align=\"right\">11099</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">7</td><td>aNonYmuS_AlcOHolic</td><td align=\"center\">Uruk Hai</td><td align=\"center\">Blood of Heroes (#53)</td><td align=\"right\">10785</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">8</td><td>Ultra Magnus</td><td align=\"center\">High Elf</td><td align=\"center\">Robots in Disguise (#109)</td><td align=\"right\">10580</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">9</td><td>Porto Paye</td><td align=\"center\">Uruk Hai</td><td align=\"center\">Blood of Heroes (#53)</td><td align=\"right\">10309</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">10</td><td>Your Next ----></td><td align=\"center\">Nazgul</td><td align=\"center\">**** THE KINKY KLINIC**** (#59)</td><td align=\"right\">10088</td></tr>";      
    }
    elseif($age == "4" && $type == "Strength") {
        echo "<tr heigth=\"20\"><td width=\"22\">1</td><td>Leffe</td><td align=\"center\">Nazgul</td><td align=\"center\">Beer (#126)</td><td align=\"right\">5617371</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">2</td><td>Nurse Death</td><td align=\"center\">Nazgul</td><td align=\"center\">**** THE KINKY KLINIC**** (#59)</td><td align=\"right\">4364234</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">3</td><td>Dr Pepper</td><td align=\"center\">Uruk Hai</td><td align=\"center\">Blood of Heroes (#53)</td><td align=\"right\">3871144</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">4</td><td>Vampiric Death</td><td align=\"center\">Undead</td><td align=\"center\">Realm of Dragons (#253)</td><td align=\"right\">3826939</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">5</td><td>Your Next ----></td><td align=\"center\">Nazgul</td><td align=\"center\">**** THE KINKY KLINIC**** (#59)</td><td align=\"right\">3710784</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">6</td><td>#50 Laser Lemon</td><td align=\"center\">Nazgul</td><td align=\"center\">Killer Krayons ^_^ (#18)</td><td align=\"right\">3688497</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">7</td><td>Ultra Magnus</td><td align=\"center\">High Elf</td><td align=\"center\">Robots in Disguise (#109)</td><td align=\"right\">3686628</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">8</td><td>Administrative Tools</td><td align=\"center\">Eagle</td><td align=\"center\">Screen Shots (#32)</td><td align=\"right\">3647531</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">9</td><td>aNonYmuS_AlcOHolic</td><td align=\"center\">Uruk Hai</td><td align=\"center\">Blood of Heroes (#53)</td><td align=\"right\">3594226</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">10</td><td>ZinEMiR</td><td align=\"center\">Uruk Hai</td><td align=\"center\">Blood of Heroes (#53)</td><td align=\"right\">3528443</td></tr>";
    }
    elseif($age == "4" && $type == "Fame") {
        echo "<tr heigth=\"20\"><td width=\"22\">1</td><td>Dark Destroyer</td><td align=\"center\">Wood Elf</td><td align=\"center\">Tribes Of Darkness (#66)</td><td align=\"right\">71697</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">2</td><td>Infamous Wrath</td><td align=\"center\">Dark Elf</td><td align=\"center\">Eternal Fame (#214)</td><td align=\"right\">60468</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">3</td><td>Famous Illumina</td><td align=\"center\">Dark Elf</td><td align=\"center\">Eternal Fame (#214)</td><td align=\"right\">58343</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">4</td><td>Infamous God</td><td align=\"center\">Dark Elf</td><td align=\"center\">Eternal Fame (#214)</td><td align=\"right\">57050</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">5</td><td>The Killer Clown</td><td align=\"center\">Eagle</td><td align=\"center\">THE CARNIVAL (#92)</td><td align=\"right\">56281</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">6</td><td>Infamous Elf</td><td align=\"center\">Dark Elf</td><td align=\"center\">Eternal Fame (#214)</td><td align=\"right\">55755</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">7</td><td>I Have No Bunnies!</td><td align=\"center\">Dark Elf</td><td align=\"center\">We have .... (#100)</td><td align=\"right\">53123</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">8</td><td>Infamous Infamy</td><td align=\"center\">Dark Elf</td><td align=\"center\">Eternal Fame (#214)</td><td align=\"right\">50754</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">9</td><td>Infamous Raper</td><td align=\"center\">Dark Elf</td><td align=\"center\">Eternal Fame (#214)</td><td align=\"right\">46997</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">10</td><td>The Fortune Teller</td><td align=\"center\">Eagle</td><td align=\"center\">THE CARNIVAL (#92)</td><td align=\"right\">46515</td></tr>";
    }

    // AGE 5
    elseif($age == "5" && $type == "Land") {
        echo "<tr heigth=\"20\"><td width=\"22\">1</td><td>|TNC| Mr [Big]</td><td align=\"center\">High Elf</td><td align=\"center\">***The New Crew*** (#31)</td><td align=\"right\">10007</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">2</td><td>|TNC| Police Patola</td><td align=\"center\">High Elf</td><td align=\"center\">***The New Crew*** (#31)</td><td align=\"right\">9205</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">3</td><td>|TNC| Modesty Blaise</td><td align=\"center\">High Elf</td><td align=\"center\">***The New Crew*** (#31)</td><td align=\"right\">8880</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">4</td><td>|TNC| Inspector Bust</td><td align=\"center\">High Elf</td><td align=\"center\">***The New Crew*** (#31)</td><td align=\"right\">8745</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">5</td><td>|TNC| The Police</td><td align=\"center\">High Elf</td><td align=\"center\">***The New Crew*** (#31)</td><td align=\"right\">7260</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">6</td><td>Akh Thoth</td><td align=\"center\">Dark Elf</td><td align=\"center\">Keeper of Life (#427)</td><td align=\"right\">6179</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">7</td><td>Lebowski</td><td align=\"center\">Dark Elf</td><td align=\"center\">Dark Order (#263)</td><td align=\"right\">6128</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">8</td><td>Crouching Bunny, Hidden Snail</td><td align=\"center\">High Elf</td><td align=\"center\">Cause of Death: Bunnies & Snails (#398)</td><td align=\"right\">5881</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">9</td><td>Pink Panther</td><td align=\"center\">Dark Elf</td><td align=\"center\">Cartoon Gang (14) (#493)</td><td align=\"right\">5715</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">10</td><td>Napoleon Bonaparte</td><td align=\"center\">High Elf</td><td align=\"center\">killin time (#393)</td><td align=\"right\">5659</td></tr>";
    }
    elseif($age == "5" && $type == "Strength") {
        echo "<tr heigth=\"20\"><td width=\"22\">1</td><td>|TNC| Mr [Big]</td><td align=\"center\">High Elf</td><td align=\"center\">***The New Crew*** (#31)</td><td align=\"right\">2846796</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">2</td><td>|TNC| Modesty Blaise</td><td align=\"center\">High Elf</td><td align=\"center\">***The New Crew*** (#31)</td><td align=\"right\">2625772</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">3</td><td>|TNC| Police Patola</td><td align=\"center\">High Elf</td><td align=\"center\">***The New Crew*** (#31)</td><td align=\"right\">2530149</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">4</td><td>|TNC| Inspector Bust</td><td align=\"center\">High Elf</td><td align=\"center\">***The New Crew*** (#31)</td><td align=\"right\">2169820</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">5</td><td>Emerald</td><td align=\"center\">Nazgul</td><td align=\"center\">stay tuned for next episode (#131)</td><td align=\"right\">1845938</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">6</td><td>Lebowski</td><td align=\"center\">Dark Elf</td><td align=\"center\">Dark Order (#263)</td><td align=\"right\">1838989</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">7</td><td>Akh Thoth</td><td align=\"center\">Dark Elf</td><td align=\"center\">Keeper of Life (#427)</td><td align=\"right\">1831309</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">8</td><td>VerWUNDERLICH</td><td align=\"center\">High Elf</td><td align=\"center\">AlkoholikA (#87)</td><td align=\"right\">1752384</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">9</td><td>|TNC| The Police</td><td align=\"center\">High Elf</td><td align=\"center\">***The New Crew*** (#31)</td><td align=\"right\">1739197</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">10</td><td>Pink Panther</td><td align=\"center\">Dark Elf</td><td align=\"center\">Cartoon Gang (14) (#493)</td><td align=\"right\">1677627</td></tr>";
    }
    elseif($age == "5" && $type == "Fame") {
        echo "<tr heigth=\"20\"><td width=\"22\">1</td><td>|TNC| Modesty Blaise</td><td align=\"center\">High Elf</td><td align=\"center\">***The New Crew*** (#31)</td><td align=\"right\">45130</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">2</td><td>|TNC| Mr [Big]</td><td align=\"center\">High Elf</td><td align=\"center\">***The New Crew*** (#31)</td><td align=\"right\">34166</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">3</td><td>|TNC| Police Patola</td><td align=\"center\">High Elf</td><td align=\"center\">***The New Crew*** (#31)</td><td align=\"right\">33719</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">4</td><td>|TNC| Inspector Bust</td><td align=\"center\">High Elf</td><td align=\"center\">***The New Crew*** (#31)</td><td align=\"right\">27348</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">5</td><td>Snail Trek 10: Nemesis</td><td align=\"center\">Nazgul</td><td align=\"center\">Cause of Death: Bunnies & Snails (#398)</td><td align=\"right\">23935</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">6</td><td>|TNC| The Police</td><td align=\"center\">High Elf</td><td align=\"center\">***The New Crew*** (#31)</td><td align=\"right\">21409</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">7</td><td>Crouching Bunny, Hidden Snail</td><td align=\"center\">High Elf</td><td align=\"center\">Cause of Death: Bunnies & Snails (#398)</td><td align=\"right\">20826</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">8</td><td>Emerald</td><td align=\"center\">Nazgul</td><td align=\"center\">stay tuned for next episode (#131)</td><td align=\"right\">20604</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">9</td><td>Snail AlMighty</td><td align=\"center\">High Elf</td><td align=\"center\">Cause of Death: Bunnies & Snails (#398)</td><td align=\"right\">19340</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">10</td><td>NSS</td><td align=\"center\">Nazgul</td><td align=\"center\">~AA~ (#116)</td><td align=\"right\">19211</td></tr>";
    }

    // AGE 6
    elseif($age == "6" && $type == "Land") {
        echo "<tr heigth=\"20\"><td width=\"22\">1</td><td>Arvingarna</td><td align=\"center\">Undead</td><td align=\"center\">Buttersweet and tender (#124)</td><td align=\"right\">14,875</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">2</td><td>Creative Destruction</td><td align=\"center\">Nazgul</td><td align=\"center\">SAVEN THE RAVEN - The New Crew (#176)</td><td align=\"right\">10,949</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">3</td><td>Driving Drunk</td><td align=\"center\">High Elf</td><td align=\"center\">~Tribes of Drunkness~ (#6)</td><td align=\"right\">10,913</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">4</td><td>Water Joe</td><td align=\"center\">High Elf</td><td align=\"center\">Warblockers EVERYWHERE! (#111)</td><td align=\"right\">10,782</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">5</td><td>Six Special Spirits</td><td align=\"center\">Eagle</td><td align=\"center\">SAVEN THE RAVEN - The New Crew (#176)</td><td align=\"right\">10,616</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">6</td><td>Battle Boosted Birds</td><td align=\"center\">Eagle</td><td align=\"center\">SAVEN THE RAVEN - The New Crew (#176)</td><td align=\"right\">10,532</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">7</td><td>To drunk to hit you</td><td align=\"center\">High Elf</td><td align=\"center\">~Tribes of Drunkness~ (#6)</td><td align=\"right\">10,347</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">8</td><td>ClearlyMisunderstood</td><td align=\"center\">Nazgul</td><td align=\"center\">SAVEN THE RAVEN - The New Crew (#176)</td><td align=\"right\">10,158</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">9</td><td>Dwaw of Waw</td><td align=\"center\">Nazgul</td><td align=\"center\">Dark Order (#145)</td><td align=\"right\">9,246</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">10</td><td>Natural BugEaters</td><td align=\"center\">Eagle</td><td align=\"center\">The Bug removers (#77)</td><td align=\"right\">8,623</td></tr>";      
    }
    elseif($age == "6" && $type == "Strength") {
        echo "<tr heigth=\"20\"><td width=\"22\">1</td><td>Arvingarna</td><td align=\"center\">Undead</td><td align=\"center\">Buttersweet and tender (#124)</td><td align=\"right\">8,222,025</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">2</td><td>Dwaw of Waw</td><td align=\"center\">Nazgul</td><td align=\"center\">Dark Order (#145)</td><td align=\"right\">5,882,971</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">3</td><td>Europatents - Just say NO!</td><td align=\"center\">Nazgul</td><td align=\"center\">reaver is back (#184)</td><td align=\"right\">5,818,925</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">4</td><td>Creative Destruction</td><td align=\"center\">Nazgul</td><td align=\"center\">SAVEN THE RAVEN - The New Crew (#176)</td><td align=\"right\">5,567,130</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">5</td><td>Water Joe</td><td align=\"center\">High Elf</td><td align=\"center\">Warblockers EVERYWHERE! (#111)</td><td align=\"right\">4,597,824</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">6</td><td>ClearlyMisunderstood</td><td align=\"center\">Nazgul</td><td align=\"center\">SAVEN THE RAVEN - The New Crew (#176)</td><td align=\"right\">4,567,461</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">7</td><td>Knights of The Lake</td><td align=\"center\">Undead</td><td align=\"center\">Knights of the Round (#41)</td><td align=\"right\">4,470,161</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">8</td><td>Driving Drunk</td><td align=\"center\">High Elf</td><td align=\"center\">~Tribes of Drunkness~ (#6)</td><td align=\"right\">3,933,940</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">9</td><td>Särimner</td><td align=\"center\">Nazgul</td><td align=\"center\">•In†erflora• Last round, still strong (#5)</td><td align=\"right\">3,742,167</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">10</td><td>Upset Undeads Unite</td><td align=\"center\">Undead</td><td align=\"center\">Saven the Raven-Orkfian Alphabet (#126)</td><td align=\"right\">3,639,674</td></tr>";
    }
    elseif($age == "6" && $type == "Fame") {
        echo "<tr heigth=\"20\"><td width=\"22\">1</td><td>Driving Drunk</td><td align=\"center\">High Elf</td><td align=\"center\">~Tribes of Drunkness~ (#6)</td><td align=\"right\">40,813</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">2</td><td>Arvingarna</td><td align=\"center\">Undead</td><td align=\"center\">Buttersweet and tender (#124)</td><td align=\"right\">33,035</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">3</td><td>Creative Destruction</td><td align=\"center\">Nazgul</td><td align=\"center\">SAVEN THE RAVEN - The New Crew (#176)</td><td align=\"right\">32,090</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">4</td><td>Glamour DoomZ</td><td align=\"center\">Eagle</td><td align=\"center\">-[RelaxationOfDoomzNudity]- (#114)</td><td align=\"right\">27,949</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">5</td><td>To drunk to hit you</td><td align=\"center\">High Elf</td><td align=\"center\">~Tribes of Drunkness~ (#6)</td><td align=\"right\">27,354</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">6</td><td>World Wide Wraiths</td><td align=\"center\">Nazgul</td><td align=\"center\">Crackbabies Anonymous (#297)</td><td align=\"right\">26,448</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">7</td><td>Dwaw of Waw</td><td align=\"center\">Nazgul</td><td align=\"center\">Dark Order (#145)</td><td align=\"right\">25,226</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">8</td><td>Clan Pitt Wyrm</td><td align=\"center\">Dragon</td><td align=\"center\">Flower of Lilath (#133)</td><td align=\"right\">24,635</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">9</td><td>Dain Bornhald</td><td align=\"center\">Eagle</td><td align=\"center\">reaver is back (#184)</td><td align=\"right\">24,564</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">10</td><td>Europatents - Just say NO!</td><td align=\"center\">Nazgul</td><td align=\"center\">reaver is back (#184)</td><td align=\"right\">23,231</td></tr>";
    }

    // AGE 7
    elseif($age == "7" && $type == "Land") {
        echo "<tr heigth=\"20\"><td width=\"22\">1</td><td>[core] Addicted</td><td align=\"center\">Dragon</td><td align=\"center\">Core Comes Clean (#386)</td><td align=\"right\">15,104</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">2</td><td>Dark Intentions</td><td align=\"center\">Dragon</td><td align=\"center\">Dark Minds (#45)</td><td align=\"right\">14,812</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">3</td><td>President Skroob</td><td align=\"center\">Undead</td><td align=\"center\">Tribes of Darkness (#129)</td><td align=\"right\">14,256</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">4</td><td>Jolly Cola</td><td align=\"center\">Undead</td><td align=\"center\">Cola for featherless Ravens (#138)</td><td align=\"right\">14,231</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">5</td><td>[core] Suicider</td><td align=\"center\">Undead</td><td align=\"center\">Core Comes Clean (#386)</td><td align=\"right\">13,458</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">6</td><td>D4rk Water</td><td align=\"center\">Dark Elf</td><td align=\"center\">[DP] going to Hollywood next age (#16)</td><td align=\"right\">13,454</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">7</td><td>Dark Lunnik</td><td align=\"center\">Dragon</td><td align=\"center\">Dark Minds (#45)</td><td align=\"right\">13,001</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">8</td><td>Heroes of The Hell</td><td align=\"center\">Undead</td><td align=\"center\">Heroes Of Orkfia (#372)</td><td align=\"right\">12,687</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">9</td><td>Tic-Tac-Toe</td><td align=\"center\">Viking</td><td align=\"center\">Games Better than Orkfia (#128)</td><td align=\"right\">12,407</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">10</td><td>Parody nr 21</td><td align=\"center\">Eagle</td><td align=\"center\">TNC :Core get a freakin' life!! (#21)</td><td align=\"right\">11,796</td></tr>";       
    }
    elseif($age == "7" && $type == "Strength") {
        echo "<tr heigth=\"20\"><td width=\"22\">1</td><td>President Skroob</td><td align=\"center\">Undead</td><td align=\"center\">Tribes of Darkness (#129)</td><td align=\"right\">8,297,132</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">2</td><td>Heroes of The Hell</td><td align=\"center\">Undead</td><td align=\"center\">Heroes Of Orkfia (#372)</td><td align=\"right\">6,907,981</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">3</td><td>[core] Suicider</td><td align=\"center\">Undead</td><td align=\"center\">Core Comes Clean (#386)</td><td align=\"right\">6,905,806</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">4</td><td>Dark Lunnik</td><td align=\"center\">Dragon</td><td align=\"center\">Dark Minds (#45)</td><td align=\"right\">6,627,539</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">5</td><td>Say NO to gay theme!</td><td align=\"center\">Nazgul</td><td align=\"center\">- +* Fear *+ - (#179)</td><td align=\"right\">6,295,119</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">6</td><td>Jolly Cola</td><td align=\"center\">Undead</td><td align=\"center\">Cola for featherless Ravens (#138)</td><td align=\"right\">6,290,075</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">7</td><td>Tic-Tac-Toe</td><td align=\"center\">Viking</td><td align=\"center\">Games Better than Orkfia (#128)</td><td align=\"right\">6,049,385</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">8</td><td>Heroes of ThunderCats</td><td align=\"center\">Nazgul</td><td align=\"center\">Heroes Of Orkfia (#372)</td><td align=\"right\">6,042,994</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">9</td><td>Dark Intentions</td><td align=\"center\">Dragon</td><td align=\"center\">Dark Minds (#45)</td><td align=\"right\">5,880,398</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">10</td><td>D4rk Water</td><td align=\"center\">Dark Elf</td><td align=\"center\">[DP] going to Hollywood next age (#16)</td><td align=\"right\">5,780,573</td></tr>"; 
    }
    elseif($age == "7" && $type == "Fame") {
        echo "<tr heigth=\"20\"><td width=\"22\">1</td><td>Heroes of ThunderCats</td><td align=\"center\">Nazgul</td><td align=\"center\">Heroes Of Orkfia (#372)</td><td align=\"right\">61,481</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">2</td><td>The Forsaken Raider</td><td align=\"center\">High Elf</td><td align=\"center\">The Foresaken Ones (#287)</td><td align=\"right\">55,780</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">3</td><td>Kamikaze Cutlery</td><td align=\"center\">Dark Elf</td><td align=\"center\">Kamikaze Roadkill (#328)</td><td align=\"right\">52,592</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">4</td><td>[core] Bug finder</td><td align=\"center\">Dark Elf</td><td align=\"center\">Core Comes Clean (#386)</td><td align=\"right\">50,090</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">5</td><td>Heroes of The Hell</td><td align=\"center\">Undead</td><td align=\"center\">Heroes Of Orkfia (#372)</td><td align=\"right\">42,258</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">6</td><td>Pitt Fiends</td><td align=\"center\">Undead</td><td align=\"center\">Vogon Poetry Team (#203)</td><td align=\"right\">38,879</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">7</td><td>President Skroob</td><td align=\"center\">Undead</td><td align=\"center\">Tribes of Darkness (#129)</td><td align=\"right\">38,637</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">8</td><td>Zana Crizata</td><td align=\"center\">Dark Elf</td><td align=\"center\">Zanele Grase v2.01b (#261)</td><td align=\"right\">38,159</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">9</td><td>The Fortune Teller</td><td align=\"center\">Wood Elf</td><td align=\"center\">THE CARNIVAL (#30)</td><td align=\"right\">37,091</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">10</td><td>[core] Suicider</td><td align=\"center\">Undead</td><td align=\"center\">Core Comes Clean (#386)</td><td align=\"right\">34,190</td></tr>";
    }

    // AGE 8
    elseif($age == "8" && $type == "Land") {
        echo "<tr heigth=\"20\"><td width=\"22\">1</td><td>MassiveBlood</td><td align=\"center\">Eagle</td><td align=\"center\">Slaves of the 'early' raves (#10)</td><td align=\"right\">22,977</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">2</td><td>TryNot2Cheat</td><td align=\"center\">Dragon</td><td align=\"center\">*** The New Crew *** (#11)</td><td align=\"right\">20,603</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">3</td><td>TurtlesNeverCry</td><td align=\"center\">Nazgul</td><td align=\"center\">*** The New Crew *** (#11)</td><td align=\"right\">19,600</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">4</td><td>Daoine Sidhe</td><td align=\"center\">Eagle</td><td align=\"center\">Slaves of the 'early' raves (#10)</td><td align=\"right\">16,978</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">5</td><td>K2 8,611m</td><td align=\"center\">Dragon</td><td align=\"center\">Lofty Expectations (#106)</td><td align=\"right\">15,358</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">6</td><td>Tyler Durden</td><td align=\"center\">Dragon</td><td align=\"center\">The Supervillains (#122)</td><td align=\"right\">14,617</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">7</td><td>Lex Luthor</td><td align=\"center\">High Elf</td><td align=\"center\">The Supervillains (#122)</td><td align=\"right\">14,415</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">8</td><td>Grobi</td><td align=\"center\">Nazgul</td><td align=\"center\">S€samstrass€ngang 8D (#152)</td><td align=\"right\">14,153</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">9</td><td>Lithuania Lands</td><td align=\"center\">Undead</td><td align=\"center\">New age and i still alive shame (#112)</td><td align=\"right\">13,993</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">10</td><td>Dark River of Mathmetic Power</td><td align=\"center\">Eagle</td><td align=\"center\">Dark Minds-Recruiting GOOD players! (#45)</td><td align=\"right\">13,645</td></tr>";      
    }
    elseif($age == "8" && $type == "Strength") {
        echo "<tr heigth=\"20\"><td width=\"22\">1</td><td>TurtlesNeverCry</td><td align=\"center\">Nazgul</td><td align=\"center\">*** The New Crew *** (#11)</td><td align=\"right\">12,519,679</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">2</td><td>Grobi</td><td align=\"center\">Nazgul</td><td align=\"center\">S€samstrass€ngang 8D (#152)</td><td align=\"right\">10,118,191</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">3</td><td>Ernie</td><td align=\"center\">Nazgul</td><td align=\"center\">S€samstrass€ngang 8D (#152)</td><td align=\"right\">10,085,979</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">4</td><td>Bert</td><td align=\"center\">Nazgul</td><td align=\"center\">S€samstrass€ngang 8D (#152)</td><td align=\"right\">9,234,346</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">5</td><td>TryNot2Cheat</td><td align=\"center\">Dragon</td><td align=\"center\">*** The New Crew *** (#11)</td><td align=\"right\">8,756,905</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">6</td><td>MassiveBlood</td><td align=\"center\">Eagle</td><td align=\"center\">Slaves of the 'early' raves (#10)</td><td align=\"right\">8,441,438</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">7</td><td>Aristoteles</td><td align=\"center\">Nazgul</td><td align=\"center\">(#e^(i*pi)) + (#26) = (#25)</td><td align=\"right\">8,199,355</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">8</td><td>Total Darkness</td><td align=\"center\">Nazgul</td><td align=\"center\">DOOMBRINGERS (#26)</td><td align=\"right\">8,166,582</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">9</td><td>Joseph I. Guillotin</td><td align=\"center\">Undead</td><td align=\"center\">(#e^(i*pi)) + (#26) = (#25)</td><td align=\"right\">7,863,925</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">10</td><td>Tribe Of Darkness</td><td align=\"center\">Undead</td><td align=\"center\">We zoeken nog 3 nederlandstalige mensen (#83)</td><td align=\"right\">7,656,960</td></tr>"; 
    }
    elseif($age == "8" && $type == "Fame") {
        echo "<tr heigth=\"20\"><td width=\"22\">1</td><td>Crazy Clown</td><td align=\"center\">Eagle</td><td align=\"center\">THE CARNIVAL (#109)</td><td align=\"right\">63,812</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">2</td><td>[core] KungenAC</td><td align=\"center\">Eagle</td><td align=\"center\">[core] +92 kills (#64)</td><td align=\"right\">49,533</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">3</td><td>[JJE] Sterilization</td><td align=\"center\">Mori Hai</td><td align=\"center\">[JJE] Justice (#48)</td><td align=\"right\">48,110</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">4</td><td>[core] zeds</td><td align=\"center\">Spirit</td><td align=\"center\">[core] +92 kills (#64)</td><td align=\"right\">47,663</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">5</td><td>ThunderCats</td><td align=\"center\">Spirit</td><td align=\"center\">Berserker (#238)</td><td align=\"right\">45,305</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">6</td><td>Urizen</td><td align=\"center\">Spirit</td><td align=\"center\">T» Endovelicus «T (#80)</td><td align=\"right\">45,155</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">7</td><td>Lex Luthor</td><td align=\"center\">High Elf</td><td align=\"center\">The Supervillains (#122)</td><td align=\"right\">42,562</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">8</td><td>FuNChOlIc</td><td align=\"center\">Eagle</td><td align=\"center\">FUNtouchable gamerz 52 kills (#21)</td><td align=\"right\">42,275</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">9</td><td>Kakarot gamerzFUN</td><td align=\"center\">Eagle</td><td align=\"center\">FUNtouchable gamerz 52 kills (#21)</td><td align=\"right\">39,012</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">10</td><td>Tyler Durden</td><td align=\"center\">Dragon</td><td align=\"center\">The Supervillains (#122)</td><td align=\"right\">35,155</td></tr>";
    }

    // AGE 9
    elseif($age == "9" && $type == "Land") {
        echo "<tr heigth=\"20\"><td width=\"22\">1</td><td>Paint it Black</td><td align=\"center\">Dragon</td><td align=\"center\">*** The New Crew *** (#233)</td><td align=\"right\">15,708</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">2</td><td>Black Or White</td><td align=\"center\">Dragon</td><td align=\"center\">*** The New Crew *** (#233)</td><td align=\"right\">14,998</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">3</td><td>Black Metal</td><td align=\"center\">Uruk Hai</td><td align=\"center\">*** The New Crew *** (#233)</td><td align=\"right\">14,616</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">4</td><td>Dûrbsnak the Raider</td><td align=\"center\">Oleg Hai</td><td align=\"center\">Let the Battle for Middle Earth Begin (#422)</td><td align=\"right\">10,404</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">5</td><td>Kamikaze Wolf</td><td align=\"center\">Brittonian</td><td align=\"center\"> KR: The Farm List Claims Another Victim. (#178)</td><td align=\"right\">10,010</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">6</td><td>LittleWolfLittleWolf</td><td align=\"center\">Dragon</td><td align=\"center\">Our fun your nightmare (#365)</td><td align=\"right\">9,895</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">7</td><td>Insomniac Ugly</td><td align=\"center\">Uruk Hai</td><td align=\"center\">Old Crew :p (#186)</td><td align=\"right\">9,840</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">8</td><td>Fury Rain</td><td align=\"center\">Nazgul</td><td align=\"center\">Our fun your nightmare (#365)</td><td align=\"right\">9,799</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">9</td><td>Booh!!</td><td align=\"center\">Spirit</td><td align=\"center\">Old Crew :p (#186)</td><td align=\"right\">9,778</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">10</td><td>Ghâshalûk the Basher</td><td align=\"center\">Uruk Hai</td><td align=\"center\">Let the Battle for Middle Earth Begin (#422)</td><td align=\"right\">9,723</td></tr>";      
    }
    elseif($age == "9" && $type == "Strength") {
        echo "<tr heigth=\"20\"><td width=\"22\">1</td><td>Black Or White</td><td align=\"center\">Dragon</td><td align=\"center\">*** The New Crew *** (#233)</td><td align=\"right\">6,941,407</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">2</td><td>Paint it Black</td><td align=\"center\">Dragon</td><td align=\"center\">*** The New Crew *** (#233)</td><td align=\"right\">6,092,874</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">3</td><td>Black Metal</td><td align=\"center\">Uruk Hai</td><td align=\"center\">*** The New Crew *** (#233)</td><td align=\"right\">5,284,543</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">4</td><td>Fury Rain</td><td align=\"center\">Nazgul</td><td align=\"center\">Our fun your nightmare (#365)</td><td align=\"right\">4,818,096</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">5</td><td>McDonalds</td><td align=\"center\">Viking</td><td align=\"center\">Food Chain (#127)</td><td align=\"right\">4,720,475</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">6</td><td>Dûrbsnak the Raider</td><td align=\"center\">Oleg Hai</td><td align=\"center\">Let the Battle for Middle Earth Begin (#422)</td><td align=\"right\">4,508,419</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">7</td><td>(ICCC) Footfall</td><td align=\"center\">Spirit</td><td align=\"center\">ICCC (#292)</td><td align=\"right\">4,470,628</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">8</td><td>Kamikaze Wolf</td><td align=\"center\">Brittonian</td><td align=\"center\"> KR: The Farm List Claims Another Victim. (#178)</td><td align=\"right\">4,328,787</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">9</td><td>(ICCC) Agony</td><td align=\"center\">Spirit</td><td align=\"center\">ICCC (#292)</td><td align=\"right\">4,250,511</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">10</td><td>Pizza Hut</td><td align=\"center\">Spirit</td><td align=\"center\">Food Chain (#127)</td><td align=\"right\">4,177,764</td></tr>";
    }
    elseif($age == "9" && $type == "Fame") {
        echo "<tr heigth=\"20\"><td width=\"22\">1</td><td>Paint it Black</td><td align=\"center\">Dragon</td><td align=\"center\">*** The New Crew *** (#233)</td><td align=\"right\">38,772</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">2</td><td>Black Or White</td><td align=\"center\">Dragon</td><td align=\"center\">*** The New Crew *** (#233)</td><td align=\"right\">35,205</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">3</td><td>[JJE] Freezing Rain</td><td align=\"center\">Spirit</td><td align=\"center\">[JJE] Imminent Disaster (#60)</td><td align=\"right\">32,081</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">4</td><td>¥.Inferi Dii.¥</td><td align=\"center\">Spirit</td><td align=\"center\">¥.Endovelicus.¥ - We SIN a lot (#59)</td><td align=\"right\">30,304</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">5</td><td>Bringer of Doom</td><td align=\"center\">Spirit</td><td align=\"center\">Dark Order (#227)</td><td align=\"right\">28,822</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">6</td><td>Dark Underwear Stain</td><td align=\"center\">Dark Elf</td><td align=\"center\">The Dark Minds Alliance (#482)</td><td align=\"right\">26,932</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">7</td><td>[JJE] Plague</td><td align=\"center\">Dark Elf</td><td align=\"center\">[JJE] Imminent Disaster (#60)</td><td align=\"right\">25,193</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">8</td><td>¥.Nefilim.¥</td><td align=\"center\">Spirit</td><td align=\"center\">¥.Endovelicus.¥ - We SIN a lot (#59)</td><td align=\"right\">22,985</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">9</td><td>[JJE] Ice Storm</td><td align=\"center\">Dark Elf</td><td align=\"center\">[JJE] Imminent Disaster (#60)</td><td align=\"right\">22,965</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">10</td><td>[JJE] Fire Storm</td><td align=\"center\">Dark Elf</td><td align=\"center\">[JJE] Imminent Disaster (#60)</td><td align=\"right\">22,952</td></tr>";
    }

    // AGE 10
    elseif($age == "10" && $type == "Land") {
        echo "<tr heigth=\"20\"><td width=\"22\">1</td><td>[Bamse] Surre</td><td align=\"center\">High Elf</td><td align=\"center\">[Bamse]s Vänner (#3)</td><td align=\"right\">23,988</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">2</td><td>[NoO]Lord Tic</td><td align=\"center\">Undead</td><td align=\"center\">[NoO] Nobles Of Orkfia (#68)</td><td align=\"right\">16,087</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">3</td><td>[Bamse] Skalman</td><td align=\"center\">Dwarf</td><td align=\"center\">[Bamse]s Vänner (#3)</td><td align=\"right\">15,585</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">4</td><td>Headbutts Trains</td><td align=\"center\">Wood Elf</td><td align=\"center\">Jinxed Indians (#79)</td><td align=\"right\">14,314</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">5</td><td>Black Monday</td><td align=\"center\">Uruk Hai</td><td align=\"center\">Mostly Harmless Infections (#72)</td><td align=\"right\">12,712</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">6</td><td>[Tomb] Cursed</td><td align=\"center\">Dragon</td><td align=\"center\">[Tomb] - The Age of The Crypt (#9)</td><td align=\"right\">12,689</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">7</td><td>[MM] Sock Exchange</td><td align=\"center\">Wood Elf</td><td align=\"center\">Mysterious Malapropisms (#157)</td><td align=\"right\">12,668</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">8</td><td>[JJE] God of War</td><td align=\"center\">Dragon</td><td align=\"center\">[JJE] And Then You Die (#22)</td><td align=\"right\">12,487</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">9</td><td>Not a Toy</td><td align=\"center\">Nazgul</td><td align=\"center\">The Orkfians Time Forgot (#14)</td><td align=\"right\">12,235</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">10</td><td>[Tomb] of Lightning</td><td align=\"center\">Dragon</td><td align=\"center\">[Tomb] - The Age of The Crypt (#9)</td><td align=\"right\">12,085</td></tr>";
    }
    elseif($age == "10" && $type == "Strength") {
        echo "<tr heigth=\"20\"><td width=\"22\">1</td><td>[Bamse] Surre</td><td align=\"center\">High Elf</td><td align=\"center\">[Bamse]s Vänner (#3)</td><td align=\"right\">8,481,099</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">2</td><td>[NoO]Lord Tic</td><td align=\"center\">Undead</td><td align=\"center\">[NoO] Nobles Of Orkfia (#68)</td><td align=\"right\">7,678,674</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">3</td><td>[Bamse] Skalman</td><td align=\"center\">Dwarf</td><td align=\"center\">[Bamse]s Vänner (#3)</td><td align=\"right\">6,420,943</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">4</td><td>Black Ice</td><td align=\"center\">Nazgul</td><td align=\"center\">[ICCC] (#106)</td><td align=\"right\">6,412,569</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">5</td><td>Not a Toy</td><td align=\"center\">Nazgul</td><td align=\"center\">The Orkfians Time Forgot (#14)</td><td align=\"right\">6,285,125</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">6</td><td>Not a red baseball cap</td><td align=\"center\">Nazgul</td><td align=\"center\">The Orkfians Time Forgot (#14)</td><td align=\"right\">5,891,908</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">7</td><td>Hard[core]</td><td align=\"center\">Nazgul</td><td align=\"center\">In[core]porated: 19% evil, 81% good (#40)</td><td align=\"right\">5,364,757</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">8</td><td>En[core]</td><td align=\"center\">Nazgul</td><td align=\"center\">In[core]porated: 19% evil, 81% good (#40)</td><td align=\"right\">5,229,618</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">9</td><td>Plays with Fires</td><td align=\"center\">Dwarf</td><td align=\"center\">Jinxed Indians (#79)</td><td align=\"right\">5,178,269</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">10</td><td>Cold Front</td><td align=\"center\">Dark Elf</td><td align=\"center\">[ICCC] (#106)</td><td align=\"right\">4,399,546</td></tr>";
    }
    elseif($age == "10" && $type == "Fame") {
        echo "<tr heigth=\"20\"><td width=\"22\">1</td><td>[Bamse] Surre</td><td align=\"center\">High Elf</td><td align=\"center\">[Bamse]s Vänner (#3)</td><td align=\"right\">37,985</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">2</td><td>You Wanna Play Rough</td><td align=\"center\">Wood Elf</td><td align=\"center\">Love and Laughter (#44)</td><td align=\"right\">33,578</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">3</td><td>¥.Igedium.¥</td><td align=\"center\">Spirit</td><td align=\"center\">¥.Endovelicus.¥ (#49)</td><td align=\"right\">31,034</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">4</td><td>[JJE] Hwighton Raid</td><td align=\"center\">Spirit</td><td align=\"center\">[JJE] And Then You Die (#22)</td><td align=\"right\">27,130</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">5</td><td>[JJE] Elendian</td><td align=\"center\">Spirit</td><td align=\"center\">[JJE] And Then You Die (#22)</td><td align=\"right\">23,471</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">6</td><td>Bud Frogs Warning</td><td align=\"center\">Dark Elf</td><td align=\"center\">Mostly Harmless Infections (#72)</td><td align=\"right\">22,946</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">7</td><td>[NoO]Lord Landfat</td><td align=\"center\">Spirit</td><td align=\"center\">[NoO] Nobles Of Orkfia (#68)</td><td align=\"right\">22,572</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">8</td><td>¥.Inferi Dii.¥</td><td align=\"center\">Spirit</td><td align=\"center\">¥.Endovelicus.¥ (#49)</td><td align=\"right\">22,231</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">9</td><td>En[core]</td><td align=\"center\">Nazgul</td><td align=\"center\">In[core]porated: 19% evil, 81% good (#40)</td><td align=\"right\">20,436</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">10</td><td>[Tomb] Cursed</td><td align=\"center\">Dragon</td><td align=\"center\">[Tomb] - The Age of The Crypt (#9)</td><td align=\"right\">20,381</td></tr>";
    }

    // AGE 11
    elseif($age == "11" && $type == "Land") {
        echo "<tr heigth=\"20\"><td width=\"22\">1</td><td>[Bamse] Kubbe Varg</td><td align=\"center\">High Elf</td><td align=\"center\">[Bamse] i Trollskogen (#3)</td><td align=\"right\">18,520</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">2</td><td>[Bamse] Wolfram Varg</td><td align=\"center\">High Elf</td><td align=\"center\">[Bamse] i Trollskogen (#3)</td><td align=\"right\">16,985</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">3</td><td>[Bamse] Krösus Sork</td><td align=\"center\">High Elf</td><td align=\"center\">[Bamse] i Trollskogen (#3)</td><td align=\"right\">16,974</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">4</td><td>[OTF] ME+OWNS = J00</td><td align=\"center\">Undead</td><td align=\"center\">Orkfians Time Forgot (#355)</td><td align=\"right\">16,828</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">5</td><td>[ITU] Idiotic Tags United</td><td align=\"center\">Nazgul</td><td align=\"center\">MH: We apologize for the inconvenience (#349)</td><td align=\"right\">13,425</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">6</td><td>There's No Core</td><td align=\"center\">High Elf</td><td align=\"center\">MH: We apologize for the inconvenience (#349)</td><td align=\"right\">12,597</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">7</td><td>New [core] Keg Can</td><td align=\"center\">Dragon</td><td align=\"center\">[core] gone wild! part 8 (#320)</td><td align=\"right\">12,430</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">8</td><td>Heavily Shaded Brain</td><td align=\"center\">High Elf</td><td align=\"center\">MH: We apologize for the inconvenience (#349)</td><td align=\"right\">12,428</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">9</td><td>[Bamse] Brum</td><td align=\"center\">Wood Elf</td><td align=\"center\">[Bamse] i Trollskogen (#3)</td><td align=\"right\">12,301</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">10</td><td>Kewl d00dz</td><td align=\"center\">High Elf</td><td align=\"center\">MH: We apologize for the inconvenience (#349)</td><td align=\"right\">11,925</td></tr>";
    }
    elseif($age == "11" && $type == "Strength") {
        echo "<tr heigth=\"20\"><td width=\"22\">1</td><td>[OTF] ME+OWNS = J00</td><td align=\"center\">Undead</td><td align=\"center\">Orkfians Time Forgot (#355)</td><td align=\"right\">8,191,980</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">2</td><td>[Bamse] Kubbe Varg</td><td align=\"center\">High Elf</td><td align=\"center\">[Bamse] i Trollskogen (#3)</td><td align=\"right\">7,199,837</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">3</td><td>[ITU] Idiotic Tags United</td><td align=\"center\">Nazgul</td><td align=\"center\">MH: We apologize for the inconvenience (#349)</td><td align=\"right\">6,586,619</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">4</td><td>[Bamse] Wolfram Varg</td><td align=\"center\">High Elf</td><td align=\"center\">[Bamse] i Trollskogen (#3)</td><td align=\"right\">6,368,154</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">5</td><td>[Bamse] Krösus Sork</td><td align=\"center\">High Elf</td><td align=\"center\">[Bamse] i Trollskogen (#3)</td><td align=\"right\">6,329,553</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">6</td><td>Anonymous Tribe</td><td align=\"center\">Nazgul</td><td align=\"center\">MH: We apologize for the inconvenience (#349)</td><td align=\"right\">5,903,311</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">7</td><td>[UP2U] Jenny Talls</td><td align=\"center\">Undead</td><td align=\"center\">It is [UP2-HOLLAND!!] (#274)</td><td align=\"right\">5,798,148</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">8</td><td>[OTF] Luck+P.O.S. = You</td><td align=\"center\">Undead</td><td align=\"center\">Orkfians Time Forgot (#355)</td><td align=\"right\">5,375,885</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">9</td><td>[OG] Akhôrahil</td><td align=\"center\">Nazgul</td><td align=\"center\">Bunch [O] dead [G]uys (#91)</td><td align=\"right\">5,309,225</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">10</td><td>[UP2U] Evil Walks</td><td align=\"center\">Undead</td><td align=\"center\">It is [UP2-HOLLAND!!] (#274)</td><td align=\"right\">5,060,199</td></tr>";
    }
    elseif($age == "11" && $type == "Fame") {
        echo "<tr heigth=\"20\"><td width=\"22\">1</td><td>[Bamse] Kubbe Varg</td><td align=\"center\">High Elf</td><td align=\"center\">[Bamse] i Trollskogen (#3)</td><td align=\"right\">27,416</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">2</td><td>[Bamse] Krösus Sork</td><td align=\"center\">High Elf</td><td align=\"center\">[Bamse] i Trollskogen (#3)</td><td align=\"right\">26,919</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">3</td><td>[ITU] Idiotic Tags United</td><td align=\"center\">Nazgul</td><td align=\"center\">MH: We apologize for the inconvenience (#349)</td><td align=\"right\">25,693</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">4</td><td>Where's the Bunnies?</td><td align=\"center\">Dark Elf</td><td align=\"center\">Disney:Just a little animated! (#45)</td><td align=\"right\">25,046</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">5</td><td>[JJE] Drow</td><td align=\"center\">Dark Elf</td><td align=\"center\">[JJE] Bestial Justice (#22)</td><td align=\"right\">23,681</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">6</td><td>Loui XVI</td><td align=\"center\">Wood Elf</td><td align=\"center\">We do kill ! (#375)</td><td align=\"right\">23,193</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">7</td><td>9inch</td><td align=\"center\">Spirit</td><td align=\"center\">Dark Order (#114)</td><td align=\"right\">23,188</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">8</td><td>[K&Qs] Arthas</td><td align=\"center\">Dark Elf</td><td align=\"center\">Kings & Queens... and some Jokers (#379)</td><td align=\"right\">22,510</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">9</td><td>[OTF] ME+OWNS = J00</td><td align=\"center\">Undead</td><td align=\"center\">Orkfians Time Forgot (#355)</td><td align=\"right\">21,961</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">10</td><td>All 4 FUN!! HiHaHo</td><td align=\"center\">Dark Elf</td><td align=\"center\">FUN congrats The Dutch football team!! (#295)</td><td align=\"right\">21,860</td></tr>";
    }

    // AGE 12
    elseif($age == "12" && $type == "Land") {
        echo "<tr heigth=\"20\"><td width=\"22\">1</td><td>Simply Confounding</td><td align=\"center\">Uruk Hai</td><td align=\"center\">Simply The Best (#230)</td><td align=\"right\">12,116</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">2</td><td>KeepItSimpleStupid</td><td align=\"center\">Brittonian</td><td align=\"center\"> Simply The Best (#230)</td><td align=\"right\">11,699</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">3</td><td>SimplyTakingYourLand</td><td align=\"center\">Uruk Hai</td><td align=\"center\">Simply The Best (#230)</td><td align=\"right\">11,681</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">4</td><td>Simply Stunning</td><td align=\"center\">Uruk Hai</td><td align=\"center\">Simply The Best (#230)</td><td align=\"right\">11,429</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">5</td><td>Simply Sucks</td><td align=\"center\">Uruk Hai</td><td align=\"center\">Simply The Best (#230)</td><td align=\"right\">11,008</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">6</td><td>Simply Enlightment</td><td align=\"center\">Brittonian</td><td align=\"center\"> Simply The Best (#230)</td><td align=\"right\">10,961</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">7</td><td>Simply Forgotten</td><td align=\"center\">Mori Hai</td><td align=\"center\">Simply The Best (#230)</td><td align=\"right\">10,711</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">8</td><td>Simply Invincible</td><td align=\"center\">Brittonian</td><td align=\"center\"> Simply The Best (#230)</td><td align=\"right\">10,039</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">9</td><td>Simply Comfortable</td><td align=\"center\">Brittonian</td><td align=\"center\"> Simply The Best (#230)</td><td align=\"right\">9,808</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">10</td><td>Simply Done</td><td align=\"center\">Brittonian</td><td align=\"center\"> Simply The Best (#230)</td><td align=\"right\">9,020</td></tr>";      
    }
    elseif($age == "12" && $type == "Strength") {
        echo "<tr heigth=\"20\"><td width=\"22\">1</td><td>Simply Forgotten</td><td align=\"center\">Mori Hai</td><td align=\"center\">Simply The Best (#230)</td><td align=\"right\">6,453,699</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">2</td><td>Simply Comfortable</td><td align=\"center\">Brittonian</td><td align=\"center\"> Simply The Best (#230)</td><td align=\"right\">5,274,189</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">3</td><td>Herr Igelkott|Away</td><td align=\"center\">Brittonian</td><td align=\"center\"> [BAMSE] on Vacation: Planning Revenge (#175)</td><td align=\"right\">5,182,576</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">4</td><td>Yojimbo</td><td align=\"center\">Brittonian</td><td align=\"center\"> Final Fantasy (#376)</td><td align=\"right\">4,951,771</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">5</td><td>Simply Invincible</td><td align=\"center\">Brittonian</td><td align=\"center\"> Simply The Best (#230)</td><td align=\"right\">4,734,135</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">6</td><td>Simply Le Meilleur</td><td align=\"center\">Brittonian</td><td align=\"center\"> Simply The Best (#230)</td><td align=\"right\">4,716,218</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">7</td><td>Brumma|Away</td><td align=\"center\">Mori Hai</td><td align=\"center\">[BAMSE] on Vacation: Planning Revenge (#175)</td><td align=\"right\">4,709,075</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">8</td><td>I´ll Be Back</td><td align=\"center\">Mori Hai</td><td align=\"center\">Director's Cut (#158)</td><td align=\"right\">4,687,654</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">9</td><td>Simply Silverlicious</td><td align=\"center\">Brittonian</td><td align=\"center\"> Simply The Best (#230)</td><td align=\"right\">4,541,634</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">10</td><td>Dark Anima</td><td align=\"center\">Brittonian</td><td align=\"center\"> Final Fantasy (#376)</td><td align=\"right\">4,538,603</td></tr>"; 
    }
    elseif($age == "12" && $type == "Fame") {
        echo "<tr heigth=\"20\"><td width=\"22\">1</td><td>Quaker Oats</td><td align=\"center\">Brittonian</td><td align=\"center\"> Cereal Killers (#346)</td><td align=\"right\">29,982</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">2</td><td>Ish See Through</td><td align=\"center\">Spirit</td><td align=\"center\">Mechanoids are taking over this space! (#132)</td><td align=\"right\">29,117</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">3</td><td>Simply Rocking</td><td align=\"center\">Brittonian</td><td align=\"center\"> Simply The Best (#230)</td><td align=\"right\">26,081</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">4</td><td>Yojimbo</td><td align=\"center\">Brittonian</td><td align=\"center\"> Final Fantasy (#376)</td><td align=\"right\">23,739</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">5</td><td>Dark Anima</td><td align=\"center\">Brittonian</td><td align=\"center\"> Final Fantasy (#376)</td><td align=\"right\">21,188</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">6</td><td>SimplyTakingYourLand</td><td align=\"center\">Uruk Hai</td><td align=\"center\">Simply The Best (#230)</td><td align=\"right\">20,282</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">7</td><td>Simply Forgotten</td><td align=\"center\">Mori Hai</td><td align=\"center\">Simply The Best (#230)</td><td align=\"right\">20,060</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">8</td><td>Herr Igelkott|Away</td><td align=\"center\">Brittonian</td><td align=\"center\"> [BAMSE] on Vacation: Planning Revenge (#175)</td><td align=\"right\">19,088</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">9</td><td>Shiva</td><td align=\"center\">Brittonian</td><td align=\"center\"> Final Fantasy (#376)</td><td align=\"right\">17,440</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">10</td><td>Simply Sucks</td><td align=\"center\">Uruk Hai</td><td align=\"center\">Simply The Best (#230)</td><td align=\"right\">17,364</td></tr>";
    }

    // AGE 13
    elseif($age == "13" && $type == "Land") {
        echo "<tr heigth=\"20\"><td width=\"22\">1</td><td>[BAMSE] Lived Live</td><td align=\"center\">Nazgul</td><td align=\"center\">[BAMSE] Sdrawkcab Gniworg (#6)</td><td align=\"right\">15,622</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">2</td><td>Codename 00666</td><td align=\"center\">Raven</td><td align=\"center\">Venis, Vidis, Vicis, Dimisis (#11)</td><td align=\"right\">14,974</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">3</td><td>Scare Bear</td><td align=\"center\">Nazgul</td><td align=\"center\">Care Bears *recruiting for next age* (#34)</td><td align=\"right\">13,032</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">4</td><td>[BAMSE] Etep lotsip</td><td align=\"center\">Mori Hai</td><td align=\"center\">[BAMSE] Sdrawkcab Gniworg (#6)</td><td align=\"right\">12,060</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">5</td><td>Sexual Harassment Bear</td><td align=\"center\">Nazgul</td><td align=\"center\">Care Bears *recruiting for next age* (#34)</td><td align=\"right\">11,720</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">6</td><td>Brit on the Rock's</td><td align=\"center\">Brittonian</td><td align=\"center\"> Hungry? We are! (#190)</td><td align=\"right\">11,650</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">7</td><td>Drug Addict Bear</td><td align=\"center\">Mori Hai</td><td align=\"center\">Care Bears *recruiting for next age* (#34)</td><td align=\"right\">11,639</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">8</td><td>Tattoed Bear</td><td align=\"center\">Brittonian</td><td align=\"center\"> Care Bears *recruiting for next age* (#34)</td><td align=\"right\">11,037</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">9</td><td>Drunken Slob</td><td align=\"center\">Nazgul</td><td align=\"center\">Mostly Harmless (#268)</td><td align=\"right\">10,759</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">10</td><td>Drunken psycho Bear</td><td align=\"center\">Nazgul</td><td align=\"center\">Care Bears *recruiting for next age* (#34)</td><td align=\"right\">10,700</td></tr>";  
    }
    elseif($age == "13" && $type == "Strength") {
        echo "<tr heigth=\"20\"><td width=\"22\">1</td><td>[BAMSE] Lived Live</td><td align=\"center\">Nazgul</td><td align=\"center\">[BAMSE] Sdrawkcab Gniworg (#6)</td><td align=\"right\">9,810,386</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">2</td><td>Scare Bear</td><td align=\"center\">Nazgul</td><td align=\"center\">Care Bears *recruiting for next age* (#34)</td><td align=\"right\">8,184,962</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">3</td><td>[BAMSE] Etep lotsip</td><td align=\"center\">Mori Hai</td><td align=\"center\">[BAMSE] Sdrawkcab Gniworg (#6)</td><td align=\"right\">7,254,597</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">4</td><td>Sexual Harassment Bear</td><td align=\"center\">Nazgul</td><td align=\"center\">Care Bears *recruiting for next age* (#34)</td><td align=\"right\">7,244,862</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">5</td><td>Drug Addict Bear</td><td align=\"center\">Mori Hai</td><td align=\"center\">Care Bears *recruiting for next age* (#34)</td><td align=\"right\">6,351,848</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">6</td><td>Codename 00666</td><td align=\"center\">Raven</td><td align=\"center\">Venis, Vidis, Vicis, Dimisis (#11)</td><td align=\"right\">6,273,830</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">7</td><td>Drunken psycho Bear</td><td align=\"center\">Nazgul</td><td align=\"center\">Care Bears *recruiting for next age* (#34)</td><td align=\"right\">6,098,495</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">8</td><td>Moat Cailin</td><td align=\"center\">Nazgul</td><td align=\"center\">Little KD (#242)</td><td align=\"right\">5,882,630</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">9</td><td>Loads of [FUN]</td><td align=\"center\">Mori Hai</td><td align=\"center\">[FUN] in Orkfia (#373)</td><td align=\"right\">5,590,192</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">10</td><td>Drunken Slob</td><td align=\"center\">Nazgul</td><td align=\"center\">Mostly Harmless (#268)</td><td align=\"right\">5,342,563</td></tr>";  
    }
    elseif($age == "13" && $type == "Fame") {
        echo "<tr heigth=\"20\"><td width=\"22\">1</td><td>I[MM]alheureux</td><td align=\"center\">Dark Elf</td><td align=\"center\">Who Are W[MM]E? (#344)</td><td align=\"right\">38,787</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">2</td><td>[SIN] Sloth</td><td align=\"center\">Dark Elf</td><td align=\"center\">We [SIN] so you dont have to (#56)</td><td align=\"right\">35,744</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">3</td><td>CrossbowSandwichMan</td><td align=\"center\">Brittonian</td><td align=\"center\"> Hungry? We are! (#190)</td><td align=\"right\">31,541</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">4</td><td>[BAMSE] Lived Live</td><td align=\"center\">Nazgul</td><td align=\"center\">[BAMSE] Sdrawkcab Gniworg (#6)</td><td align=\"right\">27,728</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">5</td><td>Big Pikemen XXL</td><td align=\"center\">Brittonian</td><td align=\"center\"> Hungry? We are! (#190)</td><td align=\"right\">22,870</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">6</td><td>Zana Crizata</td><td align=\"center\">Dark Elf</td><td align=\"center\">Zanele Nasoale (#370)</td><td align=\"right\">20,873</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">7</td><td>Ugly Stupid Metalhead</td><td align=\"center\">Wood Elf</td><td align=\"center\">Mostly Harmless (#268)</td><td align=\"right\">20,617</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">8</td><td>Brit Beer Babarian</td><td align=\"center\">Brittonian</td><td align=\"center\"> Hungry? We are! (#190)</td><td align=\"right\">20,579</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">9</td><td>Scare Bear</td><td align=\"center\">Nazgul</td><td align=\"center\">Care Bears *recruiting for next age* (#34)</td><td align=\"right\">20,408</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">10</td><td>Sexual Harassment Bear</td><td align=\"center\">Nazgul</td><td align=\"center\">Care Bears *recruiting for next age* (#34)</td><td align=\"right\">20,224</td></tr>"; 
    }

    // AGE 14
    elseif($age == "14" && $type == "Land") {
        echo "<tr heigth=\"20\"><td width=\"22\">1</td><td>The Pygmy Shrew</td><td align=\"center\">Mori Hai</td><td align=\"center\">GENESIS - A New Beginning (#70)</td><td align=\"right\">16,272</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">2</td><td>ThIsiSweRep00pgOes-></td><td align=\"center\">Mori Hai</td><td align=\"center\">GENESIS - A New Beginning (#70)</td><td align=\"right\">15,804</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">3</td><td>DaRkfRoStFiReGiZMo</td><td align=\"center\">Brittonian</td><td align=\"center\"> GENESIS - A New Beginning (#70)</td><td align=\"right\">13,810</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">4</td><td>Kick the lazy bum</td><td align=\"center\">Brittonian</td><td align=\"center\"> GENESIS - A New Beginning (#70)</td><td align=\"right\">13,521</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">5</td><td>Lord of the Things</td><td align=\"center\">Brittonian</td><td align=\"center\"> GENESIS - A New Beginning (#70)</td><td align=\"right\">13,014</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">6</td><td>(OTF) Padme</td><td align=\"center\">Nazgul</td><td align=\"center\">***OTF*** (#5)</td><td align=\"right\">12,777</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">7</td><td>[WAR] Lord</td><td align=\"center\">Mori Hai</td><td align=\"center\">[WAR]DEAD AND FORGOTTEN... (#33)</td><td align=\"right\">12,181</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">8</td><td>Yakuza at [WAR]</td><td align=\"center\">Wood Elf</td><td align=\"center\">[WAR]DEAD AND FORGOTTEN... (#33)</td><td align=\"right\">12,044</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">9</td><td>sPaNk MaStAh ;)</td><td align=\"center\">Wood Elf</td><td align=\"center\">GENESIS - A New Beginning (#70)</td><td align=\"right\">11,875</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">10</td><td>Pshycadelic</td><td align=\"center\">Wood Elf</td><td align=\"center\">GENESIS - A New Beginning (#70)</td><td align=\"right\">11,848</td></tr>";
    }
    elseif($age == "14" && $type == "Strength") {
        echo "<tr heigth=\"20\"><td width=\"22\">1</td><td>The Pygmy Shrew</td><td align=\"center\">Mori Hai</td><td align=\"center\">GENESIS - A New Beginning (#70)</td><td align=\"right\">12,106,548</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">2</td><td>ThIsiSweRep00pgOes-></td><td align=\"center\">Mori Hai</td><td align=\"center\">GENESIS - A New Beginning (#70)</td><td align=\"right\">10,656,268</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">3</td><td>Eat My DuSt</td><td align=\"center\">Brittonian</td><td align=\"center\"> J00 3nt4h t3h r34lm 0f d4 slack-m4st4hs! (#84)</td><td align=\"right\">7,841,439</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">4</td><td>[WAR] Lord</td><td align=\"center\">Mori Hai</td><td align=\"center\">[WAR]DEAD AND FORGOTTEN... (#33)</td><td align=\"right\">6,054,942</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">5</td><td>poobah supreme</td><td align=\"center\">Wood Elf</td><td align=\"center\">J00 3nt4h t3h r34lm 0f d4 slack-m4st4hs! (#84)</td><td align=\"right\">5,990,689</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">6</td><td>Elder Bear</td><td align=\"center\">Nazgul</td><td align=\"center\">Caring Care Bears (#15)</td><td align=\"right\">5,852,030</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">7</td><td>Kick the lazy bum</td><td align=\"center\">Brittonian</td><td align=\"center\"> GENESIS - A New Beginning (#70)</td><td align=\"right\">5,734,291</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">8</td><td>King of the Carnival Creation</td><td align=\"center\">Nazgul</td><td align=\"center\">[core] (#3)</td><td align=\"right\">5,679,154</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">9</td><td>¨Delta•Etheria</td><td align=\"center\">Brittonian</td><td align=\"center\"> K.O. (#28)</td><td align=\"right\">5,672,360</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">10</td><td>Solo</td><td align=\"center\">Mori Hai</td><td align=\"center\">Going Solo (#147)</td><td align=\"right\">5,405,584</td></tr>";
    }
    elseif($age == "14" && $type == "Fame") {
        echo "<tr heigth=\"20\"><td width=\"22\">1</td><td>Sir Lamorac</td><td align=\"center\">Brittonian</td><td align=\"center\"> The Round Table - Happy Birthday MnM (#6)</td><td align=\"right\">31,727</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">2</td><td>Love-a-lot Bear</td><td align=\"center\">Brittonian</td><td align=\"center\"> Caring Care Bears (#15)</td><td align=\"right\">27,855</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">3</td><td>[MM] Dogma</td><td align=\"center\">Dark Elf</td><td align=\"center\">Memorable Movies (#89)</td><td align=\"right\">26,140</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">4</td><td>Merlin</td><td align=\"center\">Dark Elf</td><td align=\"center\">The Round Table - Happy Birthday MnM (#6)</td><td align=\"right\">25,782</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">5</td><td>ThIsiSweRep00pgOes-></td><td align=\"center\">Mori Hai</td><td align=\"center\">GENESIS - A New Beginning (#70)</td><td align=\"right\">25,375</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">6</td><td>[Mychenae] Filoketes</td><td align=\"center\">Brittonian</td><td align=\"center\"> The Trojan war (#49)</td><td align=\"right\">25,176</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">7</td><td>[Troy] Achilles</td><td align=\"center\">Spirit</td><td align=\"center\">The Trojan war (#49)</td><td align=\"right\">24,594</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">8</td><td>The Pygmy Shrew</td><td align=\"center\">Mori Hai</td><td align=\"center\">GENESIS - A New Beginning (#70)</td><td align=\"right\">23,345</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">9</td><td>The Dark Horizon</td><td align=\"center\">Brittonian</td><td align=\"center\"> GENESIS - A New Beginning (#70)</td><td align=\"right\">22,041</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">10</td><td>Elder Bear</td><td align=\"center\">Nazgul</td><td align=\"center\">Caring Care Bears (#15)</td><td align=\"right\">21,031</td></tr>";
    }

    // AGE 15
    elseif($age == "15" && $type == "Land") {
        echo "<tr heigth=\"20\"><td width=\"22\">1</td><td>Pestilence Abuse?</td><td align=\"center\">Nazgul</td><td align=\"center\">~ 10 Unholy Tribes ~ (#90)</td><td align=\"right\">15,647</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">2</td><td>Evil Bouncers</td><td align=\"center\">Nazgul</td><td align=\"center\">~ 10 Unholy Tribes ~ (#90)</td><td align=\"right\">15,525</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">3</td><td>William Dalton</td><td align=\"center\">Wood Elf</td><td align=\"center\">Wanted: The Daltons (#59)</td><td align=\"right\">11,662</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">4</td><td>1 Spotted Dick</td><td align=\"center\">Raven</td><td align=\"center\">GENESIS (#48)</td><td align=\"right\">11,534</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">5</td><td>My Bells Got Jingled -_-</td><td align=\"center\">Dragon</td><td align=\"center\">MH: We Apologize For The Inconvenience (#42)</td><td align=\"right\">11,491</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">6</td><td>5 big slices of tofu</td><td align=\"center\">Oleg Hai</td><td align=\"center\">GENESIS (#48)</td><td align=\"right\">11,244</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">7</td><td>Isaac Newton</td><td align=\"center\">Brittonian</td><td align=\"center\"> Orkfia Research Team (#65)</td><td align=\"right\">11,191</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">8</td><td>[OIC]~Jaguar XKR</td><td align=\"center\">Dark Elf</td><td align=\"center\">[OIC] ||60 Kills|| *One 5864 acre kill* (#17)</td><td align=\"right\">10,952</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">9</td><td>William Gilbert</td><td align=\"center\">Brittonian</td><td align=\"center\"> Orkfia Research Team (#65)</td><td align=\"right\">10,761</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">10</td><td>[DDR] Vodka</td><td align=\"center\">Wood Elf</td><td align=\"center\">Dead Drunk Retards (#75)</td><td align=\"right\">10,664</td></tr>";
    }
    elseif($age == "15" && $type == "Strength") {
        echo "<tr heigth=\"20\"><td width=\"22\">1</td><td>Pestilence Abuse?</td><td align=\"center\">Nazgul</td><td align=\"center\">~ 10 Unholy Tribes ~ (#90)</td><td align=\"right\">11,338,063</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">2</td><td>Evil Bouncers</td><td align=\"center\">Nazgul</td><td align=\"center\">~ 10 Unholy Tribes ~ (#90)</td><td align=\"right\">9,635,788</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">3</td><td>Joe Dalton</td><td align=\"center\">Nazgul</td><td align=\"center\">Wanted: The Daltons (#59)</td><td align=\"right\">5,162,841</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">4</td><td>William Dalton</td><td align=\"center\">Wood Elf</td><td align=\"center\">Wanted: The Daltons (#59)</td><td align=\"right\">4,851,426</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">5</td><td>Tsunade</td><td align=\"center\">Mori Hai</td><td align=\"center\">Countdown (#102)</td><td align=\"right\">4,419,789</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">6</td><td>[OG] Pluto</td><td align=\"center\">Nazgul</td><td align=\"center\">Orkfian Gods of Destruction (#67)</td><td align=\"right\">4,365,337</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">7</td><td>[OG] Saturn</td><td align=\"center\">Wood Elf</td><td align=\"center\">Orkfian Gods of Destruction (#67)</td><td align=\"right\">3,812,595</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">8</td><td>Snails in sour cream</td><td align=\"center\">Undead</td><td align=\"center\">[ B v S ] Padds is a n00b! (#11)</td><td align=\"right\">3,803,234</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">9</td><td>Aj tink ajm dead</td><td align=\"center\">Undead</td><td align=\"center\">Wie ken cunnt, danm counts! Wie kil 7! (#20)</td><td align=\"right\">3,793,228</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">10</td><td>Evul dradle</td><td align=\"center\">Wood Elf</td><td align=\"center\">MH: We Apologize For The Inconvenience (#42)</td><td align=\"right\">3,598,905</td></tr>";
    }
    elseif($age == "15" && $type == "Fame") {
        echo "<tr heigth=\"20\"><td width=\"22\">1</td><td>Evil Bouncers</td><td align=\"center\">Nazgul</td><td align=\"center\">~ 10 Unholy Tribes ~ (#90)</td><td align=\"right\">21,850</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">2</td><td>Pestilence Abuse?</td><td align=\"center\">Nazgul</td><td align=\"center\">~ 10 Unholy Tribes ~ (#90)</td><td align=\"right\">20,529</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">3</td><td>Legions of Troy</td><td align=\"center\">Dark Elf</td><td align=\"center\">Impaired Legions (#80)</td><td align=\"right\">19,830</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">4</td><td>Psychoblaster</td><td align=\"center\">Spirit</td><td align=\"center\">Watch out: Virus-attack! (#47)</td><td align=\"right\">19,060</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">5</td><td>Flyboy</td><td align=\"center\">Spirit</td><td align=\"center\">~ 10 Unholy Tribes ~ (#90)</td><td align=\"right\">18,904</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">6</td><td>My Bells Got Jingled -_-</td><td align=\"center\">Dragon</td><td align=\"center\">MH: We Apologize For The Inconvenience (#42)</td><td align=\"right\">18,566</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">7</td><td>[OG] Pluto</td><td align=\"center\">Nazgul</td><td align=\"center\">Orkfian Gods of Destruction (#67)</td><td align=\"right\">18,009</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">8</td><td>[core] Martel</td><td align=\"center\">Dark Elf</td><td align=\"center\">A more personal [Core] (#43)</td><td align=\"right\">17,709</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">9</td><td>O S I R I S</td><td align=\"center\">Spirit</td><td align=\"center\">C E B U A N O inc. (#45)</td><td align=\"right\">17,605</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">10</td><td>W32.Bugbear.B@mm</td><td align=\"center\">Spirit</td><td align=\"center\">Watch out: Virus-attack! (#47)</td><td align=\"right\">17,156</td></tr>"; 
    }

    // AGE 16
    elseif($age == "16" && $type == "Land") {
        echo "<tr heigth=\"20\"><td width=\"22\">1</td><td>Zarniwoop</td><td align=\"center\">Eagle</td><td align=\"center\">\o/ MH: 1st anniversary \o/ (#142)</td><td align=\"right\">19,029</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">2</td><td>Gargravarr</td><td align=\"center\">Eagle</td><td align=\"center\">\o/ MH: 1st anniversary \o/ (#142)</td><td align=\"right\">16,726</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">3</td><td>Agrajag</td><td align=\"center\">Eagle</td><td align=\"center\">\o/ MH: 1st anniversary \o/ (#142)</td><td align=\"right\">16,687</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">4</td><td>Arthur Dent</td><td align=\"center\">Eagle</td><td align=\"center\">\o/ MH: 1st anniversary \o/ (#142)</td><td align=\"right\">16,666</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">5</td><td>Ravenous Bugblatter</td><td align=\"center\">Eagle</td><td align=\"center\">\o/ MH: 1st anniversary \o/ (#142)</td><td align=\"right\">15,517</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">6</td><td>The Wise Old Bird</td><td align=\"center\">Eagle</td><td align=\"center\">\o/ MH: 1st anniversary \o/ (#142)</td><td align=\"right\">14,951</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">7</td><td>Eccentrica Gallumbits</td><td align=\"center\">Eagle</td><td align=\"center\">\o/ MH: 1st anniversary \o/ (#142)</td><td align=\"right\">14,814</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">8</td><td>Wowbagger</td><td align=\"center\">Uruk Hai</td><td align=\"center\">\o/ MH: 1st anniversary \o/ (#142)</td><td align=\"right\">14,554</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">9</td><td>one lost Krikkit warship</td><td align=\"center\">Uruk Hai</td><td align=\"center\">\o/ MH: 1st anniversary \o/ (#142)</td><td align=\"right\">14,147</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">10</td><td>Wonko The Sane</td><td align=\"center\">Wood Elf</td><td align=\"center\">\o/ MH: 1st anniversary \o/ (#142)</td><td align=\"right\">13,687</td></tr>";         
    }
    elseif($age == "16" && $type == "Strength") {
        echo "<tr heigth=\"20\"><td width=\"22\">1</td><td>I Love Pwning N00bs</td><td align=\"center\">Nazgul</td><td align=\"center\">**The FUN Alliance** (#26)</td><td align=\"right\">5,384,989</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">2</td><td>[NwG]En Röd Med Bröd</td><td align=\"center\">Brittonian</td><td align=\"center\"> N W G (#115)</td><td align=\"right\">5,143,975</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">3</td><td>Zarniwoop</td><td align=\"center\">Eagle</td><td align=\"center\">\o/ MH: 1st anniversary \o/ (#142)</td><td align=\"right\">5,103,050</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">4</td><td>Invisible Man</td><td align=\"center\">Nazgul</td><td align=\"center\">Comic Heroes - Love us (#166)</td><td align=\"right\">4,919,728</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">5</td><td>Gargravarr</td><td align=\"center\">Eagle</td><td align=\"center\">\o/ MH: 1st anniversary \o/ (#142)</td><td align=\"right\">4,887,003</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">6</td><td>Wowbagger</td><td align=\"center\">Uruk Hai</td><td align=\"center\">\o/ MH: 1st anniversary \o/ (#142)</td><td align=\"right\">4,854,653</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">7</td><td>one lost Krikkit warship</td><td align=\"center\">Uruk Hai</td><td align=\"center\">\o/ MH: 1st anniversary \o/ (#142)</td><td align=\"right\">4,573,350</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">8</td><td>Ravenous Bugblatter</td><td align=\"center\">Eagle</td><td align=\"center\">\o/ MH: 1st anniversary \o/ (#142)</td><td align=\"right\">4,377,132</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">9</td><td>The Wise Old Bird</td><td align=\"center\">Eagle</td><td align=\"center\">\o/ MH: 1st anniversary \o/ (#142)</td><td align=\"right\">4,370,250</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">10</td><td>Arthur Dent</td><td align=\"center\">Eagle</td><td align=\"center\">\o/ MH: 1st anniversary \o/ (#142)</td><td align=\"right\">4,240,685 </td></tr>";
    }
    elseif($age == "16" && $type == "Fame") {
        echo "<tr heigth=\"20\"><td width=\"22\">1</td><td>I Love Me Myself & I</td><td align=\"center\">Spirit</td><td align=\"center\">**The FUN Alliance** (#26)</td><td align=\"right\">42,309</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">2</td><td>I Love Pizza</td><td align=\"center\">Spirit</td><td align=\"center\">**The FUN Alliance** (#26)</td><td align=\"right\">40,372</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">3</td><td>Origin of Glory</td><td align=\"center\">Wood Elf</td><td align=\"center\">Origin: Happy new age :) (#91)</td><td align=\"right\">32,293</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">4</td><td>[DDR] Flying Daggers</td><td align=\"center\">Dark Elf</td><td align=\"center\">-= DDR at WAR =- (#69)</td><td align=\"right\">28,120</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">5</td><td>For Freedom</td><td align=\"center\">Spirit</td><td align=\"center\">**ORKFIA REVOLUTION** (#300)</td><td align=\"right\">26,139</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">6</td><td>I Love My New Xbox</td><td align=\"center\">Spirit</td><td align=\"center\">**The FUN Alliance** (#26)</td><td align=\"right\">24,390</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">7</td><td>I love Makin Babies!</td><td align=\"center\">Spirit</td><td align=\"center\">**The FUN Alliance** (#26)</td><td align=\"right\">22,517</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">8</td><td>I Love Pwning N00bs</td><td align=\"center\">Nazgul</td><td align=\"center\">**The FUN Alliance** (#26)</td><td align=\"right\">22,432</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">9</td><td>I Love Beer</td><td align=\"center\">Spirit</td><td align=\"center\">**The FUN Alliance** (#26)</td><td align=\"right\">21,565</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">10</td><td>I Love Typing</td><td align=\"center\">Spirit</td><td align=\"center\">**The FUN Alliance** (#26)</td><td align=\"right\">21,304</td></tr>";
    }
    elseif($age == "17" && $type == "Land") {
        echo "<tr heigth=\"20\"><td width=\"22\">1</td><td>Brag about kills</td><td align=\"center\">Eagle</td><td align=\"center\">52 kills, 17 deaths... (#183)</td><td align=\"right\">18,251</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">2</td><td>Nobody</td><td align=\"center\">Dragon</td><td align=\"center\">1337 5l4ck3r5 (#74)</td><td align=\"right\">14,096</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">3</td><td>Wh0's j00r m4st4h??+</td><td align=\"center\">Nazgul</td><td align=\"center\">m4st4hs (#42)</td><td align=\"right\">13,835</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">4</td><td>M4st4h R3b3l</td><td align=\"center\">Nazgul</td><td align=\"center\">m4st4hs (#42)</td><td align=\"right\">10,847</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">5</td><td>Glenn</td><td align=\"center\">Eagle</td><td align=\"center\">I'm the Ace, your the jokers (#147)</td><td align=\"right\">10,807</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">6</td><td>Best M4st4h</td><td align=\"center\">Nazgul</td><td align=\"center\">m4st4hs (#42)</td><td align=\"right\">10,434</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">7</td><td>Dragon banks rock!</td><td align=\"center\">Viking</td><td align=\"center\">52 kills, 17 deaths... (#183)</td><td align=\"right\">10,306</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">8</td><td>Defense is for n00bs</td><td align=\"center\">Dwarf</td><td align=\"center\">52 kills, 17 deaths... (#183)</td><td align=\"right\">10,208</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">9</td><td>¥.Nebulosus.¥</td><td align=\"center\">Eagle</td><td align=\"center\">¥.Endovelicus.¥ (#39)</td><td align=\"right\">10,121</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">10</td><td>Origin of Chivas</td><td align=\"center\">Dark Elf</td><td align=\"center\">Origin of Next full age (#93)</td><td align=\"right\">10,022</td></tr>";
    }
    elseif($age == "17" && $type == "Strength") {
        echo "<tr heigth=\"20\"><td width=\"22\">1</td><td>Wh0's j00r m4st4h??+</td><td align=\"center\">Nazgul</td><td align=\"center\">m4st4hs (#42)</td><td align=\"right\">7,292,194</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">2</td><td>M4st4h R3b3l</td><td align=\"center\">Nazgul</td><td align=\"center\">m4st4hs (#42)</td><td align=\"right\">6,381,901</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">3</td><td>Best M4st4h</td><td align=\"center\">Nazgul</td><td align=\"center\">m4st4hs (#42)</td><td align=\"right\">5,382,117</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">4</td><td>Dragon banks rock!</td><td align=\"center\">Viking</td><td align=\"center\">52 kills, 17 deaths... (#183)</td><td align=\"right\">5,129,337</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">5</td><td>Brag about kills</td><td align=\"center\">Eagle</td><td align=\"center\">52 kills, 17 deaths... (#183)</td><td align=\"right\">5,057,032</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">6</td><td>Death by Chocolate</td><td align=\"center\">Nazgul</td><td align=\"center\">1337 5l4ck3r5 (#74)</td><td align=\"right\">4,407,270</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">7</td><td>Nobody</td><td align=\"center\">Dragon</td><td align=\"center\">1337 5l4ck3r5 (#74)</td><td align=\"right\">4,347,271</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">8</td><td>Sport Cigarettes</td><td align=\"center\">Nazgul</td><td align=\"center\">The Better Life (#78)</td><td align=\"right\">4,089,864</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">9</td><td>Just Send All</td><td align=\"center\">Viking</td><td align=\"center\">52 kills, 17 deaths... (#183)</td><td align=\"right\">4,045,293</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">10</td><td>ThA MoNkeY [Undead]</td><td align=\"center\">Undead</td><td align=\"center\">>> TorskMonkeys >> (#222)</td><td align=\"right\">4,026,052</td></tr>";
    }
    elseif($age == "17" && $type == "Fame") {
        echo "<tr heigth=\"20\"><td width=\"22\">1</td><td>Jack of Spades</td><td align=\"center\">Spirit</td><td align=\"center\">The Last Deal (#33)</td><td align=\"right\">51,656</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">2</td><td>Research Farms</td><td align=\"center\">Spirit</td><td align=\"center\">[FUN] Waves Goodbye To All (#168)</td><td align=\"right\">46,222</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">3</td><td>Cloak of Corruption</td><td align=\"center\">Eagle</td><td align=\"center\">[FUN] Waves Goodbye To All (#168)</td><td align=\"right\">33,105</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">4</td><td>Spunky Monkey</td><td align=\"center\">Wood Elf</td><td align=\"center\">>> TorskMonkeys >> (#222)</td><td align=\"right\">32,893</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">5</td><td>Space Monkey</td><td align=\"center\">Brittonian</td><td align=\"center\">>> TorskMonkeys >> (#222)</td><td align=\"right\">30,227</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">6</td><td>Sun Yat-sen</td><td align=\"center\">Eagle</td><td align=\"center\">Warriors till the end! (#237)</td><td align=\"right\">28,779</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">7</td><td>Queer of Diamonds</td><td align=\"center\">Spirit</td><td align=\"center\">The Last Deal (#33)</td><td align=\"right\">25,500</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">8</td><td>Tamp Emplolu</td><td align=\"center\">Dark Elf</td><td align=\"center\">[FUN] Waves Goodbye To All (#168)</td><td align=\"right\">25,155</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">9</td><td>Wh0's j00r m4st4h??+</td><td align=\"center\">Nazgul</td><td align=\"center\">m4st4hs (#42)</td><td align=\"right\">24,330</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">10</td><td>[HV]Akamaru</td><td align=\"center\">Spirit</td><td align=\"center\">Hidden Village (#145)</td><td align=\"right\">23,333</td></tr>";
    }
    elseif($age == "18" && $type == "Land") {
        echo "<tr heigth=\"20\"><td width=\"22\">1</td><td>S&M Whip</td><td align=\"center\">Brittonian</td><td align=\"center\">GNS + MH = MG (#339)</td><td align=\"right\">23,849</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">2</td><td>Good ol Thumbscrew</td><td align=\"center\">Brittonian</td><td align=\"center\">GNS + MH = MG (#339)</td><td align=\"right\">22,774</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">3</td><td>Lava Lamp of Pain</td><td align=\"center\">Brittonian</td><td align=\"center\">GNS + MH = MG (#339)</td><td align=\"right\">22,027</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">4</td><td>Vibrating Chainsaw</td><td align=\"center\">Brittonian</td><td align=\"center\">GNS + MH = MG (#339)</td><td align=\"right\">21,343</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">5</td><td>Iron Maiden, Baby</td><td align=\"center\">Brittonian</td><td align=\"center\">GNS + MH = MG (#339)</td><td align=\"right\">20,909</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">6</td><td>Mask of Infamy</td><td align=\"center\">Brittonian</td><td align=\"center\">GNS + MH = MG (#339)</td><td align=\"right\">19,900</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">7</td><td>Catherine Wheel</td><td align=\"center\">Brittonian</td><td align=\"center\">GNS + MH = MG (#339)</td><td align=\"right\">19,326</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">8</td><td>Heretic's Fork</td><td align=\"center\">Brittonian</td><td align=\"center\">GNS + MH = MG (#339)</td><td align=\"right\">19,128</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">9</td><td>Skull Crusher</td><td align=\"center\">Brittonian</td><td align=\"center\">GNS + MH = MG (#339)</td><td align=\"right\">18,420</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">10</td><td>Spiky Ejaction Seat</td><td align=\"center\">Brittonian</td><td align=\"center\">GNS + MH = MG (#339)</td><td align=\"right\">18,265</td></tr>";
    }
    elseif($age == "18" && $type == "Strength") {
        echo "<tr heigth=\"20\"><td width=\"22\">1</td><td>Ze Mighty Wheelchair</td><td align=\"center\">Mori Hai</td><td align=\"center\">GNS + MH = MG (#339)</td><td align=\"right\">14,389,465</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">2</td><td>Big bad...whirligig</td><td align=\"center\">Mori Hai</td><td align=\"center\">GNS + MH = MG (#339)</td><td align=\"right\">7,621,795</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">3</td><td>Spiked Torture Helmet</td><td align=\"center\">Undead</td><td align=\"center\">GNS + MH = MG (#339)</td><td align=\"right\">7,313,306</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">4</td><td>Spiky Ejaction Seat</td><td align=\"center\">Brittonian</td><td align=\"center\">GNS + MH = MG (#339)</td><td align=\"right\">6,335,383</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">5</td><td>Foxy Electric Chair</td><td align=\"center\">Mori Hai</td><td align=\"center\">GNS + MH = MG (#339)</td><td align=\"right\">6,286,721</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">6</td><td>« Ensam är Stark »</td><td align=\"center\">Wood Elf</td><td align=\"center\">[Core] - Justice and Fair Play (#195)</td><td align=\"right\">6,104,243</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">7</td><td>Skull Splitter</td><td align=\"center\">Dragon</td><td align=\"center\">GNS + MH = MG (#339)</td><td align=\"right\">5,928,727</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">8</td><td>Vibrating Chainsaw</td><td align=\"center\">Brittonian</td><td align=\"center\">GNS + MH = MG (#339)</td><td align=\"right\">5,837,435</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">9</td><td>Lava Lamp of Pain</td><td align=\"center\">Brittonian</td><td align=\"center\">GNS + MH = MG (#339)</td><td align=\"right\">5,748,427</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">10</td><td>Girls with Curls</td><td align=\"center\">Mori Hai</td><td align=\"center\">[WAR]Girls! (#236)</td><td align=\"right\">5,740,808</td></tr>";
    }
    elseif($age == "18" && $type == "Fame") {
        echo "<tr heigth=\"20\"><td width=\"22\">1</td><td>Ze Mighty Wheelchair</td><td align=\"center\">Mori Hai</td><td align=\"center\">Mori Hai GNS + MH = MG (#339)</td><td align=\"right\">37,413</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">2</td><td>Big bad...whirligig</td><td align=\"center\">Mori Hai</td><td align=\"center\">Mori Hai GNS + MH = MG (#339)</td><td align=\"right\">27,693</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">3</td><td>Spiked Torture Helmet</td><td align=\"center\">Undead</td><td align=\"center\">Mori Hai GNS + MH = MG (#339)</td><td align=\"right\">24,638</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">4</td><td>[SK]The Old Firm</td><td align=\"center\">Spirit</td><td align=\"center\">[SK] The Final Countdown (#173)</td><td align=\"right\">23,031</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">5</td><td>The Judas Chair</td><td align=\"center\">Undead</td><td align=\"center\">GNS + MH = MG (#339)</td><td align=\"right\">22,721</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">6</td><td>[PiG]ƒurious™Revenge</td><td align=\"center\">Dark Elf</td><td align=\"center\">Precision is Genocide (#76)</td><td align=\"right\">22,601</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">7</td><td>Skull Splitter</td><td align=\"center\">Dragon</td><td align=\"center\">GNS + MH = MG (#339)</td><td align=\"right\">21,063</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">8</td><td>Zâna Gauntlet</td><td align=\"center\">Spirit</td><td align=\"center\">Zânele Grase (#55)</td><td align=\"right\">20,988</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">9</td><td>Foxy Electric Chair</td><td align=\"center\">Mori Hai</td><td align=\"center\">GNS + MH = MG (#339)</td><td align=\"right\">19,830</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">10</td><td>miranda slabber</td><td align=\"center\">Mori Hai</td><td align=\"center\">[WAR]Girls! (#236)</td><td align=\"right\">18,449</td></tr>";
    }
    elseif($age == "19" && $type == "Land") {
        echo "<tr heigth=\"20\"><td width=\"22\">1</td><td>Spirokete</td><td align=\"center\">Oleg Hai</td><td align=\"center\">Bacteria Gone AWOL (#343)</td><td align=\"right\">17,315</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">2</td><td>Arseniq Testis</td><td align=\"center\">Uruk Hai</td><td align=\"center\">Bacteria Gone AWOL (#343)</td><td align=\"right\">16,600</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">3</td><td>Nautilia</td><td align=\"center\">Eagle</td><td align=\"center\">Bacteria Gone AWOL (#343)</td><td align=\"right\">16,160</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">4</td><td>Streptococci</td><td align=\"center\">Eagle</td><td align=\"center\">Bacteria Gone AWOL (#343)</td><td align=\"right\">16,127</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">5</td><td>Originated in Power</td><td align=\"center\">High Elf</td><td align=\"center\">Origin of Fast Wars :) (#93)</td><td align=\"right\">14,005</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">6</td><td>Guybrush Threepwood</td><td align=\"center\">Raven</td><td align=\"center\">Dj Aggrovator ft. d4 m4st4hs (#100)</td><td align=\"right\">13,332</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">7</td><td>Xanthomonas</td><td align=\"center\">Eagle</td><td align=\"center\">Bacteria Gone AWOL (#343)</td><td align=\"right\">12,402</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">8</td><td>Blastobacter</td><td align=\"center\">Dark Elf</td><td align=\"center\">Bacteria Gone AWOL (#343)</td><td align=\"right\">12,086</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">9</td><td>[Marbäck] TORSKTORSK</td><td align=\"center\">High Elf</td><td align=\"center\">B.R.O.W.N (#12)</td><td align=\"right\">11,888</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">10</td><td>Ratbat</td><td align=\"center\">Brittonian</td><td align=\"center\">[YourTheme] Is Gay (#161)</td><td align=\"right\">11,300</td></tr>";
    }
    elseif($age == "19" && $type == "Strength") {
        echo "<tr heigth=\"20\"><td width=\"22\">1</td><td>Spirokete</td><td align=\"center\">Oleg Hai</td><td align=\"center\">Bacteria Gone AWOL (#343)</td><td align=\"right\">5,148,000</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">2</td><td>Guybrush Threepwood</td><td align=\"center\">Raven</td><td align=\"center\">Dj Aggrovator ft. d4 m4st4hs (#100)</td><td align=\"right\">4,514,419</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">3</td><td>Streptococci</td><td align=\"center\">Eagle</td><td align=\"center\">Bacteria Gone AWOL (#343)</td><td align=\"right\">4,254,113</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">4</td><td>Arseniq Testis</td><td align=\"center\">Uruk Hai</td><td align=\"center\">Bacteria Gone AWOL (#343)</td><td align=\"right\">4,245,337</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">5</td><td>Eagle of Revenge</td><td align=\"center\">Eagle</td><td align=\"center\">last stance (#51)</td><td align=\"right\">4,212,457</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">6</td><td>Nautilia</td><td align=\"center\">Eagle</td><td align=\"center\">Bacteria Gone AWOL (#343)</td><td align=\"right\">4,101,549</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">7</td><td>Originated in Power</td><td align=\"center\">High Elf</td><td align=\"center\">Origin of Fast Wars :) (#93)</td><td align=\"right\">4,100,524</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">8</td><td>Grinshen</td><td align=\"center\">Viking</td><td align=\"center\">The Vigilantes (#253)</td><td align=\"right\">4,020,997</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">9</td><td>[LoC] Captain Underpants</td><td align=\"center\">Mori Hai</td><td align=\"center\">[LoC] Line of Control (#319)</td><td align=\"right\">4,019,558</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">10</td><td>Salmonella</td><td align=\"center\">Mori Hai</td><td align=\"center\">Bacteria Gone AWOL (#343)</td><td align=\"right\">3,897,140</td></tr>";
    }
    elseif($age == "19" && $type == "Fame") {
        echo "<tr heigth=\"20\"><td width=\"22\">1</td><td>Gooblas</td><td align=\"center\">Dark Elf</td><td align=\"center\">I'm Famous! (#210)</td><td align=\"right\">70,035</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">2</td><td>Taliessin the Bard</td><td align=\"center\">Spirit</td><td align=\"center\">[SK] Another Age Ends (#173)</td><td align=\"right\">28,489</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">3</td><td>Magenta Lightning</td><td align=\"center\">Brittonian</td><td align=\"center\">Aint it [FUN]? (#155)</td><td align=\"right\">26,404</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">4</td><td>Originated in Power</td><td align=\"center\">High Elf</td><td align=\"center\">Origin of Fast Wars :) (#93)</td><td align=\"right\">22,754</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">5</td><td>Flavimonas</td><td align=\"center\">Spirit</td><td align=\"center\">Bacteria Gone AWOL (#343)</td><td align=\"right\">21,608</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">6</td><td>Arseniq Testis</td><td align=\"center\">Uruk Hai</td><td align=\"center\">Bacteria Gone AWOL (#343)</td><td align=\"right\">21,534</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">7</td><td>Futsu-Nushi-no-Kami</td><td align=\"center\">Dark Elf</td><td align=\"center\">Warsaints Retired (#238)</td><td align=\"right\">21,389</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">8</td><td>Seth</td><td align=\"center\">Dark Elf</td><td align=\"center\">Warsaints Retired (#238)</td><td align=\"right\">20,810</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">9</td><td>Salmonella</td><td align=\"center\">Mori Hai</td><td align=\"center\">Bacteria Gone AWOL (#343)</td><td align=\"right\">20,768</td></tr>";
        echo "<tr heigth=\"20\"><td width=\"22\">10</td><td>Purple Rain</td><td align=\"center\">Wood Elf</td><td align=\"center\">Aint it [FUN]? (#155)</td><td align=\"right\">18,734</td></tr>";
    }
}       