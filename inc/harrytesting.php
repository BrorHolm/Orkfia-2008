<?php
//trying to create a new menu. See layout.php
if ($objSrcUser->get_userid() == 7160) { ?>


    <div id="harry">
        <ul>
            <li><a href="#">Depth 0</a></li>
            <li>
                <ul>
                    <li><a href="#">Depth 1</a></li>
                </ul>
            </li>
            <li>
                <ul>
                    <li>
                        <ul>
                            <li><a href="#">Depth 2</a></li>
                        </ul>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
    
    <div id="test">
        <a href="#">Depth 0</a>
    </div>
<?php
}
?>