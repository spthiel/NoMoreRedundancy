<?php

echo "<style>body {white-space: pre-wrap}</style>";

include("Elspeth\Nomoreredundancy\Types\INode.php");
include("Elspeth\Nomoreredundancy\Types\IDividable.php");
include("Elspeth\Nomoreredundancy\Types\ParentNode.php");
include("Elspeth\Nomoreredundancy\Types\Divider.php");
include("Elspeth\Nomoreredundancy\Types\Palette.php");
include("Elspeth\Nomoreredundancy\TCA\BaseTCA.php");
include("Elspeth\Nomoreredundancy\TCA\FloatingHolder.php");
include("Elspeth\Nomoreredundancy\Columns\BaseColumn.php");
include("Elspeth\Nomoreredundancy\Columns\TextField.php");
include("Elspeth\Nomoreredundancy\Columns\Passthrough.php");
include("Elspeth\Nomoreredundancy\Columns\SysLanguageUid.php");
include("Elspeth\Nomoreredundancy\Columns\TransOrigPointerField.php");
include("Elspeth\Nomoreredundancy\Utils.php");

use Elspeth\Nomoreredundancy\Columns\Passthrough;
use Elspeth\Nomoreredundancy\Columns\SysLanguageUid;
use Elspeth\Nomoreredundancy\Columns\TextField;
use Elspeth\Nomoreredundancy\TCA\BaseTCA;
use Elspeth\Nomoreredundancy\Types\Divider;

$test = new BaseTCA("smrenzproduct", "box");

$test->add("General")->addPalette("test")
    ->addChild(new TextField("text field 1"))
    ->addPalette("anotherone")
    ->addChild(new TextField("textfield2"))
    ->addChild(new TextField("textfield3"))
    ->close()
    ->addChild(new Passthrough("textfield4"))->useLast()->asLabel()->typehintDivider()
    ->addChild(new SysLanguageUid(false));

$test->add("Other Divider")->addChild(new TextField("textfield5"));

print_r($test->build());


die();