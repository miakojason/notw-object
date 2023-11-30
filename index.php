<?php

class Animal{
    protected $name;

    public function __construct($name)//初始值起始動作可使用，ex:incloud,pdo等等
    {
        $this->name=$name;
    }

    public function setName($name){
        $this->name=$name;
    }

    public function getName(){
        return $this->name;
    }

}

// $animal=new Animal('阿明'); //實例化 instant
/* 
echo '顯示名稱:'.$animal->getName();
echo "<br>";
$animal->setName('小花');
echo '顯示名稱:'.$animal->getName();
echo "<br>";*/
/* $animal->name='阿中';
echo '顯示名稱:'.$animal->name; */
/* echo "<br>"; */

// ---------------------------
class Dog extends Animal{

    function sit(){
        echo $this->name;
        echo "坐下";
    }
}
$dog=new Dog('阿富');
echo $dog->getName();
echo "<br>";
echo $dog->setName('阿旺');
echo $dog->getName();
$dog->sit();
echo "<br>";

echo $dog->setName('山東D路飛');
echo $dog->getName();
echo "<hr>";
// ---------------------------------
class Cat extends Dog{
    function call(){
        echo "喵";
    }
} 
$cat=new Cat('北京娜美');
    echo $cat->getName();
    echo "<br>";
    echo $cat->setName('羅冰');
    echo $cat->getName();
    echo "<br>";
    $cat->sit();
    echo "<br>";
    $cat->call();
    echo "<hr>";
    