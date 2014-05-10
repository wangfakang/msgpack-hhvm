<?php
if(!extension_loaded('msgpack'))
{
    dl('msgpack.' . PHP_SHLIB_SUFFIX);
}

//error_reporting(0);

function test($type, $variable, $object, $result = null)
{
    $msgpack = new MessagePack();
    $msgpack->setOption(MessagePack::OPT_PHPONLY, false);

    $serialized = $msgpack->pack($variable);

    $unserialized = $msgpack->unpack($serialized, $object);

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

    function __construct()
    {
        $this->data = "datadata";
    }
}

$obj = new MyObj();
$obj->pdata = "pubdata0";

$obj2 = new MyObj();
$obj2->pdata = "pubdata1";

$ary = array($obj, $obj2);

$tpl = array("MyObj");

test("object list /w string", $ary, $tpl, $ary);
