<?php

function include_vote2_text()
{
    $objSrcUser = &$GLOBALS["objSrcUser"];

    // if this user voted
    if (isset($_POST['votefor']))
    {
        $iVotedFor = intval($_POST['votefor']);
        include('inc/functions/vote.php');

        // Save this persons vote
        $iAllianceId = $objSrcUser->get_stat(ALLIANCE);
        $objSrcUser->set_stat(VOTE, $iVotedFor);

        // When voter == elder, allow updating coelders
        if ($objSrcUser->get_stat(TYPE) == 'elder')
        {
            if (isset($_POST['voteforcoelder']))
            {
                $arrCoelderVotes = $_POST['voteforcoelder']; // dirty input (ES)
            }

            unset_coelders($iAllianceId);
            //fixed some possibly nasty issues if less than 2 coelders are selected - AI 17/11/06
            for($i = 0; $i < 2 && !empty($arrCoelderVotes[$i]); $i++)
            {
                $coelderId = $arrCoelderVotes[$i];
                set_coelder($iAllianceId, $coelderId);
            }
        }

        unset_external_votes($iAllianceId);
        count_votes($iAllianceId);
        header('location: main.php?cat=game&page=vote');
    }
}
?>