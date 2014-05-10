<?php
if(!extension_loaded('msgpack'))
{
    dl('msgpack.' . PHP_SHLIB_SUFFIX);
}

//error_reporting(0);

function test($type, $variable, $object, $result = null)
{
    $serialized = msgpack_pack($variable);
    $unserialized = msgpack_unpack($serialized, $object);

    var_dump($unserialized);
    if ($result)
    {
        echo $unserialized == $result ? 'OK' : 'ERROR', PHP_EOL;
    }
    else
    {
        echo 'SKIP', PHP_EOL;
    }
}

class MyObj
{
    private $data = null;
    private $priv = "privdata";
    public  $pdata = null;
    public $subary = null;

    function __construct()
    {
        $this->data = "datadata";
        $this->subary = array(new SubObj());
    }
}

class SubObj
{
    private $subdata = null;
    private $subpriv = "subprivdata";
    public  $subpdata = null;

    function __construct()
    {
        $this->subdata = "subdatadata";
    }
}

$arySubObj = array(
  "subdata"  => "subdatadata",
  "subpriv"  => "subprivdata",
  "subpdata" => null,
);

$aryMyObj = array(
  "data"   => "datadata",
  "priv"   => "privdata",
  "pdata"  => null,
  "subary" => array($arySubObj),
);

$obj0 = new MyObj();
$obj0->pdata = "pubdata0";
$obj0->subary[0]->subpdata = "subpubdata00";
$subobj01 = new SubObj();
$subobj01->subpdata = "subpdata01";
$obj0->subary[1] = $subobj01;
$obj1 = new MyObj();
$obj1->pdata = "pubdata1";
$obj1->subary[0]->subpdata = "subpubdata1";
$subobj11 = new SubObj();
$subobj11->subpdata = "subpdata11";
$obj1->subary[1] = $subobj11;

$ary = array($obj0, $obj1);

$tpl = array($aryMyObj);
$resary = array($aryMyObj, $aryMyObj);
$resary[0]["pdata"] = "pubdata0";
$resary[0]["subary"][] = $arySubObj;
$resary[0]["subary"][0]["subpdata"] = "subpubdata00";
$resary[0]["subary"][1]["subpdata"] = "subpdata01";
$resary[1]["subary"][] = $arySubObj;
$resary[1]["pdata"] = "pubdata1";
$resary[1]["subary"][0]["subpdata"] = "subpubdata1";
$resary[1]["subary"][1]["subpdata"] = "subpdata11";

test("recursive object list to associative array list", $ary, $tpl, $resary);
