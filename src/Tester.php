<?php

$naamen = [];

class Boekje
{
    public string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }
    public function getName()
    {
        return $this->name;
    }

    public function saySomething()
    {
        echo "Blablablabal";
    }
}

class Tester
{
    protected string $naam;

    public $objecto;

    public function callsaySomething()
    {
        $this->objecto->saySomething();
    }

    public function __construct($naam, $objecto)
    {
        $this->naam = $naam;
        $this->objecto = $objecto;
    }
    public function get_naam()
    {
        echo $this->naam;
    }

    public function getoo()
    {
        $this->get_naam();
    }

    public function boekjes(string $name)
    {
        $namel = new Boekje($name);
        global $naamen;
        return $naamen[] = $namel;
    }
}

$bloek = new Boekje("Leboek");
$naam = new Tester("Terry", $bloek);
$naam->callsaySomething();
#$naamen[] = $naam;
#$naam = new Tester("Barry");
#$naamen[] = $naam;
#echo 'Ik ben ' . $naamen[0]->get_naam() . ' ha';
#echo "Ik ben ";
#$naamen[0]->get_naam();
echo "\n";
#echo 'Ik ben ' . $naam->get_naam();
#echo 'ik ben ' . $naam->getoo() . 'ho';
//$naam->boekjes("Bloek");
//echo "Ik ben " . $naamen[0]->getName();

#get object from array where name = 'x'